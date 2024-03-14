<?php

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}

if(!function_exists('wprp_set_user_flag')){
	function wprp_set_user_flag( $user_id = '' ){
		if( $user_id ){
			update_user_meta( $user_id, 'user_reg_with_wprp', 'Yes' );
		}
	}
}

if(!function_exists('wprw_set_html_content_type')){
	function wprw_set_html_content_type() {
		return 'text/html';
	}
}

if(!function_exists('wp_register_profile_text_domain')){
	function wp_register_profile_text_domain(){
		load_plugin_textdomain('wp-register-profile-with-shortcode', FALSE, basename( WPRPWS_DIR_PATH ) .'/languages');
	}
}

if(!function_exists('wp_register_profile_set_default_data')){
	function wp_register_profile_set_default_data() {
		global $wprw_mail_to_user_subject, $wprw_mail_to_user_body;
		
		if( get_option( 'new_user_register_mail_subject' ) == '' ){
			update_option( 'new_user_register_mail_subject', $wprw_mail_to_user_subject );
		}
		if( get_option( 'new_user_register_mail_body' ) == '' ){
			update_option( 'new_user_register_mail_body', $wprw_mail_to_user_body );
		}
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !is_plugin_active( 'wp-user-subscription/subscription.php' ) ) {
			delete_option( 'enable_subscription' );
		}
	}
}