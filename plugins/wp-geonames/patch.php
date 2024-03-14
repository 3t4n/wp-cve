<?php
//
// V1.6
//
	global $wpdb;
	$nom = $wpdb->base_prefix.'geonamesPostal';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		require_once(ABSPATH.'wp-admin/includes/upgrade.php'); // dbDelta()
		$charset_collate = '';
		if(!empty($wpdb->charset)) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if(!empty($wpdb->collate)) $charset_collate .= " COLLATE $wpdb->collate";
		$sql = "CREATE TABLE ".$nom." (
			`idwpgnp` bigint(20) unsigned NOT NULL auto_increment,
			`country_code` varchar(2) NOT NULL,
			`postal_code` varchar(20) NOT NULL,
			`place_name` varchar(180) NOT NULL,
			`admin1_name` varchar(100) NOT NULL,
			`admin1_code` varchar(20) NOT NULL,
			`admin2_name` varchar(100) NOT NULL,
			`admin2_code` varchar(20) NOT NULL,
			`admin3_name` varchar(100) NOT NULL,
			`admin3_code` varchar(20) NOT NULL,
			`latitude` decimal(10,5) NOT NULL,
			`longitude` decimal(10,5) NOT NULL,
			`accuracy` tinyint(1) unsigned NOT NULL,
			PRIMARY KEY (`idwpgnp`),
			INDEX `index1` (`country_code`,`postal_code`,`place_name`(3))
			) $charset_collate;";
		dbDelta($sql);
	}

//
// END PATCH - PATCH OFF
//
@copy(dirname(__FILE__).'/patch.php', dirname(__FILE__).'/patch_off.php');
@unlink(dirname(__FILE__).'/patch.php');
?>
