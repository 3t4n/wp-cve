<?php
/*
Plugin Name: User Status Shortcode
Plugin URI: https://www.storyblock.media/
Description: Easily allows you to display different content to your visitors that are logged in than those that are logged out via shortcode.  Tested in templates, pages, posts and text widgets.
Author: Story Block Media
Author URI: https://www.storyblock.media/
Version: 0.1.1
*/

// SECURITY CHECK: Ensure this file isn't being accessed directly
if(preg_match("#^wordpress-user-logged-in-shortcode.php#", basename($_SERVER['PHP_SELF']))) exit();

// Formats and allows shortcodes in text widgets.
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

/**
 * This shortcode will return the content only if the user is logged in.
 * SHORTCODE EXAMPLE: [userloggedin]content[/userloggedin]
 */
function wp_user_logged_in_shortcode( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return do_shortcode($content);
	}
}

// Adds shortcode [userloggedin] so pages and widgets to call function wp_user_logged_in_shortcode( $atts, $content = null );
add_shortcode('userloggedin', 'wp_user_logged_in_shortcode');

/**
 * This shortcode will return the content only if the user is logged out.
 * SHORTCODE EXAMPLE: [userloggedout]content[/userloggedout]
 */
function wp_user_logged_out_shortcode( $atts, $content = null ) {
	if ( !is_user_logged_in() ) {
		return do_shortcode($content);
	}
}

// Adds shortcode [userloggedout] so pages and widgets to call function wp_user_logged_out_shortcode( $atts, $content = null );
add_shortcode('userloggedout', 'wp_user_logged_out_shortcode');
?>
