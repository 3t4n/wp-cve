<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentValueResolver;

class ContainerValueResolver implements ArgumentValueResolver {

	/** @var ContainerInterface */
	private $container;

	public function __construct( ContainerInterface $container ) {
		$this->container = $container;
	}

	public function supports( \WP_REST_Request $request, \ReflectionParameter $parameter ): bool {
		return $parameter->hasType() && $this->container->has( $parameter->getType()->getName() );
	}

	public function resolve( \WP_REST_Request $request, \ReflectionParameter $parameter ) {
		return $this->container->get( $parameter->getType()->getName() );
	}
}
