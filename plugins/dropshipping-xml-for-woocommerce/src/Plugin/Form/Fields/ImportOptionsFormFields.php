<?php

namespace WPDesk\DropshippingXmlFree\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields as ImportOptionsFormFieldsCore;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField;

/**
 * Class ImportOptionsFormFields, import options form fields.
 */
class ImportOptionsFormFields extends ImportOptionsFormFieldsCore {

	const FIELDS_TO_DISABLE = [
		ImportOptionsFormFieldsCore::FIELD_TURN_ON_LOGICAL_CONDITION,
		ImportOptionsFormFieldsCore::CRON_WEEK_DAY,
		ImportOptionsFormFieldsCore::CRON_HOURS,
	];

	/**
	 * @see FieldProvider::get_fields()
	 */
	public function get_fields() {
		$fields = parent::get_fields();
		foreach ( $fields as $field ) {
			if ( \in_array( $field->get_name(), self::FIELDS_TO_DISABLE, true ) && $field instanceof BasicField ) {
				$this->disable_field( $field );
			}
		}
		return $fields;
	}

	private function disable_field( BasicField $field ) {
		$field->set_disabled();
	}
}
