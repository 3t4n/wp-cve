<?php
/**
 * Plugin Name: WebTotem Security
 * Description: The <a href="https://wtotem.com/" target="_blank">WebTotem</a> Security plugin monitors websites and prevents website attacks with the help of special internal and external utilities.
 * Author URI: https://wtotem.com/
 * Author: WebTotem
 * Text Domain: wtotem
 * Domain Path: /lang
 * Version: 2.4.24
 *
 * PHP version 7.1
 *
 * @copyright  2021-2022 WebTotem
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL2
 * @link       https://wordpress.org/plugins/wt-security
 */

/**
 * Main file to control the plugin.
 */
define('WEBTOTEM_INIT', true);

/**
 * Plugin dependencies.
 *
 * list of required WordPress functions for the plugin to work.
 */
$wtotem_dependencies = array(
	'wp',
	'wp_die',
	'add_action',
	'remove_action',
	'wp_remote_get',
	'wp_remote_post',
);

// Stopping execution if dependencies are not met.
foreach ($wtotem_dependencies as $dependency) {
	if (!function_exists($dependency)) {
		// Report invalid access.
		header('HTTP/1.1 403 Forbidden');
		die("Protected By WebTotem!");
	}
}

// Stopping execution if the ABSPATH constant is not available
if (!defined('ABSPATH')) {
	// Report invalid access.
	header('HTTP/1.1 403 Forbidden');
	die("Protected By WebTotem!");
}

/**
 * Current version of the plugin's code.
 */
define('WEBTOTEM_VERSION', '2.4.24');

/**
 * The name of the folder where the plugin's files will be located.
 */
define("WEBTOTEM_PLUGIN_FOLDER", basename(dirname(__FILE__)));

/**
 * The fullpath where the plugin's files will be located.
 */
define('WEBTOTEM_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . WEBTOTEM_PLUGIN_FOLDER);

/**
 * The local URL where the plugin's files and assets are served.
 */
define('WEBTOTEM_URL', rtrim(plugin_dir_url(__FILE__), '/'));

/**
 * The domain name of the current site, without protocol and www.
 */
define("WEBTOTEM_SITE_DOMAIN", str_replace(['http://', 'https://', '//', '://', 'www.'], '', get_site_url()));

/**
 * Unique name of the plugin through out all the code.
 */
define("WEBTOTEM", 'wtotem');

/* Load plugin translations */
function wtotem_load_plugin_textdomain() {
	load_plugin_textdomain('wtotem', false, basename(dirname(__FILE__)) . '/lang/');
}
add_action('plugins_loaded', 'wtotem_load_plugin_textdomain');


/* Load all classes before anything else. */
require_once 'lib/Helper.php';
require_once 'lib/API.php';
require_once 'lib/DB.php';
require_once 'lib/Cache.php';
require_once 'lib/modules/login/Login.php';
require_once 'lib/modules/logs/EventListener.php';
require_once 'lib/modules/logs/Scan.php';
require_once 'lib/modules/logs/Crawler.php';
require_once 'lib/Request.php';
require_once 'lib/Interface.php';
require_once 'lib/AgentManager.php';
require_once 'lib/Option.php';
require_once 'lib/Template.php';
require_once 'lib/Country.php';
require_once 'lib/Ajax.php';

/* Load page and ajax handlers */
require_once 'src/PageHandler.php';

/* Load common variables and triggers */
require_once 'src/Common.php';

/**
 * Uninstalled the plugin
 *
 * @return void
 */
function wtotemUninstall() {

	if (WebTotemOption::getPluginSettings('hide_wp_version')) {
			WebTotemOption::restoreReadme();
	}

	if(WebTotem::isMultiSite()){
		WebTotemOption::clearAllHosts();
	}

	/* Delete settings from the database */
	WebTotemDB::uninstall();

}

register_uninstall_hook(__FILE__, 'wtotemUninstall');

/**
 * Deactivation plugin
 *
 * @return void
 */
function wtotemDeactivation() {
    if (WebTotemOption::getPluginSettings('hide_wp_version')) {
        WebTotemOption::restoreReadme();
    }
}
register_deactivation_hook( __FILE__, 'wtotemDeactivation' );

/**
 * Deactivation plugin
 *
 * @return void
 */
function wtotemActivation() {
    WebTotemDB::install();
    if (WebTotemOption::getPluginSettings('hide_wp_version')) {
        WebTotemOption::hideReadme();
    }
}

register_activation_hook( __FILE__, 'wtotemActivation' );
