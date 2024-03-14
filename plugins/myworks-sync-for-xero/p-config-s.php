<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class MyWorks_WC_Xero_Sync_P_Config{
	public $plugin_data;
	public function __construct() {
		$this->mwxs_define_constants();
	}
	
	private function mwxs_define($name, $value) {
		if(!empty($name) && !defined($name)){
			define($name, $value);
		}
	}
	
	private function mwxs_define_constants(){
		global $wpdb;
		
		if (!function_exists('get_plugin_data')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$p_dir_name = 'myworks-sync-for-xero';
		$plugin_data = get_plugin_data( dirname( __FILE__ ) . '/'.$p_dir_name.'.php' );		
		
		$this->mwxs_define('MW_WC_XERO_SYNC_P_DIR_P', plugin_dir_path( __FILE__ ));
		$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_NAME', $p_dir_name);
		
		$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_DB_TABLE_PREFIX', $wpdb->prefix.'mw_wc_xero_sync_');
		
		if(is_array($plugin_data) && !empty($plugin_data)){			
			$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_TITLE', $plugin_data['Name']);
			
			$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_VERSION', $plugin_data['Version']);
			$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_TEXT_DOMAIN', $plugin_data['TextDomain']);
			
			#$this->mwxs_define('MW_WC_XERO_SYNC_PLUGIN_DATA',$plugin_data);
			#const MW_WC_XERO_SYNC_PLUGIN_DATA = $plugin_data;
			
			#$this->plugin_data = $plugin_data;
		}
	}
	
}

new MyWorks_WC_Xero_Sync_P_Config();