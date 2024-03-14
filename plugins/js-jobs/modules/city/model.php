<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCityModel {

    function getCitybyId($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_cities WHERE id = " . $id;
            jsjobs::$_data[0] = jsjobsdb::get_row($query);
        }
        return;
    }

    function getCityNamebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT name FROM `". jsjobs::$_db->prefix ."js_job_cities` WHERE id = " . $id;
        return jsjobsdb::get_var($query);
    }

    function getCoordinatesOfCities($pageid){
        /*
        $query = "SELECT city.id AS cityid, city.latitude,city.longitude
                    FROM `". jsjobs::$_db->prefix ."js_job_jobs` AS job
                    JOIN `". jsjobs::$_db->prefix ."js_job_cities` AS city ON city.id = job.city
                    JOIN `". jsjobs::$_db->prefix ."js_job_countries` AS country ON country.id = city.countryid
                    WHERE country.enabled = 1 AND job.status = 1 AND job.stoppublishing >= CURDATE() GROUP BY cityid " ;
                    */
        $query="SELECT city.id AS cityid, city.latitude,city.longitude ,count(jobc.cityid) tjob
                FROM `". jsjobs::$_db->prefix ."js_job_jobcities` AS jobc
                JOIN `". jsjobs::$_db->prefix ."js_job_jobs` AS job ON jobc.jobid = job.id
                JOIN `". jsjobs::$_db->prefix ."js_job_cities` AS city ON city.id = jobc.cityid
                JOIN `". jsjobs::$_db->prefix ."js_job_countries` AS country ON country.id = city.countryid
                WHERE country.enabled = 1 AND job.status = 1
                AND DATE(job.stoppublishing) >= CURDATE() AND DATE(job.startpublishing) <= CURDATE() GROUP BY jobc.cityid HAVING tjob > 0";
        $data = jsjobsdb::get_results($query);
        $final_array= array();
        $i = 0;
        foreach($data AS $l){
            if(is_numeric($l->latitude) && is_numeric($l->longitude) ){
                $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'city'=>$l->cityid , 'jsjobspageid' => $pageid ));
                $img =     JOB_MANAGER_IMAGE.'/location-icons/loction-mark-icon-'.$i.'.png';
                $final_array[] = array('lat' => $l->latitude, 'lng' => $l->longitude ,'link' => $link, 'img' => $img);
                $i ++;
                if($i > 10){
                    $i = 0;
                }
            }
        }
        $jfinal_array = json_encode($final_array);
        jsjobs::$_data['coordinates'] = $jfinal_array;
        return;
    }


    function getAllStatesCities($countryid, $stateid) {
        if (!is_numeric($countryid))
            return false;

        //Filter
        $searchname = jsjobs::$_search['city']['searchname'];
        $status = jsjobs::$_search['city']['status'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($searchname != null) {
            $inquery .= $clause . " name LIKE '%$searchname%'";
            $clause = ' AND ';
        }
        if (is_numeric($status)) {
            $inquery .= $clause . " enabled = " . $status;
            $clause = ' AND ';
        }

        if ($stateid) {
            if(is_numeric($stateid)){
                $inquery .=$clause . " stateid = " . $stateid;
                $clause = ' AND ';
            }
        }
        if ($countryid) {
            $inquery .= $clause . "countryid = " . $countryid;
            $clause = ' AND ';
        }

        jsjobs::$_data['filter']['searchname'] = $searchname;
        jsjobs::$_data['filter']['status'] = $status;


        //Pagination
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_cities`";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_cities`";
        $query .=$inquery;
        $query .=" ORDER BY name ASC LIMIT " . JSJOBSpagination::$_offset . " , " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function storeCity($data, $countryid, $stateid) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCityExist($countryid, $stateid, $data['name']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            }
        }

        $data['countryid'] = $countryid;
        $data['stateid'] = $stateid;
        $data['cityName'] = $data['name'];

        $row = JSJOBSincluder::getJSTable('city');
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $data = jsjobs::sanitizeData($data);
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }

        return JSJOBS_SAVED;
    }

    function deleteCities($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('city');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->cityCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('city');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->cityCanUnpublish($id)) {
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

    function cityCanUnpublish($cityid) {
        return true;
    }

    function cityCanDelete($cityid) {
        if (!is_numeric($cityid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE address_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` WHERE institute_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumeemployers` WHERE employer_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resumereferences` WHERE reference_city = " . $cityid . ")
                    AS total ";

        $total = jsjobsdb::get_var($query);

        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCityExist($countryid, $stateid, $title) {
        if (!is_numeric($countryid))
            return false;
        if (!is_numeric($stateid))
            return false;

        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_cities WHERE countryid=" . $countryid . "
		AND stateid=" . $stateid . " AND LOWER(name) = '" . jsjobslib::jsjobs_strtolower($title) . "'";

        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    private function getDataForLocationByCityID($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT city.cityName AS cityname,state.name AS statename,country.name AS countryname
                    FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                    JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                    WHERE city.id = " . $id;
        $result = jsjobsdb::get_row($query);
        return $result;
    }

    function getLocationDataForView($cityids) {
        if ($cityids == '')
            return false;
        $location = '';
        if (jsjobslib::jsjobs_strstr($cityids, ',')) { // multi cities id
            $cities = jsjobslib::jsjobs_explode(',', $cityids);
            $data = array();
            foreach ($cities AS $city) {
                $data[] = $this->getDataForLocationByCityID($city);
            }
            $databycountry = array();
            foreach ($data AS $d) {
                $city_name = isset($d->cityname) ? $d->cityname : '';
                $state_name = isset($d->statename) ? $d->statename : '';
                if(isset($d->countryname)){
                    $databycountry[$d->countryname][] = array('cityname' => $city_name, 'statename' => $state_name);
                }
            }
            foreach ($databycountry AS $countryname => $locdata) {
                $call = 0;
                foreach ($locdata AS $dl) {
                    if ($call == 0) {
                        $location .= '[' . $dl['cityname'];
                        if ($dl['statename']) {
                            $location .= '-' . $dl['statename'];
                        }
                    } else {
                        $location .= ', ' . $dl['cityname'];
                        if ($dl['statename']) {
                            $location .= '-' . $dl['statename'];
                        }
                    }
                    $call++;
                }
                $location .= ', ' . $countryname . '] ';
            }
        } else { // single city id
            $data = $this->getDataForLocationByCityID($cityids);
            if (is_object($data))
                $location = JSJOBSincluder::getJSModel('common')->getLocationForView($data->cityname, $data->statename, $data->countryname);
        }
        return $location;
    }

    function getAddressDataByCityName($cityname, $id = 0) {
        if (!is_numeric($id))
            return false;
        if (!$cityname)
            return false;


        if (jsjobslib::jsjobs_strstr($cityname, ',')) {
            $cityname = jsjobslib::jsjobs_str_replace(' ', '', $cityname);
            $array = jsjobslib::jsjobs_explode(',', $cityname);
            $cityname = $array[0];
            $countryname = $array[1];
        }

        $query = "SELECT concat(city.name";
        switch (jsjobs::$_configuration['defaultaddressdisplaytype']) {
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

        $query .= " AS name, city.id AS id,city.latitude,city.longitude
                      FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                      JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country on city.countryid=country.id
                      LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state on city.stateid=state.id";
        // if ($id == 0)
        //     $query .= " WHERE city.name LIKE '" . $cityname . "%' AND country.enabled = 1 AND city.enabled = 1 LIMIT " . JSJOBSincluder::getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
        // else
        //     $query .= " WHERE city.id = $id AND country.enabled = 1 AND city.enabled = 1";
        if ($id == 0) {
            if (isset($countryname)) {
                $query .= " WHERE city.name LIKE '" . $cityname . "%' AND country.name LIKE '" . $countryname . "%' AND country.enabled = 1 AND city.enabled = 1 AND IF(state.name is not null,state.enabled,1) = 1 LIMIT " . JSJOBSincluder::getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
                //$query .= " WHERE city.cityName LIKE '" . $cityname . "%' AND country.name LIKE '" . $countryname . "%' AND country.enabled = 1 AND city.enabled = 1 AND IF(state.name is not null,state.enabled,1) = 1 LIMIT " . JSJOBSincluder::getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
            } else {
                $query .= " WHERE city.name LIKE '" . $cityname . "%' AND country.enabled = 1 AND city.enabled = 1 AND IF(state.name is not null,state.enabled,1) = 1 LIMIT " . JSJOBSincluder::getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
                //$query .= " WHERE city.cityName LIKE '" . $cityname . "%' AND country.enabled = 1 AND city.enabled = 1 AND IF(state.name is not null,state.enabled,1) = 1 LIMIT " . JSJOBSincluder::getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
            }
        } else {
            $query .= " WHERE city.id = $id AND country.enabled = 1 AND city.enabled = 1";
        }
        $result = jsjobsdb::get_results($query);
        if (empty($result))
            return null;
        else
            return $result;
    }

    function storeTokenInputCity($input) {

        $latitude = JSJOBSrequest::getVar('latitude','','');
        $longitude = JSJOBSrequest::getVar('longitude','','');

        $tempData = jsjobslib::jsjobs_explode(',', $input); // array to maintain spaces
        $input = jsjobslib::jsjobs_str_replace(' ', '', $input); // remove spaces from citydata
        // find number of commas
        $num_commas = substr_count($input, ',', 0);
        if ($num_commas == 1) { // only city and country names are given
            $cityname = $tempData[0];
            $countryname = jsjobslib::jsjobs_str_replace(' ', '', $tempData[1]);
        } elseif ($num_commas > 1) {
            if ($num_commas > 2)
                return 5;
            $cityname = $tempData[0];
            if (mb_strpos($tempData[1], ' ') == 0) { // remove space from start of state name if exists
                $statename = jsjobslib::jsjobs_substr($tempData[1], 1, jsjobslib::jsjobs_strlen($tempData[1]));
            } else {
                $statename = $tempData[1];
            }
            $countryname = jsjobslib::jsjobs_str_replace(' ', '', $tempData[2]);
        }

        // get list of countries from database and check if exists or not
        $countryid = JSJOBSincluder::getJSModel('country')->getCountryIdByName($countryname); // new function coded
        if (!$countryid) {
            return 4;
        }
        // if state name given in input check if exists or not otherwise store in database
        if (isset($statename)) {
            $stateid = JSJOBSincluder::getJSModel('state')->getStateIdByName(jsjobslib::jsjobs_str_replace(' ', '', $statename)); // new function coded
            if (!$stateid) {
                $statedata = array();
                $statedata['id'] = null;
                $statedata['name'] = jsjobslib::jsjobs_ucwords($statename);
                $statedata['shortRegion'] = jsjobslib::jsjobs_ucwords($statename);
                $statedata['countryid'] = $countryid;
                $statedata['enabled'] = 1;
                $statedata['serverid'] = 0;

                $newstate = JSJOBSincluder::getJSModel('state')->storeTokenInputState($statedata);
                if (!$newstate) {
                    return 3;
                }
                $stateid = JSJOBSincluder::getJSModel('state')->getStateIdByName($statename); // to store with city's new record
            }
        } else {
            $stateid = null;
        }

        $data = array();
        $data['id'] = null;
        $data['cityName'] = jsjobslib::jsjobs_ucwords($cityname);
        $data['name'] = jsjobslib::jsjobs_ucwords($cityname);
        $data['stateid'] = $stateid;
        $data['countryid'] = $countryid;
        $data['isedit'] = 1;
        $data['enabled'] = 1;
        $data['serverid'] = 0;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

        $row = JSJOBSincluder::getJSTable('city');
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return 2;
        }
        if (!$row->store()) {
            return 2;
        }
        if (isset($statename)) {
            $statename = jsjobslib::jsjobs_ucwords($statename);
        } else {
            $statename = '';
        }
        $result[0] = 1;
        $result[1] = $row->id; // get the city id for forms
        $result[2] = JSJOBSincluder::getJSModel('common')->getLocationForView($row->name, $statename, $countryname); // get the city name for forms
        $result[3] = $latitude; // get the city name for forms
        $result[4] = $longitude; // get the city name for forms
        return $result;
    }

    public function savetokeninputcity() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $city_string = JSJOBSrequest::getVar('citydata');
        $result = $this->storeTokenInputCity($city_string);
        if (is_array($result)) {
            $return_value = json_encode(array('id' => $result[1], 'name' => $result[2], 'latitude'=>$result[3], 'longitude'=>$result[4] )); // send back the cityid newely created
        } elseif ($result == 2) {
            $return_value = __('Error in saving records please try again', 'js-jobs');
        } elseif ($result == 3) {
            $return_value = __('Error while saving new state', 'js-jobs');
        } elseif ($result == 4) {
            $return_value = __('Country not found', 'js-jobs');
        } elseif ($result == 5) {
            $return_value = __('Location format is not correct please enter city in this format city name, country name', 'js-jobs');
        }
        echo esc_attr($return_value);
        exit();
    }

    //search cookies data
    function getSearchFormDataCity(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchname'] = JSJOBSrequest::getVar('searchname');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['search_from_city'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedCity(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_city']) && $wpjp_search_cookie_data['search_from_city'] == 1){
            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableCity($jsjp_search_array){
        jsjobs::$_search['city']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        jsjobs::$_search['city']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }

    function getMessagekey(){
        $key = 'city';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
