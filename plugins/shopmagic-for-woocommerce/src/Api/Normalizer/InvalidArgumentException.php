<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

class InvalidArgumentException extends \InvalidArgumentException implements
	\WPDesk\ShopMagic\Exception\ShopMagicException {

	/**
	 * @param string        $expected
	 * @param string|object $actual
	 *
	 * @return static
	 */
	public static function invalid_object( string $expected, $actual ): self {
		return new self(
			sprintf( "Instance of %s required, but %s provided",
				$expected,
				is_object( $actual ) ? get_class( $actual ) : $actual )
		);
	}

	public static function invalid_payload( string $target_class ): self {
		return new self( sprintf( "Data provided to hydrate class %s are invalid", $target_class ) );
	}

}
