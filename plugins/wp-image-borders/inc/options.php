<?php

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// register settings
add_action( 'admin_init', 'bs_wib_admin_init');

//register setting and create the section and field
function bs_wib_admin_init() {
	register_setting(
		'wp_image_borders_options', // $option_group variable created in settings_field
		'wp_image_borders_options', // $option_name to be sanitized and saved
		'bs_wib_validation' // validation function
	);
	// this section contains remove checkbox
	add_settings_section(
		'bs_wib_main', // string used in 'id' attribute of tags
		__('Target Images', 'bs-wib'), // title of the section
		'bs_wib_section_text', // callback that will echo content
		'wp-image-borders' // $page ($menu_slug used in add_options_page)
	);
	// this section contains the custom border options
	add_settings_section(
		'bs_wib_border_styles', // string used in 'id' attribute of tags
		__('Customize Image Borders', 'bs-wib'), // title of the section
		'bs_wib_borders_section_text', // callback that will echo content
		'wp-image-borders' // $page ($menu_slug used in add_options_page)
	);
	// this section contains the box shadow options
	add_settings_section(
		'bs_wib_box_shadows', // string used in 'id' attribute of tags
		__('Add Drop Shadows to Images', 'bs-wib'), // title of the section
		'bs_wib_shadow_section_text', // callback that will echo content
		'wp-image-borders' // $page ($menu_slug used in add_options_page)
	);
	// this field lets users add classes
	add_settings_field(
		'bs_wib_post_checkbox', // string used for 'id' in attribute tags
		__('Add borders to all images in blog posts', 'bs-wib'), // Title of the field
		'bs_wib_post_checkbox_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_main' // the section the field is placed in (the id from add_settings_section above)
	);
	// this field lets users add classes
	add_settings_field(
		'bs_wib_classes', // string used for 'id' in attribute tags
		__('Additional CSS classes', 'bs-wib'), // Title of the field
		'bs_wib_classes_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_main' // the section the field is placed in (the id from add_settings_section above)
	);
	// Border options fields below
	// this field adds the style input for the custom border options
	add_settings_field(
		'bs_wib_border_style', // string used for 'id' in attribute tags
		__('Border Style:', 'bs-wib'), // Title of the field
		'bs_wib_border_style_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_border_styles' // the section the field is placed in (the id from add_settings_section above)
	);
	// this field adds the width input for the custom border options
	add_settings_field(
		'bs_wib_border_width', // string used for 'id' in attribute tags
		__('Border Width (in pixels):', 'bs-wib'), // Title of the field
		'bs_wib_border_width_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_border_styles' // the section the field is placed in (the id from add_settings_section above)
	);
	// this field adds the border radius input for the custom border options
	add_settings_field(
		'bs_wib_border_radius', // string used for 'id' in attribute tags
		__('Border Radius (in pixels):', 'bs-wib'), // Title of the field
		'bs_wib_border_radius_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_border_styles' // the section the field is placed in (the id from add_settings_section above)
	);
	// this field adds the color input for the custom border options
	add_settings_field(
		'bs_wib_border_color', // string used for 'id' in attribute tags
		__('Border Color:', 'bs-wib'), // Title of the field
		'bs_wib_border_color_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_border_styles' // the section the field is placed in (the id from add_settings_section above)
	);
	// Box shadow fields here
	// this field adds the drop shadow options for the custom border options
	add_settings_field(
		'bs_wib_box_shadow_horizontal', // string used for 'id' in attribute tags
		__('Horizontal Distance:', 'bs-wib'), // Title of the field
		'bs_wib_box_shadow_horizontal_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_box_shadows' // the section the field is placed in (the id from add_settings_section above)
	);
	add_settings_field(
		'bs_wib_box_shadow_vertical', // string used for 'id' in attribute tags
		__('Vertical Distance:', 'bs-wib'), // Title of the field
		'bs_wib_box_shadow_vertical_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_box_shadows' // the section the field is placed in (the id from add_settings_section above)
	);
	add_settings_field(
		'bs_wib_box_shadow_blur', // string used for 'id' in attribute tags
		__('Blur Radius:', 'bs-wib'), // Title of the field
		'bs_wib_box_shadow_blur_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_box_shadows' // the section the field is placed in (the id from add_settings_section above)
	);
	add_settings_field(
		'bs_wib_box_shadow_spread', // string used for 'id' in attribute tags
		__('Spread Radius:', 'bs-wib'), // Title of the field
		'bs_wib_box_shadow_spread_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_box_shadows' // the section the field is placed in (the id from add_settings_section above)
	);
	add_settings_field(
		'bs_wib_box_shadow_color', // string used for 'id' in attribute tags
		__('Box Shadow Color:', 'bs-wib'), // Title of the field
		'bs_wib_box_shadow_color_display', // callback that creates the input field
		'wp-image-borders', // $page ($menu_slug used in add_options_page)
		'bs_wib_box_shadows' // the section the field is placed in (the id from add_settings_section above)
	);
}

// Adds text to remove borders section
function bs_wib_section_text() {
	echo '<p>' . __("Use this section to target the images you want to add borders to.", "bs-wib") . '</p>';
}

// Adds text to border styles section
function bs_wib_borders_section_text() {
	echo '<p>' . __("Use this section to style your image borders.", "bs-wib") . '</p>';
}

// Adds text to box shadow section
function bs_wib_shadow_section_text() {
	echo '<p>' . __("Use this section to add drop shadows to your images.", "bs-wib") . '</p>';
}

// adds checkbox to add borders to all images in post
function bs_wib_post_checkbox_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_post_checkbox']) ) {
		$options['bs_wib_post_checkbox'] = 0;
	}
	$html = "<input type='checkbox' id='bs_wib_post_checkbox' name='wp_image_borders_options[bs_wib_post_checkbox]' value='1'" . checked( 1, $options['bs_wib_post_checkbox'], false ) . " />";
	echo $html;
}
// adds text input for entering classes to add borders to
function bs_wib_classes_display() {
	$options = get_option( 'wp_image_borders_options' );
	$html = "<input type='text' id='bs_wib_classes' name='wp_image_borders_options[bs_wib_classes]' value='{$options['bs_wib_classes']}' />";
	$html .= '<span style="margin-left: 12px;"><em>.class-1, .class-2, .another-class</em></span>';
	$html .= '<p style="margin-top: 12px;">' . __("Classes should be added to the img element.", "bs-wib") . '</p>';
	echo $html;
}

// adds the style drop-down
function bs_wib_border_style_display() {
	$options = get_option( 'wp_image_borders_options' );
	$html = '<select id="bs_wib_border_style" name="wp_image_borders_options[bs_wib_border_style]">';
	$html .= '<option value="default">Select a border style...</option>';
	$html .= '<option value="solid"' . selected( $options['bs_wib_border_style'], 'solid', false) . '>Solid</option>';
	$html .= '<option value="dotted"' . selected( $options['bs_wib_border_style'], 'dotted', false) . '>Dotted</option>';
	$html .= '<option value="dashed"' . selected( $options['bs_wib_border_style'], 'dashed', false) . '>Dashed</option>';
	$html .= '<option value="double"' . selected( $options['bs_wib_border_style'], 'double', false) . '>Double</option>';
	$html .= '<option value="groove"' . selected( $options['bs_wib_border_style'], 'groove', false) . '>Groove</option>';
	$html .= '<option value="ridge"' . selected( $options['bs_wib_border_style'], 'ridge', false) . '>Ridge</option>';
	$html .= '<option value="inset"' . selected( $options['bs_wib_border_style'], 'inset', false) . '>Inset</option>';
	$html .= '<option value="outset"' . selected( $options['bs_wib_border_style'], 'outset', false) . '>Outset</option>';
	$html .= '</select>';
	echo $html;
}

// adds the border width option
function bs_wib_border_width_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_border_width']) ) {
		$options['bs_wib_border_width'] = 0;
	}
	$html = "<input type='number' id='bs_wib_border_width' min='0' name='wp_image_borders_options[bs_wib_border_width]' value='{$options['bs_wib_border_width']}' />";
	echo $html;
}

// adds the border radius option
function bs_wib_border_radius_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_border_radius']) ) {
		$options['bs_wib_border_radius'] = 0;
	}
	$html = "<input type='number' min='0' id='bs_wib_border_radius' name='wp_image_borders_options[bs_wib_border_radius]' value='{$options['bs_wib_border_radius']}' />";
	echo $html;
}

// Add the built-in WordPress Iris color picker
add_action( 'admin_enqueue_scripts', 'bs_wib_enqueue_color_picker' );
function bs_wib_enqueue_color_picker( $hook_suffix ) {
	// first check that $hook_suffix is appropriate for your admin page
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'color-picker', BS_WIB_PRO_URL . 'js/color-picker.js', array( 'wp-color-picker' ), false, true );
}

// Adds the color picker field/form
function bs_wib_border_color_display() {
	$options = get_option( 'wp_image_borders_options' );
	$html = "<input id='bs_wib_border_color' name='wp_image_borders_options[bs_wib_border_color]' type='text' value='{$options['bs_wib_border_color']}' class='my-color-field' data-default-color='#000000' />";
	echo $html;
}

// Adds the horizontal box shadow field
function bs_wib_box_shadow_horizontal_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_box_shadow_horizontal']) ) {
		$options['bs_wib_box_shadow_horizontal'] = 0;
	}
	$html ="<input type='number' default='0' id='bs_wib_box_shadow_horizontal' name='wp_image_borders_options[bs_wib_box_shadow_horizontal]' value='{$options['bs_wib_box_shadow_horizontal']}' />";
	echo $html;
}

// Adds the vertical box shadow field
function bs_wib_box_shadow_vertical_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_box_shadow_vertical']) ) {
		$options['bs_wib_box_shadow_vertical'] = 0;
	}
	$html ="<input type='number' default='0' id='bs_wib_box_shadow_vertical' name='wp_image_borders_options[bs_wib_box_shadow_vertical]' value='{$options['bs_wib_box_shadow_vertical']}' />";
	echo $html;
}

// Adds the blur box shadow field
function bs_wib_box_shadow_blur_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_box_shadow_blur']) ) {
		$options['bs_wib_box_shadow_blur'] = 0;
	}
	$html ="<input type='number' default='0' min='0' id='bs_wib_box_shadow_blur' name='wp_image_borders_options[bs_wib_box_shadow_blur]' value='{$options['bs_wib_box_shadow_blur']}' />";
	echo $html;
}

// Adds the spread box shadow field
function bs_wib_box_shadow_spread_display() {
	$options = get_option( 'wp_image_borders_options' );
	if ( empty($options['bs_wib_box_shadow_spread']) ) {
		$options['bs_wib_box_shadow_spread'] = 0;
	}
	$html ="<input type='number' default='0' min='0' id='bs_wib_box_shadow_spread' name='wp_image_borders_options[bs_wib_box_shadow_spread]' value='{$options['bs_wib_box_shadow_spread']}' />";
	echo $html;
}

// Adds the spread box shadow field
function bs_wib_box_shadow_color_display() {
	$options = get_option( 'wp_image_borders_options' );
	$html = "<input id='bs_wib_box_shadow_color' name='wp_image_borders_options[bs_wib_box_shadow_color]' type='text' value='{$options['bs_wib_box_shadow_color']}' class='my-color-field' data-default-color='#000000' />";
	echo $html;
}

// sanitizes the inputs from the options pages and outputs clean data
// ONLY does one checkbox right now
function bs_wib_validation( $input ) {

	// Create our array for storing the validated options
	$output = array();

	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {

		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {

			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );

		} // end if

	} // end foreach

	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'bs_wib_validation', $output, $input );
}