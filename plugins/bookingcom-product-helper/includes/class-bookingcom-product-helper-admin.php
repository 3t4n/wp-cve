<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class BookingComProductHelperAdmin
*/
class BookingComProductHelperAdmin {

	/**
	 * Init method for the class
	 */
	public static function init() {
		add_action(
			'admin_enqueue_scripts',
			array(
				'BookingComProductHelperAdmin',
				'enqueue_admin',
			)
		);
	}

	/**
	 * Methods for loading custom JS and CSS
	 */
	public static function enqueue_admin() {
		// Load JS scripts.
		wp_register_script(
			'bookingcom-product-helper-js',
			BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_CLIENT . 'js/bookingcom-product-helper-admin.js',
			array(),
			BOOKINGCOM_PRODUCT_HELPER_VERSION,
			true
		);
		wp_enqueue_script(
			'bookingcom-product-helper-js'
		);

		// Load CSS styles.
		wp_register_style(
			'bookingcom-product-helper-css',
			BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_CLIENT . 'css/bookingcom-product-helper.css',
			array(),
			BOOKINGCOM_PRODUCT_HELPER_VERSION
		);
		wp_enqueue_style( 'bookingcom-product-helper-css' );
	}

	/**
	 * Method for loading repeating templates
	 *
	 * @param string $name The name of template file.
	 */
	public static function view_template( $name ) {
		if ( isset( $name ) ) {
			load_plugin_textdomain( 'bookingcom-product-helper' );

			include_once BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_SERVER . 'views/' . $name . '.php';
		}
	}
}
