<?php
/*
Plugin Name: 404 to Home
Description: Redirect all of the 404 error pages to homepage. No need for settings. Just install and activate it. Then start to use. That's it!
Version:     1.0
Author:      Burak Aydin
Author URI:  http://burak-aydin.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/



/**
* @version 1.0
  Redirect 404 pages to homepage
*/

add_action('template_redirect','tohome');

function tohome(){
	if(is_404()){
		wp_redirect(home_url(),301);
	}
}