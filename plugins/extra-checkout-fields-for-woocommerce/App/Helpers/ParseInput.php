<?php

namespace ECFFW\App\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ParseInput
{
    /**
     * Sanitize then Validate is an ID.
     * 
     * @param string $input
     * @return string|null
     */
    public static function id($input)
    {
        $input = sanitize_text_field($input);

        if (!empty($input) && strlen($input) <= 20 && preg_match('/^[1-9][0-9]*$/', $input))
            return $input;
        
        return null;
    }

    /**
     * Check the value is empty or not.
     * 
     * @param mixed $value
     * @return bool 
     */
    public static function isEmpty($value)
    {
        if (is_array($value))
            return empty($value);
            
        return trim($value) == '';
    }

    /**
     * Sanitize Input.
     * 
     * @param object $field
     * @return string|array value
     */
    public static function sanitize($field)
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") return '';

        if (!isset($_POST[$field->name]) || self::isEmpty($_POST[$field->name])) return '';
        $input = $_POST[$field->name];

        switch ($field->type) {
            case 'text':
                if ($field->subtype == 'color')
                    $value = sanitize_hex_color($input);
                elseif ($field->subtype == 'email')
                    $value = sanitize_email($input);
                else
                    $value = sanitize_text_field($input);
                break;

            case 'date':
            case 'number':
                $value = sanitize_text_field($input);
                break;

            case 'textarea':
                $value = sanitize_textarea_field($input);
                break;
            
            case 'select':
            case 'radio-group':
            case 'checkbox-group':
                if (is_array($input)) {
                    $value = [];
                    foreach($input as $option) {
                        $value[] = sanitize_text_field($option);
                    }
                } else {
                    $value = sanitize_text_field($input);
                }
                break;

            case 'file':
                $value = apply_filters('ecffw_sanitize_file', '', $input);
                break;

            default:
                $value = sanitize_text_field($input);
        }

        return $value;
    }

    /**
     * Validate Input.
     * 
     * @param object $field
     * @param bool $highlight
     * @return string|null message
     */
    public static function validate($field, $highlight = true)
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") return null;

        $value = self::sanitize($field);

        if ($highlight == true) {
            $field->label = '<strong>' . $field->label . '</strong>';
        }
        
        if ($field->type != 'file') 
        {
            if (isset($field->required) && $field->required) {
                if (!isset($_POST[$field->name]) || self::isEmpty($_POST[$field->name])) {
                    /* translators: %s is replaced with field label */
                    return sprintf(__('%s is required', 'extra-checkout-fields-for-woocommerce'), $field->label);
                }
            }

            if (!isset($_POST[$field->name]) || self::isEmpty($_POST[$field->name])) return null;
        }

        switch ($field->type) {
            case 'text':
                if ($field->subtype == 'color')
                    $message = self::validateColor($field, $value);
                elseif ($field->subtype == 'email')
                    $message = self::validateEmail($field, $value);
                else
                    $message = self::validateText($field, $value);
                break;

            case 'date':
                $message = self::validateDate($field, $value);
                break;

            case 'number':
                $message =  self::validateNumber($field, $value);
                break;

            case 'textarea':
                $message = self::validateText($field, $value);
                break;

            case 'select':
            case 'radio-group':
            case 'checkbox-group':
                if (is_array($value)) {
                    $message = self::validateOptions($field, $value);
                } else {
                    $message = self::validateOption($field, $value);
                }
                break;

            case 'file':
                $message = apply_filters('ecffw_validate_file', null, $field, $value);
                break;

            default:
                $message = null;
        }

        return $message;
    }

    /**
     * Validate Text Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateText($field, $value)
    {
        if ($value == '') {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s is invalid', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        if (isset($field->maxlength)) {
            if (!((int) $field->maxlength >= strlen($value))) {
                /* translators: %1$s is replaced with field label and %2$s is replaced with max length */
                return sprintf(__('%1$s must not be greater than %2$s characters', 'extra-checkout-fields-for-woocommerce'), $field->label, $field->maxlength);
            }
        }
        
        return null;
    }

    /**
     * Validate Email Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateEmail($field, $value)
    {
        if ($value == '') {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s must be a valid email address', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        if (isset($field->maxlength)) {
            if (!((int) $field->maxlength >= strlen($value))) {
                /* translators: %1$s is replaced with field label and %2$s is replaced with max length */
                return sprintf(__('%1$s must not be greater than %2$s characters', 'extra-checkout-fields-for-woocommerce'), $field->label, $field->maxlength);
            }
        }

        return null;
    }

    /**
     * Validate Email Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateColor($field, $value)
    {
        if ($value == '') {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s must be a valid hex color code', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }
        return null;
    }

    /**
     * Validate Date Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateDate($field, $value)
    {
        $date = explode('-', $value);

        if (count($date) != 3 || !checkdate($date[1], $date[2], $date[0])) {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s is not a valid date', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        return null;
    }

    /**
     * Validate Number Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateNumber($field, $value)
    {
        if ($value == '') {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s is invalid', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        if (!is_numeric($value)) {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s must be a number', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        if (isset($field->min)) {
            if (!((float) $field->min <= (float) $value)) {
                /* translators: %1$s is replaced with field label and %2$s is replaced with min value */
                return sprintf(__('%1$s must be at least %2$s', 'extra-checkout-fields-for-woocommerce'), $field->label, $field->min);
            }
        }

        if (isset($field->max)) {
            if (!((float) $field->max >= (float) $value)) {
                /* translators: %1$s is replaced with field label and %2$s is replaced with max value */
                return sprintf(__('%1$s must not be greater than %2$s', 'extra-checkout-fields-for-woocommerce'), $field->label, $field->max);
            }
        }

        return null;
    }

    /**
     * Validate Select and Radio group Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateOption($field, $value)
    {
        $options = [];
        foreach($field->values as $option) {
            $options[] = $option->value;
        }

        if (!in_array($_POST[$field->name], $options)) {
            /* translators: %s is replaced with field label */
            return sprintf(__('%s is invalid', 'extra-checkout-fields-for-woocommerce'), $field->label);
        }

        return null;
    }

    /**
     * Validate Checkbox group Input field.
     * 
     * @param object $field
     * @param string $value
     * @return string|null message
     */
    public static function validateOptions($field, $value)
    {
        $options = [];
        foreach($field->values as $option) {
            $options[] = $option->value;
        }

        foreach($_POST[$field->name] as $value) {
            if (!in_array($value, $options)) {
                /* translators: %s is replaced with field label */
                return sprintf(__('%s is invalid', 'extra-checkout-fields-for-woocommerce'), $field->label);
            }
        }
        
        return null;
    }
}
