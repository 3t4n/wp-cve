<?php
global $wp;

$url     = trailingslashit( get_site_url() );
$path    = urldecode( $wp->query_vars['__canvas_path'] );
$user_id = get_current_user_id();

if ( ! $user_id ) {
    wp_safe_redirect( $url );
}

$user = get_user_by( 'ID', $user_id );
$user_slug = $user->user_nicename;

if ( 'profile' === $path ) {
    $final_url = $url . 'profile/?' . $user_slug;
} else {
    $final_url = $url . 'profile/?' . $user_slug . '/' . $path;
}


wp_safe_redirect( $final_url );
die;
