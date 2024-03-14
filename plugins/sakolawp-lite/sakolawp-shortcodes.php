<?php
// Myaccount page shortcode
function sakolawp_myaccount_shortcodes() {
	ob_start();

	if ( !is_user_logged_in() ) {
		require_once plugin_dir_path(__FILE__) . 'template/login-shortcode.php';
	}
	else {
		require_once plugin_dir_path(__FILE__) . 'template/myaccount-shortcode.php';
	}
	
	return ob_get_clean();
}
add_shortcode( 'sakolawp_myaccount_shortcodes', 'sakolawp_myaccount_shortcodes' );

// Login page shortcode
function sakolawp_login_shortcodes() {
	ob_start();

	if ( !is_user_logged_in() ) {
		require_once plugin_dir_path(__FILE__) . 'template/login-shortcode.php';
	}
	else {
		require_once plugin_dir_path(__FILE__) . 'template/already-login.php';
	}
	
	return ob_get_clean();
}
add_shortcode( 'sakolawp_login_shortcodes', 'sakolawp_login_shortcodes' );

// Register page shortcode
function sakolawp_register_shortcodes() {
	ob_start();

	if ( !is_user_logged_in() ) {
		require_once plugin_dir_path(__FILE__) . 'template/register-shortcode.php';
	}
	else {
		require_once plugin_dir_path(__FILE__) . 'template/already-login.php';
	}
	
	return ob_get_clean();
}
add_shortcode( 'sakolawp_register_shortcodes', 'sakolawp_register_shortcodes' );