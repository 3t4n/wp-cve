<?php

class Login_Log_Adds{
	public function log_add( $ip = '', $msg = '', $l_added = '', $l_status = '' ){
		global $wpdb;
		if($ip == ''){
			return;
		}
		
		$log_data = array( 'ip' => $ip, 'msg' => $msg,  'l_added' => $l_added, 'l_status' => $l_status, 'l_type' => 'new' );
		$log_data_format = array( '%s', '%s', '%s', '%s', '%s' );
		$wpdb->insert( $wpdb->base_prefix."login_log", $log_data, $log_data_format );
		return;
	}
}