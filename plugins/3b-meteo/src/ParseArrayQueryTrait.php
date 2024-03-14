<?php
declare(strict_types=1);

namespace TreBiMeteo;

trait ParseArrayQueryTrait {

	/**
	 * @var array<int, string>
	 */
	private $hex_key = [];

	/**
	 * @param array<int, string> $hex_key
	 * @example ['c1','c2','c3','b1','b2','b3']
	 */
	private function addKeysContainHexValueForUrlBuilder( array $hex_key ): void {
		$this->hex_key = $hex_key;
	}

	private function assertIsValidHexValue( string $val ): bool {
		return \ctype_xdigit( $val );
	}

	/**
	 * @param array<int|string, mixed> $attributes
	 * @return array<int|string, mixed>
	 */
	private function parseArrayQuery( array $attributes ): array {

		if ( empty( $this->hex_key ) ) {
			throw new \RuntimeException('An array with keys must be provided' );
		}

		$hex_key = \array_flip( $this->hex_key );

		$new_array = [];

		/**
		 * @var int|string $value_attr
		 * @example 6 => location id
		 * @example Key string for color "c1"
		 */
		foreach ( $attributes as $key_attr => $value_attr ) {
			if ( $this->isHexProvideNotValid( $key_attr, $hex_key, (string) $value_attr )) {
				continue;
			}

			if ( \is_string( $value_attr ) ) {
				$value_attr = \strtolower( $value_attr );
			}

			/**
			 * @psalm-suppress MixedAssignment
			 */
			$new_array[ $key_attr ] = \esc_attr( (string) $value_attr );
		}

		return $new_array;
	}

	/**
	 * @param int|string $key_attr
	 * @param array<string, int> $hex_key
	 * @param string $value_attr
	 * @return bool
	 */
	public function isHexProvideNotValid( $key_attr, array $hex_key, string $value_attr ): bool {
		return \array_key_exists( $key_attr, $hex_key ) && ! $this->assertIsValidHexValue( $value_attr );
	}
}
