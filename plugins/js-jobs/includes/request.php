<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSrequest {
    /*
     * Check Request from both the Get and post method
     */

    static function getVar($variable_name, $method = null, $defaultvalue = null, $typecast = null) {
        $value = null;
        if ($method == null) {
            if (isset($_GET[$variable_name])) {
                if(is_array($_GET[$variable_name])){
                    $value = filter_var_array($_GET[$variable_name]);
                }else{
                    $value = jsjobs::sanitizeData($_GET[$variable_name]);
                }
            } elseif (isset($_POST[$variable_name])) {
                if(is_array($_POST[$variable_name])){
                    $value = filter_var_array($_POST[$variable_name]);
                }else{
                    $value = jsjobs::sanitizeData($_POST[$variable_name]);
                }
            } elseif (get_query_var($variable_name)) {
                $value = get_query_var($variable_name);
            } elseif (isset(jsjobs::$_data['sanitized_args'][$variable_name]) && jsjobs::$_data['sanitized_args'][$variable_name] != '') {
                $value = jsjobs::$_data['sanitized_args'][$variable_name];
            }
        } else {
            $method = jsjobslib::jsjobs_strtolower($method);
            switch ($method) {
                case 'post':
                    if (isset($_POST[$variable_name]))
                        if (is_array($_POST[$variable_name])) {
                            $value = filter_var_array($_POST[$variable_name]);
                        }else{
                            $value = jsjobs::sanitizeData($_POST[$variable_name]);
                        }
                    break;
                case 'get':
                    if (isset($_GET[$variable_name]))
                        if (is_array($_GET[$variable_name])) {
                            $value = filter_var_array($_GET[$variable_name]);
                        }else{
                            $value = jsjobs::sanitizeData($_GET[$variable_name]);
                        }
                    break;
            }
        }
        if ($typecast != null) {
            $typecast = jsjobslib::jsjobs_strtolower($typecast);
            switch ($typecast) {
                case "int":
                    $value = (int) $value;
                    break;
                case "string":
                    $value = (string) $value;
                    break;
            }
        }
        if ($value == null)
            $value = $defaultvalue;
        if(!is_array($value)){
            $value = jsjobslib::jsjobs_stripslashes($value);
        }
        
        return $value;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function get($method = null) {
        $array = null;
        if ($method != null) {
            $method = jsjobslib::jsjobs_strtolower($method);
            switch ($method) {
                case 'post':
                    $array = filter_var_array($_POST);
                    break;
                case 'get':
                    $array = filter_var_array($_GET);
                    break;
            }
            //$array = array_map('stripslashes',$array);
            foreach($array as $key=>$value){
                if(is_string($value)){
                    $array[$key] = jsjobslib::jsjobs_stripslashes($value);
                }
            }
        }
        return $array;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function getLayout($layout, $method, $defaultvalue) {
        $layoutname = null;
        if ($method != null) {
            $method = jsjobslib::jsjobs_strtolower($method);
            switch ($method) {
                case 'post':
                    $layoutname = jsjobs::sanitizeData($_POST[$layout]);
                    break;
                case 'get':
                    $layoutname = jsjobs::sanitizeData($_GET[$layout]);
                    break;
            }
        } else {
            if (isset($_POST[$layout]))
                $layoutname = jsjobs::sanitizeData($_POST[$layout]);
            elseif (isset($_GET[$layout]))
                $layoutname = jsjobs::sanitizeData($_GET[$layout]);
            elseif (get_query_var($layout))
                $layoutname = get_query_var($layout);
            elseif (isset(jsjobs::$_data['sanitized_args'][$layout]) && jsjobs::$_data['sanitized_args'][$layout] != '')
                $layoutname = jsjobs::$_data['sanitized_args'][$layout];
        }
        if ($layoutname == null) {
            $layoutname = $defaultvalue;
        }
        if (is_admin()) {
            $layoutname = 'admin_' . $layoutname;
        }
        return $layoutname;
    }

    static function recursive_sanitize_text_field($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = Self::recursive_sanitize_text_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }

        return $array;
    }    

}

?>
