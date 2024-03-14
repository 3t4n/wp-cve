<?php

namespace wobel\classes\helpers;

use wobel\classes\repositories\Meta_Field_Main;

class Meta_Field
{
    public static function get_field_type_by_acf_type($acf_field)
    {
        $supported_types = Meta_Field_Main::get_supported_acf_field_types();
        $output = [
            'main_type' => '',
            'sub_type' => '',
            'column_type' => ''
        ];

        if (!empty($acf_field['type']) && $acf_field['type'] == 'taxonomy') {
            if (!empty($acf_field['field_type'])) {
                switch ($acf_field['field_type']) {
                    case 'radio':
                    case 'select':
                        $column_type =  'select';
                        break;
                    case 'multi_select':
                    case 'checkbox':
                        $column_type =  'taxonomy';
                        break;
                }
            }
        }

        $field_type = (!empty($acf_field['field_type'])) ? $acf_field['field_type'] : $acf_field['type'];

        if (in_array($field_type, $supported_types)) {
            switch ($field_type) {
                case 'text':
                    $output['main_type'] = "textinput";
                    $output['sub_type'] = "string";
                    $output['column_type'] = "text";
                    break;
                case 'number':
                    $output['main_type'] = "textinput";
                    $output['sub_type'] = "number";
                    $output['column_type'] = "numeric";
                    break;
                case 'multi_select':
                    $output['main_type'] = "multi_select";
                    $output['sub_type'] = "";
                    $output['column_type'] = "multi_select";
                    break;
                case 'wysiwyg':
                case 'textarea':
                    $output['main_type'] = "editor";
                    $output['column_type'] = "textarea";
                    break;
                default:
                    $output['main_type'] = sanitize_text_field($acf_field['field_type']);
                    $output['column_type'] = (!empty($column_type)) ? $column_type : sanitize_text_field($acf_field['field_type']);
                    break;
            }
        }

        return $output;
    }

    public static function key_value_field_to_array($key_value_string)
    {
        $options = [];
        if (!empty($key_value_string)) {
            $options_items = explode('|', $key_value_string);
            if (!empty($options_items)) {
                foreach ($options_items as $options_item) {
                    $option = explode('=', $options_item);
                    if (isset($option[0]) && isset($option[1])) {
                        $options[sanitize_text_field($option[0])] = sanitize_text_field($option[1]);
                    }
                }
            }
        }

        return $options;
    }
}
