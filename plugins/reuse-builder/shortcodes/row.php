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

add_shortcode( 'reuse_builder_row', 'reuse_builder_row' );

function reuse_builder_row( $atts, $content ) {
    extract( shortcode_atts( array(
        'custom_class' => '',
    ), $atts) );

    $output = '<div class="reuse-builder-row '.trim($custom_class).'">';
        $output .= do_shortcode( $content );
    $output .= '</div>';

    return $output;
}
