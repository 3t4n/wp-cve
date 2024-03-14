<?php

namespace WPT\RestrictContent;

use  WPTools\Pimple\Container ;
/**
 * Container
 */
class Loader extends Container
{
    /**
     *
     * @var mixed
     */
    public static  $instance ;
    public function __construct()
    {
        parent::__construct();
        $this['bootstrap'] = function ( $container ) {
            return new WP\Bootstrap( $container );
        };
        $this['divi_builder'] = function ( $container ) {
            return new Divi\Builder( $container );
        };
        $this['divi_section'] = function ( $container ) {
            return new Divi\Section( $container );
        };
        $this['restrictor_logged_in_user'] = function ( $container ) {
            return new Restrictors\LoggedInUser( $container );
        };
    }
    
    /**
     * Get container instance.
     */
    public static function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Loader();
        }
        return self::$instance;
    }
    
    /**
     * Plugin run
     */
    public function run()
    {
        register_activation_hook( $this['plugin_file'], [ $this['bootstrap'], 'register_activation_hook' ] );
        add_action( 'et_builder_framework_loaded', [ $this['divi_builder'], 'on_framework_loaded' ] );
        add_action( 'divi_extensions_init', [ $this['divi_builder'], 'extensions_init' ] );
    }

}