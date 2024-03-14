<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */
/**
 * Check absolute path defined or not
 */
defined('ABSPATH') or die();
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
     * Plugin Constants
     */
    defined('WLL_PLUGIN_NAME') or define('WLL_PLUGIN_NAME', 'WPLoyalty - Launcher');
    defined('WLL_MINIMUM_PHP_VERSION') or define('WLL_MINIMUM_PHP_VERSION', '5.6.0');
    defined('WLL_MINIMUM_WP_VERSION') or define('WLL_MINIMUM_WP_VERSION', '4.9');
    defined('WLL_MINIMUM_WC_VERSION') or define('WLL_MINIMUM_WC_VERSION', '6.0');
    defined('WLL_PLUGIN_VERSION') or define('WLL_PLUGIN_VERSION', '2.0.0');
    defined('WLL_PLUGIN_AUTHOR') or define('WLL_PLUGIN_AUTHOR', 'WPLoyalty');
    defined('WLL_PLUGIN_SLUG') or define('WLL_PLUGIN_SLUG', 'wp-loyalty-launcher');
    defined('WLL_PLUGIN_URL') or define('WLL_PLUGIN_URL', plugin_dir_url(__FILE__));
    defined('WLL_PLUGIN_FILE') or define('WLL_PLUGIN_FILE', __FILE__);
    defined('WLL_PLUGIN_DIR') or define('WLL_PLUGIN_DIR', str_replace('\\', '/', __DIR__));
    defined('WLL_VIEW_PATH') or define('WLL_VIEW_PATH', str_replace('\\', '/', __DIR__) . '/V1/App/Views');
    defined('WLL_ASSETS_ADMIN_CSS_PATH') or define('WLL_ASSETS_ADMIN_CSS_PATH', plugins_url('/V1/Assets/Admin/Css', __FILE__));
    defined('WLL_ASSETS_ADMIN_JS_PATH') or define('WLL_ASSETS_ADMIN_JS_PATH', plugins_url('/V1/Assets/Admin/Js', __FILE__));
    if (class_exists(\Wll\V2\App\Router::class)) {
        $router = new \Wll\V2\App\Router();
        if (method_exists($router, 'initHooks')) $router->initHooks();
    }
}

