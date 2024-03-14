<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Api\Normalizer\Normalizer;

/**
 * @implements \WPDesk\ShopMagic\Api\Normalizer\Normalizer<Field>
 */
class JsonSchemaFieldNormalizer implements \WPDesk\ShopMagic\Api\Normalizer\Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			InvalidArgumentException::invalid_object( Field::class, $object );
		}

		return [
			"type"         => "string",
			"title"        => $object->get_label(),
			"description"  => $object->get_description(),
			"format"       => $object->get_type(),
			"default"      => $object->get_default_value(),
			"readOnly"     => $object->is_readonly(),
			"examples"     => [
				$object->get_placeholder(),
			],
			"presentation" => [
				"position" => $object->get_priority(),
			],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Field;
	}
}
