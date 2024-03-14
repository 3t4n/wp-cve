<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Front-end
//=================================================================================================

//Disable Emoji support for old browsers
function wpui_global_disable_emoji() {
	$wpui_global_disable_emoji_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_disable_emoji_option ) ) {
		foreach ($wpui_global_disable_emoji_option as $key => $wpui_global_disable_emoji_value)
			$options[$key] = $wpui_global_disable_emoji_value;
		 if (isset($wpui_global_disable_emoji_option['wpui_global_disable_emoji'])) { 
		 	return $wpui_global_disable_emoji_option['wpui_global_disable_emoji'];
		 }
	}
};

if (wpui_global_disable_emoji() == '1') {
	//Credits from Disable Emojis plugin
	function wpui_global_disable_emoji_support() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );	
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}
	add_action('init', 'wpui_global_disable_emoji_support', 999);

	function disable_emojis_tinymce( $plugins ) {
	    if ( is_array( $plugins ) ) {
	        return array_diff( $plugins, array( 'wpemoji' ) );
	    } else {
	        return array();
	    }
	}
}

//Disable JSON REST API
function wpui_global_disable_json_api() {
	$wpui_global_disable_json_api_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_disable_json_api_option ) ) {
		foreach ($wpui_global_disable_json_api_option as $key => $wpui_global_disable_json_api_value)
			$options[$key] = $wpui_global_disable_json_api_value;
		if (isset($wpui_global_disable_json_api_option['wpui_global_disable_json_rest_api'])) {
			return $wpui_global_disable_json_api_option['wpui_global_disable_json_rest_api'];
		}
	}
};

if (wpui_global_disable_json_api() =='1') {
    function wpui_global_remove_json_api(){
        //v1
        add_filter('json_enabled', '__return_false');
		add_filter('json_jsonp_enabled', '__return_false');

		//v2
		add_filter('rest_enabled', '__return_false');
		add_filter('rest_jsonp_enabled', '__return_false');
    }
    add_action('init', 'wpui_global_remove_json_api', 999);
}

//Disable XML RPC
function wpui_global_disable_xml_rpc() {
	$wpui_global_disable_xml_rpc_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_disable_xml_rpc_option ) ) {
		foreach ($wpui_global_disable_xml_rpc_option as $key => $wpui_global_disable_xml_rpc_value)
			$options[$key] = $wpui_global_disable_xml_rpc_value;
		if (isset($wpui_global_disable_xml_rpc_option['wpui_global_disable_xmlrpc'])) {
			return $wpui_global_disable_xml_rpc_option['wpui_global_disable_xmlrpc'];
		}
	}
}

if (wpui_global_disable_xml_rpc() =='1') {
    function wpui_global_disable_xml_rpc_php(){
    	add_filter('xmlrpc_enabled', '__return_false');

		add_filter('wp_headers', function($headers) {
		    unset($headers['X-Pingback']);
		    return $headers;
		});
    }
    add_action('init', 'wpui_global_disable_xml_rpc_php', 999);
}
