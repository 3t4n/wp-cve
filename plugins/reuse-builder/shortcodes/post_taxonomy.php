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

add_shortcode( 'reuse_builder_post_taxonomy', 'reuse_builder_post_taxonomy' );

function reuse_builder_post_taxonomy( $atts, $content ) {
    extract( shortcode_atts( array(
        'title' => '',
        'taxonomy' => '',
    ), $atts) );
    
    global $post;
    $terms = get_the_terms( $post, $taxonomy );

    $output = '<div class="reuse-builder-taxonomy">';
        $output .= '<div class="reuse-builder-taxonomy-title">';
            $output .= '<h4>'.$title.'</h4>';
        $output .= '</div>';
        $output .= '<div class="reuse-builder-taxonomy-terms">';
        if( !empty( $terms ) ) {
            foreach( $terms as $term ) {
                $output .= '<a href="'.get_term_link( $term ).'">'.$term->name.'</a>';
            }
        }

        $output .= '</div>';
    $output .= '</div>';

    return $output;
}
