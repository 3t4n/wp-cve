<?php

class Feeds_controller_wdi {
  private $dataFormat;
  private $allowed_message_html;
  public function __construct() {
    // Format an array of allowed HTML tags and attributes.
    $this->allowed_message_html = array(
      'div' => array(
        'class' => true,
        'style' => true
      ),
      'p' => array(),
      'strong' => array()
    );
    $this->setDataFormat();
  }

  public function execute() {
    $task = WDILibrary::get('task');
    $id = WDILibrary::get('current_id', 0);
    $message = WDILibrary::get('message');

    if ( !empty($message) ) {
      $message = explode(',', $message);
      foreach ( $message as $msg_id ) {
        echo wp_kses(WDILibrary::message_id($msg_id), $this->allowed_message_html);
      }
    }
    $get_method_tasks = array("add", "edit", "display");
    $get_task = "";
    if ( WDILibrary::get('task') != '' ) {
      $get_task = WDILibrary::get('task');
    }
    if ( method_exists($this, $task) ) {
      if ( !in_array($get_task, $get_method_tasks) ) {
        check_admin_referer('nonce_wd', 'nonce_wd');
      }
      $this->$task($id);
    }
    else {
      $search_value = WDILibrary::get('search_value');
      if ( $search_value != '' ) {
        wp_redirect( add_query_arg( array(
                                      'page' => WDILibrary::get('page'),
                                      'task' => 'display',
                                      'search' => $search_value,
                                    ), admin_url('admin.php')) );
        exit;
      }
      else {
        $this->display();
      }
    }
  }

  public function create_feed( $settings = array() ) {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    $defaults = $model->wdi_get_feed_defaults();
    if ( !empty($settings) ) {
      $settings = $this->sanitize_input($settings, $defaults);
      $settings = wp_parse_args($settings, $defaults);
    }
    else {
      $settings = $defaults;
    }
    global $wpdb;
    $wpdb->insert($wpdb->prefix . WDI_FEED_TABLE, $settings, $this->dataFormat); //db call ok

    return $wpdb->insert_id;
  }

  private function setDataFormat() {
    $this->dataFormat = array(
      '%s', // feed_name
      '%s', // feed_thumb
      '%s', // thumb_user
      '%d', // published
      '%d', // theme_id
      '%s', // feed_users
      '%s', // feed_display_view
      '%s', // sort_images_by
      '%s', // display_order
      '%d', // follow_on_instagram_btn
      '%d', // display_header
      '%d', // number_of_photos
      '%d', // load_more_number
      '%d', // pagination_per_page_number
      '%d', // pagination_preload_number
      '%d', // image_browser_preload_number
      '%d', // image_browser_load_number
      '%d', // number_of_columns
      '%d', // resort_after_load_more
      '%d', // show_likes
      '%d', // show_description
      '%d', // show_comments
      '%d', // show_usernames
      '%d', // display_user_info
      '%d', // display_user_post_follow_number
      '%d', // show_full_description
      '%d', // disable_mobile_layout
      '%s', // feed_type
      '%s', // feed_item_onclick
      '%d', // popup_fullscreen = bool
      '%d', // popup_width = number
      '%d', // popup_height = number
      '%s', // popup_type = string
      '%d', // popup_autoplay = bool
      '%d', // popup_interval = number
      '%d', // popup_enable_filmstrip = bool
      '%d', // popup_filmstrip_height = number
      '%d', // autohide_lightbox_navigation = bool
      '%d', // popup_enable_ctrl_btn = bool
      '%d', // popup_enable_fullscreen = bool
      '%d', // popup_enable_info = bool
      '%d', // popup_info_always_show = bool
      '%d', // popup_info_full_width = bool
      '%d', // popup_enable_comment = bool
      '%d', // popup_enable_fullsize_image = bool
      '%d', // popup_enable_download = bool
      '%d', // popup_enable_share_buttons = bool
      '%d', // popup_enable_facebook = bool
      '%d', // popup_enable_twitter = bool
      '%d', // popup_enable_google = bool
      '%d', // popup_enable_pinterest = bool
      '%d', // popup_enable_tumblr = bool
      '%d', // show_image_counts = bool
      '%d', // enable_loop = bool
      '%d', // popup_image_right_click = bool
      '%s', // conditional_filters = string
      '%s', // conditional_filter_type = string
      '%d', // show_username_on_thumb
      '%d', // conditional_filter_enable = 0
      '%s', // liked_feed = string
      '%d', // mobile_breakpoint = 640
      '%s', // redirect_url
      '%s', // feed_resolution
    );
  }

  private function display() {
    require_once(WDI_DIR . '/admin/models/feeds.php');
    $model = new Feeds_model_wdi();
    require_once(WDI_DIR . '/admin/views/feeds.php');
    $view = new Feeds_view_wdi($model);
    $view->display();
  }

  private function add() {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    require_once WDI_DIR . '/admin/views/feeds.php';
    $view = new Feeds_view_wdi($model);
    $view->edit(0);
  }

  private function edit( $customId = '' ) {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    require_once WDI_DIR . '/admin/views/feeds.php';
    $view = new Feeds_view_wdi($model);
    if ( $customId != '' ) {
      $id = $customId;
    }
    else {
      $id = WDILibrary::get('current_id', 0, 'intval');
    }
    $view->edit($id);
  }

  private function save_feed() {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    $settings = WDILibrary::get(WDI_FSN);
    $defaults = $model->wdi_get_feed_defaults();
    $settings = $this->sanitize_input($settings, $defaults);
    $settings = wp_parse_args($settings, $defaults);
    global $wpdb;
    $action = WDILibrary::get('add_or_edit');
    if ( $action == '' ) {
      $wpdb->insert($wpdb->prefix . WDI_FEED_TABLE, $settings, $this->dataFormat); //db call ok
      if ( $wpdb->insert_id == FALSE ) {
        $this->message(__('Cannot Write on database. Check database permissions.', 'wd-instagram-feed'), 'error');
      }
    }
    else {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $msg = $wpdb->update( $wpdb->prefix . WDI_FEED_TABLE, $settings, array( 'id' => $action ), $this->dataFormat, array( '%d' )); //db call ok
      if ( $msg == FALSE ) {
        $this->message(__("You have not made new changes", 'wd-instagram-feed'), 'notice');
      }
      else {
        $this->message(__("Successfully saved", 'wd-instagram-feed'), "updated");
      }
    }
    $this->display();
  }

  private function apply_changes() {
    require_once WDI_DIR . '/admin/models/feeds.php';
    global $wpdb;
    global $wdi_options;
    $model = new Feeds_model_wdi();
    $posts = WDILibrary::get(WDI_FSN);
    $defaults = $model->wdi_get_feed_defaults();
    $settings = $this->sanitize_input($posts, $defaults);
    $settings = wp_parse_args($settings, $defaults);
    $action = WDILibrary::get('add_or_edit');
    require_once (WDI_DIR . '/framework/WDIInstagram.php');
    $message_id = 23;
    $need_cache = 1;
    if ( $action == '' ) {
	    $settings["feed_name"] = $model->get_unique_title($settings["feed_name"]);
      $wpdb->insert( esc_sql($wpdb->prefix . WDI_FEED_TABLE), $settings, $this->dataFormat); //db call ok
      if ( $wpdb->insert_id == FALSE ) {
        $message_id = 24;
      }
    }
    else {
      $need_cache = $model->check_need_cache( $action, $settings );
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $msg = $wpdb->update(esc_sql($wpdb->prefix . WDI_FEED_TABLE), $settings, array( 'id' => $action ), $this->dataFormat, array( '%d' )); //db call ok
      if ( $msg == FALSE ) {
        $message_id = 24;
      }
    }
    $wdi_current_task = 'edit';
    if ( !empty($action) ) {
      $wdi_current_id = $action;
    }
    elseif ( $wpdb->insert_id != FALSE ) {
      $wdi_current_id = $wpdb->insert_id;
    }
    else {
      $wdi_current_task = "display";
      $wdi_current_id = 0;
    }

    $redirect_url['need_cache'] = $need_cache;
    $redirect_url['feed_id'] = $wdi_current_id;
    $redirect_url['url'] = add_query_arg(array(
                                    'page' => WDILibrary::get('page'),
                                    'task' => $wdi_current_task,
                                    'current_id' => $wdi_current_id,
                                    'message' => $message_id,
                                  ), admin_url('admin.php'));
    echo wp_json_encode($redirect_url);
    die();
  }

  private function reset_changes() {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    $defaults = $model->wdi_get_feed_defaults();
    global $wpdb;
    $action = WDILibrary::get('add_or_edit');
    if ( $action == '' ) {
      $wpdb->insert($wpdb->prefix . WDI_FEED_TABLE, $defaults, $this->dataFormat); //db call ok
      if ( $wpdb->insert_id == FALSE ) {
        $this->message(__("Cannot Write on database. Check database permissions.", 'wd-instagram-feed'), 'error');
        $this->display();
      }
      else {
        $this->edit($wpdb->insert_id);
      }
    }
    else {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $msg = $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, $defaults, array( 'id' => $action ), $this->dataFormat, array( '%d' )); //db call ok
      if ( $msg == FALSE ) {
        $this->message(__("You have not made new changes", 'wd-instagram-feed'), 'notice');
        $this->edit();
      }
      else {
        $this->message(__("Feed successfully reseted", 'wd-instagram-feed'), "updated");
        $this->edit();
      }
    }
  }

  private function cancel() {
    $this->display();
  }

  private function duplicate( $id ) {
    $message = 20;
    $duplicated = $this->duplicate_tabels($id);
    if ( $duplicated ) {
      $message = 18;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function duplicate_all( $id ) {
    global $wpdb;
    $message = 19;
    $flag = FALSE;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) ); //db call ok
    foreach ( $feed_ids_col as $slider_id ) {
      if ( WDILibrary::get('check_' . $slider_id) != '' ) {
        if ( !$flag ) {
          $flag = TRUE;
        }
        $this->duplicate_tabels($slider_id);
      }
    }
    if ( !$flag ) {
      $message = 6;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function duplicate_tabels( $feed_id ) {
    require_once WDI_DIR . '/admin/models/feeds.php';
    global $wpdb;
    $model = new Feeds_model_wdi();
    if ( $feed_id ) {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $feed_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE id="%d"', $feed_id)); //db call ok
    }
    if ( $feed_row ) {
      $duplicate_values = WDILibrary::objectToArray($feed_row);
      unset($duplicate_values['id']);
	    $duplicate_values["feed_name"] = $model->get_unique_title($duplicate_values["feed_name"]);
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $save = $wpdb->insert($wpdb->prefix . WDI_FEED_TABLE, $duplicate_values, $this->dataFormat); //db call ok
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $new_slider_id = $wpdb->get_var( 'SELECT MAX(id) FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) ); //db call ok
    }

    return $new_slider_id;
  }

  private function delete( $id ) {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $query = $wpdb->query( $wpdb->prepare('DELETE FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE id="%d"', $id) ); //db call ok
    if ( $query ) {
      $message = 3;
    }
    else {
      $message = 20;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function delete_all() {
    global $wpdb;
    $flag = FALSE;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $feed_ids_col = $wpdb->get_col( 'SELECT id FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) ); //db call ok
    foreach ( $feed_ids_col as $slider_id ) {
      if ( WDILibrary::get('check_' . $slider_id) != '' || WDILibrary::get('check_all_items') != '' ) {
        $flag = TRUE;
        /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
        $wpdb->query( $wpdb->prepare('DELETE FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE id="%d"', $slider_id) ); //db call ok
      }
    }
    if ( $flag ) {
      $message = 5;
    }
    else {
      $message = 6;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function publish( $id ) {
    global $wpdb;
    $message = 20;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $save = $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array( 'published' => 1 ), array( 'id' => $id )); //db call ok
    if ( $save !== FALSE ) {
      $message = 9;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function publish_all() {
    global $wpdb;
    $flag = FALSE;
    if ( WDILibrary::get('check_all_items') != '' ) {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $wpdb->query( $wpdb->prepare('UPDATE ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' SET published="%d', 1) ); //db call ok
      $flag = TRUE;
    }
    else {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE)); //db call ok
      foreach ( $feed_ids_col as $slider_id ) {
        if ( WDILibrary::get('check_' . $slider_id) != '' ) {
          $flag = TRUE;
          /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
          $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array( 'published' => 1 ), array( 'id' => $slider_id )); //db call ok
        }
      }
    }
    if ( $flag ) {
      $message = 10;
    }
    else {
      $message = 6;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function unpublish( $id ) {
    global $wpdb;
    $message = 20;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $save = $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array( 'published' => 0 ), array( 'id' => $id )); //db call ok
    if ( $save !== FALSE ) {
      $message = 11;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    if ( WDILibrary::get('check_all_items') != '' ) {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $wpdb->query('UPDATE ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' SET published=0'); //db call ok
      $flag = TRUE;
    }
    else {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) ); //db call ok
      foreach ( $feed_ids_col as $slider_id ) {
        if ( WDILibrary::get('check_' . $slider_id) != '' ) {
          $flag = TRUE;
          /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
          $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array( 'published' => 0 ), array( 'id' => $slider_id )); //db call ok
        }
      }
    }
    if ( $flag ) {
      $message = 12;
    }
    else {
      $message = 6;
    }
    WDILibrary::wdi_spider_redirect(add_query_arg(array(
                                                    'page' => WDILibrary::get('page'),
                                                    'task' => 'display',
                                                    'message' => $message,
                                                  ), admin_url('admin.php')));
  }

  private function sanitize_input( $settings, $defaults ) {
    require_once WDI_DIR . '/admin/models/feeds.php';
    $model = new Feeds_model_wdi();
    $sanitize_types = $model->get_sanitize_types();
    $sanitized_output = array();
    foreach ( $settings as $setting_name => $value ) {
      if( !isset($sanitize_types[$setting_name]) ) {
        continue;
      }
      switch ( $sanitize_types[$setting_name] ) {
        case 'string':
        {
          $sanitized_val = $this->sanitize_string($value, $defaults[$setting_name]);
          $sanitized_output[$setting_name] = $sanitized_val;
          break;
        }
        case 'number':
        {
          $sanitized_val = $this->sanitize_number($value, $defaults[$setting_name]);
          $sanitized_output[$setting_name] = $sanitized_val;
          break;
        }
        case 'bool':
        {
          $sanitized_val = $this->sanitize_bool($value, $defaults[$setting_name]);
          $sanitized_output[$setting_name] = $sanitized_val;
          break;
        }
        case 'url':
        {
          $sanitized_val = $this->sanitize_url($value, $defaults[$setting_name]);
          $sanitized_output[$setting_name] = $sanitized_val;
          break;
        }
        case 'json':
        {
          $sanitized_val = $this->sanitize_json($value, $defaults[$setting_name]);
          $sanitized_output[$setting_name] = $sanitized_val;
          break;
        }
      }
    }

    return $sanitized_output;
  }

  /**
   * Sanitize bool.
   *
   * @param $value
   * @param $default
   *
   * @return mixed
   */
  private function sanitize_bool( $value, $default ) {
    if ( $value == 1 || $value == 0 ) {
      return $value;
    }
    else {
      return $default;
    }
  }

  /**
   * Sanitize string
   *
   * @param $value
   * @param $default
   *
   * @return string
   */
  private function sanitize_string( $value, $default ) {
    $sanitized_val = wp_strip_all_tags(stripslashes(esc_html($value)));
    if ( $sanitized_val == '' ) {
      return $default;
    }
    else {
      return $sanitized_val;
    }
  }

  /**
   * Sanitize number field.
   *
   * @param $value
   * @param $default
   *
   * @return mixed
   */
  private function sanitize_number( $value, $default ) {
    if ( is_numeric($value) && $value > 0 ) {
      return $value;
    }
    else {
      return $default;
    }
  }

  /**
   * Sanitize url field.
   *
   * @param $value
   * @param $default
   *
   * @return mixed
   */
  private function sanitize_url( $value, $default ) {
    if ( function_exists('filter_var') && !filter_var($value, FILTER_VALIDATE_URL) === FALSE ) {
      return $value;
    }
    else {
      return $default;
    }
  }

  /**
   * Sanitize json field.
   * @param $value
   * @param $default
   *
   * @return false|string
   */
  private function sanitize_json( $value, $default ) {
    $json = $default;
    $items = json_decode($value, TRUE);
    $data = array();
    if ( !empty($items) ) {
      foreach ( $items as $index => $values ) {
        foreach ( $values as $key => $val ) {
          $data[$index][$key] = sanitize_text_field($val);
        }
      }
      $json = wp_json_encode($data);
    }

    return $json;
  }

  private function message( $text, $type ) {
    require_once(WDI_DIR . '/framework/WDILibrary.php');
    echo wp_kses(WDILibrary::message($text, $type), $this->allowed_message_html);
  }
}