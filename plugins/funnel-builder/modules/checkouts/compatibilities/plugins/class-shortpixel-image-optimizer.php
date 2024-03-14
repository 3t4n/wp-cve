<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ShortPixel Image Optimizer by ShortPixel
 *
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_ShortPixel_Image_optimizer {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ] );
	}

	public function remove_action() {
		if ( class_exists( 'ShortPixel\Controller\FrontController' ) ) {
			return;
		}
		WFACP_Common::remove_actions( 'the_content', 'ShortPixel\Controller\FrontController', 'convertImgToPictureAddWebp' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_ShortPixel_Image_optimizer(), 'wfacp-shortpixel-image-optimizer' );


