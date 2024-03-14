<?php
namespace IfSo\Addons\Base;

require_once (__DIR__ . '/extension-initializer-base.class.php');

abstract class ExtensionMain{
    protected static $instance;
    private static $subclasses = [];
    protected function __construct(){}
    public static function get_instance(){
        $class = get_called_class();
        if(!isset(self::$subclasses[$class])){
            self::$subclasses[$class] = new static();
        }
        return self::$subclasses[$class];
    }

    public function get_update_settings(){}
}