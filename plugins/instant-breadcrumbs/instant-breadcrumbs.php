<?php
/**
 * Plugin Name: Instant Breadcrumbs
 * Plugin URI: http://loseyourmarbles.co/instant-breadcrumbs
 * Description: Adds a breadcrumb trail to your nav menu, with no theme editing!
 * Version: 1.4.5
 * Author: Chris Nash
 * Author URI: http://loseyourmarbles.co/about-me
 * Text Domain: instant-breadcrumbs
 * Domain Path: /languages
 * License: GPL2
 */
/*  Copyright 2013-2014  Chris Nash  (email : chris@loseyourmarbles.co)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( dirname( __FILE__ ) . '/classes/loader.php' );

add_action( 'plugins_loaded', array( 'Instant_Breadcrumbs', 'load_textdomain' ) );

add_action( 'admin_init', array( 'Instant_Breadcrumbs_Settings', 'admin_init' ) );
add_action( 'admin_menu', array( 'Instant_Breadcrumbs_Settings', 'add_page' ) );

add_action( 'get_header', array( 'Instant_Breadcrumbs', 'hook' ) );

Instant_Breadcrumbs_Widget::hook();
