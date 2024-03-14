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

//Start Cactching URLS
add_action("parse_request", "smr_discord_url_handler");
function smr_discord_url_handler($wp)
{
  $regex = "/" . get_option("smr_discord_uri") . "+/";
  if (preg_match($regex, $wp->request, $matches)) {
    include_once plugin_dir_path(__FILE__) . "discord.php";
    exit();
  }
}

?>