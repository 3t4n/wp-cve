<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function wp3cxw_delete_plugin() {
	delete_option( 'wp3cxw' );

	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type' => 'wp3cxw_webinar_form',
			'post_status' => 'any',
		)
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}
}

wp3cxw_delete_plugin();
