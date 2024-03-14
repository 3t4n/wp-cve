<?php

if (!function_exists('wupo_log')) {

    function wupo_log($label = '', $log = '') {
        if (WP_DEBUG_LOG) {
            if ($label !== '') {
                $label .= ': ';
            }
            if (is_array($log) || is_object($log)) {
                error_log($label.print_r($log, true));
            } else {
                error_log($label.$log);
            }
        }
    }
}

?>