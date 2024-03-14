<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;

MczrAccess::isAuthorized();

class MczrProduct {

	const SETTING_OPTION_PREFIX = 'mczrSetting';

	public function buildProductUrl( $productId, $encode = true ) {
		$url    = get_permalink( $productId );
		$parsed = \parse_url( $url );

		$url .= ( isset( $parsed['query'] ) ) ? '&' : '?';
		$url .= 'designId=<designId>';
		if ( $encode ) {
			return \urlencode( $url );
		}
		return $url;
	}
}
