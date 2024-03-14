<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Nitro {

	public function __construct() {
		/* checkout page */
		add_filter( 'wr_nitro_theme_options_definition', [ $this, 'remove_panels' ] );
	}

	public function remove_panels( $theme_options ) {
		if ( WFACP_Common::is_customizer() ) {
			return [];
		}

		return $theme_options;
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Nitro(), 'nitro' );
