<?php

class AP_Forgot_Pass_Class {
	
	public function forgot_pass_form(){
		$this->load_script();
		if(!is_user_logged_in()){
			include( LSW_DIR_PATH . '/view/frontend/forgot-password.php');
		}
	}
	
	public function message_close_button(){
		$cb = '<span href="javascript:void(0);" onclick="closeMessageFp();" class="close_button_ap"></span>';
		return $cb;
	}
	
	public function load_script(){?>
		<script>
			jQuery(document).ready(function () {
				jQuery('#forgot').validate();
			});
			function closeMessageFp(){jQuery('.error_wid_login').hide();}
		</script>
	<?php }
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].$this->message_close_button().'</div>';
		}
	}
} 
