<?php
class MaxGalleriaYouTube {	
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $regex_patterns;
  public $api_url;
  public $referer;

  public function __construct() {
		$this->addon_key = 'maxgalleria-youtube';
		$this->addon_name = esc_html__('YouTube', 'maxgalleria');
		$this->addon_type = 'media_source';
		$this->addon_subtype = 'video';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/media-sources/youtube/youtube-settings.php';
    $this->api_url = 'https://www.googleapis.com/youtube/v3/videos';
    if($this->is_windows()) {
			if(isset ($_SERVER['LOCAL_ADDR']))
        $this->referer = sanitize_text_field($_SERVER['LOCAL_ADDR']);
		} else {
			if(isset($_SERVER['SERVER_ADDR']))
        $this->referer = sanitize_text_field($_SERVER['SERVER_ADDR']);
		}	
    $this->initialize_properties();


		$this->regex_patterns = apply_filters(MAXGALLERIA_FILTER_YOUTUBE_REGEX_PATTERNS, array(
			'#^(http:\/\/|https:\/\/)?(www\.)?youtube\.com\/watch\?v=(.*?)(&.*?)?$#',
			'#^(http:\/\/|https:\/\/)?(www\.)?youtube\.com\/v\/(.*?)$#',
			'#^(http:\/\/|https:\/\/)?(www\.)?youtu\.be\/(.*?)$#'
		));

		// Hooks
		add_filter('maxgalleria_video_api_url', array($this, 'get_video_api_url'), 10, 2);
		add_filter('maxgalleria_video_thumb_url', array($this, 'get_video_thumb_url'), 10, 3);
		add_filter('maxgalleria_video_embed_code', array($this, 'get_video_embed_code'), 10, 6);
		add_filter('maxgalleria_video_attachment', array($this, 'get_video_attachment'), 10, 6);
		add_action('maxgalleria_video_attachment_post_meta', array($this, 'save_video_attachment_post_meta'), 10, 4);
    
		add_action('wp_ajax_save_youtube_settings', array($this, 'save_youtube_settings'));
		add_action('wp_ajax_nopriv_save_youtube_settings', array($this, 'save_youtube_settings'));
	}
  
  public function is_windows() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
      return true;
    else
      return false;      
  }
  
	public function initialize_properties() {
		require_once 'youtube-options.php';
	}
  
	public function get_video_api_url($api_url, $video_url) {
    
    $options = new MaxGalleriaYoutubeOptions();
    $key = $options->get_developer_api_key_default();
    
		if ($api_url == '') {
			if ($this->is_youtube_video($video_url)) {
				$video_id = $this->get_video_id($video_url);
        $api_url = 'https://www.googleapis.com/youtube/v3/videos?key=' . $key . '&part=snippet,contentDetails&id='  . $video_id;
			}
		}
    
		return $api_url;
	}

	public function get_video_id($video_url) {
		foreach ($this->regex_patterns as $pattern) {
			preg_match($pattern, $video_url, $matches);

			if (isset($matches[3])) {
				return $matches[3];
			}
		}

		return '';
	}

	public function get_video_thumb_url($thumb_url, $video_url, $data) {
    
    
		if ($thumb_url == '') {
			if ($this->is_youtube_video($video_url)) {
        return $data['items'][0]['snippet']['thumbnails']['high']['url'];
			}
		}
		return $thumb_url;
	}

	public function get_video_embed_code($embed_code, $video_url, $enable_related_videos, $enable_hd_playback, $width='768', $height='432') {
		if ($embed_code == '') {
			if ($this->is_youtube_video(esc_url($video_url))) {
				global $wp_embed;
				global $maxgalleria;

				// Try getting the embed code
				$embed_code = $wp_embed->run_shortcode('[embed width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"]' . esc_url($video_url) . '[/embed]');

				// If the embed code doesn't contain any embeddable elements,
				// then we need to fall back to building the embed code manually
				if (!$maxgalleria->common->string_contains_embeddable_element($embed_code)) {
					$video_id = $this->get_video_id($video_url);
					$embed_code = '<iframe src="http://www.youtube.com/embed/' . esc_attr($video_id) . '?feature=oembed" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
				}
        
        //check for SSL on the server, iso change to https
        if ( isset( $_SERVER["HTTPS"] )) {
          if ($_SERVER["HTTPS"] == "on") {
            $embed_code = str_replace('http:', 'https:', $embed_code);
          }
        }
          
				// We always add support for fullscreen mode and javascript API access on YouTube videos
				$embed_code = str_replace('feature=oembed', 'feature=oembed&fs=1&enablejsapi=1', $embed_code);

				// Check to maintain backwards compatibility for related videos
				if (!isset($enable_related_videos) || $enable_related_videos == '') {
					$enable_related_videos = 1; // True
				}

				// Check to disable related videos
				if ($enable_related_videos == 0) {
					$embed_code = str_replace('feature=oembed', 'feature=oembed&rel=0', $embed_code);
				}

				// Check to maintain backwards compatibility for HD playback
				if (!isset($enable_hd_playback) || $enable_hd_playback == '') {
					$enable_hd_playback = 0; // False
				}

				// Check to enable HD playback
				if ($enable_hd_playback == 1) {
					$embed_code = str_replace('feature=oembed', 'feature=oembed&hd=1', $embed_code);
				}
			}
		}

		return $embed_code;
	}

	public function get_video_attachment($attachment, $video_url, $gallery_id, $guid, $file_type, $data) {
		if (empty($attachment)) {
			if ($this->is_youtube_video($video_url)) {
				global $maxgalleria;

				$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);

				$attachment = array(
					'ID' => 0,
					'guid' => $guid,
					'post_title' => $data['items'][0]['snippet']['title'],
					'post_excerpt' => $data['items'][0]['snippet']['title'],
					'post_content' => $data['items'][0]['snippet']['description'],
					'post_date' => '', // Ensures it gets today's date
					'post_parent' => $gallery_id,
					'post_mime_type' => $file_type,
					'ancestors' => array(),
					'menu_order' => $menu_order
				);
			}
		}

		return $attachment;
	}

	public function save_video_attachment_post_meta($attachment_id, $video_url, $thumb_url, $data) {
		if ($this->is_youtube_video($video_url)) {
      $duration = $this->covert_duration_to_time($data['items'][0]['contentDetails']['duration']);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_url', $video_url);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_thumb_url', $thumb_url);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_id', $data['items'][0]['id']);
			update_post_meta($attachment_id, 'maxgallery_attachment_video_seconds', $duration);
			update_post_meta($attachment_id, '_wp_attachment_image_alt', $data['items'][0]['snippet']['title']);
		}
	}

	private function is_youtube_video($video_url) {
		global $maxgalleria;
		$common = $maxgalleria->common;

		if ($common->url_matches_patterns($video_url, $this->regex_patterns)) {
			return true;
		}

		return false;
	}
  
  public function save_youtube_settings() {
		$options = new MaxGalleriaYoutubeOptions();
		
		if (isset($_POST) && check_admin_referer($options->nonce_save_youtube_defaults['action'], $options->nonce_save_youtube_defaults['name'])) {
			global $maxgalleria;
			$message = '';
            
			foreach ($_POST as $key => $value) {
				if ($maxgalleria->common->string_starts_with($key, 'maxgallery_')) {        
          $value = sanitize_text_field($value);
					update_option($key, $value);
				}
			}
			
			$message = 'success';
			
			echo esc_html($message);
			die();
		}
  }
    
  public function covert_duration_to_time($youtube_time) {
    preg_match_all('/(\d+)/',$youtube_time,$parts);

    // Put in zeros if we have less than 3 numbers.
    if (count($parts[0]) == 1) {
      array_unshift($parts[0], "0", "0");
    } elseif (count($parts[0]) == 2) {
      array_unshift($parts[0], "0");
    }

    $sec_init = $parts[0][2];
    $seconds = $sec_init%60;
    $seconds_overflow = floor($sec_init/60);

    $min_init = $parts[0][1] + $seconds_overflow;
    $minutes = ($min_init)%60;
    $minutes_overflow = floor(($min_init)/60);

    $hours = $parts[0][0] + $minutes_overflow;
    
    $total = ($hours * 60 * 60) + ($minutes * 60) + $seconds;
    
    // number of seconds
    return $total;

//    if($hours != 0)
//      return $hours.':'.$minutes.':'.$seconds;
//    else
//      return $minutes.':'.$seconds;
  }    
}

?>