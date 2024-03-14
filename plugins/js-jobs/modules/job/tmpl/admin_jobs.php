<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');

$dateformat = jsjobs::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = jsjobslib::jsjobs_strpos($dateformat, $dash, 0);
$firstvalue = jsjobslib::jsjobs_substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = jsjobslib::jsjobs_strpos($dateformat, $dash, $firstdash);
$secondvalue = jsjobslib::jsjobs_substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = jsjobslib::jsjobs_substr($dateformat, $seconddash, jsjobslib::jsjobs_strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = jsjobslib::jsjobs_str_replace('Y', 'yy', $js_scriptdateformat);
?>
<script>
    jQuery(document).ready(function () {
        jQuery("div#full_background,img#popup_cross").click(function () {
            closePopup();
        });
        jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
        jQuery("div.job-container").each(function () {
            jQuery("div#" + this.id).hover(function () {
                jQuery("div#" + this.id + " div span.selector").show();
            }, function () {
                if (jQuery("div#" + this.id + " div span.selector input:checked").length > 0) {
                    jQuery("div#" + this.id + " div span.selector").show();
                } else {
                    jQuery("div#" + this.id + " div span.selector").hide();
                }
            });
        });
        jQuery("span#showhidefilter").click(function (e) {
            e.preventDefault();
            var img2 = "<?php echo JSJOBS_PLUGIN_URL . "includes/images/filter-up.png"; ?>";
            var img1 = "<?php echo JSJOBS_PLUGIN_URL . "includes/images/filter-down.png"; ?>";
            if (jQuery('.default-hidden').is(':visible')) {
                jQuery(this).find('img').attr('src', img1);
            } else {
                jQuery(this).find('img').attr('src', img2);
            }
            jQuery(".default-hidden").toggle();
            var height = jQuery(this).height();
            var imgheight = jQuery(this).find('img').height();
            var currenttop = (height - imgheight) / 2;
            jQuery(this).find('img').css('top', currenttop);
        });
    });

    function highlight(id) {
        if (jQuery("div#job_" + id + " div span input:checked").length > 0) {
            showBorder(id);
        } else {
            hideBorder(id);
        }
    }
    function showBorder(id) {
        jQuery("div#job_" + id).addClass('blue');
    }
    function hideBorder(id) {
        jQuery("div#job_" + id).removeClass('blue');
    }
    function highlightAll() {
        if (jQuery("span.selector input").is(':checked') == false) {
            jQuery("span.selector").css('display', 'none');
            jQuery("div.job-container div#item-data").css('border', '1px solid #dedede');
            jQuery("div.job-container div#item-actions").css('border', '1px solid #dedede');
            jQuery("div.job-container div#item-actions").css('border-top', 'none');
        }
        if (jQuery("span.selector input").is(':checked') == true) {
            jQuery("span.selector").css('display', 'block');
            jQuery("div.job-container div#item-data").css('border', '1px solid #428BCA');
            jQuery("div.job-container div#item-data").css('border-bottom', '1px solid #dedede');
            jQuery("div.job-container div#item-actions").css('border', '1px solid #428BCA');
            jQuery("div.job-container div#item-actions").css('border-top', 'none');
        }
    }



    function resetFrom() {
        document.getElementById('location').value = '';
        document.getElementById('searchtitle').value = '';
        document.getElementById('searchcompany').value = '';
        document.getElementById('searchjobcategory').value = '';
        document.getElementById('searchjobtype').value = '';
        document.getElementById('status').value = '';
        document.getElementById('datestart').value = '';
        document.getElementById('dateend').value = '';
        document.getElementById('jsjobsform').submit();
    }


</script>

<?php
$categoryarray = array(
    (object) array('id' => 1, 'text' => __('Job Title', 'js-jobs')),
    (object) array('id' => 2, 'text' => __('Company Name', 'js-jobs')),
    (object) array('id' => 3, 'text' => __('Category', 'js-jobs')),
    (object) array('id' => 5, 'text' => __('Location', 'js-jobs')),
    (object) array('id' => 7, 'text' => __('Status', 'js-jobs')),
    (object) array('id' => 4, 'text' => __('Job Type', 'js-jobs')),
    (object) array('id' => 6, 'text' => __('Created', 'js-jobs'))
);
?>

<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
        $msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
        JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <span class="heading">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
            <span class="heading-text"><?php echo __('Jobs', 'js-jobs') ?></span> 
        </span>
        <a class="js-button-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=formjob'),"formjob")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-jobs') .'&nbsp;'. __('Job', 'js-jobs') ?></a>
    </span>
    <div id="js_ajax_pleasewait" style="display:none;"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pleasewait.gif"/></div>
    <p id="js_jobcopid" style="display:none;"><?php echo __('Job Copied Successfully', 'js-jobs'); ?></p>
    <div class="page-actions js-row no-margin">
        <label class="js-bulk-link button" onclick="return highlightAll();" for="selectall"><input type="checkbox" name="selectall" id="selectall" value=""><?php echo __('Select All', 'js-jobs') ?></label>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>" data-for="remove" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
        <?php
        $image1 = JSJOBS_PLUGIN_URL . "includes/images/up.png";
        $image2 = JSJOBS_PLUGIN_URL . "includes/images/down.png";
        if (jsjobs::$_data['sortby'] == 1) {
            $image = $image1;
        } else {
            $image = $image2;
        }
        ?>
        <span class="sort">
            <span class="sort-text"><?php echo __('Sort by', 'js-jobs'); ?>:</span>
            <span class="sort-field"><?php echo wp_kses(JSJOBSformfield::select('sorting', $categoryarray, jsjobs::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')), JSJOBS_ALLOWED_TAGS); ?></span>
            <a class="sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(jsjobs::$_data['sortby']); ?>"><img id="sortingimage" src="<?php echo esc_url($image); ?>" /></a>
        </span>
        <script >
            function changeSortBy() {
                var value = jQuery('a.sort-icon').attr('data-sortby');
                var img = '';
                if (value == 1) {
                    value = 2;
                    img = jQuery('a.sort-icon').attr('data-image2');
                } else {
                    img = jQuery('a.sort-icon').attr('data-image1');
                    value = 1;
                }
                jQuery("img#sortingimage").attr('src', img);
                jQuery('input#sortby').val(value);
                jQuery('form#jsjobsform').submit();
            }
            jQuery('a.sort-icon').click(function (e) {
                e.preventDefault();
                changeSortBy();
            });
            function changeCombo() {
                jQuery("input#sorton").val(jQuery('select#sorting').val());
                changeSortBy();
            }
        </script>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_job"),"job")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchtitle', jsjobs::$_data['filter']['searchtitle'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchcompany', jsjobs::$_data['filter']['searchcompany'], array('class' => 'inputbox', 'placeholder' => __('Company','js-jobs') .' '. __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('searchjobcategory', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo('kb'), jsjobs::$_data['filter']['searchjobcategory'], __('Select','js-jobs') .' '. __('Category', 'js-jobs'), array('class' => 'inputbox default-hidden')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('searchjobtype', JSJOBSincluder::getJSModel('jobtype')->getJobtypeForCombo('kb'), jsjobs::$_data['filter']['searchjobtype'], __('Select','js-jobs') .' '. __('Job Type', 'js-jobs'), array('class' => 'inputbox default-hidden')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('location', jsjobs::$_data['filter']['location'], array('class' => 'inputbox default-hidden', 'placeholder' => __('Location', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('datestart', jsjobs::$_data['filter']['datestart'], array('class' => 'custom_date default-hidden', 'autocomplete' => 'off', 'placeholder' => __('Date Start', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('dateend', jsjobs::$_data['filter']['dateend'], array('class' => 'custom_date default-hidden', 'autocomplete' => 'off', 'placeholder' => __('Date End', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getListingStatus(), jsjobs::$_data['filter']['status'], __('Select Status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="filterbutton">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('sortby', jsjobs::$_data['sortby']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('sorton', jsjobs::$_data['sorton']), JSJOBS_ALLOWED_TAGS); ?>
        <span id="showhidefilter"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/filter-down.png"/></span>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_job")); ?>">
            <?php
            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            foreach (jsjobs::$_data[0] AS $job) {
                if (isset($job->logo) && $job->logo != '') {
                    $wpdir = wp_upload_dir();
                    $logo = $wpdir['baseurl'] . '/' . $data_directory.'/data/employer/comp_'.$job->companyid.'/logo/'. $job->logo;
                } else {
                    $logo = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                }
                ?>
                <div id="job_<?php echo esc_attr($job->id); ?>" class="job-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data js-row no-margin">
                        <span id="selector_<?php echo esc_attr($job->id); ?>" class="selector"><input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($job->id); ?>);" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($job->id); ?>" /></span>
                        <div class="item-icon"><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=formjob&jsjobsid='.$job->id),"formjob")); ?>"><img src="<?php echo esc_url($logo); ?>" /></a></div>
                        <div class="item-details">
                            <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                <span class="value"><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=formjob&jsjobsid='.$job->id),"formjob")); ?>"><?php echo esc_html($job->title); ?></a></span>
                                <div class="flag-and-type">
                                    <span class="buttonu">
                                        <?php echo esc_html(__($job->jobtypetitle,'js-jobs')); ?>
                                    </span>
                                    <?php
                                    if ($job->status == 0) {
                                        echo '<span class="flag pending">' . __('Pending', 'js-jobs') . '</span>';
                                    } elseif ($job->status == 1) {
                                        echo '<span class="flag approved">' . __('Approved', 'js-jobs') . '</span>';
                                    } elseif ($job->status == -1) {
                                        echo '<span class="flag rejected">' . __('Rejected', 'js-jobs') . '</span>';
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                                <span class="heading"><?php echo esc_html(__(jsjobs::$_data['fields']['company'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html($job->companyname); ?></span>
                            </div>
                            <div class="item-values js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">
                                <span class="heading"><?php echo esc_html(__(jsjobs::$_data['fields']['jobcategory'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html(__($job->cat_title,'js-jobs')); ?></span>
                            </div>
                            <div class="item-values js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                <span class="heading"><?php echo __('Location', 'js-jobs') . ': '; ?></span><span class="value"><?php echo wp_kses(JSJOBSincluder::getJSModel('city')->getLocationDataForView($job->city), JSJOBS_ALLOWED_TAGS); ?></span>
                            </div>
                            <?php
                            $print = true; 
                            $startdate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($job->startpublishing));
                            $enddate = date_i18n('Y-m-d',jsjobslib::jsjobs_strtotime($job->stoppublishing));
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
                            <?php if($job->status == 1){ ?>
                            <span class="bigupper-jobtotal" style="padding:4px 8px;<?php echo esc_attr($publishstyle); ?>"><?php echo esc_html($publishstatus); ?></span>
                            <?php } ?>
                            <?php
                            if ($print) { ?>
                                <span class="bigupper-jobtotal noofjobs"><?php echo esc_html($job->noofjobs) . " " . __('jobs', 'js-jobs'); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="for_ajax_only_<?php echo esc_attr($job->id); ?>">
                        <div id="item-actions" class="item-actions js-row no-margin">
                            <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding">
                                <span class="heading"><?php echo __('Posted', 'js-jobs') . ': '; ?></span><span class="item-action-text"><?php echo esc_html(date_i18n($dateformat, jsjobslib::jsjobs_strtotime($job->created))); ?></span>
                            </div>
                            <div class="item-values js-col-lg-10 js-col-md-10 js-col-xs-12 no-padding">
                                <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&action=jsjobtask&task=remove&callfrom=1&jsjobs-cb[]='.$job->id),'delete-job')); ?>" onclick="return confirm('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>"/><?php echo __('Delete', 'js-jobs'); ?></a>
                                <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job&action=jsjobtask&callfrom=1&task=jobenforcedelete&jobid='.$job->id),'delete-job')); ?>" onclick="return confirmdelete('<?php echo __('This will delete every thing about this record','js-jobs').'. '.__('Are you sure to delete','js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/force-delete.png" /><?php echo __('Force Delete', 'js-jobs') ?></a>
                                <?php   
                                $config_array = jsjobs::$_data['config'];
                                ?>
                                <a class="js-action-link button" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_jobapply&jsjobslt=jobappliedresume&jobid='.$job->id)); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/ad_resume.png" /><?php echo __('Applied Resume', 'js-jobs') . " (" . esc_html($job->totalresume) . ")" ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'job_remove'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('callfrom', 1), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-job')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        $link[] = array(
                    'link' => wp_nonce_url(admin_url('admin.php?page=jsjobs_job&jsjobslt=formjob'),"formjob"),
                    'text' => __('Add New','js-jobs') .' '. __('Job','js-jobs')
                );
        JSJOBSlayout::getNoRecordFound($msg,$link);
    }
    ?>
    </div>
    </div>
