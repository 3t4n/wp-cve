<?php
/**
 * Plugin Name: Notibar - WordPress Notification Bar
 * Plugin URI: https://ninjateam.org/notibar-wordpress-notification-bar
 * Description: Custom notification bar for alert, promo code, marketing campaign, top banner
 * Version: 2.1.4
 * Author: Ninja Team
 * Author URI: https://ninjateam.org
 * Text Domain: notibar
 * Domain Path: /i18n/languages/
 *
 * @package NjtNotificationBar
 */

namespace NjtNotificationBar;

defined('ABSPATH') || exit;

define('NJT_NOFI_PREFIX', 'njt_nofi');
define('NJT_NOFI_VERSION', '2.1.4');
define('NJT_NOFI_DOMAIN', 'notibar');

define('NJT_NOFI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NJT_NOFI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NJT_NOFI_SITE_URL', site_url());

spl_autoload_register(function ($class) {
  $prefix = __NAMESPACE__; // project-specific namespace prefix
  $base_dir = __DIR__ . '/includes'; // base directory for the namespace prefix

  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) { // does the class use the namespace prefix?
    return; // no, move to the next registered autoloader
  }

  $relative_class_name = substr($class, $len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class_name) . '.php';

  if (file_exists($file)) {
    require $file;
  }
});

// Add crossale for filebird
if (file_exists(dirname(__FILE__) . '/includes/cross.php')) {
  require_once dirname(__FILE__) . '/includes/cross.php';
}

function init() {
  Plugin::activate();
  Plugin::getInstance();
  I18n::loadPluginTextdomain();

  NotificationBar\WpCustomNotification::getInstance();
  NotificationBar\NotificationBarHandle::getInstance();
  NotificationBar\WpPosts::getInstance();
  NotificationBar\overrideOldVer::overrideThemeMod();
}
add_action('plugins_loaded', 'NjtNotificationBar\\init');

register_activation_hook(__FILE__, array('NjtNotificationBar\\Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('NjtNotificationBar\\Plugin', 'deactivate'));
