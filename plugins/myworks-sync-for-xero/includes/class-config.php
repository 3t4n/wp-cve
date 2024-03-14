<?php
# Settings and Options - Do not Change
class MWXS_C_Settings{
	public static function Get_C_Setting($s_name) {
		global $wpdb;
		$mwxs_c_settings_arr = array(
			'plugin_license_url' => 'https://dash.myworks.software/api/license-validate', #License
			'xero_c_dashboard_url' => 'https://dash.myworks.software', #Connection Dashboard
			'session_prefix' => 'mw_wc_xero_',
			
			'per_page_keyword' => 'mwxs_per_page',
			'default_show_per_page' => 20,			
			'show_per_page' => array(
				'10'=>'10',
				'20'=>'20',
				'50'=>'50',
				'100'=>'100',
				'200'=>'200',
				'500'=>'500',
			),
			
			'log_save_days' => array(
				'30'=>'30',
				'10'=>'10',
				'15'=>'15',		
				'60'=>'60',
				'90'=>'90',		
			),
			
			'db_table_prefix' => MW_WC_XERO_SYNC_PLUGIN_DB_TABLE_PREFIX,
			
			#Automap Fields
			'wc_customer_automap_fields' => array(
				'user_email' => 'Email',
				'display_name' => 'Display Name',
				'first_name_last_name' => 'First Name + Last Name',
				'last_name' => 'Last Name',
				'billing_company' => 'Company Name',				
			),
			
			'xero_customer_automap_fields' => array(
				'EmailAddress' => 'Email',
				'Name' => 'Name',
				'first_name_last_name' => 'First Name + Last Name',
				'LastName' => 'Last Name',				
			),
			
			'wc_product_automap_fields' => array(
				'name' => 'Name',
				'sku' => 'SKU',
			),
			
			'xero_product_automap_fields' => array(
				'Name' => 'Name',
				'Code' => 'SKU',
			),
			
			'wc_variation_automap_fields' => array(
				'name' => 'Name',
				'sku' => 'SKU',
			),
			
			'xero_variation_automap_fields' => array(
				'Name' => 'Name',
				'Code' => 'SKU',
			),
			
			# Xero API fields char limits
			'xero_api_fields_char_limits' => array(
				'Item_Name' => 50,
				'Item_Code' => 30,
			),
		);
		
		if(!empty($s_name) && isset($mwxs_c_settings_arr[$s_name])){
			return $mwxs_c_settings_arr[$s_name];
		}
	}
}