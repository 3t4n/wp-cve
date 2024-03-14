<?php
/**
 * Plugin Name: Sparxpres for WooCommerce
 * Version: 1.2.16
 * Requires at least: 5.9
 * Requires PHP: 7.2
 * Author: Sparxpres
 * Author URI: https://sparxpres.dk/
 * Description: Sparxpres Gateway for WooCommerce.
 * Text Domain: sparxpres
 * Domain Path: /i18n/languages/
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || exit;

/**
 * Activation and deactivation hooks for WordPress
 */
function sparxpres_for_woocommerce_activate()
{
    // activation logic goes here.
}

register_activation_hook(__FILE__, 'sparxpres_for_woocommerce_activate');

function sparxpres_for_woocommerce_deactivate()
{
    // deactivation logic goes here.
}

register_deactivation_hook(__FILE__, 'sparxpres_for_woocommerce_deactivate');


if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    if (!class_exists('WC_Sparxpres_Web_Sale')) {

        class WC_Sparxpres_Web_Sale
        {
            /**
             * The single instance of the class.
             */
            private static $_instance = null;
            private static $_version = '1.2.16';

            private $plugin_dir_path = null;

            /**
             * Constructor.
             */
            protected function __construct()
            {
                $this->plugin_dir_path = plugin_dir_path(__FILE__);

                require_once $this->plugin_dir_path . 'includes/sparxpres-utils.php';
                require_once $this->plugin_dir_path . 'includes/payment-gateway/sparxpres-payment-gateway.php';
                require_once $this->plugin_dir_path . 'includes/payment-gateway/xprespay-payment-gateway.php';

                add_action('plugins_loaded', array($this, 'pluginsLoaded'));
            }

            /**
             * Main Extension Instance.
             * Ensures only one instance of the extension is loaded or can be loaded.
             */
            public static function instance()
            {
                if (is_null(self::$_instance)) {
                    self::$_instance = new self();
                }

                return self::$_instance;
            }

            /**
             * Cloning is forbidden.
             */
            public function __clone()
            {
                // Override this PHP function to prevent unwanted copies of your instance.
                wc_doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'woocommerce'), '2.1');
            }

            /**
             * Unserializing instances of this class is forbidden.
             */
            public function __wakeup()
            {
                // Override this PHP function to prevent unwanted copies of your instance.
                wc_doing_it_wrong(
                    __FUNCTION__,
                    __('Unserializing instances of this class is forbidden.', 'woocommerce'),
                    '2.1'
                );
            }

            /**
             * @return SparxpresWebSaleAdmin|SparxpresWebSaleFrontend|void
             */
            public function run()
            {
                if (is_admin()) {
                    if (!class_exists('SparxpresWebSaleAdmin', false)) {
                        require_once $this->plugin_dir_path . 'includes/admin/sparxpres-admin.php';
                        return new SparxpresWebSaleAdmin(self::$_version, __FILE__);
                    }
                } else {
                    if (!class_exists('SparxpresWebSaleFrontend', false)) {
                        require_once $this->plugin_dir_path . 'includes/rest/callback.php';
                        require_once $this->plugin_dir_path . 'includes/frontend/sparxpres-frontend.php';
                        return new SparxpresWebSaleFrontend(self::$_version, __FILE__);
                    }
                }
            }

            /**
             * Plugins loaded
             */
            public function pluginsLoaded()
            {
                load_plugin_textdomain('sparxpres', false, dirname(plugin_basename(__FILE__)) . '/i18n/languages/');
            }

        }
    }

    WC_Sparxpres_Web_Sale::instance()->run();
}
