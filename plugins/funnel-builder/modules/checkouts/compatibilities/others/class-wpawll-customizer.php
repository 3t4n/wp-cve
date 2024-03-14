<?php

/**
 * this plugin create a js error when we open customizer page
 * #[AllowDynamicProperties] 

  class WFACP_Wpawll
 */
#[AllowDynamicProperties] 

  class WFACP_Wpawll {

	public function __construct() {
		$this->actions();
	}

	public function actions() {
		if ( WFACP_Common::is_customizer() ) {
			WFACP_Common::remove_actions( 'customize_register', 'WPAWLL_Customizer', 'wpawll_customize_register' );
			WFACP_Common::remove_actions( 'customize_register', 'WPAWLL_Customizer', 'wpawll_customize_register' );
			WFACP_Common::remove_actions( 'customize_register', 'wpawll_tabs_customize_register' );
		}
	}
}

if ( class_exists( 'WPAWLL_Customizer' ) ) {
	return;
}
new WFACP_Wpawll();
