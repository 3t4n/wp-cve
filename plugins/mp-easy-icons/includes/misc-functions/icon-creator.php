<?php
/**
 * This file contains the icon creation scripts function for the icons plugin
 *
 * @since 1.0.0
 *
 * @package    MP Easy Icons
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
* Shortcode which is used to display the icon
*/
function mp_easy_icons_shortcode( $atts ) {
	global $mp_easy_icons_meta_box;
	$vars =  shortcode_atts( array(
		'icon' => NULL,
		'size' => NULL,
		'color' => NULL,
		'left_space' => NULL,
		'right_space' => NULL,
		'vertical_offset' => NULL
	), $atts );	
	
	$style_output = NULL;
	
	//Set the size of the icon
	if ( !empty( $vars['size'] ) ){
		$style_output .= 'font-size: ' . $vars['size'] . 'px; ';	
	}
	
	//Set the color of the icon
	if ( !empty( $vars['color'] ) ){
		$style_output .= ' color: ' . $vars['color'] . '; ';	
	}
	
	//Set the space to the left of the icon
	if ( !empty( $vars['left_space'] ) ){
		$style_output .= ' padding-left: ' . $vars['left_space'] . 'px; ';	
	}
	
	//Set the space to the left of the icon
	if ( !empty( $vars['right_space'] ) ){
		$style_output .= ' padding-right: ' . $vars['right_space'] . 'px; ';	
	}
	
	//Set the vertical offset
	if ( !empty( $vars['vertical_offset'] ) ){
		$style_output .= ' margin-top: ' . $vars['vertical_offset'] . 'px; ';	
	}
	
	//Set the vertical alignment of the icon
	$style_output .= ' vertical-align: top; ';	
	$style_output .= 'line-height: 1.2;';
			
	$icon_html = '<span class="' . $vars['icon'] . '" style="' . $style_output . '"></span>';
		
	//Return the stack HTML output - pass the function the stack id
	return $icon_html;
}
add_shortcode( 'mp_easy_icon', 'mp_easy_icons_shortcode' );

/**
 * Show "Insert Shortcode" above posts
 */
function mp_easy_icons_show_insert_shortcode( $post_type ){
	
	$args = array(
		'shortcode_id' => 'mp_easy_icon',
		'shortcode_title' => __('Icon', 'mp_easy_icons'),
		'shortcode_description' => __( 'Use the form below to insert the shortcode for your Icon:', 'mp_easy_icons' ),
		'shortcode_icon_spot' => true,
		'shortcode_icon_dashicon_class' => 'dashicons-info', //Grab this from https://developer.wordpress.org/resource/dashicons/#info
		'shortcode_options' => array(
			array(
				'option_id' => 'icon',
				'option_title' => __('Icon', 'mp_easy_icons'),
				'option_description' => __( 'Choose the icon', 'mp_easy_icons' ),
				'option_type' => 'iconfontpicker',
				'option_value' => mp_easy_icons_get_font_awesome_icons(),
			),
			array(
				'option_id' => 'size',
				'option_title' => __('Icon Size', 'mp_easy_icons'),
				'option_description' => __( 'Set the size of the icon in Pixels (Leave blank to have it match the font-size of this text area).', 'mp_easy_icons' ),
				'option_type' => 'number',
				'option_value' => ''
			),
			array(
				'option_id' => 'color',
				'option_title' => __( 'Icon Color', 'mp_easy_icons' ),
				'option_description' => __( 'Pick a color for this icon', 'mp_easy_icons' ),
				'option_type' => 'colorpicker',
				'option_value' => '',
			),
			array(
				'option_id' => 'left_space',
				'option_title' => __( 'Space on Left', 'mp_easy_icons' ),
				'option_description' => __( 'How much blank space should there be to the left of the icon?', 'mp_easy_icons' ),
				'option_type' => 'number',
				'option_value' => '',
			),
			array(
				'option_id' => 'right_space',
				'option_title' => __( 'Space on Right', 'mp_easy_icons' ),
				'option_description' => __( 'How much blank space should there be to the right of the icon?', 'mp_easy_icons' ),
				'option_type' => 'number',
				'option_value' => '',
			),
			array(
				'option_id' => 'vertical_offset',
				'option_title' => __( 'Vertical Offset', 'mp_easy_icons' ),
				'option_description' => __( 'Fine tune the vertical position of your icon with this. Negative values will shift the icon upwards. Positive values will shift it down.', 'mp_easy_icons' ),
				'option_type' => 'number',
				'option_value' => '0',
			),
			
		)
	); 
		
	//Shortcode args filter
	$args = has_filter('mp_easy_icons_insert_shortcode_args') ? apply_filters('mp_easy_icons_insert_shortcode_args', $args) : $args;
	
	new MP_CORE_Shortcode_Insert($args);	
}
add_action('mp_core_shortcode_setup', 'mp_easy_icons_show_insert_shortcode');