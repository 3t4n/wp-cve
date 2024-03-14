<?php
//PREFERENCES
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('NF5_Preferences'))
	{
	class NF5_Preferences
		{
		//PREFERENCES
		
		public function __construct(){
			add_action( 'wp_ajax_save_field_pref', array($this,'save_field_pref'));
			add_action( 'wp_ajax_save_validation_pref', array($this,'save_validation_pref'));
			add_action( 'wp_ajax_save_email_pref', array($this,'save_email_pref'));
			add_action( 'wp_ajax_save_other_pref', array($this,'save_other_pref'));
		}
			
		public function save_field_pref() {
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();	
			$pref_array = array();
			foreach($_POST as $key=>$val)
				{
				$set_val = esc_html(strip_tags($val)); 
				$sanitize_val = sanitize_text_field($set_val);
				$pref_array[$key] = $sanitize_val; 
				}
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['field_preferences'] = $pref_array;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_validation_pref() {
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$pref_array = array();
			foreach($_POST as $key=>$val)
				{
				$set_val = esc_html(strip_tags($val)); 
				$sanitize_val = sanitize_text_field($set_val);
				$pref_array[$key] = $sanitize_val; 
				}
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['validation_preferences'] = $pref_array;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_email_pref() {
			
			
			
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$pref_array = array();
			foreach($_POST as $key=>$val)
				{
				$set_val = esc_html(strip_tags($val)); 
				if($key=='pref_email_from_address' || $key=='pref_email_recipients')
					{
					$sanitize_val = sanitize_email($set_val);
					}
				else
					{
					$sanitize_val = sanitize_text_field($set_val);
					}
				$pref_array[$key] = $sanitize_val;
				}
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['email_preferences'] = $pref_array;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_other_pref() {
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$pref_array = array();
			foreach($_POST as $key=>$val)
				{
				$set_val = esc_html(strip_tags($val)); 
				$sanitize_val = sanitize_text_field($set_val);
				$pref_array[$key] = $sanitize_val; 
				}
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['other_preferences'] = $pref_array;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
	}
	$prefs = new NF5_Preferences();
}



?>