<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer;

use ShopMagicVendor\WPDesk\Forms\Field;
use WPDesk\ShopMagic\Admin\Form\Fields\ModuleField;

class ModuleFieldNormalizer extends CheckboxFieldNormalizer {

	/**
	 * @param Field|object $object
	 *
	 * @return array
	 */
	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw new \InvalidArgumentException( 'Wrong object' );
		}
		$normalized_field                               = parent::normalize( $object );
		$normalized_field['presentation']['pluginSlug'] = $object->get_meta_value( 'plugin_slug' );

		return $normalized_field;
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof ModuleField;
	}
}
