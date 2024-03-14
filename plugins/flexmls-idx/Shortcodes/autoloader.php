<?php

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

spl_autoload_register( function( $className ){
    call_user_func_array("flexmls_autoloader", array($className, __DIR__));
} );