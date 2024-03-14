<?php
/*
Plugin Name: PayGreen Payment Gateway
Description: Extends WooCommerce with a PayGreen gateway.
Version: 1.0.19
Author: PayGreen
Author URI: http://www.paygreen.io
Text Domain: paygreen-payment-gateway
Domain Path: /languages
*/

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\WC_Paygreen_Payment_Blocks_Support;
use Paygreen\Module\WC_Paygreen_Payment_Gateway;
use Paygreen\Module\Controller\WC_Paygreen_Payment_Return_Controller;
use Paygreen\Module\Controller\WC_Paygreen_Payment_Webhook_Controller;

define('WC_PAYGREEN_PAYMENT_MAIN_FILE', __FILE__);
define('WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE', 'page-paygreen-frontoffice.php');

add_action('plugins_loaded', 'init_paygreen_payment_gateway');
add_action('frontpage_template', 'display_frontoffice_error', PHP_INT_MAX);
add_action('woocommerce_blocks_loaded', 'paygreen_payment_woocommerce_blocks_support');

function init_paygreen_payment_gateway() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    /**
     * Localisation
     */
    function load_paygreen_payment_textdomain($mo_file, $domain) {

        if ('paygreen-payment-gateway' === $domain) {
            $locale = apply_filters('plugin_locale', determine_locale(), $domain);
            $filename = 'paygreen-payment-gateway-' . $locale . '.mo';

            $mo_file = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $filename;
        }

        return $mo_file;
    }

    add_filter('load_textdomain_mofile', 'load_paygreen_payment_textdomain', 10, 2);

    load_plugin_textdomain(
        'paygreen-payment-gateway',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );

    /**
     * Load dependencies
     */
    require_once dirname(__FILE__) . '/vendor/autoload.php';

    new WC_Paygreen_Payment_Return_Controller();
    new WC_Paygreen_Payment_Webhook_Controller();

    /**
     * Add PayGreen Gateway to WooCommerce
     */
    function add_paygreen_payment_gateway($methods) {
        $methods[] = new WC_Paygreen_Payment_Gateway();

        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_paygreen_payment_gateway');
}

function display_frontoffice_error($template)
{
    $is_paygreen_request = (isset($_GET['pgaction']) || isset($_POST['pgaction']));

    if (is_front_page() && $is_paygreen_request) {
        if (!isset($_GET['nonce']) || !wp_verify_nonce(sanitize_key($_GET['nonce']), 'wc_paygreen_payment_failure')) {
            throw new WC_Paygreen_Payment_Exception(
                'WC_Paygreen_Payment_Failure_Controller::process - Missing nonce, CSRF token validation has failed.',
                __('CSRF verification failed.', 'paygreen-payment-gateway')
            );
        }

        if (!isset($_GET['message_id'])) {
            throw new WC_Paygreen_Payment_Exception(
                'WC_Paygreen_Payment_Failure_Controller::process - Missing error message.',
                __('Missing error message.', 'paygreen-payment-gateway')
            );
        }

        if (file_exists(get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'woocommerce' . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE)) {
            $template = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'woocommerce' . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE;
        } elseif (file_exists(get_stylesheet_directory() . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE)) {
            $template = get_stylesheet_directory() . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE;
        } elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'woocommerce' . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE)) {
            $template = get_template_directory() . DIRECTORY_SEPARATOR . 'woocommerce' . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE;
        } elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE)) {
            $template = get_template_directory() . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE;
        } else {
            $template = plugin_dir_path(WC_PAYGREEN_PAYMENT_MAIN_FILE) . DIRECTORY_SEPARATOR . WC_PAYGREEN_PAYMENT_FRONTOFFICE_TEMPLATE_FILE;
        }
    }

    return $template;
}

function paygreen_payment_woocommerce_blocks_support() {
    if (class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function(PaymentMethodRegistry $payment_method_registry) {
                $payment_method_registry->register(new WC_Paygreen_Payment_Blocks_Support());
            }
        );
    }
}