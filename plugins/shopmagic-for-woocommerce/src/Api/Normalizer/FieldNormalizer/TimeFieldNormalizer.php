<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use WPDesk\ShopMagic\Admin\Form\Fields\InputTimeField;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;

class TimeFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function normalize( object $object ): array {
		if (!$this->supports_normalization($object)) {
			throw InvalidArgumentException::invalid_object(InputTimeField::class, $object);
		}
		return array_merge(
			parent::normalize( $object ),
			[
				'format' => 'time'
			]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof InputTimeField;
	}

}
