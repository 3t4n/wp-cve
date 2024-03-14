<?php
/*
Plugin Name: Redirect 404 Error Page to Homepage
Plugin URI: http://ProThoughts.com
Description: Simple redirect wordpress plugin, all 404 error pages are redirected to homepage.
Version: 1.1
Author: ProThoughts
Author URI: http://prothoughts.com
Text Domain: redirect-404-error-page-to-homepage
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function redirect_404_error_page_to_homepage() {
  	if (is_404()) {
       wp_redirect(home_url(),301);
	   die();
	}
}

add_action('wp', 'redirect_404_error_page_to_homepage', 1);
