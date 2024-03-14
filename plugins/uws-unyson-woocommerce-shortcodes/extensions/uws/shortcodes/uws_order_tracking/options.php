<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'unique_id' => array(
		'type' => 'unique'
	),
	'class'     => array(
		'label' => __( 'Custom Class', 'uws' ),
		'desc'  => __( 'Enter custom CSS class', 'uws' ),
		'type'  => 'text',
		'value' => '',
	),
);