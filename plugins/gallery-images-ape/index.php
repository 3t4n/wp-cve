<?php

/*
Plugin Name: Gallery Images Ape
Plugin URI: https://wpape.net/gallery-wordpress
Description: Gallery Images Ape - image gallery grid with lightbox gallery and video gallery modes. Easy to use as photo gallery for portfolios
Version: 2.2.8
Author: galleryape
Author URI: https://wpape.net/#demos
License: GPL2
Text Domain: gallery-images-ape
Domain Path: /languages
*/

if( !defined('WPINC') || !defined("ABSPATH") ) die();

define("WPAPE_GALLERY", 1); 

define("WPAPE_GALLERY_DEBUG", 0);

define("WPAPE_GALLERY_VERSION", '2.2.6'); 
define("WPAPE_GALLERY_OPTIONS_VERSION", '2.2.6'); 

define("WPAPE_GALLERY_PATH", plugin_dir_path( __FILE__ ));
define("WPAPE_GALLERY_FILE", __FILE__ );


define("WPAPE_GALLERY_URL", plugin_dir_url( __FILE__ ));
define("WPAPE_GALLERY_INCLUDES_PATH", 	WPAPE_GALLERY_PATH.'libs/');

define("WPAPE_GALLERY_NAMESPACE",     	'wpape_');
define("WPAPE_GALLERY_PREFIX", 			WPAPE_GALLERY_NAMESPACE);
define("WPAPE_GALLERY_ASSETS_PREFIX", 	'wpape-');

define("WPAPE_GALLERY_POST",  			'wpape_gallery_type');
define("WPAPE_GALLERY_THEME_POST",  	'wpape_gallery_theme');


define("WPAPE_GALLERY_THEME_TYPE_GRID",  	'grid');
define("WPAPE_GALLERY_THEME_TYPE_SLIDER",  	'slider');


add_action( 'plugins_loaded', 'wpape_gallery_load_textdomain' );

function wpape_gallery_load_textdomain() {
	load_plugin_textdomain( 'gallery-images-ape', false, dirname( plugin_basename( __FILE__ ) ).'/languages' ); 
}

if( file_exists(WPAPE_GALLERY_INCLUDES_PATH.'classHelper.php') ){
	require_once WPAPE_GALLERY_INCLUDES_PATH.'classHelper.php';
}

apeGalleryHelper::checkVersion();

apeGalleryHelper::load( array( 'gallery-images-ape-class.php', 'libs.php') );
$Gallery_Images_Ape_Init = new Gallery_Images_Ape_Init();


define("WPAPE_GALLERY_MODULES_PATH", 	WPAPE_GALLERY_PATH.'modules/');
apeGalleryHelper::load( 'init.php', WPAPE_GALLERY_MODULES_PATH );
