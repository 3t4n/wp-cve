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

add_shortcode( 'reuse_builder_post_thumbnail', 'reuse_builder_post_thumbnail' );

function reuse_builder_post_thumbnail( $atts, $content ) {
    extract( shortcode_atts( array(
    ), $atts) );
    
    global $post;
    $post_thumbnail_url = get_the_post_thumbnail_url( $post, 'full' );

    $output = '<img src="'.$post_thumbnail_url.'">';

    return $output;
}
