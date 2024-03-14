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
<div id="jsjobs-main-up-wrapper">
<?php
$jobs = JSJOBSincluder::getObjectClass('jobslist');

$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $radiustype = array(
        (object) array('id' => '1', 'text' => __('Meters', 'js-jobs')),
        (object) array('id' => '2', 'text' => __('Kilometers', 'js-jobs')),
        (object) array('id' => '3', 'text' => __('Miles', 'js-jobs')),
        (object) array('id' => '4', 'text' => __('Nautical Miles', 'js-jobs')),
    );

    $location = 'left';
    $borderradius = '0px 8px 8px 0px';
    $padding = '5px 10px 5px 20px';
    $searchjobtag = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('searchjobtag');
    switch ($searchjobtag) {
        case 1: // Top left
            $top = "30px";
            $left = "0px";
            $right = "none";
            $bottom = "none";
            break;
        case 2: // Top right
            $top = "30px";
            $left = "none";
            $right = "0px";
            $bottom = "none";
            $location = 'right';
            $borderradius = '8px 0px 0px 8px';
            $padding = '5px 20px 5px 10px';
            break;
        case 3: // middle left
            $top = "48%";
            $left = "0px";
            $right = "none";
            $bottom = "none";
            break;
        case 4: // middle right
            $top = "48%";
            $left = "none";
            $right = "0px";
            $bottom = "none";
            $location = 'right';
            $borderradius = '8px 0px 0px 8px';
            $padding = '5px 20px 5px 10px';
            break;
        case 5: // bottom left
            $top = "none";
            $left = "0px";
            $right = "none";
            $bottom = "30px";
            break;
        case 6: // bottom right
            $top = "none";
            $left = "none";
            $right = "0px";
            $bottom = "30px";
            $location = 'right';
            $borderradius = '8px 0px 0px 8px';
            $padding = '5px 20px 5px 10px';
            break;
    }
    $html = '<style>
                div#refineSearch{opacity:0;position:fixed;top:' . $top . ';left:' . $left . ';right:' . $right . ';bottom:' . $bottom . ';padding:' . $padding . ';background:rgba(149,149,149,.50);z-index:9999;border-radius:' . $borderradius . ';}
                div#refineSearch img{margin-' . $location . ':10px;display:inline-block;}
                div#refineSearch a{color:#ffffff;text-decoration:none;}
            </style>';
    ?>
    <div id="jsjobs-wrapper">
        <?php
        $html .= '<div id="refineSearch">';
        if ($location == 'right') {
            $html .= '<img src="' . JSJOBS_PLUGIN_URL . 'includes/images/searchicon.png" /><a href="#">' . __("Search", 'js-jobs') . '</a>';
        } else {
            $html .= '<a href="#">' . __("Search", 'js-jobs') . '</a><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/searchicon.png" />';
        }
        $html .= '
                </div>
                <script >
                    jQuery(document).ready(function(){
                        jQuery("div#refineSearch").css("' . $location . '","-"+(jQuery("div#refineSearch a").width() + 25)+"px");
                        jQuery("div#refineSearch").css("opacity",1);
                        jQuery("div#refineSearch").hover(
                            function(){
                                jQuery(this).animate({' . $location . ': "+="+(jQuery("div#refineSearch a").width() + 25)}, 1000);
                            },
                            function(){
                                jQuery(this).animate({' . $location . ': "-="+(jQuery("div#refineSearch a").width() + 25)}, 1000);
                            }
                        );
                    });
                </script>';
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
        $heading = __('Newest Jobs', 'js-jobs');
        if (isset(jsjobs::$_data['filter']['company']) && is_numeric(jsjobs::$_data['filter']['company'])) {
            $heading = __('Company Jobs', 'js-jobs');
        }
        if (isset(jsjobs::$_data['filter']['category']) && is_numeric(jsjobs::$_data['filter']['category'])) {
            $heading = __('Jobs By Category', 'js-jobs');
        }
        if (isset(jsjobs::$_data['filter']['jobtype']) && is_numeric(jsjobs::$_data['filter']['jobtype'])) {
            $heading = __('Jobs By Types', 'js-jobs');
        }
        if (isset(jsjobs::$_data['issearchform']) && jsjobs::$_data['issearchform']==1) {
            $heading = __('Search Result', 'js-jobs');
        }
        if (isset(jsjobs::$_data['filter']['fromtaglink'])) {
            $heading = __('Jobs By Tag', 'js-jobs') . ' [' . jsjobs::$_data['filter']['fromtaglink'] . ']';
        }
        ?>
        <div class="page_heading"><?php echo esc_html($heading); ?>
            <?php if(JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('show_total_number_of_jobs') == 1) { ?>
                <div class="jsjobs-job-listing-total-jobs" >
                    <?php echo __('Total Jobs','js-jobs').':&nbsp;'; ?>
                    <span><?php echo esc_html(jsjobs::$_data['total']);?></span>
                </div>
            <?php } ?>
        </div>
        <?php
        $currentuser = JSJOBSincluder::getObjectClass('user');
        if (!$currentuser->isguest() && $currentuser->isjobseeker()){
            $search_job_showsave = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('search_job_showsave');
        }else{
            $search_job_showsave = 0;
        }
            if (isset(jsjobs::$_data['issearchform']) && jsjobs::$_data['issearchform'] == 1 && $search_job_showsave == 1) {
                ?>
                <?php
            }        
            jsjobs::$_data['jsjobs_pageid'] = get_the_ID();
        $jobshtml = $jobs->printjobs(jsjobs::$_data[0]);
        if(empty(jsjobs::$_data[0])){
            if($jobshtml != ''){
                echo wp_kses($jobshtml, JSJOBS_ALLOWED_TAGS);
            }else{
                JSJOBSLayout::getNoRecordFound();
            }
        }else{
            echo wp_kses($jobshtml, JSJOBS_ALLOWED_TAGS);            
        }
        ?>
    </div>
    <div id="jsjob-popup-background"></div>
    <div id="jsjobs-listpopup">
        <span class="popup-title"><span class="title"></span><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png"></span>
        <div class="jsjob-contentarea"></div>
    </div>
    <div id="jsjob-search-popup">
        <span class="popup-title"><?php echo __('Refine Search', 'js-jobs'); ?><img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png"></span>
        <form class="job_form" id="job_form" method="post" action="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs'))); ?>">
            <div class="jsjob-contentarea">
                <?php

                function getRow($title, $value, $i) {
                    $html = '';
                    if ($i == 9)
                        $html .='<div id="jsjobs-hide">';
                    if (($i % 2) != 0)
                        $html .='<div class="jsjobs-searchwrapper">';
                    $html .= '<div class="js-col-md-6 jsjob-refine-wrapper">
                        <div class="js-col-md-12 js-searchform-title">' . $title . '</div>
                        <div class="js-col-md-12 js-searchform-value">' . $value . '</div>
                    </div>';
                    if (($i % 2) == 0)
                        $html .='</div>';
                    return $html;
                }

                $i = 0;
                foreach (jsjobs::$_data[2] AS $field) {
                    switch ($field->field) {
                        case 'metakeywords':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::text('metakeywords', isset(jsjobs::$_data['filter']['metakeywords']) ? jsjobs::$_data['filter']['metakeywords'] : '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobtitle':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::text('jobtitle', isset(jsjobs::$_data['filter']['jobtitle']) ? jsjobs::$_data['filter']['jobtitle'] : '', array('class' => 'inputbox','placeholder' => __('Title', 'js-jobs')));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'company':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('company[]', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data['filter']['company']) ? jsjobs::$_data['filter']['company'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobcategory':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('category[]', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data['filter']['category']) ? jsjobs::$_data['filter']['category'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'careerlevel':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('careerlevel[]', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data['filter']['careerlevel']) ? jsjobs::$_data['filter']['careerlevel'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobshift':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('shift[]', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data['filter']['shift']) ? jsjobs::$_data['filter']['shift'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'gender':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data['filter']['gender']) ? jsjobs::$_data['filter']['gender'] : '', __('Select','js-jobs') .' '. __('Gender', 'js-jobs'), array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobtype':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('jobtype[]', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data['filter']['jobtype']) ? jsjobs::$_data['filter']['jobtype'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobstatus':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('jobstatus[]', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data['filter']['jobstatus']) ? jsjobs::$_data['filter']['jobstatus'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'workpermit':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('workpermit[]', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data['filter']['workpermit']) ? jsjobs::$_data['filter']['workpermit'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'jobsalaryrange':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data['filter']['currencyid']) ? jsjobs::$_data['filter']['currencyid'] : '', __("Select currency", "js-jobs"), array('class' => 'inputbox sal'));
                            $value .= JSJOBSformfield::select('salaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data['filter']['salaryrangestart']) ? jsjobs::$_data['filter']['salaryrangestart'] : '', __("Select range start", "js-jobs"), array('class' => 'inputbox sal'));
                            $value .= JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data['filter']['salaryrangeend']) ? jsjobs::$_data['filter']['salaryrangeend'] : '', __("Select range end", "js-jobs"), array('class' => 'inputbox sal'));
                            $value .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data['filter']['salaryrangetype']) ? jsjobs::$_data['filter']['salaryrangetype'] : '', __("Select range type", "js-jobs"), array('class' => 'inputbox sal'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'heighesteducation':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('highesteducation[]', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data['filter']['highesteducation']) ? jsjobs::$_data['filter']['highesteducation'] : '', '', array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'city':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::text('city','', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'requiredtravel':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(), isset(jsjobs::$_data['filter']['requiredtravel']) ? jsjobs::$_data['filter']['requiredtravel'] : '', __('Select one', 'js-jobs'), array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'duration':
                            $title = __($field->fieldtitle, 'js-jobs');
                            $value = JSJOBSformfield::text('duration', isset(jsjobs::$_data['filter']['duration']) ? jsjobs::$_data['filter']['duration'] : '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        case 'map':
                            ?>
                            <div class="jsjobs-searchwrapper">
                                <div class="js-col-md-12 jsjob-refine-wrapper">
                                    <div class="js-col-md-12 js-searchform-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?></div>
                                    <div class="js-col-md-12 js-searchform-value">
                                        <div id="map_container">
                                            <div id="map">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $title = __('Longitude', 'js-jobs');
                            $value = JSJOBSformfield::text('longitude', isset(jsjobs::$_data['filter']['longitude']) ? jsjobs::$_data['filter']['longitude'] : '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            $title = __('Latitude', 'js-jobs');
                            $value = JSJOBSformfield::text('latitude', isset(jsjobs::$_data['filter']['latitude']) ? jsjobs::$_data['filter']['latitude'] : '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            $title = __('Radius', 'js-jobs');
                            $value = JSJOBSformfield::text('radius', isset(jsjobs::$_data['filter']['radius']) ? jsjobs::$_data['filter']['radius'] : '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            $title = __('Radius Length Type', 'js-jobs');
                            $value = JSJOBSformfield::select('radiuslengthtype', $radiustype, jsjobs::$_configuration['defaultradius'], '', array('class' => 'inputbox'));
                            echo wp_kses(getRow($title, $value, ++$i), JSJOBS_ALLOWED_TAGS);
                            break;
                        default:
                            $k = 0;
                            JSJOBSincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $k);
                            break;
                    }
                }
                if (($i % 2) != 0) {
                    echo '</div>';
                }
                if ($i >= 9) {
                    echo '</div>';
                }
                if ($i > 8) {
                    ?> 
                    <div class="jsjobs-searchwrapper">
                        <div class="js-col-md-12 jsjob-refine-wrapper">
                            <div class="js-col-md-12" id="jsjobs-showmore"><?php echo __('Show More', 'js-jobs'); ?></div>
                        </div>
                    </div>
                <?php } ?>
                <div id="jsjobs-refine-actions">
                    <div class="js-col-md-12 bottombutton js-form">
                        <button class="search_button" id="submit_btn" onclick="this.form.submit();"><?php echo __('Search','js-jobs'); ?></button>
                        <button class="search_button" id="reset_btn" onclick="resetpopupform();"><?php echo __('Reset','js-jobs'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script >
        jQuery(document).ready(function ($) {
            jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
            //hide/Show popup
            $("div#refineSearch").click(function () {
                showPopup();
            });

            <?php if(isset(jsjobs::$_data[2][$mapfield]) && jsjobs::$_data[2][$mapfield]->published == 1){ ?>
                //Load map
                loadMap();
            <?php } ?>

            //show more
            $("div#jsjobs-showmore").click(function () {
                $("div#jsjobs-showmore").hide();
                $("div#jsjobs-hide").css("display", "block");
            });
        });

        jQuery(window).scroll(function () {
            if (jQuery(window).scrollTop() > (jQuery("div#jsjobs-wrapper").height() - 500)) {
                var scrolltask = jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr("data-scrolltask");
                var offset = jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr("data-offset");
                var showmore = jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr("data-showmore");
                if(showmore == 1){
                    return;
                }
                if (scrolltask != null && scrolltask != '' && scrolltask != "undefined") {
                    jQuery("div#jsjobs-wrapper").find("a.scrolltask").remove();
                    var s_ajaxurl = ajaxurl + "?pagenum=" + offset;
                    var searchcriteria = '<?php if(isset(jsjobs::$_data['filter'])) echo jsjobslib::jsjobs_safe_encoding(json_encode(jsjobs::$_data['filter'])); ?>';
                    var jobtype = '<?php echo (isset(jsjobs::$_data['vars']['jobtype']) && jsjobs::$_data['vars']['jobtype'] != null) ? jsjobs::$_data['vars']['jobtype'] : ''; ?>';
                    var category = '<?php echo (isset(jsjobs::$_data['vars']['category']) && jsjobs::$_data['vars']['category'] != null) ? jsjobs::$_data['vars']['category'] : ''; ?>';
                    var company = '<?php echo (isset(jsjobs::$_data['vars']['company']) && jsjobs::$_data['vars']['company'] != null) ? jsjobs::$_data['vars']['company'] : ''; ?>';
                    var tags = '<?php echo (isset(jsjobs::$_data['vars']['tags']) && jsjobs::$_data['vars']['tags'] != null) ? jsjobs::$_data['vars']['tags'] : ''; ?>';
                    var city = '<?php echo ((isset($_GET['city']) && $_GET['city'] != null) ? $_GET['city'] : (isset(jsjobs::$_data['filter']['city']) && jsjobs::$_data['filter']['city'] != null)) ? jsjobs::$_data['filter']['city']: '' ; ?>';
                    var state = '<?php echo (isset($_GET['state']) && $_GET['state'] != null) ? $_GET['state'] : ''; ?>';
                    var country = '<?php echo (isset($_GET['country']) && $_GET['country'] != null) ? $_GET['country'] : ''; ?>';
                    jQuery("div#jsjobs-wrapper").append('<img id="jsjobs-loading-icon" src="<?php echo JSJOBS_PLUGIN_URL.'includes/images/load.gif'; ?>" />');
                    jQuery.get(s_ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'job', task: scrolltask, 'ajaxsearch': searchcriteria, jobtype: jobtype, category: category, tags: tags,company: company, city:city, state:state, country:country,jsjobs_pageid:<?php echo get_the_ID();?>}, function (data) {
                        jQuery("div#jsjobs-wrapper").append(data);
                        jQuery("div#jsjobs-wrapper").find('img#jsjobs-loading-icon').remove();
                    });
                }
            }
        });

        function showmorejobs(){
            jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr('data-showmore','0');
            jQuery(window).scroll();
            jQuery('a#showmorejobs').remove();
        }

    </script>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
<div style="display:none;" id="jsjobs_permalink">
    <?php
    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));
    echo esc_url($current_url);
    ?>
</div>
</div>
