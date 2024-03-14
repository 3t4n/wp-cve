<?php
/*
Plugin Name: WP Favicon
Plugin URI: http://www.geekthegathering.com/category/wordpress/wp-favicon/
Description: Simply include a <em>favicon.ico</em> and a <em>favicon.gif</em> in your active theme.
Author: Jean-Michel Paris
Version: 0.1
Author URI: http://www.jeanmichelparis.com/
*/

/*	Copyright 2009 Jean-Michel Paris  (email : jean_michel_paris@hotmail.fr)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
  * @package WP Favicon
  * @author Jean-Michel Paris <jean_michel_paris@hotmail.fr>
  * @copyright Copyright (c) 2009, Jean-Michel Paris
  */

// Global Definitions
define( 'WPF_NAME'  , 'WP Favicon' );
define( 'WPF_DOMAIN', 'wp-favicon' );
define( 'WPF_VER'   , '0.1' );

if	( is_admin() ) {
	require_once( 'mode-admin/admin.php' );	// Administration mode.
}
else {
	require_once( 'mode-user/user.php' );	// Live blog.
}
