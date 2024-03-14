<?php

/*
Plugin Name: Shipday Integration for Wordpress (WooCommerce)
Plugin URI: https://www.shipday.com/woocommerce
Version: 1.8
Description: Enable fast local deliveries for your online store or marketplace with Shipday. Easy driver and dispatch app with live delivery tracking. Built-in connection with on-demand delivery services like DoorDash and Uber in the US.
Author URI: https://www.shipday.com/
Text Domain: woocommerce-shipday
*/

/** Prevent direct access  */
defined('ABSPATH') || exit;



/** Functions end */
global $shipday_plugin_version;
$shipday_plugin_version = '1.8';

require_once ABSPATH.'wp-admin/includes/plugin.php';
require_once dirname( __FILE__ ) . '/views/WC_Settings_Tab_Shipday.php';
require_once dirname( __FILE__ ) . '/views/WCFM_vendor_settings_shipday.php';
require_once dirname(__FILE__) . '/views/Dokan_vendor_settings_shipday.php';

require_once dirname(__FILE__). '/dispatch_post/post_fun.php';
require_once dirname(__FILE__). '/functions/common.php';
require_once dirname(__FILE__). '/functions/logger.php';

require_once dirname(__FILE__). '/rest_api/WooCommerce_REST_API.php';

require_once dirname( __FILE__ ) . '/shipday_order_management/Shipday_Order_Management.php';
require_once dirname(__FILE__) . '/shipday_order_management/Woo_Sync_Order.php';

require_once dirname(__FILE__). '/views/Notices.php';

function main() {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		WC_Settings_Tab_Shipday::init();
		WCFM_vendor_settings_shipday::init();
        Dokan_vendor_settings_shipday::init();
		WooCommerce_REST_API::init();
		Shipday_Order_Management::init();
        Woo_Sync_Order::init();
		Notices::init();
	}
}

main();

?>