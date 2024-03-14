<?php

#[AllowDynamicProperties] 

  class WFACP_Add_Address_Field {
	private $type = 'billing';
	private $id = '';
	private $field = [];

	public function __construct( $id, $field, $type = 'billing', $show_admin = true ) {

		if ( empty( $id ) || empty( $field ) ) {
			return;
		}

		$this->id    = $id;
		$this->field = $field;
		$this->type  = $type;
		add_filter( 'wfacp_unset_address_fields', [ $this, 'add_to_unset_field' ] );
		if ( true == $show_admin ) {
			add_filter( 'woocommerce_admin_' . $type . '_fields', [ $this, 'display_in_order_edit_screen' ] );
		}

		add_filter( 'wfacp_default_' . $this->type . '_address_fields', [ $this, 'add_to_default_address_fields' ] );
		add_filter( 'wfacp_' . $this->type . '_address_options', [ $this, 'add_to_address_options' ] );
	}

	public function add_to_unset_field( $unset_fields ) {

		$unset_fields[ $this->type . '_' ][] = $this->type . '_' . $this->id;

		return $unset_fields;
	}

	public function add_to_default_address_fields( $fields ) {
		$fields[ $this->id ] = $this->field;

		return $fields;
	}

	public function add_to_address_options( $options ) {
		$options[ $this->id ] = array(
			$this->id                  => 'true',
			$this->id . '_label'       => $this->field['label'],
			$this->id . '_placeholder' => isset( $this->field['placeholder'] ) ? $this->field['placeholder'] : false,
			'hint'                     => __( 'Field ID: ', 'woofunnels-aero-checkout' ) . $this->type . '_' . $this->id,
			'required'                 => isset( $this->field['required'] ) ? $this->field['required'] : false,
		);

		return $options;
	}

	public function display_in_order_edit_screen( $fields ) {
		$fields[ $this->id ] = [ 'label' => $this->field['label'] ];

		return $fields;
	}
}