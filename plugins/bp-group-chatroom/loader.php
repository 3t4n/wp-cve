<?php
/*
Plugin Name: BuddyPress Group Chatroom
Plugin URI: https://wordpress.org/plugins/bp-group-chatroom
Description: Group Chatrooms for BuddyPress networks; add images, videos, emojis and links into chat, post threads to activity.
Version: 1.7.7
Requires at least: 4.6.0
Tested up to: 5.6
License: GPL V2
Author: Venutius
Author URI: http://buddyuser.com
Text Doamin: bp-group-chatroom
*/
if ( !defined( 'ABSPATH' ) ) exit;

define ( 'BP_GROUP_CHATROOM_IS_INSTALLED', 1 );
define ( 'BP_GROUP_CHATROOM_VERSION', '1.7.4' );
define ( 'BP_GROUP_CHATROOM_DB_VERSION', '1.3' );
if ( !defined( 'BP_GROUP_CHATROOM_SLUG' ) )
	define ( 'BP_GROUP_CHATROOM_SLUG', 'chat' );


/* Only load the component if BuddyPress is loaded and initialized. */
function bp_group_chat_init() {
	if ( bp_is_active( 'groups' ) ) {
		require( dirname( __FILE__ ) . '/includes/bp-group-chatroom-core.php' );
	}
}
add_action( 'bp_init', 'bp_group_chat_init' );

function bp_group_chat_enqueue_scripts() {
	global $wp_version;
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-touch-punch' );
	wp_enqueue_script( 'iris', site_url() . "/wp-admin/js/iris.min.js", array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
	//if ( $wp_version < 5.5 ) {
		wp_enqueue_script( 'wp-color-picker', site_url() . "/wp-admin/js/color-picker.min.js", array( 'iris','wp-i18n' ), false, 1 );
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
			'clear'            => sanitize_text_field( __( 'Clear', 'bp-group-chatroom' ) ),
			'clearAriaLabel'   => sanitize_text_field( __( 'Clear color', 'bp-group-chatroom' ) ),
			'defaultString'    => sanitize_text_field( __( 'Default', 'bp-group-chatroom' ) ),
			'defaultAriaLabel' => sanitize_text_field( __( 'Select default color', 'bp-group-chatroom' ) ),
			'pick'             => sanitize_text_field( __( 'Select Color', 'bp-group-chatroom' ) ),
			'defaultLabel'     => sanitize_text_field( __( 'Color value', 'bp-group-chatroom' ) ),
		) );
	//}
	wp_enqueue_style( 'bp-group-chatroom-style', plugin_dir_url( __FILE__ ) . 'includes/css/bp-group-chatroom-display.css', array(), BP_GROUP_CHATROOM_VERSION );
	wp_enqueue_script( 'bp-group-chatroom-timers', plugin_dir_url( __FILE__ ) . 'includes/js/jquery-timers-1.2.js' );
	wp_register_script( 'bp-group-chatroom-frontend', plugin_dir_url( __FILE__ ) . 'includes/js/bp-group-chatroom-frontend.js', array(), BP_GROUP_CHATROOM_VERSION );
	$translation_array = array(
		'noMessages'			=> sanitize_text_field( __( '-no messages yet-', 'bp-group-chatroom' ) ),
		'siteUrl'				=> plugin_dir_url( __FILE__ )
		);
	
	wp_localize_script( 'bp-group-chatroom-frontend', 'bpgc_translate', $translation_array );
	wp_enqueue_script( 'bp-group-chatroom-frontend' );
	wp_localize_script( 'bp-group-chatroom-frontend', 'chat_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'check_nonce' => wp_create_nonce('bpgl-nonce') ) );
	wp_enqueue_media();
}

add_action( 'bp_enqueue_scripts', 'bp_group_chat_enqueue_scripts' );

// create the tables
function bp_group_chat_activate() {
	global $wpdb;

	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_chat (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
				timestamp int(11) NOT NULL,
				thread_id bigint(20) NOT NULL,
		  		message_content text
		 	   ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_chat_online (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		timestamp int(11) NOT NULL
		 	   ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_chat_updates (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		timestamp int(11) NOT NULL
		 	   ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_chat_threads (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		timestamp int(11) NOT NULL
		 	   ) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );

	dbDelta($sql);

	//update_site_option( 'bp-group-chatroom-db-version', BP_GROUP_CHATROOM_DB_VERSION );
}
register_activation_hook( __FILE__, 'bp_group_chat_activate' );

?>