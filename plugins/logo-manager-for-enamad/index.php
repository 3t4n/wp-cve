<?php
/**
 *Plugin Name: لوگوی نماد الکترونیکی
 *Plugin URI : http://wp-master.ir
 *Author: wp-master.ir
 *Author URI: http://wp-master.ir
 *Description:  جهت قراردادن خودکار لوگوی نماد الکترونیکی( اینماد ) در سایت| قابلیت کدکوتاه و ابزارک برای ای نماد | شامد | نماد های دیگر
 *Version: 0.7.0
 */

/*
* No script kiddies please!
*/
defined('ABSPATH') or die("اللهم صل علی محمد و آل محمد و عجل فرجهم");

/*
* Defines
*/
$plugins_url = rtrim(plugin_dir_url(__FILE__), '/') . '/';
define('_enamadlogo_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('_enamadlogo_PATH', $plugins_url);
define('_enamadlogo_BASENAME', plugin_basename(__FILE__));
define('_enamadlogo_ver', '0.6');

/**
 * fns
 */
include _enamadlogo_DIR . 'fns.php';

/**
 * activate action
 * redirect to admin settings
 */
register_activation_hook(__FILE__, 'enamadlogo_hook_activate');


/**
 * Admin panel menu
 */
include _enamadlogo_DIR . 'simple-class-options.php';
/**
 * Widgets
 */
include _enamadlogo_DIR . 'widgets.php';
