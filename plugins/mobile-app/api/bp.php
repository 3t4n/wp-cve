<?php

/*
* canvas-api/bp endpoint
* used for bp paths
*/
global $wp;

$url  = trailingslashit( get_site_url() );
$path = urldecode( $wp->query_vars['__canvas_path'] );
$user = get_current_user_id();
if ( ! empty( $user ) && ( '' != $path ) ) {
	$url = bp_loggedin_user_domain() . $path;
} else { // open the login page, then redirect back
	$return_to = get_site_url( null, $_SERVER['REQUEST_URI'] );
	$url       = wp_login_url( $return_to );
}
$url = apply_filters( 'canvas_api_bp_url', $url, $path, $user );
wp_safe_redirect( $url );
die();
