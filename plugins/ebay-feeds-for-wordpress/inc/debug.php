<?php

/**
 * Check if the feed is a URL from auction request, and if so, we'll
 *
 * @param  string $url  The URL
 * @return string       The URL witht he added version
 */
function wp_ebay_product_feed_debug_ar_urls( $url ) {

	if ( stripos( $url, 'auctionrequest.com' ) ) {
		$version = str_replace( '.', '', EBFW_PLUGIN_VERSION );
		$url    .= '&platform=wpebpf' . $version;
	}

	return $url;
}
