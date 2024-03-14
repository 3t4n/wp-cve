<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsalaryrangetypeModel {

    function getSalaryRangeTypebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_salaryrangetypes WHERE id = " . $c_id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getAllSalaryRangeType() {
        //Filters
        if(isset(jsjobs::$_search['range']['title'])) $title = jsjobs::$_search['range']['title']; else $title = "";
        if(isset(jsjobs::$_search['range']['status'])) $status = jsjobs::$_search['range']['status']; else $status = "";

        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if ($status != null)
            $inquery .=$clause . " status = '" . $status . "'";

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;

        //Pagination
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` $inquery";
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` $inquery";
        $query .= " ORDER BY ordering ASC LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        // DB class limitation
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isSalaryRangeTypeExist($data['title']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_salaryrangetypes";
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

    function storeSalaryRangeType($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSJOBS_ALREADY_EXIST)
            return JSJOBS_ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('salaryrangetype');
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
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

    function deleteSalaryRangesType($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('salaryrangetype');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->salaryRangeTypeCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('salaryrangetype');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->salaryRangeTypeCanUnpublish($id)) {
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

    function salaryRangeTypeCanUnpublish($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` WHERE id = " . $id . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function salaryRangeTypeCanDelete($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE salaryrangetype = " . $id . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE jobsalaryrangetype = " . $id . " )
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE djobsalaryrangetype = " . $id . " )
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` WHERE id = " . $id . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getSalaryRangeTypesForCombo() {

        $query = "SELECT id, title AS text FROM `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` WHERE status = 1 ORDER BY ordering ASC ";
        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $rows;
    }

    function isSalaryRangeTypeExist($title) {
        if (!$title)
            return false;
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_salaryrangetypes WHERE title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getDefaultSalaryRangeTypeId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_salaryrangetypes WHERE isdefault = 1";
        $id = jsjobsdb::get_var($query);

        return $id;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'salaryrangetype') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = JSJOBSrequest::getVar("title");
        $jsjp_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjp_search_array['search_from_salaryrangetype'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'salaryrangetype') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_salaryrangetype']) && $wpjp_search_cookie_data['search_from_salaryrangetype'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['range']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : '';
        jsjobs::$_search['range']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
    }

    function getMessagekey(){
        $key = 'salaryrangetype';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
