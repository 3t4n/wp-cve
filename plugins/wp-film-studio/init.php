<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

if ( !class_exists( 'Wpfilm_Addons_Init' ) ) {
    class Wpfilm_Addons_Init{
        
        public function __construct(){
            add_action( 'elementor/widgets/register', array( $this, 'wpfilm_includes_widgets' ) );
        }
        // Include Widgets File
        public function wpfilm_includes_widgets(){
            if ( file_exists( WPFILM_ADDONS_PL_PATH.'includes/widgets/trailer_addons.php' ) ) {
                require_once WPFILM_ADDONS_PL_PATH.'includes/widgets/trailer_addons.php';
            }
            if ( file_exists( WPFILM_ADDONS_PL_PATH.'includes/widgets/wpfilm_campaign_addons.php' ) ) {
                require_once WPFILM_ADDONS_PL_PATH.'includes/widgets/wpfilm_campaign_addons.php';
            }
        }
}
    new Wpfilm_Addons_Init();
}

// enqueue scripts
add_action( 'wp_enqueue_scripts','wpfilm_enqueue_scripts');
function  wpfilm_enqueue_scripts(){
    // enqueue styles
    wp_enqueue_style( 'bootstrap', WPFILM_ADDONS_PL_URL . 'assets/css/bootstrap.min.css');
    wp_enqueue_style( 'icofont', WPFILM_ADDONS_PL_URL . 'assets/css/icofont.min.css');
    wp_enqueue_style( 'wpfilm-vendors', WPFILM_ADDONS_PL_URL.'assets/css/wpfilmstudio-vendors.css');
    wp_enqueue_style( 'wpfilm-widgets', WPFILM_ADDONS_PL_URL.'assets/css/wpfilm-studio-widgets.css');
    // enqueue js
     wp_enqueue_script( 'popper', WPFILM_ADDONS_PL_URL . 'assets/js/popper.min.js', array('jquery'), '1.0.0', true);    
     wp_enqueue_script( 'bootstrap', WPFILM_ADDONS_PL_URL . 'assets/js/bootstrap.min.js', array('jquery'), '4.0.0', true);
     wp_enqueue_script( 'wpfilm-vendors', WPFILM_ADDONS_PL_URL.'assets/js/wpfilmstudio-vendors.js', array('jquery'), '', false);
     wp_enqueue_script( 'wpfilm-active', WPFILM_ADDONS_PL_URL.'assets/js/wpfilm-jquery-widgets-active.js', array('jquery'), '', true);

     // custom css
     $color = wpfilm_get_option( 'wpfilm_theme_color', 'settings', '#e2a750' ); //E.g. #FF0000
     $custom_css = "
             .wp-campaign-box h3 a:hover,
             .trailer-titel h5 a:hover
             {
                color: {$color};
             }
             .indicator-style-two .slick-arrow:hover
             {
                background-color: {$color};
             }
             .wp-campaign-box h5:after,.indicator-style-two .slick-arrow:hover
             {
                border-color: {$color};
             }";
     wp_add_inline_style( 'wpfilm-widgets', $custom_css );
}

add_action('init','wpfilm_size');
function wpfilm_size(){
    add_image_size('wpfilm_img1170x600',1170,600,true);
    add_image_size('wpfilm_img550x348',550,348,true);
    add_image_size('wpfilm_img370x410',370,410,true);
    add_image_size('wpfilm_img162x100',162,100,true);
}
// Text Domain load
add_action( 'init', 'wpfilm_load_textdomain' );
function wpfilm_load_textdomain() {
  load_plugin_textdomain( 'wpfilm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}