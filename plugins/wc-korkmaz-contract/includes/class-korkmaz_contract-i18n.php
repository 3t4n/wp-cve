<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
 */


/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
 * @author     Yemliha KORKMAZ <yemlihakorkmaz@hotmail.com>
 */
class Korkmaz_contract_i18n
{


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_korkmaz_contract()
	{

		load_plugin_textdomain(
			'korkmaz_contract',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
