<?php
/**
 * Component Post Content Shortcode
 *
 * Show the post content for dynamic generated template
 *
 * @author redqteam
 * @category Theme
 * @package Scholar/Shortcodes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'reuse_builder_post_title_link', 'reuse_builder_post_title_link' );

function reuse_builder_post_title_link( $atts, $content ) {
    extract( shortcode_atts( array(
        'style' => 'h2',
    ), $atts) );
    
    $output = '<a href="'.get_the_permalink().'">';
        $output .= '<'.$style.'>';
            $output .= get_the_title();
        $output .= '</'.$style.'>';
    $output .= '</a>';

    return $output;
}
