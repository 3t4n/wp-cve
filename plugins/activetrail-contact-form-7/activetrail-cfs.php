<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://activetrail.com
 * @since             1.0.0
 * @package           Activetrail_Cf7
 *
 * @wordpress-plugin
 * Plugin Name:       ActiveTrail - Contact Form 7
 * Plugin URI:        http://github.com/activetrail
 * Description:       Integrate Contact Form 7 with ActiveTrail. Automatically add form submissions to predetermined lists in ActiveTrail, using its latest API.
 * Version:           1.1.5
 * Author:            ActiveTrail
 * Author URI:        http://activetrail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       activetrail-cf7
 */

/**
 * ActiveTrail - Contact Form 7 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * ActiveTrail - Contact Form 7 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ActiveTrail - Contact Form 7. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activetrail-cfs-activator.php
 */
function activate_activetrail_cf7() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-activetrail-cf7-activator.php';
	Activetrail_Cf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-activetrail-cfs-deactivator.php
 */
function deactivate_activetrail_cf7() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-activetrail-cf7-deactivator.php';
	Activetrail_Cf7_Deactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_activetrail_cf7');
register_activation_hook(__FILE__, 'activate_activetrail_cf7');

/**
 * The core plugin class that is used to define
 * admin-specific hooks
 */
require plugin_dir_path(__FILE__) . 'includes/class-activetrail-cf7.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_activetrail_cf7() {
	$plugin = new Activetrail_Cf7();
	$plugin->run();
}
run_activetrail_cf7();


function activetrail_cf7_dependancy_check() {
    if (!file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php')) {
		$af_cf7_error = '<div id="message" class="error is-dismissible"><p>';
		$af_cf7_error .= __('The Contact Form 7 plugin must be installed for the <b>ActiveTrail Extension</b> to work. <b><a href="' . admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins&TB_iframe=true&width=600&height=550') . '" class="thickbox" title="Contact Form 7">Install Contact Form 7  Now.</a></b>', 'mce_error');
		$af_cf7_error .= '</p></div>';
		echo $af_cf7_error;
	} else if (!class_exists('WPCF7')) {
		$af_cf7_error = '<div id="message" class="error is-dismissible"><p>';
		$af_cf7_error .= __('The Contact Form 7 Plugin is installed, but <strong>you must activate Contact Form 7</strong> below for the <b>ActiveTrail Extension</b> to work.', 'mce_error');
		$af_cf7_error .= '</p></div>';
		echo $af_cf7_error;
	}
}

add_action('admin_notices', 'activetrail_cf7_dependancy_check');