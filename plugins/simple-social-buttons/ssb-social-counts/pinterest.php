<?php

/**
 * Format the pinterest response to  beautiful count.
 *
 * @param array $response response of the count call.
 * @since 1.0.0
 * @return  int count of pinterest share.
 */
function ssb_pinterest_generate_link( $url ) {
	$request_url = 'https://api.pinterest.com/v1/urls/count.json?url=' . $url;

	return $request_url;
}

/**
 * Generate link for pinterest get  count API.
 *
 * @param string $url
 * @since 1.0.0
 * @return string  ready link to call for API.
 */
function ssb_format_pinterest_response( $response ) {

	$response = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $response );
	$response = json_decode( $response, true );
	return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
}
