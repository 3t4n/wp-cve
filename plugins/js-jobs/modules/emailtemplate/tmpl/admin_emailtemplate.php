<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('emailtemplate')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Email Templates', 'js-jobs'); ?>
    </span>
    <form method="post" class="emailtemplateform" action="<?php echo esc_url(admin_url("?page=jsjobs_emailtemplate&task=saveemailtemplate")); ?>">
        <div class="js-email-menu">
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ew-cm') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ew-cm')); ?>"><?php echo __('New company', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'd-cm') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=d-cm')); ?>"><?php echo __('Delete company', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'cm-sts') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=cm-sts')); ?>"><?php echo __('Company status', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ew-ob') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ew-ob')); ?>"><?php echo __('New job', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ew-obv') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ew-obv')); ?>"><?php echo __('New visitor job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ob-sts') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ob-sts')); ?>"><?php echo __('Job Status', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ob-d') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ob-d')); ?>"><?php echo __('Job delete', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ew-rm') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ew-rm')); ?>"><?php echo __('New resume', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ew-rmv') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ew-rmv')); ?>"><?php echo __('New visitor resume', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'rm-sts') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=rm-sts')); ?>"><?php echo __('Resume status', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'd-rs') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=d-rs')); ?>"><?php echo __('Delete resume', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'em-n') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=em-n')); ?>"><?php echo __('New employer', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'obs-n') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=obs-n')); ?>"><?php echo __('New Job seeker', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'em-pc') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=em-pc')); ?>"><?php echo __('Employer purchase credits pack', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'obs-pc') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=obs-pc')); ?>"><?php echo __('Job seeker purchase credits pack', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ob-pe') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ob-pe')); ?>"><?php echo __('Job seeker package expiry', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'em-pe') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=em-pe')); ?>"><?php echo __('Employer package expiry', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ad-jap') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ad-jap')); ?>"><?php echo __('Job apply admin', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'em-jap') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=em-jap')); ?>"><?php echo __('Job apply employer', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'js-jap') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=js-jap')); ?>"><?php echo __('Job apply job seeker', 'js-jobs'); ?></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'ap-jap') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=ap-jap')); ?>"><?php echo __('Applied resume status change', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'jb-at') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=jb-at')); ?>"><?php echo __('Job alert', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
            <span class="js-email-menu-link <?php if (jsjobs::$_data[1] == 'jb-to-fri') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_emailtemplate&for=jb-to-fri')); ?>"><?php echo __('Tell to friend', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></a></span>
        </div>
        <div class="js-email-body">
            <div class="js-form-wrapper">
                <div class="a-js-form-title"><?php echo __('Subject', 'js-jobs'); ?></div>
                <div class="a-js-form-field"><?php echo wp_kses(JSJOBSformfield::text('subject', jsjobs::$_data[0]->subject, array('class' => 'inputbox', 'style' => 'width:100%;')), JSJOBS_ALLOWED_TAGS) ?></div>
            </div>
            <div class="js-form-wrapper">
                <div class="a-js-form-title"><?php echo __('Body', 'js-jobs'); ?></div>
                <div class="a-js-form-field"><?php wp_editor(jsjobs::$_data[0]->body, 'body', array('media_buttons' => false)); ?></div>
            </div>
            <div class="js-email-parameters">
                <span class="js-email-parameter-heading"><?php echo __('Parameters', 'js-jobs') ?></span>
                <?php if (jsjobs::$_data[1] == 'ew-cm') { ?>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_LINK}:  <?php echo __('View company', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_CREDITS}:  <?php echo __('Credits for company', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_STATUS}:  <?php echo __('Company status for approve,reject,pending', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'cm-sts') { ?>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer Name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_LINK}:  <?php echo __('View company', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_STATUS}:  <?php echo __('Company approve or reject', 'js-jobs').'('.__('Gold','js-jobs') .','.__('Featured','js-jobs') . ')'; ?></span>
                    <span class="js-email-paramater">{COMPANY_CREDITS}:  <?php echo __('Credits for company', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'd-cm') { ?>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company Name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_OWNER_NAME}:  <?php echo __('Company Owner Name', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'd-rs') { ?>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ew-ob') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_LINK}:  <?php echo __('Job link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_CREDITS}:  <?php echo __('Credits for job', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ob-sts') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_LINK}:  <?php echo __('Job link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_STATUS}:  <?php echo __('Job  approve or reject', 'js-jobs').'('.__('Gold','js-jobs') .','.__('Featured','js-jobs') . ')'; ?></span>
                    <span class="js-email-paramater">{JOB_CREDITS}:  <?php echo __('Credits for job', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'em-n') { ?>
                    <span class="js-email-paramater">{USER_ROLE}:  <?php echo __('Role for employer', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{USER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{CONTROL_PANEL_LINK}:  <?php echo __('Employer control panel link', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'obs-n') { ?>
                    <span class="js-email-paramater">{USER_ROLE}:  <?php echo __('Role for job seeker', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{USER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{CONTROL_PANEL_LINK}:  <?php echo __('Job seeker control panel link', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ew-obv') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_LINK}:  <?php echo __('Job link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>    
                <?php } elseif (jsjobs::$_data[1] == 'ob-d') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'em-jap') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_DATA}:  <?php echo __('Resume data', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_APPLIED_STATUS}:  <?php echo __('Resume curent status', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COVER_LETTER_TITLE}:  <?php echo __('Cover letter title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COVER_LETTER_DESCRIPTION}:  <?php echo __('Cover letter description', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'js-jap') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COMPANY_NAME}:  <?php echo __('Company name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_APPLIED_STATUS}:  <?php echo __('Resume curent status', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ew-rm') { ?>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_LINK}:  <?php echo __('Resume link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_STATUS}:  <?php echo __('Resume  approve or reject', 'js-jobs').'('.__('Gold','js-jobs') .','.__('Featured','js-jobs') . ')'; ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ew-rmv') { ?>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_LINK}:  <?php echo __('Resume link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_STATUS}:  <?php echo __('Resume  approve or reject', 'js-jobs').'('.__('Gold','js-jobs') .','.__('Featured','js-jobs') . ')'; ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'rm-sts') { ?>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_LINK}:  <?php echo __('Resume link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_STATUS}:  <?php echo __('Resume  approve or reject', 'js-jobs').'('.__('Gold','js-jobs') .','.__('Featured','js-jobs') . ')'; ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ew-ms') { ?>
                    <span class="js-email-paramater">{RESUME_TITLE}:  <?php echo __('Resume title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'em-pc') { ?>
                    <span class="js-email-paramater">{PACKAGE_NAME}:  <?php echo __('Package title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PRICE}:  <?php echo __('Package price', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_LINK}:  <?php echo __('View package', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PURCHASE_DATE}:  <?php echo __('Payment status', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'em-pe') { ?>
                    <span class="js-email-paramater">{PACKAGE_NAME}:  <?php echo __('Package title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_LINK}:  <?php echo __('View package', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_STATUS}:  <?php echo __('package status for approve or reject', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ob-pe') { ?>
                    <span class="js-email-paramater">{PACKAGE_NAME}:  <?php echo __('Package title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PRICE}:  <?php echo __('Package price', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_LINK}:  <?php echo __('View package', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PURCHASE_DATE}:  <?php echo __('Payment status', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'obs-pc') { ?>
                    <span class="js-email-paramater">{PACKAGE_NAME}:  <?php echo __('Package title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PRICE}:  <?php echo __('Package price', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_LINK}:  <?php echo __('View package', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{PACKAGE_PURCHASE_DATE}:  <?php echo __('Payment status', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ad-jap') { ?>
                    <span class="js-email-paramater">{EMPLOYER_NAME}:  <?php echo __('Employer name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job Title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_LINK}:  <?php echo __('Resume link', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_DATA}:  <?php echo __('Resume data', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COVER_LETTER_TITLE}:  <?php echo __('Cover letter title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{COVER_LETTER_DESCRIPTION}:  <?php echo __('Cover letter description', 'js-jobs'); ?></span>
                <?php } elseif (jsjobs::$_data[1] == 'ap-jap') { ?>
                    <span class="js-email-paramater">{JOB_TITLE}:  <?php echo __('Job title', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{JOBSEEKER_NAME}:  <?php echo __('Job seeker name', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_STATUS}:  <?php echo __('Applied resume status', 'js-jobs'); ?></span>
                    <span class="js-email-paramater">{RESUME_LINK}:  <?php echo __('Resume link', 'js-jobs'); ?></span>
                <?php } ?>
            </div>
            <div class="js-form-button">
                <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Email Template', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            </div>          
        <div class="js-form-button">
            <font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
            <?php echo __('Pro Version Only', 'js-jobs');?>
        </div>

        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', jsjobs::$_data[0]->id), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('created', jsjobs::$_data[0]->created), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('templatefor', jsjobs::$_data[0]->templatefor), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('for', jsjobs::$_data[1]), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'emailtemplate_saveemailtemplate'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-emailtemplate')), JSJOBS_ALLOWED_TAGS); ?>
    </form>
</div>
</div>
