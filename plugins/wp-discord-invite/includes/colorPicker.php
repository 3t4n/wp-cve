<?php

/**
 * This file is included with WP Discord Invite WordPress Plugin (https://wordpress.com/plugins/wp-discord-invite), Developed by Sarvesh M Rao (https://sarveshmrao.in/).
 * This file is licensed under Generl Public License v2 (GPLv2)  or later.
 * Using the code on whole or in part against the license can lead to legal prosecution.
 * 
 * Sarvesh M Rao
 * https://sarveshmrao.in/
 */

if (!defined("ABSPATH")) {
  exit();
}

add_action("admin_enqueue_scripts", "smr_discord_enqueue_color_picker");
function smr_discord_enqueue_color_picker($hook_suffix)
{
  // first check that $hook_suffix is appropriate for your admin page
  wp_enqueue_style("wp-color-picker");
  wp_enqueue_script(
    "my-script-handle",
    plugins_url("color-picker.js", __FILE__),
    ["wp-color-picker"],
    false,
    true
  );
}
?>