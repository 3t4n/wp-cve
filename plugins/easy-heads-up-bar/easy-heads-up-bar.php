<?php
/*
Plugin Name: Easy Heads Up Bar
Plugin URI: http://www.beforesite.com/plugins/easy-heads-up-bar
Description: An Easy to use notification (heads up) bar for your WordPress website with a linked call to action
Version: 2.1.7
Author: Greenweb
Author URI: http://www.greenvillweb.us 
*/

/**
 * Copyright (c) 2011 Greenville Web Design. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

  // ehb_ is the Easy Heads Up Bar prefix 
  
  if (!function_exists ('add_action')){
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
  }
   
  $ehb_plugin_loc         = plugin_dir_url( __FILE__ );
  $ehb_plugname           = "Easy Heads Up Bar";
  $ehb_plug_shortname     = "easy_heads_up_bar";
  $ehb_the_web_url        = get_bloginfo('url');
  $ehb_the_blog_name      = get_bloginfo('name');
  $ehb_the_default_email  = get_bloginfo('admin_email');
  $ehb_meta_prefix        = '_ehb_';
  
  // check for ssl 
  if ( preg_match( '/^https/', $ehb_plugin_loc ) && !preg_match( '/^https/', get_bloginfo('url') ) )
    $ehb_plugin_loc = preg_replace( '/^https/', 'http', $ehb_plugin_loc );
  
  define( 'EHB_URL',            plugin_dir_url(__FILE__) );
  define( 'EHB_VERSION',        '2.1.7' );
  
  include 'lib/ehb-utility-functions.php';
  include 'lib/ehb-admin.php';
  include 'lib/ehb-metaboxs.php';

  $ehbAdmin = new ehbAdmin();

  // load front end 
  include 'lib/ehb-frontend.php';

  // @todo remove this on next major update
  include 'lib/ehb-migrate-old-data.php';

