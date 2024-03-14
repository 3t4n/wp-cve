<?php
/**
 * Plugin Name: Read More
 * Description: Hide additional content by wrapping content in an [expander_maker] shortcode.
 * Version: 3.3.8
 * Author: Edmon
 * Author URI: https://edmonsoft.com
 * License: GPLv2
 */

define('YRM_PLUGIN_PREF', plugin_basename(__FILE__));
require_once(dirname(__FILE__).'/config.php');
require_once(YRM_CLASSES."ReadMoreInit.php");
define('YRM_FOLDER_NAME',  dirname( plugin_basename(__FILE__) ));
$readMoreObj = new ReadMoreInit();