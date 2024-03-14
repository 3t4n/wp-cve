<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h3>Login</h3>
<div>
    To use the plugin, click on the provided link and log in to the ipushpull application.
    <br/>
    This will give you access to all its functionalities and keep your session active for using the plugin smoothly.
    <br/>
    <ol>
        <li>
        Click the following link <a href="<?php echo IPUSHPULL_URL . "/pages" ?>" target="_blank"><?php echo IPUSHPULL_URL . "/pages" ?></a> to open a new tab and log in into ipushpull application.
        </li>
        <li>
        After logging in, close the tab and come back to the WordPress admin site
        </li>
        <li>
        Refresh the page, if necessary.
    </ol>
</div>