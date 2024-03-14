<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

/**
 * @implements Normalizer<\WC_Order>
 */
class OrderNormalizer implements Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( \WC_Order::class, $object );
		}

		return [
			'id'     => $object->get_id(),
			'object' => 'order',
			'_links' => [
				'edit' => [ 'href' => $object->get_edit_order_url() ],
			],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof \WC_Order;
	}
}
