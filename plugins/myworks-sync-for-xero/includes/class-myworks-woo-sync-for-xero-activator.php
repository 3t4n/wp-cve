<?php

/**
 * Fired during plugin activation
 *
 * @link       https://myworks.software
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 * @author     MyWorks Software <support@myworks.design>
 */
class MyWorks_WC_Xero_Sync_Activator {
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$is_activate_plugin = true;
		$activation_error_msg = '';
		
		if(!Self::is_woocommerce_active()){
			$is_activate_plugin = false;
			$activation_error_msg = 'This plugin requires <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> plugin to be active!';
		}
		
		if(!$is_activate_plugin){
			die($activation_error_msg);
		}
		
		Self::create_plugin_database_tables();
		Self::add_plugin_default_setting_options();
		Self::do_after_activation();
	}
	
	protected static function is_woocommerce_active(){
		#Multisite
		if(is_multisite()){
			if(class_exists('WooCommerce')){
				return true;
			}
			
			if(!function_exists('is_plugin_active_for_network')){
				require_once( ABSPATH . '/wp-admin/includes/plugin.php');
			}
			
			if (is_plugin_active_for_network('woocommerce/woocommerce.php')){
				return true;
			}
			
			return false;
		}else{
			$active_plugins = (array) apply_filters('active_plugins', get_option('active_plugins' ));
			if(class_exists('WooCommerce') && in_array('woocommerce/woocommerce.php', $active_plugins)){
				return true;
			}
			
			return false;
		}
	}
	
	# Tasks after activation
	protected static function do_after_activation(){
		# -> Post and Email Removed
	}
	
	# Setting options
	protected static function add_plugin_default_setting_options(){
		$sop = 'mw_wc_xero_sync_';
		$pa_op_ecav_arr = array(
			$sop.'save_log_for_days' => 30,
			$sop.'enable_select2_dd' => 'true',
		);
		
		foreach($pa_op_ecav_arr as $k => $v){
			$eov = get_option($k);
			if(empty($eov) || !$eov){
				update_option($k,$v,false);
			}
		}
	}
	
	# Database tables
	protected static function create_plugin_database_tables(){
		global $wpdb;
		$dtp = MW_WC_XERO_SYNC_PLUGIN_DB_TABLE_PREFIX;
		if(empty($dtp)){
			return;
		}
		
		$sql = array();
		
		/*Plugin DB Tables*/
		
		#Xero Customer
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}customers (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`ContactID` varchar(36) NOT NULL UNIQUE,
			`FirstName` varchar(128) NOT NULL,
			`LastName` varchar(128) NOT NULL,
			`Name` varchar(255) NOT NULL,
			`EmailAddress` varchar(255) NOT NULL,
			`CompanyNumber` varchar(255) NOT NULL,
			`AccountNumber` varchar(255) NOT NULL,
			`Mobile` varchar(13) NOT NULL,
			`DefaultCurrency` varchar(3) NOT NULL,
			`ContactStatus` varchar(20) NOT NULL,
			`X_Data` text NOT NULL,			
			PRIMARY KEY (id)
		)";	
		
		#Customer Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_customers (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`W_C_ID` bigint(20) NOT NULL,
			`X_C_ID` varchar(36) NOT NULL,			
			PRIMARY KEY (id)
		)";
		
		#Xero Product
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}products (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`ItemID` varchar(36) NOT NULL UNIQUE,
			`Name` varchar(255) NOT NULL,
			`Code` varchar(255) NOT NULL,
			`Description` text NOT NULL,
			`PurchaseDescription` text NOT NULL,
			`UnitPrice` decimal(10,4) NOT NULL,
			`IsTrackedAsInventory` int(1) NOT NULL,
			`X_Data` text NOT NULL,			
			PRIMARY KEY (id)
		)";
		
		#Product Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_products (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`W_P_ID` bigint(20) NOT NULL,
			`X_P_ID` varchar(36) NOT NULL,			
			PRIMARY KEY (id)
		)";
		
		#Variation Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_variations (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`W_V_ID` bigint(20) NOT NULL,
			`X_P_ID` varchar(36) NOT NULL,			
			PRIMARY KEY (id)
		)";
		
		# Category Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_categories (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`W_CAT_ID` bigint(20) NOT NULL,
			`X_P_ID` varchar(36) NOT NULL,
			`X_ACC_CODE` varchar(255) NOT NULL,
			PRIMARY KEY (id)
		)";
		
		#Payment Method Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_payment_method (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`wc_payment_method` varchar(255) NOT NULL,
			`currency` char(3) NOT NULL,
			`enable_payment` int(1) NOT NULL,
			`X_ACC_ID` varchar(36) NOT NULL,
			`enable_txn_fee` int(1) NOT NULL,
			`txn_fee_x_product` varchar(36) NOT NULL,
			`x_invoice_ddd` int(3) NOT NULL,
			`aps_order_status` varchar(255) NOT NULL, # Artificial Payment Sync
			PRIMARY KEY (id)
		)";
		
		#Tax Map
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_tax (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`wc_tax_id` int(11) NOT NULL,
			`xero_tax` varchar(255) NOT NULL,
			`wc_tax_id_2` int(11) NOT NULL,
			PRIMARY KEY (id)
		)";
		
		#Plugin Log
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}log (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`log_title` varchar(500) NOT NULL,
			`details` text NOT NULL,
			`status` int(1) NOT NULL,
			`log_type` varchar(255) NOT NULL,
			`note` text NOT NULL,
			`wc_id` bigint(20) NOT NULL,
			`xero_id` varchar(36) NOT NULL,
			`added_date` datetime NOT NULL,
			PRIMARY KEY (id)
		)";
		
		#Plugin Queue
		$sql[] = "CREATE TABLE IF NOT EXISTS `{$dtp}queue` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`item_type` varchar(255) NOT NULL,
			`item_action` varchar(255) NOT NULL,
			`item_id` bigint(20) NOT NULL,
			`xero_id` varchar(36) NOT NULL,
			`priority` int(1) NOT NULL,
			`woocommerce_hook` varchar(255) NOT NULL,
			`ext_data` text NOT NULL,
			`note` tinytext NOT NULL,
			`run` int(1) NOT NULL,
			`run_datetime` datetime NULL ,
			`status` char(1) NOT NULL,
			`added_date` datetime NOT NULL,
			PRIMARY KEY (`id`)
		)";
		
		#Plugin Session
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}sessions (
			`session_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`session_key` char(32) NOT NULL UNIQUE,			
			`session_value` longtext NOT NULL,
			`session_expiry` bigint(20) UNSIGNED,			
			PRIMARY KEY (session_id)
		)";

		# Map Multiple
		$sql[] = "CREATE TABLE IF NOT EXISTS {$dtp}map_multiple (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`wc_type` varchar(255) NOT NULL,
			`wc_id` bigint(20) NOT NULL,
			`x_type` varchar(255) NOT NULL,
			`x_id` varchar(255) NOT NULL,	
			PRIMARY KEY (id)
		)";
		
		#Run table creation queries
		if(is_array($sql) && !empty($sql)){
			foreach($sql as $query){
				$wpdb->query($query);
			}
		}
	}

}