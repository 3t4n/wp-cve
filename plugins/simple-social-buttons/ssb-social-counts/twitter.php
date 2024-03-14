<?php

/**
 * Format the twitter response to  beautiful count.
 *
 * @param array $response response of the count call.
 * @since 1.0.0
 * @return  int count of twitter share.
 */
function ssb_format_twitter_response( $response ) {
	// Parse the response to get the actual number
	$response = json_decode( $response, true );
	return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
}

/**
 * Generate link for twitter get  count API.
 *
 * @param string $url
 * @since 1.0.0
 * @return string  ready link to call for API.
 */
function ssb_twitter_generate_link( $url ) {

	// Return the correct Twitter JSON endpoint URL
	$request_url = 'https://counts.twitcount.com/counts.php?url=' . $url;
	return $request_url;
}
