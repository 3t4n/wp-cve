<?php

/**
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 *
 * Plugin Name:     WP Meteor
 * Plugin URI:      https://wp-meteor.com/
 * Description:     Improves your page speed, even on top of your existing optimizations
 * Version:         3.4.0
 * Author:          Aleksandr Guidrevitch
 * Author URI:      https://wp-meteor.com/
 * Text Domain:     wp-meteor
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /languages
 * Requires PHP:    5.6
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}

define('WPMETEOR_VERSION', '3.4.0');
define('WPMETEOR_TEXTDOMAIN', 'wp-meteor');
define('WPMETEOR_NAME', 'WP Meteor');
define('WPMETEOR_PLUGIN_ROOT', plugin_dir_path(__FILE__));
define('WPMETEOR_PLUGIN_ABSOLUTE', __FILE__);

if (version_compare(PHP_VERSION, '5.6.0', '<=')) {
	add_action(
		'admin_init',
		static function () {
			deactivate_plugins(plugin_basename(__FILE__));
		}
	);
	add_action(
		'admin_notices',
		static function () {
			echo wp_kses_post(
				sprintf(
					'<div class="notice notice-error"><p>%s</p></div>',
					__('"WP Meteor" requires PHP 5.6 or newer.', WPMETEOR_TEXTDOMAIN)
				)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$wp_meteor_libraries = require_once WPMETEOR_PLUGIN_ROOT . 'vendor/autoload.php';

// ensure is_plugin_active() exists (not exists on frontend)
/*
if (!function_exists('is_plugin_active')) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
*/

require_once WPMETEOR_PLUGIN_ROOT . 'functions/functions.php';

// Add your new plugin on the wiki: https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered/wiki/Plugin-made-with-this-Boilerplate

if (!wp_installing()) {
	add_action(
		'plugins_loaded',
		static function () use ($wp_meteor_libraries) {
			new \WP_Meteor\Engine\Initialize($wp_meteor_libraries);
		}
	);
}
