<?php
/**
 * Define Cornerstone Plugin Control for ARFormslite
 *
 * @package ARFormslite
 */

global $arflitemainhelper, $arfliteformhelper, $arfliteversion, $wpdb, $arfliteform;
$arf_cs_control = array();

$forms = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name' );

$arf_forms = array();

$arf_forms[0]['value'] = '';
$arf_forms[0]['label'] =  __( '- Select form -', 'arforms-form-builder' ) ;

if ( ! empty( $forms ) ) {
	$n = 1;
	foreach ( $forms as $key => $forms_data ) {
		$arf_forms[ $n ]['value'] = $forms_data->id;
		$arf_forms[ $n ]['label'] = $forms_data->name . ' [' . $forms_data->id . ']';
		$n++;
	}
}

$arf_cs_control['arf_forms'] = array(
	'type'    => 'select',
	'ui'      => array(
		'title' =>  __( 'Select a form to insert into page', 'arforms-form-builder' ) ,
	),
	'options' => array(
		'choices' => $arf_forms,
	),
);


return $arf_cs_control;
