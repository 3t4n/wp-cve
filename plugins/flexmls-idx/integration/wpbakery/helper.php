<?php
if (!defined('ABSPATH')) die('-1');

function setTitle($text){
    $titleParams = array(
        'type' => 'section_title',
        'text' => $text,
        'param_name' => 'fmc_none_param',
        'value' => ''
    );
    return $titleParams;
}

function setScripts($filename){
    $path = script_path($filename);
    $params = array(
        'type' => 'scripts_tag',
        'path' => $path,
        'param_name' => 'fmc_none_param',
        'value' => ''
    );
    return $params;
}

function script_path($filename){
    $path = plugins_url('scripts/'.$filename, __FILE__);
    return $path;
}

function vc_get_field_name($field_name){
    return 'fmc_shortcode_field_'.$field_name;
}

function vc_get_field_id($field_name){
    return $field_name;
}
