<?php
/**
 * XPRO_UI_Panels setup
 *
 * @since 1.0.0
 * @package Xpro UI Panels Setup
 * @class XPRO_UI_Panels
 */

if ( ! class_exists( 'XPRO_UI_Panels' ) ) {
	class XPRO_UI_Panels {

		/**
		 * Class instance.
		 *
		 * @access private
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			$this->config();
			$this->init();
		}

		/**
		 *  Function that initializes template selector data.
		 *
		 *  @since 1.0.0
		 */
		public function init() {

			add_filter( 'fl_builder_template_selector_data', array( $this, 'xpro_fl_builder_template_selector_data' ), 10, 2 );
		}

		/**
		 *  Filter Templates
		 *  Add additional information in templates array
		 *
		 *  @since 1.0.0
		 *  @param array $template_data Gets the tags for the Template Data.
		 *  @param array $template Gets the author for the Template Data.
		 */
		public function xpro_fl_builder_template_selector_data( $template_data, $template ) {
			$template_data['tags']   = isset( $template->tags ) ? $template->tags : array();
			$template_data['author'] = isset( $template->author ) ? $template->author : '';
			return $template_data;
		}

		/**
		 * Affiliate link override function
		 *
		 * @since 1.0.0
		 * @param string $key_shortcuts Returns the Key shortcut for showXPROGlobalSettings.
		 */
		public function tools_key_shortcuts( $key_shortcuts ) {

			$key_shortcuts['showXPROGlobalSettings'] = 'b';

			return $key_shortcuts;
		}

		/**
		 * Function that renders Config and templates function
		 *
		 * @since 1.0.0
		 */
		public function config() {

			$is_templates_exist = $this->is_templates_exist();
			if ( $is_templates_exist ) {
				$this->load_templates();
			}
		}

		/**
		 * Load cloud templates
		 *
		 * @since 1.0.0
		 */
		public function load_templates() {

			if ( ! method_exists( 'FLBuilder', 'register_templates' ) ) {
				return;
			}

			$templates = get_site_option( '_xpro_cloud_templats', false );

			if ( is_array( $templates ) && count( $templates ) > 0 ) {

				foreach ( $templates as $type => $type_templates ) {

					if ( 'sections' === $type ) {
						$group = 'Xpro Addons Rows';
					} else {
						$group = 'Xpro Addons Templates';
					}

					// Individual type array - [page-templates], [layout] or [row].
					if ( $type_templates ) {
						foreach ( $type_templates as $template_id => $template_data ) {

							/**
							 *  Check [status] & [dat_url_local] exist
							 */
							if (
								isset( $template_data['status'] ) && true === (bool) $template_data['status'] &&
								isset( $template_data['dat_url_local'] ) && ! empty( $template_data['dat_url_local'] )
							) {
								FLBuilder::register_templates(
									$template_data['dat_url_local'],
									array(
										'group' => $group,
									)
								);
							}
						}
					}
				}
			}
		}

		/**
		 * Function that renders Before Row Layouts
		 *
		 * @since 1.0.0
		 */
		public function xpro_panel_before_row_layouts() {
			?>
			<!-- Search Module -->
			<div id="fl-builder-blocks-rows" class="fl-builder-blocks-section">
				<input type="text" id="module_search" placeholder="<?php esc_attr_e( 'Search Module...', 'xpro-bb-addons' ); ?>" style="width: 100%;">
				<div class="filter-count"></div>
			</div><!-- Search Module -->
			<?php
		}

		/**
		 *  1. Return all templates 'excluding' Xpro templates. If variable $status is set to 'exclude'. Default: 'exclude'
		 *  2. Return ONLY Xpro templates. If variable $status is NOT set to 'exclude'.
		 *
		 * @since 1.0.0
		 * @param array $templates Gets the array of Xpro templates.
		 */
		public static function xpro_templates_data( $templates, $status = 'exclude' ) {

			if ( isset( $templates['categorized'] ) && count( $templates['categorized'] ) > 0 ) {

				foreach ( $templates['categorized'] as $ind => $cat ) {

					foreach ( $cat['templates'] as $cat_id => $cat_data ) {

						// Return all templates 'excluding' Xpro templates.
						if ( 'exclude' === $status ) {
							if ( ( isset( $cat_data['author'] ) && 'xproteam' === $cat_data['author'] )
							) {
								unset( $templates['categorized'][ $ind ]['templates'][ $cat_id ] );
							}

							// Return ONLY Xpro templates.
						} else {
							if ( ( isset( $cat_data['author'] ) && 'xproteam' !== $cat_data['author'] )
							) {
								unset( $templates['categorized'][ $ind ]['templates'][ $cat_id ] );
							}
						}
					}

					// Delete category if not templates found.
					if ( count( $templates['categorized'][ $ind ]['templates'] ) <= 0 ) {
						unset( $templates['categorized'][ $ind ] );
					}
				}
			}

			return $templates;
		}

		/**
		 *  Add Buttons to panel
		 *
		 * Row button added to the panel
		 *
		 * @since 1.0
		 * @param array $buttons Gets the buttons array for UI panel.
		 */
		public function builder_ui_bar_buttons( $buttons ) {

			if ( is_callable( 'FLBuilderUserAccess::current_user_can' ) ) {
				$simple_ui = ! FLBuilderUserAccess::current_user_can( 'unrestricted_editing' );
			} else {
				$simple_ui = ! FLBuilderModel::current_user_has_editing_capability();
			}

			$has_presets  = $this->is_templates_exist( 'presets' );
			$has_sections = $this->is_templates_exist( 'sections' );

			$buttons['add-xpro-presets'] = array(
				'label' => __( 'Presets', 'xpro-bb-addons' ),
				'show'  => ( ! $simple_ui && $has_presets ),
			);

			$buttons['add-xpro-rows'] = array(
				'label' => __( 'Sections', 'xpro-bb-addons' ),
				'show'  => ( ! $simple_ui && $has_sections ),
			);

			// Move button 'Add Content' at the start.
			$add_content = $buttons['add-content'];
			unset( $buttons['add-content'] );
			$buttons['add-content'] = $add_content;

			return $buttons;
		}

		/**
		 *  Load Rows Panel
		 *
		 * Row panel showing sections - rows & modules
		 *
		 * @since 1.0
		 */
		public function render_ui() {

			global $wp_the_query;

			if ( FLBuilderModel::is_builder_active() ) {

				if ( is_callable( 'FLBuilderUserAccess::current_user_can' ) ) {
					$has_editing_cap = FLBuilderUserAccess::current_user_can( 'unrestricted_editing' );
					$simple_ui       = ! $has_editing_cap;
				} else {
					$has_editing_cap = FLBuilderModel::current_user_has_editing_capability();
					$simple_ui       = ! $has_editing_cap;
				}

				// Panel.
				$post_id    = $wp_the_query->post->ID;
				$categories = FLBuilderModel::get_categorized_modules();

				/**
				 * Renders categorized row & module templates in the UI panel.
				 */
				$is_row_template    = FLBuilderModel::is_post_user_template( 'row' );
				$is_module_template = FLBuilderModel::is_post_user_template( 'module' );
				$row_templates      = FLBuilderModel::get_template_selector_data( 'row' );
				$module_templates   = FLBuilderModel::get_template_selector_data( 'module' );

				if ( $this->is_templates_exist( 'sections' ) ) {
					include BB_PLUGINS_ADDON_DIR . 'includes/ui-panel-sections.php';
				}

				if ( $this->is_templates_exist( 'presets' ) ) {
					include BB_PLUGINS_ADDON_DIR . 'includes/ui-panel-presets.php';
				}
			}
		}

		/**
		 *  Template status
		 *
		 *  Return the status of pages, sections, presets or all templates. Default: all
		 *
		 *  @param string $templates_type gets the templates type.
		 *  @return boolean
		 */
		public function is_templates_exist( $templates_type = 'all' ) {

			$templates = get_site_option( '_xpro_cloud_templats', false );

			$exist_templates = array(
				'page-templates' => 0,
				'sections'       => 0,
				'presets'        => 0,
			);

			if ( is_array( $templates ) && count( $templates ) > 0 ) {
				foreach ( $templates as $type => $type_templates ) {

					// Individual type array - [page-templates], [layout] or [row].
					if ( $type_templates ) {
						foreach ( $type_templates as $template_id => $template_data ) {

							if ( isset( $template_data['status'] ) && true == $template_data['status'] && isset( $template_data['dat_url_local'] ) && ! empty( $template_data['dat_url_local'] ) ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

								$exist_templates[ $type ] = ( count( ( is_array( $exist_templates[ $type ] ) || is_object( $exist_templates[ $type ] ) ) ? $exist_templates[ $type ] : array() ) + 1 );
							}
						}
					}
				}
			}

			switch ( $templates_type ) {
				case 'page-templates':
					$_templates_exist = ( $exist_templates['page-templates'] >= 1 ) ? true : false;
					break;

				case 'sections':
					$_templates_exist = ( $exist_templates['sections'] >= 1 ) ? true : false;
					break;

				case 'presets':
					$_templates_exist = ( $exist_templates['presets'] >= 1 ) ? true : false;
					break;

				case 'all':
				default:
					$_templates_exist = ( $exist_templates['page-templates'] >= 1 || $exist_templates['sections'] >= 1 || $exist_templates['presets'] >= 1 ) ? true : false;
					break;
			}

			return $_templates_exist;
		}


	}
	XPRO_UI_Panels::get_instance();
}
