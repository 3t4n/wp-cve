<?php

require_once "mkgd-post-type.php";

require_once 'map-values.php';


/**
 * Enqueue a script in the WordPress admin on plugin add new or edit page.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function mkgd_enqueue_admin_script( $hook ) {
    $gmap_key = cmb2_get_option('mkgd_settings', 'mkgd_gmaps_api_key');
    if ( 'post.php' == $hook || 'post-new.php' == $hook ) {    
        wp_enqueue_script( 'mkgd_google_places', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key='.$gmap_key, array(), '1.0' );
        wp_enqueue_script( 'mkgd_admin_script', plugin_dir_url( __FILE__ ) . 'js/mkgd-admin.js', array('jquery'), '1.0' );
    }else{
        return;
    }
}
add_action( 'admin_enqueue_scripts', 'mkgd_enqueue_admin_script' );