<?php
/*
Plugin Name: FolioPress WYSIWYG
Plugin URI: http://foliovision.com/seo-tools/wordpress/plugins/wysiwyg
Description: WYSIWYG FCKEditor with custom Image Management and nice skin.
Version: 2.6.17
Author: Foliovision s r.o.
Author URI: http://www.foliovision.com
*/

define( 'FV_FCK_NAME', 'Foliopress WYSIWYG' );
define( 'FV_FCK_OPTIONS', 'fp_wysiwyg' );

require_once( 'foliopress-wysiwyg-class.php' );

if( $GLOBALS['wp_version'] > 2.9 ) {
  add_action( 'init', array( &$fp_wysiwyg, 'check_featured_image_capability' ), 999 );
}

if( $GLOBALS['wp_version'] < 2.5 ) {
  add_action( 'admin_head', array( &$fp_wysiwyg, 'admin_init' ) );
}
else {
  add_action( 'admin_init', array( &$fp_wysiwyg, 'admin_init' ) );
}
add_action( 'init', array( &$fp_wysiwyg, 'ap_action_init'));
add_action( 'admin_head', array( &$fp_wysiwyg, 'FckLoadAdminHead' ) );
add_action( 'admin_menu', array( &$fp_wysiwyg, 'AddOptionPage' ) );
add_action( 'admin_notices', array( &$fp_wysiwyg, 'AdminNotices') );

add_action( 'edit_form_advanced', array( &$fp_wysiwyg, 'LoadFCKEditor' ) );
add_action( 'edit_page_form', array( &$fp_wysiwyg, 'LoadFCKEditor' ) );
add_action( 'simple_edit_form', array( &$fp_wysiwyg, 'LoadFCKEditor' ) );

add_filter( 'the_editor', array( &$fp_wysiwyg, 'the_editor' ) );

//  remove tinyMCE editor JS in WP 3.3
if( $GLOBALS['wp_version'] >= 3.3 ) {
  add_action( 'admin_print_footer_scripts', array( &$fp_wysiwyg, 'admin_print_footer_scripts' ) );
}

if( $GLOBALS['wp_version'] >= 3.0 ) {
  add_action( 'admin_head', array( &$fp_wysiwyg, 'KillTinyMCE' ) );
}
else {
  add_action( 'option_posts_per_page', array( &$fp_wysiwyg, 'KillTinyMCE' ) );
}

if( $GLOBALS['wp_version'] >= 3.0 ) {
  add_filter('user_can_richedit', array(&$fp_wysiwyg, 'user_can_richedit') );
}
//add_action( 'personal_options_update', array( &$fp_wysiwyg, 'PersonalOptionsUpdate' ) );
//register_activation_hook( __FILE__, array( &$fp_wysiwyg, 'PluginActivate' ) );

add_filter( 'media_buttons_context', array(&$fp_wysiwyg, 'fv_remove_mediabuttons') );
add_action('admin_print_scripts', array(&$fp_wysiwyg, 'add_admin_js'));
add_action('content_edit_pre', array(&$fp_wysiwyg, 'content_edit_pre'));

add_filter('content_save_pre', array(&$fp_wysiwyg, 'remove_blank_p'));

if( $GLOBALS['wp_version'] >= 2.7 ) {
  add_action('admin_menu', array(&$fp_wysiwyg, 'meta_box_add') );
  add_action('admin_menu', array(&$fp_wysiwyg, 'remove_meta_boxes'), 99, 3 );
}
add_filter('wp_insert_post', array(&$fp_wysiwyg, 'wp_insert_post'));
add_filter('the_content', array(&$fp_wysiwyg, 'the_content'), 0);

add_action( 'wp_ajax_fv_foliopress_ajax_pointers',  array($fp_wysiwyg, 'ajax_pointers') );

add_action( 'image_send_to_editor',  array($fp_wysiwyg, 'image_disable_captions'), 0 );
add_action( 'image_send_to_editor',  array($fp_wysiwyg, 'h5_markup'), 999, 8 );
add_filter( 'media_view_settings', array($fp_wysiwyg, 'image_link_to_file') );
