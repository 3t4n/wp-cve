<?php
/*
Plugin Name: Fancy Lightbox
Plugin URI: http://wp-plugins.in/fancy-lightbox
Description: Add fancy lightbox easily, responsive lightbox and easy to use, without options and without complexity, compatible with all major browsers.
Version: 1.0.0
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


function alobaidi_lightbox_responsive_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'fancy-lightbox.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/fancy-lightbox" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_lightbox_responsive_plugin_row_meta', 10, 2 );


function alobaidi_lightbox_responsive_css_js(){	
	wp_enqueue_style( 'wpt-lightbox-responsive-fancybox-css', plugins_url( '/css/jquery.fancybox-1.3.7.css', __FILE__ ), false, false);
	wp_enqueue_script( 'wpt-lightbox-responsive-fancybox-js', plugins_url( '/js/jquery.fancybox-1.3.7.js', __FILE__ ), array('jquery'), false, false);
}
add_action('wp_enqueue_scripts', 'alobaidi_lightbox_responsive_css_js');


function alobaidi_lightbox_responsive_footer(){

	?>
		<script type="text/javascript">
			jQuery("a[href$='.jpg'], a[href$='.png'], a[href$='.jpeg'], a[href$='.gif']").fancybox();
		</script>
	<?php

}
add_action('wp_footer', 'alobaidi_lightbox_responsive_footer');

?>