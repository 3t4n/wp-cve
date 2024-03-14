<?php
/*
Plugin Name: Post Update Addon - Gravity Forms 
Description: Update/Edit a post or a custom post type with Gravity Forms.
Version: 1.1.4
Author: Alex Chernov
Author URI: https://alexchernov.com
Text Domain: post-update-addon-gravity-forms
*/
define('ACGF_POST_UPDATE_ADDON_VERSION', '1.1.4');

add_action('gform_loaded', array('ACGF_PostUpdate_AddOn_Bootstrap', 'load'), 5);
 
class ACGF_PostUpdate_AddOn_Bootstrap {
  public static function load() {
    // Check if Gravity Forms installed
    if(!method_exists('GFForms', 'include_addon_framework')) return;
    // Include primary class
    require_once('class-post-update-addon.php');
    GFAddOn::register('ACGF_PostUpdateAddOn');
  }
}
 
function acgf_post_update_addon() {
  return ACGF_PostUpdateAddOn::get_instance();
}
?>
