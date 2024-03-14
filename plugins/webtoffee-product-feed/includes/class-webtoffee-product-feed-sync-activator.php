<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
 * @author     WebToffee <info@webtoffee.com>
 */
class Webtoffee_Product_Feed_Sync_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
public static function activate() 
	{
		global $wpdb;
		delete_option('wt_pf_is_active'); /* remove if exists */

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );       
        if(is_multisite()) 
        {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach($blog_ids as $blog_id ) 
            {
                switch_to_blog( $blog_id );
                self::install_tables();
                restore_current_blog();
            }
        }
        else 
        {
            self::install_tables();
        }

        add_option('wt_pf_is_active', 1);
	}

	public static function install_tables()
	{
		global $wpdb;
		$charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}
		//install necessary tables
		

        //creating table for saving export/import history================
        $search_query = "SHOW TABLES LIKE %s";
        $tb='wt_pf_action_history';
        $like = '%'.$wpdb->prefix.$tb.'%';
        $table_name = $wpdb->prefix.$tb;
        if(!$wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N)) 
        {
            $sql_settings = "CREATE TABLE IF NOT EXISTS `$table_name` (
				`id` INT NOT NULL AUTO_INCREMENT, 
				`template_type` VARCHAR(255) NOT NULL, 
				`item_type` VARCHAR(255) NOT NULL,
				`file_name` VARCHAR(255) NOT NULL, 
				`created_at` INT NOT NULL DEFAULT '0',
				`updated_at` INT NOT NULL DEFAULT '0',
				`status` INT NOT NULL DEFAULT '0', 
				`status_text` VARCHAR(255) NOT NULL,
				`offset` INT NOT NULL DEFAULT '0', 
				`total` INT NOT NULL DEFAULT '0', 
				`data` LONGTEXT NOT NULL, 
				PRIMARY KEY (`id`)
			) $charset_collate;";
            dbDelta($sql_settings);
        }
        //creating table for saving export/import history================

		//creating table for saving cron data================
        $search_query = "SHOW TABLES LIKE %s";
        $tb='wt_pf_cron';
        $like = '%'.$wpdb->prefix.$tb.'%';
        $table_name = $wpdb->prefix.$tb;
        if(!$wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N)) 
        {
            $sql_settings = "CREATE TABLE IF NOT EXISTS `$table_name` (
				`id` INT NOT NULL AUTO_INCREMENT, 
				`status` INT NOT NULL DEFAULT '0',
				`old_status` INT NOT NULL DEFAULT '0',
				`action_type` VARCHAR(255) NOT NULL, 
				`schedule_type` VARCHAR(50) NOT NULL, 
				`item_type` VARCHAR(255) NOT NULL, 
				`data` LONGTEXT NOT NULL, 
				`start_time` INT NOT NULL, 
				`cron_data` TEXT NOT NULL,
				`last_run` INT NOT NULL,
				`next_offset` INT NOT NULL DEFAULT '0',
				`history_id_list` TEXT NOT NULL, 
				`history_id` INT NOT NULL, 
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;";
            dbDelta($sql_settings);
        }
        //creating table for saving cron data================
		
        
		
        //creating table for saving facebook sync log data================

        $fblog_tb='wt_pf_fbsync_log';
        $like = '%'.$wpdb->prefix.$fblog_tb.'%';
        $table_name = $wpdb->prefix.$fblog_tb;
        if(!$wpdb->get_results($wpdb->prepare("SHOW TABLES LIKE %s", $like), ARRAY_N)) 
        {
            $sql_settings = "CREATE TABLE IF NOT EXISTS `$table_name` (
				`id` INT NOT NULL AUTO_INCREMENT, 
				`data` LONGTEXT NOT NULL, 
				`start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`catalog_id` TEXT NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;";
            dbDelta($sql_settings);
        }
        //creating table for saving cron data================        
        
		
	}

}
