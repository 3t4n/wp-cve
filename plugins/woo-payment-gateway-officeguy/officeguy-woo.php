<?php

/**
 * Plugin Name: WooCommerce Payment Gateway - SUMIT
 * Plugin URI: https://help.sumit.co.il/he/articles/5830000
 * Description: Accept all major credit cards directly on your WooCommerce site in a seamless and secure checkout environment using SUMIT credit card clearing and invoicing.
 * Version: 3.2.6
 * Author: SUMIT
 * Author URI: https://www.sumit.co.il
 * Text Domain: officeguy
 * Domain Path: /languages

 * @package WordPress
 * @author SUMIT
 * @since 1.0.1
 */

if (!defined('ABSPATH'))
    exit;

define('PLUGIN_DIR', plugin_dir_url(__FILE__));

/**
 * Load plugin textdomain.
 */
function officeguy_load_textdomain()
{
    load_plugin_textdomain('officeguy', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'officeguy_load_textdomain');

if (!function_exists('is_woocommerce_activated'))
{
    require_once dirname(__FILE__) . '/includes/OfficeGuyAPI.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyStock.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyTokens.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyPluginSetup.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyPayment.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyRequestHelpers.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuySubscriptions.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyCartFlow.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuySettings.php';
    require_once dirname(__FILE__) . '/includes/officeguy_woocommerce_gateway.php';
    require_once dirname(__FILE__) . '/includes/officeguybit_woocommerce_gateway.php';
    require_once dirname(__FILE__) . '/templates/single-product/officeguy-price.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyDokanMarketplace.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyWCFMMarketplace.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyWCVendorsMarketplace.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyMultiVendor.php';
    require_once dirname(__FILE__) . '/includes/OfficeGuyDonation.php';

    OfficeGuyPluginSetup::Init(__FILE__);
}

add_action('before_woocommerce_init', function() {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, false);
    }
});