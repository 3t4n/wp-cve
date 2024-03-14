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

if (!$this->extraWatchCmsSpecific->isAdmin()) {
    die("Unauthorized access");
}
?>

<div style="max-width: 500px; margin: 20px">
    <form action="<?php echo($this->extraWatchCmsSpecific->getCMSURL().$this->extraWatchCmsSpecific->getComponentPage());?>&action=save" method="POST">

        <br/>
        <img src="<?php echo($this->extraWatchCmsSpecific->getPluginsURL());?>/ew-plg-common/assets/img/logo.png"/>

        <h2 class="title">Initial settings</h2>


        <div>
            <i>
                An account will be created and temporary password will be sent to your email address.
            </i>
        </div>
        <br/>

        <?php

        $pluginOptionEmail = $this->extraWatchCmsSpecific->getPluginOptionEmail();
        $url = $this->extraWatchCmsSpecific->getCMSURL();

        ?>

        <table>
            <tr>
                <td>Email:</td>
                <td>
                    <input type="text" name="<?php echo $this->extraWatchCmsSpecific->escapeOutput(EXTRAWATCH_SETTING_EMAIL);?>"  size="28"
                           value="<?php
                           echo $this->extraWatchCmsSpecific->escapeOutput($pluginOptionEmail ? $pluginOptionEmail : $this->extraWatchCmsSpecific->getAdminEmail());?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    URL:
                </td>
                <td>
                    <input type="text" name="<?php echo $this->extraWatchCmsSpecific->escapeOutput(EXTRAWATCH_SETTING_URL);?>"  size="28"
                           value="<?php echo $this->extraWatchCmsSpecific->escapeOutput($url);?>"/>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <label>
                    <input type="checkbox" name="<?php echo $this->extraWatchCmsSpecific->escapeOutput(EXTRAWATCH_SETTING_TERMS);?>"/>
                    I agree with <a href="https://www.extrawatch.com/terms-and-conditions" target="_blank">terms and contitions</a>.
                    </label>
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="<?php echo(EXTRAWATCH_NONCE);?>" value="<?php echo $this->extraWatchCmsSpecific->createNonce(EXTRAWATCH_NONCE);?>" />
                    <input type="submit" value="Proceed" class="button button-primary"/>
                </td>
                <td>
                    &nbsp; <a href="<?php echo($this->extraWatchCmsSpecific->getCMSURL().$this->extraWatchCmsSpecific->getComponentPage());?>&page=extrawatch-settings">I'm already registered.</a>
                </td>
            </tr>

        </table>

    </form>

    <br/><br/>
    <div>
        <i>
            <b>Why do we need your email and how we will use it?</b><br/>
            Email is used to create your account and send you login information.
            You will use that email address to access your account either via plugin or via <a href="https://app.extrawatch.com/" target="_blank">https://app.extrawatch.com/</a>.<br/>
            Email is stored in hashed way and we will never send you any spam.
        </i>
    </div>

    <br/>

</div>
