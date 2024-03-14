<?php
global $arflite_spinner_field_class;
$arflite_spinner_field_class = new arflite_spinner_field();

class arflite_spinner_field {

	function __construct() {

		add_filter( 'arfliteaavailablefields', array( $this, 'arflite_add_spinner_field_element_list' ), 12 );
	}

	function arflite_add_spinner_field_element_list( $fields ) {

		$fields['arf_spinner'] = array(
			'icon'  => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" fill="#4E5462" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"> <path id="XMLID_21_" class="st0" d="M7.9,23.3L26.3,5v13.6c0,0.4,0.4,0.6,0.7,0.6h0.4c0.4,0,0.7-0.4,0.7-0.6V2.9 C28,2.4,27.6,2,27.2,2H2.7C2.4,2,2,2.5,2,2.9v24.1C2,27.5,2.4,28,2.7,28h24.5c0.5,0,0.7-0.5,0.7-0.9v-3c0-0.4-0.4-0.6-0.7-0.6h-0.4 c-0.4,0-0.7,0.4-0.7,0.6v2.1H5.2l0,0H3.8V3.8h21.4L6.7,22.1c-0.3,0.3-0.3,0.6,0,0.9l0.3,0.3C7.2,23.5,7.7,23.5,7.9,23.3z"/><path id="XMLID_11_" class="st0" d="M6.6,9.8V9.1c0-0.2,0.2-0.4,0.4-0.4h5.1c0.3,0,0.4,0.1,0.4,0.4v0.8c0,0.2-0.2,0.4-0.4,0.4H7.1    C6.8,10.2,6.6,10,6.6,9.8z"/> <path id="XMLID_2_" class="st0" d="M9.2,6.4H10c0.2,0,0.4,0.2,0.4,0.4V12c0,0.3-0.1,0.4-0.4,0.4H9.2c-0.2,0-0.4-0.2-0.4-0.4V6.9    C8.9,6.6,9,6.4,9.2,6.4z"/><path id="XMLID_13_" class="st0" d="M17.3,21.2v-0.8c0-0.2,0.2-0.4,0.4-0.4h5.1c0.3,0,0.4,0.1,0.4,0.4v0.8c0,0.2-0.2,0.4-0.4,0.4    h-5.1C17.5,21.6,17.3,21.5,17.3,21.2z"/></svg>',
			'label' => __( 'Spinner', 'arforms-form-builder' ),
		);

		return $fields;
	}
}
