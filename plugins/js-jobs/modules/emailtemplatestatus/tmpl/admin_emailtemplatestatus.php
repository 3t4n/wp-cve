<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('emailtemplatestatus')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Email Templates Options', 'js-jobs') ?>
    </span>
    <table id="js-table" class="tbl" >
        <thead>
            <tr>
                <th class="left-row"><?php echo __('Title', 'js-jobs'); ?></th>
                <th class="centered"><?php echo __('Employer', 'js-jobs'); ?></th>
                <th><?php echo __('Job Seeker', 'js-jobs'); ?></th>
                <th><?php echo __('Admin', 'js-jobs'); ?></th>
                <th><?php echo __('Job Seeker Visitor', 'js-jobs'); ?></th>
                <th><?php echo __('Employer Visitor', 'js-jobs'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Company', 'js-jobs'); ?></td>
            </tr>
            <?php
                $specclass = 'emailtemplatestatus-row';
            ?>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['add_new_company']['tempname']);
                ?>		    	
                <td class="left-row"><?php echo esc_attr($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_company']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_company']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_company']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_company']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_company']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_company']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td> - </td>
            </tr>			
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['delete_company']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['delete_company']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['delete_company']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['delete_company']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['company_status']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['company_status']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['company_status']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['company_status']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
            </tr>
            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Job', 'js-jobs'); ?></td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['add_new_job']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>				
                    <?php if (jsjobs::$_data[0]['add_new_job']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_job']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_job']['employer_vis'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=5'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_job']['tempid'].'&actionfor=5'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['delete_job']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['delete_job']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['delete_job']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['delete_job']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['job_status']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['job_status']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['job_status']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['job_status']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td> - </td>
                <td> - </td>
                <td> - </td>
                <td>
                    <?php if (jsjobs::$_data[0]['job_status']['employer_vis'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['job_status']['tempid'].'&actionfor=5'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['job_status']['tempid'].'&actionfor=5'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>	
                </td>
            </tr>
            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Resume', 'js-jobs'); ?></td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['add_new_resume']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_resume']['jobseeker'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=2'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=2'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>	
                </td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_resume']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>	
                </td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_resume']['jobseeker_vis'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=4'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_resume']['tempid'].'&actionfor=4'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>	
                </td>
                <td>-</td>
            </tr>		
            


            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['resume-delete']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['resume-delete']['jobseeker'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume-delete']['tempid'].'&actionfor=2'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume-delete']['tempid'].'&actionfor=2'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>	
                </td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['resume_status']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['resume_status']['jobseeker'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume_status']['tempid'].'&actionfor=2'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume_status']['tempid'].'&actionfor=2'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>  
                </td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['resume_status']['jobseeker_vis'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume_status']['tempid'].'&actionfor=4'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['resume_status']['tempid'].'&actionfor=4'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>  
                </td>
                <td>-</td>
            </tr>
            

            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Employer', 'js-jobs'); ?></td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['add_new_employer']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_employer']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_employer']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_employer']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_employer']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_employer']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_employer']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Job seeker', 'js-jobs'); ?></td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['add_new_jobseeker']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>-</td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_jobseeker']['jobseeker'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=2'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=2'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if (jsjobs::$_data[0]['add_new_jobseeker']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td colspan="7" class="section-header" ><?php echo __('Miscellaneous', 'js-jobs'); ?></td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['employer_purchase_credits_pack']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></td>
                <td>PRO</td>
                <td>-</td>
                <td>PRO</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['jobseeker_purchase_credits_pack']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></td>
                <td>-</td>
                <td>PRO</td>
                <td>PRO</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['employer_package_expire']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></td>
                <td>PRO</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['jobseeker_package_expire']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></td>
                <td>-</td>
                <td>PRO</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>		
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['jobapply_jobapply']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?></td>
                <td>
                    <?php if (jsjobs::$_data[0]['jobapply_jobapply']['employer'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=1'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=1'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if (jsjobs::$_data[0]['jobapply_jobapply']['jobseeker'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=2'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=2'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if (jsjobs::$_data[0]['jobapply_jobapply']['admin'] == 1) { ?> 
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=noSendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=3'),'nosendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/yes.png" border="0" alt="<?php echo __('Send email', 'js-jobs'); ?>" /></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplatestatus&task=sendEmail&action=jsjobtask&jsjobsid='.jsjobs::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=3'),'sendemail-emailtemplate')); ?>">
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png" border="0" alt="<?php echo __('Dont send email', 'js-jobs'); ?>" /></a>
                    <?php } ?>
                </td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr class="<?php echo esc_attr($specclass); ?>">
                <?php
                $lang = JSJOBSincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(jsjobs::$_data[0]['applied-resume_status']['tempname']);
                ?>
                <td class="left-row"><?php echo esc_html($lang); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></td>
                <td>-</td>
                <td> PRO
                </td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>
            <div class="js-form-button">
            <font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
            <?php echo __('Pro Version Only', 'js-jobs');?>
        </div>
</div>
</div>
