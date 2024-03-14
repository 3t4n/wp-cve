<?php

if(!defined('ABSPATH')) exit;

class WPSpeed404_Log {
    private static $_instance = null;
    public static function instance() {
        if (self::$_instance == null) {
            self::$_instance = new WPSpeed404_Log();
        }
        return self::$_instance;
    }


    protected $option = 'WPSpeed404_Log';
    protected $cache = null;

    public function __construct() {
        $this->cache = get_site_option($this->option, array());
        if(!is_array($this->cache)){
            $this->cache = array();
        }
    }

    public function save() {
        update_site_option($this->option, $this->cache);
    }

    public function clear() {
        $this->cache = array();
        $this->save();
    }

    public function log($url, $referrer){
        if(!array_key_exists($url, $this->cache)){
            $this->cache[$url] = array();
        }
        if($referrer != ''){
            if(!in_array($referrer, $this->cache[$url])){
                $this->cache[$url][] = $referrer;
            }
        }
        $this->save();
    }

    public function all(){
        return $this->cache;
    }

    public function count(){
        return count($this->cache);
    }

    public function format($html = false){
        if($this->count() == 0){
            return '';
        }

        $lines = array();

        foreach($this->all() as $url => $referrers) {
            $lines[] = $html ? esc_html($url) : $url;
            if(count($referrers) > 0) {
                $lines[] = '    ' . __('USED ON THESE PAGES', 'wp-speed-404');
                foreach ($referrers as $referrer) {
                    $lines[] = '    ' . ($html ? esc_html($referrer) : $referrer);
                }
            }
        }

        return implode("\n", $lines);
    }
}