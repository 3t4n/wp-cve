<?php

if(!class_exists('MaxGalleryOptions')) {
  
  class MaxGalleryOptions {
    private $_post_id;

    public function __construct($post_id) {
      $this->_post_id = $post_id;
    }

    public function get_post_id() {
      return $this->_post_id;
    }

    public function get_post_meta($meta_key) {
      return get_post_meta($this->get_post_id(), $meta_key, true);
    }

    public function delete_post_meta($meta_key) {
      delete_post_meta($this->get_post_id(), $meta_key);
    }

    public function save_post_meta($meta_key) {
      $post_id = $this->get_post_id();

      $meta_old_value = get_post_meta($post_id, $meta_key, true);
      $meta_new_value = isset($_POST[$meta_key]) ? sanitize_text_field(($_POST[$meta_key])) : '';

      // If the option is the saves count, we need to check if we have a template value. If we do, then
      // we increment the saves count; otherwise, we reset the saves count back to -1 to ensure gallery
      // options get their proper default values.
      if ($meta_key == $this->saves_count_key) {
        $meta_new_value = ($this->get_template() != '') ? ((int)$meta_new_value) + 1 : -1;
      }
      
      //error_log("$meta_key $meta_new_value");
      switch($meta_key) {

        case 'maxgallery_main_class_image_sc':
        case 'maxgallery_grid_thumb_image_class':
        case 'maxgallery_grid_main_class':
        case 'maxgallery_main_class':
        case 'maxgallery_thumb_image_container_class':
        case 'maxgallery_thumb_image_class':
          $meta_new_value = sanitize_html_class($meta_new_value);
          break;

        case 'maxgallery_md_grid_text_color':
        case 'maxgallery_md_grid_text_bg_color':
        case 'maxgallery_md_card_action_text_color':
        case 'maxgallery_md_card_bg_color':
        case 'maxgallery_vsc_style_shadow_color_enabled':
        case 'maxgallery_vsc_style_border_color_enabled':
        case 'maxgallery_slick_style_shadow_color_enabled':
        case 'maxgallery_slick_style_border_color_enabled':
        case 'maxgallery_slider_style_shadow_color_enabled':
        case 'maxgallery_slider_style_border_color_enabled':
        case 'maxgallery_isc_style_shadow_color_enabled':
        case 'maxgallery_isc_style_border_color_enabled':
        case 'maxgallery_ic_style_shadow_color_enabled':
        case 'maxgallery_ic_style_border_color_enabled':
        case 'maxgallery_style_shadow_color_enabled':
        case 'maxgallery_style_border_color_enabled':
        case 'maxgallery_vsc_style_border_color_enabled':
        case 'maxgallery_vsc_style_shadow_color_enabled':
          $meta_new_value = sanitize_hex_color($meta_new_value);
          $meta_new_value = $this->validate_hex_color($meta_new_value);
          break;        

        case 'maxgallery_counter_markup_image_sc':
        case 'maxgallery_next_button_title_image_sc':
        case 'maxgallery_prev_button_title_image_sc':
        case 'maxgallery_arrow_markup_image_sc':          
        case 'maxgallery_grid_counter_markup':
        case 'maxgallery_grid_next_button_title':
        case 'maxgallery_grid_prev_button_title':
        case 'maxgallery_grid_arrow_markup':
        case 'maxgallery_description_text':
        case 'maxgallery_counter_markup':
        case 'maxgallery_next_button_title':
        case 'maxgallery_prev_button_title':
        case 'maxgallery_arrow_markup':
          $meta_new_value = wp_kses_post($meta_new_value);
          break;

        case 'maxgallery_slick_right_arrow':
        case 'maxgallery_slick_left_arrow':
        case 'maxgallery_slick_dot':
        case 'maxgallery_custom_scripts_url':
        case 'maxgallery_custom_styles_url':
          $meta_new_value = esc_url_raw($meta_new_value);
          break;

        case 'maxgallery_slick_custom_css';
          $meta_new_value = strip_tags($meta_new_value);
          $meta_new_value = htmlspecialchars($meta_new_value, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');            
          break;

        case 'maxgallery_slider_breakpoints':
          //error_log("breakpoint 1 $meta_new_value");
          $meta_new_value = sanitize_textarea_field($meta_new_value);
          //error_log("breakpoint 2 $meta_new_value");
          $meta_new_value = $this->validate_breakpoints($meta_new_value);
          //error_log("breakpoint 3 $meta_new_value");
          break;

        // validation for numberic values
        case 'maxgallery_videos_per_page':
        case 'maxgallery_style_shadow_spread_enabled':
        case 'maxgallery_style_shadow_blur_enabled':
        case 'maxgallery_style_shadow_enabled':
        case 'maxgallery_style_border_radius_enabled':
        case 'maxgallery_style_border_thickness_enabled':
        case 'maxgallery_lazy_load_threshold':
        case 'maxgallery_images_per_page':
        case 'maxgallery_thumb_columns':
          $meta_new_value = sanitize_text_field($meta_new_value);
          $meta_new_value = (int) $meta_new_value;
          break;
        
        // validation for checkboxes
        case 'maxgallery_thumb_click_new_window';
        case 'maxgallery_thumb_caption_enabled':
        case 'maxgallery_v_style_show_border_enabled':
        case 'maxgallery_lazy_load_enabled':
        case 'maxgallery_gallery_enabled':
        case 'maxgallery_lightbox_caption_enabled':
        case 'maxgallery_thumb_click_new_window':
        case 'maxgallery_thumb_caption_enabled';
        case 'maxgallery_style_show_border_enabled':          
        case 'maxgallery_lightbox_kb_nav_image_tiles':
        case 'maxgallery_lightbox_img_click_close_image_tiles':
        case 'maxgallery_lightbox_ol_click_close_image_tiles':
          $meta_new_value = sanitize_text_field($meta_new_value);
          $meta_new_value = $this->on_or_off($meta_new_value);
          break;
        
        default:
          $meta_new_value = sanitize_text_field($meta_new_value);
          break;
      }
      
     
      update_post_meta($post_id, $meta_key, $meta_new_value, $meta_old_value);
    }

    // These options are common to all templates
    public $custom_scripts_enabled_default = '';
    public $custom_scripts_enabled_key = 'maxgallery_custom_scripts_enabled';
    public $custom_scripts_url_key = 'maxgallery_custom_scripts_url';
    public $custom_styles_enabled_default = '';
    public $custom_styles_enabled_key = 'maxgallery_custom_styles_enabled';
    public $custom_styles_url_key = 'maxgallery_custom_styles_url';
    public $description_enabled_default = '';
    public $description_enabled_key = 'maxgallery_description_enabled';
    public $description_position_default = 'above';
    public $description_position_key = 'maxgallery_description_position';
    public $description_text_key = 'maxgallery_description_text';
    public $reset_options_default = '';
    public $reset_options_key = 'maxgallery_reset_options';
    public $saves_count_default = -1;
    public $saves_count_key = 'maxgallery_saves_count';
    public $template_key = 'maxgallery_template';
    public $type_key = 'maxgallery_type';

    public function get_custom_scripts_enabled() {
      $value = $this->get_post_meta($this->custom_scripts_enabled_key); 
      if ($value == '') {
        $value = $this->custom_scripts_enabled_default;
      }

      return $value;
    }

    public function get_custom_scripts_url() {
      return $this->get_post_meta($this->custom_scripts_url_key);
    }

    public function get_custom_styles_enabled() {
      $value = $this->get_post_meta($this->custom_styles_enabled_key); 
      if ($value == '') {
        $value = $this->custom_styles_enabled_default;
      }

      return $value;
    }

    public function get_custom_styles_url() {
      return $this->get_post_meta($this->custom_styles_url_key);
    }

    public function get_description_enabled() {
      $value = $this->get_post_meta($this->description_enabled_key); 
      if ($value == '') {
        $value = $this->description_enabled_default;
      }

      return $value;
    }

    public function get_description_position() {
      $value = $this->get_post_meta($this->description_position_key);
      if ($value == '') {
        $value = $this->description_position_default;
      }

      return $value;
    }

    public function get_description_text() {
      return $this->get_post_meta($this->description_text_key);
    }

    public function get_reset_options() {
      $value = $this->get_post_meta($this->reset_options_key); 
      if ($value == '') {
        $value = $this->reset_options_default;
      }

      return $value;
    }

    public function get_saves_count() {
      $value = $this->get_post_meta($this->saves_count_key); 
      if ($value == '') {
        $value = $this->saves_count_default;
      }

      return $value;
    }

    public function get_template() {
      return $this->get_post_meta($this->template_key);
    }

    public function get_type() {
      return $this->get_post_meta($this->type_key);
    }

    public function is_new_gallery() {
      // Use get_post_meta() instead of get_type() because get_type() will return
      // the default if it's an empty string, but we want to know if it's actually
      // an empty string to know if this is a new gallery or not.
      return ($this->get_post_meta($this->type_key) == '') ? true : false;
    }

    public function is_image_gallery() {
      return ($this->get_type() == 'image' || $this->get_type() == 'both') ? true : false;
    }

    public function is_video_gallery() {
      return ($this->get_type() == 'video' || $this->get_type() == 'both') ? true : false;
    }

    public function is_reset_options() {
      if (isset($_POST[$this->reset_options_key]) && sanitize_text_field($_POST[$this->reset_options_key]) == 'on') {
        return true;
      }

      return false;
    }

    public function save_options($options = null) {
      
      if ($this->is_new_gallery()) {
        $this->save_post_meta($this->type_key);
      }
      else {
        // Get the base options and merge in the options that were passed in, if any
        $base_options = $this->base_options();
        $all_options = isset($options) ? array_merge($base_options, $options) : $base_options;

        foreach ($all_options as $option) {
          if ($this->is_reset_options()) {
            // Check to reset saves count back to 0 (instead of deleting it)
            if ($option == $this->saves_count_key) {
              update_post_meta($this->get_post_id(), $option, 0);
            }
            elseif ($option != $this->template_key) { // Don't reset the template
              $this->delete_post_meta($option);
            }
          }
          else {
            $this->save_post_meta($option);
          }
        }
      }
    }

    private function base_options() {
      return array(
        $this->template_key, // IMPORTANT: MUST ALWAYS COME FIRST
        $this->custom_scripts_enabled_key,
        $this->custom_scripts_url_key,
        $this->custom_styles_enabled_key,
        $this->custom_styles_url_key,
        $this->description_enabled_key,
        $this->description_position_key,
        $this->description_text_key,
        $this->saves_count_key
      );
    }
    
    public function validate_hex_color($color) {
      if(preg_match('/^#[a-f0-9]{6}$/i', $color)) //hex color is valid
        return $color;
      else  
        return '';
    }
    
    public function on_or_off($input) {
      if($input === 'on' || $input === 'off' || $input == '' ) 
        return $input;
      else
        return '';        
    }
        
    public function validate_breakpoints($input) {
      if(preg_match('/\[\{\s+[a-zA-Z]+:.*[0-9]+,.*\}/', $input))
        return $input;
      else
        return '';    
    }  
  }  
}
?>