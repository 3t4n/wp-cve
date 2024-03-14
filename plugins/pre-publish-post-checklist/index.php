<?php
/**
 * Plugin Name: Pre-Publish Post Checklist
 * Plugin URI: http://www.mead.io
 * Description: With Pre-Publish Post Checklist, you’ll never have to worry about accidentally publishing a post.
 * Version: 3.1
 * Author: Andrew Mead
 * Author URI: http://www.mead.io
 * License: GPL2
 */

$PUBLISH_CHECKLIST_URI = WP_PLUGIN_URL . "/pre-publish-post-checklist"; // WP_PLUGIN_URL WP_PLUGIN_DIR
$PUBLISH_CHECKLIST_DIR = WP_PLUGIN_DIR . "/pre-publish-post-checklist"; // WP_PLUGIN_URL WP_PLUGIN_DIR

require_once('inc/ajax.php');
require_once('inc/page-setups.php');
require_once('inc/plugin-actions.php');