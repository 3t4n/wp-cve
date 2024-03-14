<?php

namespace NativeRent;

use NativeRent\Admin\Cache_Actions;

use function defined;
use function register_activation_hook;
use function register_deactivation_hook;
use function register_uninstall_hook;

use const NATIVERENT_PLUGIN_FILE;

defined( 'ABSPATH' ) || exit;

/**
 * Nativerent Install
 */
class Install {
	/**
	 * Init
	 */
	public static function init() {
		register_activation_hook( NATIVERENT_PLUGIN_FILE, array( __CLASS__, 'activate_plugin' ) );
		register_deactivation_hook( NATIVERENT_PLUGIN_FILE, array( __CLASS__, 'deactivate_plugin' ) );
		register_uninstall_hook( NATIVERENT_PLUGIN_FILE, array( __CLASS__, 'uninstall_plugin' ) );

		Migrations::init();
		Cron_Jobs::init();
	}

	/**
	 * Plugin event activation hook
	 *
	 * @return void
	 */
	public static function activate_plugin() {
		// just another reactivation.
		if ( Options::authenticated() ) {
			Cache_Actions::need_to_clear_cache();
			API::status( API::ACTIVATED_PLUGIN_STATUS );
			API::send_state();
		}
	}

	/**
	 * Plugin event deactivation hook
	 *
	 * @return void
	 */
	public static function deactivate_plugin() {
		try {
			if ( Options::authenticated() ) {
				API::status( API::DEACTIVATED_PLUGIN_STATUS );
			}
		} finally {
			Cron_Jobs::unregister();
		}
	}

	/**
	 * Plugin event uninstall hook
	 *
	 * @return void
	 */
	public static function uninstall_plugin() {
		try {
			if ( Options::authenticated() ) {
				API::status( API::UNINSTALLED_PLUGIN_STATUS );
			}
		} finally {
			Cron_Jobs::unregister();
			Options::uninstall();
		}
	}

	/**
	 * Purge action after logout.
	 *
	 * @return void
	 */
	public static function purge_plugin() {
		self::deactivate_plugin();
		Options::uninstall();
		Cache_Actions::need_to_clear_cache( '3' );
	}
}
