<?php

class Settings_controller_wdi {

  private $view;
  private $allowed_message_html;

  function __construct() {
    require_once(WDI_DIR . '/admin/views/settings.php');
    $this->view = new Settings_view_wdi();
    // Format an array of allowed HTML tags and attributes.
    $this->allowed_message_html = array(
      'div' => array(
        'class' => true,
        'style' => true
      ),
      'p' => array(),
      'strong' => array()
    );
  }

  public function execute() {
    $task = WDILibrary::get('task');
    if ( method_exists($this, $task) ) {
      check_admin_referer('wdi_nonce', 'wdi_nonce');
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    $wdi_options = wdi_get_options();
    $settings = wdi_get_settings();
    $db_options = array();
    foreach ( $settings as $setting ) {
      $settingDefault = isset($setting['default']) ? $setting['default'] : '';
      $db_options[$setting['name']] = $settingDefault;
    }
    $options = wp_parse_args($wdi_options, $db_options);

    $this->reset_access_token();
    $this->basic_instagram_api_connect();
    $this->graph_instagram_api_connect();

    $message = WDILibrary::get('message', '');
    if ( !empty($message) ) {
      echo wp_kses(WDILibrary::message($message, 'error'), $this->allowed_message_html);
    }
    $args = array();
    $args['options'] = $options;
    $args['authenticated_users_list'] = json_decode($wdi_options['wdi_authenticated_users_list'], TRUE);
    $args['min_capability'] = array(
      'manage_options' => __('Administrator', 'wd-instagram-feed'),
      'publish_posts' => __('Author', 'wd-instagram-feed'),
      'contributor' => __('Contributor', 'wd-instagram-feed'),
    );
    $this->view->display($args);
  }

  /**
   * Save.
   */
  private function save() {
    $post = WDILibrary::get(WDI_OPT);
    $wdi_options = wdi_get_options();
    $wdi_options['wdi_upd_microtime'] = microtime();
    $wdi_options['wdi_transient_time'] = (intval($post['wdi_transient_time']) < 10 ) ? 2880 : intval($post['wdi_transient_time']);
    $wdi_options['wdi_feeds_min_capability'] = $post['wdi_feeds_min_capability'];
    $wdi_options['wdi_custom_css'] = esc_js(str_replace(array( "\n", "\r" ), "", $post['wdi_custom_css']));
    $wdi_options['wdi_custom_js'] = esc_js(str_replace(array( "\n", "\r" ), "", $post['wdi_custom_js']));
    $wdi_options['wdi_cache_request_count'] = intval($post['wdi_cache_request_count']);
    update_option(WDI_OPT, $wdi_options);
    $message_id = 8;
    WDILibrary::redirect(add_query_arg(array(
                                         'page' => 'wdi_settings',
                                         'message' => $message_id,
                                       ), admin_url('admin.php')));
  }

  private function reset_access_token() {
    $reset_access_token = WDILibrary::get('wdi_reset_access_token_input', '');
    if ( !empty($reset_access_token) && $reset_access_token == '1' ) {
      global $wpdb;
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $wpdb->query( esc_sql('DELETE FROM ' . $wpdb->prefix . 'options WHERE option_name = "wdi_instagram_options"') ); //db call ok
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $wpdb->query( esc_sql('DELETE FROM ' . $wpdb->prefix . 'options WHERE option_name = "wdi_first_user_username"') ); //db call ok
      WDILibrary::redirect(add_query_arg(array( 'page' => 'wdi_settings' ), admin_url('admin.php')));
    }
  }

  private function basic_instagram_api_connect() {
    if ( !empty($_REQUEST['wdi_nonce_token']) && wp_verify_nonce(sanitize_key($_REQUEST['wdi_nonce_token']), 'wdi_nonce_token') && !empty($_REQUEST['wdi_access_token']) && !empty($_REQUEST['user_id']) ) {
      $wdi_options = wdi_get_options();
      $time = time();
      /* @ToDo in the next version
       * In this part we need to transfer the data from the API to the "base64_encode" type, as done in 'graph_instagram_api_connect'.
       * And let's add an error check operation
       */
      $user_id = WDILibrary::get('user_id', '');
      $user_name = WDILibrary::get('username', '');
      $expires_in = WDILibrary::get('expires_in', '');
      $access_token = WDILibrary::get('wdi_access_token', '');
      $authenticated_users_list = array();
      if ( !empty($wdi_options['wdi_authenticated_users_list']) ) {
        $authenticated_users_list = json_decode($wdi_options['wdi_authenticated_users_list'], TRUE);
      }

      $authenticated_users_list[$user_name] = array(
        'type' => 'personal',
        'user_id' => $user_id,
        'user_name' => $user_name,
        'access_token' => $access_token,
        'start_in' => $time,
        'expires_in' => $expires_in,
      );

      $wdi_options['wdi_authenticated_users_list'] = $authenticated_users_list;

      if ( empty($wdi_options['wdi_user_name']) ) {
        $wdi_options['wdi_user_id']   = $user_id;
        $wdi_options['wdi_user_name'] = $user_name;
        $wdi_options['wdi_start_in']  = $time;
        $wdi_options['wdi_expires_in'] = $expires_in;
        $wdi_options['wdi_access_token'] = $access_token;
      }
      update_option(WDI_OPT, $wdi_options);
      delete_option('wdi_token_error_flag');

      WDILibrary::redirect(add_query_arg(array(
                                           'page' => 'wdi_settings',
                                           'message' => 29,
                                         ), admin_url('admin.php')));
    }
  }

  private function graph_instagram_api_connect() {
    return;
  }

  function account_refresh() {
    $user_name = WDILibrary::get('user_name');
    $return_data = WDILibrary::refresh_instagram_account($user_name);
    echo wp_json_encode($return_data);
    exit;
  }
  /**
   * Account disconnect.
   *
   * @return json
   */
  function account_disconnect() {
    $user_name = WDILibrary::get('user_name');
    $wdi_options = wdi_get_options();
    if ( !empty($wdi_options['wdi_authenticated_users_list']) ) {
      $authenticated_users_list = json_decode($wdi_options['wdi_authenticated_users_list'], TRUE);
      unset($authenticated_users_list[$user_name]);
      $wdi_options['wdi_authenticated_users_list'] =  wp_json_encode($authenticated_users_list);
      $update = update_option(WDI_OPT, $wdi_options);
      $json = array(
        'message' => __('Failed.', 'wd-instagram-feed'),
        'success' => FALSE,
      );
      if ( $update ) {
        $json = array(
          'message' => __('Item Succesfully is Deleted.', 'wd-instagram-feed'),
          'success' => TRUE,
        );
      }
      echo wp_json_encode($json);
      exit;
    }
  }
}