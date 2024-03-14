<?php
/**
 * @package cloudlyup multi Map
 */
/*
Plugin Name: Multi Location Google Map
Plugin URI: https://www.cloudlyup.com/
Description: Googel map with multi office addrss and popup address and image. This plugin developed by cloudlyup Solutions for dispyaing offece locations in Google map using api
Version: 1.0.0
Author:  jagadeeshok
License: GPLv2 or later
Text Domain: multilocationgmap
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/
define( 'CLOUDLYUP_MULTILOCATION_GMAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( CLOUDLYUP_MULTILOCATION_GMAP_PLUGIN_DIR . 'function.php' );
require_once( CLOUDLYUP_MULTILOCATION_GMAP_PLUGIN_DIR . 'shortcode.php' );
