<?php
/**
* Plugin Name: Check Pincode For Woocommerce
* Description: This plugin allows Check Pincode in Woocommerce.
* Version: 1.1
* Copyright: 2023
* Text Domain:check-pincode-in-woocommerce
* Domain Path: /languages 
*/


/* Define Plugin File */
define('CPIW_PLUGIN_FILE', __FILE__);

/* define  plugin diretorypath name   */
define('CPIW_PLUGIN_DIR',plugins_url('', __FILE__));

/* Define Plugin Base Name */
define('CPIW_BASE_NAME', plugin_basename(__FILE__));

/* Backend file include*/
include_once('main/backend/cpiw-backend.php');
include_once('main/backend/cpiw-postcode.php');
include_once('main/backend/cpiw-postcode-list.php');
include_once('main/backend/cpiw-postcode-import.php');
include_once('main/backend/cpiw-initial.php');
include_once('main/backend/cpiw-comman.php');

/* Front file include*/
include_once('main/front/cpiw-front.php');
include_once('main/front/cpiw-check-pincode.php');
include_once('main/front/cpiw-pincode-cart.php');
include_once('main/front/cpiw-pincode-checkout.php');
include_once('main/front/cpiw-pincode-popup.php');

/* Resources file include*/
include_once('main/resources/cpiw-installation-require.php');
include_once('main/resources/cpiw-language.php');
include_once('main/resources/cpiw-load-js-css.php');
include_once('main/resources/cpiw-table-create.php');

function CPIW_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'check-pincode-in-woocommerce') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/check-pincode-for-woocommerce/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'check-pincode-in-woocommerce') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'CPIW_support_and_rating_links', 10, 4 );

?>