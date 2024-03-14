<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
function checkLinks($name) {
    foreach (jsjobs::$_data['fields'] as $field) {
        $array =  array();
        $array[0] = 0;
        switch ($field->field) {
            case $name:
                if($field->showonlisting == 1){
                    $array[0] = 1;
                    $array[1] =  $field->fieldtitle;
                }
            return $array;
            break;
        }
    }
    return $array;  
}
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);

JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $labelflag = true;
    $labelinlisting = jsjobs::$_configuration['labelinlisting'];
    if ($labelinlisting != 1)
        $labelflag = false;
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('My Jobs', 'js-jobs'); ?>
            <a class="additem" href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),"formjob"));?>"><?php echo __('Add New','js-jobs') .' '. __('Job', 'js-jobs'); ?></a>
        </div>

        <?php
        if (jsjobs::$_sortorder == 'ASC')
            $img = "001.png";
        else
            $img = "002.png";
        ?>
        <div id="my-jobs-header">
            <ul>
                <li>
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['title']))); ?>" class="<?php
                        if (jsjobs::$_sorton == 'title') {
                            echo 'selected';
                        }
                        ?>"><?php if (jsjobs::$_sorton == 'title') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Title', 'js-jobs'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['category']))); ?>" class="<?php
                         if (jsjobs::$_sorton == 'category') {
                             echo 'selected';
                         }
                         ?>"><?php if (jsjobs::$_sorton == 'category') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Category', 'js-jobs'); ?></a>
                     </li>

                     <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['jobtype']))); ?>" class="<?php
                         if (jsjobs::$_sorton == 'jobtype') {
                             echo 'selected';
                         }
                         ?>"><?php if (jsjobs::$_sorton == 'jobtype') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Type', 'js-jobs'); ?></a>
                     </li>

                     <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['jobstatus']))); ?>" class="<?php
                        if (jsjobs::$_sorton == 'jobstatus') {
                            echo 'selected';
                        }
                        ?>"><?php if (jsjobs::$_sorton == 'jobstatus') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Status', 'js-jobs'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['company']))); ?>" class="<?php
                            if (jsjobs::$_sorton == 'company') {
                                echo 'selected';
                            }
                            ?>"><?php if (jsjobs::$_sorton == 'company') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Company', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['salary']))); ?>" class="<?php
                            if (jsjobs::$_sorton == 'salary') {
                                echo 'selected';
                            }
                            ?>"><?php if (jsjobs::$_sorton == 'salary') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Salary Range', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'myjobs','sortby'=> jsjobs::$_sortlinks['posted']))); ?>" class="<?php
                            if (jsjobs::$_sorton == 'posted') {
                                echo 'selected';
                            }
                            ?>"><?php if (jsjobs::$_sorton == 'posted') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Posted', 'js-jobs'); ?></a>
                        </li>
                    </ul>
                </div>
                            <?php
                            if (!empty(jsjobs::$_data[0])) {
                                foreach (jsjobs::$_data[0] AS $myjob) {
                                    if ($myjob->logofilename != "") {
                                        $wpdir = wp_upload_dir();
                                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $myjob->companyid . '/logo/' . $myjob->logofilename;
                                    } else {
                                        $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                                    }
                                    ?>
                                    <div class="my-jobs-data object_<?php echo esc_attr($myjob->id); ?>">
                                        <span class="fir">
                                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$myjob->companyid))); ?>">
                                                <img src="<?php echo esc_url($path); ?>" >
                                            </a>
                                        </span>
                                        <div class="data-bigupper">
                                            <div class="big-upper-upper">
                                                <div class="headingtext item-title">
                                                    <span class="title">
                                                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$myjob->jobaliasid))); ?>">
                                                            <?php echo esc_html($myjob->title); ?>
                                                        </a>
                                                    </span>
                                                    <?php
                                                    $dateformat = jsjobs::$_configuration['date_format'];?>
                                                    
                                                </div>
                                                <?php
                                                echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($myjob->created)));
                                                $print = checkLinks('jobtype');
                                                if ($print[0] == 1) { ?> 
                                                <span class="buttonu">
                                                    <?php echo esc_html(__($myjob->jobtypetitle,'js-jobs')); ?>
                                                </span>
                                                <?php } ?>
                                            </div>
                                            <div class="big-upper-lower listing-fields">

                                                <?php
                                                $print = checkLinks('company');
                                                if ($print[0] == 1) {
                                                    ?>
                                                    <div class="custom-field-wrapper">
                                                        <?php if ($labelflag) { ?>
                                                        <span class="js-bold">
                                                            <?php echo esc_html(__($print[1], 'js-jobs')) . ": "; ?>
                                                        </span>
                                                        <?php } ?>
                                                        <span class="get-text">
                                                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'viewcompany', 'jsjobsid'=>$myjob->companyid))); ?>"><?php echo esc_html($myjob->companyname); ?></a>
                                                        </span>
                                                    </div>
                                                    <?php
                                                }
                                                $print = checkLinks('jobsalaryrange');
                                                if ($print[0] == 1) {
                                                    ?>
                                                    <div class="custom-field-wrapper">
                                                        <?php if ($labelflag) { ?>
                                                        <span class="js-bold">
                                                            <?php echo esc_html(__($print[1], 'js-jobs')) . ': '; ?>
                                                        </span>
                                                        <?php } ?>
                                                        <span class="get-text">
                                                            <?php echo esc_html($myjob->salary);?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                }
                                                $print = checkLinks('jobcategory');
                                                if ($print[0] == 1) {
                                                    ?>

                                                    <div class="custom-field-wrapper">
                                                        <?php if ($labelflag) { ?>
                                                        <span class="js-bold">
                                                            <?php echo esc_html(__($print[1], 'js-jobs')) . ": "; ?>
                                                        </span>
                                                        <?php } ?>
                                                        <span class="get-text">
                                                            <?php echo esc_html(__($myjob->cat_title,'js-jobs')); ?> 
                                                        </span>
                                                    </div>
                                                    <?php } ?> 
                                                    <?php
                                // custom fiedls 
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2, 1);
                                foreach ($customfields as $field) {
                                    echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 7,$myjob->params), JSJOBS_ALLOWED_TAGS);
                                }
                                //end
                                ?>
                            <span class="big-upper-lower2text">
                            <?php if ($labelflag) { ?>
                                    <span class="title"><?php echo __('Status', 'js-jobs') . ": "; ?></span>
                                <?php
                            }
                            $color = ($myjob->status == 1) ? "green" : "red";
                            if ($myjob->status == 1) {
                                $statusCheck = __('Approved', 'js-jobs');
                            } elseif ($myjob->status == 0) {
                                $statusCheck = __('Waiting for approval', 'js-jobs');
                            } else {
                                $statusCheck = __('Rejected', 'js-jobs');
                            }
                            ?>
                                <span class="get-status-text <?php echo esc_attr($color); ?>"><?php echo esc_html($statusCheck); ?></span> 
                            </span>
            <?php
            $print = checkLinks('noofjobs'); 
            $startdate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($myjob->startpublishing));
            $enddate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($myjob->stoppublishing));
            $curdate = date_i18n('Y-m-d');
            if($startdate > $curdate){
                $publishstatus = __('Not publish','js-jobs');
                $publishstyle = 'background:#FEA702;color:#ffffff;border:unset;';
            }elseif($startdate <= $curdate && $enddate >= $curdate){
                $publishstatus = __('Publish','js-jobs');
                $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
            }else{
                $publishstatus = __('Expired','js-jobs');
                $publishstyle = 'background:#ED3237;color:#ffffff;border:unset;';
            }
            ?>
            <?php if($myjob->status == 1){ ?>
            <span class="bigupper-jobtotal" style="padding:4px 8px;<?php echo esc_attr($publishstyle); ?>"><?php echo esc_html($publishstatus); ?></span>
            <?php } ?>
            <?php
            if ($print) { ?>
                <span class="bigupper-jobtotal"><?php echo esc_html($myjob->noofjobs) . " " . __('jobs', 'js-jobs'); ?></span>
            <?php } ?>
                        </div>
                    </div>
                    <div class="data-big-lower">
            <?php
            $print = checkLinks('city');
            if ($print) {
                   ?> <span class="big-lower-left">  <img class="big-lower-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"><?php echo esc_html($myjob->location); ?></span>
            <?php } ?>
                <div class="big-lower-data-icons">
                    <?php 
                    if($myjob->status == 1){ ?>
                        <a href="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob', 'jsjobsid'=>$myjob->id)),"formjob")); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-edit.png" alt="<?php echo __('Edit', 'js-jobs'); ?>" title="<?php echo __('Edit', 'js-jobs'); ?>"/></a>
                        
                        <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$myjob->jobaliasid))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                    <?php
                    $config_array = jsjobs::$_data['config']; ?>
                                                <a href="<?php echo wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'task'=>'remove', 'action'=>'jsjobtask', 'jsjobs-cb[]'=>$myjob->id,'jsjobspageid'=>jsjobs::getPageid())),'delete-job'); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'jobapply', 'jsjobslt'=>'jobappliedresume', 'jobid'=>$myjob->id))); ?>"><span class="icon-text-box1"><span class="icons-resume"><?php echo __('Resume', 'js-jobs') . " (" . esc_html($myjob->resumeapplied) . ")"; ?></span></span></a>
                    <?php 
                }elseif($myjob->status == 0){ ?>
                    <div class="big-lower-data-text pending"><img id="pending-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pending-corner.png"/><span><?php echo __('Waiting for approval', 'js-jobs'); ?></span></div>
                <?php
                }elseif($myjob->status == -1){ ?>
                    <div class="big-lower-data-text reject"><img id="reject-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/reject-corner.png"/><span><?php echo __('Rejected', 'js-jobs'); ?></span></div>
                <?php
                }
                ?>
                </div>
            </div>            
                </div>
            <?php
        }
        if (jsjobs::$_data[1]) {
            echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $linkmyjobs[] = array(
                    'link' => jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'addjob')),
                    'text' => __('Add New','js-jobs') .' '. __('Job', 'js-jobs')
                );
        echo wp_kses(JSJOBSLayout::getNoRecordFound($msg,$linkmyjobs), JSJOBS_ALLOWED_TAGS);
    }
    ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
