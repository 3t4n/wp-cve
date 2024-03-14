<?php
/*
Plugin Name: Site PIN
Plugin URI: http://www.bang-on.net/
Description: Prevent careless visitors by locking your site down with a PIN
Version: 1.3
Author: Marcus Downing
Contributors: marcusdowning, diddledan
Author URI: http://www.bang-on.net
License: Private
*/

/*  Copyright 2011  Marcus Downing  (email : marcus@bang-on.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('SITE_PIN_DEBUG'))
  define('SITE_PIN_DEBUG', false);

add_filter("plugin_action_links_".plugin_basename(__FILE__), 'site_pin_settings_links' );

function site_pin_settings_links($links) {
  do_action('log', 'Settings link', $links);
  array_unshift($links, '<a href="tools.php?page=site-pin.php">Settings</a>');
  return $links; 
}

add_action('template_redirect', 'site_pin_template_redirect');
function site_pin_template_redirect() {
  if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: template_redirect');
  $enabled = (boolean) get_site_option('site_pin_enable', true);
  $pin = (string) get_site_option('site_pin_code', "0000");

  if (!$enabled) return;
  if (is_admin()) return;
  if (is_user_logged_in()) return;
  if (defined('DOING_AJAX') && DOING_AJAX) return;

  $uri = $_SERVER['REQUEST_URI'];
  if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: checking URI', $uri);
  if (!preg_match('!\.php$!', $uri)) {
    if (preg_match('!^/static/!', $uri)) return;
    if (preg_match('!^/wp-content/!', $uri)) return;
    if (preg_match('!\.css$!', $uri)) return;
    if (preg_match('!\.js$!', $uri)) return;
  }

  if (!empty($_REQUEST['site_pin'])) {
    $vpin = $_REQUEST['site_pin'];
    if ($vpin == $pin) {
      if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: Setting session var', $vpin);
      session_start();
      $_SESSION['site_pin'] = $vpin;

      //  redirect back to the same page, without the ?site_pin= parameter
      //  (making sure the Site PIN page isn't cached)
      $redirect = remove_query_arg('site_pin');
      wp_redirect($redirect);
      exit;
    }
  } else {
    session_start();
    $vpin = $_SESSION['site_pin'];
    if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: Retrieved session var', $vpin);
  }

  if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: %s == %s', $pin, $vpin);
  if (empty($vpin) || $pin != $vpin) {
    if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: Incorrect PIN');

    define('DONOTCACHEPAGE', true);
    header("HTTP/1.0 200 OK");
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    header("Cache-Control: no-store, no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    include('pin-entry.php');
    exit;
  }
}

add_action('admin_menu', 'site_pin_init');
function site_pin_init() {
  if (SITE_PIN_DEBUG) do_action('log', 'Site PIN: Admin init');
  if (current_user_can('edit_theme_options'))
    add_submenu_page('tools.php', 'Site PIN', 'Site PIN', 'edit_theme_options', basename(__FILE__), 'site_pin_settings');
  else if (current_user_can('edit_posts'))
    add_submenu_page('tools.php', 'Site PIN', 'Site PIN', 'edit_posts', basename(__FILE__), 'site_pin_readout');
}

function site_pin_readout() {
  if (!current_user_can('edit_posts'))
    return;
  $enabled = (boolean) get_site_option('site_pin_enable', true);
  $pin = (string) get_site_option('site_pin_code', "0000");
  $message = (string) get_site_option('site_pin_message', '');

  ?><div class="wrap">
  <h1><i class="dashicons dashicons-admin-network"></i> Site PIN</h1>
  <?php if ($enabled) { ?>
    The site requires a PIN to read:

    <p style='margin: 20px auto; width: 250px; font-size: 40px; padding: 10px; width: 250px; text-align: center; background: #ffc;'>
      <?php echo $pin; ?>
    </p>
  <?php } else { ?>
    The site does not currently require a PIN
  <?php }
}

function site_pin_settings() {
  if (!current_user_can('edit_theme_options'))
    return;
  $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
  switch ($action) {
    case "save":
      $enable = (boolean) $_REQUEST['site_pin_enable'];
      update_site_option('site_pin_enable', $enable);

      $pin = $_REQUEST['site_pin_code'];
      update_site_option('site_pin_code', $pin);

      $message = $_REQUEST['site_pin_message'];
      update_site_option('site_pin_message', $message);
      break;
  }

  $enabled = (boolean) get_site_option('site_pin_enable', true);
  $pin = (string) get_site_option('site_pin_code', "0000");
  $message = (string) get_site_option('site_pin_message', '');

  $checked = $enabled ? 'checked' : '';

  ?><div id='bang-leftbar' class='site-pin'>
    <a href="http://www.bang-on.net">
      <img src="<?php echo plugins_url('images/bang-black-v.png', __FILE__); ?>" /></a>
    <div><h1>Site PIN</h1></div>
  </div>

  <div id='bang-main' class="wrap">
  <h1><i class="dashicons dashicons-admin-network"></i> Site PIN</h1>
  <form method="post">
    <p><label for='enable'><input type='checkbox' id='enable' name='site_pin_enable' <?php echo $checked ?>/>
        Lock the site with a PIN
      </label></p>
    <p>This will prevent any stray users from accessing the site unless they enter the right PIN.</p>

    <p style='margin: 20px auto; width: 250px;'>
      <input id='site_pin_code' name='site_pin_code' type='text' style='font-size: 40px; padding: 10px; width: 250px; text-align: center; background: #ffc;' value='<?php echo $pin; ?>' />
    </p>
    <p>We recommend at least 4 digits</p>

    <p><label for='message'>Site PIN message</label><br/>
    <input type='text' id='message' name='site_pin_message' value='<?php echo esc_attr($message); ?>' style='width: 100%;' /></p>

    <input type='hidden' name='action' value='save'/>
    <p><input type='submit' value='Save Settings' class='button-primary'/></p>
  </form>
  </div><?php
}
