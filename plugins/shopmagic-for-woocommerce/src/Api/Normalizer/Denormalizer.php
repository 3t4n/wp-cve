<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

/**
 * @template T of object
 */
interface Denormalizer {

	/**
	 * @param array $payload
	 *
	 * @return T
	 */
	public function denormalize( array $payload ): object;

	public function supports_denormalization( array $data ): bool;

}
