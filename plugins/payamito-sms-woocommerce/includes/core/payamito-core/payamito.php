<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://payamito.com/
 * @since             1.0.0
 * @package           Payamito
 * @wordpress-plugin
 * Plugin Name:       Payamito core
 * Description:       Payamito core plugin
 * Version:           2.1.8
 * Author:            payamito
 * Author URI:        https://payamito.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       payamito
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

require_once __DIR__ . '/lib/vendor/autoload.php';

if (!defined('PAYAMITO_VERSION')) {
	define('PAYAMITO_VERSION', '2.1.8');
}
if (!defined('PAYAMITO_DIR')) {
	define('PAYAMITO_DIR', plugin_dir_path(__FILE__));
}
if (!defined('PAYAMITO_ADMIN')) {
	define('PAYAMITO_ADMIN', PAYAMITO_DIR . 'admin/');
}
if (!defined('PAYAMITO_INCLUDES')) {
	define('PAYAMITO_INCLUDES', PAYAMITO_DIR . 'includes/');
}
if (!defined('PAYAMITO_URL')) {
	define('PAYAMITO_URL', plugins_url('', __FILE__));
}
if (!defined('PAYAMITO_BASENAME')) {
	define('PAYAMITO_BASENAME', plugin_basename(__FILE__));
}

$GLOBALS['payamito_prefix_option'] = 'payamito';

if (!function_exists("payamito_set_locale")) {
	function payamito_set_locale()
	{
		//$tst=dirname(__FILE__);
		$dirname = str_replace('//', '/', wp_normalize_path(dirname(__FILE__)));

		$mo = $dirname . '/languages/' . 'payamito-' . get_locale() . '.mo';
		load_textdomain('payamito', $mo);
		$mo = $dirname . '/languages/' . 'tgmpa-' . get_locale() . '.mo';
		load_textdomain('tgmpa', $mo);
	}
}

payamito_set_locale();
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-payamito-activator.php
 */
if (!function_exists('activate_payamito')) {
	function activate_payamito()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-payamito-activator.php';
		Payamito_Activator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-payamito-deactivator.php
 */
if (!function_exists("deactivate_payamito")) {
	function deactivate_payamito()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-payamito-deactivator.php';
		Payamito_Deactivator::deactivate();
	}
}

register_activation_hook(__FILE__, 'activate_payamito');
register_deactivation_hook(__FILE__, 'deactivate_payamito');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-payamito.php';

/**
 * getting path from out of plugin
 */
if (!function_exists("get_path_payamito")) {
	function get_path_payamito()
	{
		return plugin_dir_path(__FILE__);
	}
}

/*
 * return  prefix  payamito
 */
if (!function_exists("get_option_prefix_payamito")) {
	function get_option_prefix_payamito()
	{
		return 'payamito';
	}
}

/**
 * Begins execution of the plugin.
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if (!function_exists("run_payamito")) {
	function run_payamito()
	{
		Payamito::get_instance()->run();
	}
}
run_payamito();
