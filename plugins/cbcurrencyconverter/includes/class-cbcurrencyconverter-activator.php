<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CBCurrencyConverter
 * @subpackage CBCurrencyConverter/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CBCurrencyConverter_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		do_action( 'cbcurrencyconverter_plugin_activate' );

		if ( ! cbcurrencyconverter_compatible_wp_version() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( 'CBX Currency Converter plugin requires WordPress 3.5 or higher!', 'cbcurrencyconverter' ) );
		}

		if ( ! cbcurrencyconverter_compatible_php_version() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( 'CBX Currency Converter plugin requires PHP 7.4 or higher!', 'cbcurrencyconverter' ) );
		}

		set_transient( 'cbcurrencyconverter_activated_notice', 1 );

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( in_array( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php', apply_filters( 'active_plugins',
				get_option( 'active_plugins' ) ) ) || defined( 'CBCURRENCYCONVERTERADDON_NAME' ) ) {
			//plugin is activated

			$pro_plugin_version = CBCURRENCYCONVERTERADDON_VERSION;


			if ( version_compare( $pro_plugin_version, '1.7.0', '<' ) ) {
				deactivate_plugins( 'cbcurrencyconverteraddon/cbcurrencyconverteraddon.php' );
				set_transient( 'cbcurrencyconverteraddon_forcedactivated_notice', 1 );
			}
		}
	}//end activate
}//end class CBCurrencyConverter_Activator
