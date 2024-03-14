<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller;

use ShopMagicVendor\Psr\Container\ContainerInterface;

class ContainerControllerResolver extends SimpleControllerResolver {

	/** @var ContainerInterface */
	private $container;

	public function __construct( ContainerInterface $container ) {
		$this->container = $container;
	}

	protected function instantiate_controller( string $class ): object {
		if ( $this->container->has( $class ) ) {
			return $this->container->get( $class );
		}

		return parent::instantiate_controller( $class );
	}

}
