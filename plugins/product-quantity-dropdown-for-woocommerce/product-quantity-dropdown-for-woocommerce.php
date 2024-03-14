<?php
/**
* Plugin Name: Product Quantity Dropdown For Woocommerce
* Description: This plugin allows Product Quantity dropdown add in Shop page and Product page.
* Version: 1.1
* Copyright: 2023
* Text Domain: product-quantity-dropdown-for-woocommerce
* Domain Path: /languages 
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit();
}

// Define Plugin File
define('PQDFW_PLUGIN_FILE', __FILE__ );

// Define Plugin Dir
define('PQDFW_PLUGIN_DIR',plugins_url('', __FILE__));

// Define Plugin Base Name
define('PQDFW_BASE_NAME', plugin_basename(PQDFW_PLUGIN_FILE));


// include files
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once('main/backend/pqdfw_comman.php');
include_once('main/backend/pqdfw_admin.php');
include_once('main/frontend/pqdfw-frontend.php');
include_once('main/resources/pqdfw-installation-require.php');
include_once('main/resources/pqdfw-language.php');
include_once('main/resources/pqdfw-load-js-css.php');

function PQDFW_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'product-quantity-dropdown-for-woocommerce') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/product-quantity-dropdown-for-woocommerce/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'product-quantity-dropdown-for-woocommerce') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'PQDFW_support_and_rating_links', 10, 4 );