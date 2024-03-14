<?php
/*
  Plugin Name: BuddyPress Registration widget
  Plugin URI: http://clariontechnologies.co.in
  Description: BuddyPress Registration form widget
  Version: 2.1.2
  Author: Yogesh Pawar, clarionwpdeveloper
  Author URI: http://clariontechnologies.co.in
  License: GPLv2 or later
  Text Domain: BuddyPress Registration form widget
 */

//Plugin Constant
defined('ABSPATH') or die('Restricted direct access!');
define('AUTH_PLUGINS_PATH', plugins_url());

$plugin = plugin_basename(__FILE__);
define('BUDDY_FILE_DIRECTORY', __DIR__);

//Main Plugin files
if (!class_exists('Buddy_Registration')) {
    require('classes/class.buddy.registration.php');
}

?>