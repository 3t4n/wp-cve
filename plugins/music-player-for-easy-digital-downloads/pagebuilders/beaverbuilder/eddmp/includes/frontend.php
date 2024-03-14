<?php
if ( ! empty( $settings->downloads_ids ) ) {
	$downloads_ids = preg_replace( '/[^\d\,]/', '', $settings->downloads_ids );
}
if ( empty( $downloads_ids ) ) {
	$downloads_ids = '*';
}

$output = '[eddmp-playlist downloads_ids="' . sanitize_text_field( $downloads_ids ) . '"';

if ( ! empty( $settings->attributes ) ) {
	$output .= ' ' . sanitize_text_field( $settings->attributes );
}

$output .= ']';
echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
