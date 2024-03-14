<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/includes
 * @author     TheRiteSites <contact@theritesites.com>
 */
if ( ! class_exists( 'Enhanced_Ajax_Add_To_Cart_Wc_i18n' ) ) {
	class Enhanced_Ajax_Add_To_Cart_Wc_i18n {

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				'enhanced-ajax-add-to-cart-wc',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}
	}
}