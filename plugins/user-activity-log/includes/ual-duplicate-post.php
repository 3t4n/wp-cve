<?php
/**
 * Duplicate Post Support.
 *
 * @package User Activity Log
 */

/*
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Logger for very popular plugin Yoast Duplicate Post
 * https://wordpress.org/plugins/duplicate-post/
 */
add_action( 'dp_duplicate_post', 'ual_duplicate_post', 100, 3 );
add_action( 'dp_duplicate_page', 'ual_duplicate_post', 100, 3 );

if ( ! function_exists( 'ual_duplicate_post' ) ) {
	/**
	 * Duplicate Post.
	 *
	 * @param int    $new_post_id Post ID.
	 * @param object $post Post.
	 * @param string $status Status.
	 */
	function ual_duplicate_post( $new_post_id, $post, $status ) {
		$new_post                = get_post( $new_post_id );
		$duplicated_post_id      = $post->ID;
		$post_duplicated_details = get_post( $duplicated_post_id );
		$post_type               = isset( $post_duplicated_details->post_type ) ? $post_duplicated_details->post_type : '';
		$post_type_object        = get_post_type_object( $post_type );

		if ( ! is_null( $post_type_object ) ) {
			if ( ! empty( $post_type_object->labels->singular_name ) ) {
				$duplicated_post_post_type_singular_name = strtolower( $post_type_object->labels->singular_name );
			}
		}

		$duplicated_post_edit_link = get_edit_post_link( $post->ID );
		$new_post_edit_link        = get_edit_post_link( $new_post->ID );
		$post_title                = "Cloned $duplicated_post_post_type_singular_name <a href=" . $duplicated_post_edit_link . '>' . $post->post_title . '</a> to <a href=' . $new_post_edit_link . ">a new $duplicated_post_post_type_singular_name</a>";
		$action                    = 'Clone ' . $duplicated_post_post_type_singular_name;
		$obj_type                  = 'Duplicate Post';
		$post_id                   = '';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
