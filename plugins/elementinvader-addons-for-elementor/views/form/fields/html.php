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

$field_tyle = 'html';

$output .='<div class="elementinvader_addons_for_elementor_f_group '.$field_tyle.' elementinvader_addons_for_elementor_f_group_el_'.$element['_id'].' '.$helper_classes.'" style="'.$styles.'">';
            $output .=$element['field_html'].'
            </div>';
            
echo $output;