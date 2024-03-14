<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class Argument {

	public $name;

	public $required = false;

	public $default;

	public $description;

	public $type;

	public $sanitize_callback;

	public $validate_callback;

	public function __construct( string $name ) {
		$this->name = $name;
	}

	/** @return static */
	public function required(): self {
		$this->required = true;

		return $this;
	}

	/** @return static */
	public function default( $default ): self {
		$this->default = $default;

		return $this;
	}

	/** @return static */
	public function description( string $description ): self {
		$this->description = $description;

		return $this;
	}

	/** @return static */
	public function type( string $type ): self {
		$this->type = $type;

		return $this;
	}

	/** @return static */
	public function sanitization( callable $sanitization ): self {
		$this->sanitize_callback = $sanitization;

		return $this;
	}

	/** @return static */
	public function validation( callable $validation ): self {
		$this->validate_callback = $validation;

		return $this;
	}

	public function to_array(): array {
		return array_filter( get_object_vars( $this ) );
	}

}
