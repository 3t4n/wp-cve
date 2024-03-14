<?php

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Engine
{
    function __construct()
    {
        // Register Scripts
        add_action('init', array($this, 'register_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 9999, 1);

       // Register Lightboxes
        add_action('init', array($this, 'register_lightboxes'));

        // Include Functions
        $this->include_functions();
    }

    public function register_scripts()
    {
        // Register Lib Global Pack CSS
        wp_register_style('hybrig-gallery-libs-global-pack', HYBRID_GALLERY_DIR_URL . 'libs/assets/css/libs.global.pack.min.css', array(), HYBRID_GALLERY_VER, 'all');
        // Register Lib Engine Pack CSS
        wp_register_style('hybrig-gallery-libs-engine-pack', HYBRID_GALLERY_DIR_URL . 'libs/assets/css/libs.engine.pack.min.css', array(), HYBRID_GALLERY_VER, 'all');
        // Register Lib Pack JS
        wp_register_script('hybrig-gallery-libs-pack', HYBRID_GALLERY_DIR_URL . 'libs/assets/js/libs.pack.min.js', array('jquery'), HYBRID_GALLERY_VER, true);

        // Register Engine CSS
        wp_register_style('hybrid-gallery-engine', HYBRID_GALLERY_DIR_URL . 'engine/assets/css/hybrid.gallery.engine.min.css', array(), HYBRID_GALLERY_VER, 'all');
        // Register Engine JS
        wp_register_script('hybrid-gallery-engine', HYBRID_GALLERY_DIR_URL  . 'engine/assets/js/hybrid.gallery.engine.min.js', array('jquery') , HYBRID_GALLERY_VER, true);
    }

    public function enqueue_scripts()
    {
        // Loading Libs
        wp_enqueue_style('hybrig-gallery-libs-global-pack');
        wp_enqueue_style('hybrig-gallery-libs-engine-pack');
        wp_enqueue_script('hybrig-gallery-libs-pack');

        // Loading Lightboxes
        wp_enqueue_style('hybrid-gallery-lightbox-magnific-popup');   
        wp_enqueue_style('hybrid-gallery-lightbox-colorbox');      

        // Loading Engine
        wp_enqueue_style('hybrid-gallery-engine');
        wp_enqueue_script('hybrid-gallery-engine');
    }

    public function register_lightboxes() {
        // Register Magnific Popup
        wp_register_script("hybrid-gallery-lightbox-magnific-popup", HYBRID_GALLERY_DIR_URL . 'libs/lightboxes/magnific-popup/js/magnific-popup.js', array('jquery') , "1.1.0", false);
        wp_register_style("hybrid-gallery-lightbox-magnific-popup", HYBRID_GALLERY_DIR_URL . 'libs/lightboxes/magnific-popup/css/magnific-popup.css', array() , "1.1.0", "all");

        // Register Colorbox
        wp_register_script("hybrid-gallery-lightbox-colorbox", HYBRID_GALLERY_DIR_URL . 'libs/lightboxes/colorbox/js/colorbox.js', array() , "1.1.0", false);
        wp_register_style("hybrid-gallery-lightbox-colorbox",  HYBRID_GALLERY_DIR_URL. 'libs/lightboxes/colorbox/css/colorbox.css', array() , "1.1.0", "all");
    }

    public function include_functions() {
        // Pagination
        require_once(plugin_dir_path( __FILE__ ) . 'functions/pagination.php');    
    }
}

new Hybrid_Gallery_Engine;