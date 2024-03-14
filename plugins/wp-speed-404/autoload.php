<?php

if(!defined('ABSPATH')) exit;

if(!class_exists('WPSpeed404_autoload')){
    class WPSpeed404_autoload {
        private static $_autoload = array(
            'WPSpeed404_SettingsController' => 'controllers/WPSpeed404_SettingsController.php',
            'WPSpeed404_Settings'           => 'models/WPSpeed404_Settings.php',
            'WPSpeed404_Log'                => 'models/WPSpeed404_Log.php',
            'WPSpeed404_Engine'             => 'include/WPSpeed404_Engine.php'
        );

        public static function autoload($class) {
            if(array_key_exists($class, WPSpeed404_autoload::$_autoload)){
                include WPSpeed404_autoload::$_autoload[$class];
            }
        }
    }
    spl_autoload_register(array('WPSpeed404_autoload', 'autoload'));
}