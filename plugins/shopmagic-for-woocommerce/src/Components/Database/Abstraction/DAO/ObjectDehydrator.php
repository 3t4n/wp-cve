<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction\DAO;

/**
 * @template T of object
 */
interface ObjectDehydrator {

	/**
	 * @param array $payload
	 *
	 * @return T
	 */
	public function denormalize( array $payload ): object;

	public function supports_denormalization( array $data ): bool;

}
