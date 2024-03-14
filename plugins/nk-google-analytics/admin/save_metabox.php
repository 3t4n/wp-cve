<?php

defined('ABSPATH') or die("No script kiddies please!");

	if ( ! isset( $_POST['NKgoogleanalytics_meta_box_nonce'] ) ) {
		return;
	}

/**
 * Check
 */

	if ( ! wp_verify_nonce( $_POST['NKgoogleanalytics_meta_box_nonce'], 'NKgoogleanalytics_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {
		
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

/**
 * Save
 */

	if ( ! empty( $_POST['nkweb_code_in_head'] ) ) {
		update_post_meta( $post_id, 'nkweb_code_in_head', "${_POST['nkweb_code_in_head']}" );
	}

	if ( ! empty( $_POST['nkweb_Use_Custom_js'] ) ) {
		update_post_meta( $post_id, 'nkweb_Use_Custom_js', "${_POST['nkweb_Use_Custom_js']}" );
	}

	if ( isset( $_POST['nkweb_Custom_js'] ) ) {
		update_post_meta( $post_id, 'nkweb_Custom_js', "${_POST['nkweb_Custom_js']}" );
	}

	if ( ! empty( $_POST['nkweb_Use_Custom_Values'] ) ) {
		update_post_meta( $post_id, 'nkweb_Use_Custom_Values', "${_POST['nkweb_Use_Custom_Values']}" );
	}

	if ( isset( $_POST['nkweb_Custom_Values'] ) ) {
		update_post_meta( $post_id, 'nkweb_Custom_Values', "${_POST['nkweb_Custom_Values']}" );
	}
		
	if ( ! empty( $_POST['nkweb_Use_Custom'] ) ) {
		update_post_meta( $post_id, 'nkweb_Use_Custom', "${_POST['nkweb_Use_Custom']}" );
	}

	if ( isset( $_POST['nkweb_Custom_Code'] ) ) {
		update_post_meta( $post_id, 'nkweb_Custom_Code', "${_POST['nkweb_Custom_Code']}" );
	}
?>
