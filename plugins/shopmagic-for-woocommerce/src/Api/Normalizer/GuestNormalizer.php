<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Customer\Guest\GuestMeta;

/**
 * @implements Normalizer<Guest>
 */
class GuestNormalizer implements Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Guest::class, $object );
		}

		$meta = $object->get_meta()
		               ->map( static function ( GuestMeta $m ) {
			               return [ $m->get_meta_key(), $m->get_meta_value() ];
		               } )
		               ->to_array();

		return [
			'id'         => $object->get_raw_id(),
			'object'     => 'customer',
			'email'      => $object->get_email(),
			'created'    => $object->get_created()->format( \DateTimeInterface::ATOM ),
			'lastActive' => $object->get_updated()->format( \DateTimeInterface::ATOM ),
			'guest'      => true,
			'meta'       => array_column( $meta, 1, 0 ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Guest;
	}
}
