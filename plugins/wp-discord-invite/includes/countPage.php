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

//COUNT PAGE START
function smr_discord_count_page()
{
  ?>
<div class="wrap">
<img src="<?php echo plugin_dir_url(__FILE__) .
  "./../assets/icon-128x128.png"; ?>"></img>
<h2>WP Discord Invite Link Click Count</h2>
<script type="text/javascript">var $j = jQuery.noConflict();</script>
<table class="form-table">


        <tr valign="top">
        <th scope="row">Link: </th>
        <td><p><?php echo get_option("siteurl") . "/" . get_option("smr_discord_uri"); ?></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Click Count</th>
        <td><p><?php echo get_option("smr_discord_click_count"); ?></p></td>
        </tr>
        
	<tr valign="top">
        <th scope="row">Last Click</th>
        <td><p><?php echo time_elapsed_string(
          get_option("smr_discord_link_last_click")
        ) .
          " (" .
          get_option("smr_discord_link_last_click") .
          ")"; ?></p></td>
        </tr>
        <tr valign="top">
        <th scope="row">Last Reset</th>
        <td><p><?php echo time_elapsed_string(
          get_option("smr_discord_click_count_last_reset")
        ) .
          " (" .
          get_option("smr_discord_click_count_last_reset") .
          ")"; ?></p></td>
        </tr>
    </table>
<div>
<form method="post" action="options.php">
    <?php settings_fields("smr-discord-count-group"); ?>
<input type="hidden" name="smr_discord_click_count" value="0" />
<input type="hidden" name="smr_discord_click_count_last_reset" value="<?php echo current_time(
  "Y-m-d h:i:sa"
); ?>" />

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e(
      "Reset Click Count (Irreversible)"
    ); ?>" />
    </p>
</form>
</div>

<form method="post" action="options.php">
    <?php settings_fields("smr-discord-webhook-group"); 
    if(isset($_POST['wp-discord-invite-oauth']) && isset($_POST['webhook'])) {
        $webhook = sanitize_url($_POST['webhook'], array('https'));
    }
    ?>
<h2>WP Discord Invite Link Click Webhook</h2>
<p>Sends a webhook to Discord when the invite link is clicked</p>
<table class="form-table">


        <tr valign="top">
        <th scope="row">Enable Webhook </th>
        <td><input type="checkbox" name="smr_discord_webhook_enable" value="1"<?php if(isset($_POST['wp-discord-invite-oauth']) && isset($webhook)) {
          echo "checked=\"checked\"";
        } else  { checked(
          1 == get_option("smr_discord_webhook_enable")
        ); } ?> /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_webhook-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_webhook-desc">Check to enable webhook option. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite#webhook">More Info in Docs</a></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Discord Webhook URL </th>
        <td><input type="text" name="smr_discord_webhook_url" value="<?php if(isset($_POST['wp-discord-invite-oauth']) && isset($webhook)) {
           echo $webhook;
        } else { echo get_option(
          "smr_discord_webhook_url"
        );} ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_webhook-url-desc').toggleClass('hidden');"></span><br />
<p class="description hidden" id="wp_discord_webhook-url-desc">Webhook URL to post to a Discord Channel <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite#webhook">More Info in Docs</a></p>
<a class="dsc-btn" rel="nofollow" href="https://utils.sarveshmrao.in/wp-discord-invite-oauth/?redirect=<?php echo admin_url(); ?>" title="Login with Discord">
           <span class="dsc-btn-icon"></span>

           <span class="dsc-btn-text">Login with Discord</span>
        </a>
</td>
        </tr>

</table>
<?php
wp_enqueue_style(
  "CssForDscOAuth",
  plugin_dir_url(__FILE__) . "./../assets/dsc-oauth.css"
);
?>
<p class="submit">
    <input type="submit" class="button-primary" id="count_save_changes" value="<?php _e(
      "Save Changes"
    ); ?>" />
    </p>
  <?php
  if(isset($_POST['wp-discord-invite-oauth']) && isset($webhook)) {
    echo("<body onload=\"redirFunction()\"></body>");
    echo("<script> function redirFunction() {
document.getElementById(\"count_save_changes\").click();
} </script>");
  }
?>

</form>



<div><p>If you enjoy using this plugin please leave a review <a href="https://wordpress.org/support/plugin/wp-discord-invite/reviews/">here</a>. That would motivate me a lot.</p></div>
<div><p>Created with <span class="dashicons dashicons-heart"></span> by <a href="https://sarveshmrao.in">Sarvesh M Rao</a>.</p></div>
</div>
<?php
}
//COUNT PAGE END

?>