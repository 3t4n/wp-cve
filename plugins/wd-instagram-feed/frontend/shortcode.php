<?php
//global counter for webpage feeds
/*This will not work in case of AJAX request, note that for future versions*/
$wdi_feed_counter = 0;
$wdi_feed_item_onclick_type = false;
add_action('init', 'wdi_frontend_init');
function wdi_frontend_init() {
  global $wdi_options;
  $wdi_options = get_option(WDI_OPT);
}

add_shortcode('wdi_feed', 'wdi_feed');
add_shortcode('wdi_preview', 'wdi_feed');

function wdi_feed($atts, $widget_params = '') {
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  global $post;
  global $wdi_options;
  global $wdi_feed_counter;
  ob_start();
  $feed_id = WDILibrary::get('feed_id', 0, 'intval');
  if ( $feed_id != 0 && $post->post_type === "wdi_instagram" && $widget_params === '' ) {
    if ( !is_array($atts) ) {
      $atts = array();
    }
    $atts['id'] = $feed_id;
  }
  $attributes = shortcode_atts( array('id' => 'no_id'), $atts );
  $current_feed_id = $attributes['id'];

  if ( $attributes['id'] == 'no_id' ) {
    //including feed model
    require_once(WDI_DIR . '/admin/models/WDIModelEditorShortcode.php');
    $shortcode_feeds_model = new WDIModelEditorShortcode();
    /*if there are feeds select first one*/
    $first_feed_id = $shortcode_feeds_model->get_first_feed_id();
    $attributes['id'] = isset($first_feed_id) ? $first_feed_id : $attributes['id'];
    if ( $attributes['id'] == 'no_id' ) {
      ob_get_clean();

      return __('No feed. Create and publish a feed to display it.', "wd-instagram-feed");
    }
  }
  //including feed model
  require_once(WDI_DIR . '/admin/models/feeds.php');
  $feed_model = new Feeds_model_wdi();
  //getting all feed information from db
  $feed_row = WDILibrary::objectToArray( $feed_model->get_feed_row( $attributes['id'] ) );
  $feed_row = WDILibrary::keep_only_self_user($feed_row);
  
  if ( !isset($feed_row) || $feed_row == NULL ) {
    ob_get_clean();

    return __('Feed with such ID does not exist', "wd-instagram-feed");
  }
  if ( isset($feed_row['published']) && $feed_row['published'] === '0' ) {
    ob_get_clean();

    return __('Unable to display unpublished feed ', "wd-instagram-feed");
  }
  if ( $feed_row['nothing_to_display'] === '1' ) {
    ob_get_clean();

    return __('Cannot get other user media. API shut down by Instagram. Sorry. Display only your media.', "wd-instagram-feed");
  }

  if ( isset($feed_row["feed_item_onclick"]) && $feed_row["feed_item_onclick"] === 'lightbox' ){
    global $wdi_feed_item_onclick_type;
    $wdi_feed_item_onclick_type = true;
  }

  if ( !empty($feed_row['feed_users']) ){
    $feed_users = json_decode($feed_row['feed_users'], true);
    if( !empty( $feed_users) ) {
      foreach( $feed_users as $user) {
        if ( empty($user['tag_id']) ) {
          $feed_row['username'] = $user['username'];
          break;
        }
      }
    }
  }

  $params = array(
    'current_feed_id' => $current_feed_id,
    'number_of_photos' => $feed_row['number_of_photos'],
    'options' => $wdi_options
  );
  wdi_register_frontend_scripts( $params );
  if ( WDILibrary::is_ajax() || WDILibrary::elementor_is_active() ) {
    if ( $wdi_feed_counter == 0 ) {
      $wdi_feed_counter = wp_rand(1000, 9999);
      global $wdi_feed_counter_init;
      $wdi_feed_counter_init = $wdi_feed_counter;
    }
    //load scripts and styles from view files
  }
  else {
    wdi_load_frontend_scripts();
  }

  $feed_row['widget'] = false;
  if ($widget_params != '' && $widget_params['widget'] == true) {
    $feed_row['widget'] = true;
    $feed_row['number_of_photos'] = (string)$widget_params['widget_image_num'];
    $feed_row['show_description'] = (string)$widget_params['widget_show_description'];
    $feed_row['show_likes'] = (string)$widget_params['widget_show_likes_and_comments'];
    $feed_row['show_comments'] = (string)$widget_params['widget_show_likes_and_comments'];
    $feed_row['show_usernames'] = '0';
    $feed_row['display_header'] = '0';
    $feed_row['number_of_columns'] = (string)$widget_params['number_of_columns'];
    if ( $widget_params['enable_loading_buttons'] == 0 ) {
      $feed_row['feed_display_view'] = 'none';
    }
  }

  global $user_feed_header_args;
  $user_feed_header_args = array();
  if ( !empty($wdi_options['wdi_authenticated_users_list']) ) {
    $authenticated_users = json_decode($wdi_options['wdi_authenticated_users_list'], true);
    if ( !empty($authenticated_users[$feed_row['username']]) ) {
      $ig_user = $authenticated_users[$feed_row['username']];
      $user_feed_header_args['user'] = $ig_user;
    }
    else {
      ob_get_clean();

      return __('Please check your feed, the data was entered incorrectly.', "wd-instagram-feed");
    }
  }

  $user_feed_header_args['settings'] = array(
    'show_usernames' => $feed_row['show_usernames'],
    'show_follow' => $feed_row['follow_on_instagram_btn'],
    'media_followers' => $feed_row['display_user_post_follow_number'],
    'biography_website' => $feed_row['display_user_info'],
  );
  wp_localize_script("wdi_frontend", 'wdi_object', array(
    'user' => $user_feed_header_args['user']
  ));
  // checking feed type and using proper MVC
  $feed_type = isset($feed_row['feed_type']) ? $feed_row['feed_type'] : '';
  switch ($feed_type) {
    case 'thumbnails': {
      // including thumbnails controller
      require_once(WDI_DIR . '/frontend/controllers/thumbnails.php');
      $controller = new WDI_Thumbnails_controller();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    case 'masonry': {
      // including masonry controller
      require_once(WDI_DIR . '/frontend/controllers/masonry.php');
      $controller = new WDI_Masonry_controller();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    case 'blog_style': {
      // including thumbnails controller
      require_once(WDI_DIR . '/frontend/controllers/blogstyle.php');
      $controller = new WDI_BlogStyle_controller();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    case 'image_browser': {
      // including thumbnails controller
      require_once(WDI_DIR . '/frontend/controllers/imagebrowser.php');
      $controller = new WDI_ImageBrowser_controller();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    default: {
      ob_get_clean();

      return __('Invalid feed type', "wd-instagram-feed");
    }

  }
  // @TODO. All views pass_feed_data_to_js(), add_theme_styles(), generate_feed_styles() functions can be moved here
  // model and $feed_row - available here

  if ( isset($wdi_options['wdi_custom_css']) ) {
    wp_add_inline_style('generate_feed_styles', $wdi_options['wdi_custom_css']);
  }
  if ( isset($wdi_options['wdi_custom_js']) ) {
    ?>
    <?php echo wp_kses('<script>' . str_replace('&quot;', '"', $wdi_options['wdi_custom_js']) . '</script>', array('script' => array())); ?>
    <?php
  }

  return ob_get_clean();
}

function wdi_register_frontend_scripts( $params = array() ){
  global $wdi_feed_item_onclick_type;
  $min = ( WDI_MINIFY === true ) ? '.min' : '';
  wp_register_script('wdi_lazy_load', WDI_URL . '/js/jquery.lazyload.min.js', array("jquery"), wdi_get_pro_version(), true);
  wp_register_script('wdi_instagram', WDI_URL . '/js/wdi_instagram' . $min . '.js', array("jquery"), wdi_get_pro_version(), true);
  wp_register_script('wdi_frontend', WDI_URL . '/js/wdi_frontend' . $min . '.js', array("jquery", 'wdi_instagram', 'wdi_lazy_load', 'underscore'), wdi_get_pro_version(), true);
  wp_register_script('wdi_responsive', WDI_URL . '/js/wdi_responsive' . $min . '.js', array("jquery", "wdi_frontend"), wdi_get_pro_version(), true);

  ////////////////////////////GALLERY BOX//////////////////////////////
  wp_register_script('wdi_gallery_box', WDI_URL . '/js/gallerybox/wdi_gallery_box' . $min . '.js', array('jquery'), wdi_get_pro_version());
  // scripts for popup.
  wp_register_script('jquery-mobile', WDI_URL . '/js/gallerybox/jquery.mobile.min.js', array('jquery'), wdi_get_pro_version());
  if ( $wdi_feed_item_onclick_type ) {
    wp_register_script('jquery-mCustomScrollbar', WDI_URL . '/js/gallerybox/jquery.mCustomScrollbar.concat.min.js', array('jquery'), wdi_get_pro_version());
  }
  wp_register_script('jquery-fullscreen', WDI_URL . '/js/gallerybox/jquery.fullscreen-0.4.0' . $min . '.js', array('jquery'), wdi_get_pro_version());

  // localize scrips
  $user_is_admin = current_user_can('manage_options');
  $wdi_token_error_flag = get_option("wdi_token_error_flag");

  $current_feed_id = $params['current_feed_id'];
  $number_of_photos = $params['number_of_photos'];
  wp_localize_script("wdi_frontend", 'wdi_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'wdi_nonce' => wp_create_nonce("wdi_cache"),
    'WDI_MINIFY' => (WDI_MINIFY) ? 'true' : 'false',
    'feed_id' => $current_feed_id,
    'number_of_photos' => $number_of_photos,
    'wdi_cache_request_count' => isset($params['options']['wdi_cache_request_count']) ? $params['options']['wdi_cache_request_count'] : 10,
  ));

  wp_localize_script("wdi_frontend", 'wdi_url', array(
      'plugin_url' => WDI_URL . '/',
      'ajax_url' => admin_url('admin-ajax.php')));

  wp_localize_script("wdi_frontend", 'wdi_front_messages',
    array('connection_error' => __('Connection Error, try again later :(', 'wd-instagram-feed'),
      'user_not_found' => __('Username not found', 'wd-instagram-feed'),
      'network_error' => __('Network error, please try again later :(', 'wd-instagram-feed'),
      'hashtag_nodata' => __('There is no data for that hashtag', 'wd-instagram-feed'),
      'filter_title' => __('Click to filter images by this user', 'wd-instagram-feed'),
      'invalid_users_format' => __('Provided feed users are invalid or obsolete for this version of plugin', 'wd-instagram-feed'),
      'feed_nomedia' => __('There is no media in this feed', 'wd-instagram-feed'),
      'expired_token' => __('Error: Access token session has expired, please reauthorize access token', 'wd-instagram-feed'),
      'follow' => __('Follow', 'wd-instagram-feed'),
      'show_alerts' => $user_is_admin,
      'wdi_token_flag_nonce' => wp_create_nonce(''),
      'wdi_token_error_flag' => $wdi_token_error_flag
    ));

  wp_localize_script('wdi_gallery_box', 'wdi_objectL10n', array(
    'wdi_field_required' => __('Field is required.', "wd-instagram-feed"),
    'wdi_mail_validation' => __('This is not a valid email address.', "wd-instagram-feed"),
    'wdi_search_result' => __('There are no images matching your search.', "wd-instagram-feed"),
  ));
  wdi_load_frontend_styles();
}

function wdi_load_frontend_styles() {
  global $wdi_feed_item_onclick_type;
  $min = ( WDI_MINIFY === TRUE ) ? '.min' : '';
  wp_register_style('wdi_font-tenweb', WDI_URL . '/css/tenweb-fonts/fonts.css', array(), wdi_get_pro_version());
  wp_register_style('wdi_frontend', WDI_URL . '/css/wdi_frontend' . $min . '.css', array(), wdi_get_pro_version());

  wp_enqueue_style('wdi_font-tenweb');
  wp_enqueue_style('wdi_frontend');
  if ( $wdi_feed_item_onclick_type ) {
    wp_register_style('wdi_mCustomScrollbar', WDI_URL . '/css/gallerybox/jquery.mCustomScrollbar' . $min . '.css', array(), wdi_get_pro_version());
    wp_enqueue_style('wdi_mCustomScrollbar');
  }
}

function wdi_load_frontend_scripts(){
  wp_enqueue_script('underscore');
  wp_enqueue_script('wdi_lazy_load');
  wp_enqueue_script('wdi_instagram');
  wp_enqueue_script('wdi_frontend');
  wp_enqueue_script('wdi_responsive');
  wp_enqueue_script('wdi_gallery_box');
  wp_enqueue_script('wdi_mCustomScrollbar');
  wp_enqueue_script('jquery-mobile');
  wp_enqueue_script('jquery-mCustomScrollbar');
  wp_enqueue_script('jquery-fullscreen');
}

/*load all scripts and styles directly without dependency on jquery*/
function wdi_load_frontend_scripts_ajax($additional_scripts = array(), $additional_styles = array()){
  global $wp_scripts;
  global $wp_styles;

  $scripts_handles = array(
    'underscore',
    'wdi_lazy_load',
    'wdi_responsive',
    'wdi_instagram',
    'wdi_frontend',
    'wdi_gallery_box',
    'wdi_mCustomScrollbar',
    'jquery-mobile',
    'jquery-mCustomScrollbar',
    'jquery-fullscreen'
  );

  $scripts_handles = array_merge($scripts_handles, $additional_scripts);
  $script_tag = '<script src="%s?ver=%s"></script>';

  foreach($scripts_handles as $handle) {
    if(!isset($wp_scripts->registered[$handle])) {
      continue;
    }
    wp_print_scripts($handle);
  }

  $styles_handles = array(
    'wdi_font-tenweb',
    'wdi_frontend',
    'wdi_mCustomScrollbar',
  );

  $styles_handles = array_merge($styles_handles, $additional_styles);
  foreach($styles_handles as $handle) {
    if(!isset($wp_styles->registered[$handle])) {
      continue;
    }
    wp_print_styles($handle);
  }
}

add_action('wp_ajax_wdi_token_flag', 'wdi_token_flag');
add_action('wp_ajax_nopriv_wdi_token_flag', 'wdi_token_flag');
function wdi_token_flag() {
  $json = array( 'success' => 0 );
  if ( check_ajax_referer('', 'wdi_token_flag_nonce', FALSE) ) {
    $add = add_option('wdi_token_error_flag', 1);
    $json = array( 'success' => $add );
  }
  echo wp_json_encode($json);
  exit;
}

function wdi_feed_frontend_messages() {
  $manage_options_user = current_user_can('manage_options');
  $js_error_message = __('Something is wrong.', 'wd-instagram-feed');
  $token_error_message = __('Instagram token error.', 'wd-instagram-feed');
  $error_style = '';
  $private_feed_error_1 = '';
  $private_feed_error_2 = '';
  $private_feed_error_3 = '';
  if ( $manage_options_user ) {
    $js_error_message = __("Something is wrong. Response takes too long or there is JS error. Press Ctrl+Shift+J or Cmd+Shift+J on a Mac to see error in console or ask for <a class='wdi_error_link' href='https://wordpress.org/support/plugin/wd-instagram-feed' target='_blank'>free support</a>.", 'wd-instagram-feed');
    $token_error_message = __("Instagram token is invalid or expired. Please <a href='" . site_url() . "/wp-admin/admin.php?page=wdi_settings' target='_blank'>reset token</a> and sign-in again to get new one.");
    $error_style = 'style="color: #cc0000; text-align: center;"';
    $private_feed_error_1 = __('Admin warning: there is one or more private user in this feed', 'wd-instagram-feed');
    $private_feed_error_2 = __('Their media won\'t be displayed.', 'wd-instagram-feed');
    $private_feed_error_3 = '(<span class="wdi_private_feed_names"></span>). ';
  }
  $ajax_error_message = (defined('DOING_AJAX') && DOING_AJAX) ? __("Warning: Instagram Feed is loaded using AJAX request. It might not display properly.", "wd-instagram-feed") : '';
  echo wp_kses('<div ' . $error_style . ' class="wdi_js_error">' . $js_error_message . '<br/>' . $ajax_error_message . '</div>', array('div' => array('style' => true, 'class' => true), 'br' => array(), 'a' => array('href' =>true, 'target' => true, 'class' => true)));
  echo wp_kses('<div ' . $error_style . ' class="wdi_token_error wdi_hidden">' . $token_error_message . '</div>', array('div' => array('style' => true, 'class' => true), 'a' => array('href' =>true, 'target' => true, 'class' => true)));
  echo wp_kses('<div ' . $error_style . ' class="wdi_private_feed_error wdi_hidden"><span>' . $private_feed_error_1 . $private_feed_error_3 . $private_feed_error_2 . '</span></div>', array('div' => array('style' => true, 'class' => true), 'span' =>array()));
  echo wp_kses('<div class="wdi_check_fontawesome wdi_hidden"><i class="tenweb-i tenweb-i-instagram""></i></div>', array('div' => array('style' => true, 'class' => true), 'i' => array('class' => true)));
}