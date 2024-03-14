<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('jobsearch')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    $radiustype = array(
        (object) array('id' => '0', 'text' => __('Select One', 'js-jobs')),
        (object) array('id' => '1', 'text' => __('Meters', 'js-jobs')),
        (object) array('id' => '2', 'text' => __('Kilometers', 'js-jobs')),
        (object) array('id' => '3', 'text' => __('Miles', 'js-jobs')),
        (object) array('id' => '4', 'text' => __('Nautical Miles', 'js-jobs')),
    );
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Search Job', 'js-jobs'); ?></div>
        <form class="job_form" id="job_form" method="post" action="<?php echo esc_url(wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'jsjobspageid'=>jsjobs::getPageid())),"job")); ?>">
            <?php

            function getRow($title, $value) {
                $html = '<div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title">' . esc_html($title) . '</div>
                            <div class="js-col-md-12 js-form-value">' . wp_kses($value, JSJOBS_ALLOWED_TAGS) . '</div>
                        </div>';
                return $html;
            }

            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'metakeywords':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('metakeywords', isset(jsjobs::$_data[0]['filter']->metakeywords) ? jsjobs::$_data[0]['filter']->metakeywords : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobtitle':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('jobtitle', isset(jsjobs::$_data[0]['filter']->jobtitle) ? jsjobs::$_data[0]['filter']->jobtitle : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'company':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('company[]', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]['filter']->company) ? jsjobs::$_data[0]['filter']->company : '', __('Select','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobcategory':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('category[]', JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(), isset(jsjobs::$_data[0]['filter']->category) ? jsjobs::$_data[0]['filter']->category : '', __('Select','js-jobs') .' '. __('Category', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'careerlevel':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('careerlevel[]', JSJOBSincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset(jsjobs::$_data[0]['filter']->careerlevel) ? jsjobs::$_data[0]['filter']->careerlevel : '', __('Select','js-jobs') .' '. __('Career Level', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'age':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('age[]', JSJOBSincluder::getJSModel('age')->getAgesForCombo(), isset(jsjobs::$_data[0]['filter']->age) ? jsjobs::$_data[0]['filter']->age : '', __('Select','js-jobs') .' '. __('Age', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobshift':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('shift[]', JSJOBSincluder::getJSModel('shift')->getShiftForCombo(), isset(jsjobs::$_data[0]['filter']->shift) ? jsjobs::$_data[0]['filter']->shift : '', __('Select','js-jobs') .' '. __('Shift', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'gender':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('gender', JSJOBSincluder::getJSModel('common')->getGender(), isset(jsjobs::$_data[0]['filter']->gender) ? jsjobs::$_data[0]['filter']->gender : '', __('Select','js-jobs') .' '. __('Gender', 'js-jobs'), array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobtype':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('jobtype[]', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(jsjobs::$_data[0]['filter']->jobtype) ? jsjobs::$_data[0]['filter']->jobtype : '', __('Select','js-jobs') .' '. __('Job Type', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobstatus':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('jobstatus[]', JSJOBSincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(jsjobs::$_data[0]['filter']->jobstatus) ? jsjobs::$_data[0]['filter']->jobstatus : '', __('Select','js-jobs') .' '. __('Job Status', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'workpermit':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('workpermit[]', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), isset(jsjobs::$_data[0]['filter']->workpermit) ? jsjobs::$_data[0]['filter']->workpermit : '', __('Select','js-jobs') .' '. __('Work Permit', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'jobsalaryrange':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), isset(jsjobs::$_data[0]['filter']->currencyid) ? jsjobs::$_data[0]['filter']->currencyid : '', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrange) ? jsjobs::$_data[0]['filter']->salaryrange : '', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox sal'));
                        $value .= JSJOBSformfield::select('salaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(jsjobs::$_data[0]['filter']->salaryrangetype) ? jsjobs::$_data[0]['filter']->salaryrangetype : '', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox sal'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'heighesteducation':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('highesteducation[]', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), isset(jsjobs::$_data[0]['filter']->highesteducation) ? jsjobs::$_data[0]['filter']->highesteducation : '', __('Select','js-jobs') .' '. __('Highest Education', 'js-jobs'), array('class' => 'inputbox jsjob-multiselect', 'multiple' => 'true'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'city':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]['filter']->city) ? jsjobs::$_data[0]['filter']->city : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'zipcode':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]['filter']->zipcode) ? jsjobs::$_data[0]['filter']->zipcode : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'requiredtravel':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::select('requiredtravel', JSJOBSincluder::getJSModel('common')->getRequiredTravel(), isset(jsjobs::$_data[0]['filter']->requiredtravel) ? jsjobs::$_data[0]['filter']->requiredtravel : '', __('Select one', 'js-jobs'), array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'duration':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = JSJOBSformfield::text('duration', isset(jsjobs::$_data[0]['filter']->duration) ? jsjobs::$_data[0]['filter']->duration : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'map':
                        $title = __($field->fieldtitle, 'js-jobs');
                        $value = '<div id="map_container"><div id="map"></div></div>';
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        $title = __('Longitude', 'js-jobs');
                        $value = JSJOBSformfield::text('longitude', isset(jsjobs::$_data[0]['filter']->longitude) ? jsjobs::$_data[0]['filter']->longitude : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        $title = __('Latitude', 'js-jobs');
                        $value = JSJOBSformfield::text('latitude', isset(jsjobs::$_data[0]['filter']->latitude) ? jsjobs::$_data[0]['filter']->latitude : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        $title = __('Radius', 'js-jobs');
                        $value = JSJOBSformfield::text('radius', isset(jsjobs::$_data[0]['filter']->radius) ? jsjobs::$_data[0]['filter']->radius : '', array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        $title = __('Radius Length Type', 'js-jobs');
                        $value = JSJOBSformfield::select('radiuslengthtype', $radiustype, jsjobs::$_configuration['defaultradius'], __('Select','js-jobs') .' '. __('Radius Length Type', 'js-jobs'), array('class' => 'inputbox'));
                        echo wp_kses(getRow($title, $value), JSJOBS_ALLOWED_TAGS);
                        break;
                    default:
                        $i = 0;
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $i);
                        break;
                }
            }
            ?>
            <div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 bottombutton js-form" id="save-button">                 
                    <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Search Job', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
                </div>
            </div>
            <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo esc_attr(jsjobs::$_configuration['default_longitude']); ?>"/>
            <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo esc_attr(jsjobs::$_configuration['default_latitude']); ?>"/>
            <input type="hidden" id="issearchform" name="issearchform" value="1"/>
            <input type="hidden" id="JSJOBS_form_search" name="JSJOBS_form_search" value="JSJOBS_SEARCH"/>
            <input type="hidden" id="jsjobslt" name="jsjobslt" value="jobs"/>
        </form>
    </div>

<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
