<?php
/*
Plugin Name:  Auto Post After Image Upload
Plugin URI:   http://wordpress.org/extend/plugins/auto-post-after-image-upload
Description:  This plugin will provide you the facility to create automated post when you will upload an image to your wordpress media gallery. Each time after uploading one media file upload one post will be created with attached this uploaded image automatically
Version:      1.6
Author:       Shaharia Azam <mail@shaharia.com>
Author URI:   http://www.shaharia.com?utm_source=auto-post-after-image-upload
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  auto-post-after-image-upload
*/

if( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

define( "APAIU_FULL_FILE_PATH", __FILE__ );
define( "APAIP_VERSION", 1.6 );
define( "APAIP_PLUGIN_SLUG", "auto-post-after-image-upload" );

require __DIR__ . DIRECTORY_SEPARATOR . "lib/APAIU_HTMLParser.php";
require __DIR__ . DIRECTORY_SEPARATOR . "lib/APAIU_Functions.php";

if(is_admin()){
	add_action( 'admin_init', array( APAIU_Functions::class, 'registerWhitelistedOptions' ) );
	add_action( 'admin_menu', array( APAIU_Functions::class, 'adminMenu' ) );

	if ( isset( $_GET['page'] ) && $_GET['page'] === APAIP_PLUGIN_SLUG ) {
		add_action( 'admin_enqueue_scripts', array( APAIU_Functions::class, 'loadAdminHeadStyles' ) );
		add_action( 'admin_footer', array( APAIU_Functions::class, 'adminFooterScripts' ) );
	}

	add_action( 'wp_ajax_apaiu_save_preference', array( APAIU_Functions::class, 'apaiu_save_preferences' ) );
	add_action( 'wp_ajax_apaiu_get_preference', array( APAIU_Functions::class, 'apaiu_get_preferences' ) );
	add_filter( 'plugin_row_meta', array( APAIU_Functions::class, 'custom_plugin_row_meta' ), 10, 2 );
}

add_action( 'add_attachment', array( APAIU_Functions::class, 'auto_post_after_image_upload' ) );


register_activation_hook( APAIU_FULL_FILE_PATH, array( APAIU_Functions::class, 'on_activate' ) );
register_deactivation_hook( APAIU_FULL_FILE_PATH, array( APAIU_Functions::class, 'on_deactivate' ) );
register_uninstall_hook( APAIU_FULL_FILE_PATH, array( APAIU_Functions::class, 'on_uninstall' ) );

