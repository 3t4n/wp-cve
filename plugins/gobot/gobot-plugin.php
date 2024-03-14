<?php

/*
Plugin Name: Gobot - Sales Boosting Chatbot
Plugin URI:  https://www.getgobot.com
Description: Free chatbot that is better than popups and live chat at collecting emails! Also great for collecting feedback, surveys, social buttons, polls, and more.
Version:     1.0.9
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gobot
*/


define('GOBOT_SERVER', 'https://www.getgobot.com');


//
// Gobot plugin
//

function gobot_load_textdomain() {
  load_plugin_textdomain('gobot');
}
add_action('plugins_loaded', 'gobot_load_textdomain');




//
// Gobot settings and menu
//
function gobot_init_settings() {
  $apiKey = get_option('gobot_apikey');

  // register API key setting
  register_setting(
    'gobot_options',
    'gobot_apikey',
    array(
      'type' => 'string',
      'sanitize_callback' => 'gobot_sanitize_apikey'
    )
  );

  // register settings sections and fields
  add_settings_section(
    'gobot_connect',
    __('Gobot Options', 'gobot'),
    'gobot_connect_section_html',
    'gobot_options'
  );

  // only add Published Bots section if Gobot account has been connected
  if ($apiKey !== false) {
    add_settings_section(
      'gobot_summary',
      __('Published Bots', 'gobot'),
      'gobot_summary_section_html',
      'gobot_options'
    );
  }
}
add_action('admin_init', 'gobot_init_settings');


function gobot_connect_section_html($args) {
  $apiKey = get_option('gobot_apikey');

  if ($apiKey !== false) {
    echo '<form action="' . admin_url('admin-post.php') . '" method="post" style="margin-bottom:3em">';
      echo '<input type="hidden" name="action" value="gobot_disconnect" />';
      wp_nonce_field('gobot_disconnect');
      echo '<p>' . __('Connect your Gobot Account to complete the plugin installation.', 'gobot') . '</p>';
      echo '<p><button type="submit" class="button delete">' . __('Disconnect your Gobot Account', 'gobot') . '</button></p>';
      echo '<p>' . __('Once connected, your active bots will begin running on the site. You can disconnect your account at any time.', 'gobot') . '</p>';
    echo '</form>';
  }
  else {
    $nonce = wp_create_nonce('gobot_connect');
    $returnUrl = admin_url('admin-post.php?action=gobot_connect');

    echo '<form action="' . esc_url_raw(GOBOT_SERVER . '/app/connect') . '">';
      echo '<input type="hidden" name="app" value="wp" />';
      echo '<input type="hidden" name="url" value="' . esc_url_raw($returnUrl)  . '" />';
      echo '<input type="hidden" name="vk" value="' . esc_attr($nonce) . '" />';
      echo '<input type="hidden" name="site" value="' . get_home_url() . '" />';
      echo '<p>' . __('Connect your Gobot Account to complete the plugin installation.', 'gobot') . '</p>';
      echo '<p><button type="submit" class="button button-primary">' . __('Connect your Gobot Account', 'gobot') . '</button></p>';
      echo '<p>' . __('Once connected, your active bots will begin running on the site. You can disconnect your account at any time.', 'gobot') . '</p>';
    echo '</form>';
  }
}


function gobot_summary_section_html($args) {
  $apiKey = get_option('gobot_apikey');
  if ($apiKey === false) {
    return;
  }

  $bots = gobot_fetch_accountData($apiKey);

  echo '<div id="gobot-list">';
  echo '<style scoped>';
    echo '#gobot-list>table tbody td{padding:10px 9px;}';
    echo '#gobot-list>table thead th:first-child{padding-left:13px}';
    echo '#gobot-list>table tr.active>td:first-child{border-left:4px solid #00a0d2}';
    echo '#gobot-list>table tr.active>td.name{font-weight:bold}';
    echo '#gobot-list>table tr.inactive>td:first-child{padding-left:13px}';
  echo '</style>';
  echo '<table class="wp-list-table widefat striped">';
  echo '<thead><tr><th>' . __('Bot name', 'gobot') . '</th><th>' . __('Status', 'gobot') . '</th></tr></thead>';
  echo '<tbody id="gobot-list">';

  if (is_array($bots)) {
    foreach ($bots as $bot) {
      $botId = esc_attr($bot['bid']);
      $botActive = $bot['active'] ? __('Active', 'gobot') : __('Inactive', 'gobot');

      echo '<tr data-bid="' . $botId . '" class="' . strtolower($botActive) . '">';
        echo '<td class="name"><a href="' . GOBOT_SERVER . '/app/bots/' . $botId . '">' . esc_attr($bot['name']) . '</a></td>';
        echo '<td class="status">' . $botActive . '</td>';
      echo '</tr>';
    }
  }
  else {
    echo '<tr class="no-items"><td colspan="2">' . __('No bots found', 'gobot') . '</td></tr>';
  }

  echo '</tbody>';
  echo '</table>';
  echo '</div>';
}


function gobot_fetch_accountData($apiKey) {
  $args = array('headers' => array('Referer' => site_url()));
  $response = wp_remote_get( esc_url_raw(GOBOT_SERVER . '/app/connect/list/' . $apiKey), $args );
  if (!is_array($response)) {
    return null;
  }

  $body = wp_remote_retrieve_body($response);
  $jsonData = json_decode( $body, true );
  return $jsonData;
}


function gobot_sanitize_apikey($input) {
  $prevValue = get_option('gobot_apikey');

  $sanitized = sanitize_text_field($input);
  if (!preg_match('/^[A-Z0-9_\-]*$/i', $sanitized)) {
    add_settings_error(
      'gobot_apikey',
      'gobot_apikey',
      __('invalid API key', 'gobot'),
      'error'
    );
    return $prevValue;
  }
  else {
    return $sanitized;
  }
}


function gobot_init_menu() {
  // register top-level menu page
  add_menu_page(
    'Gobot',
    'Gobot',
    'manage_options',
    'gobot_options',
    'gobot_options_html',
    plugins_url('gobot/gobot.png')
  );
}
add_action('admin_menu', 'gobot_init_menu');


function gobot_options_html() {
  // verify user capabilities
  if (!current_user_can('manage_options')) {
    return;
  }

  // display errors/messages
  $msg = get_transient('gobot-msg');
  if ($msg) {
    add_settings_error('gobot_apikey', $msg['code'], $msg['message'], $msg['type']);
  }
  settings_errors();

  ?>
  <div class="wrap">
  <?php
    do_settings_sections('gobot_options');
  ?>
  </div>
  <?php
}


function gobot_connect() {
  // disconnecting, verify nonce
  if (wp_verify_nonce($_GET['nonce'], 'gobot_connect')) {
    update_option('gobot_apikey', $_GET['key']);

    set_transient('gobot-msg', array(
      'code' => 'connected',
      'message' => __('Gobot is now connected', 'gobot'),
      'type' => 'updated'
    ), 10);
  }
  else {
    set_transient('gobot-msg', array(
      'code' => 'connect-error',
      'message' => 'connect error',
      'type' => 'error'
    ), 10);
  }

  wp_redirect( admin_url('admin.php?page=gobot_options') );
  exit;
}
add_action('admin_post_gobot_connect', 'gobot_connect');


function gobot_disconnect() {
  // disconnecting, verify nonce
  if (wp_verify_nonce($_POST['_wpnonce'], 'gobot_disconnect')) {
    delete_option('gobot_apikey');

    set_transient('gobot-msg', array(
      'code' => 'disconnected',
      'message' => __('Gobot has been disconnected', 'gobot'),
      'type' => 'updated'
    ), 10);
  }
  else {
    set_transient('gobot-msg', array(
      'code' => 'disconnect-error',
      'message' => 'disconnect error',
      'type' => 'error'
    ), 10);
  }

  wp_redirect( admin_url('admin.php?page=gobot_options') );
  exit;
}
add_action('admin_post_gobot_disconnect', 'gobot_disconnect');




//
// Gobot client script
//
function gobot_append_script() {
  $apiKey = get_option('gobot_apikey');
  if ($apiKey) {
  ?>
  <script>
  (function(){
  window['gobot'] = window['gobot'] || function(){(window['gobot'].queue = window['gobot'].queue || []).push(arguments)}
  var script = document.createElement('script')
  script.async = 1
  script.src = '<?php echo GOBOT_SERVER ?>/app/v1/gobot-client.js'
  var insert = document.getElementsByTagName('script')[0]
  insert.parentNode.insertBefore(script, insert)
  })()
  gobot('create', '<?php echo esc_attr($apiKey) ?>')
  gobot('pageview')
  </script>
  <?php
  }
}
add_action('wp_footer', 'gobot_append_script');
