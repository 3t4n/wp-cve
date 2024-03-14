<?php
/**
 * Plugin Name: CSV Product Import Export for WooCommerce
 * Plugin URI : https://github.com/cmssoft/csv-wc-product-import-export
 * Description: Manage your WooCommerce product data by import & export from CSV file.
 * Version: 1.0.0
 * Author: cmssoft
 * Author URI : https://github.com/cmssoft/
 * Text Domain : csv-wc-product-import-export
*/

if ( ! defined( 'ABSPATH' ) || ! is_admin() ) {
	//return;
}

/**
* Include Dependencies
*/
if ( ! class_exists( 'PIECFW_Dependencies' ) ){
	require_once 'includes/class-piecfw-dependencies.php';
}

/**
* Include Functions
*/
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return PIECFW_Dependencies::woocommerce_active_check();
	}
}

/**
* Check WooCommerce exists
*/
if ( ! is_woocommerce_active() ) {
	return;
}

/*
* Plugin dir
*/
$upload = wp_upload_dir();
$upload_dir = $upload['basedir'];
if(!defined('PIECFW_PLUGIN_DIR_PATH')) {
	define('PIECFW_PLUGIN_DIR_PATH',plugin_dir_path(__FILE__));
}
if(!defined('PIECFW_PLUGIN_DIR_URL')) {
	define('PIECFW_PLUGIN_DIR_URL',plugin_dir_url(__FILE__));
}
if(!defined('PIECFW_UPLOAD_DIR')) {
	define('PIECFW_UPLOAD_DIR',$upload_dir."/piecfw_product_import_export/");
}
if(!defined('PIECFW_UPLOAD_DIR_NAME')) {
	define('PIECFW_UPLOAD_DIR_NAME',$upload_dir."/piecfw_product_import_export/");
}
if(!defined('PIECFW_UPLOAD_CRON_DIR')) {
	define('PIECFW_UPLOAD_CRON_DIR',PIECFW_UPLOAD_DIR."cron/");
}
if(!defined('PIECFW_UPLOAD_CRON_DIR_NAME')) {
	define('PIECFW_UPLOAD_CRON_DIR_NAME',PIECFW_UPLOAD_DIR_NAME."cron/");
}
if(!defined('PIECFW_TRANSLATE_NAME')) {
	define('PIECFW_TRANSLATE_NAME','piecfw-product-import-export');
}
/*
* Cron Frequency 
*/
$piecfw_freq = array('piecfw_one_time'=>__('One time'),
'piecfw_every_15_minutes'=>__('Every 15 minutes'),
'piecfw_every_30_minutes'=>__('Every 30 minutes'),
'piecfw_hourly'=>__('Once hourly'),
'piecfw_daily'=>__('Once daily'),
'piecfw_twicedaily'=>__('Twice daily'),
'piecfw_weekly'=>__('Once weekly'),
'piecfw_fifteendays'=>__('Every 15 days'),
'piecfw_monthly'=>__('Monthly'));
if(!defined('PIECFW_FREQ')) {
	define('PIECFW_FREQ',$piecfw_freq);
}
/*
* Cron Frequency interval
*/
$piecfw_freq_interval = array('piecfw_one_time'=>'One time',
'piecfw_every_15_minutes'=>(15*60),
'piecfw_every_30_minutes'=>(30*60),
'piecfw_hourly'=>(60*60),
'piecfw_daily'=>(24*(60*60)),
'piecfw_twicedaily'=>(12*(60*60)),
'piecfw_weekly'=>(7*(24*(60*60))),
'piecfw_fifteendays'=>(15*(24*(60*60))),
'piecfw_monthly'=>(30*(24*(60*60))));
if(!defined('PIECFW_FREQ_INTERVAL')) {
	define('PIECFW_FREQ_INTERVAL',$piecfw_freq_interval);
}

if(!class_exists( 'PIECFW_Product_Import_Export')){
    /**
	* Main CSV Import class
	*/
	class PIECFW_Product_Import_Export {
		/**
		* Logging class
		*/
		private static $logger = false;

		/**
		* Constructor
		*/
		public function __construct() {
			define( 'PIECFW_FILE', __FILE__ );
			define( 'PIECFW_VERSION', '1.0.0' );

			if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				register_activation_hook( __FILE__, array( $this, 'activate' ) );
				register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );            
			}   
			if ( is_admin() ){
				register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );      
			}

			add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );
			add_action( 'init', array( $this, 'catch_export_request' ), 20 );
			add_action( 'admin_init', array( $this, 'register_importers' ) );

			include_once( 'includes/piecfw-functions.php' );
			include_once( 'includes/class-piecfw-admin-screen.php' );
			include_once( 'includes/importer/class-piecfw-importer.php' );

			if ( defined('DOING_AJAX') ) {
				include_once( 'includes/class-piecfw-ajax-handler.php' );
			}
		}

		/**
		* Add activate
		*/
		public function activate(){
			global $wpdb;
			
			$tblname = 'piecfw_product_import_cron';
			$wp_track_table = $wpdb->prefix . "$tblname";
			#Check to see if the table exists already, if not, then create it
			if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) {	        
				$sql = "CREATE TABLE IF NOT EXISTS `".$wp_track_table."` (
				`cron_id` bigint(20) NOT NULL AUTO_INCREMENT,
				`file_name` varchar(255) NOT NULL,
				`file_path` varchar(500) NOT NULL,
				`start_date` datetime NOT NULL,
				`frequency` varchar(100) NOT NULL COMMENT 'One Time, Every 5 minute, Every 15 minute, Every 30 minute, Once Hourly, Once Daily, Twice Daily, Once Weekly, Every 15 Days, Monthly',
				`status` varchar(20) NOT NULL COMMENT 'Pending, Running, Completed',
				`created_at` timestamp NOT NULL,
				PRIMARY KEY (`cron_id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
				require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
				dbDelta($sql);
			}

			$tblname = 'piecfw_product_import_data_log';
			$wp_track_table = $wpdb->prefix . "$tblname";
			#Check to see if the table exists already, if not, then create it
			if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) {	        
				$sql = "CREATE TABLE IF NOT EXISTS `".$wp_track_table."` (
				`log_id` bigint(20) NOT NULL AUTO_INCREMENT,
				`file_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`product_id` bigint(20) NOT NULL,
				`product_sku` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`product_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`product_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`status` int(11) NOT NULL,
				`status_message` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`created_at` timestamp NOT NULL,
				PRIMARY KEY (`log_id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";
				require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
				dbDelta($sql);
			}

			$tblname = 'piecfw_product_import_file_log';
			$wp_track_table = $wpdb->prefix . "$tblname";
			#Check to see if the table exists already, if not, then create it
			if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) {	        
				$sql = "CREATE TABLE IF NOT EXISTS `".$wp_track_table."` (
				`log_id` bigint(20) NOT NULL AUTO_INCREMENT,
				`file_name` varchar(255) NOT NULL,
				`file_status` char(20) NOT NULL,
				`file_date` datetime NOT NULL,
				PRIMARY KEY (`log_id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
				require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
				dbDelta($sql);
			}
		}

		/**
		* Add deactivate
		*/
		public function deactivate(){
		}

		/**
		* Add uninstall
		*/
		public function uninstall(){
			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."piecfw_product_import_cron" );
			$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."piecfw_product_import_data_log" );
			$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."piecfw_product_import_file_log" );
		}

		/**
		* Add screen ID
		*/
		public function woocommerce_screen_ids( $ids ) {
			$ids[] = 'admin';
			return $ids;
		}

		/**
		* Catches an export request and exports the data. This class is only loaded in admin.
		*/
		public function catch_export_request() {
			if ( ! is_user_logged_in() ) {
				return;
			}

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && 'piecfw_import_export' === $_GET['page'] ) {
				switch ( $_GET['action'] ) {
					case 'export' :
						include_once( 'includes/exporter/class-piecfw-exporter.php' );
						PIECFW_Exporter::do_export( 'product' );
					break;
				}
			}
		}

		/**
		* Register importers for use
		*/
		public function register_importers() {
			register_importer( 'piecfw', 'CSV Import (Product)', __('Import <strong>products</strong> to your store via a csv file.', PIECFW_TRANSLATE_NAME), 'PIECFW_Importer::product_importer' );
		}

		/**
		* Get meta data direct from DB, avoiding get_post_meta and caches
		*/
		public static function log( $message ) {
			if ( ! self::$logger ) {
				self::$logger = new WC_Logger();
			}
			self::$logger->add( 'piecfw-import', $message );
		}

		/**
		* Get meta data direct from DB, avoiding get_post_meta and caches
		*/
		public static function get_meta_data( $post_id, $meta_key ) {
			global $wpdb;
			$value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value from $wpdb->postmeta WHERE post_id = %d and meta_key = %s", $post_id, $meta_key ) );
			return maybe_unserialize( $value );
		}
	}
}
new PIECFW_Product_Import_Export();

/*
* Cron
*/
include_once( 'includes/piecfw-cron.php' );