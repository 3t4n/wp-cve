<?php

if(!class_exists('Login_Message_Class')){
	class Login_Message_Class {
		public function show_message(){
			global $aperror;
			if ( is_wp_error( $aperror ) ) {
				$errors = $aperror->get_error_messages();
				echo '<div class="'.$errors[0].'"><p>'.$errors[1].'</p></div>';
			}
		}
		public function add_message($msg = '', $class = ''){
			global $aperror;
			$aperror = new WP_Error;
			$aperror->add( "Login_Message_Class", $class );
			$aperror->add( "login_message_msg", __($msg,'login-sidebar-widget') );
		}
	}
}