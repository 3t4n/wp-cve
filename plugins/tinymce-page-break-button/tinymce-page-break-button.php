<?php
	/**
	 * Plugin Name: TinyMCE Page Break Button
	 * Plugin URI: http://wordpress.org/plugins/tinymce-page-break-button/
	 * Description: This Plugin adds a Page Break Button to TinyMCE Menu for using the Nextpage-Tag in posts
	 * Version: 1.2.0
	 * Author: Matthias.S
	 * Author URI: https://profiles.wordpress.org/matthiass
	 * License: GPLv2
	 */

    /*
       Copyright 2014-2018 Matthias Siebler (email: matthias.siebler@gmail.com)
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


 	// Prevent Direct Access of this file
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly


	//Add the Page Break Button to TinyMCE Menu after the More Button
	function mce_page_break($mce_buttons) {
		$pos = array_search('wp_more', $mce_buttons, true);
		if ($pos !== false) {
			$buttons = array_slice($mce_buttons, 0, $pos + 1);
			$buttons[] = 'wp_page';
			$mce_buttons = array_merge($buttons, array_slice($mce_buttons, $pos + 1));
			}
		return $mce_buttons;
	}

	add_filter('mce_buttons', 'mce_page_break');

?>