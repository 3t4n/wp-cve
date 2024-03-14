<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Update', 'js-jobs'); ?>
    </span>
    <div id="jsjobs-content">
            <div id="jsjobs-lower-wrapper">
                <div style="display:none;" id="jsjob_installer_waiting_div"></div>
                <span style="display:none;" id="jsjob_installer_waiting_span"><?php echo __("Please wait installation in progress",'js-jobs'); ?></span>
                <div class="jsjobs_installer_wrapper" id="jsjobs-installer_id">    
                    <div class="jsjobs_top">
                        <div class="jsjobs_logo_wrp">
                            <img src="<?php echo JSJOBS_PLUGIN_URL.'includes/images/installer/logo.png';?>">
                        </div>
                        <div class="jsjobs_heading_text"><?php echo __("JS Jobs",'js-jobs'); ?></div>
                        <div class="jsjobs_subheading_text"><?php echo __("Most Poweful Job Board Plugin",'js-jobs'); ?></div>
                    </div>
                    <form action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs&task=getversionlist&action=jsjobtask'),"version-list")); ?>" method="post">
                        <div class="jsjobs_middle" id="jsjobs_middle">
                            <div class="jsjobs_form_field_wrp">
                                <div class="jsjobs_bg_overlay">
                                    <input type="text" name="transactionkey" id="transactionkey" class="jsjobs_key_field" value="<?php echo isset(jsjobs::$_data[0]['transactionkey']) ? esc_attr(jsjobs::$_data[0]['transactionkey']) : '';?>" placeholder="<?php echo __('Activation key','js-jobs'); ?>"/>
                                </div>
                            </div>
                        </div>
                        <?php if(isset(jsjobs::$_data[0]['response'])){ ?>
                            <div id="invalid_activation_key" class="jsjobs_error_messages">
                                 <span class="jsjobs_msg"><?php echo wp_kses(jsjobs::$_data[0]['response'], JSJOBS_ALLOWED_TAGS); ?></span>
                            </div>
                        <?php } ?>
                         <?php if (jsjobs::$_data[0]['phpversion'] < 5) { ?>
                            <div class="jsjobs_error_messages">
                                <span class="jsjobs_msg"><?php echo __('PHP version smaller then recomended', 'js-jobs'); ?></span>
                            </div>
                        <?php } ?>
                            <?php /* if (jsjobs::$_data[0]['curlexist'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('CURL not exist', 'js-jobs'); ?></span>
                                </div>
                        <?php } */ ?>
                            <?php if (jsjobs::$_data['dir'] < 755) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Directory permissions error', 'js-jobs'); ?>&nbsp;"<?php echo esc_html(JSJOBS_PLUGIN_PATH); ?>"&nbsp;<?php echo __('directory is not writeable','js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['tmp_dir'] < 755) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Directory permissions error', 'js-jobs'); ?>&nbsp;"<?php echo esc_html(ABSPATH.'/tmp'); ?>"&nbsp;<?php echo __('directory is not writeable','js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['create_table'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Database create table not allowed', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['insert_record'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Database insert record not allowed', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['update_record'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Database update record not allowed', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['delete_record'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Database delete record not allowed', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['drop_table'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Database drop table not allowed', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                            <?php if (jsjobs::$_data['file_downloaded'] != 1) { ?>
                                <div class="jsjobs_error_messages">
                                    <span class="jsjobs_msg"><?php echo __('Error file not downloaded', 'js-jobs'); ?></span>
                                </div>
                        <?php } ?>
                        <div class="jsjobs_bottom">
                            <div class="jsjobs_submit_btn">
                            <?php if ((jsjobs::$_data[0]['phpversion'] > 5) && (jsjobs::$_data['dir'] >= 755 ) && (jsjobs::$_data['tmp_dir'] >= 755 ) && (jsjobs::$_data['create_table'] == 1) && (jsjobs::$_data['insert_record'] == 1) && (jsjobs::$_data['update_record'] == 1 ) && (jsjobs::$_data['delete_record'] == 1 ) && (jsjobs::$_data['drop_table'] == 1 ) && (jsjobs::$_data['file_downloaded'] == 1 )) { ?>
                                <button type="submit" class="jsjobs_btn" role="submit"><?php echo __("Start","js-jobs"); ?></button>
                            <?php }else{ ?>
                                <button type="button" class="jsjobs_btn" role="submit"><?php echo __("Start","js-jobs"); ?></button>
                            <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="check" value="" />
                        <input type="hidden" name="domain" id="domain" value="<?php echo esc_attr(site_url()); ?>" />
                        <input type="hidden" name="producttype" id="producttype" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype')); ?>" />
                        <input type="hidden" name="productcode" id="productcode" value="jsjobs" />
                        <input type="hidden" name="productversion" id="productversion" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode')); ?>" />
                        <input type="hidden" name="count_config" id="count_config" value="<?php echo esc_attr(JSJOBSincluder::getJSModel('configuration')->getCountConfig());  ?>" />
                        <input type="hidden" name="JVERSION" id="JVERSION" value="<?php echo esc_attr(get_bloginfo('version')); ?>" />
                        <input type="hidden" name="installerversion" id="installerversion" value="1.2" />
                        <input type="hidden" name="c" value="installer" />
                        <input type="hidden" name="task" value="startinstallation" />
                        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                    </form> 
                </div>
            </div>
        </div>        
    </div>
</div>
