<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCompanyModel {

    function getCompanies_Widget($companytype, $noofcompanies) {
        if ((!is_numeric($companytype)) || ( !is_numeric($noofcompanies)))
            return false;

        if ($companytype == 1) {
            $inquery = ' AND company.isgoldcompany = 1 AND DATE(company.endgolddate) >= CURDATE() ';
        } elseif ($companytype == 2) {
            $inquery = ' AND company.isfeaturedcompany = 1 AND DATE(company.endfeatureddate) >= CURDATE() ';
        } else {
            return '';
        }

        $query = "SELECT  company.*,cat.cat_title , CONCAT(company.alias,'-',company.id) AS companyaliasid ,company.id AS companyid,company.logofilename AS companylogo
            FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON company.category = cat.id
            WHERE company.status = 1  ";
            //AND company.isgoldcompany = 1 AND DATE(company.endgolddate) >= CURDATE()
        $query .= $inquery . " ORDER BY company.created DESC ";
        if ($noofcompanies != -1)
            $query .=" LIMIT " . $noofcompanies;
        $results = jsjobsdb::get_results($query);

        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        return $results;
    }

    function getAllCompaniesForSearchForCombo() {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getCompanybyIdForView($companyid) {
        if (is_numeric($companyid) == false)
            return false;

        $query = "SELECT company.*, cat.cat_title, country.name AS countryname, state.name AS statename ,city.cityName AS cityname
                    FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON company.category = cat.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON company.country = country.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON company.state = state.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON company.city = city.id
                    WHERE  company.id = " . $companyid;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        jsjobs::$_data[0]->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data[0]->city);
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data[3] = jsjobs::$_data[0]->params;
        jsjobs::$_data['companycontactdetail'] = true;
        //update the company view counter
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_companies` SET hits = hits + 1 WHERE id = " . $companyid;
        jsjobs::$_db->query($query);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getCompanybyId($c_id) {
        if ($c_id)
            if (!is_numeric($c_id))
                return false;
        if ($c_id) {
            $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id =" . $c_id;
            jsjobs::$_data[0] = jsjobsdb::get_row($query);
            if(jsjobs::$_data[0] != ''){
                jsjobs::$_data[0]->multicity = JSJOBSincluder::getJSModel('common')->getMultiSelectEdit($c_id, 2);
            }
        }
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(1); // company fields
        return;
    }

    function sorting() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        jsjobs::$_data['sorton'] = jsjobs::$_search['company']['sorton'];
        jsjobs::$_data['sortby'] = jsjobs::$_search['company']['sortby'];

        switch (jsjobs::$_data['sorton']) {
            case 3: // created
                jsjobs::$_data['sorting'] = ' company.created ';
                break;
            case 1: // company title
                jsjobs::$_data['sorting'] = ' company.name ';
                break;
            case 2: // category
                jsjobs::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 4: // location
                jsjobs::$_data['sorting'] = ' city.cityName ';
                break;
            case 5: // status
                jsjobs::$_data['sorting'] = ' company.status ';
                break;
        }
        if (jsjobs::$_data['sortby'] == 1) {
            jsjobs::$_data['sorting'] .= ' ASC ';
        } else {
            jsjobs::$_data['sorting'] .= ' DESC ';
        }
        jsjobs::$_data['combosort'] = jsjobs::$_data['sorton'];
    }

    function getAllCompanies() {
        if(is_admin()){
            $this->sorting();
        }else{
            $this->getOrdering();
        }
        //Filters
        $searchcompany = isset(jsjobs::$_search['company']['searchcompany']) ? jsjobs::$_search['company']['searchcompany'] : "";
        $searchjobcategory = isset(jsjobs::$_search['company']['searchjobcategory']) ? jsjobs::$_search['company']['searchjobcategory'] : "";
        $status = isset(jsjobs::$_search['company']['status']) ? jsjobs::$_search['company']['status'] : "";
        $datestart = isset(jsjobs::$_search['company']['datestart']) ? jsjobs::$_search['company']['datestart'] : "";
        $dateend = isset(jsjobs::$_search['company']['dateend']) ? jsjobs::$_search['company']['dateend'] : "";
        //Front end search var
        $jsjobs_company = isset(jsjobs::$_search['company']['jsjobs_company']) ? jsjobs::$_search['company']['jsjobs_company'] : "";
        $jsjobs_city = isset(jsjobs::$_search['company']['jsjobs_city']) ? jsjobs::$_search['company']['jsjobs_city'] : "";

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        }
        if ($jsjobs_company) {
            $inquery = " AND LOWER(company.name) LIKE '%$jsjobs_company%'";
        }
        if ($jsjobs_city) {
		if(is_numeric($jsjobs_city)){
			$inquery .= " AND company.city = $jsjobs_city ";
		}else{
			$arr = jsjobslib::jsjobs_explode( ',' , $jsjobs_city);
			$cityQuery = false;
			foreach($arr as $i){
				if($cityQuery){
					$cityQuery .= " OR company.city = $i ";
				}else{
					$cityQuery = " company.city = $i ";
				}
			}
			$inquery .= " AND ( $cityQuery ) ";
		}
        }
        if ($searchjobcategory) {
            $inquery .= " AND company.category = " . $searchjobcategory;
        }
        if (is_numeric($status)) {
            $inquery .= " AND company.status = " . $status;
        }

        if ($datestart != null) {
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . $datestart . "'";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . $dateend . "'";
        }
        $curdate = date('Y-m-d');
        jsjobs::$_data['filter']['jsjobs-company'] = $jsjobs_company;
        jsjobs::$_data['filter']['jsjobs-city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($jsjobs_city);
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;

        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE company.status != 0";
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total,'companies');

        //Data
        $query = "SELECT company.uid,company.name,CONCAT(company.alias,'-',company.id) AS aliasid,
                company.isfeaturedcompany, company.city, company.created,company.logofilename,
                company.status,company.url,company.id, cat.cat_title,company.params
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1)
                WHERE company.status != 0";

        $query .=$inquery;

        if(is_admin()){
            $query .= " ORDER BY " . jsjobs::$_data['sorting'];
        }else{
            $query .= " ORDER BY " . jsjobs::$_ordering;
        }
        $query .= " LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobsdb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getAllUnapprovedCompanies() {
        $this->sorting();
        //Filters
        $searchcompany = jsjobs::$_search['company']['searchcompany'];
        $categoryid = jsjobs::$_search['company']['searchjobcategory'];
        $datestart = jsjobs::$_search['company']['datestart'];
        $dateend = jsjobs::$_search['company']['dateend'];

        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $categoryid;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;

        $inquery = '';
        if ($searchcompany)
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        if (is_numeric($categoryid))
            $inquery .= " AND company.category =" . $categoryid;

        if ($datestart != null) {
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . $datestart . "'";
        }

        if ($dateend != null) {
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . $dateend . "'";
        }

        //Pagination
        $query = "SELECT COUNT(company.id)
                    FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                    WHERE (company.status = 0 OR company.isfeaturedcompany = 0 )";
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT company.*, cat.cat_title
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON company.category = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1)
                WHERE (company.status = 0  OR company.isfeaturedcompany = 0 )";
        $query .=$inquery;
        $query .= " ORDER BY " . jsjobs::$_data['sorting'] . " LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        return;
    }

    function storeCompany($data) {
        if (empty($data))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $filerealpath = "";

        $dateformat = jsjobs::$_configuration['date_format'];
        if (isset($data['since']))
            $data['since'] = date('Y-m-d H:i:s', jsjobslib::jsjobs_strtotime($data['since']));

        if (isset($data['company_logo_deleted'])) {
            $data['logoisfile'] = '';
            $data['logofilename'] = '';
        }

        $returnvalue = 1;
        if (!empty($data['alias']))
            $companyalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $companyalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['name']);

        $companyalias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace(' ', '-', $companyalias));
        $data['alias'] = $companyalias;
        if ($data['id'] == '') {
            if (!is_admin()) {
                if (isset($data['uid'])) {
                    if($data['uid'] != JSJOBSincluder::getObjectClass('user')->uid()){
                        $data['uid'] = JSJOBSincluder::getObjectClass('user')->uid();
                    }
                }
                $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('companyautoapprove');
            }
        }
        $data = jsjobs::sanitizeData($data);

        $job_editor = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('job_editor');
        if ($job_editor == 1) {
            $data['description'] = JSJOBSincluder::getJSModel('common')->getSanitizedEditorData($_POST['description']);
        }
//custom field code start
        $userfieldforcompany = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
        $params = array();
        foreach ($userfieldforcompany AS $ufobj) {
            if ($ufobj->userfieldtype == 'date') {
                $vardata = isset($data[$ufobj->field]) ? date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data[$ufobj->field])) : '';
            } else {
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if($vardata != ''){
                // if($ufobj->userfieldtype == 'multiple'){ // multiple field change behave
                //     $vardata = implode(', ', $vardata); // fixed index
                // }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = jsjobslib::jsjobs_htmlspecialchars($vardata);
            }
        }
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        $data['params'] = $params;

//custom field code end
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->check()) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }

        // For file upload
        $companyid = $row->id;
        $actionid = 0;
        if ($data['id'] == '') {
        } else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        if (isset($data['city']))
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) && $storemulticity == false)
            return false;
        if ($_FILES['logo']['size'] > 0) { // logo
            $res = $this->uploadFile($companyid);
            if ($res == 6){
                $msg = JSJOBSMessages::getMessage(JSJOBS_FILE_TYPE_ERROR, '');
                JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
            if($res == 5){
                $msg = JSJOBSMessages::getMessage(JSJOBS_FILE_SIZE_ERROR, '');
                JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
            }
        }
        //Sending email only new case
        if ($data['id'] == '') {
            JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 1, $row->id); // 1 for company,1 for add new company
        }
        return JSJOBS_SAVED;
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated
        if (!is_numeric($companyid))
            return false;


        $query = "SELECT cityid FROM " . jsjobs::$_db->prefix . "js_job_companycities WHERE companyid = " . $companyid;
        $old_cities = jsjobsdb::get_results($query);

        $id_array = jsjobslib::jsjobs_explode(",", $city_id);
        $row = JSJOBSincluder::getJSTable('companycities');
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM " . jsjobs::$_db->prefix . "js_job_companycities WHERE companyid = " . $companyid . " AND cityid=" . $oldcityid->cityid;

                if (!jsjobsdb::query($query)) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $cols = array();
                $cols['id'] = "";
                $cols['companyid'] = $companyid;
                $cols['cityid'] = $cityid;
                if (!$row->bind($cols)) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
                if (!$row->store()) {
                    $err = jsjobs::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        if (empty($error))
            return true;
        return false;
    }

    function getUidByCompanyId($companyid) {
        if (!is_numeric($companyid))
            return false;
        $query = "SELECT uid FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
        $uid = jsjobsdb::get_var($query);
        return $uid;
    }

    function deleteCompanies($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $notdeleted = 0;
        foreach ($ids as $id) {
            $query = "SELECT company.name,company.contactemail AS contactemail,company.contactname FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company  WHERE company.id = " . $id;
            $companyinfo = jsjobsdb::get_row($query);
            $mailextradata = array();
            $mailextradata['companyname'] = $companyinfo->name;
            $mailextradata['contactname'] = $companyinfo->contactname;
            $mailextradata['contactemail'] = $companyinfo->contactemail;
            if ($this->companyCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                } else {
                    $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE companyid = " . $id;
                    jsjobsdb::query($query);
                    JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 2, $id,$mailextradata); // 1 for company,2 for delete company

                    $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo/*.*"));//deleting files
                    if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo")){
                        @rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo");
                    }
                    array_map('unlink', glob($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/*.*"));//deleting files
                    @rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id);
                }
            } else {
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

    function companyCanDelete($companyid) {
        if (!is_numeric($companyid))
            return false;
        if(!is_admin()){
            if(!$this->getIfCompanyOwner($companyid)){
                return false;
            }
        }
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE companyid = " . $companyid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE companyid = " . $companyid . ")
                    AS total ";
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function companyEnforceDeletes($companyid) {
        if (empty($companyid))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $query1 = "SELECT company.name,company.contactemail AS contactemail,company.contactname FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company  WHERE company.id = " . $companyid;
        $companyinfo = jsjobsdb::get_row($query1);
        $mailextradata = array();
        $mailextradata['companyname'] = $companyinfo->name;
        $mailextradata['contactname'] = $companyinfo->contactname;
        $mailextradata['contactemail'] = $companyinfo->contactemail;
        $query = "DELETE  company,job,department,companycity, apply, jobcity
                    FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companycities` AS companycity ON company.id=companycity.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_departments` AS department ON company.id=department.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON company.id=job.companyid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity ON job.id=jobcity.jobid
                    WHERE company.id =" . $companyid;
        if (!jsjobsdb::query($query)) {
            return JSJOBS_DELETE_ERROR;
        }
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 2, $companyid,$mailextradata); // 1 for company,2 for delete company

        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $file = $wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo/*.*";
        $files = glob($file);
        array_map('unlink', $files);//deleting files
        if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo")){
            rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo");
        }
        $file = $wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/*.*";
        $files = glob($file);
        array_map('unlink', $files);//deleting files
        if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid)){
            rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid);
        }

        return JSJOBS_DELETED;
    }

    function getCompanyForCombo($uid = null) {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE status = 1 ";
        if ($uid != null) {
            if (!is_numeric($uid))
                return false;
            $query .= " AND uid = " . $uid;
        }
        $query .= " ORDER BY id ASC ";
        $companies = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $companies;
    }

    function deletecompanylogo() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $cid = JSJOBSrequest::getVar('companyid');
        if (!is_numeric($cid))
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/employer/comp_' . $cid . '/logo';
        $files = glob($path . '/*.*');
        array_map('unlink', $files);    // delete all file in the direcoty
        $query = "UPDATE `".jsjobs::$_db->prefix."js_job_companies` SET logofilename = '', logoisfile = -1 WHERE id = ".$cid;
        jsjobs::$_db->query($query);
        return true;
    }

    function uploadFile($id) {
        $result = JSJOBSincluder::getObjectClass('uploads')->uploadCompanyLogo($id);
        return $result;
    }

    function approveQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        if($row->load($id)){
            $row->columns['status'] = 1;
            if(!$row->store()){
                return JSJOBS_APPROVE_ERROR;
            }
        }else{
            return JSJOBS_APPROVE_ERROR;
        }
        //send email
        $company_queue_approve_email = JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company approve
        return JSJOBS_APPROVED;
    }

    function rejectQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = JSJOBSincluder::getJSTable('company');
        if (!$row->update(array('id' => $id, 'status' => -1))) {
            return JSJOBS_REJECT_ERROR;
        }
        //send email
        $company_approve_email = JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company reject
        return JSJOBS_APPROVED;
    }


    function approveQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->approveQueueCompanyModel($id);
        return $result;
    }

    function rejectQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->rejectQueueCompanyModel($id);
        return $result;
    }

    function getCompaniesForCombo() {
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE status = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getUserCompaniesForCombo() {
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if(!is_numeric($uid)) return false;
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE uid = " . $uid . " AND status = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getMyCompanies($uid) {
        if (!is_numeric($uid)) return false;
        //Filters
        $searchcompany = isset(jsjobs::$_search['mycompany']['searchcompany']) ? jsjobs::$_search['mycompany']['searchcompany'] : '';
        $searchcompcategory = isset(jsjobs::$_search['mycompany']['searchcompcategory']) ? jsjobs::$_search['mycompany']['searchcompcategory'] : '';

        //Front end search var
        $jsjobs_city = isset(jsjobs::$_search['mycompany']['jsjobs-city']) ? jsjobs::$_search['mycompany']['jsjobs-city'] : '';

        if ($searchcompcategory)
            if (is_numeric($searchcompcategory) == false)
                return false;
        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%$searchcompany%'";
        }
        if ($jsjobs_city) {
            if(is_numeric($jsjobs_city)){
                $inquery .= " AND LOWER(company.city) LIKE '%$jsjobs_city%'";
            }else{
                $arr = jsjobslib::jsjobs_explode( ',' , $jsjobs_city);
                $cityQuery = false;
                foreach($arr as $i){
                    if($cityQuery){
                        $cityQuery .= " OR LOWER(company.city) LIKE '%$i%' ";
                    }else{
                        $cityQuery = " LOWER(company.city) LIKE '%$i%' ";
                    }
                }
                $inquery .= " AND ( $cityQuery ) ";
            }
        }
        if ($searchcompcategory) {
            $inquery .= " AND company.category = " . $searchcompcategory;
        }

        jsjobs::$_data['filter']['jsjobs-city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($jsjobs_city);
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchcompcategory'] = $searchcompcategory;

        //Pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE uid = " . $uid;
        $query .=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total,'mycompanies');
        //Data
        $query = "SELECT company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isgoldcompany,company.isfeaturedcompany
                 ,cat.cat_title,company.params,company.url
                FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id = company.category
                WHERE company.uid = " . $uid;
        $query .=$inquery;
        $query .= " ORDER BY company.created DESC LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        $data = array();
        foreach (jsjobs::$_data[0] AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('company');
        return;
    }

    function getCompanynameById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.name FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company WHERE company.id = " . $id;
        $companyname = jsjobs::$_db->get_var($query);
        return $companyname;
    }

    function addViewContactDetail($companyid, $uid) {
        if (!is_numeric($companyid))
            return false;
        if (!is_numeric($uid))
            return false;

        $data = array();
        $data['uid'] = $uid;
        $data['companyid'] = $companyid;
        $data['status'] = 1;
        $data['created'] = $curdate;

        $row = JSJOBSincluder::getJSTable('jobseekerviewcompany');
        if (!$row->bind($data)) {
            return false;
        }

        if ($row->store()) {
            return true;
        }else{
            return false;
        }
    }

    function canAddCompany($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function employerHaveCompany($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE uid = " . $uid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == 0) {
            return false;
        } else {
            return true;
        }
    }

    function makeCompanySeo($company_seo , $jsjobid){
        if(empty($company_seo))
            return '';

        $common = JSJOBSincluder::getJSModel('common');
        $id = $common->parseID($jsjobid);
        if(! is_numeric($id))
            return '';
        $result = '';
        $company_seo = jsjobslib::jsjobs_str_replace( ' ', '', $company_seo);
        $company_seo = jsjobslib::jsjobs_str_replace( '[', '', $company_seo);
        $array = jsjobslib::jsjobs_explode(']', $company_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'name':
                    $query = "SELECT name AS col FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = company.category
                        WHERE company.id = " . $id;
                break;
                case 'location':
                    $query = "SELECT company.city AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company WHERE company.id = " . $id;
                break;
            }
            if($query){
                $data = jsjobsdb::get_row($query);
                if(isset($data->col)){
                    if($array[$i] == 'location'){
                        $cityids = jsjobslib::jsjobs_explode(',', $data->col);
                        $location = '';
                        for ($j=0; $j < count($cityids); $j++) {
                            if(is_numeric($cityids[$j])){
                                $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_cities` WHERE id = ". $cityids[$j];
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
                        if($location != ''){
                            if($result == '')
                                $result .= jsjobslib::jsjobs_str_replace(' ', '-', $location);
                            else
                                $result .= '-'.jsjobslib::jsjobs_str_replace(' ', '-', $location);
                        }
                    }else{
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

    function getCompanyExpiryStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.id
        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
        WHERE company.status = 1
        AND company.id =" . $id;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getIfCompanyOwner($id) {
        if (!is_numeric($id))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT company.id
        FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company
        WHERE company.uid = " . $uid . "
        AND company.id =" . $id;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getMessagekey(){
        $key = 'company';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


    function getOrdering() {
        $sort = JSJOBSrequest::getVar('sortby', '', 'posteddesc');
        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "namedesc":
                jsjobs::$_ordering = "company.name DESC";
                jsjobs::$_sorton = "name";
                jsjobs::$_sortorder = "DESC";
                break;
            case "nameasc":
                jsjobs::$_ordering = "company.name ASC";
                jsjobs::$_sorton = "name";
                jsjobs::$_sortorder = "ASC";
                break;
            case "categorydesc":
                jsjobs::$_ordering = "cat.cat_title DESC";
                jsjobs::$_sorton = "category";
                jsjobs::$_sortorder = "DESC";
                break;
            case "categoryasc":
                jsjobs::$_ordering = "cat.cat_title ASC";
                jsjobs::$_sorton = "category";
                jsjobs::$_sortorder = "ASC";
                break;
            case "locationdesc":
                jsjobs::$_ordering = "city.cityName DESC";
                jsjobs::$_sorton = "location";
                jsjobs::$_sortorder = "DESC";
                break;
            case "locationasc":
                jsjobs::$_ordering = "city.cityName ASC";
                jsjobs::$_sorton = "location";
                jsjobs::$_sortorder = "ASC";
                break;
            case "posteddesc":
                jsjobs::$_ordering = "company.created DESC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "DESC";
                break;
            case "postedasc":
                jsjobs::$_ordering = "company.created ASC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "ASC";
                break;
            default: jsjobs::$_ordering = "company.created DESC";
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
        jsjobs::$_sortlinks['name'] = $this->getSortArg("name", $sort);
        jsjobs::$_sortlinks['category'] = $this->getSortArg("category", $sort);
        jsjobs::$_sortlinks['location'] = $this->getSortArg("location", $sort);
        jsjobs::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    // front end coookies search form data
    function getSearchFormDataMyCompany(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchcompany'] = JSJOBSrequest::getVar('searchcompany');
        $jsjp_search_array['jsjobs-city'] = JSJOBSrequest::getVar('jsjobs-city');
        $jsjp_search_array['searchcompcategory'] = JSJOBSrequest::getVar('searchcompcategory');
        $jsjp_search_array['search_from_myapply_mycompanies'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedMyCompany(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_myapply_mycompanies']) && $wpjp_search_cookie_data['search_from_myapply_mycompanies'] == 1){
            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['jsjobs-city'] = $wpjp_search_cookie_data['jsjobs-city'];
            $jsjp_search_array['searchcompcategory'] = $wpjp_search_cookie_data['searchcompcategory'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableMyCompany($jsjp_search_array){
        jsjobs::$_search['company']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : null;
        jsjobs::$_search['company']['jsjobs-city'] = isset($jsjp_search_array['jsjobs-city']) ? $jsjp_search_array['jsjobs-city'] : null;
        jsjobs::$_search['company']['searchcompcategory'] = isset($jsjp_search_array['searchcompcategory']) ? $jsjp_search_array['searchcompcategory'] : null;
    }

    // Admin search cookies form data
    function getSearchFormAdminCompanyData(){
        $jsjp_search_array = array();
        $jsjp_search_array['sorton'] = JSJOBSrequest::getVar('sorton', 'post', 3);
        $jsjp_search_array['sortby'] = JSJOBSrequest::getVar('sortby', 'post', 2);
        //Filters
        $jsjp_search_array['searchcompany'] = JSJOBSrequest::getVar('searchcompany');
        $jsjp_search_array['searchjobcategory'] = JSJOBSrequest::getVar('searchjobcategory');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['datestart'] = JSJOBSrequest::getVar('datestart');
        $jsjp_search_array['dateend'] = JSJOBSrequest::getVar('dateend');
        $jsjp_search_array['featured'] = JSJOBSrequest::getVar('featured');
        //Front end search var
        $jsjobs_company = JSJOBSrequest::getVar('jsjobs-company');
        $jsjp_search_array['jsjobs_company'] = jsjobs::parseSpaces($jsjobs_company);
        $jsjp_search_array['jsjobs_city'] = JSJOBSrequest::getVar('jsjobs-city');
        $jsjp_search_array['search_from_admin_company'] = 1;
        return $jsjp_search_array;
    }

    function getAdminCompanySavedCookies(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) ,true);
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_admin_company']) && $wpjp_search_cookie_data['search_from_admin_company'] == 1){
            $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
            $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
            $jsjp_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
            $jsjp_search_array['featured'] = $wpjp_search_cookie_data['featured'];
            $jsjp_search_array['jsjobs_company'] = $wpjp_search_cookie_data['jsjobs_company'];
            $jsjp_search_array['jsjobs_company'] = $wpjp_search_cookie_data['jsjobs_company'];
            $jsjp_search_array['jsjobs_city'] = $wpjp_search_cookie_data['jsjobs_city'];
        }
        return $jsjp_search_array;
    }

    function setAdminCompanySearchVariable($jsjp_search_array){
        jsjobs::$_search['company']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 3;
        jsjobs::$_search['company']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
        jsjobs::$_search['company']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : '';
        jsjobs::$_search['company']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : '';
        jsjobs::$_search['company']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        jsjobs::$_search['company']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : '';
        jsjobs::$_search['company']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : '';
        jsjobs::$_search['company']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : '';
        jsjobs::$_search['company']['jsjobs_company'] = isset($jsjp_search_array['jsjobs_company']) ? $jsjp_search_array['jsjobs_company'] : '';
        jsjobs::$_search['company']['jsjobs_city'] = isset($jsjp_search_array['jsjobs_city']) ? $jsjp_search_array['jsjobs_city'] : '';
        }

}
?>
