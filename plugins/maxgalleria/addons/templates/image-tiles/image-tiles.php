<?php
class MaxGalleriaImageTiles {
	public $addon_key;
	public $addon_name;
	public $addon_type;
	public $addon_subtype;
	public $addon_settings;
	public $addon_image;
	public $addon_output;

	public function __construct() {
		$this->addon_key = 'image-tiles';
		$this->addon_name = esc_html__('Image Tiles', 'maxgalleria');
		$this->addon_type = 'template';
		$this->addon_subtype = 'image';
		$this->addon_settings = MAXGALLERIA_PLUGIN_DIR . '/addons/templates/image-tiles/image-tiles-settings.php';
		$this->addon_image = MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/images/image-tiles.png';
		$this->addon_output = array($this, 'get_output');
		
		require_once 'image-tiles-options.php';
		
		add_action('save_post', array($this, 'save_gallery_options'));
		add_action('maxgalleria_template_options', array($this, 'show_template_options'));
		add_action('wp_ajax_save_image_tiles_defaults', array($this, 'save_image_tiles_defaults'));
		add_action('wp_ajax_nopriv_save_image_tiles_defaults', array($this, 'save_image_tiles_defaults'));
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
			
			$options = new MaxGalleriaImageTilesOptions($post->ID);
			$options->save_options();
		}
	}
	
	public function show_template_options() {
		global $post;
		$options = new MaxGalleriaImageTilesOptions($post->ID);
		
		if ($options->get_template() == 'image-tiles') {
			require_once 'image-tiles-meta.php';
		}
	}
	
	public function save_image_tiles_defaults() {
		$options = new MaxGalleriaImageTilesOptions();
		
		if (isset($_POST) && check_admin_referer($options->nonce_save_image_tiles_defaults['action'], $options->nonce_save_image_tiles_defaults['name'])) {
			global $maxgalleria;
			$message = '';
      
			foreach ($_POST as $key => $value) {
				if ($maxgalleria->common->string_starts_with($key, 'maxgallery_')) {
          
          switch($key) {
            
            case 'maxgallery_thumb_image_class_image_tiles_default':
            case 'maxgallery_thumb_image_container_class_image_tiles_default':
            case 'maxgallery_main_class_image_tiles_default':
              $value = sanitize_html_class($value);
              break;        
            
            case 'maxgallery_style_border_color_default':
            case 'maxgallery_style_shadow_color_default':
              $value = sanitize_hex_color($value);
              $value = $options->validate_hex_color($value);
              break;    
                                    
            case 'maxgallery_next_button_title_image_tiles_default':
            case 'maxgallery_prev_button_title_image_tiles_default':
            case 'maxgallery_counter_markup_image_tiles_default':
            case 'maxgallery_arrow_markup_image_tiles_default':
              $value = wp_kses_post(stripslashes($value));
              break;
            
            // validation for numberic values
            case 'maxgallery_style_border_thickness_default':
            case 'maxgallery_style_border_radius_default':
            case 'maxgallery_style_shadow_spread_default':
            case 'maxgallery_style_shadow_blur_default':
            case 'maxgallery_thumb_columns_image_tiles_default':
            case 'maxgallery_images_per_page':
            case 'maxgallery_lazy_load_threshold_image_tiles_default':
              $value = sanitize_text_field($value);
              $value = (int) $value;
              break;
                        
            // validation for checkboxes
            case 'maxgallery_lazy_load_enabled_image_tiles_default':
            case 'maxgallery_gallery_enabled_image_tiles_default':
            case 'maxgallery_lightbox_caption_enabled_image_tiles_default':
            case 'maxgallery_thumb_caption_enabled_image_tiles_default':
            case 'maxgallery_style_show_border_default':
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
		global $post;
		
		// Check to add lightbox styles
		if ($options->get_thumb_click() == 'lightbox' || $options->get_thumb_click() == 'attachment_image_link_lightbox' ) {      
			$lightbox_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/libs/topbox/css/topbox.css');
			wp_enqueue_style('maxgalleria-topbox', $lightbox_stylesheet);      
		}
		
		// The main styles for this template
		$main_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_MAIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/image-tiles.css');
		wp_enqueue_style('maxgalleria-image-tiles', $main_stylesheet);
		
		// Load skin style
		$skin = $options->get_skin();
		$skin_stylesheet = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_SKIN_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/skins/' . $skin . '.css', $skin);
		wp_enqueue_style('maxgalleria-image-tiles-skin-' . $skin, $skin_stylesheet);
		
		$mg_style_file = get_post_meta($options->gallery_id, "mg-css-file", true );
		if($mg_style_file !== '') {
		  wp_enqueue_style('maxgalleria-image-tiles-style-' . $options->gallery_id, $mg_style_file);			
		}
    
		$lightbox_skin = get_post_meta($options->gallery_id, $options->lightbox_skin_key, true );
    if($lightbox_skin != '' && $lightbox_skin != 'none') {
			wp_enqueue_style('maxgalleria-topbox-style-' . $options->gallery_id, esc_url(MAXGALLERIA_PLUGIN_URL . '/libs/topbox/skins/' . $lightbox_skin  . '/styles.css'));      
    }
						    
		// Check to load custom styles
		if ($options->get_custom_styles_enabled() == 'on' && $options->get_custom_styles_url() != '') {
			wp_enqueue_style('maxgalleria-image-tiles-custom', $options->get_custom_styles_url());
		}
    
	}

	public function enqueue_scripts($options) {
		wp_enqueue_script('jquery');
    
		if ($options->get_thumb_click() == 'lightbox' || $options->get_thumb_click() == 'attachment_image_link_lightbox' ) {
      
      $lightbox_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LIGHTBOX_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/libs/topbox/js/topbox.js');
      wp_enqueue_script('maxgalleria-topbox', $lightbox_script, array('jquery'));
                  									
			$main_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_MAIN_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/addons/templates/image-tiles/image-tiles.js');
			wp_enqueue_script('maxgalleria-image-tiles', $main_script, array('jquery'));
		}
    
		if ($options->get_lazy_load_enabled() == 'on') {
			$lazyload_script = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_LAZY_LOAD_SCRIPT, MAXGALLERIA_PLUGIN_URL . '/libs/lazyload/lazyload.min.js');
			wp_enqueue_script('maxgalleria-unveil', $lazyload_script, array('jquery'));
    }    
		
		// Check to load custom scripts
		if ($options->get_custom_scripts_enabled() == 'on' && $options->get_custom_scripts_url() != '') {
			wp_enqueue_script('maxgalleria-image-tiles-custom', $options->get_custom_scripts_url(), array('jquery'));
		}
				
	}

  // currenlty not used
	public function get_lightbox_image($options, $attachment) {
		global $maxgalleria;
		
		$lightbox_image = null;
		
		if ($options->get_lightbox_image_size() == 'custom') {
			$custom_width = $options->get_lightbox_image_size_custom_width();
			$custom_height = $options->get_lightbox_image_size_custom_height();
			$lightbox_image = $maxgalleria->image_gallery->resize_image($attachment, $custom_width, $custom_height, true);
		}
		else {
			$meta = wp_get_attachment_metadata($attachment->ID);
			$url = wp_get_attachment_url($attachment->ID); // replaces $attachment->guid
			if (is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta)) {
				$lightbox_image = array('url' => $url, 'width' => $meta['width'], 'height' => $meta['height']);
			}
			else {
				$lightbox_image = array('url' => $url, 'width' => '', 'height' => '');
			}
		}
		
		return $lightbox_image;
	}
	
	public function get_thumb_image($options, $attachment) {
		global $maxgalleria;

		$thumb_shape = $options->get_thumb_shape();
		$thumb_columns = $options->get_thumb_columns();
		
		return $maxgalleria->image_gallery->get_thumb_image($attachment, $thumb_shape, $thumb_columns);
	}
	
	public function get_output($gallery, $attachments) {
		global $maxgalleria;
    
    $gallery_attribute = '';
				
		$options = new MaxGalleriaImageTilesOptions($gallery->ID);

		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_BEFORE_ENQUEUE_STYLES, $options);
		$this->enqueue_styles($options);
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_AFTER_ENQUEUE_STYLES, $options);
		
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_BEFORE_ENQUEUE_SCRIPTS, $options);
		$this->enqueue_scripts($options);
		do_action(MAXGALLERIA_ACTION_IMAGE_TILES_AFTER_ENQUEUE_SCRIPTS, $options);
		
		$output = apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_BEFORE_GALLERY_OUTPUT, '', $gallery, $attachments, $options);
		
		$output .= '<div id="maxgallery-' . esc_attr($gallery->ID) . '" class="mg-image-tiles ' . esc_attr($options->get_skin()) . '">';		
		
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
		
		$uploads = wp_upload_dir();
    
    if($options->get_gallery_enabled() == 'on') {
      // either gallery1 or don't include the attribute
      $gallery_attribute = "data-lightbox-gallery='gallery-" . esc_attr($gallery->ID) . "'";
    }
    		
		foreach ($attachments as $attachment) {
			$excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_image_exclude', true);
			if (!$excluded) {
				$title = $attachment->post_title;
				$caption = $attachment->post_excerpt; // Used for the thumb and lightbox captions, if enabled
				$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
				$link = get_post_meta($attachment->ID, 'maxgallery_attachment_image_link', true);
				$image_class = $options->get_thumb_image_class();
				$image_container_class = $options->get_thumb_image_container_class();
				$image_rel = $options->get_thumb_image_rel_attribute();  
				$target = '';
				
//				if ($options->get_thumb_click() == 'lightbox') {
//					if ($options->get_lightbox_image_size() == 'custom') {
//						$lightbox_image = $this->get_lightbox_image($options, $attachment);
//						$href = $lightbox_image['url'];
//					}
//				}
				if(class_exists("MaxGalleriaMediaLibProS3")) 
					global $maxgalleria_media_library_pro_s3;				
				
				$href = '';
				if ($options->get_thumb_click() == 'attachment_image_page') {
					$href = get_attachment_link($attachment->ID);
				} else if ($options->get_thumb_click() == 'attachment_image_link_lightbox') {
					$href = get_post_meta($attachment->ID, 'maxgallery_attachment_image_link', true);	
					// if no link, get the original image
					if($href == '') {
						if(class_exists("MaxGalleriaMediaLibProS3")) {
							if($maxgalleria_media_library_pro_s3->s3_active && $maxgalleria_media_library_pro_s3->serve_from_s3)
							  $href = $maxgalleria_media_library_pro_s3->get_attachment_s3_url($attachment->ID);
							else
					      $href = $maxgalleria->mg_get_attachment_url($attachment, $uploads);
						} else {							
					    $href = $maxgalleria->mg_get_attachment_url($attachment, $uploads);
						}
					}	
				} else if ($options->get_thumb_click() == 'attachment_image_link') {
					if ($link != '') {
						$href = $link;
					}
				} else {
				  // Default to original, full size image
//					if(class_exists("MaxGalleriaMediaLibProS3")) {
//					  if($maxgalleria_media_library_pro_s3->s3_active && $maxgalleria_media_library_pro_s3->serve_from_s3) {
//						  $href = $maxgalleria_media_library_pro_s3->get_attachment_s3_url($attachment->ID);
//					  } else {							
//						  $href = $maxgalleria->mg_get_attachment_url($attachment, $uploads);					
//						}
//					} else {	
						$href = $maxgalleria->mg_get_attachment_url($attachment, $uploads);					
					//}
				}					
				
				if ($options->get_thumb_click_new_window() == 'on') {
          // _blank or empty
					$target = '_blank';
				}
                        
        if($options->get_lightbox_caption_enabled() == 'on')
          $image_caption = $caption;
        else
          $image_caption = "";
				
				$thumb_image = $this->get_thumb_image($options, $attachment);

		    if ($options->get_lazy_load_enabled() == 'on') { 
          if(strlen(trim($image_class))>0)
            $image_class .= ' mg_lazy';
          else        
            $image_class = 'mg_lazy';
                  
				  //$thumb_image_element = '<img class="lazy ' . esc_attr($image_class) . '" data-original="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title) . '" /><noscript><img src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" /></noscript>';
				  $thumb_image_element = '<img class="'. esc_attr($image_class) . '" data-src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title) . '" /><noscript><img src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" /></noscript>';
        }  
        else  
				  $thumb_image_element = '<img class="' . esc_attr($image_class) . '" src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title) . '" />';
				
				
				$output .= '<li>';
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'above') {
					$output .= '	<p class="caption above">' . wp_kses_post($caption) . '</p>';
				}
        																												
        if($options->get_thumb_click() !== 'no_link') {
		      if ($options->get_thumb_click() === 'lightbox' || $options->get_thumb_click() === 'attachment_image_link_lightbox' ) {
            // the lightbox does not support HTML tags 
            $output .= "	<a class='lightbox-" . esc_attr($gallery->ID)  . "' href='" . esc_url($href) .  "'  title='" . esc_attr(strip_tags($image_caption)) . "' aria-haspopup='dialog' $gallery_attribute >";            
        } else {
            if($options->get_dfactory_lightbox() == 'on') 
              $output .= "	<a  data-rel='" . esc_attr($reponsive_lb_settings) ."-0' href='" . esc_url($href) . "' target='" . $target . "' rel='" . esc_attr($image_rel) . "' title='" . esc_attr($image_caption) . "'>";
            else
              $output .= "	<a  href='" . esc_url($href) . "' target='" . $target . "' rel='" . esc_attr($image_rel) . "' title='" . esc_attr($image_caption) . "'>";
          }  
        }
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'center') {
					$output .= '	<p class="caption middle">' . wp_kses_post($caption) . '</p>';
				}
								
				$output .= '		<div class="' . esc_attr($image_container_class) . '">';
				$output .= 				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_BEFORE_THUMB, '', $options);
				$output .=				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_THUMB, $thumb_image_element, $thumb_image, $image_class, $alt, $title);
				$output .= 				apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_AFTER_THUMB, '', $options);
        
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'bottom') {
					$output .= '		<div class="caption-bottom-container">';
					$output .= '			<p class="caption bottom">' . wp_kses_post($caption) . '</p>';
					$output .= '		</div>';
				}
				
				$output .= '		</div>';
				$output .= '	</a>';
				
				if ($options->get_thumb_caption_enabled() == 'on' && $options->get_thumb_caption_position() == 'below') {
				  $output .= 		apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_BEFORE_CAPTION, '', $title);
					$output .= '	<p class="caption below">' . wp_kses_post($caption) . '</p>';
				}
				
				$output .= '</li>';
			}
		}

		$output .= '		</ul>';
		$output .= '		<div class="clear"></div>';
		
		if ($options->get_description_enabled() == 'on' && $options->get_description_position() == 'below') {
			if ($options->get_description_text() != '') {
				$output .= '<p class="mg-description">' . wp_kses_post($options->get_description_text()) . '</p>';
			}
		}

		$output .= '	</div>';
		
		// Hidden elements used by image-tiles.js
		$output .= '	<span style="display: none;" class="hidden-image-tiles-gallery-id">' . esc_attr($gallery->ID) . '</span>';
		$output .= '	<span style="display: none;" class="hidden-lightbox-skin">' . esc_html($options->get_lightbox_skin()) . '</span>';
		$output .= '	<span style="display: none;" class="hidden-lightbox-effect">' . esc_html($options->get_lightbox_effect()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-kb-nav">' . esc_html(($options->get_lightbox_kb_nav() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-img-click-close">' . esc_html(($options->get_lightbox_img_click_close() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-ol-click-close">' . esc_html(($options->get_lightbox_overlay_click_close() == 'on') ? 'true' : 'false' ) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-close-tip-text">' . esc_html($options->get_lightbox_close_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-next-tip-text">' . esc_html($options->get_lightbox_next_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-prev-tip-text">' . esc_html($options->get_lightbox_prev_text()) . '</span>';    
		$output .= '	<span style="display: none;" class="hidden-lightbox-error-tip-text">' . esc_html($options->get_lightbox_error_text()) . '</span>';    

		$output .= '</div>';
		$output .= apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_AFTER_GALLERY_OUTPUT, '', $gallery, $attachments, $options);
    
		if ($options->get_lazy_load_enabled() == 'on') {
      $output .= '<script>'. PHP_EOL;
      $output .= 'document.addEventListener("DOMContentLoaded", function(event) { '. PHP_EOL;
      $lazy_load_threshold = $options->get_lazy_load_threshold();
      if($lazy_load_threshold != '' && $lazy_load_threshold != 0)
        $output .=  'jQuery("img.mg_lazy").lazyload({threshold : 1});'. PHP_EOL;
      else
        $output .=  'jQuery("img.mg_lazy").lazyload();'. PHP_EOL;
      $output .= '});' . PHP_EOL;
      $output .= '</script>' . PHP_EOL;
    }    		
    
		return apply_filters(MAXGALLERIA_FILTER_IMAGE_TILES_GALLERY_OUTPUT, $output, $gallery, $attachments, $options);
	}
}
?>