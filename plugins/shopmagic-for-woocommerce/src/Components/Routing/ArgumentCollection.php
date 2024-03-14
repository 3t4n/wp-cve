<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class ArgumentCollection {

	/** @var Argument[] */
	private $arguments;

	public function __construct( Argument ...$arguments ) {
		$this->arguments = $arguments;
	}

	public function to_array(): array {
		$result = [];
		foreach ( $this->arguments as $argument ) {
			$result[ $argument->name ] = $argument->to_array();
		}

		return $result;
	}

}
