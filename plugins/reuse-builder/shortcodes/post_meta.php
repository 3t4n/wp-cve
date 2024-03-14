<?php
/**
 * Component Post Content Shortcode
 *
 * Show the post content for dynamic generated template
 *
 * @author redqteam
 * @category Theme
 * @package ReuseBuilder/Shortcodes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'reuse_builder_post_meta', 'reuse_builder_post_meta' );

function reuse_builder_post_meta( $atts, $content ) {
    extract( shortcode_atts( array(
        'key'           => '',
        'prefix'        => '',
        'postfix'       => '',
        'prefix_icon'   => '',
        'postfix_icon'  => '',
        'text_repeat'   => 'false',
    ), $atts) );
    
    global $post;
    $post_meta = get_post_meta( $post->ID, $key, true );
    
    $output = '';
    
    if( $prefix_icon ) {
        $output .= '<i class="'.$prefix_icon.'"></i>';
    }

    if( $prefix ) {
        $output .= $prefix;
    }

    if( $text_repeat == 'true' ) {
        $output .= implode( ', ', $post_meta );
    } else {
        $output .= $post_meta;
    }
    
    
    if( $postfix ) {
        $output .= $postfix;
    }

    if( $postfix_icon ) {
        $output .= '<i class="'.$postfix_icon.'"></i>';
    }
    

    return $output;
}
