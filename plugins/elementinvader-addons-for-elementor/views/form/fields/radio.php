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
if($element['required']){
    $required = 'required="required"';
    $required_icon = '*';
}

if($element['label_position'] == 'inline'){
    $helper_classes .='inline';
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

$string_options = '';
$options = explode('|', $element['field_options']);
foreach ($options as $key=>$option){
    $output .='<div class="elementinvader_addons_for_elementor_f_group checkbox elementinvader_addons_for_elementor_f_group_el_'.esc_attr($element['_id']).'" style="'.$styles.'">
        <label for="'.esc_attr($field_id).'">
            <input name="'.esc_attr($field_name).'" id="'.esc_attr($field_id).'" type="radio" class="elementinvader_addons_for_elementor_f_field_checkbox" value="'.$option.'">
            '.$option.'
        </label>
    </div>';
}

echo $output;