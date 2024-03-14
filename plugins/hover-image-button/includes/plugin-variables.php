<?php
/**
 * Hover Image Button
 *
 * Initialize the main plugin variables.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 */

defined( 'HOVER_IMAGE_BUTTON' ) or die( 'No script kiddies please!' );

// Settings variable.
if ( ! defined('HOVER_IMAGE_BUTTON_SETTINGS')) {
	define( 'HOVER_IMAGE_BUTTON_SETTINGS' , 'hover_image_button_settings' );
}

// Color variable.
if ( ! defined('HOVER_IMAGE_BUTTON_COLOR')) {
	define( 'HOVER_IMAGE_BUTTON_COLOR' , 'hover_image_button_text_field_color' );
}

// Opacity variable.
if ( ! defined('HOVER_IMAGE_BUTTON_OPACITY')) {
	define( 'HOVER_IMAGE_BUTTON_OPACITY' , 'hover_image_button_text_field_opacity' );
}

// Width variable.
if ( ! defined('HOVER_IMAGE_BUTTON_WIDTH')) {
	define( 'HOVER_IMAGE_BUTTON_WIDTH' , 'hover_image_button_text_field_width' );
}

// Height variable.
if ( ! defined('HOVER_IMAGE_BUTTON_HEIGHT')) {
	define( 'HOVER_IMAGE_BUTTON_HEIGHT' , 'hover_image_button_text_field_height' );
}

// CSS variable.
if ( ! defined('HOVER_IMAGE_BUTTON_CSS')) {
	define( 'HOVER_IMAGE_BUTTON_CSS' , 'hover_image_button_textarea_field_css' );
}
