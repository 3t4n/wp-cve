<?php

$placeholders = array(
	'[b]' 				=> 'Bold',
	'[/b]' 				=> 'End Bold',
	'[new_line]' 		=> 'New Line',
	'[i]' 				=> 'Italic',
	'[/i]' 				=> 'End Italic',
	'[user_email]' 		=> 'User email',
	'[quantity]' 		=> 'Quantity requested',
	'[join_date]' 		=> 'Waitlisted Date',
	'[product_id]' 		=> 'Product ID',
	'[product_name]' 	=> 'Product name',
	'[product_link]' 	=> 'Product link',
	'[product_price]' 	=> 'Product price'
);


$customFields = xoo_wl()->aff->fields->get_fields_data();

$predefined_fields = array(
	'xoo_wl_user_email',
	'xoo_wl_required_qty'
);


$placeholders_text = '';
foreach ( $placeholders as $key => $desc ) {
	$placeholders_text .= '<span>'.$key .' - '.$desc.'</span>';
}

?>

<div id="xoo-wl-placeholder-nfo"><?php echo wp_kses_post( $placeholders_text ); ?></div>

<h4>Custom Field Placeholders</h4>

<div id="xoo-wl-placeholder-nfo">

	<?php

	foreach ( $customFields as $field_id => $field_data ) {
		if( in_array( $field_id , $predefined_fields ) ) continue;
		$settings = $field_data['settings'];
		$label = $settings['label'] ? $settings['label'] : ( $settings['placeholder'] ? $settings['placeholder'] : $field_id.' value' );
		echo esc_html( '<span>'.'['.$field_id.']' .' - '.$label.'</span>' );
	}

	?>
</div>

<h4>Heading Placeholders</h4>

<div id="xoo-wl-placeholder-nfo">

	<?php

	for ( $i= 1; $i <= 6 ; $i++ ) {
		echo '<span>[h'.(int) $i.'][/h'.(int) $i.'] - Heading'.(int) $i.'</span>';
	}

	?>
</div>