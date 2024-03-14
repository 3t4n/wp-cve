<?php
/**
 * Last-Modified and If-Modified-Since Headers
 *
 * Plugin Name: Last-Modified and If-Modified-Since Headers
 * Description: Add Last-Modified and support If-Modified-Since Headers
 * Version: 1.0
 * Requires at least: 5.2.4
 * Requires PHP:      5.3+
 * Author: zubovd
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action('wp', 'last_if_modified_headers' );

function last_if_modified_headers() {
    global $post;
        if(isset($post) && is_single()){
            $LastModified_unix = strtotime($post->post_modified);
            $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
            $IfModifiedSince = false;

            if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
                $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
            }
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
            }

            if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
                exit;
            }

            header('Last-Modified: '. $LastModified);
        }
}
