<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

use Elementor\Plugin;

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_OTY_Pages_Elementor
 */
if ( ! class_exists( 'WFFN_OTY_Pages_Elementor' ) ) {
	class WFFN_OTY_Pages_Elementor {

		private static $ins = null;
		protected $template_type = [];
		protected $design_template_data = [];
		protected $templates = [];
		private $edit_id = 0;
		private $url = '';

		/**
		 * WFFN_OTY_Pages_Elementor constructor.
		 */
		public function __construct() {
			$this->url = plugin_dir_url( __FILE__ );
			add_filter( 'bwf_page_template', array( $this, 'get_page_template' ) );
			add_action( 'init', array( $this, 'setup' ) );
		}



		public function add_default_templates() {
			$templates = WooFunnels_Dashboard::get_all_templates();
			$designs   = isset( $templates['optin_ty'] ) ? $templates['optin_ty'] : [];

			$template = [
				'slug'        => 'elementor',
				'title'       => __( 'Elementor', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'post'   => $this->edit_id,
					'action' => 'elementor',
				], admin_url( 'post.php' ) ),
			];
			WFOPP_Core()->optin_ty_pages->register_template_type( $template );


			if ( isset( $designs['elementor'] ) && is_array( $designs['elementor'] ) ) {
				foreach ( $designs['elementor'] as $d_key => $templates ) {

					if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
						$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
					}
					WFOPP_Core()->optin_ty_pages->register_template( $d_key, $templates, 'elementor' );

				}
			} else {

				$empty_template = [
					"type"               => "view",
					"import"             => "no",
					"show_import_popup"  => "no",
					"slug"               => "elementor_1",
					"build_from_scratch" => true,

				];
				WFOPP_Core()->optin_ty_pages->register_template( 'elementor_1', $empty_template, 'elementor' );
			}


			return [];
		}

		/**
		 * @return WFFN_OTY_Pages_Elementor|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		/**
		 * Get page template filter callback for elementor preview mode
		 *
		 * @param string $template page template.
		 *
		 * @return string
		 */
		public function get_page_template( $template ) {
			$response = WFFN_Common::check_builder_status( 'elementor' );
			if ( is_singular() && ( true === $response['found'] ) ) {
				if ( version_compare( $response['version'], '3.2.0', '<=' ) ) {
					$el_build = Plugin::$instance->db->is_built_with_elementor( get_the_ID() );
				} else {
					$el_build = Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
				}
				if ( true === $el_build ) {
					$document = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
					if ( $document ) {
						$template = $document->get_meta( '_wp_page_template' );
					}
				}
			}
			return $template;
		}

		public function get_module_path() {
			return plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/optin-ty-pages/templates/template-default-boxed.php';
		}

		public function setup() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );
			}

		}

		public function register_conditions( $conditions_manager ) {
			require plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/optins/modules/optin-ty-pages/compatibilities/page-builders/elementor/conditions/class-wffn-oty-pages.php';
			$new_condition = new ElementorPro\Modules\ThemeBuilder\Conditions\WFFN_OTY_Pages( [
				'post_type' => WFOPP_Core()->optin_ty_pages->get_post_type_slug(),
			] );
			$conditions_manager->get_condition( 'singular' )->register_sub_condition( $new_condition );
		}

	}

	WFFN_OTY_Pages_Elementor::get_instance();
}
