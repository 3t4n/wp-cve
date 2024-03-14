<?php

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Call function at init
 */
if( !function_exists('bbp_uploader_init') ) {

	function bbp_uploader_init() {

		/**
		 * Plupload config.
		 */
		bbp_uploader()->plupload_args =  array(

					'runtimes'            => 'html5,silverlight,flash,html4',
					'browse_button'       => 'plupload-browse-button',
					'container'           => 'plupload-upload-ui',
					'drop_element'        => 'drag-drop-area',
					'file_data_name'      => 'async-upload',            
					'multiple_queues'     => true,
					'max_file_size'       => wp_max_upload_size().'b',
					'url'                 => admin_url('admin-ajax.php'),
					'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
					'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
					'filters'             => array( array('title' => __('Image Files', 'bbpress-multi-image-uploader'), 'extensions' => 'jpg,gif,png') ),
					'multipart'           => true,
					'urlstream_upload'    => true,
					// additional post data to send to our ajax hook
					'multipart_params'    => array(
					  '_ajax_nonce' => wp_create_nonce('photo-upload'),
					  'action'      => 'photo_gallery_upload',            // the ajax action name
					),
				);

		/**
		 * Configuration params.
		 * 
		 * This can be primarily used in order to customize this plugin.
		 */
		bbp_uploader()->config = array(
			'img_container_markup' => bbp_img_container_markup(),
			'bbp_uploader_img_container'=>'#bbp-uploader-img-container', //Hold images just uploaded. (Preview images)
			'drag_drop_area'=>'#drag-drop-area',
			'bbp_files_queue'=>'.bbp-files-queue',
			'bbp_uploader_close'=>'.bbp-uploader-close', // Remove uploaded image (Remove button X),
			'bbp_img_wrap'=>'.bbp-img-wrap',
			'bbp_submit_btn'=>'#bbp_reply_submit'
		);

		/**
		 * Filter plupload config for third party plugins and themes as support. :)
		 */
		bbp_uploader()->plupload_args = apply_filters( 'bbp_uploader_plupload_args', bbp_uploader()->plupload_args );

		/**
		 * Filter the config parameters
		 */
		bbp_uploader()->config = apply_filters( 'bbp_uploader_config', bbp_uploader()->config );
	}
}

/**
 * Enqueue scripts and styles
 */
if( !function_exists('bbp_uploader_wp_enqueue_scripts') ) :

	function bbp_uploader_wp_enqueue_scripts() {

		wp_enqueue_script('plupload'); // plupload library
		wp_enqueue_script('thickbox'); // thickbox
		wp_enqueue_style('thickbox'); // thickbox style

		wp_register_script( 'bbp_uploader_js', bbp_uploader()->assets_url.'js/script.js', array('jquery'), '1.1.1', true );
		wp_localize_script( 'bbp_uploader_js', 'bbp_plupload_obj', bbp_uploader()->plupload_args ); // plupload config variable
		wp_localize_script( 'bbp_uploader_js', 'bbp_uploader_config', bbp_uploader()->config ); // plupload config variable
		wp_enqueue_script( 'bbp_uploader_js' );
		wp_enqueue_style( 'bbp-uploader-css', bbp_uploader()->assets_url.'css/style.css' );
	}

endif;

if( !function_exists('bbp_photo_gallery_upload') ){

	function bbp_photo_gallery_upload() {

		check_ajax_referer('photo-upload');

		$response = array('error'=>true);
		// you can use WP's wp_handle_upload() function:
		$file = $_FILES['async-upload'];
		$status = wp_handle_upload($_FILES['async-upload'], array('test_form'=>true, 'action' => 'photo_gallery_upload'));

		/**
		 * Ensure that file has been uploaded successfully.
		 */
		if( $status && !isset($status['error']) ) {

			/**
			 * Create attachment.
			 */
			$attachment_id = wp_insert_attachment(
					array(
						'post_mime_type' => $status['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($file['name'])),
						'post_content' => '',
						'post_status' => 'inherit'
					  ),
					$status['file']
			);


			  /**
			   * Ensure that attachment has been created succesfully.
			   */
			  if( $attachment_id ) {

				 /**
				  * Generate attachment metadata for newly created attachment post.
				  */
				  $attach_data = wp_generate_attachment_metadata( $attachment_id, $status['file']);
				  wp_update_attachment_metadata( $attachment_id,  $attach_data );

				  $image_thumb = bbp_uploader_image_src( $attachment_id, apply_filters('bbp_uploader_image_size_thumb', 'thumbnail') );
				  $image_full  = bbp_uploader_image_src( $attachment_id, apply_filters('bbp_uploader_image_size_full', 'full') );
				  $response['url_thumb'] = $image_thumb[0];
				  $response['url_full']  = $image_full[0];
				  $response['attid'] = $attachment_id;
				  $response['error'] = false;
				  $response['filename'] = preg_replace('/\.[^.]+$/', '', basename($file['name']));
			  }

		}

		echo wp_json_encode($response);
		exit;

	}
}

/**
 * Returns URL of image attachment. This function is wrapper for wp_get_attachment_image_src() function.
 */
if( !function_exists('bbp_uploader_image_src') ) {
	
	function bbp_uploader_image_src( $attachment_id, $size = 'thumbnail' ) {

		if( is_numeric( $attachment_id ) && !empty( $size ) ) {
			
			return wp_get_attachment_image_src( $attachment_id, $size );
		}
	}
}

/**
 * Returns the children (attachments) of post. This is wrapper of get_children() function.
 */
if( !function_exists('bbp_uploader_post_children') ) {

	function bbp_uploader_post_children( $parent_post ) {

		if( is_numeric($parent_post) )
			return get_children( array('post_parent'=>$parent_post, 'post_type'=>'attachment', 'post_mime_type'=>'image', 'numberposts' => -1 ) );
	}
	
}

/**
 * Delete attachments associated with topics and replies.
 * 
 * @param array $attachments array of IDs of attachments that needs to be deleted.
 * @return void
 */
if( !function_exists('bbp_uploader_delete_attachments') ) {

	function bbp_uploader_delete_attachments( $attachments ) {
		
		if( !empty( $attachments ) && is_array( $attachments ) ) {
			
			foreach( $attachments as $attachment ) {
				
				if( is_numeric( $attachment ) )
					wp_delete_attachment ( $attachment );
			}
		}
	}
}

/**
 * 
 */
if( !function_exists('bbp_uploader_attachments') ) :

	function bbp_uploader_attachments() {

		if( get_post_type() == 'reply' )
			$post_id = bbp_get_reply_id();
		else
			$post_id = bbp_get_topic_id();

		if( (get_post_type() == 'reply') && bbp_private_reply_installed() && !bbp_uploader_can_view_reply( $post_id ) ) {
			return;
		}

		$attachments = bbp_uploader_post_children( $post_id );

		if( has_action( 'bbp_uploader_attachments_markup' ) ) {

			do_action( 'bbp_uploader_attachments', $attachments );

		} else {

			if( !empty( $attachments ) ) { ?>

				<div class="shr-reply-attach"><?php

					foreach( $attachments as $attachment ) {
						$attach = wp_get_attachment_image_src( $attachment->ID , 'thumbnail' );
						$attach_full  = wp_get_attachment_image_src( $attachment->ID, 'full' ); ?>
						<a href="<?php echo $attach_full[0]; ?>" class="thickbox bbp-uploader-thumb">
							<img src="<?php echo $attach[0] ?>" alt="<?php echo $attachment->post_name; ?>" />
							<span><?php echo basename( pathinfo( $attach_full[0], PATHINFO_FILENAME ) ) ?></span>
						</a><?php
					} ?>

				</div><?php

			}		
		}
	}
	
endif;

/**
 * Returns the markup structure for img container.
 * 
 * See function bbp_topic_uploader_area(), this markup will be injected in
 * do_action('bbp_uploader_topic_img_container')
 */
if( !function_exists('bbp_img_container_markup') ) :

	function bbp_img_container_markup() {

		ob_start(); ?>

		<div class="bbp-img-wrap">
			<p class="bbp-uploader-close"></p>
			<a href="%attachment-full%" class="thickbox">
				<img src="%attachment-thumb%" alt="%attachment-alt%" />
			</a>
			<input type="hidden" name="bbp_uploader_attach[]" value="%attachment-id%" />
		</div><?php

		$markup = apply_filters( 'bbp_reply_img_container_markup', ob_get_contents() );
		ob_end_clean();

		return $markup;
	}
endif;
