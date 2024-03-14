<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller;

interface ArgumentValueResolver {

	public function supports( \WP_REST_Request $request, \ReflectionParameter $parameter ): bool;

	public function resolve( \WP_REST_Request $request, \ReflectionParameter $parameter );

}
