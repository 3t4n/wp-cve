<?php

/**
 * Fired during plugin activation
 *
 * @link       http://multidots.com
 * @since      1.0.0
 *
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes
 */
global $wpdb;
$table_name = $wpdb->prefix . "menu_manager_plus";
$sql_table = "CREATE TABLE IF NOT EXISTS $table_name(id int(11) NOT NULL AUTO_INCREMENT,menuid int(11) NOT NULL,menu_item_data longtext,created datetime NOT NULL,PRIMARY KEY (id)) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta($sql_table); //phpcs:ignore

// AMM add page pagination option 

update_option('amm_post_perpage_option', '10,50,100,200,500,700,1000');
update_option('amm_post_perpage_default', 10);
