<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSStateModel {

    function getStatebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_states WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getAllCountryStates($countryid) {
        if (!is_numeric($countryid))
            return false;
        //Filters
        $searchname = jsjobs::$_search['state']['searchname'];
        $city = jsjobs::$_search['state']['city'];
        $status = jsjobs::$_search['state']['status'];


        $inquery = '';
        if ($searchname) {
            $inquery .= " AND name LIKE '%" . $searchname . "%'";
        }
        if (is_numeric($status)) {
            $inquery .= " AND state.enabled = " . $status;
        }

        if ($city == 1) {
            $inquery .=" AND (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city WHERE city.stateid = state.id) > 0 ";
        }

        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['city'] = $city;


        //Pagination
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE countryid = " . $countryid;
        $query.=$inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE countryid = " . $countryid;
        $query.=$inquery;
        $query.=" ORDER BY name ASC LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function storeState($data, $countryid) {
        if (empty($data))
            return false;

        $row = JSJOBSincluder::getJSTable('state');
        $data['countryid'] = $countryid;

        if (!$data['id']) { // only for new
            $existvalue = $this->isStateExist($data['name'], $data['countryid']);
            if ($existvalue == true)
                return JSJOBS_ALREADY_EXIST;
        }

        $data['shortRegion'] = $data['name'];
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        return JSJOBS_SAVED;
    }

    function deleteStates($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('state');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->stateCanDelete($id) == true) {
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

    function stateCanDelete($stateid) {
        if (!is_numeric($stateid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(mcity.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity ON mcity.cityid=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                   +
                   ( SELECT COUNT(cmcity.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_companycities` AS cmcity ON cmcity.cityid=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS resume ON resume.address_city=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS resume ON resume.institute_city=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS resume ON resume.employer_city=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                           JOIN `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS resume ON resume.reference_city=city.id
                           WHERE city.stateid = " . $stateid . "
                   )
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function stateCanUnpublish($stateid) {
        return true;
    }

    function isStateExist($state, $countryid) {
        if (!is_numeric($countryid))
            return false;
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_states WHERE name = '$state' AND countryid = " . $countryid;
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getStatesForCombo($country) {
        if (is_null($country) OR empty($country))
            $country = 0;
        $query = "SELECT id, name AS text FROM `" . jsjobs::$_db->prefix . "js_job_states` WHERE enabled = '1' AND countryid = " . $country . " ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        if (jsjobs::$_db->last_error != null) {
            return false;
        }
        return $rows;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('state');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->stateCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'enabled' => $status))) {
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

    function getStateIdByName($name) { // new function coded
        $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_states` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . $name . "'), ' ', '') AND enabled = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }

    function storeTokenInputState($data) { // new function coded
        if (empty($data))
            return false;
        if (!isset($data['countryid']))
            return false;
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $row = JSJOBSincluder::getJSTable('state');
        if (!$row->bind($data)) {
            return false;
        }
        if (!$row->store()) {
            return false;
        }
        return true;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'state') ) {
            die( 'Security check Failed' ); 
        }
        $jsjob_search_array = array();
        $jsjob_search_array['searchname'] = JSJOBSrequest::getVar("searchname");
        $jsjob_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjob_search_array['city'] = JSJOBSrequest::getVar("city");
        $jsjob_search_array['search_from_state'] = 1;
        return $jsjob_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'state') ) {
            die( 'Security check Failed' ); 
        }
        $jsjob_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_state']) && $wpjp_search_cookie_data['search_from_state'] == 1){
            $jsjob_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjob_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjob_search_array['city'] = $wpjp_search_cookie_data['city'];
        }
        return $jsjob_search_array;
    }

    function setSearchVariableForSearch($jsjob_search_array){
        jsjobs::$_search['state']['searchname'] = isset($jsjob_search_array['searchname']) ? $jsjob_search_array['searchname'] : '';
        jsjobs::$_search['state']['status'] = isset($jsjob_search_array['status']) ? $jsjob_search_array['status'] : '';
        jsjobs::$_search['state']['city'] = isset($jsjob_search_array['city']) ? $jsjob_search_array['city'] : '';
    }

    function getMessagekey(){
        $key = 'state';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
