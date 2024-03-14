<?php
class MaxGalleriaImageGallery {
	public $nonce_image_edit = array(
		'action' => 'image_edit',
		'name' => 'maxgalleria_image_edit'
	);

	public $nonce_image_edit_bulk = array(
		'action' => 'image_edit_bulk',
		'name' => 'maxgalleria_image_edit_bulk'
	);
	
	public $nonce_image_include_single = array(
		'action' => 'image_include_single',
		'name' => 'maxgalleria_image_include_single'
	);

	public $nonce_image_include_bulk = array(
		'action' => 'image_include_bulk',
		'name' => 'maxgalleria_image_include_bulk'
	);

	public $nonce_image_exclude_single = array(
		'action' => 'image_exclude_single',
		'name' => 'maxgalleria_image_exclude_single'
	);

	public $nonce_image_exclude_bulk = array(
		'action' => 'image_exclude_bulk',
		'name' => 'maxgalleria_image_exclude_bulk'
	);

	public $nonce_image_remove_single = array(
		'action' => 'image_remove_single',
		'name' => 'maxgalleria_image_remove_single'
	);

	public $nonce_image_remove_bulk = array(
		'action' => 'image_remove_bulk',
		'name' => 'maxgalleria_image_remove_bulk'
	);

	public $nonce_image_reorder = array(
		'action' => 'image_reorder',
		'name' => 'maxgalleria_image_reorder'
	);
	
	public $nonce_crop_image = array(
		'action' => 'crop_image',
		'name' => 'maxgalleria_crop_image'
	);	
	
	public function __construct() {
		$this->setup_hooks();
	}

	public function setup_hooks() {
		// Ajax call to add media library images to a gallery
		add_action('wp_ajax_add_media_library_images_to_gallery', array($this, 'add_media_library_images_to_gallery'));
		add_action('wp_ajax_nopriv_add_media_library_images_to_gallery', array($this, 'add_media_library_images_to_gallery'));
		
		// Ajax call to include a single image in a gallery
		add_action('wp_ajax_include_single_image_in_gallery', array($this, 'include_single_image_in_gallery'));
		add_action('wp_ajax_nopriv_include_single_image_in_gallery', array($this, 'include_single_image_in_gallery'));

		// Ajax call to include bulk images in a gallery
		add_action('wp_ajax_include_bulk_images_in_gallery', array($this, 'include_bulk_images_in_gallery'));
		add_action('wp_ajax_nopriv_include_bulk_images_in_gallery', array($this, 'include_bulk_images_in_gallery'));

		// Ajax call to exclude a single image from a gallery
		add_action('wp_ajax_exclude_single_image_from_gallery', array($this, 'exclude_single_image_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_single_image_from_gallery', array($this, 'exclude_single_image_from_gallery'));

		// Ajax call to exclude bulk images from a gallery
		add_action('wp_ajax_exclude_bulk_images_from_gallery', array($this, 'exclude_bulk_images_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_bulk_images_from_gallery', array($this, 'exclude_bulk_images_from_gallery'));

		// Ajax call to remove a single image from a gallery
		add_action('wp_ajax_remove_single_image_from_gallery', array($this, 'remove_single_image_from_gallery'));
		add_action('wp_ajax_nopriv_remove_single_image_from_gallery', array($this, 'remove_single_image_from_gallery'));
		
		// Ajax call to remove bulk images from a gallery
		add_action('wp_ajax_remove_bulk_images_from_gallery', array($this, 'remove_bulk_images_from_gallery'));
		add_action('wp_ajax_nopriv_remove_bulk_images_from_gallery', array($this, 'remove_bulk_images_from_gallery'));

		// Ajax call to reorder images
		add_action('wp_ajax_reorder_images', array($this, 'reorder_images'));
		add_action('wp_ajax_nopriv_reorder_images', array($this, 'reorder_images'));
		
		add_action('wp_ajax_crop_image', array($this, 'crop_image'));
		add_action('wp_ajax_nopriv_crop_image', array($this, 'crop_image'));		
        
	}
	
	public function add_media_library_images_to_gallery() {
        
    if ( !wp_verify_nonce( $_POST['nonce'], MG_META_NONCE)) {
      exit(esc_html__('missing nonce!','maxgalleria'));
    } 
        
		if (isset($_POST)) {
			$result = 0;
			
      $gallery_id = trim(stripslashes(sanitize_text_field($_POST['gallery_id'])));
			
      // using esc_url on $_POST['url'] generates an error because $_POST['url'] is an array
			do_action(MAXGALLERIA_ACTION_BEFORE_ADD_IMAGES_TO_GALLERY, $gallery_id, $_POST['url']);
			
			$count = count($_POST['url']);
			for ($i = 0; $i < $count; $i++) {
				$url = trim(sanitize_url($_POST['url'][$i]));
				$title = trim(sanitize_title($_POST['title'][$i]));
				$caption = trim(wp_filter_post_kses($_POST['caption'][$i]));
				$description = trim(wp_filter_post_kses($_POST['description'][$i]));
				$alt_text = trim(sanitize_text_field($_POST['alt_text'][$i]));

				if ($url != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_ADD_IMAGE_TO_GALLERY, $gallery_id, $url, $title, $caption, $description, $alt_text);

					$attachment_id = $this->download_image_attach_to_gallery($gallery_id, $url, $title, $caption, $description, $alt_text);
					$result = $attachment_id;
					
					do_action(MAXGALLERIA_ACTION_AFTER_ADD_IMAGE_TO_GALLERY, $gallery_id, $url, $title, $caption, $description, $alt_text);
				}
			}

			// Once the images have been added to the gallery, delete any attachments with menu_order
			// of zero. The reason is because starting with WP 3.9, when a user adds a new image to
			// their media library, it automatically attaches to the post behind the scenes. This causes
			// the images to get added to the gallery twice - once with menu_order of 0 and another with
			// menu_order equal to whatever the next menu_order is supposed to be. The latter image is
			// the one we want, which means the image with menu_order of 0 can be safely deleted.
      
			$bad_children = get_children(array(
				'post_parent' => $gallery_id,
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'menu_order' => 0
			));
      
			$force_delete = true; // Bypass trash and do hard delete
			foreach ($bad_children as $child) {
        if($child->ID !== $attachment_id) {
          $this->mg_delete_attachment($child->ID, $force_delete);
          delete_post_meta($child->ID, '_wp_trash_meta_status');
        }
			}
      
      // cannot use sanitize_url on $_POST['url'] as $_POST['url'] is an array
			do_action(MAXGALLERIA_ACTION_AFTER_ADD_IMAGES_TO_GALLERY, $gallery_id, $_POST['url']);
			echo (int) $result;
			die();
		}
	}
    
  // check if the image is already an attachment and get the attachment ID;
  public function check_for_duplicate_attachment($image_url) {
    global $wpdb;
    
    $current_site_url = site_url();
    if( strpos($image_url, $current_site_url) == 0) {
 
      // without prepare
      //$sql = "select * from " . $wpdb->prefix . "posts where post_type = 'attachment' and guid = '$image_url'";
      
      // use perpare to make it more secure
      // if uploads_use_yearmonth_folders is set query for the whole page url
      if ( get_option( 'uploads_use_yearmonth_folders' ) ) {
        $sql = $wpdb->prepare("select * from " . $wpdb->prefix . "posts where post_type = 'attachment' and guid = '%s'", $image_url );        
      }
      // if uploads_use_yearmonth_folders not set query for the file name using LIKE
      else {
        $image_file = basename($image_url);
        $sql = $wpdb->prepare("select * from " . $wpdb->prefix . "posts where post_type = 'attachment' and guid LIKE '%%%s%%'", $image_file );        
      }  
      
      $row = $wpdb->get_row($sql);
      if($row)
        return $row->ID;
      else
        return false;      
    }
    else
      return false;
  }
	
	public function download_image_attach_to_gallery($gallery_id, $image_url, $title = '', $caption = '', $description = '', $alt_text = '') {		
		global $maxgalleria;
		
		if(class_exists("MaxGalleriaMediaLibProS3"))
			global $maxgalleria_media_library_pro_s3, $maxgalleria_media_library_pro;
					
		$result = 0;
		$download_success = true;
    
    // Get the next menu order value for the gallery
    $menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);
    
    $is_duplicate = $this->check_for_duplicate_attachment($image_url); 

//    if($is_duplicate) {
//      
//      $existing_attachment = get_post( $is_duplicate, ARRAY_A );
//
//      if( empty( $existing_attachment[ 'post_parent' ] ) ) {
//        $is_duplicate = false;                
//      }
//    }
    
    if( !$is_duplicate ) {
			
      // this image is not already on the site

      // Download the image into a temp file
      $temp_file = download_url($image_url);

      // Parse the url and use the temp file to form the file array (used in media_handle_sideload below)
      preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|webp|WEBP)/', $image_url, $matches);
      $file_array['name'] = basename($matches[0]);
      $file_array['tmp_name'] = $temp_file;

      // If we got an error or the file is not a valid image, delete the temp file
      if (is_wp_error($temp_file) || !file_is_valid_image($temp_file)) {
        @unlink($temp_file);
        $download_success = false;
      }

      if ($download_success) {

        // Set post data; the empty post_date ensures it gets today's date
        $post_data = array(
          'post_date' => '',
          'post_parent' => $gallery_id,
          'post_title' => $title,
          'post_excerpt' => $caption,
          'post_content' => $description,
          'menu_order' => $menu_order,
          'ancestors' => array()
        );

				
        // Sideload the image to create its attachment to the gallery
        $attachment_id = media_handle_sideload($file_array, $gallery_id, $description, $post_data);

        if (!is_wp_error($attachment_id)) {
          $result = $attachment_id;

          // Add the alt text
          update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
        }

        // Delete the temp file
        @unlink($temp_file);
				
				if(class_exists("MaxGalleriaMediaLibProS3")) {

					$image_url = wp_get_attachment_url($attachment_id);
					$filename = get_attached_file($attachment_id);

					// upload the image
					$post_type = 'attachment';
					$location = $maxgalleria_media_library_pro_s3->get_location($image_url, $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->uploads_folder_name);
					$destination_location = $maxgalleria_media_library_pro_s3->get_destination_location($location);
					$destination_folder = $maxgalleria_media_library_pro_s3->get_destination_folder($destination_location, $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->uploads_folder_name_length);
					$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $location, $filename, $attachment_id);

					// upload thumbnails
					$metadata = wp_get_attachment_metadata($attachment_id);

					foreach($metadata['sizes'] as $thumbnail) {
						$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
						$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $destination_location . '/' . $thumbnail['file'], $source_file, 0);
					}

					// delete from local server										
					if($maxgalleria_media_library_pro_s3->remove_from_local) {
						if($upload_result['statusCode'] == '200')	{
							$maxgalleria_media_library_pro_s3->remove_media_file($filename);										
							foreach($metadata['sizes'] as $thumbnail) {
								$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
								$maxgalleria_media_library_pro_s3->remove_media_file($source_file);										
							}
						}
					}
				}
			}
    } else {

      // the image is already on the site so copy the attachment; code from BDN Duplicate Images plugin      
      $attachment_id = $is_duplicate;
      $attachment = get_post( $attachment_id, ARRAY_A );
						
			if(class_exists("MaxGalleriaMediaLibProS3")) {

				$image_url = wp_get_attachment_url($attachment_id);
				$filename = get_attached_file($attachment_id);

				// upload the image
				$post_type = 'attachment';
				$location = $maxgalleria_media_library_pro_s3->get_location($image_url, $maxgalleria_media_library_pro->uploads_folder_name);
				$destination_location = $maxgalleria_media_library_pro_s3->get_destination_location($location);
				$destination_folder  = $maxgalleria_media_library_pro_s3->get_destination_folder($destination_location, $maxgalleria_media_library_pro->uploads_folder_name_length);
				$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $location, $filename, $attachment_id);

				// upload thumbnails
				$metadata = wp_get_attachment_metadata($attachment_id);

				foreach($metadata['sizes'] as $thumbnail) {
					$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
					$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $destination_location . '/' . $thumbnail['file'], $source_file, 0);
				}

				// delete from local server										
				if($maxgalleria_media_library_pro_s3->remove_from_local) {
					if($upload_result['statusCode'] == '200')	{
						$maxgalleria_media_library_pro_s3->remove_media_file($filename);										
						foreach($metadata['sizes'] as $thumbnail) {
							$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
							$maxgalleria_media_library_pro_s3->remove_media_file($source_file);										
						}
					}
				}
			}
			
      // assign a new value for menu_order
      //$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);
      $attachment[ 'menu_order' ] = $menu_order;
              
      //If the attachment doesn't have a post parent, simply change it to the attachment we're working with and be done with it      
      // assign a new value for menu_order
      if( empty( $attachment[ 'post_parent' ] ) ) {
        wp_update_post(
          array(
            'ID' => $attachment[ 'ID' ],
            'post_parent' => $gallery_id,
            'menu_order' => $menu_order
          )
        );
        $result = $attachment[ 'ID' ];
      } else {
        //Else, unset the attachment ID, change the post parent and insert a new attachment
        unset( $attachment[ 'ID' ] );
        $attachment[ 'post_parent' ] = $gallery_id;
        $new_attachment_id = wp_insert_post( $attachment );


        //Now, duplicate all the custom fields. (There's probably a better way to do this)
        $custom_fields = get_post_custom( $attachment_id );

        foreach( $custom_fields as $key => $value ) {
          //The attachment metadata wasn't duplicating correctly so we do that below instead
          if( $key != '_wp_attachment_metadata' )
            update_post_meta( $new_attachment_id, $key, $value[0] );
        }

        //Carry over the attachment metadata
        $data = wp_get_attachment_metadata( $attachment_id );
        wp_update_attachment_metadata( $new_attachment_id, $data );
				
				if(class_exists("MaxGalleriaMediaLibProS3")) {

					$image_url = wp_get_attachment_url($new_attachment_id);
					$filename = get_attached_file($new_attachment_id);

					// upload the image
					$post_type = 'attachment';
					$location = $maxgalleria_media_library_pro_s3->get_location($image_url, $maxgalleria_media_library_pro->uploads_folder_name);
					$destination_location = $maxgalleria_media_library_pro_s3->get_destination_location($location);
					$destination_folder  = $maxgalleria_media_library_pro_s3->get_destination_folder($destination_location, $maxgalleria_media_library_pro->uploads_folder_name_length);
					$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $location, $filename, $new_attachment_id);

					// upload thumbnails
					$metadata = wp_get_attachment_metadata($new_attachment_id);

					foreach($metadata['sizes'] as $thumbnail) {
						$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
						$upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $destination_location . '/' . $thumbnail['file'], $source_file, 0);
					}

					// delete from local server										
					if($maxgalleria_media_library_pro_s3->remove_from_local) {
						if($upload_result['statusCode'] == '200')	{
							$maxgalleria_media_library_pro_s3->remove_media_file($filename);										
							foreach($metadata['sizes'] as $thumbnail) {
								$source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
								$maxgalleria_media_library_pro_s3->remove_media_file($source_file);										
							}
						}
					}
				}
				

        $result = $new_attachment_id;
      }      
    }

		return $result;
	}

	public function include_single_image_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_include_single['action'], $this->nonce_image_include_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {
				$image_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
					delete_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
					
					$message = esc_html__('Included the image in this gallery.', 'maxgalleria');
        } 
      }
			
			echo esc_html($message);
			die();
		}
	}

	public function include_bulk_images_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_include_bulk['action'], $this->nonce_image_include_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'include') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_BULK_IMAGES_IN_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post(sanitize_text_field($id));
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
							delete_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
							do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_SINGLE_IMAGE_IN_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_BULK_IMAGES_IN_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Included 1 image in this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Included %d images in this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function exclude_single_image_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_exclude_single['action'], $this->nonce_image_exclude_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$image_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					update_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					
					$message = esc_html__('Excluded the image from this gallery.', 'maxgalleria');
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function exclude_bulk_images_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_exclude_bulk['action'], $this->nonce_image_exclude_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'exclude') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_BULK_IMAGES_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post(sanitize_text_field($id));
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							update_post_meta($image_post->ID, 'maxgallery_attachment_image_exclude', true);
							do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_BULK_IMAGES_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Excluded 1 image from this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Excluded %d images from this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function remove_single_image_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_remove_single['action'], $this->nonce_image_remove_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$image_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($image_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					
					$temp = array();
					$temp['ID'] = $image_post->ID;
					$temp['post_parent'] = null;
					
					wp_update_post($temp);
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
					$message = esc_html__('Removed the image from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function remove_bulk_images_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_remove_bulk['action'], $this->nonce_image_remove_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'remove') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_BULK_IMAGES_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$image_post = get_post(sanitize_text_field($id));
						if (isset($image_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$temp = array();
							$temp['ID'] = $image_post->ID;
							$temp['post_parent'] = null;
							
							wp_update_post($temp);
							do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_IMAGE_FROM_GALLERY, $image_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_BULK_IMAGES_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Removed 1 image from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Removed %d images from this gallery. To delete them permanently, use the Media Library.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function reorder_images() {
		if (isset($_POST) && check_admin_referer($this->nonce_image_reorder['action'], $this->nonce_image_reorder['name'])) {
			$message = '';

			if (isset($_POST['media-order']) && isset($_POST['media-order-id'])) {		
				do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_IMAGES_IN_GALLERY, sanitize_text_field($_POST['media-order']), sanitize_text_field($_POST['media-order-id']));
        				
				for ($i = 0; $i < count($_POST['media-order']); $i++) {
					$order = sanitize_text_field($_POST['media-order'][$i]);
					$image_id = sanitize_text_field($_POST['media-order-id'][$i]);
					
					$image_post = get_post($image_id);
					if (isset($image_post)) {
						do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_IMAGE_IN_GALLERY, $image_post);
						
						$temp = array();
						$temp['ID'] = $image_post->ID;
            // changed the nunber that is saved for new datatables.js
						$temp['menu_order'] = $i+1;
						//$temp['menu_order'] = $order;
						
						wp_update_post($temp);
						do_action(MAXGALLERIA_ACTION_AFTER_REORDER_IMAGE_IN_GALLERY, $image_post);
					}
				}
				
				do_action(MAXGALLERIA_ACTION_AFTER_REORDER_IMAGES_IN_GALLERY, sanitize_text_field($_POST['media-order']), sanitize_text_field($_POST['media-order-id']));
			}
			
			echo esc_html($message);
			die();
		}
	}
	
	public function crop_image() {
		
		global $wpdb;
		
		if (isset($_POST) && check_admin_referer($this->nonce_crop_image['action'], $this->nonce_crop_image['name'])) {
			$message = '';
			
			if (isset($_POST['postid'])) {			
				wp_ajax_image_editor();
		  }
		}	
		
		die();
	}
	
	public function crop_image2() {
		
		global $wpdb;
		
		if (isset($_POST) && check_admin_referer($this->nonce_crop_image['action'], $this->nonce_crop_image['name'])) {
			$message = '';
			
			if (isset($_POST['id'])) {			
        $image_id = trim(sanitize_text_field($_POST['id']));
				if (isset($image_id)) {
					$image_details = wp_get_attachment_metadata($image_id);
				}
		  }
		}	
		
		echo json_encode($image_details);
		die();
	}
	
	
	public function show_meta_box_gallery($post) {
		require_once 'meta/meta-image-gallery.php';
	}
	
	public function show_meta_box_shortcodes($post) {
		require_once 'meta/meta-shortcodes.php';
	}
	
	public function get_image_size_display($attachment) {
		$size = '';
		
		$meta = wp_get_attachment_metadata($attachment->ID);
		if (is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta)) {
			$size = "{$meta['width']} &times; {$meta['height']}";
		}
		
		return $size;
	}
	
	public function get_thumb_image($attachment, $thumb_shape, $thumb_columns, $thumb_crop = true) {
		$thumb_size = $this->get_thumb_size($thumb_shape, $thumb_columns);
		$thumb_image = $this->resize_image($attachment, $thumb_size['width'], $thumb_size['height'], $thumb_crop);
		return $thumb_image;
	}
	
	public function get_thumb_size($thumb_shape, $thumb_columns) {
		$thumb_size = null;

		switch ($thumb_shape) {
			case MAXGALLERIA_THUMB_SHAPE_LANDSCAPE:
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_ONE_COLUMN, array('width' => '700', 'height' => '466')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_TWO_COLUMN, array('width' => '550', 'height' => '366')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_THREE_COLUMN, array('width' => '400', 'height' => '266')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_FOUR_COLUMN, array('width' => '250', 'height' => '166')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_FIVE_COLUMN, array('width' => '200', 'height' => '133')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_SIX_COLUMN, array('width' => '180', 'height' => '120')); }	
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_SEVEN_COLUMN, array('width' => '150', 'height' => '100')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_EIGHT_COLUMN, array('width' => '130', 'height' => '86')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_NINE_COLUMN, array('width' => '115', 'height' => '76')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_LANDSCAPE_TEN_COLUMN, array('width' => '100', 'height' => '66')); }
				break;
			case MAXGALLERIA_THUMB_SHAPE_PORTRAIT:
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_ONE_COLUMN, array('width' => '700', 'height' => '1050')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_TWO_COLUMN, array('width' => '550', 'height' => '825')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_THREE_COLUMN, array('width' => '400', 'height' => '600')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_FOUR_COLUMN, array('width' => '250', 'height' => '375')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_FIVE_COLUMN, array('width' => '200', 'height' => '300')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_SIX_COLUMN, array('width' => '180', 'height' => '270')); }
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_SEVEN_COLUMN, array('width' => '150', 'height' => '225')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_EIGHT_COLUMN, array('width' => '130', 'height' => '195')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_NINE_COLUMN, array('width' => '115', 'height' => '172')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_PORTRAIT_TEN_COLUMN, array('width' => '100', 'height' => '150')); }
				break;
			default:
				// Square
				if ($thumb_columns == 1) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_ONE_COLUMN, array('width' => '700', 'height' => '700')); }
				if ($thumb_columns == 2) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_TWO_COLUMN, array('width' => '550', 'height' => '550')); }
				if ($thumb_columns == 3) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_THREE_COLUMN, array('width' => '400', 'height' => '400')); }
				if ($thumb_columns == 4) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_FOUR_COLUMN, array('width' => '250', 'height' => '250')); }
				if ($thumb_columns == 5) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_FIVE_COLUMN, array('width' => '200', 'height' => '200')); }
				if ($thumb_columns == 6) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_SIX_COLUMN, array('width' => '180', 'height' => '180')); }
				if ($thumb_columns == 7) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_SEVEN_COLUMN, array('width' => '150', 'height' => '150')); }
				if ($thumb_columns == 8) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_EIGHT_COLUMN, array('width' => '130', 'height' => '130')); }
				if ($thumb_columns == 9) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_NINE_COLUMN, array('width' => '115', 'height' => '115')); }
				if ($thumb_columns == 10) { $thumb_size = apply_filters(MAXGALLERIA_FILTER_THUMB_SIZE_SQUARE_TEN_COLUMN, array('width' => '100', 'height' => '100')); }
				break;
		}
		
		return $thumb_size;
	}
	
	public function resize_image($attachment, $width, $height, $crop) {
		// Get the image source for the attachment, note the following:
		// $image_src[0] = the URL of the image
		// $image_src[1] = the width of the image
		// $image_src[2] = the height of the image
		$image_src = wp_get_attachment_image_src($attachment->ID, 'full');
		
		// If either the width or height of the full size image is bigger than the target size, then we know we need to resize
		if ($image_src[1] > $width || $image_src[2] > $height) {
			$resized_image_path = '';
			$resized_image_url = '';
			
			$file_path = get_attached_file($attachment->ID);
			
			if(class_exists("MaxGalleriaMediaLibProS3")) {
				global $maxgalleria_media_library_pro_s3;
				$maxgalleria_media_library_pro_s3->check_and_fetch_file($file_path, $attachment->ID);
			}
							
			// Get the file info and extension
			$file_info = pathinfo($file_path);
			$extension = '.' . $file_info['extension'];

			// The image path without the extension
			$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];

			// Build the cropped image path and URL with the width and height as part of the name
			$cropped_image_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			$cropped_image_url = str_replace(basename($image_src[0]), basename($cropped_image_path), $image_src[0]);
			
			// Check if resized cropped version already exists (for crop = true but will also work for crop = false if the sizes match)			
			if(!class_exists("MaxGalleriaMediaLibProS3")) {
				if (file_exists($cropped_image_path)) {
					return array('url' => $cropped_image_url, 'width' => $width, 'height' => $height);
				}
				else {
					$resized_image_path = $cropped_image_path;
					$resized_image_url = $cropped_image_url;
				}
			} else {
				if($maxgalleria_media_library_pro_s3->s3_active && $maxgalleria_media_library_pro_s3->serve_from_s3) {
					if($this->mg_remote_file_exists($cropped_image_url)) {
					  return array('url' => $cropped_image_url, 'width' => $width, 'height' => $height);
					} else {
						$resized_image_path = $cropped_image_path;
						$resized_image_url = $cropped_image_url;
					}						
				} else {
					if (file_exists($cropped_image_path)) {
						return array('url' => $cropped_image_url, 'width' => $width, 'height' => $height);
					} else {
						$resized_image_path = $cropped_image_path;
						$resized_image_url = $cropped_image_url;
					}
				}
			}

			// If crop is false then check proportional image
			if ($crop == false) {
				// Calculate the size proportionally
				$proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
				$proportional_image_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
				$proportional_image_url = str_replace(basename($image_src[0]), basename($proportional_image_path), $image_src[0]);

				// Check if resized proportional version already exists
				if (file_exists($proportional_image_path)) {
					return array('url' => $proportional_image_url, 'width' => $proportional_size[0], 'height' => $proportional_size[1]);
				}
				else {
					$resized_image_path = $proportional_image_path;
					$resized_image_url = $proportional_image_url;
				}
			}

			// Getting this far means that neither the cropped resized image nor the proportional
			// resized image exists, so we use a WP_Image_Editor to do the resizing and save to disk
      
      // code added to deal with Gantry Template Framework & WP 4.0
      
      
      if(class_exists('Gantry') ) {
        $upload_dir = wp_upload_dir();
        $image_editor = wp_get_image_editor($upload_dir['baseurl'] . '/' . $file_path);
        
      } else {
			  $image_editor = wp_get_image_editor($file_path);        
      }
      
      if ( !is_wp_error( $image_editor ) ) {           
        $resized = $image_editor->resize($width, $height, $crop);
        $new_image = $image_editor->save($resized_image_path);
      }
      else {
        $resized = NULL;
        $new_image = array('width' => 0, 'height' => 0);        
      }
			if(class_exists("MaxGalleriaMediaLibProS3")) {
				$resized_image_url = $maxgalleria_media_library_pro_s3->upload_file_from_url($resized_image_url, $resized_image_path);
			}			
			return array('url' => $resized_image_url, 'width' => $new_image['width'], 'height' => $new_image['height']);
		}
		
		// Default output, no resizing
		return array('url' => $image_src[0], 'width' => $image_src[1], 'height' => $image_src[2]);
	}
 
  
  /* this is a copy of the wp_delete_attachment. It will delete an attachment but
   * not its attached files.
   */
  function mg_delete_attachment( $post_id, $force_delete = false ) {
    global $wpdb;

    if ( !$post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", $post_id) ) )
      return $post;

    if ( 'attachment' != $post->post_type )
      return false;

    if ( !$force_delete && EMPTY_TRASH_DAYS && MEDIA_TRASH && 'trash' != $post->post_status )
      return wp_trash_post( $post_id );

    delete_post_meta($post_id, '_wp_trash_meta_status');
    delete_post_meta($post_id, '_wp_trash_meta_time');

    $meta = wp_get_attachment_metadata( $post_id );
    $backup_sizes = get_post_meta( $post->ID, '_wp_attachment_backup_sizes', true );
    $file = get_attached_file( $post_id );

    $intermediate_sizes = array();
    foreach ( get_intermediate_image_sizes() as $size ) {
      if ( $intermediate = image_get_intermediate_size( $post_id, $size ) )
        $intermediate_sizes[] = $intermediate;
    }

    if ( is_multisite() )
      delete_transient( 'dirsize_cache' );

    /**
     * Fires before an attachment is deleted, at the start of wp_delete_attachment().
     *
     * @since 2.0.0
     *
     * @param int $post_id Attachment ID.
     */
    do_action( 'delete_attachment', $post_id );

    wp_delete_object_term_relationships($post_id, array('category', 'post_tag'));
    wp_delete_object_term_relationships($post_id, get_object_taxonomies($post->post_type));

    // Delete all for any posts.
    delete_metadata( 'post', null, '_thumbnail_id', $post_id, true );

    $comment_ids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = %d", $post_id ));
    foreach ( $comment_ids as $comment_id )
      wp_delete_comment( $comment_id, true );

    $post_meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE post_id = %d ", $post_id ));
    foreach ( $post_meta_ids as $mid )
      delete_metadata_by_mid( 'post', $mid );

    /** This action is documented in wp-includes/post.php */
    do_action( 'delete_post', $post_id );
    $result = $wpdb->delete( $wpdb->posts, array( 'ID' => $post_id ) );
    if ( ! $result ) {
      return false;
    }
    /** This action is documented in wp-includes/post.php */
    do_action( 'deleted_post', $post_id );

    clean_post_cache( $post );

    return $post;
  }
	  
	public function mg_remote_file_exists($url){
    $response = wp_remote_head($url);
    $httpCode = wp_remote_retrieve_response_code( $response );
    if( $httpCode == 200 ){return true;}
    return false;
  }
    
}
?>