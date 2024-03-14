<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Backend;

use WP_Meteor\Engine\Base;

/**
 * Create the settings page in the backend
 */
class SettingsPage extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Add the options page and menu item.
		\add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		$realpath = \realpath( \dirname( __FILE__ ) );
		\assert( \is_string( $realpath ) );
		$plugin_basename = \plugin_basename( \plugin_dir_path( $realpath ) . WPMETEOR_TEXTDOMAIN . '.php' );
		\add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the main menu
		 *
		 */
		//\add_menu_page( \__( 'WP Meteor Settings', WPMETEOR_TEXTDOMAIN ), WPMETEOR_NAME, 'manage_options', WPMETEOR_TEXTDOMAIN, array( $this, 'display_plugin_admin_page' ), 'dashicons-hammer', 90 );
		add_options_page( __( 'WP Meteor', WPMETEOR_TEXTDOMAIN ), WPMETEOR_NAME, 'manage_options', WPMETEOR_TEXTDOMAIN, array( $this, 'display_plugin_admin_page' ) );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function display_plugin_admin_page() {
		include_once WPMETEOR_PLUGIN_ROOT . 'backend/views/admin.php';
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 * @param array $links Array of links.
	 * @return array
	 */
	public function add_action_links( array $links ) {
		return \array_merge(
			array(
				'settings' => '<a href="' . \admin_url( 'options-general.php?page=' . WPMETEOR_TEXTDOMAIN ) . '">' . \__( 'Settings', WPMETEOR_TEXTDOMAIN ) . '</a>',
			),
			$links
		);
	}

}
