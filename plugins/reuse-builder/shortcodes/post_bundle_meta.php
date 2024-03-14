<?php
/**
 * Component Post Bundle Meta Shortcode
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

add_shortcode( 'reuse_builder_post_bundle_meta', 'reuse_builder_post_bundle_meta' );

function reuse_builder_post_bundle_meta( $atts, $content ) {
    extract( shortcode_atts( array(
        'key'           => '',
        'bundle_key'    => '',
    ), $atts) );
    
    global $post;

    $post_meta = get_post_meta( $post->ID, $key, true );
    
    $output = '';

    return $output;
}
