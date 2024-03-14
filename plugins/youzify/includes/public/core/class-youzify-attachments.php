<?php

/**
 * Activity Attachments.
 */
class Youzify_Attachments {

	function __construct() {

		// Ajax - Upload Attachments
		add_action( 'wp_ajax_youzify_upload_wall_attachments', array( $this, 'upload_attachments' ) );

		// Save Attachments.
		add_action( 'youzify_after_adding_wall_post', array( $this, 'save_activity_attachments' ), 10 );
		add_action( 'youzify_after_adding_wall_post', array( $this, 'save_embeds_videos' ), 10 );
		add_action( 'bp_activity_comment_posted', array( $this, 'save_comments_attachments' ), 10 );

		// Delete Hashtags On Post Delete.
		add_action( 'bp_activity_after_delete', array( $this, 'delete_attachments' ) );

		// Copy Uploaded Avatar & Cover to The Youzify Upload Directory.
		add_action( 'bp_activity_after_save', array( $this, 'set_new_avatar_activity' ) );
		add_action( 'members_cover_image_uploaded', array( $this, 'set_new_cover_activity' ), 10, 3 );

		// Upload Activity Meta Attachments
		add_action( 'youzify_upload_activity_meta_attachments', array( $this, 'upload_activity_meta_attachments' ), 10, 3 );

		// Save Messages Attachments.
		add_action( 'messages_message_sent', array( $this, 'save_messages_attachments' ) );

		// Upload Profile Files.
		add_action( 'wp_ajax_upload_files', array( $this, 'upload_profile_files' ) );

		// Delete Activity Attachments.
		add_action( 'wp_ajax_youzify_delete_wall_attachment', array( $this, 'delete_temporary_attachment' ) );

		// Delete Account Attachments.
		add_action( 'wp_ajax_youzify_delete_attachment', array( $this, 'delete_attachment' ) );

		// Delete Attachment.
		add_action( 'deleted_post', array( $this, 'delete_attachments_media' ), 10, 2 );

		// Fix Image Rotation
		add_filter( 'wp_handle_upload', array( $this, 'fix_image_orientation' ), 10 );

	}

	/**
	 * Save Activity Comment Attachments
	 */
	function save_comments_attachments( $comment_id ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Sanitize Attachments.
			$attachment_files = $this->sanitize_attachments( $_POST['attachments_files'] );

			// Save Attachments.
			$attachments = $this->save_attachments( $comment_id, array( $attachment_files ), 'comment' );

			// Save Comment Attachments.
			if ( ! empty( $attachments ) ) {
				bp_activity_add_meta( $comment_id, 'youzify_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Message Attachment.
	 */
	function save_messages_attachments( $message ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Sanitize Attachments.
			$attachment_files = $this->sanitize_attachments( $_POST['attachments_files'] );

			// Handle Compose Multiple Messages.
			if ( is_array( $attachment_files ) && isset( $attachment_files[0] ) ) {
				$attachment_files = $attachment_files[0];
			}

			// Save Attachments.
			$attachments = $this->save_attachments( $message->id, array( $attachment_files ), 'message' );

			// Save Message Attachments.
			if ( ! empty( $attachments ) ) {
				bp_messages_add_meta( $message->id, 'youzify_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Activity mETA Attachments
	 */
	function upload_activity_meta_attachments( $attachments, $item_id, $component ) {

		if ( empty( $attachments ) ) {
			return;
		}

		// Sanitize Attachments.
		$attachment_files = $this->sanitize_attachments( $attachments );

		// Return Attachments.
		return $this->save_attachments( $item_id, array( $attachment_files ), $component );

	}

	/**
	 * Save Activity Attachments
	 */
	function save_activity_attachments( $activity_id ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Get Activity.
			$activity = new BP_Activity_Activity( $activity_id );

			// Sanitize Attachments.
			$attachment_files = $this->sanitize_attachments( $_POST['attachments_files'] );

			// Save Attachments.
			$attachments = $this->save_attachments( $activity_id, $attachment_files, $activity->component );

			// Save Post Attachments.
			if ( ! empty( $attachments ) ) {
				bp_activity_add_meta( $activity_id, 'youzify_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Attachments.
	 */
	function save_attachments( $item_id, $attachments, $component ) {

		// Get Attachment.
		$attachments = $this->move_attachments( $attachments, $component );

		return $this->save_media_attachments( $item_id, $attachments, $component );

	}

	/**
	 * Save Posts Embeds Videos.
	 **/
	function save_embeds_videos( $activity_id ) {

		if ( $_POST['post_type'] != 'activity_status' || empty( $_POST['content'] ) ) {
			return;
		}

		$embed_exists = false;

		// Init Array.
		$atts = array();

		$supported_videos = youzify_attachments_embeds_videos();

		// Get Post Urls.
		if ( preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', sanitize_textarea_field( $_POST['content'] ), $match ) ) {

			foreach ( array_unique( $match[0] ) as $link ) {

				foreach ( $supported_videos as $provider => $domain ) {

					$video_id = youzify_get_embed_video_id( $provider, $link );

					if ( ! empty( $video_id ) ) {

						$embed_exists = true;

						$video_data = array();

						$embed_data = youzify_get_embed_video_thumbnails( $provider, $video_id );

						if ( ! empty( $embed_data ) ) {
							$video_data['id'] = 0;
							$video_data['type'] = 'video';
							$video_data['data'] = $embed_data;
							$video_data['provider'] = $provider;
							$video_data['source'] = 'activity_video';
							$video_data['user_id'] = bp_loggedin_user_id();
						}

						$atts[] = $video_data;

					}

				}

			}

		}

		// Get Activity
		$activity = new BP_Activity_Activity( $activity_id );

		// Change Activity Type from status to video.
		if ( $embed_exists ) {
			$activity->type = 'activity_video';
			$activity->save();
		}


		// Save Attachment.
		$medias = $this->save_media_attachments( $activity_id, $atts, $activity->component );

		if ( ! empty( $medias ) ) {

			// Add Meta
			bp_activity_add_meta( $activity_id, 'youzify_attachments', $medias );

		}
	}

	/**
	 * Get Privacy
	 */
	function get_privacy( $activity_id ) {

		global $wpdb, $bp;

		$privacy = $wpdb->get_var( "SELECT privacy from {$bp->activity->table_name} WHERE id = $activity_id" );

		return $privacy;
	}

	/**
	 * Save Attachments.
	 */
	function save_media_attachments( $item_id, $attachments, $component ) {

		// Serialize Attachments Data.
		$attachments = maybe_unserialize( $attachments );

		if ( empty( $attachments ) ) {
			return;
		}

		global $wpdb, $Youzify_media_table, $Youzify_upload_dir;

		// Init Vars
		$medias = array();

		// Get Current Time.
		$time = bp_core_current_time();

		switch ( $component ) {

			case 'activity':
			case 'groups':
				$privacy = $this->get_privacy( $item_id );
				break;

			case 'comment':
				global $bp;
				$comment_activity_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = $item_id" );
				$privacy = $this->get_privacy( $comment_activity_id );
				break;

			case 'message':
				$privacy = 'onlyme';
				break;

			default:
				$privacy = 'public';
				break;

		}

		foreach ( $attachments as $attachment ) {

			$data = 1;

			if ( $component == 'activity' || $component == 'groups' ) {

				switch ( $attachment['source'] ) {

					case 'activity_video':

						$data = $attachment['provider'] == 'local' ? array( 'thumbnail' => $attachment['thumbnail'], 'provider' => $attachment['provider'] ) : $attachment['data'];

						if ( ! empty( $attachment['id'] ) ) {
							$medias[ $attachment['id'] ] = $data;
						} else {
							$medias[] = $data;
						}

						break;

					case 'activity_audio':
						$medias = array( $attachment['id'] => 1 );
						break;

					case 'activity_file':
						$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
						$medias[ $attachment['id'] ] = $data;
						break;

					default:
						$medias[ $attachment['id'] ] = 1;
						break;
				}

			} elseif ( $component == 'comment' ) {

				switch ( $attachment['type'] ) {

					case 'video':
						$data = isset( $attachment['thumbnail'] ) && ! empty( $attachment['thumbnail'] ) ? array( 'thumbnail' => $attachment['thumbnail'], 'provider' => $attachment['provider'] ) : 1;
						$medias[ $attachment['id'] ] = $data;
						break;

					default:
						$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
						$medias[ $attachment['id'] ] = $data;
						break;
				}

			} elseif ( $component == 'message' ) {
				$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
				$medias[ $attachment['id'] ] = $data;

			}

			$args = array(
				'media_id' => $attachment['id'],
				'item_id' => $item_id,
				'component' => $component,
				'user_id' => $attachment['user_id'],
				'privacy' => $privacy,
				'type' => $attachment['type'],
				'time' => $time,
				'data' => $data != 1 ? serialize( $data ) : 0,
				'source' => $attachment['source']
			);

			// Insert Attachment.
			$result = $wpdb->insert( $Youzify_media_table, $args );

		}

		return $medias;
	}

	/**
	 * Move Temporary Files To The Main Attachments Directory.
	 */
    function move_attachments( $attachments, $component ) {

    	// Get Maximum Files Number.
	    $max_files = youzify_option( 'youzify_attachments_max_nbr', 200 );

		// Check attachments files number.
	    if ( count( $attachments ) > $max_files ) {
			wp_send_json_error( array( 'error' => $this->msg( 'max_files' ) ) );
	    }

    	global $Youzify_upload_dir, $youzify_upload_source, $youzify_upload_component;

    	if ( isset( $_POST['object'] ) && $_POST['object'] == 'groups' ) {
    		$youzify_upload_component = array( 'component' => 'groups', 'group_id' => absint( $_POST['item_id'] ) );
    	}

    	// Get Source.
    	$thread_id = isset( $_POST['thread_id'] ) ? absint( $_POST['thread_id'] ) : 'message';
    	$youzify_upload_source = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : $thread_id;

    	if ( $youzify_upload_source == 'activity_comment' ) {

			// Get Comment Parent.
			$parent = new BP_Activity_Activity( absint( $_POST['form_id'] ) );

			if ( $parent->component == 'groups' ) {
				$youzify_upload_component = array( 'component' => 'groups', 'group_id' => $parent->item_id );
			}

    	}

    	// New Attachments List.
    	$new_attachments = array();

		// Get File Path.
		$temp_path = $Youzify_upload_dir . 'temp/';

 		foreach ( $attachments as $attachment ) {

			// Get Attachment ID.
			$attachment_id = $this->wml_upload( $temp_path . $attachment['original'], $attachment['real_name'] );

	        if ( $attachment_id ) {

	        	// Get Attachment Data.
	        	$data = array( 'id' => $attachment_id, 'type' => youzify_get_file_type( $attachment['real_name'] ), 'user_id' => bp_loggedin_user_id(), 'source' => $youzify_upload_source );

	        	// Add Post Type.
	        	if ( $youzify_upload_source == 'activity_file' || ( $youzify_upload_source == 'activity_comment' && $data['type'] == 'file' ) || ( $component == 'message' && $data['type'] == 'file' ) ) {
	        		$data['data'] = $attachment;
	        	}

	        	// Get Post video Data
	        	if ( in_array( $youzify_upload_source, array( 'activity_video', 'activity_comment' ) ) && $data['type'] == 'video' ) {

					// Set Video As Uploaded Localy.
					$data['provider'] = 'local';

					if ( isset( $attachment['video_thumbnail'] ) ) {

						// Get Video Thumbnail.
						$video_thumbnail_id = $this->upload_video_thumbnail( $attachment['video_thumbnail'], $attachment['real_name'] );

 						if ( ! empty( $video_thumbnail_id ) ) {
 							$data['thumbnail'] = $video_thumbnail_id;
 						}

 					}

	        	}

	        	$new_attachments[] = $data;

	        }

 		}

 		return ! empty( $new_attachments ) ? serialize( $new_attachments ) : false;

    }

	/**
	 * Upload Video Thumbnail.
	 */
	function upload_video_thumbnail( $image = null, $video_name = null) {

		if ( empty( $image ) ) {
			return;
		}

		global $Youzify_upload_dir;

		// Decode Image.
		$decoded_image = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $image ) );

		// Get Unique File Name.
		$filename = uniqid( 'file_' ) . '.jpg';

		// Get File Link.
		$file_link = $Youzify_upload_dir . 'temp/' . $filename;

		// Get Unique File Name for the file.
        while ( file_exists( $file_link ) ) {
			$filename = uniqid( 'file_' ) . '.jpg';
		}

		// Upload Image.
		$image_upload = file_put_contents( $file_link, $decoded_image );

		if ( $image_upload ) {

			// Set Same Video Name
			$video_name = pathinfo( $video_name, PATHINFO_FILENAME ) . '.jpg';

			return $this->wml_upload( $file_link, $video_name );

		}

		return false;

	}

	/**
	 * Upload Attachment.
	 */
    function upload_attachments( $manual_files = null ) {

    	global $Youzify_upload_dir, $Youzify_upload_url;

		// Before Upload User Files Action.
		do_action( 'youzify_before_upload_wall_files' );

		// Check Nonce Security
		check_ajax_referer( 'youzify-nonce', 'security' );

		// Get Files.
		$files = ! empty( $manual_files ) ? $manual_files : $_FILES;

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

		$file = $files['file'];

		// Get Uploaded File extension
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

	    // Get Max File Size in Mega.
	    switch ( sanitize_key( $_POST['target'] ) ) {

	    	case 'activity':

	    		$multiple_files_post_types = apply_filters( 'youzify_attachments_multiple_files_post_types', array( 'activity_photo', 'activity_slideshow', 'activity_poll' ) );
	    		
	    		if ( ! in_array( $_POST['post_type'], $multiple_files_post_types ) ) {
		    		if ( $_POST['attachments_number'] > 1 ) {
						wp_send_json_error( array( 'error' => __( "You can't upload more than one file.", 'youzify' ) ) );
		    		}
	    		}

	    		switch ( sanitize_key( $_POST['post_type'] ) ) {

	    			case 'activity_photo':
	    			case 'activity_slideshow':

			    		// Get Max Files Number.
			    		$max_files_number = youzify_option( 'youzify_attachments_max_nbr', 200 );

			    		if ( $_POST['attachments_number'] > $max_files_number ) {
			    			wp_send_json_error( array( 'error' => sprintf( __( "You can't upload more than %d files.", 'youzify' ), $max_files_number ) ) );
			    		}

            			// Get Image Allowed Extentions.
	    				$image_extensions = youzify_get_allowed_extensions( 'image' );

		    			if ( ! in_array( $ext, $image_extensions ) ) {
		    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid image extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $image_extensions ) ) ) );
		    			}

	    				break;

	    			case 'activity_video':

		            	// Get Video Allowed Extentions.
	    				$video_extensions = youzify_get_allowed_extensions( 'video' );

		    			if ( ! in_array( $ext, $video_extensions ) ) {
		    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid video extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $video_extensions ) ) ) );
		    			}

	    				break;

	    			case 'activity_audio':

		            	// Get Audio Allowed Extentions.
	    				$audio_extensions = youzify_get_allowed_extensions( 'audio' );

		    			if ( ! in_array( $ext, $audio_extensions ) ) {
		    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid audio extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $audio_extensions ) ) ) );
		    			}

	    				break;

	    			case 'activity_file':

		            	// Get File Allowed Extentions.
	    				$file_extensions = youzify_get_allowed_extensions( 'file' );

		    			if ( ! in_array( $ext, $file_extensions ) ) {
		    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $file_extensions ) ) ) );
		    			}

	    				break;

	    			default:
	    				break;
	    		}

	    		$max_size = youzify_option( 'youzify_attachments_max_size', 10 );

	    		break;

	    	case 'comment':
	    		$max_size = youzify_option( 'youzify_wall_comments_attachments_max_size', 10 );
	    		$comments_extensions = youzify_option( 'youzify_wall_comments_attachments_extensions', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi' ) );
    			if ( ! in_array( $ext, $comments_extensions ) ) {
    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $comments_extensions ) ) ) );
    			}

	    		break;

	    	case 'message':
	    		$max_size = youzify_option( 'youzify_messages_attachments_max_size', 10 );
	    		$message_extensions = youzify_option( 'youzify_messages_attachments_extensions', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi' ) );
    			if ( ! in_array( $ext, $message_extensions ) ) {
    				wp_send_json_error( array( 'error' => sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzify' ), implode( ', ', $message_extensions ) ) ) );
    			}

	    		break;

	    	default:
	    		break;

	    }

		// Set max file size in bytes.
		$max_file_size = apply_filters( 'youzify_wall_attachments_max_size', $max_size * 1048576 );

		// Check that the file is not too big.
	    if ( $file['size'] > $max_file_size ) {
	    	wp_send_json_error( array( 'error' =>  sprintf( __( 'File too large. File must be less than %g megabytes.', 'youzify' ), $max_size ) ) );
	    }

		// Check File has the Right Extension.
		if ( ! $this->validate_file_extension( $ext ) ) {
	    	wp_send_json_error( array( 'error' => __( 'Sorry, this file type is not permitted for security reasons.', 'youzify' ) ) );
		}

		if ( $file['name'] ) {

			// Get File Name
			$file_name = apply_filters( 'youzify_wall_attachment_filename', $file['name'], $ext );

		    // Change Default Upload Directory to the Plugin Directory.
			add_filter( 'upload_dir', array( $this, 'temporary_upload_directory' ) );

			$enable_compression = youzify_option( 'youzify_compress_images', 'on' ) == 'on' ? true : false;

			// Check if images compression is enabled.
	        if ( apply_filters( 'youzify_enable_attachments_compression', $enable_compression ) && in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {

	        	// Get Compressed Image Name.
	        	$movefile = $this->get_compressed_image( $file['tmp_name'], $file_name );

	        	// Change PNG extension to JPG.
	        	if ( $movefile && $ext == 'png' ) {
	        		$file_name = str_replace( '.png', '.jpg', $file_name );
	        	}

		        // Upload File.
	        	if ( ! $movefile ) {

	        		$upload_args = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );

		        	$movefile = wp_handle_upload( $upload_args, array( 'test_form' => false ) );

	        	}

	        } else {

	        	$upload_args = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );
		        // Upload File.
		        $movefile = wp_handle_upload( $upload_args, array( 'test_form' => false ) );

	        }

	        // Get Files Data.
	        if ( $movefile && ! isset( $movefile['error'] ) ) {
	        	$file_data = array( 'real_name' => $file_name, 'type' => $file['type'], 'file_size' => $file['size'], 'original' => basename( $movefile['url'] ), 'base_url' => $Youzify_upload_url );
	        }

    	}

    	// After Upload Hook
    	do_action( 'youzify_after_attachments_upload', $file_data, $movefile );

	    // Change Upload Directory to the Default Directory .
		remove_filter( 'upload_dir', array( $this, 'temporary_upload_directory' ) );

		if ( empty( $manual_files ) ) {
			wp_send_json_success( $file_data );
		} else {
			return $file_data;
		}

    }

    /**
     * Get Image Size.
     */
    function get_images_sizes( $sizes ) {

    	if ( ! apply_filters( 'youzify_enable_youzify_images_custom_sizes', true ) ) {
    		return $sizes;
    	}

    	global $youzify_upload_source;

    	if ( is_numeric( $youzify_upload_source ) ) {
    		$youzify_upload_source = 'message';
    	}

    	$sizes = array(
    		'youzify-thumbnail' => array( 'width' => 150, 'height' => 150, 'crop' => 1 ),
    		'youzify-medium' => array('width' => 300, 'height' => 300, 'crop' => 1 )
    	);

    	switch ( $youzify_upload_source ) {

    		case 'message':
    			$sizes = array( 'youzify-message' => array( 'width' => 500, 'crop' => 0 ) );
    			break;

    		case 'activity_comment':
    			$sizes = array( 'youzify-comment' => array( 'width' => 300, 'crop' => 0 ) );
    			break;

    		case 'activity_file':
    		case 'activity_avatar':
    			$sizes = array();
    			break;

    		case 'profile_project_widget':
    			$sizes = array( 'youzify-medium' => array( 'width' => 600, 'height' => 600, 'crop' => 1 ) );
    			break;

    		case 'profile_about_me_widget':
    			$sizes['youzify-thumbnail'] = array( 'width' => 180, 'height' => 180, 'crop' => 1 );
    			break;

    		case 'activity_link':
    		case 'activity_photo':
    			$sizes['youzify-activity-wide'] = array( 'width' => 825, 'height' => 0, 'crop' => 0 );
    			break;

    		case 'profile_link_widget':
    		case 'profile_quote_widget':
    		case 'profile_slideshow_widget':
    			$sizes = array( 'youzify-wide' => array( 'width' => 825, 'height' => 300, 'crop' => 1 ) );
    			break;

    		case 'activity_quote':
    		case 'activity_cover':
    		case 'activity_slideshow':
    			$sizes['youzify-wide'] = array( 'width' => 825, 'height' => 300, 'crop' => 1 );
    			break;

    	}

    	return apply_filters( 'youzify_images_sizes', $sizes, $youzify_upload_source );

    }

    /**
     * Upload to Wordpress Library
     **/
    function wml_upload( $file_path, $original_image, $new_name = false ) {

    	// Disable Wordpress Media Default Sizes.
    	add_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

    	// Set Upload Directory to Youzify Directory.
		add_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

    	$attachment_id = '';

    	// Call Required Files
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Upload File to WordPress Media Library.
    	$attachment_id = media_handle_sideload(
    		array(
			    'name'     => empty( $new_name ) ? $original_image : $new_name,
			    'tmp_name' => $file_path,
			)
	    );

		if ( ! is_wp_error( $attachment_id ) ) {

			// Set File Category
			wp_set_object_terms( $attachment_id, 'youzify_media', 'category', true );

			do_action( 'youzify_after_wp_media_upload', $attachment_id );

		}

    	// Set Upload Directory to Default Again.
		remove_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

		return $attachment_id;
    }

    /**
     * Delete Activity Attachments.
     */
    function delete_attachments( $activities ) {

    	global $wpdb, $Youzify_media_table;

    	$force_delete = apply_filters( 'youzify_force_attachments_delete', true );

    	foreach ( $activities as $activity ) {

			// Get Activity Attachments.
			$attachments = bp_activity_get_meta( $activity->id, 'youzify_attachments' );

	    	// Check if the activity contains Attachments.
			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment_id => $data ) {

					// Delete Attachment
					wp_delete_attachment( $attachment_id, $force_delete );

					// Delete Thumbnail if found.
					if ( isset( $data['thumbnail'] ) && ! isset( $data['id']) ) {
						wp_delete_attachment( $data['thumbnail'], $force_delete );
					}

				}
			}

			if ( $activity->type == 'activity_video' ) {

				global $Youzify_media_table, $wpdb;

				// Get Activity Attachments.
				$attachments = youzify_get_activity_attachments( $activity->id );

		    	// Check if the activity contains Attachments.
				if ( empty( $attachments ) ) {
					continue;
				}

				// Get Component.
				$component = $activity->type == 'activity_comment' ? 'comment' : 'activity';

				// Delete All Activity Attachments.
				$wpdb->delete( $Youzify_media_table, array( 'item_id' => $activity->id, 'component' => $component ), array( '%d', '%s' ) );

			}

			// Delete Activity Attachments Data.
			bp_activity_delete_meta( $activity->id, 'youzify_attachments' );

    	}

    }

    /**
     * Delete Attachments Media.
     */
    function delete_attachments_media( $post_id, $post = null ) {

    	if ( ! empty( $post ) && $post->post_type != 'attachment' ) {
    		return;
    	}

    	global $wpdb, $Youzify_media_table;

    	$wpdb->delete( $Youzify_media_table, array( 'media_id' => $post_id ), array( '%d' ) );

    }

    /**
     * Delete Attachments By Media ID.
     */
    function delete_attachments_by_media_id( $media_id = null, $item_id = null ) {

    	if ( empty( $media_id ) || empty( $item_id ) ) {
    		return;
    	}

    	// Get Medias
    	$medias = is_array( $media_id ) ? $media_id : array( $media_id );

    	// Force Delete.
    	$force_delete = apply_filters( 'youzify_force_attachments_delete', true );

    	// Get Activity Attachments
    	$activity_attachments = bp_activity_get_meta( $item_id, 'youzify_attachments' );

    	foreach ( $medias as $attachment_id ) {

    		// Delete Wordpress Media Library Attachment.
    		wp_delete_attachment( $attachment_id, $force_delete );

    		if ( isset( $activity_attachments[ $attachment_id ] ) ) {

    			// Delete Thumbnail If Found.
    			if ( isset( $activity_attachments[ $attachment_id ]['thumbnail'] ) ) {
    				wp_delete_attachment( $activity_attachments[ $attachment_id ]['thumbnail'], $force_delete );
    			}

    			// Delete Activity Attachment.
    			unset( $activity_attachments[ $attachment_id ] );

    		}

    	}

		if ( ! empty( $activity_attachments ) ) {
    		bp_activity_update_meta( $item_id, 'youzify_attachments', $activity_attachments );
    	} else {
    		bp_activity_delete_meta( $item_id, 'youzify_attachments' );
    	}

    }

    /**
     * Validate file extension.
     */
    function validate_file_extension( $file_ext ) {

	   // Get a list of allowed mime types.
	   $mimes = get_allowed_mime_types();

	    // Loop through and find the file extension icon.
	    foreach ( $mimes as $type => $mime ) {
	      if ( false !== strpos( $type, $file_ext ) ) {
	          return true;
	        }
	    }

	    return false;
	}

	/**
	 * Add 'user uploaded new avatar' Post.
	 */
	function set_new_avatar_activity( $activity ) {

		if ( 'new_avatar' != $activity->type ) {
			return false;
		}

		// Get User Avatar.
		$avatar_url = bp_core_fetch_avatar(
			array(
				'item_id' => $activity->user_id,
				'type'	  => 'full',
				'html' 	  => false,
			)
		);

		// Get Avatar Path
		$avatar_path = str_replace( bp_core_avatar_url(), bp_core_avatar_upload_path(), $avatar_url );

		global $Youzify_upload_dir;

		// Get Temporary Avatar Path
		$temp_avatar = $Youzify_upload_dir . 'temp/' . wp_basename( $avatar_url );

		// Copy file to temporary folder
	    if ( copy( $avatar_path, $temp_avatar ) ) {

		    // Get Attachment ID.
		    $attachment_id = $this->wml_upload( $temp_avatar, basename( $avatar_url ) );

			if ( $attachment_id ) {

				// Save Attachment.
				$this->save_media_attachments( $activity->id, array( array( 'id' => $attachment_id, 'user_id' => bp_displayed_user_id(), 'source' => 'activity_avatar', 'type' => 'image' ) ), 'activity' );

				// Add Meta
				bp_activity_add_meta( $activity->id, 'youzify_attachments', array( $attachment_id => 1 ) );

			}
	    }

	}

	/**
	 * Add 'User Uploaded New Cover' Post.
	 */
	function set_new_cover_activity( $item_id, $name, $cover_url ) {

		if ( ! bp_is_active( 'activity' ) ) {
			return;
		}

		// Get Activitiy ID.
		$activity_id = bp_activity_add(
			array(
				'type'      => 'new_cover',
				'user_id'   => bp_displayed_user_id(),
				'component' => buddypress()->activity->id,
			)
		);

		global $Youzify_upload_dir;

		// Get Cover Path
		$cover = bp_attachments_cover_image_upload_dir();

		// Get Cover Path.
		$cover_path = str_replace( $cover['baseurl'], $cover['basedir'], $cover_url );

		// Get Temporary Folder Path
		$temp_cover = $Youzify_upload_dir . 'temp/' . wp_basename( $cover_path );

		// Copy file to temporary upload folder folder
	    if ( copy( $cover_path, $temp_cover ) ) {

		    // Get Attachment ID.
		    $attachment_id = $this->wml_upload( $temp_cover, basename( $cover_url ) );

			// Save Cover Url.
			if ( $attachment_id ) {

				// Save Attachment.
				$this->save_media_attachments( $activity_id, array( array( 'id' => $attachment_id, 'user_id' => bp_displayed_user_id(), 'source' => 'activity_cover', 'type' => 'image' ) ), 'activity' );

				// Add Meta
				bp_activity_add_meta( $activity_id, 'youzify_attachments', array( $attachment_id => 1 ) );

			}
	    }

	}

	/**
	 * Save Uploaded Files.
	 */
	function upload_profile_files() {

		// Before Upload User Files Action.
		do_action( 'youzify_before_upload_user_files' );

		// Check Nonce Security
		check_ajax_referer( 'youzify_nonce_security', 'nonce' );

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

	    $upload_overrides = array( 'test_form' => false );

	    // Get Max File Size in Mega.
	    $max_size = youzify_option( 'youzify_files_max_size', 3 );

		// Set max file size in bytes.
		$max_file_size = $max_size * 1048576;

		// Valid Extensions
		$valid_extensions = apply_filters( 'youzify_profile_attachements_valid_extensions', array( 'jpeg', 'jpg', 'png', 'gif' ) );

		// Valid Types
		$valid_types = array( 'image/jpeg', 'image/jpg', 'image/png','image/gif' );

		// Minimum Image Resolutions.
		$min = apply_filters( 'youzify_attachments_image_min_resolution', array( 'width' => '100', 'height' => '100' ) );

	    // Change Default Upload Directory to the Youzify Directory.
		add_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

    	// Disable Wordpress Media Default Sizes.
    	add_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

		// Create New Array
		$uploaded_files = array();

	    foreach ( $_FILES as $key => $file ) :

		    // Get Image Size.
		    $get_image_size = getimagesize( $file['tmp_name'] );

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

			// Check File has the Right Extension.
			if ( ! in_array( $ext, $valid_extensions ) ) {
				wp_send_json_error( array( 'error' => __( 'Invalid file extension.', 'youzify' ) ) );
			}

			// Check That The File is of The Right Type.
			if ( ! in_array( $file['type'], $valid_types ) ) {
				wp_send_json_error( array( 'error' => __( 'Invalid file type.', 'youzify' ) ) );
			}

			// Check that the file is not too big.
		    if ( $file['size'] > $max_file_size ) {
				wp_send_json_error( array( 'error' => sprintf( esc_html__( 'File too large. File must be less than %d megabytes.', 'youzify' ), $max_size ) ) );
		    }

			// Check Image Existence.
			if ( ! $get_image_size ) {
				wp_send_json_error( array( 'error' => __( 'Uploaded file is not a valid image.', 'youzify' ) ) );
			}

			// Check Image Minimum Width.
			if ( $get_image_size[0] < $min[ 'width' ] ) {
				wp_send_json_error( array( 'error' => sprintf( esc_html__( 'Image minimum width is %d pixel.', 'youzify' ), $min['width'] ) ) );
			}
			// Check Image Minimum Height.
			if ( $get_image_size[1] < $min[ 'height' ] ) {
				wp_send_json_error( array( 'error' => sprintf( esc_html__( 'Image minimum height is %d pixel.', 'youzify' ), $min['height'] ) ) );
			}

			if ( $file['name'] ) {

				// Get File Name
				$file_name = apply_filters( 'youzify_wall_attachment_filename', $file['name'], $ext );

				// Check if images compression is enabled.
				$enable_compression = youzify_option( 'youzify_compress_images', 'on' ) == 'on' ? true : false;

		        if ( apply_filters( 'youzify_enable_attachments_compression', $enable_compression ) && in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {

		        	// Get Compressed Image Name.
		        	$movefile = $this->get_compressed_image( $file['tmp_name'], $file_name );

		        	// Change PNG extension to JPG.
		        	if ( $movefile && $ext == 'png' ) {
		        		$file_name = str_replace( '.png', '.jpg', $file_name );
		        	}

			        // Upload File.
		        	if ( ! $movefile ) {
		        		$uploadedfile = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );
			        	$movefile = wp_handle_upload( $uploadedfile, array( 'test_form' => false ) );
		        	}

		        } else {

					$uploadedfile = array(
					    'name'     => $file_name,
					    'size'     => $file['size'],
					    'type'     => $file['type'],
					    'error'    => $file['error'],
					    'tmp_name' => $file['tmp_name']
					);

			        // Upload File.
			        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		        }

		        if ( $movefile && ! isset( $movefile['error'] ) ) {

					// Get File Type.
					$wp_filetype = wp_check_filetype( $file_name, null );

					// Get Attachment Args.
					$args = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', '', $file_name ),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					// Insert Attachment.
					$attachment_id = wp_insert_attachment( $args, $movefile['file'] );

					// Set Media Category.
					wp_set_object_terms( $attachment_id, 'youzify_media', 'category', true );

					if ( ! is_wp_error( $attachment_id ) ) {

						// Include Image Clas.
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate Metadata
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $movefile['file'] );

						// Update Attachment.
						wp_update_attachment_metadata( $attachment_id, $attachment_data );

					}

					do_action( 'youzify_after_wp_media_upload', $attachment_id, $movefile, $attachment_data );

					// Save Attachment.
					$this->save_media_attachments( 0, array( array( 'id' => $attachment_id, 'user_id' => absint( $_POST['user_id'] ), 'source' => sanitize_key( $_POST['source'] ), 'type' => 'image' ) ), 'profile' );

					wp_send_json_success( array( 'attachment_id' => $attachment_id, 'url' => wp_get_attachment_url( $attachment_id, 'full' ) ) );

				}

	    	}

	    endforeach;

    	// Enable Wordpress Media Default Sizes.
    	remove_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

	    // Change Youzify Upload Directory to the Default Directory .
		remove_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

		// die();
	}

	/**
	 * Delete Attachment.
	 */
    function delete_attachment() {

    	if ( ! isset( $_POST['attachment_id'] ) || empty( $_POST['attachment_id'] ) ) {
    		return;
    	}

	    wp_delete_attachment( absint( $_POST['attachment_id'] ), apply_filters( 'youzify_force_attachments_delete', true ) );

    }

	/**
	 * Delete Temporary Attachment.
	 */
    function delete_temporary_attachment() {

    	global $Youzify_upload_dir;

		// Before Delete Attachment Action.
		do_action( 'youzify_before_delete_attachment' );

		// Check Nonce Security
		check_ajax_referer( 'youzify-nonce', 'security' );

		// Get File Path.
		$file_path = $Youzify_upload_dir . 'temp/' . wp_basename( sanitize_text_field( $_POST['attachment'] ) );

		// Delete File.
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		die();

    }


	/**
	 * Save New Thumbnail
	 */
	function get_compressed_image( $source, $filename ) {

	    // Get image from file
	    $img = false;

	    // Get File Type.
	    $file_type = wp_check_filetype( $filename );

	    // Get File Name.
	    $file_name = pathinfo( $filename, PATHINFO_FILENAME );

	    switch ( $file_type['type'] ) {

	        case 'image/jpeg': {
	            $img = imagecreatefromjpeg( $source );
	            break;
	        }

	        case 'image/png': {
	            $image = imagecreatefrompng( $source );
				$img = imagecreatetruecolor( imagesx( $image ), imagesy( $image ) );
				imagefill( $img, 0, 0, imagecolorallocate( $img, 255, 255, 255 ) );
				imagealphablending( $img, TRUE );
				imagecopy( $img, $image, 0, 0, 0, 0, imagesx( $image ), imagesy( $image ) );
	            break;
	        }

	    }

	    if ( empty( $img ) ) {
	        return false;
	    }

	    if ( function_exists( 'exif_read_data' ) ) {

		    // Get Image Data.
		    $exif = @exif_read_data( $source );

		    // Fix Rotation
		    if ( ! empty( $exif['Orientation'] ) ) {
		        switch ( $exif['Orientation'] ) {
		           case 8:
		               $img = imagerotate( $img, 90, 0 );
		               break;
		           case 3:
		               $img = imagerotate( $img, 180, 0 );
		               break;
		           case 6:
		               $img = imagerotate( $img, -90, 0 );
		               break;
		       }
		   }

	    }

	    // Get File Path.
	    $folder = wp_get_upload_dir();

	    // Get Compression Quality.
	    $quality = apply_filters( 'youzify_attachments_compression_quality', youzify_option( 'youzify_images_compression_quality', 90 ) );

	    // Get New Image Path.
	    $compressed_image = wp_unique_filename( $folder['path'], $file_name . '.jpg' );

	    // Get New File Path.
	    $new_file = $folder['path'] . '/' . $compressed_image;

	    if ( imagejpeg( $img, $new_file, $quality ) ) {

	        imagedestroy( $img );

	        return array( 'url' => $folder['url'] . '/' . $compressed_image, 'file' => $new_file );

	    }

	    return false;

	}

	/**
	 * Fix Image Rotation
	 */
	function fix_image_orientation( $file ) {

		// Check we have a file
		if ( ! file_exists( $file['file'] ) ) {
			return $file;
		}

		// Include Image Clas.
		if ( ! function_exists( 'wp_read_image_metadata' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		// Attempt to read EXIF data from the image
		$exif_data = wp_read_image_metadata( $file['file'] );

		if ( ! $exif_data ) {
			return $file;
		}

		// Check if an orientation flag exists
		if ( ! isset( $exif_data['orientation'] ) ) {
			return $file;
		}

		// Check if the orientation flag matches one we're looking for
		$required_orientations = array( 8, 3, 6 );
		if ( ! in_array( $exif_data['orientation'], $required_orientations ) ) {
			return $file;
		}

		// If here, the orientation flag matches one we're looking for
		// Load the WordPress Image Editor class
		$image = wp_get_image_editor( $file['file'] );
		if ( is_wp_error( $image ) ) {
			// Something went wrong - abort
			return $file;
		}

		// Store the source image EXIF and IPTC data in a variable, which we'll write
		// back to the image once its orientation has changed
		// This is required because when we save an image, it'll lose its metadata.
		$source_size = getimagesize( $file['file'], $image_info );

		// Depending on the orientation flag, rotate the image
		switch ( $exif_data['orientation'] ) {

			/**
			* Rotate 90 degrees counter-clockwise
			*/
			case 8:
				$image->rotate( 90 );
				break;

			/**
			* Rotate 180 degrees
			*/
			case 3:
				$image->rotate( 180 );
				break;

			/**
			* Rotate 270 degrees counter-clockwise ($image->rotate always works counter-clockwise)
			*/
			case 6:
				$image->rotate( 270 );
				break;

		}

		// Save the image, overwriting the existing image
		// This will discard the EXIF and IPTC data
		$image->save( $file['file'] );

		// Drop the EXIF orientation flag, otherwise applications will try to rotate the image
		// before display it, and we don't need that to happen as we've corrected the orientation

		// Write the EXIF and IPTC metadata to the revised image
		$result = $this->transfer_iptc_exif_to_image( $image_info, $file['file'], $exif_data['orientation'] );
		if ( ! $result ) {
			return $file;
		}

		// Finally, return the data that's expected
		return $file;

	}

	/**
	* Transfers IPTC and EXIF data from a source image which contains either/both,
	* and saves it into a destination image's headers that might not have this IPTC
	* or EXIF data
	*
	* Useful for when you edit an image through PHP and need to preserve IPTC and EXIF
	* data
	*
	* @since 1.0.0
	*
	* @source http://php.net/iptcembed - ebashkoff at gmail dot com
	*
	* @param string $image_info 			EXIF and IPTC image information from the source image, using getimagesize()
	* @param string $destination_image 		Path and File of Destination Image, which needs IPTC and EXIF data
	* @param int 	$original_orientation 	The image's original orientation, before we changed it.
	*										Used when we replace this orientation in the EXIF data
	*/
	function transfer_iptc_exif_to_image( $image_info, $destination_image, $original_orientation ) {

	    // Check destination exists
	    if ( ! file_exists( $destination_image ) ) {
	    	return false;
	    }

	    // Get EXIF data from the image info, and create the IPTC segment
	    $exif_data = ( ( is_array( $image_info ) && key_exists( 'APP1', $image_info ) ) ? $image_info['APP1'] : null );
	    if ( $exif_data ) {
	    	// Find the image's original orientation flag, and change it to 1
	    	// This prevents applications and browsers re-rotating the image, when we've already performed that function
	        // @TODO I'm not sure this is the best way of changing the EXIF orientation flag, and could potentially affect
	        // other EXIF data
	    	$exif_data = str_replace( chr( dechex( $original_orientation ) ) , chr( 0x1 ), $exif_data );

	        $exif_length = strlen( $exif_data ) + 2;
	        if ( $exif_length > 0xFFFF ) {
	        	return false;
	        }

	        // Construct EXIF segment
	        $exif_data = chr(0xFF) . chr(0xE1) . chr( ( $exif_length >> 8 ) & 0xFF) . chr( $exif_length & 0xFF ) . $exif_data;
	    }

	    // Get IPTC data from the source image, and create the IPTC segment
	    $iptc_data = ( ( is_array( $image_info ) && key_exists( 'APP13', $image_info ) ) ? $image_info['APP13'] : null );
	    if ( $iptc_data ) {
	        $iptc_length = strlen( $iptc_data ) + 2;
	        if ( $iptc_length > 0xFFFF ) {
	        	return false;
	        }

	        // Construct IPTC segment
	        $iptc_data = chr(0xFF) . chr(0xED) . chr( ( $iptc_length >> 8) & 0xFF) . chr( $iptc_length & 0xFF ) . $iptc_data;
	    }

	    // Get the contents of the destination image
	    $destination_image_contents = youzify_file_get_contents( $destination_image );
	    if ( ! $destination_image_contents ) {
	    	return false;
	    }
	    if ( strlen( $destination_image_contents ) == 0 ) {
	    	return false;
	    }

	    // Build the EXIF and IPTC data headers
	    $destination_image_contents = substr( $destination_image_contents, 2 );
	    $portion_to_add = chr(0xFF) . chr(0xD8); // Variable accumulates new & original IPTC application segments
	    $exif_added = ! $exif_data;
	    $iptc_added = ! $iptc_data;

	    while ( ( substr( $destination_image_contents, 0, 2 ) & 0xFFF0 ) === 0xFFE0 ) {
	        $segment_length = ( substr( $destination_image_contents, 2, 2 ) & 0xFFFF );
	        $iptc_segment_number = ( substr( $destination_image_contents, 1, 1 ) & 0x0F );   // Last 4 bits of second byte is IPTC segment #
	        if ( $segment_length <= 2 ) {
	        	return false;
	        }

	        $thisexistingsegment = substr( $destination_image_contents, 0, $segment_length + 2 );
	        if ( ( 1 <= $iptc_segment_number) && ( ! $exif_added ) ) {
	            $portion_to_add .= $exif_data;
	            $exif_added = true;
	            if ( 1 === $iptc_segment_number ) {
	                $thisexistingsegment = '';
	            }
	        }

	        if ( ( 13 <= $iptc_segment_number ) && ( ! $iptc_added ) ) {
	            $portion_to_add .= $iptc_data;
	            $iptc_added = true;
	            if ( 13 === $iptc_segment_number ) {
	                $thisexistingsegment = '';
	            }
	        }

	        $portion_to_add .= $thisexistingsegment;
	        $destination_image_contents = substr( $destination_image_contents, $segment_length + 2 );
	    }

	    // Write the EXIF and IPTC data to the new file
	    if ( ! $exif_added ) {
	        $portion_to_add .= $exif_data;
	    }
	    if ( ! $iptc_added ) {
	        $portion_to_add .= $iptc_data;
	    }

	    $output_file = fopen( $destination_image, 'w' );
	    if ( $output_file ) {
	    	return fwrite( $output_file, $portion_to_add . $destination_image_contents );
	    }

	    return false;

	}

	/**
	 * Sanitize Attachments.
	 */
	function sanitize_attachments( $attachments ) {

		if ( is_array( $attachments ) ) {

			foreach ( $attachments as $key => $attachment ) {

				// Decode JSON
		    	$attachment = json_decode( wp_unslash( $attachment ), true );

				// Sanitize Data
				$attachments[ $key ] = array_map( 'sanitize_text_field', $attachment );

			}

		} else {

			// Decode JSON
	    	$attachments = json_decode( wp_unslash( $attachments ), true );

			// Sanitize Data
			$attachments = array_map( 'sanitize_text_field', $attachments );

		}

		return $attachments;
	}

	/**
	 * Change Default Upload Directory to the Temporary Youzify Directory.
	 */
	function temporary_upload_directory( $dir ) {

		global $Youzify_upload_folder, $Youzify_upload_url, $Youzify_upload_dir;

	    return array(
	        'path'   => $Youzify_upload_dir . 'temp',
	        'url'    => $Youzify_upload_url . 'temp',
	        'subdir' => '/' . $Youzify_upload_folder . 'temp',
	    ) + $dir;

	}

	/**
	 * Change Default Upload Directory to the Youzify Directory.
	 */
	function youzify_upload_directory( $upload_dir ) {

		global $youzify_upload_component;

		if ( ! isset( $youzify_upload_component ) || 'groups' != $youzify_upload_component['component'] ) {
			$folder = 'members/';
			$id = apply_filters( 'youzify_upload_directory_user_folder_name', get_current_user_id() );
		} else {
			$folder = 'groups/';
			$id = apply_filters( 'youzify_upload_directory_group_folder_name', $youzify_upload_component['group_id'] );
		}

		// Youzify Upload Folder
		$upload_folder = apply_filters( 'youzify_upload_folder_name', 'youzify' );

		if ( strpos( $upload_dir['path'], $upload_folder . '/' . $folder ) === false ) {
			$upload_dir['path'] = trailingslashit( str_replace( $upload_dir['subdir'], '', $upload_dir['path'] ) ) . $upload_folder . '/' . $folder . $id . $upload_dir['subdir'];
			$upload_dir['url']  = trailingslashit( str_replace( $upload_dir['subdir'], '', $upload_dir['url'] ) ) . $upload_folder . '/' . $folder . $id . $upload_dir['subdir'];
		}

		return apply_filters( 'youzify_filter_upload_dir', $upload_dir );
	}

}

new Youzify_Attachments();