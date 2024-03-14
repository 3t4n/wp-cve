<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Insert_Field_After_Field {

	private $replace_field = 'billing_email';
	private $replace_data = [];
	private $insert_after = 'shipping_last_name';
	private $insert_after_data = [];
	private $position1 = null;
	private $position2 = null;
	private $priority = 12;

	public function __construct( $move_this_field, $after_this_field, $priority = 12 ) {

		$this->replace_field = $move_this_field;
		$this->insert_after  = $after_this_field;
		$this->priority      = $priority;
		add_filter( 'wfacp_get_checkout_fields', [ $this, 'check_insert_after_field_exist' ], $this->priority );
		add_filter( 'wfacp_form_section', [ $this, 'replace_sections' ], $this->priority );
	}

	public function check_insert_after_field_exist( $fields ) {
		$temp = $this->check_provided_field_exist( $fields, $this->replace_field );
		if ( true == $temp['status'] ) {
			$this->replace_data = $temp['field'];

		}
		$temp_2 = $this->check_provided_field_exist( $fields, $this->insert_after );
		if ( true == $temp_2['status'] ) {
			$this->insert_after_data = $temp_2['field'];
		}

		return $fields;
	}


	private function check_provided_field_exist( $fields, $insert_after ) {

		$response = [
			'status' => false,
			'field'  => [],
		];
		if ( isset( $fields['billing'][ $insert_after ] ) ) {
			$response['status'] = true;
			$response['field']  = $fields['billing'][ $insert_after ];
		}
		if ( isset( $fields['shipping'][ $insert_after ] ) ) {
			$response['status'] = true;
			$response['field']  = $fields['shipping'][ $insert_after ];
		}
		if ( isset( $fields['advanced'] ) && isset( $fields['advanced'][ $insert_after ] ) ) {
			$response['status'] = true;
			$response['field']  = $fields['advanced'][ $insert_after ];
		}

		return $response;
	}

	public function replace_sections( $section ) {

		if ( empty( $this->replace_data ) || empty( $this->insert_after_data ) ) {
			return $section;
		}

		if ( is_null( $this->position1 ) ) {
			$position1 = $this->search_field( $section['fields'], $this->replace_field );
			if ( ! is_null( $position1 ) ) {
				$this->position1 = $position1;
				unset( $section['fields'][ $position1 ] );
			}
		}

		$position2 = $this->search_field( $section['fields'], $this->insert_after );

		if ( ! is_null( $position2 ) ) {
			if ( ! is_null( $this->position1 ) ) {
				$this->position2   = $position2 + 1;
				$section['fields'] = WFACP_Common::array_insert_after( $section['fields'], $position2, [ $this->position2 => $this->replace_data ] );
			}
		}

		return $section;
	}

	private function search_field( $fields, $search ) {

		if ( empty( $fields ) ) {
			return null;
		}

		foreach ( $fields as $index => $field ) {
			if ( isset( $field['id'] ) && $search == $field['id'] ) {
				return $index;
			}
		}

		return null;
	}

}
