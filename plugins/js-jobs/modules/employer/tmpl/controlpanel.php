<?php
if (!defined('ABSPATH'))
{
    die('Restricted Access');
}?>
<div id="jsjobs-main-up-wrapper">
    <?php
    if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
        $msgkey = JSJOBSincluder::getJSModel('employer')->getMessagekey();
        JSJOBSMessages::getLayoutMessage($msgkey);
    } else {
        JSJOBSMessages::getLayoutMessage('user');
    }
    JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="control-pannel-header">
            <span class="heading"><?php echo __('Control Panel', 'js-jobs'); ?></span>
            <?php 
            $guestflag = false;
            $visitorallowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_conrolpanel');
            $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
            
            if($isguest == true && $visitorallowed == true){
                $guestflag = true;
            }
            if($isguest == false && $isouruser == false && $visitorallowed == true){
                $guestflag = true;
            }
             ?>
        </div>
        <div id='employer-control-pannel-wrapper'>
            <div class="cp-topbox">
                
                <?php
                $print = employerchecklinks('myjobs');
                if ($print) {
                    ?>
                    <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color1" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs')),"job")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/my-job.png"><span class="cp-text"><?php echo __('My Jobs', 'js-jobs'); ?></span></a>
                    </div>
                    <?php
                }
                $print = employerchecklinks('formjob');
                if ($print) {
                    ?>
                    <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color2" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),"formjob")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/add-job.png"><span class="cp-text"><?php echo __('Add Job', 'js-jobs'); ?></span></a>
                    </div>


                    <?php
                }
                $print = employerchecklinks('resumesearch');
                if ($print) {
                    ?>
                    <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color3" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesearch')),"resumesearch")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/search-resume.png"><span class="cp-text"><?php echo __('Resume Search', 'js-jobs'); ?></span>
                        </a>
                    </div>
                    <?php
                }
                $print = employerchecklinks('emresumebycategory');
                if ($print) {
                    ?>
                    <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs-cp-box">
                        <a class="jsmenu color4" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumebycategory')),"resumebycategory")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/job-categories.png"><span class="cp-text"><?php echo __('Resumes By Categories', 'js-jobs'); ?></span></a>
                    </div>
                <?php } ?>
            </div>
            <div class="jsjobs-cp-border"></div>
                
            <?php
            if(employercheckLinks('mycompanies') || employercheckLinks('formcompany') || employercheckLinks('mydepartment') || employercheckLinks('formdepartment') || employercheckLinks('myfolders') || employercheckLinks('newfolders') || employercheckLinks('empmessages') || employercheckLinks('my_resumesearches') || employercheckLinks('empresume_rss') || employercheckLinks('empregister') || employercheckLinks('emploginlogout') ){
            ?>
                <div class="jsjobs-bottombox">
                    <?php
                    $print = employerchecklinks('mycompanies');
                    if ($print) {
                        ?>
                            <div class="js-cp-wrapper"><a class="js-anc color1" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'mycompanies')),"company")); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/companies.png"></div><span class="cp-text"><?php echo __('My Companies', 'js-jobs'); ?></span></a></div>
                        <?php
                    }
                    $print = employerchecklinks('formcompany');
                    if ($print) {
                        ?>
                            <div class="js-cp-wrapper"><a class="js-anc color2" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),"formcompany")); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/add-company.png"></div><span class="cp-text"><?php echo __('Add Company', 'js-jobs'); ?></span></a></div>
                        
                        <?php
                    }

                    $print = employerchecklinks('mydepartment');
                    if ($print) {
                        ?>
                            <div class="js-cp-wrapper"><a class="js-anc color3" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments')),"department")); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/department.png"></div><span class="cp-text"><?php echo __('My Departments', 'js-jobs'); ?></span></a></div>
                        
                        <?php
                    }
                    $print = employerchecklinks('formdepartment');
                    if ($print) {
                        ?>
                            <div class="js-cp-wrapper"><a class="js-anc color4" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),"save-department")); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/add-department.png"></div><span class="cp-text"><?php echo __('Add Department', 'js-jobs'); ?></span></a></div>
                        
                        <?php
                    }

                    if (employercheckLinks('emploginlogout')) {
                        if (JSJOBSincluder::getObjectClass('user')->isguest() && (!isset($_SESSION['jsjobs-socialmedia']) && empty($_SESSION['jsjobs-socialmedia']))) {
                            ?>
                            <?php 
                                $thiscpurl = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel')),"dashboard");
                                $thiscpurl = jsjobslib::jsjobs_safe_encoding($thiscpurl);
                            ?>
                                <div class="js-cp-wrapper"><a class="js-anc color5" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login', 'jsjobsredirecturl'=>$thiscpurl)));?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/login.png"></div><span class="cp-text"><?php echo __('Login', 'js-jobs'); ?></span></a></div>
                            
                            <?php
                        } else {
                                ?>
                                    <div class="js-cp-wrapper"><a class="js-anc color6" href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/logout.png"></div><span class="cp-text"><?php echo __('Logout', 'js-jobs'); ?></span></a></div>
                                
                            <?php
                        }
                    }
                    if(JSJOBSincluder::getObjectClass('user')->isguest()){
                        $print = employerchecklinks('empregister');
                            if ($print) {
                                ?>
                                    <div class="js-cp-wrapper"><a class="js-anc color7" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'user', 'jsjobslt'=>'regemployer'))); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/jobseeker/register.png"></div><span class="cp-text"><?php echo __('Register', 'js-jobs'); ?></span></a></div>
                                <?php 
                            }
                    }else{ ?>
                            <div class="js-cp-wrapper"><a class="js-anc color8" href="<?php echo esc_url(admin_url( 'profile.php' )); ?>"><div class="jsimg"><img class="js-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/employer/profile.png"></div><span class="cp-text"><?php echo __('Profile', 'js-jobs'); ?></span></a></div>
                        
                   <?php }
                    ?>
                </div>
            <?php } ?>
        </div>  
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
