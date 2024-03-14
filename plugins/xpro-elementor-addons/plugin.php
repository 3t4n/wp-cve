<?php

namespace XproElementorAddons;

use Elementor\Plugin;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;
use XproElementorAddons\Control\Xpro_Elementor_Image_Selector;
use XproElementorAddons\Control\Xpro_Elementor_Select;
use XproElementorAddons\Control\Xpro_Elementor_Widget_Area;
use XproElementorAddons\Inc\Xpro_Elementor_Module_List;
use XproElementorAddons\Inc\Xpro_Elementor_Widget_List;
use XproElementorAddons\Libs\Xpro_Elementor_Dashboard;

defined( 'ABSPATH' ) || die();

/**
 * Class Xpro_Elementor_Addons
 *
 * Main Plugin class
 */
class Xpro_Elementor_Addons {


	/**
	 * Instance
	 * @var Xpro_Elementor_Addons The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Elementor_Addons An instance of the class.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$this->include_files();

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		//Register Custom Post Type.
		add_action( 'init', array( $this, 'custom_item_post_type' ) );

		// Init Elementor
		add_action( 'elementor/init', array( $this, 'xpro_elementor_init' ) );

		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );

		// Register widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		//Register Modules
		add_action( 'init', array( $this, 'register_modules' ) );

		// Register editor style.
		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'editor_enqueue_styles' ) );

		// Register editor script.
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_enqueue_script' ) );

		// Register control style.
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'control_enqueue_styles' ) );

		// Register control Script.
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'control_enqueue_scripts' ), 22 );

		// Register Preview Style.
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );

		//Register Control.
		add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );

		//Custom Font Mimes
		add_filter( 'upload_mimes', array( $this, 'add_custom_font_mimes' ) );

		//Footer Text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 22 );

		// Register Document Type
		add_action( 'elementor/documents/register', array( $this, 'register_elementor_document_type' ) );

		//Plugin Row Meta.
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
		add_filter( 'plugin_action_links_' . XPRO_ELEMENTOR_ADDONS_BASE, array( $this, 'plugin_action_links' ) );

	}

	public function include_files() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/dashboard/dashboard.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/starter-sites/starter-sites.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/demo-export/demo-export.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/auto-content/auto-content.php';

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/helper-functions.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/widget-area-utils.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/widget-list.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/module-list.php';

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-xpro-navwalker.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-library-manager.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-library-source.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-ajax-handler.php';

		// WPML Compatibility
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && in_array( 'wpml-string-translation/plugin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/wpml-compatibility.php';
		}

	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {

		//Widgets CSS
		wp_enqueue_style(
			'xpro-elementor-addons-widgets',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-widgets.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'xpro-elementor-addons-responsive',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-responsive.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'font-awesome',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			null,
			'5.15.3'
		);
		wp_enqueue_style(
			'xpro-icons',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			null,
			'1.0.0'
		);

		//Widgets JS
		wp_enqueue_script(
			'xpro-elementor-addons-widgets',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'js/xpro-widgets.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		$xpro_localize = apply_filters(
			'xpro_elementor_addons_localize',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'xpro-elementor-addons-nonce' ),
			)
		);

		wp_localize_script(
			'xpro-elementor-addons-widgets',
			'XproElementorAddons',
			$xpro_localize
		);

		wp_script_add_data( 'xpro-elementor-addons-widgets', 'async', true );

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style(
				'xpro-elementor-addons-woo',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-woo-widgets.css',
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
		}

		//Vendors CSS
		wp_register_style(
			'cubeportfolio',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/cubeportfolio.min.css',
			null,
			'4.4.0'
		);
		wp_register_style(
			'owl-carousel',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/owl.carousel.min.css',
			null,
			'2.3.4'
		);
		wp_register_style(
			'lightgallery',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/lightgallery.min.css',
			null,
			'1.6.12'
		);
		wp_register_style(
			'slick',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/slick.min.css',
			null,
			'1.8.0'
		);
		wp_register_style(
			'animate',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/animate.min.css',
			null,
			'3.4.0'
		);
		wp_register_style(
			'xpro-compare',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/xpro-compare.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_register_style(
			'hover',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/hover.min.css',
			null,
			'2.3.2'
		);
		wp_register_style(
			'leaflet',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/leaflet.min.css',
			null,
			'16.0'
		);
		wp_register_style(
			'plyr',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/plyr.css',
			null,
			'3.6.12'
		);
		wp_register_style(
			'fancybox',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/fancybox.css',
			null,
			'4.0.22'
		);
		wp_register_style(
			'prism',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/prism.min.css',
			null,
			'1.16.0'
		);

		//Vendors JS
		wp_register_script(
			'cubeportfolio',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.cubeportfolio.min.js',
			array( 'jquery' ),
			'4.4.0',
			true
		);
		wp_register_script(
			'owl-carousel',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js',
			array( 'jquery' ),
			'2.3.4',
			true
		);
		wp_register_script(
			'lightgallery',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lightgallery-all.min.js',
			array( 'jquery' ),
			'1.6.12',
			true
		);
		wp_register_script(
			'gsap',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/gsap.min.js',
			array( 'jquery' ),
			'3.2.4',
			true
		);
		wp_register_script(
			'slick',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/slick.min.js',
			array( 'jquery' ),
			'1.8.0',
			true
		);
		wp_register_script(
			'morphext',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/morphext.min.js',
			array( 'jquery' ),
			'2.4.4',
			true
		);
		wp_register_script(
			'typed',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/typed.min.js',
			array( 'jquery' ),
			'2.0.12',
			true
		);
		wp_register_script(
			'anime',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/anime.min.js',
			array( 'jquery' ),
			'3.0.1',
			true
		);
		wp_register_script(
			'lax',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lax.min.js',
			array( 'jquery' ),
			'2.0.0',
			true
		);
		wp_register_script(
			'vanilla-tilt',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/vanilla-tilt.min.js',
			array( 'jquery' ),
			'1.7.0',
			true
		);
		wp_register_script(
			'lottie',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lottie.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'easypiechart',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.easypiechart.min.js',
			array( 'jquery' ),
			'2.1.7',
			true
		);
		wp_register_script(
			'sliding-menu',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/sliding-menu.min.js',
			array( 'jquery' ),
			'1.8.0',
			true
		);
		wp_register_script(
			'spritespin',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/spritespin.min.js',
			array( 'jquery' ),
			'4.0.11',
			true
		);
		wp_register_script(
			'xpro-compare',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/compare.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'sharer',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/sharer.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'countdown',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/countdown.min.js',
			array( 'jquery' ),
			'0.1.2',
			true
		);
		wp_register_script(
			'jquery-cookie',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.cookie.min.js',
			array( 'jquery' ),
			'1.4.1',
			true
		);
		wp_register_script(
			'isotope',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js',
			array( 'jquery' ),
			'3.0.6',
			true
		);
		wp_register_script(
			'drawsvg',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.drawsvg.min.js',
			array( 'jquery' ),
			'0.1.2',
			true
		);
		wp_register_script(
			'leaflet',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/leaflet.min.js',
			array( 'jquery' ),
			'1.6.0',
			true
		);
		wp_register_script(
			'recaptcha',
			'https://www.google.com/recaptcha/api.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'plyr',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/plyr.js',
			array( 'jquery' ),
			'3.6.12',
			true
		);
		wp_register_script(
			'fancybox',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/fancybox.umd.js',
			array( 'jquery' ),
			'4.0.22',
			true
		);
		wp_register_script(
			'prism',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/prism.min.js',
			array( 'jquery' ),
			'1.16.0',
			true
		);
		wp_register_script(
			'parallaxie',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/parallaxie.min.js',
			array( 'jquery' ),
			'0.5',
			true
		);
		wp_register_script(
			'particles',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/particles.min.js',
			array( 'jquery' ),
			'2.0.0',
			true
		);
		wp_register_script(
			'elevatezoom',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.elevatezoom.min.js',
			array( 'jquery' ),
			'3.0.8',
			true
		);

		wp_register_script(
			'asRange',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery-asRange.min.js',
			array( 'jquery' ),
			'0.3.4',
			true
		);

		wp_register_script(
			'jplayer',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.jplayer.min.js',
			array( 'jquery' ),
			'2.9.2',
			true
		);

		wp_register_script(
			'clipboard',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/clipboard.min.js',
			array( 'jquery' ),
			'1.5.12',
			true
		);

		wp_register_script(
			'granim',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/granim.min.js',
			array( 'jquery' ),
			'2.0.0',
			true
		);

		wp_register_script(
			'3dflipbook',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/3dflipbook.min.js',
			array( 'jquery' ),
			'2.0.0',
			true
		);

	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_preview_styles() {

		//Third Party Plugins
		if ( class_exists( '\WPForms\WPForms' ) && defined( 'WPFORMS_PLUGIN_SLUG' ) ) {
			wp_enqueue_style(
				'xpro-wpforms',
				plugins_url( '/' . WPFORMS_PLUGIN_SLUG . '/assets/css/wpforms-full.css', WPFORMS_PLUGIN_SLUG ),
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
		}

		if ( class_exists( '\GFForms' ) ) {
			wp_enqueue_style(
				'xpro-gravity-forms',
				plugins_url( '/gravityforms/css/theme.min.css', 'gravityforms' ),
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
		}
	}

	/**
	 * editor_enqueue_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_styles() {
		wp_enqueue_style(
			'xpro-elementor-addons-editor',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-editor.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'xpro-icons',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
	}

	/**
	 * editor_enqueue_script
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_script() {
		wp_enqueue_script(
			'xpro-elementor-addons-editor',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'js/xpro-editor.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'xpro-elementor-addons-editor',
			'XproEditor',
			array(
				'homeUrl'    => get_site_url(),
				'ajaxURL'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'xpro-editor-nonce' ),
				'serverBusy' => __( 'Server is busy please try again later.', 'xpro-elementor-addons' ),
			)
		);

	}

	/**
	 * control_enqueue_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function control_enqueue_styles() {
		wp_enqueue_style(
			'xpro-elementor-widgetarea-editor',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/css/widgetarea-editor.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
	}

	/**
	 * control_enqueue_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function control_enqueue_scripts() {
		wp_enqueue_script(
			'xpro-elementor-widgetarea-editor',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/js/widgetarea-editor.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'xpro-elementor-widgetarea-editor',
			'XproWidgetAreaEditorParams',
			array(
				'rest_api_url' => get_rest_url(),
			)
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets( $widgets_manager ) {

		$all_widgets    = Xpro_Elementor_Widget_List::instance()->get_list();
		$active_widgets = Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_widget_list', array_keys( $all_widgets ) );

		if ( ! empty( $active_widgets ) && is_array( $active_widgets ) ) {
			foreach ( $active_widgets as $widget_slug ) {
				if ( array_key_exists( $widget_slug, $all_widgets ) ) {
					if ( 'pro-disabled' !== $all_widgets[ $widget_slug ]['package'] && 'pro' !== $all_widgets[ $widget_slug ]['package'] ) {
						require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'widgets/' . str_replace( '_', '-', $widget_slug ) . '/' . str_replace( '_', '-', $widget_slug ) . '.php';
						$class_name = '\XproElementorAddons\Widget\\' . $this->make_classname( $widget_slug );
						if ( class_exists( $class_name ) ) {
							$widgets_manager->register( new $class_name() );
						}
					}
				}
			}
		}
	}

	/**
	 * Auto generate classname from path.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function make_classname( $dirname ) {
		$dirname    = pathinfo( $dirname, PATHINFO_FILENAME );
		$class_name = explode( '-', $dirname );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}

	/**
	 * Register Modules
	 *
	 * Register Modules Settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function register_modules() {

		$all_modules    = Xpro_Elementor_Module_List::instance()->get_list();
		$active_modules = Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_module_list', array_keys( $all_modules ) );

		if ( ! empty( $active_modules ) && is_array( $active_modules ) ) {
			foreach ( $active_modules as $module_slug ) {
				if ( array_key_exists( $module_slug, $all_modules ) ) {
					if ( 'pro-disabled' !== $all_modules[ $module_slug ]['package'] && 'pro' !== $all_modules[ $module_slug ]['package'] && 'undefined' !== $all_modules[ $module_slug ]['package'] ) {
						if ( isset( $all_modules[ $module_slug ]['dependencies'] ) && ! class_exists( $all_modules[ $module_slug ]['dependencies'] ) ) {
							continue;
						}
						include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'modules/' . str_replace( '_', '-', $module_slug ) . '/' . str_replace( '_', '-', $module_slug ) . '.php';
					}
				}
			}
		}
	}

	/**
	 * Register Control
	 *
	 * Register new Elementor control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_controls() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/image-selector.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/foreground.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/widget-area.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/select.php';

		\Elementor\Plugin::instance()->controls_manager->register( new Xpro_Elementor_Image_Selector() );
		\Elementor\Plugin::instance()->controls_manager->register( new Xpro_Elementor_Select() );
		\Elementor\Plugin::instance()->controls_manager->register( new Xpro_Elementor_Widget_Area() );
		\Elementor\Plugin::instance()->controls_manager->add_group_control( Xpro_Elementor_Group_Control_Foreground::get_type(), new Xpro_Elementor_Group_Control_Foreground() );

	}

	/**
	 * Elementor Init
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function xpro_elementor_init() {

		//Register Category
		Plugin::$instance->elements_manager->add_category(
			'xpro-widgets',
			array(
				'title' => esc_html__( 'Xpro Addons', 'xpro-elementor-addons' ),
				'icon'  => 'xi xi-xpro',
			)
		);

		Plugin::$instance->elements_manager->add_category(
			'xpro-themer',
			array(
				'title' => esc_html__( 'Xpro Theme Builder', 'xpro-elementor-addons' ),
				'icon'  => 'xi xi-xpro',
			)
		);
	}

	public function add_custom_font_mimes( $mimes = array() ) {
		$mimes['otf']   = 'application/x-font-otf';
		$mimes['woff']  = 'application/x-font-woff';
		$mimes['woff2'] = 'application/x-font-woff2';
		$mimes['ttf']   = 'application/x-font-ttf';
		$mimes['eot']   = 'application/vnd.ms-fontobject';
		return $mimes;
	}

	public function admin_footer_text( $footer_text ) {
		$current_screen      = get_current_screen();
		$is_elementor_screen = ( $current_screen && false !== strpos( $current_screen->parent_base, 'xpro-elementor-addons' ) );

		if ( $is_elementor_screen ) {
			$footer_text = sprintf(
			/* translators: 1: Elementor, 2: Link to plugin review */
				__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'xpro-elementor-addons' ),
				'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
				'<a href="https://wordpress.org/plugins/xpro-elementor-addons/#reviews" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		return $footer_text;
	}

	/**
	 * Register Document Type
	 *
	 * Register Modules Settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function register_elementor_document_type( $documents_manager ) {
		if ( get_post_type() === 'xpro_content' ) {
			update_post_meta( get_the_ID(), '_elementor_template_type', 'xpro_content' );
		}
		if ( get_post_type() === 'xpro-themer' ) {
			update_post_meta( get_the_ID(), '_elementor_template_type', 'xpro-themer' );
		}
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/inc/preview-settings.php';
		$documents_manager->register_document_type( \Xpro_Saved_Templates_Settings::get_type(), \Xpro_Saved_Templates_Settings::get_class_full_name() );
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @param array $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( XPRO_ELEMENTOR_ADDONS_BASE === $plugin_file ) {
			$row_meta    = array(
				'docs' => '<a href="https://elementor.wpxpro.com/docs/" aria-label="' . esc_attr( esc_html__( 'View Documentation', 'xpro-elementor-addons' ) ) . '" target="_blank">' . esc_html__( 'Documentation', 'xpro-elementor-addons' ) . '</a>',
			);
			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=xpro-elementor-addons' ), esc_html__( 'Settings', 'xpro-elementor-addons' ) );
		array_unshift( $links, $settings_link );

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) ) {
			$links['rate_us'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-elementor-addons-gopro">%2$s</a>', 'https://wordpress.org/plugins/xpro-elementor-addons/#reviews', esc_html__( 'Rate Us', 'xpro-elementor-addons' ) );
		} else {
			$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-elementor-addons-gopro">%2$s</a>', 'https://elementor.wpxpro.com/buy/', esc_html__( 'Go Pro', 'xpro-elementor-addons' ) );
		}

		return $links;
	}

	public function custom_item_post_type() {
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'core/handler-api.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/dynamic-content/custom-post-item.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/dynamic-content/custom-post-item-api.php';
	}
}

// Instantiate Xpro_Elementor_Addons Class.
Xpro_Elementor_Addons::instance();
