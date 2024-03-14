<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired during plugin uninstallation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverter_Uninstall {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {


		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		//check_admin_referer( 'bulk-plugins' );

		$settings             = new CBCurrencyconverterSetting();
		$delete_global_config = $settings->get_option( 'delete_options', 'cbcurrencyconverter_tools', '' );

		if ( $delete_global_config == 'on' ) {
			$option_prefix = 'cbcurrencyconverter_';


			//delete plugin options
			$option_values = CBCurrencyConverterHelper::getAllOptionNames();


			foreach ( $option_values as $option_value ) {
				delete_option( $option_value['option_name'] );
			}

			//delete plugin transient caches
			$transient_caches = CBCurrencyConverterHelper::getAllTransientCacheNames();
			foreach ( $transient_caches as $names ) {
				delete_transient( $names );
			}


			do_action( 'cbcurrencyconverter_plugin_uninstall', $option_prefix );
		}

	}//end uninstall

}//end class CBCurrencyConverter_Uninstall
