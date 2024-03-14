<?php
/**
 * Define the internationalization functionality.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 */

/**
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'smartbill-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
