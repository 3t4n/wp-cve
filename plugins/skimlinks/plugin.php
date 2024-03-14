<?php

/*
Plugin Name: Skimlinks
Plugin URI: http://wordpress.org/extend/plugins/skimlinks/
Description: Skimlinks helps you easily monetize content by converting product links in your post into their equivalent affiliate links on-the-fly.                 Install the plugin and add a new revenue stream to your blog, including monetising the links in your RSS feed, without affecting your users' experience.
Author: Skimlinks
Version: 1.3
Author URI: http://skimlinks.com/
Text Domain: skimlinks
*/

define( 'SL_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'SL_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename(  dirname( __FILE__ ) ) );
define( 'SL_ADMIN_URL', add_query_arg( 'page', 'skimlinks-options', admin_url() . 'options-general.php' ) );

//Include the required plugin files
include_once( SL_PLUGIN_PATH . '/functions.php' ); 
include_once( SL_PLUGIN_PATH . '/admin.php' );
include_once( SL_PLUGIN_PATH . '/hooks.php' );
include_once( SL_PLUGIN_PATH . '/widget.php' );
