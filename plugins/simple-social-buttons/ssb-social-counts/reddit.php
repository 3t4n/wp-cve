<?php

/**
 * Format the reddit response to  beautiful count.
 *
 * @param array $response response of the count call.
 * @since 1.0.0
 * @return  int count of reddit share.
 */
function ssb_reddit_generate_link( $url ) {
	$request_url = 'https://www.reddit.com/api/info.json?url=' . $url;
	return $request_url;
}

/**
 * Generate link for reddit get  count API.
 *
 * @param string $url
 * @since 1.0.0
 * @return string  ready link to call for API.
 */
function ssb_format_reddit_response( $response ) {
	$response = json_decode( $response, true );

	$score = 0;
	// check data if data exist in respose and has length greater than 0.
	if ( isset( $response['data']['children'] ) && count( $response['data']['children'] ) > 0 ) {
		foreach ( $response['data']['children'] as $child ) {
			// check score exist
			if ( isset( $child['data']['score'] ) ) {

				$score += $child['data']['score'];

			}
		}
	}

	return $score;
}
