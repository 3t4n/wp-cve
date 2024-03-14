<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
do_action('admin_enqueue_scripts');
?>
<script >
            google.charts.load('current', {'packages':['corechart']});
            google.setOnLoadCallback(drawStackChartHorizontal);
            function drawStackChartHorizontal() {
            var data = google.visualization.arrayToDataTable([
            <?php
            echo wp_kses_post(jsjobs::$_data['stack_chart_horizontal']['title']) . ',';
            echo wp_kses_post(jsjobs::$_data['stack_chart_horizontal']['data']);
            ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    var options = {
                    curveType: 'function',
                            height:300,
                            legend: { position: 'top', maxLines: 3 },
                            pointSize: 4,
                            isStacked: true,
                            focusTarget: 'category',
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.LineChart(document.getElementById("stack_chart_horizontal"));
                    chart.draw(view, options);
            }
</script>
<div id="jsjobsadmin-wrapper">
    <div id="full_background" style="display:none;" onclick="closePopupVersioChanges()" ></div>
    <div id="popup_main" class="jsjobs-vesrion-changes-popup" style="display:none;">
        <span class="popup-top"><span id="popup_title" >
            <?php echo __("Your Version","js-jobs").':&nbsp;'?>
            <?php echo JSJOBSIncluder::getJSModel('configuration')->getConfigValue('versioncode');?>
        </span><img id="popup_cross" alt="popup cross" onclick="closePopupVersioChanges()" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png"/>
        </span>
        <div class="jsjobs-version-changes-popup-data" >
            <?php
                if(!empty($response)) {?>
                    <?php
                    $version_count = 0;
                    foreach ($response as $version => $changes) {
                        if(isset($changes['pro']) && !empty($changes['pro'])){
                        ?>
                        <div class="jsjobs-version-changes-popup-version-title version_count_num_<?php echo esc_attr($version_count);?>" > <?php echo esc_attr($version); ?></div>
                        <?php
                            if($version_count == 4){
                                $version_count = 0;
                            }else{
                                $version_count++;
                            }
                        }
                        $pro_keys = array();

                        foreach ($changes['free'] as $key => $val) {
                            if($version != $current_version ){
                                echo '<span class="jsjobs-version-changes-popup-changes" >'.esc_html($val).'</span>';
                            }
                            if(isset($changes['pro'])){
                                if(isset($changes['pro'][$key])){
                                    echo wp_kses('<span class="jsjobs-version-changes-popup-changes" > <img src="'.JSJOBS_PLUGIN_URL.'includes/images/control_panel/line.jpg"/>'.$changes['pro'][$key].' <img class="version-change-second-image" src="'.JSJOBS_PLUGIN_URL.'includes/images/control_panel/pro-icon.jpg"/></span>', JSJOBS_ALLOWED_TAGS);
                                    $pro_keys[$key] = $key;
                                }

                            }
                        }
                        if(isset($changes['pro'])){
                            foreach ($changes['pro'] as $key => $val) {
                                if(! in_array($key, $pro_keys)){
                                    echo wp_kses('<span class="jsjobs-version-changes-popup-changes" > <img src="'.JSJOBS_PLUGIN_URL.'includes/images/control_panel/line.jpg"/>'.$changes['pro'][$key].' <img class="version-change-second-image" src="'.JSJOBS_PLUGIN_URL.'includes/images/control_panel/pro-icon.jpg"/></span>', JSJOBS_ALLOWED_TAGS);
                                }

                            }
                        }
                    }
                }?>
        </div>
        <div class="version-change-popup-button-wrapper" >
            <a class="version-change-popup-first-button" target="_blank" href="<?php echo esc_url(admin_url('plugins.php'));?>" ><?php echo __('Update To Latest', 'js-jobs'); ?></a>
            <a class="version-change-popup-second-button" target="_blank" href="https://joomsky.com/products/js-jobs-pro-wp.html"  ><?php echo __('Get PRO Version', 'js-jobs'); ?></a>
        </div>
    </div>
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <div class="dashboard">
        <span class="heading-dashboard"><?php echo __('Dashboard', 'js-jobs'); ?></span>
        <span class="dashboard-icon">
            <?php
                $url = 'http://www.joomsky.com/appsys/changelog/changelog.php';
                $post_data = array();
                $post_data['version'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                $post_data['product'] = 'jsjobswp';
                $response = array();

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $call_result = $response['body'];
                }else{
                    $call_result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                   }else{
                        $error = $response->get_error_message();
                   }
                }
                if($call_result){
                    $result = json_decode($call_result,true);
                }else{
                    $result = array();
                }
                if(isset($result['result_flag']) && $result['result_flag'] == 1){
                   $response = $result['result_data'];
                }

                $current_version = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                $new_vesion = 0;
                $latest_version = $result['latest_version'];
                if($call_result != false){
                    if(version_compare($latest_version, $current_version,'<=')){
                        $image = JSJOBS_PLUGIN_URL . "includes/images/up-dated.png";
                        $lang = __('Your System Is Up To Date', 'js-jobs');
                        $class = "green";
                    }elseif(version_compare($latest_version, $current_version,'>')){
                        $image = JSJOBS_PLUGIN_URL . "includes/images/new-version.png";
                        $lang = __('New Version Is Available', 'js-jobs');
                        $class = "orange";
                        $new_vesion = 1;
                    }
                }else{
                    $image = JSJOBS_PLUGIN_URL . "includes/images/connection-error.png";
                    $lang = $error;
                    $class = "red";
                }

            ?>
            <?php if($new_vesion == 1){?>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs&jsjobslt=stepone'), 'stepone')); ?>" >
            <?php }?>
                <span class="download <?php echo esc_attr($class); ?>">
                    <img src="<?php echo esc_url($image); ?>" />
                    <span><?php echo esc_html($lang); ?></span>
                </span>
            <?php if($new_vesion == 1){?>
                </a>
            <?php }
            if( $new_vesion == 1 && !empty($response)){?>
                <span class="jsjobs-version-changes-popup" onclick="showPopUpVersionChnages();" >
                    <img src="<?php echo JSJOBS_PLUGIN_URL . "includes/images/control_panel/version-available-icon.png"; ?>" />
                    <span class="jsjobs-smaal-icon-circle">&nbsp;</span>
                </span>
                <?php
            }?>

        </span>
        <script >
            function showPopUpVersionChnages(){
                jQuery("#full_background").show();
                jQuery("#popup_main").slideDown("slow");
            }

            function closePopupVersioChanges(){
                jQuery("#popup_main").slideUp("slow");
                jQuery("#full_background").fadeOut();
            }
        </script>
    </div>
    <div id="jsjobs-admin-wrapper">
        <div class="count1">
            <div class="box">
                <img class="job" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/top-icons/job.png">
                <div class="text">
                    <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totaljobs']); ?></div>
                    <div class="nonbold-text"><?php echo __('Jobs', 'js-jobs'); ?></div>
                </div>
            </div>
            <div class="box">
                <img class="company" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/top-icons/companies.png">
                <div class="text">
                    <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalcompanies']); ?></div>
                    <div class="nonbold-text"><?php echo __('Companies', 'js-jobs'); ?></div>
                </div>
            </div>
            <div class="box">
                <img class="resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/top-icons/reume.png">
                <div class="text">
                    <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalresume']); ?></div>
                    <div class="nonbold-text"><?php echo __('Resume', 'js-jobs'); ?></div>
                </div>
            </div>
            <div class="box">
                <img class="activejobs" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/top-icons/active-jobs.png">
                <div class="text">
                    <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalactivejobs']); ?></div>
                    <div class="nonbold-text"><?php echo __('Active Jobs', 'js-jobs'); ?></div>
                </div>
            </div>
            <div class="box1">
                <img class="appliedresume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/top-icons/job-applied.png">
                <div class="text">
                    <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totaljobapply']); ?></div>
                    <div class="nonbold-text"><?php echo __('Applied Resume', 'js-jobs'); ?></div>
                </div>
            </div>
        </div>
        <div class="newestjobs">
            <span class="header">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/newesticon.png">
                <span><?php echo __('Statistics', 'js-jobs'); ?>&nbsp;(<?php echo esc_html(jsjobs::$_data['fromdate']); ?>&nbsp;-&nbsp;<?php echo esc_html(jsjobs::$_data['curdate']); ?>)&nbsp;</span>
            </span>
            <div class="performance-graph" id="stack_chart_horizontal"></div>
        </div>
        <div class="count2">
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newjobs" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/jobs.png">
                    <div class="text">
                        <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalnewjobs']); ?></div>
                        <div class="nonbold-text"><?php echo __('New Jobs', 'js-jobs'); ?></div>
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newresume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/reume.png">
                    <div class="text">
                        <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalnewresume']); ?></div>
                        <div class="nonbold-text"><?php echo __('New Resume', 'js-jobs'); ?></div>
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="jobapplied" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png">
                    <div class="text">
                        <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalnewjobapply']); ?></div>
                        <div class="nonbold-text"><?php echo __('Job Applied', 'js-jobs'); ?></div>
                    </div>
                </div>
            </div>
            <div class="js-col-md-3 js-col-lg-3 js-col-xs-12 jsjobs- box-outer">
                <div class="box">
                    <img class="newcompanies" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/lower-icons/companies.png">
                    <div class="text">
                        <div class="bold-text"><?php echo esc_html(jsjobs::$_data['totalnewcompanies']); ?></div>
                        <div class="nonbold-text"><?php echo __('New Companies', 'js-jobs'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Admin', 'js-jobs'); ?></span>
        </div>
        <div class="categories-admin">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job'), 'job')); ?>" class="box">
                <img class="jobs" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/jobs/job.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Jobs', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=jobqueue'), 'job')); ?>" class="box">
                <img class="approval-queue" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/jobs/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&ff=2'), 'fieldordering')); ?>" class="box">
                <img class="Fields" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/jobs/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_report&jsjobslt=overallreports'), 'overallreports')); ?>" class="box">
                <img class="jsjobstats" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/report.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Reports', 'js-jobs'); ?></div>
                </div>
            </a>
            <?php /*
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                <img class="packages" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/package.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Credits Pack', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                <img class="payments" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/paymentt.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Credits Log', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                <img class="messages" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/message.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Messages', 'js-jobs'); ?></div>
                </div>
            </a>
            */ ?>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_category'), 'category')); ?>" class="box">
                <img class="categories" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/category.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Categories', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs&jsjobslt=info'), 'info')); ?>" class="box">
                <img class="information" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/information.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Information', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_activitylog'), 'activity-logs')); ?>" class="box">
                <img class="information" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/activity-log.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Activity Log', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_systemerror'), 'systemerror')); ?>" class="box">
                <img class="information" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/system-error.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('System Errors', 'js-jobs'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs&jsjobslt=translations'), 'translations')); ?>" class="box">
                <img class="information" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/language.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Translations'); ?></div>
                </div>
            </a>
        </div>

        <div style="margin-bottom:10px;" >
            <a href="https://www.joomsky.com/products/js-jobs-pro-wp.html" target="_blank" title="Job Manager Pro Plugin" >
                <img style="width: 100%;height: auto;" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/banner-plugin.png">
            </a>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Configuration', 'js-jobs'); ?></span>
        </div>
        <div class="categories-configuration">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_configuration&jsjobslt=configurations'), 'configuration')); ?>" class="box">
                <img class="general" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Configuration/cofigration.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('General', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_configuration&jsjobslt=configurationsjobseeker'), 'configurationsjobseeker')); ?>" class="box">
                <img class="jobseeker" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Configuration/jobseeker-2e.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Job Seeker', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_configuration&jsjobslt=configurationsemployer'), 'configurationsemployer')); ?>" class="box">
                <img class="employer" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Configuration/jobseeker.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Employer', 'js-jobs') ?></div>
                </div>
            </a>
            <?php /*
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                <img class="payment-method" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Configuration/paymentt.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Payment Methods', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                <img class="themes" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Configuration/theme.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Themes', 'js-jobs') ?></div>
                </div>
            </a>
            */ ?>
        </div>

        <div class="newestjobs">
            <span class="header">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/newesticon.png">
                <span><?php echo __('Newest Jobs', 'js-jobs'); ?></span>
            </span>
            <table id="js-table" class="newestjobtable">
                <thead>
                    <tr>
                        <th class="colunm-heading"><?php echo __('Job title', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Company', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Location', 'js-jobs'); ?></th>
                        <th class="colunm-heading"><?php echo __('Status', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (jsjobs::$_data[0]['latestjobs'] AS $latestjobs) { ?>
                        <tr>
                            <td class="job-title"><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=formjob&jsjobsid='.$latestjobs->id),'formjob')); ?>"><?php echo esc_html($latestjobs->title); ?></a></td>
                            <td class="description"><?php echo esc_html($latestjobs->name); ?></td>
                            <td class="description"><?php echo wp_kses(JSJOBSincluder::getJSModel('city')->getLocationDataForView($latestjobs->city), JSJOBS_ALLOWED_TAGS); ?></td>
                            <?php
                            $status;
                            $startDate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($latestjobs->startpublishing));
                            $stopDate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($latestjobs->stoppublishing));
                            $currentDate = $date = date_i18n("Y-m-d");
                            if ($startDate > $currentDate) {
                                $status = __('Unpublished', 'js-jobs');
                                $class = "unpublished";
                            } elseif ($startDate <= $currentDate && $stopDate >= $currentDate) {
                                $status = __('Published', 'js-jobs');
                                $class = "published";
                            }elseif ($stopDate < $currentDate) {
                                $status = __('Expired', 'js-jobs');
                                $class = "expired";
                            }
                            ?>
                            <td class="status">
                                <span class="<?php echo esc_attr($class); ?>"><?php echo esc_html($status); ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Companies', 'js-jobs'); ?></span>
        </div>
        <div class="categories-companies">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_company'), 'company')); ?>" class="box">
                <img class="companies" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/companies/companies.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Company', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_company&jsjobslt=companiesqueue'), 'company')); ?>" class="box">
                <img class="approval-queue" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/companies/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&ff=1'), 'fieldordering')); ?>" class="box">
                <img class="Fields" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/companies/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>
                </div>
            </a>
        </div>
        <div class="main-heading">
            <span class="text"><?php echo __('Resume', 'js-jobs'); ?></span>
        </div>
        <div class="categories-resume">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_resume'), 'resume')); ?>" class="box">
                <img class="resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/resume/resume.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Resume', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_resume&jsjobslt=resumequeue'), 'resume')); ?>" class="box">
                <img class="approval-queue" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/resume/approval-queue.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Approval Queue', 'js-jobs') ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&ff=3'), 'fieldordering')); ?>" class="box">
                <img class="Fields" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/resume/fields.png">
                <div class="text">
                    <div class="nonbold-text"><?php echo __('Fields', 'js-jobs') ?></div>
                </div>
            </a>
        </div>


                        <div class="main-heading">
                            <span class="text"><?php echo __('Misc.', 'js-jobs'); ?></span>
                            <?php /*
                              <span class="showmore">
                              <a class="img" href=""><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/Menu-icon.png">Show More</a>
                              </span>
                             */ ?>
                        </div>

                        <div class="categories-jobs">
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_shift'), 'shift')); ?>" class="box">
                                <img class="shifts" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/shift.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Shift', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_highesteducation'), 'highesteducation')); ?>" class="box">
                                <img class="heighesteducation" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/higest-edu.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Education', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_careerlevel'), 'careerlevel')); ?>" class="box">
                                <img class="careerlavel" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/career-level.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Career Level', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_experience'), 'experience')); ?>" class="box">
                                <img class="experince" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/experience.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Experience', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments'), 'department')); ?>" class="box">
                                <img class="department" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/department.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Departments', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <?php /*
                            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                                <img class="folders" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/folder.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Folders', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            */ ?>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_salaryrange'), 'salaryrange')); ?>" class="box">
                                <img class="salaryrange" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/salary-range.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Salary Range', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs&jsjobslt=stepone'), 'stepone')); ?>" class="box">
                                <img class="salaryrange" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/report.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Update', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <?php /*
                            <a href="admin.php?page=jsjobs&jsjobslt=profeatures" class="box">
                                <img id="js-proicon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro-icon.png">
                                <img class="salaryrange" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/tag.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Tags', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            */ ?>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_emailtemplate'), 'emailtemplate')); ?>" class="box">
                                <img class="salaryrange" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/email-temp.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Email Templates', 'js-jobs'); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=users'), 'user')); ?>" class="box">
                                <img class="salaryrange" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/users.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Users', 'js-jobs'); ?></div>
                                </div>
                            </a>
                        </div>
                        <?php /*
                        <a id="jsjobs_pro_feature_img_link" target="_blank" href="http://www.joomsky.com/products/js-jobs-pro-wp.html">
                            <img id="jobs-pro-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/final-banner.png">
                        </a>
*/?>
                        <div style="margin-bottom:10px;" >
                            <a href="https://www.joomsky.com/products/js-jobs/job-manager-theme.html" target="_blank" title="Job Manager Theme" >
                                <img style="width: 100%;height: auto;" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/banner-theme.png">
                            </a>
                        </div>

                        <div class="main-heading">
                            <span class="text"><?php echo __('Support', 'js-jobs'); ?></span>
                        </div>
                        <div class="categories-resume">
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('?page=jsjobs&jsjobslt=shortcodes'),"shortcode")); ?>" class="box">
                                <img class="resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/shortcode.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Short codes', 'js-jobs') ?></div>
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/documentations/wp-jobs" class="box">
                                <img class="resume" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/doc.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Documentation', 'js-jobs') ?></div>
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/forum/wp-jobs" class="box">
                                <img class="approval-queue" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/forum.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Forum', 'js-jobs') ?></div>
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/support/wp-jobs" class="box">
                                <img class="Fields" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/support.png">
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Support', 'js-jobs') ?></div>
                                </div>
                            </a>
                            <a href="http://www.joomsky.com/appsys/getstarted/wp-jobs" class="simple-wrapper">
                                <img class="icon" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/gst1.png" />
                                <div class="text">
                                    <div class="nonbold-text"><?php echo __('Get Started', 'js-jobs') ?></div>
                                </div>
                                <img class="simple-arrow" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/gst2.png" />
                            </a>

                        </div>
                        <div class="review">
                            <div class="upper">
                                <div class="imgs">
                                    <img class="reviewpic" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/review.png">
                                    <img class="reviewpic2" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/corner-1.png">
                                </div>
                                <div class="text">
                                    <div class="simple-text">
                                        <span class="nobold"><?php echo __('We\'d love to hear from ', 'js-jobs'); ?></span>
                                        <span class="bold"><?php echo __('You', 'js-jobs'); ?>.</span>
                                        <span class="nobold"><?php echo __('Please write appreciated review at', 'js-jobs'); ?></span>
                                    </div>
                                    <a href="https://wordpress.org/support/view/plugin-reviews/js-jobs" target="_blank"><?php echo __('Word Press Extension Directory', 'js-jobs'); ?><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/arrow2.png"></a>
                                </div>
                                <div class="right">
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/star.png">
                                </div>
                            </div>
                            <div class="lower">

                            </div>
                        </div>
                        <div class="js-other-products-wrp">
                            <div class="js-other-product-title">
                                <?php echo __("Other Products","js-jobs"); ?>
                            </div>
                            <div class="js-other-products-detail">
                                <div class="js-other-products-image">
                                    <img title="<?php echo __("WP Vehicle Manager","js-jobs"); ?>" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/vehicle-manager.png">
                                    <div class="js-other-products-bottom">
                                        <div class="js-product-title"><?php echo __("WP Vehicle Manager","js-jobs"); ?></div>
                                        <div class="js-product-bottom-btn">
                                            <span class="js-product-view-btn">
                                                <a href="https://wpvehiclemanager.com"  target="_blank" title="<?php echo __("Visit site","js-jobs"); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/new-tab.png"></a>
                                            </span>
                                            <span class="js-product-install-btn">
                                                <?php $plugininfo = checkJSJOBSPluginInfo('js-vehicle-manager/js-vehicle-manager.php'); ?>
                                                <a title="<?php echo __("Install WP Vehicle Manager Plugin","js-jobs"); ?>" class="wp-vehicle-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="js-vehicle-manager" <?php echo esc_attr($plugininfo['disabled']); ?>>
                                                    <?php echo esc_html(__($plugininfo['text'],"js-jobs")) ?>
                                                    <?php ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-other-products-image">
                                    <img title="<?php echo __("JS Help Desk","js-jobs"); ?>" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/help-desk.png">
                                    <div class="js-other-products-bottom">
                                        <div class="js-product-title"><?php echo __("JS Help Desk","js-jobs"); ?></div>
                                        <div class="js-product-bottom-btn">
                                            <span class="js-product-view-btn">
                                                <a href="https://jshelpdesk.com"  target="_blank" title="<?php echo __("Visit site","js-jobs"); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/new-tab.png"></a>
                                            </span>
                                            <span class="js-product-install-btn">
                                                <?php $plugininfo = checkJSJOBSPluginInfo('js-support-ticket/js-support-ticket.php'); ?>
                                                <a title="<?php echo __("Install JS Help Desk Plugin","js-jobs"); ?>" class="js-jobs-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="js-support-ticket" <?php echo esc_attr($plugininfo['disabled']); ?>>
                                                    <?php echo esc_html(__($plugininfo['text'],"js-jobs")) ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-other-products-image">
                                    <img title="<?php echo __("WP Learn Manager","js-jobs"); ?>" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/lms.png">
                                    <div class="js-other-products-bottom">
                                        <div class="js-product-title"><?php echo __("WP Learn Manager","js-jobs"); ?></div>
                                        <div class="js-product-bottom-btn">
                                            <span class="js-product-view-btn">
                                                <a title="<?php echo __("Visit site","js-jobs"); ?>" href="https://wplearnmanager.com" target="_blank"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/control_panel/latest-icons-admin/otherproducts/new-tab.png"></a>
                                            </span>
                                            <span class="js-product-install-btn">
                                                <?php $plugininfo = checkJSJOBSPluginInfo('learn-manager/learn-manager.php'); ?>
                                                <a title="<?php echo __("Install WP Learn Manager Plugin","js-jobs"); ?>" class="wp-learn-manager-btn-color <?php echo esc_attr($plugininfo['class']); ?>" data-slug="learn-manager" <?php echo esc_attr($plugininfo['disabled']); ?>><?php echo esc_html(__($plugininfo['text'],"js-jobs")) ?></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                    jQuery('div.resume').animate({left: '-100%'});
                            jQuery('div.companies span.img img').click(function (e) {
                    jQuery('div.companies').animate({left: '-100%'});
                            jQuery('div.resume').animate({left: '0%'});
                    });
                            jQuery('div.resume span.img img').click(function (e) {
                    jQuery('div.resume').animate({left: '-100%'});
                            jQuery('div.companies').animate({left: '0%'});
                    });
                            jQuery('div.jobs').animate({right: '-100%'});
                            jQuery('div.jobs span.img img').click(function (e) {
                    jQuery('div.jobs').animate({right: '-100%'});
                            jQuery('div.appliedjobs').animate({right: '0%'});
                    });
                            jQuery('div.appliedjobs span.img img').click(function (e) {
                    jQuery('div.appliedjobs').animate({right: '-100%'});
                            jQuery('div.jobs').animate({right: '0%'});
                    });
                            jQuery("span.dashboard-icon").find('span.download').hover(function(){
                    jQuery(this).find('span').toggle("slide");
                    }, function(){
                    jQuery(this).find('span').toggle("slide");
                    });
                    });
                </script>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery(document).on('click','a.js-btn-install-now',function(){
                            jQuery(this).attr('disabled',true);
                            jQuery(this).html('Installing.....!');
                            jQuery(this).removeClass('js-btn-install-now');
                            var pluginslug = jQuery(this).attr("data-slug");
                            var buttonclass = jQuery(this).attr("class");
                            jQuery(this).addClass('js-installing-effect');
                            if(pluginslug != ''){
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jsjobs', task: 'installPluginFromAjax', pluginslug:pluginslug,'_wpnonce':'<?php echo esc_attr(wp_create_nonce("install-plugin-ajax")); ?>'}, function (data) {
                                    if(data == 1){
                                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                                        jQuery("span.js-product-install-btn a."+buttonclass).html("Active Now");
                                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-active-now js-btn-green");
                                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                                    }else{
                                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                                        jQuery("span.js-product-install-btn a."+buttonclass).html("Please try again");
                                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-install-now");
                                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                                    }
                                });
                            }
                        });

                        jQuery(document).on('click','a.js-btn-active-now',function(){
                            jQuery(this).attr('disabled',true);
                            jQuery(this).html('Activating.....!');
                            jQuery(this).removeClass('js-btn-active-now');
                            var pluginslug = jQuery(this).attr("data-slug");
                            var buttonclass = jQuery(this).attr("class");
                            if(pluginslug != ''){
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jsjobs', task: 'activatePluginFromAjax', pluginslug:pluginslug,'_wpnonce':'<?php echo esc_attr(wp_create_nonce("activate-plugin-ajax")); ?>'}, function (data) {
                                    if(data == 1){
                                        jQuery("a[data-slug="+pluginslug+"]").html("Activated");
                                        jQuery("a[data-slug="+pluginslug+"]").addClass("js-btn-activated");
                                        window.location.reload();
                                    }
                                });
                            }
                        });
                    });
                </script>
