<?php
/*
Plugin Name: Metaphor Members
Description: Adds a custom post type to easily create a collection of members. Add a member archive to any page with shortcodes.
Version: 1.1.9
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
License: GPL2
*/

/*
Copyright 2012 Metaphor Creations  (email : joe@metaphorcreations.com)

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

/*
Fugue Icons

Copyright (C) 2011 Yusuke Kamiyamane. All rights reserved.
The icons are licensed under a Creative Commons Attribution
3.0 license. <http://creativecommons.org/licenses/by/3.0/>

<http://p.yusukekamiyamane.com/>
*/




/**Define Widget Constants */
define ( 'MTPHR_MEMBERS_VERSION', '1.1.9' );
define ( 'MTPHR_MEMBERS_DIR', plugin_dir_path(__FILE__) );
define ( 'MTPHR_MEMBERS_URL', plugins_url().'/mtphr-members' );




// Load the general functions
require_once( MTPHR_MEMBERS_DIR.'includes/scripts.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/post-types.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/taxonomies.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/helpers.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/filters.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/functions.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/display.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/shortcodes.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/deprecated.php' );
require_once( MTPHR_MEMBERS_DIR.'includes/wpml.php' );

// Load the admin functions
if ( is_admin() ) {
	require_once( MTPHR_MEMBERS_DIR.'includes/admin/meta-boxes.php' );
	require_once( MTPHR_MEMBERS_DIR.'includes/admin/settings.php' );
	require_once( MTPHR_MEMBERS_DIR.'includes/admin/shortcode-gen.php' );
	
	require_once( MTPHR_MEMBERS_DIR.'includes/admin/generators/archive.php' );
	//require_once( MTPHR_MEMBERS_DIR.'includes/admin/generators/gallery.php' );
}




register_activation_hook( __FILE__, 'mtphr_members_activation' );
/**
 * Register the post type & flush the rewrite rules
 *
 * @since 1.0.0
 */
function mtphr_members_activation() {
	mtphr_members_posttype();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'mtphr_members_deactivation' );
/**
 * Flush the rewrite rules
 *
 * @since 1.0.0
 */
function mtphr_members_deactivation() {
	flush_rewrite_rules();
}

