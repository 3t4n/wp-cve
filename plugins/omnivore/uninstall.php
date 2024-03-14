<?php
/**
 * Runs on Uninstall.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit; // Exit if accessed directly
}

global $wpdb;

$flavour = 'omnivore';
$key_name = 'CityBeach Omnivore';

// Delete Options
$options = array(
  $flavour . '_connection_key',
  $flavour . '_connection_endpoint',
  $flavour . '_connection_email',
  $flavour . '_connection_name',
  $flavour . '_google_ads_enable',
  $flavour . '_google_ads_account_id',
  $flavour . '_google_ads_conversion_id',
);

foreach ( $options as $option ) {
  if ( get_option( $option ) ) {
    delete_option( $option );
  }
}

// Remove REST API key.
if ((get_option('active_plugins') && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) ||
    (get_site_option('active_sitewide_plugins') && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', array_keys(get_site_option('active_sitewide_plugins')))))) {
  $wpdb->delete( $wpdb->prefix . 'woocommerce_api_keys', array( 'description' => $key_name ), array( '%s' ) );
}

