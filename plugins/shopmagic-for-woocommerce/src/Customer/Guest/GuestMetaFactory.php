<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;

/**
 * @implements ObjectHydrator<GuestMeta>
 * @implements ObjectDehydrator<GuestMeta>
 */
final class GuestMetaFactory implements ObjectHydrator, ObjectDehydrator {

	public function denormalize( array $payload ): object {
		$meta = new GuestMeta();
		$meta->set_meta_id( (int) $payload['meta_id'] );
		$meta->set_guest_id( (int) $payload['guest_id'] );
		$meta->set_meta_key( $payload['meta_key'] );
		$meta->set_meta_value( $payload['meta_value'] );

		return $meta;
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}

	/**
	 * @param object            $object
	 *
	 * @phpstan-param GuestMeta $object
	 */
	public function normalize( object $object ): array {
		return [
			'meta_id'    => $object->get_meta_id(),
			'guest_id'   => $object->get_guest_id(),
			'meta_key'   => $object->get_meta_key(),
			'meta_value' => $object->get_meta_value(),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof GuestMeta;
	}
}
