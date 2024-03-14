<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version @VERSION@
 * @revision @REVISION@
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) @YEAR@ by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

if (!is_admin()) {
    die("Unauthorized access");
}
?>

<form action="admin.php?page=extrawatch&action=save" method="POST">
    <img src="<?php echo(getExtraWatchURL()); ?>/assets/extrawatch-logo-text.png"/>

    <h2 class="title">Settings</h2>

    <?php

    $extraWatchWordpress = new ExtraWatchWordpressSpecific();
    $pluginOptionEmail = $extraWatchWordpress->getPluginOptionEmail();

    ?>

    <div>
        <i>
            This page allows you to specify / change your default email address which you will use
            <br/>
            to login to <a href="https://app.extrawatch.com">app.extrawatch.com</a> and project ID which has been created for your website.
        </i>
        <br/><br/>
    </div>

    <table>
        <tr>
            <td>Email:</td>
            <td>
                <input type="text" name="<?php echo(EXTRAWATCH_SETTING_EMAIL);?>" size="28"
                       value="<?php
                       echo htmlentities($pluginOptionEmail ? $pluginOptionEmail : $extraWatchWordpress->getAdminEmail());?>"/>
            </td>
        </tr>
        <tr>
            <td>
                Project ID:
            </td>
            <td>
                <input type="text" name="<?php echo(EXTRAWATCH_SETTING_PROJECT_ID);?>" size="28"
                       value="<?php echo htmlentities($extraWatchWordpress->getPluginOptionProjectId());?>"/>

            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                <br/>

                <i>Note: To check your project ID, go to <a href="https://app.extrawatch.com" target="_blank">app.extrawatch.com</a>.
                    <br/>Then, login with your credentials or use "forgot password" functionality.</i>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
                <input type="submit" value="Save" class="button button-primary"/>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
            </td>
        </tr>

    </table>

</form>

