<?php
/**
 * Hover Image Button
 *
 * Check the plugin options and add the default values.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 */

defined( 'HOVER_IMAGE_BUTTON' ) or die( 'No script kiddies please!' );

// Default values array.
$default_data = array (
	HOVER_IMAGE_BUTTON_COLOR => '#FFFFFF',
	HOVER_IMAGE_BUTTON_OPACITY => '0.5',
	HOVER_IMAGE_BUTTON_WIDTH => '100%',
	HOVER_IMAGE_BUTTON_HEIGHT => '100%',
	HOVER_IMAGE_BUTTON_CSS => '',
);

// Get user options.
$current_data = get_option( HOVER_IMAGE_BUTTON_SETTINGS );

// Add the default values if the are not set or inconplete.
if ( false === $current_data ) {
    add_option( HOVER_IMAGE_BUTTON_SETTINGS, $default_data );
} else if ( count( array_diff_key( $default_data, $current_data ) ) != 0 ) {
    $current_data = array_merge( $default_data, $current_data );
    update_option( HOVER_IMAGE_BUTTON_SETTINGS, $current_data );
}