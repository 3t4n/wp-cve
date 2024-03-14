<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_style('jsjob-jsjobsrating', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsrating.css');
?>
<script >


    function actioncall(jobapplyid, jobid, resumeid, action) {
        if (action == 3) { // folder
            getfolders('resumeaction_' + jobapplyid, jobid, resumeid, jobapplyid);
        } else if (action == 4) { // comments
            getresumecomments('resumeaction_' + jobapplyid, jobapplyid);
        } else if (action == 5) { // email candidate
            mailtocandidate('resumeaction_' + jobapplyid, resumeid, jobapplyid);
        } else {
            var src = '#resumeactionmessage_' + jobapplyid;
            var htmlsrc = '#jsjobs_appliedresume_data_action_message_' + jobapplyid;
            jQuery(src).html("Loading ...");
        }
    }


    function setresumeid(resumeid, action) {
        jQuery('#resumeid').val(resumeid);
        jQuery('#action').val(jQuery("#" + action).val());
        jQuery('jsjobs-form').submit();
    }



    function clsjobdetail(src) {
        jQuery("#" + src).html("");
    }

    function clsaddtofolder(src) {
        jQuery("#" + src).html("");
    }

    function echeck(str) {
        var at = "@";
        var dot = ".";
        var lat = str.indexOf(at);
        var lstr = str.length;
        var ldot = str.indexOf(dot);

        if (str.indexOf(at) == -1)
            return false;
        if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr)
            return false;
        if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr)
            return false;
        if (str.indexOf(at, (lat + 1)) != -1)
            return false;
        if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot)
            return false;
        if (str.indexOf(dot, (lat + 2)) == -1)
            return false;
        if (str.indexOf(" ") != -1)
            return false;
        return true;
    }

    function closeSection() {
        jQuery("div#comments").html('').hide();
    }


    function showPopupAndSetValues(name, title, id) {
        var desc = jQuery("input#cover-letter-text_" + id).val();
        jQuery("div#full_background").css("display", "block");
        jQuery("div#popup-main.coverletter").css("display", "block");
        jQuery("div#popup-main-outer.coverletter").css("display", "block");
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
        jQuery("div#popup_main.coverletter").slideDown('slow');
        jQuery("span#popup_title.coverletter").html(name);
        jQuery("span#popup_coverletter_title.coverletter").html(title);
        jQuery("span#popup_coverletter_desc.coverletter").html(desc);
    }


    function closePopup() {
        jQuery("div#popup-main-outer").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery("span#popup_title.coverletter").html('');
            jQuery("div#popup-main").css("display", "none");
            jQuery("span#popup_coverletter_title.coverletter").html('');
            jQuery("span#popup_coverletter_desc.coverletter").html('');
        }, 700);
    }

    function getResumeDetails(resumeid, salary, exp, inisi, study, available) {
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getResumeDetail', sal: salary, expe: exp, institue: inisi, stud: study, ava: available, wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data).show();
            }
        });

    }

    function getEmailFields(emailid, resumeid) {
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'getEmailFields', em: emailid,resumeid: resumeid, wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                jQuery("div." + resumeid).html(data).show();
            }
        });
    }

</script>
<div id="jsjobsadmin-wrapper">
	<div id="full_background" style="display:none;"></div>
    <div id="popup-main-outer" class="coverletter" style="display:none;">
        <div id="popup-main" class="coverletter" style="display:none;">
            <span class="popup-top"><span id="popup_title" class="coverletter"></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png">
            </span>
            <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                <span id="popup_coverletter_title" class="coverletter"></span>
                <span id="popup_coverletter_desc" class="coverletter"> </span>
            </div>
        </div>
    </div>
</div>
<div id="jsjobsadmin-wrapper">
<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_job')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Job Applied Resume', 'js-jobs') ?>
    </span>
    <div class="jobtitleappliedresume">
        <?php echo esc_html(jsjobs::$_data['jobtitle']); ?>
    </div>

    <?php
    if (!empty(jsjobs::$_data[0]['data'])) {
        foreach (jsjobs::$_data[0]['data'] as $data) {
                        $photo = '';
                        if (isset($data->photo) && $data->photo != '') {
                            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                            $wpdir = wp_upload_dir();
                            $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $data->resumeid . '/photo/' . $data->photo;
                            $padding = "";
                        } else {
                            $photo = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                            $padding = ' style="padding:15px;" ';
                        }
                        ?>
                        <div id="user_<?php echo 1; ?>" class="user-container js-col-lg-12 js-col-md-12 no-padding">
                            <div id="item-data" class="item-data js-row no-margin">
                                <div class="item-icon admin-applied-resume-left js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding">
                                    <div class="job-img">
                                        <img src="<?php echo esc_url($photo); ?>" <?php echo esc_attr($padding); ?> />
                                    </div>
                                    <div id="view-resume">
                                        <a id="view-resume" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid='.$data->appid)); ?>">
                                            <img id="view-resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/jopappliedapplication/white-reume-icon.png" /><?php echo __('Resume', 'js-jobs'); ?>
                                        </a>
                                    </div>
                                    <?php if($data->cletterid != '') { ?>
                                        <div id="view-cover-letter">
                                            <a id="view-cover-letter" href="#" onclick="showPopupAndSetValues('<?php echo esc_js($data->first_name) . ' ' . esc_js($data->last_name); ?>', '<?php echo esc_js($data->clettertitle); ?>',<?php echo esc_js($data->appid); ?>);">
                                                <img id="view-resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>/includes/images/jopappliedapplication/view-coverletter.png"><?php echo __('Cover Letter', 'js-jobs'); ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="item-details js-col-lg-10 js-col-md-10 js-col-xs-12 no-padding">
                                    <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <span class="value">
                                            <?php echo esc_html($data->first_name) . " " . esc_html($data->last_name) ?>
                                        </span>
                                        <div id="applied-resume-ratting">

                                        </div>     
                                        <div class="created-onright">
                                            <span class="heading"><?php echo __('Created','js-jobs') . ': '; ?></span>
                                            <span class="value"><?php echo esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($data->apply_date))); ?></span>
                                        </div>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-12 js-col-md-12 js-col-xs-12">
                                        <span class="heading">
                                        <?php if(!isset(jsjobs::$_data['fields']['application_title'])){
                                                        jsjobs::$_data['fields']['application_title'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('application_title',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['application_title'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html($data->applicationtitle); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading">
                                        <?php if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                                        jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['desired_salary'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html($data->dsalary); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['total_experience'])){
                                                        jsjobs::$_data['fields']['total_experience'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('total_experience',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['total_experience'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html(__($data->total_experience,'js-jobs')); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['heighestfinisheducation'])){
                                                        jsjobs::$_data['fields']['heighestfinisheducation'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('heighestfinisheducation',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['heighestfinisheducation'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html(__($data->educationtitle,'js-jobs')); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['gender'])){
                                                        jsjobs::$_data['fields']['gender'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('gender',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['gender'], 'js-jobs')) . ': '; ?></span><span class="value"><?php
                                            if ($data->gender == 1) {
                                                echo __('Male', 'js-jobs');
                                            } elseif ($data->gender == 2) {
                                                echo __('Female', 'js-jobs');
                                            };
                                            ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12">
                                        <span class="heading"><?php if(!isset(jsjobs::$_data['fields']['iamavailable'])){
                                                        jsjobs::$_data['fields']['iamavailable'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('iamavailable',3);
                                                    }                                    
                                                    echo esc_html(__(jsjobs::$_data['fields']['iamavailable'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html($data->iamavailable) == 1 ? __('Yes', 'js-jobs') : __('No', 'js-jobs'); ?></span>
                                    </div>
                                    <div id="change-padding" class="item-values js-col-lg-12 js-col-md-12 js-col-xs-12">
                                        <span class="heading"><?php echo __('Location', 'js-jobs') . ': '; ?></span><span class="value"><?php echo esc_html($data->location); ?></span>
                                    </div>
                                </div>
                                <div id="<?php echo esc_attr($data->appid); ?>" ></div>
                                <div id="comments" class="<?php echo esc_attr($data->appid); ?>" ></div>
                            </div>
                        </div>
                        <div id="item-actions" class="item-actions js-row no-margin jobapplied-css">
                            <a href="#" id="print-link"  class="js-action-link button applied-a" data-resumeid="<?php echo esc_attr($data->appid); ?>" data-print-url="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>$data->appid, 'issocial'=>'0', 'jsjobspageid'=>jsjobs::getPageid()))); ?>" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/print.png" /><?php echo __('Print', 'js-jobs') ?></a>
                            <a target="_blank" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'pdf', 'jsjobsid'=>$data->appid,'jsjobspageid'=>jsjobs::getPageid()))); ?>" class="js-action-link button applied-a"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/pdf.png" /><?php echo __('PDF', 'js-jobs') ?></a>
                            <a class="js-action-link button applied-a" onclick="getResumeDetails(<?php echo esc_js($data->appid); ?>, '<?php echo esc_js($data->salary); ?>', '<?php echo esc_js($data->total_experience); ?>', '<?php echo esc_js($data->institute); ?>', '<?php echo esc_js($data->institute_study_area); ?>',<?php echo esc_js($data->iamavailable); ?>)"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/jopappliedapplication/details.png" /><?php echo __('Details', 'js-jobs') ?></a>
                        </div>
                        <?php
                        echo wp_kses(JSJOBSformfield::hidden('cover-letter-text_' . $data->appid, $data->cletterdescription), JSJOBS_ALLOWED_TAGS);
          
        } // loop End
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }

        $jobapplyid = jsjobs::$_data[0]['data'][0]->jobapplyid;
        echo wp_kses(JSJOBSformfield::hidden('id', ''), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('jobapplyid', $jobapplyid ), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('task', 'actionresume'), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('action', ''), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('action_status', ''), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('tab_action', ''), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('boxchecked', ''), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('jobid', jsjobs::$_data[0]['ta']), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('ta', jsjobs::$_data[0]['ta']), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS);
        echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('action-appliedresume')), JSJOBS_ALLOWED_TAGS);
    } else {
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
</form>
</div>
</div>
<script >
    jQuery(document).ready(function () {
        jQuery('a#print-link').click(function (e) {
            e.preventDefault();
            var printurl = jQuery(this).attr('data-print-url');
            print = window.open(printurl, 'print_win', 'width=1024, height=800, scrollbars=yes');
        });
    });
</script>
