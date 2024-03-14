<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $config_array = jsjobs::$_data['config'];
    function getDataRow($title, $value) {
        $html = '<div class="detail-wrapper"  >
                        <span class="heading">' . $title . ': </span>
                        <span class="txt">' . $value . '</span>
                </div>';
        return $html;
    }

    function getHeading2($value) {
        $html = '<div class="heading2">' . $value . '</div>';
        return $html;
    }

    function getPeragraph($value) {
        $html = '<div class="peragraph">' . $value . '</div>';
        return $html;
    }
    echo '<meta property="description" content="'.esc_attr(jsjobs::$_data[0]->metadescription).'"/>';
    echo '<meta property="keywords" content="'.esc_attr(jsjobs::$_data[0]->metakeywords).'"/>';
    ?>
    <div id="jsjobs-wrapper"> 
        <div id="jsjob-popup-background"></div>
        <div id="jsjobs-listpopup">
            <span class="popup-title"><span class="title"></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png"></span>
            <div class="jsjob-contentarea"></div>
        </div>
        <div class="page_heading"><?php echo __('View Job', 'js-jobs'); ?></div>
        <div id="view-job-wrapper">
            <div class="top">
                <div class="inner-wrapper">
                    <div class="jobname"><?php echo esc_html(jsjobs::$_data[0]->title); ?></div>
                    <div class="jobdetail">
                        <span class="get-text"><?php
                            if($config_array['comp_name'] == 1){ ?>
                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>jsjobs::$_data[0]->companyid))); ?>">
                                    <span class="comp-name"><?php echo esc_html(jsjobs::$_data[0]->companyname); ?></span>
                                </a><?php
                            }
                            $dateformat = jsjobs::$_configuration['date_format'];
                            $curdate = date_i18n($dateformat);
                            ?>
                        </span>
                        <span>
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png">
                            <span class="city">
                                <?php echo wp_kses(jsjobs::$_data[0]->multicity, JSJOBS_ALLOWED_TAGS); ?>
                            </span>
                        </span>
                        <span class="agodays">
                            <?php echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime(jsjobs::$_data[0]->startpublishing))); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="btn-div">
                <a class="btn blue" href="#heading_overview"><?php echo __('Overview', 'js-jobs'); ?></a>
                <a class="btn" href="#heading_requirements"><?php echo __('Requirements', 'js-jobs'); ?></a>
                <a class="btn" href="#heading_jobstatus"><?php echo __('Job Status', 'js-jobs'); ?></a>
                <?php if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->longitude != '' && jsjobs::$_data[0]->latitude != '') { ?>
                    <a class="btn" href="#heading_location"><?php echo __('Location', 'js-jobs'); ?></a>
                <?php } ?>
            </div>
            <div class="main">
                <?php
                if (isset(jsjobs::$_data[2])) {
                    ?>
                    <div id="heading_overview" class="heading1"  ><?php echo __('Overview', 'js-jobs'); ?></div>
                    <div class="left">
                        <?php
                        foreach (jsjobs::$_data[2] AS $key => $fields) {
                            switch ($fields->field) {
                                case 'jobtype':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->jobtypetitle,'js-jobs')), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'duration':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->duration), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'jobsalaryrange':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->salary), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'department':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->departmentname), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'jobcategory':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->cat_title,'js-jobs')), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'jobshift':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->shifttitle,'js-jobs')), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'zipcode':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->zipcode), JSJOBS_ALLOWED_TAGS);
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                            echo wp_kses(getDataRow(__('Posted', 'js-jobs'), date_i18n($dateformat, jsjobslib::jsjobs_strtotime(jsjobs::$_data[0]->startpublishing))), JSJOBS_ALLOWED_TAGS);
                    }
                    if (isset(jsjobs::$_data[2])) {
                        ?>
                        <div id="heading_requirements" class="heading1"  ><?php echo __('Requirements', 'js-jobs'); ?></div>
                        <?php
                        if(jsjobs::$_data[0]->iseducationminimax == 0){
                            $edutitle = jsjobs::$_data[0]->mineducationtitle .'-'. __(jsjobs::$_data[0]->maxeducationtitle,'js-jobs');
                        }else{
                            if(jsjobs::$_data[0]->educationminimax == 2){
                                $edutitle = __('Maximum Education','js-jobs').' '. __(jsjobs::$_data[0]->educationtitle,'js-jobs');
                            }else{
                                $edutitle = __('Minimum Education','js-jobs').' '. __(jsjobs::$_data[0]->educationtitle,'js-jobs');
                            }
                        }
                        if(jsjobs::$_data[0]->isexperienceminimax == 0){
                            $exptitle = jsjobs::$_data[0]->minexperiencetitle .'-'. __(jsjobs::$_data[0]->maxexperiencetitle,'js-jobs');
                        }else{
                            if(jsjobs::$_data[0]->experienceminimax == 2){
                                $exptitle = __('Maximum Experience','js-jobs').' '. __(jsjobs::$_data[0]->experiencetitle,'js-jobs');
                            }else{
                                $exptitle = __('Minimum Experience','js-jobs').' '. __(jsjobs::$_data[0]->experiencetitle,'js-jobs');
                            }
                        }

                        foreach (jsjobs::$_data[2] AS $fields) {
                            switch ($fields->field) {
                                
                                case 'heighesteducation':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), $edutitle), JSJOBS_ALLOWED_TAGS);
                                    echo wp_kses(getDataRow(__('Degree title', 'js-jobs'), jsjobs::$_data[0]->degreetitle), JSJOBS_ALLOWED_TAGS);
                                    break;
                                
                                case 'experience':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), $exptitle), JSJOBS_ALLOWED_TAGS);
                                    if(jsjobs::$_data[0]->experiencetext){
                                        echo wp_kses(getDataRow(__('Other experience', 'js-jobs'), jsjobs::$_data[0]->experiencetext), JSJOBS_ALLOWED_TAGS);
                                    }
                                    break;
                                case 'age':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->agefrom,'js-jobs') . ' ' . __(jsjobs::$_data[0]->ageto,'js-jobs')), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'workpermit':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->workpermittitle), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'requiredtravel':
                                    $value = JSJOBSincluder::getJSModel('common')->getRequiredTravelValue(jsjobs::$_data[0]->requiredtravel);
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), $value), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'gender':
                                    if(jsjobs::$_data[0]->gender == 0){
                                        $value = __('Does not matter', 'js-jobs');
                                    }elseif(jsjobs::$_data[0]->gender == 1){
                                        $value = __('Male', 'js-jobs');
                                    }elseif(jsjobs::$_data[0]->gender == 2){
                                        $value = __('Female', 'js-jobs');
                                    }
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), $value), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'careerlevel':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->careerleveltitle), JSJOBS_ALLOWED_TAGS);
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params), JSJOBS_ALLOWED_TAGS);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                    }
                    if (isset(jsjobs::$_data[2])) {
                        ?>
                        <div id="heading_jobstatus" class="heading1"  ><?php echo __('Job Status', 'js-jobs'); ?></div>
                        <?php
                        foreach (jsjobs::$_data[2] AS $fields) {
                            switch ($fields->field) {
                                case 'jobstatus':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), __(jsjobs::$_data[0]->jobstatustitle,'js-jobs')), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'startpublishing':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), date_i18n($dateformat, jsjobslib::jsjobs_strtotime(jsjobs::$_data[0]->startpublishing))), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'noofjobs':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), jsjobs::$_data[0]->noofjobs), JSJOBS_ALLOWED_TAGS);
                                    break;
                                case 'stoppublishing':
                                    echo wp_kses(getDataRow(__($fields->fieldtitle, 'js-jobs'), date_i18n($dateformat, jsjobslib::jsjobs_strtotime(jsjobs::$_data[0]->stoppublishing))), JSJOBS_ALLOWED_TAGS);
                                    break;
                                default:
                                    if($fields->isuserfield == 1){
                                        echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($fields, 2, jsjobs::$_data[0]->params), JSJOBS_ALLOWED_TAGS);
                                        unset(jsjobs::$_data[2][$key]);
                                    }
                                    break;
                            }
                        }
                    }
                    ?>
                </div>
                <div class="right">
                    <div class="companywrapper">
                        <?php
                        if (jsjobs::$_data[0]->logofilename != "") {
                            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                            $wpdir = wp_upload_dir();
                            $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . jsjobs::$_data[0]->companyid . '/logo/' . jsjobs::$_data[0]->logofilename;
                        } else {
                            $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                        }
                        ?>
                        <div class="company-img">
                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>jsjobs::$_data[0]->companyid))); ?>">
                                <img src="<?php echo esc_url($path); ?>">
                            </a>
                        </div>
                        <div class="copmany-detail"><?php
                            if($config_array['comp_name']){ ?>
                               <span class="heading"><?php echo esc_html(jsjobs::$_data[0]->companyname); ?></span><?php
                            } 
                            if($config_array['comp_show_url']){ ?>
                                <a href="<?php echo esc_url(jsjobs::$_data[0]->companyurl); ?>" class="url"><?php echo esc_html(jsjobs::$_data[0]->companyurl); ?></a><?php 
                            }if($config_array['comp_city']){ ?>
                                <span class="address">
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"><?php echo wp_kses(JSJOBSIncluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data[0]->compcity), JSJOBS_ALLOWED_TAGS); ?>
                                </span><?php
                            } ?>

                            <div id="job-info-sociallink">
                                <?php
                                if (!empty(jsjobs::$_data[0]->facebook)) {
                                    echo '<a href="' . esc_url(jsjobs::$_data[0]->facebook) . '" target="_blank"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scround/fb.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->twitter)) {
                                    echo '<a href="' . esc_url(jsjobs::$_data[0]->twitter) . '" target="_blank"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scround/twitter.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->googleplus)) {
                                    echo '<a href="' . esc_url(jsjobs::$_data[0]->googleplus) . '" target="_blank"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scround/gmail.png"/></a>';
                                }
                                if (!empty(jsjobs::$_data[0]->linkedin)) {
                                    echo '<a href="' . esc_url(jsjobs::$_data[0]->linkedin) . '" target="_blank"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scround/in.png"/></a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->longitude != '' && jsjobs::$_data[0]->latitude != '') { ?>
                <div id="heading_location" class="heading1"  ><?php echo __('Location', 'js-jobs'); ?></div>
                <?php
                foreach (jsjobs::$_data[2] AS $fields) {
                    switch ($fields->field) {
                        case 'city':
                            echo wp_kses(getDataRow(__('Address', 'js-jobs'), jsjobs::$_data[0]->multicity), JSJOBS_ALLOWED_TAGS);
                            break;
                    }
                }
                ?>
                <div class="js-col-md-12 js-form-value"><div id="map_container" style="display:inline-block; width:100%;"><div id="map"></div></div></div>
                <?php
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->description != '') {
                echo wp_kses(getHeading2(__('Description', 'js-jobs')), JSJOBS_ALLOWED_TAGS);
                echo wp_kses(getPeragraph(jsjobs::$_data[0]->description), JSJOBS_ALLOWED_TAGS);
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->agreement != '') {
                echo wp_kses(getHeading2(__('Agreement', 'js-jobs')), JSJOBS_ALLOWED_TAGS);
                echo wp_kses(getPeragraph(jsjobs::$_data[0]->agreement), JSJOBS_ALLOWED_TAGS);
            }

            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->qualifications != '') {
                echo wp_kses(getHeading2(__('Qualifications', 'js-jobs')), JSJOBS_ALLOWED_TAGS);
                echo wp_kses(getPeragraph(jsjobs::$_data[0]->qualifications), JSJOBS_ALLOWED_TAGS);
            }
            if (isset(jsjobs::$_data[2]) && jsjobs::$_data[0]->prefferdskills != '') {
                echo wp_kses(getHeading2(__('Preferred Skills', 'js-jobs')), JSJOBS_ALLOWED_TAGS);
                echo wp_kses(getPeragraph(jsjobs::$_data[0]->prefferdskills), JSJOBS_ALLOWED_TAGS);
            }
            ?>
            <div class="apply"><?php
                if($config_array['showapplybutton'] == 1){  
                    if(jsjobs::$_data[0]->jobapplylink == 1 && !empty(jsjobs::$_data[0]->joblink)){
                        if(!jsjobslib::jsjobs_strstr('http',jsjobs::$_data[0]->joblink)){
                            jsjobs::$_data[0]->joblink = 'http://'.jsjobs::$_data[0]->joblink;
                        } ?>
                        <a class="apply-btn" href= "<?php echo esc_url(jsjobs::$_data[0]->joblink) ;?>" target="_blank" ><?php echo __('Apply Now','js-jobs'); ?></a><?php 
                    }elseif(!empty($config_array['applybuttonredirecturl'])){ 
                        if(!jsjobslib::jsjobs_strstr('http',$config_array['applybuttonredirecturl'])){
                            $joblink = 'http://'.$config_array['applybuttonredirecturl'];
                        }else{
                            $joblink = $config_array['applybuttonredirecturl'];
                        } ?>
                        <a class="apply-btn" href= "<?php echo esc_url($joblink); ?>" target="_blank" ><?php echo __('Apply Now','js-jobs'); ?></a><?php 
                    }else{ ?>
                        <a class="apply-btn" onclick="getApplyNowByJobid(<?php echo esc_js(jsjobs::$_data[0]->id); ?>)"><?php echo __('Apply This Job', 'js-jobs'); ?></a><?php
                    }
                }?>    
            </div>
        </div>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
