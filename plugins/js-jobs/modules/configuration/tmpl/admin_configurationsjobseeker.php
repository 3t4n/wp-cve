<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-tabs');
$yesno = array((object) array('id' => 1, 'text' => __('Yes', 'js-jobs')), (object) array('id' => 0, 'text' => __('No', 'js-jobs')));
$showhide = array((object) array('id' => 1, 'text' => __('Show', 'js-jobs')), (object) array('id' => 0, 'text' => __('Hide', 'js-jobs')));
$applybutton = array((object) array('id' => 1, 'text' => __('Enable')), (object) array('id' => 2, 'text' => __('Disable')));
$msgkey = JSJOBSincluder::getJSModel('configuration')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
$theme_chk = jsjobs::$theme_chk;
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Job Seeker Configuration', 'js-jobs'); ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_configuration&task=saveconfiguration"),"formconfiguration")) ?>">
        <div id="tabs" class="tabs">
            <ul>
                <li><a href="#js_generalsetting"><?php echo __('General Settings', 'js-jobs'); ?></a></li>
                <li><a href="#js_resume_setting"><?php echo __('Resume Settings', 'js-jobs'); ?></a></li>
                <li><a href="#js_visitor"><?php echo __('Visitors', 'js-jobs'); ?></a></li>
                <li><a href="#js_jobsearch"><?php echo __('Job Search', 'js-jobs'); ?></a></li>
                <li><a href="#js_memberlinks"><?php echo __('Members Links', 'js-jobs'); ?></a></li>
                <li><a href="#js_visitorlinks"><?php echo __('Visitors Links', 'js-jobs'); ?></a></li>
                <li><a href="#email"><?php echo __('Email Alert', 'js-jobs'); ?></a></li>
            </ul>
            <div class="tabInner">
                <div id="js_generalsetting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('General Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Enable featured resume', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('system_have_featured_resume', $yesno, jsjobs::$_data[0]['system_have_featured_resume']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('Featured resume are allowed in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show company contact detail', 'js-jobs').' <small>( '.__('effect on credits system').' )</small>'; ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('company_contact_detail', $yesno, jsjobs::$_data[0]['company_contact_detail']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('If no then credits will be taken to view contact detail', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show apply button', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('showapplybutton', $yesno, jsjobs::$_data[0]['showapplybutton']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('Controls the visibility of apply now button in plugin', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Apply now redirect link', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('applybuttonredirecturl', jsjobs::$_data[0]['applybuttonredirecturl'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Click on Apply Now button will be redirect to given url', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show applied resume status', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('show_applied_resume_status', $yesno, jsjobs::$_data[0]['show_applied_resume_status']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show count in jobs by categories page', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('categories_numberofjobs', $yesno, jsjobs::$_data[0]['categories_numberofjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show count in jobs by types page', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobtype_numberofjobs', $yesno, jsjobs::$_data[0]['jobtype_numberofjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job seeker Registration redirect page ', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('register_jobseeker_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['register_jobseeker_redirect_page']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('whenever anyone registers as job seeker, he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow job seeker to add cover letter','js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('cover_letter', $yesno, 1), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('If no is selected then there will no way to add cover letter in the system', 'js-jobs'); ?></small></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume','js-jobs') .' '. __('auto approve', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('empautoapprove', $yesno, jsjobs::$_data[0]['empautoapprove']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Featured','js-jobs') .' '. __('resume','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('featuredresume_autoapprove', $yesno, jsjobs::$_data[0]['featuredresume_autoapprove']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job alert for visitor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('overwrite_jobalert_settings', $yesno, jsjobs::$_data[0]['overwrite_jobalert_settings']), JSJOBS_ALLOWED_TAGS); ?> </div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job short list', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('allow_jobshortlist', $yesno, jsjobs::$_data[0]['allow_jobshortlist']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Job short list setting effects on jobs listing page', 'js-jobs'); ?></small></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job alert','js-jobs') .' '. __('auto approve', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobalert_auto_approve', $yesno, jsjobs::$_data[0]['jobalert_auto_approve']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Tell a friend', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('allow_tellafriend', $yesno, jsjobs::$_data[0]['allow_tellafriend']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Tell a friend setting effects on jobs listing page', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show login logout button', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobsloginlogout', $yesno, jsjobs::$_data[0]['jobsloginlogout']), JSJOBS_ALLOWED_TAGS); ?> </div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Show login logout button in job seeker control panel', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
                <div id="js_resume_setting">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Resume Settings', 'js-jobs'); ?></h3>
                    <div class="js-job-configuration-table">
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Document file extensions', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('document_file_type', jsjobs::$_data[0]['document_file_type'], array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12  js-job-configuration-description"><small><?php echo __('Document file extensions allowed', 'js-jobs'); ?>, <?php echo __('Must be comma separated', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Resume file maximum size', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('document_file_size', jsjobs::$_data[0]['document_file_size'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?>&nbsp KB</div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('System will not upload if resume file size exceeds than given size', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of files for resume', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('document_max_files', jsjobs::$_data[0]['document_max_files'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of files that job seeker can upload in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Resume photo maximum size ', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('resume_photofilesize', jsjobs::$_data[0]['resume_photofilesize'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?> &nbsp;KB</div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of employers allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('max_resume_employers', jsjobs::$_data[0]['max_resume_employers'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of employers allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                        </div>          
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of institutes allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('max_resume_institutes', jsjobs::$_data[0]['max_resume_institutes'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of institutes allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of languages allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('max_resume_languages', jsjobs::$_data[0]['max_resume_languages'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of languages allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of references allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('max_resume_references', jsjobs::$_data[0]['max_resume_references'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of references allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12  js-job-configuration-title"><?php echo __('Number of addresses allowed', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::text('max_resume_addresses', jsjobs::$_data[0]['max_resume_addresses'], array('class' => 'inputbox', 'data-validation' => 'number')), JSJOBS_ALLOWED_TAGS); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Maximum number of addresses allowed in resume', 'js-jobs'); ?></small></div>
                            </div>
                        </div>      
                    </div>
                </div>
                <div id="js_visitor">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor can apply to job', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitor_can_apply_to_job', $yesno, jsjobs::$_data[0]['visitor_can_apply_to_job']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor can add resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitor_can_add_resume', $yesno, jsjobs::$_data[0]['visitor_can_add_resume']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show login message to visitor', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitor_show_login_message', $yesno, jsjobs::$_data[0]['visitor_show_login_message']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Show login option to visitor on job apply', 'js-jobs'); ?></small></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Visitor post resume redirect page ', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitor_add_resume_redirect_page', JSJOBSincluder::getJSModel('postinstallation')->getPageList(), jsjobs::$_data[0]['visitor_add_resume_redirect_page']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('whenever any visitor posts a resume, he will be redirected to this page', 'js-jobs'); ?></small></div>
                        </div>
                    </div> 
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on resume form', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('resume_captcha', $yesno, jsjobs::$_data[0]['resume_captcha']), JSJOBS_ALLOWED_TAGS); ?><div><small><?php echo __('Show captcha on visitor form resume', 'js-jobs'); ?></small></div></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on Job alert form', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('job_alert_captcha', $yesno, jsjobs::$_data[0]['job_alert_captcha']), JSJOBS_ALLOWED_TAGS); ?><br clear="all"/><div><small><?php echo __('Show captcha visitor job alert form', 'js-jobs'); ?></small></div></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Show captcha on tell a friend popup', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tell_a_friend_captcha', $yesno, jsjobs::$_data[0]['tell_a_friend_captcha']), JSJOBS_ALLOWED_TAGS); ?><br clear="all"/><div><small><?php echo __('Show captcha on visitor tell a friend popup', 'js-jobs'); ?></small></div></div>
                        </div>
                    </div>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Visitors Can View Job seeker', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_js_controlpanel', $showhide, jsjobs::$_data[0]['visitorview_js_controlpanel']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('View company', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_emp_viewcompany', $showhide, jsjobs::$_data[0]['visitorview_emp_viewcompany']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('View Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_emp_viewjob', $showhide, jsjobs::$_data[0]['visitorview_emp_viewjob']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_js_jobcat', $showhide, jsjobs::$_data[0]['visitorview_js_jobcat']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                    </div>
                    <div class="right">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_js_newestjobs', $showhide, jsjobs::$_data[0]['visitorview_js_newestjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_js_jobsearch', $showhide, jsjobs::$_data[0]['visitorview_js_jobsearch']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job search result', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('visitorview_js_jobsearchresult', $showhide, jsjobs::$_data[0]['visitorview_js_jobsearchresult']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div> 
                    </div>
                
                </div>
                <div id="js_jobsearch">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Search Job Settings', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Allow save search', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('search_job_showsave', $yesno, jsjobs::$_data[0]['search_job_showsave']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div><small><?php echo __('User can save search criteria', 'js-jobs'); ?></small></div>
                        </div> 
                    </div>
                </div>
                <div id="js_memberlinks">
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Top Menu Links','js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_jscontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_jscontrolpanel']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div> 
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_jsjobcategory', $showhide, jsjobs::$_data[0]['tmenu_jsjobcategory']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_jsnewestjob', $showhide, jsjobs::$_data[0]['tmenu_jsnewestjob']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        </div>
                        <div class="right"> 
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_jsmyresume', $showhide, jsjobs::$_data[0]['tmenu_jsmyresume']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_jssearchjob', $showhide, jsjobs::$_data[0]['tmenu_jssearchjob']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                            <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Dashboard','js-jobs'); ?></h3>
                            <div class="left">
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_jobs_graph', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_jobs_graph']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_useful_links']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_apllied_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_apllied_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Shortlisted Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_shortlisted_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_shortlisted_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                            </div>
                            <div class="right"> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_credits_log']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_purchase_history']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('temp_jobseeker_dashboard_newest_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_newest_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                            </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Control Panel Links','js-jobs'); ?></h3>
                    <div class="left">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Active Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsactivejobs_graph', $showhide, jsjobs::$_data[0]['jsactivejobs_graph']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Notifications', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('js_cpnotification', $showhide, jsjobs::$_data[0]['js_cpnotification']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('User Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('js_cpmessage', $showhide, jsjobs::$_data[0]['js_cpmessage']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resumes Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsappliedresume_box', $showhide, jsjobs::$_data[0]['jsappliedresume_box']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('myresumes', $showhide, jsjobs::$_data[0]['myresumes']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('formresume', $showhide, jsjobs::$_data[0]['formresume']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Cover Letters', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('mycoverletters', $showhide, jsjobs::$_data[0]['mycoverletters']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('formcoverletter', $showhide, jsjobs::$_data[0]['formcoverletter']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('All Companies', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('listallcompanies', $showhide, jsjobs::$_data[0]['listallcompanies']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobcat', $showhide, jsjobs::$_data[0]['jobcat']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('listnewestjobs', $showhide, jsjobs::$_data[0]['listnewestjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">    
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('listjobbytype', $showhide, jsjobs::$_data[0]['listjobbytype']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jscredits', $showhide, jsjobs::$_data[0]['jscredits']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jssuggestedjobs_box', $showhide, jsjobs::$_data[0]['jssuggestedjobs_box']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jspurchasehistory', $showhide, jsjobs::$_data[0]['jspurchasehistory']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobsearch', $showhide, jsjobs::$_data[0]['jobsearch']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('my_jobsearches', $showhide, jsjobs::$_data[0]['my_jobsearches']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job Alert', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobalertsetting', $showhide, jsjobs::$_data[0]['jobalertsetting']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsmessages', $showhide, jsjobs::$_data[0]['jsmessages']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsjob_rss', $showhide, jsjobs::$_data[0]['jsjob_rss']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsregister', $showhide, jsjobs::$_data[0]['jsregister']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Short Listed Jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('listjobshortlist', $showhide, jsjobs::$_data[0]['listjobshortlist']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jscreditlog', $showhide, jsjobs::$_data[0]['jscreditlog']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsratelist', $showhide, jsjobs::$_data[0]['jsratelist']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Applied Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('myappliedjobs', $showhide, jsjobs::$_data[0]['myappliedjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jsmystats', $showhide, jsjobs::$_data[0]['jsmystats']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                    </div>
                </div>
                <div id="js_visitorlinks">
                    <?php if($theme_chk == 0){ ?>     
                        <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Top Menu Links','js-jobs'); ?></h3>
                        <div class="left">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Control Panel', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_vis_jscontrolpanel', $showhide, jsjobs::$_data[0]['tmenu_vis_jscontrolpanel']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_vis_jsjobcategory', $showhide, jsjobs::$_data[0]['tmenu_vis_jsjobcategory']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_vis_jssearchjob', $showhide, jsjobs::$_data[0]['tmenu_vis_jssearchjob']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_vis_jsnewestjob', $showhide, jsjobs::$_data[0]['tmenu_vis_jsnewestjob']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('tmenu_vis_jsmyresume', $showhide, jsjobs::$_data[0]['tmenu_vis_jsmyresume']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        </div>
                    <?php }else{ ?>
                            <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Dashboard','js-jobs'); ?></h3>
                            <div class="left">
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs Graph', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_jobs_graph', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_jobs_graph']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Useful Links', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_useful_links', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_useful_links']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_apllied_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_apllied_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Shortlisted Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_shortlisted_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_shortlisted_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                            </div>
                            <div class="right"> 
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_credits_log', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_credits_log']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_purchase_history', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_purchase_history']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                                <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                    <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                                    <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_temp_jobseeker_dashboard_newest_jobs', $showhide, jsjobs::$_data[0]['temp_jobseeker_dashboard_newest_jobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                                </div>
                            </div>
                    <?php } ?>
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Job Seeker Control Panel Links','js-jobs'); ?></h3>
                    <div class="left">

                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Active Jobs Graph', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                                <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsactivejobs_graph', $showhide, jsjobs::$_data[0]['vis_jsactivejobs_graph']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied Resumes Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsappliedresume_box', $showhide, jsjobs::$_data[0]['vis_jsappliedresume_box']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Resumes', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmyresumes', $showhide, jsjobs::$_data[0]['vis_jsmyresumes']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Resume', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsformresume', $showhide, jsjobs::$_data[0]['vis_jsformresume']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Cover Letters', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmycoverletters', $showhide, jsjobs::$_data[0]['vis_jsmycoverletters']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Add','js-jobs') .' '. __('Cover Letter', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsformcoverletter', $showhide, jsjobs::$_data[0]['vis_jsformcoverletter']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('All Companies', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jslistallcompanies', $showhide, jsjobs::$_data[0]['vis_jslistallcompanies']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Categories', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsjobcat', $showhide, jsjobs::$_data[0]['vis_jsjobcat']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Newest Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jslistnewestjobs', $showhide, jsjobs::$_data[0]['vis_jslistnewestjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs By Types', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jslistjobbytype', $showhide, jsjobs::$_data[0]['vis_jslistjobbytype']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Applied Jobs', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmyappliedjobs', $showhide, jsjobs::$_data[0]['vis_jsmyappliedjobs']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">  
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jscredits', $showhide, jsjobs::$_data[0]['vis_jscredits']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <?php if($theme_chk == 0){ ?>     
                            <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                                <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Suggested Jobs Box', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                               <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jssuggestedjobs_box', $showhide, jsjobs::$_data[0]['vis_jssuggestedjobs_box']), JSJOBS_ALLOWED_TAGS); ?></div>
                            </div>
                        <?php } ?>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Credits Log', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jscreditlog', $showhide, jsjobs::$_data[0]['vis_jscreditlog']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Purchase History', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jspurchasehistory', $showhide, jsjobs::$_data[0]['vis_jspurchasehistory']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row"> 
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Search Job', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsjobsearch', $showhide, jsjobs::$_data[0]['vis_jsjobsearch']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">    
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Saved Searches', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmy_jobsearches', $showhide, jsjobs::$_data[0]['vis_jsmy_jobsearches']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Job Alert', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsjobalertsetting', $showhide, jsjobs::$_data[0]['vis_jsjobalertsetting']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">   
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Messages', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmessages', $showhide, jsjobs::$_data[0]['vis_jsmessages']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Jobs RSS', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_job_rss', $showhide, jsjobs::$_data[0]['vis_job_rss']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Rate List', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsratelist', $showhide, jsjobs::$_data[0]['vis_jsratelist']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Register', 'js-jobs'); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsregister', $showhide, jsjobs::$_data[0]['vis_jsregister']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('My Stats', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jsmystats', $showhide, jsjobs::$_data[0]['vis_jsmystats']), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Short Listed Jobs', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('vis_jslistjobshortlist', $showhide, jsjobs::$_data[0]['vis_jslistjobshortlist']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small></small></div>
                        </div>
                    </div>
                </div>
                <div id="email">
                    <h3 class="js-job-configuration-heading-main"><?php echo __('Applied Resume Alert', 'js-jobs'); ?></h3>
                    <div class="left">
                        <div class="js-col-xs-12 js-col-md-12 js-job-configuration-row">
                            <div class="js-col-xs-12 js-job-configuration-title"><?php echo __('Applied resume notification', 'js-jobs'); ?><font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font></div>
                            <div class="js-col-xs-12 js-job-configuration-value"><?php echo wp_kses(JSJOBSformfield::select('jobseeker_resume_applied_status', $yesno, jsjobs::$_data[0]['jobseeker_resume_applied_status']), JSJOBS_ALLOWED_TAGS); ?></div>
                            <div class="js-col-xs-12 js-job-configuration-desc"><small><?php echo __('Applied resume status change mail to jobseeker', 'js-jobs'); ?></small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('isgeneralbuttonsubmit', 0), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('jsjobslt', 'configurationsjobseeker'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'configuration_saveconfiguration'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-configuration')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-form-button">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Configuration', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>    
        <div class="js-form-button">
            <font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
            <?php echo __('Pro Version Only', 'js-jobs');?>
        </div>        
    </form>

    <script >
        jQuery(document).ready(function () {
            var value = jQuery("#showapplybutton").val();
            var divsrc = "div#showhideapplybutton";
            if (value == 2) {
                jQuery(divsrc).slideDown("slow");
            }
        });
        function showhideapplybutton(src, value) {
            var divsrc = "div#" + src;
            if (value == 2) {
                jQuery(divsrc).slideDown("slow");
            } else if (value == 1) {
                jQuery(divsrc).slideUp("slow");
                jQuery(divsrc).hide();
            }
            return true;
        }

        jQuery(document).ready(function () {
            jQuery("#tabs").tabs();
        });
    </script>
</div>
</div>
