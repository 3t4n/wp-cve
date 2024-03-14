<?php
/**
 * Theme locations manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Locations' ) ) {

	/**
	 * Define Soft_template_Core_Locations class
	 */
	class Soft_template_Core_Locations {

		private $_locations     = array();
		private $_pro_locations = array();

		function __construct() {

			if ( soft_template_core()->has_elementor_pro() ) {
				add_action( 'elementor/theme/register_locations', array( $this, 'register_elementor_locations' ) );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		}

		/**
		 * Register Elementor Pro locations
		 *
		 * @param  [type] $elementor_theme_manager [description]
		 * @return [type]                          [description]
		 */
		public function register_elementor_locations( $elementor_theme_manager ) {

			$prevent_locations = soft_template_core()->settings->get( 'prevent_pro_locations' );

			if ( 'true' === $prevent_locations ) {
				return;
			}

			foreach ( $this->_pro_locations as $softtemplate_location => $pro_location ) {
				$elementor_theme_manager->register_location( $pro_location );
			}
		}

		/**
		 * Register new location
		 *
		 * @param  [type] $id                 [description]
		 * @param  [type] $structure_instance [description]
		 * @return [type]                     [description]
		 */
		public function register_location( $id, $structure_instance ) {

			$this->_locations[ $id ] = $structure_instance;

			if ( $structure_instance->pro_location_mapping() ) {
				$this->_pro_locations[ $id ] = $structure_instance->pro_location_mapping();
			}

		}

		/**
		 * Return all locations data
		 *
		 * @return array
		 */
		public function get_locations() {
			return $this->_locations;
		}

		/**
		 * Get structure object for passed location name
		 *
		 * @param  [type] $location [description]
		 * @return [type]           [description]
		 */
		public function get_structure_for_location( $location ) {
			return isset( $this->_locations[ $location ] ) ? $this->_locations[ $location ] : false;
		}

		/**
		 * Enqueue locations styles
		 *
		 * @return void
		 */
		public function enqueue_styles() {

			if ( ! soft_template_core()->has_elementor() ) {
				return;
			}

			$locations = $this->get_locations();

			if ( empty( $locations ) ) {
				return;
			}

			$plugin = Elementor\Plugin::instance();

			$plugin->frontend->enqueue_styles();

			$current_post_id = get_the_ID();

			foreach ( $locations as $location => $structure ) {

				$template_id = soft_template_core()->conditions->find_matched_conditions( $structure->get_id() );

				if ( $current_post_id !== $template_id ) {
					$css_file = new Elementor\Core\Files\CSS\Post( $template_id );
					$css_file->enqueue();
				}

			}

		}

		/**
		 * Try to print location
		 *
		 * @param  string $location [description]
		 * @return [type]           [description]
		 */
		public function do_location( $location = 'header' ) {

			if ( ! soft_template_core()->has_elementor() ) {
				return false;
			}

			$structure = $this->get_structure_for_location( $location );

			if ( ! $structure ) {
				return false;
			}

			$template_id = soft_template_core()->conditions->find_matched_conditions( $structure->get_id() );

			$done_before = $this->maybe_do_pro_location( 'before', $location, $template_id );

			if ( 1 === $done_before ) {
				return true;
			}

			if ( ! $template_id ) {

				if ( -1 === $done_before ) {
					return true;
				} else {
					return false;
				}

			}

			/**
			 * Fires before Softtemplate template output started
			 */
			do_action( 'soft-template-core/location/before/' . $location );

			$this->print_location_content( $template_id, $location );

			/**
			 * Fires after Softtemplate template output ended
			 */
			do_action( 'soft-template-core/location/after/' . $location );

			$done_after = $this->maybe_do_pro_location( 'after', $location, $template_id );

			return true;
		}

		public function get_location_status( $location = 'header' ) {

			if ( ! soft_template_core()->has_elementor() ) {
				return false;
			}

			$structure = $this->get_structure_for_location( $location );

			if ( ! $structure ) {
				return false;
			}

			$template_id = soft_template_core()->conditions->find_matched_conditions( $structure->get_id() );

	
			$done_before = $this->maybe_do_pro_location( 'before', $location, $template_id );

			if ( 1 === $done_before ) {
				return true;
			}

			if ( ! $template_id ) {
			
				if ( -1 === $done_before ) {
					return true;
				} else {
					return false;
				}

			}
			return true;
		}

		/**
		 * Maybe output EPro location.
		 *
		 * @param  string $where    [description]
		 * @param  string $location [description]
		 * @return 1 - If Pro has priority and pro rendered
		 *        -1 - If:
		 *               - Shown both and Pro should rendered before Softtemplate
		 *               - Softtemplate should be shown, but no Softtemplate template defined and Pro could render own template.
		 *         0 - If:
		 *               - Pron not installed
		 *               - Softtemplate has priority and Softtemplate template found for current page
		 *               - We render pro after Softtemplate
		 *               - Softtemplate has priority, Softtemplate template not found and Pro also not renders anything
		 */
		public function maybe_do_pro_location( $where = 'before', $location = 'header', $template_id = 0 ) {

			if ( ! soft_template_core()->has_elementor_pro() ) {
				return 0;
			}

			if ( ! function_exists( 'elementor_theme_do_location' ) ) {
				return 0;
			}

			$relations = soft_template_core()->settings->get( 'pro_relations', 'show_both' );

			// If Softtemplate templates overrides Pro...
			if ( 'softtemplate_override' === $relations ) {

				// ... do nothing if has softtemplate template and render pro if not
				if ( ! $template_id ) {
					$rendered = elementor_theme_do_location( $location );
					return ( true === $rendered ? -1 : 0 );
				} else {
					return 0;
				}

			}

			// If Pro overrides Softtemplate - try show pro template and return result
			if ( 'pro_override' === $relations ) {
				$rendered = elementor_theme_do_location( $location );
				return ( true === $rendered ? 1 : 0 );
			}

			// If showed both, and Pro before - show Pro, and return -1 to define that something rendered
			if ( 'before' === $where && 'show_both_reverse' === $relations ) {
				elementor_theme_do_location( $location );
				return -1;
			}

			// If showed both, and Softtemplate before - show Pro and return false
			if ( 'after' === $where && 'show_both' === $relations ) {
				elementor_theme_do_location( $location );
				return 0;
			}

			// In all other cases return false
			return 0;

		}

		/**
		 * Output location content by template ID
		 * @param  integer $template_id [description]
		 * @param  string  $location    [description]
		 * @return [type]               [description]
		 */
		public function print_location_content( $template_id = 0, $location = 'header' ) {

			$plugin     = Elementor\Plugin::instance();
			$structure  = $this->get_structure_for_location( $location );
			$content    = $plugin->frontend->get_builder_content( $template_id, false );
			$allow_edit = array( 'header', 'footer' );

			if ( empty( $_GET['elementor-preview'] ) || ! in_array( $location, $allow_edit ) ) {
				printf("%s", $content);
			} else {

				if ( version_compare( ELEMENTOR_VERSION, '2.6.0', '<' ) ) {
					$edit_url = Elementor\Utils::get_edit_link( $template_id );
				} else {
					$edit_url = Elementor\Plugin::$instance->documents->get( $template_id )->get_edit_url();
				}

				printf(
					'<div class="softtemplate-location-edit">
						%1$s
						<a href="%2$s" target="_blank" class="softtemplate-location-edit__link elementor-clickable">
							<div class="softtemplate-location-edit__link-content">
								<span class="dashicons dashicons-edit"></span>%3$s %4$s
							</div>
						</a>
					</div>',
					$content,
					$edit_url,
					esc_html__( 'Edit', 'soft-template-core' ),
					$structure->get_single_label()
				);
			}

		}

	}

}

