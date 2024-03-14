<?php
/*
Plugin Name: The Single Background
Plugin URI: http://wp-plugins.in/single-background
Description: Add different background color or responsive background image for every single post or page or custom post type.
Version: 1.0.2
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

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


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function the_single_background_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'the-single-background.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/single-background" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>',
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'the_single_background_plugin_row_meta', 10, 2 );


// The Single Background Function
function the_single_background_plugin( $atts, $content = null ) {
	
	if( is_single() or is_page() ){ // check if single post or single page or custom post type
		
		Extract(
			shortcode_atts(
				array(
					"url"		=>	"", // url attr, default is empty
					"color"		=>	"" // color attr, default is empty
				),$atts
			)
		);
		
		if ( !empty($url) ){ // if choose background image
			return '<style type="text/css">	
						html{
							background-image:none !important;
							background:none !important;
						}
						body{
							background:url('.$url.') 0 0 fixed no-repeat !important;
							background-size:100% 100% !important;
							-webkit-background-size:100% 100% !important;
							-moz-background-size:100% 100% !important;
							-o-background-size:100% 100% !important;
						}
						/*
						body.logged-in{
							background-position: 0 -32px !important;
						}
						*/
					</style>';
			return false;
		} // end if choose background image
		
		if ( !empty($color) ){ // if choose background color
			return '<style type="text/css">	
						html{
							background-image:none !important;
							background:none !important;
						}
						body{
							background-image:none !important;
							background:none !important;
							background-color:'.$color.' !important;
						}
					</style>';
		} // end if choose background color
	
	} // end if is_single() or is_page()
	
} // end function
add_shortcode("single_bg", "the_single_background_plugin"); // Add Shortcode [single_bg url="" color=""]

?>