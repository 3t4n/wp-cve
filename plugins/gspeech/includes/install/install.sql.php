<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");


// global $wpcfg_db_version;
global $sh_;

$plg_v = PLG_VERSION;
$new_db_version = NEW_DB_VER;

require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

$query = "SHOW TABLES LIKE '".$wpdb->prefix."gspeech_data'";
$wpdb->get_results($query);
$num_r = $wpdb->num_rows;

if($num_r == 0) {

	$sql =
	        "
	          CREATE TABLE `".$wpdb->prefix."gspeech_data` (
	          `widget_id` text NOT NULL,
			  `lazy_load` tinyint(3) UNSIGNED NOT NULL,
			  `crypto` text NOT NULL,
			  `reload_session` tinyint(3) UNSIGNED NOT NULL,
			  `plugin_version` text NOT NULL,
			  `version_index` mediumint(8) UNSIGNED NOT NULL,
			  `email` text NOT NULL,
			  `sh_` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
			  `sh_w_loaded` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
	        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	        ";
	dbDelta($sql);

	$sql = "INSERT IGNORE INTO `".$wpdb->prefix."gspeech_data` (`widget_id`, `lazy_load`, `crypto`, `reload_session`, `plugin_version`, `version_index`, `email`, `sh_`, `sh_w_loaded`) VALUES('', 1, '', 0, '".$plg_v."', 0, '', 0, 0)";
	$wpdb->query($sql);
}
else {

	// add email, sh_ and  sh_w_loaded fields
	$query = "SHOW COLUMNS FROM `".$wpdb->prefix."gspeech_data` LIKE 'email'";
	$rows = $wpdb->get_results($query);

	if(sizeof($rows) == 0) {

	    $sql = "ALTER TABLE `".$wpdb->prefix."gspeech_data` ";
	    $sql .= "ADD `email` TEXT NOT NULL AFTER `version_index`, ";
	    $sql .= "ADD `sh_` tinyint(3) UNSIGNED NOT NULL  DEFAULT '0' AFTER `email`, ";
	    $sql .= "ADD `sh_w_loaded` tinyint(3) UNSIGNED NOT NULL  DEFAULT '0' AFTER `sh_`;";

	    $wpdb->query($sql);
	}

	$sql = "UPDATE `".$wpdb->prefix."gspeech_data` SET `plugin_version` = '".$plg_v."', `version_index` = `version_index` + 1";
	$wpdb->query($sql);
}

?>