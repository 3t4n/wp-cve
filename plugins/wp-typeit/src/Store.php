<?php 

namespace TypeIt;

class Store {

    private static $data = array(
        'typeit_version' => '7.0.4',
        'option_defaults' => array(),
        'option_default_values' => array()
    );

    public static function get($key) {
        
        //-- If we've got a hard-coded value, just return that. 
        if(!empty(self::$data[$key])) {
            return self::$data[$key];
        }

        //-- Otherwise, do some magic to get what we want. 
        self::$data['option_defaults'] = require plugin_dir_path(__FILE__) . 'default-options.php';
        self::$data['option_default_values'] = array_map(function ($option) {
			return $option['default_value'];
        }, self::$data['option_defaults']);

        return !empty(self::$data[$key]) ? self::$data[$key] : null;
    }
}
