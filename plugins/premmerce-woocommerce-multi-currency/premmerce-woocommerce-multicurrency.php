<?php

use Premmerce\WoocommerceMulticurrency\WoocommerceMulticurrencyPlugin;

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce Multi-Currency for Woocommerce
 * Plugin URI:        https://premmerce.com/premmerce-woocommerce-multi-currency/
 * Description:       Add multi-currency to your Woocommerce store.
 * Version:           2.3.5
 * Author:            Premmerce
 * Author URI:        https://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-woocommerce-multicurrency
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 7.3.0
 *
  */

// If this file is called directly, abort.
defined('WPINC') || die;


if ( ! function_exists('premmerce_pwm_fs')) {

    call_user_func(function () {

        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

        require_once plugin_dir_path(__FILE__) . '/freemius.php';

        $main = new WoocommerceMulticurrencyPlugin(__FILE__);

        add_action('woocommerce_loaded', function () use ($main) {
            $functionsFilePath = plugin_dir_path(__FILE__) . '/functions.php';
            if (file_exists($functionsFilePath)) {
                require_once $functionsFilePath;
            }
        }, 11);

        register_activation_hook(__FILE__, [$main, 'activate']);

        register_uninstall_hook(__FILE__, [WoocommerceMulticurrencyPlugin::class, 'uninstall']);

        /**
         * Freemius rewrites registered uninstall hook on deactivation (@see Freemius::_deactivate_plugin_hook).
         * So, if we have Freemius included, we need to run uninstall function by another hook (@see Freemius::_uninstall_plugin_hook
         * and hooks description in freemius start.php file).
         */
        if (function_exists('premmerce_pwm_fs')) {
            premmerce_pwm_fs()->add_action('after_uninstall', [WoocommerceMulticurrencyPlugin::class, 'uninstall']);
        }

        $main->run();
    });

}
