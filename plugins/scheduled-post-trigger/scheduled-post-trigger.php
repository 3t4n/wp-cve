<?php
/**
 * Plugin Name: MWW Scheduled Post Trigger
 * Description: This plugin triggers scheduled posts that were missed by the server's cron.
 * Version: 3.2
 * Author: Moss Web Works
 * Author URI: http://mosswebworks.com
 * License: GPL2
 */
function pubMissedPosts() {
	if (is_front_page() || is_single()) {

		global $wpdb;
		$now=gmdate('Y-m-d H:i:00');
		
		//CHECK IF THERE ARE CUSTOM POST TYPES
		$args = array(
       'public'   => true,
       '_builtin' => false,
    	);

	    $output = 'names'; // names or objects, note names is the default
    	$operator = 'and'; // 'and' or 'or'
	    $post_types = get_post_types( $args, $output, $operator ); 
	
		
		if (count($post_types)===0) {
			$sql="Select ID from $wpdb->posts WHERE post_type in ('post','page') AND post_status='future' AND post_date_gmt<'$now'";
		}
		else {
			$str=implode ('\',\'',$post_types);
			$sql="Select ID from $wpdb->posts WHERE post_type in ('page','post','$str') AND post_status='future' AND post_date_gmt<'$now'";
		}
		
		$resulto = $wpdb->get_results($sql);
 		if($resulto) {
			foreach( $resulto as $thisarr ) {
				wp_publish_post($thisarr->ID);
			}
		}
	}
}
add_action('wp_head', 'pubMissedPosts'); 
?>