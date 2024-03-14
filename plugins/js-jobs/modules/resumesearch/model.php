<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumeSearchModel {

    function __construct() {
        
    }

    function getSearchResume_Widget($title, $showtitle, $apptitle, $name, $natinality, $gender, $iamavailable, $category, $jobtype, $salaryrange, $heighesteducation, $experience, $columnperrow) {

        if ($columnperrow <= 0)
            $columnperrow = 1;

        $width = round(100 / $columnperrow);

        $style = "style='width:" . $width . "%'";
        $html = '
                <div id="jsjobs_mod_wrapper">';
        if ($showtitle == 1) {
            $html .= '<div id="jsjobs-mod-heading"> ' . $title . ' </div>';
        }
        $html .='<form class="resume_form" id="resume_form" method="post" action="' . wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'jsjobspageid'=>jsjobs::getPageid())),"resume") . '">';

        if ($apptitle == 1) {
            $title = __('Application Title', 'js-jobs');
            $value = JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        if ($name == 1) {
            $title = __('Name', 'js-jobs');
            $value = JSJOBSformfield::text('first_name', isset(jsjobs::$_data[0]->first_name) ? jsjobs::$_data[0]->first_name : '', array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($natinality == 1) {
            $title = __('Nationality', 'js-jobs');
            $value = JSJOBSformfield::select('nationality', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]->nationality) ? jsjobs::$_data[0]->nationality : '', __('Select','js-jobs') .'&nbsp;'. __('Nationality', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($gender == 1) {
            $title = __('Gender', 'js-jobs');
            $value = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data[0]->gender) ? jsjobs::$_data[0]->gender : '', __('Select','js-jobs') .'&nbsp;'. __('Gender', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($iamavailable == 1) {
            
        }
        if ($category == 1) {
            $title = __('Category', 'js-jobs');
            $value = JSJOBSformfield::select('category', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data[0]->category) ? jsjobs::$_data[0]->category : '', __('Select','js-jobs') .'&nbsp;'. __('Category', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($jobtype == 1) {
            $title = __('Job Type', 'js-jobs');
            $value = JSJOBSformfield::select('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]->jobtype) ? jsjobs::$_data[0]->jobtype : '', __('Select job type', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($salaryrange == 1) {
            $title = __('Salary Range', 'js-jobs');
            $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]->currencyid) ? jsjobs::$_data[0]->currencyid : '', __('Select','js-jobs') .'&nbsp;'. __('Currency', 'js-jobs'), array('class' => 'inputbox sal'))
                    . JSJOBSformfield::select('salaryrangefrom', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrange) ? jsjobs::$_data[0]->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('start', 'js-jobs'), array('class' => 'inputbox sal'))
                    . JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrange) ? jsjobs::$_data[0]->salaryrange : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range','js-jobs') .'&nbsp;'. __('End', 'js-jobs'), array('class' => 'inputbox sal'))
                    . JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]->salaryrangetype) ? jsjobs::$_data[0]->salaryrangetype : '', __('Select','js-jobs') .'&nbsp;'. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($heighesteducation == 1) {
            $title = __('Highest Education', 'js-jobs');
            $value = JSJOBSformfield::select('highesteducation', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->highesteducation) ? jsjobs::$_data[0]->highesteducation : '', __('Select','js-jobs') .'&nbsp;'. __('Highest Education', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }
        if ($experience == 1) {
            $title = __('Experience', 'js-jobs');
            $value = JSJOBSformfield::text('experience', isset(jsjobs::$_data[0]->experience) ? jsjobs::$_data[0]->experience : '', array('class' => 'inputbox'));
            $value = JSJOBSformfield::select('experience', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->experience) ? jsjobs::$_data[0]->experience : '', __('Select','js-jobs') .'&nbsp;'. __('Experience', 'js-jobs'), array('class' => 'inputbox'));
            $html .= '<div class="js-col-md-12 js-mod-valwrapper" ' . $style . '>
                <div class="js-form-mod-title">' . $title . '</div>
                <div class="js-form-mod-value">' . $value . '</div>
            </div>';
        }

        $html .= '<div class="js-col-md-12 js-mod-valwrapper">
                        <div class="js-col-md-6 bottombutton">
                            ' . JSJOBSformfield::submitbutton('save', __('Resume Search', 'js-jobs'), array('class' => 'button')) . '
                        </div>
                        <div class="js-col-md-6 bottombutton">
                            <a class="anchor" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesavesearch', 'jsjobspageid'=>jsjobs::getPageid())) . '">
                                ' . __('Advance Search', 'js-jobs') . '
                            </a>

                        </div>                        
                    </div>
                </form>
                </div>
                ';

        return $html;
    }

    function getResumeSearchOptions() {
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(3);
    }

    function getMessagekey(){
        $key = 'resumesearch';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }
}

?>
