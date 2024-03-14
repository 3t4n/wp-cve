<?php

/*
Plugin Name: WP Speed 404
Description: Automatically find and repair broken content on your site that is overloading your web host.
Version: 1.0
Author: Michael Thomas
Author URI: https://imincomelab.com
License: GPLv2 or later
Text Domain: wp-speed-404
*/

if(!defined('ABSPATH')) exit;

include 'autoload.php';

class WPSpeed404{
    private static $_instance = null;
    public static function instance() {
        if (self::$_instance == null) {
            WPSpeed404::$view_path = dirname(__FILE__) . '/views/';
            WPSpeed404::$path = dirname(__FILE__);
            self::$_instance = new WPSpeed404();
            self::$_instance->setup();
        }
        return self::$_instance;
    }

    public static $slug = 'wp-speed-404';
    public static $title = 'Speed 404';
    public static $view_path = '';
    public static $path = '';

    public static function asset_url($file){
        return sprintf("%s%s/%s", plugin_dir_url(__FILE__), 'assets', $file);
    }

    public $engine = null;
    public $controller = null;

    public function setup(){
        $engine = WPSpeed404_Engine::instance();
        if(is_admin()) {
            WPSpeed404_SettingsController::instance();
        }

        register_activation_hook(__FILE__, array($engine, 'activate'));
        register_deactivation_hook(__FILE__, array($engine, 'deactivate'));
    }
}

WPSpeed404::instance();