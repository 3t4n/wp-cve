<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\Paragraph;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;

class ParagraphFieldNormalizer extends JsonSchemaFieldNormalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Paragraph::class, $object );
		}

		return array_merge(
			parent::normalize( $object ),
			[
				'readOnly' => true,
			]
		);
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Paragraph;
	}

}
