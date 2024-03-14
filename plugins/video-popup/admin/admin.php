<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_meta_row_style(){
	wp_enqueue_style( 'video-popup-meta-row-style', plugins_url('/css/meta-row-style.css', __FILE__), array(), time(), "all" );

	if( isset($_GET['page']) and ($_GET['page'] == 'video_popup_general_settings' or $_GET['page']== 'video_popup_shortcode' or $_GET['page']== 'video_popup_on_pageload') ){
		wp_enqueue_style( 'video-popup-settings-style', plugins_url('/css/settings.css', __FILE__), array(), time(), "all" );
	}

    if( !get_option('vp_green_bg_menu') ) {
        wp_enqueue_style( 'video-popup-green-menu', plugins_url('/css/green-menu.css', __FILE__), array(), time(), "all" );
    }
}
add_action('admin_enqueue_scripts', 'video_popup_meta_row_style');


function video_popup_add_menu_page() {
    add_menu_page(
        __('General Settings', 'video-popup'),
        __('Video PopUp', 'video-popup'),
        'manage_options',
        'video_popup_general_settings',
        '',
        'dashicons-video-alt3'
    );
}
add_action( 'admin_menu', 'video_popup_add_menu_page' );


function video_popup_add_submenu() {
    add_submenu_page(
    	"video_popup_general_settings",
    	__('Video PopUp General Settings', 'video-popup'), __('General Settings', 'video-popup'),
    	'manage_options',
    	'video_popup_general_settings',
    	'video_popup_general_settings_callback'
    );

    add_submenu_page(
        "video_popup_general_settings",
        __('Video PopUp on Page Load', 'video-popup'), __('On Page Load', 'video-popup'),
        'manage_options',
        'video_popup_on_pageload',
        'video_popup_on_pageload_callback'
    );

    add_submenu_page(
    	"video_popup_general_settings",
    	__('Video PopUp Shortcode', 'video-popup'), __('Shortcode Usage', 'video-popup'),
    	'manage_options',
    	'video_popup_shortcode',
    	'video_popup_shortcode_callback'
    );
}
add_action('admin_menu', 'video_popup_add_submenu');


function video_popup_extension_update_checker(){
    if( get_option('vp_extension_update_checker_106') === false ){
        if( get_option('vp_extension_update_checker_105') === true ){
            delete_option('vp_extension_update_checker_105');
        }
        $cache_time = 3600 * 24 * 7;
        delete_transient('vp-prm-alobaidi');
        update_option('vp_extension_update_checker_106', '1');
        update_option('vp_prm_alobaidi', 'has_up');
        set_transient('vp-prm-alobaidi', 'has_up', $cache_time);
    }
}
add_action('admin_init', 'video_popup_extension_update_checker');


require_once dirname( __FILE__ ). '/settings.php';

require_once dirname( __FILE__ ). '/on-pageload.php';

require_once dirname( __FILE__ ). '/shortcode-usage.php';