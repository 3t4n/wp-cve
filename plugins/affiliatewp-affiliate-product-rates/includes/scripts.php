<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Scripts
 *
 * @since 1.0
 * @since 1.2.1 We load the script library in Core.
*/
function affwp_affiliate_product_rates_admin_enqueue_scripts() {

	if ( affwp_apr_is_affiliate_page() ) {
		wp_enqueue_script( 'affwp-select2' );
		wp_enqueue_style( 'affwp-select2' );
	}
}
add_action( 'admin_enqueue_scripts', 'affwp_affiliate_product_rates_admin_enqueue_scripts' );

/**
 * JS for admin page to allow options to be visible
 *
 * @since 1.0
*/
function affwp_affiliate_product_rates_admin_footer_js() { 
	
	if ( ! affwp_apr_is_affiliate_page() ) {
		return;
	}

	?>
	<script>

		window.affwpAffiliateProductRatesSelect2Args = {
			placeholder: "Select a Product",
			allowClear: true,
		};

		jQuery(document).ready(function ($) {
			$('select.apr-select-multiple').select2( window.affwpAffiliateProductRatesSelect2Args );
		});
	</script>
<?php 
}
add_action( 'in_admin_footer', 'affwp_affiliate_product_rates_admin_footer_js' );


/**
 *  Determines whether the current admin page is either the edit or add affiliate admin page
 *  
 *  @since 1.0
 *  @return bool True if either edit or new affiliate admin pages
 */
function affwp_apr_is_affiliate_page() {

	if ( ! is_admin() || ! did_action( 'wp_loaded' ) ) {
		$ret = false;
	}
	
	if ( ! ( isset( $_GET['page'] ) && 'affiliate-wp-affiliates' != $_GET['page'] ) ) {
		$ret = false;
	}

	$action  = isset( $_GET['action'] ) ? $_GET['action'] : '';

	$actions = array(
		'edit_affiliate',
		'add_affiliate'
	);
		
	$ret = in_array( $action, $actions );
	
	return $ret;
}