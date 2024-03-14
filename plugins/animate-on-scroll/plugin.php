<?php
	
/**
* Plugin Name: Animate on Scroll
* Description: Animate any Elements on scroll using the popular AOS JS library simply by adding class names.
* Author: Arya Dhiratara
* Author URI: https://dhiratara.com/
* Version: 1.0.6
* Requires at least: 5.8
* Requires PHP: 7.4
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: aos-wp
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('AOSWP_VERSION', '1.0.6');
define('AOSWP_HANDLER', 'aoswp');
define("AOSWP_DIR", plugin_dir_path(__FILE__));
define("AOSWP_PUBLIC_URL", plugin_dir_url(__FILE__) . 'public/');
define("AOSWP_INCLUDE_DIR", plugin_dir_path(__FILE__) . 'includes/');
define("AOSWP_VENDOR_DIR", plugin_dir_path(__FILE__) . 'vendor/');

include_once(AOSWP_INCLUDE_DIR . 'init.php');