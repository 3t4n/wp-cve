<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs&jsjobslt=steptwo')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Update', 'js-jobs'); ?>
    </span>
    <div id="jsjobs-content">
        <form action="<?php echo esc_url(admin_url("admin.php?page=jsjobs&task=getversionlist&action=jsjobtask")); ?>" method="POST" name="adminForm" id="adminForm"  >
            <div id="jsjob_installer_waiting_div" style="display:none;"></div>
            <span id="jsjob_installer_waiting_span" style="display:none;"><?php echo __('Please wait installation in progress', 'js-jobs'); ?></span>
            <div class="js_installer_wrapper">
                <div class="update-header-img step-3">
                    <div class="header-parts first-part">
                        <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                        <span class="text"><?php echo __('Configuration', 'js-jobs'); ?></span>
                        <span class="text-no">1</span>
                        <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
                    </div>
                    <div class="header-parts second-part">
                        <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                        <span class="text"><?php echo __('Permissions', 'js-jobs'); ?></span>
                        <span class="text-no">2</span>
                        <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
                    </div>
                    <div class="header-parts third-part">
                        <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange.png" />
                        <span class="text"><?php echo __('Install', 'js-jobs'); ?></span>
                        <span class="text-no">3</span>
                        <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange-2.png" />
                    </div>
                    <div class="header-parts fourth-part">
                        <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/blue.png" />
                        <span class="text"><?php echo __('Finish', 'js-jobs'); ?></span>
                        <span class="text-no">4</span>
                        <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/grey-2.png" />
                    </div>
                </div>
                <div class="js_header_bar"><?php echo __('Please fill the form and press start', 'js-jobs'); ?></div>
                <div class="js_wrapper">
                    <?php /* if (in_array('curl', get_loaded_extensions())) { */ ?>
                        <div class="js_installer_formwrapper">
                            <div id="jsjob_installer_forminput">
                                <input style="height:35px;" id="transactionkey" name="transactionkey" class="inputbox required" value="<?php echo esc_attr(jsjobs::$_data['transactionkey']);?>" placeholder="<?php echo __('Authentication key','js-jobs'); ?>..." />
                                <div id="jsjob_installer_formsubmitbutton">
                                    <input type="submit" class="nextbutton" id="startpress" value="<?php echo __('Start','js-jobs'); ?>"/>  
                                </div>
                                <div class="js_installer_wrapper">
                                    <?php if (!in_array('curl', get_loaded_extensions())) { ?>
                                        <span id="jsjob_installer_arrow"><?php echo __('Refrence links'); ?></span>
                                        <span id="jsjob_installer_link"><a href="http://devilsworkshop.org/tutorial/enabling-curl-on-windowsphpapache-machine/702/"><?php echo __('http://devilsworkshop.org/...'); ?></a></span>
                                        <span id="jsjob_installer_link"><a href="http://www.tomjepson.co.uk/enabling-curl-in-php-php-ini-wamp-xamp-ubuntu/"><?php echo __('http://www.tomjepson.co.uk/...'); ?></a></span>
                                        <span id="jsjob_installer_link"><a href="http://www.joomlashine.com/blog/how-to-enable-curl-in-php.html"><?php echo __('http://www.joomlashine.com/...'); ?></a></span>
                                    <?php } else { ?>
                                        <span id="jsjob_installer_mintmsg"><?php echo __('It may take few minutes', 'js-jobs'); ?>...</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php /* } else { ?>
                        <div id="jsjob_installer_warning"><?php echo __('WARNING', 'js-jobs'); ?>!</div>
                        <div id="jsjob_installer_warningmsg"><?php echo __('CURL is not enabled please enable CURL', 'js-jobs'); ?></div>
                    <?php } */ ?>
                </div>
            </div>
            <input type="hidden" name="check" value="" />
            <input type="hidden" name="domain" id="domain" value="<?php echo site_url(); ?>" />
            <input type="hidden" name="producttype" id="producttype" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype')); ?>" />
            <input type="hidden" name="productcode" id="productcode" value="jsjobs" />
            <input type="hidden" name="productversion" id="productversion" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode')); ?>" />
            <input type="hidden" name="count_config" id="count_config" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getCountConfig());  ?>" />
            <input type="hidden" name="JVERSION" id="JVERSION" value="<?php echo esc_attr(get_bloginfo('version')); ?>" />
            <input type="hidden" name="installerversion" id="installerversion" value="1.1" />
            <input type="hidden" name="c" value="installer" />
            <input type="hidden" name="task" value="startinstallation" />
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php 
            if(jsjobs::$_data['response'] != ''){
                $response = jsjobslib::jsjobs_safe_decoding(jsjobs::$_data['response']);
                $response = json_decode($response);
                if($response[0] !== true){ ?>
                    <div id="jsst_error_message" style="display: inline-block;"><?php echo wp_kses($response[1], JSJOBS_ALLOWED_TAGS)?></div><?php  
                }else{ ?>
                    <div id="jsst_next_form" style="display: inline-block;"><?php echo wp_kses($response[1], JSJOBS_ALLOWED_TAGS)?></div><?php 
                }
            } ?>
    </div>
</div>
</div>
