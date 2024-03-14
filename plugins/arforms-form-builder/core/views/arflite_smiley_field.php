<?php
define( 'ARFLITE_SMILEY_SLUG', 'arf_smiley' );

global $arflite_smiley_field_class_name, $arflite_smiley_new_field_data, $arflite_smiley_field_image_path, $arflite_font_awesome_loaded;

$arflite_smiley_field_image_path = array( ARFLITE_SMILEY_SLUG => ARFLITEIMAGESURL . '/fields_elements_icon/smiley-field-icon.png' );
$arflite_smiley_field_class_name = array( ARFLITE_SMILEY_SLUG => 'red' );
$arflite_smiley_new_field_data   = array( ARFLITE_SMILEY_SLUG => __( 'Smiley', 'arforms-form-builder' ) );
$arflite_smiley_total_class      = array();
$arflite_smiley_field_class      = new arflite_smiley_field();

global $arflite_smiley_loaded;
$arflite_smiley_loaded = array();

class arflite_smiley_field {

	function __construct() {

		add_filter( 'arfliteaavailablefields', array( $this, 'arflite_add_smiley_field_in_list' ), 10 );

	}


	function arflite_add_smiley_field_in_list( $fields ) {

		$fields['arf_smiley'] = array(
			'icon'  => '<svg viewBox="0 0 30 30"><g id="smiley"><path fill="#4E5462" d="M15.236,28.534c-7.7,0-14.091-6.3-14.091-14s6.392-14,14.091-14c7.702,0,14,6.3,14,14S22.938,28.534,15.236,28.534z M15.236,2.558C8.564,2.558,3.26,7.862,3.26,14.534c0,6.673,5.304,11.976,11.976,11.976c6.673,0,11.976-5.303,11.976-11.976C27.211,7.862,21.909,2.558,15.236,2.558z M15.423,22.509c-3.5,0-6.65-2.101-8.05-5.427l1.575-0.698c1.05,2.625,3.675,4.198,6.476,4.198c2.799,0,5.424-1.75,6.475-4.198l1.574,0.698C22.073,20.583,18.923,22.509,15.423,22.509z M19.643,13.035c-1.104,0-2-0.897-2-2.001s0.897-2.001,2-2.001c1.104,0,2.002,0.897,2.002,2.001S20.747,13.035,19.643,13.035z M10.672,13.035c-1.104,0-2.001-0.897-2.001-2.001s0.897-2.001,2.001-2.001s2,0.897,2,2.001S11.776,13.035,10.672,13.035z"/></g></svg>',
			'label' => __( 'Smiley', 'arforms-form-builder' ),
		);

		return $fields;
	}

}
