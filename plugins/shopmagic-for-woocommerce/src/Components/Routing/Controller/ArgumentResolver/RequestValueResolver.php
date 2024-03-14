<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;

use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentValueResolver;

class RequestValueResolver implements ArgumentValueResolver {

	public function supports( \WP_REST_Request $request, \ReflectionParameter $parameter ): bool {
		return $parameter->hasType() && $parameter->getType()->getName() === \WP_REST_Request::class;
	}

	public function resolve( \WP_REST_Request $request, \ReflectionParameter $parameter ) {
		return $request;
	}
}
