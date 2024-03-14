<?php
require_once('feed-settings.php');

GFForms::include_feed_addon_framework();
 
class ACGF_PostUpdateAddOn extends GFFeedAddOn {
  use ACGF_PostUpdateAddon_FeedSettings;

  protected $_version = ACGF_POST_UPDATE_ADDON_VERSION;
  // Earlier versions maybe supported but not tested
  protected $_min_gravityforms_version = '2.5';
  protected $_slug = 'post-update-addon-gravity-forms';
  protected $_path = 'post-update-addon-gravity-forms/post-update-addon-gravity-forms.php';
  protected $_full_path = __FILE__;
  protected $_title = 'Post Update Add-On';
  protected $_short_title = 'Post Update';

  private static $_instance = null;
               
  public static function get_instance() {
    if(self::$_instance == null) {
      self::$_instance = new ACGF_PostUpdateAddOn();
    }
    return self::$_instance;
  }
               
  public function init() {
    parent::init();
  }

  public function feed_list_columns() {
    return array(
      'feedName' => __('Name', $this->_slug)
    );
  }

  public function get_menu_icon() {
    return 'dashicons-welcome-write-blog';
  }

  public function process_feed($feed, $entry, $form) {
    $this->log_debug(__METHOD__ . '(): Start feed processing');

    $raw_post_id = rgars($feed, 'meta/post_id');
    $raw_post_id = trim($raw_post_id);

    // get current post id
    $current_post_id = get_the_ID();
    if($current_post_id !== false) {
      $raw_post_id = str_replace('{current_post_id}', $current_post_id, $raw_post_id);
    }

    // replacing merge tags
    $raw_post_id = GFCommon::replace_variables($raw_post_id, $form, $entry, false, false, false);
    if($raw_post_id == '') {
      $this->log_debug(__METHOD__ . sprintf('(): After processing merge tags Post ID is an empty string. Cancelling feed processing.'));
      return;
    }

    $post_id = intval($raw_post_id);
    $this->log_debug(__METHOD__ . sprintf('(): Provided Post ID "%d"', $post_id));
  
    $postarr = array(
      'ID' => $post_id
    );

    // Preparing standard post fields
    $this->prepare_author_id($feed, $entry, $form, $postarr);
    $this->prepare_post_status($feed, $entry, $form, $postarr);
    $this->prepare_post_title($feed, $entry, $form, $postarr);
    $this->prepare_post_content($feed, $entry, $form, $postarr);

    // Updating standard post fields
    $this->process_standard_post_fields($postarr);
    $this->process_featured_image($feed, $entry, $post_id);

    // Updating taxonomies
    $this->process_taxonomy($feed, $entry, 'category', $post_id);
    $this->process_taxonomy($feed, $entry, 'post_tag', $post_id);
    $this->process_custom_taxonomies($feed, $entry, $post_id);

    // Updating meta fields
    $this->process_meta_fields($feed, $entry, $post_id);
  }

  function process_standard_post_fields($postarr) {
    $this->log_debug(__METHOD__ . sprintf('(): Starting post update'));
    $result = wp_update_post($postarr, $wp_error = true);
    if(is_wp_error($result)) {
      $this->log_debug(__METHOD__ . sprintf('(): ERROR: Can\'t update the post - "%s"', $result->get_error_message()));
      return;
    }
  }

  function process_featured_image($feed, $entry, $post_id) {
    $this->log_debug(__METHOD__ . sprintf('(): Starting featured image update'));
    $featured_image_field_id = rgars($feed, 'meta/featured_image_field');
    $featured_image_allow_empty = rgars($feed, 'meta/allow_empty_featured_image');
    //var_dump($featured_image_allow_empty);
    if($featured_image_field_id === '') return;
    
    $new_featured_image = rgar($entry, $featured_image_field_id);
    //var_dump($new_featured_image); 
    if($new_featured_image === '') {
      if($featured_image_allow_empty !== '1') return;
      delete_post_thumbnail($post_id);
      return;
    }
    $upload_path = GFFormsModel::get_upload_path($entry['form_id']);
    $upload_url = GFFormsModel::get_upload_url($entry['form_id']);
    $file_path = str_replace($upload_url, $upload_path, $new_featured_image);

    $filename = basename($file_path);

    $upload_file = wp_upload_bits($filename, null, file_get_contents($file_path));
    if(!$upload_file['error']) {
      $wp_filetype = wp_check_filetype($filename, null );
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_parent' => $post_id,
        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );

      $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
      if(!is_wp_error($attachment_id)) {
        $this->log_debug(__METHOD__ . sprintf('(): New featured image id: %d', $attachment_id));
        require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        set_post_thumbnail($post_id, $attachment_id);
      }
    } else {
      $this->log_debug(__METHOD__ . '(): Can\'t add new featured image: ' . $upload_file['error']);
    }
  }

  function process_meta_fields($feed, $entry, $post_id) {
    $this->log_debug(__METHOD__ . sprintf('(): Starting meta fields (custom fields) update'));
    $update_non_empty_meta_fields_only = rgars($feed, 'meta/update_non_empty_meta_fields') === '1';
    $metaMap = $this->get_dynamic_field_map_fields($feed, 'meta_field_map');
    foreach($metaMap as $target_meta_key => $source_field_id) {
      // target meta key can contain "type" like <post_id>, extracting it
      if(strpos($target_meta_key, '<') !== false) {
        $target_meta_key_name = substr($target_meta_key, 0, strpos($target_meta_key, '<'));
        $target_meta_key_type = substr($target_meta_key, strpos($target_meta_key, '<') + 1, -1);
      } else {
        $target_meta_key_name = $target_meta_key;
        $target_meta_key_type = 'string';
      }
      $form_field_value = '';
      if(array_key_exists($source_field_id . '.1', $entry)) {
        // this is composite GF value, checkbox or similar
        $form_field_value = array();
        foreach($entry as $key => $value) {
          if($value === '') continue; // non empty values
          if(strpos($key, $source_field_id . '.') !== 0) continue; // that belong to our field
          array_push($form_field_value, rgar($entry, $key));
        }
      } else {
        // this is just a plain value
        $form_field_value = rgar($entry, $source_field_id);
        // type matching
        if($target_meta_key_type === 'post_id' && !is_numeric($form_field_value)) {
          if(filter_var($form_field_value, FILTER_VALIDATE_URL) !== false) {
            $attachment_id = attachment_url_to_postid($form_field_value);
            if($attachment_id) $form_field_value = $attachment_id;
          }
        }
      }
      if($update_non_empty_meta_fields_only && $form_field_value === '') continue;
      update_post_meta($post_id, $target_meta_key_name, $form_field_value);
    }
  }

  function process_taxonomy($feed, $entry, $taxonomy_name, $post_id) {
    $tax_field_id = rgars($feed, 'meta/' . $taxonomy_name . '_tax_settings_field');
    // Checking if the field configured
    if($tax_field_id === '') return;

    $tax_mode = rgars($feed, 'meta/' . $taxonomy_name . '_tax_settings_mode');
    $new_tax_value = trim(rgar($entry, $tax_field_id));
    if($new_tax_value === '' && $tax_mode === 'override_not_empty') return;

    wp_set_object_terms($post_id, explode(',', $new_tax_value), $taxonomy_name, $tax_mode === 'append');
  }

  function process_custom_taxonomies($feed, $entry, $post_id) {
    $tax_map = $this->get_dynamic_field_map_fields($feed, 'custom_tax_settings');
    $tax_mode = rgars($feed, 'meta/custom_tax_override_mode');

    foreach($tax_map as $tax_slug => $tax_field_id) {
      $new_tax_value = trim(rgar($entry, $tax_field_id));
      if($new_tax_value === '' && $tax_mode === 'override_not_empty') continue;
      wp_set_object_terms($post_id, explode(',', $new_tax_value), $tax_slug, $tax_mode === 'append');
    }
  }

  function prepare_author_id($feed, $entry, $form, &$postarr) {
    $author_id = rgars($feed, 'meta/author_id');
    $author_id = trim($author_id);
    $author_id = GFCommon::replace_variables($author_id, $form, $entry, false, false, false);
    if($author_id !== '') {
      $postarr['post_author'] = $author_id;
      $this->log_debug(__METHOD__ . sprintf('(): Provided Author ID "%d"', $author_id));
    }
  }

  function prepare_post_status($feed, $entry, $form, &$postarr) {
    $post_status = rgars($feed, 'meta/post_status');
    $post_status = trim($post_status);
    $post_status = GFCommon::replace_variables($post_status, $form, $entry, false, false, false);
    if($post_status !== '') {
      $postarr['post_status'] = $post_status;
      $this->log_debug(__METHOD__ . sprintf('(): Provided Post Status "%s"', $post_status));
    }
  }

  function prepare_post_title($feed, $entry, $form, &$postarr) {
    $post_title = rgars($feed, 'meta/post_title');
    $post_title = trim($post_title);
    $post_title = GFCommon::replace_variables($post_title, $form, $entry, false, false, false);
    if($post_title !== '') {
      $postarr['post_title'] = $post_title;
      $this->log_debug(__METHOD__ . sprintf('(): Provided Post Title "%s"', $post_title));
    }
  }

  function prepare_post_content($feed, $entry, $form, &$postarr) {
    $post_content = rgars($feed, 'meta/post_content');
    $post_content = trim($post_content);
    $post_content = GFCommon::replace_variables($post_content, $form, $entry, false, false, false);
    $allow_empty_content = rgars($feed, 'meta/allow_empty_content');
    if($allow_empty_content === '1' || $post_content !== '') {
      $postarr['post_content'] = $post_content;
      $this->log_debug(__METHOD__ . sprintf('(): Provided Post Content "%s"', $post_content));
    }
  }
}
?>
