<?php
if (!defined('ABSPATH')) die('-1');

function checkbox_group_tag_field($settings, $value){
    $additional_field_options = $settings['options'];
    $script = $settings['script'];
    $checked_code = " checked='checked'";
    $param_name = $settings['param_name'];

    $output = '<div class="checkbox_group">';
    foreach ($additional_field_options as $k => $v) {
        $output .= "<div>";
        $this_checked = (strpos($value, $k) === false) ? "" : $checked_code;
        $output .= "<input fmc-field='{$param_name}' fmc-type='checkbox' type='checkbox' name='".vc_get_field_name($param_name)."[{$k}]' value='{$k}' id='".vc_get_field_id($param_name)."-".$k."'{$this_checked} /> ";
        $output .= "<label for='".vc_get_field_id($param_name)."-".$k."'>{$v}</label>";
        $output .= "</div>";
    }
    $output .="<input fmc-field=\"{$param_name}\" fmc-type='text' type='hidden' value=\"{$value}\" name=\"{$param_name}\" class='wpb_vc_param_value' />";
    $output .= '</div>';

    if(!empty($script)){
		$custom_tag = 'script';
		$output .= '<' . $custom_tag . ' src="' . esc_url( $script ) . '"></' . $custom_tag . '>';
	}
    return $output;
}

vc_add_shortcode_param( 'checkbox_group_tag', 'checkbox_group_tag_field' );