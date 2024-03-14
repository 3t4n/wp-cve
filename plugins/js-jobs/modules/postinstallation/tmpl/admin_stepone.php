<?php 
$date_format = array((object) array('id' => 'd-m-Y', 'text' => __('DD MM YYYY', 'js-jobs')),
                            (object) array('id' => 'm/d/Y', 'text' => __('MM DD YYYY', 'js-jobs')), 
                            (object) array('id' => 'Y-m-d', 'text' => __('YYYY MM DD', 'js-jobs')));
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs'))
                    , (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper" class="post-installation">
    <div class="js-admin-title-installtion">
        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/setting-icon.png" />
        <?php echo __('JS Jobs Settings','js-jobs'); ?>
    </div>
    <div class="post-installtion-content-header">
        <div class="update-header-img step-1">
            <div class="header-parts first-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange.png" />
                <span class="text"><?php echo __('General', 'js-jobs'); ?></span>
                <span class="text-no">1</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange-2.png" />
            </div>
            <div class="header-parts second-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/blue.png" />
                <span class="text"><?php echo __('Employer', 'js-jobs'); ?></span>
                <span class="text-no">2</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/grey-2.png" />
            </div>
            <div class="header-parts third-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/blue.png" />
                <span class="text"><?php echo __('Job seeker', 'js-jobs'); ?></span>
                <span class="text-no">3</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/grey-2.png" />
            </div>
            <div class="header-parts fourth-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/blue.png" />
                <span class="text"><?php echo __('Sample data', 'js-jobs'); ?></span>
                <span class="text-no">4</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/grey-2.png" />
            </div>
        </div>
    </div>
    <span class="heading-post-ins"><?php echo __('Site Settings','js-jobs');?></span>
    <div class="post-installtion-content">
        <form id="jsjobs-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_postinstallation&task=save&action=jsjobtask"),"postinstallation")); ?>">
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Title','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('title',jsjobs::$_data[0]['title'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('System slug','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('system_slug',jsjobs::$_data[0]['system_slug'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Default page');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('default_pageid', JSJOBSincluder::getJSModel('postinstallation')->getPageList(),jsjobs::$_data[0]['default_pageid'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Select JS Jobs default page, on action system will redirect on selected page','js-jobs');?>.</div>
                <div class="desc"><?php echo __('If not select default page, email links and support icon might not work','js-jobs');?>. </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Data directory','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('data_directory',jsjobs::$_data[0]['data_directory'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('System will upload all user files in this folder','js-jobs');?> </div>
                <div class="desc"><?php echo wp_kses(JSJOBS_PLUGIN_PATH.jsjobs::$_data[0]['data_directory'], JSJOBS_ALLOWED_TAGS);?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Admin email address','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('adminemailaddress',jsjobs::$_data[0]['adminemailaddress'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Admin will receive email notifications on this address','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('System email address','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('mailfromaddress',jsjobs::$_data[0]['mailfromaddress'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Email address that will be used to send emails','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Email from name','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::text('mailfromname',jsjobs::$_data[0]['mailfromname'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Sender name that will be used in emails','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Show breadcrumbs','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('cur_location', $yesno,jsjobs::$_data[0]['cur_location'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Show navigation in breadcrumbs','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Default date format','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('date_format', $date_format,jsjobs::$_data[0]['date_format'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Date format for plugin','js-jobs');?> </div>
            </div>
            <div class="pic-button-part">
                <a class="next-step full-width" href="#" onclick="document.getElementById('jsjobs-form-ins').submit();" >
                    <?php echo __('Next','js-jobs'); ?>
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/arrow.png" />
                </a>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'postinstallation_save'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('step', 1), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    </div>
            <div class="close-button-bottom">
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>" class="close-button">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/close-icon.png" />
                    <?php echo __('Close','js-jobs'); ?>
                </a>
            </div>
    
</div>
