<?php /*
**************************************************************************

Plugin Name:  Image Mouseover
Plugin URI:   http://terrychay.com/wordpress-plugins/img-mouseover/
Version:      1.4.2
Description:  Allows you to have img mouseovers on the page without resorting to javascript. Just class="mouseover" data-oversrc="URL_TO_MOUSEOVER" 
Author:       tychay
Author URI:   http://terrychay.com/

**************************************************************************/
/*  Copyright 2010-2011  terry chay  (email : tychay@automattic.com)

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
class ImgMouseover {
	 function init() {
		// Load the locale script
		wp_enqueue_script( 'img-mouseover', plugins_url( 'img-mouseover.js', __FILE__ ) , array( 'jquery' ), '20110509' );
	}

	/**
	 * This adds the new attributes as valid elements to the visual
	 * editor.
	 *
	 * See {@link http://codex.wordpress.org/Plugin_API/Hooks_2.0.x|
	 * documentation on valid elements} as outlined in
	 * {@link http://granades.com/2007/02/14/adding-quicktags-to-wordpress/|this tutoral}.
	 * {@link http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/valid_elements TinyMCE|Here is a list of valid elements]
	 */
	function add_valid_elements( $inits ) {
		$default_globals = 'id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|';
		$default_img     = 'longdesc|usemap|src|border|alt=|title|hspace|vspace|width|height|align|';
		$add_img         = 'oversrc|clicksrc|noresize';
		$default_a       = 'rel|rev|charset|hreflang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur|';
		$add_a           = 'for|for_link|src|oversrc';
		if ( empty( $inits['extended_valid_elements'] ) ) {
			$inits['extended_valid_elements'] =
				'img['.$default_globals.$default_img.$add_img.']' .
				',a['.$default_globals.$default_a.$add_a.']';
		} else {
			$e =& $inits['extended_valid_elements'];
			$e = str_replace( 'img[', 'img['.$add_img, $e, $count );
			if (!$count) {
				$e .= ',img['.$default_globals.$default_img.$add_img.']';
			}
			$e = str_replace( 'a[', 'a['.$add_a, $e, $count );
			if (!$count) {
				$e .= ',a['.$default_globals.$default_a.$add_a.']';
			}
		}
		return $inits;
	}

	/**
	 * This uses {@link http://www.javascriptkit.com/dhtmltutors/customattributes.shtml HTML5 custom data attributes}
	 */
	function make_html5_compliant( $content ) {
		$content = preg_replace(
			array(
				'!<img([^>]*)\soversrc=!ims',
				'!<img([^>]*)\sclicksrc=!ims',
				'!<img([^>]*)\snoresize=!ims',
				'!<a([^>]*)\sfor=!ims',
				'!<a([^>]*)\sfor_link=!ims',
				'!<a([^>]*)\ssrc=!ims',
				'!<a([^>]*)\soversrc=!ims',
				),
			array(
				'<img$1 data-oversrc=',
				'<img$1 data-clicksrc=',
				'<img$1 data-noresize=',
				'<a$1 data-for=',
				'<a$1 data-for_link=',
				'<a$1 data-src=',
				'<a$1 data-oversrc=',
				),
			$content
		);
		return $content;
	}
	function make_html5_compliantx( $content ) {
		var_dump($content);
		return $content;
	}
}

// Start this plugin
add_action( 'init', array('ImgMouseover','init'), 12 );
add_filter( 'tiny_mce_before_init', array('ImgMouseover', 'add_valid_elements'), 0);
add_filter( 'the_content', array('ImgMouseover', 'make_html5_compliant'), 12);
//add_filter( 'dynamic_sidebar', array('ImgMouseover', 'make_html5_compliant_sidebar'), 12); //dynamic_sidebar is before the content is rendered (WP_Widget_Text::display_callback)
?>
