<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\ProductSelect;

class ProductSelectFieldNormalizer extends JsonSchemaFieldNormalizer {

	/**
	 * @param ProductSelect|object $object
	 *
	 * @return array
	 */
	public function normalize( object $object ): array {
		return array_merge(
			parent::normalize( $object ),
			[
				"type"         => 'array',
				'items'        => [
					'type' => 'number',
				],
				'uniqueItems'  => true,
				'presentation' => [
					'type' => 'products',
				],
			]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof ProductSelect;
	}
}
