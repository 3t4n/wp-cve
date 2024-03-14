<?php
/*
Plugin Name: Show Private
Plugin URI: http://amib.ir/weblog/?p=153
Description: Provides access to Private Pages and Attachments for everyone using its direct link and removes "Private" prefix from title of this pages.
Author: Amir Masoud Irani( AMIB )
Version: 0.2.1
Author URI: http://amib.ir
*/

/*  Copyright 2011  AMIB  (email : amib@amib.ir)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// ------------------------------------------------------
// Remove "Private" prefix from page titles( Multilingual )
// ------------------------------------------------------
function sp_remove_private_prefix( $title ) {
	$title = str_replace( sprintf( __('Private: %s'), '' ), '', $title );
	return $title;
}

add_filter( 'the_title', 'sp_remove_private_prefix' );

// ------------------------------------------------------
// Provide access to private posts
// ------------------------------------------------------
function sp_posts_fields_request( $fields ) {
    global $wpdb;
    if( is_singular() ) {
        $fields = str_replace(
            "$wpdb->posts.*",
            "$wpdb->posts.ID, $wpdb->posts.post_author, $wpdb->posts.post_date," .
                " $wpdb->posts.post_date_gmt, $wpdb->posts.post_content," .
                " $wpdb->posts.post_title, $wpdb->posts.post_excerpt," .
                " REPLACE( $wpdb->posts.post_status, 'private', 'publish' ) AS `post_status`," .
                " $wpdb->posts.comment_status, $wpdb->posts.ping_status, $wpdb->posts.post_password," .
                " $wpdb->posts.post_name, $wpdb->posts.to_ping, $wpdb->posts.pinged," .
                " $wpdb->posts.post_modified, $wpdb->posts.post_modified_gmt," .
                " $wpdb->posts.post_content_filtered, $wpdb->posts.post_parent," .
                " $wpdb->posts.guid, $wpdb->posts.menu_order, $wpdb->posts.post_type," .
                " $wpdb->posts.post_mime_type, $wpdb->posts.comment_count",
            $fields
        );
       
    }
   
    return $fields;
}

add_filter( 'posts_fields_request' , 'sp_posts_fields_request' );

// ------------------------------------------------------
// Provide access to private attachments
// STEP 1
// ------------------------------------------------------
function sp_posts_results( $posts ) {

	if ( count( $posts ) == 1 && isset( $posts[ 0 ] ) && 'attachment' == $posts[ 0 ]->post_type && 'private' == get_post_status( $posts[ 0 ]->post_parent ) ) {
		$posts[ 0 ]->post_type = 'show_private_attachment';
		$posts[ 0 ]->post_status = 'publish';
	}
	
	return $posts;
}

add_filter( 'posts_results', 'sp_posts_results' );
// ------------------------------------------------------
// Provide access to private attachments
// STEP 2
// ------------------------------------------------------

function sp_the_posts( $posts ) {
	if ( isset( $posts[ 0 ] ) && 'show_private_attachment' == $posts[ 0 ]->post_type ) {
		$posts[ 0 ]->post_type = 'attachment';
		$posts[ 0 ]->post_status = 'inherit';
		wp_cache_delete( $posts[ 0 ]->ID, 'posts' );
	}
	return $posts;

}

add_filter( 'the_posts', 'sp_the_posts', 0 );
?>
