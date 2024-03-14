<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobSearchModel {

    function getSearchJobs_Widget($title, $showtitle, $fieldtitle, $category, $jobtype, $jobstatus, $salaryrange, $shift, $duration, $startpublishing, $stoppublishing, $company, $address, $columnperrow) {

        if ($columnperrow <= 0)
            $columnperrow = 1;
        $width = round(100 / $columnperrow);
        $style = "style='width:" . $width . "%'";

        $html = '
                <div id="jsjobs_mod_wrapper">';
        if ($showtitle == 1) {
            $html .= '<div id="jsjobs-mod-heading"> ' . $title . ' </div>';
        }
        $html .='<form class="job_form" id="job_form" method="post" action="' . wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'jsjobspageid'=>jsjobs::getPageid())),"job") . '">';

        if ($fieldtitle == 1) {
            $title = __('Title', 'js-jobs');
            $value = JSJOBSformfield::text('title', '', array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        if ($category == 1) {
            $title = __('Category', 'js-jobs');
            $value = JSJOBSformfield::select('category[]', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data[0]['filter']->category) ? jsjobs::$_data[0]['filter']->category : '', __('Select','js-jobs') .' '. __('Category', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        if ($jobtype == 1) {
            $title = __('Job Type', 'js-jobs');
            $value = JSJOBSformfield::select('jobtype[]', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]['filter']->jobtype) ? jsjobs::$_data[0]['filter']->jobtype : '', __('Select','js-jobs') .' '. __('Job Type', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($jobstatus == 1) {
            $title = __('Job Status', 'js-jobs');
            $value = JSJOBSformfield::select('jobstatus[]', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]['filter']->jobstatus) ? jsjobs::$_data[0]['filter']->jobstatus : '', __('Select','js-jobs') .' '. __('Job Status', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($salaryrange == 1) {
            $title = __('Salary Range', 'js-jobs');
            $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]['filter']->currencyid) ? jsjobs::$_data[0]['filter']->currencyid : '', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox sal'));
            $value .= JSJOBSformfield::select('salaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox sal'));
            $value .= JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox sal'));
            $value .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrangetype) ? jsjobs::$_data[0]['filter']->salaryrangetype : '', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($shift == 1) {
            $title = __('Shifts', 'js-jobs');
            $value = JSJOBSformfield::select('shift[]', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]['filter']->shift) ? jsjobs::$_data[0]['filter']->shift : '', __('Select','js-jobs') .' '. __('Shift', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($duration == 1) {
            $title = __('Duration', 'js-jobs');
            $value = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]['filter']->duration) ? jsjobs::$_data[0]['filter']->duration : '', array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($startpublishing == 1) {
            
        }
        if ($stoppublishing == 1) {
            
        }
        if ($company == 1) {
            $title = __('Company', 'js-jobs');
            $value = JSJOBSformfield::select('company[]', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]['filter']->company) ? jsjobs::$_data[0]['filter']->company : '', __('Select','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($address == 1) {
            $title = __('City', 'js-jobs');
            $value = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]['filter']->city) ? jsjobs::$_data[0]['filter']->city : '', array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        $html .= '<div class="js-col-md-12 js-mod-valwrapper">
                        <div class="js-col-md-6 bottombutton">                 
                            ' . JSJOBSformfield::submitbutton('save', __('Search Job', 'js-jobs'), array('class' => 'button')) . '
                        </div>
                        <div class="js-col-md-6 bottombutton">
                            <a class="anchor" href="' . jsjobs::makeUrl(array('jsjobsme'=>'jobsearch', 'jsjobslt'=>'jobsearch', 'jsjobspageid'=>jsjobs::getPageid())) . '"> 
                            ' . __('Advance Search', 'js-jobs') . '
                            </a>
                        </div>
                    </div>
                </form>
                </div>
                
        
            <script >
                function getTokenInput() {
                    var cityArray = "' . admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname") . '";
                    cityArray = cityArray+"&_wpnonce=<?php echo wp_create_nonce("address-data-by-cityname"); ?>";
                    jQuery("#city").tokenInput(cityArray, {
                        theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "' . __('Type In A Search Term', 'js-jobs') . '",
                        noResultsText: "' . __('No Results', 'js-jobs') . '",
                        searchingText: "' . __('Searching', 'js-jobs') . '"
                    });
                }
                jQuery(document).ready(function(){
                    getTokenInput();
                });
            </script>
            ';




        return $html;
    }

    function getJobSearchOptions() {
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(2);
    }
    
function getMessagekey(){
        $key = 'jobsearch';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }



}

?>
