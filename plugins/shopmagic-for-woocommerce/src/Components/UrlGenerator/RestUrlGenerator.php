<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\UrlGenerator;

class RestUrlGenerator implements UrlGenerator {

	public function generate( string $path = '', array $parameters = [] ): string {
		return get_rest_url( null, '/shopmagic/v1/' . ltrim( $path, '/' ) );
	}
}
