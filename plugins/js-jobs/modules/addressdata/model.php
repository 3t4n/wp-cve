<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSAddressdataModel {

    function loadaddressdata() {
        ob_start();
        if ($_FILES['loadaddressdata']['size'] > 0) {
            $file_name = sanitize_file_name($_FILES['loadaddressdata']['name']); // file name
            $file_tmp = sanitize_mime_type($_FILES['loadaddressdata']['tmp_name']); // actual location
            $file_size = sanitize_key($_FILES['loadaddressdata']['size']); // file size
            $file_type = sanitize_mime_type($_FILES['loadaddressdata']['type']); // mime type of file determined by php
            $file_error = sanitize_key($_FILES['loadaddressdata']['error']); // any error!. get reason here
            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $ext = JSJOBSincluder::getJSModel('common')->getExtension($file_name);
                if (($ext != "zip") && ($ext != "sql"))
                    return JSJOBS_FILE_TYPE_ERROR; //file type mistmathc
            }

            $path = JSJOBS_PLUGIN_PATH . 'data';
            if (!file_exists($path)) { // creating data directory
                JSJOBSincluder::getJSModel('common')->makeDir($path);
            }
            $path = JSJOBS_PLUGIN_PATH . 'data/temp';
            if (!file_exists($path)) { // creating temp directory
                JSJOBSincluder::getJSModel('common')->makeDir($path);
            }
            $comp_filename = $path . '/' . $file_name;
            move_uploaded_file($file_tmp, $path . '/' . $file_name);
            if ($ext == 'zip') {
                require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
                $archive = new PclZip($comp_filename);
                $list = $archive->listContent();
                if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
                    die("Error : " . $archive->errorInfo(true));
                }
                $comp_filename = $path . '/' . $list[0]['filename'];
            }

            $filestring = file_get_contents($comp_filename);

            $option = sanitize_text_field($_POST['datakept']);
            $fileowner = sanitize_text_field($_POST['fileowner']);
            $datacontain = sanitize_text_field($_POST['datacontain']);
            if(($filestring != false) AND ($fileowner == 2)){

                $hasstate = jsjobslib::jsjobs_strpos($filestring, '#__js_job_states');
                $hascities = jsjobslib::jsjobs_strpos($filestring, '#__js_job_cities');
                
                if (($hasstate) AND ($hascities)) {

                    $filestring = jsjobslib::jsjobs_str_replace('#__', jsjobs::$_db->prefix, $filestring);

                    $queries = jsjobslib::jsjobs_explode(';', $filestring);
                    unset($queries[count($queries) - 1]);

                    $perquery = 0;

                    echo "<style >
                                div#progressbar{display:block;width:275px;height:20px;position:relative;padding:2px;border:1px solid #E0E1E0;}
                                span#backgroundtext{position:absolute;width:275px;height:20px;top:0px;left:0px;text-align:center;}
                                span#backgroundcolour{display:block;height:20px;background:#D8E8ED;width:1%;}
                                h1{color:1A5E80;}
                            </style>";
                    echo str_pad('<html><h1>' . __('Address Data Update', 'js-jobs') . '</h1><div id="progressbar"><span id="backgroundtext">0% complete.</span><span id="backgroundcolour" style="width:1%;"></span></div></html>', 5120);
                    echo str_pad(__('LOADING'), 5120) . "<br />\n";
                    ob_flush();
                    flush();
                    if ($option == 1) { //kept data
                        $city_insert = 0;
                        $state_insert = 0;
                        echo str_pad(__('Backup', 'js-jobs'), 5120) . "<br />\n";
                        ob_flush();
                        flush();
                        if ($fileowner == 1) { // myfile
                        } elseif ($fileowner == 2) { // joomsky file
                            if ($datacontain == 1) { // states
                                $state_insert = 1;
                            } elseif ($datacontain == 2) { // cities
                                $city_insert = 1;
                            } elseif ($datacontain == 3) { // states and cities
                                $city_insert = 1;
                                $state_insert = 1;
                            }
                        }
                        if ($city_insert == 1) {
                            $drop_city = "DROP TABLE IF EXISTS `" . jsjobs::$_db->prefix . "js_job_cities_new`";
                            jsjobsdb::query($drop_city);

                            $create_cities = " CREATE TABLE `" . jsjobs::$_db->prefix . "js_job_cities_new` (
    							  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
    							  `cityName` varchar(70) DEFAULT NULL,
    							  `name` varchar(60) DEFAULT NULL,
    							  `stateid` smallint(8) DEFAULT NULL,
    							  `countryid` smallint(9) DEFAULT NULL,
    							  `isedit` tinyint(1) DEFAULT '0',
    							  `enabled` tinyint(1) NOT NULL DEFAULT '0',
    							  `serverid` int(11) DEFAULT NULL,
    							  PRIMARY KEY (`id`),
    							  KEY `countryid` (`countryid`),
    							  KEY `stateid` (`stateid`),
    							  FULLTEXT KEY `name` (`name`)
    							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";

                            jsjobsdb::query($create_cities);

                            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities_new`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
    							SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
    							FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city";

                           // jsjobsdb::query($query);
                        }
                        if ($state_insert == 1) {
                            $drop_state = "DROP TABLE IF EXISTS `" . jsjobs::$_db->prefix . "js_job_states_new`";

                            jsjobsdb::query($drop_state);

                            $create_state = "CREATE TABLE `" . jsjobs::$_db->prefix . "js_job_states_new` (
    						  `id` smallint(8) NOT NULL AUTO_INCREMENT,
    						  `name` varchar(35) DEFAULT NULL,
    						  `shortRegion` varchar(25) DEFAULT NULL,
    						  `countryid` smallint(9) DEFAULT NULL,
    						  `enabled` tinyint(1) NOT NULL DEFAULT '0',
    						  `serverid` int(11) DEFAULT NULL,
    						  PRIMARY KEY (`id`),
    						  KEY `countryid` (`countryid`),
    						  FULLTEXT KEY `name` (`name`)
    						) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";

                            jsjobsdb::query($create_state);

                            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_states_new`(id,name,shortRegion,countryid,enabled,serverid)
    							SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
    							FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state
    							";

                           // jsjobsdb::query($query);
                        }
                    } elseif ($option == 2) {// Discard old data;
                        $discaed_city = 0;
                        $discaed_state = 0;
                        echo str_pad(__('Delete', 'js-jobs'), 5120) . "<br />\n";
                        ob_flush();
                        flush();
                        if ($fileowner == 1) { // myfile
                            $discaed_city = 1;
                            $discaed_state = 1;
                        } elseif ($fileowner == 2) { // joomsky file
                            if ($datacontain == 1) { // states
                                $discaed_state = 1;
                            } elseif ($datacontain == 2) { // cities
                                $discaed_city = 1;
                            } elseif ($datacontain == 3) { // states and cities
                                $discaed_city = 1;
                                $discaed_state = 1;
                            }
                        }
                        if ($discaed_city == 1) {
                            
                            $drop_city = "DROP TABLE IF EXISTS `" . jsjobs::$_db->prefix . "js_job_cities_new`";
                            jsjobsdb::query($drop_city);

                            $q = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_cities`";
                            jsjobsdb::query($q);

                        }
                        if ($discaed_state == 1) {
                            
                            $drop_state = "DROP TABLE IF EXISTS `" . jsjobs::$_db->prefix . "js_job_states_new`";
                            jsjobsdb::query($drop_state);

                            $q = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_states`";
                            jsjobsdb::query($q);

                        }
                    }
                    echo str_pad(__('Importing New Data', 'js-jobs'), 5120) . "<br />\n";
                    ob_flush();
                    flush();

                    $percentageperquery = count($queries) - 1;
                    $percentageperquery = round( 100 / $percentageperquery , 1);
                    $percentageperquery = ($percentageperquery > 100) ? 100 : $percentageperquery;
                    if($option == 1){

                        $queries = jsjobslib::jsjobs_str_replace('js_job_states', 'js_job_states_new', $queries);
                        $queries = jsjobslib::jsjobs_str_replace('js_job_cities', 'js_job_cities_new', $queries);

                        if($state_insert == 1){
                            $q = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_states`";
                           // jsjobsdb::query($q);
                        }
                        if($city_insert == 1){
                            $q = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_cities`";
                            // jsjobsdb::query($q);
                        }
    
                        if($state_insert == 1 AND $city_insert == 1){

                        }else{
                            if($city_insert == 1){ // only cities
                                unset($queries[0]);
                            }else{ 
                                $temp = $queries;
                                $queries = array();
                                $queries[0] = $temp[0]; // only states
                            }
                        }
                    }else{
                        if($discaed_state == 1 AND $discaed_city == 1){

                        }else{
                            if($discaed_city == 1){ // only cities
                                unset($queries[0]);
                            }else{ 
                                $temp = $queries;
                                $queries = array();
                                $queries[0] = $temp[0]; // only states
                            }
                        }                        
                    }

                    $perquery = 0;

                    foreach ($queries AS $query) {
                        if (!empty($query)) {
                            jsjobsdb::query($query);
                        }
                        $perquery += $percentageperquery;
                        $perquery = ($perquery > 100) ? 100 : $perquery;
                        //This div will show loading percents
                        echo str_pad('<script >document.getElementById("backgroundcolour").style.width = "' . esc_attr($perquery) . '%";</script>', 50120);
                        echo str_pad('<script >document.getElementById("backgroundtext").innerHTML = "' . esc_attr($perquery) . '% complete.";</script>', 50120);
                        ob_flush();
                        flush();
                    }

                    if ($option == 1) {// kept data
                        if ($city_insert == 1) {
                            $removeduplicationofcities = $this->correctCityData();
                            if ($removeduplicationofcities == 0)
                                return JSJOBS_SAVE_ERROR;
                            $q = "DROP TABLE `" . jsjobs::$_db->prefix . "js_job_cities_new`";

                            jsjobsdb::query($q);
                        }
                        if ($state_insert == 1) {
                            $removeduplicationofstates = $this->correctStateData();
                            if ($removeduplicationofstates == 0)
                                return JSJOBS_SAVE_ERROR;
                            $q = "DROP TABLE `" . jsjobs::$_db->prefix . "js_job_states_new`";

                            jsjobsdb::query($q);
                        }
                    }
                    return JSJOBS_SAVED;
                }// iffile
            }
        }
        return false; //return 0 if any error occured
    }

    function correctCityData() {

        $query = "SELECT country.id AS countryid FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country ";

        $country_data = jsjobsdb::get_results($query);
        $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_cities`";

        jsjobsdb::query($query);

        foreach ($country_data AS $d) {
            switch ($d->countryid) {
                case 1:// United States Country
                    $query = "SELECT state.id AS stateid FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE countryid=" . $d->countryid;

                    $us_state_by_id = jsjobsdb::get_results($query);
                    if (is_array($us_state_by_id) AND ( !empty($us_state_by_id))) {
                        foreach ($us_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
                                            FROM `" . jsjobs::$_db->prefix . "js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";

                                if (!jsjobsdb::query($query))
                                    return 0;
                            }else {
                                $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `" . jsjobs::$_db->prefix . "js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";

                                if (!jsjobsdb::query($query))
                                    return 0;
                            }
                        }
                    }
                    break;
                case 2:
                    $query = "SELECT state.id AS stateid FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state WHERE countryid=" . $d->countryid;

                    $ca_state_by_id = jsjobsdb::get_results($query);
                    if (is_array($ca_state_by_id) AND ( !empty($ca_state_by_id))) {
                        foreach ($ca_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `" . jsjobs::$_db->prefix . "js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";

                                if (!jsjobsdb::query($query))
                                    return 0;
                            }else {
                                $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `" . jsjobs::$_db->prefix . "js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";

                                if (!jsjobsdb::query($query))
                                    return 0;
                            }
                        }
                    }
                    break;
                default:
                    $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
								SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
								FROM `" . jsjobs::$_db->prefix . "js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";

                    if (!jsjobsdb::query($query))
                        return 0;
                    break;
            }
        }
        return true;
    }

    function correctStateData() {

        $query = "SELECT country.id AS countryid FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country ";

        $country_data = jsjobsdb::get_results($query);
        $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_states`";

        jsjobsdb::query($query);

        foreach ($country_data AS $d) {
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_states`(id,name,shortRegion,countryid,enabled,serverid)
					SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
					FROM `" . jsjobs::$_db->prefix . "js_job_states_new` AS state WHERE countryid=" . $d->countryid . " group by name ";

            if (!jsjobsdb::query($query))
                return 0;
        }
        return true;
    }
    function getMessagekey(){
        $key = 'addressdata';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
