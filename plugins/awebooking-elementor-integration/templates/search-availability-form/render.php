<?php
$attributes = [
	'layout'         => $settings['layout'],
	'hotel_location' => $settings['hotel_location'] ? $settings['hotel_location'] : false,
	'occupancy'      => $settings['occupancy'] ? $settings['occupancy'] : false,
];

$shortcode_atts = '';
foreach ( $attributes as $attribute => $value ) {
	$shortcode_atts .= $attribute . '="' . esc_attr( $value ) . '" ';
}

echo do_shortcode( '[awebooking_search_form ' . $shortcode_atts . ']' );
