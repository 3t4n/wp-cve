<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/10/18
 * Time: 4:39 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce;


class Helper
{
    /**
     * @var array
     */
    protected static $attributeNames = array();

    /**
     * Return true if WooCommerce is active
     *
     * @return bool
     */
    public static function isWooCommerceActive()
    {
        if (!function_exists('is_plugin_active_for_network')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if (!is_plugin_active_for_network('woocommerce/woocommerce.php')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return true if beeketing is active
     *
     * @return bool
     */
    public static function isBeeketingActive()
    {
        if (!function_exists('is_plugin_active_for_network')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (!in_array('beeketing-for-woocommerce/beeketing-woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if (!is_plugin_active_for_network('beeketing-for-woocommerce/beeketing-woocommerce.php')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get woocommerce plugin url
     *
     * @return string
     */
    public static function getWooCommercePluginUrl()
    {
        return get_site_url() . '/wp-admin/plugin-install.php?tab=plugin-information&plugin=woocommerce';
    }

    /**
     * Get beeketing plugin url
     *
     * @return string
     */
    public static function getBeeketingPluginUrl()
    {
        return get_site_url() . '/wp-admin/plugin-install.php?tab=plugin-information&plugin=beeketing-for-woocommerce';
    }

    /**
     * Get shop domain
     *
     * @return string
     */
    public static function getShopDomain()
    {
        $site_url = get_home_url();
        $url_parsed = parse_url($site_url);
        $host = isset($url_parsed['host']) ? $url_parsed['host'] : '';

        // Config www
        if (isset($_GET['www'])) {
            if (in_array($_GET['www'], array(0, false))) {
                $host = preg_replace('/^www\./', '', $host);
            } elseif (!preg_match('/^www\./', $host) && in_array($_GET['www'], array(1, true))) {
                $host = 'www.' . $host;
            }
        }

        return $host;
    }

    /**
     * Get url to conenct dashboard
     * @param $path
     * @param bool $addDailyCache
     * @return string
     */
    public static function getConnectDashboardUrl($path, $addDailyCache = true)
    {
        return BEEKETINGWOOCOMMERCE_CONNECT_DASHBOARD . '/' . $path . ($addDailyCache ? '?v' . date('Ymd') : '');
    }

    /**
     * High priority widget
     *
     * @param $name
     */
    public static function highPriorityWidget($name)
    {
        // Globalize the metaboxes array, this holds all the widgets for wp-admin
        global $wp_meta_boxes;

        // Get the regular dashboard widgets array
        // (which has our new widget already but at the end)
        $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup and delete our new dashboard widget from the end of the array
        $example_widget_backup = array($name => $normal_dashboard[$name]);
        unset($normal_dashboard[$name]);

        // Merge the two arrays together so our widget is at the beginning
        $sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

        // Save the sorted array back into the original metaboxes
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    /**
     * Get currency format
     *
     * @return string|null
     */
    public static function getCurrencyFormat()
    {
        if (self::isWooCommerceActive()) {
            $currencyFormat = wc_price(11.11);
            $currencyFormat = html_entity_decode($currencyFormat);
            $amountFormat = 'amount';
            if (get_option('woocommerce_price_num_decimals') === '0') {
                $amountFormat = 'amount_no_decimals';
            }
            $decimalSep = get_option('woocommerce_price_decimal_sep');
            switch ($decimalSep) {
                case ',':
                    $amountFormat .= '_with_comma_separator';
                    break;
                case ' ':
                    $amountFormat .= '_with_space_separator';
                    break;
            }

            $currencyFormat = preg_replace('/[1]+[.,]{0,1}[1]+/', '{{' . $amountFormat . '}}', $currencyFormat, 1);

            return $currencyFormat;
        }

        return null;
    }

    /**
     * Get currency
     *
     * @return string|null
     */
    public static function getCurrency()
    {
        if (self::isWooCommerceActive()) {
            return get_woocommerce_currency();
        }

        return null;
    }

    /**
     * Generate Token
     *
     * @return string
     */
    public static function generateToken()
    {
        try {
            $string = random_bytes(16);
            $token = bin2hex($string);
        } catch (\Exception $e) {
            $token = md5(uniqid(rand(), true));
        }

        return $token;
    }

    /**
     * Return true if this is wc 3+ version
     * Use to fallback code for woocommerce 3+
     *
     * @return boolean
     */
    public static function isWc3()
    {
        if (!defined('WOOCOMMERCE_VERSION')) {
            return false;
        }

        return version_compare(WOOCOMMERCE_VERSION, '3.0', '>=');
    }

    /**
     * Get url handle
     *
     * @param $url
     * @return string
     */
    public static function getUrlHandle($url)
    {
        return ltrim(preg_replace('/^http(s)?:\/\/[^\/]+\//', '', $url), '/');
    }

    /**
     * Is beeketing hidden name
     *
     * @param $name
     * @return bool
     */
    public static function isBeeketingHiddenName($name)
    {
        if ((bool)preg_match('/\(BK (\d+)\)/', $name, $matches)) {
            return true;
        }

        return false;
    }

    /**
     * @param $attribute
     * @return string
     */
    public static function getAttributeName($attribute)
    {
        if (isset(self::$attributeNames[$attribute])) {
            return static::$attributeNames[$attribute];
        }

        if ($taxonomy = get_taxonomy($attribute)) {
            $labels = get_taxonomy_labels($taxonomy);
            static::$attributeNames[$attribute] = $labels->singular_name;
            return $labels->singular_name;
        }

        return $attribute;
    }
}
