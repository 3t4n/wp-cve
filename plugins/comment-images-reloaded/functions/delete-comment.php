<?php
class CIR_Delete_Comment{
	/**
	 * delete images
	 *
	 */
	public static function cir_delete_image() {

		if ( !isset($_POST['cid']) || !isset($_POST['aid']) ) {
			echo 'false';
			die;
		}

		$aid = $_POST['aid'];
		$cid = $_POST['cid'];


		$deleted = wp_delete_attachment( intval($aid) );
		if ( $deleted ) {

			self::delete_or_update_cmeta($cid,$aid);
			echo 'true';
		}

		die;

	}

	//
	// clear commentmeta on delete COMMENT
	//
	function clear_commentmeta_ondelete_comment( $comment_id ) {

		$attachment_id = get_comment_meta( $comment_id, 'comment_image_reloaded', true );

		wp_delete_attachment( intval($attachment_id) );

		delete_comment_meta( $comment_id, 'comment_image_reloaded' );
		delete_comment_meta( $comment_id, 'comment_image_reloaded_url' );

	}

	//
	// clear commentmeta on delete ATTACHMENT
	//
	function clear_commentmeta_ondelete_attachment( $id ) {

		global $wpdb;

		// $table = $wpdb->base_prefix . 'commentmeta';
		// $postids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_id FROM $table WHERE meta_value = $id" ) );
		$postids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_id FROM wp_commentmeta WHERE meta_key = 'comment_image_reloaded' and meta_value LIKE '%%%s%%'", $id ) );
		// $postids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_id FROM $wpdb->commentmeta WHERE meta_value = $id" ) );
		foreach ( $postids as $cid ) {
			self::delete_or_update_cmeta($cid,$id);
		}

	}


	private static function delete_or_update_cmeta($cid,$aid){
		$cmeta = get_comment_meta( $cid, 'comment_image_reloaded',true);
		$cmeta_url = get_comment_meta($cid,'comment_image_reloaded_url',true);
		if(!key_exists($aid,$cmeta_url)){
			return;
		}

		unset( $cmeta_url[$aid] );

		if(!empty($cmeta_url)){
			update_comment_meta($cid,'comment_image_reloaded_url',$cmeta_url);
		} else {
			delete_comment_meta( $cid, 'comment_image_reloaded_url' );
		}

		if(($key = array_search($aid, $cmeta)) !== false) {
			unset($cmeta[$key]);
		}


		if(!empty($cmeta)) {
			update_comment_meta($cid, 'comment_image_reloaded', $cmeta);
		} else {
			delete_comment_meta( $cid, 'comment_image_reloaded' );
		}

	}

}