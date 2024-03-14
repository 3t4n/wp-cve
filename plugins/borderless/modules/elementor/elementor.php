<?php

// don't load directly
defined( 'ABSPATH' ) || exit;

final class Borderless_Elementor {

	/**
	 * Plugin Version
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @var Borderless_Elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Borderless_Elementor An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );

		require_once('assets.php');
        borderless_elementor_assets()->init();

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 */
	public function i18n() {

		load_plugin_textdomain( 'borderless' );

	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 */
	public function on_plugins_loaded() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}

	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			//add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 */
	public function init() {
	
		$this->i18n();

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_borderless_elementor_category' ] );

	}

	/**
	 * Init Widgets
	 */
	public function init_widgets() {

		// Include Widget files
		require_once('helper.php');
		require_once('widgets/animated-text.php');
		require_once('widgets/circular-progress-bar.php');
		require_once('widgets/contact-form-7.php');
		require_once('widgets/hero.php');
		require_once('widgets/marquee-text.php');
		require_once('widgets/portfolio.php');
		require_once('widgets/progress-bar.php');
		require_once('widgets/semi-circular-progress-bar.php');
		require_once('widgets/slider.php');
		require_once('widgets/split-hero.php');
		require_once('widgets/team-member.php');
		require_once('widgets/testimonial.php');

		// Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Animated_Text() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Circular_Progress_Bar() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Contact_Form_7() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Hero() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Marquee_text() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Portfolio() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Progress_Bar() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Semi_Circular_Progress_Bar() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Slider() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Split_Hero() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Team_Member() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Borderless\Widgets\Testimonial() );

	}

    public function register_borderless_elementor_category( $elements_manager ) {
        $elements_manager->add_category(
            'borderless',
            [
                'title' => __( 'Borderless', 'borderless' ),
                'icon' => 'borderless-icon-borderless',
            ]
        );
    }
	
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'borderless' ),
			'<strong>' . esc_html__( 'Borderless', 'borderless' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'borderless' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'borderless' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'borderless' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'borderless' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'borderless' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'borderless' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'borderless' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

Borderless_Elementor::instance();
