<?php
/**
 * @package Fusion_Extension_Copyright
 */

/**
 * Copyright Extension.
 *
 * Function for adding a Copyright element to the Fusion engine
 *
 * @since 1.0.0
 */

/**
 * Map Shortcode
 */

add_action('init', 'fsn_init_copyright', 12);
function fsn_init_copyright() {	
			
	if (function_exists('fsn_map')) {
		fsn_map(array(
			'name' => __('Copyright', 'fusion-extension-copyright'),
			'shortcode_tag' => 'fsn_copyright',
			'description' => __('Add Copyright. Displays year and copyright symbol. Input text to appear before or after the copyright using the fields below.', 'fusion-extension-copyright'),
			'icon' => 'copyright',
			'params' => array(
				array(
					'type' => 'text',
					'label' => __('Before Copyright', 'fusion-extension-copyright'),
					'param_name' => 'before_copyright'
				),
				array(
					'type' => 'text',
					'label' => __('After Copyright', 'fusion-extension-copyright'),
					'param_name' => 'after_copyright'
				)
			)
		));
	}
}

/**
 * Output Shortcode
 */
 
function fsn_copyright_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'before_copyright' => '',
		'after_copyright' => ''
	), $atts));
	
	$output = '<div class="fsn-copyright '. fsn_style_params_class($atts) .'">';
		$output .= !empty($before_copyright) ? esc_html($before_copyright) .' ' : '';
		$output .= '&copy;'.date("Y");
		$output .= !empty($after_copyright) ? ' '. esc_html($after_copyright) : '';
	$output .= '</div>';
	
	return $output;
}
add_shortcode( 'fsn_copyright', 'fsn_copyright_shortcode' );

?>