<?php
/**.
 * Woo Credits Platinum
 * by http://woocredits.com/
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Woo_Credit
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_Woo_Credit {
	public function __construct() {
		$this->resolved_fatal_error();
	}

	public function resolved_fatal_error() {
		if ( class_exists( 'Woo_Download_Credits_Platinum' ) && WFACP_Common::is_theme_builder() ) {
			WFACP_Common::remove_actions( 'init', 'Woo_Download_Credits_Platinum', 'template_redirect' );
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Woo_Credit(), 'woo_credit' );
