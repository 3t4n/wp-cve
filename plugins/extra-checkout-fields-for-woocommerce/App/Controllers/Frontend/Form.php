<?php

namespace ECFFW\App\Controllers\Frontend;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Helpers\ParseInput;

class Form
{
    /**
     * Form fields.
     * @var array|null
     */
    public $fields = null;

    /**
     * Set Form fields.
     */
    public function setFields()
    {
        if (!is_null($this->fields)) return;

        $settings = get_option(ECFFW_SETTINGS_KEY);
        if ($settings) {
            $types = ['billing', 'shipping', 'order', 'custom'];
            foreach ($types as $type) {
                $fields = json_decode($settings[$type . '_fields_json']);
                if (is_array($fields) && !empty($fields)) {
                    foreach ($fields as $field) {
                        $this->fields[$type][] = $field;
                    }
                } else {
                    $this->fields[$type] = [];
                }
            }
        }
    }

    /**
     * Get Form fields.
     */
    public function getFields()
    {
        $this->setFields();
        return $this->fields;
    }

    /**
     * Validate Inputs.
     * 
     * @return array errors
     */
    public function validateInputs()
    {
        $errors = [];
        foreach ($this->fields as $type => $fields) {
            foreach ($fields as $field) {
                $message = $this->validateInput($field);
                if ($message) {
                    $errors[$field->name] = $message;
                }
            }
        }

        return $errors;
    }

    /**
     * Validate Input.
     * 
     * @param object $field
     * @return string|null message
     */
    public function validateInput($field)
    {
        $message = null;
        if (!in_array($field->type, array('header', 'paragraph'))) {
            $message = ParseInput::validate($field);
        }

        return apply_filters('ecffw_validate_input', $message, $field);
    }

    /**
     * Get Form Data.
     * 
     * @return array data
     */
    public function getData()
    {
        $data = [];
        foreach ($this->fields as $type => $fields) {
            foreach ($fields as $field) {
                $data[] = $this->getFieldData($field);
            }
        }

        return $data;
    }

    /**
     * Get Form Field Data.
     * 
     * @param object $field
     * @return array data
     */
    public function getFieldData($field)
    {
        $data = [];
        if (!in_array($field->type, array('header', 'paragraph'))) {
            $type = $field->type;
            $name = $field->name;
            $label = isset($field->label) ? $field->label : '';
            $value = ParseInput::sanitize($field);

            if (isset($_POST[$name])) {
                if ($type == 'text' && $field->subtype == 'color') $type = 'color';

                if (in_array($type, array('select', 'radio-group', 'checkbox-group'))) {
                    if (is_array($value)) {
                        $options = [];
                        foreach ($field->values as $option) {
                            if (in_array($option->value, $value)) {
                                $options[] = $option->label;
                            }
                        }
                    } else {
                        $options = '';
                        foreach ($field->values as $option) {
                            if ($option->value == $value) {
                                $options = $option->label;
                            }
                        }
                    }

                    $data = [
                        'type' => $type,
                        'name' => $name,
                        'label' => $label,
                        'value' => $options
                    ];
                } else {
                    $data = [
                        'type' => $type,
                        'name' => $name,
                        'label' => $label,
                        'value' => $value
                    ];
                }
            }
        }

        return apply_filters('ecffw_get_field_data', $data, $field);
    }
}
