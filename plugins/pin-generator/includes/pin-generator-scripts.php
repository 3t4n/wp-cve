<?php

// Load JS on all admin pages
function pin_generator_admin_scripts($hook)
{
  // Load color picker
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( "pin-generator-admin", PIN_GENERATOR_PLUGIN_URL . "admin/js/pin-generator-admin.js", array( 'wp-color-picker' ), false, true );

  wp_register_script(
    "pin-generator-admin",
    PIN_GENERATOR_PLUGIN_URL . "admin/js/pin-generator-admin.js",
    ["jquery"],
    time()
  );

  $add_something_nonce = wp_create_nonce( "add_something" );
  $ajaxurl = admin_url( 'admin-ajax.php' );

  // This makes variables available to the front end using javascript
  wp_localize_script("pin-generator-admin", "pingenerator", [
    "hook" => $hook,
    'add_something_nonce'=> $add_something_nonce,
    "ajaxurl" => $ajaxurl,
  ]);

  if ("edit.php" == $hook || "admin.php" == $hook) {
    wp_enqueue_script("pin-generator-admin");
  }
}
add_action("admin_enqueue_scripts", "pin_generator_admin_scripts", 100);

// Load JS on the frontend
function pin_generator_frontend_scripts()
{
  wp_register_script(
    "pin-generator-frontend",
    PIN_GENERATOR_PLUGIN_URL . "frontend/js/pin-generator-frontend.js",
    [],
    time()
  );

  if (is_single()) {
    wp_enqueue_script("pin-generator-frontend");
  }
}
add_action("wp_enqueue_scripts", "pin_generator_frontend_scripts", 100);

// Increate the deafult http curl timeout
function pin_generator_custom_http_request_timeout( ) {
  return 15;
}
add_filter( 'http_request_timeout', 'pin_generator_custom_http_request_timeout' );