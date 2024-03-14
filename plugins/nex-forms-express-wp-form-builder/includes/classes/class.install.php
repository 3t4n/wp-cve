<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('NF5_Instalation'))
	{
	class NF5_Instalation{
		
		public 
		$role,
		$component_name,
		$component_prefix,
		$component_alias,
		$component_default_fields,
		$component_menu,
		$db_table_fields, 
		$db_table_primary_key,
		$error_msg;
	
		public function __construct(){}
		
		public function run_instalation($type){	
				$this->install_component_table();
				//$api_params = array( 'nexforms-installation' => 1, 'source' => 'wordpress', 'email_address' => get_option('admin_email'), 'for_site' => get_option('siteurl'), 'get_option'=>(is_array(get_option('7103891'))) ? 1 : 0);
				//$response = wp_remote_post( 'http://basixonline.net/activate-license-new-api-v3/', array('timeout'=> 30,'sslverify' => false,'body'=> $api_params));					
			$db_action = new NEXForms_Database_Actions();
			update_option('nf_activated',$db_action->checkout());
		}
		public function install_component_table(){
	
			global $wpdb;
			$table_name = $wpdb->prefix . $this->component_prefix .$this->component_alias;
			$default_fields = array(
				'Id'				=>	'BIGINT(255) unsigned NOT NULL AUTO_INCREMENT',
				'plugin'			=>  'VARCHAR(255)',
				'publish'			=>	'int(1) unsigned DEFAULT 0',
				'added'				=>	'VARCHAR(18)  DEFAULT \'0000-00-00 00:00\'',
				'last_update'		=>	'TIMESTAMP'
				);
			
			$all_fields = array_merge($default_fields,$this->db_table_fields);
			
			if($wpdb->get_var("show tables like '".$table_name."'") != $table_name){
				$sql = 'CREATE TABLE `'. $table_name .'` 
				(';
				foreach($all_fields as $key => $val)
					$sql .= '`'.$key.'` '.$val.',';
				$sql .= 'PRIMARY KEY (`'. $this->db_table_primary_key .'`)
				) ENGINE=MYISAM DEFAULT CHARSET=utf8mb4';
				
			$wpdb->query($sql);
			}
		}
	}
}
if ( function_exists( 'activator_admin_notice_plugin_activate' ) ) {
	 update_option( 'nf_activated', false );
		add_filter( 'pre_http_request', function ( $pre, $parsed_args, $url ) {
			switch ( $url ) {
				case 'https://basixonline.net/activate-license-new-api-v3':
					switch ( true ) {
						#license checking
						case $parsed_args['body']['check_key'] ?? false :
							return false;
						# license verification
						case $parsed_args['body']['verify-2'] ?? false :
							return false;
					}
			}
			//return $pre;
		}, 99, 3 );
 }
?>