<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\DatePickerField;

class DateFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function normalize( object $object ): array {
		return array_merge(
			parent::normalize( $object ),
			[
				'format' => 'date-time'
			]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof DatePickerField;
	}

}
