<?php

defined( 'ABSPATH' ) || die();

/**
 * Check if cookie consent is set
 */
if( !function_exists( 'is_cookie_consent_set' ) ) {
	function is_cookie_consent_set() {
		return Cookie_Notice_Consent_Helper::is_cookie_consent_set();
	}
}

/**
 * Check if given cookie category is accepted
 */
if( !function_exists( 'is_cookie_category_accepted' ) ) {
	function is_cookie_category_accepted( $category ) {
		return Cookie_Notice_Consent_Helper::is_cookie_category_accepted( $category );
	}
}