<?php
/**
 * Include files for Elementor widgets
 *
 * @package AbsoluteAddons
 */

namespace AbsoluteAddons;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use AbsoluteAddons\Controls\Group_Control_ABSP_Background;
use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Plugin as ElementorPlugin;

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	protected $settings;

	private static $is_script_debug;

	private static $scripts = array();

	private static $styles = array();

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();

			/**
			 * ABSP Loaded.
			 *
			 * Fires when AbsoluteAddons was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'absp/loaded' );

		}

		return self::$_instance;
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Preload Settings.
		$this->get_settings();

		// Add font group
		add_filter( 'elementor/fonts/groups', [ __CLASS__, 'add_font_group' ] );

		// Add additional fonts
		add_filter( 'elementor/fonts/additional_fonts', [ __CLASS__, 'add_additional_fonts' ] );

		/**
		 * Widget assets has to be registered before elementor preview calls the wp_enqueue_scripts...
		 *
		 * elementor/preview/enqueue_styles
		 * elementor/frontend/after_register_scripts
		 * elementor/frontend/after_enqueue_styles
		 *
		 * @see \Elementor\Preview::init
		 * @see \Elementor\Element_Base::get_script_depends()
		 * @see \Elementor\Element_Base::get_style_depends()
		 */

		// Register widget styles.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_styles' ], 8 );

		// Register widget scripts.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_scripts' ], 8 );

		add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'preview_style' ], ABSOLUTE_ADDONS_INT_MIN );
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'editor_scripts' ], ABSOLUTE_ADDONS_INT_MIN );
		add_action( 'elementor/editor/after_enqueue_styles', [ __CLASS__, 'preview_style' ], ABSOLUTE_ADDONS_INT_MIN );
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'preview_script' ], ABSOLUTE_ADDONS_INT_MIN );

		// Register controls & widgets.
		spl_autoload_register( [ __CLASS__, 'autoload' ] );

		// Register controls.
		add_action( 'elementor/controls/register', [ __CLASS__, 'register_controls' ] );

		// Register widgets.
		add_action( 'elementor/widgets/register', [ __CLASS__, 'register_active_widgets' ] );

		// add_filter( 'elementor/editor/localize_settings', [ $this, 'register_editor_config' ], -1 );
	}

	private static function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		if ( false !== strpos( $class_name, 'AbsolutePluginsServices' ) ) {
			return;
		}

		$helpers       = [];
		$_class_name   = str_replace( __NAMESPACE__, '', $class_name );
		$_class_name   = ltrim( $_class_name, '\\' );
		$_class_name   = strtolower( $_class_name );
		$_class_name   = str_replace( [ '_' ], '-', $_class_name );
		$_class_name   = explode( '\\', $_class_name );
		$_class_name[] = 'class-' . array_pop( $_class_name );
		$_class_name   = implode( '/', $_class_name );
		$file          = ABSOLUTE_ADDONS_PATH . 'includes/' . $_class_name . '.php';

		if ( ! file_exists( $file ) ) {
			$file = str_replace( 'class-', 'trait-', $file );
		}

		if ( ! file_exists( $file ) ) {
			if ( false !== strpos( $class_name, 'AbsoluteAddons\Controls\Fields' ) ) {
				$_class_name = str_replace( 'AbsoluteAddons\Controls\Fields\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = ABSOLUTE_ADDONS_PATH . 'controls/fields/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AbsoluteAddons\Controls' ) ) {
				$_class_name = str_replace( 'AbsoluteAddons\Controls\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = ABSOLUTE_ADDONS_PATH . 'controls/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AbsoluteAddons\Widgets' ) ) {
				$_class_name = str_replace( 'AbsoluteAddons\Widgets\Absoluteaddons_Style_', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$_class_name = str_replace( [ '_' ], '-', $_class_name );
				$file        = ABSOLUTE_ADDONS_PATH . 'widgets/' . $_class_name . '/class-absolute-addons-style-' . $_class_name . '.php';
				$helper      = ABSOLUTE_ADDONS_PATH . 'widgets/' . $_class_name . '/' . $_class_name . '.php';

				if ( file_exists( $helper ) ) {
					$helpers[] = $helper;
				}

				if ( absp_has_pro() ) {
					$helper = ABSOLUTE_ADDONS_PRO_PATH . 'widgets/' . $_class_name . '/' . $_class_name . '.php';
					if ( file_exists( $helper ) ) {
						$helpers[] = $helper;
					}
				}
				unset( $helper );
			}
		}

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r,WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			if ( defined( 'ABSP_DEV' ) && ABSP_DEV && ! file_exists( $file ) ) {
				$data = print_r(
					[
						'symbol_name' => $class_name,
						'namespace'   => __NAMESPACE__,
						'path'        => $file,
						'helpers'     => $helpers,
					],
					true
				);
				error_log( 'Failed to load file.' . PHP_EOL . $data );
			}
		}
		// phpcs:enable

		// We can read it
		if ( $file && file_exists( $file ) ) {
			// Load Helper First.
			if ( ! empty( $helpers ) ) {
				foreach ( $helpers as $helper ) {
					include_once $helper;
				}
			}

			// Load it.
			include_once $file;
		}
	}

	/**
	 * Function widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_scripts() {

		//@TODO Use appropriate version for 3rd-party assets.
		$scripts = [
			'swiper-slider'        => [
				'src'     => '/assets/dist/js/libraries/swiper-bundle',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'sweetalert2'          => [
				'src'     => '/assets/dist/js/libraries/sweetalert2.all',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'filterizr'            => [
				'src'     => '/assets/dist/js/libraries/jquery.filterizr',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'jquery.beefup'        => [
				'src'     => '/assets/dist/js/libraries/jquery.beefup',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'waypoints'            => [
				'src'     => '/assets/dist/js/libraries/jquery.waypoints',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'counterup'            => [
				'src'     => '/assets/dist/js/libraries/jquery.counterup',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'tilt.jquery'          => [
				'src'     => '/assets/dist/js/libraries/tilt.jquery',
				'deps'    => [ 'jquery', 'wp-util' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'lineProgressbar'      => [
				'src'     => '/assets/dist/js/libraries/jquery.lineProgressbar',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'progressBar'          => [
				'src'     => '/assets/dist/js/libraries/progressBar',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'jquery.fancybox'      => [
				'src'     => '/assets/dist/js/libraries/jquery.fancybox',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'jquery.countdown'     => [
				'src'     => '/assets/dist/js/libraries/jquery.countdown',
				'deps'    => [ 'jquery' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'sifter'               => [
				'src'     => '/assets/dist/js/libraries/sifter',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'jquery.isotope'       => [
				'src'     => '/assets/dist/js/libraries/isotope.pkgd',
				'deps'    => [ 'imagesloaded' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'absolute-addons-core' => [
				'src'     => '/assets/dist/js/absolute-addons-core',
				'deps'    => [ 'jquery', 'wp-util', 'swiper-slider' ],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
			'responsive-menu' => [
				'src'     => '/assets/dist/js/libraries/responsive-menu',
				'deps'    => [ 'jquery'],
				'version' => ABSOLUTE_ADDONS_VERSION,
			],
		];

		foreach ( $scripts as $name => $props ) {

			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}

		$data = apply_filters( 'absp/js/data', [
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'absp-frontend' ),
			'i18n'     => [
				'monthly'      => esc_html__( 'Monthly', 'absolute-addons' ),
				'annually'     => esc_html__( 'Annually', 'absolute-addons' ),
				'or'           => esc_html__( 'Or', 'absolute-addons' ),
				'okay'         => esc_html__( 'Okay', 'absolute-addons' ),
				'cancel'       => esc_html__( 'Cancel', 'absolute-addons' ),
				'submit'       => esc_html__( 'Submit', 'absolute-addons' ),
				'success'      => esc_html__( 'Success', 'absolute-addons' ),
				'warning'      => esc_html__( 'Warning', 'absolute-addons' ),
				'error'        => esc_html__( 'Error', 'absolute-addons' ),
				'e404'         => esc_html__( 'Requested Resource Not Found!', 'absolute-addons' ),
				'are_you_sure' => esc_html__( 'Are You Sure?', 'absolute-addons' ),
			],
		] );

		wp_localize_script( 'absolute-addons-core', 'ABSP_JS', $data );

		wp_enqueue_script( 'absolute-addons-core' );

		$widget_scripts = [
			'blog',
			'testimonial',
			'portfolio',
			'faq',
			'counter',
			'fun-fact',
			'count-down',
			'advance-tab',
			'skill-bar',
		];

		foreach ( $widget_scripts as $script ) {
			self::register_script( 'absp-' . $script, '/assets/dist/js/widgets/' . $script, [ 'absolute-addons-core' ] );
		}
	}

	/**
	 * Function widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_styles() {

		$register_styles = [
			'absolute-addons-core' => [
				'src'     => '/assets/dist/css/absolute-addons-core',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'absolute-addons-btn'  => [
				'src'     => '/assets/dist/css/absolute-addons-btn',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'absp-btn'             => [
				'src'     => '/assets/dist/css/components/btn',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'fontawesome'          => [
				'src'     => '/assets/dist/css/libraries/fontawesome',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'absolute-icons'       => [
				'src'     => '/assets/dist/css/libraries/absp-icons/css/absolute-icons',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'ico-font'             => [
				'src'     => '/assets/dist/css/icofont',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'swiper-slider'        => [
				'src'     => '/assets/dist/css/libraries/swiper-bundle',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'lineProgressbar'      => [
				'src'     => '/assets/dist/css/libraries/jquery.lineProgressbar',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'progressBar'          => [
				'src'     => '/assets/dist/css/libraries/progressBar',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'jquery.fancybox'      => [
				'src'     => '/assets/dist/css/libraries/jquery.fancybox',
				'deps'    => [],
				'version' => ABSOLUTE_ADDONS_VERSION,
				'has_rtl' => true,
			],

		];

		foreach ( $register_styles as $name => $props ) {
			self::register_style( $name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl'] );
		}

		self::enqueue_style( 'absolute-addons-core' );
		self::enqueue_style( 'absolute-addons-btn' );
		self::enqueue_style( 'absolute-icons' );

		$widget_styles = [
			'blog-post',
			'blog-page',
			'pricing-table',
			'button',
			'dual-button',
			'service',
			'list',
			'team',
			'info-box',
			'testimonial',
			'testimonial-carousel',
			'portfolio',
			'icon-box',
			'skill-bar',
			'logo-grid',
			'alert',
			'call-to-action',
			'content-card',
			'divider',
			'faq',
			'counter',
			'product-grid',
			'interactive-card',
			'restaurant-menu',
			'icon-box-carousel',
			'fun-fact',
			'business-hours',
			'list-group',
			'image-carousel',
			'count-down',
			'logo-carousel',
			'team-carousel',
			'product-carousel',
			'advance-tab',
			'multi-color-heading',
			'image-grid',
		];

		foreach ( $widget_styles as $style ) {
			self::register_style( 'absp-' . $style, '/assets/dist/css/widgets/' . $style, [], ABSOLUTE_ADDONS_VERSION, 'all', true );
		}
	}

	public static function preview_style() {
		self::enqueue_style( 'absp-preview', '/assets/dist/css/preview' );
	}

	public static function preview_script() {
		self::enqueue_script(
			'absp-preview',
			'/assets/dist/js/template-library.js',
			[
				'jquery',
//				'elementor-editor',
			],
			ABSOLUTE_ADDONS_VERSION,
			false
		);

		wp_localize_script(
			'absp-preview',
			'AbspTemplateLibraryData',
			apply_filters( 'absp/template-library/js/data', [
				'has_pro' => absp_has_pro(),
				'i18n'    => [
					'get_absp_pro'     => esc_html__( 'Get Absolute Addons Pro', 'absolute-addons' ),
					'browse_libs'      => esc_html__( 'Browse Absolute Templates', 'absolute-addons' ),
					'library_name'     => esc_html__( 'Absolute Templates', 'absolute-addons' ),
					'Blocks'           => esc_html__( 'Blocks', 'absolute-addons' ),
					'Filter'           => esc_html__( 'Filter', 'absolute-addons' ),
					'sync_error'       => esc_html__( 'Something went wrong', 'absolute-addons' ),
					'sync_again'       => esc_html__( 'Please click the sync template button above.', 'absolute-addons' ),
					'no_result'        => esc_html__( 'No Results Found', 'absolute-addons' ),
					'search_different' => esc_html__( 'Please make sure your search is spelled correctly or try a different words.', 'absolute-addons' ),
					'no_favorite'      => esc_html__( 'No Favorite Templates', 'absolute-addons' ),
					'make_favorite'    => esc_html__( 'You can mark any pre-designed template as a favorite.', 'absolute-addons' ),
					'an_err'           => esc_html__( 'An error occurred', 'absolute-addons' ),
					'unknown_err'      => esc_html__( 'Unknown Error', 'absolute-addons' ),
					/* translators: 1. Error Details. */
					'ajax_err'         => esc_html__( 'The following error(s) occurred while processing your request: %s', 'absolute-addons' ),
				],
			] )
		);
	}

	public static function editor_scripts() {

		self::enqueue_style( 'absolute-icons', 'assets/dist/css/libraries/absp-icons/css/absolute-icons.css', [], ABSOLUTE_ADDONS_VERSION, 'all', true );

		self::enqueue_style( 'absp-editor', 'assets/dist/css/editor.css', [], ABSOLUTE_ADDONS_VERSION, 'all', true );

		self::enqueue_script( 'absp-editor', 'assets/dist/js/editor.js', [ 'elementor-editor' ] );

		wp_localize_script(
			'absp-editor',
			'Absp_Editor_Config',
			[
				'has_pro'     => absp_has_pro(),
				'i18n'        => [
					'absp_pro'     => esc_html__( 'Absolute Addons Pro', 'absolute-addons' ),
					/* translators: 1. Promo Widget Name */
					'promo.header' => esc_html__( '%s Widget', 'absolute-addons' ),
					/* translators: 1. Promo Widget Name */
					'promo.body'   => esc_html__( 'Use %s widget and a lot more exciting features and widgets to make your sites faster and better.', 'absolute-addons' ),
				],
				'pro_widgets' => self::instance()->register_pro_widgets(),
				'mdv'         => apply_filters( 'absp/controller/multiple_default_value', [] ),
			]
		);
	}

	public static function add_font_group( $font_groups ) {
		$font_groups['custom_fonts'] = esc_html__( 'Custom Fonts', 'absolute-addons' );

		return $font_groups;
	}

	public static function add_additional_fonts( $additional_fonts ) {
		$theme_options = get_option( 'trix_theme_option' );
		for ( $i = 1; $i <= 50; $i ++ ) {
			if ( ! empty( $theme_options[ 'webfontName' . $i ] ) ) {
				$additional_fonts[] = $theme_options[ 'webfontName' . $i ];
			}
		}

		foreach ( $additional_fonts as $value ) {
			$additional_fonts[ $value ] = 'custom_fonts';
		}

		return $additional_fonts;
	}

	/**
	 * Register a script for use.
	 *
	 * @param string $handle Name of the script. Should be unique.
	 * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string|string[] $deps An array of registered script handles this script depends on.
	 * @param string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 *
	 * @uses   wp_register_script()
	 */
	public static function register_script( $handle, $path, $deps = [ 'jquery' ], $version = ABSOLUTE_ADDONS_VERSION, $in_footer = true ) {

		if ( false === strpos( $path, '.js' ) ) {
			$path .= '.js';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_script( $handle, $path, $deps, self::asset_version( $path, $version ), $in_footer );

		if ( $registered ) {
			self::$scripts[] = $handle;
		}
	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @param string $handle Name of the script. Should be unique.
	 * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string|string[] $deps An array of registered script handles this script depends on.
	 * @param string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 *
	 * @uses   wp_enqueue_script()
	 */
	public static function enqueue_script( $handle, $path = '', $deps = [ 'jquery' ], $version = ABSOLUTE_ADDONS_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * Register a style for use.
	 *
	 * @param string $handle Name of the stylesheet. Should be unique.
	 * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
	 * @param string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param boolean $has_rtl If has RTL version to load too.
	 *
	 * @uses   wp_register_style()
	 */
	public static function register_style( $handle, $path, $deps = [], $version = ABSOLUTE_ADDONS_VERSION, $media = 'all', $has_rtl = false ) {

		if ( false === strpos( $path, '.css' ) ) {
			$path .= '.css';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_style( $handle, $path, $deps, self::asset_version( $path, $version ), $media );

		if ( $registered ) {
			self::$styles[] = $handle;

			if ( $has_rtl ) {
				wp_style_add_data( $handle, 'rtl', 'replace' );
			}
		}

	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @param string $handle Name of the stylesheet. Should be unique.
	 * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
	 * @param string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param boolean $has_rtl If has RTL version to load too.
	 *
	 * @uses   wp_enqueue_style()
	 */
	public static function enqueue_style( $handle, $path = '', $deps = [], $version = ABSOLUTE_ADDONS_VERSION, $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, self::$styles, true ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Register Controls
	 *
	 * Include Controls Files
	 *
	 * Register new Elementor Controls.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function register_controls() {

		$controls_manager = ElementorPlugin::instance()->controls_manager;

		$controls_manager->register( new Absp_Control_Styles() );
		$controls_manager->add_group_control(
			Group_Control_ABSP_Background::get_type(),
			new Group_Control_ABSP_Background()
		);
		$controls_manager->add_group_control(
			Group_Control_ABSP_Foreground::get_type(),
			new Group_Control_ABSP_Foreground()
		);
	}

	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {

		return apply_filters( 'absp/widgets', [
			'accordion'             => [
				'label'       => __( 'Accordion', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-accordion'     => [
				'label'       => __( 'Advance Accordion', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/advance-accordion-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/advance-accordion',
				'youtube_url' => 'https://www.youtube.com/watch?v=59knMAjukUw',
			],
			'advance-google-maps'   => [
				'label'       => __( 'Advance Google Maps', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-heading'       => [
				'label'       => __( 'Advance Heading', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-tab'           => [
				'label'       => __( 'Advance Tab', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/advance-tab-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/advance-tab',
				'youtube_url' => 'https://www.youtube.com/watch?v=Uyxm0XX9Y2A',
			],
			'advance-toggle'        => [
				'label'       => __( 'Advance Toggle', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'alert'                 => [
				'label'       => __( 'Alert', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'animated-text'         => [
				'label'       => __( 'Animated Text', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'blog-post'             => [
				'label'       => __( 'Blog Post', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'blog-page'             => [
				'label'       => __( 'Blog Page', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'business-hours'        => [
				'label'       => __( 'Business Hours', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'button'                => [
				'label'       => __( 'Button', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'call-to-action'        => [
				'label'       => __( 'Call To Action', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/call-to-action-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/call-to-action',
				'youtube_url' => 'https://www.youtube.com/watch?v=fPpMO6kEWWU',
			],
			'chart'                 => [
				'label'       => __( 'Chart', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'client'                => [
				'label'       => __( 'Client', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'cf7'                   => [
				'label'       => __( 'Cf7', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'contact-box'           => [
				'label'       => __( 'Contact Box', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'content-card'          => [
				'label'       => __( 'Content Card', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/content-card-widget-for-elementor',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'counter'               => [
				'label'       => __( 'Counter', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/counter-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/counter',
				'youtube_url' => 'https://www.youtube.com/watch?v=K76eWrXz6TM',
			],
			'countdown'             => [
				'label'       => __( 'Countdown', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/counter-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/countdown',
				'youtube_url' => '',
			],
			'course'                => [
				'label'       => __( 'Course', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'custom-button'         => [
				'label'       => __( 'Custom Button', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'divider'               => [
				'label'       => __( 'Divider', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'drop-cap'              => [
				'label'       => __( 'Drop Cap', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'dual-button'           => [
				'label'       => __( 'Dual Button', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'event'                 => [
				'label'       => __( 'Event', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'fancy-text-box'        => [
				'label'       => __( 'Fancy Text Box', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'faq'                   => [
				'label'       => __( 'FAQ', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/faq-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/faq',
				'youtube_url' => 'https://www.youtube.com/watch?v=3IQTWcSCPM0',
			],
			'flip-box'              => [
				'label'       => __( 'Flip Box', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'fun-fact'              => [
				'label'       => __( 'Fun Fact', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/fun-fact-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/fun-fact',
				'youtube_url' => 'https://www.youtube.com/watch?v=nho_QQ-rXgc',
			],
			'gradient-background'   => [
				'label'       => __( 'Gradient Background', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'gradient-heading'      => [
				'label'       => __( 'Gradient Heading', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'highlight-box'         => [
				'label'       => __( 'Highlight Box', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'hotspot'               => [
				'label'       => __( 'Hotspot', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'icon-box'              => [
				'label'       => __( 'Icon Box', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/icon-box-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/icon-box',
				'youtube_url' => 'https://www.youtube.com/watch?v=dT52WL9vzn8',
			],
			'icon-box-carousel'     => [
				'label'       => __( 'Icon Box Carousel', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/icon-box-banner-carousel',
				'youtube_url' => 'https://www.youtube.com/watch?v=zI8VIdvDbfY',
			],
			'image-carousel'        => [
				'label'       => __( 'Image Carousel', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/image-carousel-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/image-carousel',
				'youtube_url' => 'https://www.youtube.com/watch?v=qNzc1Xi4vdA',
			],
			'image-compare'         => [
				'label'       => __( 'Image Compare', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'image-gallery'         => [
				'label'       => __( 'Image Gallery', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'image-grid'            => [
				'label'       => __( 'Image Grid', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'image-heading'         => [
				'label'       => __( 'Image Heading', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'info-box'              => [
				'label'       => __( 'Info Box', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/info-box-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/info-box',
				'youtube_url' => 'https://www.youtube.com/watch?v=A__LtYcBKWY',
			],
			'instagram-feed'        => [
				'label'       => __( 'Instagram Feed', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'list'                  => [
				'label'       => __( 'List', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'list-group'            => [
				'label'       => __( 'List Group', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/list-group-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/list-group',
				'youtube_url' => 'https://www.youtube.com/watch?v=Pn840eDpNC4',
			],
			'logo-carousel'         => [
				'label'       => __( 'Logo Carousel', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'logo-grid'             => [
				'label'       => __( 'Logo Grid', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/logo-grid-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/logo-grid',
				'youtube_url' => 'https://www.youtube.com/watch?v=4NeGIXfg2C0',
			],
			'modal'                 => [
				'label'       => __( 'Modal', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'multi-color-heading'   => [
				'label'       => __( 'Multi Color Heading', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/multi-color-heading',
				'youtube_url' => '',
			],
			'news-post'             => [
				'label'       => __( 'News Post', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'news-ticker'           => [
				'label'       => __( 'News Ticker', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'notification'          => [
				'label'       => __( 'Notification', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'popup-video'           => [
				'label'       => __( 'Popup Video', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'portfolio'             => [
				'label'       => __( 'Portfolio', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/portfolio-widget-for-elementor',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/portfolio',
				'youtube_url' => '',
			],
			'post-carousel'         => [
				'label'       => __( 'Post Carousel', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'pricing-table'         => [
				'label'       => __( 'Pricing Table', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'product-carousel'      => [
				'label'       => __( 'Product Carousel', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'progressbar'           => [
				'label'       => __( 'Progressbar', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'separator'             => [
				'label'       => __( 'Separator', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'service'               => [
				'label'       => __( 'Service', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'skill-bar'             => [
				'label'       => __( 'Skill Bar', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'source-code'           => [
				'label'       => __( 'Source Code', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'team'                  => [
				'label'       => __( 'Team', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'team-carousel'         => [
				'label'       => __( 'Team Carousel', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'testimonial'           => [
				'label'       => __( 'Testimonial', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => 'https://demo.absoluteplugins.com/absolute-addons/testimonial-widget-for-elementor',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'testimonial-carousel'  => [
				'label'       => __( 'Testimonial Carousel', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'timeline'              => [
				'label'       => __( 'Timeline', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'toggle-button'         => [
				'label'       => __( 'Toggle Button', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'tooltips'              => [
				'label'       => __( 'Tooltips', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'twitter-feed-carousel' => [
				'label'       => __( 'Twitter Feed Carousel', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'case-studies-slider'   => [
				'label'       => __( 'Case Studies Slider', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'case-studies'          => [
				'label'       => __( 'Case Studies', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'beforeafter'           => [
				'label'       => __( 'Before After', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'typewriter-text'       => [
				'label'       => __( 'Typewriter Text', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'product-grid'          => [
				'label'       => __( 'Product Grid', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => 'https://absoluteplugins.com/docs/docs/absolute-addons/widgets/product-grid',
				'youtube_url' => '',
			],
			'interactive-card'      => [
				'label'       => __( 'Interactive Card', 'absolute-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => false,
				'is_upcoming' => true,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'restaurant-menu'       => [
				'label'       => __( 'Restaurant Menu', 'absolute-addons' ),
				'is_pro'      => true,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
		] );
	}

	/**
	 * Get All Settings.
	 * @return array
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->settings = get_option( 'absolute_addons_settings', [] );
		}

		return null === $this->settings ? [] : $this->settings;
	}

	/**
	 * Get widget settings.
	 *
	 * @return array
	 */
	public function get_widgets_settings() {
		return isset( $this->settings['widgets'] ) && is_array( $this->settings['widgets'] ) ? $this->settings['widgets'] : [];
	}

	/**
	 * Autoload active widgets.
	 *
	 * @return void
	 */
	public static function register_active_widgets() {
		$widgets_option = self::$_instance->get_widgets_settings();
		foreach ( self::get_widgets() as $slug => $data ) {

			// If upcoming or pro don't register.
			// Pro widgets will get registered by Pro Version.
			if ( $data['is_upcoming'] || $data['is_pro'] ) {
				continue;
			}

			if ( isset( $widgets_option[ $slug ] ) ) {
				$is_active = 'on' === $widgets_option[ $slug ];
			} else {
				$is_active = $data['is_active'];
			}

			if ( $is_active ) {

				$class = explode( '-', $slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = "AbsoluteAddons\\Widgets\\Absoluteaddons_Style_" . $class;
				ElementorPlugin::instance()->widgets_manager->register( new $class() );
			}
		}
	}

	private function register_pro_widgets() {
		if ( absp_has_pro() ) {
			return [];
		}

		return [
			[
				'name'  => 'absolute-animated-text',
				'title' => __( 'Animated Text', 'absolute-addons' ),
				'icon'  => 'absp eicon-animation-text',
			],
			[
				'name'  => 'absolute-news-post',
				'title' => __( 'News Post', 'absolute-addons' ),
				'icon'  => 'absp eicon-archive-posts',
			],
			[
				'name'  => 'absolute-business-hours',
				'title' => __( 'Business Hours', 'absolute-addons' ),
				'icon'  => 'absp eicon-clock-o',
			],
			[
				'name'  => 'absolute-drop-cap',
				'title' => __( 'Drop Cap', 'absolute-addons' ),
				'icon'  => 'absp eicon-t-letter',
			],
			[
				'name'  => 'absolute-news-ticker',
				'title' => __( 'News Ticker', 'absolute-addons' ),
				'icon'  => 'absp eicon-posts-ticker',
			],
			[
				'name'  => 'absolute-tooltips',
				'title' => __( 'Tooltips', 'absolute-addons' ),
				'icon'  => 'absp eicon-commenting-o',
			],
			[
				'name'  => 'absolute-chart',
				'title' => __( 'Chart', 'absolute-addons' ),
				'icon'  => 'absp eicon-paint-brush',
			],
			[
				'name'  => 'absolute-instagram-feed',
				'title' => __( 'Instagram Feed', 'absolute-addons' ),
				'icon'  => 'absp eicon-instagram-gallery',
			],
			[
				'name'  => 'absolute-logo-carousel',
				'title' => __( 'Logo Carousel', 'absolute-addons' ),
				'icon'  => 'absp eicon-carousel',
			],
			[
				'name'  => 'absolute-twitter-feed-carousel',
				'title' => __( 'Twitter Feed Carousel', 'absolute-addons' ),
				'icon'  => 'absp eicon-twitter-feed',
			],
			[
				'name'  => 'absolute-testimonial-carousel',
				'title' => __( 'Testimonial Carousel', 'absolute-addons' ),
				'icon'  => 'absp eicon-testimonial-carousel',
			],
			[
				'name'  => 'absolute-post-carousel',
				'title' => __( 'Post Carousel', 'absolute-addons' ),
				'icon'  => 'absp eicon-posts-carousel',
			],
			[
				'name'  => 'absolute-product-carousel',
				'title' => __( 'Product Carousel', 'absolute-addons' ),
				'icon'  => 'absp eicon-product-images',
			],
			[
				'name'  => 'absolute-gradient-heading',
				'title' => __( 'Gradient Heading', 'absolute-addons' ),
				'icon'  => 'absp eicon-heading',
			],
			[
				'name'  => 'absolute-source-code',
				'title' => __( 'Source Code', 'absolute-addons' ),
				'icon'  => 'absp eicon-code',
			],
			[
				'name'  => 'absolute-gradient-background',
				'title' => __( 'Gradient Background', 'absolute-addons' ),
				'icon'  => 'absp eicon-colors-typography',
			],
			[
				'name'  => 'absolute-restaurant-menu',
				'title' => __( 'Restaurant Menu', 'absolute-addons' ),
				'icon'  => 'absp eicon-sitemap',
			],
		];
	}

	/**
	 * Get Asset string for static assets
	 *
	 * @param string $file file path.
	 * @param string $version original version.
	 *
	 * @return string
	 */
	public static function asset_version( $file, $version = ABSOLUTE_ADDONS_VERSION ) {
		if ( self::is_debug() ) {
			if ( false === strpos( $file, ABSOLUTE_ADDONS_PATH ) ) {
				$file = self::plugin_file( $file );
			}

			return file_exists( $file ) ? (string) filemtime( $file ) : time();
		}

		return $version;
	}

	/**
	 * Get full path for file relative to plugin directory.
	 *
	 * @param string $path File or path to resolve.
	 *
	 * @return string
	 */
	public static function plugin_file( $path ) {
		$path = ltrim( $path, '/\\' );
		$path = untrailingslashit( $path );

		return ABSOLUTE_ADDONS_PATH . $path;
	}

	/**
	 * Get full url for file relative to this plugin directory.
	 *
	 * @param string $path Name of the file or directory to get the url for.
	 *
	 * @return string
	 */
	public static function plugin_url( $path ) {
		$path = ltrim( $path, '/\\' );
		$path = untrailingslashit( $path );

		return plugins_url( $path, ABSOLUTE_ADDONS_FILE );
	}

	/**
	 * Check if WP Debug is enabled.
	 *
	 * @return bool
	 */
	public static function is_debug() {
		return apply_filters( 'absp_debug_mode', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) );
	}

	public static function is_dev() {
		return ( defined( 'WP_DEBUG' ) && WP_DEBUG ) && defined( 'ABSP_DEV' ) && ABSP_DEV;
	}

	public static function is_template_debug() {
		return self::is_dev() && defined( 'ABSP_TEMPLATE_DEBUG' ) && ABSP_TEMPLATE_DEBUG;
	}

	/**
	 * Check if WP Script Debug is enabled.
	 *
	 * @return bool
	 */
	public static function is_script_debug() {
		if ( null === self::$is_script_debug ) {
			self::$is_script_debug = apply_filters( 'absp_script_debug_mode', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
		}

		return self::$is_script_debug;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'absolute-addons' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'absolute-addons' ), '1.0.0' );
	}
}

// Instantiate Plugin Class.
Plugin::instance();
// End of file class-plugin.php
