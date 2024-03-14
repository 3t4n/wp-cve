<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * 
 * @since      1.0.0
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/includes
 * @author     New York Software Lab
 * @link       https://nysoftwarelab.com
 */
class Spam_Protect_for_Contact_Form7_i18n {


	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'spam-protect-for-contact-form7',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
