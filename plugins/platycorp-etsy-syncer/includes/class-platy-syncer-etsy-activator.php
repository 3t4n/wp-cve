<?php

/**
 * Fired during plugin activation
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		Platy_Syncer_Etsy_Activator::doDbDelta();
		
		// need to add weekly schedule for wp lower than 5.4
		add_filter( 'cron_schedules', function ( $schedules ) {
			if(!isset($schedules['weekly'])) {
				$schedules['weekly'] = array(
					'interval' => 604800,
					'display' => __('Once Weekly')
				);
			}
			return $schedules;
		} ); 
		wp_clear_scheduled_hook(  'platy_etsy_clean_logs' );
		wp_schedule_event( time(), 'weekly', "platy_etsy_clean_logs");
	}

	
	public static function update($current_version) {
		
		if(version_compare($current_version, "3.0.0") >= 0) {
			Platy_Syncer_Etsy_Activator::doDbDelta();
			Platy_Syncer_Etsy_Activator::fix_bad_default_shop_option();
		}

		update_option( "platy_syncer_etsy_version", $current_version, false );
	}

	public static function doDbDelta() {
		global  $wpdb;
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();
		$tblname = Platy_Syncer_Etsy::SHOP_TABLE_NAME;
		$table_prefix = $wpdb->prefix;
		$full_table_name = $table_prefix . "$tblname";

		$sql = "CREATE TABLE `". $full_table_name . "` ( ";
		$sql .= "  `id`  varchar(30)   NOT NULL,\n ";
		$sql .= "  `name`  varchar(255)   NOT NULL,\n ";
		$sql .= "  `user_id`  varchar(30)   NOT NULL,\n ";
		$sql .= "  `identifier`  varchar(255)   NOT NULL,\n ";
		$sql .= "  `secret`  varchar(255)   NOT NULL,\n ";
		$sql .= "  `oauth2_token`  varchar(255)   NOT NULL,\n ";
		$sql .= "  `oauth2_refresh_token`  varchar(255)   NOT NULL,\n ";
		$sql .= "  `last_ouath2_use`  BIGINT UNSIGNED,\n ";
		$sql .= "  `def_shipping_template`  varchar(30)   NOT NULL,\n ";
		$sql .= "  `def_taxonomy` varchar(30)   NOT NULL,\n ";
		$sql .= "  PRIMARY KEY  (`id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);
		

		$tblname = Platy_Syncer_Etsy::TEMPLATES_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it
		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `shop_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `name`  varchar(50) NOT NULL, \n";
		$sql .= "  PRIMARY KEY  (`id`)\n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

		$tblname = Platy_Syncer_Etsy::TEMPLATES_META_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `template_id`  varchar(30) NOT NULL,\n";
		$sql .= "  `meta_name`  varchar(255) NOT NULL,\n";
		$sql .= "  `meta_value` TEXT(60000),\n";
		$sql .= "  PRIMARY KEY  (`id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

		$tblname = Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `post_id`  int(20) NOT NULL, \n";
		$sql .= "  `shop_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `etsy_id`  varchar(30), \n";
		$sql .= "  `status`  BIT NOT NULL, \n ";
		$sql .= "  `error`  varchar(255), \n";
		$sql .= "  `parent_id`  varchar(20) DEFAULT 0, \n";
		$sql .= "  `type`  varchar(255) DEFAULT 'legacy', \n";
		$sql .= "  PRIMARY KEY  (`id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

		$tblname = Platy_Syncer_Etsy::PRODUCT_META_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `post_id`  int(20) NOT NULL, \n";
		$sql .= "  `shop_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `meta_key`  varchar(100), \n";
		$sql .= "  `meta_value`  TEXT(60000) \n ";
		// $sql .= "  PRIMARY KEY  (`post_id`, `shop_id`)\n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

		$tblname = Platy_Syncer_Etsy::PRODUCT_ATTRIBUTES_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `shop_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `enabled`  BIT NOT NULL, \n ";
		$sql .= "  `tax_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `tax_name`  varchar(50), \n";
		$sql .= "  `property_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `display_name`  varchar(50), \n";
		$sql .= "  `scale_id`  varchar(30), \n";
		$sql .= "  `value_ids` TEXT(60000) NOT NULL, \n";
		$sql .= "  `values`  TEXT(60000), \n";
		$sql .= "  PRIMARY KEY  (`id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

		$tblname = Platy_Syncer_Etsy::CONNECTIONS_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `connection_id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `shop_id`  varchar(30) NOT NULL, \n";
		$sql .= "  `source_type`  varchar(255) NOT NULL, \n";
		$sql .= "  `target_type`  varchar(255) NOT NULL, \n";
		$sql .= "  `source_id`  varchar(30), \n";
		$sql .= "  `target_id`  varchar(30), \n";
		$sql .= "  `target_name`  varchar(255) NOT NULL, \n";
		$sql .= "  PRIMARY KEY  (`connection_id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);


		$tblname = Platy_Syncer_Etsy::OPTIONS_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `shop_id`  varchar(30), \n";
		$sql .= "  `option_name`  varchar(255) NOT NULL, \n";
		$sql .= "  `option_value`  TEXT(60000) NOT NULL, \n";
		$sql .= "  `group`  varchar(255)\n" ;
		$sql .= ") $charset_collate;";
		dbDelta($sql);
		
		$tblname = Platy_Syncer_Etsy::LOG_TABLE_NAME;
		$full_table_name = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		$sql = "CREATE TABLE `". $full_table_name . "` ( \n";
		$sql .= "  `id`  int(20) NOT NULL AUTO_INCREMENT, \n";
		$sql .= "  `post_id`  int(20), \n";
		$sql .= "  `shop_id`  varchar(30), \n";
		$sql .= "  `etsy_id`  varchar(30), \n";
		$sql .= "  `message`  TEXT(60000) NOT NULL, \n";
		$sql .= "  `status`  int(5) NOT NULL, \n ";
		$sql .= "  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, \n ";	
		$sql .= "  `type`  varchar(255), \n";
		$sql .= "  PRIMARY KEY  (`id`) \n"; 
		$sql .= ") $charset_collate;";
		dbDelta($sql);

	}

	/**
	 * this changes the default shop option from the user id to the shop id
	 *
	 * @return void
	 */
	private static function fix_bad_default_shop_option() {
		global $table_prefix, $wpdb;

		$user_id = get_option( "platy_etsy_default_etsy_shop", "" );
		if(!empty($user_id)){
            $shop_tbl = Platy_Syncer_Etsy::SHOP_TABLE_NAME;
            $shops = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$shop_tbl WHERE user_id=$user_id", ARRAY_A);
            if(!empty($shops)) {
                update_option( "platy_etsy_default_etsy_shop", $shops[0]['id']);
            }
        }
	}
}
