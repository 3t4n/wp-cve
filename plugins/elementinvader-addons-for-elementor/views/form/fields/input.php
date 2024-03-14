<?php
$output ='';
$styles ='';
$helper_classes ='';
$value = '';
$required = '';
$required_icon = '';
$field_id = $this->_ch($element['custom_id'],'elementinvader_addons_for_elementor_f_field_id_'.$element['_id']).strtolower(str_replace(' ', '_', $element['field_label']));
$value = $this->_ch($element['field_value']);
$this->add_field_css($element);
$field_tyle = 'text';
if($element['required']){
    $required = 'required="required"';
    $required_icon = '*';
}
if($element['label_position'] == 'inline'){
    $helper_classes .='inline';
}
switch ( $element['field_type'] ){
        case 'number':
            $field_tyle = 'number';
            break;
        case 'tel':
            $field_tyle = 'tel';
            break;
        case 'text':
            $field_tyle = 'text';
            break;
        case 'email':
            $field_tyle = 'email';
            break;
        case 'url':
            $field_tyle = 'url';
            break;
        case 'password':
            $field_tyle = 'password';
            break;
        case 'file':
            $field_tyle = 'password';
            break;
        case 'hidden':
            $field_tyle = 'hidden';
            break;
        case 'date':
            $field_tyle = 'date';
            break;
        case 'time':
            $field_tyle = 'time';
            break;
        case 'upload':
            $field_tyle = 'file';
            break;
        case 'subject':
            $field_tyle = 'text';
            break;
        case 'search':
}

$field_name = $element['field_id'];

if(empty($field_name)) {
    $field_name = $element['field_label'];
} 

if(empty($field_name)) {
    $field_name = $element['placeholder'];
} 

if(empty($field_name)) {
    $field_name = 'field_id_'.$element['_id'];
} 

if($field_tyle =='hidden'){

    $output .='<input name="'.esc_attr($element['field_label']).'" id="'.esc_attr($field_id).'" type="'.esc_attr($field_tyle).'" class="elementinvader_addons_for_elementor_f_field" '.esc_attr($required).' value="'.esc_attr($value).'" placeholder="'.esc_attr($element['placeholder']).'" >';
} else {
$output .='<div class="elementinvader_addons_for_elementor_f_group '.$field_tyle.' elementinvader_addons_for_elementor_f_group_el_'.$element['_id'].' '.esc_attr($helper_classes).'" style="'.wp_kses_post($styles).'">';
if($element['show_label'])
    $output .='<label for="'.esc_attr($field_id).'">'.esc_html($element['field_label']).esc_html($required_icon).'</label>';
            $output .='
                <input ';

                if(!empty($element['custom_validation_message'])) {
                    $output .='oninvalid="this.setCustomValidity(\''.esc_attr($element['custom_validation_message']).'\')" ';
                }

                if($element['field_type'] == 'subject' ) {
                    $element['field_label'] = 'custom_subject';
                }
             
            $output .='    
                name="'.esc_attr($field_name).'" id="'.esc_attr($field_id).'" type="'.esc_attr($field_tyle).'" class="elementinvader_addons_for_elementor_f_field" '.wp_kses_post($required).' value="'.esc_attr($value).'" placeholder="'.esc_attr($element['placeholder']).'" >
            </div>';
}

echo ($output);