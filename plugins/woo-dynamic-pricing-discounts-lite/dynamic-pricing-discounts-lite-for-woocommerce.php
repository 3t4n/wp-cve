<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.redefiningtheweb.com
 * @since             1.0.0
 * @package           Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       Dynamic Pricing & Discounts Lite
 * Plugin URI:        https://redefiningtheweb.com/plugins/
 * Description:       This plugin is a lite version of WooCommerce Dynamic Pricing & Discounts with A.I.
 * Version:           1.6.1
 * Author:            RedefiningTheWeb
 * Author URI:        https://redefiningtheweb.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rtwwdpdl-woo-dynamic-pricing-discounts-lite
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to: 7.6.1
 */

// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_VERSION', '1.6.1');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/rtwwdpdl-class-woo-dynamic-pricing-discounts-lite.php';

/**
 * Check woocommerce and other required setting to run plugin.
 *
 * @since     1.0.0
 * @return    boolean.
 */
function rtwwdpdl_check_run_allows()
{
    $rtwwdpdl_woo_status = true;
    if (function_exists('is_multisite') && is_multisite())
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (!is_plugin_active('woocommerce/woocommerce.php'))
        {
            $rtwwdpdl_woo_status = false;
        }
    }
    else
    {
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
        {
            $rtwwdpdl_woo_status = false;
        }
    }
    return $rtwwdpdl_woo_status;
}
if (rtwwdpdl_check_run_allows())
{

    if (function_exists('is_multisite') && is_multisite())
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (is_plugin_active('woo-dynamic-pricing-discounts-with-ai/rtwwdpd-woo-dynamic-pricing-discounts-with-ai.php'))
        {
            return;
        }
    }
    else
    {
        if (in_array('woo-dynamic-pricing-discounts-with-ai/rtwwdpd-woo-dynamic-pricing-discounts-with-ai.php', apply_filters('active_plugins', get_option('active_plugins'), array())))
        {
            return;
        }
    }

    //Plugin Constant
    if (!defined('RTWWDPDL_DIR'))
    {
        define('RTWWDPDL_DIR', plugin_dir_path(__FILE__));
    }
    if (!defined('RTWWDPDL_URL'))
    {
        define('RTWWDPDL_URL', plugin_dir_url(__FILE__));
    }
    if (!defined('RTWWDPDL_HOME'))
    {
        define('RTWWDPDL_HOME', home_url());
    }

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function rtwwdpdl_run_woo_dynamic_pricing_discounts_lite()
    {
        $plugin = new Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite();
        $plugin->rtwwdpdl_run();
    }
    rtwwdpdl_run_woo_dynamic_pricing_discounts_lite();
}
else
{
    add_action('admin_notices', 'rtwwdpdl_error_notice');

    /**
     * Show plugin error notice.
     *
     * @since     1.0.0
     */
    function rtwwdpdl_error_notice()
    {
?>
        <div class="error notice is-dismissible">
            <p><?php esc_html_e('Woocommerce is not activated, Please activate Woocommerce first to install ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><strong><?php esc_html_e('Dynamic Pricing & Discounts Lite for WooCommerce.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
        </div>
<?php
    }
}
