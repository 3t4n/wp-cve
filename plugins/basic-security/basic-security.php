<?php
/*
Plugin Name: Basic Security
Description: Ultra lightweight plugin to prevent Cross Site Scripting (XSS).
Author: Jose Mortellaro
Author URI: https://josemortellaro.com
Version: 0.0.3
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

//It sanitizes inputs coming from the URL
function jose_bs_xss_init(){
  if( isset( $_SERVER["PHP_SELF"] ) ){
    $_SERVER["PHP_SELF"] = htmlspecialchars( $_SERVER["PHP_SELF"] );
  }
  if( !defined( 'DOING_AJAX' ) && !empty( $_GET ) ){
    $remove = array( '(','.js' );
    foreach( $_GET as $k => $v ){
      if( is_string( $v ) ){
        $_GET[$k] = str_replace( $remove,'',htmlspecialchars( $v ) );
      }
    }
  }
}

jose_bs_xss_init();
