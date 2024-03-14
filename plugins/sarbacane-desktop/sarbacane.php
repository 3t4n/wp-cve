<?php
/*
Plugin Name: Sarbacane
Plugin URI: http://wordpress.org/plugins/sarbacane-desktop/
Description: This plugin allows you to synchronize your WordPress data in Sarbacane
Author: Sarbacane Software
Version: 1.4.15
Author URI: http://sarbacane.com/?utm_source=module-wordpress&utm_medium=plugin&utm_content=lien-sarbacane&utm_campaign=wordpress
Text Domain: sarbacane-desktop
Domain Path: /locales
*/

if ( defined( 'ABSPATH' ) ) {

	require_once( 'class.sarbacane.php' );
	require_once( 'class.sarbacane-newsletterwidget.php' );
	require_once( 'class.sarbacane-lists-sync.php' );
	require_once( 'class.sarbacane-about.php' );
	require_once( 'class.sarbacane-content.php' );
	require_once( 'class.sarbacane-settings.php' );
	require_once( 'class.sarbacane-medias.php' );

	$sarbacane_instance = new Sarbacane();

	register_activation_hook( __FILE__, array( 'Sarbacane', 'activation' ) );
	register_deactivation_hook( __FILE__, array( 'Sarbacane', 'deactivation' ) );

	add_action( 'admin_menu', array( new SarbacaneAbout(), 'add_admin_menu' ) );
	add_action( 'admin_menu', array( $sarbacane_instance, 'add_admin_menu' ) );
	add_action( 'admin_menu', array( new SarbacaneListsSync(), 'add_admin_menu' ) );
	add_action( 'plugins_loaded', array( $sarbacane_instance, 'sarbacane_load_locales' ) );
	add_action( 'profile_update', array( $sarbacane_instance, 'trigger_user_update' ), 10, 2 );
	add_action( 'delete_user', array( $sarbacane_instance, 'trigger_user_delete' ) );
	add_action( 'parse_request', array( $sarbacane_instance, 'sarbacane_process_request' ) );
	add_filter( 'query_vars', array( $sarbacane_instance, 'sarbacane_query_vars' ) );
	$sd_list_news = get_option( 'sarbacane_news_list', false );
	if ( $sd_list_news ) {
		$widget = new SarbacaneNewsWidget();
		add_action( 'admin_menu', array( $widget, 'add_admin_menu' ) );
		add_action( 'widgets_init', array( $widget, 'sarbacane_init_widget' ) );
	}

}
