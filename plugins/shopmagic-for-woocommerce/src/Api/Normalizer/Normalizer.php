<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

/**
 * @template T of object
 */
interface Normalizer {

	/**
	 * @param T|object $object
	 * @phpstan-param T $object
	 *
	 * @return array
	 */
	public function normalize( object $object ): array;

	/**
	 * @param object $object
	 *
	 * @phpstan-assert-if-true T $object
	 * @return bool
	 */
	public function supports_normalization( object $object ): bool;

}
