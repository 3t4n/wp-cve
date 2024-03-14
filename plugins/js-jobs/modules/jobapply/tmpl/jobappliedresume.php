<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('jobapply')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $gender = array(
        (object) array('id' => '', 'text' => __('Search All', 'js-jobs')),
        (object) array('id' => 1, 'text' => __('Male', 'js-jobs')),
        (object) array('id' => 2, 'text' => __('Female', 'js-jobs')));

    $nationality = JSJOBSincluder::getJSModel('country')->getCountriesForCombo();
    $job_type = JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo();
    $heighesteducation = JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo();
    $job_categories = JSJOBSincluder::getJSModel('category')->getCategoriesForCombo();
    $job_salaryrange = JSJOBSincluder::getJSModel('salaryrange')->getJobSalaryRangeForCombo();
    $job_currency = JSJOBSincluder::getJSModel('currency')->getCurrencyResumeAppliedForCombo();
    ?>
    <div id="jsjobs-wrapper">
        <div id="popup-main-outer" class="sendmessage" style="display:none;">
            <div id="popup-main" class="sendmessage" style="display:none;">
                <span class="popup-top">
                    <span id="popup_title" class="message"></span>
                    <img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png" />
                </span>
                <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                    <span id="popup_coverletter_title">
                        <?php echo wp_kses(JSJOBSformfield::text('subject', '', array('class' => 'inputbox', 'placeholder' => __('Message Subject', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
                    </span>
                    <span id="popup_coverletter_desc">
                        <?php wp_editor('', 'jobseekermessage'); ?>
                    </span>
                </div>
                <div class="js-field-wrapper js-row no-margin center" id="popup-bottom-part">
                    <input type="button" class="jsjobs-button" value="<?php echo __('Send Message', 'js-jobs'); ?>" onclick="sendMessage();"/>
                    <input type="hidden" name="jobseekerid" id="jobseekerid" value="" />
                    <input type="hidden" name="resumeid" id="resumeid" value="" />
                    <input type="hidden" name="jobid" id="jobid" value="" />
                </div>
            </div>
        </div>

        <div id="full_background" style="display:none;"></div>
        <div id="popup-main-outer" class="coverletter" style="display:none;">
            <div id="popup-main" class="coverletter" style="display:none;">
                <span class="popup-top"><span id="popup_title" ></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png">
                </span>
                <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                    <span id="popup_coverletter_title" class="coverletter"></span>
                    <span id="popup_coverletter_desc" class="coverletter"></span>
                </div>
            </div>
        </div>
        <div class="page_heading"><?php echo __('Job Applied Resume', 'js-jobs'); ?>
            <?php if (jsjobs::$_data[0]['applied'] != null or jsjobs::$_data[0]['hits'] != null) { ?>
                <span class="applied-resume-count"><?php echo __('Job View', 'js-jobs') . ': ' . jsjobs::$_data[0]['hits'] . ' / ' . __('Applied', 'js-jobs') . ': ' . jsjobs::$_data[0]['applied'] ?></span>
            <?php } ?>
        </div>
        <div id="job-applied-resume-wrapper">
            <?php
            $tab = JSJOBSrequest::getVar('ta',"","1");
            $img = (jsjobs::$_sortorder == 'ASC') ? "001.png" : "002.png";
            ?>
            <div id="job-applied-resume-navebar">
                <ul>
                    <li><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>jsjobs::$_data['jobid'], 'ta'=>$tab, 'sortby'=>jsjobs::$_sortlinks['title'], 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="<?php echo (jsjobs::$_sorton == 'title') ? 'selected' : ''; ?>"><?php if (jsjobs::$_sorton == 'title') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Title', 'js-jobs'); ?></a></li>
                    <li><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>jsjobs::$_data['jobid'], 'ta'=>$tab, 'sortby'=>jsjobs::$_sortlinks['jobtype'], 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="<?php echo (jsjobs::$_sorton == 'jobtype') ? 'selected' : ''; ?>"><?php if (jsjobs::$_sorton == 'jobtype') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Type', 'js-jobs'); ?></a></li>
                    <li><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>jsjobs::$_data['jobid'], 'ta'=>$tab, 'sortby'=>jsjobs::$_sortlinks['company'], 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="<?php echo (jsjobs::$_sorton == 'company') ? 'selected' : ''; ?>"><?php if (jsjobs::$_sorton == 'company') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Company', 'js-jobs'); ?></a></li>
                    <li><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>jsjobs::$_data['jobid'], 'ta'=>$tab, 'sortby'=>jsjobs::$_sortlinks['salary'], 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="<?php echo (jsjobs::$_sorton == 'salary') ? 'selected' : ''; ?>"><?php if (jsjobs::$_sorton == 'salary') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Salary Range', 'js-jobs'); ?></a></li>
                    <li><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>jsjobs::$_data['jobid'], 'ta'=>$tab, 'sortby'=>jsjobs::$_sortlinks['posted'], 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="<?php echo (jsjobs::$_sorton == 'posted') ? 'selected' : ''; ?>"><?php if (jsjobs::$_sorton == 'posted') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Posted', 'js-jobs'); ?></a></li>
                </ul>
            </div>
            <div id="job-applied-resume-jobtitle"><?php echo esc_html(jsjobs::$_data[0]['jobtitle']); ?></div>
            <?php $ta = JSJOBSrequest::getVar('ta', null, 1); ?>
            <div id="job-applied-resume-inner-navebar">
                <a class="export-all" target="_blank" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'export', 'task'=>'exportallresume', 'action'=>'jsjobtask', 'jobid'=>jsjobs::$_data['jobid'], 'jsjobspageid'=>jsjobs::getPageid())),'exportall-resume')); ?>">
                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/export.png">
                    <?php echo __('Export All', 'js-jobs'); ?>
                </a>
            </div>
            <?php
            if (isset(jsjobs::$_data[0]['data']) && !empty(jsjobs::$_data[0]['data'])) {
                foreach (jsjobs::$_data[0]['data'] AS $resume) { ?>
                        <div id="job-applied-resume">
                            <div class="jobs-upper-wrapper">
                                <div class="job-upper-left">
                                    <div class="job-img">
                                        <?php
                                        if (isset($resume->photo) && $resume->photo != '') {
                                           $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                                           $wpdir = wp_upload_dir();
                                           $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->appid . '/photo/' . $resume->photo;
                                        } else {
                                            $path = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                                        }
                                        ?>
                                        <img src="<?php echo esc_url($path); ?>">
                                    </div>
                                    <div class="anchor">
                                        <a class="view-resume" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jobid'=>$resume->id, 'jsjobsid'=>$resume->resumealiasid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/white-reume-icon.png">
                                            <span><?php echo __('Resume', 'js-jobs') ?></span>
                                        </a>
                                        <?php if($resume->cletterid != '') { ?>
                                            <a class="view-coverletter" href="#" onclick="showPopupAndSetValues('<?php echo esc_js($resume->first_name) . ' ' . esc_js($resume->middle_name) . ' ' . esc_js($resume->last_name); ?>', '<?php echo esc_js($resume->clettertitle); ?>', '<?php echo esc_js($resume->appid); ?>');">
                                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/view-coverletter.png">
                                                <span><?php echo __('Cover Letter', 'js-jobs') ?></span>
                                            </a>
                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="job-detail">
                                    <div class="job-detail-upper">
                                        <div class="job-detail-upper-left">
                                            <span class="job-title"><?php echo esc_html($resume->first_name) . '&nbsp' . esc_html($resume->middle_name) . '&nbsp' . esc_html($resume->last_name); ?></span>
                                        </div>
                                        <div class="job-detail-upper-right">
                                            <span class="created"><?php echo __('Created', 'js-jobs') . ':&nbsp'; ?></span>
                                            <span class="job-date"><?php echo esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($resume->apply_date))); ?></span>
                                        </div>
                                    </div>
                                    <div class="job-detail-lower">
                                        <div class="job-detail-lower-left">
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['application_title'])){
                                                        jsjobs::$_data['fields']['application_title'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('application_title',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['application_title'], 'js-jobs')) . ': '; ?>
                                                </span>
                                                <span class="get-text"><?php echo esc_html($resume->applicationtitle); ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                                        jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['desired_salary'], 'js-jobs')) . ': '; ?></span>
                                                <span class="get-text"><?php echo esc_html($resume->dsalary); ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['gender'])){
                                                        jsjobs::$_data['fields']['gender'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('gender',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['gender'], 'js-jobs')) . ':&nbsp;'; ?></span>
                                                <span class="get-text"><?php if($resume->gender == 1) echo __('Male', 'js-jobs'); else if($resume->gender == 2) echo  __('Female', 'js-jobs'); else echo __('Does not matter', 'js-jobs'); ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="heading"><?php echo __('Location', 'js-jobs') . ':&nbsp;'; ?></span>
                                                <span class="get-text"><?php echo esc_html($resume->location); ?></span>
                                            </div>
                                        </div>
                                        <div class="job-detail-lower-right">
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['total_experience'])){
                                                        jsjobs::$_data['fields']['total_experience'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('total_experience',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['total_experience'], 'js-jobs')) . ':&nbsp;'; ?></span>
                                                <span class="get-text"><?php echo esc_html(__($resume->total_experience,'js-jobs')); ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['heighestfinisheducation'])){
                                                        jsjobs::$_data['fields']['heighestfinisheducation'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('heighestfinisheducation',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['heighestfinisheducation'], 'js-jobs')) . ':&nbsp;'; ?></span>
                                                <span class="get-text"><?php echo esc_html(__($resume->educationtitle,'js-jobs')); ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="heading">
                                                <?php
                                                    if(!isset(jsjobs::$_data['fields']['iamavailable'])){
                                                        jsjobs::$_data['fields']['iamavailable'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('iamavailable',3);
                                                    }
                                                    echo esc_html(__(jsjobs::$_data['fields']['iamavailable'], 'js-jobs')) . ':&nbsp;'; ?></span>
                                                <?php
                                                $availableStatus;
                                                if ($resume->iamavailable == 1) {
                                                    $availableStatus = 'Yes';
                                                } else {
                                                    $availableStatus = 'No';
                                                }
                                                ?>
                                                <span class="get-text"><?php echo esc_html($availableStatus); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($resume->comments != ''){ ?>
                                        <div class="notes">
                                            <?php echo wp_kses($resume->comments, JSJOBS_ALLOWED_TAGS); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="<?php echo esc_attr($resume->appid); ?>" ></div>
                            <div id="comments" class="<?php echo esc_attr($resume->appid); ?>" style="display:none" ></div>
                            <div class="jobs-lower-wrapper">
                                <div class="buttons">
                                    <a href="#" id="print-link" data-socialprofile="0"  class="action-links" data-resumeid="<?php echo esc_attr($resume->appid); ?>" data-print-url="<?php echo esc_attr(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>$resume->appid, 'issocial'=>'0', 'jsjobspageid'=>jsjobs::getPageid()))); ?>" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/print.png" /><?php echo __('Print', 'js-jobs') ?></a>
                                    <a target="_blank" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'pdf', 'jsjobsid'=>$resume->appid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="action-links"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/pdf.png" /><?php echo __('PDF', 'js-jobs') ?></a>
                                    <a class="action-links" target="_blank" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'export', 'task'=>'exportresume', 'action'=>'jsjobtask', 'jobid'=>$resume->id,'jsjobsid'=>$resume->appid, 'jsjobspageid'=>jsjobs::getPageid())),'export-resume')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/export.png" /><?php echo __('Export', 'js-jobs') ?></a>
                                    <a class="action-links" onclick="getResumeDetails(<?php echo esc_js($resume->appid); ?>, '<?php echo esc_js($resume->salary); ?>', '<?php echo esc_js($resume->total_experience); ?>', '<?php echo esc_js($resume->institute); ?>', '<?php echo esc_js($resume->institute_study_area); ?>',<?php echo esc_js($resume->iamavailable); ?>)"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/details.png" /><?php echo __('Details', 'js-jobs') ?></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    echo wp_kses(JSJOBSformfield::hidden('cover-letter-text_' . $resume->appid, esc_html($resume->cletterdescription)), JSJOBS_ALLOWED_TAGS);
                }
                if (jsjobs::$_data[1]) {
                    echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
                }
            } else {
                JSJOBSlayout::getNoRecordFound();
            }
            ?>
        </div>
    </div>
<?php
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
