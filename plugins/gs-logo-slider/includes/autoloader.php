<?php

namespace GSLOGO;

// if direct access than exit the file.
defined('ABSPATH') || exit;

class Autoloader {
    
    /**
     * Instance of Autoloader
     * 
     * @var Autoloader
     */
    private static $instance = null;

    /**
     * Classes map.
     * Maps classes to file names.
     * 
     * @access private
     * @static
     * @var    array Classes array use by GS Portfolio Portfolio.
     */
    private static $classesMap;

    /**
     * Classes directory.
     * Maps classes to file names.
     * 
     * @access private
     * @static
     * 
     * Classes folder used for @package GS Portfolio Portfolio.
     */
    private static $SOURCE_DIRECTORY = GSL_PLUGIN_DIR;

    /**
     * Classmap file for Autoloader
     * 
     * @access private
     * @static
     * 
     * Classes array used for @package GS Portfolio Portfolio.
     */
    private static $CLASS_MAPS_FILE = __DIR__ . '/classmaps.php';

    /**
     * Get a single instace of Autoloader
     * 
     * @return Autoloader
     */
    public static function init() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Automatically get invoked when initilize the Autoloader.
     */
    public function __construct() {
        spl_autoload_register( array( $this, 'autoload' ) );
    }

    /**
     * Autoload function ensure that, every class you called is loaded properly.
     * 
     * @param String $class
     * @access private
     * 
     * @return void
     */
    private function autoload( $class ) {
        
        if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
            return;
        }

        $relative_class_name = preg_match( '/.*\\\\([\\w]+)/', $class, $matches );
        
        if ( count( $matches ) > 1 ){
            $relative_class_name = $matches[1];
        }

        if ( ! class_exists( $class ) ){
            self::load_class( $relative_class_name );
        }
    }

    /**
     * Load class.
     * For a given class name, require the class file.
     *
     * @access private
     * @static
     * 
     * @param string $relative_class_name Class name.
     */
    private static function load_class( $relative_class_name ) {
        $classesMap = self::get_classes_map();

        if ( isset( $classesMap[ $relative_class_name ] ) ) {
            $filename = self::$SOURCE_DIRECTORY . $classesMap[ $relative_class_name ];
            if ( is_readable( $filename ) ) {
                require $filename;
            }
        }
    }

    /**
     * Get the class map, if its not set than load the classmap file.
     * 
     * @return void
     */
    private static function get_classes_map() {
        if ( ! self::$classesMap ) {
            self::init_classes_map();
        }
        return self::$classesMap;
    }

    /**
     * Loader of the classmap file.
     * 
     * @return array of classes.
     */
    private static function init_classes_map() {
        self::$classesMap = require_once self::$CLASS_MAPS_FILE;
    }
}

Autoloader::init();