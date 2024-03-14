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

add_shortcode( 'reuse_builder_image_grid', 'reuse_builder_image_grid' );

function reuse_builder_image_grid( $atts, $content ) {
    extract( shortcode_atts( array(
    ), $atts) );

    global $post;
    $post_thumbnail_url = get_the_post_thumbnail_url( $post, 'full' );

    $output = '<article>';
        $output .= '<div class="inner-wrap">';
            $output .= '<div class="post-content">';
                $output .= '<div class="post-featured-img">';
                    $output .= '<img src="'.esc_url( $post_thumbnail_url ).'">';
                $output .= '</div>';
                $output .= '<div class="content-inner">';
                    $output .= '<div class="post-header">';
                        $output .= '<a href="'.get_the_permalink().'"><h3 class="title">'.get_the_title().'</h3></a>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
    $output .= '</article>';

    return $output;
}
