<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function un_enoty_get_option( $name ){
    $easynotify_values = get_option( 'easynotify_opt' );
    if ( is_array( $easynotify_values ) && array_key_exists( $name, $easynotify_values ) ) return $easynotify_values[$name];
    return false;
} 

function easynotify_clean_data() {

	if ( un_enoty_get_option('easynotify_disen_databk') != '1' ) {

// Remove plugin-specific custom post type entries.	
		$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'easynotify',
		'post_status' => 'any' ) );

			foreach ( $posts as $post )
				{
				wp_delete_post( $post->ID, true );
					}
		// Remove plugin options from database.	
		delete_option( 'easynotify_opt' );	
	}
	
}

easynotify_clean_data();

?>