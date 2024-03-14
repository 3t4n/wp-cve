<?php

/*
Plugin Name: ESV CrossReference Tool
Plugin URI: https://wordpress.org/plugins/esv-crossref/
Description: The ESV CrossReference Tool transforms Scripture references in your content into hoverable links that show the ESV text and link back to ESV.org.
Author: Crossway
Version: 2.1
Author URI: https://www.esv.org/resources/esv-crossreference-tool/
*/

function esv_crossreference_tool_enqueue_script () {
    $handle = 'esv-crossreference-tool';
    $src = 'https://static.esvmedia.org/crossref/crossref.min.js';

    wp_enqueue_script($handle, $src, [], false, true);
}

add_action('wp_enqueue_scripts', 'esv_crossreference_tool_enqueue_script');
