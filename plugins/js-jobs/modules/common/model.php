<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCommonModel {

    function removeSpecialCharacter($string) {
        $string = sanitize_title($string);
        // $string = jsjobslib::jsjobs_strtolower($string);
        // $string = jsjobslib::jsjobs_strip_tags($string, "");
        // //Strip any unwanted characters
        // // $string = jsjobslib::jsjobs_preg_replace("/[^a-z0-9_\s-]/", "", $string);

        // $string = jsjobslib::jsjobs_preg_replace("/[@!*&$;%'\\\\#\\/]+/", "", $string);

        // //Clean multiple dashes or whitespaces
        // $string = jsjobslib::jsjobs_preg_replace("/[\s-]+/", " ", $string);
        // //Convert whitespaces and underscore to dash
        // $string = jsjobslib::jsjobs_preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    function setDefaultForDefaultTable($id, $tablename) {
        if (is_numeric($id) == false)
            return false;

        switch ($tablename) {
            case "jobtypes":
            case "jobstatus":
            case "shifts":
            case "heighesteducation":
            case "ages":
            case "currencies":
            case "careerlevels":
            case "experiences":
            case "salaryrange":
            case "salaryrangetypes":
            case "categories":
            case "subcategories":
                if (self::checkCanMakeDefault($id, $tablename)) {
                    if ($tablename == "currencies")
                        $column = "default";
                    else
                        $column = "isdefault";
                    //DB class limitations
                    $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_" . $tablename . "` AS t SET t." . $column . " = 0 ";
                    jsjobsdb::query($query);
                    $query = "UPDATE  `" . jsjobs::$_db->prefix . "js_job_" . $tablename . "` AS t SET t." . $column . " = 1 WHERE id=" . $id;
                    if (!jsjobsdb::query($query))
                        return JSJOBS_SET_DEFAULT_ERROR;
                    else
                        return JSJOBS_SET_DEFAULT;
                    break;
                }else {
                    return JSJOBS_UNPUBLISH_DEFAULT_ERROR;
                }
                break;
        }
    }

    function checkCanMakeDefault($id, $tablename) {
        if (!is_numeric($id))
            return false;
        switch ($tablename) {
            case 'jobtypes':
            case 'jobstatus':
            case 'shifts':
            case 'heighesteducation':
            case 'categories':
                $column = "isactive";
                break;
            default:
                $column = "status";
                break;
        }
        $query = "SELECT " . $column . " FROM `" . jsjobs::$_db->prefix . "js_job_" . $tablename . "` WHERE id=" . $id;
        $res = jsjobsdb::get_var($query);
        if ($res == 1)
            return true;
        else
            return false;
    }

    function getDefaultValue($table) {

        switch ($table) {
            case "categories":
            case "jobtypes":
            case "jobstatus":
            case "shifts":
            case "heighesteducation":
            case "ages":
            case "careerlevels":
            case "experiences":
            case "salaryrange":
            case "salaryrangetypes":
            case "subcategories":
                $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_" . $table . "` WHERE isdefault=1";

                $default_id = jsjobsdb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . jsjobs::$_db->prefix . "js_job_" . $table . "`";

                    $min_id = jsjobsdb::get_var($query);
                    return $min_id;
                }
            case "currencies":
                $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_" . $table . "` WHERE `default`=1";

                $default_id = jsjobsdb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . jsjobs::$_db->prefix . "js_job_" . $table . "`";

                    $min_id = jsjobsdb::get_var($query);
                    return $min_id;
                }
                break;
        }
    }

    function setOrderingUpForDefaultTable($field_id, $table) {
        if (is_numeric($field_id) == false)
            return false;
        //DB class limitations
        if($table == 'categories'){
            $parentid = jsjobs::$_db->get_var("SELECT parentid FROM `".jsjobs::$_db->prefix."js_job_categories` WHERE id = ".$field_id);
            $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f1, " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f2
                        SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1 AND f1.parentid = ".$parentid."
                        AND f2.id = " . $field_id;
        }else{
            $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f1, " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f2
                        SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1
                        AND f2.id = " . $field_id;
        }
        if (false == jsjobsdb::query($query)) {
            return JSJOBS_ORDER_UP_ERROR;
        }
        $query = " UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . "
                    SET ordering = ordering - 1
                    WHERE id = " . $field_id;

        if (false == jsjobsdb::query($query)) {
            return JSJOBS_ORDER_UP_ERROR;
        }
        return JSJOBS_ORDER_UP;
    }

    function setOrderingDownForDefaultTable($field_id, $table) {
        if (is_numeric($field_id) == false)
            return false;
        //DB class limitations
        if($table == 'categories'){
            $parentid = jsjobs::$_db->get_var("SELECT parentid FROM `".jsjobs::$_db->prefix."js_job_categories` WHERE id = ".$field_id);
            $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f1, " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f2
                        SET f1.ordering = f1.ordering - 1
                        WHERE f1.ordering = f2.ordering + 1 AND f1.parentid = ".$parentid."
                        AND f2.id = " . $field_id;
        }else{
            $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f1, " . jsjobs::$_db->prefix . "js_job_" . $table . " AS f2
                        SET f1.ordering = f1.ordering - 1
                        WHERE f1.ordering = f2.ordering + 1
                        AND f2.id = " . $field_id;
        }

        if (false == jsjobsdb::query($query)) {
            return JSJOBS_ORDER_DOWN_ERROR;
        }
        $query = " UPDATE " . jsjobs::$_db->prefix . "js_job_" . $table . "
                    SET ordering = ordering + 1
                    WHERE id = " . $field_id;

        if (false == jsjobsdb::query($query)) {
            return JSJOBS_ORDER_DOWN_ERROR;
        }
        return JSJOBS_ORDER_DOWN;
    }

    function getMultiSelectEdit($id, $for) {
        if (!is_numeric($id))
            return false;

        $config = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('default');
        $query = "SELECT city.id AS id, concat(city.cityName";
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                break;
            case 'cs'://City, State
                $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                break;
            case 'cc'://City, Country
                $query .= " ,', ', country.name)";
                break;
            case 'c'://city by default select for each case
                $query .= ")";
                break;
        }
        $query .= " AS name ,city.latitude,city.longitude";
        switch ($for) {
            case 1:
                $query .= " FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity";
                break;
            case 2:
                $query .= " FROM `" . jsjobs::$_db->prefix . "js_job_companycities` AS mcity";
                break;
            case 3:
                $query .= " FROM `" . jsjobs::$_db->prefix . "js_job_jobalertcities` AS mcity";
                break;
        }
        $query .=" JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city on city.id=mcity.cityid
        	  JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country on city.countryid=country.id
        	  LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state on city.stateid=state.id";
        switch ($for) {
            case 1:
                $query .= " WHERE mcity.jobid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 2:
                $query .= " WHERE mcity.companyid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 3:
                $query .= " WHERE mcity.alertid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
        }

        $result = jsjobsdb::get_results($query);
        $json_array = json_encode($result);
        if (empty($json_array))
            return null;
        else
            return $json_array;
    }

    function getRequiredTravel() {
        $requiredtravel = array();
        $requiredtravel[] = (object) array('id' => 1, 'text' => __('Not Required', 'js-jobs'));
        $requiredtravel[] = (object) array('id' => 2, 'text' => __('25 Per', 'js-jobs'));
        $requiredtravel[] = (object) array('id' => 3, 'text' => __('50 Per', 'js-jobs'));
        $requiredtravel[] = (object) array('id' => 4, 'text' => __('75 Per', 'js-jobs'));
        $requiredtravel[] = (object) array('id' => 5, 'text' => __('100 Per', 'js-jobs'));
        return $requiredtravel;
    }

    function getRequiredTravelValue($value) {
        switch ($value) {
            case '1': return __('Not Required', 'js-jobs'); break;
            case '2': return __('25 Per', 'js-jobs'); break;
            case '3': return __('50 Per', 'js-jobs'); break;
            case '4': return __('75 Per', 'js-jobs'); break;
            case '5': return __('100 Per', 'js-jobs'); break;
        }
    }

    function getLogAction($for) {
        $logaction = array();
        if ($for == 1) { //employer
            $logaction[] = (object) array('id' => 'add_company', 'text' => __('New company', 'js-jobs'));
            $logaction[] = (object) array('id' => 'gold_company', 'text' => __('Gold company', 'js-jobs'));
            $logaction[] = (object) array('id' => 'featured_company', 'text' => __('Featured company', 'js-jobs'));
            $logaction[] = (object) array('id' => 'add_department', 'text' => __('New department', 'js-jobs'));
            $logaction[] = (object) array('id' => 'add_job', 'text' => __('New job', 'js-jobs'));
            $logaction[] = (object) array('id' => 'gold_job', 'text' => __('Gold job', 'js-jobs'));
            $logaction[] = (object) array('id' => 'featured_job', 'text' => __('Featured job', 'js-jobs'));
            $logaction[] = (object) array('id' => 'resume_save_search', 'text' => __('Searched and saved resume', 'js-jobs'));
            $logaction[] = (object) array('id' => 'view_resume_contact_detail', 'text' => __('Viewed resume contact details', 'js-jobs'));
            $logaction[] = (object) array('id' => 'gold_company_timeperiod', 'text' => __('Gold company for time period', 'js-jobs'));
            //$logaction[] = (object) array('id' => 'featured_company_timeperiod', 'text' => __('Featured company for time period', 'js-jobs'));
        }
        if ($for == 2) {
            $logaction[] = (object) array('id' => 'add_resume', 'text' => __('New resume', 'js-jobs'));
            $logaction[] = (object) array('id' => 'gold_resume', 'text' => __('Gold resume', 'js-jobs'));
            $logaction[] = (object) array('id' => 'featured_resume', 'text' => __('Featured resume', 'js-jobs'));
            $logaction[] = (object) array('id' => 'add_cover_letter', 'text' => __('New cover letter', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_alert_lifetime', 'text' => __('Life time job alert', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_alert_time', 'text' => __('Job alert', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_alert_timeperiod', 'text' => __('Job alert for time', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_save_search', 'text' => __('Saved a job search', 'js-jobs'));
            //$logaction[] = (object) array('id' => 'shortlist_job', 'text' => __('Job short listed', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_apply', 'text' => __('Applied for job', 'js-jobs'));
            $logaction[] = (object) array('id' => 'view_job_apply_status', 'text' => __('Viewed job status', 'js-jobs'));
            $logaction[] = (object) array('id' => 'view_company_contact_detail', 'text' => __('Viewed company contact detail', 'js-jobs'));
            //$logaction[] = (object) array('id' => 'tell_a_friend', 'text' => __('Told a friend', 'js-jobs'));
            $logaction[] = (object) array('id' => 'job_save_filter', 'text' => __('Saved a job filter', 'js-jobs'));
            $logaction[] = (object) array('id' => 'fb_share', 'text' => __('Shared on social media', 'js-jobs'));
        }
        return $logaction;
    }

    function getMiniMax() {
        $minimax = array();
        $minimax[] = (object) array('id' => '1', 'text' => __('Minimum', 'js-jobs'));
        $minimax[] = (object) array('id' => '2', 'text' => __('Maximum', 'js-jobs'));
        return $minimax;
    }

    function getYesNo() {
        $yesno = array();
        $yesno[] = (object) array('id' => '1', 'text' => __('Yes', 'js-jobs'));
        $yesno[] = (object) array('id' => '0', 'text' => __('No', 'js-jobs'));
        return $yesno;
    }

    function getGender() {
        $gender = array();
        $gender[] = (object) array('id' => '0', 'text' => __('Does not matter', 'js-jobs'));
        $gender[] = (object) array('id' => '1', 'text' => __('Male', 'js-jobs'));
        $gender[] = (object) array('id' => '2', 'text' => __('Female', 'js-jobs'));
        return $gender;
    }

    function getStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Published', 'js-jobs'));
        $status[] = (object) array('id' => '0', 'text' => __('Unpublished', 'js-jobs'));
        return $status;
    }

    function getOptionsForJobAlert() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Subscribed', 'js-jobs'));
        $status[] = (object) array('id' => '0', 'text' => __('Unsubscribed', 'js-jobs'));
        return $status;
    }

    function getQueStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Approved', 'js-jobs'));
        $status[] = (object) array('id' => '0', 'text' => __('Rejected', 'js-jobs'));
        return $status;
    }

    function getListingStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => __('Approved', 'js-jobs'));
        $status[] = (object) array('id' => '-1', 'text' => __('Rejected', 'js-jobs'));
        return $status;
    }

    function getRolesForCombo() {
        $roles = array();
        $roles[] = (object) array('id' => '1', 'text' => __('Employer', 'js-jobs'));
        $roles[] = (object) array('id' => '2', 'text' => __('Job seeker', 'js-jobs'));
        return $roles;
    }

    function getFeilds() {
        $values = array();
        $values[] = (object) array('id' => 'text', 'text' => __('Text Field', 'js-jobs'));
        $values[] = (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-jobs'));
        $values[] = (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-jobs'));
        $values[] = (object) array('id' => 'date', 'text' => __('Date', 'js-jobs'));
        $values[] = (object) array('id' => 'select', 'text' => __('Drop Down', 'js-jobs'));
        $values[] = (object) array('id' => 'emailaddress', 'text' => __('Email Address', 'js-jobs'));
        return $values;
    }

    function getRadiusType() {
        $radiustype = array(
            (object) array('id' => '0', 'text' => __('Select One', 'js-jobs')),
            (object) array('id' => '1', 'text' => __('Meters', 'js-jobs')),
            (object) array('id' => '2', 'text' => __('Kilometers', 'js-jobs')),
            (object) array('id' => '3', 'text' => __('Miles', 'js-jobs')),
            (object) array('id' => '4', 'text' => __('Nautical Miles', 'js-jobs')),
        );
        return $radiustype;
    }

    function checkImageFileExtensions($file_name, $file_tmp, $image_extension_allow) {
        $allow_image_extension = jsjobslib::jsjobs_explode(',', $image_extension_allow);
        if ($file_name != "" AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = jsjobslib::jsjobs_strtolower($ext);
            if (in_array($ext, $allow_image_extension))
                return true;
            else
                return false;
        }
    }

    function checkDocumentFileExtensions($file_name, $file_tmp, $document_extension_allow) {
        $allow_document_extension = jsjobslib::jsjobs_explode(',', $document_extension_allow);
        if ($file_name != '' AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = jsjobslib::jsjobs_strtolower($ext);
            if (in_array($ext, $allow_document_extension))
                return true;
            else
                return false;
        }
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = jsjobslib::jsjobs_strlen($str) - $i;
        $ext = jsjobslib::jsjobs_substr($str, $i + 1, $l);
        return $ext;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function getJobtempModelFrontend() {
        $componentPath = JPATH_SITE . '/components/com_jsjobs';
        require_once $componentPath . '/models/jobtemp.php';
        $jobtemp_model = new JSJOBSModelJobtemp();
        return $jobtemp_model;
    }

    function getSalaryRangeView($currencysymbol, $salaryrangestart, $salaryrangeend, $salarytype) {
        $salary = '';
        $currency_align = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('currency_align');
        if ($currency_align == 1) { // Left align
            $salary = $currencysymbol . ' ' . $salaryrangestart . ' - ' . $salaryrangeend . ' ' . __($salarytype,'js-jobs');
        } elseif ($currency_align == 2) { // Right align
            $salary = $salaryrangestart . ' - ' . $salaryrangeend . ' ' . $currencysymbol . ' ' . __($salarytype,'js-jobs');
        }
        if ($salaryrangestart == '' && $salaryrangeend == '') {
            $salary = '';
        }
        return $salary;
    }

    function getLocationForView($cityname, $statename, $countryname) {
        $location = $cityname;
        $defaultaddressdisplaytype = jsjobs::$_configuration['defaultaddressdisplaytype'];
        switch ($defaultaddressdisplaytype) {
            case 'csc':
                if ($statename)
                    $location .= ', ' . $statename;
                if ($countryname)
                    $location .= ', ' . $countryname;
                break;
            case 'cs':
                if ($statename)
                    $location .= ', ' . $statename;
                break;
            case 'cc':
                if ($countryname)
                    $location .= ', ' . $countryname;
                break;
        }
        return $location;
    }

    function getUidByObjectId($actionfor, $id) {
        if (!is_numeric($id))
            return false;
        switch ($actionfor) {
            case'company':
                $table = 'js_job_companies';
                break;
            case'job':
                $table = 'js_job_jobs';
                break;
            case'resume':
                $table = 'js_job_resume';
                break;
        }
        $query = "SELECT uid FROM `" . jsjobs::$_db->prefix . $table . "`WHERE id = " . $id;
        $result = jsjobsdb::get_var($query);

        return $result;
    }

    public function makeFilterdOrEditedTagsToReturn($tags) {
        if (empty($tags))
            return null;
        $temparray = jsjobslib::jsjobs_explode(',', $tags);
        $array = array();
        for ($i = 0; $i < count($temparray); $i++) {
            $array[] = array('id' => $temparray[$i], 'name' => $temparray[$i]);
        }
        return json_encode($array);
    }

    function saveNewInJSJobs($data) {
        if (empty($data))
            return false;

        $allow_reg_as_emp = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('showemployerlink');
        if($allow_reg_as_emp != 1){
            $data['roleid '] = 2;
        }

        $currentuser = get_userdata(get_current_user_id());
        $data['socialid'] = '';
        $data['socialmedia'] = '';
        $data['first_name'] = $currentuser->first_name;
        $data['last_name'] = $currentuser->last_name;
        $data['emailaddress'] = $currentuser->user_email;
        $data['uid'] = $currentuser->ID;
        $row = JSJOBSincluder::getJSTable('users');
        $data['status'] = 1; // all user autoapprove when registered as JS Jobs users
        $data['created'] = date('Y-m-d H:i:s');
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->check()) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        return JSJOBS_SAVED;
    }

    function parseID($id){
        if(is_numeric($id)) return $id;
        $id = jsjobslib::jsjobs_explode('-', $id);
        if (!empty($id)) {
            $id = $id[count($id) -1];
        }
        return $id;
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = jsjobs::$_configuration['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', function(){return "text/html";});
        $body = jsjobslib::jsjobs_preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = jsjobslib::jsjobs_str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        $result = wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
        return $result;
    }

    function jsMakeRedirectURL($module, $layout, $for, $cpfor = null){
        if(empty($module) AND empty($layout) AND empty($for))
            return null;

        $finalurl = '';
        if( $for == 1 ){ // login links
            $jsthisurl = jsjobs::makeUrl(array('jsjobsme'=>$module, 'jsjobslt'=>$layout));
            $jsthisurl = jsjobslib::jsjobs_safe_encoding($jsthisurl);
            $finalurl = jsjobs::makeUrl(array('jsjobsme'=>'jsjobs', 'jsjobslt'=>'login', 'jsjobsredirecturl'=>$jsthisurl));
        }

        return $finalurl;
    }

    function getCitiesForFilter($cities){
        if(empty($cities))
            return NULL;


        $cities = jsjobslib::jsjobs_explode(',', $cities);
        $result = array();

        $defaultaddressdisplaytype = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('defaultaddressdisplaytype');

        foreach ($cities as $city) {
            $query = "SELECT city.id AS id, concat(city.cityName";
            switch ($defaultaddressdisplaytype) {
                case 'csc'://City, State, Country
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                    break;
                case 'cs'://City, State
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                    break;
                case 'cc'://City, Country
                    $query .= " ,', ', country.name)";
                    break;
                case 'c'://city by default select for each case
                    $query .= ")";
                    break;
            }
            $query .= " AS name ";
            $query .= " FROM `".jsjobs::$_db->prefix."js_job_cities` AS city
                        JOIN `".jsjobs::$_db->prefix."js_job_countries` AS country on city.countryid=country.id
                        LEFT JOIN `".jsjobs::$_db->prefix."js_job_states` AS state on city.stateid=state.id
                        WHERE country.enabled = 1 AND city.enabled = 1";
            $query .= " AND city.id =".$city;


            $result[] = jsjobsdb::get_row($query);
        }
        if(!empty($result)){
            return json_encode($result);
        }else{
            return NULL;
        }
    }
    function getMessagekey(){
        $key = 'common';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function stripslashesFull($input){// testing this function/.
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = jsjobslib::jsjobs_stripslashes($input);
        }
        return $input;
    }

    function setSearchVariableOnlySortandOrder($jsjp_search_array,$jstlay){
       if($jstlay == 'activitylog'){
           $val1 = 4;
       }else{
           $val1 = 6;
       }
       jsjobs::$_search['jobs']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : $val1;
       jsjobs::$_search['jobs']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
   }

   function getCookiesSavedOnlySortandOrder(){
       $jsjp_search_array = array();
       $wpjp_search_cookie_data = '';
       if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
           $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
           $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
       }
       if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_myapply_myjobs']) && $wpjp_search_cookie_data['search_from_myapply_myjobs'] == 1){
           $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
           $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
       }
       return $jsjp_search_array;
   }
    function getSearchFormDataOnlySort($jstlay){
       if($jstlay == 'activitylog'){
           $val1 = 4;
       }else{
           $val1 = 6;
       }
       $jsjp_search_array = array();
       $jsjp_search_array['sorton'] = JSJOBSrequest::getVar('sorton', 'post', $val1);
       $jsjp_search_array['sortby'] = JSJOBSrequest::getVar('sortby', 'post', 2);
       $jsjp_search_array['search_from_myapply_myjobs'] = 1;
       return $jsjp_search_array;
    }

    function getSanitizedFormData($data){
       return $data;
    }

    function getSanitizedEditorData($data){
       $data = wp_filter_post_kses($data);
       return $data;
    }

    function jsjobs_isadmin(){
        if (current_user_can('manage_options')) {
            return true;
        } else {
            return false;
        }
    }

    function js_verify_nonce(){
        $parameter = JSJOBSrequest::getVar('jsjobscf');
        if (isset($parameter) && $parameter == 'activitylog') {
            if(current_user_can('manage_options')){
                return true;
            }
        } elseif(isset($parameter) && $parameter == 'email'){
            return true;
        }
        die( 'Security check Failed' );
    }
}
?>
