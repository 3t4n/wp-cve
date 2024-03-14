<?php
class CIR_Front{


	private static $options = array();

	private $limit_filesize;

	private $files_count_limit;

	private $needs_to_approve;


	public function __construct($options,$limit_filesize,$limit_files_count,$needs_to_approve) {
		self::$options = $options;
		$this->limit_filesize = $limit_filesize;
		$this->files_count_limit = $limit_files_count;
		$this->needs_to_approve = $needs_to_approve;
	}

	/**
	 * Adds the comment image upload form to the comment form.
	 *
	 * @param	$post_id	The ID of the post on which the comment is being added.
	 */
	function add_image_upload_form( $post_id ) {

		$current_post_state = get_post_meta( $post_id, 'comment_images_reloaded_toggle', true )
			? get_post_meta( $post_id, 'comment_images_reloaded_toggle', true )
			: 'enable';

		$option = get_option( 'CI_reloaded_settings' );
		// $option

		$all_posts_state = !empty($option['disable_comment_images'])
			? $option['disable_comment_images']
			: '';

		$logoimg = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAAAoCAYAAAB+Qu3IAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpGNzU2NkRBNDY2RDFFNTExQkUxNUNGNEE1REE1M0E4RiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo0RUMxMzg5OEQ0QjYxMUU1ODQwQTlDMDI4MjI5QTdFQyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0RUMxMzg5N0Q0QjYxMUU1ODQwQTlDMDI4MjI5QTdFQyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjgxMTA0OEQzQjVENEU1MTFCREQ1QTE1NDk0MjRBRjI1IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkY3NTY2REE0NjZEMUU1MTFCRTE1Q0Y0QTVEQTUzQThGIi8+IDwvcmRmOkRlc2NyaXB0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" alt="wp-puzzle.com logo">';

		// $brand_img = ( !empty($option['show_brand_img']) && 'enable'==$option['show_brand_img'] )
		$brand_img = ( !empty($option['show_brand_img']) && 'disable'==$option['show_brand_img'] )
			? ''
			: '<a href="http://wp-puzzle.com/" rel="external nofollow" target="_blank" class="cir-link">'. $logoimg .'</a>';


		$input_name = "comment_image_reloaded_".$post_id."[]";

		// Create the label and the input field for uploading an image
		if( 'disable' != $all_posts_state  && $current_post_state == 'enable' ){

			$before = ( isset($option['before_title']) )
				? $option['before_title']
				: __( 'Select an image for your comment (GIF, PNG, JPG, JPEG):', 'comment-images-reloaded' );

			$html = '<div id="comment-image-reloaded-wrapper">';
			$html .= '<p id="comment-image-reloaded-error"></p>';
			$html .= "<label for='comment_image_reloaded_$post_id'>". $before ."</label>";
			$html .= "<p class='comment-image-reloaded'><input type='file' name=$input_name id='comment_image_reloaded' multiple='multiple' /></p>";
			$html .= $brand_img;
			$html .= '</div><!-- #comment-image-wrapper -->';

			echo $html;
			// $fields['comment_notes_after'] = $html;

		} // end if

		// return $fields;

	} // end add_image_upload_form


	/**
	 * Adds the comment image upload form to the comment form.
	 *
	 * @param	$comment_id	The ID of the comment to which we're adding the image.
	 */
	function save_comment_image( $comment_id ) {

		// The ID of the post on which this comment is being made
		$post_id = $_POST['comment_post_ID'];

		$img_ids = array();

		// The key ID of the comment image
		$comment_image_id = "comment_image_reloaded_$post_id";
//		if($this->files_count_limit < count($_FILES[ $comment_image_id ]['name'])){
//			echo sprintf(__('Error! You\'re trying to upload to many files. Maximum number of files is %s','comment-images-reloaded'),$this->files_count_limit);
//			die();
//		}
		// If the nonce is valid and the user uploaded an image, let's upload it to the server
		// if( isset( $_FILES[ $comment_image_id ] ) && ! empty( $_FILES[ $comment_image_id ] ) ) {
		for($j=0; $j < count($_FILES[ $comment_image_id ]['name']); $j++) {
			if ( isset( $_FILES[ $comment_image_id ] ) && ! empty( $_FILES[ $comment_image_id ]['name'][$j] ) ) {

				//foreach($_FILES[$comment_image_id])
				// disable save files larger than $limit_filesize
				if ( $this->limit_filesize < $_FILES[ $comment_image_id ]['size'][$j] ) {

					echo __( 'Error: Uploaded file is too large. <br/> Go back to: ', 'comment-images-reloaded' );
					echo '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>';
					die;

				}

				// check errors
				if ( ! empty( $_FILES[ $comment_image_id ]['error'][$j] ) ) {

					echo __( 'Unknown error occurred while loading image.<br/> Go back to: ', 'comment-images-reloaded' );
					echo '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>';
					die;

				}

				if ( count($_FILES[ $comment_image_id ]['name']) > intval($this->files_count_limit)){
					echo sprintf(__('You\'re trying to upload to many images at a time. Maximum number of images to upload is %s ','comment-images-reloaded'),$this->files_count_limit);
					echo __( 'Go back to: ', 'comment-images-reloaded' );
					echo '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>';
					die;
				}

				// safe image name
				$safe_name = preg_replace( "/[^A-Za-z0-9_\-\.]/", '', $_FILES[ $comment_image_id ]['name'][$j] );

				// if is empty name - add same random digits
				$onlyname = substr( $safe_name, 0, - 4 );
				if ( empty( $onlyname ) ) {
					$safe_name = $comment_image_id . rand( 100, 900 ) . $safe_name;
				}

				// Store the parts of the file name into an array
				// $file_name_parts = explode( '.', $_FILES[ $comment_image_id ]['name'] );
				$file_name_parts = explode( '.', $_FILES[ $comment_image_id ]['name'][$j] );

				// Get file ext.
				$file_ext = $file_name_parts[ count( $file_name_parts ) - 1 ];

				// If the file is valid, upload the image, and store the path in the comment meta
				if ( $this->is_valid_file_type( $file_ext ) ) {
					// Upload the comment image to the uploads directory
					//---$comment_image_file = wp_upload_bits( $comment_id . '.' . $file_ext, null, file_get_contents( $_FILES[ $comment_image_id ]['tmp_name'] ) );

					$img  = array();
					$img['type'] = $_FILES[ $comment_image_id ]['type'][$j];
					$img['tmp_name'] = $_FILES[ $comment_image_id ]['tmp_name'][$j];
					$img['size'] = $_FILES[$comment_image_id]['size'][$j];
					$img['error'] = $_FILES[$comment_image_id]['error'][$j];
					$img['name'] = $safe_name;
					// $id = media_handle_sideload( $_FILES[ $comment_image_id ], $post_id);
					$id = media_handle_sideload( $img, $post_id );

					// Set post meta about this image. Need the comment ID and need the path.
					//---if( FALSE === $comment_image_file['error'] ) {
					if ( ! is_wp_error( $id ) ) {
						$img_ids[] = $id;
						// Since we've already added the key for this, we'll just update it with the file.


					} // end if/else

				} // end if

			} // end if



		}
		if(!empty($img_ids)){
			add_comment_meta( $comment_id, 'comment_image_reloaded', $img_ids );
		}

		if ( TRUE === $this->needs_to_approve ) {

			$commentarr = array();
			$commentarr['comment_ID'] = $comment_id;
			$commentarr['comment_approved'] = 1;

			wp_update_comment($commentarr);
		}

	} // end save_comment_image





	/**
	 * Appends the image below the content of the comment.
	 *
	 * @param	$comment	The content of the comment.
	 */
	function display_comment_image( $comments, $pid ) {

		if( count( $comments ) < 1 ){
			return $comments;
		}

		global $wpdb,$post;

		$comment_ids = '';
		$current_post_state = get_post_meta( $post->ID, 'comment_images_reloaded_toggle', true );
		$option = get_option( 'CI_reloaded_settings' );

		// get current file size or set default to 'large'
		$size = $option['image_size']
			? $option['image_size']
			: 'large';

		$all_posts_state = !empty($option['disable_comment_images'])
			? $option['disable_comment_images']
			: '';

		// get comments ID list
		foreach ($comments as $count => $comment) {
			$comment_ids .= $comment->comment_ID . ',';
		}
		$comment_ids =  rtrim ( $comment_ids, ',' );

		// get all meta fields for comments images
		$table = $wpdb->prefix . 'commentmeta';

		if( !empty($comment_ids) ){
			$fivesdrafts = $wpdb->get_results("SELECT comment_id, meta_value  FROM $table
												WHERE comment_id IN ($comment_ids) AND meta_key = 'comment_image_reloaded'
												ORDER BY meta_id ASC");

			$urls_from_db = $wpdb->get_results("SELECT comment_id, meta_value  FROM $table
												WHERE comment_id IN ($comment_ids) AND meta_key = 'comment_image_reloaded_url'
												ORDER BY meta_id ASC");
		}



		$metadata_ids = array();
		$metadata_url = array();



		foreach ($urls_from_db as $key => $value) {
			$metadata_url[$value->comment_id] = $value->meta_value;
		}

		foreach ($fivesdrafts as $key => $value) {
			$metadata_ids[$value->comment_id] = $value->meta_value;
		}

		//
		// Make sure that there are comments
		//
		if( count( $comments ) > 0 ) {

			// Loop through each comment...
			foreach( $comments as $comment ) {
				// ...and if the comment has a comment image...
				if( !empty($metadata_ids[$comment->comment_ID]) ) {

					// ...get the comment image meta
					//$comment_image = get_comment_meta( $comment->comment_ID, 'comment_image_reloaded', true );
					$img_url = array();
					$img_url_out = array();
					// Size of the image to show (thumbnail, large, full, medium)
					if ( array_key_exists($comment->comment_ID,$metadata_url) && !empty($metadata_url[$comment->comment_ID]) ){

						$img_url = unserialize($metadata_url[$comment->comment_ID]);
						$updated = false;
						foreach($img_url as $id => $img) {
							if(!is_numeric($id)){
								$old_meta = get_comment_meta($comment->comment_ID,'comment_image_reloaded',true);
								$old_meta = is_array($old_meta) ? $old_meta[0] : $old_meta;
								$buf = $img_url;
								$img_url = array();
								$img_url[$old_meta] = $buf;
								$updated = true;
								$img_url_out[$old_meta] = $buf[$size];
								if(empty($img_url_out[$old_meta])){
									$img_url_out[$old_meta] = $buf['full'];
								}
								break;
							}

							if (!empty($img[$size])) {
								$img_url_out[$id] = $img[$size];
							}
							else {
								$img_url[$id][$size] = wp_get_attachment_image( $id, $size );
								$updated = true;
								$img_url_out[$id] = $img_url[$id][$size];
							}

						}

						if($updated) {
							update_comment_meta($comment->comment_ID, 'comment_image_reloaded_url', $img_url);
						}

					} else {

						foreach( get_intermediate_image_sizes() as $_size ){

							$img_ids = unserialize($metadata_ids[ $comment->comment_ID ]);

							foreach($img_ids as $imgID){
								$img_url[$imgID][ $_size ] = wp_get_attachment_image( $imgID, $_size );
								$img_url[$imgID]['full'] = wp_get_attachment_image($imgID, 'full');
							}

						}
						update_comment_meta( $comment->comment_ID, 'comment_image_reloaded_url',$img_url);

						foreach($img_url as $id => $imgURL) {
							if (!empty($imgURL[$size]))
								$img_url_out[$id] = $imgURL[$size];
							else
								$img_url_out[$id] = $imgURL['full'];
						}



					}

					if(!empty($img_url_out)) {
						$comment->comment_content .= '<div class="comment-image-box">';
					}

					// ...and render it in a paragraph element appended to the comment
					if (isset(self::$options['image_zoom']) && 'enable' == self::$options['image_zoom']) {



						foreach($img_url_out as $id => $img_out) {
							// get full image URI
							preg_match('/src=[\'|\"]([^\'\"]*)/i', $img_url[$id]['full'], $matches);

							$comment->comment_content .= '<p class="comment-image-reloaded">';
							if ($matches) {
								$comment->comment_content .= '<a class="cir-image-link image-id-'.$id.'" href="' . $matches[1] . '">' . $img_out . '</a>';
							} else {
								$comment->comment_content .= $img_out;
							}
							$comment->comment_content .= '</p>';
						}

					} else {

						foreach($img_url_out as $id => $img_out) {
							$comment->comment_content .= '<p class="comment-image-reloaded">';
							$comment->comment_content .= $img_out;
							$comment->comment_content .= '</p>';
						}

					}

					if(!empty($img_url_out)) {
						$comment->comment_content .= '</div>';
					}


				} // end if

			} // end foreach

		} // end if

		return $comments;

	} // end display_comment_image


	private function is_empty_array($old){
		foreach($old as $o){
			if(!empty($o)){
				return false;
			}
		}
		return true;
	}

	/**
	 * Determines if the specified type if a valid file type to be uploaded.
	 *
	 * @param	$type	The file type attempting to be uploaded.
	 * @return			Whether or not the specified file type is able to be uploaded.
	 */
	private function is_valid_file_type( $type ) {

		$type = strtolower( trim ( $type ) );
		return 	$type == 'png' ||
		$type == 'gif' ||
		$type == 'jpg' ||
		$type == 'jpeg';

	} // end is_valid_file_type


	/**
	 *  add small css for author's link
	 */
	function add_authorslink_style() {

		if ( !isset(self::$options['show_brand_img']) || 'disable' != self::$options['show_brand_img'] ){
			// if ( isset(self::$options['show_brand_img']) || !empty(self::$options['show_brand_img']) ){
			echo "<style>.cir-link{height:20px;display:block;width:90px;overflow:hidden;}.cir-link,.cir-link img{padding:0;margin:0;border:0}.cir-link:hover img{position:relative;bottom:20px}</style>\n";
		}
	}
}