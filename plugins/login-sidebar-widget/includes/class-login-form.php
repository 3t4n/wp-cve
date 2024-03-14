<?php

class AP_Login_Form{
	
	public function __construct() {
		add_action( 'wp_head', array( $this, 'custom_styles_ap' ) );
	}
	 
	public function add_remember_me(){
		$login_ap_rem = get_option('login_ap_rem');
		if($login_ap_rem == 'Yes'){
			include( LSW_DIR_PATH . '/view/frontend/remember-me-input.php');
		}
	}
	
	public function add_extra_links(){
		
		$fp_url 						= '';
		$login_ap_forgot_pass_link 		= get_option('login_ap_forgot_pass_link');
		$login_ap_forgot_pass_page_url 	= get_option('login_ap_forgot_pass_page_url');
		
		$reg_url 						= '';
		$login_ap_register_link 		= get_option('login_ap_register_link');
		$login_ap_register_page_url 	= get_option('login_ap_register_page_url');
		
		do_action( 'lwws_extra_links_start' );
		
		if( $login_ap_forgot_pass_page_url != '' ){
			$fp_url = esc_url( $login_ap_forgot_pass_page_url ); 
		} else {
			if( $login_ap_forgot_pass_link != '' ){
				$fp_url = esc_url( get_permalink($login_ap_forgot_pass_link) ); 
			}
		}
		
		if( $login_ap_register_page_url != '' ){
			$reg_url = esc_url( $login_ap_register_page_url ); 
		} else {
			if( $login_ap_register_link != '' ){
				$reg_url = esc_url( get_permalink($login_ap_register_link) ); 
			}
		}
		
		if( $fp_url ){
			echo '<a href="' . $fp_url . '" ' . apply_filters( 'lwws_lost_password_link_a_tag', $fp_url ) . '>'.__('Lost Password?','login-sidebar-widget').'</a>';
		}
		
		if( $fp_url and $reg_url ){
			echo apply_filters( 'lwws_extra_links_separator', ' | ' );
		}
		
		if( $reg_url ){
			echo '<a href="' . $reg_url . '" ' . apply_filters( 'lwws_register_link_a_tag', $reg_url ) . '>'.__('Register','login-sidebar-widget').'</a>';
		}
		
		do_action( 'lwws_extra_links_end' );
		
	}
	
	public static function curPageURL() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	public function gen_redirect_url(){
		$redirect_page = get_option('redirect_page');
		$redirect_page_url = get_option('redirect_page_url');
		
		if(isset($_REQUEST['redirect'])){
			$redirect = sanitize_text_field($_REQUEST['redirect']);
		} elseif(isset($_REQUEST['redirect_to'])){
			$redirect = sanitize_text_field($_REQUEST['redirect_to']);
		} else {
			if($redirect_page_url){
				$redirect = $redirect_page_url;
			} else {
				if($redirect_page){
					$redirect = get_permalink($redirect_page);
				} else {
					$redirect = $this->curPageURL();
				}
			}
		}
		return esc_url( $redirect );
	}
	
	public function login_form( $wid_id = '' ){
		$this->load_script();
		if(!is_user_logged_in()){	
			$nonce_check = (get_option('nonce_check_on_login') == 'Yes'?true:false);	
			include( LSW_DIR_PATH . '/view/frontend/login.php');
		} else {
			$logout_redirect_page = get_option('logout_redirect_page');
			$link_in_username = get_option('link_in_username');
			if($logout_redirect_page){
				$logout_redirect_page = get_permalink($logout_redirect_page);
			} else {
				$logout_redirect_page = $this->curPageURL();
			}
			$current_user = wp_get_current_user();
			
			if($link_in_username){
				$link_with_username = '<a href="' . esc_url( get_permalink($link_in_username) ) . '" ' . apply_filters( 'lwws_username_link_a_tag', get_permalink($link_in_username) ) . '>' . apply_filters( 'lwws_welcome_text', __('Howdy','login-sidebar-widget') ) . ', ' . $current_user->display_name . '</a>';
			} else {
				$link_with_username = apply_filters( 'lwws_welcome_text', __('Howdy','login-sidebar-widget') ) . ', ' . $current_user->display_name;
			}
			include( LSW_DIR_PATH . '/view/frontend/after-login.php');
		}
	}
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].$this->message_close_button().'</div>';
		}
	}
	
	public function message_close_button(){
		$cb = '<span href="javascript:void(0);" onclick="closeMessage();" class="close_button_ap"></span>';
		return $cb;
	}
	
	public function custom_styles_ap(){
		echo '<style>';
		echo stripslashes(get_option('custom_style_ap'));
		echo '</style>';
	}
	
	public function load_script(){?>
		<script>
			function closeMessage(){jQuery('.error_wid_login').hide();}
			jQuery(document).ready(function () {
				jQuery('#login').validate({ errorClass: "lw-error" });
			});
		</script>
	<?php }
}