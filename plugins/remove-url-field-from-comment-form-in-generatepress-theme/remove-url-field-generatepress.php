<?php
/* 
Plugin Name: Remove URL Field from Comment Form in GeneratePress Theme
Plugin URI: https://wordpress.org/plugins/remove-url-field-from-comment-form-in-generatepress-theme/
Description: This plugin helps to remove URL Field from comment form in GeneratePress Theme.
Version: 1.0.0
Author: Suraj Katwal
Author URI: https://www.wplogout.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

add_action( 'after_setup_theme', 'wplogout_add_comment_url_filter' );
function wplogout_add_comment_url_filter() {
    add_filter( 'comment_form_default_fields', 'wplogout_disable_comment_url', 20 );
}

function wplogout_disable_comment_url($fields) {
    unset($fields['url']);
    return $fields;
}