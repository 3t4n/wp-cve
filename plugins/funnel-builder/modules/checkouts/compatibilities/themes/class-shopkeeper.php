<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Shopkeeper {

	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_inline_styling' ] );

	}

	public function remove_inline_styling() {
		if ( function_exists( 'shopkeeper_custom_styles' ) ) {

			$tempInstanse = wfacp_template();
			if ( $tempInstanse->get_template_type() == 'pre_built' ) {
				remove_action( 'wp_head', 'shopkeeper_custom_styles', 99 );
			}
		}

		if ( function_exists( 'getbowtied_notification_class' ) ) {
			remove_filter( 'body_class', 'getbowtied_notification_class' );
		}

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Shopkeeper(), 'shopkeeper' );
