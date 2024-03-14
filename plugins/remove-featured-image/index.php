<?php

/**
 * @package Remove Featured Image
 */
/*
 * Plugin Name: Remove Featured Image
 * Plugin URI: http://www.viralsoftwares.com/remove-featured-image
 * Description: To show/hide/remove featured images on individual posts or from all posts.
 * Author: Sumit Chattha
 * Version: 1.1
 * Author URI: http://www.viralsoftwares.com/sumit-chattha
 * Text Domain: Remove Featured Image
 */
define( 'RFI_PREFIX',         'rfi_');
define( 'RFI_PLUGIN_SLUG',    plugin_basename(__FILE__));
define( 'RFI_PLUGIN_PATH',    untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'RFI_PLUGIN_URL',     plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) );
require_once RFI_PLUGIN_PATH."/inc/hooks.php";
require_once RFI_PLUGIN_PATH."/inc/functions.php";