<?php
/**
 * Uninstall plugin.
 *
 * @package hreflang-manager-lite
 */

// Exit if this file is called outside WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die(); }

require_once plugin_dir_path( __FILE__ ) . 'shared/class-daexthrmal-shared.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-daexthrmal-admin.php';

// Delete options and tables.
Daexthrmal_Admin::un_delete();
