<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\TextAreaField;
use ShopMagicVendor\WPDesk\Forms\Field\WyswigField;

class TextFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function supports_normalization( object $object ): bool {
		return $object instanceof TextAreaField || $object instanceof WyswigField;
	}

	public function normalize( object $object ): array {
		return array_merge_recursive(
			parent::normalize( $object ),
			[
				'presentation' => [
					'type' => $object instanceof WyswigField ? 'rich' : 'plain'
				]
			]
		);
	}

}
