<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

class ExtraWatchRenderer
{

    private $extraWatchCMSSpecific;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific)
    {
        $this->extraWatchCMSSpecific = $extraWatchCMSSpecific;
    }

    function renderIFrame($from) {
        $path = "/#/pages/login/?from=".$from;
        $output = $this->renderFullIFrameWithURL($path);
        return $output;
    }

    function renderTrackingCode($projectId)
    {

        ?>


     <script type="text/javascript">
            var _extraWatchParams = _extraWatchParams || [];
            _extraWatchParams.projectId = '<?php echo $this->extraWatchCMSSpecific->escapeOutput($projectId);?>';
            (function() {
               var ewHead = document.getElementsByTagName('head')[0];
               var ewInsertScript = document.createElement('script');
               ewInsertScript.type = 'text/javascript';
               ewInsertScript.async = true;
               ewInsertScript.src = '<?php echo ExtraWatchConfig::AGENT_URL;?>/agent/js/ew.js'
               ewHead.appendChild(ewInsertScript);
            })();
     </script>


        <?php

        return;

    }

    public function renderForgotPasswordForEmail($email) {
        echo $this->renderFullIFrameWithURL("/#/pages/reset/init?email=". $this->extraWatchCMSSpecific->sanitizeEmail($email));
        die();
    }

    public function renderAccountCreatedForEmail($email) {
        echo $this->renderFullIFrameWithURL("/#/pages/account/created?email=". $this->extraWatchCMSSpecific->sanitizeEmail($email));
        die();

    }

    /**
     * @param $url
     * @return string
     */
    public function renderFullIFrameWithURL($path) {
        $url = ExtraWatchConfig::ADMIN_GUI_URL.$path;
        $output = "
            <div style='overflow: hidden'>
            <iframe src='" . $this->extraWatchCMSSpecific->escapeOutput($url) . "' width='100%' height='100vh' style='min-height: 100vh; width:100%; overflow: hidden' scrolling='no' frameborder='0'>
            </iframe>
            </div>
            ";
        return $output;
    }

    public function renderAccountCreated() {
        $output = "
                        <div style='margin:20px'>
                        <br/><br/>Account has been created. Check your <b>*email*</b> for temporary password to log in with.
                        <br/><br/>
                        It will take a few minutes to initialize the project and see your visitors.
                        <br/><br/>     
                        <form action='". $this->extraWatchCMSSpecific->getCMSURL().$this->extraWatchCMSSpecific->getComponentPage()."' method='POST'>
                            <input type='submit' value='Continue'/>
                        </form>
                        </div>";
        return $output;
    }

}
