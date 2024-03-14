<?php

/*
 * Plugin Name: Lucas String Replace
 * Plugin URI: https://string-replace.lucas.solutions/
 * Description: Change anything: Lucas String Replace takes the final output of WordPress and replaces the defined strings with another string
 * Version: 2.0.5
 * Author: lucasstad
 * Author URI: https://string-replace.lucas.solutions/
 * License: GPL2
 * 
 * Lucas String Replace is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Lucas String Replace is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Lucas String Replace. If not, see https://www.google.com/?q=GNU+General+Public+License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'includes/tools.php';
require_once 'includes/class-lucas-string-replace.php';
require_once 'includes/class-lucas-string-replace-settings.php';
require_once 'includes/class-lucas-string-replace-replacer.php';


function lucas_string_replace(){

    $instance = Lucas_String_Replace::instance( '2.0.5', __FILE__ );

    if( is_null( $instance->settings ) ){
        $instance->settings = Lucas_String_Replace_Settings::instance( $instance );
    }

    if( is_null( $instance->replacer ) ){
        $instance->replacer = Lucas_String_Replace_Replacer::instance( $instance );
    }

    return $instance;
}

lucas_string_replace();