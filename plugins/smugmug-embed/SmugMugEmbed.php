<?php
/*
Plugin Name: SmugMug Embed
Plugin URI: http://www.wicklundphotography.com/smugmugembed-wordpress-plugin
Description: Demo Version - Embeds images from a users SmugMug account into a post or page
Author: Tracy Wicklund	
Version: 3.13
Author URI: http://www.wicklundphotography.com/
*/

/*  Copyright 2019  Tracy Wicklund  (email : tracy@wicklundphotography.com)

  
*/

//start a session

require_once 'vendor/autoload.php';
//require_once( dirname( __FILE__ ) . '/includes/SmugMugEmbedSettings.php' );

add_action('init', 'SME_myStartSession', 1);
add_action('wp_logout', 'SME_myEndSession');
add_action('wp_login', 'SME_myEndSession');

function SME_myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function SME_myEndSession() {
    session_destroy ();
}

    function SME_admin_scripts() {
		
        wp_register_style( 'SME_EmbedStyle', plugins_url( '/includes/css/style.css?t='.time(), __FILE__ ) );
        wp_register_script( 'SME_JavaScript', plugins_url( '/includes/SME_SmugMugEmbed.js?t='.time(), __FILE__ ));

		
        wp_enqueue_script(  'SME_JavaScript' );
        wp_enqueue_style( 'SME_EmbedStyle' );
		wp_localize_script('SME_JavaScript', 'passedData', get_option( 'SME_Settings'));
	}
	add_action( 'admin_enqueue_scripts', 'SME_admin_scripts' );

	function SME_public_scripts() {
        wp_register_style( 'SME_EmbedStyle', plugins_url( '/includes/css/style.css?t='.time(), __FILE__ ) );
        wp_enqueue_style( 'SME_EmbedStyle' );
	}
	add_action('wp_enqueue_scripts','SME_public_scripts');



	//define settings and functions
    $SME_smugmugembed_api      = get_option( 'SME_smugmugembed_api' );
    //get the SmugMug auth flag
    $SME_api_progress =  get_option('SME_api_progress');
    $SME_api_token = get_option( 'SME_api_token');
    $SME_Settings = get_option( 'SME_Settings');
 	
    $SME_api = new phpSmug\Client( "WLqGcsnPjdLfPbMkFRgcNk39FKvjbdxx", ['_verbosity'=>'1','AppName' => get_bloginfo("name")." SmugMug Embed for Wordpress", 'OAuthSecret'=>"9wb2rFRJFTfJ2x8LSLCS7HPTdvbg4xMszdbZ4Kbz2hk6MJPMnMpnVdf3fDfVnD7T" ]);
	require_once( dirname( __FILE__ ) . '/includes/class_SME_helper.php' );
	require_once( dirname( __FILE__ ) . '/includes/class_SME_Settings.php' );
	if (is_array($SME_api_token) && $SME_api_progress=="Verified") $SME_api->setToken($SME_api_token['oauth_token'],$SME_api_token['oauth_token_secret']);
require_once( dirname( __FILE__ ) . '/includes/class-sme-license-manager-client.php' );    
	$license_manager = new SME_License_Manager_Client(
        'smugmug-embed-plugin',
        'SmugMug Embed Plugin',
        'smugmug-embed-plugin-text',
        'http://www.wicklundphotography.com/api/license-manager/v1',
        'plugin',
        __FILE__
    );
$SME_Helper= new SME_Helper($license_manager);
	
	/*-----------------------------------------------------------------------------------*/
    /* Call register settings function */
    /*-----------------------------------------------------------------------------------*/

    function SME_smugmugembed_settings() {

        register_setting( 'SME_smugmugembed_api_group', 'SME_smugmugembed_api' );
        register_setting( 'SME_smugmugembed_api_group', 'SME_api_progress' );
        register_setting( 'SME_smugmugembed_settings_group', 'SME_Settings' );       
		
      }

    add_action( 'admin_init', 'SME_smugmugembed_settings' );
	
	
	/**
	 * BLOCK: SME Image Block.
	 */
	require_once( dirname( __FILE__ ) . '/block/SME_image/index.php');
	
/*if ( is_admin() ) {
    $license_manager = new SME_License_Manager_Client(
        'smugmug-embed-plugin',
        'SmugMug Embed Plugin',
        'smugmug-embed-plugin-text',
        'http://www.wicklundphotography.com/api/license-manager/v1',
        'plugin',
        __FILE__
    );
	//$SME_Helper->write_log("in manager");
	//$SME_Helper->write_log($license_manager->get_license_info());
//$license_manager->get_license_info();
}*/




	add_action( 'init', function(){
		$assets_url = plugin_dir_url( __FILE__ );
		//Setup menu
		if( is_admin() ){
			new SME_Settings_Menu( $assets_url );
		}
		//Setup REST API
		add_action('rest_api_init', function () {
		  $SME_settings_api = new SME_Settings_API();
		  $SME_settings_api->add_routes();
		});
		
	});
	?>