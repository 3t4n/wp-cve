<?php
/**
 *
 * Remove JetPack Notes JS
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_JetPack
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_JetPack {
	public function __construct() {
		add_action( 'wp_loaded', [ $this, 'remove_action' ] );
	}

	public function remove_action() {
		if ( WFACP_Common::is_builder() ) {
			WFACP_Common::remove_actions( 'admin_head', 'Jetpack_Notifications', 'styles_and_scripts' );
		}
	}
}

add_action( 'plugins_loaded', function () {
	if ( ! class_exists( 'Jetpack' ) ) {
		return;
	}
	new WFACP_Compatibility_JetPack();
} );
