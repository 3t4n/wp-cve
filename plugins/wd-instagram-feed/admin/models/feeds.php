<?php

class Feeds_model_wdi {

  private $page_number = null;

  private $search_text = "";

  public function __construct() {
    if ( WDILibrary::get('paged', 0, 'intval') != 0 ) {
      $this->page_number = WDILibrary::get('paged', 0, 'intval');
    } elseif ( WDILibrary::get('page_number', 0, 'intval') !=  0 ) {
      $this->page_number = WDILibrary::get('page_number', 0, 'intval');
    }
    if ( WDILibrary::get('search_value') != '' ) {
      $this->search_text = WDILibrary::get('search_value');
    } elseif ( WDILibrary::get('search', '', 'sanitize_text_field', 'GET' ) != '' ) {
      $this->search_text = WDILibrary::get('search', '', 'sanitize_text_field', 'GET' );
    }
  }

   public function get_slides_row_data($slider_id) {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $row = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id)); //db call ok
    return $row;
  }

  public function get_rows_data() {
    global $wpdb;
    $prepare_args = array();
    $where = '1=%d';
    $prepare_args[] = 1;
    if ( isset($this->search_text) && !empty($this->search_text) && (esc_html(stripslashes($this->search_text)) != '') ) {
      $where .= ' AND feed_name LIKE "%%%s%%" ';
      $prepare_args[] = esc_html(stripslashes($this->search_text));
    }
    // The "WDILibrary::get()" method by default sanitize according to "sanitize_text_field"․ It is described in the "$callback" parameter․
    $asc_or_desc = WDILibrary::get('order') == 'asc' ? 'asc' : 'desc';
    $order_by_arr = array('id', 'feed_name', 'published');
    $order_by = WDILibrary::get('order_by');
    $order_by = (in_array($order_by, $order_by_arr)) ? $order_by : 'id';
    $limit = 0;
    if (isset($this->page_number) && $this->page_number) {
      $limit = ((int) $this->page_number - 1) * 20;
    }
    $order_by_limit = sprintf(' ORDER BY %s %s LIMIT %d, 20', $order_by, $asc_or_desc, $limit);
    // $wpdb->prepare() not needed (will throw a notice) as there are no parameters (all parts are already sanitised or cast to known-safe types if not sanitised here)
    /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching */
    $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE ' . $where . esc_sql($order_by_limit), $prepare_args)); //db call ok

    return $rows;
  }

  public function page_nav() {
    global $wpdb;
    $prepare_args = array();
    $where = '1=%d';
    $prepare_args[] = 1;
    if ( isset($this->search_text) && !empty($this->search_text) && (esc_html(stripslashes($this->search_text)) != '') ) {
      $where .= ' AND feed_name LIKE "%%%s%%"';
      $prepare_args[] = esc_html(stripslashes($this->search_text));
    }
    // $wpdb->prepare() not needed (will throw a notice) as there are no parameters (all parts are already sanitised or cast to known-safe types if not sanitised here)
    /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching */
    $total = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE ' . $where, $prepare_args) ); //db call ok
    $page_nav['total'] = $total;
    $limit = 0;
    if (isset($this->page_number) && $this->page_number) {
      $limit = ((int) $this->page_number - 1) * 20;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
  }

  public static function wdi_get_feed_defaults() {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $results = $wpdb->get_results( $wpdb->prepare("SELECT id FROM " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " WHERE default_theme='%d'", 1) ); //db call ok
    $default_theme = WDILibrary::objectToArray($results);
    $settings = array(
      'thumb_user' => '',
      'feed_name' => '',
      'feed_thumb' => '',
      'published' => '1',
      'theme_id' => $default_theme[0]['id'],
      'feed_users' => '',
      'feed_display_view' => 'load_more_btn',
      'sort_images_by' => 'date',
      'display_order' => 'desc',
      'follow_on_instagram_btn' => '1',
      'display_header' => '0',
      'number_of_photos' => '20',
      'load_more_number' => '4',
      'pagination_per_page_number' => '12',
      'pagination_preload_number' => '10',
      'image_browser_preload_number' => '10',
      'image_browser_load_number' => '10',
      'number_of_columns' => '4',
      'resort_after_load_more' => '0',
      'show_likes' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_description' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_comments' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_usernames' => '1', // @ToDo API Changes 2020 (change to 0)
      'display_user_info' => '1', // @ToDo API Changes 2020 (change to 0)
      'display_user_post_follow_number' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_full_description' => '1', // @ToDo API Changes 2020 (change to 0)
      'disable_mobile_layout' => '0',
      'feed_type' => 'thumbnails',
      'feed_item_onclick' => 'lightbox',
      //lightbox defaults
      'popup_fullscreen' => '0',
      'popup_width' => '648',
      'popup_height' => '648',
      'popup_type' => 'fade',
      'popup_autoplay' => '0',
      'popup_interval' => '5',
      'popup_enable_filmstrip' => '1',
      'popup_filmstrip_height' => '70',
      'autohide_lightbox_navigation' => '1',
      'popup_enable_ctrl_btn' => '1',
      'popup_enable_fullscreen' => '1',
      'popup_enable_info' => '1',
      'popup_info_always_show' => '0',
      'popup_info_full_width' => '0',
      'popup_enable_comment' => '1', // @ToDo API v10.0 In the case of 'Personal' need to 0
      'popup_enable_fullsize_image' => '1',
      'popup_enable_download' => '0',
      'popup_enable_share_buttons' => '1',
      'popup_enable_facebook' => '0',
      'popup_enable_twitter' => '0',
      'popup_enable_google' => '0',
      'popup_enable_pinterest' => '0',
      'popup_enable_tumblr' => '0',
      'show_image_counts' => '0',
      'enable_loop' => '1',
      'popup_image_right_click' => '1',
      'conditional_filters' => '',
      'conditional_filter_type' => 'none',
      'show_username_on_thumb' => '0',
      'conditional_filter_enable' => '0',
      'liked_feed' => 'userhash',
      'mobile_breakpoint' => '640',
      'redirect_url' => '',
      'feed_resolution' => 'optimal',
      'hashtag_top_recent' => '0',
    );
    if ( WDI_IS_FREE ) {
      $settings['show_description'] = '0';
      $settings['show_likes'] = '0';
      $settings['show_comments'] = '0';
      $settings['show_username_on_thumb'] = '0';
      $settings['popup_enable_filmstrip'] = '0';
      $settings['popup_info_always_show'] = '0';
      $settings['popup_info_full_width'] = '0';
      $settings['popup_enable_info'] = '0';
      $settings['popup_enable_comment'] = '0';
      $settings['popup_enable_share_buttons'] = '0';
    }
    return $settings;
  }

  public function get_sanitize_types(){
    $sanitize_types = array(
    'thumb_user'=>'string',
    'feed_name' => 'string',
    'feed_thumb'=>  'url',
    'published' => 'bool',
    'theme_id'=> 'number',
    'feed_users'=>  'json',
    'feed_display_view' =>'string',
    'sort_images_by' => 'string',
    'display_order'=>  'string',
    'follow_on_instagram_btn' => 'bool',
    'display_header'=>  'bool',
    'number_of_photos'=>  'number',
    'load_more_number' => 'number',
    'pagination_per_page_number'=>'number',
    'pagination_preload_number'=>'number',
    'image_browser_preload_number'=>'number',
    'image_browser_load_number'=>'number',
    'number_of_columns'=>  'number',
    'resort_after_load_more'=>'bool',
    'show_likes'=>  'bool',
    'show_description'=> 'bool' ,
    'show_comments'=>  'bool',
    'show_username_on_thumb'=>'bool',
    'show_usernames'=>'bool',
    'display_user_info'=>'bool',
    'display_user_post_follow_number'=>'bool',
    'show_full_description'=>'bool',
    'disable_mobile_layout'=>'bool',
    'feed_type' => 'string',
    'feed_item_onclick' => 'string',
    //lightbox defaults
    'popup_fullscreen'=>'bool',
    'popup_width'=>'number',
    'popup_height'=>'number',
    'popup_type'=>'string',
    'popup_autoplay'=>'bool',
    'popup_interval'=>'number',
    'popup_enable_filmstrip'=>'bool',
    'popup_filmstrip_height'=>'number',
    'autohide_lightbox_navigation'=>'bool',
    'popup_enable_ctrl_btn'=>'bool',
    'popup_enable_fullscreen'=>'bool',
    'popup_enable_info'=>'bool',
    'popup_info_always_show'=>'bool',
    'popup_info_full_width'=>'bool',
    'popup_enable_comment'=>'bool',
    'popup_enable_fullsize_image'=>'bool',
    'popup_enable_download'=>'bool',
    'popup_enable_share_buttons'=>'bool',
    'popup_enable_facebook'=>'bool',
    'popup_enable_twitter'=>'bool',
    'popup_enable_google'=>'bool',
    'popup_enable_pinterest'=>'bool',
    'popup_enable_tumblr'=>'bool',
    'show_image_counts'=>'bool',
    'enable_loop'=>'bool',
    'popup_image_right_click'=>'bool',
    'conditional_filters' => 'json',
    'conditional_filter_enable'=>'number',
    'conditional_filter_type' => 'string',
    'liked_feed' => 'string',
    'mobile_breakpoint' => 'number',
    'redirect_url' => 'string',
    'feed_resolution' => 'string',
    'hashtag_top_recent' => 'bool',
  );
  return $sanitize_types;
}

  public function get_feed_row($current_id){
  global $wpdb;
  /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
  $feed_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE id ='%d' ", $current_id)); //db call ok
  return $feed_row;
}

  public function get_unique_title($feed_name){
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $check_feed_title = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE feed_name='%s' ", $feed_name)); //db call ok
    if($check_feed_title){
	 $num = 1;
	 do {
	   $alt_name = $feed_name . "-$num";
	   $num++;
     /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
	   $slug_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE feed_name='%s' ", $alt_name)); //db call ok
	 }
	 while ( $slug_check );
	   $feed_name = $alt_name;
    }
    return $feed_name;
  }

  /**
   * Create Preview Instagram post.
   *
   * @return string $guid
   */
  public function get_instagram_preview_post() {
    global $wpdb;
    $post_type = 'wdi_instagram';
    $args = array(
      'post_type' => $post_type,
      'post_status' => 'publish'
    );
    $row = get_posts($args);

    if ( !empty($row[0]) ) {
      return get_permalink($row[0]->ID);
    }
    else {
      $post_params = array(
        'post_author' => 1,
        'post_status' => 'publish',
        'post_content' => '[wdi_preview]',
        'post_title' => 'Preview',
        'post_type' => $post_type,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_parent' => 0,
        'menu_order' => 0,
        'import_id' => 0,
      );
      // Create new post by wdi_preview preview type.
      if ( wp_insert_post($post_params) ) {
        /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules */
        flush_rewrite_rules();

        return get_the_guid($wpdb->insert_id);
      }
      else {
        return "";
      }
    }
  }

  /**
   * Check if data in db and new data the same ( especially feed_users, conditional_filters, conditional_filter_type)
   *
   * int $feed_id
   * array $data
   *
   * @return string $guid
  */
  public function check_need_cache( $feed_id, $data ) {
    global $wpdb;
    $users = $data['feed_users'];
    $conditional_filters = $data['conditional_filters'];
    $conditional_filter_type = $data['conditional_filter_type'];
    $top_recent = $data['hashtag_top_recent'];
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ". esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE id ='%d' AND feed_users='%s' AND conditional_filters='%s' AND conditional_filter_type='%s' AND hashtag_top_recent='%s'", $feed_id, $users, $conditional_filters, $conditional_filter_type, $top_recent)); //db call ok
    if ( $count == 0 ) {
      return 1;
    } else {
      $transient_key = "wdi_cache_" . md5($feed_id."_0");
      $cache_data = get_option($transient_key);
      $wdi_requests_success = intval(get_option('wdi_cache_success_'.$feed_id, 0));
      if ( isset($cache_data) && $cache_data != FALSE && isset($cache_data["cache_response"]) && $wdi_requests_success) {
        return 0;
      } else {
        return 1;
      }
    }
  }
}