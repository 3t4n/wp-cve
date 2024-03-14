<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_cars");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_configs");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_countries");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_currencies");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_orders");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_order_car_rel");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_paymentmethods");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_payment_plg_cash");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblight_payment_plg_paypal");

delete_option( "tblight_db_version" );
delete_option( "tblight_plugin_version" );
delete_option( "tblight_installed_at" );