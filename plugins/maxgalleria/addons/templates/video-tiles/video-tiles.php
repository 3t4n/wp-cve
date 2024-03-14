<?php
class MaxGalleriaVideoTiles {
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $addon_image;
	public $addon_output;

	public function __construct() {
		$this->addon_key = 'video-tiles';
		$this->addon_name = esc_html__('Video Tiles', 'maxgalleria');
		$this->addon_type = 'template';
		$this->addon_subtype = 'video';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/templates/video-tiles/video-tiles-settings.php';
		$this->addon_image = MAXGALLERIA_PLUGIN_URL . '/addons/templates/video-tiles/images/video-tiles.png';
		$this->addon_output = array($this, 'get_output');
		
		require_once 'video-tiles-options.php';
		
		add_action('save_post', array($this, 'save_gallery_options'));
		add_action('maxgalleria_template_options', array($this, 'show_template_options'));
		add_action('wp_ajax_save_video_tiles_defaults', array($this, 'save_video_tiles_defaults'));
		add_action('wp_ajax_nopriv_save_video_tiles_defaults', array($this, 'save_video_tiles_defaults'));
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
			
			$options = new MaxGalleriaVideoTilesOptions($post->ID);
			$options->save_options();
		}
	}
	
	public function show_template_options() {
		global $post;
		$options = new MaxGalleriaVideoTilesOptions($post->ID);
		
		if ($options->get_template() == 'video-tiles') {
			require_once 'video-tiles-meta.php';
		}
	}
	
	public function save_video_tiles_defaults() {
		$options = new MaxGalleriaVideoTilesOptions();
    
		if (isset($_POST) && check_admin_referer($options->nonce_save_video_tiles_defaults['action'], $options->nonce_save_video_tiles_defaults['name'])) {
			global $maxgalleria;
			$message = '';
      
			foreach ($_POST as $key => $value) {
				if ($maxgalleria->common->string_starts_with($key, 'maxgallery_')) {
          
          //error_log($key);
          switch($key) {
            
            case 'maxgallery_thumb_image_class_video_tiles_default':
            case 'maxgallery_thumb_imagee_container_class_video_tiles_default':
            case 'maxgallery_main_class_video_tiles_default':
              $value = sanitize_html_class($value);
              break;    
            
            case 'maxgallery_v_style_border_color_default':
            case 'maxgallery_v_style_border_color_default':
              $value = sanitize_hex_color($value);
              $value = $options->validate_hex_color($value);
              break;    
                        
            case 'maxgallery_next_button_title_video_tiles_default':
            case 'maxgallery_prev_button_title_video_tiles_default':
            case 'maxgallery_counter_markup_video_tiles_default':
            case 'maxgallery_arrow_markup_video_tiles_default':
              $value = wp_kses_post(stripslashes($value));
              break;
                          
            // validation for numberic values
            case 'maxgallery_thumb_columns_video_tiles_default':
            case 'maxgallery_style_shadow_spread_default':
            case 'maxgallery_style_shadow_blur_default':
            case 'maxgallery_v_style_border_radius_default':
            case 'maxgallery_v_style_border_thickness_default':
              $value = sanitize_text_field($value);
              $value = (int) $value;
              break;
                        
            // validation for checkboxes
            case 'maxgallery_gallery_enabled_video_tiles_default':
            case 'maxgallery_thumb_caption_enabled_video_tiles_default':
            case 'maxgallery_v_style_show_border_default':
              $value = sanitize_text_field($value);
              $value = $options->on_or_off($value);
              break;
                          
            default:
              $value = sanitize_text_field($value);
              break;
          }
                    
					update_option($key, $value);
				}
			}
			
			$message = 'success';
			
			echo esc_html($message);
			die();
		}
	}
	
	public function enqueue_styles($options) {
		// Check to add lightbox styles
		if ($options->get_thumb_click() == 'lightbox') {
      
			$lightbox_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/libs/topbox/css/topbox.css');
			wp_enqueue_style('maxgalleria-topbox', $lightbox_stylesheet);      
          
		}
		
		// The main styles for this template
		$main_stylesheet = apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_MAIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/video-tiles/video-tiles.css');
		wp_enqueue_style('maxgalleria-video-tiles', $main_stylesheet);
		
		// Load skin style
		$skin = $options->get_skin();
		$skin_stylesheet = apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_SKIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/video-tiles/skins/' . $skin . '.css', $skin);
		wp_enqueue_style('maxgalleria-video-tiles-skin-' . $skin, $skin_stylesheet);
		
		$mg_style_file = get_post_meta($options->gallery_id, "mg-css-file", true );
		if($mg_style_file !== '') {
		  wp_enqueue_style('maxgalleria-video-tiles-style-' . $options->gallery_id, $mg_style_file);			
		}
    
		$lightbox_skin = get_post_meta($options->gallery_id, $options->lightbox_skin_key, true );
    if($lightbox_skin != '' && $lightbox_skin != 'none') {
			wp_enqueue_style('maxgalleria-topbox-style-' . $options->gallery_id, esc_url(MAXGALLERIA_PLUGIN_URL . '/libs/topbox/skins/' . $lightbox_skin  . '/styles.css'));      
    }
    		
		// Check to load custom styles
		if ($options->get_custom_styles_enabled() == 'on' && $options->get_custom_styles_url() != '') {
			wp_enqueue_style('maxgalleria-video-tiles-custom', $options->get_custom_styles_url());
		}
	}

	public function enqueue_scripts($options) {
		wp_enqueue_script('jquery');
    		
		if ($options->get_thumb_click() == 'lightbox') {
      
      $lightbox_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/libs/topbox/js/topbox.js');
      wp_enqueue_script('maxgalleria-topbox', $lightbox_script, array('jquery'));
      			
			$main_script = apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_MAIN_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/addons/templates/video-tiles/video-tiles.js');
			wp_enqueue_script('maxgalleria-video-tiles', $main_script, array('jquery'));
		}
		
		// Check to load custom scripts
		if ($options->get_custom_scripts_enabled() == 'on' && $options->get_custom_scripts_url() != '') {
			wp_enqueue_script('maxgalleria-video-tiles-custom', $options->get_custom_scripts_url(), array('jquery'));
		}
	}
	
	public function get_thumb_image($options, $attachment) {
		global $maxgalleria;

		$thumb_shape = $options->get_thumb_shape();
		$thumb_columns = $options->get_thumb_columns();
		
		return $maxgalleria->image_gallery->get_thumb_image($attachment, $thumb_shape, $thumb_columns);
	}
	
	public function get_output($gallery, $attachments) {
    
		$options = new MaxGalleriaVideoTilesOptions($gallery->ID);
    
    $gallery_attribute = '';    
    
		do_action(MAXGALLERIA_ACTION_VIDEO_TILES_BEFORE_ENQUEUE_STYLES, $options);
		$this->enqueue_styles($options);
		do_action(MAXGALLERIA_ACTION_VIDEO_TILES_AFTER_ENQUEUE_STYLES, $options);
		
		do_action(MAXGALLERIA_ACTION_VIDEO_TILES_BEFORE_ENQUEUE_SCRIPTS, $options);
		$this->enqueue_scripts($options);
		do_action(MAXGALLERIA_ACTION_VIDEO_TILES_AFTER_ENQUEUE_SCRIPTS, $options);
		
		$output = wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_BEFORE_GALLERY_OUTPUT, '', $gallery, $attachments, $options));
		$output .= '<div id="maxgallery-' . esc_attr($gallery->ID) . '" class="mg-video-tiles ' . esc_attr($options->get_skin()) . '">';
		
		if ($options->get_description_enabled() == 'on' && $options->get_description_position() == 'above') {
			if ($options->get_description_text() != '') {
				$output .= '<p class="mg-description">' . wp_kses_post($options->get_description_text()) . '</p>';
			}
		}
    
    // check for dfactory selector
    if($options->get_dfactory_lightbox() == 'on') {
      $dfactory_settings = get_option('responsive_lightbox_settings');
      if(!empty($dfactory_settings)) 
        $reponsive_lb_settings = $dfactory_settings['selector'];        
      else
        $reponsive_lb_settings = "";
    }
    		
		$output .= '	<div class="mg-thumbs ' . esc_attr($options->get_thumb_columns_class()) . '">';
		$output .= '		<ul>';
    
    if($options->get_gallery_enabled() == 'on') {
      // either gallery1 or don't include the attribute
      $gallery_attribute = "data-lightbox-gallery='gallery-" . esc_attr($gallery->ID) . "'";
    }
    
		foreach ($attachments as $attachment) {
			$excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_video_exclude', true);
			if (!$excluded) {
				$title = $attachment->post_title;
				$caption = $attachment->post_excerpt; // Used for the thumb caption, if enabled
				$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
				$href = get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true);
				$image_class = $options->get_thumb_image_class();
				$image_container_class = $options->get_thumb_image_container_class();
				$image_rel = $options->get_thumb_image_rel_attribute();
				$target = '';
								
				if ($options->get_thumb_click_new_window() == 'on') {
          // either _blank or empty
					$target = '_blank';
				}
        
        if($options->get_lightbox_caption_enabled() == 'on')
          $image_caption = $caption;
        else
          $image_caption = "";        
				
				$thumb_image = $this->get_thumb_image($options, $attachment);
				$thumb_image_element = '<img class="' . esc_attr($image_class) . '" src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title) . '" />';
				        
				$output .= '<li>';
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'above') {
					$output .= '	<p class="caption above">' . wp_kses_post($caption) . '</p>';
				}
        				        
        if($options->get_dfactory_lightbox() == 'on') 
				  $output .= '	<a data-rel="' . esc_attr($reponsive_lb_settings) . '-0" class="video" data-video-thumb-id="' . esc_attr($attachment->ID) . '" href="' . esc_url($href) . '" target="' . $target . '" rel="' . esc_attr($image_rel) . '">';
        else {
          // reformat youtube urls
          $id_pos = strpos($href, '?v=');
          if($id_pos !== false) {
            $href = "https://youtu.be/" . substr($href, $id_pos + 3);
          }
          // the lightbox does not support HTML taga
          $output .= '	<a class="video-lightbox-'. esc_attr($gallery->ID)  .'" href="' . esc_url($href) . '" title="'. esc_attr(strip_tags($image_caption)) .'" aria-haspopup="dialog" ' . $gallery_attribute . ' >';
        }  
          
				$output .= '		<div class="' . esc_attr($image_container_class) . '">';
				$output .= 				wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_BEFORE_THUMB, '', $options));
				$output .=				wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_THUMB, $thumb_image_element, $thumb_image, $image_class, $alt, $title));
				$output .= 				wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_AFTER_THUMB, '', $options));
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'bottom') {
					$output .= '		<div class="caption-bottom-container">';
					$output .= '			<p class="caption bottom">' . wp_kses_post($caption) . '</p>';
					$output .= '		</div>';
				}
				
				$output .= '		</div>';
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'below') {
					$output .= '<p class="caption below">' . wp_kses_post($caption) . '</p>';
				}
				
				$output .= '	</a>';
				$output .= '</li>';
			}
		}

		$output .= '		</ul>';
		$output .= '		<div class="clear"></div>';
		$output .= '	</div>';
		
		if ($options->get_description_enabled() == 'on' && $options->get_description_position() == 'below') {
			if ($options->get_description_text() != '') {
				$output .= '<p class="mg-description">' . wp_kses_post($options->get_description_text()) . '</p>';
			}
		}
        
		// Hidden elements used by video-tiles.js
		$output .= '	<span style="display: none;" class="hidden-video-tiles-gallery-id">' . esc_attr($gallery->ID) . '</span>';
		$output .= '	<span style="display: none;" class="hidden-video-tiles-thumb-click">' . esc_attr($options->get_thumb_click()) . '</span>';
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-skin">' . esc_html($options->get_lightbox_skin()) . '</span>';
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-effect">' . esc_html($options->get_lightbox_effect()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-kb-nav">' . esc_html(($options->get_lightbox_kb_nav() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-img-click-close">' . esc_html(($options->get_lightbox_img_click_close() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-ol-click-close">' . esc_html(($options->get_lightbox_overlay_click_close() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-close-tip-text">' . esc_html($options->get_lightbox_close_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-next-tip-text">' . esc_html($options->get_lightbox_next_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-prev-tip-text">' . esc_html($options->get_lightbox_prev_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-video-tiles-lightbox-error-tip-text">' . esc_html($options->get_lightbox_error_text()) . '</span>';    

		$output .= '</div>';
		$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_AFTER_GALLERY_OUTPUT, '', $gallery, $attachments, $options));
		
		return wp_kses_post(apply_filters(MAXGALLERIA_FILTER_VIDEO_TILES_GALLERY_OUTPUT, $output, $gallery, $attachments, $options));
	}
}
?>