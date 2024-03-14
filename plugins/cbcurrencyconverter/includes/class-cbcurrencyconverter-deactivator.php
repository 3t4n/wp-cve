<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired during plugin deactivation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverter_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );


		do_action( 'cbcurrencyconverter_plugin_deactivate' );

	}//end deactivate

}//end class CBCurrencyConverter_Deactivator
