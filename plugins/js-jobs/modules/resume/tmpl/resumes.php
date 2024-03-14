<script type="text/javascript">
</script>

<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
if (jsjobs::$_error_flag == null) { ?>
    <div id="jsjobs-main-up-wrapper">
    <?php
    $msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    $msgkey = JSJOBSincluder::getJSModel('resumesearch')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    JSJOBSbreadcrumbs::getBreadcrumbs();
    include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
    ?>
    <div id="jsjobs-wrapper">
        <?php
        $heading = __('Resume', 'js-jobs');
        ?>
        <div class="page_heading"><?php echo esc_html($heading); ?></div>
        <?php
            $config_array = jsjobs::$_data['config'];
            ?>
            <div id="resume-list-wrraper">
            <?php
            $link_array = array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'jsjobspageid'=>jsjobs::getPageid());
            if(isset(jsjobs::$_data['filter']['category'])) {
                $link_array['category'] = jsjobs::$_data['filter']['category'];
            }
            if (jsjobs::$_sortorder == 'ASC')
                $img = "001.png";
            else
                $img = "002.png";
            ?>
            <div id="resume-list-navebar">
                <ul>
                    <li>
                        <a href="<?php  $link_array['sortby'] = jsjobs::$_sortlinks['title'] ; echo esc_url(wp_nonce_url(jsjobs::makeUrl($link_array),"resume")); ?>" class="<?php
                        if (jsjobs::$_sorton == 'title') {
                            echo 'selected';
                        }
                        ?>"><?php if (jsjobs::$_sorton == 'title') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Title', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php  $link_array['sortby'] = jsjobs::$_sortlinks['jobtype'] ; echo esc_url(wp_nonce_url(jsjobs::makeUrl($link_array),"resume")); ?>" class="<?php
                       if (jsjobs::$_sorton == 'jobtype') {
                           echo 'selected';
                       }
                       ?>"><?php if (jsjobs::$_sorton == 'jobtype') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Type', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php  $link_array['sortby'] = jsjobs::$_sortlinks['salary'] ; echo esc_url(wp_nonce_url(jsjobs::makeUrl($link_array),"resume")); ?>" class="<?php
                    if (jsjobs::$_sorton == 'salary') {
                        echo 'selected';
                    }
                        ?>"><?php if (jsjobs::$_sorton == 'salary') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Salary Range', 'js-jobs'); ?></a>
                    </li>
                    <li>
                        <a href="<?php  $link_array['sortby'] = jsjobs::$_sortlinks['posted'] ; echo esc_url(wp_nonce_url(jsjobs::makeUrl($link_array),"resume")); ?>" class="<?php
            if (jsjobs::$_sorton == 'posted') {
                echo 'selected';
            }
            ?>"><?php if (jsjobs::$_sorton == 'posted') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Posted', 'js-jobs'); ?></a>
                    </li>
                </ul>
            </div>
            <?php
            if (isset(jsjobs::$_data[0]) && !empty(jsjobs::$_data[0])) {
                foreach (jsjobs::$_data[0] AS $myresume) {
                    if ($myresume->photo != "") {
                        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                        $wpdir = wp_upload_dir();
                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $myresume->id . '/photo/' . $myresume->photo;
                    } else {
                        $path = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                    }
                    ?>
                    <div id="resume-list">
                        <div class="resume-upper-wrapper">
                            <div class="resume-img">
                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->aliasid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                    <img src="<?php echo esc_url($path); ?>">
                                </a>
                            </div>
                            <div class="resume-detail">
                                <div class="resume-detail-upper">
                                    <div class="resume-detail-upper-left">
                                        <span class="resume-title"><a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->aliasid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><?php echo esc_html($myresume->first_name) . '&nbsp' . esc_html($myresume->last_name); ?></a></span>
                                        <?php
                                        $dateformat = jsjobs::$_configuration['date_format'];
                                        $curdate = date_i18n($dateformat);
                                        ?>
                                    </div>
                                    <div class="resume-detail-upper-right">
                                        <span class="resume-date"><?php echo __('Created', 'js-jobs') . ':&nbsp;' . esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($myresume->created))); ?></span>
                                        <span class="time-of-resume"><?php echo esc_html(__($myresume->jobtypetitle,'js-jobs')); ?></span>
                                    </div>
                                </div>
                                <div class="resume-detail-lower listing-fields">
                                    <?php if($myresume->application_title != ''){ ?>
                                        <span class="get-text"><?php echo '(' . esc_html($myresume->application_title) . ')'; ?></span>
                                    <?php } ?>
                                    <div class="custom-field-wrapper">
                                        <span class="js-bold"><?php echo __('Email Address', 'js-jobs') . ': '; ?></span>
                                        <span class="get-text"><?php echo esc_html($myresume->email_address); ?></span>												
                                    </div>
                                    <div class="custom-field-wrapper">
                                        <span class="js-bold"><?php echo __('Category', 'js-jobs') . ': '; ?></span>
                                        <span class="get-text"><?php echo esc_html(__($myresume->cat_title,'js-jobs')); ?></span>
                                    </div>
                                    <div class="custom-field-wrapper">
                                        <span class="js-bold"><?php echo __('Salary', 'js-jobs') . ': '; ?></span>
                                        <span class="get-text"><?php echo esc_html($myresume->salary); ?></span>	
                                    </div>									
                                    <div class="custom-field-wrapper">
                                        <span class="js-bold"><?php echo __('Total Experience', 'js-jobs') . ': '; ?></span>
                                        <span class="get-text"><?php echo esc_html(__($myresume->total_experience,'js-jobs')); ?></span>	
                                    </div>
                                    <?php
                                    // custom fiedls 
                                    $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3, 1,1);
                                    foreach ($customfields as $field) {
                                        echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 10, $myresume->params), JSJOBS_ALLOWED_TAGS);
                                    }
                                    //end
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="resume-lower-wrapper">
                            <div class="resume-lower-wrapper-left">
                                <?php if ($myresume->location != '') { ?>
                                    <span  class="lower-img"><img id=location-img  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"></span>
                                    <span class="company-address"><?php echo esc_html($myresume->location); ?></span>
                                <?php } ?>
                            </div>
                            <div class="resume-lower-wrapper-right">
                                <div class="button">
                                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->aliasid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><?php echo __('View Resume', 'js-jobs'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php } ?>

            </div>

        <?php
        if (jsjobs::$_data[1]) {
            echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
        }
    } else {
        JSJOBSlayout::getNoRecordFound();
    }
    ?>
    </div>
    </div>
<?php } ?>
