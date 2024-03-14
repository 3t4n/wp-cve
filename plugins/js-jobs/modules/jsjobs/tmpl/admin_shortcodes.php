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
    $msgkey = JSJOBSincluder::getJSModel('jsjobs')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Short Codes', 'js-jobs'); ?>
    </span>
    <table id="js-table">
        <thead>
            <tr>
                <th id="short-code-left" class="left-row"><?php echo __('Title', 'js-jobs'); ?></th>
                <th id="short-code-middle" class="left-row"><?php echo __('Short code', 'js-jobs'); ?></th>
                <th id="short-code-right" class="left-row"><?php echo __('Description', 'js-jobs'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker control panel','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_controlpanel]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker control panel','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer control panel','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_controlpanel]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer control panel','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('All Companies','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_all_companies]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show all published companies','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job','js-jobs').' '. __('search','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_job_search]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job','js-jobs').' '. __('search','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Jobs','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_job]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('jobs','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job categories','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_job_categories]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job categories','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job types','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_job_types]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job types','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My applied jobs','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_appliedjobs]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('My applied jobs','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My companies','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_companies]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show employer','js-jobs').' '. __('My companies','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My cover letters','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_coverletter]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('My cover letters','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My departments','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_departments]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('My departments','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My jobs','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_jobs]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('My jobs','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('My resume','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_my_resumes]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('My resumes','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add','js-jobs').' '. __('Company','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_add_company]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('add','js-jobs').' '. __('company','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add','js-jobs').' '. __('Cover Letter','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_add_coverletter]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('add','js-jobs').' '. __('Cover letter','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add','js-jobs').' '. __('Department','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_add_department]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('add','js-jobs').' '. __('Department','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add','js-jobs').' '. __('job','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_add_job]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('add','js-jobs').' '. __('job','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Add','js-jobs').' '. __('resume','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_add_resume]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('add','js-jobs').' '. __('resume','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Resume','js-jobs').' '. __('search','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_resume_search]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('resume','js-jobs').' '. __('search','js-jobs').' '. __('form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer registration','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_registration]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('registration form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker registration','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_registration]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('registration form','js-jobs'); ?>
                </td>
            </tr>
            <?php /*
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker credit packages','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_credits_pack]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('credit packages','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer credit packages','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_credits_pack]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('credit packages','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker','js-jobs').' '. __('rate list','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_ratelist]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker','js-jobs').' '. __('rate list','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer rate list','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_ratelist]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('rate list','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Shortlisted jobs','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_shortlisted_jobs]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('shortlisted jobs','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job alert','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobalert]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show job alert form','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker','js-jobs').' '. __('credits log','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_credits_log]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('jobseeker credits log','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer credits log','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_credits_log]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer','js-jobs').' '. __('credits log','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker purchase history','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_purchase_history]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker purchase history','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer purchase history','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_purchase_history]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer purchase history','js-jobs'); ?>
                </td>
            </tr>
            */ ?>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job seeker my stats','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobseeker_my_stats]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('job seeker my stats','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Employer my stats','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_employer_my_stats]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('employer my stats','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Login page','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_login_page]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show login page','js-jobs'); ?>
                </td>
            </tr>
            <?php /*
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Jobs','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobs]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('jobs','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Resume','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_resumes]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('resume','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Companies','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_companies]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('companies','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            */ ?>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Search job','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_searchjob]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show','js-jobs').' '. __('search','js-jobs').' '. __('job','js-jobs').' '. __('form','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <?php /*
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Search resume widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_searchresume]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('search ','js-jobs').' '. __('resume ','js-jobs').' '. __('form','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job categories ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobbycategory]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job categories ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job types ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobbytypes]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job types ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job stats ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobstats]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job statistics ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job by cities ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobsbycities]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job by cities ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job by state ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobsbystate]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job by state ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            <tr valign="top">
                <td id="short-code-left" class="left-row">
                    <?php echo __('Job by countries ','js-jobs').' '. __('widget','js-jobs'); ?>
                </td>
                <td id="short-code-middle" class="left-row">
                    <?php echo '[jsjobs_jobsbycountries]'; ?>
                </td>
                <td id="short-code-right" class="left-row">
                    <?php echo __('Show ','js-jobs').' '. __('job by countries ','js-jobs').' '. __('in widget style','js-jobs'); ?>
                </td>
            </tr>
            */ ?>
        </tbody>
    </table>
</div>
</div>
