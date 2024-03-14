<?php
/*
Plugin Name: WPFavicon
Plugin URI: http://www.SuperbCodes.com/
Description: This plugin is for adding favicons to WordPress site.
Tags: favicon,wpfavicons,icon,icons,Nazmul Hossain Nihal,Suberbcodes.com,login screen,admin,site
Version: 2.1.1
Author:	Nazmul Hossain Nihal
Author URI: https://Nihal.ONE/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

load_plugin_textdomain('cwfav', false, basename( dirname( __FILE__ ) ) . '/languages' );

/******************************
* global variables
******************************/

$cwfav_options = get_option('cwfav_settings');

/******************************
* includes
******************************/

include('admin/cwfav_options.php'); //Admin Panel

include('display/settings.php'); //Display