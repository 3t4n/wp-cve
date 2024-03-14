<?php
/**
 * This extension provides the functionality for the if-so gutenberg block(wp >v5.0).
 * This class is the base from which all if-so blocks inherit
 *
 * @since      1.4.4
 * @package    IfSo
 * @subpackage IfSo/extensions
 * @author Nick Martianov
 */
namespace IfSo\Extensions\IfSoGutenbergBlock;

abstract class IfSoGutenbergBlockBase{
    private static $instance;

    private static $subclasses = [];

    protected $gutenberg_exists = false;

    private function __construct(){
        if(function_exists('has_blocks') || function_exists('is_gutenberg_page'))
            $this->gutenberg_exists = true;
    }

    public static function get_instance(){
        $class = get_called_class();

        if(!isset(self::$subclasses[$class])){
            self::$subclasses[$class] = new static();
        }

        return self::$subclasses[$class];
    }

    private function __clone() {}
    //private function __wakeup() {} Avoid warning in php8 +

    abstract public function enqueue_block_assets();

    abstract public function enqueue_block_styles();
}