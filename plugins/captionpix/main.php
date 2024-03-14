<?php
/*
 * Plugin Name: Caption Pix
 * Plugin URI: https://www.captionpix.com
 * Description: Displays images with captions beautifully
 * Version: 1.8
 * Author: Russell Jamieson
 * Author URI: https://www.diywebmastery.com/about
 * License: GPLv2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('CAPTIONPIX_VERSION', '1.8');
define('CAPTIONPIX_PLUGIN_NAME', 'CaptionPix');
define('CAPTIONPIX_SLUG', 'captionpix');
define('CAPTIONPIX_PATH', plugin_basename(__FILE__));
define('CAPTIONPIX_PLUGIN_URL', plugins_url(CAPTIONPIX_SLUG));
define('CAPTIONPIX_DOMAIN', 'CAPTIONPIX_DOMAIN');  
define('CAPTIONPIX_HOME', 'https://www.captionpix.com/');
define('CAPTIONPIX_HELP','https://www.captionpix.com/help/');
define('CAPTIONPIX_ICON', 'dashicons-format-image');
define('CAPTIONPIX_UPDATER', 'http://updater%1$s.captionpix.com/');
define('CAPTIONPIX_NEWS', 'https://www.diywebmastery.com/tags/newsfeed/feed/?images=1&featured_only=1');
if (!defined('DIYWEBMASTERY_NEWS')) define('DIYWEBMASTERY_NEWS', 'https://www.diywebmastery.com/tags/newsfeed/feed/?images=1&featured_only=1');
if (!defined('CAPTIONPIX_IMAGES_URL')) define('CAPTIONPIX_IMAGES_URL', CAPTIONPIX_PLUGIN_URL.'/images');
if (!defined('CAPTIONPIX_BORDERS_URL')) define('CAPTIONPIX_BORDERS_URL', CAPTIONPIX_PLUGIN_URL.'/borders');
if (!defined('CAPTIONPIX_FRAMES_URL')) define('CAPTIONPIX_FRAMES_URL', CAPTIONPIX_PLUGIN_URL.'/frames');
require_once(dirname(__FILE__) . '/classes/class-plugin.php');
Captionpix_Plugin::get_instance();
