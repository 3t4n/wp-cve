<?php

add_filter( 'map_meta_cap', 'wp3cxw_map_meta_cap', 10, 4 );

function wp3cxw_map_meta_cap( $caps, $cap, $user_id, $args ) {
	$meta_caps = array(
		'wp3cxw_edit_webinar_form' => WP3CXW_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxw_edit_webinar_forms' => WP3CXW_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxw_read_webinar_forms' => WP3CXW_ADMIN_READ_CAPABILITY,
		'wp3cxw_delete_webinar_form' => WP3CXW_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxw_submit' => 'read',
	);

	$meta_caps = apply_filters( 'wp3cxw_map_meta_cap', $meta_caps );

	$caps = array_diff( $caps, array_keys( $meta_caps ) );

	if ( isset( $meta_caps[$cap] ) ) {
		$caps[] = $meta_caps[$cap];
	}

	return $caps;
}
