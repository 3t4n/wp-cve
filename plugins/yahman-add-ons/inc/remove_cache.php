<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_remove_all_cache() {
	$allposts = get_posts( 'numberposts=-1&post_type=any&post_status=any' );
	foreach( $allposts as $postinfo ) {
		yahman_addons_remove_cache( $postinfo->ID );
	}
}

function yahman_addons_remove_cache( $post_ID ) {

	delete_transient( 'ya_faster_cache_' . $post_ID );

	$post_data = get_post($post_ID);

	$count = substr_count( $post_data->post_content , '<!--nextpage-->' );

	if($count === 0) return;

	while($count >= 0){
		delete_transient( 'ya_faster_cache_' . $post_ID . '-' . ($count + 1) );
		--$count;
	}

}


