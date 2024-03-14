<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\UrlGenerator;

class AdminUrlGenerator implements UrlGenerator {

	public function generate( string $path = '', array $parameters = [] ): string {
		$url = admin_url( $path );

		$fragment = '';
		if ( isset( $parameters['_fragment'] ) ) {
			$fragment = $parameters['_fragment'];
			unset( $parameters['_fragment'] );
		}

		$query = http_build_query( $parameters );

		if ( $query ) {
			$url .= '?' . $query;
		}

		if ( $fragment ) {
			$url .= '#' . $fragment;
		}

		return $url;
	}
}
