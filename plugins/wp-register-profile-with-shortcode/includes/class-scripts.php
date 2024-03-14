<?php
class Register_Scripts {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_styles_admin' ) );
	}
	
	public function register_plugin_styles_admin() {
		wp_enqueue_style( 'style_register_widget', plugins_url( WPRPWS_DIR_NAME . '/css/style_register_widget_admin.css' ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'ap.cookie', plugins_url( WPRPWS_DIR_NAME . '/js/ap.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( WPRPWS_DIR_NAME . '/js/ap-tabs.js' ) );
	}
	
	public function register_plugin_styles() {
		wp_enqueue_style( 'style_register_widget', plugins_url( WPRPWS_DIR_NAME . '/css/style_register_widget.css' ) );		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery.validate.min', plugins_url( WPRPWS_DIR_NAME . '/js/jquery.validate.min.js' ) );
		wp_enqueue_script( 'additional-methods', plugins_url( WPRPWS_DIR_NAME . '/js/additional-methods.js' ) );
	}
	
}
