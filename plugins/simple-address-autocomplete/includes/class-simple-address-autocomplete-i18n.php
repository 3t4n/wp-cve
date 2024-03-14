<?php

/**
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simple_Address_Autocomplete
 * @subpackage Simple_Address_Autocomplete/includes
 * @author     Raza Khadim <razakhadim@gmail.com>
 */
class Simple_Address_Autocomplete_i18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'simple-address-autocomplete',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
