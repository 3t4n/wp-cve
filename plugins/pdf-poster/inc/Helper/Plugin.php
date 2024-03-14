<?php
namespace PDFPro\Helper;

class Plugin{

    public static $version = PDFPRO_VER;
    public static $latestVersion = null;

    public static function dir(){
        return plugin_dir_url(__FILE__);
    }

    public static function path(){
        return plugin_dir_path(__FILE__);
    }

    public static function version(){
        return self::$version;
    }

    public static function getLatestVersion(){
        $checked = get_option('flcbplsccheck', 0);
        if($checked < current_time('d')){
            update_option('flcbplsccheck', current_time('d'));
            $version = wp_remote_get('https://bplugins.com/wp-json/version/v1/product/46958');

            if(!is_array($version) || !array_key_exists('body', $version)) return false;
            $version = json_decode($version['body']);
            if(!isset($version->version)) return false;
            update_option('flcbplscver', $version->version);
        }

        return get_option('flcbplscver', self::version());
    }
}