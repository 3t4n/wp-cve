<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSResumeModel {


    function getResumePercentage( $resumeid ){
        if(!is_numeric($resumeid))  return false;
        // get published sections first
        $list = $this->getPublishedSectionsList();
        $sections_status = array();
        foreach ($list as $key => $value) {
            $field = $value->field;
            $field = jsjobslib::jsjobs_explode('_', $field);
            $sections_status[$value->section] = array('name' => $field[1] , 'id' => $value->section, 'status' => 0);
        }
        foreach ($sections_status as $key => $section) {
            if($section['id'] == 5 || $section['id'] == 6){
                $field = 'skills';
                if($section['id'] == 6) $field = 'resume';
                $query = "SELECT $field FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE `id` = ".$resumeid;
                $result = jsjobs::$_db->get_var($query);
                if($result !=''){
                    $sections_status[$key]['status'] = 1;
                }else{
                    // check their params now
                    $query = "SELECT params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE `id` = ".$resumeid;
                    $result = jsjobs::$_db->get_var($query);
                    if($result != '' ){
                        $params = json_decode($result , true);
                        $fields = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor( 3 , $section['id']);
                        foreach($fields AS $field){
                            if(isset($params[$field->field])){
                                if($field->userfieldtype == 'date'){
                                    if(jsjobslib::jsjobs_strpos($params[$field->field] , '1970') === false){
                                        $sections_status[$key]['status'] = 1;
                                    }
                                } else {
                                    $sections_status[$key]['status'] = 1;
                                }
                            }
                        }
                    }
                }
            }else{
                $table_name = 'resume' . $section['name'] . 's';
                if ($section['id'] == 2)
                    $table_name = 'resume' . $section['name'] . 'es';
                $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_".$table_name."` WHERE `resumeid` = ".$resumeid;
                $count = jsjobs::$_db->get_var($query);
                if($count > 0){
                    $sections_status[$key]['status'] = 1;
                }
            }
        }
        $filled_sections = 1;
        foreach ($sections_status as $key => $value) {
            if($value['status'] == 1)
                $filled_sections += 1;
        }
        if(empty($sections_status)){
            $total = 0;
        }else{
            $total = count($sections_status);
        }
        if($total > 0){
            $others = 75 / $total;
            $total_fill = 0;
            for ($i=1; $i < $filled_sections; $i++) {
                $total_fill += $others;
            }
            if($total_fill > 0){
                $percentage = 25 + $total_fill;
                $percentage = round($percentage);
            }else{
                $percentage = 25;
            }
        }else{
            $percentage = 100;
        }
        $sections_status['percentage'] = $percentage;
        return $sections_status;
    }
    function getPublishedSectionsList(){
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if ($uid != 0)
            $published = '`published` = 1';
        else
            $published = '`isvisitorpublished` = 1';
        $query = "SELECT field , section FROM `" . jsjobs::$_db->prefix . "js_job_fieldsordering` WHERE `field` IN('section_address', 'section_institute', 'section_employer', 'section_skills', 'section_resume', 'section_reference', 'section_language') AND ".$published." AND `fieldfor` = 3";
        $fields = jsjobs::$_db->get_results($query);
        return $fields;
    }

    /* new code for resume start */

    function storeResume($data){
        if (empty($data)) return false;
        // check to validate the captcha
        if (!$this->captchaValidate()) {
            JSJOBSMessages::setLayoutMessage(__('Incorrect Captcha code', 'js-jobs'), 'error',$this->getMessagekey());
            $array = json_encode(array('html' => 'error'));
            return $array;
        }
        //$resume_tbl_obj = JSJOBSincluder::getJSTable('resume');
        if(!current_user_can('manage_options')){
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
            $data['uid'] = $uid;
        }else{
            $uid = $this->getUidByResumeId($data['id']);
            $data['uid'] = $uid;
        }
        $resumeid = $data['id'];
        $data['sec_1']['id'] = $resumeid; // because id is not in any section to put for sections
        $data['sec_1']['uid'] = $data['uid']; // because id is not in any section to put for sections
        $resumedata = $data['sec_1'];
        $resume = $this->storePersonalSection($resumedata); // store persnal section
        if($resume === false) return false;
        if(isset($resume[0])) $filestatus = $resume[0];
        $resumeid = $resume[1];
        $resumealiasid = $resume[2].'-'.$resumeid;
        if (is_admin()) {
            $resumealiasid = $resumeid;
        }
        $sections =
            array(
                1 => array('name' => 'address' , 'id' => 2),
                2 => array('name' => 'institute' , 'id' => 3),
                3 => array('name' => 'employer' , 'id' => 4),
                4 => array('name' => 'skills' , 'id' => 5),
                5 => array('name' => 'editor' , 'id' => 6),
                6 => array('name' => 'reference' , 'id' => 7),
                7 => array('name' => 'language' , 'id' => 8),
            );
        $doremove = false;
        foreach ($sections as $sec) {
            $sec_id = 'sec_'.$sec['id'];
            // get sections's data object vise
            $row = array();
            $total = isset($data[$sec_id]) ? count($data[$sec_id]['id']) : 0; // only published sections will be considred
            // check if empty section submitted
            $is_filled = false;
            for ($i = 0; $i < $total; $i++) {
                $doremove = false;
                foreach ($data[$sec_id] as $key => $arr) {
                    $row[$key] = isset($arr[$i]) ? $arr[$i] : '';
                    if($key == 'deletethis' AND $arr[$i] == 1){
                        $doremove = true;
                    }
                    if( ! empty($arr[$i])){
                        $is_filled = true;
                    }
                }
                $row['resumeid'] = $resumeid;
                if($doremove){
                    //var_dump('do remove sec '.$sec);
                    $result = $this->removeResumeSection( $row, $sec);
                }else{
                    if($sec['id'] == 5 || $sec['id'] == 6){
                        $is_filled = true;
                    }
                    if( $is_filled ){
                        $result = $this->storeResumeSection( $row , $sec , $i); // i is use for geting custom files
                        if($result==false) return false;
                    }
                }
            }
        }
        // visitor apply
        if (isset($_COOKIE['jsjobs_apply_visitor'])) {
            $url = JSJOBSincluder::getJSModel('jobapply')->visitorapplyjob(1,$resumeid); // 1 for call from resume model
            wp_redirect($url);
            exit;
        }
        return JSJOBS_SAVED;

    }
    function storeResumeSection( $formdata, $section , $i) { // i is the index of A section have multi forms
        if(empty($section)) return false;
        $sectionid = $section['id'];
        $datafor = $section['name'];

        // store skills/editor sections data
        if($sectionid == 5 || $sectionid == 6){
            $result = $this->storeSkillsAndResumeSection($formdata , $section, $i);
            return $result;
        }

        if ($sectionid == 2) {
            $table_name = 'resume' . $datafor . 'es';
        } else {
            $table_name = 'resume' . $datafor . 's';
        }
        //$table_name = 'resume' . $datafor;
        $row = JSJOBSincluder::getJSTable($table_name);
        // custom field code start
        $return_cf = $this->makeResumeTableParams($formdata,$sectionid,$i);
        $params = array();
        $par = json_decode($return_cf['params'],true);
        if(is_array($par)){
            foreach($par AS $key => $value){
                $params[$key] = $value;
            }
        }
        $resumeid = $formdata['resumeid'];

        //check whether form data array is empty;
            $check_array = $formdata;
            unset($check_array['resumeid']);
            $empty_flag = (count(array_filter($check_array)) == 0) ? 1 : 0;
            if($empty_flag == 1){
                return true;
            }
        //

        // set created date
        if( ! is_numeric($formdata['id'])){
            $formdata['created'] = date('Y-m-d H:i:s');
        }

        if($params){
            $formdata['params'] = json_encode($params);
        }else{
            $formdata['params'] = '';
        }
        // custom field code end
        $formdata = jsjobs::sanitizeData($formdata);
        $formdata = JSJOBSincluder::getJSmodel('common')->stripslashesFull($formdata);// remove slashes with quotes.
        if (!$row->bind($formdata)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }
    function storeSkillsAndResumeSection($formdata , $section, $i){
        if(empty($section)) return '';

        $sectionid = $section['id'];
        $datafor = $section['name'];

        $row = JSJOBSincluder::getJSTable('resume');

        $formdata['id'] = $formdata['resumeid'];
        $resumeid = $formdata['resumeid'];
        unset($formdata['resumeid']);
        if ($sectionid == 6) { // editor
            //$formdata['resume'] = JRequest::getVar('resumeeditor', '', 'post', 'string', JREQUEST_ALLOWHTML );
            // RESUME Resume CUSTOM FIELD
            //$params = $this->getDataForParams(6, $data);
            $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
            $params = $return_cf['params'];

            $pquery = "SELECT params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $parmsvar = jsjobs::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && $parmsvar != '') {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && $params != '') {
                $params = json_decode($params);
            }
            if(!empty($parray)){
                $params = (object) array_merge((array) $params, (array) $parray);
            }
            if(is_object($params) && !empty($params)){
                $params = json_encode($params, JSON_UNESCAPED_UNICODE);
                $queryparams = " , params='" . $params . "' ";
            }else{
                $queryparams = "";
            }
            //END
            $resume = JSJOBSrequest::getVar('resume_edit_val');
            if($resume == ''){
                $resume = JSJOBSrequest::getVar('resume');
            }
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_resume` SET resume='" . $resume . "' " .$queryparams." WHERE id = $resumeid";
            jsjobs::$_db->query($query);

        }elseif($sectionid==5){
            $skills = JSJOBSrequest::getVar('skills');
            // RESUME SKILL CUSTOM FIELD
            //$params = $this->getDataForParams(5, $data);
            $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
            $params = $return_cf['params'];
            $pquery = "SELECT params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $parmsvar = jsjobs::$_db->get_var($pquery);

            $parray = array();
            if (isset($parmsvar) && $parmsvar !='' ) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && $params != '') {
                $params = json_decode($params);
            }
            if(!empty($parray)){
                $params = (object) array_merge((array) $params, (array) $parray);
            }
            if(is_object($params) && !empty($params)){
                $params = json_encode($params, JSON_UNESCAPED_UNICODE);
                $queryparams = " , params='" . $params . "' ";
            }else{
                $queryparams = "";
            }
            //END
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_resume` SET skills='" . $skills . "' " . $queryparams . " WHERE id = $resumeid";
            jsjobs::$_db->query($query);

        }
        return true;
        $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
        $formdata['params'] = $return_cf['params'];
        if (!$row->bind($formdata)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        //retain last state of below vars in edit
        if (is_numeric($formdata['id']) ){
            unset($row->isgoldresume);
            unset($row->isfeaturedresume);
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function removeResumeSection( $formdata, $section ){
        if($formdata['deletethis'] != 1){
            return;
        }
        if($formdata['id'] == '' || !isset($formdata['id'])){
            return;
        }
        //exit;
        if(empty($section)) return false;
        $sec_id = $section['id'];
        $datafor = $section['name'];

        $resumeid = $formdata['resumeid'];
        $sectionid = $formdata['id'];

        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if(!is_numeric($resumeid)) return false;
        if(!is_numeric($sectionid)) return false;

        if ( ! current_user_can( 'manage_options' ) ) { // user is not admin check perform
            if( ! JSJOBSincluder::getObjectClass('user')->isguest()){
                $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = $resumeid AND uid = $uid";
                $result = jsjobs::$_db->get_var($query);
                if($result == 0){
                    return false; // not your resume
                }
            }
        }

        if ($sec_id == 2) {
            $table_name = 'resume' . $datafor . 'es';
        } else {
            $table_name = 'resume' . $datafor . 's';
        }

        if($sec_id == 5 || $sec_id == 6){ //skill,editor
            return true;
        }else{
            $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_".$table_name."` WHERE id = ".$sectionid;
            if (jsjobsdb::query($query)) {
                return true;
            }else{
                return false;
            }
        }
    }

    function storePersonalSection($data){
        if(empty($data))return '';
        if(isset($data['id']) && $data['id'] == 0 ) $data['id'] = '';
        $row = JSJOBSincluder::getJSTable('resume');
        if (empty($data['id'])) {
            if(isset($data['application_title'])){
                $data['alias'] = jsjobslib::jsjobs_str_replace(' ', '-', $data['application_title']);
            }else{
                $alias_string = $data['first_name'].' '.$data['middle_name'].' '.$data['last_name'];
                $data['alias'] = jsjobslib::jsjobs_str_replace(' ', '-', $alias_string);
            }
            $data['created'] = date('Y-m-d H:i:s');
            $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('empautoapprove');
        } else {
            if(current_user_can('manage_options')){
                $data['status'] = $data['status'];
            }else{
                $row->load($data['id']);
                $data['status'] = $row->status;
            }
        }
        if(!empty($data['date_of_birth'])){
            $data['date_of_birth'] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data['date_of_birth']));
        }
        if(!empty($data['date_start'])){
            $data['date_start'] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data['date_start']));
        }
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE field =  'searchable' AND fieldfor =3";
        $record = jsjobs::$_db->get_row($query);
        if($record->published == 0 AND is_user_logged_in()){
            $data['searchable'] = 1;
        }elseif($record->isvisitorpublished == 0){
            $data['searchable'] = 1;
        }
        if (!isset($data['iamavailable'])) {
            $data['iamavailable'] = 0;
        }
        $data['last_modified'] = date('Y-m-d H:i:s');
        $section = 1;
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $return_cf = $this->makeResumeTableParams($data,$section);
        $video = $_POST['sec_1']['video'];
        $data['video'] = jsjobslib::jsjobs_str_replace('\"', '"', $video);
        $data['params'] = $return_cf['params'];
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        $objectid = $row->id;
        $resumeid = $row->id;
        if (isset($_FILES['photo']['size']) && $_FILES['photo']['size'] > 0) {
            $this->uploadPhoto($objectid);
        }
        if (isset($_FILES['resumefiles'])) {
            $filereturnvalue=$this->uploadResume($objectid);
        }
        // Save resumeid in session in case of visitor add resume is allowed
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            $visitor_can_add_resume = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_add_resume');
            if ($visitor_can_add_resume == 1) {
                $wp_jsjobs['resumeid'] = $resumeid;
                $wp_jsjobs = json_encode( $wp_jsjobs );
                $wp_jsjobs = jsjobslib::jsjobs_safe_encoding($wp_jsjobs);
                jsjobslib::jsjobs_setcookie('wp-jsjobs' , $wp_jsjobs , 0 , COOKIEPATH);
                if ( SITECOOKIEPATH != COOKIEPATH ){
                    jsjobslib::jsjobs_setcookie('wp-jsjobs' , $wp_jsjobs , 0 , SITECOOKIEPATH);
                }
            }
        }
        //Update credits log in case of new resume
        if ($data['id'] == '') {
            if(empty($data['id'])){
                JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3,1,$resumeid); // 3 for resume,1 for add new resume
            }
        }
        $return = array();
        if(isset($filereturnvalue)) $return[0] = $filereturnvalue;
        $return[1] = $row->id;
        $return[2] = $row->alias;
        return $return;
    }
    function makeResumeTableParams($formdata,$sectionid,$i=0){

        $return_cf = $this->getDataForParamsResume($sectionid , $formdata, $i);

        $params_new = $return_cf['params'];

        if(is_numeric($formdata['id'])){
            $params_new = json_decode($params_new, true);
            $query = "SELECT params FROM `". jsjobs::$_db->prefix ."js_job_resume` WHERE id = ".$formdata['id'];
            $oParams = jsjobsdb::get_var($query);
            if(!empty($oParams)){
                $oParams = json_decode($oParams,true);
                $unpublihsedFields = JSJOBSincluder::getJSModel('customfield')->getUnpublishedFieldsFor(3,1);
                foreach($unpublihsedFields AS $field){
                    if(isset($oParams[$field->field]) && !empty($oParams[$field->field])){
                        $params_new[$field->field] = $oParams[$field->field];
                    }
                }
                $sectionfields = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(3,$sectionid);
                foreach($sectionfields AS $cfield){
                    if(isset($oParams[$cfield->field]))
                        unset($oParams[$cfield->field]);
                }

                foreach($oParams AS $key => $value){
                    $params_new[$key] = $value;
                }
            }
            if($params_new){
                $params_new = json_encode($params_new);
            }
        }
        $return_cf['params'] = $params_new;
        //fix for resume only
        if($return_cf['params'] == null || $return_cf['params'] == 'null'){
            $return_cf['params'] = '';
        }
        return $return_cf;
    }

    // custom field code start
    function getDataForParamsResume($sectionid, $data , $i = 0) {
        $userfieldforresume = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(3, $sectionid);
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $params = array();
        foreach ($userfieldforresume AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }else{
                    if($sectionid == 1){
                        $section_id = 'sec_'.$sectionid;
                        $vardata = isset($_FILES[$section_id]['name'][$ufobj->field]) ? sanitize_text_field($_FILES[$section_id]['name'][$ufobj->field]) : '';
                    }else{
                        $section_id = 'sec_'.$sectionid;
                        $vardata = isset($_FILES[$section_id]['name'][$ufobj->field][$i]) ? sanitize_text_field($_FILES[$section_id]['name'][$ufobj->field][$i]) : '';
                    }
                }
                $customflagforadd = true;
                $custom_field_namesforadd[] = $ufobj->field;
            } elseif ($ufobj->userfieldtype == 'date') {
                $vardata = isset($data[$ufobj->field]) ? date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data[$ufobj->field])) : '';
            } else {
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $custom_field_namesfordelete[]= $data[$ufobj->field.'_2'];
            }
            if($vardata != ''){
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = jsjobslib::jsjobs_htmlspecialchars($vardata);
            }
        }
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);

        $return = array();
        $return['params'] = $params;
        $return['customflagforadd'] = $customflagforadd;
        $return['customflagfordelete'] = $customflagfordelete;
        $return['custom_field_namesforadd'] = $custom_field_namesforadd;
        $return['custom_field_namesfordelete'] = $custom_field_namesfordelete;

        return $return;
    }
    // custom field code End

    function getResumeDataBySection($resumeid, $sectionName){
        if(!is_numeric($resumeid)) return false;

        switch ($sectionName) {
            case 'personal': $section = 1; break;
            case 'address': $section = 2; break;
            case 'institute': $section = 3; break;
            case 'employer': $section = 4; break;
            case 'skills': $section = 5; break;
            case 'editor': $section = 6; break;
            case 'reference': $section = 7; break;
            case 'language': $section = 8; break;
            case 'default':
                return false;
        }
        $data = array();
        if ($sectionName == 'personal') {
            $results = $this->getResumeBySection($resumeid, $sectionName);
            //$resumelists = $this->getResumeListsForForm($results);
            //jsjobs::$_data[2]=$resumelists;
        } else {
            $sectionData = array();
            if ($sectionName == "skills" OR $sectionName == "editor") {
                $results = $this->getResumeBySection($resumeid, $sectionName);
            } else {
                $results = $this->getResumeBySection($resumeid, $sectionName);
            }
        }
        //$custom_fields =JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field, 1, 1);
        //$resume_section_fields = JSJOBSincluder::getJSModel('customfield')->getResumeFieldsOrderingBySection($section);
        jsjobs::$_data[0] = $results;
        return;
    }

    function getResumeBySection($resumeid, $sectionName ) {
        if (!is_numeric($resumeid)) {
            return false;
        }
        if (empty($sectionName)) {
            return false;
        }
        $resume = '';
        if ($sectionName == 'personal') {
            $query = "SELECT resume.id,resume.driving_license,resume.facebook,resume.googleplus,resume.linkedin,resume.twitter, resume.tags AS viewtags , resume.tags AS resumetags ,resume.license_no,licensecountry.name AS licensecountryname,resume.license_country,resume.videotype,resume.uid,resume.application_title, resume.first_name, resume.last_name, resume.middle_name, resume.cell, resume.email_address, resume.nationality AS nationalityid, resume.photo, resume.gender, resume.job_category, resume.heighestfinisheducation, resume.experienceid, resume.home_phone, resume.work_phone, resume.date_of_birth, resume.jobsalaryrangestart,resume.jobsalaryrangeend
                        , resume.jobsalaryrangetype, resume.currencyid, resume.dcurrencyid , resume.desiredsalarystart, resume.desiredsalaryend, resume.djobsalaryrangetype, resume.skills, resume.video, resume.keywords, resume.searchable, resume.iamavailable, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle, salstart.rangestart AS rangestart, salend.rangeend AS rangeend, resume.date_start,resume.jobtype
                        , resume.resume, saltype.title AS rangetype, dsalstart.rangestart AS drangestart, dsalend.rangeend AS drangeend, dsaltype.title AS drangetype, currency.symbol AS symbol, dcurrency.symbol AS dsymbol,nationality.name AS nationality, highestfinisheducation.title AS highestfinisheducation, exp.title AS total_experience
                        ,resume.params,resume.status
                        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = resume.job_category
                        JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salstart ON salstart.id = resume.jobsalaryrangestart
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salend ON salend.id = resume.jobsalaryrangeend
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS saltype ON saltype.id = resume.jobsalaryrangetype
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalstart ON dsalstart.id = resume.desiredsalarystart
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalend ON dsalend.id = resume.desiredsalaryend
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS dsaltype ON dsaltype.id = resume.djobsalaryrangetype
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS dcurrency ON dcurrency.id = resume.dcurrencyid
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS nationality ON nationality.id = resume.nationality
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS licensecountry ON licensecountry.id = resume.license_country
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS highestfinisheducation ON highestfinisheducation.id = resume.heighestfinisheducation
                        WHERE resume.id = " . $resumeid;

            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
            $isjsjobsuser = JSJOBSincluder::getObjectClass('user')->isJSJOBSUser();
            if(! $isguest && $isjsjobsuser){
                if (!current_user_can( 'manage_options' ) && $uid) {
                    //$query .= " AND resume.uid  = " . $uid;
                }
            }
            $resume = jsjobsdb::get_row($query);
        } elseif ($sectionName == 'skills') {
            $query = "SELECT id,uid,skills,params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $resume = jsjobsdb::get_row($query);
        } elseif ($sectionName == 'editor') {
            $query = "SELECT id,uid,resume,params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $resume = jsjobsdb::get_row($query);
        } elseif ($sectionName == 'language') {
            $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages` WHERE resumeid = " . $resumeid;
            $resume = jsjobsdb::get_results($query);
        } elseif ($sectionName == 'address') {
            $query = "SELECT address.*,
                        cities.id AS cityid,
                        cities.cityName AS city,
                        states.name AS state,
                        countries.name AS country
                        FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON address.address_city = cities.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id
                        WHERE address.resumeid = " . $resumeid;
            $resume = jsjobsdb::get_results($query);
        } else {
            $query = "SELECT " . $sectionName . ".*,
                        cities.id AS cityid,
                        cities.cityName AS city,
                        states.name AS state,
                        countries.name AS country
                        FROM `" . jsjobs::$_db->prefix . "js_job_resume" . $sectionName . "s` AS " . $sectionName . "
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON " . $sectionName . "." . $sectionName . "_city = cities.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id
                        WHERE " . $sectionName . ".resumeid = " . $resumeid;
            $resume = jsjobsdb::get_results($query);
        }
        return $resume;
    }

    function getResumeListsForForm($application) {
        $resumelists = array();
        $nationality_required = '';
        $license_country_required = '';
        $gender_required = '';
        $driving_license_required = '';
        $category_required = '';
        $subcategory_required = '';
        $salary_required = '';
        $workpreference_required = '';
        $education_required = '';
        $expsalary_required = '';

        // explicit use of site model in case form admin resume
        //$fieldsordering = $this->getJSSiteModel('customfields')->getResumeFieldsOrderingBySection(1);
        $fieldsordering = JSJOBSincluder::getJSModel('customfield')->getResumeFieldsOrderingBySection(1);
        foreach ($fieldsordering AS $fo) {
            switch ($fo->field) {
                case "nationality":
                    $nationality_required = ($fo->required ? 'required' : '');
                    break;
                case "license_country":
                    $license_country_required = ($fo->required ? 'required' : '');
                    break;
                case "gender":
                    $gender_required = ($fo->required ? 'required' : '');
                    break;
                case "driving_license":
                    $driving_license_required = ($fo->required ? 'required' : '');
                    break;
                case "job_category":
                    $category_required = ($fo->required ? 'required' : '');
                    break;
                case "job_subcategory":
                    $subcategory_required = ($fo->required ? 'required' : '');
                    break;
                case "salary":
                    $salary_required = ($fo->required ? 'required' : '');
                    break;
                case "jobtype":
                    $workpreference_required = ($fo->required ? 'required' : '');
                    break;
                case "heighestfinisheducation":
                    $education_required = ($fo->required ? 'required' : '');
                    break;
                case "desired_salary":
                    $expsalary_required = ($fo->required ? 'required' : '');
                    break;
                case "total_experience":
                    $experienceid_required = ($fo->required ? 'required' : '');
                    break;
            }
        }
        // since common is already executed form admin


        $gender = JSJOBSincluder::getJSModel('common')->getGender();
        $driving_license = JSJOBSincluder::getJSModel('common')->getYesNo();

        $defaultCategory = JSJOBSincluder::getJSModel('category')->getDefaultCategoryId();
        $defaultJobtype = JSJOBSincluder::getJSModel('jobtype')->getDefaultJobTypeId();
        $yesno=JSJOBSincluder::getJSModel('common')->getYesNo();
        $defaultEducation = JSJOBSincluder::getJSModel('common')->getDefaultValue('heighesteducation');
        $defaultSalaryrange = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrange');
        $defaultSalaryrangeType = JSJOBSincluder::getJSModel('common')->getDefaultValue('salaryrangetypes');
        $defaultCurrencies = JSJOBSincluder::getJSModel('common')->getDefaultValue('currencies');
        $defaultExperiences = JSJOBSincluder::getJSModel('common')->getDefaultValue('experiences');

        $job_type = JSJOBSincluder::getJSModel('jobtype')->getJobTypeForCombo();
        $heighesteducation = JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationForCombo();
        $job_categories = JSJOBSincluder::getJSModel('category')->getCategoryForCombobox('');
        $job_salaryrange_start = JSJOBSincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo();
        $job_salaryrange_end = JSJOBSincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo();
        $job_salaryrangetype = JSJOBSincluder::getJSModel('currency')->getCurrencyForCombo();
        $experiences = JSJOBSincluder::getJSModel('experience')->getExperiencesForCombo();
        $countries = JSJOBSincluder::getJSModel('country')->getCountriesForCombo();
        if (isset($application)) {
            $resumelists['nationality'] = JHTML::_('select.genericList', $countries, 'sec_1[nationality]', 'class="inputbox ' . $nationality_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->nationality);
            $resumelists['license_country'] = JHTML::_('select.genericList', $countries, 'sec_1[license_country]', 'class="inputbox ' . $license_country_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->license_country);

            $resumelists['gender'] = JHTML::_('select.genericList', $gender, 'sec_1[gender]', 'class="inputbox ' . $gender_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->gender);
            $resumelists['driving_license'] = JHTML::_('select.genericList', $driving_license, 'sec_1[driving_license]', 'class="inputbox ' . $driving_license_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->driving_license);

            $resumelists['job_category'] = JHTML::_('select.genericList', $job_categories, 'sec_1[job_category]', 'class="inputbox ' . $category_required . ' jsjobs-cbo" ' . 'onChange="return fj_getsubcategories(\'job_subcategory\', this.value)"', 'value', 'text', $application->job_category);
            if(!empty($job_subcategories))
                $resumelists['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->job_subcategory);
            else
                $resumelists['job_subcategory'] = JHTML::_('select.genericList', array(), 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->job_subcategory);

            $resumelists['jobtype'] = JHTML::_('select.genericList', $job_type, 'sec_1[jobtype]', 'class="inputbox ' . $workpreference_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->jobtype);
            $resumelists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'sec_1[heighestfinisheducation]', 'class="inputbox ' . $education_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->heighestfinisheducation);
            $resumelists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[jobsalaryrange]', 'class="inputbox ' . $salary_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->jobsalaryrange);
            $resumelists['desired_salary'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[desired_salary]', 'class="inputbox ' . $expsalary_required . ' jsjobs-cbo" ' . '', 'value', 'text', $application->desired_salary);
            $resumelists['jobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[jobsalaryrangetype]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $application->jobsalaryrangetype);
            $resumelists['djobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[djobsalaryrangetype]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $application->djobsalaryrangetype);
            $resumelists['currencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[currencyid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $application->currencyid);
            $resumelists['dcurrencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[dcurrencyid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $application->dcurrencyid);
            $resumelists['experienceid'] = JHTML::_('select.genericList', $experiences, 'sec_1[experienceid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $application->experienceid);
        } else {
            $resumelists['license_country'] = JHTML::_('select.genericList', $countries, 'sec_1[license_country]', 'class="inputbox ' . $license_country_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
            $resumelists['nationality'] = JHTML::_('select.genericList', $countries, 'sec_1[nationality]', 'class="inputbox ' . $nationality_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
            $resumelists['gender'] = JHTML::_('select.genericList', $gender, 'sec_1[gender]', 'class="inputbox ' . $gender_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
            $resumelists['driving_license'] = JHTML::_('select.genericList', $driving_license, 'sec_1[driving_license]', 'class="inputbox ' . $driving_license_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');

            $resumelists['job_category'] = JHTML::_('select.genericList', $job_categories, 'sec_1[job_category]', 'class="inputbox ' . $category_required . ' jsjobs-cbo" ' . 'onChange="fj_getsubcategories(\'job_subcategory\', this.value)"', 'value', 'text', $defaultCategory);
            $resumelists['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');

            $resumelists['jobtype'] = JHTML::_('select.genericList', $job_type, 'sec_1[jobtype]', 'class="inputbox ' . $workpreference_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultJobtype);
            $resumelists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'sec_1[heighestfinisheducation]', 'class="inputbox ' . $education_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultEducation);
            $resumelists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[jobsalaryrange]', 'class="inputbox ' . $salary_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultSalaryrange);
            $resumelists['jobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[jobsalaryrangetype]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $defaultSalaryrangeType);
            $resumelists['djobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[djobsalaryrangetype]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $defaultSalaryrangeType);

            $resumelists['desired_salary'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[desired_salary]', 'class="inputbox ' . $expsalary_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultSalaryrange);
            $resumelists['currencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[currencyid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $defaultCurrencies);
            $resumelists['dcurrencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[dcurrencyid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $defaultCurrencies);
            $resumelists['experienceid'] = JHTML::_('select.genericList', $experiences, 'sec_1[experienceid]', 'class="inputbox jsjobs-cbo" ' . '', 'value', 'text', $defaultExperiences);
        }
        return $resumelists;
    }













    /* new code for resume start */






















    function getAllEmpApps() {
        $this->sorting();
        //Filter
        $searchtitle = jsjobs::$_search['resumes']['searchtitle'];
        $searchname = jsjobs::$_search['resumes']['searchname'];
        $searchjobcategory = jsjobs::$_search['resumes']['searchjobcategory'];
        $searchjobtype = jsjobs::$_search['resumes']['searchjobtype'];
        $searchjobsalaryrange = jsjobs::$_search['resumes']['searchjobsalaryrange'];
        $status = jsjobs::$_search['resumes']['status'];
        $datestart = jsjobs::$_search['resumes']['datestart'];
        $dateend = jsjobs::$_search['resumes']['dateend'];

        jsjobs::$_data['filter']['searchtitle'] = $searchtitle;
        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['searchjobtype'] = $searchjobtype;
        jsjobs::$_data['filter']['searchjobsalaryrange'] = $searchjobsalaryrange;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(app.application_title) LIKE '%" . $searchtitle . "%'";
        if ($searchname) {
            $inquery .= " AND (";
            $inquery .= " LOWER(app.first_name) LIKE '%" . $searchname . "%'";
            $inquery .= " OR LOWER(app.last_name) LIKE '%" . $searchname . "%'";
            $inquery .= " OR LOWER(app.middle_name) LIKE '%" . $searchname . "%'";
            $inquery .= " )";
        }
        if ($searchjobcategory)
            $inquery .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $inquery .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange){
            $inquery .= " AND (SELECT rangestart FROM `".jsjobs::$_db->prefix."js_job_salaryrange` WHERE id = ".$searchjobsalaryrange.") >= salarystart.rangestart AND (SELECT rangestart FROM `".jsjobs::$_db->prefix."js_job_salaryrange` WHERE id = ".$searchjobsalaryrange.") <= salarystart.rangeend";
        }
        if ($status != null) {
            if (is_numeric($status)) {
                $inquery .= " AND app.status = " . $status;
            }
        }
        if ($datestart != null) {
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(app.created) >=  '" . $datestart . "' ";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(app.created) <=  '" . $dateend . "'";
        }
        $curdate = date('Y-m-d');
        //Pagination
        $query = "SELECT COUNT(app.id) FROM " . jsjobs::$_db->prefix . "js_job_resume AS app
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salarystart ON app.jobsalaryrangestart = salarystart.id
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salaryend ON app.jobsalaryrangeend = salaryend.id
                WHERE app.status <> 0";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT app.uid,app.id, app.application_title,app.first_name, app.last_name, app.jobtype,app.photo,
                    app.jobsalaryrangestart, app.created, app.status, cat.cat_title, salarystart.rangestart, salaryend.rangeend , currency.symbol
                , jobtype.title AS jobtypetitle,city.id as city, salarytype.title AS rangetype
            FROM " . jsjobs::$_db->prefix . "js_job_resume AS app
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON app.job_category = cat.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_jobtypes AS jobtype    ON app.jobtype = jobtype.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salarystart ON app.desiredsalarystart = salarystart.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salaryend ON app.desiredsalaryend = salaryend.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrangetypes AS salarytype ON app.djobsalaryrangetype = salarytype.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_currencies AS currency ON currency.id = app.dcurrencyid
            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = app.id ORDER BY id DESC LIMIT 1)
            WHERE app.status <> 0  ";
        $query.=$inquery;
        $query.=" ORDER BY " . jsjobs::$_data['sorting'];
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(3);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        return;
    }

    function sorting() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        jsjobs::$_data['sorton'] = jsjobs::$_search['resumes']['sorton'];
        jsjobs::$_data['sortby'] = jsjobs::$_search['resumes']['sortby'];
        switch (jsjobs::$_data['sorton']) {
            case 1: // appilcation title
                jsjobs::$_data['sorting'] = ' app.application_title ';
                break;
            case 2: // first name
                jsjobs::$_data['sorting'] = ' app.first_name ';
                break;
            case 3: // category
                jsjobs::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 4: // job type
                jsjobs::$_data['sorting'] = ' app.jobtype ';
                break;
            case 5: // location
                jsjobs::$_data['sorting'] = ' city.cityName ';
                break;
            case 6: // created
                jsjobs::$_data['sorting'] = ' app.created ';
                break;
            case 7: // status
                jsjobs::$_data['sorting'] = ' app.status ';
                break;
        }
        if (jsjobs::$_data['sortby'] == 1) {
            jsjobs::$_data['sorting'] .= ' ASC ';
        } else {
            jsjobs::$_data['sorting'] .= ' DESC ';
        }
        jsjobs::$_data['combosort'] = jsjobs::$_data['sorton'];
    }

    function getAllUnapprovedEmpApps() {
        $this->sorting();
        //Filter
        $searchtitle = jsjobs::$_search['resumes']['searchtitle'];
        $searchname = jsjobs::$_search['resumes']['searchname'];
        $searchjobcategory = jsjobs::$_search['resumes']['searchjobcategory'];
        $searchjobtype = jsjobs::$_search['resumes']['searchjobtype'];
        $searchjobsalaryrange = jsjobs::$_search['resumes']['searchjobsalaryrange'];
        $status = jsjobs::$_search['resumes']['status'];
        $datestart = jsjobs::$_search['resumes']['datestart'];
        $dateend = jsjobs::$_search['resumes']['dateend'];

        jsjobs::$_data['filter']['searchtitle'] = $searchtitle;
        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['searchjobtype'] = $searchjobtype;
        jsjobs::$_data['filter']['searchjobsalaryrange'] = $searchjobsalaryrange;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(app.application_title) LIKE '%" . $searchtitle . "%'";
        if ($searchname) {
            $inquery .= " AND (";
            $inquery .= " LOWER(app.first_name) LIKE '%" . $searchname . "%'";
            $inquery .= " OR LOWER(app.last_name) LIKE '%" . $searchname . "%'";
            $inquery .= " OR LOWER(app.middle_name) LIKE '%" . $searchname . "%'";
            $inquery .= " )";
        }
        if ($searchjobcategory)
            $inquery .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $inquery .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange)
            $inquery .= " AND app.jobsalaryrangetype = " . $searchjobsalaryrange;
        if ($status != null) {
            if (is_numeric($status))
                $inquery .= " AND app.status = " . $status;
        }

        if ($datestart != null) {
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(app.created) >=  '" . $datestart . "' ";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(app.created) <=  '" . $dateend . "'";
        }

        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_resume AS app WHERE (app.status = 0)";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT app.photo,app.id, app.application_title,app.first_name, app.last_name, app.jobtype,
                app.jobsalaryrangetype, app.created, app.status, cat.cat_title, salarystart.rangestart, salaryend.rangeend , currency.symbol
                , jobtype.title AS jobtypetitle,city.id as city, salarytype.title AS rangetype
            FROM " . jsjobs::$_db->prefix . "js_job_resume AS app
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON app.job_category = cat.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_jobtypes AS jobtype    ON app.jobtype = jobtype.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salarystart ON app.desiredsalarystart = salarystart.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrange AS salaryend ON app.desiredsalaryend = salaryend.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_salaryrangetypes AS salarytype ON app.djobsalaryrangetype = salarytype.id
            LEFT JOIN " . jsjobs::$_db->prefix . "js_job_currencies AS currency ON currency.id = app.dcurrencyid
            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = app.id ORDER BY id DESC LIMIT 1)
            WHERE (app.status = 0 ) ";
        $query.=$inquery;
        $query.=" ORDER BY " . jsjobs::$_data['sorting'];
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(3);
        return;
    }

    function getUserStatsResumes($resumeuid) {
        if (is_numeric($resumeuid) == false)
            return false;
        //pagination
        $query = "SELECT COUNT(resume.id) FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume WHERE resume.uid =" . $resumeuid;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT resume.id,resume.application_title,resume.first_name,resume.last_name,cat.cat_title,resume.created,resume.status
                    FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id=resume.job_category
                    WHERE resume.uid = " . $resumeuid;
        $query .= " ORDER BY resume.first_name";
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function getResumeSearch() {
        //Filters
        $title = JSJOBSrequest::getVar('title');
        $name = JSJOBSrequest::getVar('name');
        $nationality = JSJOBSrequest::getVar('nationality');
        $gender = JSJOBSrequest::getVar('gender');
        $iamavailable = JSJOBSrequest::getVar('iamavailable', 0); // b/c when checkbox is unchecked it remain get its last value
        $jobcategory = JSJOBSrequest::getVar('jobcategory');
        $jobtype = JSJOBSrequest::getVar('jobtype');
        $jobsalaryrange = JSJOBSrequest::getVar('jobsalaryrange');
        $education = JSJOBSrequest::getVar('heighestfinisheducation');
        $experience = JSJOBSrequest::getVar('experience');
        $currency = JSJOBSrequest::getVar('currency');
        $zipcode = JSJOBSrequest::getVar('zipcode');
        $jobstatus = JSJOBSrequest::getVar('jobstatus');

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['name'] = $name;
        jsjobs::$_data['filter']['nationality'] = $nationality;
        jsjobs::$_data['filter']['gender'] = $gender;
        jsjobs::$_data['filter']['iamavailable'] = $iamavailable;
        jsjobs::$_data['filter']['jobcategory'] = $jobcategory;
        jsjobs::$_data['filter']['jobtype'] = $jobtype;
        jsjobs::$_data['filter']['jobsalaryrange'] = $jobsalaryrange;
        jsjobs::$_data['filter']['heighestfinisheducation'] = $education;
        jsjobs::$_data['filter']['experience'] = $experience;
        jsjobs::$_data['filter']['currency'] = $currency;
        jsjobs::$_data['filter']['zipcode'] = $zipcode;
        jsjobs::$_data['filter']['jobstatus'] = $jobstatus;

        if ($gender != '')
            if (is_numeric($gender) == false)
                return false;
        if ($iamavailable != '')
            if (is_numeric($iamavailable) == false)
                return false;
        if ($jobcategory != '')
            if (is_numeric($jobcategory) == false)
                return false;
        if ($jobtype != '')
            if (is_numeric($jobtype) == false)
                return false;
        if ($jobsalaryrange != '')
            if (is_numeric($jobsalaryrange) == false)
                return false;
        if ($education != '')
            if (is_numeric($education) == false)
                return false;

        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;
        if ($zipcode != '')
            if (is_numeric($zipcode) == false)
                return false;

        $wherequery = '';
        if ($title != '')
            $wherequery .= " AND resume.application_title LIKE '%" . jsjobslib::jsjobs_str_replace("'", "", $title) . "%'";
        if ($name != '') {
            $wherequery .= " AND (";
            $wherequery .= " LOWER(resume.first_name) LIKE '%" . $name . "%'";
            $wherequery .= " OR LOWER(resume.last_name) LIKE '%" . $name . "%'";
            $wherequery .= " OR LOWER(resume.middle_name) LIKE '%" . $name . "%'";
            $wherequery .= " )";
        }

        if ($nationality != '')
            $wherequery .= " AND resume.nationality = '" . $nationality . "'";
        if ($gender != '')
            $wherequery .= " AND resume.gender = " . $gender;
        if ($iamavailable != '')
            $wherequery .= " AND resume.iamavailable = " . $iamavailable;
        if ($jobcategory != '')
            $wherequery .= " AND resume.job_category = " . $jobcategory;
        if ($jobtype != '')
            $wherequery .= " AND resume.jobtype = " . $jobtype;
        if ($jobsalaryrange != '')
            $wherequery .= " AND resume.jobsalaryrange = " . $jobsalaryrange;
        if ($education != '')
            $wherequery .= " AND resume.heighestfinisheducation = " . $education;
        if(is_numeric($experience))
            $wherequery .= " AND resume.experienceid = " . $experience;
        if ($currency != '')
            $wherequery .= " AND resume.currencyid =" . $currency;
        if ($zipcode != '')
            $wherequery .= " AND resume.address_zipcode =" . $zipcode;

        //Pagination
        $query = "SELECT count(resume.id)
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id
                WHERE resume.status = 1 ";
        $query .= $wherequery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT resume.*, cat.cat_title, jobtype.title AS jobtypetitle
                , salary.rangestart, salary.rangeend , currency.symbol
                ,salarytype.title AS salarytype
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                LEFT JOIN  `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id ";
        $query .= "WHERE resume.status = 1 ";
        $query .= $wherequery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function rejectQueueAllResumesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        $result = $this->rejectQueueResumeModel($id);
        return $result;
    }

    function approveQueueAllResumesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        $result = $this->approveQueueResumeModel($id);
        return $result;
    }

    function rejectQueueResumeModel($id) {
        if (is_numeric($id) == false) return false;
        $row = JSJOBSincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['status'] = -1;
            if(!$row->store()){
                return JSJOBS_REJECT_ERROR;
            }
        }else{
            return JSJOBS_REJECT_ERROR;
        }
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); //3 for resume. 2 for resume approve or reject
        return JSJOBS_REJECTED;
    }

    function approveQueueResumeModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = JSJOBSincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['status'] = 1;
            if(!$row->store()){
                return JSJOBS_APPROVE_ERROR;
            }
        }else{
            return JSJOBS_APPROVE_ERROR;
        }
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); //3 for resume. 3 for resume approve or reject
        return JSJOBS_APPROVED;
    }

    function getResumes_Widget($resumetype, $noofresumes) {
        if ((!is_numeric($resumetype)) || ( !is_numeric($noofresumes)))
            return false;

        if ($resumetype == 1) { //newest
            $inquery = ' ORDER BY resume.created DESC ';
        } elseif ($resumetype == 2) { //top
            $inquery = ' ORDER BY resume.hits DESC ';
        } else {
            return '';
        }

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS resumealiasid ";
        $query = "SELECT resume.packageid,resume.id AS resumeid,
                $id, resume.application_title AS applicationtitle, CONCAT(resume.first_name,' ', resume.last_name) AS name
                , resume.gender, resume.iamavailable AS available, resume.photo, resume.heighestfinisheducation
                , exp.title AS experiencetitle, resume.created AS created , cat.cat_title, jobtype.title AS jobtypetitle,nationality.name AS nationalityname
                $alias,(SELECT address.address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address WHERE address.resumeid = resume.id LIMIT 1) AS city

                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS nationality ON nationality.id=resume.nationality
                WHERE resume.status = 1 ";
        $query .= $inquery;
        if ($noofresumes != -1)
            $query .=" LIMIT " . $noofresumes;

        $results = jsjobsdb::get_results($query);
        foreach ($results as $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        return $results;
    }

    private function isYoursResume($id, $uid) {
        if (!is_numeric($id))
            return false;
        if (current_user_can( 'manage_options' )){
            return true;
        }
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            $conflag = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_add_resume');
            if ($conflag == 1) {
                if (isset($_COOKIE['wp-jsjobs'])) {
                    $wp_jsjobs = sanitize_key(json_decode(jsjobslib::jsjobs_safe_decoding($_COOKIE['wp-jsjobs']),true));
                    if ($id == $wp_jsjobs['resumeid']) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $id . " AND uid = " . $uid;
        $result = jsjobsdb::get_var($query);
        if ($result == 0)
            return false;
        else
            return true;
    }

    function cancelResumeSectionAjax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $section = JSJOBSrequest::getVar('section');
        $data = JSJOBSrequest::get('post');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $data['uid'] = $uid;
        $resumeid = $data['resumeid'];
        $objectid = $data['sectionid'];
        if ($section != 'skills' && $section != 'resume' && $section != 'personal')
            if ($objectid)
                if (!is_numeric($objectid))
                    return false;
        $result = null;
        $resumelayout = JSJOBSincluder::getObjectClass('resumeformlayout');
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data[2][$field->section][$field->field] = $field->required;
        }
        switch ($section) {
            case 'addresses':
                if (is_numeric($objectid))
                    jsjobs::$_data[0]['address_section'][0] = $this->getResumeAddressSection($resumeid, $uid, $objectid);
                else
                    jsjobs::$_data[0]['address_section'][0] = '';
                $result = $resumelayout->getAddressesSection(0, 1);
                break;
            case 'institutes':
                if (is_numeric($objectid))
                    jsjobs::$_data[0]['institute_section'][0] = $this->getResumeInstituteSection($resumeid, $uid, $objectid);
                else
                    jsjobs::$_data[0]['institute_section'][0] = '';
                $result = $resumelayout->getEducationSection(0, 1);
                break;
            case 'employers':
                if (is_numeric($objectid))
                    jsjobs::$_data[0]['employer_section'][0] = $this->getResumeEmployerSection($resumeid, $uid, $objectid);
                else
                    jsjobs::$_data[0]['employer_section'][0] = '';
                $result = $resumelayout->getEmployerSection(0, 1);
                break;
            case 'references':
                if (is_numeric($objectid))
                    jsjobs::$_data[0]['reference_section'][0] = $this->getResumeReferenceSection($resumeid, $uid, $objectid);
                else
                    jsjobs::$_data[0]['reference_section'][0] = '';
                $result = $resumelayout->getReferenceSection(0, 1);
                break;
            case 'languages':
                if (is_numeric($objectid))
                    jsjobs::$_data[0]['language_section'][0] = $this->getResumeLanguageSection($resumeid, $uid, $objectid);
                else
                    jsjobs::$_data[0]['language_section'][0] = '';
                $result = $resumelayout->getLanguageSection(0, 1);
                break;
            case 'skills':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                $result = $resumelayout->getSkillSection(0, 1);
                break;
            case 'resume':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                $result = $resumelayout->getResumeSection(0, 1);
                break;
            case 'personal':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                jsjobs::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                jsjobs::$_data['resumecontactdetail'] = true;
                $result = $resumelayout->getPersonalTopSection(1, 0);
                $result .= '<div class="resume-section-title personal"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/personal-info.png" />' . __('Personal information', 'js-jobs') . '</div>';
                $result .= $resumelayout->getPersonalSection(0);
                break;
        }
        if ($section != 'skills' && $section != 'resume' && $section != 'personal') {
            $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
            $anchor = '<a class="add" data-section="' . $section . '"> + ' . __('Add New', 'js-jobs') . ' ' . __($section, 'js-jobs') . '</a>';
        } else {
            $canadd = 0;
            $anchor = '';
        }
        $array = json_encode(array('html' => $result, 'canadd' => $canadd, 'anchor' => $anchor));
        return $array;
    }

    function captchaValidate() {
        if (!is_user_logged_in()) {
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
            if ($config_array['resume_captcha'] == 1) {
                if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                    $gresponse = sanitize_text_field($_POST['g-recaptcha-response']);
                    $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                    if ($resp) {
                        return true;
                    } else {
                        jsjobs::$_data['google_captchaerror'] = __("Invalid captcha","js-jobs");
                        return false;
                    }

                } else { // own captcha
                    $captcha = new JSJOBScaptcha;
                    $result = $captcha->checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function getDataForParams($section, $data) {
        //custom field code start
        $userfieldforjob = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(3, $section);
        $params = array();
        foreach ($userfieldforjob AS $ufobj) {
            $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            if($vardata != ''){
                if($ufobj->userfieldtype == 'multiple'){
                    $vardata = jsjobslib::jsjobs_explode(',', $vardata[0]); // fixed index
                }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = jsjobslib::jsjobs_htmlspecialchars($vardata);
            }
        }
        if (!empty($params)) {
            $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            return $params;
        } else {
            return false;
        }
        //custom field code end
    }

    function saveResumeSectionAjax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $section = JSJOBSrequest::getVar('section');
        $data = JSJOBSrequest::get('post');
        if(!current_user_can('manage_options')){
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
            $data['uid'] = $uid;
        }else{
			$uid = $this->getUidByResumeId($data['resumeid']);
			$data['uid'] = $uid;
		}
        $resumeid = $data['resumeid'];
        $row = null;
        switch ($section) {
            case 'personal':
                $row = JSJOBSincluder::getJSTable('resume');
                $data['id'] = $resumeid;
                $params = $this->getDataForParams(1, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'addresses':
                $row = JSJOBSincluder::getJSTable('resumeaddress');
                $params = $this->getDataForParams(2, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'institutes':
                $row = JSJOBSincluder::getJSTable('resumeinstitute');
                $params = $this->getDataForParams(3, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'employers':
                $row = JSJOBSincluder::getJSTable('resumeemployer');
                $params = $this->getDataForParams(4, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'references':
                $row = JSJOBSincluder::getJSTable('resumereference');
                $params = $this->getDataForParams(7, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'languages':
                $row = JSJOBSincluder::getJSTable('resumelanguage');
                $params = $this->getDataForParams(8, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
        }
        if ($row != null) {
            if ($section == 'personal') { // b/c of form ajax loop we have to unset the photo field if no photo selected
                if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                    //empty here to make it simple to understand
                } else {
                    unset($data['photo']);
                }
                if (empty($data['id'])) {
                    $data['alias'] = jsjobslib::jsjobs_str_replace(' ', '-', $data['application_title']);
                    $data['created'] = date('Y-m-d H:i:s');
                    $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('empautoapprove');
                } else {
                    if(current_user_can('manage_options')){
                        $data['status'] = $data['status'];
                    }else{
                        $row = JSJOBSincluder::getJSTable('resume');
                        $row->load($data['id']);
                        $data['status'] = $row->status;
                    }
                }
                if(!empty($data['date_of_birth']))
                    $data['date_of_birth'] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data['date_of_birth']));
                if(!empty($data['date_start']))
                    $data['date_start'] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data['date_start']));
				$query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE field =  'searchable' AND fieldfor =3";
				$record = jsjobs::$_db->get_row($query);
				if($record->published == 0 AND is_user_logged_in()){
					$data['searchable'] = 1;
				}elseif($record->isvisitorpublished == 0){
					$data['searchable'] = 1;
				}
                if (!$this->captchaValidate()) {
                    JSJOBSMessages::setLayoutMessage(__('Incorrect Captcha code', 'js-jobs'), 'error',$this->getMessagekey());
                    $array = json_encode(array('html' => 'error'));
                    return $array;
                }
            }
            if (!$row->bind($data)) {
                return JSJOBS_SAVE_ERROR;
            }
            if (!$row->store()) {
                return JSJOBS_SAVE_ERROR;
            }
            $objectid = $row->id;
            if ($section == 'personal') {
                $resumeid = $row->id;
            }
            //Check for the resume photo && files upload
            if ($section == 'personal') {
                if (isset($_FILES['photo'])) {
                    $this->uploadPhoto($objectid);
                }
                if (isset($_FILES['resumefiles'])) {
                    $this->uploadResume($objectid);
                }
                // Save resumeid in session in case of visitor add resume is allowed
                if (JSJOBSincluder::getObjectClass('user')->isguest()) {
                    $visitor_can_add_resume = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_add_resume');
                    if ($visitor_can_add_resume == 1) {
                        $wp_jsjobs['resumeid'] = $resumeid;
                        $wp_jsjobs = json_encode( $wp_jsjobs );
                        jsjobslib::jsjobs_setcookie('wp-jsjobs' , $wp_jsjobs , 0 , COOKIEPATH);
                        if ( SITECOOKIEPATH != COOKIEPATH ){
                            jsjobslib::jsjobs_setcookie('wp-jsjobs' , $wp_jsjobs , 0 , SITECOOKIEPATH);
                        }
                    }
                }
                //Update credits log in case of new resume
                if ($data['resumeid'] == '') {
                    $actionid = $data['creditid'];
                }
            }
        } elseif ($section == 'skills') {
            $skills = JSJOBSrequest::getVar('skills');
// RESUME SKILL CUSTOM FIELD
            $params = $this->getDataForParams(5, $data);
            $pquery = "SELECT params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $parmsvar = jsjobs::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && !empty($parmsvar)) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && !empty($params)) {
                $params = json_decode($params);
            }
            $params = (object) array_merge((array) $params, (array) $parray);
            $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            $queryparams = " , params='" . $params . "' ";
//END
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_resume` SET skills='" . $skills . "' " . $queryparams . " WHERE id = $resumeid";
            jsjobs::$_db->query($query);
        } elseif ($section == 'resume') {
// RESUME SKILL CUSTOM FIELD
            $params = $this->getDataForParams(6, $data);
            $pquery = "SELECT params FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
            $parmsvar = jsjobs::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && !empty($parmsvar)) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && !empty($params)) {
                $params = json_decode($params);
            }
            $params = (object) array_merge((array) $params, (array) $parray);
            $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            $queryparams = " , params='" . $params . "' ";
//END
            $resume = JSJOBSrequest::getVar('resume');
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_resume` SET resume='" . $resume . "' " .$queryparams." WHERE id = $resumeid";
            jsjobs::$_db->query($query);
        }
        $result = null;
        $resumelayout = JSJOBSincluder::getObjectClass('resumeformlayout');
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data[2][$field->section][$field->field] = $field->required;
        }
        switch ($section) {
            case 'addresses':
                jsjobs::$_data[0]['address_section'][0] = $this->getResumeAddressSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getAddressesSection(0, 1);
                break;
            case 'institutes':
                jsjobs::$_data[0]['institute_section'][0] = $this->getResumeInstituteSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getEducationSection(0, 1);
                break;
            case 'employers':
                jsjobs::$_data[0]['employer_section'][0] = $this->getResumeEmployerSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getEmployerSection(0, 1);
                break;
            case 'references':
                jsjobs::$_data[0]['reference_section'][0] = $this->getResumeReferenceSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getReferenceSection(0, 1);
                break;
            case 'languages':
                jsjobs::$_data[0]['language_section'][0] = $this->getResumeLanguageSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getLanguageSection(0, 1);
                break;
            case 'skills':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                $result = $resumelayout->getSkillSection(0, 1);
                break;
            case 'resume':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                $result = $resumelayout->getResumeSection(0, 1);
                break;
            case 'personal':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                jsjobs::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                jsjobs::$_data['resumecontactdetail'] = true;
                $result = $resumelayout->getPersonalTopSection(1, 0);
                $result .= '<div class="resume-section-title personal"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/personal-info.png" />' . __('Personal information', 'js-jobs') . '</div>';
                $result .= $resumelayout->getPersonalSection(0);
                break;
        }
        if ($section != 'skills' && $section != 'resume' && $section != 'personal') {
            $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
            $anchor = '<a class="add" data-section="' . $section . '"> + ' . __('Add New', 'js-jobs') . ' ' . __($section, 'js-jobs') . '</a>';
        } else {
            $canadd = 0;
            $anchor = '';
        }
        //send email

        if($section == 'personal' && empty($data['id'])){
            JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3,1,$resumeid); // 3 for resume,1 for add new resume
        }
        $array = json_encode(array('html' => $result, 'canadd' => $canadd, 'anchor' => $anchor, 'resumeid' => $resumeid));
        return $array;
    }

    function deleteResumeSectionAjax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $section = JSJOBSrequest::getVar('section');
        $data = JSJOBSrequest::get('post');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $data['uid'] = $uid;
        $resumeid = $data['resumeid'];
        $row = null;
        switch ($section) {
            case 'languages':
                $row = JSJOBSincluder::getJSTable('resumelanguage');
                break;
            case 'references':
                $row = JSJOBSincluder::getJSTable('resumereference');
                break;
            case 'employers':
                $row = JSJOBSincluder::getJSTable('resumeemployer');
                break;
            case 'institutes':
                $row = JSJOBSincluder::getJSTable('resumeinstitute');
                break;
            case 'addresses':
                $row = JSJOBSincluder::getJSTable('resumeaddress');
                break;
        }
        $msg = __('Section has been deleted', 'js-jobs');
        $result = 1;
        if ($this->isYoursResume($resumeid, $uid)) {
            if (!$row->delete($data['sectionid'])) {
                $msg = __('Error deleting section', 'js-jobs');
                $result = 0;
            }
        }
        $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
        $anchor = '<a class="add" data-section="' . $section . '"> + ' . __('Add New', 'js-jobs') . ' ' . __($section, 'js-jobs') . '</a>';
        $array = json_encode(array('canadd' => $canadd, 'msg' => $msg, 'result' => $result, 'anchor' => $anchor));
        return $array;
    }

    function canAddMoreSection($uid, $resumeid, $section) {
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($uid))
            return false;
        switch ($section) {
            case 'languages':
                $tablename = 'js_job_resumelanguages';
                $count = $config_array['max_resume_languages'];
                break;
            case 'references':
                $tablename = 'js_job_resumereferences';
                $count = $config_array['max_resume_references'];
                break;
            case 'employers':
                $tablename = 'js_job_resumeemployers';
                $count = $config_array['max_resume_employers'];
                break;
            case 'institutes':
                $tablename = 'js_job_resumeinstitutes';
                $count = $config_array['max_resume_institutes'];
                break;
            case 'addresses':
                $tablename = 'js_job_resumeaddresses';
                $count = $config_array['max_resume_addresses'];
                break;
        }
        $query = "SELECT COUNT(sec.id)
                    FROM `" . jsjobs::$_db->prefix . $tablename . "` AS sec
                    JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = sec.resumeid
                    WHERE sec.resumeid = " . $resumeid;
        $visallowed = 0;
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            if ($config_array['visitor_can_add_resume'] == 1) {
                $visallowed = 1;
            }
        }
        if ($uid && $visallowed = 0) {
            $query .= " AND resume.uid = " . $uid;
        }
        $total = jsjobs::$_db->get_var($query);
        if ($count > $total) {
            return 1;
        } else {
            return 0;
        }
    }

    function getResumeSectionAjax() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $section = JSJOBSrequest::getVar('section');
        $sectionid = JSJOBSrequest::getVar('sectionid');
        $resumeid = JSJOBSrequest::getVar('resumeid');
        $resumelayout = JSJOBSincluder::getObjectClass('resumeformlayout');
        $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        jsjobs::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            jsjobs::$_data[2][$field->section][$field->field] = $field->required;
        }

        $data = '';
        switch ($section) {
            case 'addresses':
                jsjobs::$_data[0]['address_section'] = $this->getResumeAddressSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getAddressesSection(1, 1);
                break;
            case 'institutes':
                jsjobs::$_data[0]['institute_section'] = $this->getResumeInstituteSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getEducationSection(1, 1);
                break;
            case 'employers':
                jsjobs::$_data[0]['employer_section'] = $this->getResumeEmployerSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getEmployerSection(1, 1);
                break;
            case 'references':
                jsjobs::$_data[0]['reference_section'] = $this->getResumeReferenceSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getReferenceSection(1, 1);
                break;
            case 'languages':
                jsjobs::$_data[0]['language_section'] = $this->getResumeLanguageSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getLanguageSection(1, 1);
                break;
            case 'resume':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getResumeSection(1, 1);
                break;
            case 'skills':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getSkillSection(1, 1);
                break;
            case 'personal':
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                jsjobs::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                $data = $resumelayout->getPersonalSection(1);
                break;
        }
        return $data;
    }

    private function getUidByResumeId($id) {
        if (!is_numeric($id)) return false;
        $query = "SELECT uid FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $id;
        $uid = jsjobs::$_db->get_var($query);
        return $uid;
    }

    function getResumeById($id) {
        if (JSJOBSincluder::getObjectClass('user')->isemployer() || current_user_can( 'manage_options' )) { // Current user is employer
            $uid = $this->getUidByResumeId($id);
        } else {
	    $userobject = JSJOBSincluder::getObjectClass('user');
	    if($userobject->isguest() || !$userobject->isJSJOBSUser()){
		$uid = $this->getUidByResumeId($id);
	     }else{
		$uid = $userobject->uid();
	     }
        }
        // visitor job apply job data

        if(isset($_COOKIE['jsjobs_apply_visitor']) && is_numeric($_COOKIE['jsjobs_apply_visitor'])){
            $query = "SELECT job.id as jobid,job.title,job.params
                        ,company.logofilename,job.title AS jobtitle
                        ,company.name AS companyname,company.id AS companyid
                        ,rstart.rangestart,rend.rangeend,srtype.title AS rangetype,currency.symbol,job.city,cat.cat_title
                        ,LOWER(jobtype.title) AS jobtypetitle,job.created,job.noofjobs
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS rstart ON rstart.id = job.salaryrangefrom
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS rend ON rend.id = job.salaryrangeto
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS srtype ON srtype.id = job.salaryrangetype
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                        WHERE job.id = " . sanitize_key($_COOKIE['jsjobs_apply_visitor']);
            jsjobs::$_data['jobinfo'] = jsjobsdb::get_row($query);
            if(jsjobs::$_data['jobinfo'] != ''){
                jsjobs::$_data['jobinfo']->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data['jobinfo']->city);
            }
        }
        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
            // $guestallowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_can_add_resume'); old code// problem
	    $guestallowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_viewresume');
            if ($guestallowed == 0)
                return false;
        }else {
            if($uid)
            if (is_numeric($uid) == false)
                return false;
        }
            if (($id != '') && ($id != 0)) {
                if (is_numeric($id) == false)
                    return false;
                global $job_manager_options;
                // getting personal section
                jsjobs::$_data[0]['personal_section'] = $this->getResumePersonalSection($id, $uid);
                // getting address section
                jsjobs::$_data[0]['address_section'] = $this->getResumeAddressSection($id, $uid);
                // getting employer section
                jsjobs::$_data[0]['employer_section'] = $this->getResumeEmployerSection($id, $uid);
                // getting institutes section
                jsjobs::$_data[0]['institute_section'] = $this->getResumeInstituteSection($id, $uid);
                // getting languages section
                jsjobs::$_data[0]['language_section'] = $this->getResumeLanguageSection($id, $uid);
                // getting reference section
                jsjobs::$_data[0]['reference_section'] = $this->getResumeReferenceSection($id, $uid);
                // getting file section
                jsjobs::$_data[0]['file_section'] = $this->getResumeFilesSection($id, $uid);
                $theme = wp_get_theme();
            $layout = JSJOBSrequest::getVar('jsjobslt');
            $finalresume = array();
            if($layout == 'viewresume' && !is_admin()){
                if(jsjobs::$theme_chk != 0){
                    // Related Resumes data
                    $max = $job_manager_options['maximum_relatedresume'];
                    $finalresume = array();
                    $relatedresume=array();
                    $layout =JSJOBSrequest::getVar("jsjobslt");
                    if ($layout != 'printresume') {
                        //var_dump($job_manager_options['relatedresume_criteria_sorter']['enabled']);
                        foreach($job_manager_options['relatedresume_criteria_sorter']['enabled'] AS $key => $value){
                            $inquery = '';
                            switch($key){
                                case 'category':
                                    if(jsjobs::$_data[0]['personal_section']->job_category != ''){

                                        $inquery = ' resume.job_category = ' . jsjobs::$_data[0]['personal_section']->job_category;
                                    }
                                break;
                                case 'heducation':
                                    if(jsjobs::$_data[0]['personal_section']->heighestfinisheducation != ''){
                                        $inquery = ' resume.heighestfinisheducation = ' . jsjobs::$_data[0]['personal_section']->heighestfinisheducation;
                                    }
                                break;
                                case 'experience':
                                    if(jsjobs::$_data[0]['personal_section']->experienceid != ''){
                                        $inquery = ' resume.experienceid = ' . jsjobs::$_data[0]['personal_section']->experienceid;
                                    }
                                break;
                            }
                            if(!empty($inquery)){
                                $query = "SELECT resume.id,resume.uid,resume.application_title, resume.first_name, resume.last_name, resume.middle_name,resume.photo,resume.job_category, resume.jobsalaryrangestart,resume.jobsalaryrangeend
                                        ,resume.jobsalaryrangetype, resume.currencyid, resume.dcurrencyid , resume.desiredsalarystart, resume.desiredsalaryend, resume.djobsalaryrangetype, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle, salstart.rangestart AS rangestart, salend.rangeend AS rangeend, resume.jobtype
                                        ,saltype.title AS rangetype, dsalstart.rangestart AS drangestart, dsalend.rangeend AS drangeend, dsaltype.title AS drangetype, currency.symbol AS symbol, dcurrency.symbol AS dsymbol,highestfinisheducation.title AS highestfinisheducation, exp.title AS total_experience
                                        ,resume.params,resume.status,resume.created,LOWER(jobtype.title) AS jobtypetit
                                        ,resumeaddress.address_city, resumeaddress.address, resumeaddress.address_zipcode, resumeaddress.longitude, resumeaddress.latitude
                                        ,city.cityName AS cityname, state.name AS statename, country.name AS countryname ,resumeaddress.params

                                        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                                        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = resume.job_category
                                        JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salstart ON salstart.id = resume.jobsalaryrangestart
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salend ON salend.id = resume.jobsalaryrangeend
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS saltype ON saltype.id = resume.jobsalaryrangetype
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalstart ON dsalstart.id = resume.desiredsalarystart
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalend ON dsalend.id = resume.desiredsalaryend
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS dsaltype ON dsaltype.id = resume.djobsalaryrangetype
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS dcurrency ON dcurrency.id = resume.dcurrencyid
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS highestfinisheducation ON highestfinisheducation.id = resume.heighestfinisheducation
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS resumeaddress ON resumeaddress.resumeid = resume.id
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = resumeaddress.address_city
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                                        WHERE 1=1 AND ".$inquery." AND resume.id != $id GROUP BY resume.id LIMIT ".$max;
                                        $result = jsjobsdb::get_results($query);
                                        $relatedresume = array_merge($relatedresume, $result);
                                        $relatedresume = array_map('unserialize', array_unique(array_map('serialize', $relatedresume)));
                                        if(COUNT($relatedresume) >= $max){
                                            break;
                                        }
                            }
                        }
                    }
                    if(!empty($relatedresume)){
                        foreach ($relatedresume AS $d) {
                            $d->location = JSJOBSincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
                            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->rangetype);
                            $finalresume[] = $d;
                        }
                    }
                    jsjobs::$_data['relatedresume'] = $finalresume;
                }
                jsjobs::$_data['relatedresume'] = $finalresume;
            }
        }
        jsjobs::$_data['resumecontactdetail'] = true;
        return;
    }

    function getResumePersonalSection($id, $uid) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        $query = "SELECT resume.id,resume.driving_license,resume.facebook,resume.googleplus,resume.linkedin,resume.twitter, resume.tags AS viewtags , resume.tags AS resumetags ,resume.license_no,licensecountry.name AS licensecountryname,resume.license_country,resume.videotype,resume.uid,resume.application_title, resume.first_name, resume.last_name, resume.middle_name, resume.cell, resume.email_address, resume.nationality AS nationalityid, resume.photo, resume.gender, resume.job_category, resume.heighestfinisheducation, resume.experienceid, resume.home_phone, resume.work_phone, resume.date_of_birth, resume.jobsalaryrangestart,resume.jobsalaryrangeend
                    , resume.jobsalaryrangetype, resume.currencyid, resume.dcurrencyid , resume.desiredsalarystart, resume.desiredsalaryend, resume.djobsalaryrangetype, resume.skills, resume.video, resume.keywords, resume.searchable, resume.iamavailable, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle, salstart.rangestart AS rangestart, salend.rangeend AS rangeend, resume.date_start,resume.jobtype
                    , resume.resume, saltype.title AS rangetype, dsalstart.rangestart AS drangestart, dsalend.rangeend AS drangeend, dsaltype.title AS drangetype, currency.symbol AS symbol, dcurrency.symbol AS dsymbol,nationality.name AS nationality, highestfinisheducation.title AS highestfinisheducation, exp.title AS total_experience
                    ,resume.params,resume.status,resume.created,LOWER(jobtype.title) AS jobtypetit
                    FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                    JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = resume.job_category
                    JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salstart ON salstart.id = resume.jobsalaryrangestart
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salend ON salend.id = resume.jobsalaryrangeend
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS saltype ON saltype.id = resume.jobsalaryrangetype
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalstart ON dsalstart.id = resume.desiredsalarystart
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS dsalend ON dsalend.id = resume.desiredsalaryend
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS dsaltype ON dsaltype.id = resume.djobsalaryrangetype
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS dcurrency ON dcurrency.id = resume.dcurrencyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS nationality ON nationality.id = resume.nationality
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS licensecountry ON licensecountry.id = resume.license_country
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS highestfinisheducation ON highestfinisheducation.id = resume.heighestfinisheducation
                    WHERE resume.id = " . $id;

        $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
        $isjsjobsuser = JSJOBSincluder::getObjectClass('user')->isJSJOBSUser();
        if(!$isguest && $isjsjobsuser){
            if (!current_user_can( 'manage_options' ) && $uid) {
                $query .= " AND resume.uid  = " . $uid;
            }
        }
        $result = jsjobsdb::get_row($query);
        if(!empty($result)){
            $result->resumetags = JSJOBSincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($result->resumetags);
            $result->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($result->symbol, $result->rangestart, $result->rangeend, $result->rangetype);
            $result->dsalary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($result->dsymbol, $result->drangestart, $result->drangeend, $result->drangetype);
        }
        return $result;
    }

    function getResumeAddressSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT resumeaddress.id, resumeaddress.address_city, resumeaddress.address, resumeaddress.address_zipcode, resumeaddress.longitude, resumeaddress.latitude
                        , city.cityName AS cityname, state.name AS statename, country.name AS countryname ,resumeaddress.params
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` resumeaddress
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = resumeaddress.address_city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    WHERE resumeaddress.resumeid = " . $id;
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND resumeaddress.id = ' . $sectionid;
            $result = jsjobsdb::get_row($query);
        }else {
            $result = jsjobsdb::get_results($query);
        }

        return $result;
    }

    function getResumeEmployerSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT employer.id, employer.employer, employer.employer_position, employer.employer_resp, employer.employer_pay_upon_leaving, employer.employer_supervisor, employer.employer_from_date, employer.employer_to_date, employer.employer_leave_reason, employer.employer_city
                    , employer.employer_zip, employer.employer_phone, employer.employer_address
                    , city.cityName AS cityname, state.name AS statename, country.name AS countryname ,employer.params
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS employer
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = employer.employer_city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    WHERE employer.resumeid = " . $id;
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND employer.id = ' . $sectionid;
            $result = jsjobsdb::get_row($query);
        }else {
            $result = jsjobsdb::get_results($query);
        }
        return $result;
    }

    function getResumeInstituteSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT institute.id, institute.institute, institute.institute_address, institute.institute_city, institute.institute_certificate_name, institute.institute_study_area
                    , city.cityName AS cityname, state.name AS statename, country.name AS countryname
                    , institute.fromdate, institute.todate, institute.iscontinue,institute.params
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS institute
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = institute.institute_city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    WHERE institute.resumeid = " . $id;
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND institute.id = ' . $sectionid;
            $result = jsjobsdb::get_row($query);
        }else {
            $result = jsjobsdb::get_results($query);
        }
        return $result;
    }

    function getResumeLanguageSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT *
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages`
                    WHERE resumeid = " . $id;
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND id = ' . $sectionid;
            $result = jsjobsdb::get_row($query);
        }else {
            $result = jsjobsdb::get_results($query);
        }
        return $result;
    }

    function getResumeReferenceSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT ref.id, ref.reference, ref.reference_name, ref.reference_city, ref.reference_zipcode, ref.reference_address, ref.reference_phone, ref.reference_relation, ref.reference_years
                    , city.cityName AS cityname, state.name AS statename, country.name AS countryname ,ref.params
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS ref
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = ref.reference_city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    WHERE ref.resumeid = " . $id;
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND ref.id = ' . $sectionid;
            $result = jsjobsdb::get_row($query);
        }else {
            $result = jsjobsdb::get_results($query);
        }
        return $result;
    }

    function getResumeFilesSection($id, $uid) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT *
                    FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles`
                    WHERE resumeid = " . $id;
        $result = jsjobsdb::get_results($query);
        return $result;
    }


    function getResumeBySection1($resumeid, $sectionName, $section) { // created and used by muhiaudin for resume form
        if (!is_numeric($resumeid) OR empty($resumeid)) {
            return false;
        }
        if (empty($sectionName)) {
            return false;
        }
        $query = "";
        if ($resumeid == -1) { // in case of new form
            $result[0] = null;
        } else {
            if ($sectionName == 'personal') {
                $query = "SELECT resume.*, cat.cat_title AS categorytitle
                            ,salary.rangestart AS rangestart, salary.rangeend AS rangeend
                            ,jobtype.title AS jobtypetitle
                            ,heighesteducation.title AS heighesteducationtitle
                            ,nationality_country.name AS nationalitycountry
                            ,currency.symbol AS symbol
                            ,salarytype.title AS salarytype
                            FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id
                            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON resume.currencyid = currency.id
                            WHERE resume.id = " . $resumeid;
                $result[0] = jsjobsdb::get_results($query);
            } elseif ($sectionName == 'skills' OR $sectionName == 'editor') {
                $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $resumeid;
                $result[0] = jsjobsdb::get_results($query);
            } else {
                if ($sectionName == 'language') {
                    $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages` WHERE resumeid  = " . $resumeid;
                    $total = $db->loadResult();
                    $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages` WHERE resumeid = " . $resumeid;
                } elseif ($sectionName == 'address') {
                    $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid  = " . $resumeid;
                    $total = $db->loadResult();
                    $query = "SELECT address.*,
                                cities.id AS cityid,
                                cities.cityName AS city,
                                states.name AS state,
                                countries.name AS country
                                FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON address.address_city = cities.id
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id
                                WHERE address.resumeid = " . $resumeid;
                } else {
                    $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume" . $sectionName . "s` WHERE resumeid  = " . $resumeid;
                    $total = $db->loadResult();
                    $query = "SELECT " . $sectionName . ".*,
                                cities.id AS cityid,
                                cities.cityName AS city,
                                states.name AS state,
                                countries.name AS country
                                FROM `" . jsjobs::$_db->prefix . "js_job_resume" . $sectionName . "s` AS " . $sectionName . "
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS cities ON " . $sectionName . "." . $sectionName . "_city = cities.id
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS states ON cities.stateid = states.id
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS countries ON cities.countryid = countries.id
                                WHERE " . $sectionName . ".resumeid = " . $resumeid;
                }
                $result[0] = $db->loadObjectList();
                $result[2] = $total;
            }
        }
        //$result[1] = JSJOBSincluder::getJSModel('customfield')->getResumeFieldsOrderingBySection($section); // 1 for personal section
        return $result;
    }

    function getResumeFiles() {
        $resumeid = (int) JSJOBSrequest::getVar('resumeid');
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigValue('data_directory');
        $files = array();
        $totalFilesQry = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE resumeid=" . $resumeid;
        $filesFound = jsjobsdb::get_results($totalFilesQry);
        if ($filesFound > 0) {
            $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE resumeid = " . $resumeid;
            $files = jsjobsdb::get_results($query);
        }
        // resume form layout class
        include_once(JSJOBS_PLUGIN_PATH . '/includes/resumeformlayout.php');
        $resumeformlayout = new JSJobsResumeformlayout();
        $data = $resumeformlayout->getResumeFilesLayout($files, $data_directory);
        return $data;
    }

    function uploadResume($id) {
        if (is_numeric($id) == false)
            return false;
        JSJOBSincluder::getObjectClass('uploads')->uploadResumeFiles($id);
        return;
    }

    function uploadPhoto($id) {
        if (is_numeric($id) == false)
            return false;
        JSJOBSincluder::getObjectClass('uploads')->uploadResumePhoto($id);
        return;
    }

    function deleteResume($ids) {
        if (empty($ids))
            return false;
        $notdeleted = 0;
        $row = JSJOBSincluder::getJSTable('resume');
        foreach ($ids as $id) {
            if ($this->resumeCanDelete($id) == true) {
                //code for preparing data for delete resume email
                $resultforsendmail = JSJOBSincluder::getJSModel('resume')->getResumeInfoForEmail($id);
                $username = $resultforsendmail->firstname . '' . $resultforsendmail->middlename . '' . $resultforsendmail->lastname;
                if ($username == '') {
                    $username = $resultforsendmail->username;
                }
                $email = $resultforsendmail->useremailfromresume;
                if ($email == '') {
                    $email = $resultforsendmail->useremail;
                }
                $resumetitle = $resultforsendmail->resumetitle;

                $mailextradata['resumetitle'] = $resumetitle;
                $mailextradata['jobseekername'] = $username;
                $mailextradata['useremail'] = $email;

                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = " . $id;
                jsjobsdb::query($query);
                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` WHERE resumeid = " . $id;
                jsjobsdb::query($query);

                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE resumeid = " . $id;
                jsjobsdb::query($query);
                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` WHERE resumeid = " . $id;
                jsjobsdb::query($query);

                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumelanguages` WHERE resumeid = " . $id;
                jsjobsdb::query($query);

                $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` WHERE resumeid = " . $id;
                jsjobsdb::query($query);
                $wpdir = wp_upload_dir();
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id."/resume/*.*"));//deleting files
                array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id."/photo/*.*"));//deleting files
                @rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/resume');
                @rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/photo');
                @rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id);
                JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, 6, $id,$mailextradata); // 3 for resume,6 for DELETE resume
            }else{
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSJOBSMessages::$counter = false;
            return JSJOBS_DELETED;
        } else {
            JSJOBSMessages::$counter = $notdeleted;
            return JSJOBS_DELETE_ERROR;
        }
    }

    function resumeCanDelete($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        if(!is_admin()){
            if(!$this->getIfResumeOwner($resumeid)){
                return false;
            }
        }
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE cvid = " . $resumeid;
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function resumeEnforceDelete($resumeid, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($resumeid) == false)
            return false;

        $juid = 0; // jobseeker uid
        $query = "DELETE  resume,apply,resumeaddress,resumeemployers,resumefiles
                            ,resumeinstitutes,resumelanguages,resumereferences
                    FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS apply ON resume.id=apply.cvid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS resumeaddress ON resume.id=resumeaddress.resumeid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS resumeemployers ON resume.id=resumeemployers.resumeid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumefiles` AS resumefiles ON resume.id=resumefiles.resumeid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS resumeinstitutes ON resume.id=resumeinstitutes.resumeid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumelanguages` AS resumelanguages ON resume.id=resumelanguages.resumeid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS resumereferences ON resume.id=resumereferences.resumeid
                    WHERE resume.id = " . $resumeid;
            //code for preparing data for delete resume email
                $resultforsendmail = JSJOBSincluder::getJSModel('resume')->getResumeInfoForEmail($resumeid);
                $username = $resultforsendmail->firstname . '' . $resultforsendmail->middlename . '' . $resultforsendmail->lastname;
                if ($username == '') {
                    $username = $resultforsendmail->username;
                }
                $email = $resultforsendmail->useremailfromresume;
                if ($email == '') {
                    $email = $resultforsendmail->useremail;
                }
                $resumetitle = $resultforsendmail->resumetitle;

                $mailextradata['resumetitle'] = $resumetitle;
                $mailextradata['jobseekername'] = $username;
                $mailextradata['useremail'] = $email;

        if (!jsjobsdb::query($query)) {
            return JSJOBS_DELETE_ERROR; //error while delete resume
        }

        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        array_map('unlink', glob($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid."/resume/*.*"));//deleting files
        array_map('unlink', glob($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid."/photo/*.*"));//deleting files
        if(is_dir($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid.'/resume')){
            rmdir($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid.'/resume');
        }
        if(is_dir($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid.'/photo')){
            rmdir($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid.'/photo');
        }
        @rmdir($wpdir['basedir'] .'/'. $data_directory . "/data/jobseeker/resume_".$resumeid);

        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, 6, $resumeid,$mailextradata); // 3 for resume,6 for DELETE resume
        return JSJOBS_DELETED;
    }

    function getResumeInfoForEmail($resumeid) {
        if ((is_numeric($resumeid) == false))
            return false;
        $query = 'SELECT resume.application_title AS resumetitle, CONCAT(resume.first_name," ",resume.last_name) AS username
                        ,resume.email_address AS useremailfromresume
                        ,resume.first_name AS firstname, resume.last_name AS lastname, resume.middle_name AS middlename
                        , resume.email_address AS useremail
                        FROM `' . jsjobs::$_db->prefix . 'js_job_resume` AS resume
                        WHERE resume.id = '.$resumeid;
        $return_value = jsjobsdb::get_row($query);
        return $return_value;
    }

    function empappReject($app_id) {
        if (is_numeric($app_id) == false)
            return false;

        $row = JSJOBSincluder::getJSTable('resume');
        if(! $row->update(array('id' => $app_id , 'status' => -1))){
            return JSJOBS_DELETE_ERROR;
        }

        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, -1, $app_id);
        return JSJOBS_REJECTED;
    }

    function getResumeByCategory() {
        $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid,category.id AS categoryid
            ,(SELECT count(resume.id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                where resume.job_category = category.id AND resume.status = 1 AND resume.searchable = 1 )  AS totaljobs
            FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
            WHERE category.isactive = 1 AND category.parentid = 0 ORDER BY category.ordering ASC";
        $categories = jsjobsdb::get_results($query);
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = $config_array['subcategory_limit'];
        foreach($categories AS $category){
            $total = 0;
            $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                ,(SELECT count(resume.id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                where resume.job_category = category.id AND resume.status = 1 AND resume.searchable = 1 )  AS totaljobs
                FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
                WHERE category.isactive = 1 AND category.parentid = ".$category->categoryid." ORDER BY category.ordering ASC ";
            $subcats = jsjobs::$_db->get_results($query);
            $i = 0;
            foreach ($subcats as $id => $scat) {
                $total += $scat->totaljobs;
                if($subcategory_limit <= $i){
                    unset($subcats[$id]);
                }
                $i++;
            }
            $category->subcat = $subcats;
            $category->total_sub_jobs = $total;
        }

        if(jsjobs::$_configuration['job_resume_show_all_categories'] == 0){//configuration based
            $final_arr = array();
            foreach ($categories as $job_category) {
                if($job_category->totaljobs != 0 || $job_category->total_sub_jobs != 0){
                    $final_arr[] = $job_category;
                }
            }
            $categories = $final_arr;
        }
        jsjobs::$_data[0] = $categories;
        jsjobs::$_data['config'] =  JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        return;
    }

    function getMyResumes($uid) {
        if (!is_numeric($uid))
            return false;
        $this->getOrdering();
        $query = "SELECT COUNT(resume.id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category
                WHERE resume.uid =" . $uid;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total, 'myresumes');

        $query = "SELECT resume.id,resume.first_name,resume.last_name,resume.application_title,resume.email_address,category.cat_title,resume.experienceid,resume.created,jobtype.title AS jobtypetitle,resume.photo,
                resume.status,salaryrangestart.rangestart,salaryrangeend.rangeend,salaryrangetype.title AS rangetype, currency.symbol,city.cityName AS cityname,state.name AS statename,country.name AS countryname
                ,exp.title AS total_experience,resume.params,resume.last_modified,LOWER(jobtype.title) AS jobtypetit
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangestart ON salaryrangestart.id = resume.desiredsalarystart
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeend ON salaryrangeend.id = resume.desiredsalaryend
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = resume.djobsalaryrangetype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.dcurrencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                WHERE resume.uid = " . $uid;
        $query.= " ORDER BY " . jsjobs::$_ordering;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobs::$_db->get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->rangetype);
            $d->location = JSJOBSincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
            $data[] = $d;
        }
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(3);
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        jsjobs::$_data['listingfields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsForListing(3);
        if(isset($_COOKIE['jsjobs_apply_visitor'])){
            jsjobslib::jsjobs_setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                jsjobslib::jsjobs_setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , SITECOOKIEPATH);
            }
        }
        return;
    }

    function canAddResume($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }
    function getResumeTitleById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT resume.application_title FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume WHERE resume.id = " . $id;
        $jobname = jsjobs::$_db->get_var($query);
        return $jobname;
    }

    function getResumes($vars) {
        $inquery = '';
        $jsformresumesearch = JSJOBSrequest::getVar('jsformresumesearch');
        if (isset($jsformresumesearch) AND $jsformresumesearch == 1) {
            jsjobs::$_data['issearchform'] = 1;
            jsjobs::$_data['filter'] =  array();
        }

        if (isset($vars['category']) AND $vars['category'] != '') {
            $categoryid = $vars['category'];
            if (!is_numeric($categoryid))
                return false;
            $js_query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE parentid = ". $categoryid;
            $js_cats = jsjobsdb::get_results($js_query);
            $js_ids = [];
            foreach ($js_cats as $js_cat) {
                $js_ids[] = $js_cat->id;
            }
            $js_ids[] = $categoryid;
            $js_ids = implode(",",$js_ids);
            $inquery = " AND resume.job_category IN(".$js_ids.")";
            jsjobs::$_data['filter']['category'] = $categoryid;
        }
        if (isset($vars['searchid']) AND $vars['searchid'] != '') {
            $search = $vars['searchid'];
            if (!is_numeric($search))
                return false;
            $inquery = $this->getSaveSearchForView($search);
            jsjobs::$_data['filter']['search'] = $search;
        }
        if (isset($vars['tags']) AND $vars['tags'] != '') {
            jsjobs::$_data['fromtags'] = $vars['tags'];
            $tags = $vars['tags'];
            $inquery = " AND resume.tags LIKE '%" . $tags . "%'";
            jsjobs::$_data['filter']['tags'] = $tags;
        }
        $this->getOrdering();
        //variables form search form
        $title = isset(jsjobs::$_search['resumes']['application_title']) ? jsjobs::$_search['resumes']['application_title'] : '';
        if ($title != '') {
            $inquery .= ' AND resume.application_title LIKE "%' . $title . '%" ';
            jsjobs::$_data['filter']['application_title'] = $title;
            jsjobs::$_data['issearchform'] = 1;
        }

        $firstName = isset(jsjobs::$_search['resumes']['first_name']) ? jsjobs::$_search['resumes']['first_name'] : '';
        if ($firstName != '') {
            $inquery .= ' AND resume.first_name LIKE "%' . $firstName . '%" ';
            jsjobs::$_data['filter']['application_title'] = $title;
            jsjobs::$_data['issearchform'] = 1;
            jsjobs::$_data['filter']['first_name'] = $firstName;
            jsjobs::$_data['issearchform'] = 1;
        }
        $middle_name = isset(jsjobs::$_search['resumes']['middle_name']) ? jsjobs::$_search['resumes']['middle_name'] : '';
        if ($middle_name != '') {
            $inquery .= ' AND resume.middle_name LIKE "%' . $middle_name . '%" ';
            jsjobs::$_data['filter']['middle_name'] = $middle_name;
            jsjobs::$_data['issearchform'] = 1;
        }

        $lastName = isset(jsjobs::$_search['resumes']['last_name']) ? jsjobs::$_search['resumes']['last_name'] : '';
        if ($lastName != '') {
            $inquery .= ' AND resume.last_name LIKE "%' . $lastName . '%" ';
            jsjobs::$_data['filter']['last_name'] = $lastName;
            jsjobs::$_data['issearchform'] = 1;
        }
        $nationality = isset(jsjobs::$_search['resumes']['nationality']) ? jsjobs::$_search['resumes']['nationality'] : '';
        if ($nationality != '') {
            $inquery .= ' AND resume.nationality =' . $nationality . '';
            jsjobs::$_data['filter']['nationality'] = $nationality;
            jsjobs::$_data['issearchform'] = 1;
        }
        $gender = isset(jsjobs::$_search['resumes']['gender']) ? jsjobs::$_search['resumes']['gender'] : '';
        if ($gender != '') {
            $inquery .= ' AND resume.gender LIKE "%' . $gender . '%" ';
            jsjobs::$_data['filter']['gender'] = $gender;
            jsjobs::$_data['issearchform'] = 1;
        }
        $salaryfixed = isset(jsjobs::$_search['resumes']['salaryfixed']) ? jsjobs::$_search['resumes']['salaryfixed'] : '';
        $jobType = isset(jsjobs::$_search['resumes']['jobtype']) ? jsjobs::$_search['resumes']['jobtype'] : '';
        if ($jobType != '') {
            $inquery .= ' AND resume.jobtype = ' . $jobType . ' ';
            jsjobs::$_data['filter']['jobtype'] = $jobType;
            jsjobs::$_data['issearchform'] = 1;
        }

        $currencyId = isset(jsjobs::$_search['resumes']['currencyid']) ? jsjobs::$_search['resumes']['currencyid'] : '';
        if ($currencyId != '') {
            $inquery .= ' AND resume.currencyid =' . $currencyId . ' ';
            jsjobs::$_data['filter']['currencyid'] = $currencyId;
            jsjobs::$_data['issearchform'] = 1;
        }
        $salaryRangeFrom = isset(jsjobs::$_search['resumes']['salaryrangefrom']) ? jsjobs::$_search['resumes']['salaryrangefrom'] : '';
        if ($salaryRangeFrom != '') {
            $inquery .= ' AND (SELECT rangestart  FROM  ' . jsjobs::$_db->prefix . 'js_job_salaryrange WHERE id = ' . $salaryRangeFrom . ' ) >= salaryrangestart.rangestart ';
            jsjobs::$_data['filter']['salaryrangestart'] = $salaryRangeFrom;
            jsjobs::$_data['issearchform'] = 1;
        }

        $salaryRangeend = isset(jsjobs::$_search['resumes']['salaryrangeend']) ? jsjobs::$_search['resumes']['salaryrangeend'] : '';
        if ($salaryRangeend != '') {
            $inquery .= ' AND (SELECT rangeend  FROM  ' . jsjobs::$_db->prefix . 'js_job_salaryrange WHERE id = ' . $salaryRangeend . ' ) <= salaryrangeend.rangeend ';
            jsjobs::$_data['filter']['salaryrangeend'] = $salaryRangeend;
            jsjobs::$_data['issearchform'] = 1;
        }

        $salaryRangeType = isset(jsjobs::$_search['resumes']['salaryrangetype']) ? jsjobs::$_search['resumes']['salaryrangetype'] : '';
        if ($salaryRangeType != '') {
            $inquery .= ' AND resume.jobsalaryrangetype = ' . $salaryRangeType . '  ';
            jsjobs::$_data['filter']['salaryrangetype'] = $salaryRangeType;
            jsjobs::$_data['issearchform'] = 1;
        }

        $highestEducation = isset(jsjobs::$_search['resumes']['highesteducation']) ? jsjobs::$_search['resumes']['highesteducation'] : '';
        if ($highestEducation != '') {
            $inquery .= ' AND resume.heighestfinisheducation = ' . $highestEducation . ' ';
            jsjobs::$_data['filter']['highesteducation'] = $highestEducation;
            jsjobs::$_data['issearchform'] = 1;
        }

        $totalExperience = isset(jsjobs::$_search['resumes']['experience']) ? jsjobs::$_search['resumes']['experience'] : '';
        if ($totalExperience != '') {
            $inquery .= ' AND resume.experienceid = ' . $totalExperience . ' ';
            jsjobs::$_data['filter']['experience'] = $totalExperience;
            jsjobs::$_data['issearchform'] = 1;
        }

        $category = isset(jsjobs::$_search['resumes']['category']) ? jsjobs::$_search['resumes']['category'] : '';
        if ($category != '' && is_numeric($category)) {
            $js_query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE parentid = ". $category;
            $js_cats = jsjobsdb::get_results($js_query);
            $js_ids = [];
            foreach ($js_cats as $js_cat) {
                $js_ids[] = $js_cat->id;
            }
            $js_ids[] = $category;
            $js_ids = implode(",",$js_ids);
            $inquery .= ' AND resume.job_category IN('.$js_ids.')';
            jsjobs::$_data['filter']['category'] = $category;
            jsjobs::$_data['issearchform'] = 1;
        }

        $zipCode = isset(jsjobs::$_search['resumes']['zipcode']) ? jsjobs::$_search['resumes']['zipcode'] : '';
        if ($zipCode) {
            jsjobs::$_data['filter']['zipcode'] = $zipCode;
        }

        $keywords = isset(jsjobs::$_search['resumes']['keywords']) ? jsjobs::$_search['resumes']['keywords'] : '';
        if ($keywords) {
            $res = $this->makeQueryFromArray('keywords', $keywords);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
            jsjobs::$_data['filter']['keywords'] = $keywords;
        }

        //Custom field search
        //start
        $data = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3);
        $valarray = array();
        if (!empty($data)) {
            foreach ($data as $uf) {
                $session_userfield = isset(jsjobs::$_search['resume_custom_fields'][$uf->field]) ? jsjobs::$_search['resume_custom_fields'][$uf->field] : '';
                $valarray[$uf->field] = $session_userfield;
                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null && $valarray[$uf->field] !="" ) {
                    switch ($uf->userfieldtype) {
                        case 'text':
                        case 'email':
                            $inquery .= ' AND resume.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'combo':
                            $inquery .= ' AND resume.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            $or = " OR ";
                            break;
                        case 'depandant_field':
                            $inquery .= ' AND resume.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'radio':
                            $inquery .= ' AND resume.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'checkbox':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND resume.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($finalvalue) . '.*"\' ';
                            break;
                        case 'date':
                            if (isset($valarray[$uf->field])) {
                                $valarray[$uf->field] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($valarray[$uf->field]));
                            }
                            $inquery .= ' AND resume.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'textarea':
                            $inquery .= ' AND resume.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'multiple':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                if($value){
                                    $finalvalue .= $value.'.*';
                                }
                            }
                            if($finalvalue){
                                $inquery .= ' AND resume.params REGEXP \'"' . $uf->field . '":"[^"]*'.jsjobslib::jsjobs_htmlspecialchars($finalvalue).'"\' ';
                            }
                            break;
                    }
                    jsjobs::$_data['filter']['params'] = $valarray;
                    jsjobs::$_data['issearchform'] = 1;
                }
            }
        }
        //end
        $tags = JSJOBSrequest::getVar('tags');
        if ($tags) {
            $res = $this->makeQueryFromArray('tags', $tags);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }

        $city = isset(jsjobs::$_search['resumes']['city']) ? jsjobs::$_search['resumes']['city'] : '';
        if ($city != '') {
            jsjobs::$_data['filter']['city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($city);
            $res = $this->makeQueryFromArray('city', $city);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if(isset(jsjobs::$_data['issearchform']) &&  jsjobs::$_data['issearchform'] == 1){
            $inquery .= ' AND resume.searchable = 1 ';
        }
        //Pagination
        $query = "SELECT COUNT(resume.id) AS total
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category ";
            if($zipCode != ''){
                $query .= " JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address1 ON (address1.resumeid = resume.id AND address1.address_zipcode = '".$zipCode."' ) ";
            }elseif ($city != '') {
                $query .= " JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
            }
            $query .= "
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangestart ON salaryrangestart.id = resume.jobsalaryrangestart
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeend ON salaryrangeend.id = resume.jobsalaryrangeend
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = resume.jobsalaryrangetype
                WHERE resume.status = 1 AND resume.searchable = 1 ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total, 'resumes');

        //Data
        $query = "SELECT resume.id,CONCAT(resume.alias,'-',resume.id) AS aliasid,resume.first_name
                ,resume.last_name,resume.application_title,resume.email_address,category.cat_title
                ,exp.title as total_experience,resume.created,jobtype.title AS jobtypetitle,resume.photo
                ,resume.status,salaryrangestart.rangestart
                ,salaryrangeend.rangeend,salaryrangetype.title AS rangetype, currency.symbol,city.cityName AS cityname
                ,state.name AS statename,country.name AS countryname,resume.params
                ,resume.last_modified,LOWER(jobtype.title) AS jobtypetit
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category ";
            if($zipCode != ''){
                $query .= " JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address1 ON (address1.resumeid = resume.id AND address1.address_zipcode = '".$zipCode."' ) ";
            }elseif ($city != '') {
                $query .= " JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
            }
            $query .= "
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangestart ON salaryrangestart.id = resume.jobsalaryrangestart
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeend ON salaryrangeend.id = resume.jobsalaryrangeend
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = resume.jobsalaryrangetype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.currencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                WHERE resume.status = 1 AND resume.searchable = 1 ";
        $query .= $inquery;
        $query .= " GROUP BY resume.id ";
        $query.= " ORDER BY " . jsjobs::$_ordering;
        $query .=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        $results = jsjobs::$_db->get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->rangetype);
            $d->location = JSJOBSincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;

        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('resume');
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(3);
        jsjobs::$_data['listingfields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsForListing(3);
        return;
    }

    private function makeQueryFromArray($for, $array) {
        if (empty($array))
            return false;
        $qa = array();
        switch ($for) {
            case 'keywords':
                $array = jsjobslib::jsjobs_explode(",", $array);
                $total = count($array);
                for ($i = 0; $i < $total; $i++) {
                    $qa[] = "resume.keywords LIKE '%" . jsjobslib::jsjobs_trim($array[$i]) . "%'";
                }
                break;
            case 'tags':
                $array = jsjobslib::jsjobs_explode(',', $array);
                foreach ($array as $item) {
                    $qa[] = "resume.tags LIKE '%" . $item . "%'";
                }
                break;
            case 'city':
                $array = jsjobslib::jsjobs_explode(',', $array);
                foreach ($array as $item) {
                    $qa[] = " address1.address_city = " . $item;
                }
                break;
        }
        $query = implode(" OR ", $qa);
        return $query;
    }

    function getAllResumeFiles() {
        $resumeid = JSJOBSrequest::getVar('resumeid');
        require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = JSJOBS_PLUGIN_PATH . $data_directory;
        if (!file_exists($path)) {
            JSJOBSincluder::getJSModel('common')->makeDir($path);
        }
        $path .= '/zipdownloads';
        if (!file_exists($path)) {
            JSJOBSincluder::getJSModel('common')->makeDir($path);
        }
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;
        if (!file_exists($path)) {
            JSJOBSincluder::getJSModel('common')->makeDir($path);
        }
        $archive = new PclZip($path . '/allresumefiles.zip');
        $wpdir = wp_upload_dir();
        $directory = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resumeid . '/resume/';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        $filelist = '';
        $query = "SELECT filename FROM `".jsjobs::$_db->prefix."js_job_resumefiles` WHERE resumeid = ".$resumeid;
        $files = jsjobs::$_db->get_results($query);
        foreach ($files AS $file) {
            $filelist .= $directory . '/' . $file->filename . ',';
        }
        $filelist = jsjobslib::jsjobs_substr($filelist, 0, jsjobslib::jsjobs_strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/allresumefiles.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . jsjobslib::jsjobs_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();//this was commented and causing problems
        flush();
        readfile($file);
        @unlink($file);
        $path = JSJOBS_PLUGIN_PATH . $data_directory;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        if(is_dir($path)){
            rmdir($path);
        }
        exit();
    }

    function getResumeFileDownloadById($fileid) {
        if (!is_numeric($fileid))
            return false;
        $query = "SELECT filename,resumeid FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE id = " . $fileid;
        $object = jsjobs::$_db->get_row($query);
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $file =  $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $object->resumeid . '/resume/' . $object->filename;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . jsjobslib::jsjobs_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
//        ob_clean();
        flush();
        readfile($file);
        exit();
    }

    function getRandomFolderName($path) {
        $match = '';
        do {
            $rndfoldername = "";
            $length = 5;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            $maxlength = jsjobslib::jsjobs_strlen($possible);
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            $i = 0;
            while ($i < $length) {
                $char = jsjobslib::jsjobs_substr($possible, mt_rand(0, $maxlength - 1), 1);
                if (!jsjobslib::jsjobs_strstr($rndfoldername, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $rndfoldername .= $char;
                            $i++;
                        }
                    } else {
                        $rndfoldername .= $char;
                        $i++;
                    }
                }
            }
            $folderexist = $path . '/' . $rndfoldername;
            if (file_exists($folderexist))
                $match = 'Y';
            else
                $match = 'N';
        }while ($match == 'Y');

        return $rndfoldername;
    }

    function getResumenameById($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        $query = "SELECT resume.application_title FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume WHERE resume.id = " . $resumeid;
        $resumename = jsjobs::$_db->get_var($query);
        return $resumename;
    }

    function addViewContactDetail($resumeid, $uid) {
        $profileid = 0;
        if(jsjobslib::jsjobs_strstr($resumeid, 'jssc-')){
            $array = jsjobslib::jsjobs_explode('-', $resumeid);
            $profileid = $array[1];
            $resumeid = 0;
        }
        if (!is_numeric($profileid))
            return false;
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($uid))
            return false;
        $curdate = date('Y-m-d H:i:s');

        $row = JSJOBSincluder::getJSTable('employerviewresume');

        $data = array();
        $data['uid'] = $uid;
        $data['resumeid'] = $resumeid;
        $data['status'] = 1;
        $data['created'] = $curdate;
        $data['profileid'] = $profileid;

        if (!$row->bind($data)) {
            return false;
        }

        if($row->store()){
            return true;
        }else{
            return false;
        }
    }

    function getOrdering() {
        $sort = JSJOBSrequest::getVar('sortby', '', null);
        if ($sort == null) {
            $id = JSJOBSrequest::getVar('jsjobsid');
            if ($id != null) {
                $array = jsjobslib::jsjobs_explode('_', $id);
                if ($array[1] == '14') {
                    $sort = $array[0];
                }
            }
        }else{
            $array = jsjobslib::jsjobs_explode('_', $sort);
            if (isset($array[1]) && $array[1] == '14') {
                $sort = $array[0];
            }
        }
        if ($sort == null) {
            $sort = 'posteddesc';
        }

        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "titledesc":
                jsjobs::$_ordering = "resume.application_title DESC";
                jsjobs::$_sorton = "title";
                jsjobs::$_sortorder = "DESC";
                break;
            case "titleasc":
                jsjobs::$_ordering = "resume.application_title ASC";
                jsjobs::$_sorton = "title";
                jsjobs::$_sortorder = "ASC";
                break;
            case "jobtypedesc":
                jsjobs::$_ordering = "jobtype.title DESC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "DESC";
                break;
            case "jobtypeasc":
                jsjobs::$_ordering = "jobtype.title ASC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "ASC";
                break;
            case "salarydesc":
                jsjobs::$_ordering = "salaryrangestart.rangestart DESC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "DESC";
                break;
            case "salaryasc":
                jsjobs::$_ordering = "salaryrangestart.rangestart ASC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "ASC";
                break;
            case "posteddesc":
                jsjobs::$_ordering = "resume.created DESC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "DESC";
                break;
            case "postedasc":
                jsjobs::$_ordering = "resume.created ASC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "ASC";
                break;
            default: jsjobs::$_ordering = "resume.created DESC";
        }
        return;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (jsjobslib::jsjobs_preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "iddesc";
    }

    function getListSorting($sort) {
        jsjobs::$_sortlinks['title'] = $this->getSortArg("title", $sort);
        jsjobs::$_sortlinks['salary'] = $this->getSortArg("salary", $sort);
        jsjobs::$_sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        jsjobs::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    function removeResumeFileById() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $id = JSJOBSrequest::getVar('id');
        if (!is_numeric($id))
            return false;
        if(current_user_can('manage_options')){
            $uid = ' resume.uid ';
        }else{
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        $query = "SELECT COUNT(file.id) AS file, resume.id AS resumeid, file.filename
                    FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                    JOIN `" . jsjobs::$_db->prefix . "js_job_resumefiles` AS file ON file.resumeid = resume.id
                    WHERE resume.uid = " . $uid . " AND file.id = " . $id;
        $file = jsjobs::$_db->get_row($query);
        if ($file->file > 0) { // You are the owner
            $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE id = " . $id;
            jsjobs::$_db->query($query);
            $wpdir = wp_upload_dir();
            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            $file = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $file->resumeid . '/resume/' . $file->filename;
            @unlink($file);
            return true;
        }
        return false;
    }

    function getRssResumes() {
        $resume_rss = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('resume_rss');
        if ($resume_rss == 1) {
            $curdate = date_i18n('Y-m-d H:i:s');
            $query = "SELECT resume.id,resume.application_title,resume.photo,resume.first_name,resume.last_name,
                        resume.email_address,exp.title AS total_experience,cat.cat_title,resume.gender,edu.title AS education,
                        CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON resume.experienceid = exp.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON resume.job_category = cat.id
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS edu ON resume.heighestfinisheducation = edu.id
                        WHERE resume.status = 1";
            $result = jsjobs::$_db->get_results($query);
            foreach ($result AS $rs) {
                $query = "SELECT filename,filetype,filesize FROM `" . jsjobs::$_db->prefix . "js_job_resumefiles` WHERE resumeid = " . $rs->id;
                $rs->filename = jsjobs::$_db->get_results($query);
            }
            return $result;
        }
        return false;
    }
    function makeResumeSeo($resume_seo , $jsjobid){
        if(empty($resume_seo))
            return '';

        $common = JSJOBSincluder::getJSModel('common');
        $id = $common->parseID($jsjobid);
        if(! is_numeric($id))
            return '';

        $result = '';
        $resume_seo = jsjobslib::jsjobs_str_replace( ' ', '', $resume_seo);
        $resume_seo = jsjobslib::jsjobs_str_replace( '[', '', $resume_seo);
        $array = jsjobslib::jsjobs_explode(']', $resume_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'title':
                    $query = "SELECT application_title AS col FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category
                        WHERE resume.id = " . $id;
                break;
                case 'location':
                    $locationquery = "SELECT ra.address_city AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS ra
                        JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = ra.resumeid
                        WHERE resume.id = " . $id;
                break;
            }

            if($array[$i] == 'location'){
                $rows = jsjobsdb::get_results($locationquery);
                $location = '';
                foreach ($rows as $row) {
                    if($row->col != ''){
                        $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_cities` WHERE id = ". $row->col;
                        $cityname = jsjobsdb::get_row($query);
                        if(isset($cityname->name)){
                            if($location == '')
                                $location .= $cityname->name;
                            else
                                $location .= ' '.$cityname->name;
                        }
                    }
                }
                $location = $common->removeSpecialCharacter($location);
                if($location != ""){
                    if($result == '')
                        $result .= jsjobslib::jsjobs_str_replace(' ', '-', $location);
                    else{
                        $result .= '-'.jsjobslib::jsjobs_str_replace(' ', '-', $location);
                    }
                }
            }else{
                if($query){
                    $data = jsjobsdb::get_row($query);
                    if(isset($data->col)){
                        $val = $common->removeSpecialCharacter($data->col);
                        if($result == '')
                            $result .= jsjobslib::jsjobs_str_replace(' ', '-', $val);
                        else
                            $result .= '-'.jsjobslib::jsjobs_str_replace(' ', '-', $val);
                    }
                }
            }
        }
        return $result;
    }

    //function for resume files in jobapply email
    function getResumeFilesByResumeId($resumeid) { // by resumeid because files are stored in seperate table
        if (!is_numeric($resumeid)) return false;
        $query = "SELECT COUNT(id) FROM `".jsjobs::$_db->prefix."js_job_resumefiles` WHERE resumeid=" . $resumeid;

        $filesFound = jsjobsdb::get_var($query);
        if ($filesFound > 0) {
           $query = "SELECT * FROM `".jsjobs::$_db->prefix."js_job_resumefiles` WHERE resumeid = " . $resumeid;

           $files = jsjobsdb::get_results($query);
           return $files;
        } else {
           return false;
        }
    }

    function getResumeExpiryStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT resume.id
        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
        WHERE resume.status = 1 AND resume.id =" . $id;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getIfResumeOwner($jobid) {
        if (!is_numeric($jobid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT resume.id
        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
        WHERE resume.uid = " . $uid . "
        AND resume.id =" . $jobid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function deleteresumelogo() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $rid = JSJOBSrequest::getVar('resumeid');
        if (!is_numeric($rid))
            return false;
        $row = JSJOBSincluder::getJSTable('resume');
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $rid . '/photo';
        $files = glob($path . '/*.*');
        array_map('unlink', $files);    // delete all file in the direcoty
        $query = "UPDATE `".jsjobs::$_db->prefix."js_job_resume` SET photo = '' WHERE id = ".$rid;
        jsjobs::$_db->query($query);
        return true;
    }

    function getMyResumeSearchFormData($layout){
        $jsjp_search_array = array();
        $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3);
        $jsjp_search_array['sorton'] = JSJOBSrequest::getVar('sorton', 'post', 6);
        $jsjp_search_array['sortby'] = JSJOBSrequest::getVar('sortby', 'post', 2);
        $jsjp_search_array['application_title'] = JSJOBSrequest::getVar('application_title');
        $jsjp_search_array['first_name'] = JSJOBSrequest::getVar('first_name');
        $jsjp_search_array['middle_name'] = JSJOBSrequest::getVar('middle_name');
        $jsjp_search_array['last_name'] = JSJOBSrequest::getVar('last_name');
        $jsjp_search_array['nationality'] = JSJOBSrequest::getVar('nationality');
        $jsjp_search_array['gender'] = JSJOBSrequest::getVar('gender');
        $jsjp_search_array['salaryfixed'] = JSJOBSrequest::getVar('salaryfixed');
        $jsjp_search_array['currencyid'] = JSJOBSrequest::getVar('currencyid');
        $jsjp_search_array['category'] = JSJOBSrequest::getVar('category');
        $jsjp_search_array['salaryrangefrom'] = JSJOBSrequest::getVar('salaryrangefrom');
        $jsjp_search_array['salaryrangeend'] = JSJOBSrequest::getVar('salaryrangeend');
        $jsjp_search_array['jobtype'] = JSJOBSrequest::getVar('jobtype');
        $jsjp_search_array['salaryrangetype'] = JSJOBSrequest::getVar('salaryrangetype');
        $jsjp_search_array['highesteducation'] = JSJOBSrequest::getVar('highesteducation');
        $jsjp_search_array['experience'] = JSJOBSrequest::getVar('experience');
        $jsjp_search_array['zipcode'] = JSJOBSrequest::getVar('zipcode');
        $jsjp_search_array['keywords'] = JSJOBSrequest::getVar('keywords');
        $jsjp_search_array['city'] = JSJOBSrequest::getVar('city');
        if (!empty($customfields)) {
            foreach ($customfields as $uf) {
                $jsjp_search_array['resume_custom_fields'][$uf->field] = JSJOBSrequest::getVar($uf->field, 'post');
            }
        }
        $jsjp_search_array['search_from_resumes'] = 1;
        return $jsjp_search_array;
    }

    function getAdminResumeSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchtitle'] = JSJOBSrequest::getVar('searchtitle');
        $jsjp_search_array['searchname'] = JSJOBSrequest::getVar('searchname');
        $jsjp_search_array['searchjobcategory'] = JSJOBSrequest::getVar('searchjobcategory');
        $jsjp_search_array['searchjobtype'] = JSJOBSrequest::getVar('searchjobtype');
        $jsjp_search_array['searchjobsalaryrange'] = JSJOBSrequest::getVar('searchjobsalaryrange');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['datestart'] = JSJOBSrequest::getVar('datestart');
        $jsjp_search_array['dateend'] = JSJOBSrequest::getVar('dateend');
        $jsjp_search_array['featured'] = JSJOBSrequest::getVar('featured');
        $jsjp_search_array['sorton'] = JSJOBSrequest::getVar('sorton', 'post', 6);
        $jsjp_search_array['sortby'] = JSJOBSrequest::getVar('sortby', 'post', 2);
        $jsjp_search_array['search_from_resumes'] = 1;
        return $jsjp_search_array;
    }

    function getResumeSavedCookiesData($layout){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_resumes']) && $wpjp_search_cookie_data['search_from_resumes'] == 1){
            if(is_admin()){
                $jsjp_search_array['searchtitle'] = $wpjp_search_cookie_data['searchtitle'];
                $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
                $jsjp_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
                $jsjp_search_array['searchjobtype'] = $wpjp_search_cookie_data['searchjobtype'];
                $jsjp_search_array['searchjobsalaryrange'] = $wpjp_search_cookie_data['searchjobsalaryrange'];
                $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
                $jsjp_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
                $jsjp_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
                $jsjp_search_array['featured'] = $wpjp_search_cookie_data['featured'];
                $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
            }else{
                if($layout == 'myresume'){
                    $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                    $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
                }elseif($layout == 'resumes'){
                    $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3);
                    $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                    $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
                    $jsjp_search_array['application_title'] = $wpjp_search_cookie_data['application_title'];
                    $jsjp_search_array['first_name'] = $wpjp_search_cookie_data['first_name'];
                    $jsjp_search_array['middle_name'] = $wpjp_search_cookie_data['middle_name'];
                    $jsjp_search_array['last_name'] = $wpjp_search_cookie_data['last_name'];
                    $jsjp_search_array['nationality'] = $wpjp_search_cookie_data['nationality'];
                    $jsjp_search_array['gender'] = $wpjp_search_cookie_data['gender'];
                    $jsjp_search_array['salaryfixed'] = $wpjp_search_cookie_data['salaryfixed'];
                    $jsjp_search_array['currencyid'] = $wpjp_search_cookie_data['currencyid'];
                    $jsjp_search_array['salaryrangefrom'] = $wpjp_search_cookie_data['salaryrangefrom'];
                    $jsjp_search_array['salaryrangeend'] = $wpjp_search_cookie_data['salaryrangeend'];
                    $jsjp_search_array['jobtype'] = $wpjp_search_cookie_data['jobtype'];
                    $jsjp_search_array['salaryrangetype'] = $wpjp_search_cookie_data['salaryrangetype'];
                    $jsjp_search_array['highesteducation'] = $wpjp_search_cookie_data['highesteducation'];
                    $jsjp_search_array['experience'] = $wpjp_search_cookie_data['experience'];
                    $jsjp_search_array['category'] = $wpjp_search_cookie_data['category'];
                    $jsjp_search_array['zipcode'] = $wpjp_search_cookie_data['zipcode'];
                    $jsjp_search_array['keywords'] = $wpjp_search_cookie_data['keywords'];
                    $jsjp_search_array['city'] = $wpjp_search_cookie_data['city'];
                    if (!empty($customfields)) {
                        foreach ($customfields as $uf) {
                            $jsjp_search_array['resume_custom_fields'][$uf->field] = $wpjp_search_cookie_data['resume_custom_fields'][$uf->field];
                        }
                    }
                }
            }
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForMyResume($jsjp_search_array,$layout){
        jsjobs::$_search['myresume']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : null;
        jsjobs::$_search['myresume']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : null;
        if($layout == 'resumes'){
            $customfields = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(3);
            jsjobs::$_search['resumes']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 6;
            jsjobs::$_search['resumes']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
            jsjobs::$_search['resumes']['application_title'] = isset($jsjp_search_array['application_title']) ? $jsjp_search_array['application_title'] : null;
            jsjobs::$_search['resumes']['first_name'] = isset($jsjp_search_array['first_name']) ? $jsjp_search_array['first_name'] : null;
            jsjobs::$_search['resumes']['middle_name'] = isset($jsjp_search_array['middle_name']) ? $jsjp_search_array['middle_name'] : null;
            jsjobs::$_search['resumes']['last_name'] = isset($jsjp_search_array['last_name']) ? $jsjp_search_array['last_name'] : null;
            jsjobs::$_search['resumes']['nationality'] = isset($jsjp_search_array['nationality']) ? $jsjp_search_array['nationality'] : null;
            jsjobs::$_search['resumes']['gender'] = isset($jsjp_search_array['gender']) ? $jsjp_search_array['gender'] : null;
            jsjobs::$_search['resumes']['salaryfixed'] = isset($jsjp_search_array['salaryfixed']) ? $jsjp_search_array['salaryfixed'] : null;
            jsjobs::$_search['resumes']['currencyid'] = isset($jsjp_search_array['currencyid']) ? $jsjp_search_array['currencyid'] : null;
            jsjobs::$_search['resumes']['jobtype'] = isset($jsjp_search_array['jobtype']) ? $jsjp_search_array['jobtype'] : null;
            jsjobs::$_search['resumes']['salaryrangefrom'] = isset($jsjp_search_array['salaryrangefrom']) ? $jsjp_search_array['salaryrangefrom'] : null;
            jsjobs::$_search['resumes']['salaryrangeend'] = isset($jsjp_search_array['salaryrangeend']) ? $jsjp_search_array['salaryrangeend'] : null;
            jsjobs::$_search['resumes']['salaryrangetype'] = isset($jsjp_search_array['salaryrangetype']) ? $jsjp_search_array['salaryrangetype'] : null;
            jsjobs::$_search['resumes']['highesteducation'] = isset($jsjp_search_array['highesteducation']) ? $jsjp_search_array['highesteducation'] : null;
            jsjobs::$_search['resumes']['experience'] = isset($jsjp_search_array['experience']) ? $jsjp_search_array['experience'] : null;
            jsjobs::$_search['resumes']['category'] = isset($jsjp_search_array['category']) ? $jsjp_search_array['category'] : null;
            jsjobs::$_search['resumes']['zipcode'] = isset($jsjp_search_array['zipcode']) ? $jsjp_search_array['zipcode'] : null;
            jsjobs::$_search['resumes']['keywords'] = isset($jsjp_search_array['keywords']) ? $jsjp_search_array['keywords'] : null;
            jsjobs::$_search['resumes']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : null;
            if (!empty($customfields)) {
                foreach ($customfields as $uf) {
                    jsjobs::$_search['resume_custom_fields'][$uf->field] = isset($jsjp_search_array['resume_custom_fields'][$uf->field]) ? $jsjp_search_array['resume_custom_fields'][$uf->field] : '';
                }
            }
        }
    }

    function setSearchVariableForAdminResume($jsjp_search_array){
        jsjobs::$_search['resumes']['searchtitle']  = isset($jsjp_search_array['searchtitle']) ? $jsjp_search_array['searchtitle'] : null;
        jsjobs::$_search['resumes']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        jsjobs::$_search['resumes']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : null;
        jsjobs::$_search['resumes']['searchjobtype'] = isset($jsjp_search_array['searchjobtype']) ? $jsjp_search_array['searchjobtype'] : null;
        jsjobs::$_search['resumes']['searchjobsalaryrange'] = isset($jsjp_search_array['searchjobsalaryrange']) ? $jsjp_search_array['searchjobsalaryrange'] : null;
        jsjobs::$_search['resumes']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
        jsjobs::$_search['resumes']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : null;
        jsjobs::$_search['resumes']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : null;
        jsjobs::$_search['resumes']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : null;
        jsjobs::$_search['resumes']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 6;
        jsjobs::$_search['resumes']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
    }

    function getMessagekey(){
        $key = 'resume';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}
?>
