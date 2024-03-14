<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Assets' ) ) {

	/**
	 * Define Soft_template_Core_Assets class
	 */
	class Soft_template_Core_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 0 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'elementor/editor/footer', array( $this, 'print_templates' ) );
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'elementor/frontend/after_register_styles', array( $this, 'load_frontend_assets' ), 10 );
			add_action('elementor/frontend/after_register_scripts', array($this, 'register_frontend_scripts'), 10);

			//add_action( 'elementor/frontend/after_enqueue_scripts', array( 'WC_Frontend_Scripts', 'localize_printed_scripts' ), 5 );

			add_action( 'admin_enqueue_scripts', array( $this, 'template_type_form_assets' ) );
		}

		public function suffix() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {
			$avaliable_widgets = soft_template_core()->settings->get( 'softemplate_available_widgets' );
			
			foreach ( glob( soft_template_core()->plugin_path( 'includes/widgets/' ) . '*.php' ) as $file ) {
				
				$slug    = basename( $file, '.php' );
				$enabled = isset( $avaliable_widgets[ $slug ] ) ? $avaliable_widgets[ $slug ] : '';
				
				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {

					$base  = basename( str_replace( '.php', '', $file ) );
					$widget_css_assets = 'assets/css/' . $base . $this->suffix() . '.css';

					$assets_paths = soft_template_core()->plugin_path($widget_css_assets);

					if (file_exists( $assets_paths )) {
						wp_enqueue_style(
							$base,
							soft_template_core()->plugin_url( $widget_css_assets ),
							false, soft_template_core()->get_version()
						);  
					}
				}
			}
			
			// Theme common assets
			wp_enqueue_script('soft-template-main', soft_template_core()->plugin_url( 'assets/js/soft-template-main.js' ), array( 'jquery' ), false, true );
			wp_enqueue_style('soft-template-main', soft_template_core()->plugin_url( 'assets/css/soft-template-main.css' ), false, soft_template_core()->get_version() ); 	
		}

		public function enqueue_scripts() {

		}		
		
		public function register_frontend_scripts() {
			// Nav Menu
			wp_register_script('soft-template-menu', soft_template_core()->plugin_url( 'assets/js/soft-template-menu.js' ), array( 'jquery' ), false, true );		
			
			wp_register_script('soft-element-resize', soft_template_core()->plugin_url( 'assets/js/jquery_resize.min.js' ), array( 'jquery' ), false, true );		
			
			wp_register_script('soft-element-cookie', soft_template_core()->plugin_url( 'assets/js/js_cookie.min.js' ), array( 'jquery' ), false, true );

			// offcanvas
			wp_register_script('soft-template-offcanvas', soft_template_core()->plugin_url( 'assets/js/soft-template-offcanvas.js' ), array( 'jquery' ), false, true );		
			
			// offcanvas
			wp_register_script('magnific-popup', soft_template_core()->plugin_url( 'assets/js/jquery.magnific-popup.min.js' ), array( 'jquery' ), false, true );

			wp_register_script('soft-template-search', soft_template_core()->plugin_url( 'assets/js/soft-template-search.js' ), array( 'jquery' ), false, true );

			wp_register_script('soft-template-social-share', soft_template_core()->plugin_url( 'assets/js/soft-template-social-share.js' ), array( 'jquery' ), false, true );

			// isotope
			wp_register_script('isotope', soft_template_core()->plugin_url( 'assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), false, true );
			wp_register_script('packery-mode', soft_template_core()->plugin_url( 'assets/js/packery-mode.pkgd.min.js' ), array( 'jquery' ), false, true );
			wp_register_script('soft-template-post-archive', soft_template_core()->plugin_url( 'assets/js/soft-template-post-archive.js' ), array( 'jquery' ), false, true );

			// Cart
			wp_register_script('soft-template-mini-cart', soft_template_core()->plugin_url( 'assets/js/soft-template-mini-cart.js' ), array( 'jquery' ), false, true );
		}		
		
		public function load_frontend_assets() {
			wp_register_style( 'magnific-popup', soft_template_core()->plugin_url( '/assets/css/magnific-popup.css', array(), '1.1.0' ) );
		}		
		
		public function localize_printed_scripts() {
			
		}

		/**
		 * Template type popup assets
		 *
		 * @return void
		 */
		public function template_type_form_assets() {

			$screen = get_current_screen();

			if ( $screen->id !== 'edit-' . soft_template_core()->templates->slug() ) {
				return;
			}

			wp_enqueue_script(
				'softtemplate-templates-type-form',
				soft_template_core()->plugin_url( 'assets/js/templates-type' . $this->suffix() . '.js' ),
				array( 'jquery' ),
				soft_template_core()->get_version(),
				true
			);

			wp_enqueue_style(
				'softtemplate-templates-type-form',
				soft_template_core()->plugin_url( 'assets/css/templates-type.css' ),
				array(),
				soft_template_core()->get_version()
			);

			add_action( 'admin_footer', array( $this, 'print_template_types_popup' ), 999 );

		}

		/**
		 * Print template type form HTML
		 *
		 * @return void
		 */
		public function print_template_types_popup() {

			$template_types = soft_template_core()->templates_manager->get_library_types();
			$default_option = array(
				'' => esc_html__( 'Select...', $domain = 'default' )
			);

			$template_types = $default_option + $template_types;
			$selected       = isset( $_GET[ soft_template_core()->templates->type_tax ] ) ? sanitize_key( $_GET[ soft_template_core()->templates->type_tax ] ) : '';

			$action = add_query_arg(
				array(
					'action' => 'softtemplate_create_new_template',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			include soft_template_core()->get_template( 'template-types-popup.php' );
		}

		/**
		 * Load preview assets
		 *
		 * @return void
		 */
		public function preview_styles() {

			wp_enqueue_style(
				'soft-template-core-preview',
				soft_template_core()->plugin_url( 'assets/css/preview.css' ),
				array(),
				soft_template_core()->get_version()
			);

		}

		/**
		 * Enqueue elemnetor editor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			wp_enqueue_script(
				'soft-template-core-editor',
				soft_template_core()->plugin_url( 'assets/js/editor' . $this->suffix() . '.js' ),
				array( 'jquery', 'underscore', 'backbone-marionette' ),
				soft_template_core()->get_version(),
				true
			);

			$icon   = $this->get_library_icon();
			$button = soft_template_core()->config->get( 'library_button' );

			wp_localize_script( 'soft-template-core-editor', 'SofttemplateThemeCoreData', apply_filters(
				'soft-template-core/assets/editor/localize',
				array(
					'libraryButton' => ( false !== $button ) ? $icon . $button : false,
					'modalRegions'  => $this->get_modal_regions(),
					'license'       => array(
						'activated' => true,
						'link'      => '',
					),
				)
			) );

		}

		/**
		 * Returns modal regions
		 * @return [type] [description]
		 */
		public function get_modal_regions() {

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.1.0-beta1', '>=' ) ) {
				return array(
					'modalHeader'  => '.dialog-header',
					'modalContent' => '.dialog-message',
				);
			} else {
				return array(
					'modalContent' => '.dialog-message',
					'modalHeader'  => '.dialog-widget-header',
				);
			}

		}

		/**
		 * Get library icon markup
		 *
		 * @return string
		 */
		public function get_library_icon() {

			ob_start();
			include soft_template_core()->plugin_path( 'assets/img/library-icon.svg' );
			$icon = ob_get_clean();

			return apply_filters( 'soft-template-core/library-button/icon', $icon );
		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'soft-template-core-editor',
				soft_template_core()->plugin_url( 'assets/css/editor.css' ),
				array(),
				soft_template_core()->get_version()
			);

		}

		/**
		 * Prints editor templates
		 *
		 * @return void
		 */
		public function print_templates() {

			foreach ( glob( soft_template_core()->plugin_path( 'templates/editor/*.php' ) ) as $file ) {
				$name = basename( $file, '.php' );
				ob_start();
				include $file;
				echo wp_get_inline_script_tag(
					ob_get_clean(),
					array(
						'type' => 'text/html',
						'id' => 'tmpl-softtemplate-' . $name
					)
				);
			}

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
