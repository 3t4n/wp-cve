<?php

namespace App\Utils;

class Helper
{
    /**
     * Get domain without protocol
     * @return string
     */
    public static function getDomain()
    {
        return str_replace(['http://', 'https://'], '', get_site_url());
    }

    /**
     * Generate random string to echange with access token
     *
     * @return string
     */
    public static function generateExchangeCode()
    {
        return md5(uniqid() . microtime(true));
    }

    /**
     * Return true if WooCommerce is active
     *
     * @return bool
     */
    public static function isWooCommerceActive()
    {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if (!is_plugin_active_for_network('woocommerce/woocommerce.php')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if woocommerce version
     * @return boolean
     */
    public static function isMinVerWc()
    {
        $wc_version = (float) get_option('woocommerce_version');

        if (!$wc_version) {
            return false;
        }

        return $wc_version >= 3.0;
    }

    /**
     * Check if the platform coulc be considered as WooCommerce. 
     *
     * @return boolean
     */
    public static function isWc()
    {
        return self::isWooCommerceActive() && self::isMinVerWc();
    }

    /**
     * Generate HMAC string
     *
     * @param string $msg
     * @return string
     */
    public static function generateWebhookHmac($msg)
    {
        if (!is_string($msg)) {
            $msg = json_encode($msg);
        }

        return base64_encode(
            hash_hmac(
                "sha256",
                $msg,
                get_option('nextsale_access_token'),
                true
            )
        );
    }

    /**
     * Generate relative url relative to the site root.
     * Removes the site url (schema and host) from the url.
     *
     * @param string $url
     * @return void
     */
    public static function getRelativeUrl($url)
    {
        return str_replace(get_site_url(), '', $url);
    }

    /**
     * Get the platform
     *
     * @return string Return wordpress or woocommerce depending on the 
     *                installation status of the WooCommerce plugin.
     */
    public static function getPlatform()
    {
        return self::isWc() ? 'woocommerce' : 'wordpress';
    }

    /**
     * Check if auth is granted to connect with Nextsale servers
     *
     * @return boolean
     */
    public static function isAuthGranted()
    {
        return (bool) get_option('nextsale_auth_granted');
    }
}
