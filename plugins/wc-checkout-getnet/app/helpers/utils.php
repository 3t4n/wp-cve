<?php

/**
 * Function to get the client ip address
 *
 * @return string The Ip address
 */
if (!function_exists('getClientIpAddress')) {
    function getClientIpAddress(): string {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ipAddress, $validUniqueIp);

        if( empty($validUniqueIp) ) {
            return '';
        }

        return current($validUniqueIp);
    }
}

/**
 * Return an json to object recursively
 *
 * @param string $jsonData
 * @return object
 */

if (!function_exists('jsonToObject')) {
    function jsonToObject( $jsonData ) : object {
        return json_decode(json_encode(json_decode($jsonData)));
    }
}

/**
 * Return an Array to object recursively
 *
 * @param string $jsonData
 * @return object
 */
if (!function_exists('arrayToObject')) {
    function arrayToObject( $array ) : object {
        return json_decode(json_encode($array), FALSE);
    }
}

/**
 * Return the array of plugin info
 *
 * @return array|bool
 */
if (!function_exists('getPluginInfo')) {
    function getPluginInfo() {
        $pluginDir = plugin_dir_path(dirname( __FILE__ , 2 ));

        if( !file_exists($pluginDir.'wc-checkout-getnet.php') ) {
            return false;
        }

        return get_plugin_data($pluginDir.'wc-checkout-getnet.php');
    }
}

/**
 * Return UUID v4
 *
 * @return string
 */
if (!function_exists('getUUID')) {
    function getUUID() {
        /* 32 random HEX + space for 4 hyphens */
        $out = bin2hex(random_bytes(18));

        $out[8]  = "-";
        $out[13] = "-";
        $out[18] = "-";
        $out[23] = "-";

        /* UUID v4 */
        $out[14] = "4";
        
        /* variant 1 - 10xx */
        $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

        return $out;
    }
}
