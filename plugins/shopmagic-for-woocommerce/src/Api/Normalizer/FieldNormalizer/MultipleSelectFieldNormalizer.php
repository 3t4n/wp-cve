<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;
use WPDesk\ShopMagic\Admin\Form\Fields\MultipleCheckboxField;

class MultipleSelectFieldNormalizer extends SelectFieldNormalizer {

	public function normalize( object $object ): array {
		return array_merge(
			parent::normalize( $object ),
			[ "uniqueItems" => true ]
		);
	}

	public function supports_normalization( object $object ): bool {
		return ( $object instanceof SelectField && $object->is_multiple() ) ||
		       $object instanceof MultipleCheckboxField;
	}

}
