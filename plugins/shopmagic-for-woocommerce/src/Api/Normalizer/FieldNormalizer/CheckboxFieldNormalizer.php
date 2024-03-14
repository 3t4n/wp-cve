<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;

class CheckboxFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( CheckboxField::class, $object );
		}

		return array_merge(
			parent::normalize( $object ),
			[
				"type" => ["boolean", "string"],
				"extendedCoerce" => true,
			]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof CheckboxField;
	}

}
