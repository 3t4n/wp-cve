<?php
/*
Plugin Name: Photo Gallery Builder
Plugin URI: #
Description: Photo Gallery Builder plugin is an easy way to create responsive photo gallery. Add galleries using grid and masonry layout using 2,3 and 4 column layout. 
Author: wpdiscover
Author URI: #
Version: 3.0
Text Domain: photo-gallery-builder
*/
/** Configuration **/

if ( !defined( 'PHOTO_GALLERY_BUILDER_CURRENT_VERSION' ) ) {
    define( 'PHOTO_GALLERY_BUILDER_CURRENT_VERSION', '3.0' );
}

if(!defined( 'PHOTO_GALLERY_BUILDER_UPGRADE' ) ) {
    define('PHOTO_GALLERY_BUILDER_UPGRADE','https://blogwpthemes.com/downloads/photo-gallery-builder-pro-wordpress-plugin/'); // Plugin Check link
}

define( 'PHOTO_GALLERY_BUILDER_DIR'              , plugin_dir_path(__FILE__) );
define( 'PHOTO_GALLERY_BUILDER_URL'              , plugin_dir_url(__FILE__) );

define( 'PHOTO_GALLERY_BUILDER_INCLUDES'         , PHOTO_GALLERY_BUILDER_DIR        . 'includes'    . DIRECTORY_SEPARATOR );
define( 'PHOTO_GALLERY_BUILDER_ADMIN'            , PHOTO_GALLERY_BUILDER_INCLUDES   . 'admin'       . DIRECTORY_SEPARATOR );
define( 'PHOTO_GALLERY_BUILDER_LIBRARIES'        , PHOTO_GALLERY_BUILDER_INCLUDES   . 'libraries'   . DIRECTORY_SEPARATOR );

define( 'PHOTO_GALLERY_BUILDER_ASSETS'           , PHOTO_GALLERY_BUILDER_URL . 'assets/' );
define( 'PHOTO_GALLERY_BUILDER_IMAGES'           , PHOTO_GALLERY_BUILDER_URL . 'assets/images/' );


define('PHOTO_GALLERY_BUILDER_MASONRY'           , PHOTO_GALLERY_BUILDER_URL . 'includes/public/templates/masonry-animation');

/**
* Activating plugin and adding some info
*/

if (class_exists( 'Photo_Gallery_Pro' ) ) {           
            include_once( ABSPATH . "wp-admin/includes/plugin.php" );           
            deactivate_plugins( 'photo-gallery-builder/photo-gallery-builder.php' );
            return;
}

function pgb_activate() {
    update_option( "photo-gallery-v", PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
    update_option( "photo-gallery-v", PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
    update_option("photo-gallery-type","FREE");
    update_option("photo-gallery-installDate",date('Y-m-d h:i:s') );

    require_once PHOTO_GALLERY_BUILDER_DIR.'default_image.php';
    
}
/**
 * Deactivate the plugin
 */
function pgb_deactivate() {
    // Do nothing
} 

// Installation and uninstallation hooks
register_activation_hook(__FILE__, 'pgb_activate' );
register_deactivation_hook(__FILE__, 'pgb_deactivate' );

add_image_size( 'pgb_image_grid',500,500,true);
add_image_size( 'pgb_masonary',535);

/**
 * The core plugin class that is used to define admin-specific hooks,
 * internationalization, and public-facing site hooks.
 */

require PHOTO_GALLERY_BUILDER_INCLUDES . 'class-photo-gallery.php';

// Installation file
require_once( 'includes/install/installation.php' );


/**
 * Start execution of the plugin.
*/
function photo_gallery_run() {
	//instantiate the plugin class
    $photoGallery = new Photo_Gallery();
} 
photo_gallery_run();

?>