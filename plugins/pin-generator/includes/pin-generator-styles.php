<?php

// Load CSS on all admin pages
function pin_generator_admin_styles($hook)
{
  wp_register_style(
    "pin-generator-admin",
    PIN_GENERATOR_PLUGIN_URL . "admin/css/pin-generator-admin-style.css",
    [],
    time()
  );

  //if ("toplevel_page_pin-generator" == $hook) {
    wp_enqueue_style("pin-generator-admin");
  //}
}
add_action("admin_enqueue_scripts", "pin_generator_admin_styles");

// Load CSS on the frontend
function pin_generator_frontend_styles()
{
  wp_register_style(
    "pin-generator-frontend",
    PIN_GENERATOR_PLUGIN_URL . "frontend/css/pin-generator-frontend-styles.css",
    [],
    time()
  );

  if (is_singular() && get_post_type() == "post") {
    wp_enqueue_style("pin-generator-frontend");
  }
}
add_action("wp_enqueue_scripts", "pin_generator_frontend_styles", 100);