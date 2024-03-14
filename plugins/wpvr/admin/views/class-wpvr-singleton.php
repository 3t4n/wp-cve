<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The abstract class for maintaing singleton design pattern
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/views
 */

/**
 * Orginial Singleton class
 */
abstract class Singleton {

    /**
     * Any Singleton class
     * 
     * @var Singleton[] $instances
     * @since 8.0.0
     */
    private static $instances = array();


    /**
     * Private construct to avoid 'new'
     * 
     * @since 8.0.0
     */
    private function __construct()
    {
        
    }


    /**
    * Get Instacne
    *
    * @return Singleton
    * @since 8.0.0
    */
    final public static function get_instance() {
        $class = get_called_class();
    
        if ( ! isset( $instances[ $class ] ) ) {
          self::$instances[ $class ] = new $class();
        }
    
        return self::$instances[ $class ];
    }


    /**
     * Declared to overwrite magic method __clone()
     * In order to prevent object cloning
     * 
     * @return void
     * @since 8.0.0
     */
    private function __clone()
    {
        // Do nothing
    }


    /**
     * Declared to overwrite magic method __sleep()
     * In order to avoid serialize instance
     * 
     * @return void
     * @since 8.0.0
     */
    public function __sleep()
    {
        // Do nothing
    }


    /**
     * Declared to overwrite magic method __wakeup()
     * In order to avoid unserialize instance
     * 
     * @return void
     * @since 8.0.0
     */
    public function __wakeup()
    {  
        // Do nothing
    }


    /**
     * Responsible for rendering setting tabs
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    abstract static public function render($postdata);

    /**
     * Responsible for rendering setting meta fields
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    abstract static public function render_meta_fields($postdata);
}