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

<div style="max-width: 500px;">
    <form action="admin.php?page=extrawatch&action=save" method="POST">

        <br/>
        <img src="<?php echo(getExtraWatchURL()); ?>/assets/extrawatch-logo-text.png"/>

        <h2 class="title">Initial settings</h2>


        <div>
            <i>
                An account will be created and temporary password will be sent to your email address. We will never send you any spam.
            </i>
        </div>
        <br/>

        <?php

        $extraWatchWordpress = new ExtraWatchWordpressSpecific();
        $pluginOptionEmail = $extraWatchWordpress->getPluginOptionEmail();
        $url = $extraWatchWordpress->getCMSURL();

        ?>




        <table>
            <tr>
                <td>Email:</td>
                <td>
                    <input type="text" name="<?php echo(EXTRAWATCH_SETTING_EMAIL);?>"  size="28"
                           value="<?php
                           echo htmlentities($pluginOptionEmail ? $pluginOptionEmail : $extraWatchWordpress->getAdminEmail());?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    URL:
                </td>
                <td>
                    <input type="text" name="<?php echo(EXTRAWATCH_SETTING_URL);?>"  size="28"
                           value="<?php echo htmlentities($url);?>"/>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <br/>
                    <label>
                    <input type="checkbox" name="<?php echo(EXTRAWATCH_SETTING_TERMS);?>"/>
                    I agree with <a href="https://www.extrawatch.com/terms" target="_blank">terms and contitions</a>.
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Proceed" class="button button-primary"/>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

        </table>

    </form>

    <br/>
    <a href="?page=extrawatch-settings">I'm already registered.</a>

</div>
