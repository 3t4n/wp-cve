<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsalaryrangeModel {

    function getSalaryRangebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_salaryrange WHERE id = " . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getAllSalaryRange() {
        //Filters
        $rangestart = jsjobs::$_search['range']['rangestart'];
        $rangeend = jsjobs::$_search['range']['rangeend'];
        $status = jsjobs::$_search['range']['status'];

        $inquery = '';
        $clause = ' WHERE ';
        if (is_numeric($rangestart)) {
            $inquery .= $clause . "rangestart >= $rangestart";
            $clause = ' AND ';
        }
        if (is_numeric($rangeend)) {
            $inquery .= $clause . "rangeend <= $rangeend";
            $clause = ' AND ';
        }
        if (is_numeric($status)) {
            $inquery .=$clause . " status = $status";
            $clause = ' AND ';
        }

        jsjobs::$_data['filter']['rangeend'] = $rangeend;
        jsjobs::$_data['filter']['rangestart'] = $rangestart;
        jsjobs::$_data['filter']['status'] = $status;

        //Paginstion
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` $inquery";
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` $inquery";
        $query .= " ORDER BY ordering ASC LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        // DB class limitation
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_salaryrange` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isSalaryRangeExist($data['rangestart'], $data['rangeend']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_salaryrange";
                $data['ordering'] = jsjobsdb::get_var($query);
            }

            if ($data['status'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if ($data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['jsjobs_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['status'] = 1;
            } else {
                if ($data['status'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if ($data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeSalaryRange($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSJOBS_ALREADY_EXIST)
            return JSJOBS_ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('salaryrange');
        $data = jsjobs::sanitizeData($data);
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
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }

        return JSJOBS_SAVED;

    }

    function deleteSalaryRanges($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('salaryrange');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->salaryRangeCanDelete($id) == true) {
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

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('salaryrange');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->salaryRangeCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                } else {
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            JSJOBSMessages::$counter = false;
            if ($status == 1)
                return JSJOBS_PUBLISHED;
            else
                return JSJOBS_UN_PUBLISHED;
        }else {
            JSJOBSMessages::$counter = $total;
            if ($status == 1)
                return JSJOBS_PUBLISH_ERROR;
            else
                return JSJOBS_UN_PUBLISH_ERROR;
        }
    }

    function salaryRangeCanUnpublish($salaryid) {
        if (is_numeric($salaryid) == false)
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` WHERE id = " . $salaryid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function salaryRangeCanDelete($salaryid) {
        if (is_numeric($salaryid) == false)
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE salaryrangefrom = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE salaryrangeto = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE jobsalaryrangestart = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE jobsalaryrangeend = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE desiredsalarystart = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE desiredsalaryend = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` WHERE id = " . $salaryid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getSalaryRangeForCombo() {
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` ORDER BY ordering ";
        $rows = jsjobsdb::get_results($query);
        foreach ($rows as $row) {
            $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $salrange = $row->rangestart; //.' - '.$currency . $row->rangeend;
            $jobsalaryrange[] = (object) array('id' => $row->id, 'text' => $salrange);
        }
        return $jobsalaryrange;
    }

    function getJobSalaryRangeForCombo() {
        $query = "SELECT id, concat(rangestart,' - ',rangeend) AS text  FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` ORDER BY ordering ";
        $jobsalaryrange = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $jobsalaryrange;
    }

    function getJobStartSalaryRangeForCombo() {

        $query = "SELECT id , rangestart AS text  FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` ORDER BY ordering ";
        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $rows;
    }

    function getJobEndSalaryRangeForCombo() {

        $query = "SELECT id , rangeend AS text  FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` ORDER BY ordering ";
        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $rows;
    }

    function isSalaryRangeExist($rangestart, $rangeend) {
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_salaryrange WHERE rangestart = '" . $rangestart . "' AND rangeend='" . $rangeend . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getDefaultSalaryRangeId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_salaryrange WHERE isdefault = 1";
        $id = jsjobsdb::get_var($query);

        return $id;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'salaryrange') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $jsjp_search_array['rangestart'] = JSJOBSrequest::getVar("rangestart");
        $jsjp_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjp_search_array['rangeend'] = JSJOBSrequest::getVar("rangeend");
        $jsjp_search_array['search_from_salaryrange'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'salaryrange') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_salaryrange']) && $wpjp_search_cookie_data['search_from_salaryrange'] == 1){
            $jsjp_search_array['rangestart'] = $wpjp_search_cookie_data['rangestart'];
            $jsjp_search_array['rangeend'] = $wpjp_search_cookie_data['rangeend'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['range']['rangestart'] = isset($jsjp_search_array['rangestart']) ? $jsjp_search_array['rangestart'] : '';
        jsjobs::$_search['range']['rangeend'] = isset($jsjp_search_array['rangeend']) ? $jsjp_search_array['rangeend'] : '';
        jsjobs::$_search['range']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
    }

    function getMessagekey(){
        $key = 'salaryrange';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
