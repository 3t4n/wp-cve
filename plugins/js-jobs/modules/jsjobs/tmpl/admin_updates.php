<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

$host = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];
$url = "http://$host$self";
?>
<script>
    function opendiv() {
        document.getElementById('jsjob_installer_waiting_div').style.display = 'block';
        document.getElementById('jsjob_installer_waiting_span').style.display = 'block';
    }
</script>
<table width="100%">
    <tr>
        <td align="left" width="175" valign="top">
            <table width="100%"><tr><td style="vertical-align:top;">
                        <?php
                        include_once('components/com_jsjobs/views/menu.php');
                        ?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top">
            <div id="jsjobs_info_heading"><?php echo __('JS_ACTIVATE_UPDATES'); ?></div>	
            <div id="jsjob_installer_msg">
                <?php echo __('JS_JOBS_INSTALLER'); ?>
            </div>
            <form action="index.php" method="POST" name="adminForm" id="adminForm" >
                <div id="jsjob_installer_waiting_div" style="display:none;"></div>
                <span id="jsjob_installer_waiting_span" style="display:none;"><?php echo __('PLEASE_WAIT_INSTALLATION_IN_PROGRESS'); ?></span>
                <div id="jsjob_installer_outerwrap">
                    <div id="jsjob_installer_leftimage">
                        <span id="jsjob_installer_leftimage_logo"></span>
                    </div>
                    <div id="jsjob_installer_wrap">
                        <span id="installer_text">
                            <?php echo __('JS_PLEASE_FILL_THE_FORM_AND_PRESS_UPDATE'); ?>
                        </span>
                        <?php if (in_array('curl', get_loaded_extensions())) { ?>
                            <div id="jsjob_installer_formlabel">
                                <label id="transactionkeymsg" for="transactionkey"><?php echo __('ACTIVATION_KEY'); ?></label>
                            </div>
                            <div id="jsjob_installer_forminput">
                                <input id="transactionkey" name="transactionkey" class="inputbox required" value="" />
                            </div>
                            <div id="jsjob_installer_formsubmitbutton">
                                <input type="submit" class="button" name="submit_app" id="jsjob_instbutton" onclick="return confirmcall();" value="<?php echo __('Start'); ?>" />
                            </div>
                        <?php } else { ?>
                            <div id="jsjob_installer_warning"><?php echo __('WARNING'); ?>!</div>
                            <div id="jsjob_installer_warningmsg"><?php echo __('CURL_IS_NOT_ENABLE_PLEASE_ENABLE_CURL'); ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div id="jsjob_installer_lowerbar">
                    <?php if (!in_array('curl', get_loaded_extensions())) { ?>
                        <span id="jsjob_installer_arrow"><?php echo __('REFRENCE_LINK'); ?></span>
                        <span id="jsjob_installer_link"><a href="http://devilsworkshop.org/tutorial/enabling-curl-on-windowsphpapache-machine/702/"><?php echo __('http://devilsworkshop.org/...'); ?></a></span>
                        <span id="jsjob_installer_link"><a href="http://www.tomjepson.co.uk/enabling-curl-in-php-php-ini-wamp-xamp-ubuntu/"><?php echo __('http://www.tomjepson.co.uk/...'); ?></a></span>
                        <span id="jsjob_installer_link"><a href="http://www.joomlashine.com/blog/how-to-enable-curl-in-php.html"><?php echo __('http://www.joomlashine.com/...'); ?></a></span>
                    <?php } else { ?>
                        <span id="jsjob_installer_mintmsg"><?php echo __('IT_MAY_TAKE_FEW_MINUTES...'); ?></span>
                    <?php } ?>
                </div>

                <input type="hidden" name="check" value="" />
                <input type="hidden" name="domain" value="<?php echo site_url(); ?>" />
                <input type="hidden" name="producttype" value="pro" />
                <input type="hidden" name="count_config" value="<?php echo esc_attr($this->count_config); ?>" />
                <input type="hidden" name="productcode" value="jsjobs" />
                <input type="hidden" name="productversion" value="<?php echo esc_attr($this->configur[1]); ?>" />
                <input type="hidden" name="task" value="startupdate" />
                <input type="hidden" name="option" value="<?php echo esc_attr($this->option); ?>" />
            </form>
        </td>
    </tr>
</table>
<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'startinstallation') {
                returnvalue = validate_form(document.adminForm);
            } else
                returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
                return false;
        }
    }
    function confirmcall() {
        var result = confirm("<?php echo __('ALL_FILES_OVERRIDE_ARE_YOU_SURE_TO_CONTINUE'); ?>");
        if (result == true) {
            var r = validate_form(document.adminForm);
            return r;
        } else
            return false;
    }
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php if (JVERSION < 3)
                        echo JUtility::getToken();
                    else
                        echo JSession::getFormToken();
                    ?>';//send token
        }
        else {
            alert("<?php echo __('Some values are not acceptable. Please retry.','js-jobs'); ?>");
            return false;
        }
        opendiv();
        return true;
    }
</script>
















