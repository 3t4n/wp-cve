<?php

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}

if (!function_exists('lsw_set_html_content_type')) {
	function lsw_set_html_content_type() {
		return 'text/html';
	}
}
	
if(!function_exists('get_login_error_message_text')){
	function get_login_error_message_text( $errors ){	
		$code = $errors->get_error_code();
		$lap_invalid_username = get_option('lap_invalid_username');
		$lap_invalid_email = get_option('lap_invalid_email');
		$lap_invalid_password = get_option('lap_invalid_password');
		
		if($code == 'invalid_username'){
			if($lap_invalid_username){
				$error = $lap_invalid_username;
			} else {
				$error = $errors->get_error_message();
			}
		} else if($code == 'invalid_email'){
			if($lap_invalid_email){
				$error = $lap_invalid_email;
			} else {
				$error = $errors->get_error_message();
			}
		} else if($code == 'incorrect_password'){
			if($lap_invalid_password){
				$error = $lap_invalid_password;
			} else {
				$error = $errors->get_error_message();
			}
		} else {
			$error = apply_filters( 'lsw_login_errors', $errors );
		}
		return $error;
	}
}

if(!function_exists('lsw_login_error_message')){
	function lsw_login_error_message( $error ){	
		if( is_wp_error( $error ) ) {
			$error = $error->get_error_message();
		}
		return $error;
	}
}

if(!function_exists('login_widget_ap_text_domain')){
	function login_widget_ap_text_domain(){
		load_plugin_textdomain('login-sidebar-widget', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}
}

if(!function_exists('lwws_user_captcha_field_no_auto')){
	function lwws_user_captcha_field_no_auto(){
		return 'autocomplete="off"';
	}
}

if(!function_exists('lsw_setup_init')){
	function lsw_setup_init() {
		global $wpdb, $forgot_password_link_mail_subject, $forgot_password_link_mail_body, $new_password_mail_subject, $new_password_mail_body;
		
		// log tables //
		$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->base_prefix."login_log` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `ip` varchar(50) NOT NULL,
		  `msg` varchar(255) NOT NULL,
		  `l_added` datetime NOT NULL,
		  `l_status` enum('success','failed','blocked') NOT NULL,
		  `l_type` enum('new','old') NOT NULL,
		  PRIMARY KEY (`id`)
		)");
		// log tables //
			
		update_option( 'forgot_password_link_mail_subject', $forgot_password_link_mail_subject );
		update_option( 'forgot_password_link_mail_body', $forgot_password_link_mail_body );
		update_option( 'new_password_mail_subject', $new_password_mail_subject );
		update_option( 'new_password_mail_body', $new_password_mail_body );
		
	}
}