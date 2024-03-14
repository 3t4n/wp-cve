<?php
/*
Plugin Name: WP Testimonials
Plugin Title: WP Testimonials Plugin
Plugin URI: https://wordpress.org/plugins/testimonial-widgets/
Description: Display your Testimonials on your website fast and easily. 21 widget types, 25 widget styles available. (Free Plugin)
Tags: reviews, ratings, recommendations, testimonials, widget, slider, review, rating, recommendation, testimonial, customer review
Author: Trustindex.io <support@trustindex.io>
Author URI: https://www.trustindex.io/
Contributors: trustindex
License: GPLv2 or later
Version: 1.4.4
Text Domain: testimonial-widgets
Domain Path: /languages/
Donate link: https://www.trustindex.io/prices/
*/
/*
Copyright 2021 Trustindex Kft (email: support@trustindex.io)
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once plugin_dir_path( __FILE__ ) . 'testimonials-plugin.class.php';
$trustindex_testimonials_pm = new TrustindexTestimonialsPlugin("Testimonials", __FILE__, "1.4.4", "WP Testimonials");
add_action('admin_menu', array($trustindex_testimonials_pm, 'add_setting_menu'), 10);
add_filter('parent_file', array($trustindex_testimonials_pm, 'menu_highlight'));
add_filter('plugin_action_links', array($trustindex_testimonials_pm, 'add_plugin_action_links'), 10, 2);
add_filter('plugin_row_meta', array($trustindex_testimonials_pm, 'add_plugin_meta_links'), 10, 2);
/* Add menu to custom post type & category pages
*/
add_action( 'load-edit.php', array($trustindex_testimonials_pm, 'generate_cpt_page_menu'), 10 );
add_action( 'load-post.php', array($trustindex_testimonials_pm, 'generate_cpt_page_menu'), 10 );
add_action( 'load-post-new.php', array($trustindex_testimonials_pm, 'generate_cpt_page_menu'), 10 );
add_action( 'load-edit-tags.php', array($trustindex_testimonials_pm, 'generate_cpt_page_menu'), 10 );
function custom_list_table()
{
echo esc_html('<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity"><h1 class="ti-free-title">' . 'WP Testimonials' . '</h1></div>');
}
add_action('admin_enqueue_scripts', array($trustindex_testimonials_pm, 'trustindex_testimonials_add_scripts'));
add_action( 'after_setup_theme', array($trustindex_testimonials_pm, 'set_testimonial_image'));
add_filter( 'manage_edit-wpt-testimonial_columns', array($trustindex_testimonials_pm, 'edit_columns' ) );
add_action( 'manage_wpt-testimonial_posts_custom_column', array($trustindex_testimonials_pm, 'custom_columns' ) );
add_action('init', array($trustindex_testimonials_pm, 'init_shortcode'));
add_action('plugins_loaded', array($trustindex_testimonials_pm, 'plugin_loaded'));
register_activation_hook( __FILE__, array( $trustindex_testimonials_pm, 'plugin_activation' ) );
register_activation_hook( __FILE__, array( $trustindex_testimonials_pm, 'create_widgets_table' ) );
register_deactivation_hook( __FILE__, array( $trustindex_testimonials_pm, 'plugin_deactivation' ) );
?>