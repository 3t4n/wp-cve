<?php

class LPageryMemoryUtils {

    public static function lpagery_get_memory_usage() {
        $memory = array();
        $wp_limit = 9999999999999999999999;
        if (WP_MAX_MEMORY_LIMIT) {
            $wp_limit = self::format_wp_limit(WP_MAX_MEMORY_LIMIT);
        }
        $system_limit =  self::format_wp_limit(ini_get('memory_limit'));

        if(!empty($wp_limit) && !empty($system_limit)) {
            $memory['limit'] = min($wp_limit, $system_limit);
        } else {
            $memory['limit'] = $wp_limit;
        }

        //$memory['limit'] =  self::format_wp_limit("15M");
        //$memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
        // Changed memory_get_usage to memory_get_peak_usage
        $memory['usage'] = function_exists('memory_get_peak_usage') ? memory_get_peak_usage(TRUE) : 0;

        if ( !empty($memory['usage']) && !empty($memory['limit']) ) {
            $memory['percent'] = round ($memory['usage'] / $memory['limit'] * 100, 0);
        } else {
            $memory['percent'] = 0;
        }
        //$memory['percent'] = array_rand([1,2,3,4,5,6,7,8,9]);
        $memory['pretty_usage'] = self::human_filesize($memory['usage']);
        $memory['pretty_limit'] = self::human_filesize($memory['limit']);
        return $memory;
    }

    private static function human_filesize($bytes, $dec = 0): string {

        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor == 0) $dec = 0;


        return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);

    }

    private static function format_wp_limit( $size ) {
        $value  = substr( $size, -1 );
        $return = substr( $size, 0, -1 );

        $return = (int)$return; // Solved: PHP 7.1 Notice: A non well formed numeric value encountered
        switch ( strtoupper( $value ) ) {
            case 'G' :
                $return*= 1024;
            case 'M' :
                $return*= 1024;
            case 'K' :
                $return*= 1024;
        }
        return $return;
    }

}