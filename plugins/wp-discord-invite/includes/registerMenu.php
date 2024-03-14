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
// create custom plugin settings menu
add_action("admin_menu", "smr_discord_create_menu");

function smr_discord_create_menu()
{
  //create new top-level menu

  add_menu_page(
    "WP Discord Invite",
    "WP Discord Invite",
    "administrator",
    "wp-discord-invite",
    "smr_discord_settings_page",
    "dashicons-admin-links"
  ); //plugin_dir_url( __FILE__ ) . 'assets/discord.svg'

  add_submenu_page(
    "wp-discord-invite",
    "Click Count",
    "Click Count",
    "administrator",
    "wp-discord-invite-count",
    "smr_discord_count_page"
  );

  // add_submenu_page(
  //   "wp-discord-invite",
  //   "Help",
  //   "Help",
  //   "administrator",
  //   "wp-discord-invite-help",
  //   "smr_discord_help_page"
  // );
  //call register settings function

  add_action("admin_init", "smr_discord_settings");
}

?>