<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
jsjobs::$_data['resumeid'] = isset(jsjobs::$_data['resumeid']) ? jsjobs::$_data['resumeid'] : '';

echo wp_kses(JSJOBSformfield::hidden('resume_temp', jsjobs::$_data['resumeid']), JSJOBS_ALLOWED_TAGS);
$msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
    if(! is_admin()){
        update_option( 'jsjobsresumeeditadmin', '' );
        JSJOBSbreadcrumbs::getBreadcrumbs();
        include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
    }
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="resume-wating" class="loading"></div>
    <div id="black_wrapper_jobapply" style="display:none;"></div>
    <div id="warn-message" style="display: none;">
        <span class="close-warnmessage"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/close-icon.png" /></span>
        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/warning-icon.png" />
        <span class="text"></span>
    </div>
    <div id="resume-files-popup-wrapper" style="display:none;">
        <span class="close-resume-files"><?php echo __('Resume Files', 'js-jobs'); ?><img src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/popup-close.png" /></span>
        <div class="resumepopupsectionwrapper">
            <span class="clickablefiles"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/resume/select-file.png"/><?php echo __('Select files', 'js-jobs'); ?></span>
            <span class="headingpopup"><?php echo __('Selected files', 'js-jobs'); ?></span>
            <span id="resume-files-selected"><?php echo __('No file selected', 'js-jobs'); ?></span>
            <div class="resume-filepopup-lowersection-wrapper">
                <div class="allowedfiles"><?php echo __('Files allowed', 'js-jobs') . '&nbsp;(&nbsp;' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_max_files') . '&nbsp;)'; ?></div>
                <div class="allowedextension">(&nbsp;<?php echo esc_html(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_type')); ?>&nbsp;)</div>
                <div class="allowedsize"><?php echo __('Maximum file size', 'js-jobs') . '&nbsp;(&nbsp;' . esc_html(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_size')) . '&nbsp;KB&nbsp;)'; ?></div>
            </div>
        </div>
    </div>
    <div id="jsjobs-wrapper" <?php if (isset($_COOKIE['jsjobs_apply_visitor'])) echo 'style="padding-bottom:63px;"'; ?>>
        <?php $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        if(isset($_COOKIE['jsjobs_apply_visitor'])){
            $msg = __('Job Apply', 'js-jobs');
        }
         ?>
        <div class="page_heading"><?php echo esc_html($msg) . '&nbsp;' . __("Resume", 'js-jobs'); ?></div>
        <?php
        if(isset($_COOKIE['jsjobs_apply_visitor'])){
            $job = jsjobs::$_data['jobinfo'];
            $labelflag = true;
            $labelinlisting = jsjobs::$_configuration['labelinlisting'];
            if ($labelinlisting != 1) {
                $labelflag = false;
            }
            $fields = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(2);
            if ($job->logofilename != "") {
                    $wpdir = wp_upload_dir();
                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
                } else {
                    $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                }
            ?>
            <div class="visitor-apply-job-jobinforamtion-wrapper">
                <div class="visitor-apply-job-jobinforamtion-message">
                    <img src="<?php echo JSJOBS_PLUGIN_URL . '/includes/images/info icon.png';?>" />
                    <?php echo __('You Are Applying On This Job','js-jobs').'!';?>
                </div>
                <div class="jsjobs-shorlisted-wrapper visitor-apply-job-jobinforamtion">
                    <div class="jsdata-icon">
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyid))); ?>"><img class="fir" src="<?php echo esc_url($path); ?>" /></a>
                    </div>
                    <div class="data-bigupper">
                        <div class="big-upper-upper">
                            <span class="headingtext">
                                <span class="title">
                                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$job->jobid))); ?>">
                                        <?php echo esc_html($job->title); ?>
                                    </a>
                                </span>
                            </span>
                            <?php
                                $dateformat = jsjobs::$_configuration['date_format'];
                                echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($job->created))) ?>
                            <span class="buttonu">
                                <?php echo esc_html(__($job->jobtypetitle,'js-jobs')); ?>
                            </span>
                        </div>
                        <div class="big-upper-lower listing-fields">
                            <?php if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('comp_name') == 1){ ?>
                                <div class="custom-field-wrapper">
                                    <?php if ($labelflag) { ?>
                                        <span class="js-bold"><?php echo esc_html(__($fields['company'], 'js-jobs')) . ": "; ?></span>
                                    <?php } ?>
                                    <span class="get-text"><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$job->companyid))); ?>"> <?php echo esc_html($job->companyname); ?></a></span>
                                </div>
                            <?php } ?>
                            <div class="custom-field-wrapper">
                                <?php if ($labelflag) { ?>
                                    <span class="js-bold">
                                        <?php
                                        if(!isset($fields['jobsalaryrange'])){
                                            $fields['jobsalaryrange'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('jobsalaryrange',2);
                                        }
                                        echo esc_html(__($fields['jobsalaryrange'], 'js-jobs')) . ": ";
                                        ?>
                                    </span>
                            <?php } ?>
                                <span class="get-text"> <?php echo wp_kses(JSJOBSincluder::getJSModel('common')->getSalaryRangeView($job->symbol, $job->rangestart, $job->rangeend, $job->rangetype), JSJOBS_ALLOWED_TAGS); ?></span>
                            </div>
                            <div class="custom-field-wrapper">
                                <?php if ($labelflag) { ?>
                                    <span class="js-bold"><?php echo esc_html(__($fields['jobcategory'], 'js-jobs')) . ": "; ?></span>
                            <?php } ?>
                                <span class="get-text"> <?php echo esc_html(__($job->cat_title,'js-jobs')); ?></span>
                            </div>
                            <?php
                            // custom fiedls
                            $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2, 1);
                            foreach ($customfields as $field) {
                                echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 7,$job->params), JSJOBS_ALLOWED_TAGS);
                            }
                            //end
                            ?>
                            <span class="bigupper-jobtotal"><?php echo esc_html($job->noofjobs) . " " . __('Jobs', 'js-jobs'); ?></span>
                        </div>
                    </div>
                    <div class="data-big-lower">
                        <span class="big-lower-left">  <img class="big-lower-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"><?php echo esc_html($job->location); ?></span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <?php
        $resumelayout = JSJOBSincluder::getObjectClass('resumeformlayout');
        //var_dump(jsjobs::$_data[2]);
        $resumelayout->printResume();
        /*if (isset($_COOKIE['jsjobs_apply_visitor'])) {
            echo  '<div class="js-jobs-resume-apply-now-visitor" style="position:absolute; top: 100%;width:100%;z-index:9999;">
                        <div class="js-jobs-resume-apply-now-text">'.__('Please save your resume first then press apply now button','js-jobs').'</div>
                        <div class="js-jobs-resume-apply-now-button">
                            <input id="jsjobs-cancel-btn" type="button" onclick="cancelJobApplyVisitor();" link="javascript:void(0);" value="'.__('Cancel','js-jobs').'" />
                            <input id="jsjobs-login-btn" type="button" onclick="JobApplyVisitor();" link="javascript:void(0);" value="'.__('Apply Now','js-jobs').'" />
                        </div>
                    </div>';

        }*/
        ?>
    </div>
    <div id="ajax-loader" style="display:none"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/loading.gif"></div>
<?php
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
