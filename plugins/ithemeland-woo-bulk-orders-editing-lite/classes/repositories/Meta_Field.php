<?php

namespace wobel\classes\repositories;

class Meta_Field
{
    const TEXTINPUT = "textinput";
    const TEXTAREA = "textarea";
    const CHECKBOX = "checkbox";
    const RADIO = "radio";
    const ARRAY_TYPE = "array";
    const CALENDAR = "calendar";
    const EMAIL = "email";
    const PASSWORD = "password";
    const URL = "url";
    const IMAGE = "image";
    const FILE = "file";
    const EDITOR = "editor";
    const SELECT = "select";
    const MULTI_SELECT = "multi_select";
    const TAXONOMY = "taxonomy";
    const COLOR = "color_picker";
    const DATE = "date_picker";
    const DATE_TIME = "date_time_picker";
    const TIME = "time_picker";

    const STRING_TYPE = "string";
    const NUMBER = "number";


    private $meta_fields_option_name = "wobel_meta_fields";

    public static function get_fields_name_have_operator()
    {
        return [
            self::TEXTAREA,
            self::EDITOR,
            self::EMAIL,
            self::PASSWORD,
            self::URL,
            self::ARRAY_TYPE,
        ];
    }

    public static function get_main_types()
    {
        return [
            self::TEXTINPUT => __('TextInput', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::TEXTAREA => __('TextArea', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::CHECKBOX => __('Checkbox', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::RADIO => __('Radio', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::ARRAY_TYPE => __('Array', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::CALENDAR => __('Calendar', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::EMAIL => __('Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::PASSWORD => __('Password', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::URL => __('Url', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::IMAGE => __('Image', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::FILE => __('File', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::EDITOR => __('Editor', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::SELECT => __('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function get_supported_acf_field_types()
    {
        return [
            'text',
            'textarea',
            'number',
            'checkbox',
            'radio',
            'email',
            'image',
            'file',
            'select',
            'multi_select',
            'wysiwyg',
            'password',
            'url',
            'taxonomy',
            'date_picker',
            'date_time_picker',
            'time_picker',
            'color_picker',
        ];
    }

    public static function get_sub_types()
    {
        return [
            self::STRING_TYPE => __('String', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::NUMBER => __('Number', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public function update(array $meta_fields)
    {
        return update_option($this->meta_fields_option_name, $meta_fields);
    }

    public function get()
    {
        $meta_fields = get_option($this->meta_fields_option_name);
        return !empty($meta_fields) ? $meta_fields : [];
    }
}
