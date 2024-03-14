<?php
/*
Plugin Name: Permalinks Moved Permanently
Plugin URI: http://www.microkid.net/wordpress/permalinks-moved-permanently/
Description: When permalink isn't found, this checks if a post with the requested slug exists somewhere else on your blog.
Version: 1.3
Author: Microkid
Author URI: http://www.microkid.net/

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


  // Check if a given slug belongs to a post in the database
function bas_post_exists_elsewhere( $slug ) {
 
 	global $wpdb;
 	 
 	if( $ID = $wpdb->get_var( 'SELECT ID FROM '.$wpdb->posts.' WHERE post_name = "'.$slug.'" AND post_status = "publish" ' ) ) {
		return $ID;
	}
	else {
		return false;
	}
	
}


function bas_forward_to_new_location( $post_new_location ) {
	header( "HTTP/1.1 301 Moved Permanently" );
	header( "Location: $post_new_location" );
}


  // When the post is not found, and is_404() == true, check
  // if the requested slug belongs to a post in the database.
  
function bas_permalink_moved_permanently() {
 
	 if( is_404() ) {
	 
	 	$slug = basename( $_SERVER['REQUEST_URI'] );
	 	 
	 	if( $ID = bas_post_exists_elsewhere( $slug ) ) {
	 		
	 		bas_forward_to_new_location( get_permalink( $ID ) );
		
		}
	}
}


  // Plugin added to Wordpress plugin architecture
add_action( 'template_redirect', 'bas_permalink_moved_permanently' );

?>