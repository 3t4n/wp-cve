<?php

/*
Plugin Name: Instant Gzip compression
Plugin URI: https://riotweb.nl
Description: Enables gzip-compression, this will speed up your WordPress website.
Author: RiotWeb
Version: 2.0
Author URI: https://riotweb.nl/plugins
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined('ABSPATH') )
  die('-1');

// Add gzip rules to .htaccess
function gzipc_load_htaccess( $rules )
{
$my_content = <<<EOD
\n # Gzip Compression
<IfModule mod_filter.c>
    AddOutputFilterByType DEFLATE "application/atom+xml" \
                                  "application/javascript" \
                                  "application/json" \
                                  "application/ld+json" \
                                  "application/manifest+json" \
                                  "application/rdf+xml" \
                                  "application/rss+xml" \
                                  "application/schema+json" \
                                  "application/vnd.geo+json" \
                                  "application/vnd.ms-fontobject" \
                                  "application/x-font-ttf" \
                                  "application/x-javascript" \
                                  "application/x-web-app-manifest+json" \
                                  "application/xhtml+xml" \
                                  "application/xml" \
                                  "font/eot" \
                                  "font/opentype" \
                                  "image/bmp" \
                                  "image/svg+xml" \
                                  "image/vnd.microsoft.icon" \
                                  "image/x-icon" \
                                  "text/cache-manifest" \
                                  "text/css" \
                                  "text/html" \
                                  "text/javascript" \
                                  "text/plain" \
                                  "text/vcard" \
                                  "text/vnd.rim.location.xloc" \
                                  "text/vtt" \
                                  "text/x-component" \
                                  "text/x-cross-domain-policy" \
                                  "text/xml"

</IfModule>
# END Gzip Compression\n
EOD;
    return $my_content . $rules;
}
add_filter('mod_rewrite_rules', 'gzipc_load_htaccess');

// Calling this function will make flush_rules to be called at the end of the PHP execution
function gzipc_enable_flush_rules() {

    global $wp_rewrite;

    // Flush the rewrite rules
    $wp_rewrite->flush_rules();

}

// On plugin activation, call the function that will make flush_rules to be called at the end of the PHP execution
register_activation_hook( __FILE__, 'gzipc_enable_flush_rules' );

include( plugin_dir_path( __FILE__ ) . '/settings.php');