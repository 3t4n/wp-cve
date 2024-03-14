<?php
/*
Plugin Name: Loading Bar
Plugin URI: http://wp-plugins.in/loading-bar
Description: Add loading bar to your website easily, like youtube loading bar! just one click and custom loading bar color and responsive.
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


include 'setting.php';


function alobaidi_loading_bar_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'loading-bar.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/loading-bar" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_loading_bar_plugin_row_meta', 10, 2 );


function alobaidi_loading_bar_include_js(){
	wp_enqueue_script( 'alobaidi-loading-bar-js', plugins_url( '/js/nanobar.js', __FILE__ ), array(), false, false);
}
add_action('wp_enqueue_scripts', 'alobaidi_loading_bar_include_js');


function alobaidi_loading_bar_script_element(){

	if ( get_option('alob_loading_bar_c') ){

		if( preg_match('/(#)/', get_option('alob_loading_bar_c') ) ){
			$color = str_replace( array(',', '.'), "", get_option('alob_loading_bar_c') );
		}
		else{
			$color_code = str_replace( array(',', '.'), "", get_option('alob_loading_bar_c') );
			$color = '#'.$color_code;
		}
		
	}
	else{
		$color = '#f00000';
	}

	?>
		<script type="text/javascript">
			var options = { bg:<?php echo "'$color'" ?>, id:'alobaidi-loading-bar' };
			var nanobar = new Nanobar( options );
			nanobar.go(100);
		</script>
	<?php

}
add_action('wp_footer', 'alobaidi_loading_bar_script_element');

?>