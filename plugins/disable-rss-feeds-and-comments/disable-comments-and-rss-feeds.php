<?php
/*
Plugin Name: Disable RSS Feeds and Comments
Description: Disables all RSS and Atom feeds and comments on pages or posts in WordPress
Version: 1.2
Author: Haseeb Asghar
*/

function disable_feed() {
  if (get_option('disable_rss_feeds')) {
    wp_die( __( 'No feed available, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!' ) );
  }
}

function disable_comments($open, $post_id) {
  $disable_comments_on_pages = get_option('disable_comments_on_pages');
  $disable_comments_on_posts = get_option('disable_comments_on_posts');
  $post = get_post($post_id);
  if (($disable_comments_on_pages && $post->post_type === 'page') || ($disable_comments_on_posts && $post->post_type === 'post')) {
    $open = false;
  }
  return $open;
}

function disable_rss_feeds_and_comments_admin_menu() {
  add_options_page(
    'Disable RSS Feeds and Comments', 
    'Disable RSS Feeds and Comments', 
    'manage_options', 
    'disable-rss-feeds-and-comments', 
    'disable_rss_feeds_and_comments_admin_options'
  );
}

function disable_rss_feeds_and_comments_admin_options() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<h1>Disable RSS Feeds and Comments</h1>';
  echo '<form method="post" action="options.php">';
  settings_fields('disable_rss_feeds_and_comments_options');
  do_settings_sections('disable-rss-feeds-and-comments');
  submit_button();
  echo '</form>';
  echo '</div>';
}

function disable_rss_feeds_and_comments_settings_init() {
  register_setting(
    'disable_rss_feeds_and_comments_options', 
    'disable_rss_feeds'
  );
  register_setting(
    'disable_rss_feeds_and_comments_options', 
    'disable_comments_on_pages'
  );
  register_setting(
    'disable_rss_feeds_and_comments_options', 
    'disable_comments_on_posts'
  );
  add_settings_section(
    'disable_rss_feeds_and_comments_section', 
    'Settings', 
    'disable_rss_feeds_and_comments_section_cb', 
    'disable-rss-feeds-and-comments'
  );
  add_settings_field(
    'disable_rss_feeds', 
    'Disable RSS Feeds', 
    'disable_rss_feeds_cb', 
    'disable-rss-feeds-and-comments', 
    'disable_rss_feeds_and_comments_section'
  );
  add_settings_field(
    'disable_comments_on_pages', 
    'Disable Comments on Pages', 
    'disable_comments_on_pages_cb', 
    'disable-rss-feeds-and-comments', 
    'disable_rss_feeds_and_comments_section'
  );
  add_settings_field(
    'disable_comments_on_posts', 
    'Disable Comments on Posts', 
    'disable_comments_on_posts_cb', 
    'disable-rss-feeds-and-comments', 
    'disable_rss_feeds_and_comments_section'
  );
}

function disable_rss_feeds_and_comments_section_cb() {
  echo '<p>Choose what to disable:</p>';
}

function disable_rss_feeds_cb() {
  $disable_rss_feeds = get_option('disable_rss_feeds');
  echo '<input name="disable_rss_feeds" id="disable_rss_feeds" type="checkbox" value="1" ' . checked( 1, $disable_rss_feeds, false ) . ' />';
}

function disable_comments_on_pages_cb() {
  $disable_comments_on_pages = get_option('disable_comments_on_pages');
  echo '<input name="disable_comments_on_pages" id="disable_comments_on_pages" type="checkbox" value="1" ' . checked( 1, $disable_comments_on_pages, false ) . ' />';
}

function disable_comments_on_posts_cb() {
  $disable_comments_on_posts = get_option('disable_comments_on_posts');
  echo '<input name="disable_comments_on_posts" id="disable_comments_on_posts" type="checkbox" value="1" ' . checked( 1, $disable_comments_on_posts, false ) . ' />';
}

add_action('do_feed', 'disable_feed', 1);
add_action('do_feed_rdf', 'disable_feed', 1);
add_action('do_feed_rss', 'disable_feed', 1);
add_action('do_feed_rss2', 'disable_feed', 1);
add_action('do_feed_atom', 'disable_feed', 1);
add_action('admin_menu', 'disable_rss_feeds_and_comments_admin_menu');
add_filter('comments_open', 'disable_comments', 10, 2);
add_action('admin_init', 'disable_rss_feeds_and_comments_settings_init');
