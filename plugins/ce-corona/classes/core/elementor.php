<?php
namespace CoderExpert\Corona;

/**
 * Class Plugin
 */
class Elementor {
	/**
	 * Instance
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 * Load required plugin core files.
	 */
	public function widget_scripts() {
		$suffix = ! ( defined('CORONA_DEV') && CORONA_DEV ) ? '.min' : '';

		wp_register_style( 'ce-elementor-corona', 
			CE_CORONA_ASSETS . 'css/corona.css', [ ], 
			\filemtime( CE_CORONA_PATH . 'assets/css/corona.css' ), 'all' 
		);
		wp_register_style( 'ce-elementor-country-corona', 
			CE_CORONA_ASSETS . 'css/corona-countrywise.css', [ ], 
			\filemtime( CE_CORONA_PATH . 'assets/css/corona-countrywise.css' ), 'all' 
		);
		wp_register_script( 'ce-elementor-corona-nformat', 
			CE_CORONA_ASSETS . 'js/ce-numberformat.js', 
			[ 'jquery' ], 
			\filemtime( CE_CORONA_PATH . 'assets/js/ce-numberformat.js' ), true 
		);
		wp_register_script( 'ce-elementor-country-corona', 
			CE_CORONA_ASSETS . 'js/countrywise'. $suffix .'.js', 
			[ 'jquery', 'wp-i18n', 'ce-elementor-corona-nformat' ], 
			\filemtime( CE_CORONA_PATH . 'assets/js/countrywise'. $suffix .'.js' ), true 
		);
		wp_register_script( 'ce-elementor-corona', 
			CE_CORONA_ASSETS . 'js/corona'. $suffix .'.js', 
			[ 'jquery', 'wp-i18n', 'wp-components' ], 
			\filemtime( CE_CORONA_PATH . 'assets/js/corona'. $suffix .'.js' ), true 
		);

		wp_localize_script( 'ce-elementor-corona', 'CeCorona', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'coronaBG'        => CE_CORONA_ASSETS . 'images/corona-bg.jpg',
		) );

		wp_localize_script( 'ce-elementor-country-corona', 'CeCorona', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'coronaBG'        => CE_CORONA_ASSETS . 'images/corona-bg.jpg',
		) );

		wp_register_script( 'cec-graph', 
			CE_CORONA_ASSETS . 'js/cegraph'. $suffix .'.js', 
			[ 'jquery', 'ce-elementor-corona-nformat' ], 
			\filemtime( CE_CORONA_PATH . 'assets/js/cegraph'. $suffix .'.js' ), true 
		);
		Shortcode::coronaTableTranslate( 'cec-graph' );
		wp_localize_script( 'cec-graph', 'CeCorona', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'coronaBG'        => CE_CORONA_ASSETS . 'images/corona-bg.jpg',
		) );

		Shortcode::coronaTableTranslate( 'ce-elementor-corona' );
		Shortcode::coronaTableTranslate( 'ce-elementor-country-corona' );
	}

	public function editor_scripts(){
		wp_enqueue_style( 'ce-elementor-fonts-corona', 
			CE_CORONA_ASSETS . 'css/corona-fonts.css', [], 
			\filemtime( CE_CORONA_PATH . 'assets/css/corona-fonts.css' ), 'all' 
		);
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/elementor-widget.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 */
	public function register_widgets() {
		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CoronaElementor() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CoronaCountryWiseElementor() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CoronaGraphElementor() );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}
}