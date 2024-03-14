<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 *
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete SmartBill Woocommerce Settings
 *
 * @return void
 */
function smartbill_delete_options() {
	delete_option( 'smartbill_s_taxes' );
	delete_option( 'smartbill_s_um' );
	delete_option( 'smartbill_plugin_options' );
	delete_option( 'smartbill_plugin_options_settings' );
	delete_option( 'smartbill_invoice_series' );
	delete_option( 'smartbill_estimate_series' );
	delete_option( 'smartbill_stocks' );
}
smartbill_delete_options();
