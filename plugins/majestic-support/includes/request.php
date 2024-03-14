<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_request {
    /*
     * Check Request from both the Get and post method
     */

    static function MJTC_getVar($variable_name, $method = null, $defaultvalue = null, $typecast = null) {
        // nonce varification start
        // $nonce = majesticsupport::$_data['sanitized_args']['_wpnonce'];
        // if (! wp_verify_nonce( $nonce, 'VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE') ) {
        //     die( 'Security check Failed' );
        // }
        // nonce varification end

        $value = null;
        if ($method == null) {
            if (isset($_GET[$variable_name])) {
                if(is_array($_GET[$variable_name])){
                    $value = majesticsupport::MJTC_sanitizeData($_GET[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                }else{
                    $value = majesticsupport::MJTC_sanitizeData($_GET[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                }
            } elseif (isset($_POST[$variable_name])) {
                if(is_array($_POST[$variable_name])){
                    $value = majesticsupport::MJTC_sanitizeData($_POST[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                }else{
                    $value = majesticsupport::MJTC_sanitizeData($_POST[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                }
            } elseif (get_query_var($variable_name)) {
                $value = get_query_var($variable_name);
            } elseif (isset(majesticsupport::$_data['sanitized_args'][$variable_name]) && majesticsupport::$_data['sanitized_args'][$variable_name] != '') {
                $value = majesticsupport::$_data['sanitized_args'][$variable_name];
            }
        } else {
            $method = MJTC_majesticsupportphplib::MJTC_strtolower($method);
            switch ($method) {
                case 'post':
                    if (isset($_POST[$variable_name]))
                        if (is_array($_POST[$variable_name])) {
                            $value = majesticsupport::MJTC_sanitizeData($_POST[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                        }else{
                            $value = majesticsupport::MJTC_sanitizeData($_POST[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                        }
                    break;
                case 'get':
                    if (isset($_GET[$variable_name]))
                        if (is_array($_GET[$variable_name])) {
                            $value = majesticsupport::MJTC_sanitizeData($_GET[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                        }else{
                            $value = majesticsupport::MJTC_sanitizeData($_GET[$variable_name]);// MJTC_sanitizeData() function uses wordpress santize functions
                        }
                    break;
            }
        }
        if ($typecast != null) {
            $typecast = MJTC_majesticsupportphplib::MJTC_strtolower($typecast);
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
            if ($value != null){
                $value = MJTC_majesticsupportphplib::MJTC_stripslashes($value);
            }
        }
        
        return $value;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function get($method = null) {
        // nonce varification start
        $nonce = majesticsupport::$_data['sanitized_args']['_wpnonce'];
        if (! wp_verify_nonce( $nonce, 'VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE') ) {
            die( 'Security check Failed' );
        }
        // nonce varification end

        $array = null;
        if ($method != null) {
            $method = MJTC_majesticsupportphplib::MJTC_strtolower($method);
            switch ($method) {
                case 'post':
                    $array = majesticsupport::MJTC_sanitizeData($_POST);// MJTC_sanitizeData() function uses wordpress santize functions
                    break;
                case 'get':
                    $array = majesticsupport::MJTC_sanitizeDatay($_GET);// MJTC_sanitizeData() function uses wordpress santize functions
                    break;
            }
            foreach($array as $key=>$value){
                if(is_string($value)){
                    $array[$key] = MJTC_majesticsupportphplib::MJTC_stripslashes($value);
                }
            }
        }
        return $array;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function MJTC_getLayout($layout, $method, $defaultvalue) {
        // nonce varification start
        $nonce = majesticsupport::$_data['sanitized_args']['_wpnonce'];
        if (! wp_verify_nonce( $nonce, 'VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE') ) {
            die( 'Security check Failed' );
        }
        // nonce varification end

        $layoutname = null;
        if ($method != null) {
            $method = MJTC_majesticsupportphplib::MJTC_strtolower($method);
            switch ($method) {
                case 'post':
                    $layoutname = majesticsupport::MJTC_sanitizeData($_POST[$layout]);// MJTC_sanitizeData() function uses wordpress santize functions
                    break;
                case 'get':
                    $layoutname = majesticsupport::MJTC_sanitizeData($_GET[$layout]);// MJTC_sanitizeData() function uses wordpress santize functions
                    break;
            }
        } else {
            if (isset($_POST[$layout]))
                $layoutname = majesticsupport::MJTC_sanitizeData($_POST[$layout]);// MJTC_sanitizeData() function uses wordpress santize functions
            elseif (isset($_GET[$layout]))
                $layoutname = majesticsupport::MJTC_sanitizeData($_GET[$layout]);// MJTC_sanitizeData() function uses wordpress santize functions
            elseif (get_query_var($layout))
                $layoutname = get_query_var($layout);
            elseif (isset(majesticsupport::$_data['sanitized_args'][$layout]) && majesticsupport::$_data['sanitized_args'][$layout] != '')
              $layoutname = majesticsupport::$_data['sanitized_args'][$layout];
        }
        if ($layoutname == null) {
            $layoutname = $defaultvalue;
        }
        if (is_admin()) {
            $layoutname = 'admin_' . $layoutname;
        }
        return $layoutname;
    }

}

?>
