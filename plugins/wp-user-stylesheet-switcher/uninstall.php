<?php 
if(defined('WP_UNINSTALL_PLUGIN') ){  
	delete_option( 'wp_user_stylesheet_switcher_settings' );
	delete_option( 'widget_wp_user_stylesheet_switcher_widgets' );
	if (isset($_COOKIE["wp_user_stylesheet_switcher_js"])) {
		unset($_COOKIE["wp_user_stylesheet_switcher_js"]);
		setcookie('wp_user_stylesheet_switcher_js', '', time() - 3600);
	}
}
?>
