<?php

/**
 * By Algoritmika
 * #[AllowDynamicProperties] 

  class WFACP_Maximum_Products_Per_User
 */
#[AllowDynamicProperties] 

  class WFACP_Maximum_Products_Per_User {
	public function __construct() {
		WFACP_Common::remove_actions( 'wp', 'Alg_WC_MPPU_Core', 'block_checkout' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Maximum_Products_Per_User(), 'mppu' );