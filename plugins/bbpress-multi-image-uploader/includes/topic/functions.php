<?php

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Injects uploader markup and area before submit button wrapper in topic form.
 */
if( !function_exists('bbp_topic_uploader_area') ) :

	function bbp_topic_uploader_area() {

		ob_start(); ?>

		<div class="bbp-uploader-msg">
			<?php echo apply_filters( 'bbp_topic_uploader_area_text',  __( 'To better explain your question or answer, you can upload some screenshots.', 'bbpress-multi-image-uploader' )); ?>
		</div>
		<div id="plupload-upload-ui" class="hide-if-no-js">

			<div id="bbp-uploader-img-container">
				<div class="bbp-files-queue"></div>
				<?php do_action('bbp_uploader_topic_img_container'); ?>
			</div>

			<div id="drag-drop-area">
			   <div class="drag-drop-inside">
				<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Upload Images', 'bbpress-multi-image-uploader'); ?>" class="button" /></p>
			  </div>
			</div>

		</div><?php

		$uploader_area = ob_get_contents();
		ob_end_clean();

		echo apply_filters( 'bbp_topic_uploader_area', $uploader_area );

	}

endif;

/**
 * Adds attachment to the topic once it is created.
 */
if( !function_exists('bbp_uploader_topic_created') ) {

	function bbp_uploader_topic_created( $topic_id ) {

		/**
		 * Ensure that $bbp_uploader_attach is always of type array. ;)
		 */
		$bbp_uploader_attach = empty($_POST['bbp_uploader_attach']) ? array() : $_POST['bbp_uploader_attach'];

		/**
		 * Get all images attached to topic.
		 * 
		 * We will delete any attachments that has been removed by user.
		 */
		$attachments = bbp_uploader_post_children( $topic_id );
		$attachments = array_map( function( $val ) { return $val->ID; }, $attachments );

		/**
		 * This will contain IDs of attachments which needs to be deleted.
		 */
		$diff_attachments = array_diff( $attachments, $bbp_uploader_attach );

		if( !empty( $diff_attachments ) ) // No image deleted
			bbp_uploader_delete_attachments( $diff_attachments );

		foreach( $bbp_uploader_attach as $k=>$v ) {

			/**
			 * If attachment is already attached to topic then skip it. :D
			 */
			if( in_array( $v, $attachments ) )
				continue;

			/**
			 * Assign attachment to the topic.
			 */
			if( is_numeric( $v ) && wp_attachment_is_image($v) ) {
				wp_update_post(array(
					'ID' => $v,
					'post_parent' => $topic_id
				));
			}
		}
	}

}


/**
 * Previews images added to replies.
 */
if( !function_exists('bbp_topic_img_container') ) {

	function bbp_topic_img_container() {

		if( bbp_allow_revisions() && bbp_is_topic_edit() ){

			 $topic_id = bbp_get_topic_id();
			 $attachments = bbp_uploader_post_children( $topic_id );

			 if( !empty($attachments) ) {

				 $markup = bbp_img_container_markup(); // Get markup which needs to be diaplyed.

				 foreach( $attachments as $attachment ) {

					$container_markup = $markup;

					$attach_thumb = bbp_uploader_image_src( $attachment->ID , 'thumbnail' );
					$attach_full  = bbp_uploader_image_src( $attachment->ID, 'full' );

					$container_markup = str_replace( '%attachment-full%', $attach_full[0], $container_markup);
					$container_markup = str_replace( '%attachment-thumb%', $attach_thumb[0], $container_markup);
					$container_markup = str_replace( '%attachment-alt%', $attachment->post_name, $container_markup);
					$container_markup = str_replace( '%attachment-id%', $attachment->ID, $container_markup);

					echo $container_markup;
				 }
			 }
		}
	}
}
