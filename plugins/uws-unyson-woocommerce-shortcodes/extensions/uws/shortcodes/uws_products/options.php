<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'unique_id' => array(
		'type' => 'unique'
	),
	'type'      => array(
		'label'   => __( 'Type', 'uws' ),
		'desc'    => __( 'Select the type', 'uws' ),
		'type'    => 'short-select',
		'value'   => 'default',
		'choices' => array(
			'default'      => 'Default',
			'on_sale'      => 'On Sale',
			'best_selling' => 'Best Selling',
			'top_rated'    => 'Top Rated',
		)
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
	'category'  => array(
		'label'      => __( 'Categories', 'uws' ),
		'desc'       => __( 'Select the categories', 'uws' ),
		'type'       => 'multi-select',
		'value'      => '',
		'population' => 'taxonomy',
		'source'     => 'product_cat',
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
	'order'     => array(
		'label'   => __( 'Order', 'uws' ),
		'desc'    => __( 'Select the order type', 'uws' ),
		'type'    => 'short-select',
		'value'   => 'title',
		'choices' => array(
			'ASC'  => __( 'ASC', 'uws' ),
			'DESC' => __( 'DESC', 'uws' ),
		)
	),
	'class'     => array(
		'label' => __( 'Custom Class', 'uws' ),
		'desc'  => __( 'Enter custom CSS class', 'uws' ),
		'type'  => 'text',
		'value' => '',
	),
);