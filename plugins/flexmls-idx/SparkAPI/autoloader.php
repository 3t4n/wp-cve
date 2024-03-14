<?php

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

// Make sure that the Spark API Core class is loaded before anything else.
require_once( 'Core.php' );

spl_autoload_register( function( $className ){
    call_user_func_array("flexmls_autoloader", array($className, __DIR__));
} );