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

add_shortcode( 'reuse_builder_single_post_title', 'reuse_builder_single_post_title' );

function reuse_builder_single_post_title( $atts, $content ) {
    extract( shortcode_atts( array(
        'style' => 'h1'
    ), $atts) );
    
    // global $post;
    $output = '<'.$style.'>';
        $output .= get_the_title();
    $output .= '</'.$style.'>';
    return $output;
}
