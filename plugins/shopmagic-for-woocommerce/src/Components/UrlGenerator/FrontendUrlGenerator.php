<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\UrlGenerator;

class FrontendUrlGenerator implements UrlGenerator {

	/**
	 * Can handle site URL for both regular WordPress setup and Bedrock directory structure.
	 */
	public function generate( string $path = '', array $parameters = [] ): string {
		if ( defined( 'WP_HOME' ) && \WP_HOME ) {
			$url = trailingslashit( \WP_HOME ) . ltrim( $path, '/' );
		} else {
			$url = get_home_url( null, $path );
		}

		$fragment = '';

		if ( isset( $parameters['_fragment'] ) ) {
			$fragment = $parameters['_fragment'];
			unset( $parameters['_fragment'] );
		}

		if ( ! empty( $parameters ) ) {
			$url .= '?' . http_build_query( $parameters, '', '&' );
		}

		if ( $fragment ) {
			$url .= '#' . $fragment;
		}

		return $url;
	}

}
