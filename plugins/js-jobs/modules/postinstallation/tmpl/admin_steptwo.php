<?php 
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs'))
                    , (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
wp_enqueue_script('jsjob-commonjs', JSJOBS_PLUGIN_URL . 'includes/js/radio.js');
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper" class="post-installation">
    <div class="js-admin-title-installtion">
        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/setting-icon.png" />
        <?php echo __('JS Jobs Settings','js-jobs'); ?>
    </div>
    <div class="post-installtion-content-header">
        <div class="update-header-img step-2">
            <div class="header-parts first-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green.png" />
                <span class="text"><?php echo __('General', 'js-jobs'); ?></span>
                <span class="text-no">1</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/green-2.png" />
            </div>
            <div class="header-parts second-part">
                <img class="start" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange.png" />
                <span class="text"><?php echo __('Employer', 'js-jobs'); ?></span>
                <span class="text-no">2</span>
                <img class="end" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/header/orange-2.png" />
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
    
    <span class="heading-post-ins"><?php echo __('Employer Configuration','js-jobs');?></span>
    <div class="post-installtion-content">
        <form id="jsjobs-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_postinstallation&task=save&action=jsjobtask"),"postinstallation")); ?>">
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Enable Employer Area','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('disable_employer', $yesno,jsjobs::$_data[0]['disable_employer'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('If no then front end employer area is not accessable','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Allow user register as employer','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('showemployerlink', $yesno,jsjobs::$_data[0]['showemployerlink'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('Effects on user registration','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Employer default role','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('employer_defaultgroup', $userroles,jsjobs::$_data[0]['employer_defaultgroup'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
                <div class="desc"><?php echo __('This role will auto assign to new employer','js-jobs');?> </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Employer can view job seeker area','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('employerview_js_controlpanel', $yesno,jsjobs::$_data[0]['employerview_js_controlpanel'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Company auto approve','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('companyautoapprove', $yesno,jsjobs::$_data[0]['companyautoapprove'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <div class="pic-config">
                <div class="title"> 
                    <?php echo __('Job auto approve','js-jobs');?>: &nbsp;
                </div>
                <div class="field"> 
                    <?php echo wp_kses(JSJOBSformfield::select('jobautoapprove', $yesno,jsjobs::$_data[0]['jobautoapprove'],'',array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <div class="pic-button-part">
                <a class="next-step" href="#"  onclick="document.getElementById('jsjobs-form-ins').submit();" >
                    <?php echo __('Next','js-jobs'); ?>
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/next-arrow.png" />
                </a>
                <a class="back" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_postinstallation&jsjobslt=stepone')); ?>"> 
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/back-arrow.png" />
                    <?php echo __('Back','js-jobs'); ?>
                </a>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'postinstallation_save'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('step', 2), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    </div>
    <div class="close-button-bottom">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>" class="close-button">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/postinstallation/close-icon.png" />
            <?php echo __('Close','js-jobs'); ?>
        </a>
    </div>
    
</div>
