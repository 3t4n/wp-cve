<?php

class Login_Scripts {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_login_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_login_front_style' ) );
	}
	
	public function load_login_admin_style(){
		wp_register_style( 'style_login_admin', plugins_url( LSW_DIR_NAME. '/css/style_login_admin.css' ) );
		wp_enqueue_style( 'style_login_admin' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'ap.cookie', plugins_url( LSW_DIR_NAME . '/js/ap.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( LSW_DIR_NAME . '/js/ap-tabs.js' ) );
	}
	
	public function load_login_front_style() {
		wp_enqueue_style( 'style_login_widget', plugins_url( LSW_DIR_NAME . '/css/style_login_widget.css' ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery.validate.min', plugins_url( LSW_DIR_NAME . '/js/jquery.validate.min.js' ) );
		wp_enqueue_script( 'additional-methods', plugins_url( LSW_DIR_NAME . '/js/additional-methods.js' ) );
	}
	
}