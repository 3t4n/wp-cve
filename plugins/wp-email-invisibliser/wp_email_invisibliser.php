<?php
/*
Plugin Name: WP Email Invisibliser
Plugin URI: http://www.sargant.net
Description: A simple plugin to hide emails from spambots. Simply use the shortcode [hide_email myemail@mydomain.com] to hide myemail@mydomain.com from harvesters but create a clickable email link.
Version: 0.1.2
Author: Adam Sargant
Author URI: http://www.sargant.net
License: GPL2
Copyright 2011  Adam Sargant  (email : adam@sargant.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Register the carousel shortcode
add_shortcode ( 'hide_email', 'aphs_email_invisibliser' );
	
//The call back function that will replace [hide_email]
function aphs_email_invisibliser($attr){
	$email=$attr[0];
    $temp = ''; 
    $length = strlen($email); 
    for($i = 0; $i < $length; $i++) {
        $temp .= '%' . bin2hex($email[$i]); 
	}
	return "<span class='wp_hide_email $temp'>{This email is obscured. Your must have javascript enabled to see it}</span>";
}

//check posts for map insert code before loading javascript
//http://beerpla.net/2010/01/13/wordpress-plugin-development-how-to-include-css-and-javascript-conditionally-and-only-when-needed-by-the-posts/
add_filter('the_posts', 'conditionally_add_scripts_and_styles'); // the_posts gets triggered before wp_head
function conditionally_add_scripts_and_styles($posts){
	if (empty($posts)) return $posts;
 
	$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
	foreach ($posts as $post) {
		if (stripos($post->post_content, '[hide_email')!==FALSE) {
			$shortcode_found = true; // bingo!
			break;
		}
	}
 
	if ($shortcode_found) {
		// enqueue here
		wp_enqueue_script("jquery"); 
		$hide_emailScriptUrl = plugins_url('js/hide_email.js', __FILE__); // Respects SSL
		wp_register_script("hide_email",$hide_emailScriptUrl, array('jquery'), "", TRUE );
		wp_enqueue_script( "hide_email");
	}
	return $posts;
}
?>
