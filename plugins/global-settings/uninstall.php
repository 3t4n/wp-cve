<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function gbs_delete_plugin() {
	global $wpdb;

	$table_name = $wpdb->prefix . "options";

	$wpdb->query( "DELETE FROM $table_name WHERE option_name LIKE 'gbs_%'" );
}

gbs_delete_plugin();

?>