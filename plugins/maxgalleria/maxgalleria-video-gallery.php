<?php
class MaxGalleriaVideoGallery {
	public $nonce_video_add = array(
		'action' => 'video_add',
		'name' => 'maxgalleria_video_add'
	);
	
	public $nonce_video_edit = array(
		'action' => 'video_edit',
		'name' => 'maxgalleria_video_edit'
	);
	
	public $nonce_video_edit_bulk = array(
		'action' => 'video_edit_bulk',
		'name' => 'maxgalleria_video_edit_bulk'
	);
	
	public $nonce_video_include_single = array(
		'action' => 'video_include_single',
		'name' => 'maxgalleria_video_include_single'
	);
	
	public $nonce_video_include_bulk = array(
		'action' => 'video_include_bulk',
		'name' => 'maxgalleria_video_include_bulk'
	);
	
	public $nonce_video_exclude_single = array(
		'action' => 'video_exclude_single',
		'name' => 'maxgalleria_video_exclude_single'
	);
	
	public $nonce_video_exclude_bulk = array(
		'action' => 'video_exclude_bulk',
		'name' => 'maxgalleria_video_exclude_bulk'
	);
	
	public $nonce_video_remove_single = array(
		'action' => 'video_remove_single',
		'name' => 'maxgalleria_video_remove_single'
	);
	
	public $nonce_video_remove_bulk = array(
		'action' => 'video_remove_bulk',
		'name' => 'maxgalleria_video_remove_bulk'
	);
	
	public $nonce_video_reorder = array(
		'action' => 'video_reorder',
		'name' => 'maxgalleria_video_reorder'
	);
	
	public function __construct() {
		$this->setup_hooks();
	}
	
	public function setup_hooks() {
		// Ajax call to include a single video in a gallery
		add_action('wp_ajax_include_single_video_in_gallery', array($this, 'include_single_video_in_gallery'));
		add_action('wp_ajax_nopriv_include_single_video_in_gallery', array($this, 'include_single_video_in_gallery'));

		// Ajax call to include bulk videos in a gallery
		add_action('wp_ajax_include_bulk_videos_in_gallery', array($this, 'include_bulk_videos_in_gallery'));
		add_action('wp_ajax_nopriv_include_bulk_videos_in_gallery', array($this, 'include_bulk_videos_in_gallery'));

		// Ajax call to exclude a single video from a gallery
		add_action('wp_ajax_exclude_single_video_from_gallery', array($this, 'exclude_single_video_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_single_video_from_gallery', array($this, 'exclude_single_video_from_gallery'));

		// Ajax call to exclude bulk videos from a gallery
		add_action('wp_ajax_exclude_bulk_videos_from_gallery', array($this, 'exclude_bulk_videos_from_gallery'));
		add_action('wp_ajax_nopriv_exclude_bulk_videos_from_gallery', array($this, 'exclude_bulk_videos_from_gallery'));

		// Ajax call to remove a single video from a gallery
		add_action('wp_ajax_remove_single_video_from_gallery', array($this, 'remove_single_video_from_gallery'));
		add_action('wp_ajax_nopriv_remove_single_video_from_gallery', array($this, 'remove_single_video_from_gallery'));
		
		// Ajax call to remove bulk videos from a gallery
		add_action('wp_ajax_remove_bulk_videos_from_gallery', array($this, 'remove_bulk_videos_from_gallery'));
		add_action('wp_ajax_nopriv_remove_bulk_videos_from_gallery', array($this, 'remove_bulk_videos_from_gallery'));

		// Ajax call to reorder videos
		add_action('wp_ajax_reorder_videos', array($this, 'reorder_videos'));
		add_action('wp_ajax_nopriv_reorder_videos', array($this, 'reorder_videos'));
		
		// Adds wmode=transparent to videos
		add_filter('embed_oembed_html', array($this, 'add_video_wmode_transparent'), 10, 3);
    add_filter('next_posts_link_attributes', array($this, 'posts_next_link_attributes'));
    add_filter('previous_posts_link_attributes', array($this, 'posts_previous_link_attributes'));    
    
	}
	
	public function add_video_wmode_transparent($html = "", $url = "", $attr = "") {   
		if (strpos($html, '<embed src=') !== false) {
			return str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed wmode="transparent" ', $html);
		}
		elseif (strpos($html, 'feature=oembed') !== false) {
			return str_replace('feature=oembed', 'feature=oembed&wmode=transparent', $html);
		}
		else {
			return $html;
		}
	}
	
	public function include_single_video_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_include_single['action'], $this->nonce_video_include_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$video_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($video_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_SINGLE_VIDEO_IN_GALLERY, $video_post);
					delete_post_meta($video_post->ID, 'maxgallery_attachment_video_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_SINGLE_VIDEO_IN_GALLERY, $video_post);
					
					$message = esc_html__('Included the video in this gallery.', 'maxgalleria');
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function include_bulk_videos_in_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_include_bulk['action'], $this->nonce_video_include_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'include') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_INCLUDE_BULK_VIDEOS_IN_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$video_post = get_post(sanitize_text_field($id));
						if (isset($video_post)) {
							delete_post_meta($video_post->ID, 'maxgallery_attachment_video_exclude', true);
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_INCLUDE_BULK_VIDEOS_IN_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Included 1 video in this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Included %d videos in this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function exclude_single_video_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_exclude_single['action'], $this->nonce_video_exclude_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$video_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($video_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
					update_post_meta($video_post->ID, 'maxgallery_attachment_video_exclude', true);
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
					
					$message = esc_html__('Excluded the video from this gallery.', 'maxgalleria');
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function exclude_bulk_videos_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_exclude_bulk['action'], $this->nonce_video_exclude_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'exclude') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_EXCLUDE_BULK_VIDEOS_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$video_post = get_post(sanitize_text_field($id));
						if (isset($video_post)) {
							update_post_meta($video_post->ID, 'maxgallery_attachment_video_exclude', true);
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_EXCLUDE_BULK_VIDEOS_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Excluded 1 video from this gallery.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Excluded %d videos from this gallery.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function remove_single_video_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_remove_single['action'], $this->nonce_video_remove_single['name'])) {
			$message = '';

			if (isset($_POST['id'])) {			
				$video_post = get_post(sanitize_text_field($_POST['id']));
				if (isset($video_post)) {
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
					
					$temp = array();
					$temp['ID'] = $video_post->ID;
					$temp['post_parent'] = null;
					
					wp_update_post($temp);
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
					$message = esc_html__('Removed the video from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
				}
			}
			
			echo esc_html($message);
			die();
		}
	}
	
	public function remove_bulk_videos_from_gallery() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_remove_bulk['action'], $this->nonce_video_remove_bulk['name'])) {
			$message = '';

			if (isset($_POST['media-id']) && isset($_POST['bulk-action-select'])) {
				if ($_POST['bulk-action-select'] == 'remove') {
					$count = 0;
					
					do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_BULK_VIDEOS_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					foreach ($_POST['media-id'] as $id) {
						$video_post = get_post(sanitize_text_field($id));
						if (isset($video_post)) {
							do_action(MAXGALLERIA_ACTION_BEFORE_REMOVE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
							
							$temp = array();
							$temp['ID'] = $video_post->ID;
							$temp['post_parent'] = null;
							
							wp_update_post($temp);
							do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_SINGLE_VIDEO_FROM_GALLERY, $video_post);
							
							$count++;
						}
					}
					
					do_action(MAXGALLERIA_ACTION_AFTER_REMOVE_BULK_VIDEOS_FROM_GALLERY, sanitize_text_field($_POST['media-id']));
					
					if ($count == 1) {
						$message = esc_html__('Removed 1 video from this gallery. To delete it permanently, use the Media Library.', 'maxgalleria');
					}
					
					if ($count > 1) {
						$message = sprintf(esc_html__('Removed %d videos from this gallery. To delete them permanently, use the Media Library.', 'maxgalleria'), $count);
					}
				}
			}
			
			echo esc_html($message);
			die();
		}
	}
	
	public function reorder_videos() {
		if (isset($_POST) && check_admin_referer($this->nonce_video_reorder['action'], $this->nonce_video_reorder['name'])) {
			$message = '';

			if (isset($_POST['media-order']) && isset($_POST['media-order-id'])) {		
				do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_VIDEOS_IN_GALLERY, sanitize_text_field($_POST['media-order']), sanitize_text_field($_POST['media-order-id']));
				
				for ($i = 0; $i < count($_POST['media-order']); $i++) {
					$order = sanitize_text_field($_POST['media-order'][$i]);
					$video_id = sanitize_text_field($_POST['media-order-id'][$i]);
					
					$video_post = get_post($video_id);
					if (isset($video_post)) {
						do_action(MAXGALLERIA_ACTION_BEFORE_REORDER_VIDEO_IN_GALLERY, $video_post);
						
						$temp = array();
						$temp['ID'] = $video_post->ID;
            // changed the nunber that is saved for new datatables.js
						$temp['menu_order'] = $i+1;
						//$temp['menu_order'] = $order;
						
						wp_update_post($temp);
						do_action(MAXGALLERIA_ACTION_AFTER_REORDER_VIDEO_IN_GALLERY, $video_post);
					}
				}
				
				do_action(MAXGALLERIA_ACTION_AFTER_REORDER_VIDEOS_IN_GALLERY, sanitize_text_field($_POST['media-order']), sanitize_text_field($_POST['media-order-id']));
			}
			
			echo esc_html($message);
			die();
		}
	}

	public function show_meta_box_gallery($post) {
		require_once 'meta/meta-video-gallery.php';
	}
	
	public function show_meta_box_shortcodes($post) {
		require_once 'meta/meta-shortcodes.php';
	}
  
  public function posts_next_link_attributes() {
    return 'class="mg-page-next"';
  }  

  public function posts_previous_link_attributes() {
    return 'class="mg-page-previous"';
  }  
  
}
?>