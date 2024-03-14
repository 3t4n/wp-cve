<?php
/**
 * Plugin Name: PayTR Virtual POS WooCommerce - iFrame API
 * Plugin URI: https://wordpress.org/plugins/paytr-sanal-pos-woocommerce-iframe-api/
 * Description: The infrastructure required to receive payments through WooCommerce with your PayTR membership.
 * Version: 2.0.4
 * Requires at least: 4.4
 * Requires PHP: 5.6
 * Author: PayTR Ödeme ve Elektronik Para Kuruluşu A.Ş.
 * Author URI: http://www.paytr.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 3.0
 * WC tested up to: 6.0.0
 * Text Domain: paytr-sanal-pos-woocommerce-iframe-api
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
};

define('PAYTRSPI_VERSION', '2.0.3');
define('PAYTRSPI_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
define('PAYTRSPI_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('PAYTRSPI_MIN_WC_VER', '3.0');
define('PAYTRSPI_MIN_WP_VER', '4.4');

function active_paytrspi_plugin()
{
    require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-activation.php';
    PaytrCheckoutActivation::active();
}

function deactivate_paytrspi_plugin()
{
    require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-deactivation.php';
    PaytrCheckoutDeactivation::deactivate();
}

function notice_paytrspi_wc_missing()
{
    echo '<div class="error"><p>' . esc_html__('WooCommerce is required to be installed and active!', 'paytr-sanal-pos-woocommerce-iframe-api') . '</p></div>';
}

function notice_paytrspi_wc_not_supported()
{
    echo '<div class="error"><p>' . sprintf(esc_html__('WooCommerce %1$s or greater version to be installed and active. WooCommerce %2$s is no longer supported.', 'paytr-sanal-pos-woocommerce-iframe-api'), PAYTRSPI_MIN_WC_VER, WC_VERSION) . '</p></div>';
}

function woocommerce_paytrcheckout_init()
{

    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'notice_paytrspi_wc_missing');

        return;
    }

    if (version_compare(WC_VERSION, PAYTRSPI_MIN_WC_VER, '<')) {
        add_action('admin_notices', 'notice_paytrspi_wc_not_supported');

        return;
    }

    load_plugin_textdomain('paytr-sanal-pos-woocommerce-iframe-api', false, dirname(plugin_basename(__FILE__)) . '/languages');

    if (!class_exists('WC_PaytrCheckout')) {
        class WC_PaytrCheckout
        {
            private static $instance;

            public static function get_instance()
            {
                if (null == self::$instance) {
                    self::$instance = new self();
                }

                return self::$instance;
            }

            private function __construct()
            {
                $this->init_plugin();
            }

            public function init_plugin()
            {
                require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-checkout.php';

                add_filter('woocommerce_payment_gateways', array($this, 'add_paytr_payment_gateway'));

                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
                    $this,
                    'plugin_action_links'
                ));
                add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);

                $get_pspi_options = get_option('woocommerce_paytrcheckout_settings');

                if ($get_pspi_options != '' && $get_pspi_options['logo'] === 'yes') {
                    add_action('wp_enqueue_scripts', array($this, 'add_paytr_payment_style'));
                }
            }

            /**
             * @param $links
             *
             * @return array
             */
            public function plugin_action_links($links)
            {
                $plugin_links = array('<a href="admin.php?page=wc-settings&tab=checkout&section=paytrcheckout">' . esc_html__('Settings', 'paytr-sanal-pos-woocommerce-iframe-api') . '</a>');

                return array_merge($plugin_links, $links);
            }

            /**
             * @param $links
             * @param $file
             *
             * @return array
             */
            public function plugin_row_meta($links, $file)
            {
                if (plugin_basename(__FILE__) === $file) {
                    $row_meta = array(
                        'support' => '<a href="' . esc_url(apply_filters('paytrspi_support_url', 'https://www.paytr.com/magaza/destek')) . '" target="_blank">' . __('Support', 'paytr-sanal-pos-woocommerce-iframe-api') . '</a>'
                    );

                    return array_merge($links, $row_meta);
                }

                return (array)$links;
            }

            /**
             * @param $methods
             *
             * @return mixed
             */
            public function add_paytr_payment_gateway($methods)
            {
                $methods[] = 'WC_Gateway_PayTRCheckout';

                return $methods;
            }

            public function add_paytr_payment_style()
            {
                wp_register_style('paytr-sanal-pos-woocommerce-iframe-api', PAYTRSPI_PLUGIN_URL . '/assets/css/paytr-sanal-pos-iframe-style.css');
                wp_enqueue_style('paytr-sanal-pos-woocommerce-iframe-api');
            }
        }

        WC_PaytrCheckout::get_instance();
    }
}

add_action('plugins_loaded', 'woocommerce_paytrcheckout_init');

register_activation_hook(__FILE__, 'active_paytrspi_plugin');
register_deactivation_hook(__FILE__, 'deactivate_paytrspi_plugin');