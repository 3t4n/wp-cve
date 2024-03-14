<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function location_tag_form_field($settings, $value){
    $param_name = $settings['param_name'];
    $field_slug = $settings['field_slug'];    
    $script = empty($settings['script']) ? plugins_url('../scripts/initLocation.js', __FILE__) : $settings['script'];
    $isMultiple = empty($settings['multiple']) ? false : $settings['multiple'];
	
	if($isMultiple === true){
		$multiply = 'style="height: 110px;" multiple="multiple"';
	} else {
		$multiply = '';
	}

    $output = '';

    $output .= '<select class="flexmlsAdminLocationSearch" type="hidden" style="width: 100%;"  '.$multiply.' id="'. vc_get_field_id($param_name) . '" name="' . $param_name . '_input" data-portal-slug="' . $field_slug . '"></select>';

    $output .="<input fmc-field=\"{$param_name}\" fmc-type='text' type='hidden' value=\"{$value}\" name=\"{$param_name}\" class='wpb_vc_param_value flexmls_connect__location_fields' />";

    $custom_tag = 'script';
    $output .= '<' . $custom_tag . ' src="' . esc_url( $script ) . '"></' . $custom_tag . '>';

    return $output;
}

vc_add_shortcode_param( 'location_tag', 'location_tag_form_field' );