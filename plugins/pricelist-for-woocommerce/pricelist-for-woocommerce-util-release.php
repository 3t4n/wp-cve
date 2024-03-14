<?php 

    if (!class_exists('pricelist_wc_profiler')) {

        class pricelist_wc_profiler {
            public static function clear() {
                return;
            }
            
            public static function msg($msg) {
                return;
            }
            
            public static function start($name) {
                return;
            }
            
            public static function stop($name, $child = null) {
                return;
            }
            
            public static function report($html = false, $mintime = 0) {
                return '';
            }
            
            public static function ignore($ignore, $keyword = null) {
                return false;
            }
        }
    }
?>