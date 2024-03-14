<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

if(!class_exists('WPBForWPbakery_Admin_Init')){
	class WPBForWPbakery_Admin_Init{

	    public function __construct(){
	        add_action('admin_enqueue_scripts', array( $this, 'wpbforwpbakery_enqueue_admin_scripts' ) );
	        $this->wpbforwpbakery_admin_settings_page();
	    }

	    /*
	    *  Setting Page
	    */
	    public function wpbforwpbakery_admin_settings_page() {
	        require_once('include/class.settings-api.php');
	        if( is_plugin_active('wc-builder-pro/wc-builder-pro.php') ){
	            require_once WPBFORWPBAKERY_PRO_ADDONS_PL_PATH.'includes/admin/admin-setting.php';
	        }else{
	            require_once('include/admin-setting.php');
	        }
	    }

	    /*
	    *  Enqueue admin scripts
	    */
	    public function wpbforwpbakery_enqueue_admin_scripts(){
	        wp_enqueue_style( 'wpbforwpbakery-admin', WPBFORWPBAKERY_ADDONS_PL_URL . 'includes/admin/assets/css/admin_optionspanel.css', FALSE, WPBFORWPBAKERY_VERSION );

	        // wp core styles
	        wp_enqueue_style( 'wp-jquery-ui-dialog' );

	        // wp core scripts
	        wp_enqueue_script( 'jquery-ui-dialog' );
	        
	        wp_enqueue_script( 'wpbforwpbakery-admin-main', WPBFORWPBAKERY_ADDONS_PL_URL . 'includes/admin/assets/js/admin.js', array('jquery'), WPBFORWPBAKERY_VERSION, TRUE );
	    }

	}

	new WPBForWPbakery_Admin_Init();
}