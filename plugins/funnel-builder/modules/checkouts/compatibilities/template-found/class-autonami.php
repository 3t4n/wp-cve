<?php

#[AllowDynamicProperties] 

  class WFACP_Bwf_Autonami {
	public function __construct() {
		add_filter( 'bwfan_public_scripts_include', [ $this, 'disable_script' ] );
	}
	public function disable_script( $status ) {
		if ( WFACP_Common::is_customizer() ) {
			$status = false;
		}

		return $status;
	}

}


return new WFACP_Bwf_Autonami();