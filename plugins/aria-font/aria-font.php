<?php
/**
* Plugin Name: Aria Font
* Plugin URI: https://wordpress.org/plugins/aria-font
* Description: Add new fonts to wordpress
* Version: 1.4
* Author: AriaWP
* Author URI: https://ariawp.com
* Text Domain: aria-font
* Domain Path: /languages
* License: GPLv2
*/

if (!defined('ABSPATH')) exit;
 
load_plugin_textdomain('aria-font', false, dirname(plugin_basename( __FILE__ )) . '/languages');

define("ARIAFONTPLUGINURL", plugin_dir_url(__FILE__));
define("ARIAFONTPLUGINPATH", plugin_dir_path(__FILE__));

include('includes/main.php');
include('includes/settings.php');

?>
