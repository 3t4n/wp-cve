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
function smr_discord_settings_page()
{
  ?>
<div class="wrap">
<img src="<?php echo plugin_dir_url(__FILE__) .
  "./../assets/icon-128x128.png"; ?>"></img>
<h2>WP Discord Invite</h2>

<form method="post" action="options.php">
    <?php settings_fields("smr-discord-settings-group"); ?>
    <script type="text/javascript">var $j = jQuery.noConflict();</script>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Invite Link </th>
        <td><p>https://discord.gg/<input type="text" name="smr_discord_invite_link" value="<?php echo get_option(
          "smr_discord_invite_link",
          "abCxYz"
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_invite-link-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_invite-link-desc">A permenant invite link to your server. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Redirect URL</th>
        <td><p><?php echo get_option("siteurl")?>/<input type="text" name="smr_discord_uri" value="<?php echo get_option(
          "smr_discord_uri",
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_redirect-url-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_redirect-url-desc">The suffix after your site URL like: <b>/discord</b> or <b>/community</b> or <b>/support</b> <br /> Don't include the forward slash ('/') in the setting. <a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>


        <tr valign="top">
        <th scope="row">Title </th>
        <td><input type="text" name="smr_discord_title" value="<?php echo get_option(
          "smr_discord_title",
          "My Awesome Discord Server"
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_title-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_title-desc">Title will be displayed above description and below author. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Description </th>
        <td><input type="text" name="smr_discord_description" value="<?php echo get_option(
          "smr_discord_description",
          "My server is awesome coz of these"
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_description-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_description-desc">Description will be displayed below title. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Author </th>
        <td><input type="text" name="smr_discord_author" value="<?php echo get_option(
          "smr_discord_author",
          "You have been invited to a server!"
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_author-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_author-desc">Author will be displayed above the title. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Image URL </th>
        <td><input type="text" id="smr_discord_image_url" name="smr_discord_image_url" value="<?php echo get_option(
          "smr_discord_image_url",
          "https://i.imgur.com/LzO5Aw5.png"
        ); ?>" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_img-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_img-desc">It's the URL of the image to be displayed at the right end of the embed. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Embed color </th>
        <td><input type="text" name="smr_discord_embed_color" value="<?php echo get_option(
          "smr_discord_embed_color",
          "#f4f4f4"
        ); ?>" class="smr-discord-embed-color-picker" /><span class="dashicons dashicons-editor-help" onclick="$j('#wp_discord_color-desc').toggleClass('hidden');"></span>
<p class="description hidden" id="wp_discord_color-desc">Hex code of the color in the left side of the embed. <br /><a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=settings-page">More Info in Docs</a></p></td>
        </tr>

	<p>You can visit <a href="<?php echo get_option(
   "siteurl"
 ).'/'.get_option("smr_discord_uri")?>"><?php echo get_option(
  "siteurl"
).'/'.get_option("smr_discord_uri")?></a>. Don't use '/' at the end if you want to display the author.</p>
	<p>Please note that Discord Caches the URL for approx. 2 hours so changes won't get reflected immediately.</p>
	</table>


    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e(
      "Save Changes"
    ); ?>" />
    </p>

<?php wp_enqueue_style(
  "CssForEmbed",
  plugin_dir_url(__FILE__) . "./../assets/styles.css"
); ?>

<?php //EMBED PREVIEW START
  ?>
	<div><h2>Embed Preview</h2><p>(Click save changes for changes to get previewed)</p></div>
	<div class="embed-wrapper mb-2" style="max-width:200px;margin-top:50px">
	<div class="embed-color-pill" id="embedPreviewPlace" style="background-color:<?php echo get_option(
   "smr_discord_embed_color",
   "#f4f4f4"
 ); ?>"></div>
	<div class="embed embed-rich bg-none" style="background-color:#2C2F33;border-color:#16171a">
	<div class="embed-content" style="padding:5px;">
	<div class="embed-content-inner">
	<div class="_author">
	<a class="embed-author-name"><span style="color:white;font-size:0.8em"><span id="embedSayingPlace"><?php echo get_option(
   "smr_discord_author",
   "You have been invited to a server!"
 ); ?></span></span></a>
	</div>
	<div class="_title"><a class="embed-title"><span id="embedTitlePlace"></span><?php echo get_option(
   "smr_discord_title",
   "My Awesome Discord Server"
 ); ?></a></div>
	<div class="embed-description" style="color:#797a7a;width:300px;"><p><span id="embedInvitedByPlace" style="overflow-wrap: break-word;"><?php echo get_option(
   "smr_discord_description",
   "My server is awesome coz of these"
 ); ?></span></p>
	</div>
	</div>
	<img id="embedImage" src="<?php echo get_option(
   "smr_discord_image_url",
   "https://i.imgur.com/LzO5Aw5.png"
 ); ?>" role="presentation" class="embed-rich-thumb" style="max-width: 80px; max-height: 80px;">
	</div>
	</div>
	</div>
<?php //EMBED PREVIEW END
  ?>

</form>

<div><p>If you enjoy using this plugin please leave a review <a href="https://wordpress.org/support/plugin/wp-discord-invite/reviews/">here</a>. That would motivate me a lot.</p></div>
<div><p>Created with <span class="dashicons dashicons-heart"></span> by <a href="https://sarveshmrao.in">Sarvesh M Rao</a>.</p></div>

</div>
<?php
}
//MAIN PAGE END

?>