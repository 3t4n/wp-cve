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
<script type="text/javascript">

    jQuery(document).ready(function () {

        jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
        jQuery("div.resume-container").each(function () {
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
        if (jQuery("div#resume_" + id + " div span input:checked").length > 0) {
            showBorder(id);
        } else {
            hideBorder(id);
        }
    }
    function showBorder(id) {
        jQuery("div#resume_" + id).addClass('blue');
    }
    function hideBorder(id) {
        jQuery("div#resume_" + id).removeClass('blue');
    }
    function highlightAll() {
        if (jQuery("span.selector input").is(':checked') == false) {
            jQuery("span.selector").css('display', 'none');
            jQuery("div.resume-container div#item-data").css('border', '1px solid #dedede');
            jQuery("div.resume-container div#item-actions").css('border', '1px solid #dedede');
            jQuery("div.resume-container div#item-actions").css('border-top', 'none');
        }
        if (jQuery("span.selector input").is(':checked') == true) {
            jQuery("span.selector").css('display', 'block');
            jQuery("div.resume-container div#item-data").css('border', '1px solid rgb(78, 140, 245)');
            jQuery("div.resume-container div#item-data").css('border-bottom', '1px solid #dedede');
            jQuery("div.resume-container div#item-actions").css('border', '1px solid rgb(78, 140, 245)');
            jQuery("div.resume-container div#item-actions").css('border-top', 'none');
        }
    }
    function checkAllSelection() {
        var totalItems = jQuery("div.resume-container").length;
        jQuery("div.resume-container").each(function () {
        });
    }

    function resetFrom() {
        document.getElementById('searchtitle').value = '';
        document.getElementById('searchname').value = '';
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
    (object) array('id' => 1, 'text' => __('Application title', 'js-jobs')),
    (object) array('id' => 2, 'text' => __('First name', 'js-jobs')),
    (object) array('id' => 3, 'text' => __('Category', 'js-jobs')),
    (object) array('id' => 4, 'text' => __('Job type', 'js-jobs')),
    (object) array('id' => 5, 'text' => __('Location', 'js-jobs')),
    (object) array('id' => 6, 'text' => __('Created', 'js-jobs')),
    (object) array('id' => 7, 'text' => __('Status', 'js-jobs'))
);
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Resume', 'js-jobs') ?>
    </span>
    <div class="page-actions js-row no-margin">
        <label class="js-bulk-link button" onclick="return highlightAll();" for="selectall"><input type="checkbox" name="selectall" id="selectall" value=""><?php echo __('Select All', 'js-jobs') ?></label>
        <a class="js-bulk-link button multioperation" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo __('Are you sure to delete', 'js-jobs') .' ?'; ?>" data-for="removeResume" href="#"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-jobs') ?></a>
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
        <script type="text/javascript">
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

            function addBadgeToObject(cid, specialtype, expiry) {
                var html = '';
            }

        </script>
    </div>
    <form class="js-filter-form" name="jsjobsform" id="jsjobsform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumes"),"resume")); ?>">
        <?php echo wp_kses(JSJOBSformfield::text('searchtitle', jsjobs::$_data['filter']['searchtitle'], array('class' => 'inputbox', 'placeholder' => __('Title', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('searchname', jsjobs::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('searchjobcategory', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo('kb'), jsjobs::$_data['filter']['searchjobcategory'], __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('searchjobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), jsjobs::$_data['filter']['searchjobtype'], __('Select','js-jobs') .'&nbsp;'. __('Job Type', 'js-jobs'), array('class' => 'inputbox default-hidden')), JSJOBS_ALLOWED_TAGS); ?>
        <?php //echo wp_kses(JSJOBSformfield::select('searchjobsalaryrange', JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(), jsjobs::$_data['filter']['searchjobsalaryrange'], __('Select','js-jobs') .'&nbsp;'. __('Salary Range', 'js-jobs'), array('class' => 'inputbox default-hidden')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('datestart', jsjobs::$_data['filter']['datestart'], array('class' => 'custom_date default-hidden', 'autocomplete' => 'off', 'placeholder' => __('Date Start', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::text('dateend', jsjobs::$_data['filter']['dateend'], array('class' => 'custom_date default-hidden', 'autocomplete' => 'off', 'placeholder' => __('Date End', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::select('status', JSJOBSincluder::getJSModel('common')->getListingStatus(), jsjobs::$_data['filter']['status'], __('Select Status', 'js-jobs'), array('class' => 'inputbox')), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('JSJOBS_form_search', 'JSJOBS_SEARCH'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="filterbutton">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('btnsubmit', __('Search', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::button('reset', __('Reset', 'js-jobs'), array('class' => 'button', 'onclick' => 'resetFrom();')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('sortby', jsjobs::$_data['sortby']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('sorton', jsjobs::$_data['sorton']), JSJOBS_ALLOWED_TAGS); ?>
        <span id="showhidefilter"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/filter-down.png"/></span>
    </form>
    <hr class="listing-hr" />
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <form id="jsjobs-list-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_resume")); ?>">
            <?php
            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            foreach (jsjobs::$_data[0] AS $resume) {                
                if (isset($resume->photo) && $resume->photo != '') {
                    $wpdir = wp_upload_dir();
                    $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->id. '/photo/' . $resume->photo;
                } else {
                    $photo = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                }
                $approved = ($resume->status == 1) ? '<span style="color:Green">' . __('Approved', 'js-jobs') . '</span>' : '<span style="color:Green">' . __('Rejected', 'js-jobs') . '</span>';
                ?>
                <div id="resume_<?php echo esc_attr($resume->id); ?>" class="resume-container js-col-lg-12 js-col-md-12 no-padding">
                    <div id="item-data" class="item-data item-data-resume js-row no-margin">
                        <span id="selector_<?php echo esc_attr($resume->id); ?>" class="selector"><input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($resume->id); ?>);" class="jsjobs-cb" id="jsjobs-cb" name="jsjobs-cb[]" value="<?php echo esc_attr($resume->id); ?>" /></span>
                        <div class="item-icon js_circle">
                            <div class="profile">
                                <a class="js-anchor" href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid='.$resume->id)); ?>">
                                    <span class="js-border">
                                        <img src="<?php echo esc_url($photo); ?>"  />
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="item-details">
                            <div class="item-title js-col-lg-12 js-col-md-12 js-col-xs-8 no-padding">
                                <span class="value"><a class="" href="<?php echo esc_url(admin_url("admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid=".$resume->id));?>"> <?php echo esc_html($resume->application_title); ?> </a></span>
                                <div class="flag-and-type">
                                    <?php
                                    if ($resume->status == 0) {
                                        echo '<span class="flag pending">' . __('Pending', 'js-jobs') . '</span>';
                                    } elseif ($resume->status == 1) {
                                        echo '<span class="flag approved">' . __('Approved', 'js-jobs') . '</span>';
                                    } elseif ($resume->status == -1) {
                                        echo '<span class="flag rejected">' . __('Rejected', 'js-jobs') . '</span>';
                                    }
                                    ?> 
                                </div>
                            </div>

                            <div class="jsjobs-row-wrapper">
                                <div class="jstab-half item-values js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                                    <span class="heading"><?php echo __('Name', 'js-jobs') . ': '; ?></span><span class="value"><?php echo esc_html($resume->first_name) . ' ' . esc_html($resume->last_name); ?></span>
                                </div>
                                <div class="jstab-half item-values js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                                    <span class="heading"><?php echo esc_html(__(jsjobs::$_data['fields']['job_category'], 'js-jobs')) . ': '; ?></span><span class="value"><?php echo esc_html(__($resume->cat_title,'js-jobs')); ?></span>
                                </div>
                                <div class="jstab-half item-values js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                                    <span class="heading">
                                    <?php 
                                        if(!isset(jsjobs::$_data['fields']['desired_salary'])){
                                            jsjobs::$_data['fields']['desired_salary'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
                                        }                                    
                                        echo esc_html(__(jsjobs::$_data['fields']['desired_salary'], 'js-jobs')) . ': '; ?>
                                    </span><span class="value"><?php echo wp_kses(JSJOBSincluder::getJSModel('common')->getSalaryRangeView($resume->symbol, $resume->rangestart, $resume->rangeend, $resume->rangetype), JSJOBS_ALLOWED_TAGS); ?></span>
                                </div>
                                <div class="jstab-half item-values js-col-lg-4 js-col-md-4 js-col-xs-12 no-padding">
                                    <span class="heading"><?php echo esc_html(__(jsjobs::$_data["fields"]["jobtype"], "js-jobs")) . ': '; ?></span><span class="value"><?php echo esc_html(__($resume->jobtypetitle,'js-jobs')); ?></span>
                                </div>
                            </div>
                            <div class="jsjobs-row-wrapper">
                                <div class="item-values js-col-lg-8 js-col md-8 js-col-xs-12 no-padding">
                                    <span class="heading"><?php echo __('Location', 'js-jobs') . ': '; ?></span><span class="value"><?php echo wp_kses(JSJOBSincluder::getJSModel('city')->getLocationDataForView($resume->city), JSJOBS_ALLOWED_TAGS); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="item-actions" class="item-actions js-row no-margin">
                        <div class="item-text-block js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding">
                            <span class="heading"><?php echo __('Created', 'js-jobs') . ': '; ?></span><span class="item-action-text"><?php echo esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($resume->created))); ?></span>
                        </div>
                        <div class="item-values js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding">
                            <?php 
                                $config_array = jsjobs::$_data['config'];
                             ?>
                            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_resume&task=removeresume&jsjobs-cb[]='.$resume->id.'&action=jsjobtask&callfrom=1'),'delete-resume')) ;?>" onclick="return confirm('<?php echo __('Are you sure to delete','js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(JSJOBSMessages::getMSelectionEMessage()); ?>"><?php echo __('Delete', 'js-jobs'); ?></a>
                            <a class="js-action-link button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_resume&task=resumeEnforceDelete&action=jsjobtask&resumeid='.$resume->id.'&callfrom=1'),'delete-resume')) ;?>" onclick="return confirmdelete('<?php echo __('Are you sure to force delete', 'js-jobs').' ?'; ?>');"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/force-delete.png" /><?php echo __('Enforce Delete', 'js-jobs') ?></a>
                            <a class="js-action-link button" href="admin.php?page=jsjobs_resume&jsjobslt=formresume&jsjobsid=<?php echo esc_attr($resume->id); ?>" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/edit-small.png" alt="edit"><?php echo __('Edit', 'js-jobs'); ?></a>
                            <a class="js-action-link button" href="admin.php?page=jsjobs_resume&jsjobslt=viewresume&jsjobsid=<?php echo esc_attr($resume->id); ?>" ><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/ad_resume.png" alt="view"><?php echo __('View', 'js-jobs'); ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('action', 'resume_remove'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('task', ''), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('callfrom', 1), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('delete-resume')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
    </div>
    </div>
