<?php
global $arflite_multiselect_field_class;
$arflite_multiselect_field_class = new arflite_multiselect_field();

class arflite_multiselect_field {

	function __construct() {

		add_filter( 'arfliteaavailablefields', array( $this, 'arflite_add_multiselect_field_element_list' ), 11 );
	}

	function arflite_add_multiselect_field_element_list( $fields ) {

		$fields['arf_multiselect'] = array(
			'icon'  => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60.123 60.123" style="enable-background:new 0 0 60.123 60.123;" xml:space="preserve">
					<g fill="#4E5462">
						<path d="M57.124,51.893H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,51.893,57.124,51.893z"/>
						<path d="M57.124,33.062H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3
							C60.124,31.719,58.781,33.062,57.124,33.062z"/>
						<path d="M57.124,14.231H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,14.231,57.124,14.231z"/>
						<circle cx="4.029" cy="11.463" r="4.029"/>
						<circle cx="4.029" cy="30.062" r="4.029"/>
						<circle cx="4.029" cy="48.661" r="4.029"/>
					</g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>',
			'label' => __( 'Multi Select', 'arforms-form-builder' ),
		);

		return $fields;
	}
}
