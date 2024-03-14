<?php
/*
 * Plugin Name: PRyC WP: TinyMCE more buttons
 * Plugin URI: http://PRyC.pl
 * Description: Add more buttons (third line/row) to default TinyMCE editor: select font, select font size, select style, text background color, new document, cut and copy, charset map, horizontal break line.
 * Author: PRyC
 * Author URI: http://PRyC.pl
 * Version: 1.2.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


 
/* CODE: */

if ( ! defined( 'ABSPATH' ) ) exit;

function pryc_wp_tinymce_more_buttons( $buttons ) {

	if ( ! in_array( "fontselect", $buttons ) ) { $buttons[] = 'fontselect'; }
	if ( ! in_array( "fontsizeselect", $buttons ) ) { $buttons[] = 'fontsizeselect'; }
	if ( ! in_array( "styleselect", $buttons ) ) { $buttons[] = 'styleselect'; }
	if ( ! in_array( "backcolor", $buttons ) ) { $buttons[] = 'backcolor'; }
	if ( ! in_array( "alignjustify", $buttons ) ) { $buttons[] = 'alignjustify'; }
	if ( ! in_array( "underline", $buttons ) ) { $buttons[] = 'underline'; }
	if ( ! in_array( "newdocument", $buttons ) ) { $buttons[] = 'newdocument'; }
	if ( ! in_array( "cut", $buttons ) ) { $buttons[] = 'cut'; }
	if ( ! in_array( "copy", $buttons ) ) { $buttons[] = 'copy'; }
	if ( ! in_array( "charmap", $buttons ) ) { $buttons[] = 'charmap'; }
	if ( ! in_array( "hr", $buttons ) ) { $buttons[] = 'hr'; }

	//$buttons[] = 'code';
	//$buttons[] = 'pre';
	//$buttons[] = 'anchor';
	//$buttons[] = 'separator';
	//$buttons[] = 'visualaid';

	return $buttons;
}
add_filter( 'mce_buttons_3', 'pryc_wp_tinymce_more_buttons', 99999 ); // mce_buttons_3 - row 3

/* END */

