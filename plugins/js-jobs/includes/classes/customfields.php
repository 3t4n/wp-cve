<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScustomfields {
    public $class_prefix = '';

    function __construct(){
        if(jsjobs::$theme_chk == 1){
            $this->class_prefix = 'jsjb-jm';
        }elseif(jsjobs::$theme_chk == 2){
            $this->class_prefix = 'jsjb-jh';
        }
    }

    function formCustomFieldsResume($field , $obj_id, $obj_params ,$resumeform=null,  $section = null, $sectionid = null, $ishidden = null,$themecall=null){
        //had to do this so that there are minimum changes in resume code
        $field = $this->userFieldData($field->field, 5, $section);
        //$field = $this->userFieldData($field, 5, $section);
        if (empty($field)) {
            return '';
        }
        $themebfclass = " ".$this->class_prefix."-bigfont ";
        if(null != $themecall){
            $div1 = 'js-col-md-12 js-form-wrapper';
            $div2 = ' js-col-md-12 js-form-title '.$themebfclass;
            $div3 = 'js-col-md-12 js-form-value';
        }else{
            $div1 = 'resume-row-wrapper form';
            $div2 = 'row-title';
            $div3 = 'row-value';

        }
        $cssclass = "";
        $required = $field->required;
        $html = '<div class="' . $div1 . '">
               <div class="' . $div2 . '">';
        if ($required == 1) {
            $html .= __($field->fieldtitle,'js-jobs') . '<font color="red"> *</font>';
            // if ($field->userfieldtype == 'email'){
            //     //$cssclass = "required validate-email";
            //     if($section AND $section == null){ // too handle bug related to sub section email field
            //         $cssclass = "required email";
            //     }
            // }else{
                $cssclass = "required";
            // }
        }else {
            $html .= __($field->fieldtitle,'js-jobs');
            // if ($field->userfieldtype == 'email'){
            //     if($section AND $section == null){ // too handle bug related to sub section email field
            //         //$cssclass = "validate-email";
            //         $cssclass = "required email";
            //     }
            // }else{
                $cssclass = "";
            // }
        }
        $html .= ' </div><div class="' . $div3 . '">';

        $resumeTitle = __($field->fieldtitle,'js-jobs');

        $size = '';
        $maxlength = '';
        if(isset($field->size) && 0!=$field->size){
            $size = $field->size;
        }
        if(isset($field->maxlength) && 0!=$field->maxlength){
            $maxlength = $field->maxlength;
        }

        $fvalue = "";
        $value = "";
        $userdataid = "";
        $value = $obj_params;

        if($value){ // data has been stored
            $userfielddataarray = json_decode($value);
            $valuearray = json_decode($value,true);
        }else{
            $valuearray = array();
        }
        if ($valuearray == NULL) {
            $valuearray = array();
        }
        if(array_key_exists($field->field, $valuearray)){
            $value = $valuearray[$field->field];
        }else{
            $value = '';
        }
        $user_field = '';
        if($themecall != null){
            $theme_string = ', '. $themecall;
        }else{
            $theme_string = '';
        }
        switch ($field->userfieldtype) {
            case 'text':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $extraattr = array('class' => "inputbox one $cssclass $themeclass", 'data-validation' => $cssclass, 'size' => $size, 'maxlength' => $maxlength);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume
                $user_field .= $this->textResume($field->field, $value, $extraattr, $section , $sectionid);
            break;
            case 'email':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $extraattr = array('class' => "inputbox one $cssclass $themeclass", 'data-validation' => $cssclass, 'size' => $size, 'maxlength' => $maxlength);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume
                $user_field .= $this->emailResume($field->field, $value, $extraattr, $section , $sectionid);
            break;
            case 'date':
                    if(jsjobs::$theme_chk == 1){
                        $themeclass = getJobManagerThemeClass('text');
                    }elseif(jsjobs::$theme_chk == 2){
                        $themeclass = getJobHubThemeClass('text');
                    }else{
                        $themeclass = '';
                    }
                    $req=($field->required==1)?"required":"";
                    $extraattr = array('class' => 'inputbox custom_date cal_userfield '.$themeclass.' '.$cssclass, 'size' => '10', 'maxlength' => '19', 'autocomplete' => 'off','data-validation'=>$req);
                    // handleformresume
                    if($section AND $section != 1){
                        if($ishidden){
                            if ($required == 1) {
                                $extraattr['data-validation'] = '';
                                $extraattr['data-myrequired'] = $cssclass;
                                $extraattr['class'] = "inputbox custom_date cal_userfield ".$themeclass." ".$cssclass;
                            }
                        }
                    }
                    //END handleformresume
                    if(jsjobslib::jsjobs_strpos($value , '1970') !== false){
                        $value = "";
                    }
                    $user_field .= $this->dateResume($field->field, $value, $extraattr, $section , $sectionid);
            break;
            case 'textarea':
                $rows = '';
                $cols = '';
                if(isset($field->rows)){
                    $rows = $field->rows;
                }
                if(isset($field->cols)){
                    $cols = $field->cols;
                }
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('textarea');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('textarea');
                }else{
                    $themeclass = '';
                }

                $extraattr = array('class' => "inputbox one $cssclass $themeclass", 'data-validation' => $cssclass, 'rows' => $rows, 'cols' => $cols);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->textareaResume($field->field, $value, $extraattr , $section , $sectionid);
            break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    $i = 0;
                    $valuearray = jsjobslib::jsjobs_explode(', ',$value);
                    $name = $field->field;
                    if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
                        $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
                    }else{
                        $id = $name;
                    }
                    $data_required = '';
                    if($section){
                        if($section != 1){
                            if($ishidden){
                                if($required == 1){
                                    $data_required = 'data-myrequired="required"';
                                    $cssclass = '';
                                }
                            }
                            $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                            $id .=$sectionid;
                        }else{
                            $name = 'sec_'.$section.'['.$name.']';
                        }
                    }

                    $jsFunction = '';
                    if ($required == 1) {
                        $jsFunction = "deRequireUfCheckbox('" . $field->field . "');";
                    }
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $user_field .= '<span class="uf_checkbox_wrp">';
                        $user_field .= '<input type="checkbox" ' . $check . ' '.$data_required.' class="'. $field->field .' radiobutton uf_of_type_ckbox '.$cssclass.'" value="' . $option . '" id="' . $id . '_' . $i . '" name="' . $name . '[]" data-validation="'.$cssclass.'" onclick = "' . $jsFunction . '" ckbox-group-name="' . $field->field . '">';
                        $user_field .= '<label class="cf_chkbox" for="' . $id . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $user_field .= '</span>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => __($field->fieldtitle,'js-jobs'));
                    $extraattr = array('class' => "radiobutton $cssclass");
                    // handleformresume
                    if($section AND $section != 1){
                        if($ishidden){
                            if ($required == 1) {
                                $extraattr['data-validation'] = '';
                                $extraattr['data-myrequired'] = $cssclass;
                                $extraattr['class'] = "radiobutton";
                            }
                        }
                    }
                    //END handleformresume
                    $user_field .= $this->checkboxResume($field->field, $comboOptions, $value, array('class' => "radiobutton $cssclass") , $section , $sectionid);
                }
            break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = __($obj_option[$i],'js-jobs');
                    }
                }
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantFieldResume('" . $field->field . "','" . $field->depandant_field . "',2,'".$section."','".$sectionid."'". $theme_string.");";
                }
                $extraattr = array('class' => "cf_radio radiobutton $cssclass" , 'data-validation' => $cssclass, 'onclick' => $jsFunction);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "cf_radio radiobutton";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->radiobuttonResume($field->field, $comboOptions, $value, $extraattr , $section , $sectionid);
            break;
            case 'combo':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => __($opt,'js-jobs'));
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantFieldResume('" . $field->field . "','" . $field->depandant_field . "',1,'".$section."','".$sectionid."'". $theme_string.");";
                }
                //end
                $extraattr = array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => "inputbox one $cssclass $themeclass");
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->selectResume($field->field, $comboOptions, $value, __('Select','js-jobs') . ' ' . $field->fieldtitle, $extraattr , null,$section , $sectionid);
            break;
            case 'depandant_field':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => __($opt,'js-jobs'));
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantFieldResume('" . $field->field . "','" . $field->depandant_field . "',1,'".$section."','".$sectionid."'". $theme_string.");";
                }
                //end
                $extraattr = array('data-validation' => $cssclass, 'class' => "inputbox one $cssclass $themeclass");
                if(""!=$jsFunction){
                    $extraattr['onchange']=$jsFunction;
                }
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume
                $user_field .= $this->selectResume($field->field, $comboOptions, $value, __('Select','js-jobs') . ' ' . $field->fieldtitle, $extraattr , null, $section , $sectionid);
            break;
            case 'multiple':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => __($opt,'js-jobs'));
                    }
                }
                $name = $field->field;
                $name .= '[]';
                $valuearray = jsjobslib::jsjobs_explode(', ', $value);
                $ismultiple = 1;
                $extraattr = array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => "inputbox one $cssclass $themeclass");
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-validation'] = '';
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume
                $user_field .= $this->selectResume($name, $comboOptions, $valuearray, '', $extraattr , null ,$section , $sectionid , $ismultiple);
            break;
            case 'file':
                if($value != null){ // since file already uploaded so we reglect the required
                    $cssclass = jsjobslib::jsjobs_str_replace('required', '', $cssclass);
                }

                $name = $field->field;
                $data_required = '';
                if($section){
                    if($section != 1){
                        if($ishidden){
                            if($required == 1){
                                $data_required = 'data-myrequired="required"';
                                $cssclass = '';
                            }
                        }
                        $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                    }else{
                        $name = 'sec_'.$section.'['.$name.']';
                    }
                }

                $user_field .= '<input type="file" class="'.$cssclass.' cf_uploadfile" '.$data_required.' name="'.$name.'" id="'.$field->field.'"/>';
                if(JFactory::getApplication()->isAdmin()){
                    $this->_config = JSModel::getJSModel('configuration')->getConfig();
                }else{
                    $this->_config = JSModel::getJSModel('configurations')->getConfig('');
                }
                $fileext  = '';
                foreach ($this->_config as $conf) {
                    if ($conf->configname == 'image_file_type'){
                        if($fileext)
                            $fileext .= ',';
                        $fileext .= $conf->configvalue;
                    }
                    if ($conf->configname == 'document_file_type'){
                        if($fileext)
                            $fileext .= ',';
                        $fileext .= $conf->configvalue;
                    }
                    if ($conf->configname == 'document_file_size')
                        $maxFileSize = $conf->configvalue;
                }

                $fileext = jsjobslib::jsjobs_explode(',', $fileext);
                $fileext = array_unique($fileext);
                $fileext = implode(',', $fileext);
                $user_field .= '<div id="js_cust_file_ext">'.__('Files','js-jobs').' ('.$fileext.')<br> '.__('Maximum Size','js-jobs').' '.$maxFileSize.'(kb)</div>';
                if($value != null){
                    $user_field .= $this->hidden($field->field.'_1', 0 , array(), $section , $sectionid);
                    $user_field .= $this->hidden($field->field.'_2',$value, array(), $section , $sectionid);
                    $jsFunction = "deleteCutomUploadedFile('".$field->field."','".$field->required."')";
                    $value = jsjobslib::jsjobs_explode('_', $value , 2);
                    $value = $value[1];
                    $user_field .='<span class='.$field->field.'_1>'.$value.'( ';
                    $user_field .= "<a href='javascript:void(0)' onClick=".$jsFunction." >". __('Delete','js-jobs')."</a>";
                    $user_field .= ' )</span>';
                }
            break;
        }
        $html .= $user_field;
        $html .= '</div></div>';
        if ($resumeform === 1) {
            return array('title' => $resumeTitle , 'value' => $user_field);
        }elseif($resumeform == 'admin'){
            return array('title' => $resumeTitle , 'value' => $user_field , 'lable' => $field->field);
        }elseif($resumeform == 'f_company'){
            return array('title' => $resumeTitle , 'value' => $user_field , 'lable' => $field->field);
        }else {
            return $html;
        }

    }

    static function selectResume($name, $list, $defaultvalue, $title = '', $extraattr = array() , $disabled = '',  $resume_section_id = null , $sectionid = null , $ismultiple = false) {
        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                if($ismultiple){
                    $name = jsjobslib::jsjobs_str_replace('[]', '', $name);
                    $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.'][]';
                    $id .=$sectionid;
                }else{
                    $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                    $id .=$sectionid;
                }
            }else{
                if($ismultiple){
                    $name = jsjobslib::jsjobs_str_replace('[]', '', $name);
                    $name = 'sec_'.$resume_section_id.'['.$name.'][]';
                }else{
                    $name = 'sec_'.$resume_section_id.'['.$name.']';
                }
            }
        }
        //END handleformresume

        $selectfield = '<select name="' . $name . '" id="' . $id . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' . $val . '"';
            }
        if($disabled)
            $selectfield .= ' disabled>';
        else
            $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if (!empty($list))
            foreach ($list AS $record) {
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option selected="selected" value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
                else
                    $selectfield .= '<option value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }



    static function radiobuttonResume($name, $list, $defaultvalue, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        $radiobutton = '';
        $count = 1;
        $match = false;
        $firstvalue = '';
        foreach($list AS $value => $label){
            if($firstvalue == '')
                $firstvalue = $value;
            if($defaultvalue == $value){
                $match = true;
                break;
            }
        }
        if($match == false){
            //$defaultvalue = $firstvalue;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        foreach ($list AS $value => $label) {
            $radiobutton .= '<span class="uf_radiobtn_wrp">';
            $radiobutton .= '<input type="radio" name="' . $name . '" id="' . $id . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value){
                $radiobutton .= ' checked="checked"';
            }
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $radiobutton .= ' ' . $key . '="' . $val . '"';
                }
            $radiobutton .= '/><label id="for' . $id . '" class="cf_radiobtn" for="' . $id . $count . '">' . $label . '</label>';
            $radiobutton .= '</span>';
            $count++;
        }
        return $radiobutton;
    }



    static function checkboxResume($name, $list, $defaultvalue, $extraattr = array() , $resume_section_id = null , $sectionid = null) {

        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        $checkbox = '';
        $count = 1;

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.'][]';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.'][]';
            }
        }
        //END handleformresume

        foreach ($list AS $value => $label) {
            $checkbox .= '<input type="checkbox" name="' . $name . '" id="' . $id . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value)
                $checkbox .= ' checked="checked"';
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $checkbox .= ' ' . $key . '="' . $val . '"';
                }
            $checkbox .= '/><label id="for' . $id . '" for="' . $id . $count . '">' . $label . '</label>';
            $count++;
        }
        return $checkbox;
    }


    static function textareaResume($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
            if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
                $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
            }else{
                $id = $name;
            }
        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textarea = '<textarea name="' . $name . '" id="' . $id . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textarea .= ' ' . $key . '="' . $val . '"';
        $textarea .= ' >' . $value . '</textarea>';
        return $textarea;
    }


    static function dateResume($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textfield = '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    static function textResume($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {


        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textfield = '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    static function emailResume($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        if (jsjobslib::jsjobs_strpos($name, '[]') !== false) {
            $id = jsjobslib::jsjobs_str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                $id .=$sectionid;
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textfield = '<input type="email" name="' . $name . '" id="' . $id . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    function formCustomFields($field,$resumeform = null, $section = null, $refid = null,$themecall=null) {
        //patch to saolve notices on resume form but it may causse problems like showing disabled fiedls
        if ($resumeform != 1) {
            if ($field->isuserfield != 1) {
                return;
            }
        }

        $cssclass = "";
        $html = '';
        $themebfclass = " ".$this->class_prefix."-bigfont ";

        if ($resumeform == 1) {
            //had to do this so that there are minimum changes in resume code 
            $field = $this->userFieldData($field, 5, $section);
            if (empty($field)) {
                return;
            }
            //end
            $div1 = 'resume-row-wrapper form';
            $div2 = 'row-title';
            $div3 = 'row-value';
        } else {
            $div1 = (is_admin()) ? 'js-field-wrapper js-row no-margin' : 'js-col-md-12 js-form-wrapper';
            $div2 = (is_admin()) ? 'js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding' : ' js-col-md-12 js-form-title '.$themebfclass;
            $div3 = (is_admin()) ? 'js-field-obj js-col-lg-9 js-col-md-9 no-padding' : 'js-col-md-12 js-form-value';
        }
        $required = $field->required;
        $html = '<div class="' . $div1 . '">
               <div class="' . $div2 . '">';
        if ($required == 1) {
	    $html .= __($field->fieldtitle,'js-jobs') . '<font color="red">*</font>';
            // if ($field->userfieldtype == 'email')
            //     $cssclass = "email";
            // else
                $cssclass = "required";
        }else {
            $html .= __($field->fieldtitle,'js-jobs');
            // if ($field->userfieldtype == 'email')
            //     $cssclass = "email";
            // else
                $cssclass = "";
        }
        $html .= ' </div><div class="' . $div3 . '">';
        $readonly = $field->readonly ? "'readonly => 'readonly'" : "";
        $size = '';
        $maxlength = '';
        if(isset($field->size) && 0!=$field->size){
            $size = $field->size;
        }
        if(isset($field->maxlength) && 0!=$field->maxlength){
            $maxlength = $field->maxlength;
        }
        $fvalue = "";
        $value = "";
        $userdataid = "";
        if ($resumeform == 1) {
            if($section == 1 || $section == 5 || $section == 6){ // personal section
                if(isset(jsjobs::$_data[0]['personal_section'])){
                    $value = jsjobs::$_data[0]['personal_section']->params;
                }
            }elseif($section == 2){
                if(isset(jsjobs::$_data[0]['address_section'])){
                    $value = jsjobs::$_data[0]['address_section']->params;
                }
            }elseif($section == 3){
                if(isset(jsjobs::$_data[0]['institute_section'])){
                    $value = jsjobs::$_data[0]['institute_section']->params;
                }
            }elseif($section == 4){
                if(isset(jsjobs::$_data[0]['employer_section'])){
                    $value = jsjobs::$_data[0]['employer_section']->params;
                }
            }elseif($section == 7){
                if(isset(jsjobs::$_data[0]['reference_section'])){
                    $value = jsjobs::$_data[0]['reference_section']->params;
                }
            }elseif($section == 8){
                if(isset(jsjobs::$_data[0]['language_section'])){
                    $value = jsjobs::$_data[0]['language_section']->params;
                }
            }
            if($value){ // data has been stored
                $userfielddataarray = json_decode($value);
                $valuearray = json_decode($value,true);
            }else{
                $valuearray = array();
            }
            if(array_key_exists($field->field, $valuearray)){
                $value = $valuearray[$field->field];
            }else{
                $value = '';
            }
        } elseif (isset(jsjobs::$_data[0]->id)) {
            $userfielddataarray = json_decode(jsjobslib::jsjobs_stripslashes(jsjobs::$_data[0]->params));
            $uffield = $field->field;
            if (isset($userfielddataarray->$uffield) || !empty($userfielddataarray->$uffield)) {
                $value = $userfielddataarray->$uffield;
            } else {
                $value = '';
            }
        }
        if($themecall != null){
            $theme_string = ', "'. $themecall .'"';
        }else{
            $theme_string = '';
        }
        switch ($field->userfieldtype) {
            case 'text':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::text($field->field, $value, array('class' => ' inputbox one '. $themeclass, 'data-validation' => $cssclass, 'size' => $size, 'maxlength' => $maxlength, $readonly));
                break;
            case 'email':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::email($field->field, $value, array('class' => ' inputbox one '. $themeclass, 'data-validation' => $cssclass, 'size' => $size, 'maxlength' => $maxlength, $readonly));
                break;
            case 'date':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                if(jsjobslib::jsjobs_strpos($value , '1970') !== false){
                    $value = "";
                }
                $html .= JSJOBSformfield::text($field->field, $value, array('autocomplete' => 'off', 'class' => 'custom_date one '. $themeclass, 'data-validation' => $cssclass));
                break;
            case 'textarea':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('textarea');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('textarea');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::textarea($field->field, $value, array('class' => ' inputbox one '. $themeclass, 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    $i = 0;
                    $valuearray = jsjobslib::jsjobs_explode(', ',$value);
                    $jsFunction = '';
                    if ($required == 1) {
                        $jsFunction = "deRequireUfCheckbox('" . $field->field . "');";
                    }
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<input type="checkbox" ' . $check . ' class="uf_of_type_ckbox radiobutton ' . $field->field . '" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]" data-validation="'.$cssclass.'" onclick = "' . $jsFunction . '" ckbox-group-name="' . $field->field . '">';
                        $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => $field->fieldtitle);
                    $html .= JSJOBSformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                    }
                }
                $jsFunction = '';
                $dependentclass = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2,'',''". $theme_string.");";
                    $dependentclass = 'dependent';
                }
                $html .= JSJOBSformfield::radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass , 'class' =>  'js-form-radiobtn'.$dependentclass, 'onclick' => $jsFunction));

                break;
            case 'combo':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1,'',''". $theme_string. ");";
                }
                //end
                $html .= JSJOBSformfield::select($field->field, $comboOptions, $value, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox one '. $themeclass));
                break;
            case 'depandant_field':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        if(!empty($obj_option) && is_array($obj_option))
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "','1','',''". $theme_string.");";
                }
                //end
                $html .= JSJOBSformfield::select($field->field, $comboOptions, $value, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox one '. $themeclass));
                break;
            case 'multiple':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(jsjobslib::jsjobs_stripslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $valuearray = jsjobslib::jsjobs_explode(', ', $value);
                $html .= JSJOBSformfield::select($array, $comboOptions, $valuearray, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => 'inputbox one'. $themeclass));
                break;
        }
        $html .= '</div></div>';
        if ($resumeform == 1) {
            return $html;
        } else {
            echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
        }
    }

    function formCustomFieldsForSearch($field, &$i, $resumeform = null, $subrefid = null,$themecall=null,$themrefine=null) {
        if ($field->isuserfield != 1)
            return false;
        $cssclass = "";
        $html = '';
        $i++;
        if($resumeform != 3){// to handle top search case for job and resume listing.

            $themebfclass = " ".$this->class_prefix."-bigfont ";

            $themenopadmarclass = " ".$this->class_prefix."-nopad-nomar ";

            $required = $field->required;
            $div1 = 'js-col-md-12 js-form-wrapper '.$themenopadmarclass;
            $div2 = 'js-col-md-12 js-form-title '.$themebfclass;
            $div3 = 'js-col-md-12 js-form-value';

            $html = '<div class="' . $div1 . '" title="'. __(jsjobslib::jsjobs_htmlspecialchars($field->fieldtitle, ENT_QUOTES, 'UTF-8', false),'js-jobs') .'" >
                   <div class="' . $div2 . '">';
            $html .= __($field->fieldtitle,'js-jobs');
            $html .= ' </div><div class="' . $div3 . '">';
        }
        $readonly = ''; //$field->readonly ? "'readonly => 'readonly'" : "";
        $maxlength = ''; //$field->maxlength ? "'maxlength' => '".$field->maxlength : "";
        $fvalue = "";
        $value = null;
        $userdataid = "";
        $userfielddataarray = array();
        if (isset(jsjobs::$_data['filter']['params'])) {
            $userfielddataarray = jsjobs::$_data['filter']['params'];
            $uffield = $field->field;
            //had to user || oprator bcz of radio buttons

            if (isset($userfielddataarray[$uffield]) || !empty($userfielddataarray[$uffield])) {
                $value = $userfielddataarray[$uffield];
            } else {
                $value = '';
            }
        }
         if($themecall != null){
            $theme_string = ", '". $themecall ."'";
        }else{
            $theme_string = '';
        }
        switch ($field->userfieldtype) {
            case 'text':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::text($field->field, $value, array('class' => 'inputbox one form-control '.$this->class_prefix.'-input'.$themeclass, 'data-validation' => $cssclass, 'size' => $field->size, $maxlength, $readonly, 'placeholder' => __(jsjobslib::jsjobs_htmlspecialchars($field->fieldtitle, ENT_QUOTES, 'UTF-8', false), 'js-jobs')));
                break;
            case 'email':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::email($field->field, $value, array('class' => 'inputbox one form-control '.$this->class_prefix.'-input'.$themeclass, 'data-validation' => $cssclass, 'size' => $field->size, $maxlength, $readonly, 'placeholder' => __(jsjobslib::jsjobs_htmlspecialchars($field->fieldtitle, ENT_QUOTES, 'UTF-8', false), 'js-jobs')));
                break;
            case 'date':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('text');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('text');
                }else{
                    $themeclass = '';
                }
                if(jsjobslib::jsjobs_strpos($value , '1970') !== false){
                    $value = "";
                }
                $html .= JSJOBSformfield::text($field->field, $value, array('class' => 'custom_date one '.$themeclass, 'data-validation' => $cssclass,'autocomplete'=>'off'));
                break;
            case 'editor':
                $html .= wp_editor(isset($value) ? $value : '', $field->field, array('media_buttons' => false, 'data-validation' => $cssclass));
                break;
            case 'textarea':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('textarea');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('textarea');
                }else{
                    $themeclass = '';
                }
                $html .= JSJOBSformfield::textarea($field->field, $value, array('class' => 'inputbox one '.$themeclass, 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode(stripcslashes($field->userfieldparams));
                    if(empty($value) || $value == ''){
                        unset($value);
                        $value = array();
                    }
                    foreach ($obj_option AS $option) {
                        if(is_array($value)){
                            if( in_array($option, $value)){
                                $check = 'checked="true"';
                            }else{
                                $check = '';
                            }
                        }else{
                            $check = '';
                        }
                        $html .= '<input type="checkbox" ' . $check . ' class="radiobutton" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]">';
                        $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => $field->fieldtitle);
                    $html .= JSJOBSformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(stripcslashes($field->userfieldparams));
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                    }
                }
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2,'',''" . $theme_string . ");";
                }
                $html .= JSJOBSformfield::radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass, "autocomplete" => "off", 'onclick' => $jsFunction));
                break;
            case 'combo':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');
                }else{
                    $themeclass = '';
                }
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(stripcslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "','1','',''" . $theme_string . ");";
                }
                //end
                $html .= JSJOBSformfield::select($field->field, $comboOptions, $value, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox one form-control  '.$this->class_prefix.'-select '.$themeclass));
                break;
            case 'depandant_field':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');            
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');            
                }else{
                    $themeclass = '';
                }

                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                    if (!empty($obj_option) && is_array($obj_option)) {
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "','','',''" . $theme_string . ");";

                }
                //end
                $html .= JSJOBSformfield::select($field->field, $comboOptions, $value, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox one form-control  '.$this->class_prefix.'-select '.$themeclass));
                break;
            case 'multiple':
                if(jsjobs::$theme_chk == 1){
                    $themeclass = getJobManagerThemeClass('select');            
                }elseif(jsjobs::$theme_chk == 2){
                    $themeclass = getJobHubThemeClass('select');            
                }else{
                    $themeclass = '';
                }

                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode(stripcslashes($field->userfieldparams));
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $html .= JSJOBSformfield::select($array, $comboOptions, $value, __('Select', 'js-jobs') . ' ' . $field->fieldtitle, array('data-validation' => $cssclass, 'multiple' => 'multiple','class'=>'inputbox one form-control  '.$this->class_prefix.'-select '.$themeclass));
                break;
        }
        if ($resumeform == 3) {// to handle top search case for job and resume listing.
            return $html;
        }
        $html .= '</div></div>';
        if ($resumeform == 1) {
            return $html;
        } else {
            echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
        }
    }

    function getUserFieldByField($field){
        $query = "SELECT * FROM `".jsjobs::$_db->prefix."js_job_fieldsordering` WHERE field = '".$field."' AND isuserfield = 1 ";
        $field = jsjobs::$_db->get_row($query);
        return $field;
    }

    function showCustomFields($field, $fieldfor, $params, $isforjobs = 0) {
        $html = '';
        $fvalue = '';
        $labelflag = jsjobs::$_configuration['labelinlisting'];
        if($fieldfor == 11){
            $field = $this->getUserFieldByField($field);
            if(empty($field)){
                return false;
            }
        }
        if(!empty($params)){
            $data = json_decode(jsjobslib::jsjobs_stripslashes($params),true);
            if(array_key_exists($field->field, $data)){
                $fvalue = $data[$field->field];
            }
        }
        if ($field->userfieldtype == 'date' && $fvalue != '') {
            $fvalue = date_i18n(jsjobs::$_configuration['date_format'],jsjobslib::jsjobs_strtotime($fvalue));
            if(jsjobslib::jsjobs_strpos($fvalue , '1970') !== false){
                $fvalue = "";
            }
        }
    
        if($fieldfor == 1){ // jobs listing
            $html = '<div class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-fields for-rtl joblist-datafields custom-field-wrapper ">';
            if ($labelflag == 1) {
                $html .= '<span class="js-bold">' . $field->fieldtitle . ':&nbsp;</span>';
            } elseif ($fvalue == '') {
                return '';
            }
            $html .= '<span class="get-text">' . $fvalue . '</span>
                         </div>';
        }elseif($fieldfor == 2){ // job view
            $html = '<div class="detail-wrapper"  >
                    <span class="heading">' . $field->fieldtitle . ':&nbsp;</span>
                        <span class="txt">' . $fvalue . '</span>
                    </div>';
        }elseif($fieldfor == 7 || $fieldfor == 9 || $fieldfor == 10){ // myjobs, myresume, resume listing
            if ($isforjobs == 1) {
                $html = '<div class="custom-field-wrapper">';
                if ($labelflag == 1) {
                    $html .= '<span class="js-bold">'.$field->fieldtitle.':&nbsp;</span>';
                } elseif ($fvalue == '') {
                    return '';
                }
                $html .= '<span class="get-text">'.$fvalue.'</span>';
                $html .= '</div>';
            } else {
                $html = '<div class="custom-field-wrapper">';
                $html .= '<span class="js-bold">'.$field->fieldtitle.':&nbsp;</span>';
                $html .= '<span class="get-text">'.$fvalue.'</span>';
                $html .= '</div>';
            }
        }elseif($fieldfor == 4){ // company listing
            $html = '<div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower">';
            $html .= '<span class="js-get-title">'.$field->fieldtitle.':&nbsp;</span>';
            $html .= '<span class="js-get-value">'.$fvalue.'</span>';
            $html .= '</div>';
        }elseif($fieldfor == 5){ // company view
            $html = '<div class="data-row">';
            $html .= '<span class="title">'.$field->fieldtitle.':&nbsp;</span>';
            $html .= $fvalue;
            $html .= '</div>';
        }elseif($fieldfor == 8){ // mycompanies
            $html = '<div class="js-col-xs-12 js-col-sm-6 js-col-md-4 company-detail-lower-left">';
            $html .= '<span class="js-text">'.$field->fieldtitle.':&nbsp;</span>';
            $html .= '<span class="js-value">'.$fvalue.'</span>';
            $html .= '</div>';
        }elseif($fieldfor == 11 || $fieldfor == 6){ // view resume
            return array('title' => $field->fieldtitle, 'value' => $fvalue);
        }

        return $html;
    }

    function userFieldData($field, $fieldfor, $section = null) {

        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $ff = '';
        if ($fieldfor == 2 || $fieldfor == 3) {
            $ff = " AND fieldfor = 2 ";
        } elseif ($fieldfor == 1 || $fieldfor == 4) {
            $ff = "AND fieldfor = 1 ";
        } elseif ($fieldfor == 5) {
            $ff = "AND fieldfor = 3 ";
        } elseif ($fieldfor == 6) {
            //form resume
            $ff = "AND fieldfor = 3 AND section = $section ";
        }
        $query = "SELECT field,fieldtitle,required,isuserfield,userfieldtype,readonly,maxlength,depandant_field,userfieldparams  from " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND field ='" . $field . "'" . $ff;
        $data = jsjobsdb::get_row($query);
        return $data;
    }

    function userFieldsData($fieldfor, $listing = null,$getpersonal = null) {
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $inquery = '';
        if ($listing == 1) {
            $inquery = ' AND showonlisting = 1 ';
        }
        if( $getpersonal == 1){
            $inquery .= ' AND section = 1 ';
        }

        $query = "SELECT field,fieldtitle,isuserfield,userfieldtype,userfieldparams  FROM " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND fieldfor =" . $fieldfor . $inquery;
        $data = jsjobsdb::get_results($query);
        return $data;
    }

    function getDataForDepandantFieldByParentField($fieldfor, $data) {
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $value = '';
        $returnarray = array();
        $query = "SELECT field from " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND depandant_field ='" . $fieldfor . "'";
        $field = jsjobsdb::get_var($query);
        if ($data != null) {
            foreach ($data as $key => $val) {
                if ($key == $field) {
                    $value = $val;
                }
            }
        }
        $query = "SELECT userfieldparams from " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND field ='" . $fieldfor . "'";
        $field = jsjobsdb::get_var($query);
        $fieldarray = json_decode($field);
        if(!empty($fieldarray)){
            foreach ($fieldarray as $key => $val) {
                if ($value == $key)
                    $returnarray = $val;
            }
        }
        return $returnarray;
    }

}

?>
