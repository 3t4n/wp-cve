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
    $labelflag = true;
    $labelinlisting = jsjobs::$_configuration['labelinlisting'];
    if ($labelinlisting != 1) {
        $labelflag = false;
    } ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('My Applied Jobs', 'js-jobs'); ?></div>
        <div id="my-applied-jobs-wrraper">
            <?php
            if (jsjobs::$_sortorder == 'ASC')
                $img = "001.png";
            else
                $img = "002.png";
            ?>
            <div id="my-applied-jobs-navebar">
                <ul>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['title'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                        if (jsjobs::$_sorton == 'title') {
                            echo 'selected';
                        }
                        ?>"><?php if (jsjobs::$_sorton == 'title') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Title', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['jobtype'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                       if (jsjobs::$_sorton == 'jobtype') {
                           echo 'selected';
                       }
                       ?>"><?php if (jsjobs::$_sorton == 'jobtype') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Type', 'js-jobs'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['jobstatus'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                       if (jsjobs::$_sorton == 'jobstatus') {
                           echo 'selected';
                       }
                        ?>"><?php if (jsjobs::$_sorton == 'jobstatus') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Status', 'js-jobs'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['company'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                           if (jsjobs::$_sorton == 'company') {
                               echo 'selected';
                           }
                           ?>"><?php if (jsjobs::$_sorton == 'company') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Company', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['salary'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                           if (jsjobs::$_sorton == 'salary') {
                               echo 'selected';
                           }
                           ?>"><?php if (jsjobs::$_sorton == 'salary') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Salary Range', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'myappliedjobs', 'sortby'=>jsjobs::$_sortlinks['posted'], 'jsjobspageid'=>jsjobs::getPageid())));?>" class="<?php
                        if (jsjobs::$_sorton == 'posted') {
                            echo 'selected';
                        }
                        ?>"><?php if (jsjobs::$_sorton == 'posted') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Posted', 'js-jobs'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    <?php
    if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
        foreach (jsjobs::$_data[0] AS $appliedJobs) {
            if ($appliedJobs->logofilename != "") {
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $wpdir = wp_upload_dir();
                $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $appliedJobs->companyid . '/logo/' . $appliedJobs->logofilename;
            } else {
                $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
            }
            ?>
                <div id="my-applied-jobs-wrraper">
                    <div id="my-applied-jobs-list">
                        <div class="jobs-upper-wrapper">
                            <div class="job-img">
                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$appliedJobs->companyid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                    <img src="<?php echo esc_url($path); ?>">
                                </a>
                            </div>
                            <div class="job-detail">
                                <div class="job-detail-upper">
                                    <div class="job-detail-upper-left">                                         
                                        <span class="job-title"><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$appliedJobs->jobid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><?php echo esc_html($appliedJobs->title); ?></a></span>
                                    </div>
                                    <div class="job-detail-upper-right">
                                        <span class="job-date"><?php echo __('Applied Date', 'js-jobs') . ': ' . esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($appliedJobs->apply_date))); ?></span>
                                        <span class="time-of-job"><?php echo esc_html(__($appliedJobs->jobtypetitle,'js-jobs')); ?></span>
                                    </div>
                                </div>
                                <div class="job-detail-lower listing-fields">
                                    <?php if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('comp_name') == 1){ ?>
                                        <div class="custom-field-wrapper">
                                            <?php if ($labelflag) { ?>
                                                <span class="js-bold"><?php echo esc_html(__(jsjobs::$_data['fields']['company'], 'js-jobs')) . ': '; ?></span>
                                            <?php } ?>
                                            <span class="get-text">
                                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$appliedJobs->companyid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                            <?php echo esc_html($appliedJobs->companyname); ?>
                                                </a>
                                            </span>
                                        </div>
                                    <?php }?>
                                    <div class="custom-field-wrapper">
                                    <?php if ($labelflag) { ?>
                                            <span class="js-bold"><?php echo esc_html(__(jsjobs::$_data['fields']['jobcategory'], 'js-jobs')) . ': '; ?></span>
                                    <?php } ?>
                                        <span class="get-text"><?php echo esc_html(__($appliedJobs->cat_title,'js-jobs')); ?></span>
                                    </div>
                                    <div class="custom-field-wrapper">
                                    <?php if ($labelflag) { ?>
                                            <span class="js-bold">
                                                <?php 
                                                if(!isset(jsjobs::$_data['fields']['jobsalaryrange'])){
                                                    jsjobs::$_data['fields']['jobsalaryrange'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('jobsalaryrange',2);
                                                }
                                                echo esc_html(__(jsjobs::$_data['fields']['jobsalaryrange'], 'js-jobs')) . ": "; 
                                                ?>
                                            </span>
                                    <?php } ?>
                                        <span class="get-text"><?php echo esc_html($appliedJobs->salary); ?></span>
                                                        </div>
                                <?php
                                // custom fiedls 
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2, 1);
                                foreach ($customfields as $field) {
                                    echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 3,$appliedJobs->params), JSJOBS_ALLOWED_TAGS);
                                }
                                //end
                                 ?>
                                </div>
                            </div>
                        </div>
                        <div class="jobs-lower-wrapper">
                            <div class="jobs-lower-wrapper-left">
                                <?php if ($appliedJobs->location != '') { ?>
                                    <span class="company-address"><img id=location-img  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"/><?php echo esc_html($appliedJobs->location); ?></span>
                                <?php } ?>                                                                                      
                            </div>
                            <a class="applied-info-button" onclick="toggleCommentsDivById('detail_<?php echo esc_js($appliedJobs->jobid); ?>')"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/ad_resume.png"/><?php echo __('Applied Info', 'js-jobs'); ?></a>
                        </div>
                        <div class="job-detail-bottom-part" id="detail_<?php echo esc_attr($appliedJobs->jobid); ?>">
                            <div class="full-width" id="full-width-top">
                                <span class="heading"><?php echo __('Resume Title', 'js-jobs') . ': '; ?></span>
                                <span class="get-text">
                                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$appliedJobs->resumeid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                        <?php 
                                        echo esc_html($appliedJobs->first_name);
                                        if($appliedJobs->middle_name != ''){
                                            echo ' '.esc_html($appliedJobs->middle_name);
                                        }
                                        echo ' '.esc_html($appliedJobs->last_name);
                                        ?>
                                    </a>
                                    <?php
                                        if($appliedJobs->application_title != ''){
                                            echo '('.esc_html($appliedJobs->application_title).')';
                                        }
                                    ?>
                                </span>	
                            </div>
                            <div class="full-width">
                                <span class="heading"><?php echo __('Cover Letter', 'js-jobs') . ': '; ?></span>
                                <span class="get-text">
                                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'viewcoverletter', 'jsjobsid'=>$appliedJobs->coverletterid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                        <?php echo esc_html($appliedJobs->coverlettertitle); ?>
                                    </a>
                                </span>	
                            </div>
                        </div>
                    </div>	
                </div>

            <?php
        }
        if (jsjobs::$_data[1]) {
            echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
        }
    } else {
        JSJOBSlayout::getNoRecordFound();
    }
    ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
