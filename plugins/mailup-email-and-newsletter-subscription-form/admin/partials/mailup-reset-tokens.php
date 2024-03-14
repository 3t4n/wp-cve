<?php declare(strict_types=1);

/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="box_reset">
<h2><?php _e('&#9940; Disconnect Plugin &#9940;', 'mailup'); ?></h2>
<p><?php _e('Disconnecting your plugin all your forms included in your website will stop working.<br>If you no longer want to use the plugin, remove all your MailUp forms before.', 'mailup'); ?></p>
    <form id="mup-reset" method="POST" action="">
        <input type="hidden" name="reset" value="1" />
        <div class="separator-with-border"></div>
        <input type="submit" value="<?php _e('Disconnect', 'mailup'); ?>" class="button red right" />
    </form>
</div>