<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_action( 'admin_init', 'update_adsense_inpost_ads_options' );
function update_adsense_inpost_ads_options(){
	$a = get_option('oizuled-adsense-unit-a');
	$b = get_option('oizuled-adsense-unit-b');
	$c = get_option('oizuled-adsense-unit-c');

	$notoptions = wp_cache_get( 'notoptions', 'options' );

	if ( isset( $notoptions[ 'oizuled-adsense-unit-a' ] ) && isset( $notoptions[ 'oizuled-adsense-unit-b' ] ) && isset( $notoptions[ 'oizuled-adsense-unit-c' ] ) ){
		return;
	} else {
		$updated = array(
			'unit_a' => $a,
			'unit_b' => $b,
			'unit_c' => $c
		);

		update_option( 'aip_settings', $updated );

		delete_option( 'oizuled-adsense-unit-a' );
		delete_option( 'oizuled-adsense-unit-b' );
		delete_option( 'oizuled-adsense-unit-c' );
	}
}