<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller;

class SimpleControllerResolver implements ControllerResolver {

	public function get_controller( $controller ): callable {
		if ( is_array( $controller ) ) {
			$controller[0] = $this->instantiate_controller( $controller[0] );
		}

		return $controller;
	}

	/**
	 * @template T of object
	 * @param class-string<T> $class
	 *
	 * @return T
	 */
	protected function instantiate_controller( string $class ): object {
		return new $class();
	}
}
