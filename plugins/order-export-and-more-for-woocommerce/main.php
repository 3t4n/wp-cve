<?php
/*
Plugin Name: Order Export for WooCommerce
Plugin URI: https://www.jem-products.com/
Description: Export WooCommerce orders, products, and other data.
Version: 3.21
Author: JEM Plugins
Author URI: https://www.jem-products.com/
Text Domain: order-export-and-more-for-woocommerce
Tested up to: 6.4
Requires PHP: 5.0

Copyright 2015 - 2022  JEM Products  (email: support@jem-products.com)
Copyright 2021 - 2023  WebFactory Ltd  (email: support@webfactoryltd.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

define('JEMEXP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('JEMEXP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JEMEXP_DOMAIN', 'jeml-woo-export-lite');

// declare global variable for options name
global $JEMEXP_product_option, $JEMEXP_order_option, $JEMEXP_customer_option, $JEMEXP_shipping_option, $JEMEXP_coupons_option, $JEMEXP_categories_option, $JEMEXP_tags_option;

// set option name
// TODO - each object should know it's own options
$JEMEXP_product_option    = JEMEXP_DOMAIN . '_Product_option';
$JEMEXP_order_option      = JEMEXP_DOMAIN . '_Order_option';
$JEMEXP_customer_option   = JEMEXP_DOMAIN . '_Customer_option';
$JEMEXP_shipping_option   = JEMEXP_DOMAIN . '_Shipping_option';
$JEMEXP_coupons_option    = JEMEXP_DOMAIN . '_Coupons_option';
$JEMEXP_categories_option = JEMEXP_DOMAIN . '_Categories_option';
$JEMEXP_tags_option       = JEMEXP_DOMAIN . '_Tags_option';

// TODO at some point we need to consider an autoloader
require_once JEMEXP_PLUGIN_PATH . 'inc/JEMEXP_Export_Data.php';

// only proceed if we are in admin mode!
if (!is_admin()) {
    return;
}

add_action('admin_post_jemxp_download_batch_file', 'jemxp_process_batch_export_download');
/*
 * This writes out the file
 * TODO need to make this variable by type
 * for now we are just exporting orders by batch
 * but will change that in the future
 */
function jemxp_process_batch_export_download()
{
    $obj = new JEMEXP_Order(new JEMEXP_Export_Data());
    $obj->download_file();
    die();
}


require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'plugin.php';
require_once JEMEXP_PLUGIN_PATH . 'inc/jem-exporter.php';
require_once JEMEXP_PLUGIN_PATH . 'inc/JEMEXP_BaseEntity.php';
require_once JEMEXP_PLUGIN_PATH . 'inc/category.php';
require_once JEMEXP_PLUGIN_PATH . 'inc/JEMEXP_Data_Engine.php';
require_once JEMEXP_PLUGIN_PATH . 'inc/JEMEXP_Order.php';

// And an order object
$order = new JEMEXP_Order(new JEMEXP_Export_Data());

/**
 * Loads the right js & css assets
 */
function jemexp_load_scripts()
{

    // Only enqueue/load if we are on our page
    if (!isset($_GET['page']) || ($_GET['page'] != 'JEMEXP_MENU')) {
        return;
    }

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-sortable');
    // wp_enqueue_script('jquery - ui - tabs');

    // Need the jquery CSS files
    global $wp_scripts;
    $jquery_version = isset($wp_scripts->registered['jquery-ui-core']->ver) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
    // Admin styles for WC pages only
    wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION);
    wp_enqueue_style('dashicons');
    wp_enqueue_script('jemexport-main', JEMEXP_PLUGIN_URL . 'js/main.js');

    // @simon 3.0
    // Bootstrap
    wp_enqueue_style('bootstrap-css', JEMEXP_PLUGIN_URL . 'css/bootstrap.min.css');
    wp_enqueue_style('fontawesome-css', JEMEXP_PLUGIN_URL . 'css/font-awesome.min.css');
    // Bootsrap Tooltip Core File
    wp_enqueue_script('bootstrap-popper-min', JEMEXP_PLUGIN_URL . 'js/popper.min.js');
    wp_enqueue_script('bootstrap-js-min', JEMEXP_PLUGIN_URL . 'js/bootstrap.min.js');

    $jemexp_settings['settings_nonce'] = wp_create_nonce('jemexp_saving_field');
    wp_localize_script('jemexport-main', 'jemexport_settings', $jemexp_settings);

    wp_enqueue_style('select-css', JEMEXP_PLUGIN_URL . 'css/select2.min.css');
    wp_enqueue_script('select2', JEMEXP_PLUGIN_URL . 'js/select2.js', array('jquery'));

    wp_enqueue_style('jemexport-css', JEMEXP_PLUGIN_URL . 'css/jem-export-lite.css');

}

/**
 * Add action links to plugins table, left part
 *
 * @param array  $links  Initial list of links.
 *
 * @return array
 */
function jemexp_plugin_action_links($links)
{

  if (class_exists( 'WooCommerce', false)) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=JEMEXP_MENU')) . '" title="' . esc_attr(__('Export Orders', 'order-export-and-more-for-woocommerce')) . '">' . esc_html(__('Export Orders', 'order-export-and-more-for-woocommerce')) . '</a>';
    array_unshift($links, $settings_link);
  }
  $pro_link = '<a target="_blank" href="' . esc_url('https://jem-products.com/woocommerce-export-orders-pro-plugin/') . '" title="' . esc_attr(__('Upgrade to PRO version', 'order-export-and-more-for-woocommerce')) . '"><b>' . esc_html(__('Get PRO', 'order-export-and-more-for-woocommerce')) . '</b></a>';

  $links[] = $pro_link;

  return $links;
} // plugin_action_links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'jemexp_plugin_action_links');


add_action('admin_enqueue_scripts', 'jemexp_load_scripts');

add_action('woocommerce_init', 'jemexp_instantiate_export');

function jemexp_instantiate_export()
{
    $jemexporter_lite = new JEMEXP_lite();
}
