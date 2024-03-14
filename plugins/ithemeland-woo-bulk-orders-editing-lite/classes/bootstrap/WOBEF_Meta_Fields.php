<?php


namespace wobef\classes\bootstrap;


use wobef\classes\repositories\Meta_Field;

class WOBEF_Meta_Fields
{
    public function init()
    {
        add_filter('wobef_column_fields', [$this, 'add_meta_fields_to_column_manager']);
    }

    public function add_meta_fields_to_column_manager($fields)
    {
        $meta_fields = (new Meta_Field())->get();
        if (!empty($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                $content_type = '';
                switch ($meta_field['main_type']) {
                    case "textinput":
                        if ($meta_field['sub_type'] == 'string') {
                            $content_type = 'text';
                        } else {
                            $content_type = 'numeric';
                        }
                        break;
                    case 'textarea':
                    case 'editor':
                        $content_type = 'textarea';
                        break;
                    case 'array':
                        $content_type = 'text';
                        break;
                    case 'calendar':
                        $content_type = 'date';
                        break;
                    default:
                        $content_type = sanitize_text_field($meta_field['main_type']);
                        break;
                }
                $fields[$meta_field['key']] = [
                    'field_type' => 'custom_field',
                    'label' => $meta_field['title'],
                    'editable' => true,
                    'content_type' => $content_type,
                ];
            }
        }
        return $fields;
    }
}
