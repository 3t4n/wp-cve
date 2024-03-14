<?php
/*
Plugin Name: Clean Login
Plugin URI: https://codection.com
Description: Responsive Frontend Login and Registration plugin. A plugin for displaying login, register, editor and restore password forms through shortcodes. [clean-login] [clean-login-edit] [clean-login-register] [clean-login-restore]
Author: codection
Version: 1.14.4
Author URI: https://codection.com
Text Domain: clean-login
Domain Path: /lang
*/
if ( ! defined( 'ABSPATH' ) ) 
	exit; 

define( "CLEAN_LOGIN_PATH", plugin_dir_path( __FILE__ ) );
define( "CLEAN_LOGIN_URL", plugin_dir_url( __FILE__ ) );	
define( "CLEAN_LOGIN_CAPTCHA_URL", plugins_url( 'content/captcha', __FILE__ ) );

function clean_login_init(){
	$clean_login = new CleanLogin();
	$clean_login->widgets_init();
	
    add_action( 'plugins_loaded', array( $clean_login, 'on_loaded' ) );
}
add_action('plugins_loaded', 'clean_login_init', 0 );

class CleanLogin{
	public function widgets_init(){
		require_once( plugin_dir_path( __FILE__ ) . "include/widget.php" );

		$widget = new CleanLogin_Widget();
		$widget->load();
	}

	public function on_loaded(){
		load_plugin_textdomain( 'clean-login', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		add_filter( 'plugin_action_links_clean-login/clean-login.php', array( $this, 'settings_link' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2);
		add_action( 'wp_logout', array( $this, 'maybe_redirect_after_logout' ) );
		
		if ( ! is_admin() && get_option( 'cl_chooserole' ) ) {
			load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
		}

		foreach ( glob( plugin_dir_path( __FILE__ ) . "include/*.php" ) as $file ) {
			if ( strpos( $file, 'widget' ) !== false )
				continue;
			
			include_once( $file );
		}

		$settings = new CleanLogin_Settings();
		$settings->load();

		$controller = new CleanLogin_Controller();
		$controller->load();

		$shortcodes = new CleanLogin_Shortcode();
		$shortcodes->load();
		
		$roles = new CleanLogin_Roles();
		$roles->load();

		$frontend = new CleanLogin_Frontend();
		$frontend->load();

		$nav_menu_links = new CleanLogin_NavMenuLinks();
		$nav_menu_links->load();
	}
	
	function settings_link( $links ) { 
		$url = "options-general.php?page=clean_login_menu";
		$settings_link = "<a href='$url'>" . __( 'Settings', 'clean-login' ) . "</a>";
		array_unshift( $links, $settings_link );

		return $links; 
	}	

	function plugin_row_meta( $links, $file ){
		if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
			$new_links = array(
						'<a href="https://ko-fi.com/codection" target="_blank">' . __( 'Invite us for a coffee', 'clean-login' ) . '</a>',
						'<a href="mailto:contacto@codection.com" target="_blank">' . __( 'Premium support', 'clean-login' ) . '</a>',
						'<a href="https://codection.com/" target="_blank">' . __( 'RedSys and Ceca Gateways', 'clean-login' ) . '</a>',
						'<a href="https://import-wp.com/" target="_blank" style="color:#d54e21;font-weight:bold">' . __( 'Premium addons and plugins', 'clean-login' ) . '</a>',
					);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}

	function maybe_redirect_after_logout(){
		if( get_option('cl_logout_redirect', false) == '' )
			return;
	
		$logoutredirect_url = get_option('cl_logout_redirect_url', false) ? esc_url( apply_filters( 'cl_logout_redirect_url', CleanLogin_Controller::get_translated_option_page('cl_logout_redirect_url' ) ) ): home_url();
		wp_redirect( $logoutredirect_url );
		exit();
	}
}
