<?php
$attributes = [
	'orderby'        => $settings['orderby'],
	'order'          => $settings['order'],
	'posts_per_page' => $settings['per_page'],
	'offset'         => $settings['offset'],
];

if ( $settings['hotel'] ) {
	$attributes['hotel'] = $settings['hotel'];
}

$shortcode_atts = '';
foreach ( $attributes as $attribute => $value ) {
	$shortcode_atts .= $attribute . '="' . esc_attr( $value ) . '" ';
}

echo do_shortcode( '[awebooking_rooms ' . $shortcode_atts . ']' );
