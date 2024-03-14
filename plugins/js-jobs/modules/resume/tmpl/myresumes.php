<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
wp_enqueue_style('status-graph', JSJOBS_PLUGIN_URL . 'includes/css/status_graph.css');
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>

    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('My Resumes', 'js-jobs'); ?>
            <a class="additem" href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume', 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Resume', 'js-jobs'); ?></a>
        </div>
        <?php
        if (jsjobs::$_sortorder == 'ASC')
            $img = "001.png";
        else
            $img = "002.png";
        ?>
        <div id="my-resume-header">
            <ul>
                <li>
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'sortby' => jsjobs::$_sortlinks['title'], 'jsjobspageid'=>jsjobs::getPageid()))) ?>" class="<?php
                    if (jsjobs::$_sorton == 'title') {
                        echo 'selected';
                    }
                    ?>"><?php if (jsjobs::$_sorton == 'title') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Title', 'js-jobs'); ?></a>
                </li>
                <li>
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'sortby' => jsjobs::$_sortlinks['jobtype'], 'jsjobspageid'=>jsjobs::getPageid()))) ?>" class="<?php
                   if (jsjobs::$_sorton == 'jobtype') {
                       echo 'selected';
                   }
                   ?>"><?php if (jsjobs::$_sorton == 'jobtype') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Job Type', 'js-jobs'); ?></a>
                </li>
                <li>
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'sortby' => jsjobs::$_sortlinks['salary'], 'jsjobspageid'=>jsjobs::getPageid()))) ?>" class="<?php
                if (jsjobs::$_sorton == 'salary') {
                    echo 'selected';
                }
                    ?>"><?php if (jsjobs::$_sorton == 'salary') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Salary Range', 'js-jobs'); ?></a>
                </li>
                <li>
                    <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'sortby' => jsjobs::$_sortlinks['posted'], 'jsjobspageid'=>jsjobs::getPageid()))) ?>" class="<?php
        if (jsjobs::$_sorton == 'posted') {
            echo 'selected';
        }
        ?>"><?php if (jsjobs::$_sorton == 'posted') { ?> <img src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/' . $img ?>"> <?php } ?><?php echo __('Posted', 'js-jobs'); ?></a>
                </li>
            </ul>
        </div>
            <?php
            $dateformat = jsjobs::$_configuration['date_format'];
            if (!empty(jsjobs::$_data[0])) {
                foreach (jsjobs::$_data[0] AS $myresume) {
                    $status_array = JSJOBSincluder::getJSModel('resume')->getResumePercentage($myresume->id);
                    $percentage = $status_array['percentage'];
                    ?>
                <div class="my-resume-data object_<?php echo esc_attr($myresume->id); ?>">
            <?php
            $wpdir = wp_upload_dir();
            if ($myresume->photo != "") {
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $photourl = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $myresume->id . '/photo/' . $myresume->photo;
            } else {
                $photourl = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
            }
            ?>
                    <div class="my-resume-listing-img-modified-wrapper" >
                        <span class="fir">
                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->id, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                <img  src="<?php echo esc_url($photourl); ?>" />
                            </a>
                        </span>
                        <span class="my-resume-modified-date" >
                            <span class="my-resume-modified-date-title" >
                                <?php echo __('Modified Date','js-jobs'); ?>
                            </span>
                            <span class="my-resume-modified-date-value" >
                                <?php echo esc_html(date_i18n($dateformat,jsjobslib::jsjobs_strtotime($myresume->last_modified))); ?>
                            </span>
                        </span>
                    </div>
                    <div class="data-bigupper">
                        <div class="big-upper-upper">
                            <div class="headingtext item-title">
                                <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->id, 'jsjobspageid'=>jsjobs::getPageid()))); ?>">
                                    <span class="title"><?php echo esc_html($myresume->first_name) . " " . esc_attr($myresume->last_name); ?></span>
                                </a>
                            </div>
                            <span class="buttonu"><?php echo esc_html(__($myresume->jobtypetitle,'js-jobs')); ?></span><span class="datecreated"><?php echo __('Created', 'js-jobs') . ':&nbsp;' . esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($myresume->created))); ?></span>
                        </div>
                        <div class="big-upper-lower listing-fields">
                            <div class="myresume-list-bottom-left">
                                <span class="lower-upper-title">(
                                        <?php echo esc_html($myresume->application_title); ?>
                                    </a>)
                                </span>
                                <div class="custom-field-wrapper">
                                    <span class="js-bold"><?php echo esc_html(__(jsjobs::$_data['fields']['email_address'], 'js-jobs')) . ': '; ?></span>
                                    <span class="get-text"><?php echo esc_html($myresume->email_address); ?></span>                                               
                                </div>
                                <div class="custom-field-wrapper">
                                    <span class="js-bold">
                                    <?php 
                                        if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                            jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                        }                                    
                                        echo esc_html(__(jsjobs::$_data['fields']['desired_salary'], 'js-jobs')) . ': '; ?>
                                    </span>
                                    <span class="get-text"><?php echo esc_html($myresume->salary); ?></span>  
                                </div>                                  
                                <div class="custom-field-wrapper">
                                    <span class="js-bold"><?php echo esc_html(__(jsjobs::$_data['fields']['job_category'], 'js-jobs')) . ': '; ?></span>
                                    <span class="get-text"><?php echo esc_html(__($myresume->cat_title,'js-jobs')); ?></span>
                                </div>
                                <div class="custom-field-wrapper">
                                    <span class="js-bold">
                                    <?php 
                                        if(!isset(jsjobs::$_data['fields']['total_experience'])){
                                            jsjobs::$_data['fields']['total_experience'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('total_experience',3);
                                        }                                    
                                        echo esc_html(__(jsjobs::$_data['fields']['total_experience'], 'js-jobs')) . ': '; ?></span>
                                    <span class="get-text"><?php echo esc_html(__($myresume->total_experience,'js-jobs')); ?></span>    
                                </div>
                                <?php
                                // custom fields 
                                $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3, 1,1);
                                foreach ($customfields as $field) {
                                    echo wp_kses(JSJOBSincluder::getObjectClass('customfields')->showCustomFields($field, 9,$myresume->params), JSJOBS_ALLOWED_TAGS);
                                }
                                //end
                                ?>
                            </div>
                            <div class="myresume-list-data-profile myresume-complete-status" data-per="<?php echo esc_attr($percentage); ?>" >
                                <span class="myresume-profile-heading">
                                    <?php echo __('Your Profile Status','js-jobs');?>
                                </span>
                                <span class="myresume-profile-counter">
                                    <div class="js-mr-rp" data-progress="100"> <div class="circle"> <div class="mask full"> <div class="fill"></div> </div> <div class="mask half"> <div class="fill"></div> <div class="fill fix"></div> </div> <div class="shadow"></div> </div> <div class="inset"> <div class="percentage"> <div class="numbers"><span>-</span><span>0%</span><span>1%</span><span>2%</span><span>3%</span><span>4%</span><span>5%</span><span>6%</span><span>7%</span><span>8%</span><span>9%</span><span>10%</span><span>11%</span><span>12%</span><span>13%</span><span>14%</span><span>15%</span><span>16%</span><span>17%</span><span>18%</span><span>19%</span><span>20%</span><span>21%</span><span>22%</span><span>23%</span><span>24%</span><span>25%</span><span>26%</span><span>27%</span><span>28%</span><span>29%</span><span>30%</span><span>31%</span><span>32%</span><span>33%</span><span>34%</span><span>35%</span><span>36%</span><span>37%</span><span>38%</span><span>39%</span><span>40%</span><span>41%</span><span>42%</span><span>43%</span><span>44%</span><span>45%</span><span>46%</span><span>47%</span><span>48%</span><span>49%</span><span>50%</span><span>51%</span><span>52%</span><span>53%</span><span>54%</span><span>55%</span><span>56%</span><span>57%</span><span>58%</span><span>59%</span><span>60%</span><span>61%</span><span>62%</span><span>63%</span><span>64%</span><span>65%</span><span>66%</span><span>67%</span><span>68%</span><span>69%</span><span>70%</span><span>71%</span><span>72%</span><span>73%</span><span>74%</span><span>75%</span><span>76%</span><span>77%</span><span>78%</span><span>79%</span><span>80%</span><span>81%</span><span>82%</span><span>83%</span><span>84%</span><span>85%</span><span>86%</span><span>87%</span><span>88%</span><span>89%</span><span>90%</span><span>91%</span><span>92%</span><span>93%</span><span>94%</span><span>95%</span><span>96%</span><span>97%</span><span>98%</span><span>99%</span><span>100%</span></div></div></div></div>
                                </span>
                                <?php if($percentage == 100){ ?>
                                    <span class="myresume-profile-title"><?php echo __('Profile Completed', 'js-jobs'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="data-big-lower">
                        <span class="big-lower-left">  
                            <img class="big-lower-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/location.png"><?php echo esc_html($myresume->location); ?>
                        </span> 
                            <?php if ($myresume->status == 1) {
                                $config_array_res = jsjobs::$_data['config'];
                             ?>
                        <div class="big-lower-data-icons">
                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume', 'jsjobsid'=>$myresume->id, 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-edit.png" alt="<?php echo __('Edit', 'js-jobs'); ?>" title="<?php echo __('Edit', 'js-jobs'); ?>"/></a>
                            <a href="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$myresume->id, 'jsjobspageid'=>jsjobs::getPageid()))); ?>"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-view.png" alt="<?php echo __('View', 'js-jobs'); ?>" title="<?php echo __('View', 'js-jobs'); ?>"/></a>
                            <a href="<?php echo wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'task'=>'removeresume', 'action'=>'jsjobtask', 'jsjobs-cb[]'=>$myresume->id, 'jsjobspageid'=>jsjobs::getPageid())),'delete-resume'); ?>"onclick="return confirmdelete('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img class="icon-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/fe-force-delete.png" alt="<?php echo __('Delete', 'js-jobs'); ?>" title="<?php echo __('Delete', 'js-jobs'); ?>"/></a>
                        </div>
            <?php } elseif ($myresume->status == 0) { ?>
                            <div class="big-lower-data-text"><img id="pending-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pending-corner.png"/><?php echo __('Waiting for approval', 'js-jobs'); ?></div>
            <?php }elseif ($myresume->status == -1){ ?>
                            <div class="big-lower-data-text rjctd"><img id="pending-img"  src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/reject-cornor.png"/><?php echo __('Rejected', 'js-jobs'); ?></div>
            <?php
                } ?>
                    </div>
                </div>

        <?php } ?>

        <?php
        if (jsjobs::$_data[1]) {
            echo '<div id="jsjobs-pagination">' . wp_kses_post(jsjobs::$_data[1]) . '</div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $links[] = array(
                    'link' => jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume', 'jsjobspageid'=>jsjobs::getPageid())),
                    'text' => __('Add New','js-jobs') .'&nbsp;'. __('Resume', 'js-jobs')
                );
        JSJOBSlayout::getNoRecordFound($msg,$links);
    }
?>
    </div>
<?php
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
