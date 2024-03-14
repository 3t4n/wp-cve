<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//check class dependencies exist or not
if ( ! class_exists( 'Elex_CM_Dependencies' ) ) {
	require_once( 'elex-dependencies.php' );
}

//check woocommerce is active function exist
if ( ! function_exists( 'elex_cm_is_woocommerce_active' ) ) {

	function elex_cm_is_woocommerce_active() {
		return Elex_CM_Dependencies::woocommerce_active_check();
	}
}
