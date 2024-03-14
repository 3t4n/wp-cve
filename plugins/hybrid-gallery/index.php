<?php

/**
 * Plugin Name: Gallery: Hybrid - Advanced Visual Gallery
 * Plugin URI: https://hybrid-gallery.com/
 * Description: Hybrid Gallery - 1st WordPress Visual Gallery Plugin
 * Version: 1.4.0.2
 * Author: DivEngine
 * Text Domain: hybrid-gallery
 */

if( !defined( 'ABSPATH') ) exit();

// Define Hybrid Gallery Version!
if ( !defined( 'HYBRID_GALLERY_VER' ) ) {
    define( 'HYBRID_GALLERY_VER', '1.4.0.2' );
}

// Define Hybrid Gallery Directory URL!
if ( !defined( 'HYBRID_GALLERY_DIR_URL' ) ) {
    define( 'HYBRID_GALLERY_DIR_URL', plugin_dir_url( __FILE__ ) );
}

class Hybrid_Gallery_Config
{
    // Plugin initialization
    function __construct()
    {
        // Load Localization
        add_action( 'init', array($this, 'load_textdomain') );

        // Include Functions
        $this->include_functions();
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'hybrid-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }

    public function include_functions() {
        // Admin
        include( plugin_dir_path( __FILE__ ) . 'admin/admin.php');

        // Engine
        include( plugin_dir_path( __FILE__ ) . 'engine/engine.php');

        // Shortcodes
        include( plugin_dir_path( __FILE__ ) . 'shortcodes/index.php');
    }
}

new Hybrid_Gallery_Config;