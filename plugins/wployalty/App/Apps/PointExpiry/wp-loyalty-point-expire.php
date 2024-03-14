<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') or die;
//Define the plugin version
defined('WLPE_PLUGIN_VERSION') or define('WLPE_PLUGIN_VERSION', '1.0.0');
defined('WLPE_PLUGIN_SLUG') or define('WLPE_PLUGIN_SLUG', 'wp-loyalty-point-expire');
defined('WLPE_PLUGIN_PATH') or define('WLPE_PLUGIN_PATH', __DIR__ . '/');
defined('WLPE_PLUGIN_NAME') or define('WLPE_PLUGIN_NAME', 'WPLoyalty - Points Expiry');
defined('WLPE_PLUGIN_FILE') or define('WLPE_PLUGIN_FILE', __FILE__);
defined('WLPE_PLUGIN_AUTHOR') or define('WLPE_PLUGIN_AUTHOR', 'WPLoyalty');
defined('WLPE_PLUGIN_URL') or define('WLPE_PLUGIN_URL', plugin_dir_url(__FILE__));
defined('WLPE_MINIMUM_PHP_VERSION') or define('WLPE_MINIMUM_PHP_VERSION', '5.6.0');
defined('WLPE_MINIMUM_WP_VERSION') or define('WLPE_MINIMUM_WP_VERSION', '4.9');
defined('WLPE_MINIMUM_WC_VERSION') or define('WLPE_MINIMUM_WC_VERSION', '3.0.9');
/**
 * Function to check parent plugin wployalty activate or not
 */
if (!function_exists('isWployaltyActiveOrNot')) {
    function isWployaltyActiveOrNot()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('wp-loyalty-rules/wp-loyalty-rules.php', $active_plugins, false) || in_array('wp-loyalty-rules-lite/wp-loyalty-rules-lite.php', $active_plugins, false) || in_array('wployalty/wp-loyalty-rules-lite.php', $active_plugins, false);
    }
}
if (isWployaltyActiveOrNot()) {
    /**
     * Start Plugin
     */
    if (class_exists(\Wlpe\App\Router::class)) {
        $plugin = new \Wlpe\App\Router();
        if (method_exists($plugin, 'init')) $plugin->init();
    }
}