<?php
if( ! function_exists( 'berocket_get_memory_limit' ) ){
    function berocket_get_memory_limit($memory_limit = '') {
        if( ! empty($memory_limit) ) {
            $val = $memory_limit;
        } elseif( function_exists('ini_get') ) {
            $val = ini_get('memory_limit');
        } else {
            $val = '128M';
        }
        $val = trim($val);
        if( preg_match('#([0-9]+)[\s]*([a-z]+)#i', $val, $matches) != 1 ) {
            preg_match('#([0-9]+)[\s]*([a-z]+)#i', '128M', $matches);
        }
        $last = '';
        if(isset($matches[2])){
            $last = $matches[2];
        }
        if(isset($matches[1])){
            $val = (int) $matches[1];
        }
        switch (strtolower($last))
        {
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }
        return (int) $val;
    }
}
if( ! function_exists( 'berocket_get_memory_data' ) ){
    function berocket_get_memory_data($check_value = 0, $memory_limit = 0) {
        $memory_limit = berocket_get_memory_limit($memory_limit);
        $memory_usage = memory_get_usage();
        $memory_left = $memory_limit - $memory_usage;
        $memory_check = $memory_left - $check_value;
        return array('memory_limit' => $memory_limit, 'memory_usage' => $memory_usage, 'memory_left' => $memory_left, 'memory_check' => $memory_check);
    }
}
