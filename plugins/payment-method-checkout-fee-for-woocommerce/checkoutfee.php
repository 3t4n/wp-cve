<?php
/**
* Plugin Name: Payment Method Checkout Fee for WooCommerce
* Plugin URI:
* Description: Payment Method Checkout Fee for WooCommerce
* Version: 1.0
* Author: Ivan Popov
* Author URI: https://vipestudio.com
**/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/* Include all PHP files */
foreach (glob(plugin_dir_path(__FILE__) . "*.php") as $file) {
    include_once $file;
}

// Admin Css
function pmcf_load_wp_admin_style() {
    if (class_exists('WooCommerce')) {
        wp_register_style('checkoutfee_wp_admin_css', plugins_url('admin/css/admin_css.css', __FILE__), false, '1.0.0', 'all');
        wp_enqueue_style('checkoutfee_wp_admin_css');
    }
}
add_action('admin_enqueue_scripts', 'pmcf_load_wp_admin_style');

// Register page and menu
function pmcf_options_page() {
    if (class_exists('WooCommerce')) {
        add_options_page('Payment Method Checkout Fee', 'Checkout Fee', 'manage_options', 'checkoutfee', 'pmcf_options_pageview');
    }
}
add_action('admin_menu', 'pmcf_options_page');

// Register field Label
function pmcf_register_settings() {
    if (class_exists('WooCommerce')) {
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];

        if ($gateways) {
            foreach ($gateways as $gateway) {
                if ($gateway->enabled == 'yes') {
                    $enabled_gateways[] = $gateway;
                    $methodtitle = $gateway->title;
                    $methodslug = $gateway->id;
                    add_option($methodslug . "_name_enabled", ''); //enabled
                    register_setting('checkoutfee_options_group', $methodslug . "_name_enabled", $methodslug . "_callback_enabled");
                    add_option($methodslug . "_name_label", ''); //label
                    register_setting('checkoutfee_options_group', $methodslug . "_name_label", $methodslug . "_callback_label");
                    add_option($methodslug . "_name_percent", ''); //percent
                    register_setting('checkoutfee_options_group', $methodslug . "_name_percent", $methodslug . "_callback_percent");
                }
            }
        }
    }
}
add_action('admin_init', 'pmcf_register_settings');

// Visualize the admin page
function pmcf_options_pageview() {
    if (class_exists('WooCommerce')) {
        include plugin_dir_path(__FILE__) . 'admin/main.php';
    }
}

// Check if WooCommerce is enabled before executing the function
function pmcf_payment_method_checkout_fee() {
    if (class_exists('WooCommerce')) {
        $payment_method = WC()->session->get('chosen_payment_method');
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];

        foreach ($gateways as $gateway) {
            if ($gateway->enabled == 'yes') {
                $enabled_gateways[] = $gateway;
                $methodtitle = $gateway->title;
                $methodslug = $gateway->id;

                if ($payment_method == $methodslug) {  // Payment Method Slug
                    $isitenabled = get_option($methodslug . "_name_enabled");

                    if ($isitenabled == 1) {
                        $label = get_option($methodslug . "_name_label"); // Give your fee a name
                        $percentageChange = get_option($methodslug . "_name_percent"); // Define the % from the order total
                        $label .= " (" . $percentageChange . "%)";
                        $originalNumber = WC()->cart->get_cart_contents_total();
                        $numberToAdd = ($originalNumber / 100) * $percentageChange;

                        // Third parameter is tax application
                        // Fourth is the tax slug
                        WC()->cart->add_fee($label, $numberToAdd, true, 'standard');
                    }
                }
            }
        }
    }
}

// Action for calculating fees
function pmcf_add_checkout_fees() {
    if (class_exists('WooCommerce')) {
        add_action('woocommerce_cart_calculate_fees', 'pmcf_payment_method_checkout_fee');
    }
}
add_action('wp_loaded', 'pmcf_add_checkout_fees');

// Some JavaScript for Calculation
function pmcf_js_calculation() {
    if (class_exists('WooCommerce')) {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('body').on('change', '.checkout .input-radio', function() {
                    $('body').trigger('update_checkout');
                });
            });
        </script>
        <?php
    }
}
add_action('woocommerce_after_checkout_form', 'pmcf_js_calculation');
