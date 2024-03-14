<?php
class MaxGalleriaMeta {
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'add_gallery_meta_boxes'));
		add_action('save_post', array($this, 'save_gallery_options'));
		
    add_action('wp_ajax_nopriv_mg_hide_template_ad', array($this, 'mg_hide_template_ad'));
    add_action('wp_ajax_mg_hide_template_ad', array($this, 'mg_hide_template_ad'));
			
    add_action('wp_ajax_nopriv_mg_hide_gallery_ad', array($this, 'mg_hide_gallery_ad'));
    add_action('wp_ajax_mg_hide_gallery_ad', array($this, 'mg_hide_gallery_ad'));
		
    add_action( 'admin_enqueue_scripts', array($this, 'load_mg_wp_admin_script'));		
	}
	
	public function load_mg_wp_admin_script() {
		
		wp_enqueue_script('jquery');
		
	}
	
	public function add_gallery_meta_boxes() {
		global $post;
		global $maxgalleria;
		
		$new_gallery = $maxgalleria->new_gallery;
		$image_gallery = $maxgalleria->image_gallery;
		$video_gallery = $maxgalleria->video_gallery;
		
		if (isset($post)) {
			$options = new MaxGalleryOptions($post->ID);
			
			if ($options->is_new_gallery()) {
				$this->add_normal_meta_box('meta-new', esc_html__('New Gallery', 'maxgalleria'), array($new_gallery, 'show_meta_box_new'));
			}
			
			if ($options->is_image_gallery()) {
				$this->add_side_meta_box('meta-shortcodes', esc_html__('Shortcodes', 'maxgalleria'), array($image_gallery, 'show_meta_box_shortcodes'));
				// save the gallery ID to return to the gallery by the admin bar			
				update_option( 'mg_current_gallery', $post->ID, false );
				
				
				// Only show if a template has been chosen
				if ($options->get_template() != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_TEMPLATE_META_BOXES);
					$this->add_normal_meta_box('meta-image-gallery', esc_html__('Gallery', 'maxgalleria'), array($image_gallery, 'show_meta_box_gallery'));
					do_action(MAXGALLERIA_ACTION_AFTER_TEMPLATE_META_BOXES);
				}				
			}
			
			if ($options->is_video_gallery()) {
				$this->add_side_meta_box('meta-shortcodes', esc_html__('Shortcodes', 'maxgalleria'), array($video_gallery, 'show_meta_box_shortcodes'));
				// save the gallery ID to return to the gallery by the admin bar			
				update_option( 'mg_current_gallery', $post->ID, false );
				
				// Only show if a template has been chosen
				if ($options->get_template() != '') {
					do_action(MAXGALLERIA_ACTION_BEFORE_TEMPLATE_META_BOXES);
					$this->add_normal_meta_box('meta-video-gallery', esc_html__('Gallery', 'maxgalleria'), array($video_gallery, 'show_meta_box_gallery'));
					do_action(MAXGALLERIA_ACTION_AFTER_TEMPLATE_META_BOXES);
				}
			}
		}
	}
	
	public function add_side_meta_box($id, $title, $callback) {
		$id = $id;
		$title = $title;
		$callback = $callback;
		$post_type = MAXGALLERIA_POST_TYPE;
		$context = 'side';
		$priority = 'default';
		add_meta_box($id, $title, $callback, $post_type, $context, $priority);
	}

	public function add_normal_meta_box($id, $title, $callback) {
		$id = $id;
		$title = $title;
		$callback = $callback;
		$post_type = MAXGALLERIA_POST_TYPE;
		$context = 'normal';
		$priority = 'high';
		add_meta_box($id, $title, $callback, $post_type, $context, $priority);
	}
	
	public function save_gallery_options() {
		global $post;

		if (isset($post)) {
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post->ID;
			}

			if (!current_user_can('edit_post', $post->ID)) {
				return $post->ID;
			}
			
			$options = new MaxGalleryOptions($post->ID);
			$options->save_options();
		}
	}
	
	public function mg_hide_template_ad() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MG_META_NONCE)) {
      exit(esc_html__('missing nonce!','maxgalleria'));
    } 
		
    update_option('show_template_ad', "off");
		
		die();
	}
	
	public function mg_hide_gallery_ad() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MG_META_NONCE)) {
      exit(esc_html__('missing nonce!','maxgalleria'));
    } 
		
    update_option('show_gallery_ad', "off");
		
		die();
	}
	
}

?>