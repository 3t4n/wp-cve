<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;

class SelectFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( SelectField::class, $object );
		}
		$options = array_map(
			static function ( $value, $label ) {
				return [
					"const" => $value,
					"title" => $label,
				];
			},
			array_keys( $object->get_possible_values() ),
			array_values( $object->get_possible_values() )
		);

		return array_merge(
			parent::normalize( $object ),
			[ "oneOf" => $options ]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof SelectField;
	}
}
