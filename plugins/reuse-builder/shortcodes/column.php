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

add_shortcode( 'reuse_builder_column', 'reuse_builder_column' );

function reuse_builder_column( $atts, $content ) {
    extract( shortcode_atts( array(
        'md' => '',
        'lg' => '',
        'xl' => '',
    ), $atts) );
    
    $column_class = '';

    if( $md ) {
        $column_class .= ' reuse-builder-col-md-'.$md;
    }

    if( $lg ) {
        $column_class .= ' reuse-builder-col-lg-'.$lg;
    }

    if( $xl ) {
        $column_class .= ' reuse-builder-col-xl-'.$xl;
    }

    $output = '<div class="'.trim($column_class).'">';
        $output .= do_shortcode( $content );
    $output .= '</div>';

    return $output;
}
