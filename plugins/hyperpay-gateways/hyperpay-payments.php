<?php

/**
 * Plugin Name: HyperPay Payments
 * Description: Hyperpay is the first one stop-shop service company for online merchants in MENA Region.<strong>If you have any question, please <a href="http://www.hyperpay.com/" target="_new">contact Hyperpay</a>.</strong>
 * Version:     4.1.2
 * Text Domain: hyperpay-payments
 * Domain Path: /languages
 * Author:      Hyperpay Team
 * Author URI:  https://www.hyperpay.com
 * Requires at least: 5.3
 * Requires PHP: 7.1
 * WC requires at least: 3.0.9
 * WC tested up to: 8.3.1
 * 
 */

namespace Hyperpay\Gateways;

use Hyperpay\Gateways\App\Log;
use Hyperpay\Gateways\Main;

(function () {

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }



    require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';





    /**
     * Register schedule to remove expired log files
     */

    add_action('remove_hyperpay_expired_logs', [Log::class, 'removeExpiredLogs']);

    if (!wp_next_scheduled('remove_hyperpay_expired_logs')) {
        wp_schedule_event(time(), 'daily', 'remove_hyperpay_expired_logs');
    }


    if (!function_exists('add_settings_error')) {
        require_once ABSPATH . '/wp-admin/includes/template.php';
    }

    /**
     * HyperPay plugin url root
     */

    if (!defined('HYPERPAY_PLUGIN_DIR')) {

        define('HYPERPAY_PLUGIN_DIR', untrailingslashit(plugins_url('/', __FILE__)));
    }

    /**
     * Initialize the plugin and its modules.
     */

    add_action('plugins_loaded',  [Main::class, 'load']);


    /*
    * Load plugin textdomain.
    */
    add_action('init', function () {

        load_plugin_textdomain('hyperpay-payments', false, basename(dirname(__FILE__)) . '/languages');
        wp_enqueue_style('hyperpay_custom_style', HYPERPAY_PLUGIN_DIR . '/src/assets/css/style.css', [], '4');

        if (is_rtl())
            wp_enqueue_style('hyperpay_custom_style_ar', HYPERPAY_PLUGIN_DIR . '/src/assets/css/style-rtl.css');
    });

    /**
     * add capture function to order actions
     */
    add_action('woocommerce_order_actions',  function ($actions, $order) {
        $is_pre_authorization = $order->get_meta('is_pre_authorization');
        if (is_array($actions) && $is_pre_authorization) {
            $actions['capture_payment'] = __('Capture Pre Authorization', 'hyperpay-payments');
        }
        return $actions;
    }, 10, 2);

    /**
     * add HPOS wooCommerce feature
     */
    add_action('before_woocommerce_init', function () {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    });



})();
