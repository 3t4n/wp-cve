<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('job')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);

JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo esc_html($msg) . '&nbsp;' . __('Job', 'js-jobs'); ?></div>
        <form class="js-ticket-form" id="job_form" method="post" action="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'task'=>'savejob'))); ?>">
            <?php

            function printFormField($title, $field) {
                $html = '<div class="js-col-md-12 js-form-wrapper">
                                    <div class="js-col-md-12 js-form-title">' . $title . '</div>
                                    <div class="js-col-md-12 js-form-value">' . $field . '</div>
                                </div>';
                return $html;
            }

            function printFormField2($title, $field) {
                $html = '<div id="input-text-joblink" class="js-col-md-12 js-form-wrapper">
                                    <div class="js-col-md-12 js-form-title">' . $title . '</div>
                                    <div class="js-col-md-12 js-form-value">' . $field . '</div>
                                </div>';
                return $html;
            }

            $i = 0;
            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'jobtitle':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? jsjobs::$_data[0]->title : '', array('class' => 'inputbox', 'maxlength' => '255', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobcategory':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('jobcategory', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->jobcategory) ? jsjobs::$_data[0]->jobcategory : JSJOBSincluder::getJSModel('category')->getDefaultCategoryId(), __('Select','js-jobs') .' '. __('Category', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'gender':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red">&nbsp*</font>';
                        }
                        $formfield = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data[0]->gender) ? jsjobs::$_data[0]->gender : '', __('Select','js-jobs') .' '. __('Gender', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'age':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red">&nbsp*</font>';
                        }
                        $formfield = JSJOBSformfield::select('agefrom', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->agefrom) ? jsjobs::$_data[0]->agefrom : JSJOBSincluder::getJSModel('age')->getDefaultAgeId() , __('Select','js-jobs') .' '. __('Age','js-jobs') .' '. __('start', 'js-jobs'), array('class' => 'inputbox age', 'data-validation' => $req));
                        $formfield .= JSJOBSformfield::select('ageto', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]->ageto) ? jsjobs::$_data[0]->ageto : JSJOBSincluder::getJSModel('age')->getDefaultAgeId() , __('Select','js-jobs') .' '. __('Age','js-jobs') .' '. __('end', 'js-jobs'), array('class' => 'inputbox age', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'heighesteducation':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        if (isset(jsjobs::$_data[0])){
                            $iseducationminimax = jsjobs::$_data[0]->iseducationminimax;
                        }else{
                            $iseducationminimax = 1;
                        }
                        if($iseducationminimax == 1){
                            $style1 = 'inline-block';
                            $style2 = 'none';
                        }else{
                            $style1 = 'none';
                            $style2 = 'inline-block';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value">
                                <?php echo wp_kses(JSJOBSformfield::hidden('iseducationminimax', $iseducationminimax), JSJOBS_ALLOWED_TAGS); ?>
                                <span id='educationid' style="display:<?php echo esc_attr($style1); ?>;width:100%;">
                                    <?php echo wp_kses(JSJOBSformfield::select('educationminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(), isset(jsjobs::$_data[0]->educationminimax) ? jsjobs::$_data[0]->educationminimax : '', __('Select','js-jobs') .' '. __('Minimum','js-jobs') .' '. __('Or','js-jobs') .' '. __('Maximum', 'js-jobs'), array('class' => 'inputbox edu', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(JSJOBSformfield::select('educationid', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->educationid) ? jsjobs::$_data[0]->educationid : JSJOBSincluder::getJSModel('highesteducation')->getDefaultEducationId(), __('Select','js-jobs') .' '. __('Education', 'js-jobs'), array('class' => 'inputbox edu', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>

                                </span>
                                <span id='education' style="display:<?php echo esc_attr($style2); ?>;width:100%;">
                                    <?php echo wp_kses(JSJOBSformfield::select('mineducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->mineducationrange) ? jsjobs::$_data[0]->mineducationrange : JSJOBSincluder::getJSModel('highesteducation')->getDefaultEducationId(), __('Select','js-jobs') .' '. __('Education','js-jobs') .' '. __('Minimum', 'js-jobs'), array('class' => 'inputbox edu', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(JSJOBSformfield::select('maxeducationrange', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]->maxeducationrange) ? jsjobs::$_data[0]->maxeducationrange : JSJOBSincluder::getJSModel('highesteducation')->getDefaultEducationId(), __('Select','js-jobs') .' '. __('Education','js-jobs') .' '. __('Maximum', 'js-jobs'), array('class' => 'inputbox edu', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <span id='range-edu' style="display:<?php echo esc_attr($style1); ?>"><a onclick="getotheredu(1);"><?php echo __('Specify range', 'js-jobs'); ?></a></span>
                            <span id='range-edu-one' style="display:<?php echo esc_attr($style2); ?>"><a onclick="getotheredu(2);"><?php echo __('Cancel range', 'js-jobs'); ?></a></span>                  
                        </div>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo __('Degree title', 'js-jobs'); ?></div>
                            <div class="js-col-md-12 js-form-value"><?php echo wp_kses(JSJOBSformfield::text('degreetitle', isset(jsjobs::$_data[0]->degreetitle) ? jsjobs::$_data[0]->degreetitle : '', array('class' => 'inputbox', 'maxlength' => '255')), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <?php
                        break;
                    case 'experience':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        if (isset(jsjobs::$_data[0])){
                            $isexperienceminimax = jsjobs::$_data[0]->isexperienceminimax;
                        }else{
                            $isexperienceminimax = 1;
                        }
                        if($isexperienceminimax == 1){
                            $style1 = 'inline-block';
                            $style2 = 'none';
                        }else{
                            $style1 = 'none';
                            $style2 = 'inline-block';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value">
                                <?php echo wp_kses(JSJOBSformfield::hidden('isexperienceminimax', $isexperienceminimax), JSJOBS_ALLOWED_TAGS); ?>
                                <span id='experienceid' style="display:<?php echo esc_attr($style1); ?>;width:100%;">
                                    <?php echo wp_kses(JSJOBSformfield::select('experienceminimax', JSJOBSincluder::getJSModel('common')->getMiniMax(), isset(jsjobs::$_data[0]->experienceminimax) ? jsjobs::$_data[0]->experienceminimax : '', __('Select','js-jobs') .' '. __('Minimum','js-jobs') .' '. __('Or','js-jobs') .' '. __('Maximum', 'js-jobs'), array('class' => 'inputbox exp', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(JSJOBSformfield::select('experienceid', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->experienceid) ? jsjobs::$_data[0]->experienceid : JSJOBSincluder::getJSModel('experience')->getDefaultExperienceId() , __('Select','js-jobs') .' '. __('Experience', 'js-jobs'), array('class' => 'inputbox exp', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                </span>
                                <span id='experience' style="display:<?php echo esc_attr($style2); ?>;width:100%;">
                                    <?php echo wp_kses(JSJOBSformfield::select('minexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->minexperiencerange) ? jsjobs::$_data[0]->minexperiencerange : JSJOBSincluder::getJSModel('experience')->getDefaultExperienceId() , __('Select','js-jobs') .' '. __('Minimum','js-jobs') .' '. __('Experience', 'js-jobs'), array('class' => 'inputbox exp', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                    <?php echo wp_kses(JSJOBSformfield::select('maxexperiencerange', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), isset(jsjobs::$_data[0]->maxexperiencerange) ? jsjobs::$_data[0]->maxexperiencerange : JSJOBSincluder::getJSModel('experience')->getDefaultExperienceId() , __('Select','js-jobs') .' '. __('Maximum','js-jobs') .' '. __('Experience', 'js-jobs'), array('class' => 'inputbox exp', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <span id='range-exp' style="display: <?php echo esc_attr($style1); ?>;"><a onclick="getotherexp(1);"><?php echo __('Specify range', 'js-jobs'); ?></a></span>
                            <span id='range-one' style="display: <?php echo esc_attr($style2); ?>;"><a onclick="getotherexp(2);"><?php echo __('Cancel range', 'js-jobs'); ?></a></span>
                        </div>
                        <div  class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo __('Total Experience', 'js-jobs'); ?></div>
                            <div class="js-col-md-12 js-form-value"><?php echo wp_kses(JSJOBSformfield::text('experiencetext', isset(jsjobs::$_data[0]->experiencetext) ? jsjobs::$_data[0]->experiencetext : '', array('class' => 'inputbox exp', 'maxlength' => '255')), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <?php
                        break;
                    case 'jobsalaryrange':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]->currencyid) ? jsjobs::$_data[0]->currencyid : JSJOBSincluder::getJSModel('currency')->getDefaultCurrencyId(), __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox sal', 'data-validation' => $req));
                        $formfield .= JSJOBSformfield::select('salaryrangefrom', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangefrom) ? jsjobs::$_data[0]->salaryrangefrom : JSJOBSincluder::getJSModel('salaryrange')->getDefaultSalaryRangeId(), __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox sal', 'data-validation' => $req));
                        $formfield .= JSJOBSformfield::select('salaryrangeto', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]->salaryrangeto) ? jsjobs::$_data[0]->salaryrangeto : JSJOBSincluder::getJSModel('salaryrange')->getDefaultSalaryRangeId(), __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox sal', 'data-validation' => $req));
                        $formfield .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]->salaryrangetype) ? jsjobs::$_data[0]->salaryrangetype : JSJOBSincluder::getJSModel('salaryrangetype')->getDefaultSalaryRangeTypeId(), __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'department':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('departmentid', JSJOBSincluder::getJSModel('departments')->getDepartmentForCombo(), isset(jsjobs::$_data[0]->departmentid) ? jsjobs::$_data[0]->departmentid : '', __('Select','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'company':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        if (JSJOBSincluder::getJSModel('company')->employerHaveCompany($uid)) {
                            $field = JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getCompanyForCombo($uid), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : '', __('Select','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'inputbox', 'onchange' => 'getdepartments(\'departmentid\', this.value);', 'data-validation' => $req));
                        } else {
                            $field = '<a href="'.wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'jsjobslt'=>'addcompany')),"formcompany").'">' . __('Add','js-jobs').' '. __('Company', 'js-jobs') . '</a><input type="hidden" name="companyid" id="companyid" data-validation="required" />';
                        }
                        echo wp_kses(printFormField($title, $field), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobtype':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]->jobtype) ? jsjobs::$_data[0]->jobtype : JSJOBSincluder::getJSModel('jobtype')->getDefaultJobTypeId(), __('Select','js-jobs') .' '. __('Job Type', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'noofjobs':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('noofjobs', isset(jsjobs::$_data[0]->noofjobs) ? jsjobs::$_data[0]->noofjobs : '', array('class' => 'inputbox one', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobstatus':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('jobstatus', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]->jobstatus) ? jsjobs::$_data[0]->jobstatus : JSJOBSincluder::getJSModel('jobstatus')->getDefaultJobStatusId(), __('Select','js-jobs') .' '. __('Job Status', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'workpermit':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('workpermit', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]->workpermit) ? jsjobs::$_data[0]->workpermit : '', __('Select','js-jobs') .' '. __('Work Permit', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'duration':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]->duration) ? jsjobs::$_data[0]->duration : '', array('class' => 'inputbox', 'maxlength' => '255', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'requiredtravel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(), isset(jsjobs::$_data[0]->requiredtravel) ? jsjobs::$_data[0]->requiredtravel : '', __('Select','js-jobs') .' '. __('Required Travel', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobshift':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('shift', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]->shift) ? jsjobs::$_data[0]->shift : JSJOBSincluder::getJSModel('shift')->getDefaultShiftId() , __('Select','js-jobs') .' '. __('Shift', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'description':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'careerlevel':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::select('careerlevel', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data[0]->careerlevel) ? jsjobs::$_data[0]->careerlevel : JSJOBSincluder::getJSModel('careerlevel')->getDefaultCareerlevelId(), __('Select','js-jobs') .' '. __('Career Level', 'js-jobs'), array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'city':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]->city) ? jsjobs::$_data[0]->city : '', array('class' => 'inputbox', 'maxlength' => '255', 'data-validation' => $req));
                        echo printFormField($title, $formfield);
                        break;
                    case 'zipcode':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('class' => 'inputbox', 'maxlength' => '25', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'map':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><div id="map_container"><div id="map"></div></div></div>
                        </div>
                        <?php
                        $title = __('Latitude', 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('latitude', '', array('class' => 'inputbox', 'maxlength' => '100', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        $title = __('Longitude', 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('longitude','', array('class' => 'inputbox', 'maxlength' => '100', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'startpublishing':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('startpublishing', isset(jsjobs::$_data[0]->startpublishing) ? jsjobs::$_data[0]->startpublishing : '', array('class' => 'inputbox custom_date ', 'autocomplete' => 'off', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'stoppublishing':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $req = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .='<font color="red"> *</font>';
                        }
                        $formfield = JSJOBSformfield::text('stoppublishing', isset(jsjobs::$_data[0]->stoppublishing) ? jsjobs::$_data[0]->stoppublishing : '', array('class' => 'inputbox custom_date', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'qualifications':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>  <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->qualifications) ? jsjobs::$_data[0]->qualifications : '', 'qualifications', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'prefferdskills':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->prefferdskills) ? jsjobs::$_data[0]->prefferdskills : '', 'prefferdskills', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'agreement':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->agreement) ? jsjobs::$_data[0]->agreement : '', 'agreement', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'metadescription':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value "><?php echo wp_kses(JSJOBSformfield::textarea('metadescription', isset(jsjobs::$_data[0]->metadescription) ? jsjobs::$_data[0]->metadescription : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?></div>
                        </div>
                        <?php
                        break;
                    case 'metakeywords':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        ?>
                        <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo esc_html(__($field->fieldtitle, 'js-jobs')) . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value "><?php echo wp_kses(JSJOBSformfield::textarea('metakeywords', isset(jsjobs::$_data[0]->metakeywords) ? jsjobs::$_data[0]->metakeywords : '', array('class' => 'inputbox one', 'rows' => '7', 'cols' => '94', 'data-validation' => $req)), JSJOBS_ALLOWED_TAGS); ?></div> 
                        </div>
                        <?php
                        break;
                    case 'joblink':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red"> *</font>';
                        }
                        $formfield = '
                                             <div class="js-col-md-12 ">
                                                <div class="js-field-wrapper js-row  no-margin">
                                                    <div class="js-field-obj chck-box-fields widthauto js-col-lg-4 js-col-md-4 js-col-xs-12 email-checkbox no-padding">' . JSJOBSformfield::checkbox('jobapplylink', array('1' => __('Set Job Apply Redirect Link', 'js-jobs')), (isset(jsjobs::$_data[0]->jobapplylink) && jsjobs::$_data[0]->jobapplylink == 1) ? '1' : '0') . '</div>
                                                    <div id="input-text-joblink">' . JSJOBSformfield::text('joblink', isset(jsjobs::$_data[0]->joblink) ? jsjobs::$_data[0]->joblink : '', array('class' => 'inputbox one input-text-joblink', 'maxlength' => '400')) . '</div>
                                                </div>
                                            </div>';
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            ?> 
            <div class="js-col-md-12 js-form-wrapper">
                <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ), JSJOBS_ALLOWED_TAGS); ?>
                <?php
                if (!isset(jsjobs::$_data[0]->id)) { // edit case form
                    echo wp_kses(JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()), JSJOBS_ALLOWED_TAGS);
                } ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('creditid', ''), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('action', 'job_savejob'), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('jsjobspageid', get_the_ID()), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-job')), JSJOBS_ALLOWED_TAGS); ?>
                <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo  esc_attr(jsjobs::$_configuration['default_longitude']); ?>"/>
                <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo esc_attr(jsjobs::$_configuration['default_latitude']); ?>"/>
                <input type="hidden" id="edit_longitude" name="edit_longitude" value="<?php echo  isset(jsjobs::$_data[0]->longitude) ? esc_attr(jsjobs::$_data[0]->longitude) : ''; ?>"/>
                <input type="hidden" id="edit_latitude" name="edit_latitude" value="<?php echo  isset(jsjobs::$_data[0]->latitude) ? esc_attr(jsjobs::$_data[0]->latitude) : ''; ?>"/>
                <div class="js-col-md-12 bottombutton js-form"id="save-button">			    	
                    <?php
                        echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Job', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS);
                    ?>
                </div>
            </div>
        </form>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
</div>
