<?php
/*
Plugin Name: Check Conflicts
Plugin URI: https://wordpress.org/plugins/check-conflicts/
Description: The plugin allows you to disable/enable plugins and/or activate a default theme for <a href="https://wpmudev.com/blog/wordpress-plugin-conflicts-how-to-check-for-them-and-what-to-do/" target="_blank">checking conflict</a> between them <strong>only for your IP</strong>; other users won't see any changes during the tests.
Version: 1.1.6
Author: ioannup
Author URI: https://www.upwork.com/freelancers/~0165d3dc4b2ffbbd7d
License: GPLv2
Requires at least: 5.0
Tested up to: 6.4
*/

if (  class_exists( 'MUCheckConflicts' ) ) {
	register_deactivation_hook( __FILE__, [ 'MUCheckConflicts', 'deactivation' ] );
}

// Show this page only if MU plugin doesn't installed, otherwise the MU plugin shows all plugin settings
if ( ! class_exists( 'MUCheckConflicts' ) && ! class_exists( 'CheckConflicts' ) ) {
	class CheckConflicts {

		public static function init() {
			add_action( 'admin_menu', [ self::class, 'add_menu' ] );
			add_filter( 'plugin_action_links', array( __CLASS__, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'network_admin_plugin_action_links', array( __CLASS__, 'add_plugin_action_links' ), 10, 2 );
		}

		public static function add_menu() {
			add_menu_page( 'Check Conflicts',  __( 'Check Conflicts' ), 'manage_options', 'check_conflicts_settings', [ self::class, 'render_main_page' ], 'dashicons-plugins-checked', 65 );
		}

		private static function handle_post() {
			$page = filter_input( INPUT_GET, 'page' );
			if ( 'check_conflicts_settings' !== $page ) {
				return;
			}
			$install = filter_input( INPUT_POST, 'install_mu_plugin' );
			if ( !is_null( $install ) ) {
				self::install_mu_plugin();
			}

		}

		private static function install_mu_plugin() {
			if ( ! check_admin_referer( 'install MU-plugin' ) ) {
				return;
			}
			global $wp_filesystem;
			$source = dirname( __FILE__ ) . '/mu-plugins/mu-check-conflicts.php';
			$destination = WP_CONTENT_DIR . '/mu-plugins/';

			if ( WP_Filesystem( request_filesystem_credentials( 'admin.php?page=check_conflicts_settings', 'direct', false, WP_CONTENT_DIR, ['data'] ) ) ) {

				if ( ! $wp_filesystem->is_dir( $destination ) ) {
					$wp_filesystem->mkdir( $destination, FS_CHMOD_DIR );
				}
				$wp_filesystem->copy( $source, $destination . 'mu-check-conflicts.php', true, FS_CHMOD_FILE );

				echo '<script type="text/javascript">window.location.reload();</script>';
				exit;
			}
		}

		/**
		 * Render main page for settings
		 */
		public static function render_main_page() {
			self::handle_post();
			require_once dirname( __FILE__ ) . '/views/move_to_mu.php';
		}


		/**
		 * Plugin action links
		 *
		 * @param array  $actions Plugin actions.
		 * @param string $plugin_file Plugin file.
		 * @return array
		 */
		public static function add_plugin_action_links( $actions, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) === $plugin_file ) {
				$admin_url = admin_url( 'admin.php' );
				if ( ! empty( $admin_url ) ) {
					$settings_url      = add_query_arg( 'page', 'check_conflicts_settings', $admin_url );
					$links['settings'] = '<a href="' . $settings_url . '">' . __( 'Settings', 'ub' ) . '</a>';
				}
				$actions = array_merge( $links, $actions );
			}

			return $actions;
		}
	}

	CheckConflicts::init();
}
