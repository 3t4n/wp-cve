<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSdepartmentsModel {

    function getDepartmentById($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT department.* FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department WHERE department.id=" . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getViewDepartment($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT department.name,department.description,company.name AS companyname
            		FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
                    JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company
                    ON company.id = department.companyid
                    WHERE department.id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getDepartments($companyid) {
        //Filters
        $departmentname = jsjobs::$_search['department']['departmentname'];
        $companyname = jsjobs::$_search['department']['companyname'];
        $status = jsjobs::$_search['department']['status'];

        $inquery = " WHERE department.status != 0 ";
        if ($departmentname) {
            $inquery .= " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }if (is_numeric($status)) {
            $inquery .= " AND department.status = " . $status;
        }
        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
            $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
            jsjobs::$_data[0]['companyname'] = jsjobsdb::get_var($query);
        }
        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;
        jsjobs::$_data['filter']['status'] = $status;

        //pagination
        $query = "SELECT COUNT(department.id)
            FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid";
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT department.*, company.name as companyname
            FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid";
        $query .= $inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0]['department'] = jsjobsdb::get_results($query);

        return;
    }

    function getMyDepartments($uid, $companyid) {
        if (!is_numeric($uid))
            return false;
        $departmentname = JSJOBSrequest::getVar('departmentname');
        $companyname = JSJOBSrequest::getVar('companyname');
        $status = JSJOBSrequest::getVar('status');

        //$inquery = " WHERE department.status != 0 ";
        $inquery = "";
        if ($departmentname) {
            $inquery .= " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }if (is_numeric($status)) {
            $inquery .= " AND department.status = " . $status;
        }
        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
            $query = "SELECT name FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE id = " . $companyid;
            jsjobs::$_data[0]['companyname'] = jsjobsdb::get_var($query);
        }
        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;
        jsjobs::$_data['filter']['status'] = $status;

        /*//Filters
        $departmentname = JSJOBSrequest::getVar('departmentname');
        $companyname = JSJOBSrequest::getVar('companyname');

        jsjobs::$_data['filter']['departmentname'] = $departmentname;
        jsjobs::$_data['filter']['companyname'] = $companyname;

        $inquery = "";
        if ($departmentname) {
            $inquery = " AND department.name LIKE '%" . $departmentname . "%' ";
        }
        if ($companyname) {
            $inquery .= " AND company.name LIKE '%" . $companyname . "%' ";
        }

        if (is_numeric($companyid)) {
            $inquery .= " AND company.id = " . $companyid;
        }*/

        //pagination
        $query = "SELECT COUNT(department.id)
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
            WHERE department.uid = " . $uid;
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total, 'mydepartments');

        //Data
        $query = "SELECT department.id,department.uid,department.name,department.status,department.created,company.name as companyname,department.companyid,CONCAT(company.alias,'-',company.id) AS companyalias
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
            WHERE department.uid = " . $uid;
        $query .= $inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function getAllUnapprovedDepartments() {

        //Filters
        $searchcompany = jsjobs::$_search['department']['companyname'];
        $searchdepartment = jsjobs::$_search['department']['departmentname'];

        jsjobs::$_data['filter']['companyname'] = $searchcompany;
        jsjobs::$_data['filter']['departmentname'] = $searchdepartment;

        $inquery = "";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . $searchcompany . "%'";
        if ($searchdepartment)
            $inquery .= " AND LOWER(department.name) LIKE '%" . $searchdepartment . "%'";

        //Pagination
        $query = "SELECT COUNT(department.id)
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT department.*, company.name as companyname
			FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
			JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        $query.=$inquery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function storeDepartment($data) {
        if (empty($data))
            return false;

        $row = JSJOBSincluder::getJSTable('department');
        if (!empty($data['alias']))
            $departmentalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $departmentalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['name']);

        $departmentalias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace(' ', '-', $departmentalias));
        $data['alias'] = $departmentalias;
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $data['description'] = JSJOBSincluder::getJSModel('common')->getSanitizedEditorData($_POST['description']);

        $data['uid'] = JSJOBSincluder::getJSModel('company')->getUidByCompanyId($data['companyid']); // Uid must be the same as the company owner id

        if ($data['id'] == ''){
            $data['created'] = date("Y-m-d H:i:s");
            if (!is_admin()) {
                $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('department_auto_approve');
                if (isset($data['uid'])) {
                    if($data['uid'] != JSJOBSincluder::getObjectClass('user')->uid()){
                        $data['uid'] = JSJOBSincluder::getObjectClass('user')->uid();
                    }
                }
            }
        }
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

    function deleteDepartments($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('department');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->departmentCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
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

    function departmentCanDelete($departmentid) {
        if (!is_numeric($departmentid))
            return false;
        if(!is_admin()){
            if(!$this->getIfDepartmentOwner($departmentid)){
                return false;
            }
        }
        $query = "SELECT
            ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE departmentid = " . $departmentid . ")
            AS total ";

        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getDepartmentForCombo($uid = null,$companyid = 0) {
        if ($uid != null) {
            if ((is_numeric($uid) == false) || ($uid == ''))// admin can have 0 uid;
                return false;
        }else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        if($uid != null){
            $query = "SELECT id, name as text FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE uid = " . $uid . " AND status = 1  ORDER BY name ASC ";
            if($companyid != 0 && $uid == 0){// this code is for the case when admin inserts compnay job and department(admin uid is inserted 0)
                $query = "SELECT id, name as text FROM `" . jsjobs::$_db->prefix . "js_job_departments` WHERE companyid = " . $companyid . " AND status = 1  ORDER BY name ASC ";
            }

            $rows = jsjobsdb::get_results($query);
            if (jsjobs::$_db->last_error != null) {

                return false;
            }
            return $rows;
        }
        return false;
    }

    function listDepartments() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $return_value = '';
        $val = JSJOBSrequest::getVar('val');
        $themecall = JSJOBSrequest::getVar('themecall');
        $themeclass="";
        if($themecall){
            if(function_exists("getJobManagerThemeClass")){
                $themeclass = getJobManagerThemeClass("select");
            }
        }
        if (is_numeric($val) === false)
            return false;
        $query = "SELECT published,isvisitorpublished,required FROM " . jsjobs::$_db->prefix . "js_job_fieldsordering WHERE  field='department' AND fieldfor = 2";
        $authentication = jsjobsdb::get_row($query);
        if (JSJOBSincluder::getObjectClass('user')->isguest() == true) {
            $published = $authentication->isvisitorpublished;
        } else {
            $published = $authentication->published;
        }
        if ($published == 1) {
            $query = "SELECT id, name FROM " . jsjobs::$_db->prefix . "js_job_departments  WHERE status = 1 AND companyid = " . $val . " ORDER BY name ASC";
            $result = jsjobsdb::get_results($query);
            $required = ($authentication->required == 1) ? 'data-validation="required"' : '';
            $return_value = "<select name='departmentid' class='inputbox one $themeclass' $required >\n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function departmentsApprove($ids) {
        if (empty($ids))
            return false;

        $row = JSJOBSincluder::getJSTable('department');
        $total = 0;
        $status = 1;
        foreach ($ids as $id) {
            if (!is_numeric($id))
                $total +=1;

            if (!$row->update(array('id' => $id, 'status' => $status))) {
                $total += 1;
            }
        }

        if ($total != 0) {
            JSJOBSMessages::$counter = $total;
            return JSJOBS_APPROVE_ERROR;
        } else {
            return JSJOBS_APPROVED;
        }
    }

    function canAddDepartment($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function departmentsReject($ids) {
        if (empty($ids))
            return false;

        $total = 0;
        $row = JSJOBSincluder::getJSTable('department');
        $status = -1;
        foreach ($ids as $id) {
            if (!is_numeric($id))
                $total +=1;
            if (!$row->update(array('id' => $id, 'status' => $status))) {
                $total += 1;
            }
        }

        if ($total != 0) {
            JSJOBSMessages::$counter = $total;
            return JSJOBS_REJECT_ERROR;
        } else {
            return JSJOBS_REJECTED;
        }
    }

    function getIfDepartmentOwner($departentid) {
        if (!is_numeric($departentid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT departent.id
        FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS departent
        WHERE departent.uid = " . $uid . "
        AND departent.id =" . $departentid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'department') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $jsjp_search_array['departmentname'] = JSJOBSrequest::getVar("departmentname");
        $jsjp_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjp_search_array['companyname'] = JSJOBSrequest::getVar("companyname");
        $jsjp_search_array['search_from_department'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'department') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_department']) && $wpjp_search_cookie_data['search_from_department'] == 1){
            $jsjp_search_array['departmentname'] = $wpjp_search_cookie_data['departmentname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['companyname'] = $wpjp_search_cookie_data['companyname'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['department']['departmentname'] = isset($jsjp_search_array['departmentname']) ? $jsjp_search_array['departmentname'] : '';
        jsjobs::$_search['department']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        jsjobs::$_search['department']['companyname'] = isset($jsjp_search_array['companyname']) ? $jsjp_search_array['companyname'] : '';
    }

    function getMessagekey(){
        $key = 'departments';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;} return $key;
    }


}

?>
