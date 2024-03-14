<?php
/**
 * @package myRepono
 * @version 2.0.12
 */
/*
Plugin Name: myRepono Backup Plugin
Plugin URI: http://myrepono.com/wordpress-backup-plugin/
Description: Automate your WordPress, website &amp; database backups using the <a href="http://myRepono.com/wordpress-backup-plugin/">myRepono remote website backup service</a>.  To get started: 1) Click the 'Activate' link to the left of this description, 2) Go to the 'myRepono' link shown in the menu on the left of the page.
Author: myRepono (ionix Limited)
Author URI: http://myRepono.com/
License: GPLv2
Version: 2.0.12
*/
/*
Copyright 2016 ionix Limited (email: support@myRepono.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ((function_exists('add_action')) && (function_exists('add_filter')) && (function_exists('is_admin')) && (is_admin())) {

	add_action('init', 'myrepono_plugin');

}


function myrepono_plugin() {

	if ((function_exists('wp_get_current_user')) && (current_user_can('manage_options'))) {

		define('WP_MYREPONO_PLUGIN', '2.0.12');

		require_once 'myrepono_plugin_begin.php';

		add_action('admin_notices', 'myrepono_plugin_admin_notices');

		add_action('admin_menu', 'myrepono_plugin_menu');

		add_action('admin_head', 'myrepono_plugin_head');

		add_action('admin_enqueue_scripts', 'myrepono_plugin_styles');

		add_action('wp_ajax_myrepono_plugin_home_queue', 'myrepono_plugin_home_queue_ajax');

		if (myrepono_wordpress_version()>3.2) {

			add_filter('contextual_help', 'myrepono_plugin_options_help', 10, 3);

			add_filter('set-screen-option', 'myrepono_plugin_screen_option', 10, 3);

		} else {

			add_action('admin_menu', 'myrepono_plugin_menu_help');

		}

		add_action('admin_notices', 'myrepono_plugin_status');

		add_action('admin_init', 'myrepono_plugin_admin_notices_ignore');

	}
}


?>
