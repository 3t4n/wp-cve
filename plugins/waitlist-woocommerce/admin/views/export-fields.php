<?php

$fields = array(

	'products_table' => array(
		'product_id' 	=> array(
			'title' 	=> 'Product ID',
			'checked' 	=> 'yes'
		),
		'product_sku'	=> array(
			'title' 	=> 'Product SKU',
			'checked' => 'yes'
		),	
		'product_name'	=> array(
			'title' 	=> 'Product Name',
			'checked' 	=> 'yes'
		),
		'stock_status' 	=> array(
			'title' 	=> 'Stock Status',
			'checked' 	=> 'yes'
		),
		'quantity' 		=> array(
			'title' 	=> 'Quantity',
			'checked' 	=> 'yes'
		),
		'users_count' 	=> array(
			'title' 	=> 'No. of Users',
			'checked' 	=> 'yes'
		),
		'product_link' 	=> array(
			'title' 	=> 'Product Link',
			'checked' => 'no'
		),
	),

	'users_table' => array(
		'joined_on' => array(
			'title' 	=> 'Joined on',
			'checked' 	=> 'yes',
		),
		'email' => array(
			'title' 	=> 'Email',
			'checked' 	=> 'yes',
		),
		'quantity' => array(
			'title' 	=> 'Quantity',
			'checked' 	=> 'yes',
		),

		'is_registered' => array(
			'title' 	=> 'Registered',
			'checked' 	=> 'yes',
		),
	)
);


$customFields = xoo_wl()->aff->fields->get_fields_data();

$predefined_fields = array(
	'xoo_wl_user_email',
	'xoo_wl_required_qty'
);

foreach ( $customFields as $field_id => $field_data ) {
	if( in_array( $field_id , $predefined_fields ) ) continue;
	$settings = $field_data['settings'];
	$fields['users_table'][ $field_id ] = array(
		'title' => $settings['label'] ? $settings['label'] : ( isset( $settings['placeholder'] ) && $settings['placeholder'] ? $settings['placeholder'] : $field_id ),
		'checked' => $settings['active'] === 'yes' ? 'yes' : 'no'
	);
}

return $fields;

?>