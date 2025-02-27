<?php
/**
 * Plugin Name: Ultimate Member - Online
 * Plugin URI: https://ultimatemember.com/extensions/online-users/
 * Description: Display online users and show the user online status on your site.
 * Version: 2.2.0
 * Author: Ultimate Member
 * Author URI: http://ultimatemember.com/
 * Text Domain: um-online
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 5.6
 * UM version: 2.7.0
 *
 * @package UM_Online
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin_data = get_plugin_data( __FILE__ );

define( 'um_online_url', plugin_dir_url( __FILE__  ) );
define( 'um_online_path', plugin_dir_path( __FILE__ ) );
define( 'um_online_plugin', plugin_basename( __FILE__ ) );
define( 'um_online_extension', $plugin_data['Name'] );
define( 'um_online_version', $plugin_data['Version'] );
define( 'um_online_textdomain', 'um-online' );
define( 'um_online_requires', '2.7.0' );


if ( ! function_exists( 'um_online_plugins_loaded' ) ) {
	/**
	 * Text-domain loading
	 */
	function um_online_plugins_loaded() {
		$locale = ( get_locale() != '' ) ? get_locale() : 'en_US';
		load_textdomain( um_online_textdomain, WP_LANG_DIR . '/plugins/' . um_online_textdomain . '-' . $locale . '.mo' );
		load_plugin_textdomain( um_online_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	add_action( 'plugins_loaded', 'um_online_plugins_loaded', 0 );
}


if ( ! function_exists( 'um_online_check_dependencies' ) ) {
	/**
	 * Check dependencies in core
	 */
	function um_online_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! file_exists( um_path  . 'includes/class-dependencies.php' ) ) {
			//UM is not installed
			function um_online_dependencies() {
				// translators: %s is the Online extension name.
				echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-online' ), um_online_extension ) . '</p></div>';
			}

			add_action( 'admin_notices', 'um_online_dependencies' );
		} else {

			if ( ! function_exists( 'UM' ) ) {
				require_once um_path . 'includes/class-dependencies.php';
				$is_um_active = um\is_um_active();
			} else {
				$is_um_active = UM()->dependencies()->ultimatemember_active_check();
			}

			if ( ! $is_um_active ) {
				//UM is not active
				function um_online_dependencies() {
					// translators: %s is the Online extension name.
					echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-online' ), um_online_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_online_dependencies' );

			} elseif ( true !== UM()->dependencies()->compare_versions( um_online_requires, um_online_version, 'online', um_online_extension ) ) {
				//UM old version is active
				function um_online_dependencies() {
					echo '<div class="error"><p>' . UM()->dependencies()->compare_versions( um_online_requires, um_online_version, 'online', um_online_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_online_dependencies' );

			} else {
				require_once um_online_path . 'includes/core/um-online-init.php';
			}
		}
	}
	add_action( 'plugins_loaded', 'um_online_check_dependencies', -20 );
}


if ( ! function_exists( 'um_online_activation_hook' ) ) {
	/**
	 * Plugin Activation
	 */
	function um_online_activation_hook() {
		//first install
		$version = get_option( 'um_online_version' );
		if ( ! $version ) {
			update_option( 'um_online_last_version_upgrade', um_online_version );
		}

		if ( $version != um_online_version ) {
			update_option( 'um_online_version', um_online_version );
		}

		//run setup
		if ( ! class_exists( 'um_ext\um_online\core\Online_Setup' ) ) {
			require_once um_online_path . 'includes/core/class-online-setup.php';
		}

		$online_setup = new um_ext\um_online\core\Online_Setup();
		$online_setup->run_setup();
	}
	register_activation_hook( um_online_plugin, 'um_online_activation_hook' );
}
