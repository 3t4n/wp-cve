<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScountryModel {

    function storeCountry($data) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCountryExist($data['name']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            }
        }

        $data['shortCountry'] = jsjobslib::jsjobs_str_replace(' ', '-', $data['name']);
        $row = JSJOBSincluder::getJSTable('country');
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

    function getCountrybyId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getAllCountries() {

        $countryname = jsjobs::$_search['country']['countryname'];
        $Status = jsjobs::$_search['country']['status'];
        $states = jsjobs::$_search['country']['states'];
        $city = jsjobs::$_search['country']['city'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($countryname) {
            $inquery .= $clause . "  country.name LIKE '%" . $countryname . "%' ";
            $clause = " AND ";
        }
        if (is_numeric($Status)) {
            $inquery .= $clause . " country.enabled = " . $Status;
            $clause = " AND ";
        }

        if ($states == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE state.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        if ($city == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city WHERE city.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        jsjobs::$_data['filter']['countryname'] = $countryname;
        jsjobs::$_data['filter']['status'] = $Status;
        jsjobs::$_data['filter']['states'] = $states;
        jsjobs::$_data['filter']['city'] = $city;

        // Pagination
        $query = "SELECT COUNT(country.id)
                    FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country";
        $query .= $inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        // Data
        $query = "SELECT country.* FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country";
        $query .= $inquery;

        $query .= " ORDER BY country.name ASC LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function deleteCountries($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('country');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->countryCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('country');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->countryCanUnpublish($id)) {
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

    function countryCanUnpublish($countryid) {
        return true;
    }

    function countryCanDelete($countryid) {
        if (!is_numeric($countryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(jobcity.id)
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity
                        JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = jobcity.cityid
                        WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(companycity.id)
                            FROM `" . jsjobs::$_db->prefix . "js_job_companycities` AS companycity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = companycity.cityid
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE nationality = " . $countryid . ")
                    + ( SELECT COUNT(resumecity.id)
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` AS resumecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = resumecity.address_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(institutecity.id)
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` AS institutecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = institutecity.institute_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(employeecity.id)
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` AS employeecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = employeecity.employer_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(referencecity.id)
                            FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` AS referencecity
                            JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = referencecity.reference_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_states` WHERE countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities` WHERE countryid = " . $countryid . ")
            AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        if (!$country)
            return;
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_countries WHERE name = '" . $country . "'";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return true;
        else
            return false;
    }

    function getCountriesForCombo() {
        $query = "SELECT id , name AS text FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE enabled = 1 ORDER BY name ASC ";
        $rows = jsjobsdb::get_results($query);
        return $rows;
    }

    function getCountryIdByName($name) { // new function coded
        if (!$name)
            return;
        $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_countries` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . $name . "'), ' ', '') AND enabled = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }

    //search cookies data
    function getCountrySearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['countryname'] = JSJOBSrequest::getVar("countryname");
        $jsjp_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjp_search_array['states'] = JSJOBSrequest::getVar("states");
        $jsjp_search_array['city'] = JSJOBSrequest::getVar("city");
        $jsjp_search_array['search_from_country'] = 1;
        return $jsjp_search_array;
    }

    function getCountrySavedCookiesData(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_country']) && $wpjp_search_cookie_data['search_from_country'] == 1){
            $jsjp_search_array['countryname'] = $wpjp_search_cookie_data['countryname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['states'] = $wpjp_search_cookie_data['states'];
            $jsjp_search_array['city'] = $wpjp_search_cookie_data['city'];
        }
        return $jsjp_search_array;
    }

    function setCountrySearchVariable($jsjp_search_array){
        jsjobs::$_search['country']['countryname'] = isset($jsjp_search_array['countryname']) ? $jsjp_search_array['countryname'] : '';
        jsjobs::$_search['country']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        jsjobs::$_search['country']['states'] = isset($jsjp_search_array['states']) ? $jsjp_search_array['states'] : '';
        jsjobs::$_search['country']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : '';
    }

    function getMessagekey(){
        $key = 'country';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
