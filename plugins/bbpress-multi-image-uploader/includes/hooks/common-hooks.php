<?php

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Common actions and filters for plugin
 */

/**
 * Calls at init
 */
add_action( 'init', 'bbp_uploader_init' );

/**
 * Add scripts and style
 */
add_action( 'wp_enqueue_scripts', 'bbp_uploader_wp_enqueue_scripts' );

/**
 * Once file has been added through uploader, this ajax will be called.
 */
add_action( 'wp_ajax_photo_gallery_upload', 'bbp_photo_gallery_upload' );