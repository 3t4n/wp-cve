<?php
/*
Plugin Name: Remove Unrestricted Uploads
Plugin URI: https://wordpress.org/plugins/remove-unrestricted-uploads
Description: Restores the ability to upload non-image files in WordPress. This plugin allows to upload restricted files in the media.
Version: 1.0
Author: Amit Mittal
Author URI:
*/

function remove_unrestricted_uploads_filter($caps, $cap) {
    if ($cap == 'unfiltered_upload') {
        $caps = array();
        $caps[] = $cap;
    }
    return $caps;
}

add_filter('map_meta_cap', 'remove_unrestricted_uploads_filter', 0, 2);