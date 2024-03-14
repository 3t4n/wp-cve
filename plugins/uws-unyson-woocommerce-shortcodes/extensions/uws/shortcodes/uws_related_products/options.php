<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'unique_id' => array(
		'type' => 'unique'
	),
	'limit'     => array(
		'label' => __( 'Limit', 'uws' ),
		'desc'  => __( 'Enter the limit', 'uws' ),
		'type'  => 'short-text',
		'value' => '12',
	),
	'columns'   => array(
		'label'   => __( 'Columns', 'uws' ),
		'desc'    => __( 'Enter the columns', 'uws' ),
		'type'    => 'short-select',
		'value'   => '4',
		'choices' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		)
	),
	'orderby'   => array(
		'label'   => __( 'Order by', 'uws' ),
		'desc'    => __( 'Select the order by', 'uws' ),
		'type'    => 'short-select',
		'value'   => 'title',
		'choices' => array(
			'title'      => __( 'Title', 'uws' ),
			'date'       => __( 'Date', 'uws' ),
			'id'         => __( 'Id', 'uws' ),
			'menu_order' => __( 'Menu Order', 'uws' ),
			'popularity' => __( 'Popularity', 'uws' ),
			'rand'       => __( 'Randomly', 'uws' ),
			'rating'     => __( 'Rating', 'uws' ),
		)
	),
	'class'     => array(
		'label' => __( 'Custom Class', 'uws' ),
		'desc'  => __( 'Enter custom CSS class', 'uws' ),
		'type'  => 'text',
		'value' => '',
	),
);