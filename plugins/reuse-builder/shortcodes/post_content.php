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

add_shortcode( 'reuse_builder_post_content', 'reuse_builder_post_content' );

function reuse_builder_post_content( $atts, $content ) {
    extract( shortcode_atts( array(
    ), $atts) );
    
    global $post;

    $output = apply_filters( 'the_content', $post->post_content );

    return $output;
}
