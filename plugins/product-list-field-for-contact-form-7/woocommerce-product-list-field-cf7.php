<?php
/**
* Plugin Name: Product List Field For Contact Form 7
* Description: This plugin allows create Woocommerce Product List Field For Contact Form 7 plugin.
* Version: 1.0
* Copyright: 2023
* Text Domain: woocommerce-product-list-field-for-contact-form-7
* Domain Path: /languages 
*/


if (!defined('ABSPATH')) {
    die('-1');
}


// Define plugin file
define('WPLFCF7_PLUGIN_FILE', __FILE__);

// Define plugin dir
define('WPLFCF7_PLUGIN_DIR', plugins_url('', __FILE__));

// Define base name
define('WPLFCF7_BASE_NAME', plugin_basename(__FILE__));

// Include all files
include_once('main/backend/wplfcf7-product.php');
include_once('main/frontend/wplfcf7-product-control.php');
include_once('main/resources/wplfcf7-installation-require.php');
include_once('main/resources/wplfcf7-language.php');
include_once('main/resources/wplfcf7-load-js-css.php');

function WPLFCF7_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'woocommerce-product-list-field-for-contact-form-7') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/product-list-field-for-contact-form-7/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'woocommerce-product-list-field-for-contact-form-7') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'WPLFCF7_support_and_rating_links', 10, 4 );