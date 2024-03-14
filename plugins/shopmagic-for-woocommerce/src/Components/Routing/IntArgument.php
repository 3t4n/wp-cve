<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class IntArgument extends Argument {

	/** @var int */
	public $minimum;
	/** @var int */
	public $maximum;

	public function __construct( string $name ) {
		$this->type = 'integer';
		parent::__construct( $name );
	}

	public function minimum( int $min ): self {
		$this->minimum = $min;

		return $this;
	}

	public function maximum( int $max ): self {
		$this->maximum = $max;

		return $this;
	}
}
