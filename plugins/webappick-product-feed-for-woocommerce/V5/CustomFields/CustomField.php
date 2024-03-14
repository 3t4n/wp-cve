<?php

namespace CTXFeed\V5\CustomFields;

/**
 * Class CustomField
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\CustomField
 */
class CustomField {
	private $customField;
	
	public function __construct( CustomFieldInterface $customField ) {
		$this->customField = $customField;
	}
	
	public function set() {
		return $this->customField->set_custom_field();
	}
	
	public function save( $post_id ) {
		return $this->customField->save_custom_field_value( $post_id );
	}
}