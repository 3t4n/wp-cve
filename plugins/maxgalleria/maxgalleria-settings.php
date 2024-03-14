<?php
class MaxGalleriaSettings {
  
	public $nonce_save_general_settings = array(
		'action' => 'save_general_settings',
		'name' => 'maxgalleria_save_general_settings'
	);
	
	public function __construct() {
		// Ajax call for saving general settings
		add_action('wp_ajax_save_general_settings', array($this, 'save_general_settings'));
		add_action('wp_ajax_nopriv_save_general_settings', array($this, 'save_general_settings'));
	}

	public function get_rewrite_slug() {
		// Get the rewrite slug, with 'gallery' as the default
		return get_option(MAXGALLERIA_SETTING_REWRITE_SLUG, 'gallery');
	}
	
	public function get_exclude_galleries_from_search() {
		return get_option(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH, '');
	}
	
	public function get_show_addon_page() {
		return get_option(MAXGALLERIA_SETTING_SHOW_ADDON_PAGE, '');
	}
		
	public function get_default_image_gallery_template() {
		return get_option(MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE, 'image-tiles');
	}
	
	public function get_default_video_gallery_template() {
		return get_option(MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE, 'video-tiles');
	}
	
	public function save_general_settings() {
		if (isset($_POST) && check_admin_referer($this->nonce_save_general_settings['action'], $this->nonce_save_general_settings['name'])) {
			$message = '';
			
			if (isset($_POST[MAXGALLERIA_SETTING_REWRITE_SLUG]) && $_POST[MAXGALLERIA_SETTING_REWRITE_SLUG] != '') {
        $rewrite_slug = trim(sanitize_text_field($_POST[MAXGALLERIA_SETTING_REWRITE_SLUG]));				
				update_option(MAXGALLERIA_SETTING_REWRITE_SLUG, $rewrite_slug);
			}
			else {
				update_option(MAXGALLERIA_SETTING_REWRITE_SLUG, 'gallery');
			}
			
			if (isset($_POST[MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH]) && $_POST[MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH] == 'on') {
				update_option(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH, sanitize_text_field($_POST[MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH]));
			}
			else {
				update_option(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH, '');
			}
			
			if (isset($_POST[MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE]) && $_POST[MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE] != '') {
				update_option(MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE, sanitize_text_field($_POST[MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE]));
			}
			else {
				update_option(MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE, 'image-tiles');
			}
			
			if (isset($_POST[MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE]) && $_POST[MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE] != '') {
				update_option(MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE, sanitize_text_field($_POST[MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE]));
			}
			else {
				update_option(MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE, 'video-tiles');
			}
			
			if (isset($_POST[MAXGALLERIA_SETTING_SHOW_ADDON_PAGE]) && $_POST[MAXGALLERIA_SETTING_SHOW_ADDON_PAGE] != '') {
				update_option(MAXGALLERIA_SETTING_SHOW_ADDON_PAGE, sanitize_text_field($_POST[MAXGALLERIA_SETTING_SHOW_ADDON_PAGE]));
			}
			else {
				update_option(MAXGALLERIA_SETTING_SHOW_ADDON_PAGE, 'off');
			}
						
			$message = 'success';
			echo esc_html($message);
			die();
		}
	}
}
?>