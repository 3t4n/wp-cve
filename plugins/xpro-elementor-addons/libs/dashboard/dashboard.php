<?php

namespace XproElementorAddons\Libs;

use Xpro_Elementor_Addons;
use XproElementorAddons\Libs\Dashboard\Classes;

/**
 * Class Xpro_Elementor_Dashboard
 *
 * Main Xpro_Elementor_Dashboard class
 * @since 1.0.0
 */
class Xpro_Elementor_Dashboard {


	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Xpro_Elementor_Dashboard The single instance of the class.
	 */
	private static $instance = null;
	public $utils;

	/**
	 *  Xpro_Elementor_Dashboard class constructor
	 *
	 * Register Xpro_Elementor_Dashboard action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/dashboard/classes/utils.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/dashboard/classes/ajax.php';

		$this->utils = Classes\Xpro_Elementor_Dashboard_Utils::instance();
		new Classes\Xpro_Elementor_Dashboard_Ajax();

		add_action( 'admin_menu', array( $this, 'register_settings_menus' ) );

		// register js/ css
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Elementor_Dashboard An instance of the class.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function enqueue_scripts() {

		//Enqueue CSS
		wp_enqueue_style(
			'xpro-elementor-addons-admin',
			XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/css/admin.css',
			'',
			XPRO_ELEMENTOR_ADDONS_VERSION
		);

		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['page'] ) && ( Xpro_Elementor_Addons::PAGE_SLUG === $_GET['page'] || Xpro_Elementor_Addons::LICENSE_PAGE_SLUG === $_GET['page'] ) ) {
			//Enqueue CSS
			wp_enqueue_style(
				'owl-carousel',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/owl.carousel.min.css',
				'',
				'2.3.4'
			);
			wp_enqueue_style(
				'xpro-elementor-addons-grid',
				XPRO_ELEMENTOR_ADDONS_ASSETS . '/css/xpro-grid.min.css',
				'',
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
			wp_enqueue_style(
				'xpro-icons',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
			wp_enqueue_style(
				'xpro-elementor-addons-dashboard',
				XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/css/dashboard.css',
				'',
				XPRO_ELEMENTOR_ADDONS_VERSION
			);

			//Enqueue JS
			wp_enqueue_script(
				'owl-carousel',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js',
				array( 'jquery' ),
				'2.3.4',
				true
			);
			wp_enqueue_script(
				'xpro-elementor-addons-dashboard',
				XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/js/dashboard.js',
				array( 'jquery' ),
				XPRO_ELEMENTOR_ADDONS_VERSION,
				true
			);
		}
	}

	public function register_settings_menus() {

		// Add sub menu
		add_submenu_page( Xpro_Elementor_Addons::PAGE_SLUG, esc_html__( 'General', 'xpro-elementor-addons' ), esc_html__( 'General', 'xpro-elementor-addons' ), 'manage_options', Xpro_Elementor_Addons::PAGE_SLUG );

		// dashboard, main menu
		add_menu_page(
			esc_html__( 'Xpro Addons Settings', 'xpro-elementor-addons' ),
			esc_html__( 'Xpro Addons', 'xpro-elementor-addons' ),
			'manage_options',
			Xpro_Elementor_Addons::PAGE_SLUG,
			array( $this, 'register_settings_contents' ),
			XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/xpro-icon.svg',
			'58.6'
		);
	}

	public function register_settings_contents() {

		include __DIR__ . '/views/settings-init.php';
	}
}

// Instantiate Xpro_Elementor_Dashboard Class
Xpro_Elementor_Dashboard::instance();
