<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * plugin Name: Iubenda cookie solution by iubenda (3.10.0)
 *
 */

#[AllowDynamicProperties]
class WFACP_Iubenda_cookie_solution_by_iubenda {
	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_wp_head' ] );
	}

	public function remove_wp_head() {
			WFACP_Common::remove_actions( 'template_redirect', 'iubenda', 'output_start' );
	}
}

new WFACP_Iubenda_cookie_solution_by_iubenda();

