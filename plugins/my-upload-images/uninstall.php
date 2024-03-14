<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$opt = get_option('mui_options');

if ( isset($opt['keepvalues']) && $opt['keepvalues'] == 'delete_options' ) {

	delete_option( 'mui_options' );

} elseif ( isset($opt['keepvalues']) && $opt['keepvalues'] == 'delete' ) {

	delete_option( 'mui_options' );
	$post_types = get_post_types( array( 'public' => true ), 'names' ); 
	unset($post_types['attachment']); 
	$posts = get_posts( array( 'numberposts' => -1, 'post_type' => $post_types, 'post_status' => 'any' ) );
	foreach ( $posts as $post ) delete_post_meta( $post->ID, 'my_upload_images' );

}

return;

