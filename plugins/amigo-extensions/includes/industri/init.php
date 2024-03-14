<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Industri theme default settings
 */ 
require_once ( AMIGO_PLUGIN_DIR_PATH . 'includes/industri/defaults/default.php');

/**
 * Proper way to enqueue scripts and styles
 */
function amigo_industri_scripts() {
    wp_enqueue_style( 'amigo-industri-main', AMIGO_PLUGIN_DIR_URL.'/includes/industri/assets/css/main.css', false, AMIGO_PLUGIN_VER, 'all' );
    wp_enqueue_script( 'amigo-industri-custom', AMIGO_PLUGIN_DIR_URL . '/includes/industri/assets/js/custom.js', array( 'jquery'), AMIGO_PLUGIN_VER, true );

     $default = amigo_industri_default_settings();
    $sticky_header = get_theme_mod('sticky_header', $default['sticky_header']);

    // Localize the script with new data
     wp_localize_script( 'amigo-industri-custom', 'ind',
        array(             
            'sticky_header' => $sticky_header,            
        )
    );
}
add_action( 'wp_enqueue_scripts', 'amigo_industri_scripts' );

//dynamic CSS
function amigo_industri_dynamic_css(){ 
    // c2a
    $default = amigo_industri_default_settings();
    $c2a_bg = get_theme_mod('c2a_bg_image',$default['c2a_bg_image']);
    $css =".callout-section{background-image: url(".esc_url($c2a_bg).");}";
    wp_add_inline_style( 'industri-style', $css );
}
add_action('wp_enqueue_scripts', 'amigo_industri_dynamic_css' );

// template part
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-header.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-footer.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-slider.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-info.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-about.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-service.php' );
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/template-parts/sections/section-c2a.php' );


// customizer
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/customizer/customizer.php' );

