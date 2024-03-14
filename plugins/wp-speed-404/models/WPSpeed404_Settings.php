<?php

if(!defined('ABSPATH')) exit;

class WPSpeed404_Settings {
    private static $_instance = null;
    public static function instance() {
        if (self::$_instance == null) {
            self::$_instance = new WPSpeed404_Settings();
        }
        return self::$_instance;
    }


    protected $_option = 'WPSpeed404_Settings';

    protected $_default = array(
        'mode' => 'off',
        'include_wp_includes' => true,
        'include_wp_admin' => true,
        'notify_email' => '',
    );

    //$options stores all our serializable fields
    protected $_properties = null;

    //$_suspend_autosave is used to prevent auto saving
    protected $_suspend_autosave = false;

    public function __set($name, $value) {
        $this->_properties[$name] = $value;
        $this->save();
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_properties)) {
            return $this->_properties[$name];
        }

        if (isset($this->_default)) {
            if (array_key_exists($name, $this->_default)) {
                return $this->_default[$name];
            }
        }

        throw new Exception('Attempt to access non-existent property: ' . $name);
    }

    public function __isset($name) {
        return isset($this->_properties[$name]);
    }

    public function __unset($name) {
        unset($this->_properties[$name]);
    }

    public function __construct() {
        $this->_properties = get_site_option($this->_option, array());
    }

    public function save() {
        update_site_option($this->_option, $this->_properties);
    }
}