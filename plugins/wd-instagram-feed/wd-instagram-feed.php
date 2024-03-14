<?php
/**
 * Plugin Name: 10Web Social Photo Feed
 * Plugin URI: https://10web.io/plugins/wordpress-instagram-feed/?utm_source=instagram_feed&utm_medium=free_plugin
 * Description: 10Web Social Photo Feed is a user-friendly tool for displaying user or hashtag-based feeds on your website. You can create feeds with one of the available layouts. It allows displaying image metadata, open up images in lightbox, download them and even share in social networking websites.
 * Version: 1.4.35
 * Author: 10Web
 * Author URI: https://10Web.io/plugins/?utm_source=instagram_feed&utm_medium=free_plugin
 * License: GPLv2 or later
 */

include_once 'config.php';
add_action('wp_ajax_wdi_cache', 'wdi_cache');
add_action('wp_ajax_nopriv_wdi_cache', 'wdi_cache');
add_action('wp_ajax_wdi_getUserMedia', 'wdi_getUserMedia');
add_action('wp_ajax_nopriv_wdi_getUserMedia', 'wdi_getUserMedia');
add_action('wp_ajax_wdi_getTagRecentMedia', 'wdi_getTagRecentMedia');
add_action('wp_ajax_nopriv_wdi_getTagRecentMedia', 'wdi_getTagRecentMedia');
add_action('wp_ajax_wdi_getRecentMediaComments', 'wdi_getRecentMediaComments');
add_action('wp_ajax_nopriv_wdi_getRecentMediaComments', 'wdi_getRecentMediaComments');
add_action('wp_ajax_wdi_set_preload_cache_data', 'wdi_set_preload_cache_data');
add_action('wp_ajax_nopriv_wdi_set_preload_cache_data', 'wdi_set_preload_cache_data');
add_action('wp_ajax_wdi_getHashtagId', 'wdi_getHashtagId');
add_action('wp_ajax_nopriv_wdi_getHashtagId', 'wdi_getHashtagId');
add_action('wp_ajax_wdi_apply_changes', 'WDI_instagram_feeds_page');
add_action('wp_ajax_nopriv_wdi_apply_changes', 'WDI_instagram_feeds_page');
add_action('wp_ajax_wdi_account_disconnect', 'wdi_backend_ajax');
add_action('wp_ajax_wdi_account_refresh', 'wdi_backend_ajax');

// Enqueue block editor assets for Gutenberg.
add_filter('tw_get_plugin_blocks', 'wdi_register_plugin_block');
add_filter('tw_get_block_editor_assets', 'wdi_register_block_editor_assets');

function wdi_register_plugin_block($blocks){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $data = WDILibrary::get_shortcode_data();
  $blocks['tw/' . 'wdi'] = array(
    'nothing_selected' => __('Nothing selected.', 'wdi'),
    'title' => "Instagram WD",
    'titleSelect' => sprintf(__('Select %s', 'wdi'), 'Instagram WD'),
    'iconUrl' => WDI_URL . '/images/insta_2.svg',
    'iconSvg' => array('width' => 20, 'height' => 20, 'src' => WDI_URL . '/images/insta.svg'),
    'isPopup' => false,
    'data' => $data,
  );
  return $blocks;
}

function wdi_register_block_editor_assets($assets){
  $min = ( WDI_MINIFY === true ) ? '.min' : '';
  $version = '2.0.3';
  $wd_bp_plugin_url = WDI_URL;
  $js_path = $wd_bp_plugin_url . '/js/block' . $min . '.js';
  $css_path = $wd_bp_plugin_url . '/css/block' . $min . '.css';

  if ( !isset($assets['version']) || version_compare($assets['version'], $version) === -1 ) {
    $assets['version'] = $version;
    $assets['js_path'] = $js_path;
    $assets['css_path'] = $css_path;
  }
  return $assets;
}

function wdi_getUserMedia(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  $user_name =  WDILibrary::get('user_name');
  $feed_id =  WDILibrary::get('feed_id');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') && $user_name != '' ) {
    require_once ("framework/WDIInstagram.php");
    $WDIInstagram = new WDIInstagram();
    $data = $WDIInstagram->getUserMedia($user_name, $feed_id);
    // All variables in the "getUserMedia()" function are esc․
    /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
    echo $data; die;
  }
}

function wdi_set_preload_cache_data() {
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  $user_name =  WDILibrary::get('user_name');
  $feed_id =  WDILibrary::get('feed_id', 0);
  $endpoint =  WDILibrary::get('endpoint');
  $tag_id = WDILibrary::get('tag_id');
  $tag_name = WDILibrary::get('$tag_name');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') && $user_name != '' ) {
    require_once ("framework/WDIInstagram.php");
    $WDIInstagram = new WDIInstagram();
    $data = $WDIInstagram->wdi_set_preload_cache_data($user_name, $feed_id, $endpoint, $tag_id, $tag_name);
    // All variables in the "wdi_set_preload_cache_data()" function are esc․
    /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
    echo $data;
    die;
  }
}

function wdi_getHashtagId() {
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  $tagname = WDILibrary::get('tagname');
  $user_name = WDILibrary::get('user_name');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') ) {
    require_once("framework/WDIInstagram.php");
    $WDIInstagram = new WDIInstagram();
    $data = $WDIInstagram->wdi_getHashtagId($tagname,$user_name);
    echo wp_json_encode($data);
    die;
  }
}

function wdi_getRecentMediaComments(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  $user_name =  WDILibrary::get('user_name');
  $media_id =  WDILibrary::get('media_id');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') && $user_name != '' ) {
    require_once ("framework/WDIInstagram.php");
    $WDIInstagram = new WDIInstagram();
    $data = $WDIInstagram->getRecentMediaComments($user_name,$media_id);
    // All variables in the "getRecentMediaComments()" function are esc․
    /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
    echo $data; die;
  }
}

function wdi_getTagRecentMedia(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') ) {
    $feed_id = WDILibrary::get('feed_id');
    require_once ("framework/WDIInstagram.php");
    $WDIInstagram = new WDIInstagram();
    $data = $WDIInstagram->getTagRecentMedia($feed_id);
    // All variables in the "getTagRecentMedia()" function are esc․
    /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
    echo $data; die;
  }
}

function wdi_cache(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $wdi_nonce = WDILibrary::get('wdi_nonce');
  $task = WDILibrary::get('task');
  if ( wp_verify_nonce($wdi_nonce, 'wdi_cache') && $task != '' ) {
    require_once ("framework/WDICache.php");
    $WDICache = new WDICache();
    $wdi_cache_name = WDILibrary::get('wdi_cache_name');
    if ( $wdi_cache_name != '' ) {
      if($task == "get"){
        $data = $WDICache->get_cache_data($wdi_cache_name);
        wdi_send_response($data);
      } elseif ($task == "set"){
        $wdi_cache_response = WDILibrary::get('wdi_cache_response');
        if ( $wdi_cache_response != '' ) {
          die;
        }
      }
    }
    if ($task == "reset") {
      global $wpdb;
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $feeds = $wpdb->get_results("SELECT id,feed_users,hashtag_top_recent FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE), ARRAY_A); //db call ok
      foreach ( $feeds as $feed ) {
        $data['data'][] = array(
          'feed_id' => $feed['id'],
          'users' => $feed['feed_users'],
          'endpoint' => $feed['hashtag_top_recent']
        );
      }
      $status = $WDICache->reset_cache();
      if ( $status === FALSE ) {
        $data['status'] = FALSE;
        wdi_send_response($data);
      }
      else {
        $data['status'] = TRUE;
        wdi_send_response($data);
      }
      die;
    }
  }
}

function wdi_send_response($data){
  echo wp_json_encode($data);
  die;
}

add_action('wp_ajax_WDIGalleryBox', 'wdi_ajax_frontend');
add_action('wp_ajax_nopriv_WDIGalleryBox', 'wdi_ajax_frontend');
function wdi_ajax_frontend(){
  /* reset from user to site locale*/
  if(function_exists('switch_to_locale')) {
    switch_to_locale(get_locale());
  }

  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('action');
  //chenged action hook for esacpeing Photo Gallery confilct
  if($page === 'WDIGalleryBox') {
    $page = 'GalleryBox';
  }
  if(($page != '') && (($page == 'GalleryBox') || ($page == 'Share'))) {
    require_once(WDI_DIR . '/frontend/controllers/WDIController' . ucfirst($page) . '.php');
    $controller_class = 'WDIController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  } else {
    wp_die();
  }
}

//including admin functions
require_once(WDI_DIR . '/admin-functions.php');
//including shortcode file
require_once(WDI_DIR . '/frontend/shortcode.php');

// Plugin activate.
register_activation_hook( __FILE__, 'wdi_instagram_activate' );

function wdi_instagram_activate( $networkwide ) {
  if ( function_exists('is_multisite') && is_multisite() ) {
    // Check if it is a network activation - if so, run the activation function for each blog id.
    if ( $networkwide ) {
      global $wpdb;
      // Get all blog ids.
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $blogids = $wpdb->get_col("SELECT blog_id FROM " . esc_sql($wpdb->blogs) ); //db call ok
      foreach ( $blogids as $blog_id ) {
        switch_to_blog($blog_id);
        wdi_install();
        restore_current_blog();
      }
      return;
    }
  }
  wdi_install();
}

// Plugin deactivate.
register_deactivation_hook(__FILE__, 'wdi_instagram_deactivate');
function wdi_instagram_deactivate( $networkwide ) {
  if ( function_exists('is_multisite') && is_multisite() ) {
    if ( $networkwide ) {
      global $wpdb;
      // Check if it is a network activation - if so, run the activation function for each blog id.
      // Get all blog ids.
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $blogids = $wpdb->get_col("SELECT blog_id FROM " . esc_sql($wpdb->blogs) );  //db call ok
      foreach ( $blogids as $blog_id ) {
        switch_to_blog($blog_id);
        wdi_deactivate();
        restore_current_blog();
      }

      return;
    }
  }
  wdi_deactivate();
}

add_action("admin_init", 'wdi_admin_init');

function wdi_admin_init() {
  wdi_register_settings();
  wdi_privacy_policy();
}

function wdi_register_settings(){
  //gettings settings for registering
  $settings = wdi_get_settings();
  //adding settings fileds form getted settings
  foreach($settings as $setting_name => $setting) {
    if(isset($setting['field_or_not']) == true && $setting['field_or_not'] != 'no_field') {
      add_settings_field(
        $setting_name,
        $setting['title'],
        'wdi_field_callback',
        'settings_wdi',
        $setting['section'],
        array($setting)
      );
    }
  }

  //registering all settings
  register_setting('wdi_all_settings', 'wdi_instagram_options', 'wdi_sanitize_options');
  wdi_get_options();
}

function wdi_privacy_policy() {
  if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
    return;
  }

  $title = __('Instagram Feed', 'wd-instagram-feed');
  $link = '<a target="_blank" href="https://instagram.com/legal/privacy">' . __('Privacy Policy', 'wd-instagram-feed') . '</a>';
  $text  = sprintf(__('Instagram Feed plugin uses Instagram API on website front end. All the data received from Instagram via API is cached in WordPress database for some short period to provide front end optimization. You may request us to delete your Instagram data if it is accidentally cached in our website database with hashtag feed data. Instagram saves some cookies in browsers of website visitors via API data. These cookies are mostly used for security purposes. They are regulated under terms of Instagram’s %s.', 'wd-instagram-feed'), $link);
  $text .= "<br/>";
  $text .= __('10Web Disclaimer: The above text is for informational purposes only and is not a legal advice. You must not rely on it as an alternative to legal advice. You should contact your legal counsel to obtain advice with respect to your particular case.', 'wd-instagram-feed');
  $text .= "<br/>&nbsp;";

  wp_add_privacy_policy_content(
    $title,
    $text
  );
}

add_action( 'init', 'wdi_run_cache_cron' );
function wdi_run_cache_cron() {
  $cache_time = get_option('wdi_current_cache_time');
  $now_time = current_time('timestamp',1);
  $wdi_options = get_option("wdi_instagram_options");
  /* Case when wdi_transient_time option is less then 10 minutes */
  if ( $wdi_options["wdi_transient_time"] < 0 ) {
    $wdi_options["wdi_transient_time"] = 2880;
    update_option("wdi_instagram_options" , $wdi_options);
  }
  $interval = intval($wdi_options["wdi_transient_time"])*60;
  $next_run_time = intval( $cache_time + $interval );
  if ( $cache_time === false || $next_run_time <= $now_time ) {
    update_option('wdi_current_cache_time', $now_time, 1);
    require_once(WDI_DIR . '/framework/WDILibrary.php');
    WDILibrary::refresh_instagram_access_token();
    require_once("framework/WDICache.php");
    $WDICache = new WDICache();
    $WDICache->reset_cache();
  }
}

add_filter('wdi_sanitize_options', 'wdi_create_sample_feed');
function wdi_create_sample_feed($new_options){
  require_once(WDI_DIR . '/framework/WDILibrary.php');

  //submit wdi options
  $option_page = WDILibrary::get('option_page');
  if ( $option_page != 'wdi_all_settings' ) {
    return $new_options;
  }

  $wdi_options = wdi_get_options();

  if ( empty($new_options['wdi_user_name']) ) {
    return $new_options;
  }

  $first_user_username = get_option('wdi_first_user_username');
  if($first_user_username !== false && $new_options['wdi_user_name'] !== $first_user_username) {
    update_option('wdi_sample_feed_post_url', '0');
    return $new_options;
  }


  if(!empty($wdi_options['wdi_access_token']) || empty($new_options['wdi_access_token'])) {
    return $new_options;
  }

  $wdi_sample_feed_post_id = get_option('wdi_sample_feed_post_id');
  if($wdi_sample_feed_post_id !== false) {
    return $new_options;
  }

  require_once(WDI_DIR . '/admin/controllers/WDIControllerFeeds_wdi.php');
  require_once(WDI_DIR . '/framework/WDILibrary.php');

  $default_user = new stdClass();
  $default_user->username = $new_options['wdi_user_name'];
  $default_user->id = "";

  $settings = array(
    'feed_users' => wp_json_encode(array($default_user)),
  );

  $controller = new WDIControllerFeeds_wdi();
  $feed_id = $controller->create_feed($settings);

  //db error
  if($feed_id == false) {
    return $new_options;
  }

  $post_content = "<p>" . __('This is a private page. To make it public, edit it and change the visibility.') .
    "</p>" .
    '[wdi_feed id="' . $feed_id . '"]';

  $post_args = array(
    'post_content' => $post_content,
    'post_status' => 'publish',
    'post_title' => __('My Instagram Feed', 'wd-instagram-feed'),
    'post_type' => 'page',
  );

  $post_id = wp_insert_post($post_args);

  if($post_id === 0) {
    return $new_options;
  }

  update_option('wdi_first_user_username', $new_options['wdi_user_name']);
  update_option('wdi_sample_feed_id', $feed_id);
  update_option('wdi_sample_feed_post_id', $post_id);
  update_option('wdi_sample_feed_post_url', '1');

  return $new_options;
}

add_action('admin_menu', 'WDI_instagram_menu', 9);
function WDI_instagram_menu() {
  $menu_icon = WDI_URL . '/images/menu_icon.png';
  $min_feeds_capability = wdi_get_create_feeds_cap();
  $wdi_options = get_option("wdi_instagram_options");
  $authenticated_users_list = json_decode($wdi_options['wdi_authenticated_users_list']);
  if ( empty($authenticated_users_list) ) {
    $parent_slug = "wdi_settings";
    add_menu_page(__('Instagram Settings', 'wd-instagram-feed'), 'Instagram Feed', $min_feeds_capability, $parent_slug, 'WDI_instagram_settings_page', $menu_icon);
    add_submenu_page("", __('Uninstall', 'wd-instagram-feed'), __('Uninstall', 'wd-instagram-feed'), 'manage_options', 'wdi_uninstall', 'WDI_instagram_uninstall_page');
  }
  else {
    $parent_slug = "wdi_feeds";
    add_menu_page(__('Instagram Feed', 'wd-instagram-feed'), 'Instagram Feed', $min_feeds_capability, $parent_slug, 'WDI_instagram_feeds_page', $menu_icon);
    add_submenu_page($parent_slug, __('Feeds', 'wd-instagram-feed'), __('Feeds', 'wd-instagram-feed'), $min_feeds_capability, 'wdi_feeds', 'WDI_instagram_feeds_page');
    add_submenu_page($parent_slug, __('Themes', 'wd-instagram-feed'), __('Themes', 'wd-instagram-feed'), $min_feeds_capability, 'wdi_themes', 'WDI_instagram_themes_page');
    add_submenu_page($parent_slug, __('Settings', 'wd-instagram-feed'), __('Settings', 'wd-instagram-feed'), 'manage_options', 'wdi_settings', 'WDI_instagram_settings_page');
    add_submenu_page("", __('Uninstall', 'wd-instagram-feed'), __('Uninstall', 'wd-instagram-feed'), 'manage_options', 'wdi_uninstall', 'WDI_instagram_uninstall_page');
    if ( WDI_IS_FREE ) {
      // Custom link to wordpress.org
      global $submenu;
      $url = 'https://wordpress.org/support/plugin/wd-instagram-feed/#new-post';
      /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */
      $submenu[$parent_slug][] = array(
        '<div id="wdi_ask_question">' . __('Ask a question', 'wd-instagram-feed') . '</div>',
        'manage_options',
        $url
      );
    }
  }
}

add_action('admin_head-toplevel_page_wdi_feeds', 'wdi_check_necessary_params');

// Settings page callback
function WDI_instagram_settings_page(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  require_once(WDI_DIR . '/admin/controllers/settings.php');
  $controller = new Settings_controller_wdi();
  $controller->execute();
}

// Feeds page callback
function WDI_instagram_feeds_page(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $ajax_nonce = WDILibrary::get('wdi_nonce');
  $action = WDILibrary::get('action');
  if ( $action == 'wdi_apply_changes' && wp_verify_nonce($ajax_nonce, 'wdi_cache') == FALSE ) {
    die ( 'Invalid nonce.' );
  }

  require_once(WDI_DIR . '/admin/controllers/feeds.php');
  $controller = new Feeds_controller_wdi();
  $controller->execute();
}

function WDI_instagram_themes_page(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  require_once(WDI_DIR . '/admin/controllers/themes.php');
  $controller = new Themes_controller_wdi();
  $controller->execute();
}

function WDI_instagram_uninstall_page() {
  require_once( WDI_DIR . '/framework/WDILibrary.php' );
  require_once( WDI_DIR . '/admin/controllers/uninstall.php' );
  $controller = new Uninstall_controller_wdi();
  $controller->execute();
}

// Loading admin scripts
add_action('admin_enqueue_scripts', 'wdi_load_scripts');

function wdi_load_scripts(){
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  global $wdi_options;
  $page = WDILibrary::get('page');
  if ( $page === 'wdi_themes' || $page === 'wdi_feeds' || $page === 'wdi_settings' || $page === 'wdi_uninstall' ) {
    $min = ( WDI_MINIFY === true ) ? '.min' : '';
    wp_register_style(WDI_PREFIX . '-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
    wp_enqueue_script('jquery-color');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');

    wp_enqueue_script('wdi_admin', plugins_url('js/wdi_admin' . $min . '.js', __FILE__), array("jquery", 'wdi_instagram'), wdi_get_pro_version());
    wp_enqueue_script('wdi_instagram', plugins_url('js/wdi_instagram' . $min . '.js', __FILE__), array("jquery"), wdi_get_pro_version());

    //localize
    $uninstall_url = wp_nonce_url(admin_url('admin-ajax.php'), 'wdiUninstallPlugin', 'uninstall_nonce');
    wp_localize_script("wdi_admin", 'wdi_ajax', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'uninstall_url' => $uninstall_url,
      'wdi_nonce' => wp_create_nonce("wdi_cache"),
      'wdi_cache_request_count' => isset($wdi_options['wdi_cache_request_count']) ? $wdi_options['wdi_cache_request_count'] : 10,
    ));

    wp_localize_script("wdi_admin", 'wdi_messages', array(
      'uninstall_confirm' => __("All the data will be removed from the database. Continue?", 'wd-instagram-feed'),
      'uninstall_plugin' => __('Are you sure you want to uninstall plugin?', 'wd-instagram-feed'),
      'instagram_server_error' => __('Some error with instagram servers, try agian later :(', 'wd-instagram-feed'),
      'invalid_user' => __('Invalid user:', 'wd-instagram-feed'),
      'already_added' => __('already added!', 'wd-instagram-feed'),
      'user_not_exist' => __('User %s does not exist.', 'wd-instagram-feed'),
      'network_error' => __("Network Error, please try again later. :(", 'wd-instagram-feed'),
      'invalid_hashtag' => __('Invalid hashtag', 'wd-instagram-feed'),
      'hashtag_no_data' => __('This hashtag has no media published within last 24 hours. Are you sure you want to add it? Try to display its top media.', 'wd-instagram-feed'),
      'invalid_url' => __('URL is not valid', 'wd-instagram-feed'),
      'selectConditionType' => __('Please Select Condition Type', 'wd-instagram-feed'),
      'and_descr' => __('Show posts which have all of these conditions', 'wd-instagram-feed'),
      'or_descr' => __('Show posts which have at least one of these conditions', 'wd-instagram-feed'),
      'nor_descr' => __('Hide posts which have at least one of these conditions', 'wd-instagram-feed'),
      'either' => __('EITHER', 'wd-instagram-feed'),
      'neither' => __('NEITHER', 'wd-instagram-feed'),
      'not' => __('EXCEPT', 'wd-instagram-feed'),
      'and' => __('AND', 'wd-instagram-feed'),
      'or' => __('OR', 'wd-instagram-feed'),
      'nor' => __('NOR', 'wd-instagram-feed'),
      'do_you_want_to_delete_selected_items' => __('Do you want to delete selected items?', 'wd-instagram-feed'),
      'user_field_required' => __('You have not selected a user, the user field is required.', 'wd-instagram-feed'),
      'feed_title_field_required' => __('Title field is required.', 'wd-instagram-feed'),
      'please_write_hashtag' => __('Please write hashtag.', 'wd-instagram-feed'),
      'you_can_add_only_hashtags' => __('You can add only hashtags.', 'wd-instagram-feed')
    ));
    wp_localize_script("wdi_admin", 'wdi_url', array('plugin_url' => WDI_URL . '/'));
    wp_localize_script("wdi_admin", 'wdi_admin', array('admin_url' => get_admin_url()));
    wp_localize_script("wdi_admin", 'wdi_options', $wdi_options);
  }
  if(WDI_IS_FREE){
    wp_register_style(WDI_PREFIX . '-pricing', WDI_URL . '/css/pricing.css', array(), WDI_VERSION);
  }
}

//loading admin styles
add_action('admin_enqueue_scripts', 'wdi_load_styles');

function wdi_load_styles() {
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('page');
  if ( $page === 'wdi_themes' || $page === 'wdi_feeds' || $page === 'wdi_settings' || $page === 'wdi_uninstall' ) {
    $min = ( WDI_MINIFY === true ) ? '.min' : '';
    wp_enqueue_style('wdi_backend', plugins_url('css/wdi_backend' . $min . '.css', __FILE__), array(), wdi_get_pro_version());
  }
}

add_action('enqueue_block_editor_assets', 'wdi_enqueue_block_editor_assets');

function wdi_enqueue_block_editor_assets(){
  // Remove previously registered or enqueued versions
  $wp_scripts = wp_scripts();
  foreach($wp_scripts->registered as $key => $value) {
    // Check for an older versions with prefix.
    if(strpos($key, 'tw-gb-block') > 0) {
      wp_deregister_script($key);
      wp_deregister_style($key);
    }
  }
  // Get the last version from all 10Web plugins.
  $assets = apply_filters('tw_get_block_editor_assets', array());
  $blocks = apply_filters('tw_get_plugin_blocks', array());
  // Not performing unregister or unenqueue as in old versions all are with prefixes.
  wp_enqueue_script('tw-gb-block', $assets['js_path'], array('wp-blocks', 'wp-element'), $assets['version']);
  wp_localize_script('tw-gb-block', 'tw_obj_translate', array(
    'nothing_selected' => __('Nothing selected.', 'wdi'),
    'empty_item' => __('- Select -', 'wdi'),
    'blocks' => wp_json_encode($blocks),
  ));
  wp_enqueue_style('tw-gb-block', $assets['css_path'], array('wp-edit-blocks'), $assets['version']);
}

// Instagram WDI Widget.
if(class_exists('WP_Widget')) {
  require_once(WDI_DIR . '/admin/controllers/WDIControllerWidget.php');
  add_action('widgets_init', 'wdi_register_widget');
}

function wdi_register_widget(){
  return register_widget("WDIControllerWidget");
}

// Editor shortcode button
add_action('media_buttons', 'wdi_add_editor_button');

function wdi_add_editor_button($context){
  ob_start();
  $display = apply_filters('wdi_display_shortcode_button', true);
  if($display === false) {
    return $context;
  }
  global $pagenow;
  if(in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    ?>
    <a onclick="tb_click.call(this);wdi_thickDims(); return false;" href="<?php echo esc_url_raw( add_query_arg(array('action' => 'WDIEditorShortcode', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) ); ?>" class="wdi_thickbox button" style="padding-left: 0.4em;" title="Add Instagram Feed">
      <span class="wp-media-buttons-icon wdi_media_button_icon" style="vertical-align: text-bottom; background: url(<?php echo esc_url(WDI_URL); ?>/images/menu_icon.png) no-repeat scroll left top rgba(0, 0, 0, 0);background-size:contain;"></span>
      Add Instagram Feed
    </a>
    <?php
  }
  // All variables used in HTML are esc․
  /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
  echo ob_get_clean();
}

// Editor button ajax handler
add_action("wp_ajax_WDIEditorShortcode", 'wdi_editor_button');

function wdi_editor_button(){
  if(function_exists('current_user_can')) {
    if(!current_user_can('publish_posts')) {
      die('Access Denied');
    }
  } else {
    die('Access Denied');
  }
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('action');
  if($page != '' && (($page == 'WDIEditorShortcode'))) {
    if (WDI_MINIFY == TRUE) {
      wp_register_script('wdi-shortcode', WDI_URL . '/js/shortcode.min.js', array('jquery'), WDI_VERSION);
    } else {
      wp_register_script('wdi-shortcode', WDI_URL . '/js/shortcode.js', array('jquery'), WDI_VERSION);
    }


    require_once(WDI_DIR . '/admin/controllers/WDIControllerEditorShortcode.php');
    $controller_class = 'WDIControllerEditorShortcode';
    $controller = new $controller_class();
    $controller->execute();
  }
  wp_die();
}

/**
 *  handle editor popup
 */
add_action('admin_head', 'wdi_admin_ajax');

function wdi_admin_ajax() {
  global $pagenow;
  if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    ?>
    <script>
      var wdi_thickDims, wdi_tbWidth, wdi_tbHeight;
      wdi_tbWidth = 420;
      wdi_tbHeight = 140;
      wdi_thickDims = function() {
        var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
        w = (wdi_tbWidth && wdi_tbWidth < W - 90) ? wdi_tbWidth : W - 40;
        h = (wdi_tbHeight && wdi_tbHeight < H - 60) ? wdi_tbHeight : H - 40;
        if (tbWindow.size()) {
          tbWindow.width(w).height(h);
          jQuery('#TB_iframeContent').width(w).height(h - 27);
          tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
          if (typeof document.body.style.maxWidth != 'undefined') {
            tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
          }
        }
      };
    </script>
    <?php
  }
}

function wdi_get_pro_version(){
  $version = explode('.', WDI_VERSION);
  $version[0]++;
  return implode('.', $version);
}

add_action('init', 'wdi_load_textdomain');
add_action('init', 'wdi_register_instagram_preview_cpt');

function wdi_register_instagram_preview_cpt() {
  $args = array(
    'public' => true,
    'exclude_from_search' => true,
    'show_in_menu' => false,
    'create_posts' => 'do_not_allow',
    'capabilities' => array(
      'create_posts' => FALSE,
      'edit_post' => 'edit_posts',
      'read_post' => 'edit_posts',
      'delete_posts' => FALSE,
    )
  );

  register_post_type('wdi_instagram', $args);
}

/**
 * Load plugin textdomain.
 *
 */
function wdi_load_textdomain(){
  load_plugin_textdomain('wd-instagram-feed', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('init', 'wdi_check_silent_update');
function wdi_check_silent_update() {
  $current_version = WDI_VERSION;
  $saved_version = get_option('wdi_version');
  if ( $current_version != $saved_version ) {
    wdi_install();
  }
}

add_action('init', 'wdi_wd_lib_init', 9);

function wdi_wd_lib_init(){
  $wdi_options = wdi_get_options();
  $parent_slug = "wdi_feeds";
  if(!isset($wdi_options['wdi_access_token']) || empty($wdi_options['wdi_access_token'])) {
    $parent_slug = "wdi_settings";
  }
  /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
  if ( !isset($_REQUEST['ajax']) && is_admin() ) {
    if(!class_exists("TenWebLib")) {
      require_once(WDI_DIR . '/wd/start.php');
    }
    global $wdi_wd_plugin_options;
    $wdi_wd_plugin_options = array(
      "prefix" => "wdi",
      "plugin_id" => 43, // tenweb
      "plugin_title" => "Instagram Feed",
      "plugin_wordpress_slug" => 'wd-instagram-feed',
      "plugin_dir" => WDI_DIR,
      "plugin_main_file" => __FILE__,
      "description" => __("The most advanced and user-friendly Instagram plugin. Instagram Feed plugin allows you to display image feeds from single or multiple Instagram accounts on a WordPress site.", 'wd-instagram-feed'),
      "plugin_features" => array(
        0 => array(
          "title" => __("Responsive", 'wd-instagram-feed'),
          "description" => __("Instagram feeds are not only elegantly designed to be displayed on your website, but also come fully responsive for better user experience when using mobile devices and tables.", 'wd-instagram-feed'),
        ),
        1 => array(
          "title" => __("SEO Friendly", 'wd-instagram-feed'),
          "description" => __("Instagram Feed uses clean coding and latest SEO techniques necessary to keep your pages and posts SEO-optimized.", 'wd-instagram-feed'),
        ),
        2 => array(
          "title" => __("4 Fully Customizable Layouts", 'wd-instagram-feed'),
          "description" => __("There are four layout options for Instagram feeds: Thumbnails, Image Browser, Blog Style and Masonry. Display a feed as a simply arranged thumbnails with captions. Use Masonry layout to create a beautiful combination of images and captions. Create a blog feed by simply sharing Instagram posts with captions using blog style layout. Image browser layout saves space, yet allows to display larger images. In addition users can choose the number of the displayed images, layout columns, image order and etc.", 'wd-instagram-feed'),
        ),
        3 => array(
          "title" => __("Individual and Mixed Feeds", 'wd-instagram-feed'),
          "description" => __("Create mixed and single feeds of Instagram posts. Single feeds can be based on public Instagram accounts and single Instagram hashtag. Mixed feeds can contain multiple public Instagram accounts and multiple Instagram hashtags. A front end filter is available for mixed feeds. Click to filter only one feed based on a single hashtag or account.", 'wd-instagram-feed'),
        ),
        4 => array(
          "title" => __("Advanced Lightbox", 'wd-instagram-feed'),
          "description" => __("Upon clicking on image thumbnails an elegant lightbox will be opened, where you will find control buttons for displaying images in larger view, read image comments, captions, view image metadata and easily navigate between images. Lightbox can serve as a slider with various stunning slide transition effects. If the feed contains video, the video will be played within the lightbox as an HTML5 video.", 'wd-instagram-feed'),
        )
      ),
      // user guide from web-dorado.com
      "user_guide" => array(
        0 => array(
          "main_title" => __("Installation and configuration", 'wd-instagram-feed'),
          "url" => "https://help.10web.io/hc/en-us/articles/360016277532-Configuring-Instagram-Access-Token?utm_source=instagram_feed&utm_medium=free_plugin",
          "titles" => array(
            array(
              "title" => __("Getting Instagram Access Token", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016277532-Configuring-Instagram-Access-Token?utm_source=instagram_feed&utm_medium=free_plugin"
            )
          )
        ),
        1 => array(
          "main_title" => __("Creating an Instagram Feed", 'wd-instagram-feed'),
          "url" => "https://help.10web.io/hc/en-us/articles/360016497251-Creating-Instagram-Feed?utm_source=instagram_feed&utm_medium=free_plugin",
          "titles" => array(
            array(
              "title" => __("Thumbnails and Masonry Layouts", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016277632?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Blog Style Layout", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016277632?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Image Browser", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016277632?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Lightbox Settings", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016277752?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Conditional Filters", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016497371?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
          )
        ),
        2 => array(
          "main_title" => __("Publishing Instagram Feed", 'wd-instagram-feed'),
          "url" => "https://help.10web.io/hc/en-us/articles/360016497391?utm_source=instagram_feed&utm_medium=free_plugin",
          "titles" => array(
            array(
              "title" => __("Publishing in a Page/Post", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016497391?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Publishing as a Widget", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016497391?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Publishing by PHP function", 'wd-instagram-feed'),
              "url" => "https://help.10web.io/hc/en-us/articles/360016497391?utm_source=instagram_feed&utm_medium=free_plugin",
            ),
          )
        ),
        3 => array(
          "main_title" => __("Styling with Themes", 'wd-instagram-feed'),
          "url" => "https://help.10web.io/hc/en-us/articles/360016277832?utm_source=instagram_feed&utm_medium=free_plugin",
          "titles" => array()
        )
      ),
      "overview_welcome_image" => null,
      "video_youtube_id" => "ijdrpkVAfEw",
      "plugin_wd_url" => "https://10web.io/plugins/wordpress-instagram-feed/?utm_source=instagram_feed&utm_medium=free_plugin",
      "plugin_wd_demo_link" => "https://demo.10web.io/instagram-feed/?utm_source=instagram_feed&utm_medium=free_plugin",
      "plugin_wd_addons_link" => "",
      "after_subscribe" => "admin.php?page=wdi_settings", // this can be plagin overview page or set up page
      "plugin_wizard_link" => "",
      "plugin_menu_title" => "Instagram Feed",
      "plugin_menu_icon" => WDI_URL . '/images/menu_icon.png',
      "subscribe" => false,
      "custom_post" => '',
      "menu_capability" => wdi_get_create_feeds_cap(),
      "menu_position" => null,
      "display_overview" => false,
    );
    if(WDI_IS_FREE){
      $wdi_wd_plugin_options["deactivate"] = TRUE;
      unset($wdi_wd_plugin_options["start_using_url"]);
    }else{
      $wdi_wd_plugin_options["deactivate"] = FALSE;
      $wdi_wd_plugin_options["start_using_url"] = "admin.php?page=wdi_settings";
    }

    ten_web_lib_init($wdi_wd_plugin_options);

  }
}

add_filter("plugin_row_meta", 'wdi_add_plugin_meta_links', 10, 2);

$wdi_token_error_flag = get_option("wdi_token_error_flag");
if($wdi_token_error_flag === "1"){
  add_action('admin_notices', 'wdi_token_error_flag_notice');
}

function wdi_token_error_flag_notice(){
  $screen_base = get_current_screen()->base;

  if($screen_base === "dashboard" || $screen_base === "toplevel_page_wdi_feeds" || $screen_base === "instagram-feed_page_wdi_themes" || $screen_base === "instagram-feed_page_wdi_settings" || $screen_base === "instagram-feed_page_overview_wdi" ){
    $link_to_reset = "<a href='" . site_url() . "/wp-admin/admin.php?page=wdi_settings'>reset token</a>";
    if($screen_base === "instagram-feed_page_wdi_settings"){
      $link_to_reset = "reset token";
    }
    echo "<div class='notice notice-error'><p>Instagram token is invalid or expired. Please " . wp_kses( $link_to_reset, array('a' => array('href')) ) . " and sign-in again to get new one.</p></div>";
  }
}

function wdi_add_plugin_meta_links( $meta_fields, $file ) {

  return $meta_fields;
}

add_action('admin_notices', 'wdi_filter_var_notice');
function wdi_filter_var_notice(){
  $screen = get_current_screen();
  if(!function_exists('filter_var') && ($screen->base === 'toplevel_page_wdi_feeds' || $screen->base === 'instagram-feed-wd_page_wdi_themes')) {
    echo "<div class='notice notice-error '>
	    <p>Some functionality may be broken. Please enable PHP Filters extension or make sure you have PHP version not older than 5.2.</p>
    </div>";
  }
}

/*ELEMENTOR*/
add_action('plugins_loaded', 'wdi_elementor');
function wdi_elementor(){
  if ( defined('ELEMENTOR_VERSION') ) {
    include_once 'elementor/elementor.php';
    WDIElementor::get_instance();
  }
}

function wdi_backend_ajax() {
  if ( function_exists('current_user_can') ) {
    if ( !current_user_can('manage_options') ) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once( WDI_DIR . '/framework/WDILibrary.php' );
  $page = WDILibrary::get('page');
  $action = WDILibrary::get('action');
  $ajax_nonce = WDILibrary::get('nonce');
  $allowed_pages = array(
    'settings',
  );
  $page = str_replace( WDI_PREFIX . '_', '', $page );
  $action = str_replace( WDI_PREFIX . '_', '', $action );
  $file = WDI_DIR . '/admin/controllers/' . $page . '.php';

  if ( !file_exists($file) ) {
    die ( esc_html( $page ) . ' file not found.' );
  }
  if ( wp_verify_nonce($ajax_nonce, 'wdi_cache') == FALSE ) {
    die ( 'Invalid nonce.' );
  }
  if ( !empty($page) && !in_array($page, $allowed_pages) ) {
    die ('The ' . esc_html($page) . ' page not available.');
  }

  require_once($file);
  $controller_class = ucfirst($page) . '_controller_' . WDI_PREFIX;
  $controller = new $controller_class();
  if ( method_exists($controller, $action) ) {
    $controller->$action();
  }
  else {
    die ( esc_html( $action ) . ' not found on ' . esc_html( ucfirst($page) ) . ' class.' );
  }
}

require_once(WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . '/booster/init.php');
add_action('init', function() {
  TWB(array(
        'plugin_dir' => WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . '/booster',
        'plugin_url' => plugins_url(plugin_basename(dirname(__FILE__))) . '/booster',
        'submenu' => array(
          'parent_slug' => 'wdi_feeds',
        ),
        'page' => array(
          'slug' => 'instagram-feed',
        ),
      ));
}, 11);
