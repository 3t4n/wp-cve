<?php

/**
 * Format the facebook response to  beautiful count.
 *
 * @param array $response response of the count call.
 * @since 1.0.0
 * @return  int count of facebook share.
 */
function ssb_format_fbshare_response( $response ) {
	$formatted_response = json_decode( $response, true );
	// Facebook share count check.
	if ( ! isset( $formatted_response['engagement'] ) ) {
		return 0;
	}
	$reaction = isset( $formatted_response['engagement']['reaction_count'] ) ? $formatted_response['engagement']['reaction_count'] : 0;
	$comments = isset( $formatted_response['engagement']['comment_count'] ) ? $formatted_response['engagement']['comment_count'] : 0;
	$shares   = isset( $formatted_response['engagement']['share_count'] ) ? $formatted_response['engagement']['share_count'] : 0;
	$total    = $reaction + $comments + $shares;
	return $total;
}


/**
 * Generate link for facebook get  count API.
 *
 * @param string $url
 * @since 1.0.0
 * @return string  ready link to call for API.
 */
function ssb_fbshare_generate_link( $url ) {
	global $_ssb_pr;
	$advance_settings    = $_ssb_pr->extra_option;
	$facebook_app_id     = isset( $advance_settings['facebook_app_id'] ) ? $advance_settings['facebook_app_id'] : '';
	$facebook_secret_key = isset( $advance_settings['facebook_app_secret'] ) ? $advance_settings['facebook_app_secret'] : '';
	$link                = "https://graph.facebook.com/v3.0/?id={$url}&fields=engagement&access_token={$facebook_app_id}|{$facebook_secret_key}";
	return $link;
}
