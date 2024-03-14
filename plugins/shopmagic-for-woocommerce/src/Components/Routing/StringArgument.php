<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class StringArgument extends Argument {

	/** @var int */
	protected $minLength;

	/** @var int */
	protected $maxLength;

	/** @var string */
	protected $pattern;

	public function __construct( string $name ) {
		parent::__construct( $name );
		$this->type = 'string';
	}

	public function minLength( int $length ): self {
		$this->minLength = $length;

		return $this;
	}

	public function maxLength( int $length ): self {
		$this->maxLength = $length;

		return $this;
	}

	public function pattern( string $pattern ): self {
		$this->pattern = $pattern;

		return $this;
	}

}
