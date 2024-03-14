<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpartisan.net/
 * @since      1.0.0
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/includes
 * @author     wpArtisan
 */
class Remove_Add_To_Cart_Button_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function ratcw_load_plugin_textdomain() {

		load_plugin_textdomain(
			'remove-add-to-cart-button-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
