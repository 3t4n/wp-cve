<?php

namespace WPDesk\DropshippingXmlFree\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields as ImportMapperFormFieldsCore;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;

/**
 * Class ImportMapperFormFields, import mapper form fields.
 */
class ImportMapperFormFields extends ImportMapperFormFieldsCore {

	const FIELDS_TO_DISABLE = [
		ImportMapperFormFieldsCore::PRODUCT_PRICE_MODIFICATOR,
		ImportMapperFormFieldsCore::PRODUCT_PRICE_MODIFICATOR_VALUE,
		ImportMapperFormFieldsCore::PRODUCT_ATTRIBUTE_AS_TAXONOMY,
		ImportMapperFormFieldsCore::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_AUTO_CREATE,
		ImportMapperFormFieldsCore::PRODUCT_IMAGES_APPEND_TO_EXISTING,
	];

	const VARIATION_FIELDS_TO_DISABLE = [
		VariationComponent::PRODUCT_PRICE_MODIFICATOR,
		VariationComponent::PRODUCT_PRICE_MODIFICATOR_VALUE,
	];

	const FIELDS_TO_REMOVE = [
		ImportMapperFormFieldsCore::PRODUCT_EXTERNAL_URL,
		ImportMapperFormFieldsCore::PRODUCT_EXTERNAL_BUTTON_TEXT,
	];


	/**
	 * @see FieldProvider::get_fields()
	 */
	public function get_fields() {
		$fields = parent::get_fields();
		foreach ( $fields as $key => $field ) {
			if ( \in_array( $field->get_name(), self::FIELDS_TO_DISABLE, true ) && $field instanceof BasicField ) {
				$this->disable_field( $field );
			}

			if ( $field->get_name() === ImportMapperFormFieldsCore::VARIATION_EMBEDDED ) {
				$this->disable_variations_field( $field );
			}

			if ( $field->get_name() === ImportMapperFormFieldsCore::PRODUCT_PRICE_MODIFICATOR_CONDITIONS ) {
				$this->disable_price_component_field( $field );
			}

			if ( \in_array( $field->get_name(), self::FIELDS_TO_REMOVE, true ) && $field instanceof BasicField ) {
				unset( $fields[ $key ] );
			}

			if ( $field->get_name() === ImportMapperFormFieldsCore::PRODUCT_TYPE && $field instanceof SelectField ) {
				$field->set_options(
					[
						ImportMapperFormFieldsCore::PRODUCT_TYPE_OPTION_SIMPLE   => __( 'Simple product', 'woocommerce-dropshipping-xml-core' ), // phpcs:ignore
						ImportMapperFormFieldsCore::PRODUCT_TYPE_OPTION_VARIABLE => __( 'Variable product', 'woocommerce-dropshipping-xml-core' ), // phpcs:ignore
					]
				);
			}
		}
		return $fields;
	}

	private function disable_field( BasicField $field ) {
		$field->set_disabled();
	}

	private function disable_variations_field( VariationComponent $field ) {
		$items = $field->get_items();
		foreach ( $items as $item ) {
			if ( \in_array( $item->get_name(), self::VARIATION_FIELDS_TO_DISABLE, true ) && $item instanceof BasicField ) {
				$this->disable_field( $item );
			}
		}
	}

	private function disable_price_component_field( PriceModificatorComponent $field ) {
		$items = $field->get_price_item_modificator_fields();
		foreach ( $items as $item ) {
			if ( $item instanceof BasicField ) {
				$this->disable_field( $item );
			}
		}

		$items = $field->get_items();
		foreach ( $items as $item ) {
			if ( $item instanceof BasicField ) {
				$this->disable_field( $item );
			}
		}
	}
}
