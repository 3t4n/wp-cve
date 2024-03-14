<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSResumeFormlayout {

    public $config_array_sec=array();
    public $resumefields=array();
    public $class_prefix = '';
    public $themecall = 0;

    function __construct(){
        $this->config_array_sec = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        $this->resumefields=$fieldsordering;
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
            jsjobs::$_data[2][$field->section][$field->field] = $field;
        }
        if(jsjobs::$theme_chk == 2){/// code to manage class prefix for diffrent template cases
            $this->class_prefix = 'jsjb-jh';
            $this->themecall = 2;

        }elseif(jsjobs::$theme_chk == 1){
            $this->class_prefix = 'jsjb-jm';
            $this->themecall = 1;
        }else{
            $this->class_prefix = '';
        }
    }

    function getFieldTitleByField($field){

        return __(jsjobs::$_data['fieldtitles'][$field],'js-jobs');
    }
    function getResumeFormUserFieldJobManager($title, $field,$required) {
        $html = '<div class="js-col-md-12 js-form-wrapper">
        <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont">' . $title;
        if($required==1){
            $html .= '<span class="'.$this->class_prefix.'-error-msg">*</span>';
        }
        $html .= '</div>
            <div class="js-col-md-12 js-form-value">' . $field . '</div>
        </div>';

        return $html;
    }



    function getResumeFormUserField($field, $object , $section , $sectionid, $ishidden,$themecall=null) {
        $id = isset($object->id)  ? $object->id : NULL;
        $params = isset($object->params) ? $object->params : NULL;
        $data = NULL;
        $result = JSJOBSincluder::getObjectClass('customfields')->formCustomFieldsResume($field , $id , $params,null,$section , $sectionid, $ishidden,$themecall);
        if( isset($result['value'])){
            if(null !=$themecall){
                $data=$this->getResumeFormUserFieldJobManager($result['title'],$result['value'],$field->required);

            }else{
                $data .= '<div class="resume-row-wrapper form resumefieldswrapper">';
                $data .= '  <label class="resumefieldtitle" for="">';
                $data .=        __($result['title'],'js-jobs');
                                if($field->required == 1){
                $data .= '          <span class="error-msg">*</span>';
                                }
                $data .= '  </label>';
                $data .= '  <div class="resumefieldvalue">';
                $data .=        $result['value'];
                $data .= '  </div>
                          </div>';
            }
            return $data;
        }
        return $result;
    }


    function getResumeCheckBoxField($field, $fieldValue){

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;

        $name = 'sec_1['.$fieldName.']';
        $data = '
            <div class="resume-row-wrapper form jsresume_seach_width">
                <div class="checkbox-field row-value">
                    <input type="checkbox" class="" name="' . $name . '" id="' . $fieldName . '" value="1" ';
                        if($fieldValue == "" AND $fieldName == 'searchable'){  //new case
                            $data .= 'checked="checked" ';
                        }elseif($fieldValue == 1){
                            $data .= 'checked="checked" ';
                        }
                    $data .= '" />
                </div>
                <div class="row-title checkbox-field-label">
                    <label id="' . $fieldName . 'msg" for="' . $fieldName . '">' . __($fieldtitle,'js-jobs');
                        if ($required == 1) {
                            $data .= '<span class="error-msg">*</span>';
                        }
                    $data .= '</label>
                </div>
            </div>';
        return $data;
    }

    function getResumeSelectFieldJobManager($fieldtitle,$fieldName,$fieldValue,$required,$column){
        $html="";
        if($column==4){
            $html .= '<div class="js-col-md-3 '.$this->class_prefix.'-field-padding">';
        }else{
            $html .= '<div class="js-col-md-12 js-form-wrapper">';

        }
        $html .= '
            <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont">' . __($fieldtitle,'js-jobs');
            if($required==1){
                $html .='<span class="'.$this->class_prefix.'-error-msg">*</span>';
            }
            $html .='</div>
            <div class="js-col-md-12 js-form-value">' . $fieldValue . '</div>
        </div>';
        return $html;
    }

    function getResumeSelectField($field, $fieldValue,$column=0,$themecall=null) {

        $fieldtitle="";
        if(isset($field->fieldtitle)) $fieldtitle = $field->fieldtitle;
        $fieldName="";
        if(isset($field->field)) $fieldName = $field->field;
        $required="";
        if(isset($field->required)) $required = $field->required;
        if(null != $themecall){
            $data=$this->getResumeSelectFieldJobManager($fieldtitle,$fieldName,$fieldValue,$required,$column);

        }else{
            $data = '
                <div class="resume-row-wrapper form resumefieldswrapper">
                    <label class="row-title resumefieldtitle" for="' . $fieldName . '">' . __($fieldtitle,'js-jobs');
                        if ($required == 1) {
                            $data .= '<span class="error-msg">*</span>';
                        }
            $data .= '
                    </label>
                    <div class="row-value resumefieldvalue">
                        ' . $fieldValue .'
                    </div>
                </div>';
        }
        return $data;
    }
    function getResumeSectionTitleJobManager($sectionid,$imagename,$imagealt,$title) {

        $html='<div id="jsresume_sectionid'.$sectionid.'" class="'.$this->class_prefix.'-addresume-title-wrap">
                <span class="'.$this->class_prefix.'-addresume-icon">
                    <img alt="'.$imagealt.'" title="'.$title.'" src="'.JOB_MANAGER_IMAGE.'/'.$imagename.'">
                </span>
                <h3 class="'.$this->class_prefix.'-addresume-title">
                    ' . __($title, 'js-jobs') . '
                </h3>
            </div>';

        return $html;
    }

    function getSectionTitle($sectionFor, $title , $sectionid,$themecall) {
        if ($sectionFor == "education") {
            $sectionFor = "institute";
        }
        switch ($sectionFor) {
            case 'personal':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"personal-info.png",__("Personal information","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title personal"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/personal-info.png" />' . __($title, 'js-jobs') . '</div>';
                }
            break;
            case 'address':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"location.png",__("Location","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/word.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'institute':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"education.png",__("Education","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/education.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'employer':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"employer.png",__("Employer","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/employer.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'skills':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"skills.png",__("Skills","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/skills.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'editor':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"resume.png",__("Resume","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/resume.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'reference':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"referances.png",__("References","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/referances.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            case 'language':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobManager($sectionid,"language.png",__("language","js-jobs"),$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.$sectionid.'" class="resume-section-title "><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/language.png" />' . __($title, 'js-jobs') . '</div>';
                }

            break;
            default:
            break;
        }
        return $html;
    }

    function getFieldForPersonalSectionJobManager($fieldtitle,$fieldName,$fieldValue,$required,$columns,$extraattr){

        $data="";

        if($columns == 3){
            $data .= '<div class="js-col-md-4 '.$this->class_prefix.'-field-padding">';
        }else{
            $data .= '<div class="js-col-md-12 js-form-wrapper">';
        }
        $data .= '
            <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont">' . __($fieldtitle,'js-jobs');
            if ($required == 1) {
                $data .= '<span class="'.$this->class_prefix.'-error-msg">*</span>';
            }
            $data .='</div>
            <div class="js-col-md-12 js-form-value">';
                $data .='<input class="inputbox form-control '.$this->class_prefix.'-input-field';

                        if ($required == 1 ) {
                                $data .= ' required ';
                        }
                        if($fieldName == "date_of_birth" || $fieldName == "date_start" ){
                            $data .= ' custom_date ';
                            if($fieldValue == '0000-00-00 00:00:00'){
                                $fieldValue = '';
                            }
                        }
                        if(jsjobslib::jsjobs_strpos($fieldValue , '0001') !== false){
                            $fieldValue = "";
                        }
                        $data .= '"';
                        if ($fieldName == "email_address") {
                            $data .= ' data-validation="email"';
                        }
                        if ($required == 1 && $fieldName != "email_address") {
                            $data .= ' data-validation="required"';
                        }
                $name = 'sec_1['.$fieldName.']';
                $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" value = "' . $fieldValue.'"' ;
                if (!empty($extraattr)){
                    foreach ($extraattr AS $key => $val){
                        $data .= ' ' . $key . '="' . $val . '"';
                    }
                }
                $data .= '" />';
            $data .='</div>
        </div>';
        return $data;
    }

    function getFieldForPersonalSection($field, $fieldValue, $columns = 0,$extraattr=array(),$themecall=null) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;
        $style = '';
        $jb_jm_class="";
        if($columns == 3){
            $style = ' formresumethree';
        }
        if(null != $themecall){
                $data=$this->getFieldForPersonalSectionJobManager($fieldtitle,$fieldName,$fieldValue,$required,$columns,$extraattr);
        }else{
            $data = '
                <div class="resume-row-wrapper form resumefieldswrapper'.$style.'">
                    <label class="row-title resumefieldtitle" for="' . $fieldName . '">';
                        $data .= __($fieldtitle,'js-jobs');
            if ($required == 1) {
                        $data .= '<span class="error-msg">*</span>';
            }
            $data .= '</label>
                    <div class="row-value resumefieldvalue">
                        <input class="inputbox';
                            if ($required == 1 ) {
                                $data .= ' required ';
                            }
                            if($fieldName == "date_of_birth" || $fieldName == "date_start" ){
                                $data .= ' custom_date ';
                            }
                            $data .= '"';
                            if ($fieldName == "email_address") {
                                $data .= ' data-validation="email"';
                            }
                            if ($required == 1 && $fieldName != "email_address") {
                                $data .= ' data-validation="required"';
                            }
                            if(jsjobslib::jsjobs_strpos($fieldValue , '0001') !== false){
                                $fieldValue = "";
                            }




            $name = 'sec_1['.$fieldName.']';
            $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" value = "' . jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'"' ;
            if (!empty($extraattr)){
                foreach ($extraattr AS $key => $val){
                    $data .= ' ' . $key . '="' . $val . '"';
                }
            }
                $data .= ' />
                </div>
            </div>';
        }
        return $data;
    }
    function getFieldForMultiSectionJobManager($fieldtitle,$fieldName,$required,$fieldValue,$field_id_for,$section, $sectionid, $ishidden){
            $html = '<div class="js-col-md-12 js-form-wrapper">
            <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont" for="'.$field_id_for.'">' . __($fieldtitle,'js-jobs');
                if ($required == 1) {
                    $html .= '<span class="'.$this->class_prefix.'-error-msg">*</span>';
                }
              $html .='</div>
            <div class="js-col-md-12 js-form-value">';
                $data_required = '';
                $class_required = '';
                if($ishidden != ''){
                    if ($required == 1) {
                        $data_required = 'data-myrequired="required"';
                    }
                    if ($fieldName == "email_address") {
                        $data_required = 'data-myrequired="required validate-email"';
                    }
                }else{
                    if ($required == 1) {
                        $class_required = ' required';
                    }
                    if ($fieldName == "email_address") {
                        $class_required = ' required validate-email';
                    }
                }

                $html .= '<input class="inputbox form-control '.$this->class_prefix.'-input-field '.$class_required.'" '.$data_required;

                switch ($section) {
                    case '2': $section = 'sec_2'; break;
                    case '3': $section = 'sec_3'; break;
                    case '4': $section = 'sec_4'; break;
                    case '5': $section = 'sec_5'; break;
                    case '6': $section = 'sec_6'; break;
                    case '7': $section = 'sec_7'; break;
                    case '8': $section = 'sec_8'; break;
                }
                $name = $section."[$fieldName][$sectionid]";

                $html .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="250" value = "' . $fieldValue . '" />';

            $html .= '</div>
        </div>';
        return $html;

    }

    function getFieldForMultiSection($field, $fieldValue, $section, $sectionid, $ishidden, $maxlength,$themecall ) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;

        $field_id_for = $fieldName.$section.$sectionid;
        if(null !=$themecall){

            $data=$this->getFieldForMultiSectionJobManager($fieldtitle,$fieldName,$required,$fieldValue,$field_id_for,$section, $sectionid, $ishidden);

        }else{

            $data = '
                <div class="resumefieldswrapper resume-row-wrapper form">
                    <label class="row-title resumefieldtitle" for="' . $field_id_for . '">';
                $data .= __($fieldtitle,'js-jobs');

            if ($required == 1) {
                        $data .= '<span class="error-msg">*</span>';
            }
            $data .= '</label>';
            $data .= '<div class="resumefieldvalue row-value">';

            $data_required = '';
            $class_required = '';
            if($ishidden != ''){
                if ($required == 1) {
                    $data_required = 'data-myrequired="required"';
                }
                if ($fieldName == "email_address") {
                    $data_required = 'data-myrequired="required validate-email"';
                }
            }else{
                if ($required == 1) {
                    $class_required = ' required';
                }
                if ($fieldName == "email_address") {
                    $class_required = ' required validate-email';
                }
            }

            $data .= '<input class="inputbox'.$class_required.'" '.$data_required;

            switch ($section) {
                case '2': $section = 'sec_2'; break;
                case '3': $section = 'sec_3'; break;
                case '4': $section = 'sec_4'; break;
                case '5': $section = 'sec_5'; break;
                case '6': $section = 'sec_6'; break;
                case '7': $section = 'sec_7'; break;
                case '8': $section = 'sec_8'; break;
            }
            if($maxlength == 0){
                $maxlength = '';
            }
            $name = $section."[$fieldName][$sectionid]";

            $data .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="' . $maxlength . '" value = "' . jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false) . '" />
                    </div>
                </div>';
        }

        return $data;
    }

    function prepareDateFormat(){

        $config_date=JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('date_format');
        if ($config_date == 'm/d/Y'){
            $dash = '/';
        }else{
            $dash = "-";
        }
        $dateformat = $config_date;
        $firstdash = jsjobslib::jsjobs_strpos($dateformat, $dash, 0);
        $firstvalue = jsjobslib::jsjobs_substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = jsjobslib::jsjobs_strpos($dateformat, $dash, $firstdash);
        $secondvalue = jsjobslib::jsjobs_substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = jsjobslib::jsjobs_substr($dateformat, $seconddash, jsjobslib::jsjobs_strlen($dateformat) - $seconddash);
        //$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $js_dateformat =  $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;

        return $js_dateformat;
    }

    function getCityFieldForForm($for , $sectionid, $object, $field , $ishidden,$themecall){
        $html = '';
        switch ($for) {
            case '2':
                $cityfor = 'address'; break;
            case '3':
                $cityfor = 'institute'; break;
            case '4':
                $cityfor = 'employer'; break;
            case '7':
                $cityfor = 'reference'; break;
            break;
        }
        $data_required = '';
        $city_required = ($field->required ? 'required' : '');
        if($ishidden){
            if($city_required){
                $data_required = 'data-myrequired="required"';
                $city_required = '';
            }
        }
        $cityforedit = '';
        $data = array('city_id' => null, 'city_name' => null);
        if (isset($object->{$field->field}) AND ($object->{$field->field})) {
            $cityforedit = 1;
            $data['city_id'] = $object->{$field->field};
            $data['city_name'] = $object->cityname ;
            $default_location_view=JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('defaultaddressdisplaytype');
            switch ($default_location_view) {
                case 'csc':
                    $data['city_name'] .= ", " . $object->statename . ", " . $object->countryname;
                    break;
                case 'cs':
                    $data['city_name'] .= ", " . $object->statename;
                    break;
                case 'cc':
                    $data['city_name'] .= ", " . $object->countryname;
                    break;
            }
        }

        $field_city_id="'".$cityfor.'_city_'.$sectionid."'";
        $edit_field_city_id="'".$cityfor.'cityforedit_'.$sectionid."'";
        $html .= '
            <div class="resume-row-wrapper form">
                <label id="'.$cityfor.'_citymsg" class="row-title resumefieldtitle" for="'.$cityfor.'_city_'.$sectionid.'">' . __($field->fieldtitle,'js-jobs');
                    if ($field->required == 1) {
                        $html .= '<span class="error-msg">*</span>';
                    }
        $html .= '</label>
                <div class="resumefieldvalue row-value">
                    <input data-for="'.$cityfor.'_'.$sectionid.'" class="inputbox jstokeninputcity ' . $city_required . '" '.$data_required.' type="text" name="sec_'.$for.'['.$cityfor.'_city]['.$sectionid.']" id="'.$cityfor.'_city_'.$sectionid.'" size="40" maxlength="100" value="'.$data['city_name'].'" />
                    <input type="hidden" name="sec_'.$for.'['.$cityfor.'cityforedit]['.$sectionid.']" id="'.$cityfor.'cityforedit_'.$sectionid.'" value="'.$cityforedit.'" />
                    <input type="hidden" class="jscityid" name="jscityid" value="'.$data['city_id'].'" />
                    <input type="hidden" class="jscityname" name="jscityname" value="'.$data['city_name'].'" />
                </div>';

            $html .= '</div>';

        return $html;
    }
    function makeLanguageSectionFields($themecall=null){
        $languages="";
        if(isset(jsjobs::$_data[0]['language_section'])) $languages = jsjobs::$_data[0]['language_section'];
        //$fields_ordering = jsjobs::$_data[1];
        $sections_allowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('max_resume_languages');

        $html = '<div id="jssection_language" class="section_wrapper jssectionwrapper">';
        $j = 1;
        if(empty($languages)){
            $languages = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $languages[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($languages);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $languages[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($languages as $language) {
            //$jssection_hide = isset($language->id) ? '' :((isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide');
            $jssection_hide = isset($language->id) ? '' : 'jssection_hide';
            $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_language_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/delete-icon.png" />';
            foreach (jsjobs::$_data[2][8] as $field) {
                switch ($field->field) {
                    case "language":
                        $fvalue = isset($language->language) ? $language->language : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide,50,$themecall);
                        break;
                    case "language_reading":
                        $fvalue = isset($language->language_reading) ? $language->language_reading : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide,20,$themecall);
                        break;
                    case "language_writing":
                        $fvalue = isset($language->language_writing) ? $language->language_writing : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide,20,$themecall);
                        break;
                    case "language_understanding":
                        $fvalue = isset($language->language_understanding) ? $language->language_understanding : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide,20,$themecall);
                        break;
                    case "language_where_learned":
                        $fvalue = isset($language->language_where_learned) ? $language->language_where_learned : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide,250,$themecall);
                        break;
                    default:
                        $html .= $this->getResumeFormUserField($field, $language , 8 , $sectionid, $jssection_hide,$themecall);
                    break;
                }
            }
            $id = isset($language->id) ? $language->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis8'.$sectionid.'" class="jsdeletethissection" name="sec_8[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_8[id]['.$sectionid.']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.$this->class_prefix.'-resume-section-sep" />';
                    }
                    $html .='</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'language\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add New','js-jobs').'&nbsp;'. __('Language','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'language\');"><div class="jsresume_plus">+</div> '.__('Add New','js-jobs').'&nbsp;'. __('Language','js-jobs').'</div>';
            }
        }

        return $html;
    }

    function makeReferenceSectionFields($themecall=null){
        $references="";
        if(isset(jsjobs::$_data[0]['reference_section'])) $references = jsjobs::$_data[0]['reference_section'];
        //$fields_ordering = jsjobs::$_data[1];
        $sections_allowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('max_resume_references');

        $html = '<div id="jssection_reference" class="section_wrapper jssectionwrapper">';
        $j = 1;
        if(empty($references)){
            $references = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $references[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($references);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $references[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($references as $reference) {
            //$jssection_hide = isset($reference->id) ? '' :((isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide');

            $jssection_hide = isset($reference->id) ? '' : 'jssection_hide';
            $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_reference_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/delete-icon.png" />';
            foreach (jsjobs::$_data[2][7] as $field) {
                switch ($field->field) {
                    case "reference":
                        $fvalue = isset($reference->reference) ? $reference->reference : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,50,$themecall);
                        break;
                    case "reference_name":
                        $fvalue = isset($reference->reference_name) ? $reference->reference_name : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,50,$themecall);
                        break;
                    case "reference_city":
                        $for = 7;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $reference, $field ,$jssection_hide,$themecall);
                        break;
                    case "reference_zipcode":
                        $fvalue = isset($reference->reference_zipcode) ? $reference->reference_zipcode : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,20,$themecall);
                        break;
                    case "reference_address":
                        $fvalue = isset($reference->reference_address) ? $reference->reference_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,150,$themecall);
                        break;
                    case "reference_phone":
                        $fvalue = isset($reference->reference_phone) ? $reference->reference_phone : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,50,$themecall);
                        break;
                    case "reference_relation":
                        $fvalue = isset($reference->reference_relation) ? $reference->reference_relation : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,50,$themecall);
                        break;
                    case "reference_years":
                        $fvalue = isset($reference->reference_years) ? $reference->reference_years : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide,10,$themecall);
                        break;
                    default:
                        $html .= $this->getResumeFormUserField($field, $reference , 7 ,  $sectionid, $jssection_hide,$themecall);
                        break;
                }
            }
            $id = isset($reference->id) ? $reference->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis7'.$sectionid.'" class="jsdeletethissection" name="sec_7[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_7[id]['.$sectionid.']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.$this->class_prefix.'-resume-section-sep" />';
                    }
                    $html .='</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'reference\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add New','js-jobs').' '. __('Reference','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this, \'reference\');"><div class="jsresume_plus">+</div> '.__('Add New','js-jobs') .' '. __('Reference','js-jobs').'</div>';
            }
        }
        return $html;
    }

    function makeResumeSectionFields($themecall=null){
        $resume="";
        if(isset(jsjobs::$_data[0]['personal_section'])) $resume = jsjobs::$_data[0]['personal_section'];
        //$fields_ordering = jsjobs::$_data[1];

        $html = '<div id="jssection_resume" class="section_wrapper jssectionwrapper ">';
        if(empty($resume->resume)){
            //$jssection_hide = (isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide';
            $jssection_hide = 'jssection_hide';
        }else{
            ///$jssection_hide = (isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide';
            $jssection_hide = '';
        }
        $sectionid = 0;
        // <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JURI::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
        // <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JURI::root().'components/com_jsjobs/images/resume/delete-icon.png" />
        $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_resume_'.$sectionid.'">';
        foreach (jsjobs::$_data[2][6] as $field) {
            switch ($field->field) {
                case "resume":
                    $fvalue = isset($resume->resume) ? $resume->resume : '';
                    $req = ($field->required ? 'required' : '');
                    $data_required = '';
                    if($jssection_hide){
                        if($req){
                            $data_required = 'data-myrequired="required"';
                            $req = '';
                        }
                    }
                    $html .= '
                        <div class="resumefieldswrapper resume-row-wrapper form js-col-md-12 js-form-wrapper">
                            <label id="" class="row-title resumefieldtitle" for="resumeeditor">' . __($field->fieldtitle,'js-jobs');
                                if ($field->required == 1) {
                                    $html .= '<span class="error-msg">*</span>';
                                }
                    //$name = 'sec_6[resume]['.$sectionid.']';
                    $name = 'resumeeditor';

                    //$value=wp_editor(isset($resume->resume) ? $resume->resume: '', 'resume', array('media_buttons' => false, 'data-validation' => $req));
                    $value=isset($resume->resume) ? $resume->resume: '';
                    $efield = JSJOBSformfield::textarea('resume', $value, array('class' => 'inputbox one resumeeditor form-control '.$this->class_prefix.'-textarea-field', 'height'=>'270px','rows'=>'10','cols'=>'40'));
                    $efield .= JSJOBSformfield::hidden('resume_edit_val','');
                    $html .= '</label>
                            <div class="row-value resumefieldvalue">
                                '.$efield.'
                            </div>
                        </div>';
                    break;
                default:
                    $html .= $this->getResumeFormUserField($field, $resume , 6 , $sectionid, $jssection_hide,$themecall);
                break;
            }
        }
        $id = '';
        $deletethis = (empty($resume->resume)) ? 1 : 0;
        $html .= '<input type="hidden" id="deletethis6'.$sectionid.'" class="jsdeletethissection" name="sec_6[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                    <input type="hidden" id="id" name="sec_6[id]['.$sectionid.']" value="'.$id.'">
            </div></div>';
        if(empty($resume->resume)){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'resume\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add Resume','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this, \'resume\');"><div class="jsresume_plus">+</div> '.__('Add Resume','js-jobs').'</div>';

            }
        }
        return $html;
    }

    function makeSkillsSectionFields($themecall=null){
        $skills="";
        if(isset(jsjobs::$_data[0]['personal_section'])){
            $skills = jsjobs::$_data[0]['personal_section'];
        }
        //$fields_ordering = jsjobs::$_data[1];
        $html = '<div id="jssection_skills" class="jssectionwrapper section_wrapper">';
        if(empty($skills->skills)){
            $jssection_hide = 'jssection_hide';
        }else{
            $jssection_hide = '';
        }
        $sectionid = 0;
        $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_skills_'.$sectionid.'">';
        foreach (jsjobs::$_data[2][5] as $field) {
            switch ($field->field) {
                case "skills":
                    $fvalue = isset($skills->skills) ? $skills->skills : '';
                    $resume_required = ($field->required ? 'required' : '');
                    $data_required = '';
                    if($jssection_hide){
                        if($resume_required){
                            $data_required = 'data-myrequired="required"';
                            $resume_required = '';
                        }
                    }
                    $html .= '
                        <div class="resumefieldswrapper resume-row-wrapper form js-col-md-12 js-form-wrapper">
                            <label id="skillsmsg" class="row-title resumefieldtitle" for="skills">' . __($field->fieldtitle,'js-jobs');
                                if ($field->required == 1) {
                                    $html .= '<span class="error-msg">*</span>';
                                }
                    $html .= '</label>
                            <div class="row-value resumefieldvalue">
                                <textarea  class="inputbox '.$resume_required.' form-control '.$this->class_prefix.'-textarea-field" '.$data_required.' name="skills" id="skills" cols="180" rows="5" >'.$fvalue.'</textarea>
                            </div>
                        </div>';
                    break;
                default:
                    $html .= $this->getResumeFormUserField($field, $skills , 5 ,  $sectionid, $jssection_hide,$themecall);
                break;
            }
        }
        $id = '';
        $deletethis = (empty($skills->skills)) ? 1 : 0;
        $html .= '<input type="hidden" id="deletethis5'.$sectionid.'" class="jsdeletethissection" name="sec_5[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                    <input type="hidden" id="id" name="sec_5[id]['.$sectionid.']" value="'.$id.'">
            </div></div>';

        if(empty($skills->skills)){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'skills\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add Skills','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'skills\');"><div class="jsresume_plus">+</div> '.__('Add Skills','js-jobs').'</div>';
            }

        }
        return $html;

    }

    function makeAddressSectionFields($themecall=null) {
        $addresses=array();
        if(isset(jsjobs::$_data[0]['address_section'])){
            $addresses = jsjobs::$_data[0]['address_section'];
        }
        //$fields_ordering = jsjobs::$_data[1];
        $sections_allowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('max_resume_addresses');
        $j = 1;
        $html = '<div id="jssection_address" class="jssectionwrapper section_wrapper">';
        if(empty($addresses)){
            $addresses = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $addresses[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($addresses);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $addresses[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($addresses as $address) {

            //$jssection_hide = isset($address->id) ? '' :((isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide');
            $jssection_hide = isset($address->id) ? '' : 'jssection_hide';

            //$jssection_hide = isset($address->id) ? '' : '';
            $html .= '<div class="section_wrapper form '.$jssection_hide.' jssection_address_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/delete-icon.png" />';
            foreach (jsjobs::$_data[2][2] as $field) {
                switch ($field->field) {
                    case "address_city":
                        $for = 2;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $address, $field ,$jssection_hide,$themecall);
                        break;
                    case "address_zipcode":
                        $fieldValue = isset($address->address_zipcode) ? $address->address_zipcode : '';
                        $html .= $this->getFieldForMultiSection($field, $fieldValue, 2, $sectionid, $jssection_hide,60,$themecall);
                        break;
                    case "address":
                        $fieldValue = isset($address->address) ? $address->address : '';
                        $html .= $this->getFieldForMultiSection($field, $fieldValue, 2, $sectionid, $jssection_hide,0,$themecall);
                    break;
                    case "address_location": //longitude and latitude
                        $required = ($field->required ? 'required' : '');
                        $latitude = isset($address->latitude) ? $address->latitude : '';
                        $longitude = isset($address->longitude) ? $address->longitude : '';
                        $data_required = '';
                        if($jssection_hide){
                            if($required){
                                $data_required = 'data-myrequired="required"';
                                $required = '';
                            }
                        }

                        $html .='<div class="resume-row-wrapper form js-col-md-12 js-form-wrapper">
                            <div class="resumefieldswrapper loc-field">
                                <label id="longitudemsg" class="resumefieldtitle" for="longitude">' . __($field->fieldtitle,'js-jobs');
                                    if ($field->required == 1) {
                                        $html .= '<span class="'.$this->class_prefix.'-error-msg error-msg">*</span>';
                                    }
                        $html .= '</label>
                                <div class="resumefieldvalue">
                                    <div id="outermapdiv_'.$sectionid.'" class="outermapdiv">
                                        <div id="map_'.$sectionid.'" class="map" style="width:' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('mapwidth') . 'px; height:' . JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('mapheight'). 'px;visibility:hidden;display:none;">
                                            <div id="closetag_'.$sectionid.'"><a class="js-resume-close-cross" onclick="hidediv('.$sectionid.');">' . __('X','js-jobs') . '</a></div>
                                            <div id="map_container_'.$sectionid.'" class="map_container" style="position: relative; overflow: hidden;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="resumefieldvalueform">
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <input  class="inputbox form-control '.$this->class_prefix.'-input-field ' . $required . '" '.$data_required.' type="text" id="latitude_'.$sectionid.'" name="sec_2[latitude]['.$sectionid.']" size="25" maxlength="50" placeholder="' . __('Latitude','js-jobs') . '" value = "'.jsjobslib::jsjobs_htmlspecialchars($latitude, ENT_QUOTES, 'UTF-8', false).'" />
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <input  class="inputbox form-control '.$this->class_prefix.'-input-field' . $required . '" '.$data_required.' type="text" id="longitude_'.$sectionid.'" name="sec_2[longitude]['.$sectionid.']" size="25" maxlength="50" placeholder="' . __('Longitude','js-jobs') . '" value = "'.jsjobslib::jsjobs_htmlspecialchars($longitude, ENT_QUOTES, 'UTF-8', false).'" />
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <a class="anchor map-link" onclick="showdiv('.$sectionid.');loadMap('.$sectionid.');"><span id="anchor">' . __('Map','js-jobs') . '</span></a>
                                    </div>
                                </div>
                            </div></div>';
                    break;
                    default:
                        $html .= $this->getResumeFormUserField($field, $address , 2 ,  $sectionid, $jssection_hide,$themecall);
                    break;
                }
            }
            $id = isset($address->id) ? $address->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis2'.$sectionid.'" class="jsdeletethissection" name="sec_2[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_2[id]['.$sectionid.']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.$this->class_prefix.'-resume-section-sep" />';
                    }
                    $html .= '</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'address\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add New','js-jobs').' '. __('Address','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'address\');"><div class="jsresume_plus">+</div> '.__('Add New','js-jobs').' '. __('Address','js-jobs').'</div>';
            }
        }
        return $html;
    }

    function makeInstituteSectionFields($themecall=null){
        $institutes="";
        if(isset(jsjobs::$_data[0]['institute_section'])){
            $institutes = jsjobs::$_data[0]['institute_section'];
        }
        //$fields_ordering = jsjobs::$_data[1];
        $sections_allowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('max_resume_institutes');
        $j = 1;
        $html = '<div id="jssection_institute" class="jssectionwrapper section_wrapper">';
        if(empty($institutes)){
            $institutes = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $institutes[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($institutes);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $institutes[] = 'new';
                }
            }
        }
        $sectionid = 0;
        foreach ($institutes as $institute) {
            //$jssection_hide = isset($institute->id) ? '' :((isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide');
            $jssection_hide = isset($institute->id) ? '' : 'jssection_hide';
            $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_institute_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/delete-icon.png" />';
            foreach (jsjobs::$_data[2][3] as $field) {
                switch ($field->field) {
                    case "institute":
                        $fvalue = isset($institute->institute) ? $institute->institute : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide,100,$themecall);
                    break;
                    case "institute_city":
                        $for = 3;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $institute, $field , $jssection_hide,$themecall);
                        break;
                    case "institute_address":
                        $fvalue = isset($institute->institute_address) ? $institute->institute_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide,150,$themecall);
                        break;
                    case "institute_certificate_name":
                        $fvalue = isset($institute->institute_certificate_name) ? $institute->institute_certificate_name : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide,100,$themecall);
                        break;
                    case "institute_study_area":
                        $fvalue = isset($institute->institute_study_area) ? $institute->institute_study_area : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide,0,$themecall);
                        break;
                    case "institute_date_from":
                        if($themecall == null){
                            $html .= '
                                <div class="resume-row-wrapper form resumefieldswrapper formresumetwo js-col-xs-12 js-col-md-6">
                                    <label class="row-title resumefieldtitle" for="institue_from_date4'.$sectionid.'">' . __($field->fieldtitle,'js-jobs');
                                    if ($field->required == 1) {
                                        $html .= '<span class="'.$this->class_prefix.'-error-msg error-msg">*</span>';
                                    }
                            $html .='</label>
                                    <div class="row-value resumefieldvalue">';
                                        $fieldValue = isset($institute->fromdate) ? $institute->fromdate : '';
                                        $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" name="sec_3[fromdate][]" id="institue_from_date4'.$sectionid.'" maxlength="60" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                            $html .='</div>
                                </div>';
                        }else{
                            $html .= '<div class="js-col-md-12 js-form-wrapper">
                                        <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont" >
                                            '. __($field->fieldtitle,"js-jobs");
                                            if ($field->required == 1) {
                                                $html .= '<span class="'.$this->class_prefix.'-error-msg">*</span>';
                                            }
                                            $html .='
                                        </div>
                                        <div class="js-col-md-12 js-form-value">';
                                            $fieldValue = isset($institute->fromdate) ? $institute->fromdate : '';
                                            $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" name="sec_3[fromdate][]" id="institue_from_date4'.$sectionid.'" maxlength="60" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                            $html .='    </div>
                                    </div>';
                        }
                        break;
                    case "institute_date_to":
                        if($themecall == null){

                            $html .= '
                                <div class="resume-row-wrapper form resumefieldswrapper formresumetwo js-col-xs-12 js-col-md-6">
                                    <label class="row-title resumefieldtitle" for="institue_to_date4'.$sectionid.'">' . __($field->fieldtitle,'js-jobs');
                                    if ($field->required == 1) {
                                        $html .= '<span class="'.$this->class_prefix.'-error-msg error-msg">*</span>';
                                    }
                            $html .='</label>
                                    <div class="row-value resumefieldvalue">';
                                        $fieldValue = isset($institute->todate) ? $institute->todate : '';
                                        $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" name="sec_3[todate][]" id="institue_to_date4'.$sectionid.'" maxlength="60" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                            $html .='</div>
                                </div>';
                        }else{
                            $html .= '<div class="js-col-md-12 js-form-wrapper">
                                        <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont" >
                                            '. __($field->fieldtitle,"js-jobs");
                                            if ($field->required == 1) {
                                                $html .= '<span class="'.$this->class_prefix.'-error-msg">*</span>';
                                            }
                                            $html .='
                                        </div>
                                        <div class="js-col-md-12 js-form-value">';
                                            $fieldValue = isset($institute->todate) ? $institute->todate : '';
                                            $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" maxlength="60" name="sec_3[todate][]" id="institue_to_date4'.$sectionid.'" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                            $html .='    </div>
                                    </div>';
                        }


                    break;
                    default:
                        $html .= $this->getResumeFormUserField($field, $institute , 3 , $sectionid, $jssection_hide,$themecall);
                        break;
                }
            }
            $id = isset($institute->id) ? $institute->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis3'.$sectionid.'" class="jsdeletethissection" name="sec_3[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_3[id]['.$sectionid.']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.$this->class_prefix.'-resume-section-sep" />';
                    }
                    $html .='</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'institute\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add New','js-jobs').' '. __('Institute','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'institute\');"><div class="jsresume_plus">+</div> '.__('Add New','js-jobs').' '. __('Institute').'</div>';

            }
        }
        return $html;
    }

    function makeEmployerSectionFields($themecall=null){
        $employers="";
        if(isset(jsjobs::$_data[0]['employer_section'])){
            $employers = jsjobs::$_data[0]['employer_section'];
        }

        //$fields_ordering = jsjobs::$_data[1];
        $sections_allowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('max_resume_employers');
        $js_dateformat = $this->prepareDateFormat();
        $j = 1;
        $html = '<div id="jssection_employer" class="jssectionwrapper section_wrapper">';
        if(empty($employers)){
            $employers = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $employers[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($employers);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $employers[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($employers as $employer) {

            //$jssection_hide = isset($employer->id) ? '' :((isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid']))?"": 'jssection_hide');
            $jssection_hide = isset($employer->id) ? '' : 'jssection_hide';
            $html .= '<div class="section_wrapper form jssection_wrapper '.$jssection_hide.' jssection_employer_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.JSJOBS_PLUGIN_URL.'includes/images/resume/delete-icon.png" />';
            $counter = 0;
            foreach (jsjobs::$_data[2][4] as $field) {
                switch ($field->field) {
                    case "employer":
                        $fvalue = isset($employer->employer) ? $employer->employer : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,250,$themecall);
                        break;
                    case "employer_position":
                        $fvalue = isset($employer->employer_position) ? $employer->employer_position : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,150,$themecall);
                        break;
                    case "employer_resp":
                        $fvalue = isset($employer->employer_resp) ? $employer->employer_resp : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,0,$themecall);
                        break;
                    case "employer_pay_upon_leaving":
                        $fvalue = isset($employer->employer_pay_upon_leaving) ? $employer->employer_pay_upon_leaving : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,250,$themecall);
                        break;
                    case "employer_supervisor":
                        $fvalue = isset($employer->employer_supervisor) ? $employer->employer_supervisor : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,100,$themecall);
                        break;
                    case "employer_from_date":
                    case "employer_to_date":
                        if($counter == 0){
                            $html .= '<div class="fullwidthwrapper js-col-md-12 js-form-wrapper">';
                                $field_obj = '';
                                foreach (jsjobs::$_data[2][4] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "employer_from_date":
                                            $html .= '
                                                <div class="resume-row-wrapper form resumefieldswrapper formresumetwo js-col-xs-12 js-col-md-6">
                                                    <label class="row-title resumefieldtitle" for="employer_from_date4'.$sectionid.'">' . __($field_obj->fieldtitle,'js-jobs');
                                                    if ($field_obj->required == 1) {
                                                        $html .= '<span class="'.$this->class_prefix.'-error-msg error-msg">*</span>';
                                                    }
                                            $html .='</label>
                                                    <div class="row-value resumefieldvalue">';
                                                        $fieldValue = isset($employer->employer_from_date) ? $employer->employer_from_date : '';
                                                        $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" name="sec_4[employer_from_date][]" id="employer_from_date4'.$sectionid.'" maxlength="60" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                                            $html .='</div>
                                                </div>';
                                            break;
                                        case "employer_to_date":
                                            $html .= '
                                                <div class="resume-row-wrapper form resumefieldswrapper formresumetwo js-col-xs-12 js-col-md-6">
                                                    <label class="row-title resumefieldtitle" for="employer_to_date4'.$sectionid.'">' . __($field_obj->fieldtitle,'js-jobs');
                                                    if ($field_obj->required == 1) {
                                                        $html .= '<span class="'.$this->class_prefix.'-error-msg error-msg">*</span>';
                                                    }
                                            $html .='</label>
                                                    <div class="row-value resumefieldvalue">';
                                                        $fieldValue = isset($employer->employer_to_date) ? $employer->employer_to_date : '';
                                                        $html .= '<input type="text" class="input form-control '.$this->class_prefix.'-input-field custom_date" name="sec_4[employer_to_date][]" id="employer_to_date4'.$sectionid.'" maxlength="60" value="'.jsjobslib::jsjobs_htmlspecialchars($fieldValue, ENT_QUOTES, 'UTF-8', false).'">';
                                            $html .='</div>
                                                </div>';
                                        break;
                                    }
                                }
                            $html .= '</div>';
                        }
                        $counter = 1;
                        break;
                    case "employer_leave_reason":
                        $fvalue = isset($employer->employer_leave_reason) ? $employer->employer_leave_reason : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,0,$themecall);
                        break;
                    case "employer_city":
                        $for = 4;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $employer, $field , $jssection_hide,$themecall);
                        break;
                    case "employer_zip":
                        $fvalue = isset($employer->employer_zip) ? $employer->employer_zip : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,60,$themecall);
                        break;
                    case "employer_phone":
                        $fvalue = isset($employer->employer_phone) ? $employer->employer_phone : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,60,$themecall);
                        break;
                    case "employer_address":
                        $fvalue = isset($employer->employer_address) ? $employer->employer_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,150,$themecall);
                        break;
                    default:
                        $html .= $this->getResumeFormUserField($field, $employer , 4 , $sectionid, $jssection_hide,$themecall);
                    break;
                }
            }
            $id = isset($employer->id) ? $employer->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis4'.$sectionid.'" class="jsdeletethissection" name="sec_4[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_4[id]['.$sectionid.']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.$this->class_prefix.'-resume-section-sep" />';
                    }
                    $html .='</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="jsresume_addnewbutton '.$this->class_prefix.'-resume-addnewbutton" onclick="showResumeSection( this ,\'employer\');">
                <span class="'.$this->class_prefix.'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.__('Add New','js-jobs').'&nbsp;'. __('Employer','js-jobs').'
                </span></div>';
            }else{
                $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'employer\');"><div class="jsresume_plus">+</div> '.__('Add New','js-jobs').' '. __('Employer').'</div>';

            }
        }
        return $html;

    }

    function makePersonalSectionFields($themecall=null) {
        $resume="";
        if(isset(jsjobs::$_data[0]['personal_section'])){
            $resume = jsjobs::$_data[0]['personal_section'];
        }
        //$fields_ordering = jsjobs::$_data[1];
        $resumelists = "";
        $js_dateformat = $this->prepareDateFormat();
        $sectionid = 0;
        $data = '<div class="section_wrapper" data-section="personal" data-sectionid="">';
        $data = '<div class="section_wrapper" data-section="personal" data-sectionid="">';
            $name_counter = 0;
            $cell_counter = 0;
            $date_counter = 0;
            $available_counter = 0;
            foreach (jsjobs::$_data[2][1] as $field) {
            //foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "application_title":
                        $fieldValue = isset($resume->application_title) ? $resume->application_title : "";
                        $extraattr = array('maxlength' => '150');
                        $data .= $this->getFieldForPersonalSection($field, $fieldValue,'',$extraattr,$themecall);
                        break;
                    case "first_name":
                    case "middle_name":
                    case "last_name":
                        if($name_counter == 0){
                            $data .= '<div class="fullwidthwrapper js-form-wrapper">';
                                $field_obj = '';
                                foreach (jsjobs::$_data[2][1] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "first_name":
                                                $fieldValue = isset($resume->first_name) ? $resume->first_name : "";
                                                $extraattr = array('maxlength' => '150');
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3,$extraattr,$themecall);
                                            break;
                                        case "middle_name":
                                                $fieldValue = isset($resume->middle_name) ? $resume->middle_name : "";
                                                $extraattr = array('maxlength' => '150');
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3,$extraattr,$themecall);
                                            break;
                                        case "last_name":
                                                $fieldValue = isset($resume->last_name) ? $resume->last_name : "";
                                                $extraattr = array('maxlength' => '150');
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3,$extraattr,$themecall);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $name_counter = 1;
                        break;
                    case "email_address": $email_required = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume->email_address) ? $resume->email_address : "";
                            $extraattr = array('maxlength' => '200');
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'',$extraattr,$themecall);
                        break;
                    case "cell":
                    case "home_phone":
                    case "work_phone":
                        if($cell_counter == 0){
                            $data .= '<div class="fullwidthwrapper js-form-wrapper">';
                                $field_obj = '';
                                foreach (jsjobs::$_data[2][1] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "cell":
                                            $fieldValue = isset($resume->cell) ? $resume->cell : "";
                                            $extraattr = array('maxlength' => '60');
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3,$extraattr,$themecall);
                                            break;
                                        case "home_phone":
                                            $fieldValue = isset($resume->home_phone) ? $resume->home_phone : "";
                                            $extraattr = array('maxlength' => '60');
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3,$extraattr,$themecall);
                                            break;
                                        case "work_phone":
                                            $fieldValue = isset($resume->work_phone) ? $resume->work_phone : "";
                                            $extraattr = array('maxlength' => '60');
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3,$extraattr,$themecall);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $cell_counter = 1;
                        break;
                    case "gender":
                            $value=isset($resume->gender)?$resume->gender:"";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('gender', JSJOBSincluder::getJSModel('common')->getGender(), $value,'sec_1', __('Select','js-jobs') .'&nbsp;'. __('Gender', 'js-jobs'), array('class' => 'inputbox form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "photo":
                        $text = __($field->fieldtitle,'js-jobs');

                        $photo_required = ($field->required ? 'required' : '');
                        $imgpath = '';
                        if (!empty($resume->photo)) {
                            $wpdir = wp_upload_dir();
                            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                            $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->id . '/photo/' . $resume->photo;
                        } else {
                            $img = JSJOBS_PLUGIN_URL . 'includes/images/users.png';
                        }
                        $fieldvalue = '<input type="file" name="photo" class="photo jsjobs-browser-hidden-element" id="photo" />
                        <img class="rs_photo" id="rs_photo" src="' . $img . '"/><br>';
                        if (isset($resume->id) && !empty($resume->photo)) {
                            $fieldvalue .= '<span class="remove-file" onclick="return removeLogo('. esc_js($resume->id).');"><img src="'. JSJOBS_PLUGIN_URL.'includes/images/no.png"></span>';
                        }
                        $logoformat = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                        $maxsize = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('resume_photofilesize');
                        $p_detail = '('.$logoformat.')<br>';
                        $p_detail .= '('.__("Max logo size allowed","js-jobs").' '.$maxsize.' Kb)';

                        $fieldvalue .= $p_detail;
                        $data .= $this->getRowForForm($text, $fieldvalue,$themecall);

                        /*$data .= '<input type="file" name="photo" class="photo" id="photo" />
                        <img class="rs_photo" id="rs_photo" src="' . $img . '"/><br>';
                            $logoformat = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                            $maxsize = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('resume_photofilesize');
                            $p_detail = '('.$logoformat.')<br>';
                            $p_detail .= '('.__("Max logo size allowed","js-jobs").' '.$maxsize.' Kb)';

                            //$data .= $p_detail;
                            $data .= $this->getFieldForPersonalSection($field, $p_detail);*/

                    break;
                    case "resumefiles":

                        $text = __($field->fieldtitle,'js-jobs');
                        $req = ''; // for checking field is required or not
                        if ($field->required == 1) {
                            $text .= '<span style="color:red;">*</span>';
                            $req = 'required';
                        }
                        $fieldvalue = '<input type="file" id="resumefiles" class="jsjobs-browser-hidden-element" name="resumefiles[]" data-validation="' . $req . '" multiple="true" />
                                    <div id="resumefileswrapper"><span class="livefiles" style="display:inline-block;float:left;"></span>';
                        if (!empty(jsjobs::$_data[0]['file_section'])) {
                            foreach (jsjobs::$_data[0]['file_section'] AS $file) {
                                $fieldvalue .= '<a href="#" id="file_' . $file->id . '" onclick="deleteResumeFile(' . $file->id . ');" class="file">
                                            <span class="filename">' . $file->filename . '</span><span class="fileext"></span>
                                            <img class="filedownload" src="' . JSJOBS_PLUGIN_URL . 'includes/images/resume/cancel.png" />
                                        </a>';
                            }
                        }
                        $fieldvalue .= '<span class="resume-selectfiles"><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/resume/upload-icon.png" /></span>';
                        $fieldvalue .= '</div>';
                        $data .= $this->getRowForForm($text, $fieldvalue,$themecall);

                        /*$files_required = ($field->required ? 'required' : '');
                        $data .= '
                            <div class="resumefieldswrapper">
                                <label class="resumefieldtitle" for="resumefile">' . __($field->fieldtitle,'js-jobs');
                                    if ($field->required == 1) {
                                        $data .= '<span class="error-msg">*</span>';
                                    }
                                $data .= '</label>
                                <div class="resumefieldvalue">
                                    <div class="files-field">
                                        <span id="resumeFileSelector" onclick="return resumeFilesSelection();" class="upload_btn">'.__('Choose Files','js-jobs').'</span>
                                        <div id="selectedFiles" class="selectedFiles" onclick="return resumeFilesSelection();">' . __('No File Selected','js-jobs') . '</div>
                                    </div>
                                    <small class="fileSizeText">' . JSJOBSIncluder::getJSModel('configuration')->getConfigValue('document_file_type') . '&nbsp;('.JSJOBSIncluder::getJSModel('configuration')->getConfigValue('document_file_size').' KB)<br>'.__('Maximum Uploads','js-jobs').' '.JSJOBSIncluder::getJSModel('configuration')->getConfigValue('document_max_files').'</small>
                                    <small><strong>' . __('You may also upload your resume file','js-jobs') . '</strong></small>
                                    <input type="hidden" maxlenght=""/>
                                    <input type="file" id="selectedFiles_required" name="selectedFiles_required[]" value="' . $files_required . '" />
                                    <div id="existingFiles" class="uploadedFiles"></div>
                                </div>
                            </div>';*/
                        break;
                    case 'linkedin':
                        $value = isset($resume->linkedin) ? $resume->linkedin : '';
                        $extraattr = array('maxlength' => '300');
                        $data .= $this->getFieldForPersonalSection($field,$value,'',$extraattr,$themecall);
                    break;
                    case 'twitter':
                        $value = isset($resume->twitter) ? $resume->twitter : '';
                        $extraattr = array('maxlength' => '300');
                        $data .= $this->getFieldForPersonalSection($field,$value,'',$extraattr,$themecall);
                    break;
                    case 'googleplus':
                        $value = isset($resume->googleplus) ? $resume->googleplus : '';
                        $extraattr = array('maxlength' => '300');
                        $data .= $this->getFieldForPersonalSection($field,$value,'',$extraattr,$themecall);
                    break;
                    case 'facebook':
                        $value = isset($resume->facebook) ? $resume->facebook : '';
                        $extraattr = array('maxlength' => '300');
                        $data .= $this->getFieldForPersonalSection($field,$value,'',$extraattr,$themecall);
                    break;
                    case "job_category":
                            $value=isset($resume->job_category)?$resume->job_category:JSJOBSincluder::getJSModel('category')->getDefaultCategoryId();
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('job_category', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(''),$value,'sec_1', __('Select','js-jobs') , array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "jobtype":
                            $value = isset($resume->jobtype) ? $resume->jobtype : JSJOBSincluder::getJSModel('jobtype')->getDefaultJobTypeId();
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('jobtype', JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo(), $value,'sec_1', __('Select','js-jobs') , array('class' => 'inputbox one  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "nationality":
                            $value = isset($resume->nationalityid) ? $resume->nationalityid : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('nationality', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), $value,'sec_1', __('Select','js-jobs') .' '. __('Nationality', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));;
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "driving_license":
                            $value = isset($resume->driving_license) ? $resume->driving_license : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('driving_license', JSJOBSincluder::getJSModel('common')->getYesNo(), $value,'sec_1', __('Driving License', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "license_no":
                            $fieldValue = isset($resume->license_no) ? $resume->license_no : "";
                            $extraattr = array('maxlength' => '100');
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'',$extraattr,$themecall);
                        break;
                    case "license_country":
                            $value = isset($resume->license_country) ? $resume->license_country : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('license_country', JSJOBSincluder::getJSModel('country')->getCountriesForCombo(), $value,'sec_1', __('License Country', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "heighestfinisheducation":
                            $value = isset($resume->heighestfinisheducation) ? $resume->heighestfinisheducation : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('heighestfinisheducation', JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), $value,'sec_1', __('Select','js-jobs') .' '. __('Highest Education', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "total_experience":
                            $value = isset($resume->experienceid) ? $resume->experienceid : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = JSJOBSformfield::resumeSelect('experienceid', JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo(), $value,'sec_1', __('Select','js-jobs') .' '. __('Experience', 'js-jobs'), array('class' => 'inputbox one  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case 'section_moreoptions':
                        $sectionmoreoption = 1;
                        $data .= '<span class="jsjobs-resume-moreoptiontitle">' . __('Show More', 'js-jobs') . '<img src="' . JSJOBS_PLUGIN_URL . 'includes/images/resume/down.png" /></span>
                                    <div class="jsjobs-resume-moreoption">';
                        break;
                    case "date_of_birth":
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume->date_of_birth) ? date_i18n($js_dateformat,jsjobslib::jsjobs_strtotime($resume->date_of_birth)) : '';
                            if($fieldValue == "01/01/1970" OR $fieldValue == "11/01/-0001") $fieldValue = "";
                            if(jsjobslib::jsjobs_strpos($fieldValue , '1970') !== false) $fieldValue = "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,0,array('size' => '10', 'maxlength' => '19','data-validation' => $req),$themecall);
                        break;
                    case "date_start":
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume->date_start) ? date_i18n($js_dateformat,jsjobslib::jsjobs_strtotime($resume->date_start)) : '';
                            if($fieldValue == "01/01/1970" OR $fieldValue == "11/01/-0001") $fieldValue = "";
                            if(jsjobslib::jsjobs_strpos($fieldValue , '1970') !== false) $fieldValue = "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,0,array('size' => '10', 'maxlength' => '19', 'autocomplete' => 'off','data-validation' => $req),$themecall);
                            //$data .= JSJOBSformfield::text('sec_1[date_start]', $date,  array('class' => 'inputbox custom_date ', 'size' => '10', 'maxlength' => '19','data-validation' => $req));
                        break;
                    /*case "searchable":
                    case "iamavailable":
                        if($available_counter == 0){
                            $data .= '<div class="resumefieldswrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "searchable":
                                            $fieldValue = isset($resume) ? $resume->searchable : "";
                                            $data .= $this->getResumeCheckBoxField($field_obj, $fieldValue);
                                            break;
                                        case "iamavailable":
                                            $fieldValue = isset($resume) ? $resume->iamavailable : "";
                                            $data .= $this->getResumeCheckBoxField($field_obj, $fieldValue);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $available_counter = 1;
                        break;*/
                    case "salary":
                            $rangestart = isset($resume->jobsalaryrangestart) ? $resume->jobsalaryrangestart : '';
                            $rangeend = isset($resume->jobsalaryrangeend) ? $resume->jobsalaryrangeend : '';
                            $rangetype = isset($resume->jobsalaryrangetype) ? $resume->jobsalaryrangetype : '';
                            $currencyid = isset($resume->currencyid) ? $resume->currencyid : '';
                            $req = ($field->required ? 'required' : '');
                            if(null != $themecall){
                                $data .= '<div class="js-col-md-12 '.$this->class_prefix.'-field-padding-title">
                                    <div class="js-form-title '.$this->class_prefix.'-bigfont">' . $field->fieldtitle . '</div>
                                </div>';
                                $data .= '<div class="fullwidthwrapper js-form-wrapper">';
                                    $fieldValue = JSJOBSformfield::resumeSelect('jobsalaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), $rangestart,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox salarystart  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('jobsalaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), $rangeend,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox salaryend  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('jobsalaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), $rangetype,'sec_1', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox salarytype  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), $currencyid,'sec_1', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox currency  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                $data .='</div>';
                            }else{
                                $fieldValue = JSJOBSformfield::resumeSelect('jobsalaryrangestart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), $rangestart,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox salarystart', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('jobsalaryrangeend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), $rangeend,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox salaryend', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('jobsalaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), $rangetype,'sec_1', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox salarytype', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('currencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), $currencyid,'sec_1', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox currency', 'data-validation' => $req));
                                $data .= $this->getResumeSelectField($field, $fieldValue);
                            }
                        break;
                    case "desired_salary":
                            $rangestart = isset($resume->desiredsalarystart) ? $resume->desiredsalarystart : '';
                            $rangeend = isset($resume->desiredsalaryend) ? $resume->desiredsalaryend : '';
                            $rangetype = isset($resume->djobsalaryrangetype) ? $resume->djobsalaryrangetype : '';
                            $currencyid = isset($resume->dcurrencyid) ? $resume->dcurrencyid : '';
                            $req = ($field->required ? 'required' : '');
                            if(null != $themecall){
                                $data .= '<div class="js-col-md-12 '.$this->class_prefix.'-field-padding-title">
                                    <div class="js-form-title '.$this->class_prefix.'-bigfont">' . $field->fieldtitle . '</div>
                                </div>';
                                $data .= '<div class="fullwidthwrapper js-form-wrapper">';
                                    $fieldValue = JSJOBSformfield::resumeSelect('desiredsalarystart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), $rangestart,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox salarystart  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('desiredsalaryend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), $rangeend,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox salaryend  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('djobsalaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), $rangetype,'sec_1', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox salarytype  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                    $fieldValue = JSJOBSformfield::resumeSelect('dcurrencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), $currencyid,'sec_1', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox currency  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                    $data .= $this->getResumeSelectField('', $fieldValue,4,$themecall);
                                $data .='</div>';
                            }else{
                                $fieldValue = JSJOBSformfield::resumeSelect('desiredsalarystart', JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), $rangestart,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('Start', 'js-jobs'), array('class' => 'inputbox salarystart  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('desiredsalaryend', JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), $rangeend,'sec_1', __('Select','js-jobs') .' '. __('Salary Range','js-jobs') .' '. __('End', 'js-jobs'), array('class' => 'inputbox salaryend  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('djobsalaryrangetype', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), $rangetype,'sec_1', __('Select','js-jobs') .' '. __('Salary Range Type', 'js-jobs'), array('class' => 'inputbox salarytype  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                $fieldValue .= JSJOBSformfield::resumeSelect('dcurrencyid', JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo(), $currencyid,'sec_1', __('Select','js-jobs') .' '. __('Currency', 'js-jobs'), array('class' => 'inputbox currency  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                                $data .= $this->getResumeSelectField($field, $fieldValue);
                            }
                        break;
                    case "video":
                            $videotypeoptions = array((object) array('id' => 1, 'text' => __('Youtube video url', 'js-jobs')),(object) array('id' => 2, 'text' => __('Embeded HTML', 'js-jobs')));
                            $value = isset($resume->videotype) ? $resume->videotype : 1;
                            $fieldValue = JSJOBSformfield::resumeSelect('videotype', $videotypeoptions, $value,'sec_1', __('Video Type', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field'));
                            $data .= $this->getResumeSelectField($field,$fieldValue,'',$themecall);

                            $fieldValue = isset($resume->video) ? jsjobslib::jsjobs_htmlspecialchars($resume->video) : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'','',$themecall);
                        break;
                    case "keywords":
                            $fieldValue = isset($resume->keywords) ? $resume->keywords : "";
                            $extraattr = array('maxlength' => '255');
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'',$extraattr,$themecall);
                        break;
                    case 'searchable':
                        $value = isset($resume->searchable) ? $resume->searchable : 1;
                        $req = ''; // for checking field is required or not
                        $req = ($field->required ? 'required' : '');
                        $fieldValue = JSJOBSformfield::resumeSelect('searchable', JSJOBSincluder::getJSModel('common')->getYesNo(), $value,'sec_1', __('Searchable', 'js-jobs'), array('class' => 'inputbox  form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                        $data .= $this->getResumeSelectField($field,$fieldValue,'',$themecall);
                    break;
                    case 'iamavailable':
                        $value = isset($resume->iamavailable) ? $resume->iamavailable : '';
                        $req = ''; // for checking field is required or not
                        $req = ($field->required ? 'required' : '');
                        $fieldValue = JSJOBSformfield::resumeSelect('iamavailable', JSJOBSincluder::getJSModel('common')->getYesNo(), $value,'sec_1', __('Select','js-jobs') .' '. __('availability', 'js-jobs'), array('class' => 'inputbox form-control '.$this->class_prefix.'-select-field', 'data-validation' => $req));
                        $data .= $this->getResumeSelectField($field,$fieldValue,'',$themecall);
                        break;
                    default:
                        $data .= $this->getResumeFormUserField($field, $resume , 1 ,  0 , '',$themecall);
                    break;
                }
            }
        if ($sectionmoreoption == 1) {
            $data .= '</div>'; // closing div for the more option
        }
        $data .= '</div>'; // to handle background color of scetions
        return $data;
    }

    function printResume($themecall=null) {

        //check wheather to show resume form or resumeformview
        $resumeformview = 1; // for add case
        if (isset(jsjobs::$_data['resumeid']) && is_numeric(jsjobs::$_data['resumeid'])) {
            $resumeformview = 0; // for edit case
            $resumeid=jsjobs::$_data['resumeid'];
        }
        $html = '<div id="resume-wrapper">';
        $form_class="js-jobs-form";
        if(1 == $themecall){
            $html='<div class="jsjb-jm-form-wrap">';
            $form_class="jsjb-jm-form";
        }elseif(2 == $themecall){
            $html='<div class="jsjb-jh-form-wrap">';
            $form_class="jsjb-jh-form";
        }
        $html .= '<form class="'.$form_class.'" id="resumeform" method="post" enctype="multipart/form-data" action="'.jsjobs::makeUrl(array('jsjobsme'=>'resume', 'task'=>'saveresume')).'" >'; // main form;
        //$personal_data = JSJOBSIncluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'personal');
        if (!isset(jsjobs::$_data[0]['personal_section']->uid)) {
            $isowner = 1; // user come to add new resume
        } else {
            $isowner = (JSJOBSincluder::getObjectClass('user')->uid() == jsjobs::$_data[0]['personal_section']->uid) ? 1 : 0;
        }
        foreach ($this->resumefields AS $field) {
            if($field->published == 1){
                switch ($field->field){
                    case 'section_personal':
                        $title = 'Personal Information';
                        $html .= $this->getSectionTitle('personal', $title , 1,$themecall);
                        $html .= $this->makePersonalSectionFields($themecall);
                    break;
                    case 'section_address':
                        $title = 'Address';
                        $html .= $this->getSectionTitle('address', $title, 2,$themecall);
                        //$section_address = JSJOBSIncluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'address');
                        $html .= $this->makeAddressSectionFields($themecall);
                    break;
                    case 'section_education':
                        $title = 'Education';
                        $html .= $this->getSectionTitle('education', $title, 3,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'institute');
                        $html .= $this->makeInstituteSectionFields($themecall);
                    break;
                    case 'section_employer':
                        $title = 'Employer';
                        $html .= $this->getSectionTitle('employer', $title, 4,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'employer');
                        $html .= $this->makeEmployerSectionFields($themecall);
                    break;
                    case 'section_skills':
                        $title = 'Skills';
                        $html .= $this->getSectionTitle('skills', $title, 5,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'skills');
                        $html .= $this->makeSkillsSectionFields($themecall);

                    break;
                    case 'section_resume':
                        $title = 'Resume editor';
                        $html .= $this->getSectionTitle('editor', $title, 6,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'editor');
                        $html .= $this->makeResumeSectionFields($themecall);
                    break;
                    case 'section_reference':
                        $title = 'Reference';
                        $html .= $this->getSectionTitle('reference', $title, 7,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'reference');
                        $html .= $this->makeReferenceSectionFields($themecall);
                    break;
                    case 'section_language';
                        $title = 'Language';
                        $html .= $this->getSectionTitle('language', $title, 8,$themecall);
                        //JSJOBSincluder::getJSModel('resume')->getResumeDataBySection($resumeid , 'language');
                        $html .= $this->makeLanguageSectionFields($themecall);

                    break;
                }
            }
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $html .= wp_enqueue_script('jsjobs-repaptcha-scripti', 'https://www.google.com/recaptcha/api.js');
        if(current_user_can('manage_options')){
            $one = '';
            $two = '';
            $three = '';
            if(isset(jsjobs::$_data[0]['personal_section']->status)){
                if(jsjobs::$_data[0]['personal_section']->status == 1){
                    $one = ' selected ';
                }elseif(jsjobs::$_data[0]['personal_section']->status == 0){
                    $two = ' selected ';
                }else{
                    $three = ' selected ';
                }
            }
            $status = isset(jsjobs::$_data[0]['personal_section']->status) ? jsjobs::$_data[0]['personal_section']->status : '';
            $html .= '
            <div class="section_wrapper">
                <div class="resume-row-wrapper form resumefieldswrapper">
                    <label id="total_experiencemsg" class="row-title" for="status">'.__('Status','js-jobs').'</label>
                    <div class="row-value resumefieldvalue">
                    <select id="status" name="sec_1[status]">
                        <option ';
                        $selected = ($status == 1) ? 'selected="selected"' : '';
            $html .=    $selected.' value="1" '.$one.'>'.__('Approved','js-jobs').'</option>
                        <option ';
                        $selected = ($status == 0) ? 'selected="selected"' : '';
            $html .=    $selected.' value="0" '.$two.'>'.__('Pending','js-jobs').'</option>
                        <option ';
                        $selected = ($status == -1) ? 'selected="selected"' : '';
            $html .=    $selected.' value="-1" '.$three.'>'.__('Reject','js-jobs').'</option>
                    </select></div>
                </div>
            </div>
                ';
        }
        $html .= '<div class="resume-section-button">';
        $isvisitor=false;
        if(isset($_COOKIE['jsjobs_apply_visitor']) && !is_user_logged_in()) $isvisitor=true;
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
        if (!is_user_logged_in() && $config_array['resume_captcha'] == 1) {
            $html .= '<div class="resume-row-wrapper form js-col-md-12 js-form-wrapper js-form-resume-captcha-wrp">
                        <div class="row-title js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont">' . __('Captcha', 'js-jobs') . '</div>
                        <div class="row-value js-col-md-12 js-form-value">';
            if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                $html .= '<div class="g-recaptcha" data-sitekey="'.$config_array["recaptcha_publickey"].'"></div>';

            } else { // own captcha
                $captcha = new JSJOBScaptcha;
                $html .= $captcha->getCaptchaForForm();
            }
            $html .= '  </div>
                    </div>';
        }

        $created = isset(jsjobs::$_data[0]['personal_section']->created) ? jsjobs::$_data[0]['personal_section']->created : date('Y-m-d H:i:s');
        $html .= '<div class="js-col-md-12 js-form-wrapper">
                <input type="hidden" id="created" name="sec_1[created]" value="'.$created.'">';
            $html .=JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]['personal_section']->id) ? jsjobs::$_data[0]['personal_section']->id : '' );
            if(isset(jsjobs::$_data[0]['personal_section']->uid) && ""!=jsjobs::$_data[0]['personal_section']->uid){
                $uid=jsjobs::$_data[0]['personal_section']->uid;
            } else{
                $uid=JSJOBSincluder::getObjectClass('user')->uid();
            }
            //$html .= '<input type="hidden" id="uid" name="sec_1[uid]" value="'.$uid.'">';
            $html .=JSJOBSformfield::hidden('uid', $uid);
            $html .=JSJOBSformfield::hidden('action', 'resume_saveresume');
            $html .=JSJOBSformfield::hidden('jsjobspageid', get_the_ID());
            $html .=JSJOBSformfield::hidden('creditid', '');
            $html .=JSJOBSformfield::hidden('form_request', 'jsjobs');
            $html .=JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-resume'));
            $html .='<div class="js-col-md-12 bottombutton js-form" id="save-button">';
            $guestallowed = 0;
            if (JSJOBSincluder::getObjectClass('user')->isguest()) {
                $guestallowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_add_resume');
            }
            if(!is_admin()){ // site
                $cancel_link = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume");
            }elseif(is_admin()){
                $html .=JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0);
                $cancel_link = wp_nonce_url(admin_url("admin.php?page=jsjobs_resume"),"resume");
            }
            $btn_cancel=false;
            if(!$isvisitor && is_user_logged_in() ){
                $btn_cancel=true;
            }
            if($btn_cancel==true)  {
                $html .= '<div class="js-col-md-6 cacel-button-half-dv">';
            }

            if ($isvisitor &&  !is_admin()) {
                    $html .= '<input class="'.$this->class_prefix.'-btn-primary" type="button" onclick="submitresume();" value="' . __('Apply Now', 'js-jobs') . '"/>';
            } else {
                    $html .= '<input class="'.$this->class_prefix.'-btn-primary" type="button" onclick="submitresume();" value="' . __('Save', 'js-jobs') . '"/>';
            }
            if($btn_cancel==true)  $html .= '</div>';
            if(!$isvisitor && is_user_logged_in() ){
                if($btn_cancel==true)  $html .= '<div class="js-col-md-6">';
                    $html .= '<a class="resume_submits cancel '.$this->class_prefix.'-btn-primary" href="'.$cancel_link.'">' . __('Cancel', 'js-jobs') . '</a>';
                if($btn_cancel==true)  {
                        $html .= '</div>';
                }
            }
            //$html .= '<input type="button" onclick="cancelresume();" value="' . __('Cancel', 'js-jobs') . '"/></div>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';// section wrapper end;
        echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
        // if (isset(jsjobs::$_data[0]) && isset(jsjobs::$_data[0]['personal_section'])) {
        //     $viewtags = jsjobs::$_data[0]['personal_section']->viewtags;
        // } else {
        //     $viewtags = '';
        // }
        // $viewtags = $this->makeanchorfortags($viewtags);
    }

    function getRowForView($text, $value, &$i) {
        $html = '';
        if ($i == 0 || $i % 2 == 0) {
            $html .= '<div class="resume-row-wrapper-wrapper">';
        }
        $html .= '<div class="resume-row-wrapper">
                    <div class="row-title">' . $text . ':</div>
                    <div class="row-value">' . __($value,'js-jobs') . '</div>
                </div>';
        $i++;
        if ($i % 2 == 0) {
            $html .= '</div>';
        }
        return $html;
    }

    function getRowForForm($text, $value,$themecall=null) {
        if(null != $themecall){
            $html = '<div class="js-col-md-12 js-form-wrapper">
                <div class="js-col-md-12 js-form-title '.$this->class_prefix.'-bigfont">' . $text . '</div>
                <div class="js-col-md-12 js-form-value">' . $value . '</div>
            </div>';
        }else{
            $html = '<div class="resume-row-wrapper form">
                <div class="row-title">' . $text . ':</div>
                <div class="row-value">' . $value . '</div>
            </div>';

        }
        return $html;
    }

    function getHeadingRowForView($value) {
        $html = '<div class="resume-heading-row">' . $value . '</div>';
        return $html;
    }

    // function makeanchorfortags($tags) {
    //     if (empty($tags)) {
    //         $anchor = '<div id="jsresume-tags-wrapper"></div>';
    //         return $anchor;
    //     }
    //     $array = jsjobslib::jsjobs_explode(',', $tags);
    //     $anchor = '<div id="jsresume-tags-wrapper">';
    //     $anchor .= '<span class="jsresume-tags-title">' . __('Tags', 'js-jobs') . '</span>';
    //     $anchor .= '<div class="tags-wrapper-border">';
    //     for ($i = 0; $i < count($array); $i++) {
    //         $with_spaces = jsjobs::tagfillin($array[$i]);
    //         $anchor .= '<a class="jsjob_tags_a" href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'tags'=>$with_spaces)) . '">' . __($array[$i], 'js-jobs') . '</a>';
    //     }
    //     $anchor .= '</div>';
    //     $anchor .= '</div>';
    //     return $anchor;
    // }

}

?>
