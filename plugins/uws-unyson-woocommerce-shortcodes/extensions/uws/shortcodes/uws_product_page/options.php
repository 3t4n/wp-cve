<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'unique_id' => array(
		'type' => 'unique'
	),
	'id'        => array(
		'type'       => 'multi-select',
		'value'      => array(),
		'label'      => __( 'Select Product', 'uws' ),
		'desc'       => __( 'Select a product to display', 'uws' ),
		'population' => 'posts',
		'source'     => 'product',
		'limit'      => 1,
	),
	'class'     => array(
		'label' => __( 'Custom Class', 'uws' ),
		'desc'  => __( 'Enter custom CSS class', 'uws' ),
		'type'  => 'text',
		'value' => '',
	),
);