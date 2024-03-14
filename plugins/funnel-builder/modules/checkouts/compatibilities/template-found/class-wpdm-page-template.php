<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
PluginName: WPDM - Page Template by Shaon (v.1.1)
*/

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WPDM_Page_Template {

	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ] );
	}

	public function action() {

		WFACP_Common::remove_actions( 'template_include', 'wpdm_page_template', 'wpdm_page_template_template_include' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WPDM_Page_Template(), 'wpdm-page-temp' );
