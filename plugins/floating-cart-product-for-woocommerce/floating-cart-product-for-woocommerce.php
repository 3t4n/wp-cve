<?php
/**
* Plugin Name: Floating Cart Product For Woocommerce
* Description: This plugin allows you to Create Sidebar cart in WooCommerce.
* Version: 1.0
* Copyright: 2023
* Text Domain: floating-cart-product-for-woocommerce
* Domain Path: /languages 
*/

if (!defined('FCPFW_PLUGIN_DIR')) {
  define('FCPFW_PLUGIN_DIR',plugins_url('', __FILE__));
}


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Include All Files

//Backend Property
include_once('main/backend/fcpfw_backend.php');
include_once('main/backend/fcpfw_comman.php');
include_once('main/backend/fcpfw_svg.php');

//Fronend Design
include_once('main/frontend/fcpfw_front.php');
include_once('main/frontend/fcpfw_head_foot.php');

//Js css Instaal Requrired file
include_once('main/resource/fcpfw_load_js_css.php');
include_once('main/resource/fcpfw-language.php');
include_once('main/resource/fcpfw-installation-require.php');

function FCPFW_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'floating-cart-product-for-woocommerce') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/floating-cart-product-for-woocommerce/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'floating-cart-product-for-woocommerce') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'FCPFW_support_and_rating_links', 10, 4 );