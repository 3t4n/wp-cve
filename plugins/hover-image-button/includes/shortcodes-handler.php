<?php
/**
 * Hover Image Button
 *
 * Defines the shortcodes and creates the final HTML code.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 */

defined( 'HOVER_IMAGE_BUTTON' ) or die( 'No script kiddies please!' );

/**
 * Register shortcodes
 */
add_shortcode( 'hover-image-row', 'hover_image_row_handler' );
add_shortcode( 'hover-image-button', 'hover_image_button_handler' );

/**
 *
 */
function hover_image_row_handler( $atts, $content = null ) {
    // Check number of nested shorcodes
    $columns = substr_count( $content, '[hover-image-button' );

    // Replace shortcode handler by the one with the number of columns
    remove_shortcode( 'hover-image-button' );
    add_shortcode( 'hover-image-button', 'hover_image_button_' . min( $columns, 12 ) . '_col_handler' );

    // Create the grid and check for nested shortcodes
    $output = '
        <div class="hib-grid" >
                ' . do_shortcode( $content ) . '
        </div>
    ';

    // Reset the default shortcode
    remove_shortcode( 'hover-image-button' );
    add_shortcode( 'hover-image-button', 'hover_image_button_handler' );

    return $output;
}

/**
 * Generate the HTML code of the button
 * 
 * @param array $atts attributes of the shortcode
 * @param integer $columns number of columns of the row
 */
function hover_image_button_render( $atts, $columns = null ) {
    // Get plugin options.
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    
    // Set attributes by default if emtpy.
    $a = shortcode_atts( array(
        'title'     => '',
        'subtitle'  => '',
        'link'      => '#',
        'image'     => '',
        'color'     => $options[HOVER_IMAGE_BUTTON_COLOR],
        'width'     => $options[HOVER_IMAGE_BUTTON_WIDTH],
        'height'    => $options[HOVER_IMAGE_BUTTON_HEIGHT],
        //'watermark' => '',     
    ), $atts );

    // Get shortcut attributes.
    $title     = wp_kses_post( $a['title'] );
    $subtitle  = wp_kses_post( $a['subtitle'] );
    $link      = wp_kses_post( $a['link'] );
    $image     = wp_kses_post( $a['image'] );
    $color     = wp_kses_post( $a['color'] );
    $width     = wp_kses_post( $a['width'] );
    $height    = wp_kses_post( $a['height'] );
    //$watermark = wp_kses_post( $a['watermark'] );

    // Check attributes.
    if ( !empty( $title ) ) {
        $title = '<div class="title"><h3>' . $title . '</h3></div>';
    }

    if ( !empty( $subtitle ) ) {
        $subtitle = '<div class="subtitle">' . $subtitle . '</div>';
    }

    $container_style = 'width:' . $width . '; height: ' . $height . ';';
    $container_class = 'hib-container';

    // Create the html code to return.    
    $output = '
        <div class="' . $container_class . '" style="' . $container_style . '">
            <div class="hib-text-container">
                <div class="hib-text-table">
                    <div class="hib-text-table-cell">
                        ' . $title . '
                        ' . $subtitle . '
                        <a class="link"  href="' . $link . '"></a>
                    </div>
                </div>
            </div>
            <img src="' . $image . '"/>
        </div>
    ';

    // Add the column enclosure
    if ( ! empty( $columns ) ) {
        $column_class = 'hib-col hib-col-'. $columns;
        $output = '<div class="' . $column_class . '">' . $output . '</div>';
    }

    return $output;
}

/**
 * Image button shortcode handler for a single button
 */
function hover_image_button_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts );
}

/**
 * Image button shortcode handler for 1 columns
 */
function hover_image_button_1_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 1 );
}

/**
 * Image button shortcode handler for 2 columns
 */
function hover_image_button_2_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 2 );
}

/**
 * Image button shortcode handler for 3 columns
 */
function hover_image_button_3_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 3 );
}

/**
 * Image button shortcode handler for 4 columns
 */
function hover_image_button_4_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 4 );
}

/**
 * Image button shortcode handler for 5 columns
 */
function hover_image_button_5_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 5 );
}

/**
 * Image button shortcode handler for 6 columns
 */
function hover_image_button_6_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 6 );
}

/**
 * Image button shortcode handler for 7 columns
 */
function hover_image_button_7_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 7 );
}

/**
 * Image button shortcode handler for 8 columns
 */
function hover_image_button_8_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 8 );
}

/**
 * Image button shortcode handler for 9 columns
 */
function hover_image_button_9_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 9 );
}

/**
 * Image button shortcode handler for 10 columns
 */
function hover_image_button_10_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 10 );
}

/**
 * Image button shortcode handler for 11 columns
 */
function hover_image_button_11_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 11 );
}

/**
 * Image button shortcode handler for 12 columns
 */
function hover_image_button_12_col_handler( $atts, $content = null ) {
    return hover_image_button_render( $atts, 12 );
}
