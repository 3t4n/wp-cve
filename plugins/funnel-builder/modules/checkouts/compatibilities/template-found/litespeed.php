<?php

class WFACP_LiteSpeed {
	public function __construct() {
		if ( ! class_exists( '\LiteSpeed\CDN' ) ) {
			return;
		}
		try {
			remove_filter( 'wp_get_attachment_image_src', array( \LiteSpeed\CDN::get_instance(), 'attach_img_src' ), 999 );
			remove_filter( 'wp_get_attachment_url', array( \LiteSpeed\CDN::get_instance(), 'url_img' ), 999 );
		} catch ( Exception $e ) {

		}


	}
}

if ( ! defined( 'LSCWP_V' ) ) {
	return;
}
new WFACP_LiteSpeed();