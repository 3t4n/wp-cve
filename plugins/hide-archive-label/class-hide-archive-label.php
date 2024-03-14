<?php

/**
 * Plugin Name: Hide Archive Label
 * Description: Hide Archive Label is a free WordPress plugin to quickly hide or remove archive page title prefixes on your site such as “Category:”, “Tags:”, “Author:”, and more. A clean archive page titles in just a few seconds!
 * Plugin URI: https://catchplugins.com/hide-archive-label
 * Author: Catch Plugins
 * Author URI: https://catchplugins.com
 * Version: 1.5.2
 * License: GPL2
 * Text Domain: hide-archive-label
 * Domain Path: domain/path
 */

/*
	Copyright (C) 2020 Catch Plugins info@catchplugins.com

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

/**
 * Snippet WordPress Plugin Boilerplate based on:
 *
 * - https://github.com/purplefish32/sublime-text-2-wordpress/blob/master/Snippets/Plugin_Head.sublime-snippet
 * - http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to/25979#25979
 *
 * By default the option to uninstall the plugin is disabled,
 * to use uncomment or remove if not used.
 *
 * This Template does not have the necessary code for use in multisite.
 *
 * Also delete this comment block is unnecessary once you have read.
 *
 * Version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hide_Archive_Label
 */
class Hide_Archive_Label {
	/** Constructor */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.3.2.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		} else {
			$this->set_constants();
			require_once HAL_PATH . 'inc/class-main.php';
			HAL\Main::get_instance();
		}
	}

	/** Set Plugin constants */
	public function set_constants() {
		if ( ! defined( 'HAL_URL' ) ) {
			define( 'HAL_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'HAL_PATH' ) ) {
			define( 'HAL_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'HAL_BASENAME' ) ) {
			define( 'HAL_BASENAME', plugin_basename( __FILE__ ) );
		}
		if ( ! defined( 'ARCHIVE_TITLE_CSS_A11Y' ) ) {
			define( 'ARCHIVE_TITLE_CSS_A11Y', 'screen-reader-text' );
		}
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}

	/** Set Plugin version constant */
	public function set_plugin_version_constant() {
		if ( ! defined( 'HAL_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'HAL_VERSION', $plugin_data['Version'] );
		}
		if ( ! defined( 'HAL_PLUGIN_NAME' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'HAL_PLUGIN_NAME', str_replace( ' ', '-', strtolower( $plugin_data['Name'] ) ) );
		}
	}
}

$hide_archive_label = new Hide_Archive_Label();
