<?php 

	// Ensure uninstall source is WordPress...
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
	// Define the key used for storing options...
$tag = 'pagemeta';
	// Fetch the plugin options...
if ( $options = get_option( $tag ) ) {
	// Remove all traces of the plugin if required...
	// Lookup all posts and remove the meta data for each...
		$posts = get_posts( 'numberposts=-1&post_type=any' );
		foreach ( $posts as $post ) {
			$keys = array( 'title', 'description', 'keywords' );
			foreach ( $keys AS $key ) {
				delete_post_meta( $post->ID, '_'.$tag.'_'.$key );
			}
		}
	// Finally, remove the subheading options...
	delete_option($tag);
}