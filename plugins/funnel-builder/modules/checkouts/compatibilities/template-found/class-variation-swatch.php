<?php
/**
 * Variation Swatches By CartFlows
 *
 */

class WFACP_Compatibility_With_CFW_Swatches {
	public function __construct() {
		add_filter( 'cfvsw_is_required_page', [ $this, 'enable_checkout_as_required_page' ] );
		add_action( 'wfacp_qv_images', [ $this, 'action' ] );

	}

	public function action() {
		add_filter( 'woocommerce_wfacp_dropdown_variation_attribute_options_html', [ $this, 'change_filter' ], 10, 2 );
	}

	public function change_filter( $html, $args ) {
		return apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args );
	}

	public function enable_checkout_as_required_page( $status ) {
		return is_checkout() ? true : $status;
	}
}

new WFACP_Compatibility_With_CFW_Swatches();