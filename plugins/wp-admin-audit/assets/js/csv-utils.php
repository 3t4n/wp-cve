<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Script_Utils_CSV
{
    public static function getScript()
    {
        wp_register_script('wada_utils_csv_script', '');
        wp_enqueue_script('wada_utils_csv_script');
        // Credit MB.
        // Source https://stackoverflow.com/questions/1011628/detecting-regional-settings-list-separator-from-web
        // Not working in all browsers
        return "
            function getCsvSeparator() {
                var list = ['a', 'b'], str;
                if (list.toLocaleString) {
                    str = list.toLocaleString();
                    if (str.indexOf(';') > 0 && str.indexOf(',') == -1) {
                        return ';';
                    }
                }
                return ',';
            }
        ";
    }

}