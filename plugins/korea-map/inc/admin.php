<?php
	
	include KIMS_INCLUDE_DIR . 'setting.php';
	
	add_action('admin_enqueue_scripts', 'kims_admin_scripts');
	add_action('admin_menu', 'kims_admin_menu');
	add_filter('plugin_action_links' . plugin_basename(__FILE__), 'kims_add_settings_link');
		
	function kims_admin_scripts(){
		
	  wp_enqueue_style( 'kims_css', plugins_url('../css/style.css', __FILE__), null, '0.0.1');
	  wp_enqueue_script('jquery');
	  //wp_enqueue_script('daumpostcode', 'https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', null, '1.1', true);
	}
	
	function kims_admin_menu() {
		add_options_page('Korea Map Options', KIMS_TITLE, 'manage_options', 'kims_admin_settings', 'kims_admin_settings');
    load_plugin_textdomain(KIMS_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)).'/../languages');
	}
	
	function kims_add_settings_link ( $links ) {
		$mylinks = array('<a href="' . admin_url( 'options-general.php?page=kims_admin_settings' ) . '">'.__("Settings").'</a>');
		return array_merge( $links, $mylinks );
	}

	
		
	