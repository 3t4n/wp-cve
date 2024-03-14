<?php


class CIR_Importer{

	/**
	 * Import images meta from Comment Attachment
	 *
	 */

	public function convert_CA_images(){
		$counter = 0;
		$comments = get_comments('meta_key=attachmentId');
		foreach($comments as $comment){
			$img_id = array();
			$img_id[0] =  get_comment_meta( $comment->comment_ID, 'attachmentId', true );
			$attachment = wp_get_attachment_image( $img_id[0], 'thumbnail', false);
			if($attachment){
				update_comment_meta($comment->comment_ID,'comment_image_reloaded',$img_id);
				$counter++;
			}
		}
		$response = __( 'Updated ', 'comment-images-reloaded' ) . $counter .' '. self::num_word($counter);
		echo $response;
		die();
	}

	/**
	 * Import images meta from Comment Image
	 *
	 */
	public function convert_CI_images() {
		$counter = 0;
		$attachments = array();

		$old_meta_key = 'comment_image';
		$new_meta_key = 'comment_image_reloaded';

		// get all comments with Comment Image meta key
		$comments = get_comments( 'meta_key=' . $old_meta_key );

		/**
		 *
		 * Iterate through each of the comments...
		 *
		 */
		foreach( $comments as $comment ) {

			// Get the associated comment image
			$comment_image = get_comment_meta( $comment->comment_ID, $old_meta_key, true );

			$image_path = $comment_image['file'];


			/**
			 * check absolute FILE path not exists
			 *
			 */
			if ( !file_exists( $image_path ) ) {

				$pos_path = strpos( $image_path, 'wp-content' );
				$fixed_image_path = ABSPATH . substr( $image_path, $pos );

				// try fix image path
				if ( file_exists($fixed_image_path) ) {
					$image_path = $fixed_image_path;
				}

				// check path by url
				else {

					$pos_url = strpos( $comment_image['url'], 'wp-content' );
					$fixed_url_path = ABSPATH . substr( $comment_image['url'], $pos_url );

					// try fix image path
					if ( file_exists( $fixed_url_path ) ) {
						$image_path = $fixed_url_path;
					} else {
						continue;
					}

				}

			} // end !file_exists


			// get post ID
			$post_id = $comment->comment_post_ID;

			// save attachments data if it not exists
			if ( !array_key_exists( $post_id, $attachments) ) {
				$new = array();
				if ( function_exists('get_attached_media') ) {
					$new = get_attached_media( 'image', $post_id );
				} else {
					$new = get_children( 'post_type=attachment&post_mime_type=image' );
				}

				$attachments[ $post_id ] = json_decode( json_encode($new), true ); // save WP_Post as associative array
			}

			// try get exists image
			$imageID_in_medialibrary = array();
			foreach ( $attachments[ $post_id ] as $att_id => $att ) {
				if ( $comment_image['url'] === $att['guid'] ) {
					$imageID_in_medialibrary[] = $att['ID'];
				}
			}

			// update meta key if attachment exist
			if ( $imageID_in_medialibrary != 0 ) {

				update_comment_meta( $comment->comment_ID, $new_meta_key, $imageID_in_medialibrary );
				$counter++;

			} else {

				// upload new image attachment
				// ??? (((
				$file = array(
					'name'     => basename( $image_path ),
					'type'     => $comment_image['type'],
					'size'     => filesize( $image_path ),
					'tmp_name' => $image_path,
					'error'    => $comment_image['error'],
				);

				$id[0] = media_handle_sideload( $file, $comment->comment_post_ID );

				if( !is_wp_error($id[0])  ){
					update_comment_meta( $comment->comment_ID, $new_meta_key, $id );
					$counter++;
				}

				//@unlink( $file['tmp_name'] );

			}

		} // end foreach

		$response = __( 'Updated ', 'comment-images-reloaded' ) . $counter .' '. self::num_word($counter) . $dump;
		echo $response;
		// wp_die();
		die;

	} // end update_old_comments


	public static function num_word($num){
		$words = array(__( 'image', 'comment-images-reloaded' ),__( 'images', 'comment-images-reloaded' ),__( 'images.', 'comment-images-reloaded' ));
		$num = $num % 100;
		if ($num > 19) {
			$num = $num % 10;
		}
		switch ($num) {
			case 1: {
				return($words[0]);
			}
			case 2:
			case 3:
			case 4: {
				return($words[1]);
			}
			default: {
				return($words[2]);
			}
		}
	}
}


