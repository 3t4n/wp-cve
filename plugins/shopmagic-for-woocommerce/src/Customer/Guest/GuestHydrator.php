<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;

/**
 * @implements ObjectHydrator<Guest>
 * @implements ObjectDehydrator<Guest>
 */
final class GuestHydrator implements ObjectHydrator, ObjectDehydrator {

	public static function generate_tracking_key(): string {
		return md5( uniqid( 'sm_', true ) );
	}

	public function denormalize( array $payload ): object {
		$guest = new Guest();
		$guest->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$guest->set_email( (string) $payload['email'] );
		$guest->set_tracking_key( $payload['tracking_key'] ?? self::generate_tracking_key() );
		$guest->set_created( new \DateTimeImmutable( $payload['created'] ?? 'now' ) );
		$guest->set_updated( new \DateTimeImmutable( $payload['updated'] ?? 'now' ) );

		return $guest;
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Guest::class, $object );
		}

		return [
			'id'           => $object->get_raw_id(),
			'email'        => $object->get_email(),
			'tracking_key' => $object->get_tracking_key(),
			'created'      => $object->get_created()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
			'updated'      => $object->get_updated()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Guest;
	}
}
