<?php
if (!defined("ABSPATH")) {
   exit;
} // Exit if accessed directly
else {
   global $wpdb;
   $lightbox_settings = $wpdb->get_results
       (
       "SELECT * FROM " . wp_lightbox_bank_settings()
   );

   if (count($lightbox_settings) != 0) {
      $lightbox_settings_keys = array();
      for ($flag = 0; $flag < count($lightbox_settings); $flag++) {
         array_push($lightbox_settings_keys, $lightbox_settings[$flag]->setting_key);
      }

      $index = array_search("wp_galleries", $lightbox_settings_keys);
      $wp_galleries = intval($lightbox_settings[$index]->setting_value);

      $index = array_search("wp_caption_image", $lightbox_settings_keys);
      $wp_caption_image = intval($lightbox_settings[$index]->setting_value);

      $index = array_search("attachment_image", $lightbox_settings_keys);
      $attachment_image = intval($lightbox_settings[$index]->setting_value);

      $index = array_search("overlay_click", $lightbox_settings_keys);
      $overlay_click = esc_attr($lightbox_settings[$index]->setting_value);

      $index = array_search("error_message", $lightbox_settings_keys);
      $error_message = esc_attr($lightbox_settings[$index]->setting_value);

      $index = array_search("image_title", $lightbox_settings_keys);
      $image_title = esc_html($lightbox_settings[$index]->setting_value);

      $index = array_search("image_caption", $lightbox_settings_keys);
      $image_caption = esc_attr($lightbox_settings[$index]->setting_value);

      $index = array_search("text_align", $lightbox_settings_keys);
      $text_align = esc_attr($lightbox_settings[$index]->setting_value);

      $index = array_search("language_direction", $lightbox_settings_keys);
      $language_direction = esc_attr($lightbox_settings[$index]->setting_value);

      $index = array_search("disable_other_lightbox", $lightbox_settings_keys);
      $disable_other_lightbox = esc_attr($lightbox_settings[$index]->setting_value);
   }
}