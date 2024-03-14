<?php
/**
* Plugin Name: 				CPO Widgets
* Description: 				A number of useful widgets that add some essential functionality to your WordPress site. There widgets are intended to let you add more engaging content in your sidebars, such as a Twitter timeline, recent posts, image banners, or social media links.
* Version: 					1.1.0
* Author: 					WPChill
* Author URI: 				https://wpchill.com
* Requires: 				5.2 or higher
* License: 					GPLv3 or later
* License URI:       		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 			5.6
*
* Copyright 2014-2017		Manuel Vicedo 		mvicedo@cpo.es, manuelvicedo@gmail.com			
* Copyright 2017-2020 		MachoThemes 		office@machothemes.com
* Copyright 2020 			WPChill 			heyyy@wpchill.com
*
* Original Plugin URI: 		https://cpothemes.com/plugins/cpo-widgets
* Original Author URI: 		https://cpothemes.com/
* Original Author: 			https://profiles.wordpress.org/manuelvicedo/
*
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License, version 3, as
* published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Add public stylesheets
add_action('wp_enqueue_scripts', 'ctwg_add_styles');
function ctwg_add_styles(){
	$stylesheets_path = plugins_url('css/' , __FILE__);
	wp_enqueue_style('ctwg-shortcodes', $stylesheets_path.'style.css');	
}

//Add all Shortcode components
$core_path = plugin_dir_path(__FILE__).'widgets/';
require_once($core_path.'widget-advert.php');
require_once($core_path.'widget-flickr.php');
require_once($core_path.'widget-recent.php');
require_once($core_path.'widget-tweets.php');
require_once($core_path.'widget-social.php');
require_once($core_path.'widget-author.php');