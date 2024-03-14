<?php

class WDFacebookFeed {

    protected static $fb_type;
    protected static $facebook_sdk;
    protected static $graph_url = 'https://graph.facebook.com/v12.0/{FB_ID}/{EDGE}?{ACCESS_TOKEN}{FIELDS}{LIMIT}{OTHER}';
    protected static $id;
    protected static $fb_valid_types = array('page', 'group', 'profile');
    protected static $valid_content_types = array('timeline', 'specific');
    protected static $content_url;
    protected static $content_type;
    protected static $timeline_type;
    protected static $content;
    protected static $limit;
    protected static $fb_limit = 20;
    protected static $data;
    // For collapse timeline data matching content requirements
    protected static $complite_timeline_data = array();
    // Maximum graph call integer for timeline type
    protected static $timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    protected static $next_page = '';
    protected static $valid_content = array('statuses', 'photos', 'videos', 'links', 'events', 'albums');
    protected static $access_token;
    protected static $event_order;
    protected static $upcoming_events;
    protected static $exist_access = false;
    protected static $auto_update_feed = 0;
    protected static $updateOnVersionChange = false;


    protected static $wd_fb_error_massage = '';
    protected static $ajax_cache_action = '';


    // Existing app ids and app secrets
    protected static $access_tokens = array();
    private static $ffwd_fb_massage = true;
    private static $ffwd_fb_massage_response = '';
    protected static $save = true;
    protected static $edit_feed = false;
    protected static $update_mode = 'keep_old';
    protected static $fb_id;
    protected static $non_public_share_count = 0;

    public static $client_side_check = array();

    /* this is count of media in DB for specific feed */
    public static $media_db_current_count = 0;
    /* this is specify if data in DB already exceed to post limit option */
    public static $media_db_limit_full = false;

    public static function execute() {
        if (function_exists('current_user_can')) {
            if (!current_user_can('manage_options')) {
              if (defined( 'DOING_AJAX' ) && DOING_AJAX )
              {
                die('Access Denied');
              }
            }
        } else {
            die('Access Denied');
        }
        require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
        $action = WDW_FFWD_Library::get('action');

        if (!WDW_FFWD_Library::verify_nonce('')) {
          if (defined( 'DOING_AJAX' ) && DOING_AJAX )
          {
            die(WDW_FFWD_Library::delimit_wd_output(json_encode(array("error", "Sorry, your nonce did not verify."))));
          }
        }

        if (method_exists('WDFacebookFeed', $action)) {
            call_user_func(array('WDFacebookFeed', $action));
        } else {
            call_user_func(array('WDFacebookFeed', 'wd_fb_massage'), array('error', 'Unknown action'));
        }
    }

    /**
     * Save/Edit feed
     */
    public static function save_facebook_feed() {
        $id = (isset($_POST['current_id']) && $_POST['current_id'] != '') ? (int)esc_html(stripslashes($_POST['current_id'])) : 0;
        if ($id) {
            self::$fb_id = $id;
            self::$edit_feed = true;
            self::$save = false;
        } else {
            self::$save = true;
        }
        self::check_fb_type();
    }

    /**
     * Edit Facebook Feed
     */
    public static function edit_feed() {
      global $wpdb;
      $update_wd_fb_data = FALSE;
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', self::$fb_id));
      $ffwd_info_options = array();
      $ffwd_info_options['theme'] = ((isset($_POST['theme'])) ? sanitize_text_field(stripslashes($_POST['theme'])) : '');
      $ffwd_info_options['masonry_hor_ver'] = ((isset($_POST['masonry_hor_ver'])) ? sanitize_text_field(stripslashes($_POST['masonry_hor_ver'])) : '');
      $ffwd_info_options['image_max_columns'] = ((isset($_POST['image_max_columns'])) ? sanitize_text_field(stripslashes($_POST['image_max_columns'])) : '');
      $ffwd_info_options['thumb_width'] = ((isset($_POST['thumb_width'])) ? sanitize_text_field(stripslashes($_POST['thumb_width'])) : '');
      $ffwd_info_options['thumb_height'] = ((isset($_POST['thumb_height'])) ? sanitize_text_field(stripslashes($_POST['thumb_height'])) : '');
      $ffwd_info_options['thumb_comments'] = ((isset($_POST['thumb_comments'])) ? sanitize_text_field(stripslashes($_POST['thumb_comments'])) : '');
      $ffwd_info_options['thumb_likes'] = ((isset($_POST['thumb_likes'])) ? sanitize_text_field(stripslashes($_POST['thumb_likes'])) : '');
      $ffwd_info_options['thumb_name'] = ((isset($_POST['thumb_name'])) ? sanitize_text_field(stripslashes($_POST['thumb_name'])) : '');
      $ffwd_info_options['blog_style_width'] = ((isset($_POST['blog_style_width'])) ? sanitize_text_field(stripslashes($_POST['blog_style_width'])) : '');
      $ffwd_info_options['blog_style_height'] = ((isset($_POST['blog_style_height'])) ? sanitize_text_field(stripslashes($_POST['blog_style_height'])) : '');
      $ffwd_info_options['blog_style_view_type'] = ((isset($_POST['blog_style_view_type'])) ? sanitize_text_field(stripslashes($_POST['blog_style_view_type'])) : '');
      $ffwd_info_options['blog_style_comments'] = ((isset($_POST['blog_style_comments'])) ? sanitize_text_field(stripslashes($_POST['blog_style_comments'])) : '');
      $ffwd_info_options['blog_style_likes'] = ((isset($_POST['blog_style_likes'])) ? sanitize_text_field(stripslashes($_POST['blog_style_likes'])) : '');
      $ffwd_info_options['blog_style_message_desc'] = ((isset($_POST['blog_style_message_desc'])) ? sanitize_text_field(stripslashes($_POST['blog_style_message_desc'])) : '');
      $ffwd_info_options['blog_style_shares'] = ((isset($_POST['blog_style_shares'])) ? sanitize_text_field(stripslashes($_POST['blog_style_shares'])) : '');
      $ffwd_info_options['blog_style_shares_butt'] = ((isset($_POST['blog_style_shares_butt'])) ? sanitize_text_field(stripslashes($_POST['blog_style_shares_butt'])) : '');
      $ffwd_info_options['blog_style_facebook'] = ((isset($_POST['blog_style_facebook'])) ? sanitize_text_field(stripslashes($_POST['blog_style_facebook'])) : '');
      $ffwd_info_options['blog_style_twitter'] = ((isset($_POST['blog_style_twitter'])) ? sanitize_text_field(stripslashes($_POST['blog_style_twitter'])) : '');
      $ffwd_info_options['blog_style_google'] = '0';
      $ffwd_info_options['blog_style_author'] = ((isset($_POST['blog_style_author'])) ? sanitize_text_field(stripslashes($_POST['blog_style_author'])) : '');
      $ffwd_info_options['blog_style_name'] = ((isset($_POST['blog_style_name'])) ? sanitize_text_field(stripslashes($_POST['blog_style_name'])) : '');
      $ffwd_info_options['blog_style_place_name'] = ((isset($_POST['blog_style_place_name'])) ? sanitize_text_field(stripslashes($_POST['blog_style_place_name'])) : '');
      $ffwd_info_options['fb_name'] = ((isset($_POST['fb_name'])) ? sanitize_text_field(stripslashes($_POST['fb_name'])) : '');
      $ffwd_info_options['fb_plugin'] = ((isset($_POST['fb_plugin'])) ? sanitize_text_field(stripslashes($_POST['fb_plugin'])) : '');
      $ffwd_info_options['album_max_columns'] = ((isset($_POST['album_max_columns'])) ? sanitize_text_field(stripslashes($_POST['album_max_columns'])) : '');
      $ffwd_info_options['album_title'] = ((isset($_POST['album_title'])) ? sanitize_text_field(stripslashes($_POST['album_title'])) : '');
      $ffwd_info_options['album_thumb_width'] = ((isset($_POST['album_thumb_width'])) ? sanitize_text_field(stripslashes($_POST['album_thumb_width'])) : '');
      $ffwd_info_options['album_thumb_height'] = ((isset($_POST['album_thumb_height'])) ? sanitize_text_field(stripslashes($_POST['album_thumb_height'])) : '');
      $ffwd_info_options['album_image_max_columns'] = ((isset($_POST['album_image_max_columns'])) ? sanitize_text_field(stripslashes($_POST['album_image_max_columns'])) : '');
      $ffwd_info_options['album_image_thumb_width'] = ((isset($_POST['album_image_thumb_width'])) ? sanitize_text_field(stripslashes($_POST['album_image_thumb_width'])) : '');
      $ffwd_info_options['album_image_thumb_height'] = ((isset($_POST['album_image_thumb_height'])) ? sanitize_text_field(stripslashes($_POST['album_image_thumb_height'])) : '');
      $ffwd_info_options['pagination_type'] = ((isset($_POST['pagination_type'])) ? sanitize_text_field(stripslashes($_POST['pagination_type'])) : '');
      $ffwd_info_options['objects_per_page'] = ((isset($_POST['objects_per_page'])) ? sanitize_text_field(stripslashes($_POST['objects_per_page'])) : '');
      $ffwd_info_options['popup_fullscreen'] = ((isset($_POST['popup_fullscreen'])) ? sanitize_text_field(stripslashes($_POST['popup_fullscreen'])) : '');
      $ffwd_info_options['popup_height'] = ((isset($_POST['popup_height'])) ? sanitize_text_field(stripslashes($_POST['popup_height'])) : '');
      $ffwd_info_options['popup_width'] = ((isset($_POST['popup_width'])) ? sanitize_text_field(stripslashes($_POST['popup_width'])) : '');
      $ffwd_info_options['popup_effect'] = ((isset($_POST['popup_effect'])) ? sanitize_text_field(stripslashes($_POST['popup_effect'])) : '');
      $ffwd_info_options['popup_autoplay'] = ((isset($_POST['popup_autoplay'])) ? sanitize_text_field(stripslashes($_POST['popup_autoplay'])) : '');
      $ffwd_info_options['open_commentbox'] = ((isset($_POST['open_commentbox'])) ? sanitize_text_field(stripslashes($_POST['open_commentbox'])) : '');
      $ffwd_info_options['popup_interval'] = ((isset($_POST['popup_interval'])) ? sanitize_text_field(stripslashes($_POST['popup_interval'])) : '');
      $ffwd_info_options['popup_enable_filmstrip'] = ((isset($_POST['popup_enable_filmstrip'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_filmstrip'])) : '');
      $ffwd_info_options['popup_filmstrip_height'] = ((isset($_POST['popup_filmstrip_height'])) ? sanitize_text_field(stripslashes($_POST['popup_filmstrip_height'])) : '');
      $ffwd_info_options['popup_comments'] = ((isset($_POST['popup_comments'])) ? sanitize_text_field(stripslashes($_POST['popup_comments'])) : '');
      $ffwd_info_options['popup_likes'] = ((isset($_POST['popup_likes'])) ? sanitize_text_field(stripslashes($_POST['popup_likes'])) : '');
      $ffwd_info_options['popup_shares'] = ((isset($_POST['popup_shares'])) ? sanitize_text_field(stripslashes($_POST['popup_shares'])) : '');
      $ffwd_info_options['popup_author'] = ((isset($_POST['popup_author'])) ? sanitize_text_field(stripslashes($_POST['popup_author'])) : '');
      $ffwd_info_options['popup_name'] = ((isset($_POST['popup_name'])) ? sanitize_text_field(stripslashes($_POST['popup_name'])) : '');
      $ffwd_info_options['popup_place_name'] = ((isset($_POST['popup_place_name'])) ? sanitize_text_field(stripslashes($_POST['popup_place_name'])) : '');
      $ffwd_info_options['popup_enable_ctrl_btn'] = ((isset($_POST['popup_enable_ctrl_btn'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_ctrl_btn'])) : '');
      $ffwd_info_options['popup_enable_fullscreen'] = ((isset($_POST['popup_enable_fullscreen'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_fullscreen'])) : '');
      $ffwd_info_options['popup_enable_info_btn'] = ((isset($_POST['popup_enable_info_btn'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_info_btn'])) : '');
      $ffwd_info_options['popup_message_desc'] = ((isset($_POST['popup_message_desc'])) ? sanitize_text_field(stripslashes($_POST['popup_message_desc'])) : '');
      $ffwd_info_options['popup_enable_facebook'] = ((isset($_POST['popup_enable_facebook'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_facebook'])) : '');
      $ffwd_info_options['popup_enable_twitter'] = ((isset($_POST['popup_enable_twitter'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_twitter'])) : '');
      $ffwd_info_options['popup_enable_google'] = '0';
      $ffwd_info_options['fb_view_type'] = ((isset($_POST['fb_view_type'])) ? sanitize_text_field(stripslashes($_POST['fb_view_type'])) : '');
      $ffwd_info_options['image_onclick_action'] = ((isset($_POST['image_onclick_action'])) ? sanitize_text_field(stripslashes($_POST['image_onclick_action'])) : 'lightbox');
      $ffwd_options_db = array(
        'view_on_fb',
        'post_text_length',
        'event_street',
        'event_city',
        'event_country',
        'event_zip',
        'event_map',
        'event_date',
        'event_desp_length',
        'comments_replies',
        'comments_filter',
        'comments_order',
        'page_plugin_pos',
        'page_plugin_fans',
        'page_plugin_cover',
        'page_plugin_header',
        'page_plugin_width',
        'event_order',
        'upcoming_events',
        'fb_page_id'
      );
      foreach ( $ffwd_options_db as $ffwd_option_db ) {
        $ffwd_info_options[$ffwd_option_db] = ((isset($_POST[$ffwd_option_db])) ? sanitize_text_field(stripslashes($_POST[$ffwd_option_db])) : '');
      }
      $name = ((isset($_POST['name'])) ? sanitize_text_field(stripslashes($_POST['name'])) : '');
      $name = str_replace(array( "'", '"' ), "", $name);
      $page_access_token = ((isset($_POST['page_access_token'])) ? sanitize_text_field(stripslashes($_POST['page_access_token'])) : '');
      $update_mode = ((isset($_POST['update_mode'])) ? sanitize_text_field(stripslashes($_POST['update_mode'])) : '');
      $published = ((isset($_POST['published'])) ? (int) esc_html(stripslashes($_POST['published'])) : 1);
      $content = implode(",", self::$content);
      $from = self::$id;
      $update_wd_fb_data = ((self::$auto_update_feed != 0) || ($row->type != self::$fb_type) || ($row->content_type != self::$content_type) || ($row->content != $content) || ($row->from != $from) || ($row->timeline_type != self::$timeline_type) || ($row->limit != self::$limit) || ($row->event_order != self::$event_order) || ($row->upcoming_events != self::$upcoming_events));
      if ( self::$fb_type == 'group' ) {
        self::$timeline_type = 'feed';
      }
      $save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array(
        'name' => $name,
        'page_access_token' => $page_access_token,
        'type' => self::$fb_type,
        'content_type' => self::$content_type,
        'content' => $content,
        'content_url' => self::$content_url,
        'timeline_type' => self::$timeline_type,
        'from' => $from,
        'limit' => self::$limit,
        'app_id' => '',
        'app_secret' => '',
        'exist_access' => 1,
        'access_token' => self::$access_token,
        'published' => $published,
        'update_mode' => $update_mode,
      ), array( 'id' => self::$fb_id ));

      if ( $save !== FALSE ) {
        self::update_wd_fb_info_options($ffwd_info_options);
        if ( $update_wd_fb_data ) {
          $delete_query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d"', self::$fb_id);
          $delete = $wpdb->query($delete_query);
          if ( $delete === FALSE ) {
            self::$wd_fb_error_massage = json_encode(array('status'=>'error','msg'=>'Something went wrong (trying delete wd_fb_data)'));
            return false;
          } else {
            self::$ajax_cache_action = "set_cache_data";
          }
        }
        self::$ajax_cache_action = "set_cache_data";
        return self::$fb_id;
      }
      else {
        self::$wd_fb_error_massage = json_encode(array('status'=>'error','msg'=>'Something went wrong (trying edit feed)'));
        return false;
      }
    }

  /**
   * Update Facebook Feed Options
   *
   * @param $options
   */
  static function update_wd_fb_info_options( $options ) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array(
      'theme' => $options['theme'],
      'masonry_hor_ver' => $options['masonry_hor_ver'],
      'image_max_columns' => $options['image_max_columns'],
      'thumb_width' => $options['thumb_width'],
      'thumb_height' => $options['thumb_height'],
      'thumb_comments' => $options['thumb_comments'],
      'thumb_likes' => $options['thumb_likes'],
      'thumb_name' => $options['thumb_name'],
      'blog_style_width' => $options['blog_style_width'],
      'blog_style_height' => $options['blog_style_height'],
      'blog_style_view_type' => $options['blog_style_view_type'],
      'blog_style_comments' => $options['blog_style_comments'],
      'blog_style_likes' => $options['blog_style_likes'],
      'blog_style_message_desc' => $options['blog_style_message_desc'],
      'blog_style_shares' => $options['blog_style_shares'],
      'blog_style_shares_butt' => $options['blog_style_shares_butt'],
      'blog_style_facebook' => $options['blog_style_facebook'],
      'blog_style_twitter' => $options['blog_style_twitter'],
      'blog_style_google' => $options['blog_style_google'],
      'blog_style_author' => $options['blog_style_author'],
      'blog_style_name' => $options['blog_style_name'],
      'blog_style_place_name' => $options['blog_style_place_name'],
      'fb_name' => $options['fb_name'],
      'fb_plugin' => $options['fb_plugin'],
      'album_max_columns' => $options['album_max_columns'],
      'album_title' => $options['album_title'],
      'album_thumb_width' => $options['album_thumb_width'],
      'album_thumb_height' => $options['album_thumb_height'],
      'album_image_max_columns' => $options['album_image_max_columns'],
      'album_image_thumb_width' => $options['album_image_thumb_width'],
      'album_image_thumb_height' => $options['album_image_thumb_height'],
      'pagination_type' => $options['pagination_type'],
      'objects_per_page' => $options['objects_per_page'],
      'popup_fullscreen' => $options['popup_fullscreen'],
      'popup_height' => $options['popup_height'],
      'popup_width' => $options['popup_width'],
      'popup_effect' => $options['popup_effect'],
      'popup_autoplay' => $options['popup_autoplay'],
      'open_commentbox' => $options['open_commentbox'],
      'popup_interval' => $options['popup_interval'],
      'popup_enable_filmstrip' => $options['popup_enable_filmstrip'],
      'popup_filmstrip_height' => $options['popup_filmstrip_height'],
      'popup_comments' => $options['popup_comments'],
      'popup_likes' => $options['popup_likes'],
      'popup_shares' => $options['popup_shares'],
      'popup_author' => $options['popup_author'],
      'popup_name' => $options['popup_name'],
      'popup_place_name' => $options['popup_place_name'],
      'popup_enable_ctrl_btn' => $options['popup_enable_ctrl_btn'],
      'popup_enable_fullscreen' => $options['popup_enable_fullscreen'],
      'popup_enable_info_btn' => $options['popup_enable_info_btn'],
      'popup_message_desc' => $options['popup_message_desc'],
      'popup_enable_facebook' => $options['popup_enable_facebook'],
      'popup_enable_twitter' => $options['popup_enable_twitter'],
      'popup_enable_google' => $options['popup_enable_google'],
      'fb_view_type' => $options['fb_view_type'],
      'view_on_fb' => $options['view_on_fb'],
      'post_text_length' => $options['post_text_length'],
      'event_street' => $options['event_street'],
      'event_city' => $options['event_city'],
      'event_country' => $options['event_country'],
      'event_zip' => $options['event_zip'],
      'event_map' => $options['event_map'],
      'event_date' => $options['event_date'],
      'event_desp_length' => $options['event_desp_length'],
      'comments_replies' => $options['comments_replies'],
      'comments_filter' => $options['comments_filter'],
      'comments_order' => $options['comments_order'],
      'page_plugin_pos' => $options['page_plugin_pos'],
      'page_plugin_fans' => $options['page_plugin_fans'],
      'page_plugin_cover' => $options['page_plugin_cover'],
      'page_plugin_header' => $options['page_plugin_header'],
      'page_plugin_width' => $options['page_plugin_width'],
      'image_onclick_action' => $options['image_onclick_action'],
      'event_order' => $options['event_order'],
      'upcoming_events' => $options['upcoming_events'],
      'fb_page_id' => $options['fb_page_id'],
    ), array( 'id' => self::$fb_id ));
  }

    // Prepare to delete
    public static function prepare_to_delete($rows = array()) {
        foreach ($rows as $row) {
            self::$fb_id = isset($row->id) ? $row->id : '';
            self::$fb_type = isset($row->type) ? $row->type : '';
            self::$content_type = isset($row->content_type) ? $row->content_type : '';
            self::$content = isset($row->content) ? explode(",", $row->content) : array();
            self::$content_url = isset($row->content_url) ? $row->content_url : '';
            self::$limit = isset($row->limit) ? $row->limit : '';
            self::$id = isset($row->from) ? $row->from : '';
            self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';

            self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
            self::get_rows_for_delete();
        }
    }

    public static function get_rows_for_delete($rows = array())
    {
        global $wpdb;
        $id = self::$fb_id;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
        $client_side_check = array();
        foreach ($rows as $row) {
            $data_for_client_side = new stdclass();
            $fields = 'fields=id&';
            $object_id = $row->object_id;

            $fb_graph_url = str_replace(
                array('{FB_ID}', '{EDGE}', '{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
                array($object_id, '', 'access_token=' . self::$access_token . '&', $fields, '', ''),
                self::$graph_url
            );
            $data_for_client_side->id = $row->id;
            $data_for_client_side->fb_graph_url = $fb_graph_url;

            array_push($client_side_check, $data_for_client_side);
        }
        array_push(self::$client_side_check, $client_side_check);
    }

    // Auto update
  public static function update_from_shedule( $rows = array() ) {
    self::$save = FALSE;
    self::$edit_feed = FALSE;
    self::$auto_update_feed = 1;
    foreach ( $rows as $row ) {
     self::$fb_id = isset($row->id) ? $row->id : '';
      self::$fb_type = isset($row->type) ? $row->type : '';
      self::$content_type = isset($row->content_type) ? $row->content_type : '';
      self::$content = isset($row->content) ? explode(",", $row->content) : array();
      self::$content_url = isset($row->content_url) ? $row->content_url : '';
      self::$timeline_type = isset($row->timeline_type) ? $row->timeline_type : 'posts';
      self::$limit = isset($row->limit) ? $row->limit : '';
      self::$id = isset($row->from) ? $row->from : '';
      self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';
      self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
      $function_name = self::$content_type;
      self::$function_name();
    }
  }

    // updateOnVersionChange
  public static function updateOnVersionChange( $rows = array() ) {
    self::$save = FALSE;
    self::$edit_feed = FALSE;
    self::$auto_update_feed = 0;
    self::$updateOnVersionChange = TRUE;
    foreach ( $rows as $row ) {
      self::$fb_id = isset($row->id) ? $row->id : '';
      self::$fb_type = isset($row->type) ? $row->type : '';
      self::$content_type = isset($row->content_type) ? $row->content_type : '';
      self::$content = isset($row->content) ? explode(",", $row->content) : array();
      self::$content_url = isset($row->content_url) ? $row->content_url : '';
      self::$timeline_type = isset($row->timeline_type) ? $row->timeline_type : 'posts';
      self::$limit = isset($row->limit) ? $row->limit : '';
      self::$id = isset($row->from) ? $row->from : '';
      self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';
      self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
      $function_name = self::$content_type;
      self::$function_name();
    }
  }

  public static function update_db() {
    if ( !isset(self::$data['data']) ) {
      return;
    }
    global $wpdb;
    $data = self::$data['data'];
    $id = self::$fb_id;
    $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
    $to_drop = array();
    $to_insert = array();
    $del_count = 0;
    // Store content array as string.
    $content = implode(",", self::$content);

    foreach ( $data as $next ) {
      // @todo API v10.0
      $next['type'] = 'photos';
      $exists = FALSE;
      $is_newer_then_any_of_olds = TRUE;
      $created_time = array_key_exists('created_time', $next) ? strtotime($next['created_time']) : '';
      $created_time = ($created_time == '' && array_key_exists('start_time', $next)) ? strtotime($next['start_time']) : $created_time;
      foreach ( $rows as $row ) {
        if ( $row->object_id == $next['id'] ) {
          $exists = TRUE;
        }
        if ( $exists && $created_time < $row->created_time_number ) {
          $is_newer_then_any_of_olds = FALSE;
        }
      }
      if ( !$exists && $is_newer_then_any_of_olds ) {
        if ( self::$content_type == 'timeline' ) {
          $from = array_key_exists('from', $next) ? $next['from']['id'] : '';
          if ( strpos($content, $next['type']) === FALSE ) {
            continue;
          }
          if ( self::$timeline_type == "posts" && self::$fb_type != 'group' ) {
            if ( $from != self::$id ) {
              continue;
            }
          }
          else {
            if ( self::$timeline_type == "others" ) {
              if ( $from == self::$id ) {
                continue;
              }
            }
          }
        }

        array_push($to_insert, $next);
      }
    }
    $exist_count = count($rows);
    $insert_count = count($to_insert);
    if ( (self::$update_mode == 'remove_old') && ($insert_count + $exist_count) > self::$limit ) {
      $del_count = ($insert_count + $exist_count) - self::$limit;
      $ids = array();
      $results = $wpdb->get_results($wpdb->prepare('SELECT `id` FROM `' . $wpdb->prefix . 'wd_fb_data` WHERE  `fb_id` = "%d" ORDER BY `created_time_number` ASC LIMIT ' . $del_count, self::$fb_id));
      foreach ( $results as $row ) {
        array_push($ids, $row->id);
      }
      $ids = implode(',', $ids);
      $delete = $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `id` IN (' . $ids . ') AND `fb_id` = "%d"', self::$fb_id));
    }
    if ( $insert_count ) {
      self::insert_wd_fb_data($to_insert);
    }
  }

  public static function update_version() {
    global $wpdb;
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `fb_id` = "%d"', self::$fb_id));
    self::$ajax_cache_action = "set_cache_data";
    return self::$fb_id;
  }

  public static function page() {
    $page_id = isset($_POST['fb_page_id']) ? sanitize_text_field($_POST['fb_page_id']) : 0;
    $pages_list = get_option('ffwd_pages_list', array());
    $fb_page = NULL;
    foreach ( $pages_list as $page ) {
      if ( $page->id === $page_id ) {
        $fb_page = $page;
        break;
      }
    }
    if ( $fb_page == NULL ) {
      die(0);
    }
    $fb_page_name = str_replace("/", "", $fb_page->name);
    $_POST['content_url'] = 'https://www.facebook.com/' . $fb_page_name . "-" . $fb_page->id . '/';
    $_POST['page_access_token'] = $fb_page->access_token;
    self::$content_url = ((isset($_POST['content_url'])) ? sanitize_text_field(stripslashes($_POST['content_url'])) : '');
    self::$limit = ((isset($_POST['limit'])) ? sanitize_text_field(stripslashes($_POST['limit'])) : '');
    self::set_access_token();
    self::check_fb_page_url();
    // If user exists => set content.
    self::set_content();
    // If right content => set access_token.
    // If right access_token => call function.
    $function_name = self::$content_type;
    self::$function_name();
  }

  public static function group() {
    self::$content_url = ((isset($_POST['content_url'])) ? sanitize_text_field(stripslashes($_POST['content_url'])) : '');
    self::$limit = ((isset($_POST['limit'])) ? sanitize_text_field(stripslashes($_POST['limit'])) : '');
    self::check_fb_group_url();
    self::set_content();
    self::set_access_token();
    self::timeline();
  }

  public static function profile() {
    self::$content_url = '';
    self::$limit = ((isset($_POST['limit'])) ? sanitize_text_field(stripslashes($_POST['limit'])) : '');
    self::check_fb_user();
    self::set_content();
    self::set_access_token();
    $function_name = self::$content_type;
    self::$function_name();
  }

  public static function check_fb_user() {
    include WD_FFWD_DIR . '/framework/facebook-sdk/src/Facebook/autoload.php';
    $fb_option_data = self::get_fb_option_data();
    $app_id = $fb_option_data->app_id;
    $app_secret = $fb_option_data->app_secret;
    self::$facebook_sdk = new Facebook\Facebook(array(
                                                  'app_id' => $app_id,
                                                  'app_secret' => $app_secret,
                                                ));
    $user_profile = 0;
    if ( isset($_SESSION['facebook_access_token']) ) {
      $user_profile = self::$facebook_sdk->get('/me', $_SESSION['facebook_access_token']);
      $user_profile = $user_profile->getDecodedBody();
    }
    if ( $user_profile ) {
      $user_id = $user_profile['id'];
    }
    if ( !$user_id ) {
      self::wd_fb_massage('error', 'Please login first');
    }
    else {
      self::$id = $user_id;
    }
  }

  public static function get_fb_option_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));

    return $row;
  }

  public static function set_content() {
    $content_type = ((isset($_POST['content_type'])) ? sanitize_text_field(stripslashes($_POST['content_type'])) : '');
    $content = (isset($_POST['content'])) ? $_POST['content'] : array();
    self::$event_order = ((isset($_POST['event_order'])) ? sanitize_text_field(stripslashes($_POST['event_order'])) : 0);
    self::$upcoming_events = ((isset($_POST['upcoming_events'])) ? sanitize_text_field(stripslashes($_POST['upcoming_events'])) : 0);
    self::$content_type = in_array($content_type, self::$valid_content_types) ? $content_type : FALSE;
    // If right content type
    if ( self::$content_type ) {
      self::$content = $content;
    }
    else {
      self::wd_fb_massage('error', 'Invalid content type');
    }
  }

  public static function set_access_token() {
    if ( isset($_POST["page_access_token"]) && $_POST["page_access_token"] != "" ) {
      self::$access_token = $_POST["page_access_token"];
      self::$exist_access = TRUE;
    }
    else {
      if ( !isset(self::$access_token) || empty(self::$access_token) ) {
        $rand_key = array_rand(self::$access_tokens);
        self::$access_token = self::$access_tokens[$rand_key];
      }
    }
  }

  public static function check_fb_page_url() {
    $first_token = strtok(self::$content_url, '/');
    $second_token = strtok('/');
    $third_token = strtok('/');
    $fourth = strtok('/');
    $fifth = strtok('/');
    // Check if it's facebook url
    if ( $second_token === 'www.facebook.com' ) {
      if ( $third_token == 'pages' ) {
        $fifth = explode('?', $fifth);
        self::$id = $fifth[0];
      }
      else {
        // If page's id not showing in url (trying to get id by it's name)
        $third_token = explode('-', $third_token);
        if ( count($third_token) > 0 ) {
          $last = count($third_token) - 1;
          $name_id = $third_token[$last];
        }
        else {
          $name_id = $third_token[0];
        }
        // If not set access token , get random from our's
        if ( empty(self::$access_token) ) {
          $rand_key = array_rand(self::$access_tokens);
          $access_token = self::$access_tokens[$rand_key];
        }
        else {
          $access_token = self::$access_token;
        }
        // Get data (including page id) by graph url
        $fb_graph_url = str_replace(array(
                                      '{FB_ID}',
                                      '{EDGE}',
                                      '{ACCESS_TOKEN}',
                                      '{FIELDS}',
                                      '{LIMIT}',
                                      '{OTHER}',
                                    ), array(
                                      $name_id,
                                      '',
                                      'access_token=' . $access_token . '&',
                                      'fields=id&',
                                      'limit=10',
                                      '',
                                    ), self::$graph_url);
        $data = self::decap_do_curl($fb_graph_url);
        // Check id existing
        if ( array_key_exists("id", $data) ) {
          self::$id = $data['id'];
        } // Check if exist error
        else {
          if ( array_key_exists("error", $data) ) {
            if ( $data['error']['code'] == 4 ) {
              update_option('ffwd_limit_notice', 1);
            }
            self::wd_fb_massage('error', $data['error']['message']);
          }
        }
      }
    }
    else {
      self::wd_fb_massage('error', 'not Facebook url');
    }
  }

  public static function check_fb_group_url() {
    // Help tool for find your group id http://lookup-id.com/
    $first_token = strtok(self::$content_url, '/');
    $id = $first_token;
    // If not set access token , get random from our's
    $rand_key = array_rand(self::$access_tokens);
    $access_token = self::$access_tokens[$rand_key];
    $fb_graph_url = str_replace(array(
                                  '{FB_ID}',
                                  '{EDGE}',
                                  '{ACCESS_TOKEN}',
                                  '{FIELDS}',
                                  '{LIMIT}',
                                  '{OTHER}',
                                ), array(
                                  $id,
                                  '',
                                  'access_token=' . $access_token . '&',
                                  '',
                                  '',
                                  '',
                                ), self::$graph_url);
    // Check if no errors with that id
    $data = self::decap_do_curl($fb_graph_url);
    if ( array_key_exists("error", $data) ) {
      if ( $data['error']['code'] == 4 ) {
        update_option('ffwd_limit_notice', 1);
      }
      self::wd_fb_massage('error', $data['error']['message']);
    }
    else {
      self::$id = $id;

      return;
    }
  }

  /**
   * Check if data exists for feed in DB
   *
   * @param $feed_id int feed id
   *
   * @return bool
  */
  public static function check_cache_exists( $feed_id ) {
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare('SELECT count(id) FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d"', intval($feed_id)));
    if(intval($count) !== 0) {
      return true;
    }
    return false;
  }

  /**
   * Set timeline type.
   * Set complite_timeline_data empty array.
   * Message_tags in message, with_tags in story.
   * Check if fb_type is group so set `feed` for edge, else set `posts`.
   * Replace params in graph url.
   */
  public static function timeline() {

    if(!class_exists('WDW_FFWD_Library')) {
      require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    }
    self::$timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    self::set_timeline_type();
    self::$complite_timeline_data = array();
    self::set_access_token();
    if ( self::$save ) {
      $save_db = self::save_db();
    }
    else {
      if ( self::$edit_feed ) {
        $save_db = self::edit_feed();
      }
      else {
        if ( self::$updateOnVersionChange ) {
          $save_db = self::update_version();
        }
        else {
          $save_db = self::$fb_id;
          self::$ajax_cache_action = 'update_cache_data';
        }
      }
    }
    if(!self::check_cache_exists( $save_db )) {
      self::$ajax_cache_action = 'set_cache_data';
    }
    if ( $save_db === 'false' ) {
        echo self::$wd_fb_error_massage;
    } else {
        echo json_encode(array(
                            "status" => "success",
                            "feed_id" => $save_db,
                            "token"=>self::$access_token,
                            "user_id" => self::$id,
                            "ajax_action" => self::$ajax_cache_action,
                            "content_type" => 'timeline'));
    }
    die;
  }

  /**
   * Update timeline cache data
  */
  public static function update_timeline_cache_data() {
    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    $iter = WDW_FFWD_Library::get('iter', 0);
    $fb_graph_url = isset($_POST['graph_url']) ? $_POST['graph_url']: '';
    $user_id = WDW_FFWD_Library::get('user_id');
    self::$id = $user_id;
    self::$fb_id = WDW_FFWD_Library::get('fb_id');
    if( !self::check_cache_exists(self::$fb_id) && $iter == 0 ) {
      self::set_timeline_cache_data();
      die;
    }
    global $wpdb;
    self::$fb_limit = $wpdb->get_var($wpdb->prepare('SELECT `limit` FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', self::$fb_id));

    self::$content = explode(',', self::get_content(self::$fb_id));
    self::$content_type = self::get_content_type(self::$fb_id);

    self::$timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    self::set_timeline_type();
    self::$complite_timeline_data = array();
    $data = array();
    self::set_access_token();

    if ( $fb_graph_url == '' ) {
      $edge = (self::$fb_type == 'group') ? 'feed' : ((self::$timeline_type == 'feed' || self::$timeline_type == 'others') ? 'feed' : 'posts');
      // @todo v3.3 api
      $fields = 'fields=id,status_type,message,message_tags,shares,place,story,story_tags,privacy,attachments{media_type,media,url},from{picture,name,link},event,comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,comment_count},created_time,updated_time&';
      $fb_graph_url = str_replace(array(
                                    '{FB_ID}',
                                    '{EDGE}',
                                    '{ACCESS_TOKEN}',
                                    '{FIELDS}',
                                    '{LIMIT}',
                                    '{OTHER}'
                                  ), array(
                                    $user_id,
                                    $edge,
                                    'access_token=' . self::$access_token . '&',
                                    $fields,
                                    'locale=' . get_locale() . '&limit=10&',
                                    ''
                                  ), self::$graph_url);
    }
    self::$media_db_current_count = self::get_db_data_count( self::$fb_id );
    if ( self::$media_db_current_count >= self::$fb_limit ) {
      self::$media_db_limit_full = true;
    }

    $data['data'] = self::complite_timeline($fb_graph_url, $iter);
    self::$data = $data;
    self::update_db();

    if ( self::$timeline_max_call_count > intval($iter) && self::$next_page && !self::$media_db_limit_full ) {
      self::$timeline_max_call_count--;
      echo json_encode(array("status" => "success", "next_page" => self::$next_page, "iter" => $iter, "fb_id" => self::$fb_id));
    } else {
      echo json_encode(array( "status" => "success", "next_page" => '', "iter" => $iter, "fb_id" => self::$fb_id ));
    }

    die;
  }

  /**
   * Insert timeline cache data
  */
  public static function set_timeline_cache_data() {
    global $wpdb;
    $iter = WDW_FFWD_Library::get('iter', 0);
    $fb_graph_url = isset($_POST['graph_url']) ? $_POST['graph_url']: '';
    $user_id = WDW_FFWD_Library::get('user_id');
    self::$fb_id = WDW_FFWD_Library::get('fb_id');
    self::$content = explode(',', self::get_content(self::$fb_id));
    self::$content_type = self::get_content_type(self::$fb_id);
    self::$fb_limit = $wpdb->get_var($wpdb->prepare('SELECT `limit` FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', self::$fb_id));
    self::$timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    self::set_timeline_type();
    self::$complite_timeline_data = array();
    $data = array();
    self::set_access_token();
    if ( $fb_graph_url == '' ) {
      $edge = (self::$fb_type == 'group') ? 'feed' : ((self::$timeline_type == 'feed' || self::$timeline_type == 'others') ? 'feed' : 'posts');
      // @todo v3.3 api
      $fields = 'fields=id,status_type,message,message_tags,shares,place,story,story_tags,privacy,attachments{media_type,media,url},from{picture,name,link},event,comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,comment_count},created_time,updated_time&';
      $fb_graph_url = str_replace(array(
                                    '{FB_ID}',
                                    '{EDGE}',
                                    '{ACCESS_TOKEN}',
                                    '{FIELDS}',
                                    '{LIMIT}',
                                    '{OTHER}'
                                  ), array(
                                    $user_id,
                                    $edge,
                                    'access_token=' . self::$access_token . '&',
                                    $fields,
                                    'locale=' . get_locale() . '&limit=10&',
                                    ''
                                  ), self::$graph_url);
    }
    if( defined('FFWD_NEED_UPDATE_CACHE') && FFWD_NEED_UPDATE_CACHE ) {
      self::$auto_update_feed = 1;
    }

    self::$media_db_current_count = self::get_db_data_count( self::$fb_id );
    if ( self::$media_db_current_count >= self::$fb_limit ) {
      self::$media_db_limit_full = true;
    }
    if ( self::$auto_update_feed == 1 ) {
      $id = self::$fb_id;
      $update_ids = array();
      $rows = $wpdb->get_results($wpdb->prepare('SELECT object_id, id FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
      foreach ( $rows as $row ) {
        $update_ids[$row->object_id] = $row->id;
      }
      $fb_graph_url_update = $fb_graph_url;
      $update_data = self::decap_do_curl($fb_graph_url_update);
      self::update_wd_fb_data($update_data, $update_ids);
    }
    else {
      /* When update schedule no need and cache exists */
      if( self::check_cache_exists(self::$fb_id) && $iter == 0 ) {
        echo json_encode(array("status" => "success", "next_page" => '', "iter" => 0, "fb_id" => self::$fb_id));
        die;
      }

      $data['data'] = self::complite_timeline($fb_graph_url, $iter);
      self::$data = $data;
      self::insert_wd_fb_data($data['data']);
    }
    if ( self::$timeline_max_call_count > intval($iter) && self::$next_page && !self::$media_db_limit_full ) {
      self::$timeline_max_call_count--;
      echo json_encode(array("status" => "success", "next_page" => self::$next_page, "iter" => $iter, "fb_id" => self::$fb_id, "non_public_share_count" => self::$non_public_share_count));
    } else {
      echo json_encode(array( "status" => "success", "next_page" => '', "iter" => $iter, "fb_id" => self::$fb_id, "non_public_share_count" => self::$non_public_share_count ));
    }
    die;

  }

  /* TODO need to understand how does it work, currently no result */
  public static function complite_timeline( $fb_graph_url, $iter = 0 ) {
    self::$non_public_share_count = WDW_FFWD_Library::get('non_public_share_count', 0);
    $content = implode(',', self::$content);
    if ( !empty(self::$content) ) {

      $content = '';
      foreach ( self::$content as $val ) {
        if ( $val == 'photos') {
          $content .= 'photo';
        }
        if ( $val == 'videos') {
          $content .= 'video,';
        }
        if ( $val == 'links') {
           $content .= 'link,';
        }
        if ( $val == 'albums') {
          $content .= 'album,';
        }
      }
      $content = trim($content, ',');
    }
    $data = self::decap_do_curl($fb_graph_url);
    if ( !empty($data) ) {
      // If error exist
      if ( array_key_exists('error', $data) ) {
        if ( $data['error']['code'] == 4 ) {
          update_option('ffwd_limit_notice', 1);
        }
        if ( $data['error']['code'] == 100 ) {
          self::wd_fb_massage('error', $data['error']['message'] . ' <a target="_blank" href="https://help.10web.io/hc/en-us/articles/360025514692-Solving-Facebook-Feed-Errors?utm_source=facebook_feed&utm_medium=free_plugin">See more</a>');
        }
        else {
          self::wd_fb_massage('error', $data['error']['message']);
        }
      }
      else {
        $post_data = !empty($data['data']) ? $data['data'] : array();
        if ( strpos($content, 'link') !== FALSE ){
          self::$non_public_share_count += self::get_link_type_count($post_data);
        }

        // Set next page if it exists
        $paging = array_key_exists('paging', $data) ? $data['paging'] : array();
        $next_page = array_key_exists('next', $paging) ? $paging['next'] : 0;
        self::$next_page = array_key_exists('next', $paging) ? $paging['next'] : 0;
        foreach ( $post_data as $next ) {
          // @todo v10.0 api new logic.
          if ( !empty($next['attachments']['data']) && !empty($next['attachments']['data'][0]) ) {
            $attachments = $next['attachments']['data'][0];
            $media_type = $attachments['media_type'];
            if ( strpos($content, $media_type) === FALSE) {
              continue;
            }
          }
          if ( !empty($next['attachments']['data']) &&  $next['attachments']['data'][0]['media_type'] === "link" && empty($next['attachments']['data'][0]['media']) ) {
            continue;
          }
          /* @TODO v3.3 deprecated
           * if (strpos($content, $next['type']) === false) {
           * continue;
           * }
           * if ($next['type'] == 'status' && !isset($next['description']) && !isset($next['message']) && !isset($next['name'])) {
           * continue;
           * }
           */
          if ( self::$timeline_type == 'others' && self::$id == $next['from']['id'] ) {
            continue;
          }
          if ( $next['privacy']['value'] && $next['privacy']['value'] != 'EVERYONE' ) {
            continue;
          }
          //  if ( count(self::$complite_timeline_data) < self::$limit ) {
          array_push(self::$complite_timeline_data, $next);
          // }
        }
        return self::$complite_timeline_data;
        // @todo what was this for?
        //  intval(count(self::$complite_timeline_data)) <= intval(self::$limit) &&
/*        if ( self::$timeline_max_call_count > 0 && $next_page ) {
          self::$timeline_max_call_count--;

          return self::complite_timeline($next_page);
        }
        else {
          return self::$complite_timeline_data;
        }*/
      }
    }
  }

  /**
   * Getting count of posts which are have media type link and not available
   *
   * @param $datas array posts which we got from endpoint
   *
   * @return int
   */
  public static function get_link_type_count( $datas ) {
    if( !empty($datas) ) {
      $count = 0;
      foreach ( $datas as $data ) {
        if ( !empty($data['attachments']) && !empty($data['attachments']['data'][0]) && $data['attachments']['data'][0]['media_type'] == 'link' && empty($data['attachments']['data'][0]['media']) ) {
          $count++;
        }
      }
    }
    return $count;
  }

  public static function update_wd_fb_data( $data, $ids ) {
    global $wpdb;
    if ( !empty($data['data']) ) {
      // Set next page if it exists
      $paging = array_key_exists('paging', $data) ? $data['paging'] : array();
      self::$next_page = array_key_exists('next', $paging) ? $paging['next'] : 0;
      foreach ( $data['data'] as $key => $next ) {
        $type = '';
        $source = '';
        $main_url = '';
        $thumb_url = '';
        $link = '';
        $width = '';
        $height = '';
        $attachments = (!empty($next['attachments']['data'][0])) ? $next['attachments']['data'][0] : array();
        if( !self::check_post_type( self::$content, $next ) ) {
          continue;
        }

        if ( !empty($attachments) ) {
          $type = $attachments['media_type'];
          // @todo API V10.0 Temporary solution for photos!
          if ( self::$content_type == 'specific' && self::$content[0] == 'photos' && $type != 'photo' ) {
            continue;
          }
          if ( !empty($next['attachments']['data']) &&  $next['attachments']['data'][0]['media_type'] === "link" && empty($next['attachments']['data'][0]['media']) ) {
            continue;
          }

          $link = !empty($attachments['url']) ? $attachments['url'] : '';
          if ( !empty($attachments['media']) ) {
            $media = $attachments['media'];
            $main_url = !empty($media['image']['src']) ? $media['image']['src'] : '';
            $thumb_url = !empty($media['image']['src']) ? $media['image']['src'] : '';
            $width = !empty($media['image']['width']) ? $media['image']['width'] : '';
            $height = !empty($media['image']['height']) ? $media['image']['height'] : '';
          }
        }
        else if ( self::$content_type == 'specific' ) {
          $type = self::$content[0];
        }

        // Use this var for check if album imgs count not 0
        $album_imgs_exists = true;
        switch ($type) {
          case 'photos': {
            /* @todo v3.3 API  deprecated.
            // * If object type is photo(photos, video, videos,
            // * album, event cover photo etc ) so trying to
            // * check the count of resolution types
            // * and store source for thumb and main size

            if ( array_key_exists('images', $next) ) {
            $images = count($next['images']);
            if ($images > 6 ) {
            $thumb_url = $next['images'][$images - 1]['source'];
            $main_url = $next['images'][0]['source'];
            } else {
            $thumb_url = $next['images'][0]['source'];
            $main_url = $next['images'][0]['source'];
            }
            $width = $next['images'][0]['width'];
            $height = $next['images'][0]['height'];
            }
             */
            break;
          }
          case 'videos': {
            $name = array_key_exists('title', $next) ? addcslashes($next['title'], '\\') : '';
            $link = array_key_exists('permalink_url', $next) ? 'https://www.facebook.com' . $next['permalink_url'] : '';
            if (array_key_exists('format', $next)) {
              $img_res_count = count($next['format']);
              if ($img_res_count > 2) {
                $main_url = $next['format'][$img_res_count - 1]['picture'];
                $thumb_url = $next['format'][1]['picture'];
              } else {
                $thumb_url = $next['format'][$img_res_count - 1]['picture'];
                $main_url = $next['format'][$img_res_count - 1]['picture'];
              }
              $width = $next['format'][$img_res_count - 1]['width'];
              $height = $next['format'][$img_res_count - 1]['height'];
            }
            break;
          }
          case 'albums': {
            if (array_key_exists('count', $next)) {
              $album_imgs_count = $next['count'];
              if ($album_imgs_count == 0) {
                $album_imgs_exists = false;
              }
            }

            break;
          }
          // @todo V10.0 API
          case 'video': {
            $type ='videos';
            if ( !empty($attachments['media']['source']) ) {
              $source = $attachments['media']['source'];
            }
            /* @todo v3.3 API  deprecated.
            if (array_key_exists('format', $next)) {
            $img_res_count = count($next['format']);
            if ($img_res_count > 2) {
            $main_url = $next['format'][$img_res_count - 1]['picture'];
            $thumb_url = $next['format'][1]['picture'];
            } else {
            $thumb_url = $next['format'][$img_res_count - 1]['picture'];
            $main_url = $next['format'][$img_res_count - 1]['picture'];
            }
            $width = $next['format'][$img_res_count - 1]['width'];
            $height = $next['format'][$img_res_count - 1]['height'];
            }
             */
            break;
          }
          // @todo V10.0 API
          case 'album': {
            $type = 'albums';
            /* @todo v3.3 API  deprecated.
            if (array_key_exists('count', $next)) {
            $album_imgs_count = $next['count'];
            if ($album_imgs_count == 0) {
            $album_imgs_exists = false;
            }
            }
             */
            break;
          }
        }
        // @todo In the case of 'albums' it works in JS version․
        if ( $type == 'albums' && !$album_imgs_exists ) {
          continue;
        }
        // Check if exists such keys in $next array
        $object_id = array_key_exists('id', $next) ? $next['id'] : '';

        $name = array_key_exists('name', $next) ? addcslashes($next['name'], '\\') : '';
        $description = array_key_exists('description', $next) ? addcslashes($next['description'], '\\') : '';
        //  @todo v3.3 API
        //  $source = array_key_exists('source', $next) ? $next['source'] : '';
        //  $link = array_key_exists('link', $next) ? $next['link'] : '';
        $status_type = array_key_exists('status_type', $next) ? $next['status_type'] : '';
        $message = array_key_exists('message', $next) ? addcslashes($next['message'], '\\') : '';
        $story = array_key_exists('story', $next) ? $next['story'] : '';
        $place = array_key_exists('place', $next) ? json_encode($next['place']) : '';
        $message_tags = array_key_exists('message_tags', $next) ? json_encode($next['message_tags']) : '';
        $with_tags = array_key_exists('with_tags', $next) ? json_encode($next['with_tags']) : '';
        $story_tags = array_key_exists('story_tags', $next) ? json_encode($next['story_tags']) : '';
        $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';
        $comments = array_key_exists('comments', $next) ? json_encode($next['comments']) : '';
        $shares = array_key_exists('shares', $next) ? json_encode($next['shares']) : '';
        $attachments = array_key_exists('attachments', $next) ? json_encode($next['attachments']) : '';
        $from = array_key_exists('from', $next) ? $next['from']['id'] : '';
        $from_json = array_key_exists('from', $next) ? json_encode($next['from']) : '';
        $updated_time = array_key_exists('updated_time', $next) ? $next['updated_time'] : '';
        $created_time = array_key_exists('created_time', $next) ? $next['created_time'] : '';
        // When content is events some fields have different names, so check them.
        if ( $type == 'events' ) {
          $source = array_key_exists('cover', $next) ? $next['cover']['source'] : '';
          $main_url = $source;
          $thumb_url = $main_url;
          $from = array_key_exists('owner', $next) ? $next['owner']['id'] : '';
          $from_json = array_key_exists('owner', $next) ? json_encode($next['owner']) : '';
          // Store event end time in update_time field
          $updated_time = array_key_exists('end_time', $next) ? $next['end_time'] : '';
          $created_time = array_key_exists('start_time', $next) ? $next['start_time'] : '';
        }
        $created_time_number = ($created_time != '') ? strtotime($created_time) : 0;
        $format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');


        $feed_data = array(
          'fb_id' => self::$fb_id,
          'object_id' => $object_id,
          'from' => $from,
          'name' => $name,
          'description' => $description,
          'type' => $type,
          'message' => $message,
          'story' => $story,
          'place' => $place,
          'message_tags' => $message_tags,
          'with_tags' => $with_tags,
          'story_tags' => $story_tags,
          'status_type' => $status_type,
          'link' => $link,
          'source' => $source,
          'thumb_url' => $thumb_url,
          'main_url' => $main_url,
          'width' => $width,
          'height' => $height,
          'created_time' => $created_time,
          'updated_time' => $updated_time,
          'created_time_number' => $created_time_number,
          'comments' => $comments,
          'shares' => $shares,
          'attachments' => $attachments,
          'who_post' => $from_json,
          'reactions' => $reactions,
        );

        /* Second argument : Case when media count in db is equal to option posts limit */
        if ( !isset($ids[$object_id]) ) {
          if ( self::$media_db_limit_full ) {
            continue;
          }
          $wpdb->insert($wpdb->prefix . 'wd_fb_data', $feed_data, $format);
            /* increment number of medias to keep equal with DB count */
            self::$media_db_current_count++;
            if( self::$media_db_current_count >= self::$fb_limit ) {
              self::$media_db_limit_full = true;
            }
        } else {
          $wpdb->update($wpdb->prefix . 'wd_fb_data', $feed_data, array( 'id' => $ids[$object_id] ), $format);
        }
      }
    }
  }

  /**
   * check if the post have type which mention in feed settings (Statuses, Photos, Videos, Links)
   *
   * @param $post_types array
   * @param $post_data array
   *
   * @return bool
  */
  public static function check_post_type( $post_types, $post_data ) {
    /* In specific album and video we are getting only albums or videos from endpoint, so no need to check post type */
    if(self::$content_type === 'specific' && isset(self::$content[0]) && (self::$content[0] === 'albums' || self::$content[0] === 'videos')) {
      return true;
    }
    $media_type = (!empty($post_data['attachments']['data'][0])) ? $post_data['attachments']['data'][0]['media_type'] : '';
    foreach ( $post_types as $post_type ) {
      if ( $post_type == "statuses" ) {
        if( empty($media_type) && isset($post_data['message']) && $post_data['message'] !== '' ) {
          return true;
        }
      }
      if ( $post_type == "photos" ) {
        if( !empty($media_type) && $media_type == 'photo') {
          return true;
        }
      }
      if ( $post_type == "videos" ) {
        if( !empty($media_type) && $media_type == 'video') {
          return true;
        }
      }
      if ( $post_type == "links" ) {
        if( !empty($media_type) && $media_type == 'link') {
          return true;
        }
      }
      if ( $post_type == "albums" ) {
        if( !empty($media_type) && $media_type == 'album') {
          return true;
        }
      }
    }
    return FALSE;
  }

  public static function ffwd_event_data_sort( $a, $b ) {
    $date1 = strtotime($a['start_time']);
    $date2 = strtotime($b['start_time']);
    if ( $date1 == $date2 ) {
      return 0;
    }

    return ($date1 > $date2) ? -1 : 1;
  }

  public static function filter_upcoming_events( $data ) {
    foreach ( $data['data'] as $key => $event ) {
      $event_start_time = strtotime($event['start_time']);
      $now = strtotime(date('Y-m-d H:i:s'));
      if ( $event_start_time < $now ) {
        unset($data['data'][$key]);
      }
    }

    return $data;
  }

  /**
   * Define timeline type only for not being null.
   * Set fields.
   * Chaek if content is photo or videos, so replace {other} => type=uploaded.
   * Replace params in graph url.
   * Check errors.
   */
  public static function specific() {
    self::$timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    self::set_timeline_type();
    self::$complite_timeline_data = array();
    self::set_access_token();

    if ( self::$save ) {
      $save_db = self::save_db();
    }
    else {
      if ( self::$edit_feed ) {
        $save_db = self::edit_feed();
      }
      else {
        if ( self::$updateOnVersionChange ) {
          $save_db = self::update_version();
        }
        else {
          $save_db = self::$fb_id;
          self::$ajax_cache_action = 'update_cache_data';
/*          self::update_db();*/
        }
      }
    }
    if ( $save_db === 'false' ) {
      echo self::$wd_fb_error_massage;
    } else {
      self::$ajax_cache_action = 'set_cache_data';
      echo json_encode(array(
                         "status" => "success",
                         "feed_id" => $save_db,
                         "token"=>self::$access_token,
                         "user_id" => self::$id,
                         "ajax_action" => self::$ajax_cache_action));
    }
    die;
  }

  /**
   * Insert specific cache data
  */
  public static function set_specific_cache_data() {
    self::$timeline_max_call_count = WD_FB_TIMELINE_MAX_CALL_COUNT;
    global $wpdb;
    self::$timeline_type = '';
    $other = '';
    $iter = WDW_FFWD_Library::get('iter', 0);
    self::$fb_id =  WDW_FFWD_Library::get('fb_id');
    self::$id = WDW_FFWD_Library::get('user_id');
    self::$access_token =  WDW_FFWD_Library::get('page_access_token');
    self::$fb_limit = $wpdb->get_var($wpdb->prepare('SELECT `limit` FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', self::$fb_id));
    self::$content = explode(',', self::get_content(self::$fb_id));
    self::$content_type = self::get_content_type(self::$fb_id);
    /* TODO sanitise graph_url */
    $fb_graph_url = isset($_POST['graph_url']) ? $_POST['graph_url']: '';
    if( $fb_graph_url == '' ) {
      if ( self::$content[0] == 'albums' ) {
        $fields = 'fields=id,name,from{picture,name,link},link,count,created_time,updated_time&';
      }
      else {
        if ( self::$content[0] == 'photos' ) {
          $fields = 'fields=source,name,link,images,from{picture,name,link},created_time,updated_time&';
        }
        else {
          if ( self::$content[0] == 'videos' ) {
            $fields = 'fields=id,title,description,picture,permalink_url,format,from{picture,name,link},created_time,updated_time&';
          }
          else {
            if ( self::$content[0] == 'events' ) {
              if ( self::$upcoming_events == 1 ) {
                $fields = 'fields=comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,message,comment_count},name,start_time,end_time,event_times,description,cover,owner{picture,name,link},place&since=' . strtotime(date('Y-m-d')) . '&';
              }
              else {
                $fields = 'fields=comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,message,comment_count},name,start_time,end_time,event_times,description,cover,owner{picture,name,link},place&';
              }
            }
            else {
              $fields = 'fields=source,name,story,created_time,updated_time,from{picture,name,link},link&';
            }
          }
        }
      }
      if ( self::$content[0] == 'photos' || self::$content[0] == 'videos' ) {
        // @todo 'type=uploaded' dnon't work  https://developers.facebook.com/support/bugs/135549845263767/?join_id=f28633b0543591
        // $other = 'type=uploaded';
      }
      $edge = self::$content[0];
      // @todo API V10.0 Temporary solution for photos!
      if ( self::$content[0] == 'photos' ) {
        $edge = 'posts';
        $fields = 'fields=id,status_type,message,message_tags,shares,place,story,story_tags,privacy,attachments{media_type,media,url},from{picture,name,link},created_time,updated_time&';
      }
      $fb_graph_url = str_replace(array(
                                    '{FB_ID}',
                                    '{EDGE}',
                                    '{ACCESS_TOKEN}',
                                    '{FIELDS}',
                                    '{LIMIT}',
                                    '{OTHER}',
                                  ), array(
                                    self::$id,
                                    $edge,
                                    'access_token=' . self::$access_token . '&',
                                    $fields,
                                    'locale=' . get_locale() . '&',
                                    $other,
                                  ), self::$graph_url);
    }
    if( defined('FFWD_NEED_UPDATE_CACHE') && FFWD_NEED_UPDATE_CACHE ) {
      self::$auto_update_feed = 1;
    }

    self::$media_db_current_count = self::get_db_data_count( self::$fb_id );
    if( self::$media_db_current_count >= self::$fb_limit ) {
      self::$media_db_limit_full = true;
    }

    if ( self::$auto_update_feed == 1 ) {
      $id = self::$fb_id;
      $update_ids = array();
      $rows = $wpdb->get_results($wpdb->prepare('SELECT object_id,id FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
      foreach ( $rows as $row ) {
        $update_ids[$row->object_id] = $row->id;
      }
      $fb_graph_url_update = $fb_graph_url;
      $update_data = self::decap_do_curl($fb_graph_url_update);
      $paging = array_key_exists('paging', $update_data) ? $update_data['paging'] : array();
      self::$next_page = array_key_exists('next', $paging) ? $paging['next'] : 0;
      self::update_wd_fb_data($update_data, $update_ids);
    }
    else {
      if( self::check_cache_exists(self::$fb_id) ) {
        echo json_encode(array("status" => "success", "next_page" => '', "iter" => 0, "fb_id" => self::$fb_id));
        die;
      }
      $data = self::decap_do_curl($fb_graph_url);
      $paging = array_key_exists('paging', $data) ? $data['paging'] : array();
      self::$next_page = array_key_exists('next', $paging) ? $paging['next'] : 0;
      self::$data = $data;
      self::insert_wd_fb_data($data['data']);
    }

    if ( self::$timeline_max_call_count > intval($iter) && self::$next_page && !self::$media_db_limit_full ) {
      self::$timeline_max_call_count--;
      echo json_encode(array("status" => "success", "next_page" => self::$next_page, "iter" => $iter, "fb_id" => self::$fb_id));
    } else {
      echo json_encode(array( "status" => "success", "next_page" => '', "iter" => $iter, "fb_id" => self::$fb_id ));
    }
    die;
  }

  /**
   * Set timeline type.
   * Posts by owner (so edge is posts).
   * Posts by others (so edge is feed).
   * Posts by owner and others (so edge is feed (but data must be filtered by from atribute not equal to owner ID)).
   */
    public static function set_timeline_type() {
      if (self::$save || self::$edit_feed)
          self::$timeline_type = (isset($_POST['timeline_type']) && $_POST['timeline_type'] != '') ? sanitize_text_field(stripcslashes($_POST['timeline_type'])) : 'posts';

      return;
    }

    public static function save_db() {
        global $wpdb;
        $name = ((isset($_POST['name'])) ? sanitize_text_field(stripslashes($_POST['name'])) : '');
        $name = str_replace(array("'", '"'), "" , $name);
        $page_access_token = ((isset($_POST['page_access_token'])) ? sanitize_text_field(stripslashes($_POST['page_access_token'])) : '');
        $update_mode = ((isset($_POST['update_mode'])) ? sanitize_text_field(stripslashes($_POST['update_mode'])) : '');
        // Collapse content types (multiple when content type is timeline, one when specific)
        $content = implode(",", self::$content);
        $from = self::$id;

        $ffwd_info_options = array();
        $ffwd_info_options['theme'] = ((isset($_POST['theme'])) ? sanitize_text_field(stripslashes($_POST['theme'])) : '');
        $ffwd_info_options['masonry_hor_ver'] = ((isset($_POST['masonry_hor_ver'])) ? sanitize_text_field(stripslashes($_POST['masonry_hor_ver'])) : '');
        $ffwd_info_options['image_max_columns'] = ((isset($_POST['image_max_columns'])) ? sanitize_text_field(stripslashes($_POST['image_max_columns'])) : '');
        $ffwd_info_options['thumb_width'] = ((isset($_POST['thumb_width'])) ? sanitize_text_field(stripslashes($_POST['thumb_width'])) : '');
        $ffwd_info_options['thumb_height'] = ((isset($_POST['thumb_height'])) ? sanitize_text_field(stripslashes($_POST['thumb_height'])) : '');
        $ffwd_info_options['thumb_comments'] = ((isset($_POST['thumb_comments'])) ? sanitize_text_field(stripslashes($_POST['thumb_comments'])) : '');
        $ffwd_info_options['thumb_likes'] = ((isset($_POST['thumb_likes'])) ? sanitize_text_field(stripslashes($_POST['thumb_likes'])) : '');
        $ffwd_info_options['thumb_name'] = ((isset($_POST['thumb_name'])) ? sanitize_text_field(stripslashes($_POST['thumb_name'])) : '');
        $ffwd_info_options['blog_style_width'] = ((isset($_POST['blog_style_width'])) ? sanitize_text_field(stripslashes($_POST['blog_style_width'])) : '');
        $ffwd_info_options['blog_style_height'] = ((isset($_POST['blog_style_height'])) ? sanitize_text_field(stripslashes($_POST['blog_style_height'])) : '');
        $ffwd_info_options['blog_style_view_type'] = ((isset($_POST['blog_style_view_type'])) ? sanitize_text_field(stripslashes($_POST['blog_style_view_type'])) : '');
        $ffwd_info_options['blog_style_comments'] = ((isset($_POST['blog_style_comments'])) ? sanitize_text_field(stripslashes($_POST['blog_style_comments'])) : '');
        $ffwd_info_options['blog_style_likes'] = ((isset($_POST['blog_style_likes'])) ? sanitize_text_field(stripslashes($_POST['blog_style_likes'])) : '');
        $ffwd_info_options['blog_style_message_desc'] = ((isset($_POST['blog_style_message_desc'])) ? sanitize_text_field(stripslashes($_POST['blog_style_message_desc'])) : '');
        $ffwd_info_options['blog_style_shares'] = ((isset($_POST['blog_style_shares'])) ? sanitize_text_field(stripslashes($_POST['blog_style_shares'])) : '');
        $ffwd_info_options['blog_style_shares_butt'] = ((isset($_POST['blog_style_shares_butt'])) ? sanitize_text_field(stripslashes($_POST['blog_style_shares_butt'])) : '');
        $ffwd_info_options['blog_style_facebook'] = ((isset($_POST['blog_style_facebook'])) ? sanitize_text_field(stripslashes($_POST['blog_style_facebook'])) : '');
        $ffwd_info_options['blog_style_twitter'] = ((isset($_POST['blog_style_twitter'])) ? sanitize_text_field(stripslashes($_POST['blog_style_twitter'])) : '');
        $ffwd_info_options['blog_style_google'] = '0';
        $ffwd_info_options['blog_style_author'] = ((isset($_POST['blog_style_author'])) ? sanitize_text_field(stripslashes($_POST['blog_style_author'])) : '');
        $ffwd_info_options['blog_style_name'] = ((isset($_POST['blog_style_name'])) ? sanitize_text_field(stripslashes($_POST['blog_style_name'])) : '');
        $ffwd_info_options['blog_style_place_name'] = ((isset($_POST['blog_style_place_name'])) ? sanitize_text_field(stripslashes($_POST['blog_style_place_name'])) : '');
        $ffwd_info_options['fb_name'] = ((isset($_POST['fb_name'])) ? sanitize_text_field(stripslashes($_POST['fb_name'])) : '');
        $ffwd_info_options['fb_plugin'] = ((isset($_POST['fb_plugin'])) ? sanitize_text_field(stripslashes($_POST['fb_plugin'])) : '');
        $ffwd_info_options['album_max_columns'] = ((isset($_POST['album_max_columns'])) ? sanitize_text_field(stripslashes($_POST['album_max_columns'])) : '');
        $ffwd_info_options['album_title'] = ((isset($_POST['album_title'])) ? sanitize_text_field(stripslashes($_POST['album_title'])) : '');
        $ffwd_info_options['album_thumb_width'] = ((isset($_POST['album_thumb_width'])) ? sanitize_text_field(stripslashes($_POST['album_thumb_width'])) : '');
        $ffwd_info_options['album_thumb_height'] = ((isset($_POST['album_thumb_height'])) ? sanitize_text_field(stripslashes($_POST['album_thumb_height'])) : '');
        $ffwd_info_options['album_image_max_columns'] = ((isset($_POST['album_image_max_columns'])) ? sanitize_text_field(stripslashes($_POST['album_image_max_columns'])) : '');
        $ffwd_info_options['album_image_thumb_width'] = ((isset($_POST['album_image_thumb_width'])) ? sanitize_text_field(stripslashes($_POST['album_image_thumb_width'])) : '');
        $ffwd_info_options['album_image_thumb_height'] = ((isset($_POST['album_image_thumb_height'])) ? sanitize_text_field(stripslashes($_POST['album_image_thumb_height'])) : '');
        $ffwd_info_options['pagination_type'] = ((isset($_POST['pagination_type'])) ? sanitize_text_field(stripslashes($_POST['pagination_type'])) : '');
        $ffwd_info_options['objects_per_page'] = ((isset($_POST['objects_per_page'])) ? sanitize_text_field(stripslashes($_POST['objects_per_page'])) : '');
        $ffwd_info_options['popup_fullscreen'] = ((isset($_POST['popup_fullscreen'])) ? sanitize_text_field(stripslashes($_POST['popup_fullscreen'])) : '');
        $ffwd_info_options['popup_height'] = ((isset($_POST['popup_height'])) ? sanitize_text_field(stripslashes($_POST['popup_height'])) : '');
        $ffwd_info_options['popup_width'] = ((isset($_POST['popup_width'])) ? sanitize_text_field(stripslashes($_POST['popup_width'])) : '');
        $ffwd_info_options['popup_effect'] = ((isset($_POST['popup_effect'])) ? sanitize_text_field(stripslashes($_POST['popup_effect'])) : '');
        $ffwd_info_options['popup_autoplay'] = ((isset($_POST['popup_autoplay'])) ? sanitize_text_field(stripslashes($_POST['popup_autoplay'])) : '');
        $ffwd_info_options['open_commentbox'] = ((isset($_POST['open_commentbox'])) ? sanitize_text_field(stripslashes($_POST['open_commentbox'])) : '');
        $ffwd_info_options['popup_interval'] = ((isset($_POST['popup_interval'])) ? sanitize_text_field(stripslashes($_POST['popup_interval'])) : '');
        $ffwd_info_options['popup_enable_filmstrip'] = ((isset($_POST['popup_enable_filmstrip'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_filmstrip'])) : '');
        $ffwd_info_options['popup_filmstrip_height'] = ((isset($_POST['popup_filmstrip_height'])) ? sanitize_text_field(stripslashes($_POST['popup_filmstrip_height'])) : '');
        $ffwd_info_options['popup_comments'] = ((isset($_POST['popup_comments'])) ? sanitize_text_field(stripslashes($_POST['popup_comments'])) : '');
        $ffwd_info_options['popup_likes'] = ((isset($_POST['popup_likes'])) ? sanitize_text_field(stripslashes($_POST['popup_likes'])) : '');
        $ffwd_info_options['popup_shares'] = ((isset($_POST['popup_shares'])) ? sanitize_text_field(stripslashes($_POST['popup_shares'])) : '');
        $ffwd_info_options['popup_author'] = ((isset($_POST['popup_author'])) ? sanitize_text_field(stripslashes($_POST['popup_author'])) : '');
        $ffwd_info_options['popup_name'] = ((isset($_POST['popup_name'])) ? sanitize_text_field(stripslashes($_POST['popup_name'])) : '');
        $ffwd_info_options['popup_place_name'] = ((isset($_POST['popup_place_name'])) ? sanitize_text_field(stripslashes($_POST['popup_place_name'])) : '');
        $ffwd_info_options['popup_enable_ctrl_btn'] = ((isset($_POST['popup_enable_ctrl_btn'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_ctrl_btn'])) : '');
        $ffwd_info_options['popup_enable_fullscreen'] = ((isset($_POST['popup_enable_fullscreen'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_fullscreen'])) : '');
        $ffwd_info_options['popup_enable_info_btn'] = ((isset($_POST['popup_enable_info_btn'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_info_btn'])) : '');
        $ffwd_info_options['popup_message_desc'] = ((isset($_POST['popup_message_desc'])) ? sanitize_text_field(stripslashes($_POST['popup_message_desc'])) : '');
        $ffwd_info_options['popup_enable_facebook'] = ((isset($_POST['popup_enable_facebook'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_facebook'])) : '');
        $ffwd_info_options['popup_enable_twitter'] = ((isset($_POST['popup_enable_twitter'])) ? sanitize_text_field(stripslashes($_POST['popup_enable_twitter'])) : '');
        $ffwd_info_options['popup_enable_google'] = '0';
        $ffwd_info_options['fb_view_type'] = ((isset($_POST['fb_view_type'])) ? sanitize_text_field(stripslashes($_POST['fb_view_type'])) : '');
        $ffwd_info_options['image_onclick_action'] = ((isset($_POST['image_onclick_action'])) ? sanitize_text_field(stripslashes($_POST['image_onclick_action'])) : 'lightbox');

        $ffwd_options_db = array('view_on_fb', 'post_text_length', 'event_street', 'event_city', 'event_country', 'event_zip', 'event_map', 'event_date', 'event_desp_length', 'comments_replies', 'comments_filter', 'comments_order', 'page_plugin_pos', 'page_plugin_fans', 'page_plugin_cover', 'page_plugin_header', 'page_plugin_width', 'event_order', 'upcoming_events', 'fb_page_id');

        foreach ( $ffwd_options_db as $ffwd_option_db ) {
          $ffwd_info_options[$ffwd_option_db] = ((isset($_POST[$ffwd_option_db])) ? sanitize_text_field(stripslashes($_POST[$ffwd_option_db])) : '');
        }

        if (self::$fb_type == 'group') {
            self::$timeline_type = 'feed';
        }
        $save_fb_info = $wpdb->insert($wpdb->prefix . 'wd_fb_info', array(
            'name'                     => $name,
            'page_access_token'        => $page_access_token,
            'type'                     => self::$fb_type,
            'content_type'             => self::$content_type,
            'content'                  => $content,
            'content_url'              => self::$content_url,
            'timeline_type'            => self::$timeline_type,
            'from'                     => $from,
            'limit'                    => self::$limit,
            'app_id'                   => '',
            'app_secret'               => '',
            'exist_access'             => 1,
            'access_token'             => self::$access_token,
            'order'                    => ((int)$wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'wd_fb_info')) + 1,
            'published'                => 1,
            'update_mode'              => $update_mode,
            'theme'                    => $ffwd_info_options['theme'],
            'masonry_hor_ver'          => $ffwd_info_options['masonry_hor_ver'],
            'image_max_columns'        => $ffwd_info_options['image_max_columns'],
            'thumb_width'              => $ffwd_info_options['thumb_width'],
            'thumb_height'             => $ffwd_info_options['thumb_height'],
            'thumb_comments'           => $ffwd_info_options['thumb_comments'],
            'thumb_likes'              => $ffwd_info_options['thumb_likes'],
            'thumb_name'               => $ffwd_info_options['thumb_name'],
            'blog_style_width'         => $ffwd_info_options['blog_style_width'],
            'blog_style_height'        => $ffwd_info_options['blog_style_height'],
            'blog_style_view_type'     => $ffwd_info_options['blog_style_view_type'],
            'blog_style_comments'      => $ffwd_info_options['blog_style_comments'],
            'blog_style_likes'         => $ffwd_info_options['blog_style_likes'],
            'blog_style_message_desc'  => $ffwd_info_options['blog_style_message_desc'],
            'blog_style_shares'        => $ffwd_info_options['blog_style_shares'],
            'blog_style_shares_butt'   => $ffwd_info_options['blog_style_shares_butt'],
            'blog_style_facebook'      => $ffwd_info_options['blog_style_facebook'],
            'blog_style_twitter'       => $ffwd_info_options['blog_style_twitter'],
            'blog_style_google'        => $ffwd_info_options['blog_style_google'],
            'blog_style_author'        => $ffwd_info_options['blog_style_author'],
            'blog_style_name'          => $ffwd_info_options['blog_style_name'],
            'blog_style_place_name'    => $ffwd_info_options['blog_style_place_name'],
            'fb_name'                  => $ffwd_info_options['fb_name'],
            'fb_plugin'                => $ffwd_info_options['fb_plugin'],
            'album_max_columns'        => $ffwd_info_options['album_max_columns'],
            'album_title'              => $ffwd_info_options['album_title'],
            'album_thumb_width'        => $ffwd_info_options['album_thumb_width'],
            'album_thumb_height'       => $ffwd_info_options['album_thumb_height'],
            'album_image_max_columns'  => $ffwd_info_options['album_image_max_columns'],
            'album_image_thumb_width'  => $ffwd_info_options['album_image_thumb_width'],
            'album_image_thumb_height' => $ffwd_info_options['album_image_thumb_height'],
            'pagination_type'          => $ffwd_info_options['pagination_type'],
            'objects_per_page'         => $ffwd_info_options['objects_per_page'],
            'popup_fullscreen'         => $ffwd_info_options['popup_fullscreen'],
            'popup_height'             => $ffwd_info_options['popup_height'],
            'popup_width'              => $ffwd_info_options['popup_width'],
            'popup_effect'             => $ffwd_info_options['popup_effect'],
            'popup_autoplay'           => $ffwd_info_options['popup_autoplay'],
            'open_commentbox'          => $ffwd_info_options['open_commentbox'],
            'popup_interval'           => $ffwd_info_options['popup_interval'],
            'popup_enable_filmstrip'   => $ffwd_info_options['popup_enable_filmstrip'],
            'popup_filmstrip_height'   => $ffwd_info_options['popup_filmstrip_height'],
            'popup_comments'           => $ffwd_info_options['popup_comments'],
            'popup_likes'              => $ffwd_info_options['popup_likes'],
            'popup_shares'             => $ffwd_info_options['popup_shares'],
            'popup_author'             => $ffwd_info_options['popup_author'],
            'popup_name'               => $ffwd_info_options['popup_name'],
            'popup_place_name'         => $ffwd_info_options['popup_place_name'],
            'popup_enable_ctrl_btn'    => $ffwd_info_options['popup_enable_ctrl_btn'],
            'popup_enable_fullscreen'  => $ffwd_info_options['popup_enable_fullscreen'],
            'popup_enable_info_btn'    => $ffwd_info_options['popup_enable_info_btn'],
            'popup_message_desc'       => $ffwd_info_options['popup_message_desc'],
            'popup_enable_facebook'    => $ffwd_info_options['popup_enable_facebook'],
            'popup_enable_twitter'     => $ffwd_info_options['popup_enable_twitter'],
            'popup_enable_google'      => $ffwd_info_options['popup_enable_google'],
            'fb_view_type'             => $ffwd_info_options['fb_view_type'],
            'view_on_fb'               => $ffwd_info_options['view_on_fb'],
            'post_text_length'         => $ffwd_info_options['post_text_length'],
            'event_street'             => $ffwd_info_options['event_street'],
            'event_city'               => $ffwd_info_options['event_city'],
            'event_country'            => $ffwd_info_options['event_country'],
            'event_zip'                => $ffwd_info_options['event_zip'],
            'event_map'                => $ffwd_info_options['event_map'],
            'event_date'               => $ffwd_info_options['event_date'],
            'event_desp_length'        => $ffwd_info_options['event_desp_length'],
            'comments_replies'         => $ffwd_info_options['comments_replies'],
            'comments_filter'          => $ffwd_info_options['comments_filter'],
            'comments_order'           => $ffwd_info_options['comments_order'],
            'page_plugin_pos'          => $ffwd_info_options['page_plugin_pos'],
            'page_plugin_fans'         => $ffwd_info_options['page_plugin_fans'],
            'page_plugin_cover'        => $ffwd_info_options['page_plugin_cover'],
            'page_plugin_header'       => $ffwd_info_options['page_plugin_header'],
            'page_plugin_width'        => $ffwd_info_options['page_plugin_width'],
            'image_onclick_action'     => $ffwd_info_options['image_onclick_action'],
            'event_order'              => $ffwd_info_options['event_order'],
            'upcoming_events'          => $ffwd_info_options['upcoming_events'],
            'fb_page_id'               => $ffwd_info_options['fb_page_id'],
        ), array(
            '%s',//name
            '%s',//page_access_token
            '%s',//type
            '%s',//content_type
            '%s',//content
            '%s',//content_url
            '%s',//timeline_type
            '%s',//from
            '%d',//limit
            '%s',//app_id
            '%s',//app_secret
            '%d',//exist_access
            '%s',//access_token
            '%d',//order
            '%d',//published
            '%s',//update_mode
        ));

        /**
         * Get last inserted id from wd_fb_info for table bellow.
         * Insert into type column the
         * first and only value of self::$content array.
         * Escape paging in self::data
         */

        if ( $save_fb_info !== false ) {
          self::$fb_id = $wpdb->insert_id;
          self::$ajax_cache_action = "set_cache_data";
          return self::$fb_id;
        }
        else {
          self::$wd_fb_error_massage = json_encode(array('status'=>'error','msg'=>'Problem with save fb feed'));
          return false;
        }
    }

    public static function get_content( $id ) {
      global $wpdb;
      $var = $wpdb->get_var($wpdb->prepare('SELECT content FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', $id));
      return $var;
    }

    public static function get_content_type( $id ) {
      global $wpdb;
      $var = $wpdb->get_var($wpdb->prepare('SELECT content_type FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', $id));
      return $var;
    }

    /**
     * Get count of medias in DB for current feed
     *
     * @param $id int feed id
     *
     * return int
    */
    public static function get_db_data_count( $id ) {
      global $wpdb;
      $count = $wpdb->get_var($wpdb->prepare('SELECT count(id) from ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id = %d', $id));
      return $count;
    }

    /**
     * Insert data from facebook response to DataBase
     *
     * @param $data
     */
    public static function insert_wd_fb_data($data) {

      global $wpdb;
      $success = 'no_data';
      if ( self::$content_type == 'specific' && self::$content[0] == 'events' ) {
        $start_time = array();
        foreach ($data as $key => $event) {
          if (isset($event['event_times'])) {
            $event_times = $event['event_times'];
            foreach ($event_times as $event_time) {
              $event['start_time'] = $event_time['start_time'];
              $event['end_time'] = $event_time['end_time'];
              $data[] = $event;
              unset($data[$key]);
            }
          }
        }

        $added_events = 0;
        foreach ($data as $key => $event) {
          $start_time[$key] = $data['start_time'];
        }
        array_multisort($start_time, SORT_DESC, $data);
      }
      foreach ( $data as $key => $next ) {
        /* Case when media count in db is equal to option posts limit */
        if ( self::$media_db_limit_full ) {
          break;
        }
        $type = '';
        $source = '';
        $main_url = '';
        $thumb_url = '';
        $link = '';
        $width = '';
        $height = '';
        $attachments = (!empty($next['attachments']['data'][0])) ? $next['attachments']['data'][0] : array();
        if ( self::$content_type == 'timeline' && !self::check_post_type( self::$content, $next ) ) {
          continue;
        }
        if( self::$content_type == 'specific' && self::$content[0] == 'photos' && empty($attachments)) {
          continue;
        }
        if ( !empty($attachments) ) {
          $type = $attachments['media_type'];
          // @todo API V10.0 Temporary solution for photos!
          if ( self::$content_type == 'specific' && self::$content[0] == 'photos' && $type != 'photo' ) {
            continue;
          }
          $link = !empty($attachments['url']) ? $attachments['url'] : '';
          if ( !empty($attachments['media']) ) {
            $media = $attachments['media'];
            $main_url = !empty($media['image']['src']) ? $media['image']['src'] : '';
            $thumb_url = !empty($media['image']['src']) ? $media['image']['src'] : '';
            $width = !empty($media['image']['width']) ? $media['image']['width'] : '';
            $height = !empty($media['image']['height']) ? $media['image']['height'] : '';
          }
        }
        else if ( self::$content_type == 'specific' ) {
          $type = self::$content[0];
        }

        /**
         * check if content_type is timeline dont save wd_fb_data if
         * $content string not contain $next['type']
         */
          if ( self::$content_type == 'specific' && self::$content[0] == 'events' ) {
            $added_events++;
            if ($added_events > self::$limit) {
              break;
            }
          }
        /* @TODO v3.3 API
          if ( self::$content_type == 'timeline' ) {
              if (strpos($content, $next['type']) === false){
                continue;
              }
              $type = $next['type'];

              if (self::$timeline_type == 'others' && self::$id == $next['from']['id'] ) {
                continue;
              }
          }
          else {
            $type = self::$content[0];
          }
        */

        // Use this var for check if album imgs count not 0
        $album_imgs_exists = true;
        switch ($type) {
          case 'photos': {
            /* @todo v3.3 API  deprecated.
             // * If object type is photo(photos, video, videos,
             // * album, event cover photo etc ) so trying to
             // * check the count of resolution types
             // * and store source for thumb and main size

            if ( array_key_exists('images', $next) ) {
              $images = count($next['images']);
              if ($images > 6 ) {
                  $thumb_url = $next['images'][$images - 1]['source'];
                  $main_url = $next['images'][0]['source'];
              } else {
                  $thumb_url = $next['images'][0]['source'];
                  $main_url = $next['images'][0]['source'];
              }
              $width = $next['images'][0]['width'];
              $height = $next['images'][0]['height'];
            }
            */
            break;
          }
          case 'videos': {
            $name = array_key_exists('title', $next) ? addcslashes($next['title'], '\\') : '';
            $link = array_key_exists('permalink_url', $next) ? 'https://www.facebook.com' . $next['permalink_url'] : '';
            if (array_key_exists('format', $next)) {
              $img_res_count = count($next['format']);
              if ($img_res_count > 2) {
                $main_url = $next['format'][$img_res_count - 1]['picture'];
                $thumb_url = $next['format'][1]['picture'];
              } else {
                $thumb_url = $next['format'][$img_res_count - 1]['picture'];
                $main_url = $next['format'][$img_res_count - 1]['picture'];
              }
              $width = $next['format'][$img_res_count - 1]['width'];
              $height = $next['format'][$img_res_count - 1]['height'];
            }
            break;
          }
          case 'albums': {
            if (array_key_exists('count', $next)) {
            $album_imgs_count = $next['count'];
              if ($album_imgs_count == 0) {
               $album_imgs_exists = false;
              }
            }

            break;
          }
          // @todo V10.0 API
          case 'video': {
            $type ='videos';
            if ( !empty($attachments['media']['source']) ) {
              $source = $attachments['media']['source'];
            }
            /* @todo v3.3 API  deprecated.
              if (array_key_exists('format', $next)) {
                $img_res_count = count($next['format']);
                if ($img_res_count > 2) {
                  $main_url = $next['format'][$img_res_count - 1]['picture'];
                  $thumb_url = $next['format'][1]['picture'];
                } else {
                  $thumb_url = $next['format'][$img_res_count - 1]['picture'];
                  $main_url = $next['format'][$img_res_count - 1]['picture'];
                }
                $width = $next['format'][$img_res_count - 1]['width'];
                $height = $next['format'][$img_res_count - 1]['height'];
              }
              */
            break;
          }
          // @todo V10.0 API
          case 'album': {
            $type = 'albums';
            /* @todo v3.3 API  deprecated.
            if (array_key_exists('count', $next)) {
                $album_imgs_count = $next['count'];
                if ($album_imgs_count == 0) {
                    $album_imgs_exists = false;
                }
            }
            */
            break;
          }
        }
        // @todo In the case of 'albums' it works in JS version․
        if ( $type == 'albums' && !$album_imgs_exists ) {
          continue;
        }
		    $name = array_key_exists('name', $next) ? addcslashes($next['name'], '\\') : '';
        // Check if exists such keys in $next array
        $object_id = array_key_exists('id', $next) ? $next['id'] : '';
        $description = array_key_exists('description', $next) ? addcslashes($next['description'], '\\') : '';
        //  @todo v3.3 API
        //  $link = array_key_exists('link', $next) ? $next['link'] : '';
        //  $source = array_key_exists('source', $next) ? $next['source'] : '';
        $status_type = array_key_exists('status_type', $next) ? $next['status_type'] : '';
        $message = array_key_exists('message', $next) ? addcslashes($next['message'], '\\') : '';
        $story = array_key_exists('story', $next) ? $next['story'] : '';
        $place = array_key_exists('place', $next) ? json_encode($next['place']) : '';
        $message_tags = array_key_exists('message_tags', $next) ? json_encode($next['message_tags']) : '';
        $with_tags = array_key_exists('with_tags', $next) ? json_encode($next['with_tags']) : '';
        $story_tags = array_key_exists('story_tags', $next) ? json_encode($next['story_tags']) : '';
        $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';
        $comments = array_key_exists('comments', $next) ? json_encode($next['comments']) : '';
        $shares = array_key_exists('shares', $next) ? json_encode($next['shares']) : '';
        $attachments = array_key_exists('attachments', $next) ? json_encode($next['attachments']) : '';
        $from = array_key_exists('from', $next) ? $next['from']['id'] : '';
        $from_json = array_key_exists('from', $next) ? json_encode($next['from']) : '';
        $created_time = array_key_exists('created_time', $next) ? $next['created_time'] : '';
        $updated_time = array_key_exists('updated_time', $next) ? $next['updated_time'] : '';
        // When content is events some fields have different names, so check them.
        if ($type == 'events') {
          $source = array_key_exists('cover', $next) ? $next['cover']['source'] : '';
          $main_url = $source;
          $thumb_url = $main_url;
          $from = array_key_exists('owner', $next) ? $next['owner']['id'] : '';
          $from_json = array_key_exists('owner', $next) ? json_encode($next['owner']) : '';
          // Store event end time in update_time field
          $created_time = array_key_exists('start_time', $next) ? $next['start_time'] : '';
          $updated_time = array_key_exists('end_time', $next) ? $next['end_time'] : '';
        }
        $created_time_number = ($created_time != '') ? strtotime($created_time) : 0;
        $insert_data = array(
          'fb_id'               => self::$fb_id,
          'object_id'           => $object_id,
          'from'                => $from,
          'name'                => $name,
          'description'         => $description,
          'type'                => $type,
          'message'             => $message,
          'story'               => $story,
          'place'               => $place,
          'message_tags'        => $message_tags,
          'with_tags'           => $with_tags,
          'story_tags'          => $story_tags,
          'status_type'         => $status_type,
          'link'                => $link,
          'source'              => $source,
          'thumb_url'           => $thumb_url,
          'main_url'            => $main_url,
          'width'               => $width,
          'height'              => $height,
          'created_time'        => $created_time,
          'updated_time'        => $updated_time,
          'created_time_number' => $created_time_number,
          'comments'            => $comments,
          'shares'              => $shares,
          'attachments'         => $attachments,
          'who_post'            => $from_json,
          'reactions'           => $reactions
        );
        $save_fb_data = $wpdb->insert($wpdb->prefix . 'wd_fb_data',
          $insert_data,
          array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s')
        );
        if ($save_fb_data !== false) {
            $success = 'success';
            /* increment number of medias to keep equal with DB count */
            self::$media_db_current_count++;
            if ( self::$media_db_current_count >= self::$fb_limit ) {
              self::$media_db_limit_full = true;
            }
        }
        else {
            $success = 'error';
            break;
        }
      }
      if ($success == 'success') {
        if (self::$save || self::$edit_feed){
          self::wd_fb_massage('success', self::$fb_id);
        }
      }
      else if ($success == 'error' || $success == 'no_data' && (self::$save || self::$edit_feed)) {
        $message = ($success == 'error') ? 'Problem with save' : 'There is no data matching your choice.';
        self::wd_fb_massage('error', $message);
      }
      else {
        if (self::$save || self::$edit_feed){
          self::wd_fb_massage('error', 'Problem with save');
        }
      }
    }

    /**
     * check if string is JSON
     *
     * @param $string
     *
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /*not used*/
    public static function check_app()
    {
        global $wpdb;

        if (!class_exists('Facebook'))
            include WD_FFWD_DIR . "/framework/facebook-sdk/src/Facebook/autoload.php";
        $app_id = ((isset($_POST['app_id'])) ? sanitize_text_field(stripslashes($_POST['app_id'])) : '');
        $app_secret = ((isset($_POST['app_secret'])) ? sanitize_text_field(stripslashes($_POST['app_secret'])) : '');
        //prepare params for graph api call
        $fb_graph_url = str_replace(
            array('{FB_ID}', '{EDGE}', '{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
            array($app_id, '', 'access_token=' . self::$access_token . '&', 'fields=roles&', '', ''),
            self::$graph_url
        );
        $data = self::decap_do_curl($fb_graph_url);

        //check if exists app with such app_id and app_secret
        if (array_key_exists("id", $data) && array_key_exists("roles", $data)) {
            //create facebook object

            self::$facebook_sdk = new Facebook\Facebook(array(
                'app_id'     => $app_id,
                'app_secret' => $app_secret,
            ));

            $response_url = 'https://graph.facebook.com/oauth/access_token?client_id=' . $app_id . '&client_secret=' . $app_secret . '&grant_type=client_credentials';
            $response = wp_remote_get($response_url);

            $access_token = explode('=', $response['body']);
            $access_token = $access_token[1];
            //save app id and app secret
            $save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
                'app_id'       => $app_id,
                'app_secret'   => $app_secret,
                'access_token' => $access_token,
            ),
                array('id' => 1)
            );

            //checked logged in user
            $helper = self::$facebook_sdk->getRedirectLoginHelper();

            if ($helper) {
                $permissions = array('user_photos', 'user_videos', 'user_posts', 'user_events');
                $callback = admin_url() . 'admin.php?page=options_ffwd';

                $app_link_url = $helper->getLoginUrl($callback, $permissions);
                $app_link_text = __('Log into Facebook with your app', 'bwg');
                self::wd_fb_massage('success', $app_link_url);

            } else {

                self::wd_fb_massage('success', admin_url() . 'admin.php?page=options_ffwd');
            }
        } //check if exist error
        else if (array_key_exists("error", $data)) {
            $save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
                'access_token' => '',
            ),
                array('id' => 1)
            );
            if ($data['error']['code'] == 4){
              update_option('ffwd_limit_notice', 1);
            }
            self::wd_fb_massage('error', $data['error']['message']);
        } else {
            self::wd_fb_massage('error', 'Something went wrong');
        }
    }

    public static function dropp_objects()
    {
        global $wpdb;
        $dropped_id = (isset($_POST['ids']) && $_POST['ids'] != '') ? sanitize_text_field($_POST['ids']) : '';
        $yes = $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `id` = "%d"', $dropped_id));
        echo $yes;
        if (defined( 'DOING_AJAX' ) && DOING_AJAX )
        {
          die();
        }
    }

    /**
     * Check if user logged in on Facebook
     * @return int
     */
    public static function check_logged_in_user()
    {
        global $wpdb;
        if (!class_exists('Facebook'))
            include WD_FFWD_DIR . "/framework/facebook-sdk/facebook.php";
        $fb_option_data = self::get_fb_option_data();
        // Create facebook object
        self::$facebook_sdk = new Facebook(array(
            'appId'  => $fb_option_data->app_id,
            'secret' => $fb_option_data->app_secret,
        ));
        // Checked logged in user
        $user = self::$facebook_sdk->getUser();
        if (!isset($_SESSION['facebook_access_token'])) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * @param $mood
     * @param $massage
     */
    public static function wd_fb_massage($mood, $massage)
    {
      if(self::$ffwd_fb_massage){
        self::$ffwd_fb_massage_response = json_encode(array($mood, $massage, self::$non_public_share_count));
        self::$ffwd_fb_massage = false;
      }else{
        return '';
      }
    }

    /**
     * Check if Facebook type is valid
     */
    public static function check_fb_type() {
      $fb_type = ((isset($_POST['fb_type'])) ? sanitize_text_field(stripslashes($_POST['fb_type'])) : '');
      self::$fb_type = in_array($fb_type, self::$fb_valid_types) ? $fb_type : false;
      if (self::$fb_type) {
        self::$fb_type();
      }
      else {
        self::wd_fb_massage('error', 'no such FB type');
      }
    }

    /**
     * Get data from Facebook
     *
     * @param $uri
     *
     * @return mixed|null
     */
    public static function decap_do_curl( $uri = '' ) {
      $facebook_graph_results = NULL;
      $facebook_graph_url = $uri; // TODO: Add URL checking here, else error out
      $response = wp_remote_get($facebook_graph_url);
      if ( isset($response->errors) && isset($response->errors["http_request_failed"][0]) ) {
        self::wd_fb_massage('error', $response->errors["http_request_failed"][0]);
      }
      elseif ( is_array($response) && isset($response['body']) ) {
        $header = $response['headers']; // array of http header lines
        $facebook_graph_results = $response['body']; // use the content
      }
      $facebook_graph_results = json_decode($facebook_graph_results, TRUE);
      if ( !empty($facebook_graph_results) ) {
        if ( array_key_exists('error', $facebook_graph_results) ) {
          update_option('ffwd_token_error_flag', "1");
          if ( $facebook_graph_results['error']['code'] == 2 ) {
            return self::decap_do_curl($facebook_graph_url);
          }
        }
        else {
          update_option('ffwd_token_error_flag', "0");
        }
      }
      if ( isset($facebook_graph_results['error']) && count(self::$access_tokens) > 1 ) {
        if ( ($key = array_search(self::$access_token, self::$access_tokens)) !== FALSE ) {
          unset(self::$access_tokens[$key]);
          self::$access_token = NULL;
        }
        $rand_key = array_rand(self::$access_tokens);
        self::$access_token = self::$access_tokens[$rand_key];
        $parts = parse_url($uri);
        $queryParams = array();
        parse_str($parts['query'], $queryParams);
        $queryParams["access_token"] = self::$access_token;
        $queryString = http_build_query($queryParams);
        $url = "https://graph.facebook.com" . $parts['path'] . '?' . $queryString;

        return self::decap_do_curl($url);
      }

      return $facebook_graph_results;
    }

    public static function update_page_access_token($old_access_token, $page_id = ''){
      $redirect_uri = 'https://api.web-dorado.com/fb/';
      $admin_url = urlencode(admin_url('admin.php?page=options_ffwd'));
      $state = array(
        'wp_site_url' => $admin_url
      );
      $base_url = add_query_arg(array(
                                'action' => 'ff_wd_exchange_token',
                                'ff_wd_user_token' => $old_access_token,
                                'scope' => 'manage_pages',
                                'code' => '200',
                              ), $redirect_uri);

      $base_url .= '&state=' . base64_encode(json_encode($state));
      $response = wp_remote_post($base_url);

      if(!is_wp_error( $response ) && isset($response["body"])) {
        $ffwd_user_access_token = json_decode($response["body"], TRUE);

        if ( isset($ffwd_user_access_token["access_token"]) ) {
          $ffwd_user_access_token = sanitize_text_field($ffwd_user_access_token["access_token"]);
          $datas = get_option('ffwd_pages_list');
          foreach ( $datas as $data ) {
            if( $data->id == $page_id ) {
              $data->access_token = $ffwd_user_access_token;
            }
          }
          update_option('ffwd_pages_list', $datas);
          update_option("ffwd_pages_list_success", "1");
		      update_option('ffwd_token_error_flag', "0");
          self::update_access_tokens();

        }
      }
    }

    public static function get_autoupdate_interval()
    {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
        if (!isset($row)) {
            return 30;
        }
        if (!isset($row->autoupdate_interval)) {
            return 30;
        }
        $autoupdate_interval = $row->autoupdate_interval;

        return $autoupdate_interval;
    }

  public static function get_auth_url(){
    $app_id = '457830911380339';
    $redirect_uri = 'https://api.web-dorado.com/fb/';
    $admin_url = urlencode(admin_url('admin.php?page=options_ffwd'));

    $state = array(
      'wp_site_url' => $admin_url
    );

    $fb_url = add_query_arg(array(
      'client_id' => $app_id,
      'redirect_uri' => $redirect_uri,
      'scope' => 'pages_show_list,pages_read_engagement,pages_read_user_content',
    ), "https://www.facebook.com/dialog/oauth");

    $fb_url .= '&scope=pages_show_list,pages_read_engagement,pages_read_user_content&state=' . base64_encode(json_encode($state));

    return $fb_url;
  }

  public static function save_pages( $access_token ) {
    $message_id = 0;
    $url = 'https://graph.facebook.com/me/accounts?limit=500&access_token=' . $access_token;
    $response = wp_remote_get($url);
    if ( !is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200 ) {
      if ( !empty($response['body']) ) {
        $pages = json_decode($response['body']);
        if ( !empty($pages) && !empty($pages->data) ) {
          update_option('ffwd_pages_list', $pages->data);
          update_option("ffwd_pages_list_success", "1");
          update_option('ffwd_token_error_flag', "0");
          self::update_access_tokens();
          $message_id = 1;
        }
        else {
          $message_id = 2;
        }
      }
    }

    return $message_id;
  }

  private static function update_access_tokens(){
    global $wpdb;

    $pages = get_option('ffwd_pages_list', array());

    foreach($pages as $page) {
      $wpdb->update($wpdb->prefix . 'wd_fb_info', array(
        'page_access_token' => $page->access_token
      ), array('fb_page_id' => $page->id));
    }

  }
}