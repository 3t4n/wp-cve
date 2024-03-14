<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * plugin Name: All in One SEO by  All in One SEO Team (4.1.6.2)
 *
 */


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_AIOSEO {
	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_after_template_found', [ $this, 'remove_actions' ] );
	}


	public function remove_actions( $template ) {
		if ( 'embed_form' == $template->get_template_type() ) {
			WFACP_Common::remove_actions( 'wp_head', 'AIOSEO\Plugin\Pro\Main\Head', 'init' );
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_AIOSEO(), 'aioseo' );


