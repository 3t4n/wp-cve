<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Express Checkout via PayPal for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/express-checkout/
 * Description:       Add a PayPal Checkout to your WooCommerce Website and start selling today. Developed by Official PayPal Partner.
 * Version:           5.1.2
 * Author:            wpgateways
 * Author URI:        https://profiles.wordpress.org/wpgateways/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       express-checkout
 * Domain Path:       /languages
 * Requires at least: 5.3.0
 * Tested up to: 6.1.1
 * WC requires at least: 3.0.0
 * WC tested up to: 7.1.0
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('EXPRESS_CHECKOUT_VERSION', '5.1.2');

if (!defined('EXPRESS_CHECKOUT_PATH')) {
    define('EXPRESS_CHECKOUT_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
}
if (!defined('EXPRESS_CHECKOUT_DIR')) {
    define('EXPRESS_CHECKOUT_DIR', dirname(__FILE__));
}
if (!defined('EXPRESS_CHECKOUT_BASENAME')) {
    define('EXPRESS_CHECKOUT_BASENAME', plugin_basename(__FILE__));
}
if (!defined('EXPRESS_CHECKOUT_ASSET_URL')) {
    define('EXPRESS_CHECKOUT_ASSET_URL', plugin_dir_url(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-express-checkout-activator.php
 */
function activate_express_checkout() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-express-checkout-activator.php';
    Express_Checkout_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-express-checkout-deactivator.php
 */
function deactivate_express_checkout() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-express-checkout-deactivator.php';
    Express_Checkout_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_express_checkout');
register_deactivation_hook(__FILE__, 'deactivate_express_checkout');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce.php';
require plugin_dir_path(__FILE__) . 'includes/class-express-checkout.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_express_checkout() {

    $plugin = new Express_Checkout();
    $plugin->run();
}

function init_express_checkout_gateway_class() {
    if (class_exists('WC_Payment_Gateway')) {
        run_ppcp_paypal_checkout_for_woocommerce();
    }
    run_express_checkout();
}

add_action('plugins_loaded', 'init_express_checkout_gateway_class', 11);

function run_ppcp_paypal_checkout_for_woocommerce() {
    $plugin = new PPCP_Paypal_Checkout_For_Woocommerce();
    $plugin->run();
}
