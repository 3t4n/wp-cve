<?php

/**
 * Add the admin stylesheet.
 *
 * @return void
 */
function ebay_feeds_for_wordpress_add_admin_stylesheet() {
  wp_register_style( 'ebay-feeds-for-wordpress-style', EBAYFEEDSFORWORDPRESS_PLUGIN_URL . '/ebay-feeds-for-wordpress-admin.css', array(), EBFW_PLUGIN_VERSION );
  wp_register_script( 'ebay-feeds-for-wordpress-script', EBAYFEEDSFORWORDPRESS_PLUGIN_URL . '/ebffwp_option.js', array( 'jquery' ), EBFW_PLUGIN_VERSION, true );
  wp_enqueue_style( 'ebay-feeds-for-wordpress-style' );
  wp_enqueue_script( 'ebay-feeds-for-wordpress-script' );
}