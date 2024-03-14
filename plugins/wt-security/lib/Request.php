<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
    if (!headers_sent()) {
        header('HTTP/1.1 403 Forbidden');
    }
		die("Protected By WebTotem!");
}

/**
 * HTTP request handler.
 *
 */
class WebTotemRequest extends WebTotem {

	/**
	 * Returns the value of the _GET, _POST or _REQUEST key.
	 *
	 * @param  array  $list
	 *     The array where the specified key will be searched.
	 * @param  string $key
	 *     Name of the variable contained in _POST.
	 *
	 * @return array|string|bool Value from the global _GET or _POST variable.
	 */
    private static function request($list = array(), $key = '') {
        if (!is_array($list) || !isset($list[$key])) {
            return false;
        }

	      // raw request parameter
        $key_value = $list[$key];

        if(is_array($key_value)){
        	
        	foreach ($key_value as $key => $value){
		        $key_value[$key] = self::escape($value);
	        }

	        return $key_value;
        }
        else {
	        return self::escape($key_value);
        }

    }

    /**
     * Returns the value stored in the index in the global variable _GET.
     *
     * @param  string $key
     *     Name of the variable contained in _GET.
     *
     * @return array|string
     *     Value from the global _GET variable.
     */
    public static function get($key = '') {
        return self::request($_GET, $key);
    }

    /**
     * Returns the value stored in the index in the global _POST variable.
     *
     * @param  string $key
     *   Name of the variable contained in _POST.
     *
     * @return array|string
     *    Value from the global _POST variable.
     */
    public static function post($key = '') {
        return self::request($_POST, $key);
    }

    /**
     * Returns the value stored in the index in the global _REQUEST variable.
     *
     * @param  string $key
     *    Name of the variable contained in _REQUEST.
     *
     * @return array|string
     *    Value from the global _REQUEST variable.
     */
    public static function getOrPost($key = '') {
        return self::request($_REQUEST, $key);
    }
}
