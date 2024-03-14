<?php
/*
 * Plugin Name: Click to Chat - WP Support All-in-One Floating Widget
 * Plugin URI: https://ninjateam.org
 * Description: Allows your customers to click to chat/contact support via Phone Call, WhatsApp, WhatsApp Business, Facebook Messenger, Line, KakaoTalk, Email, and multiple custom apps.
 * Version: 2.3.1
 * Author: NinjaTeam
 * Author URI: https://ninjateam.org
 */

define('WP_SAIO_DIR', realpath(plugin_dir_path(__FILE__)));
define('WP_SAIO_URL', plugins_url('', __FILE__));
define('WP_SAIO_FILE', __FILE__);
define('WP_SAIO_LANG_PREFIX', 'wp_saio');
define('WP_SAIO_BASE_NAME', plugin_basename(__FILE__));

require_once WP_SAIO_DIR . '/autoload.php';

require_once WP_SAIO_DIR . '/functions.php';

WpSaioInit::instance();

register_activation_hook(__FILE__, array('WpSaioInit', 'activate'));
register_deactivation_hook(__FILE__, array('WpSaioInit', 'deactivate'));
