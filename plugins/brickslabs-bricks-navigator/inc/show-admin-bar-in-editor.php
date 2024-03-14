<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Show WP admin bar in Bricks editor.
add_action( 'init', function () {
  // if this is not the outer frame, abort
  if ( ! bricks_is_builder_main() || ! brickslabs_bricks_navigator_user_can_use_bricks_builder() ) {
    return;
  }

  add_filter( 'show_admin_bar', '__return_true' );
} );

// Add CSS to fix the admin bar.
add_action( 'wp_head', function() {
  if ( bricks_is_builder_main() &&  brickslabs_bricks_navigator_user_can_use_bricks_builder() ) {
    echo '<style>body.admin-bar #bricks-toolbar {
      top: var(--wp-admin--admin-bar--height);
    }
    
    #bricks-structure {
      top: calc(40px + var(--wp-admin--admin-bar--height));
    }</style>';
  }
} );