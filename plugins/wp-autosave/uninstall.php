<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       wpautosave@gmail.com
 * @since      1.0.0
 *
 * @package    Wp_Autosave
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
