<?php

/*
Plugin Name: Allow ePUB and MOBI formats upload
Version: 1.0
Plugin URI: https://eknizky.sk
Author: eKnizky.sk
Author URI: https://eknizky.sk
Description: Upload ePUB and MOBI formats on your site.
License: GPL2
*/

function eknizky_custom_myme_types($mime_types){

    //Adding avi extension
    $mime_types['epub'] = 'application/octet-stream'; 

    return $mime_types;
}

add_filter('upload_mimes', 'eknizky_custom_myme_types', 1, 1);

function eknizky_upload_mimes($mimes) {
    $mimes = array_merge($mimes, array(
        'epub|mobi' => 'application/octet-stream'
    ));
    return $mimes;
}
add_filter('upload_mimes', 'eknizky_upload_mimes');

add_filter('upload_mimes','eknizky_custom_mime_types');
function eknizky_custom_mime_types($mimes){

    $new_file_types = array (
        'zip' => 'application/zip',
        'mobi' => 'application/x-mobipocket-ebook',
        'epub' => 'application/epub+zip'
    );

    return array_merge($mimes,$new_file_types);
}
