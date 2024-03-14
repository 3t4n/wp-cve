<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 * @package    shortcodes-finder
 * @author     Scribit <wordpress@scribit.it>
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'shortcodes-finder-consts.php';

delete_option( SHORTCODES_FINDER_OPTION_VERSION );