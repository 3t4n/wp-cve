<?php

/**
 * This file cleans up when the plugin is uninstalled from Wordpress
 */

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN'))
	die;

// delete all options starting with feedbackcompany_
foreach (wp_load_alloptions() as $option => $value)
{
	if (strpos($option, 'feedbackcompany_') === 0)
	{
		delete_option( $option );
		delete_site_option( $option );
	}
}

// drop our error log table
global $wpdb;
$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
$sql = "DROP TABLE IF EXISTS $table_name;";
$wpdb->query($sql);

