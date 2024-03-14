<?php

// add extra option to font select
add_filter('vc_font_container_get_fonts_filter', 'htmega_vc_fonts_filter');
function htmega_vc_fonts_filter($content){
	$content = array('Use From Theme' => 'None') + $content;
	return $content;
}

// custom field for vc
add_action('init', 'htmegavc_add_vc_shortcode_param');
	function htmegavc_add_vc_shortcode_param(){
	if(defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, 4.8) >= 0) {
		if(function_exists('vc_add_shortcode_param')){
			vc_add_shortcode_param('htmegavc_param_heading' , 'htmegavc_param_heading_callback');
		}
	} else {
		if(function_exists('add_shortcode_param')){
			add_shortcode_param('htmegavc_param_heading' , 'htmegavc_param_heading_callback');
		}
	}

	function htmegavc_param_heading_callback( $settings, $value ){
		$dependency = '';
		$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
		$class = isset($settings['class']) ? $settings['class'] : '';
		$text = isset($settings['text']) ? $settings['text'] : '';
		$output = '<h4 '.$dependency.' class="wpb_vc_param_value '.esc_attr( $class ).'">'.$text.'</h4>';
		$output .= '<input type="hidden" name="'.esc_attr( $settings['param_name'] ).'" class="wpb_vc_param_value htmegavc-param-heading '.esc_attr( $settings['param_name'] ).' '. esc_attr( $settings['type'] ).'_field" value="'.esc_attr( $value ).'" '.$dependency.'/>';
		return $output;
	}
}


/**
 * Contact form list
 * @return array
 */

function htmegavc_contact_form_seven(){
    $countactform = array();
    $forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
    $forms = get_posts( $forms_args );

    if( $forms ){
        foreach ( $forms as $form ){
            $countactform[$form->post_title] = $form->ID;
        }
    }else{
        $countactform[ esc_html__( 'No contact form found', 'htmegavc' ) ] = 0;
    }
    
    return $countactform;
}


/*
 * Plugisn Options value
 * return on/off
 */
function htmegavc_get_option( $option, $section, $default = '' ){

    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}