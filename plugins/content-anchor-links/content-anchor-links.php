<?php

/*
 * Plugin Name: Content Anchor Links
 * Description: Easily add content anchors to your pages and posts
 * Author: Roland Barker, xnau webdesign
 * Version: 1.6.3
 * Author URI: https://xnau.com
 * License: GPL3
 * Text Domain: content-anchor-links
 * Domain Path: /languages
 * 
 */

spl_autoload_register( 'xnau_content_anchor_links_autoload' );

add_filter( 'content_edit_pre', array( 'xnau_WP_Headings_IDs', 'add_heading_ids'), 5, 2 );
add_filter( 'acf/load_value/type=wysiwyg', array( 'xnau_WP_Headings_IDs', 'add_heading_ids'), 5, 2 );
add_filter( 'wp_link_query', array( 'xnau_WP_Anchor_Links', 'get_content_anchors'), 10, 2 );

function xnau_content_anchor_links_autoload( $class )
{
  $file = $class . '.php';
  if ( !class_exists( $class ) && is_file( trailingslashit( plugin_dir_path( __FILE__ ) ) . $file ) ) {
    include $file;
  }
}