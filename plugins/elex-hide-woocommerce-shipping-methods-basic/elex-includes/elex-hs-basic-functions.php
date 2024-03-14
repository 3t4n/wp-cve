<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//check class dependencies exist or not
if ( ! class_exists( 'ELEX_HS_BASIC_Dependencies' ) ) {
	require_once  'elex-hs-basic-dependencies.php' ;
}

//check woocommerce is active function exist
if ( ! function_exists( 'elex_hs_basic_is_woocommerce_active' ) ) {

	function elex_hs_basic_is_woocommerce_active() {
		return ELEX_HS_BASIC_Dependencies::woocommerce_active_check();
	}
}
