<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 1.0
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wcpscwc_Admin {

	function __construct() {

		// Action to register plugin settings
		add_action ( 'admin_init', array( $this, 'wcpscwc_admin_processes' ) );

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wcpscwc_register_menu'), 12 );
	}

	/**
	 * Function register setings
	 * 
	 * @since 2.5
	 */
	function wcpscwc_admin_processes() {

		// If plugin notice is dismissed
		if( isset($_GET['message']) && 'wcpscwc-plugin-notice' == $_GET['message'] ) {
			set_transient( 'wcpscwc_install_notice', true, 604800 );
		}

		// Redirect on features page
		if( get_option( 'wcpscwc_sf_optin', false ) ) {

			delete_option( 'wcpscwc_sf_optin' );

			$redirect_link = add_query_arg( array('page' => 'wcpscwc-solutions-features' ), admin_url( 'admin.php' ) );

			wp_safe_redirect( $redirect_link );
			exit;
		}
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.0.0
	 */
	function wcpscwc_register_menu() {

		// Getting Started page
		add_menu_page( __('Woo - Product Slider', 'woo-product-slider-and-carousel-with-category'), __('Woo - Product Slider', 'woo-product-slider-and-carousel-with-category'), 'manage_options', 'wcpscwc-about', array($this, 'wcpscwc_designs_page'), 'dashicons-slides', 56 );

		// Setting page
		add_submenu_page( 'wcpscwc-about', __('Solutions & Features - Woo - Product Slider/Carousel', 'woo-product-slider-and-carousel-with-category'), '<span style="color:#2ECC71">'. __('Solutions & Features', 'woo-product-slider-and-carousel-with-category').'</span>', 'manage_options', 'wcpscwc-solutions-features', array($this, 'wcpscwc_solutions_features_page') );

		// Register plugin premium page
		add_submenu_page( 'wcpscwc-about', __('Upgrade To PRO - Woo Product Slider', 'woo-product-slider-and-carousel-with-category'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'woo-product-slider-and-carousel-with-category').'</span>', 'edit_posts', 'wcpscwc-premium', array($this, 'wcpscwc_premium_page') );
	}

	/**
	 * How it work Page Html
	 * 
	 * @since 1.0.0
	 */
	function wcpscwc_designs_page() {
		include_once( WCPSCWC_DIR . '/includes/admin/wcpscwc-how-it-work.php' );
	}

	/**
	 * solutions and features Page Html
	 * 
	 * @since 1.0.0
	 */
	function wcpscwc_solutions_features_page() {
		include_once( WCPSCWC_DIR . '/includes/admin/settings/solutions-features.php' );
	}

	/**
	 * Premium Page Html
	 * 
	 * @since 1.0.0
	 */
	function wcpscwc_premium_page() {
		include_once( WCPSCWC_DIR . '/includes/admin/settings/premium.php' );
	}
}

$wcpscwc_Admin = new Wcpscwc_Admin();