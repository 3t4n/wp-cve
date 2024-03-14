<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCareerlevelModel {

    function getJobCareerLevelbyId($id) {
        if (is_numeric($id) == false)
            return false;

        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_careerlevels WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getAllCareerLevels() {
        // Filter
        $title = jsjobs::$_search['careerlevel']['title'];
        $status = jsjobs::$_search['careerlevel']['status'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%$title%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .=$clause . " status = " . $status;

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;

        //pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_careerlevels ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //data
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_careerlevels";
        $query .= $inquery;
        $query .= " ORDER BY ordering ASC LIMIT " . JSJOBSpagination::$_offset . " , " . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        //DB class limitations
        if (!is_numeric($id))
            return false;
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_careerlevels` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCareerlevelExist($data['title']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_careerlevels";
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

    function storeCareerLevel($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSJOBS_ALREADY_EXIST)
            return JSJOBS_ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('careerlevels');
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

    function deleteCareerLevels($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('careerlevels');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->careerLevelCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('careerlevels');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->careerLevelCanUnpublish($id)) {
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

    function careerLevelCanUnpublish($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;
        $query = " SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_careerlevels` WHERE id = " . $careerlevelid . " AND isdefault = 1 ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function careerLevelCanDelete($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE careerlevel = " . $careerlevelid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_careerlevels` WHERE id = " . $careerlevelid . " AND isdefault = 1)
                    AS total ";

        $total = jsjobsdb::get_var($query);


        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCareerLevelsForCombo() {
        $query = "SELECT id, title AS text FROM `" . jsjobs::$_db->prefix . "js_job_careerlevels` WHERE status = 1 ORDER BY ordering ASC ";
        $careerlevels = jsjobsdb::get_results($query);
        return $careerlevels;
    }

    function getDefaultCareerlevelId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_careerlevels WHERE `isdefault` = 1";
        $id = jsjobsdb::get_var($query);

        return $id;
    }


    function isCareerlevelExist($title) {
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_careerlevels WHERE title ='$title'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getSearchFormDataCareerLevel(){
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = JSJOBSrequest::getVar('title');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['search_from_careerlevel'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedCareerLevel(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_careerlevel']) && $wpjp_search_cookie_data['search_from_careerlevel'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableCareerLevel($jsjp_search_array){
        jsjobs::$_search['careerlevel']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        jsjobs::$_search['careerlevel']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }

    function getMessagekey(){
        $key = 'careerlevel';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
