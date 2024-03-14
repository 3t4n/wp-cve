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

if (get_option("smr_discord_invite_link") == "") {
  die("Invite link not set");
}
$optionName = "smr_discord_click_count";
$counter = get_option($optionName);
$counter = $counter + 1;

if (get_option($optionName) !== false) {
  // The option already exists, so we just update it.
  update_option($optionName, $counter);
}

update_option("smr_discord_link_last_click", current_time("Y-m-d h:i:sa"));

function discordmsg($invite, $invite_link, $clicks, $color, $webhook, $file)
{
  $msg = json_decode(
    '{   "content": "New Invite Link Click!", "embeds": [ { "title": "New Invite Link Click", "description": "A new click has been detected in your **WP Discord Invite Link** (' .
      $invite .
      '). \n Having Discord Invite Link ' .
      $invite_link .
      '.\n The link currently has ' .
      $clicks .
      ' clicks.",
                    "color": 65280, "footer": { "text": "This was given by the automatic system of WP Discord Invite Plugin" } } ], "username": "WP Dsc Invite",
                    "avatar_url": "https://i.imgur.com/LzO5Aw5.png" } ',
    true
  );

  if ($webhook != "") {
    $response = wp_remote_post($webhook, [
      "body" => "payload_json=" . urlencode(json_encode($msg)),
    ]);

    if (is_wp_error($response)) {
      $errorResponse = $response->get_error_message();
    } else {
      //echo 'Response:<pre>';
      //print_r( $response );
      //echo '</pre>';
    }
  }
}
if (get_option("smr_discord_webhook_enable") == 1) {
  discordmsg(
    get_option("siteurl") . '/' . get_option("smr_discord_uri"),
    "https://discord.gg/" . get_option("smr_discord_invite_link"),
    get_option("smr_discord_click_count"),
    get_option("smr_discord_embed_color"),
    get_option("smr_discord_webhook_url"),
    plugin_dir_url(__FILE__) . "assets/icon-128x128.png"
  );
}
?>

<!DOCTYPE html>
<html>
<head>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="<?php echo get_option(
  "smr_discord_author"
); ?>"/>
<meta property="og:title"  content="<?php echo get_option(
  "smr_discord_title"
); ?>"/>
<meta property="og:description" content="<?php echo get_option(
  "smr_discord_description"
); ?>"/>
<meta property="og:image" content="<?php echo get_option(
  "smr_discord_image_url"
); ?>"/>
<meta name="theme-color" content="<?php echo get_option(
  "smr_discord_embed_color"
); ?>"/>
<meta property="og:url" content="<?php echo get_option(
  "smr_discord_invite_link"
); ?>"/>

</head>
<body>
<meta http-equiv="refresh" content="0; URL=<?php echo "https://discord.com/invite/" .
  get_option("smr_discord_invite_link"); ?>" />
</body>
</html>