<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class Route {

	/** @var string[] */
	public $methods = [];

	public $controller;

	/** @var string */
	public $path = '/';

	/** @var string */
	public $prefix = '';

	public $args;

	public $authorize;

	public function __construct( string $path ) {
		$this->path = $path;
	}

	public function path( string $path ) {
		$this->path = $path;

		return $this;
	}

	/** @param string|array $methods */
	public function methods( $methods ) {
		$this->methods = (array) $methods;

		return $this;
	}

	/** @param array|callable $controller */
	public function controller( $controller ) {
		$this->controller = $controller;

		return $this;
	}

	public function prefix( string $prefix ) {
		$this->prefix = $prefix;

		return $this;
	}

	public function authorize( callable $authorize ) {
		$this->authorize = $authorize;

		return $this;
	}

	public function args( ArgumentCollection $args ) {
		$this->args = $args->to_array();

		return $this;
	}

}
