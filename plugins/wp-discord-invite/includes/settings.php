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

function smr_discord_settings()
{
  //register our settings
  register_setting("smr-discord-settings-group", "smr_discord_invite_link", "sanitize_smr_discord_text");
  register_setting("smr-discord-settings-group", "smr_discord_title", "sanitize_smr_discord_text");
  register_setting("smr-discord-settings-group", "smr_discord_description", "sanitize_smr_discord_text");
  register_setting("smr-discord-settings-group", "smr_discord_image_url", "sanitize_smr_discord_url");
  register_setting("smr-discord-settings-group", "smr_discord_embed_color", "sanitize_smr_discord_color");
  register_setting("smr-discord-settings-group", "smr_discord_author", "sanitize_smr_discord_text");
  register_setting("smr-discord-settings-group", "smr_discord_uri", "sanitize_smr_discord_text");

  register_setting("smr-discord-count-group", "smr_discord_click_count", "sanitize_smr_discord_click_count");
  register_setting(
    "smr-discord-count-group",
    "smr_discord_click_count_last_reset",
    "sanitize_smr_discord_click_count_last_reset"
  );
  register_setting("smr-discord-count-group", "smr_discord_link_last_click", "sanitize_smr_discord_link_last_click");
  register_setting("smr-discord-webhook-group", "smr_discord_webhook_enable", "sanitize_smr_discord_webhook_enable");
  register_setting("smr-discord-webhook-group", "smr_discord_webhook_url", "sanitize_smr_discord_url");

  add_option("smr_discord_click_count", "0");
  add_option("smr_discord_click_count_last_reset", "Never");
  add_option("smr_discord_link_last_click", "Never");
  add_option("smr_discord_uri", "discord");
}

function sanitize_smr_discord_text($option){
    $option = sanitize_text_field($option);
    return $option;
}

function sanitize_smr_discord_url($option){
    $option = sanitize_url($option);
    return $option;
}

function sanitize_smr_discord_color($option){
    $option = sanitize_hex_color($option);
    return $option;
}


?>