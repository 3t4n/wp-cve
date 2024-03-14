<?php

if ( filter_input( INPUT_GET, 'app-builder-search' ) ) {

	define( 'SHORINIT', true );

	require_once ABSPATH . '/wp-load.php';

	$search = sanitize_text_field( $_GET['app-builder-search'] );

	global $wpdb;

	$return = array();

	// Escape the search string.
	$search = $wpdb->esc_like( $search );

	$posts = $wpdb->get_results( "SELECT * FROM wp_posts WHERE MATCH (`post_title`, `post_content`) AGAINST ('$search' IN NATURAL LANGUAGE MODE)" );

	foreach ( $posts as $post ) {
		$newPost = array();

		$newPost['id']      = (int) $post->ID;
		$newPost['title']   = $post->post_title;
		$newPost['url']     = $post->guid;
		$newPost['type']    = $post->post_type;
		$newPost['subtype'] = $post->post_type;

		$return[] = $newPost;
	}

	wp_send_json( $return );
}

if ( filter_input( INPUT_GET, 'app-builder-lang' ) ) {

	define( 'SHORINIT', true );

	require_once ABSPATH . '/wp-load.php';

	$lang = sanitize_text_field( $_GET['app-builder-lang'] );

	$languages = file_get_contents( plugin_dir_url( __DIR__ ) . "assets/lang/$lang" );

	wp_send_json( json_decode( $languages ) );
}

if ( filter_input( INPUT_GET, 'app-builder-return' ) ) {
	require_once ABSPATH . '/wp-load.php';
	$success = filter_input( INPUT_GET, 'success' );
	if ( $success ) {
		$data = json_decode( get_option( 'app_builder_test_site' ), true );
		delete_option( 'app_builder_test_site' );
		// build the url to redirect to.
		$qr  = build_query( $data );
		$app = "cirilla://?$qr";
		header( 'Location: ' . $app );
		exit;
	} else {
		echo 'Error';
		exit;
	}
}

if ( filter_input( INPUT_GET, 'app-builder-callback' ) ) {
	require_once ABSPATH . '/wp-load.php';
	update_option( 'app_builder_test_site', file_get_contents( 'php://input' ) );
	exit;
}
