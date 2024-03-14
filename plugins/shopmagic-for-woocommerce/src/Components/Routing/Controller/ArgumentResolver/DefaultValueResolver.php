<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;

class DefaultValueResolver implements \WPDesk\ShopMagic\Components\Routing\Controller\ArgumentValueResolver {

	public function supports( \WP_REST_Request $request, \ReflectionParameter $parameter ): bool {
		return $parameter->isDefaultValueAvailable();
	}

	public function resolve( \WP_REST_Request $request, \ReflectionParameter $parameter ) {
		return $parameter->getDefaultValue();
	}
}
