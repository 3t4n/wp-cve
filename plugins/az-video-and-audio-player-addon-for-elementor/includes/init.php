<?php
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Plugin as Plugin;


if( !class_exists('VAPFEM_Elementor_Init') ){
	class VAPFEM_Elementor_Init {

		const VERSION = "1.0.0";
		const MINIMUM_ELEMENTOR_VERSION = "2.0.0";
		const MINIMUM_PHP_VERSION = "5.6";

		private static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;

		}

		public function __construct() {
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		public function init() {

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

				return;
			}

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

				return;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			}

			// load text domain
			load_plugin_textdomain( 'vapfem', false, VAPFEM_DIR . '/languages/' );

			add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

			add_action( "elementor/frontend/after_enqueue_styles", [ $this, 'widget_styles' ] );
			add_action( "elementor/frontend/after_register_scripts" , [ $this, 'widget_scripts' ] );

			// Elementor dashboard panel style
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );

		}

		// widget styles
		function widget_styles() {
			wp_enqueue_style( "plyr", VAPFEM_URI . '/assets/css/plyr.css' );
			wp_enqueue_style( "vapfem-main", VAPFEM_URI . '/assets/css/main.css' );
		}

		// widget scripts
		function widget_scripts() {
			wp_register_script( "plyr", VAPFEM_URI. '/assets/js/plyr.min.js', array( 'jquery' ), self::VERSION, true );
			wp_register_script( "plyr-polyfilled", VAPFEM_URI. '/assets/js/plyr.polyfilled.min.js', array( 'jquery' ), self::VERSION, true );
			wp_register_script( "vapfem-main", VAPFEM_URI. '/assets/js/main.js', array( 'jquery' ), self::VERSION, true );
		}

		function editor_scripts() {
			wp_enqueue_style( "vapfem-editor", VAPFEM_URI . '/assets/css/editor.css' );
		}

		// initialize widgets
		public function init_widgets() {
			require_once( VAPFEM_DIR . '/includes/widgets/video-player.php' );
			require_once( VAPFEM_DIR . '/includes/widgets/audio-player.php' );

			// Register widget
			Plugin::instance()->widgets_manager->register_widget_type( new \VAPFEM_Video_Player() );
			Plugin::instance()->widgets_manager->register_widget_type( new \VAPFEM_Audio_Player() );
		}


		public function admin_notice_minimum_php_version() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'vapfem' ),
				'<strong>' . esc_html__( 'Smart Player For Elementor', 'vapfem' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'vapfem' ) . '</strong>',
				self::MINIMUM_PHP_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		public function admin_notice_minimum_elementor_version() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'vapfem' ),
				'<strong>' . esc_html__( 'Smart Player For Elementor', 'vapfem' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'vapfem' ) . '</strong>',
				self::MINIMUM_ELEMENTOR_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		public function admin_notice_missing_main_plugin() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'vapfem' ),
				'<strong>' . esc_html__( 'Smart Player For Elementor', 'vapfem' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'vapfem' ) . '</strong>'
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );


		}

	}
	
	VAPFEM_Elementor_Init::instance();
}
