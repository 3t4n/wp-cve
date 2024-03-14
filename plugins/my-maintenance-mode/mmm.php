<?php
/*
Plugin Name: My Maintenance Mode
Plugin URI: http://www.sooource.net/my-maintenance-mode
Description: The plugin allows you to put your website on the WordPress into maintenance mode.
Version: 1.0.2
Author: TrueFalse
Author URI: http://www.sooource.net
License: GPLv2 or later
Text Domain: mmm
Domain Path: /languages
*/

load_plugin_textdomain('mmm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

/* Инициализация плагина. */
add_action('admin_init', 'mmm_admin_init');

/* Деинсталляция плагина: удаление полей-настроек из БД. */
register_uninstall_hook(__FILE__, 'mmm_deinstall'); 

/* Загружаем переводы, добавляем опции в БД. */
function mmm_admin_init() {
  add_settings_section('mmm_privacy', __('Maintenance mode', 'mmm'), 'mmm_privacy_callback', 'privacy');
  add_settings_field('mmm_settings_maintenance_mode', 'Offline', 'mmm_maintenance_mode_callback', 'privacy', 'mmm_privacy');
  register_setting('privacy', 'mmm_maintenance_mode');
  if ( !current_user_can('administrator') ) // Если user, не admin, то пусть выходит.
    wp_logout();
}

/* На страницу "Общие" добавляем основные настройки нашего плагина. */
function mmm_privacy_callback() {
  echo '';
}

function mmm_maintenance_mode_callback() {
  echo '<input name="mmm_maintenance_mode" id="mmm_maintenance_mode" type="checkbox" value="1" class="code" ' . checked(1, get_option('mmm_maintenance_mode'), false) . ' /> '. __('Translate the site into maintenance mode', 'mmm');
}

/* Удаляем созданные нами поля из БД. */
function mmm_deinstall() {
  delete_option('mmm_maintenance_mode');
}

/* Обслуживание. Генерация страницы-заглушки. */
function mmm_maintenance_mode() {
  if ( !current_user_can('administrator') )
    wp_die('&laquo;'. get_bloginfo('name'). '&raquo;. '. __('Now maintenance. Please check back later.', 'mmm'),
           __('Maintenance mode', 'mmm'),
           array('response'=>503)); /* Код ответа сервера - 503. */
}

/* Если находится на обслуживании. */
if (get_option('mmm_maintenance_mode')==1) {
  add_action('admin_notices', 'mmm_admin_notice');
  add_action('get_header', 'mmm_maintenance_mode');
}

/* Уведомление. */
function mmm_admin_notice(){
  echo '<div class="updated">
        <p>'. __('The site is in maintenance mode', 'mmm'). '.</p>
      </div>';
}
?>