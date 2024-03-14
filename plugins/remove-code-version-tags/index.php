<?php
/*
Plugin Name: No Version Tags
Version: 1.0
Plugin URI: http://plugins.wordpress.org/no-version-tags
Description: Upon activation, this plugin will automatically eliminate the annoying ?ver=x.x signs from your perfect code :)
Author: George Gkouvousis
Author URI: http://www.8web.gr/
*/

add_filter( 'style_loader_src', 'no_version_tags' );
add_filter( 'script_loader_src', 'no_version_tags' );

function no_version_tags( $url )
{
    return remove_query_arg( 'ver', $url );
}

?>