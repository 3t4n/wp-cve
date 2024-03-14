<?php defined('ABSPATH') or die("No script kiddies please!");

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->base_prefix . 'urpro';
 	$sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);