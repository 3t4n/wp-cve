<?php

#[AllowDynamicProperties] 

  class WFACP_PYS_Compatibility {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'dequeue_pys_js' ] );
	}

	public function dequeue_pys_js() {
		if ( ! class_exists( 'PixelYourSite\EventsManager' ) ) {
			return;
		}
		$page_settings               = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		$override_global_track_event = wc_string_to_bool( isset( $page_settings['override_global_track_event'] ) ? $page_settings['override_global_track_event'] : false );
		if ( $override_global_track_event ) {
			WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'PixelYourSite\EventsManager', 'enqueueScripts' );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_PYS_Compatibility(), 'pys' );
