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

$helper_attr = '';
if($element['allow_multiple']){
    $helper_attr .='multiple="multiple"';
    $helper_attr .=' size="'.$this->_ch($element['select_size'],3).'"';
    $helper_attr .=' style="height:'.$this->_ch($element['select_height']['size'],80).'px"';
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

$output .='<div class="elementinvader_addons_for_elementor_f_group elementinvader_addons_for_elementor_f_group_el_'.$element['_id'].' '.$helper_classes.'" style="'.$styles.'">';
if($element['show_label'])
    $output .='<label for="'.$field_id.'">'.$element['field_label'].$required_icon.'</label>';
            $output .='<select '.$helper_attr.' name="'.esc_attr($field_name).'" id="'.esc_attr($field_id).'" type="select" class="elementinvader_addons_for_elementor_f_field" '.$required.' value="'.$value.'" placeholder="'.$element['placeholder'].'" >
                %1$s
            </select>
        </div>';

$string_options = '';
$options = explode('|', $element['field_options']);
foreach ($options as $option){
    $string_options .= sprintf('<option value="%1$s">%2$s</option>', $option, $option);
}
$output = sprintf($output, $string_options);

echo $output;