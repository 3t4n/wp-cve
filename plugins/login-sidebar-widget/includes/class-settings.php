<?php

class Login_Settings {

	public function __construct() {
		$this->load_settings();
	}
	
	public function login_widget_ap_save_settings(){
		global $lsw_default_options_data;
		
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_ap_save_settings"){
			
			if ( ! isset( $_POST['login_widget_ap_field'] )  || ! wp_verify_nonce( $_POST['login_widget_ap_field'], 'login_widget_ap_action' ) ) {
			   wp_die( __('Sorry, your nonce did not verify.', 'login-sidebar-widget'));
			   exit;
			} 
			$lmc = new Login_Message_Class;
			
			if( is_array($lsw_default_options_data) ){
				foreach( $lsw_default_options_data as $key => $value ){
					if ( !empty( $_REQUEST[$key] ) ) {
						if( $value['sanitization'] == 'sanitize_text_field' ){
							update_option( $key, sanitize_text_field($_REQUEST[$key]) );
						} elseif( $value['sanitization'] == 'esc_html' ){
							update_option( $key, esc_html($_REQUEST[$key]) );
						} elseif( $value['sanitization'] == 'esc_textarea' ){
							update_option( $key, esc_textarea($_REQUEST[$key]) );
						} else {
							update_option( $key, sanitize_text_field($_REQUEST[$key]) );
						}
					} else {
						delete_option( $key );
					}
				}
			}
			
			update_option( 'custom_style_ap',  sanitize_text_field($_POST['custom_style_ap']) );
			
			do_action('lwws_ap_save_settings');
			
			$lmc->add_message('Settings updated successfully.','updated');
		}
	}
	
	public function removeslashes($string){
		$string=implode("",explode("\\",$string));
		return stripslashes(trim($string));
	}
	
	public static function wrap_start(){
		echo '<div class="wrap">';
	}
	
	public static function wrap_end(){
		echo '</div>';
	}

	public function login_widget_ap_options () {
		global $wpdb, $lsw_default_options_data;
		
		$lmc = new Login_Message_Class;
		
		$stripslashes = array('custom_style_ap', 'forgot_password_link_mail_subject', 'forgot_password_link_mail_body', 'new_password_mail_subject', 'new_password_mail_body', 'login_sidebar_widget_from_email', 'lap_invalid_username', 'lap_invalid_email', 'lap_invalid_password' );
		
		if( is_array($lsw_default_options_data) ){
			foreach( $lsw_default_options_data as $key => $value ){
				if( is_array($stripslashes) and in_array($key, $stripslashes) ){
					$$key = $this->removeslashes( get_option($key) );
				} else {
					$$key = get_option($key);
				}
			}
		}
		
		$custom_style_ap = $this->removeslashes(get_option('custom_style_ap'));
		
		$this->wrap_start();
		$lmc->show_message();
	
		self :: wp_register_profile_add();
	
		Form_Class::form_open();
		wp_nonce_field('login_widget_ap_action','login_widget_ap_field');
		Form_Class::form_input('hidden','option','','login_widget_ap_save_settings');
		include( LSW_DIR_PATH . '/view/admin/settings.php');
		Form_Class::form_close();
		
		self :: fb_login_pro_add();
		self :: social_login_so_setup_add();
		self :: help_support();
		self :: donate(); 
		
		$this->wrap_end();
	}
		
	public function login_widget_ap_menu () {
		add_menu_page( 'Login Widget', 'Login Widget Settings', 'activate_plugins', 'login_widget_ap', array( $this,'login_widget_ap_options' ));
	}
	
	public function load_settings(){
		add_action( 'admin_menu' , array( $this, 'login_widget_ap_menu' ) );
		add_action( 'admin_init', array( $this, 'login_widget_ap_save_settings' ) );
	}
	
	private static function wp_register_profile_add(){
		include( LSW_DIR_PATH . '/view/admin/register-pro-add.php');
	}
	
	public static function help_support(){
		include( LSW_DIR_PATH . '/view/admin/help.php');
	}
	
	private static function fb_login_pro_add(){
		include( LSW_DIR_PATH . '/view/admin/login-pro-add.php');
	}
	
	private static function social_login_so_setup_add(){
		include( LSW_DIR_PATH . '/view/admin/social-login-add.php');
	}
	
	public static function donate(){
		include( LSW_DIR_PATH . '/view/admin/donate.php');
	}
}