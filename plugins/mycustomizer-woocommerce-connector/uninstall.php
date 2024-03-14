<?php

require __DIR__ . '/vendor/autoload.php';

use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

global $wpdb, $wp_version;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$settings    = new MczrSettings();
$mczrConnect = new MczrConnect();

$brand = $settings->get( 'brand' );

if ( ! empty( $brand ) ) {
	$mczrConnect->uninstall( $brand, $settings->get( 'shopId' ) );
} else {
	error_log( 'NO BRAND' );
}

// Delete options beginning with prefix (mczrSetting)
$settingOptionPrefix = "'%" . MczrSettings::SETTING_OPTION_PREFIX . "%'";
$wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->options . '` WHERE option_name LIKE %s', $settingOptionPrefix ) );
// Clear any cached data that has been removed
wp_cache_flush();
