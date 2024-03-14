<?php
/*
Plugin Name: BI Button Changer
Plugin URI: https://ja.wordpress.org/plugins/bi-button-changer/
Description: In the visual editor and text editor, when you click the B button, change the output tag from strong to b and em to i.
Version: 1.0.0
Author:accelboon
Author URI: http://accelboon.com/tn
License: GPL2
*/

/*	Copyright 2017 accelboon (email : nakaike@accelboon.com)
 
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


function bib_modify_formats($settings){
	$formats = array(
		'bold' => array('inline' => 'b'),
		'italic' => array('inline' => 'i')
	);
	$settings['formats'] = json_encode( $formats );
	return $settings;
}
add_filter('tiny_mce_before_init', 'bib_modify_formats');
 
function bib_default_quicktags($qtInit) {
	$qtInit['buttons'] = 'link,block,del,ins,img,ul,li,ol,code,more,spell,close,fullscreen';
	return $qtInit;
}
add_filter('quicktags_settings', 'bib_default_quicktags', 10, 1);
 
function bib_appthemes_add_quicktags() {
	if (wp_script_is('quicktags')){
?>
	<script type="text/javascript">
		QTags.addButton( 'eg_bold', 'B', '<b>', '</b>', 'b', 'Bold tag', 1 );
		QTags.addButton( 'eg_i', 'I', '<i>', '</i>', 'i', 'Italic tag', 2 );
	</script>
<?php
	}
}
add_action( 'admin_print_footer_scripts', 'bib_appthemes_add_quicktags' );
?>