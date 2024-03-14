<?php

namespace PlatiOnlinePO6\Inc\Core;

use PlatiOnlinePO6 as NS;

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */
class Activator
{

    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $php_min_version = '5.5';
        $curl_min_version = '7.29.0';
        $openssl_min_version = 0x1000100f; //1.0.1

        // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
        if (\version_compare(PHP_VERSION, $php_min_version, '<')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires a minmum PHP Version of ' . $php_min_version);
        }

        if (\version_compare(WOOCOMMERCE_VERSION, '3.0.4', '<')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires Woocommerce minimum version 3.0.4 or later');
        }

        if (!\extension_loaded('soap')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires PHP SOAP extension to be installed and active');
        }

        if (!\extension_loaded('curl')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires PHP CURL extension to be installed and active');
        }

        if (\version_compare(\curl_version()['version'], $curl_min_version, '<')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires a minmum cURL Version of ' . $curl_min_version);
        }

        if (!\extension_loaded('openssl')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires a minmum OpenSSL extension');
        }

        if (OPENSSL_VERSION_NUMBER < $openssl_min_version) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires a minmum OpenSSL Version of 1.0.1' . $openssl_min_version);
        }

        if (!\class_exists('WC_Payment_Gateway')) {
            deactivate_plugins(NS\PLUGIN_BASENAME);
            wp_die('This plugin requires Woocommerce');
        }
    }
}
