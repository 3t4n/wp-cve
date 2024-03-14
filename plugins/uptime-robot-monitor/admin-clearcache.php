<?php defined('ABSPATH') or die("No script kiddies please!");

	global $wpdb;
	$table_name = $wpdb->base_prefix . 'urpro';
	$data = $wpdb->get_results ( "SELECT * FROM $table_name WHERE ur_key = 'refresh' ORDER BY ur_value DESC LIMIT 1");

		$expired = time() - $data[0]->ur_value; echo "EXPIRED - ".$expired;
		$sql = "DELETE FROM ".$table_name." WHERE time < ".$expired." AND ur_key LIKE 'cache-%'";
		$wpdb->query($sql);