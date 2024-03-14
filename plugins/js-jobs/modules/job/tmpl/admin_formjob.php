<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');

$config = jsjobs::$_configuration;
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$dateformat = $config['date_format'];
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
<style>
    div#map_container{
        width: 100%;
        height:<?php echo esc_attr(jsjobs::$_configuration['mapheight']) . 'px'; ?>;
    }
</style>
<?php
$lists = array();
$defaultCategory = JSJOBSincluder::getJSModel('common')->getDefaultValue('categories');
$defaultJobtype = JSJOBSincluder::getJSModel('common')->getDefaultValue('jobtypes');
$defaultJobstatus = JSJOBSincluder::getJSModel('common')->getDefaultValue('jobstatus');
$defaultShifts = JSJOBSincluder::getJSModel('common')->getDefaultValue('shifts');
$defaultEducation = JSJOBSincluder::getJSModel('common')->getDefaultValue('highesteducation');
$defaultSalaryrange = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrange');
$defaultSalaryrangeType = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrangetypes');
$defaultAge = JSJOBSincluder::getJSModel('common')->getDefaultValue('ages');
$defaultExperiences = JSJOBSincluder::getJSModel('common')->getDefaultValue('experiences');
$defaultCareerlevels = JSJOBSincluder::getJSModel('common')->getDefaultValue('careerlevels');
$defaultCurrencies = JSJOBSincluder::getJSModel('common')->getDefaultValue('currencies');
?>

<script >


    var companycall = 0;
    jQuery(document).ready(function () {
        jQuery('select#companyid').on('change', function() {
            companycall = companycall + 1;
            var companyid = this.value;
            getdepartments('departmentid', companyid);
        });
        jQuery('select#companyid').change();
        jQuery("div#full_background,img#popup_cross").click(function (e) {
            jQuery("div#popup_main").slideUp('slow', function () {
                jQuery("div#full_background").hide();
            });
        });
    });
    //End PoPuP
</script>

<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_job')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo esc_html($heading) . ' ' . __('Job', 'js-jobs');
        ?>
    </span>
    <form id="job_form" class="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_job&task=savejob")); ?>">
        <?php if (isset(jsjobs::$_data[0]->msg) AND jsjobs::$_data[0]->msg != '') { ?>
            <span class="formMsg"><font color="red"><strong><?php echo esc_html(__(jsjobs::$_data[0]->msg)); ?></strong></font></span>
        <?php } ?>

        <?php
        $validation = '';

        function printFormField($title, $field) {
            $html = '<div class="js-field-wrapper js-row no-margin">
                           <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding">' . $title . '</div>
                           <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding">' . $field . '</div>
                       </div>';
            return $html;
        }

        $i = 0;
        foreach (jsjobs::$_data[2] AS $field) {
            if ($field->published) {
                if ($field->required == 1) {
                    $validation = 'required';
                }
                switch ($field->field) {
                    case 'jobtitle':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox one', 'maxlength' => '255', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'company':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : 0, __('Select','js-jobs') .'&nbsp;'. __('Company','js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobcategory':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('jobcategory', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->jobcategory) ? jsjobs::$_data[0]->jobcategory : $defaultCategory, '', array('class' => 'inputbox one', 'data-validation' => $req, 'onchange' => 'getsubcategories(\'subcategory-field\', this.value);'));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobtype':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]->jobtype) ? jsjobs::$_data[0]->jobtype : $defaultJobtype, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobstatus':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('jobstatus', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]->jobstatus) ? jsjobs::$_data[0]->jobstatus : $defaultJobstatus, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'gender':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(''), isset(jsjobs::$_data[0]->gender) ? jsjobs::$_data[0]->gender : 0, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'age':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('agefrom', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->agefrom) ? jsjobs::$_data[0]->agefrom : $defaultAge, __('From', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('ageto', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->ageto) ? jsjobs::$_data[0]->ageto : $defaultAge, __('To', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobsalaryrange':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]->currencyid) ? jsjobs::$_data[0]->currencyid : $defaultCurrencies, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangefrom', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangefrom) ? jsjobs::$_data[0]->salaryrangefrom : $defaultSalaryrange, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangeto', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangeto) ? jsjobs::$_data[0]->salaryrangeto : $defaultSalaryrange, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $field .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]->salaryrangetype) ? jsjobs::$_data[0]->salaryrangetype : $defaultSalaryrangeType, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobshift':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('shift', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]->shift) ? jsjobs::$_data[0]->shift : $defaultShifts, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'noofjobs':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('noofjobs', isset(jsjobs::$_data[0]->noofjobs) ? jsjobs::$_data[0]->noofjobs : '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'careerlevel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('careerlevel', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data[0]->careerlevel) ? jsjobs::$_data[0]->careerlevel : $defaultCareerlevels, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'workpermit':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('workpermit', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]->workpermit) ? jsjobs::$_data[0]->workpermit : 0, __('Select','js-jobs').' '. __('work permit','js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'requiredtravel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(''), isset(jsjobs::$_data[0]->requiredtravel) ? jsjobs::$_data[0]->requiredtravel : 0, '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'startpublishing':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('startpublishing', isset(jsjobs::$_data[0]->startpublishing) ? jsjobs::$_data[0]->startpublishing : '', array('class' => 'custom_date one', 'autocomplete' => 'off', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'stoppublishing':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('stoppublishing', isset(jsjobs::$_data[0]->stoppublishing) ? jsjobs::$_data[0]->stoppublishing : '', array('class' => 'custom_date one', 'autocomplete' => 'off', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'metadescription':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::textarea('metadescription', isset(jsjobs::$_data[0]->metadescription) ? jsjobs::$_data[0]->metadescription : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req));
                        echo wp_kses(printFormField($title,$field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'metakeywords':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::textarea('metakeywords', isset(jsjobs::$_data[0]->metakeywords) ? jsjobs::$_data[0]->metakeywords : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'department':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $uid = isset(jsjobs::$_data[0]->uid) ? jsjobs::$_data[0]->uid : 0;
                        $depcomid = isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : 0;
                        $field = JSJOBSformfield::select('departmentid', JSJOBSincluder::getJSModel('departments')->getDepartmentForCombo($uid,$depcomid), isset(jsjobs::$_data[0]->departmentid) ? jsjobs::$_data[0]->departmentid : '',__('Select','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);

                        break;
                    case 'city':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('city', '', array('class' => 'inputbox one', 'data-validation' => $req));
                        $field .= JSJOBSformfield::hidden('cityforedit', isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : '', array('class' => 'inputbox one'));
                        echo (printFormField($title, $field));
                        break;
                    case 'duration':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]->duration) ? jsjobs::$_data[0]->duration : '', array('class' => 'inputbox one', 'maxlength' => '255', 'data-validation' => $req)) . __('IE 18 Months Or 3 Years', 'js-jobs');
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'zipcode':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox one', 'maxlength' => '25', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'joblink':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red"> *</font>';
                        }
                        $field = '<div id="chk-for-joblink" class="js-field-obj chck-box-fields js-col-lg-6 js-col-md-6 js-col-xs-12 no-padding">' . JSJOBSformfield::checkbox('jobapplylink', array('1' => __('Set Job Apply Redirect Link', 'js-jobs')), (isset(jsjobs::$_data[0]->jobapplylink) && jsjobs::$_data[0]->jobapplylink == 1) ? '1' : '0') . '</div>';
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);

                        $joblink_field = JSJOBSformfield::text('joblink', isset(jsjobs::$_data[0]->joblink) ? jsjobs::$_data[0]->joblink : '', array('class' => 'inputbox one input-text-joblink', 'maxlength' => '400', 'data-validation' => $req));
                        $linkfield = printFormField(__('Redirect link','js-jobs'), $joblink_field);
                        echo '<div id="input-text-joblink">' . wp_kses($linkfield, JSJOBS_ALLOWED_TAGS) . '</div>';

                        break;
                    case 'heighesteducation':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        $lists['educationminimax'] = JSJOBSformfield::select('educationminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(''), isset(jsjobs::$_data[0]->educationminimax) ? jsjobs::$_data[0]->educationminimax : 1, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['educationid'] = JSJOBSformfield::select('educationid', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->educationid) ? jsjobs::$_data[0]->educationid : $defaultEducation, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['mineducationrange'] = JSJOBSformfield::select('mineducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->mineducationrange) ? jsjobs::$_data[0]->mineducationrange : JSJOBSincluder::getJSModel('highesteducation')->getDefaultEducationId(), __('Minimum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['maxeducationrange'] = JSJOBSformfield::select('maxeducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->maxeducationrange) ? jsjobs::$_data[0]->maxeducationrange : JSJOBSincluder::getJSModel('highesteducation')->getDefaultEducationId(), __('Maximum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        if (isset(jsjobs::$_data[0]->id))
                            $iseducationminimax = jsjobs::$_data[0]->iseducationminimax;
                        else
                            $iseducationminimax = 1;
                        if ($iseducationminimax == 1) {
                            $minimaxEdu = "display:block;";
                            $rangeEdu = "display:none;";
                        } else {
                            $minimaxEdu = "display:none;";
                            $rangeEdu = "display:block;";
                        }
                        echo wp_kses(JSJOBSformfield::hidden('iseducationminimax', $iseducationminimax), JSJOBS_ALLOWED_TAGS);
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div id="defaultEdu" class="js-field-obj js-col-lg-9 js-col-xs-12 js-col-md-5 no-padding" style="<?php echo esc_attr($minimaxEdu); ?>"><?php echo wp_kses($lists['educationminimax'], JSJOBS_ALLOWED_TAGS); ?><?php echo wp_kses($lists['educationid'], JSJOBS_ALLOWED_TAGS); ?></div>
                            <div id="eduRanges" class="js-field-obj js-col-lg-9 js-col-xs-12 js-col-md-5 no-padding" style="<?php echo esc_attr($rangeEdu); ?>"><?php echo wp_kses($lists['mineducationrange'], JSJOBS_ALLOWED_TAGS); ?><?php echo wp_kses($lists['maxeducationrange'], JSJOBS_ALLOWED_TAGS); ?></div>
                            <div id="defaultEduShow" class="js-field-obj js-col-lg-2 js-col-xs-12 js-col-md-2 no-padding" style="<?php echo esc_attr($minimaxEdu); ?>"><a class="show-hide-link" onclick="hideShowRange('defaultEdu', 'eduRanges', 'defaultEduShow', 'hideEduRanges', 'iseducationminimax', 0);"><?php echo __('Specify range', 'js-jobs'); ?></a></div>
                            <div id="hideEduRanges" class="js-field-obj js-col-lg-2 js-col-xs-12 js-col-md-2 no-padding" style="<?php echo esc_attr($rangeEdu); ?>"><a class="show-hide-link" onclick="hideShowRange('eduRanges', 'defaultEdu', 'defaultEduShow', 'hideEduRanges', 'iseducationminimax', 1);"><?php echo __('Cancel range', 'js-jobs'); ?></a></div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo __('Degree title', 'js-jobs'); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding"><?php echo wp_kses(JSJOBSformfield::text('degreetitle', isset(jsjobs::$_data[0]->degreetitle) ? jsjobs::$_data[0]->degreetitle : '', array('class' => 'inputbox one', 'maxlength' => '255', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <?php
                        break;
                    case 'experience':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        $lists['experienceminimax'] = JSJOBSformfield::select('experienceminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(''), isset(jsjobs::$_data[0]->experienceminimax) ? jsjobs::$_data[0]->experienceminimax : 0, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['experienceid'] = JSJOBSformfield::select('experienceid', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->experienceid) ? jsjobs::$_data[0]->experienceid : $defaultExperiences, '', array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['minexperiencerange'] = JSJOBSformfield::select('minexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->minexperiencerange) ? jsjobs::$_data[0]->minexperiencerange : JSJOBSincluder::getJSModel('experience')->getDefaultExperienceId(), __('Minimum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        $lists['maxexperiencerange'] = JSJOBSformfield::select('maxexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->maxexperiencerange) ? jsjobs::$_data[0]->maxexperiencerange : JSJOBSincluder::getJSModel('experience')->getDefaultExperienceId(), __('Maximum', 'js-jobs'), array('class' => 'inputbox two', 'data-validation' => $req));
                        ?>
                        <?php
                        if (isset(jsjobs::$_data[0]->id))
                            $isexperienceminimax = jsjobs::$_data[0]->isexperienceminimax;
                        else
                            $isexperienceminimax = 1;
                        if ($isexperienceminimax == 1) {
                            $minimaxExp = "display:block;";
                            $rangeExp = "display:none;";
                        } else {
                            $minimaxExp = "display:none;";
                            $rangeExp = "display:block;";
                        }
                        echo wp_kses(JSJOBSformfield::hidden('isexperienceminimax', $isexperienceminimax), JSJOBS_ALLOWED_TAGS);
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div id="defaultExp" class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding" style="<?php echo esc_attr($minimaxExp); ?>"><?php echo wp_kses($lists['experienceminimax'], JSJOBS_ALLOWED_TAGS); ?><?php echo wp_kses($lists['experienceid'], JSJOBS_ALLOWED_TAGS); ?></div>
                            <div id="expRanges" class="js-field-obj js-col-lg-9 js-col-md-9 js-col-xs-12 no-padding" style="<?php echo esc_attr($rangeExp); ?>"><?php echo wp_kses($lists['minexperiencerange'], JSJOBS_ALLOWED_TAGS); ?><?php echo wp_kses($lists['maxexperiencerange'], JSJOBS_ALLOWED_TAGS); ?></div>
                            <div id="defaultExpShow" class="js-field-obj js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding" style="<?php echo esc_attr($minimaxExp); ?>"><a class="show-hide-link" onclick="hideShowRange('defaultExp', 'expRanges', 'defaultExpShow', 'hideExpRanges', 'isexperienceminimax', 0);"><?php echo __('Specify range', 'js-jobs'); ?></a></div>
                            <div id="hideExpRanges" class="js-field-obj js-col-lg-2 js-col-md-2 js-col-xs-12 no-padding" style="<?php echo esc_attr($rangeExp); ?>"><a class="show-hide-link" onclick="hideShowRange('expRanges', 'defaultExp', 'defaultExpShow', 'hideExpRanges', 'isexperienceminimax', 1);"><?php echo __('Cancel range', 'js-jobs'); ?></a></div>
                            <div class="js-field-obj js-col-lg-9 js-col-md-9 js-col-lg-offset-3 js-col-xs-12 js-col-md-offset-3 no-padding"><?php echo wp_kses(JSJOBSformfield::text('experiencetext', isset(jsjobs::$_data[0]->experiencetext) ? jsjobs::$_data[0]->experiencetext : '', array('class' => 'inputbox one', 'maxlength' => '255', 'data-validation' => $req)) . __('If Any Other Experience', 'js-jobs'), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <?php
                        break;
                    case 'map':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-5 js-col-md-5 js-col-xs-12 no-padding"> <div id="map_container"></div> </div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-obj js-col-lg-6 js-col-md-6 js-col-md-offset-3 js-col-lg-offset-3 no-padding"><?php echo wp_kses(JSJOBSformfield::text('longitude', isset(jsjobs::$_data[0]->longitude) ? jsjobs::$_data[0]->longitude : '', array('class' => 'inputbox one', 'maxlength' => '100', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS) . __('Longitude', 'js-jobs'); ?></div>
                        </div>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-obj js-col-lg-6 js-col-md-6 js-col-md-offset-3 js-col-lg-offset-3 no-padding"><?php echo wp_kses(JSJOBSformfield::text('latitude', isset(jsjobs::$_data[0]->latitude) ? jsjobs::$_data[0]->latitude : '', array('class' => 'inputbox one', 'maxlength' => '100', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS) . __('Latitude', 'js-jobs'); ?></div>

                        </div>
                        <?php
                        break;
                    case 'description':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'qualifications':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php wp_editor(isset(jsjobs::$_data[0]->qualifications) ? jsjobs::$_data[0]->qualifications : '', 'qualifications', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'prefferdskills':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php wp_editor(isset(jsjobs::$_data[0]->prefferdskills) ? jsjobs::$_data[0]->prefferdskills : '', 'prefferdskills', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    case 'agreement':
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                        }
                        ?>
                        <div class="js-field-wrapper js-row no-margin">
                            <div class="js-field-title form-editor-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')); ?><?php if ($req != '') { ?><font class="required-notifier">*</font><?php } ?></div>
                            <div class="js-field-obj js-col-lg-8 js-col-md-8 js-col-xs-12 no-padding"><?php wp_editor(isset(jsjobs::$_data[0]->agreement) ? jsjobs::$_data[0]->agreement : '', 'agreement', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>
                        <?php
                        break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            $validation = "";
        }
        ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('default_longitude', jsjobs::$_configuration['default_longitude']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('default_latitude', jsjobs::$_configuration['default_latitude']), JSJOBS_ALLOWED_TAGS); ?>
        <input type="hidden" id="edit_longitude" name="edit_longitude" value="<?php echo  isset(jsjobs::$_data[0]->longitude) ? esc_attr(jsjobs::$_data[0]->longitude) : ''; ?>"/>
        <input type="hidden" id="edit_latitude" name="edit_latitude" value="<?php echo  isset(jsjobs::$_data[0]->latitude) ? esc_attr(jsjobs::$_data[0]->latitude) : ''; ?>"/>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'job_savejob'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('isadmin', '1'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('payment', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('creditid', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-job')), JSJOBS_ALLOWED_TAGS); ?>
        <?php
            $status = array((object) array('id' => 0, 'text' => __('Pending', "js-jobs")), (object) array('id' => 1, 'text' => __('Approved', "js-jobs")), (object) array('id' => -1, 'text' => __('Rejected', "js-jobs")));
            $title = __('Status', 'js-jobs');
            $field = JSJOBSformfield::select('status', $status, isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, __('Select Status', 'js-jobs'), array('class' => 'inputbox one'));
            echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
        ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-xs-12 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_job'),"job")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php
                echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Job', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS);
            ?>
        </div>
    </form>
<?php
$mapfield = null;
foreach(jsjobs::$_data[2] AS $key => $value){
    $value = (array) $value;
    if(in_array('map', $value)){
        $mapfield = $key;
        break;
    }
}
if($mapfield):
    $mapfield = jsjobs::$_data[2][$mapfield];
    if($mapfield->published == 1){ ?>
        <style>
            div#map_container{border:2px solid #fff;}
        </style>
        <?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        wp_enqueue_script('jsjobs-repaptcha-scripti','https://maps.googleapis.com/maps/api/js?key='.jsjobs::$_configuration['google_map_api_key']);
        ?>
        <script >
            var map = null;
            var markers = [];
            var latlang_marker_array = [];
            function addMarker(latlang,cityid = 0){
                var marker = new google.maps.Marker({
                    position: latlang,
                    map: map,
                    draggable: true,
                });
                marker.setMap(map);
                map.setCenter(latlang);
                // cityid is to identify the marker neds to be removed.
                if(cityid != 0){
                    marker.cityid = cityid;
                    markers.push(marker);
                }
                // this array is for newly added city whoose marker may need to be removed.
                latlang_marker_array[latlang] = marker;
                //..

                marker.addListener("dblclick", function() {
                    deleteMarker(marker);
                });
                if(document.getElementById('latitude').value == ''){
                    document.getElementById('latitude').value = marker.position.lat();
                }else{
                    document.getElementById('latitude').value += ',' + marker.position.lat();
                }
                if(document.getElementById('longitude').value == ''){
                    document.getElementById('longitude').value = marker.position.lng();
                }else{
                    document.getElementById('longitude').value += ',' + marker.position.lng();
                }
            }

            function deleteMarker(marker){ // this fucntion completely remves markr and thier lat lang values from text field
                var latitude = document.getElementById('latitude').value;
                latitude = latitude.replace(','+marker.position.lat(), "");
                latitude = latitude.replace(marker.position.lat()+',', "");
                latitude = latitude.replace(marker.position.lat(), "");
                document.getElementById('latitude').value = latitude;
                var longitude = document.getElementById('longitude').value;
                longitude = longitude.replace(','+marker.position.lng(), "");
                longitude = longitude.replace(marker.position.lng()+',', "");
                longitude = longitude.replace(marker.position.lng(), "");
                document.getElementById('longitude').value = longitude;
                marker.setMap(null);
                return;
            }

            function identifyMarkerForDelete(t_item){// this fucntion identifies the marker assiciated with token input value that has been removed.
                var id = t_item.id;
                // this code is when lat lang are added from data base cities
                for (var i = 0; i < markers.length; i++) {
                    if (markers[i].cityid == id) {
                        //Remove the marker from Map
                        //markers[i].setMap(null);
                        deleteMarker(markers[i]);
                        //Remove the marker from array.
                        markers.splice(i, 1);
                        return;
                    }
                }
                // this code is for when lat lang belonged to newely added city
                if( t_item.latitude != undefined && t_item.latitude != '' && t_item.latitude != 0){
                    var markerLatlng = new google.maps.LatLng(t_item.latitude, t_item.longitude);
                    deleteMarker(latlang_marker_array[markerLatlng]);
                    markers.splice(markerLatlng, 1);
                }
            }

            function addMarkerOnMap(location){
                if( location.latitude != undefined && location.latitude != '' && location.latitude != 0){// code is for adding a marker from data base lat lang.
                    var latlng = new google.maps.LatLng(String(location.latitude), String(location.longitude));
                    if(map != null){
                        addMarker(latlng,location.id);
                    } else {
                        alert("<?php echo __("Something got wrong 1","js-jobs");?>:");
                    }
                }else{ // this code for adding a marker from location name. // this code is redundant but leaving it here
                    var geocoder =  new google.maps.Geocoder();
                    geocoder.geocode( { 'address': location.name}, function(results, status) {
                        var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        if (status == google.maps.GeocoderStatus.OK) {
                            if(map != null){
                                addMarker(latlng,location.id);
                            }
                        } else {
                            //alert("<?php //echo __('Something got wrong','js-jobs');?>:"+status);
                        }
                    });
                }
                return;
            }

            function loadMap() {
                var default_latitude = document.getElementById('default_latitude').value;
                var default_longitude = document.getElementById('default_longitude').value;
                var latitude = document.getElementById('edit_latitude').value;
                var longitude = document.getElementById('edit_longitude').value;
                var isdefaultvalue = true;
                if (latitude != '' && longitude != '') {
                    default_latitude = latitude;
                    default_longitude = longitude;
                    isdefaultvalue = false;
                }

                var latlng = new google.maps.LatLng(document.getElementById('default_latitude').value, document.getElementById('default_longitude').value);
                zoom = 8;
                var myOptions = {
                    zoom: zoom,
                    center: latlng,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                default_latitude = default_latitude.split(',');
                if(default_latitude instanceof Array){
                    default_longitude = default_longitude.split(',');
                    for (i = 0; i < default_latitude.length; i++) {
                        var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                        if(isdefaultvalue == false)
                            addMarker(latlng);
                    }
                }else{
                    var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                    if(isdefaultvalue == false)
                        addMarker(latlng);
                }
                google.maps.event.addListener(map, "click", function (e) {
                    var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode({'latLng': latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        addMarker(results[0].geometry.location);
                    } else {
                        alert("<?php echo __("Geocode was not successful for the following reason", "js-jobs"); ?>: " + status);
                    }
                    });
                });
            }
        </script>
    <?php } ?>
<?php endif; ?>
    <script >
        jQuery(document).ready(function ($) {
            /*job apply link start*/
            if (jQuery("input#jobapplylink1").is(":checked")){
                jQuery("div#input-text-joblink").show();
            }
            jQuery("input#jobapplylink1").click(function(){
                if (jQuery(this).is(":checked")){
                    jQuery("div#input-text-joblink").show();
                } else{
                    jQuery("div#input-text-joblink").hide();
                }
            });
            /*job apply link end*/
            $('.custom_date').datepicker({dateFormat: '<?php echo esc_attr($js_scriptdateformat); ?>'});
            $.validate();
            var multicities = <?php echo isset(jsjobs::$_data[0]->multicity) ? jsjobs::$_data[0]->multicity : "''" ?>;
            getTokenInput(multicities);
            var map_obj = document.getElementById('map_container');
            if (typeof map_obj !== 'undefined' && map_obj !== null) {
                window.onload = loadMap();
            }
        });

        function getdepartments(src, val){
            if(companycall > 1){
                var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'departments', task: 'listdepartments', val: val, wpnoncecheck:common.wp_jm_nonce}, function(data){
                    if (data){
                        jQuery("#" + src).html(data); //retuen value
                    }
                });
            }
        }

        function hideShowRange(hideSrc, showSrc, showLink, hideLink, showName, showVal){
            jQuery("#" + hideSrc).toggle();
            jQuery("#" + showSrc).toggle();
            jQuery("#" + showLink).toggle();
            jQuery("#" + hideLink).toggle();
        }

        function getTokenInput(multicities) {
            var cityArray = '<?php echo admin_url("admin.php?page=jsjobs_city&action=jsjobtask&task=getaddressdatabycityname"); ?>';
            cityArray = cityArray+"&_wpnonce=<?php echo wp_create_nonce('address-data-by-cityname'); ?>";
            var city = jQuery("#cityforedit").val();
            if (city != "") {
                jQuery("#city").tokenInput(cityArray, {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo __("Type In A Search Term", "js-jobs"); ?>",
                    noResultsText: "<?php echo __("No Results", "js-jobs"); ?>",
                    searchingText: "<?php echo __("Searching", "js-jobs"); ?>",
                    // tokenLimit: 1,
                    prePopulate: multicities,
                    <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                    if ($newtyped_cities == 1) { ?>
                        onResult: function(item) {
                            if (jQuery.isEmptyObject(item)){
                                return [{id:0, name: jQuery("tester").text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id:0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function(item) {
                            if (item.id > 0){
                            <?php
                                if($mapfield):
                                    if($mapfield->published == 1){ ?>
                                        addMarkerOnMap(item);
                                    <?php } ?>
                                <?php endif; ?>
                                    return;
                                    }
                                    if (item.name.search(",") == - 1) {
                                        var input = jQuery("tester").text();
                                        alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                                        jQuery("#city").tokenInput("remove", item);
                                        return false;
                                    } else{
                                        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                                        var location_data =  jQuery("tester").text();
                                            //alert(new_loction_lat);
                                            var n_latitude;
                                            var n_longitude;
                                            var geocoder =  new google.maps.Geocoder();
                                            geocoder.geocode( { 'address': location_data}, function(results, status) {
                                                if (status == google.maps.GeocoderStatus.OK) {
                                                    n_latitude = results[0].geometry.location.lat();
                                                    n_longitude = results[0].geometry.location.lng();
                                                } else {
                                                    alert("<?php echo __('Something got wrong','js-jobs');?>:"+status);
                                                }
                                            });
                                            setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude, wpnoncecheck:common.wp_jm_nonce }, function(data){
                                                    if (data){
                                                        try {
                                                            var value = jQuery.parseJSON(data);
                                                            jQuery('#city').tokenInput('remove', item);
                                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                                        }
                                                        catch (err) {
                                                            jQuery("#city").tokenInput("remove", item);
                                                            alert(data);
                                                        }
                                                    }
                                                });
                                            },1500);
                                    }
                                },
                                onDelete: function(item){
                                    identifyMarkerForDelete(item);// delete marker associted with this token input value.
                                }
                            <?php } ?>
                            });
                        } else {
                            jQuery("#city").tokenInput(cityArray, {
                                theme: "jsjobs",
                                preventDuplicates: true,
                                hintText: "<?php echo __("Type In A Search Term", "js-jobs"); ?>",
                                noResultsText: "<?php echo __("No Results", "js-jobs"); ?>",
                                searchingText: "<?php echo __("Searching", "js-jobs"); ?>",
                                // tokenLimit: 1,
                                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                                if ($newtyped_cities == 1) { ?>
                                    onResult: function(item) {
                                        if (jQuery.isEmptyObject(item)){
                                            return [{id:0, name: jQuery("tester").text()}];
                                        } else {
                                            //add the item at the top of the dropdown
                                            item.unshift({id:0, name: jQuery("tester").text()});
                                            return item;
                                        }
                                    },
                                    onAdd: function(item) {
                                    if (item.id > 0){
                                    <?php
                                    if($mapfield):
                                        if($mapfield->published == 1){ ?>
                                            addMarkerOnMap(item);
                                        <?php } ?>
                                    <?php endif; ?>
                                        return;
                                    }
                                    if (item.name.search(",") == - 1) {
                                        var input = jQuery("tester").text();
                                        alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                                        jQuery("#city").tokenInput("remove", item);
                                        return false;
                                    } else{
                                        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                                        var location_data =  jQuery("tester").text();
                                            //alert(new_loction_lat);
                                            var n_latitude;
                                            var n_longitude;
                                            var geocoder =  new google.maps.Geocoder();
                                            geocoder.geocode( { 'address': location_data}, function(results, status) {
                                                if (status == google.maps.GeocoderStatus.OK) {
                                                    n_latitude = results[0].geometry.location.lat();
                                                    n_longitude = results[0].geometry.location.lng();
                                                } else {
                                                    alert("<?php echo __('Something got wrong','js-jobs');?>:"+status);
                                                }
                                            });
                                            setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude, wpnoncecheck:common.wp_jm_nonce }, function(data){
                                                    if (data){
                                                        try {
                                                            var value = jQuery.parseJSON(data);
                                                            jQuery('#city').tokenInput('remove', item);
                                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude, longitude:value.longitude});
                                                        }
                                                        catch (err) {
                                                            jQuery("#city").tokenInput("remove", item);
                                                            alert(data);
                                                        }
                                                    }
                                                });
                                            },1000);
                                    }
                                },
                                onDelete: function(item){
                                    identifyMarkerForDelete(item);// delete marker associted with this token input value.
                                }
                                <?php } ?>
                            });
                        }
                    }
    </script>
</div>
</div>

