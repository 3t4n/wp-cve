<?php

/*
Plugin Name: ACF Repeater Editor Accordion
Plugin URI: jeremywaraksa.com
Description: For use with ACF repeater rows, only affects page editor view for easier editing. When using the repeater fields for ACF, this plugin adds an accordion feature to view each row. 
Version: 1.0
Author: Jeremy Waraksa
Author URI: http://jeremywaraksa.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Add accordion feature to ACF repeater editor views
function acf_accordion() {
  global $current_screen;

  if($current_screen->id == 'page')
  {
    echo '<link rel="stylesheet" href="'.plugins_url('css/custom.css', __FILE__).'" type="text/css" />';
    echo '<script src="'.plugins_url('js/custom.js', __FILE__).'"></script>';
  }
}
add_action('admin_head','acf_accordion');