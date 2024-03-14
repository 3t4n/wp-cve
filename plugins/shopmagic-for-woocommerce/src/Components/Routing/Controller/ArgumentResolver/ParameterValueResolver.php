<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;

use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentValueResolver;

class ParameterValueResolver implements ArgumentValueResolver {

	public function supports( \WP_REST_Request $request, \ReflectionParameter $parameter ): bool {
		return $parameter->hasType() &&
			   in_array( $parameter->getType()->getName(), [ 'string', 'int', 'float', 'array' ] ) &&
			   $request->has_param( $parameter->getName() );
	}

	public function resolve( \WP_REST_Request $request, \ReflectionParameter $parameter ) {
		$param = $request->get_param( $parameter->getName() );
		settype( $param, $parameter->getType()->getName() );

		return $param;
	}
}
