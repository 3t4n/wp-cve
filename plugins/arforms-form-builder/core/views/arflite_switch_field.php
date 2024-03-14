<?php
define( 'ARFLITE_SWITCH_SLUG', 'arf_switch' );

global $arflite_switch_new_field_data, $arflite_switch_field_image_path;

$arflite_switch_new_field_data = array( ARFLITE_SWITCH_SLUG => __( 'Switch', 'arforms-form-builder' ) );
$arflite_switch_total_class    = array();
$arflite_switch_field_class    = new arflite_switch_field();

class arflite_switch_field {

	function __construct() {

		add_filter( 'arfliteaavailablefields', array( $this, 'arflite_add_switch_field_in_list' ), 10 );

	}

	function arflite_add_switch_field_in_list( $fields ) {

		$fields['arf_switch'] = array(
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -7 30 30"><g id="smiley"><path xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#4E5462" stroke-width="2" d="M22.194,1.617h-14c-3.866,0-7,3.134-7,7s3.134,7,7,7h14c3.866,0,7-3.134,7-7S26.06,1.617,22.194,1.617zM9.194,12.617c-2.209,0-4-1.791-4-4s1.791-4,4-4s4,1.791,4,4S11.403,12.617,9.194,12.617z"/></g></svg>',
			'label' => __( 'Switch', 'arforms-form-builder' ),
		);

		return $fields;
	}

}
