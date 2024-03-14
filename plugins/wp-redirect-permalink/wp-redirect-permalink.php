<?php
/*
Plugin Name: WP Redirect Permallink
Plugin URI: https://nabtron.com/wp-redirect-permalink/
Description: Redirects old permalink format: <strong><code>'/postname/post_id/'</code></strong> links to new: <code><strong>'/postname/'</code></strong> only using <strong>301 redirect</strong>. Respects query variables.
Author: nabtron
Version: 1.0.9
Author URI: https://nabtron.com/
*/

function wp_redirect_permalink()
{
	//acquire the complete url of the current page the user is on
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	//the preg_match will check for any pages having a number after the main link except for pages/ one as it's navigation
	if (preg_match("/.+?\/page\/(*SKIP)(*F)|(.+?\/)\d+\/(.*)/i", $actual_link, $matches)) {
		$new_redirect_link = $matches[1].$matches[2];
		wp_redirect( $new_redirect_link, 301 ); exit;
	}
}
add_action( 'template_redirect', 'wp_redirect_permalink' );