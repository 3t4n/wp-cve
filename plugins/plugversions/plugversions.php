<?php
/*
Plugin Name: PlugVersions
Description: it retains up to three versions when you update a plugin. It works also with premium and custom plugins.
Author: Jose Mortellaro
Author URI: https://josemortellaro.com
Domain Path: /languages/
Text Domain: plugversions
Version: 0.0.5
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
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

//Definitions.
define( 'PLUGIN_REVISIONS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'PLUGIN_REVISIONS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

if( is_admin() ){
  require_once PLUGIN_REVISIONS_PLUGIN_DIR.'/admin/pr-admin.php';
}

add_filter( 'site_transient_update_plugins',function( $obj ){
  /**
   * Remove plugin revisions from the update notifications
   *
   * @since  0.0.1
   */ 
  if( isset( $obj ) && is_object( $obj ) && isset( $obj->response ) ){
    $response = $obj->response;
    $key = eos_plugin_revision_key();
    foreach( $response as $p => $arr ){
      if( false !== strpos( $p,'pr-'.$key.'-' ) && isset( $response[$p] ) ){
        unset( $response[$p] );
      }
    }
    $obj->response = $response;
  }
  return $obj;
} );

/**
 * Return revision key
 *
 * @since  0.0.1
 */
function eos_plugin_revision_key(){
  $opts = get_site_option( 'plugin_revisions' );
  if( $opts && isset( $opts['time' ] ) ){
    $key = substr( md5( sanitize_text_field( $opts['time' ] ) ),0,8 );
    return $key;
  }
  else{
    $time = time();
    $opts['time'] = $time;
    update_site_option( 'plugin_revisions',$opts );
    return substr( md5( $time ),0,8 );
  }
  return false;
}