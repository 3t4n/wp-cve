<?php
/*--------------------------------------------*
	 * Meta Box Functions
	 *---------------------------------------------*/

/**
 * Registers the meta box for displaying the 'Comment Images' options in the post editor.
 *
 * @version	1.0
 * @since 		1.8
 */

class CIR_MetaBox {

	public function add_comment_image_meta_box() {

		add_meta_box(
			'disable_comment_images_reloaded',
			__( 'Comment Images', 'comment-images-reloaded' ),
			array( $this, 'comment_images_display' ),
			'post',
			'side',
			'low'
		);

		add_meta_box(
			'disable_comment_images_reloaded',
			__( 'Comment Images', 'comment-images-reloaded' ),
			array( $this, 'comment_images_display' ),
			'page',
			'side',
			'low'
		);

	} // end add_project_completion_meta_box


	/**
	 * Displays the option for disabling the Comment Images upload field.
	 *
	 * @version    1.0
	 * @since        1.8
	 */
	public function comment_images_display( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'comment_images_reloaded_display_nonce' );

		$html = '<p class="comment-image-info" style="text-align:center;color: #3a87ad;margin: 10px 0 10px 0;padding:10px;background-color: #d9edf7;border-left: 5px solid #3a87ad;">' . __( 'Doing this will only update <strong>this</strong> post.', 'comment-images-reloaded' ) . '</p>';
		$html .= '<select name="comment_images_reloaded_toggle" id="comment_images_reloaded_toggle" class="comment_images_reloaded_toggle_select" style="width:100%;">';
		$html .= '<option value="enable" ' . selected( 'enable', get_post_meta( $post->ID, 'comment_images_reloaded_toggle', true ), false ) . '>' . __( 'Enable comment images for this post.', 'comment-images-reloaded' ) . '</option>';
		$html .= '<option value="disable" ' . selected( 'disable', get_post_meta( $post->ID, 'comment_images_reloaded_toggle', true ), false ) . '>' . __( 'Disable comment images for this post.', 'comment-images-reloaded' ) . '</option>';
		$html .= '</select>';

		$html .= '<hr />';

		echo $html;

	} // end comment_images_display


	/**
	 * Saves the meta data for displaying the 'Comment Images' options in the post editor.
	 *
	 * @version    1.0
	 * @since        1.8
	 */
	public function save_comment_image_display( $post_id ) {

		// If the user has permission to save the meta data...
		if ( $this->user_can_save( $post_id, 'comment_images_reloaded_display_nonce' ) ) {

			// Only do this if the source of the request is from the button
			if ( isset( $_POST['comment_image_reloaded_source'] ) && 'button' == $_POST['comment_image_reloaded_source'] ) {

				if ( '' == get_option( 'comment_image_reloaded_toggle_state' ) || 'enabled' == get_option( 'comment_image_reloaded_toggle_state' ) ) {


					update_option( 'comment_image_reloaded_toggle_state', 'disabled' );

				} elseif ( 'disabled' == get_option( 'comment_image_reloaded_toggle_state' ) ) {


					update_option( 'comment_image_reloaded_toggle_state', 'enabled' );

				} // end if

				// Otherwise, we're doing this for the post-by-post basis with the select box
			} else {

				// Delete any existing meta data for the owner
				if ( get_post_meta( $post_id, 'comment_images_reloaded_toggle' ) ) {
					delete_post_meta( $post_id, 'comment_images_reloaded_toggle' );
				} // end if
				update_post_meta( $post_id, 'comment_images_reloaded_toggle', $_POST['comment_images_reloaded_toggle'] );

			} // end if/else

		} // end if

	} // end save_comment_image_display


	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param		int		$post_id	The ID of the post being save
	 * @param		bool				Whether or not the user has the ability to save this post.
	 * @version	1.0
	 * @since		1.8
	 */
	private function user_can_save( $post_id, $nonce ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], plugin_basename( __FILE__ ) ) ) ? true : false;

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision) && $is_valid_nonce;

	} // end user_can_save


	/**
	 * Determines whether or not the current comment has comment images. If so, adds a new link
	 * to the 'Recent Comments' dashboard.
	 *
	 * @param	array	$options	The array of options for each recent comment
	 * @param	object	$comment	The current recent comment
	 * @return	array	$options	The updated list of options
	 * @since	1.8
	 */
	public function recent_comment_has_image( $options, $comment ) {

		$comment_image = get_comment_meta( $comment->comment_ID, 'comment_image_reloaded', true );

		if ( !is_wp_error($comment_image) && !empty($comment_image) ) {

			$html = '<a href="edit-comments.php?p=' . $comment->comment_post_ID . '">';
			$html .= __( 'Comment Images', 'comment-images-reloaded' );
			$html .= '</a>';

			$options['comment-images'] = $html;

		} // end if

		return $options;

	} // end recent_comment_has_image

	/**
	 * Adds a column to the 'Comments' page indicating whether or not there are
	 * Comment Images available.
	 *
	 * @param	array	$columns	The columns displayed on the page.
	 * @param	array	$columns	The updated array of columns.
	 */
	public function comment_has_image( $columns ) {

		$columns['comment-image-reloaded'] = __( 'Comment Image', 'comment-images-reloaded' );

		return $columns;

	} // end comment_has_image

	/**
	 * Renders the actual image for the comment.
	 *
	 * @param	string	The name of the column being rendered.
	 * @param	int		The ID of the comment being rendered.
	 * @since	1.8
	 */
	public function comment_image( $column_name, $comment_id ) {

		if( 'comment-image-reloaded' == strtolower( $column_name ) ) {

			$comment_image_data = get_comment_meta( $comment_id, 'comment_image_reloaded', true );
			if(!is_array($comment_image_data) && !empty($comment_image_data)){
				$buf = $comment_image_data;
				$comment_image_data = array();
				$comment_image_data[0] = $buf;
				update_comment_meta($comment_id,'comment_image_reloaded',$comment_image_data);
			}

			$html = '';

			if ( is_wp_error($comment_image_data) ) {

				$html = '<p class="error">Error: ' . $comment_image_data->get_error_message(). '</p>';

			} elseif ( !empty($comment_image_data) ) {
				$html = '';
				foreach($comment_image_data as $data) {
					if(is_numeric($data) && !empty($data)) {
						$image_attributes = wp_get_attachment_image_src($data);
						$image_url = $image_attributes[0];
						$html .= '<div class="ci-wrapper"><img src="' . $image_url . '" width="150" style="max-width:100%"/>';
						$html .= '<div class="row-actions">';
						$html .= '<button class="button delete-cid" data-cid="' . $comment_id . '" data-aid="' . $data . '">';
						$html .= __('Delete image', 'comment-images-reloaded');
						$html .= '</button>';
						$html .= '</div></div>';
					}
				}

			} // end if

			echo $html;

		} // end if/else

	} // end comment_image
}