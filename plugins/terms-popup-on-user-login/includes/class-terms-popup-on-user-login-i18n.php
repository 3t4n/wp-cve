<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'terms-popup-on-user-login',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
