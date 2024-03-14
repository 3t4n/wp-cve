<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
    $msgkey = JSJOBSincluder::getJSModel('jobseeker')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
} else {
    JSJOBSMessages::getLayoutMessage('user');
}
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $guestflag = false;
    $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
    $isguest = JSJOBSincluder::getObjectClass('user')->isguest();

    if($isguest == true){
        $guestflag = true;
    }
    if($isguest == false && $isouruser == false){
        $guestflag = true;
    }
    ?>
    <div id="jsjobs-wrapper">
        <div class="control-pannel-header">
            <span class="heading"><?php echo __('Control Panel', 'js-jobs'); ?></span>
        </div>

        <div id='jobseeker-control-pannel-wrapper'>

            <div class="cp-topbox">
                <?php
                $print = jobseekercheckLinks('listnewestjobs');
                if ($print) {
                    ?>
                    <div class="js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color1" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'newestjobs')),'job')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/newest-job.png"><span class="cp-text"><?php echo __('Newest Jobs', 'js-jobs'); ?></span></a>
                    </div>
                    <?php
                }
                $print = jobseekercheckLinks('myappliedjobs');
                if ($print) {
                    ?>
                    <div class="js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color2" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs'))); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/applied-jobs.png"><span class="cp-text"><?php echo __('My Applied Jobs', 'js-jobs'); ?></span></a>
                    </div>

                    <?php
                }
                $print = jobseekercheckLinks('myresumes');
                if ($print) {
                    ?>
                    <div class="js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color3" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),'resume')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/resume.png"><span class="cp-text"><?php echo __('My Resumes', 'js-jobs'); ?></span></a>
                    </div>
                    <?php
                }
                $print = jobseekercheckLinks('jobsearch');
                if ($print) {
                    ?>
                    <div class="js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color4" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsearch'))); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/search-job.png"><span class="cp-text"><?php echo __('Search Job', 'js-jobs'); ?></span></a>
                    </div>
                <?php } ?>
            </div>
            <div class="jsjobs-cp-border"></div>
            <?php
            if(jobseekercheckLinks('jobcat') || jobseekercheckLinks('listjobbytype') || jobseekercheckLinks('formresume') || jobseekercheckLinks('listjobshortlist') || jobseekercheckLinks('formcoverletter') || jobseekercheckLinks('mycoverletters') || jobseekercheckLinks('jsmessages') || jobseekercheckLinks('my_jobsearches') || jobseekercheckLinks('jsregister') || jobseekercheckLinks('jsjob_rss') || jobseekercheckLinks('jobalertsetting') || jobseekercheckLinks('jobsloginlogout') )
            { ?>
                <div class="jsjobs-bottombox">
                    <?php
                    $print = jobseekercheckLinks('jobcat');
                    if ($print) {
                        ?>
                        <div class="js-cp-wrapper">
                            <a class="js-anc color1" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbycategories'))); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/job-categories.png"></div><span class="cp-text"><?php echo __('Jobs By Categories', 'js-jobs'); ?></span></a>
                        </div>
                        <?php
                    }
                    $print = jobseekercheckLinks('listjobbytype');
                    if ($print) {
                        ?>
                        <div class="js-cp-wrapper">
                            <a class="js-anc color2" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobsbytypes'))); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/job-type.png"></div><span class="cp-text"><?php echo __('Jobs By Types', 'js-jobs'); ?></span></a>
                        </div>
                        <?php
                    }
                    $print = jobseekercheckLinks('formresume');
                    if ($print) {
                        ?>
                        <div class="js-cp-wrapper">
                            <a class="js-anc color3" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume')),'formresume')); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/add-resume.png"></div><span class="cp-text"><?php echo __('Add Resume', 'js-jobs'); ?></span></a>
                        </div>
                        <?php
                    }
                    $print = jobseekercheckLinks('formcoverletter');
                    if ($print) {
                        ?>
                        <div class="js-cp-wrapper">
                            <a class="js-anc color4" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'addcoverletter')),'formcoverletter')); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/add-cover.png"></div><span class="cp-text"><?php echo __('Add Cover Letter', 'js-jobs'); ?></span></a>
                        </div>
                        <?php
                    }
                    $print = jobseekercheckLinks('mycoverletters');
                    if ($print) {
                        ?>
                        <div class="js-cp-wrapper">
                            <a class="js-anc color5" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),'coverletter')); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/my-cover.png"></div><span class="cp-text"><?php echo __('My Cover Letters', 'js-jobs'); ?></span></a>
                        </div>
                        <?php
                    }
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $print = jobseekercheckLinks('jsregister');
                        if ($print) {
                            ?>
                            <div class="js-cp-wrapper">
                                <a class="js-anc color6" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'user', 'jsjobslt'=>'regjobseeker'))); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/register.png"></div><span class="cp-text"><?php echo __('Register', 'js-jobs'); ?></span></a>
                            </div>
                            <?php
                        } ?>
                   <?php }else{ ?>
                        <div class="js-cp-wrapper"><a class="js-anc color8" href="<?php echo esc_url(admin_url( 'profile.php' )); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/profile.png"></div><span class="cp-text"><?php echo __('Profile', 'js-jobs'); ?></span></a></div>
                   <?php
                   }
                    if (jobseekercheckLinks('jobsloginlogout') ) {
                        if (JSJOBSincluder::getObjectClass('user')->isguest() && (!isset($_SESSION['jsjobs-socialmedia']) && empty($_SESSION['jsjobs-socialmedia']))) {
                            ?>
                            <?php 
                                $thiscpurl = jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'controlpanel', 'jsjobspageid'=>jsjobs::getPageid()));
                                $thiscpurl = jsjobslib::jsjobs_safe_encoding($thiscpurl);
                            ?>
                            <div class="js-cp-wrapper">
                                <a class="js-anc color7" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login', 'jsjobsredirecturl'=>$thiscpurl, 'jsjobspageid'=>jsjobs::getPageid())));?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/login.png"></div><span class="cp-text"><?php echo __('Login', 'js-jobs'); ?></span></a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="js-cp-wrapper">
                                <a class="js-anc color7" href="<?php echo wp_logout_url(get_permalink()); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/logout.png"></div><span class="cp-text"><?php echo __('Logout', 'js-jobs'); ?></span></a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
