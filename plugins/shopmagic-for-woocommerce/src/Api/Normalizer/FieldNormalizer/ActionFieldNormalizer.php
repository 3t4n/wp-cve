<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use WPDesk\ShopMagic\Admin\Form\Fields\ActionField;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;

class ActionFieldNormalizer extends JsonSchemaFieldNormalizer {

	/**
	 * @param ActionFieldNormalizer|object $object
	 *
	 * @return array
	 */
	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( ActionField::class, $object );
		}

		$result = array_merge(
			parent::normalize( $object ),
			[
				'format' => 'action',
			]
		);

		$result['presentation']['callback'] = $object->get_callback();

		return $result;
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof ActionField;
	}

}
