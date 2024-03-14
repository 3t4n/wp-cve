<?php

if (!function_exists('ifw_var_to_array')) {
    /**
     * @param $var
     * @param null $callback
     * @return array
     */
    function ifw_var_to_array($var, $callback = null, array $emptyDefault = []) {
        if (!is_array($var)) {
            if (empty($var)) {
                $var = $emptyDefault;
            } else {
                if (is_string($var) && strpos($var, ',') !== false) {
                    $var = array_map('trim', explode(',', $var));
                    $var = array_filter($var, function ($v) {
                        return $v !== '';
                    });
                } else {
                    $var = array($var);
                }
            }
        }
        if (is_callable($callback)) {
            $var = array_map($callback, $var);
        } elseif (is_array($callback)) {
            foreach ($callback as $cb) {
                if (is_callable($cb)) {
                    $var = array_map($cb, $var);
                }
            }
        }
        return $var;
    }
}

if (!function_exists('ifw_filter_scalar')) {
    /**
     * @param $scalar
     * @param array $valid
     * @param null $default
     * @param bool $strict
     * @return mixed|null
     */
    function ifw_filter_scalar($scalar, array $valid, $default = null, $strict = true) {
        $result = $default;
        if (in_array($scalar, $valid, $strict)) {
            $result = $scalar;
        }
        return $result;
    }
}

if (!function_exists('ifw_remove_whitespace_between_tags')) {
    /**
     * @param $html
     * @return null|string|string[]
     */
    function ifw_remove_whitespace_between_tags($html)
    {
        $html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);
        return $html;
    }
}

if (!function_exists('ifw_array_shift_assoc')) {
    /**
     * @param $arr
     * @return array
     */
    function ifw_array_shift_assoc(&$arr) {
        reset($arr);
        $return = array(key($arr) => current($arr));
        unset($arr[key($arr)]);
        return $return;
    }
}

if (!function_exists('ifw_array_get_col')) {
    /**
     * collects a column/index of an array
     * @param  array $data  the soure array
     * @param  string $col  the column/index to collect
     * @param  string $type the variable type, leave blank for original type
     * @return array
     */
    function ifw_array_get_col(array $data, $col, $type = null) {

        $result = array();
        foreach ($data as $row) {
            if (array_key_exists($col, $row)) {
                $value = $row[$col];
                if ($type !== null) {
                    $value = settype($value, $type);
                }
                array_push($result, $value);
            }
        }
        return $result;
    }
}

if (!function_exists('ifw_filter_scalar')) {
    /**
     * @param $scalar
     * @param array $valid
     * @param null $default
     * @param bool $strict
     * @return mixed|null
     */
    function ifw_filter_scalar($scalar, array $valid, $default = null, $strict = true) {
        $result = $default;
        if (in_array($scalar, $valid, $strict)) {
            $result = $scalar;
        }
        return $result;
    }
}

if (!function_exists('ifw_is_empty')) {
    function ifw_is_empty($var) {
        return empty($var);
    }
}

if (!function_exists('ifw_is_not_empty')) {
    function ifw_is_not_empty($var) {
        return !empty($var);
    }
}

if (!function_exists('ifw_raise_memory_limit')) {
    function ifw_raise_memory_limit($to = 128, $from = null) {
        if (empty($from)) {
            $from = $to;
        }
        $memory_limit = ifw_return_bytes(ini_get('memory_limit'));
        if ($memory_limit < ($from * 1024 * 1024)) {
            // Memory insufficient
            ini_set('memory_limit', $to . 'M');
        }
    }
}

if (!function_exists('ifw_return_bytes')) {
    function ifw_return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}

if (!function_exists('ifw_str_extract_numbers')) {
    /**
     * @param $str
     * @return null|string|string[]
     */
    function ifw_str_extract_numbers($str)
    {
        return (int)preg_replace("/[^0-9]/", '', $str);
    }
}

if (!function_exists('ifw_is_cli')) {
    /**
     * @return bool
     */
    function ifw_is_cli()
    {
        $sapiname = php_sapi_name();

        if (in_array($sapiname, array('apache2handler', 'fpm-fcgi', 'apache', 'apache2filter'))) {
            // first exclude some common webserver interface types because on some servers 'cli' has not always been named 'cli'
            return false;
        } elseif ($sapiname === 'cli') {
            return true;
        }
        return false;
    }
}