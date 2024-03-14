<?php
/*
Plugin Name:    MakeStories Helper
Plugin URI:     https://makestories.io/official-wordpress-webstories-plugin/
Description:    The leading Google Web Stories Editor is now available to create Stories in WordPress. It is easy to use, allows for extensive customization, and is adaptive for future changes.
Version:        3.0.3
Author:         MakeStories Team
Author URI:     https://makestories.io
License:        GPL2
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
*/

define("MS_PLUGIN_BASE_PATH", plugin_dir_path(__FILE__));
define("MS_PLUGIN_BASE_URL", plugin_dir_url(__FILE__));
define("MS_WP_ADMIN_BASE_URL", admin_url("admin.php"));

//Load Main configuration
require_once MS_PLUGIN_BASE_PATH."config.php";

//Load helper functions
require_once MS_PLUGIN_BASE_PATH."helpers.php";

//Add hooks
require_once MS_PLUGIN_BASE_PATH."hooks.php";

//Add shortcode
require_once MS_PLUGIN_BASE_PATH."shortcode.php";

//Add Gutenburg
require_once MS_PLUGIN_BASE_PATH."gutenberg-block.php";

//Add basic auth functionality
require_once MS_PLUGIN_BASE_PATH."basic-auth.php";

//Add All Pages
require_once MS_PLUGIN_BASE_PATH."pages/index.php";
//Add Apis
require_once MS_PLUGIN_BASE_PATH."api/index.php";

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ms_add_action_links' );