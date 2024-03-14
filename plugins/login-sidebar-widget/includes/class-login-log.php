<?php

class AP_Login_Log{
	
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'login_log_ap_menu' ) );
		add_action( 'admin_init', array( $this, 'login_log_ip_data' ) );
	}
	
	public function login_log_ap_menu () {
		add_submenu_page( 'login_widget_ap', 'Login Logs', 'Login Logs', 'activate_plugins', 'login_log_ap', array( $this, 'login_log_ap_options' ));
	}
	
	public function  login_log_ap_options () {
		global $wpdb;
		$lmc = new Login_Message_Class;
		$query = "SELECT `ip`,`msg`,`l_added`,`l_status` FROM `".$wpdb->base_prefix."login_log` ORDER BY `l_added` DESC";
		$ap = new AP_Paginate(15);
		$data = $ap->initialize($query,@$_REQUEST['paged']);
		$empty_log_url = wp_nonce_url( "admin.php?page=login_log_ap&action=empty_log", 'empty_login_log', 'trash_log' );
		
		Login_Settings::wrap_start();
		$lmc->show_message();
		include( LSW_DIR_PATH . '/view/admin/login-log.php');
		Login_Settings::help_support();
		Login_Settings::donate();
		Login_Settings::wrap_end();
	}
	
	public function login_log_ip_data(){
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == "empty_log"){
			global $wpdb;
			if ( ! isset( $_REQUEST['trash_log'] ) || ! wp_verify_nonce( $_REQUEST['trash_log'], 'empty_login_log' ) ) {
			   wp_die( __('Sorry, your nonce did not verify.','login-sidebar-widget'));
			} 
			$lmc = new Login_Message_Class;
			$wpdb->query("TRUNCATE TABLE ".$wpdb->base_prefix."login_log");
			$lmc->add_message('Log successfully cleared.','updated');
			return;
		}
	}
}


