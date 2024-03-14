<?php

class Register_Admin_Security {
	
	public function __construct() {
		if( in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ) ) ){
			add_action( 'register_form', array( $this, 'display_captcha_admin_registration' ) );
			add_action( 'registration_errors', array( $this, 'validate_captcha_admin_registration' ), 10, 3 );
		}
	}
	
	public function is_field_enabled($value){
		$data = get_option( $value );
		if($data === 'Yes'){
			return true;
		} else {
			return false;
		}
	}
	
	public function display_captcha_admin_registration() {
		if($this->is_field_enabled('captcha_in_wordpress_default_registration')){ 
			include( WPRPWS_DIR_PATH . '/view/admin/captcha.php');
		} 
	}
	
	public function validate_captcha_admin_registration($errors, $sanitized_user_login, $user_email) {
		start_session_if_not_started();
		if($this->is_field_enabled('captcha_in_wordpress_default_registration')){ 
			if ( sanitize_text_field($_POST['admin_captcha']) != $_SESSION['wprp_captcha_code_admin'] ){
				$errors->add( 'invalid_captcha', '<strong>ERROR</strong>: Security code do not match!');
			}
		}
		return $errors;
	}
	
}