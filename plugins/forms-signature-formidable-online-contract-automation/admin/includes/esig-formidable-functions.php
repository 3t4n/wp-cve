<?php

if (!function_exists('EFF_GET')) {

    function EFF_GET($key) {
        $value = filter_input(INPUT_GET, $key, FILTER_DEFAULT);
        return sanitize_text_field($value);
    }

}

if (!function_exists('esig_formidable_get')) {

    function esig_formidable_get($name, $array = null) {

        if (!isset($array)) {
            return EFF_GET($name);
        }

        if (is_array($array)) {
            if (isset($array[$name])) {
                $value = wp_unslash($array[$name]);
                if(is_array($value)){
                    return $value;
                }
                return sanitize_text_field($value);
            }
            return false;
        }

        if (is_object($array)) {
            if (isset($array->$name)) {
                if(is_array($array->$name)){
                    return $array->$name;
                }
                return sanitize_text_field(wp_unslash($array->$name));
            }
            return false;
        }

        return false;
    }

}

if (!function_exists('esig_formidable_sanitize_init')) {
    function esig_formidable_sanitize_init($number) {
     return filter_var($number, FILTER_SANITIZE_NUMBER_INT);    
    }
}

?>