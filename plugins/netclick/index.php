<?php
/**
 * Plugin Name: Netclick
 * Description: Analytics, statistiques et suivi de visiteurs
 * Version: 0.1.0
 * Author: Netclick
 * Author URI: https://netclick.io
 */
register_uninstall_hook(__FILE__, 'netclick_plugin_cleanup');
add_action('admin_menu', 'netclick_create_menu');

function netclick_plugin_cleanup() {
  delete_option('netclick-script-token');
  delete_option('netclick-onboarding');
}

function netclick_create_menu() {
  add_menu_page(__('Netclick', 'Netclick'), __('Netclick', 'Netclick'), 'administrator', __FILE__, 'netclick_settings_page', plugins_url('assets/netclick-icon-only.svg', __FILE__));
  add_action('admin_init', 'netclick_register_settings');
  add_action('admin_init', 'netclick_onboarding');
}

function netclick_register_settings() {
  register_setting('netclick', 'netclick-script-token');
  add_option('netclick-onboarding', false);
}

function netclick_onboarding() {
  $onboarding = get_option('netclick-onboarding');
  $script_token = get_option('netclick-script-token');

  if ((empty($onboarding) || !$onboarding)) {
    wp_redirect('admin.php?page=' . plugin_basename(__FILE__));
    update_option('netclick-onboarding', true);
  }
}

function netclick_settings_page() {
  $email = urlencode(wp_get_current_user()->user_email);

  ?>
    <div class="card">
      <a href="https://netclick.io?utm_source=wordpress" target="_blank" rel="noopener">
        <img style="margin-left: -13px;" src="<? echo plugins_url("assets/logo.png", __FILE__ ); ?>" width="180"/>
      </a>
      <? settings_errors(); ?>
      <h3>Installation</h3>
      <p>1. Créer un compte gratuitement sur <a href="https://netclick.io/register?utm_source=wordpress" target="_blank" rel="noopener">Netclick</a></p>
      <p>2. Copier et coller le code d'installation ci-dessous :</p>

      <form action="options.php" method="POST">
        <?
          echo settings_fields('netclick');
          echo do_settings_sections('netclick');
          ?>
        <textarea name="netclick-script-token" id="netclick-script-token" cols="60" rows="20"><? echo esc_attr(get_option('netclick-script-token')) ?></textarea>
        <br>
        <? submit_button(); ?>
      </form>
    </div>
  <?
}

function netclick_javascript_block() {
  echo get_option('netclick-script-token');
}

add_action('wp_head', 'netclick_javascript_block', 1);
