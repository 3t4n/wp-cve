<?php

/* admin style and script */
add_action( 'admin_enqueue_scripts',  'CPIW_LoadCssAdmin');
function CPIW_LoadCssAdmin(){
        wp_enqueue_script( 'CPIW_front_script', CPIW_PLUGIN_DIR . '/assets/js/back.js', false, '1.0.0' );
        wp_enqueue_style('CPIW_front_style', CPIW_PLUGIN_DIR . '/assets/css/back.css', false, '1.0.0' );
        wp_enqueue_media();
        wp_enqueue_script( 'CPIW_front_scriptt', CPIW_PLUGIN_DIR . '/assets/js/wp_media_uploader.js', false, '1.0.0', true);
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker-alpha', CPIW_PLUGIN_DIR . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0.0', true );
}


add_action( 'wp_enqueue_scripts',  'CPIW_LoadFrontCss');
function CPIW_LoadFrontCss() {
        global $cpiw_comman;
        wp_enqueue_script('jquery', false, array(), false, false);
        wp_enqueue_script( 'CPIW_front_script', CPIW_PLUGIN_DIR . '/assets/js/front.js', false, '1.0.0' );
        wp_enqueue_style('CPIW_front_style', CPIW_PLUGIN_DIR . '/assets/css/front.css', false, '1.0.0' );
        wp_localize_script( 'CPIW_front_script', 'CpiwData', array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'cpiw_plugin_url' => CPIW_PLUGIN_DIR,
                'cpiw_not_availabletext'=>'We Are Not Services This Place',
        
        ));

}