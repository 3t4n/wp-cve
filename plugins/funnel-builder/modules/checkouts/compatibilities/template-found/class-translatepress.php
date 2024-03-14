<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Translate_press
 * Cozmoslabs, Razvan Mocanu, Madalin Ungureanu, Cristophor Hurduban
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_Translate_press {
	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_our_template' ] );
	}


	public function remove_our_template() {
		$user = WFACP_Core()->role->user_access( 'checkout', 'read' );
		if ( ( false !== $user ) && isset( $_REQUEST['trp-edit-translation'] ) && 'true' === $_REQUEST['trp-edit-translation'] ) { //phpcs:ignore WordPress.Security.NonceVerification
			WFACP_Common::remove_actions( 'template_redirect', 'WFACP_Template_loader', 'setup_preview' );
		}
	}
}


new WFACP_Compatibility_Translate_press();

