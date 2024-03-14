<?php
/**
 * Expand Divi Lightbox
 * opens all images in posts or pages in a lightbox
 *
 * @package  ExpandDivi/ExpandDiviLightBoxEverywhere
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviLightBoxEverywhere {

	/**
	 * constructor
	 */
	function __construct() {
		add_action( 'wp_footer', array( $this, 'expand_divi_lightbox') );	
	}

	/**
	 * append script to page/post
	 *
	 * @return string
	 */
	function expand_divi_lightbox() {
		if ( is_singular() ) {
			?><script>(function($){
				$(document).ready(function(){
					$('.entry-content').children().not( $('.et-boc') ).find('a img').parent('a').addClass('et_pb_lightbox_image');
				});
			})(jQuery)</script><?php
		}
	}
		
}

new ExpandDiviLightBoxEverywhere();