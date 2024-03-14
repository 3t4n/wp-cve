<?php

/**
 * Format the tumblr response to  beautiful count.
 *
 * @param array $response response of the count call.
 * @since 1.0.0
 * @return  int count of tumblr share.
 */
function ssb_format_tumblr_response( $response ) {

	$counts   = 0;
	$response = json_decode( $response, true );
	// Check is valid api response
	if ( isset( $response['meta']['status'] ) && isset( $response['response']['note_count'] ) ) {
		if( $response['response']['note_count'] > 0 ) {
			if ( $response['meta']['status'] == 200 ) {
				$counts = $response['response']['note_count'];
			}
		}
		return $counts;
	}
}

/**
 * Generate link for tumblr get  count API.
 *
 * @param string $url
 * @since 1.0.0
 * @return string  ready link to call for API.
 */
function ssb_tumblr_generate_link( $url ) {
	$request_url = 'https://api.tumblr.com/v2/share/stats?url=' . $url;
	return $request_url;
}


