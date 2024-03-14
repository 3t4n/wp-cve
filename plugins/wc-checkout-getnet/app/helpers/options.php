<?php
/**
 * Add link settings page
 *
 * @since 1.0
 * @param Array $links
 * @return Array
 */
function plugin_links( $links ) {
	$getnet_settings = [
		sprintf(
		'<a href="%s">%s</a>',
		'admin.php?page=getnet-settings',
		__( 'Settings' )
		)
	];

	$getnet_creditcard_settings = [
		sprintf(
		'<a href="%s">%s</a>',
		'admin.php?page=wc-settings&tab=checkout&section=getnet-creditcard',
		__( 'Cartão' )
		)
	];

	$getnet_billet_settings = [
		sprintf(
		'<a href="%s">%s</a>',
		'admin.php?page=wc-settings&tab=checkout&section=getnet-billet',
		__( 'Boleto' )
		)
	];

	$getnet_pix_settings = [
		sprintf(
		'<a href="%s">%s</a>',
		'admin.php?page=wc-settings&tab=checkout&section=getnet-pix',
		__( 'PIX' )
		)
	];

	return array_merge( $getnet_settings, $getnet_creditcard_settings, $getnet_billet_settings, $getnet_pix_settings, $links );
}

/**
 * Add support link page
 *
 * @since 1.0
 * @param Array $links
 * @return Array
 */
function support_links( $links_array, $plugin_file_name, $plugin_data, $status ) {

	if ( $plugin_file_name === 'wc-checkout-getnet/wc-checkout-getnet.php' ) {
		$links_array[] = '<a href="https://coffee-code.tech/#contact">'.__( 'Suporte' ).'</a>';
	}

	return $links_array;
}
