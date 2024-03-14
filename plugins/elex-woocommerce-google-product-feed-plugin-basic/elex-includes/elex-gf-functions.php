<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//check class dependencies exist or not
if ( ! class_exists( 'ELEX_GF_Basic_Dependencies' ) ) {
	require_once  'elex-gf-dependencies.php' ;
}
//check woocommerce is active function exist
if ( ! function_exists( 'elex_gf_basic_is_woocommerce_active' ) ) {

	function elex_gf_basic_is_woocommerce_active() {
		return ELEX_GF_Basic_Dependencies::woocommerce_active_check();
	}
}
