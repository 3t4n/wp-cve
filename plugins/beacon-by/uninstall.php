<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Beacon Plugin
 * @author    Beacon
 * @license   GPL-2.0+
 * @link      https://beacon.by/wordpress
 * @copyright 2015 beacon.by
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
}

delete_option('beacon_authorized');
delete_option('widget_beacon_widget');
delete_option('beacon_promote_options');
delete_option('beacon_connected');
