<?php

function pin_generator_settings_page()
{
  add_menu_page(
    __("Pin Generator", "pin-generator"),
    __("Pin Generator", "pin-generator"),
    "manage_options",
    "pin-generator-settings",
    "pin_generator_settings_page_markup",
    "dashicons-columns",
    7
  );

  add_submenu_page(
    "pin-generator",
    __("Pin Generator Settings", "pin-generator"),
    __("Settings", "pin-generator"),
    "manage_options",
    "pin-generator-settings",
    "pin_generator_settings_page_markup",
    1
  );
}
add_action("admin_menu", "pin_generator_settings_page");

function pin_generator_settings_page_markup()
{
  // Double check user capabilities
  if (!current_user_can("manage_options")) {
    return;
  }

  include PIN_GENERATOR_PLUGIN_DIR . "templates/admin/settings-page.php";
}

// Add a link to your settings page in your plugin
function pin_generator_add_settings_link($links)
{
  $settings_link =
    '<a href="admin.php?page=pin-generator-settings">' .
    __("Settings", "pin-generator-settings") .
    "</a>";
  array_push($links, $settings_link);
  return $links;
}
$filter_name = "plugin_action_links_" . plugin_basename(__FILE__);
add_filter($filter_name, "pin_generator_add_settings_link");

?>
