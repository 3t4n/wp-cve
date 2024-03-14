<?php
/*
Plugin Name: Responsive Videos by Angie Makes
Plugin URI: http://angiemakes.com/feminine-wordpress-blog-themes-women/
Description: Simple responsive video plugin. Automatically determine aspect ratio. CSS only. No Javascript.
Author: Chris Baldelomar
Author URI: http://angiemakes.com/
Version: 1.11
License: GPLv2 or later
*/

define( 'WC_RESPONSIVE_VIDEO_VERSION', '1.11' );

// Should only work on the front end
if ( is_admin() ) {
	return;
}

require_once( dirname(__FILE__) . '/includes/functions.php' ); // Adds basic filters and actions
require_once( dirname(__FILE__) . '/includes/scripts.php' ); // Adds plugin JS and CSS
