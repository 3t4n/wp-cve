<?php

/**
 * Public File
 * Handles to public functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*** remove_script_version code start ***/
function esb_eu_remove_script_version( $src ) {
    
    $parts = explode( '?ver', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'esb_eu_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'esb_eu_remove_script_version', 15, 1 );
/*** remove_script_version code start ***/

/*** defer parsing of javascript code start ***/
if( !( is_admin() ) ) {
    function esb_defer_parsing_of_js ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        return $url . "' defer class='";
    }
    add_filter( 'clean_url', 'esb_defer_parsing_of_js', 11, 1 );
}
/*** defer parsing of javascript code end ***/
 
/*** .html append to url code start ***/
function esb_eu_html_page_permalink() {
    
    global $wp_rewrite, $esb_eu_settings;
    
    if( !empty( $esb_eu_settings['extension'] ) && trim( $esb_eu_settings['extension'] != '' ) ) {
        
        $extension = $esb_eu_settings['extension'];
        
        if( !strpos( $wp_rewrite->get_page_permastruct(), $extension ) ) {
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . $extension;
        }
        $wp_rewrite->flush_rules();
    }
}
add_action( 'init', 'esb_eu_html_page_permalink', -1 );

function esb_eu_no_page_slash( $string, $type ) {
    global $wp_rewrite;
    if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true ) {
        return untrailingslashit($string);
    } else {
        return $string;
    }
}
add_filter( 'user_trailingslashit', 'esb_eu_no_page_slash', 66, 2 );
/*** .html append to url code end ***/
?>