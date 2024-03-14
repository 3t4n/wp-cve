<?php

/**
 * Check if we're on rich text editor of 4.9< and if so add the TinyMCE buttons
 * @return void
 */
function ebay_feeds_for_wordpress_addbuttons() {
  // Don't bother doing this stuff if the current user lacks permissions
  if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
    return;

  // Add only in Rich Editor mode
  if ( get_user_option( 'rich_editing' ) == 'true' ) {
    add_filter( "mce_external_plugins", "add_ebay_feeds_for_wordpress_tinymce_plugin" );
    add_filter( 'mce_buttons', 'ebay_feeds_for_wordpress_button' );
  }
}


/**
 * Add the button for WP eBay Product Feeds
 * @param  array 		$buttons 			Array of current buttons
 * @return array 							Array of buttons with WP eBay Product Feeds added
 */
function ebay_feeds_for_wordpress_button( $buttons ) {
  array_push( $buttons, "separator", "ebffwplugin" );
  return $buttons;
}

// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_ebay_feeds_for_wordpress_tinymce_plugin( $plugin_array ) {
  $url = EBAYFEEDSFORWORDPRESS_PLUGIN_URL . "/editor_plugin_new.js";
  $plugin_array['ebffwplugin'] = $url;
  return $plugin_array;
}
