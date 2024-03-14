<?php

global $wpdb;
$eli_db_version = '1.0.0';
$charset_collate = $wpdb->get_charset_collate();
/* first db install */
if ( get_site_option( 'eli_db_version' ) === false ||
     get_site_option( 'eli_db_version' ) < '1.0.0' ) {
        
    $table_name = $wpdb->prefix . "eli_newsletters";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
                `id` MEDIUMINT NOT NULL AUTO_INCREMENT ,
                `form_id` VARCHAR(64) NULL DEFAULT NULL ,
                `email` VARCHAR(64) NULL DEFAULT NULL ,
                `json_object` TEXT NULL DEFAULT NULL , 
                `date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $charset_collate COMMENT='ElementInvader Addons for Elementor';";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    /* set next version */
    $eli_db_version = '1.0.4';
    update_option('eli_db_version', $eli_db_version);
}

/* version 1.1.0 db install */
if ( get_site_option( 'eli_db_version' ) < '1.1.0' ) {
    $table_name = $wpdb->prefix . "eli_newsletters";
    $sql = "ALTER TABLE `$table_name` ADD `website` VARCHAR(128) NULL DEFAULT NULL AFTER `date`;";
	$wpdb->query($sql);
    
    /* set next version */
    $eli_db_version = '1.1.0';
    /* udpate option with db version */
    update_option('eli_db_version', $eli_db_version);
}