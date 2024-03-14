<?php
		/*
	Plugin Name: CSS3 Rotating Words - WordPress Plugin
	Description: This plugin will allow you to use multiple words in a sentence that will change randomly in a sentence.
	Plugin URI: http://webdevocean.com/css3-rotating-words-demo/
	Author: Labib Ahmed
	Author URI: http://webdevocean.com
	Version: 5.5
	License: GPL2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: la-wordsrotator
	*/
	
	/*
	
	    Copyright (C) 2023  Labib Ahmed webdevocean@gmail.com
	
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

	add_action( 'activated_plugin', 'wdo_activation_redirect_rotator' );

	function wdo_activation_redirect_rotator( $plugin ) {
	    if( $plugin == plugin_basename( __FILE__ ) ) {
	        exit( wp_redirect( admin_url( 'admin.php?page=word_rotator' ) ) );
	    }
	}

	include_once ('plugin.class.php');
	if (class_exists('LA_Words_Rotator')) {
		$object = new LA_Words_Rotator;
	}
 ?>