<?php

/**
 * Fired during plugin activation
 *
 * @link       http://fresh-d.biz/wocommerce-remove-background.html
 * @since      1.0.0
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/includes
 * @author     Fresh-d <info@fresh-d.biz>
 */
class Remove_BG_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        update_option( 'RemoveBG_thumbnail', 1 );
        update_option( 'RemoveBG_gallery', 1 );
        update_option( 'RemoveBG_Preserve_Resize', 'auto' );
        global $wpdb;
        $sql = "CREATE TABLE `".$wpdb->prefix."wc_remove_bg` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `date_start` datetime DEFAULT NULL,
                  `date_end` datetime DEFAULT NULL,
                  `status` varchar(10) DEFAULT NULL,
                  `error_msg` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $wpdb->query($sql);
        $sql = "CREATE TABLE `".$wpdb->prefix."wc_remove_bg_backup` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `attach_id` int(11) DEFAULT NULL,
                  `old_attach_id` int(11) DEFAULT NULL,
                  `post_id` int(11) DEFAULT NULL,
                  `backup_date` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
        $wpdb->query($sql);
        
		$countCreatingTable = 0;
		
		$table_name = $wpdb->prefix.'wc_remove_bg';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			$countCreatingTable++;
		}
		$table_name = $wpdb->prefix.'wc_remove_bg_backup';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			$countCreatingTable++;
		}
		if($countCreatingTable == 2){
			return true;
		}else{
			return false;
		}
	}

}
