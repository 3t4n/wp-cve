<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class RoutesConfigurator implements \IteratorAggregate {

	/** @var Route[] */
	private $routes;

	/** @var string|null */
	private $prefix;

	/** @var callable|null */
	private $authorize;

	/** @var bool */
	private $initialized = false;

	public function add( string $path ): Route {
		$route          = new Route( $path );
		$this->routes[] = $route;

		return $route;
	}

	/**
	 * @return iterable<Route>
	 */
	public function getIterator(): \Traversable {
		if ( ! $this->initialized ) {
			$this->initialize();
		}

		return new \ArrayIterator( $this->routes );
	}

	private function initialize(): void {
		if ( $this->prefix ) {
			foreach ( $this->routes as $route ) {
				$route->prefix( $this->prefix );
			}
		}


		if ( $this->authorize ) {
			foreach ( $this->routes as $route ) {
				// Don't overwrite authorization, if it's defined per route
				if ( $route->authorize !== null ) {
					continue;
				}
				$route->authorize( $this->authorize );
			}
		}

		$this->initialized = true;
	}

	public function prefix( string $prefix ): void {
		$this->prefix = $prefix;
	}

	public function authorize( callable $authorize ): void {
		$this->authorize = $authorize;
	}
}
