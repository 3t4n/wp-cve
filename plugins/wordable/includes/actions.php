<?php

#error_log(var_export($my_var, true));

class WordablePluginActions extends WordablePlugin {
  public $params;

  public static function install() {
    add_action('wp_ajax_nopriv_wordable_action', 'WordablePluginActions::handle_wordable_action');
  }

  public static function handle_wordable_action() {
    header("Content-Type: application/json");
    ob_start();

    try {
      $wordable_plugin_actions = new WordablePluginActions();

      if(!$_POST['params']) {
        $wordable_plugin_actions->params = array();
      } else {
        $wordable_plugin_actions->params = (new ActionParams($wordable_plugin_actions))->parse_and_validate($_POST['params']);
      }

      if($wordable_plugin_actions->authenticate($wordable_plugin_actions->params)) {
        $result = $wordable_plugin_actions->action();
        http_response_code(200);
        error_log('****** result ******');
        error_log(var_export($result, true));
        ob_clean();
        echo json_encode($result);
      }
    } catch (Throwable $e) {
      http_response_code(500);
      error_log('****** error ******');
      error_log($e);
      ob_clean();

      $error_response = array(
        'error' => $e->getMessage(),
        'code' => $e->getCode()
      );

      if(method_exists($e, 'getMeta')) {
        $error_response['error_meta'] = $e->getMeta();
      } else {
        $error_response['error_meta'] = array();
      }

      $error_response['error_meta']['wp_trace'] = $e->getTraceAsString();

      echo json_encode($error_response);
    }

    die();
  }

  function action() {
    return $this->{$this->params['method'].'_action'}($this->params);
  }

  function authenticate($params) {
    global $wpdb;
    $file = '';

    if(array_key_exists('file', $_FILES)) {
      if(empty($_FILES['file']['tmp_name'])) {
        if($params['content_length'] && $params['content_length'] > wp_max_upload_size()) {
          throw new WordableException('warnings.connections.wordpress.upload_image.too_large',
                                      array(
                                        'content_length' => $params['content_length'],
                                        'max_upload_size' => wp_max_upload_size()
                                      ));
        } else {
          throw new WordableException('warnings.connections.wordpress.upload_image.rejected',
                                      array(
                                        'content_length' => $params['content_length'],
                                        'max_upload_size' => wp_max_upload_size()
                                      ));
        }
      }

      $file = file_get_contents($_FILES['file']['tmp_name']);
    }

    $raw_post = implode('', [stripslashes($_POST['params']), $file]);

    if (!isset($_SERVER['HTTP_X_WORDABLE_SIGNATURE'])) {
      throw new WordableException("errors.connections.wordpress.missing_security_header");
    }

    list($algo, $hash) = explode('=', $_SERVER['HTTP_X_WORDABLE_SIGNATURE'], 2) + array('', '');

    $table_name = $wpdb->prefix . 'wordable';

    foreach ($wpdb->get_results("SELECT * FROM `$table_name`") as $secret) {
      if ($hash == hash_hmac('sha1', $raw_post, $secret->secret)) {
        return true;
      }
    }
  }

  function throw_if_wp_error($response_or_wp_error) {
    if (is_wp_error($response_or_wp_error)) {
      throw new Exception(implode("\n", $response_or_wp_error->get_error_messages()));
    }

    return $response_or_wp_error;
  }

  function upload_image_action($params) {
    if(!file_is_valid_image($_FILES['file']['tmp_name'])) {
      throw new WordableException('warnings.connections.wordpress.upload_image.invalid_image');
    }

    $attachment_id = $this->throw_if_wp_error(media_handle_upload('file', 0));
    $this->update_image_attributes($params, $attachment_id);

    return array(
      'plugin_version' => WORDABLE_VERSION,
      'wordpress_version' => get_bloginfo('version'),
      'id' => $attachment_id,
      'url' => esc_url(wp_get_attachment_url($attachment_id))
    );
  }

  function update_image_attributes_action($params) {
    $attachment_id = $params['attachment_id'];
    $this->update_image_attributes($params, $attachment_id);
  }

  function update_image_attributes($params, $attachment_id) {
    if(!array_key_exists('image_attributes', $params)) return;

    $image_attributes = $params['image_attributes'];

    if(array_key_exists('alt', $image_attributes)) {
      update_post_meta($attachment_id, '_wp_attachment_image_alt', $image_attributes['alt']);
    }

    if(array_key_exists('caption', $image_attributes)) {
      wp_update_post(
        array('ID' => $attachment_id,
              'post_excerpt' => $image_attributes['caption']));
    }
  }

  function create_post_action($params) {
    if($params['post'] && $params['post']['author_id']) {
      wp_set_current_user($params['post']['author_id']);
    } else {
      $current_user_id = $this->user_id_to_be_current();

      if($current_user_id) {
        wp_set_current_user($current_user_id);
      }
    }

    $post_attributes = array(
      'post_author' => $params['post']['author_id'],
      'post_content' => $params['post']['content'],
      'post_name' => $params['post']['slug'],
      'post_status' => $params['post']['status'],
      'post_title' => $params['post']['title'],
      'post_type' => $params['post']['type'],
      'post_category' => $params['post']['categories']
    );

    if(array_key_exists('meta_input', $params['post']) && is_array($params['post']['meta_input'])) {
      $post_attributes['meta_input'] = $params['post']['meta_input'];
    }

    if(array_key_exists('previous_post_id', $params['post']) && get_post_status($params['post']['previous_post_id']) !== false) {
      $post_attributes['ID'] = $params['post']['previous_post_id'];
      $post_id = $post_attributes['ID'];
      $this->throw_if_wp_error(wp_update_post($post_attributes, true));
    } else {
      $post_id = $this->throw_if_wp_error(wp_insert_post($post_attributes, true));
    }

    return $this->segmented_post_hook($post_id, $post_attributes, $params);
  }

  function get_post_action($params) {
    $post = get_post($params['post_id']);

    if($post == null) {
      return array('error' => 'null');
    }

    return $post;
  }

  function segmented_post_hook($post_id, $post_attributes, $params) {
    $post = get_post($post_id);

    list($final, $post_segment_number, $unique_identifier, $post_title) = explode('::', $post_attributes['post_title']);

    if (!empty($post_title) && ($final == 'final')) {
      $post_attributes['post_title'] = $post_title;
      $post_id = $this->join_segmented_posts($unique_identifier, $post_segment_number, $post, $post_attributes);
    }

    if($params['post']['featured_image_attachment_id']) {
      set_post_thumbnail($post_id, $params['post']['featured_image_attachment_id']);
    }

    return array(
      'plugin_version' => WORDABLE_VERSION,
      'wordpress_version' => get_bloginfo('version'),
      'id' => $post_id,
      'url' => html_entity_decode(get_edit_post_link($post_id))
    );
  }

  function join_segmented_posts($unique_identifier, $final_post_segment_number, $final_post, $post_attributes) {
    $post_attributes['post_content'] = '';

    for ($i = 0; $i < $final_post_segment_number; $i++) {
      $post = get_page_by_title("$i::$unique_identifier", OBJECT, 'post');
      $post_attributes['post_content'] = $post_attributes['post_content'].$post->post_content;
      wp_delete_post($post->ID, true);
    }

    wp_delete_post($final_post->ID, true);

    return $this->throw_if_wp_error(wp_insert_post($post_attributes, true));
  }

  function sync_action() {
    return $this->connector()->destination_meta();
  }
}

WordablePluginActions::install();
