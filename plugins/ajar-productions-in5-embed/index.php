<?php
/*
* Plugin Name: Ajar Productions in5 Embed
* Description: This plugin lets you insert HTML content exported from InDesign using in5 into a Wordpress post or page.
* Author: Ajar Productions
* Author URI: http://ajarproductions.com
* Version: 3.1.3
*/

require_once __DIR__ . '/in5-embed.php';

function in5_embed_activate() {
  $in5_folder = in5_getIn5UploadsFolder();
  if (!file_exists($in5_folder)) {
    mkdir($in5_folder);
  }
}

register_activation_hook(__FILE__, 'in5_embed_activate');
