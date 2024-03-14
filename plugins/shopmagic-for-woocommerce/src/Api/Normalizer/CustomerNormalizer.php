<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Customer\Customer;

/**
 * @implements Normalizer<Customer>
 */
class CustomerNormalizer implements Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Customer::class, $object );
		}

		$meta = [
			'firstName' => $object->get_first_name(),
			'lastName'  => $object->get_last_name(),
			'phone'     => $object->get_phone(),
		];

		return [
			'id'     => $object->get_id(),
			'object' => 'customer',
			'email'  => $object->get_email(),
			'guest'  => $object->is_guest(),
			'meta'   => array_filter( $meta ),
			'_links' => [
				'edit' => [ 'href' => $object->is_guest() ? get_edit_user_link( $object->get_id() ) : '' ],
			],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Customer;
	}
}
