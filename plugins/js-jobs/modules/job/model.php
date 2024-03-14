<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobModel {
        public $class_prefix = '';

        function __construct(){
            if(jsjobs::$theme_chk == 1){
                $this->class_prefix = 'jsjb-jm';
            }elseif(jsjobs::$theme_chk == 2){
                $this->class_prefix = 'jsjb-jh';
            }
        }

        function setListStyleSession(){
            check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
            $listingstyle = JSJOBSrequest::getVar('styleid');
            if(jsjobs::$theme_chk == 1){
                $_SESSION['jsjb_jm_listing_style'] = $listingstyle;
            }else{
                $_SESSION['jsjb_jh_listing_style'] = $listingstyle;
            }

            return $listingstyle;
        }

        function getNewestJobsForMap_Widget($noofjobs) {
            if(!isset($noofjobs))
                $noofjobs = 0;
            if( ! is_numeric($noofjobs))
                $noofjobs = 0;
            if($noofjobs > 100)
                $noofjobs = 100;
            if($noofjobs < 0)
                $noofjobs = 0;

            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";

            $query = "SELECT job.id,job.title, job.jobcategory, job.created, cat.cat_title
                , job.city, job.latitude, job.longitude
                , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
                $alias $companyaliasid

                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                ORDER BY created DESC LIMIT " . $noofjobs;

            $result = jsjobsdb::get_results($query);

            foreach ($result AS $job) {
                if (empty($job->latitude) || empty($job->longitude)) {
                    $query = "SELECT city.cityName AS cityname, country.name AS countryname
                                FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` AS job
                                JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = job.cityid
                                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                                WHERE job.jobid = " . $job->id;
                    $job->multicity = jsjobsdb::get_results($query);
                }
            }
            $jobs = $result;
            foreach ($jobs AS $job) {
                $job->joblink = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'viewjob', 'jsjobsid'=>$job->aliasid));
            }
            $result = $jobs;
            return $result;
    }


    function getJobsByTypes_Widget($showalltypes, $haverecordss, $maximumrecords) {
        if ((!is_numeric($showalltypes)) || ( !is_numeric($haverecordss)) || ( !is_numeric($maximumrecords)))
            return false;

        $haverecords = '';
        $maxlimit = '';
        if ($haverecordss == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($maximumrecords >= 0) {
            $maxlimit = " LIMIT $maximumrecords";
        }

        if ($showalltypes == 1) {
            $haverecords = '';
            $maxlimit = '';
        }

        $inquery = " (SELECT COUNT(jobs.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS jobs
                        WHERE jobtype.id = jobs.jobtype AND jobs.status = 1
                        AND DATE(jobs.startpublishing) <= CURDATE() AND DATE(jobs.stoppublishing) >= CURDATE() ) as totaljobs";
        $query = "SELECT DISTINCT jobtype.id, jobtype.title AS objtitle , CONCAT(jobtype.alias, '-' , jobtype.id) AS aliasid , ";
        $query .= $inquery;
        $query .= " FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON jobtype.id = job.jobcategory
                    WHERE jobtype.isactive = 1 ";
        $query .= " $haverecords ORDER BY objtitle $maxlimit";


        $results = jsjobsdb::get_results($query);
        return $results;
    }

    function getJobsBycategory_Widget($showallcats, $haverecordss, $maximumrecords) {
        if ((!is_numeric($showallcats)) || ( !is_numeric($haverecordss)) || ( !is_numeric($maximumrecords)))
            return false;

        $haverecords = '';
        $maxlimit = '';
        if ($haverecordss == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($maximumrecords >= 0) {
            $maxlimit = " LIMIT " . $maximumrecords;
        }

        if ($showallcats == 1) {
            $haverecords = '';
            $maxlimit = '';
        }

        $inquery = " (SELECT COUNT(jobs.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS jobs
                        WHERE cat.id = jobs.jobcategory AND jobs.status = 1
                        AND DATE(jobs.startpublishing) <= CURDATE() AND DATE(jobs.stoppublishing) >= CURDATE() ) as totaljobs";
        $query = "SELECT DISTINCT cat.id, cat.cat_title AS objtitle , CONCAT(cat.alias,'-',cat.id) AS aliasid,";
        $query .= $inquery;
        $query .= " FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON cat.id = job.jobcategory
                    WHERE cat.isactive = 1 ";
        $query .= " $haverecords ORDER BY objtitle $maxlimit";


        $results = jsjobsdb::get_results($query);
        return $results;
    }

    function getJobsBylocation_Widget($showjobsby, $showonlyrecordhavejobs, $maximumrecords) {
        if ((!is_numeric($showjobsby)) || ( !is_numeric($showonlyrecordhavejobs)) || ( !is_numeric($maximumrecords)))
            return false;

        if ($maximumrecords > 100)
            $maximumrecords = 100;
        elseif ($maximumrecords < 0)
            $maximumrecords = 20;

        $haverecords = "";
        if ($showonlyrecordhavejobs == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($showjobsby == 1) {
            $query = "SELECT city.id AS locationid, city.cityName AS locationname, COUNT(job.id) AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid $haverecords ORDER BY totaljobs DESC , locationname ASC LIMIT " . $maximumrecords;
        } elseif ($showjobsby == 2) {
            $query = "SELECT state.id AS locationid, state.name AS locationname, COUNT(job.id) AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_states` AS state
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON state.id = city.stateid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid $haverecords ORDER BY totaljobs DESC, cityname ASC LIMIT " . $maximumrecords;
        } elseif ($showjobsby == 3) {
            $query = "SELECT country.id AS locationid, country.name AS locationname,COUNT(job.id) AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_countries` AS country
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON country.id = city.countryid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid $haverecords ORDER BY totaljobs DESC, locationname ASC LIMIT " . $maximumrecords;
        } else {
            return '';
        }

        $results = jsjobsdb::get_results($query);
        return $results;
    }

    function getJobs_Widget($typeofjobs, $noofjobs) {
        if ((!is_numeric($typeofjobs)) || ( !is_numeric($noofjobs)))
            return '';
        $col = '';
        if ($typeofjobs == 1) { // newest jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() ORDER BY job.created DESC LIMIT " . $noofjobs;
        } elseif ($typeofjobs == 2) { //top jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() ORDER BY job.hits DESC LIMIT " . $noofjobs;
        } elseif ($typeofjobs == 3) { // hot jobs
            $col = ' COUNT(ja.jobid) as totalapply , ';
            $inquery = " JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS ja ON ja.jobid = job.id WHERE job.status = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing >= CURDATE() GROUP BY ja.jobid ORDER BY totalapply DESC LIMIT " . $noofjobs;
        } elseif ($typeofjobs == 4) { // gold jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.endgolddate) > CURDATE() AND job.isgoldjob = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing >= CURDATE() ORDER BY job.created DESC LIMIT " . $noofjobs;
        } elseif ($typeofjobs == 5) { // featured jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.endfeatureddate) > CURDATE() AND job.isfeaturedjob = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing >= CURDATE() ORDER BY job.created DESC LIMIT " . $noofjobs;
        } else {
            return '';
        }

        $query = "SELECT $col job.id AS jobid,job.title,job.created,job.city,CONCAT(job.alias,'-',job.id) AS jobaliasid,
                 cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, CONCAT(company.alias,'-',company.id) AS companyaliasid,
                 jobtype.title AS jobtypetitle
                 FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                 JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                 JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 ";
        $query .= $inquery;
        $results = jsjobsdb::get_results($query);
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
        }

        return $results;
    }

    function getTopJobs() {

        $result = array();
        $query = "SELECT job.id,job.title AS jobtitle,company.name AS companyname,cat.cat_title AS cattile,job.stoppublishing,
        salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto,currency.symbol
        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
        JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid ORDER BY job.created desc LIMIT 5";

        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function approveQueueJobModel($id) {
        if (is_numeric($id) == false) return false;

        $row = JSJOBSincluder::getJSTable('job');
        if($row->load($id)){
            $row->columns['status'] = 1;
            $startpublishing = jsjobslib::jsjobs_strtotime($row->startpublishing);
            $stoppublishing = jsjobslib::jsjobs_strtotime($row->stoppublishing);
            $datediff = $stoppublishing - $startpublishing;
            $diff_days = floor($datediff/(60*60*24));
            $row->columns['startpublishing'] = date('Y-m-d H:i:s');
            $row->columns['stoppublishing'] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime(" +$diff_days days"));
            if(!$row->store()){
                return JSJOBS_APPROVE_ERROR;
            }
        }else{
            return JSJOBS_APPROVE_ERROR;
        }
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(2, 3, $id); // 2 for job,3 for Approve or reject Job
        return JSJOBS_APPROVED;
    }

    function rejectQueueJobModel($id) {
        if (is_numeric($id) == false)
            return false;

        $row = JSJOBSincluder::getJSTable('job');
        if (!$row->update(array('id' => $id , 'status' => -1))) {
            return JSJOBS_REJECT_ERROR;
        }

        $company_approve_email = JSJOBSincluder::getJSModel('emailtemplate')->sendMail(1, -1, $id);
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(2, 3, $id); // 2 for job,3 for reject or approve  Job
        return JSJOBS_APPROVED;
    }

    function approveQueueAllJobsModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->approveQueueJobModel($id);
        return $result;
    }

    function rejectQueueAllJobsModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->rejectQueueJobModel($id);
        return $result;
    }

    function getMultiCityData($jobid) {
        if (!is_numeric($jobid))
            return false;

        $query = "SELECT mjob.*,city.id AS cityid,city.cityName AS cityname ,state.name AS statename,country.name AS countryname
                FROM " . jsjobs::$_db->prefix . "js_job_jobcities AS mjob
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_cities AS city on mjob.cityid=city.id
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_states AS state on city.stateid=state.id
                LEFT JOIN " . jsjobs::$_db->prefix . "js_job_countries AS country on city.countryid=country.id
                WHERE mjob.jobid=" . $jobid;

        $data = jsjobsdb::get_results($query);
        if (is_array($data) AND ! empty($data)) {
            $i = 0;
            $multicitydata = "";
            foreach ($data AS $multicity) {
                $last_index = count($data) - 1;
                if ($i == $last_index)
                    $multicitydata.=$multicity->cityname;
                else
                    $multicitydata.=$multicity->cityname . " ,";
                $i++;
            }
            if ($multicitydata != "") {
                $mc = __('JS multi city', 'js-jobs');
                $multicity = (jsjobslib::jsjobs_strlen($multicitydata) > 35) ? $mc . jsjobslib::jsjobs_substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
            } else
                return;
        }
    }

    function getSearchOptions() {
        $searchjobconfig = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('searchjob');

        $searchoptions = array();
        $companies = JSJOBSincluder::getJSModel('company')->getAllCompaniesForSearchForCombo(__('JS search all', 'js-jobs'));
        $job_type = JSJOBSincluder::getJSModel('jobtype')->getJobType(__('JS_SEARCH_ALL'));
        $jobstatus = JSJOBSincluder::getJSModel('jobstatus')->getJobStatus(__('JS_SEARCH_ALL'));
        $heighesteducation = JSJOBSincluder::getJSModel('highesteducation')->getHeighestEducation(__('JS search all', 'js-jobs'));
        $job_categories = JSJOBSincluder::getJSModel('category')->getCategoriesForCombo(__('JS search all', 'js-jobs'), '');
        $job_salaryrange = JSJOBSincluder::getJSModel('salaryrange')->getJobSalaryRangeForCombo(__('JS search all', 'js-jobs'), '');
        $shift = JSJOBSincluder::getJSModel('shift')->getShift(__('JS search all', 'js-jobs'));
        $countries = JSJOBSincluder::getJSModel('country')->getCountriesForCombo('');

        if (!isset($this->_config)) {
            $this->_config = JSJOBSincluder::getJSModel('configuration')->getConfig();
        }
        $searchoptions['country'] = JSJOBSformfield::select('select.genericList', $countries, 'country', 'class="inputbox required" ' . 'onChange="dochange(\'state\', this.value)"', 'value', 'text', '');
        if (isset($states[1]))
            if ($states[1] != '')
                $searchoptions['state'] = JSJOBSformfield::select('select.genericList', $states, 'state', 'class="inputbox" ' . 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
        if (isset($cities[1]))
            if ($cities[1] != '')
                $searchoptions['city'] = JSJOBSformfield::select('select.genericList', $cities, 'city', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['companies'] = JSJOBSformfield::select('select.genericList', $companies, 'company', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobcategory'] = JSJOBSformfield::select('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobsalaryrange'] = JSJOBSformfield::select('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
        $searchoptions['salaryrangefrom'] = JSJOBSformfield::select('select.genericList', JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(__('JS From', 'js-jobs')), 'salaryrangefrom', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
        $searchoptions['salaryrangeto'] = JSJOBSformfield::select('select.genericList', JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(__('JS To')), 'salaryrangeto', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
        $searchoptions['salaryrangetypes'] = JSJOBSformfield::select('select.genericList', JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', 2);
        $searchoptions['jobstatus'] = JSJOBSformfield::select('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobtype'] = JSJOBSformfield::select('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['heighestfinisheducation'] = JSJOBSformfield::select('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['shift'] = JSJOBSformfield::select('select.genericList', $shift, 'shift', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['currency'] = JSJOBSformfield::select('select.genericList', JSJOBSincluder::getJSModel('currency')->getCurrency(), 'currency', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
        $result = array();
        $result[0] = $searchoptions;
        $result[1] = $searchjobconfig;
        return $result;
    }

    function getJobbyIdForView($job_id) {

        if (is_numeric($job_id) == false) return false;
        global $job_manager_options;
        $query = "SELECT job.*,company.url AS companyurl,company.logofilename,company.city AS compcity,company.isgoldcompany,company.isfeaturedcompany,cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
                , jobstatus.title AS jobstatustitle, shift.title as shifttitle
                , department.name AS departmentname
                , cant.name AS workpermittitle
                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salarytype
                , education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle,job.iseducationminimax
                , experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle,job.isexperienceminimax
                ,currency.symbol ,ageto.title AS ageto ,agefrom.title AS agefrom,company.facebook,company.twitter,company.linkedin,company.googleplus
                ,LOWER(jobtype.title) AS jobtypetit,careerlevel.title AS careerleveltitle
        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
        JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
        JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS cant ON job.workpermit = cant.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_departments` AS department ON job.departmentid = department.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS education ON job.educationid = education.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS mineducation ON job.mineducationrange = mineducation.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS maxeducation ON job.maxeducationrange = maxeducation.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS experience ON job.experienceid = experience.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS minexperience ON job.minexperiencerange = minexperience.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS maxexperience ON job.maxexperiencerange = maxexperience.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_shifts` AS shift ON job.shift = shift.id
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_ages` AS agefrom ON agefrom.id = job.agefrom
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_ages` AS ageto ON ageto.id = job.ageto
        LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_careerlevels` AS careerlevel ON careerlevel.id = job.careerlevel
        WHERE  job.id = " . $job_id;

        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        jsjobs::$_data[0]->multicity = JSJOBSincluder::getJSModel('jsjobs')->getMultiCityDataForView($job_id, 1);
        jsjobs::$_data[0]->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView(jsjobs::$_data[0]->symbol, jsjobs::$_data[0]->salaryfrom, jsjobs::$_data[0]->salaryto, jsjobs::$_data[0]->salarytype);
        jsjobs::$_data[0]->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView(jsjobs::$_data[0]->compcity);
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        $string = "'company', 'jobapply','social'";
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigForMultiple($string);
        $theme = wp_get_theme();

        $layout = JSJOBSrequest::getVar('jsjobslt');
        if($layout == 'viewjob'){
            if(jsjobs::$_data[0] != '' && jsjobs::$_data[0]->metakeywords != '' ){
                $_SESSION['m_keywords'] = jsjobs::$_data[0]->metakeywords;
            }
        }

        if(jsjobs::$theme_chk != 0){
            // Related Jobs data
            $max = $job_manager_options['maximum_relatedjobs'];
            $finaljobs = array();
            $relatedjobs=array();
            //var_dump($job_manager_options['relatedjob_criteria_sorter']['enabled']);
            foreach($job_manager_options['relatedjob_criteria_sorter']['enabled'] AS $key => $value){
                $inquery = '';
                switch($key){
                    case 'type':
                        if(jsjobs::$_data[0]->jobtype != ''){
                            $inquery = ' job.jobtype = ' . jsjobs::$_data[0]->jobtype;
                        }
                    break;
                    case 'category':
                        if(jsjobs::$_data[0]->jobtype != ''){
                            $inquery = ' job.jobcategory = ' . jsjobs::$_data[0]->jobcategory;
                        }
                    break;
                    case 'location':
                        if(jsjobs::$_data[0]->city != ''){
                            $inquery = ' job.city IN (' . jsjobs::$_data[0]->city .')';
                        }
                    break;
                }
                if(!empty($inquery)){
                    $query = "SELECT job.id,job.title,job.alias,job.created,job.city AS jobcity,company.id AS companyid,company.url AS companyurl,company.logofilename,company.city AS compcity,company.isgoldcompany,company.isfeaturedcompany,cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
                            , jobstatus.title AS jobstatustitle,job.created
                            , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salarytype
                            ,currency.symbol ,company.facebook,company.twitter,company.linkedin,company.googleplus
                            ,LOWER(jobtype.title) AS jobtypetit

                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                    JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                    JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                    AND ".$inquery." AND job.id != $job_id LIMIT ".$max;
                    $result = jsjobsdb::get_results($query);
                    $relatedjobs = array_merge($relatedjobs, $result);
                    $relatedjobs = array_map('unserialize', array_unique(array_map('serialize', $relatedjobs)));
                    if(COUNT($relatedjobs) >= $max){
                        break;
                    }
                }
            }
            if(!empty($relatedjobs)){
                foreach ($relatedjobs AS $d) {
                    $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->jobcity);
                    $finaljobs[] = $d;
                }
            }
            jsjobs::$_data['relatedjobs'] = $finaljobs;
        }
        //update the job view counter
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_jobs` SET hits = hits + 1 WHERE id = " . $job_id;
        jsjobs::$_db->query($query);
        return;
    }

    function getJobTitleById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT job.title FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE job.id = " . $id;
        $jobname = jsjobs::$_db->get_var($query);
        return $jobname;
    }

    function getJobsExpiryStatus($jobid) {
        if (!is_numeric($jobid))
            return false;
        $curdate = date_i18n('Y-m-d');
        $query = "SELECT job.id
        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
        WHERE job.status = 1 AND DATE(job.stoppublishing) >= DATE('" . $curdate . "')
        AND job.id =" . $jobid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }


    function getJobbyId($c_id) {
        if ($c_id) {
            if (!is_numeric($c_id))
                return false;
            $query = "SELECT job.* ,cat.cat_title, salary.rangestart, salary.rangeend
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                WHERE job.id = " . $c_id;
            jsjobs::$_data[0] = jsjobsdb::get_row($query);
        }
        if (isset(jsjobs::$_data[0])) {
            jsjobs::$_data[0]->multicity = JSJOBSincluder::getJSModel('common')->getMultiSelectEdit($c_id, 1);
            jsjobs::$_data[0]->jobtags = JSJOBSincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn( jsjobs::$_data[0]->tags );
        }
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2); // job fields
    }

    function sorting() {
        $pagenum = JSJOBSrequest::getVar('pagenum');
        jsjobs::$_data['sorton'] = isset(jsjobs::$_search['jobs']['sorton']) ? jsjobs::$_search['jobs']['sorton'] : 6;
        jsjobs::$_data['sortby'] = isset(jsjobs::$_search['jobs']['sortby']) ? jsjobs::$_search['jobs']['sortby'] : 2;
        switch (jsjobs::$_data['sorton']) {
            case 6: // created
                jsjobs::$_data['sorting'] = ' job.created ';
                break;
            case 2: // company name
                jsjobs::$_data['sorting'] = ' company.name ';
                break;
            case 3: // category
                jsjobs::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 5: // location
                jsjobs::$_data['sorting'] = ' city.cityName ';
                break;
            case 7: // status
                jsjobs::$_data['sorting'] = ' job.jobstatus ';
                break;
            case 1: // job title
                jsjobs::$_data['sorting'] = ' job.title ';
                break;
            case 4: // job type
                jsjobs::$_data['sorting'] = ' jobtype.title ';
                break;
        }
        if (jsjobs::$_data['sortby'] == 1) {
            jsjobs::$_data['sorting'] .= ' ASC ';
        } else {
            jsjobs::$_data['sorting'] .= ' DESC ';
        }
        jsjobs::$_data['combosort'] = jsjobs::$_data['sorton'];
    }

    function getAllJobs() {
        $this->sorting();
        //filters
        $searchtitle = jsjobs::$_search['jobs']['searchtitle'];
        $searchcompany = jsjobs::$_search['jobs']['searchcompany'];
        $searchjobcategory = jsjobs::$_search['jobs']['searchjobcategory'];
        $searchjobtype = jsjobs::$_search['jobs']['searchjobtype'];
        $status = jsjobs::$_search['jobs']['status'];
        $datestart = jsjobs::$_search['jobs']['datestart'];
        $dateend = jsjobs::$_search['jobs']['dateend'];
        $location = jsjobs::$_search['jobs']['location'];

        jsjobs::$_data['filter']['searchtitle'] = $searchtitle;
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['searchjobtype'] = $searchjobtype;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;
        jsjobs::$_data['filter']['location'] = $location;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($status)
            if (is_numeric($status) == false)
                return false;

        $this->checkCall();
        $curdate = date('Y-m-d');
        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(job.title) LIKE '%" . $searchtitle . "%'";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . $searchcompany . "%'";
        if ($searchjobcategory)
            $inquery .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $inquery .= " AND job.jobtype = " . $searchjobtype;
        if ($dateend != null){
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(job.created) <= '" . $dateend . "'";
        }
        if ($datestart != null){
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(job.created) >= '" . $datestart . "'";
        }
        if ($status != null)
            $inquery .= " AND job.status = " . $status;
        if ($location != null)
            $inquery .= " AND city.cityName LIKE '%" . $location . "%'";

        $query = "SELECT COUNT(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                WHERE job.status != 0";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, company.name AS companyname ,company.logofilename AS logo ,company.id AS companyid,
                ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = job.id) AS totalresume
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                WHERE job.status != 0";
        $query.=$inquery;
        $query.= " ORDER BY" . jsjobs::$_data['sorting'];
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(2);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('job');

        return;
    }

    function getAllUnapprovedJobs() {
        $this->sorting();
        //filters
        $searchtitle = jsjobs::$_search['jobs']['searchtitle'];
        $searchcompany = jsjobs::$_search['jobs']['searchcompany'];
        $searchjobcategory = jsjobs::$_search['jobs']['searchjobcategory'];
        $searchjobtype = jsjobs::$_search['jobs']['searchjobtype'];
        $status = jsjobs::$_search['jobs']['status'];
        $datestart = jsjobs::$_search['jobs']['datestart'];
        $dateend = jsjobs::$_search['jobs']['dateend'];
        $location = jsjobs::$_search['jobs']['location'];

        jsjobs::$_data['filter']['searchtitle'] = $searchtitle;
        jsjobs::$_data['filter']['searchcompany'] = $searchcompany;
        jsjobs::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        jsjobs::$_data['filter']['searchjobtype'] = $searchjobtype;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['datestart'] = $datestart;
        jsjobs::$_data['filter']['dateend'] = $dateend;
        jsjobs::$_data['filter']['location'] = $location;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($status)
            if (is_numeric($status) == false)
                return false;

        $this->checkCall();

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(job.title) LIKE '%" . $searchtitle . "%'";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . $searchcompany . "%'";
        if ($searchjobcategory)
            $inquery .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $inquery .= " AND job.jobtype = " . $searchjobtype;
        if ($dateend != null){
            $dateend = date('Y-m-d',jsjobslib::jsjobs_strtotime($dateend));
            $inquery .= " AND DATE(job.created) <= '" . $dateend . "'";
        }
        if ($datestart != null){
            $datestart = date('Y-m-d',jsjobslib::jsjobs_strtotime($datestart));
            $inquery .= " AND DATE(job.created) >= '" . $datestart . "'";
        }
        if ($status != null)
            $inquery .= " AND job.status = $status";
        if ($location != null)
            $inquery .= " AND city.cityName LIKE '%" . $location . "%'";

        $query = "SELECT COUNT(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                 WHERE (job.status = 0)";
        $query.=$inquery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle,company.logofilename AS logofilename, company.name AS companyname ,
                ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = job.id) AS totalresume
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT cityid FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                WHERE (job.status = 0)";
        $query.=$inquery;
        $query.= " ORDER BY" . jsjobs::$_data['sorting'];
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforView(2);

        return;
    }

    function storeJob($data) {
        if (empty($data))
            return false;
        $row = JSJOBSincluder::getJSTable('job');

        $dateformat = jsjobs::$_configuration['date_format'];
        if ($data['id'] == '') {
            //$expiry = get from config;
            $expiry = 30;
            if(!isset($data['stoppublishing'])){
                $data['stoppublishing'] = date($dateformat,jsjobslib::jsjobs_strtotime('+'.$expiry.' days') );
            }
        }
        if(isset($data['startpublishing'])){
            $data['startpublishing'] = date('Y-m-d H:i:s', jsjobslib::jsjobs_strtotime($data['startpublishing']));
        }
        if(isset($data['stoppublishing'])){
            $data['stoppublishing'] = date('Y-m-d H:i:s', jsjobslib::jsjobs_strtotime($data['stoppublishing']));
        }
        $data['jobapplylink'] = isset($data['jobapplylink']) ? 1 : 0;
        if (!empty($data['alias']))
            $jobalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $jobalias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['title']);

        $jobalias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;

        $data['uid'] = JSJOBSincluder::getJSModel('company')->getUidByCompanyId($data['companyid']); // Uid must be the same as the company owner id

        if ($data['id'] == '') {
            $data['jobid'] = $this->getJobId();
            $data['created'] = date_i18n("Y-m-d H:i:s");
            if (!is_admin()) {
                $data['status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('jobautoapprove');
                if (isset($data['uid'])) {
                    if($data['uid'] != JSJOBSincluder::getObjectClass('user')->uid()){
                        $data['uid'] = JSJOBSincluder::getObjectClass('user')->uid();
                    }
                }
            }
        } else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }

        $data = jsjobs::sanitizeData($data);
        $data['description'] = JSJOBSincluder::getJSModel('common')->getSanitizedEditorData($_POST['description']);
        $data['qualifications'] = wp_filter_post_kses($_POST['qualifications']);
        $data['prefferdskills'] = wp_filter_post_kses($_POST['prefferdskills']);
        $data['agreement'] = wp_filter_post_kses($_POST['agreement']);
        //custom field code start
        $userfieldforjob = JSJOBSincluder::getJSModel('fieldordering')->getUserfieldsfor(2);
        $params = array();
        foreach ($userfieldforjob AS $ufobj) {
            if ($ufobj->userfieldtype == 'date') {
                $vardata = isset($data[$ufobj->field]) ? date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($data[$ufobj->field])) : '';
            } else {
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if($vardata != ''){
                // if($ufobj->userfieldtype == 'multiple'){ // multiple field change its behave
                //     $vardata = jsjobslib::jsjobs_explode(',', $vardata[0]); // fixed index
                // }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = jsjobslib::jsjobs_htmlspecialchars($vardata);
            }
        }
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        $data['params'] = $params;
        if(!isset($data['jobapplylink'])){
            $data['jobapplylink'] = 0;
        }

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
        $jobid = $row->id;
        $actionid = $data['creditid'];
        if ($data['id'] == '') {
            JSJOBSincluder::getJSModel('emailtemplate')->sendMail(2, 1, $row->id); // 2 for Job,1 for add new Job
        } else {
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
        }
        if (isset($data['city']))
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) && $storemulticity == false)
            return false;

        return JSJOBS_SAVED;
    }

    function captchaValidate() {
        if (!is_user_logged_in()) {
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
            if ($config_array['job_captcha'] == 1) {
                if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                    $gresponse = sanitize_text_field($_POST['g-recaptcha-response']);
                    $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                    if ($resp) {
                        return true;
                    } else {
                        jsjobs::$_data['google_captchaerror'] = __("Invalid captcha","js-jobs");
                        return false;
                    }
                } else { // own captcha
                    $captcha = new JSJOBScaptcha;
                    $result = $captcha->checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function storeMultiCitiesJob($city_id, $jobid) { // city id comma seprated
        if (!is_numeric($jobid))
            return false;

        $query = "SELECT cityid FROM " . jsjobs::$_db->prefix . "js_job_jobcities WHERE jobid = " . $jobid;

        $old_cities = jsjobsdb::get_results($query);

        $id_array = jsjobslib::jsjobs_explode(",", $city_id);
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
                $query = "DELETE FROM " . jsjobs::$_db->prefix . "js_job_jobcities WHERE jobid = " . $jobid . " AND cityid=" . $oldcityid->cityid;
                if (!jsjobsdb::query($query)) {
                    $error[] = jsjobs::$_db->last_error;
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
                $row = JSJOBSincluder::getJSTable('jobcities');
                $cols['jobid'] = $jobid;
                $cols['cityid'] = $cityid;
                if (!$row->bind($cols)) {
                    $error[] = jsjobs::$_db->last_error;
                }
                if (!$row->store()) {
                    $error[] = jsjobs::$_db->last_error;
                }
            }
        }
        if (empty($error))
            return true;
        else
            return false;
    }

    function deleteJobs($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('job');
        $notdeleted = 0;

        foreach ($ids as $id) {
            if ($this->jobCanDelete($id) == true) {
                $resultforsendmail = JSJOBSincluder::getJSModel('job')->getJobInfoForEmail($id);
                $mailextradata['jobtitle'] = $resultforsendmail->jobtitle;
                $mailextradata['companyname'] = $resultforsendmail->companyname;
                $mailextradata['user'] = $resultforsendmail->user;
                $mailextradata['useremail'] = $resultforsendmail->useremail;

                if (!$row->delete($id)) {
                    $notdeleted += 1;
                } else {
                    $query = "DELETE FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE jobid = " . $id;
                    jsjobsdb::query($query);
                    JSJOBSincluder::getJSModel('emailtemplate')->sendMail(2, 2, $id,$mailextradata); // 2 for job,2 for DELETE job
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

    function jobCanDelete($jobid) {
        if (!is_numeric($jobid))
            return false;
        if(!is_admin()){
            if(!$this->getIfJobOwner($jobid)){
                return false;
            }
        }
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE jobid = " . $jobid . ")
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getJobInfoForEmail($jobid) {
        if ((is_numeric($jobid) == false))
            return false;
        $query = "SELECT job.title AS jobtitle, company.name AS companyname, company.contactname AS user, company.contactemail AS useremail
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid=company.id
                    WHERE job.id =" . $jobid;
        $return_value = jsjobsdb::get_row($query);
        return $return_value;
    }

    function jobEnforceDelete($jobid, $uid) {
        // This function is not using the $uid var why we pass it?
        // if ($uid)
        //     if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
        //         return false;
        if (is_numeric($jobid) == false)
            return false;
        $serverjodid = 0;
        $query = "DELETE  job,apply,jobcity
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity ON job.id=jobcity.jobid
                    WHERE job.id = " . $jobid;

        if (!jsjobsdb::query($query)) {
            return JSJOBS_DELETE_ERROR;
        }
        return JSJOBS_DELETED;
    }

    function checkCall() {
        // DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = configvalue+1 WHERE configname = 'jsjobupdatecount'";
        jsjobsdb::query($query);
        $query = "SELECT configvalue AS jsjobupdatecount FROM `" . jsjobs::$_db->prefix . "js_job_config` WHERE configname = 'jsjobupdatecount'";
        $result = jsjobsdb::get_var($query);
        if ($result >= 100) {
            JSJOBSincluder::getJSModel('jsjobs')->getConcurrentrequestdata();
        }
    }

    function getJobId() {

        $query = "Select jobid from `" . jsjobs::$_db->prefix . "js_job_jobs`";
        $match = '';
        do {

            $jobid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ!@#";
            // we refer to the length of $possible a few times, so let's grab it now
            $maxlength = jsjobslib::jsjobs_strlen($possible);
            // check for length overflow and truncate if necessary
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            // set up a counter for how many characters are in the password so far
            $i = 0;
            // add random characters to $password until $length is reached
            while ($i < $length) {
                // pick a random character from the possible ones
                $char = jsjobslib::jsjobs_substr($possible, mt_rand(0, $maxlength - 1), 1);
                // have we already used this character in $password?

                if (!jsjobslib::jsjobs_strstr($jobid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $jobid .= $char;
                            $i++;
                        }
                    } else {
                        $jobid .= $char;
                        $i++;
                    }
                }
            }

            $rows = jsjobsdb::get_results($query);
            foreach ($rows as $row) {
                if ($jobid == $row->jobid)
                    $match = 'Y';
                else
                    $match = 'N';
            }
        }while ($match == 'Y');
        return $jobid;
    }

    function getJobSearch() {

        //Filters
        $title = JSJOBSrequest::getVar('title');
        $jobcategory = JSJOBSrequest::getVar('jobcategory');
        $jobsubcategory = JSJOBSrequest::getVar('jobsubcategory');
        $jobtype = JSJOBSrequest::getVar('jobtype');
        $jobstatus = JSJOBSrequest::getVar('jobstatus');
        $salaryrangefrom = JSJOBSrequest::getVar('salaryrangefrom');
        $salaryrangeto = JSJOBSrequest::getVar('salaryrangeto');
        $salaryrangetype = JSJOBSrequest::getVar('salaryrangetype');
        $shift = JSJOBSrequest::getVar('shift');
        $durration = JSJOBSrequest::getVar('durration');
        $startpublishing = JSJOBSrequest::getVar('startpublishing');
        $stoppublishing = JSJOBSrequest::getVar('stoppublishing');
        $company = JSJOBSrequest::getVar('company');
        $city = JSJOBSrequest::getVar('city');
        $zipcode = JSJOBSrequest::getVar('zipcode');
        $currency = JSJOBSrequest::getVar('currency');
        $longitude = JSJOBSrequest::getVar('longitude');
        $latitude = JSJOBSrequest::getVar('latitude');
        $radius = JSJOBSrequest::getVar('radius');
        $radius_length_type = JSJOBSrequest::getVar('radius_length_type');
        $keywords = JSJOBSrequest::getVar('keywords');

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['jobcategory'] = $jobcategory;
        jsjobs::$_data['filter']['jobsubcategory'] = $jobsubcategory;
        jsjobs::$_data['filter']['jobtype'] = $jobtype;
        jsjobs::$_data['filter']['jobstatus'] = $jobstatus;
        jsjobs::$_data['filter']['salaryrangefrom'] = $salaryrangefrom;
        jsjobs::$_data['filter']['salaryrangeto'] = $salaryrangeto;
        jsjobs::$_data['filter']['salaryrangetype'] = $salaryrangetype;
        jsjobs::$_data['filter']['shift'] = $shift;
        jsjobs::$_data['filter']['durration'] = $durration;
        jsjobs::$_data['filter']['startpublishing'] = $startpublishing;
        jsjobs::$_data['filter']['stoppublishing'] = $stoppublishing;
        jsjobs::$_data['filter']['company'] = $company;
        jsjobs::$_data['filter']['city'] = $city;
        jsjobs::$_data['filter']['zipcode'] = $zipcode;
        jsjobs::$_data['filter']['currency'] = $currency;
        jsjobs::$_data['filter']['longitude'] = $longitude;
        jsjobs::$_data['filter']['latitude'] = $latitude;
        jsjobs::$_data['filter']['radius'] = $radius;
        jsjobs::$_data['filter']['radius_length_type'] = $radius_length_type;
        jsjobs::$_data['filter']['keywords'] = $keywords;

        if ($jobcategory != '')
            if (is_numeric($jobcategory) == false)
                return false;
        if ($jobsubcategory != '')
            if (is_numeric($jobsubcategory) == false)
                return false;
        if ($jobtype != '')
            if (is_numeric($jobtype) == false)
                return false;
        if ($jobstatus != '')
            if (is_numeric($jobstatus) == false)
                return false;
        if ($salaryrangefrom != '')
            if (is_numeric($salaryrangefrom) == false)
                return false;
        if ($salaryrangeto != '')
            if (is_numeric($salaryrangeto) == false)
                return false;
        if ($salaryrangetype != '')
            if (is_numeric($salaryrangetype) == false)
                return false;
        if ($shift != '')
            if (is_numeric($shift) == false)
                return false;
        if ($company != '')
            if (is_numeric($company) == false)
                return false;
        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;


        $dateformat = jsjobs::$_configuration['date_format'];
        if ($startpublishing != '') {
            $startpublishing = date('Y-m-d', jsjobslib::jsjobs_strtotime($startpublishing));
        }
        if ($stoppublishing != '') {
            $stoppublishing = date('Y-m-d', jsjobslib::jsjobs_strtotime($stoppublishing));
        }

        $issalary = '';
        //for radius search
        switch ($radius_length_type) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if ($keywords) {// For keyword Search
            $keywords = jsjobslib::jsjobs_explode(' ', $keywords);
            $length = count($keywords);
            if ($length <= 5) {// For Limit keywords to 5
                $i = $length;
            } else {
                $i = 5;
            }
            for ($j = 0; $j < $i; $j++) {
                $keys[] = " job.metakeywords Like '%" . $keywords[$j] . "%'";
            }
        }
        $selectdistance = " ";
        if ($longitude != '' && $latitude != '' && $radius != '') {
            $radiussearch = " acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
            $selectdistance = " ,acos((sin(PI()*$latitude/180)*sin(PI()*job.latitude/180))+(cos(PI()*$latitude/180)*cos(PI()*job.latitude/180)*cose(PI()*job.longitude/180 - PI()*$longitude/180)))*$radiuslength AS distance ";
        }

        $wherequery = '';
        if ($title != '') {
            $title_keywords = jsjobslib::jsjobs_explode(' ', $title);
            $tlength = count($title_keywords);
            if ($tlength <= 5) {// For Limit keywords to 5
                $r = $tlength;
            } else {
                $r = 5;
            }
            for ($k = 0; $k < $r; $k++) {
                $t_keywords = jsjobslib::jsjobs_str_replace("'", "", $title_keywords[$k]);
                $titlekeys[] = " job.title LIKE '%" . $t_keywords . "%'";
            }
        }
        if ($jobcategory != '')
            if ($jobcategory != '')
                $wherequery .= " AND job.jobcategory = " . $jobcategory;
        if (isset($keys))
            $wherequery .= " AND ( " . implode(' OR ', $keys) . " )";
        if (isset($titlekeys))
            $wherequery .= " AND ( " . implode(' OR ', $titlekeys) . " )";
        if ($jobsubcategory != '')
            $wherequery .= " AND job.subcategoryid = " . $jobsubcategory;
        if ($jobtype != '')
            $wherequery .= " AND job.jobtype = " . $jobtype;
        if ($jobstatus != '')
            $wherequery .= " AND job.jobstatus = " . $jobstatus;
        if ($salaryrangefrom != '') {
            $query = "SELECT salfrom.rangestart
            FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salfrom
            WHERE salfrom.id = " . $salaryrangefrom;

            $rangestart_value = jsjobsdb::get_var($query);
            $wherequery .= " AND salaryrangefrom.rangestart >= " . $rangestart_value;
            $issalary = 1;
        }
        if ($salaryrangeto != '') {
            $query = "SELECT salto.rangestart
            FROM `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salto
            WHERE salto.id = " . $salaryrangeto;

            $rangeend_value = jsjobsdb::get_var($query);
            $wherequery .= " AND salaryrangeto.rangeend <= " . $rangeend_value;
            $issalary = 1;
        }
        if (($issalary != '') && ($salaryrangetype != '')) {
            $wherequery .= " AND job.salaryrangetype = " . $salaryrangetype;
        }
        if ($shift != '')
            $wherequery .= " AND job.shift = " . $shift;
        if ($durration != '')
            $wherequery .= " AND job.duration LIKE '" . $durration . "'";
        if ($startpublishing != '')
            $wherequery .= " AND job.startpublishing >= '" . $startpublishing . "'";
        if ($stoppublishing != '')
            $wherequery .= " AND job.stoppublishing <= '" . $stoppublishing . "'";
        if ($company != '')
            $wherequery .= " AND job.companyid = " . $company;
        if ($city != '') {
            $city_value = jsjobslib::jsjobs_explode(',', $city);
            $lenght = count($city_value);
            for ($i = 0; $i < $lenght; $i++) {
                if ($i == 0)
                    $wherequery .= " AND ( mjob.cityid=" . $city_value[$i];
                else
                    $wherequery .= " OR mjob.cityid=" . $city_value[$i];
            }
            $wherequery .= ")";
        }

        if ($zipcode != '')
            $wherequery .= " AND job.zipcode = '" . $zipcode . "'";
        if (isset($radiussearch) && $radiussearch != '')
            $wherequery .= " AND " . $radiussearch;

        //Pagination
        $curdate = date('Y-m-d');
        $query = "SELECT count(DISTINCT job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
                    LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mjob ON mjob.jobid = job.id ";

        $query .= " LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid ";
        $query .= " WHERE job.status = 1 ";
        if ($startpublishing == '')
            $query .= " AND DATE(job.startpublishing) <= " . $curdate;
        if ($stoppublishing == '')
            $query .= " AND DATE(job.stoppublishing) >= " . $curdate;
        $query .= $wherequery;

        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
                , salaryrangefrom.rangestart AS salaryfrom, salaryrangeto.rangeend AS salaryend
                , company.name AS companyname, company.url
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mjob ON mjob.jobid = job.id ";
        $query .= " LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid ";
        $query .= " WHERE  job.status = 1 ";
        if ($startpublishing == '')
            $query .= " AND DATE(job.startpublishing) <= " . $curdate;
        if ($stoppublishing == '')
            $query .= " AND DATE(job.stoppublishing) >= " . $curdate;
        if ($currency != '')
            $query.= " AND currency.id = job.currencyid ";
        $query .= $wherequery;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;

        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        foreach (jsjobs::$_data[0] AS $searchdata) {  // for multicity select
            $multicitydata = $this->getMultiCityData($searchdata->id);
            if ($multicitydata != "")
                $searchdata->city = $multicitydata;
        }

        return;
    }

    function getMyJobs($uid) {
        if (!is_numeric($uid))
            return false;
        $this->getOrdering();
        $this->checkCall();
        $query = "SELECT COUNT(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON job.companyid = company.id
                WHERE job.uid =" . $uid;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total,'myjobs');

        $query = "SELECT job.endgolddate,job.endfeatureddate,job.id,job.uid,job.title,job.serverid,job.noofjobs,job.city,job.status,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,job.serverid,company.name AS companyname,company.id AS companyid,company.logofilename,CONCAT(company.alias,'-',company.id) AS compnayaliasid,
                cat.cat_title, jobtype.title AS jobtypetitle,currency.symbol AS currencysymbol,srto.rangestart,srfrom.rangeend,salaryrangetype.title AS srangetypetitle,
                (SELECT count(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply
                 WHERE jobapply.jobid = job.id) AS resumeapplied ,job.params,job.startpublishing,job.stoppublishing
                 ,LOWER(jobtype.title) AS jobtypetit
                FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS srto ON srto.id = job.salaryrangefrom
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS srfrom ON srfrom.id = job.salaryrangeto
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryrangetype
                WHERE job.uid =" . $uid;
        $query.= " ORDER BY " . jsjobs::$_ordering;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobsdb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        $results = $data;
        $data = array();
        foreach ($results AS $d) {
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->currencysymbol, $d->rangestart, $d->rangeend, $d->srangetypetitle);
            $data[] = $d;
        }
        jsjobs::$_data[0] = $data;
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('job');
        return;
    }

    function getJobsByCategories() {
        $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid,category.id AS categoryid
            ,(SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                WHERE job.status = 1 AND job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
            FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
            WHERE category.isactive = 1 AND category.parentid = 0 ORDER BY category.ordering ASC";
        $categories = jsjobsdb::get_results($query);
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = $config_array['subcategory_limit'];
        foreach($categories AS $category){
            $total = 0;
            $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                ,(SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    WHERE job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
                WHERE category.isactive = 1 AND category.parentid = ".$category->categoryid." ORDER BY category.ordering ASC ";
            $subcats = jsjobs::$_db->get_results($query);
            $i = 0;
            foreach ($subcats as $id => $scat) {
                $total += $scat->totaljobs;
                if($subcategory_limit <= $i){
                    unset($subcats[$id]);
                }
                $i++;
            }
            $category->subcat = $subcats;
            $category->total_sub_jobs = $total;
        }

        if(jsjobs::$_configuration['job_resume_show_all_categories'] == 0){//configuration based
            $final_arr = array();
            foreach ($categories as $job_category) {
                if($job_category->totaljobs != 0 || $job_category->total_sub_jobs != 0){
                    $final_arr[] = $job_category;
                }
            }
            $categories = $final_arr;
        }

        jsjobs::$_data[0] = $categories;
        jsjobs::$_data['config'] =  JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        return;
    }

    function getJobsByTypes() {
        $query = "SELECT jobtype.title,jobtype.id, jobtype.serverid,CONCAT(jobtype.alias,'-',jobtype.id) AS alias
                ,(SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                where job.status = 1 AND job.jobtype = jobtype.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype
                WHERE jobtype.isactive = 1 ORDER BY jobtype.title ASC";
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        jsjobs::$_data['config'] =  JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jobtype');
        return;
    }

    private function makeQueryFromArray($for, $array) {
        if (empty($array))
            return false;
        if (!is_array($array) && $for != 'metakeywords' && $for != 'tags') {
            $newarray[] = $array;
            $array = $newarray;
        }
        $qa = array();
        switch ($for) {
            case 'metakeywords':
                $array = jsjobslib::jsjobs_explode(",", $array);
                $total = count($array);
                if ($total > 5)
                    $total = 5;
                for ($i = 0; $i < $total; $i++) {
                    $qa[] = "job.metakeywords LIKE '%" . $array[$i] . "%'";
                }
                break;
            case 'company':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.companyid = " . $item;
                    }
                }
                break;
            case 'category':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE parentid = ". $item;
                        $cats = jsjobsdb::get_results($query);
                        $ids = [];
                        foreach ($cats as $cat) {
                            $ids[] = $cat->id;
                        }
                        $ids[] = $item;
                        $ids = implode(",",$ids);
                        $qa[] = "job.jobcategory IN(" . $ids.")";
                    }
                }
                break;
            case 'careerlevel':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.careerlevel = " . $item;
                    }
                }
                break;
            case 'age':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = " job.agefrom = $item OR job.ageto = " . $item;
                    }
                }
                break;
            case 'jobtype':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.jobtype = " . $item;
                    }
                }
                break;
            case 'jobstatus':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.jobstatus = " . $item;
                    }
                }
                break;
            case 'shift':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.shift = " . $item;
                    }
                }
                break;
            case 'education':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.educationid = " . $item;
                    }
                }
                break;
            case 'city':
                $a = jsjobslib::jsjobs_explode(',', $array[0]);
                foreach ($a as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.city LIKE '%" . $item . "%'";
                    }
                }
                break;
            case 'tags':
                $array = jsjobslib::jsjobs_explode(',', $array);
                foreach ($array as $item) {
                    $qa[] = "job.tags LIKE '%" . $item . "%'";
                }
                break;
            case 'workpermit':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.workpermit LIKE '%" . $item . "%'";
                    }
                }
                break;
            default:
                return false;
                break;
        }
        $query = implode(" OR ", $qa);
        return $query;
    }

    function isvalidJSON($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    private function getSaveSearchForView($search) {
        if (!is_numeric($search))
            return false;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_jobsearches` WHERE id = " . $search;
        $result = jsjobs::$_db->get_row($query);
        $params = array();
        if($result != ''){
            if ($result->searchparams != null) {
                $params = json_decode($result->searchparams, true);
            }
        }
        $inquery = "";

        if (isset($params['metakeywords'])) {
            jsjobs::$_data['filter']['metakeywords'] = $params['metakeywords'];
            $res = $this->makeQueryFromArray('metakeywords', $params['metakeywords']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['jobtitle'])) {
            jsjobs::$_data['filter']['jobtitle'] = $params['jobtitle'];
            $inquery .= " AND job.title LIKE '%" . $params['jobtitle'] . "%'";
        }
        if (isset($params['company'])) {
            jsjobs::$_data['filter']['company'] = $params['company'];
            $res = $this->makeQueryFromArray('company', $params['company']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['category'])) {
            jsjobs::$_data['filter']['category'] = $params['category'];
            $res = $this->makeQueryFromArray('category', $params['category']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['jobtype'])) {
            jsjobs::$_data['filter']['jobtype'] = $params['jobtype'];
            $res = $this->makeQueryFromArray('jobtype', $params['jobtype']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['careerlevel'])) {
            jsjobs::$_data['filter']['careerlevel'] = $params['careerlevel'];
            $res = $this->makeQueryFromArray('careerlevel', $params['careerlevel']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['gender'])) {
            if (is_numeric($params['gender'])) {
                $inquery .= " AND job.gender = " . $params['gender'];
                jsjobs::$_data['filter']['gender'] = $params['gender'];
            }
        }
        if (isset($params['jobstatus'])) {
            jsjobs::$_data['filter']['jobstatus'] = $params['jobstatus'];
            $res = $this->makeQueryFromArray('jobstatus', $params['jobstatus']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['currencyid'])) {
            if (is_numeric($params['currencyid'])) {
                jsjobs::$_data['filter']['currencyid'] = $params['currencyid'];
                $inquery .= " AND job.currencyid = " . $params['currencyid'];
            }
        }
        if (isset($params['salaryrangestart'])) {
            if (is_numeric($params['salaryrangestart'])) {
                jsjobs::$_data['filter']['salaryrangestart'] = $params['salaryrangestart'];
                $inquery .= " AND job.salaryrangefrom = " . $params['salaryrangestart'];
            }
        }
        if (isset($params['salaryrangeend'])) {
            if (is_numeric($params['salaryrangeend'])) {
                jsjobs::$_data['filter']['salaryrangeend'] = $params['salaryrangeend'];
                $inquery .= " AND job.salaryrangeto = " . $params['salaryrangeend'];
            }
        }
        if (isset($params['salaryrangetype'])) {
            if (is_numeric($params['salaryrangetype'])) {
                jsjobs::$_data['filter']['srangetype'] = $params['salaryrangetype'];
                $inquery .= " AND job.salaryrangetype = " . $params['salaryrangetype'];
            }
        }
        if (isset($params['shift'])) {
            jsjobs::$_data['filter']['shift'] = $params['shift'];
            $res = $this->makeQueryFromArray('shift', $params['shift']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['highesteducation'])) {
            jsjobs::$_data['filter']['highesteducation'] = $params['highesteducation'];
            $res = $this->makeQueryFromArray('education', $params['highesteducation']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['city'])) {
            jsjobs::$_data['filter']['city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($params['city']);
            $res = $this->makeQueryFromArray('city', $params['city']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['tags'])) {
            jsjobs::$_data['filter']['tags'] = JSJOBSincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($params['tags']);
            $res = $this->makeQueryFromArray('tags', $params['tags']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['workpermit'])) {
            jsjobs::$_data['filter']['workpermit'] = $params['workpermit'];
            $res = $this->makeQueryFromArray('workpermit', $params['workpermit']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['requiredtravel'])) {
            if (is_numeric($params['requiredtravel'])) {
                jsjobs::$_data['filter']['requiredtravel'] = $params['requiredtravel'];
                $inquery .= " AND job.requiredtravel = " . $params['requiredtravel'];
            }
        }
        if (isset($params['duration'])) {
            jsjobs::$_data['filter']['duration'] = $params['duration'];
            $inquery .= " AND job.duration LIKE '%" . $params['duration'] . "%'";
        }
//custom field code
        if ( isset($result->params) && $result->params != null) {
            $data = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2);
            $or = '';
            if (!empty($data)) {
                $valarray = json_decode($result->params);
                foreach ($data as $uf) {
                    $fieldname = $uf->field;
                    if (isset($valarray->$fieldname) && $valarray->$fieldname != null) {
                        switch ($uf->userfieldtype) {
                            case 'text':
                            case 'email':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'combo':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'depandant_field':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'radio':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'checkbox':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars(implode(", ",$valarray->$fieldname)) . '%\' ';

                                break;
                            case 'date':
                                if (isset($valarray->$fieldname)) {
                                    $valarray->$fieldname = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($valarray->$fieldname));
                                }
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'editor':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'textarea':
                                $inquery .= ' job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'multiple':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars(implode(", ",$valarray->$fieldname)) . '%\' ';
                                break;
                        }
                    }
                }
                //to convert an std class object to array
                if (!empty($valarray)) {
                    $valarray = json_encode($valarray);
                    $valarray = json_decode($valarray, true);
                }
                jsjobs::$_data['filter']['params'] = $valarray;
            }
        }

//end

        $longitude = isset($params['longitude']) ? $params['longitude'] : '';
        $longitude = jsjobslib::jsjobs_str_replace(',','',$longitude);
        $latitude = isset($params['latitude']) ? $params['latitude'] : '';
        $latitude = jsjobslib::jsjobs_str_replace(',','',$latitude);
        $radius = isset($params['radius']) ? $params['radius'] : '';
        $radius_length_type = isset($params['radiuslengthtype']) ? $params['radiuslengthtype'] : '';
        $radiuslength = '';
        //for radius search
        switch ($radius_length_type) {
            case "m":case 1:$radiuslength = 6378137;
                break;
            case "km":case 2:$radiuslength = 6378.137;
                break;
            case "mile":case 3:$radiuslength = 3963.191;
                break;
            case "nacmiles":case 4:$radiuslength = 3441.596;
                break;
        }
        if ($longitude != '' && $latitude != '' && $radius != '' && $radiuslength != '') {
            jsjobs::$_data['filter']['longitude'] = $longitude;
            jsjobs::$_data['filter']['latitude'] = $latitude;
            jsjobs::$_data['filter']['radius'] = $radius;
            jsjobs::$_data['filter']['radiuslengthtype'] = $radius_length_type;
            $inquery .= " AND acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
        }
        return $inquery;
    }

    private function getRefinedJobs($searchajax = null) {
        $inquery = "";
        if($searchajax == null){
            $keywords_a = JSJOBSrequest::getVar('metakeywords', 'post');
        }else{
            $keywords_a = isset($searchajax['metakeywords']) ? $searchajax['metakeywords'] : '';
        }
        if ($keywords_a) {
            jsjobs::$_data['filter']['metakeywords'] = $keywords_a;
            $res = $this->makeQueryFromArray('metakeywords', $keywords_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $jobtitle = JSJOBSrequest::getVar('jobtitle', 'post');
        }else{
            $jobtitle = isset($searchajax['jobtitle']) ? $searchajax['jobtitle'] : '';
        }
        if ($jobtitle) {
            jsjobs::$_data['filter']['jobtitle'] = $jobtitle;
            $inquery .= " AND job.title LIKE '%" . $jobtitle . "%'";
        }
        if($searchajax == null){
            $company_a = JSJOBSrequest::getVar('company', 'post');
        }else{
            $company_a = isset($searchajax['company']) ? $searchajax['company'] : '';
        }
        if ($company_a) {
            jsjobs::$_data['filter']['company'] = $company_a;
            $res = $this->makeQueryFromArray('company', $company_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $category_a = JSJOBSrequest::getVar('category', 'post');
        }else{
            $category_a = isset($searchajax['category']) ? $searchajax['category'] : '';
        }
        if ($category_a) {
            jsjobs::$_data['filter']['category'] = $category_a;
            $res = $this->makeQueryFromArray('category', $category_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $jobtype_a = JSJOBSrequest::getVar('jobtype', 'post');
        }else{
            $jobtype_a = isset($searchajax['jobtype']) ? $searchajax['jobtype'] : '';
        }
        if ($jobtype_a) {
            jsjobs::$_data['filter']['jobtype'] = $jobtype_a;
            $res = $this->makeQueryFromArray('jobtype', $jobtype_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $careerlevel_a = JSJOBSrequest::getVar('careerlevel', 'post');
        }else{
            $careerlevel_a = isset($searchajax['careerlevel']) ? $searchajax['careerlevel'] : '';
        }
        if ($careerlevel_a) {
            jsjobs::$_data['filter']['careerlevel'] = $careerlevel_a;
            $res = $this->makeQueryFromArray('careerlevel', $careerlevel_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $age_a = JSJOBSrequest::getVar('age', 'post');
        }else{
            $age_a = isset($searchajax['age']) ? $searchajax['age'] : '';
        }
        if ($age_a) {
            jsjobs::$_data['filter']['age'] = $age_a;
            $res = $this->makeQueryFromArray('age', $age_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $gender = JSJOBSrequest::getVar('gender', 'post');
        }else{
            $gender = isset($searchajax['gender']) ? $searchajax['gender'] : '';
        }
        if ($gender) {
            if (is_numeric($gender)) {
                $inquery .= " AND job.gender = " . $gender;
                jsjobs::$_data['filter']['gender'] = $gender;
            }
        }
        if($searchajax == null){
            $agestart = JSJOBSrequest::getVar('agestart', 'post');
        }else{
            $agestart = isset($searchajax['agestart']) ? $searchajax['agestart'] : '';
        }
        if ($agestart) {
            if (is_numeric($agestart)) {
                jsjobs::$_data['filter']['agestart'] = $agestart;
                $inquery .= " AND job.agefrom = " . $agestart;
            }
        }
        if($searchajax == null){
            $ageend = JSJOBSrequest::getVar('ageend', 'post');
        }else{
            $ageend = isset($searchajax['ageend']) ? $searchajax['ageend'] : '';
        }
        if ($ageend) {
            if (is_numeric($ageend)) {
                jsjobs::$_data['filter']['ageend'] = $ageend;
                $inquery .= " AND job.ageto = " . $ageend;
            }
        }
        if($searchajax == null){
            $jobstatus_a = JSJOBSrequest::getVar('jobstatus', 'post');
        }else{
            $jobstatus_a = isset($searchajax['jobstatus']) ? $searchajax['jobstatus'] : '';
        }
        if ($jobstatus_a) {
            jsjobs::$_data['filter']['jobstatus'] = $jobstatus_a;
            $res = $this->makeQueryFromArray('jobstatus', $jobstatus_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $symbol = JSJOBSrequest::getVar('currencyid', 'post');
        }else{
            $symbol = isset($searchajax['currencyid']) ? $searchajax['currencyid'] : '';
        }
        if ($symbol) {
            if (is_numeric($symbol)) {
                jsjobs::$_data['filter']['currencyid'] = $symbol;
                $inquery .= " AND job.currencyid = " . $symbol;
            }
        }
        if($searchajax == null){
            $srangestart = JSJOBSrequest::getVar('salaryrangestart', 'post');
        }else{
            $srangestart = isset($searchajax['salaryrangestart']) ? $searchajax['salaryrangestart'] : '';
        }
        if ($srangestart) {
            if (is_numeric($srangestart)) {
                jsjobs::$_data['filter']['salaryrangestart'] = $srangestart;
                $inquery .= " AND job.salaryrangefrom = " . $srangestart;
            }
        }
        if($searchajax == null){
            $srangeend = JSJOBSrequest::getVar('salaryrangeend', 'post');
        }else{
            $srangeend = isset($searchajax['salaryrangeend']) ? $searchajax['salaryrangeend'] : '';
        }
        if ($srangeend) {
            if (is_numeric($srangeend)) {
                jsjobs::$_data['filter']['salaryrangeend'] = $srangeend;
                $inquery .= " AND job.salaryrangeto = " . $srangeend;
            }
        }
        if($searchajax == null){
            $srangetype = JSJOBSrequest::getVar('salaryrangetype', 'post');
        }else{
            $srangetype = isset($searchajax['salaryrangetype']) ? $searchajax['salaryrangetype'] : '';
        }
        if ($srangetype) {
            if (is_numeric($srangetype)) {
                jsjobs::$_data['filter']['salaryrangetype'] = $srangetype;
                $inquery .= " AND job.salaryrangetype = " . $srangetype;
            }
        }
        if($searchajax == null){
            $shift_a = JSJOBSrequest::getVar('shift', 'post');
        }else{
            $shift_a = isset($searchajax['shift']) ? $searchajax['shift'] : '';
        }
        if ($shift_a) {
            jsjobs::$_data['filter']['shift'] = $shift_a;
            $res = $this->makeQueryFromArray('shift', $shift_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $education_a = JSJOBSrequest::getVar('highesteducation', 'post');
        }else{
            $education_a = isset($searchajax['highesteducation']) ? $searchajax['highesteducation'] : '';
        }
        if ($education_a) {
            jsjobs::$_data['filter']['highesteducation'] = $education_a;
            $res = $this->makeQueryFromArray('education', $education_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $city_a = JSJOBSrequest::getVar('city', 'post');
        }else{
            $city_a = isset($searchajax['city']) ? $searchajax['city'] : '';
        }
        if ($city_a) {
            jsjobs::$_data['filter']['city_ids'] = $city_a;
            jsjobs::$_data['filter']['city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($city_a);
            $res = $this->makeQueryFromArray('city', $city_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $tags_a = JSJOBSrequest::getVar('tags', 'post');
        }else{
            $tags_a = isset($searchajax['tags']) ? $searchajax['tags'] : '';
        }
        if ($tags_a) {
            jsjobs::$_data['filter']['tags_ids'] = $tags_a;
            jsjobs::$_data['filter']['tags'] = JSJOBSincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($tags_a);
            $res = $this->makeQueryFromArray('tags', $tags_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $workpermit_a = JSJOBSrequest::getVar('workpermit', 'post'); // workpermit countries
        }else{
            $workpermit_a = isset($searchajax['workpermit']) ? $searchajax['workpermit'] : '';
        }
        if ($workpermit_a) {
            jsjobs::$_data['filter']['workpermit'] = $workpermit_a;
            $res = $this->makeQueryFromArray('workpermit', $workpermit_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $requiredtravel = JSJOBSrequest::getVar('requiredtravel', 'post');
        }else{
            $requiredtravel = isset($searchajax['requiredtravel']) ? $searchajax['requiredtravel'] : '';
        }
        if ($requiredtravel) {
            if (is_numeric($requiredtravel)) {
                jsjobs::$_data['filter']['requiredtravel'] = $requiredtravel;
                $inquery .= " AND job.requiredtravel = " . $requiredtravel;
            }
        }
        if($searchajax == null){
            $duration = JSJOBSrequest::getVar('duration', 'post');
        }else{
            $duration = isset($searchajax['duration']) ? $searchajax['duration'] : '';
        }
        if ($duration) {
            jsjobs::$_data['filter']['duration'] = $duration;
            $inquery .= " AND job.duration LIKE '%" . $duration . "%'";
        }
        if($searchajax == null){
            $zipcode = JSJOBSrequest::getVar('zipcode', 'post');
        }else{
            $zipcode = isset($searchajax['zipcode']) ? $searchajax['zipcode'] : '';
        }
        if ($zipcode) {
            jsjobs::$_data['filter']['zipcode'] = $zipcode;
            $inquery .= " AND job.zipcode LIKE '%" . $zipcode . "%'";
        }
        //Custom field search
        //start
        $data = JSJOBSincluder::getObjectClass('customfields')->userFieldsData(2);
        $valarray = array();
        if (!empty($data)) {
            foreach ($data as $uf) {
                if($searchajax == null){
                    $valarray[$uf->field] = JSJOBSrequest::getVar($uf->field, 'post');
                }else{
                    // jobs pagination fix
                    if (isset($searchajax['params'][$uf->field])) {
                        $valarray[$uf->field] = $searchajax['params'][$uf->field];
                    } elseif (isset($searchajax[$uf->field])) {
                        $valarray[$uf->field] = $searchajax[$uf->field];
                    } else {
                        $valarray[$uf->field] = '';
                    }
                    // $valarray[$uf->field] = isset($searchajax[$uf->field]) ? $searchajax[$uf->field] : '';
                }
                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null) {
                    switch ($uf->userfieldtype) {
                        case 'text':
                        case 'email':
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'combo':
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'depandant_field':
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'radio':
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'checkbox':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($finalvalue) . '.*"\' ';
                            break;
                        case 'date':
                            if (isset($valarray[$uf->field])) {
                                $valarray[$uf->field] = date('Y-m-d H:i:s',jsjobslib::jsjobs_strtotime($valarray[$uf->field]));
                            }
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'textarea':
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . jsjobslib::jsjobs_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'multiple':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                if($value){
                                    $finalvalue .= $value.'.*';
                                }
                            }
                            if($finalvalue){
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*'.jsjobslib::jsjobs_htmlspecialchars($finalvalue).'"\' ';
                            }
                            break;
                    }
                    jsjobs::$_data['filter']['params'] = $valarray;
                }
            }
        }

        //end
        if($searchajax == null){
            $longitude = JSJOBSrequest::getVar('longitude', 'post');
            $latitude = JSJOBSrequest::getVar('latitude', 'post');
            $radius = JSJOBSrequest::getVar('radius', 'post');
            $radius_length_type = JSJOBSrequest::getVar('radiuslengthtype', 'post');
        }else{
            $longitude = isset($searchajax['longitude']) ? $searchajax['longitude'] : '';
            $latitude = isset($searchajax['latitude']) ? $searchajax['latitude'] : '';
            $radius = isset($searchajax['radius']) ? $searchajax['radius'] : '';
            $radius_length_type = isset($searchajax['radiuslengthtype']) ? $searchajax['radiuslengthtype'] : '';
        }
        $longitude = jsjobslib::jsjobs_str_replace(',', '', $longitude);
        $latitude = jsjobslib::jsjobs_str_replace(',', '', $latitude);
        //for radius search
        switch ($radius_length_type) {
            case "1":$radiuslength = 6378137;
                break;
            case "2":$radiuslength = 6378.137;
                break;
            case "3":$radiuslength = 3963.191;
                break;
            case "4":$radiuslength = 3441.596;
                break;
        }
        if ($longitude != '' && $latitude != '' && $radius != '' && $radiuslength != '') {
            jsjobs::$_data['filter']['longitude'] = $longitude;
            jsjobs::$_data['filter']['latitude'] = $latitude;
            jsjobs::$_data['filter']['radius'] = $radius;
            jsjobs::$_data['filter']['radiuslengthtype'] = $radius_length_type;
            $inquery .= " AND acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
        }
        return $inquery;
    }

    function getJobs($vars) {
        $this->getOrdering();
        $inquery = '';
        if (isset($vars['search']) && $vars['search'] != null) {
            $array = jsjobslib::jsjobs_explode('-', $vars['search']);
            $search = $array[count($array) - 1];
            $inquery = $this->getSaveSearchForView($search);
            jsjobs::$_data['filter']['search'] = $search;
        } elseif (empty($vars)) {
            $inquery = $this->getRefinedJobs();
        } elseif(isset($vars['searchajax'])){
            $inquery = $this->getRefinedJobs($vars);
        } else {
            if (isset($vars['company']) && is_numeric($vars['company'])) { // if action form a <link> defined in cp
                jsjobs::$_data['filter']['company'] = $vars['company'];
                $inquery = " AND job.companyid=" . $vars['company'];
            }
            if (isset($vars['category']) && is_numeric($vars['category'])) { // if action form a <link> defined in cp

                // code for child category
                $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE parentid = ". $vars['category'];
                $cats = jsjobsdb::get_results($query);
                $ids = [];
                foreach ($cats as $cat) {
                    $ids[] = $cat->id;
                }
                $ids[] = $vars['category'];
                $ids = implode(",",$ids);
                $inquery = " AND job.jobcategory IN(" . $ids.")";
                jsjobs::$_data['filter']['category'] = $vars['category'];
            }
            if (isset($vars['jobtype']) && is_numeric($vars['jobtype'])) { // if action form a <link> defined in cp
                jsjobs::$_data['filter']['jobtype'] = $vars['jobtype'];
                $inquery = " AND job.jobtype=" . $vars['jobtype'];
            }
            if (isset($vars['tags']) && (!is_numeric($vars['tags']))) { // if action form a <link> defined in cp
                jsjobs::$_data['filter']['tags'] = JSJOBSincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($vars['tags']);
                jsjobs::$_data['filter']['fromtaglink'] = $vars['tags'];
                $inquery = " AND job.tags LIKE '%" . $vars['tags'] . "%'";
            }
            if (isset($vars['city']) && is_numeric($vars['city'])) { // if action form a <link> defined in cp
                jsjobs::$_data['filter']['city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($vars['city']);
                $res = $this->makeQueryFromArray('city', $vars['city']);
                if ($res){
                    $inquery = " AND ( " . $res . " )";
                }
            }

        }
        $city = JSJOBSrequest::getVar('city','GET');
        if($city && is_numeric($city)){
            //$inquery .= " AND city.id = ".$city;
            jsjobs::$_data['filter']['city'] = JSJOBSincluder::getJSModel('common')->getCitiesForFilter($city);
            $res = $this->makeQueryFromArray('city', $city);
            if ($res){
                $inquery = " AND ( " . $res . " )";
            }
        }

        $state = JSJOBSrequest::getVar('state','GET');
        if($state && is_numeric($state)){
            $inquery .= " AND state.id = ".$state;
        }

        $country = JSJOBSrequest::getVar('country','GET');
        if($country && is_numeric($country)){
            $inquery .= " AND country.id = ".$country;
        }
        //local vars
        $simplejobs = array();
        //Pagination
        $query = "SELECT COUNT(DISTINCT job.id)
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                    JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                    LEFT JOIN `".jsjobs::$_db->prefix."js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
                    LEFT JOIN `".jsjobs::$_db->prefix."js_job_cities` AS city ON city.id = jobcity.cityid
                    LEFT JOIN `".jsjobs::$_db->prefix."js_job_states` AS state ON state.countryid = city.countryid
                    LEFT JOIN `".jsjobs::$_db->prefix."js_job_countries` AS country ON country.id = city.countryid
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()";
        $query.=$inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data

        $query = "SELECT DISTINCT job.id AS jobid,job.tags AS jobtags,job.jobapplylink,job.joblink,job.title,job.created,job.city,CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,job.isgoldjob,job.isfeaturedjob,job.endgolddate,job.endfeatureddate,
                 cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, CONCAT(company.alias,'-',company.id) AS companyaliasid,
                 rstart.rangestart,rend.rangeend,srtype.title AS rangetype, jobtype.title AS jobtypetitle,currency.symbol,job.params
                 ,LOWER(jobtype.title) AS jobtypetit

                 FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                 JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS rstart ON rstart.id = job.salaryrangefrom
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS rend ON rend.id = job.salaryrangeto
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS srtype ON srtype.id = job.salaryrangetype
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = jobcity.cityid
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.countryid = city.countryid
                 LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                 WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                 ";
        $query.=$inquery;
        $theme = wp_get_theme();

        if(jsjobs::$theme_chk != 0){
            $query.= " ORDER BY " . jsjobs::$_ordering;
        }else{
            $query.= " ORDER BY job.created DESC ";
        }

        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        $results = jsjobsdb::get_results($query);
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->rangetype);
            $d->simplejob = 1;
            $simplejobs[] = $d;
        }

        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('job');

        jsjobs::$_data[0] = $simplejobs;
        jsjobs::$_data[2] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(2);
        jsjobs::$_data['fields'] = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        return;
    }

    function getIpAddress() {
        //if client use the direct ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getNextJobs() {
        $searchcriteria = JSJOBSrequest::getVar('ajaxsearch');
        jsjobs::$_data['jsjobs_pageid'] = JSJOBSrequest::getVar('jsjobs_pageid');
        $decoded = jsjobslib::jsjobs_safe_decoding($searchcriteria);
        $array = json_decode($decoded,true);
        //$vars = $this->getjobsvar();
        $array['searchajax'] = 1;
        $this->getJobs($array);
        $jobs = JSJOBSincluder::getObjectClass('jobslist');
        $jobshtml = $jobs->printjobs(jsjobs::$_data[0]);
        echo($jobshtml);
        exit;
    }
    function getNextTemplateJobs(){
        $searchcriteria = JSJOBSrequest::getVar('ajaxsearch');
        jsjobs::$_data['jsjobs_pageid'] = JSJOBSrequest::getVar('jsjobs_pageid');

        $decoded = jsjobslib::jsjobs_safe_decoding($searchcriteria);
        $array = json_decode($decoded,true);
        //$vars = $this->getjobsvar();
        $array['searchajax'] = 1;
        $this->getJobs($array);
        $jobs = JSJOBSincluder::getObjectClass('jobslist');
        $jobshtml = $jobs->printtemplatejobs(jsjobs::$_data[0]);
        echo($jobshtml);
        exit;
    }

    function getjobsvar() {
        $vars = array();
        $id = JSJOBSrequest::getVar('jsjobsid');
        if ($id) {
            //parse id what is the meaning of it
            $array = jsjobslib::jsjobs_explode('_', $id);
            if ($array[0] == 'tags') {
                unset($array[0]);
                $array = implode(' ', $array);
                $vars['tags'] = $array;
            } else {
                if(isset($array[1])){
                    $id = $array[1];
                    $clue = $id[0] . $id[1];
                    $id = jsjobslib::jsjobs_substr($id, 2);
                    switch ($clue) {
                        case '10':
                            $vars['category'] = $id;
                            break;
                        case '11':
                            $vars['jobtype'] = $id;
                            break;
                        case '12':
                            $vars['company'] = $id;
                            break;
                        case '13':
                            $vars['search'] = $id;
                            break;
                        case '14':
                            $vars['city'] = $id;
                            break;
                    }
                }
            }
        } else {
            $id = JSJOBSrequest::getVar('category', 'get');
            if ($id) {
                $vars['category'] = $this->parseid($id);
            }
            $id = JSJOBSrequest::getVar('jobtype', 'get');
            if ($id) {
                $vars['jobtype'] = $this->parseid($id);
            }
            $id = JSJOBSrequest::getVar('company', 'get');
            if ($id) {
                $vars['company'] = $this->parseid($id);
            }
            $id = JSJOBSrequest::getVar('search', 'get');
            if ($id) {
                $vars['search'] = $this->parseid($id);
            }
            $id = JSJOBSrequest::getVar('city', 'get');
            if ($id) {
                $vars['city'] = $this->parseid($id);
            }
            $id = JSJOBSrequest::getVar('tags', 'get');
            if ($id) {
                $id = jsjobs::tagfillout($id);
                $vars['tags'] = $id;
            }
        }
        return $vars;
    }

    function parseid($value) {
        $arr = jsjobslib::jsjobs_explode('-', $value);
        $id = $arr[count($arr) - 1];
        return $id;
    }

    function getOrdering() {
        $sort = JSJOBSrequest::getVar('sortby', '', 'posteddesc');
        jsjobs::$_data['sortby'] = $sort;
        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "titledesc":
                jsjobs::$_ordering = "job.title DESC";
                jsjobs::$_sorton = "title";
                jsjobs::$_sortorder = "DESC";
                break;
            case "titleasc":
                jsjobs::$_ordering = "job.title ASC";
                jsjobs::$_sorton = "title";
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
            case "jobtypedesc":
                jsjobs::$_ordering = "jobtype.title DESC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "DESC";
                break;
            case "jobtypeasc":
                jsjobs::$_ordering = "jobtype.title ASC";
                jsjobs::$_sorton = "jobtype";
                jsjobs::$_sortorder = "ASC";
                break;
            case "jobstatusdesc":
                jsjobs::$_ordering = "job.status DESC";
                jsjobs::$_sorton = "jobstatus";
                jsjobs::$_sortorder = "DESC";
                break;
            case "jobstatusasc":
                jsjobs::$_ordering = "job.status ASC";
                jsjobs::$_sorton = "jobstatus";
                jsjobs::$_sortorder = "ASC";
                break;
            case "companydesc":
                jsjobs::$_ordering = "company.name DESC";
                jsjobs::$_sorton = "company";
                jsjobs::$_sortorder = "DESC";
                break;
            case "companyasc":
                jsjobs::$_ordering = "company.name ASC";
                jsjobs::$_sorton = "company";
                jsjobs::$_sortorder = "ASC";
                break;
            case "salarydesc":
                jsjobs::$_ordering = "srfrom.rangestart DESC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "DESC";
                break;
            case "salaryasc":
                jsjobs::$_ordering = "srfrom.rangestart ASC";
                jsjobs::$_sorton = "salary";
                jsjobs::$_sortorder = "ASC";
                break;
            case "posteddesc":
                jsjobs::$_ordering = "job.created DESC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "DESC";
                break;
            case "postedasc":
                jsjobs::$_ordering = "job.created ASC";
                jsjobs::$_sorton = "posted";
                jsjobs::$_sortorder = "ASC";
                break;
            default: jsjobs::$_ordering = "job.title DESC";
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
        jsjobs::$_sortlinks['title'] = $this->getSortArg("title", $sort);
        jsjobs::$_sortlinks['category'] = $this->getSortArg("category", $sort);
        jsjobs::$_sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        jsjobs::$_sortlinks['jobstatus'] = $this->getSortArg("jobstatus", $sort);
        jsjobs::$_sortlinks['company'] = $this->getSortArg("company", $sort);
        jsjobs::$_sortlinks['salary'] = $this->getSortArg("salary", $sort);
        jsjobs::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    function makeJobSeo($job_seo , $jsjobid){
        if(empty($job_seo))
            return '';

        $common = JSJOBSincluder::getJSModel('common');
        $id = $common->parseID($jsjobid);
        if(! is_numeric($id)) return '';
        $result = '';
        $job_seo = jsjobslib::jsjobs_str_replace( ' ', '', $job_seo);
        $job_seo = jsjobslib::jsjobs_str_replace( '[', '', $job_seo);
        $array = jsjobslib::jsjobs_explode(']', $job_seo);

        $total = count($array);
        if($total > 5)
            $total = 5;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'title':
                    $query = "SELECT title AS col FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = job.jobcategory
                        WHERE job.id = " . $id;
                break;
                case 'company':
                    $query = "SELECT company.name AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = job.companyid
                        WHERE job.id = " . $id;
                break;
                case 'jobtype':
                    $query = "SELECT jt.title AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jt ON jt.id = job.jobtype
                        WHERE job.id = " . $id;
                break;
                case 'location':
                    $query = "SELECT job.city AS col
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE job.id = " . $id;
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

    function getIfJobOwner($jobid) {
        if (!is_numeric($jobid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if(!is_numeric($uid)) return false;
        $query = "SELECT job.id
        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
        WHERE job.uid = " . $uid . "
        AND job.id =" . $jobid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    // get form submit data for frontend side
    function getFrontSideJobSearchFormData($search_userfields){
        $jsjob_search_array = array();
        // $search_userfields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsForSearch(1);
        $jsjob_search_array['metakeywords'] = JSJOBSrequest::getVar('metakeywords', 'post');
        $jsjob_search_array['jobtitle'] = JSJOBSrequest::getVar('jobtitle', 'post');
        $jsjob_search_array['company'] = JSJOBSrequest::getVar('company', 'post');
        $jsjob_search_array['category'] = JSJOBSrequest::getVar('category', 'post');
        $jsjob_search_array['jobtype'] = JSJOBSrequest::getVar('jobtype', 'post');
        $jsjob_search_array['careerlevel'] = JSJOBSrequest::getVar('careerlevel', 'post');
        $jsjob_search_array['jobstatus'] = JSJOBSrequest::getVar('jobstatus', 'post');
        $jsjob_search_array['currencyid'] = JSJOBSrequest::getVar('currencyid', 'post');
        $jsjob_search_array['salarytype'] = JSJOBSrequest::getVar('salarytype', 'post');
        $jsjob_search_array['city'] = JSJOBSrequest::getVar('city', 'post');
        $jsjob_search_array['salarymin'] = JSJOBSrequest::getVar('salarymin', 'post');
        $jsjob_search_array['salarymax'] = JSJOBSrequest::getVar('salarymax', 'post');
        $jsjob_search_array['salaryrangetype'] = JSJOBSrequest::getVar('salaryrangetype', 'post');
        $jsjob_search_array['search_from_jobs'] = 1;
        // if (!empty($search_userfields)) {
        //     foreach ($search_userfields as $uf) {
        //         $jsjob_search_array['jsst_ticket_custom_field'][$uf->field] = JSSTrequest::getVar($uf->field, 'post');
        //     }
        // }
        return $jsjob_search_array;
    }

    // get form submit data for admin jobs
    function getAdminJobSearchFormData($search_userfields){
        $jsjob_search_array = array();
        $jsjob_search_array['searchtitle'] = JSJOBSrequest::getVar('searchtitle');
        $jsjob_search_array['searchcompany'] = JSJOBSrequest::getVar('searchcompany');
        $jsjob_search_array['searchjobcategory'] = JSJOBSrequest::getVar('searchjobcategory');
        $jsjob_search_array['searchjobtype'] = JSJOBSrequest::getVar('searchjobtype');
        $jsjob_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjob_search_array['featured'] = JSJOBSrequest::getVar('featured');
        $jsjob_search_array['datestart'] = JSJOBSrequest::getVar('datestart');
        $jsjob_search_array['dateend'] = JSJOBSrequest::getVar('dateend');
        $jsjob_search_array['location'] = JSJOBSrequest::getVar('location');
        $jsjob_search_array['sorton'] = JSJOBSrequest::getVar('sorton' , 'post', 6);
        $jsjob_search_array['sortby'] = JSJOBSrequest::getVar('sortby' , 'post', 2);
        $jsjob_search_array['search_from_jobs'] = 1;
        return $jsjob_search_array;
    }

    function setSearchVariableForJob($jsjob_search_array,$search_userfields){
        if(is_admin()){
            jsjobs::$_search['jobs']['searchtitle'] = isset($jsjob_search_array['searchtitle']) ? $jsjob_search_array['searchtitle'] : '';
            jsjobs::$_search['jobs']['searchcompany'] = isset($jsjob_search_array['searchcompany']) ? $jsjob_search_array['searchcompany'] : '';
            jsjobs::$_search['jobs']['searchjobcategory'] = isset($jsjob_search_array['searchjobcategory']) ? $jsjob_search_array['searchjobcategory'] : '';
            jsjobs::$_search['jobs']['searchjobtype'] = isset($jsjob_search_array['searchjobtype']) ? $jsjob_search_array['searchjobtype'] : '';
            jsjobs::$_search['jobs']['status'] = isset($jsjob_search_array['status']) ? $jsjob_search_array['status'] : '';
            jsjobs::$_search['jobs']['featured'] = isset($jsjob_search_array['featured']) ? $jsjob_search_array['featured'] : '';
            jsjobs::$_search['jobs']['datestart'] = isset($jsjob_search_array['datestart']) ? $jsjob_search_array['datestart'] : '';
            jsjobs::$_search['jobs']['dateend'] = isset($jsjob_search_array['dateend']) ? $jsjob_search_array['dateend'] : '';
            jsjobs::$_search['jobs']['location'] = isset($jsjob_search_array['location']) ? $jsjob_search_array['location'] : '';
            jsjobs::$_search['jobs']['sorton'] = isset($jsjob_search_array['sorton']) ? $jsjob_search_array['sorton'] : 6;
            jsjobs::$_search['jobs']['sortby'] = isset($jsjob_search_array['sortby']) ? $jsjob_search_array['sortby'] : 2;
        }else{
            jsjobs::$_search['jobs']['jobtitle'] = isset($jsjob_search_array['jobtitle']) ? $jsjob_search_array['jobtitle'] : null;
            jsjobs::$_search['jobs']['city'] = isset($jsjob_search_array['city']) ? $jsjob_search_array['city'] : null;
            jsjobs::$_search['jobs']['company'] = isset($jsjob_search_array['company']) ? $jsjob_search_array['company'] : null;
            jsjobs::$_search['jobs']['metakeywords'] = isset($jsjob_search_array['metakeywords']) ? $jsjob_search_array['metakeywords'] : null;
            jsjobs::$_search['jobs']['category'] = isset($jsjob_search_array['category']) ? $jsjob_search_array['category'] : null;
            jsjobs::$_search['jobs']['jobtype'] = isset($jsjob_search_array['jobtype']) ? $jsjob_search_array['jobtype'] : null;
            jsjobs::$_search['jobs']['careerlevel'] = isset($jsjob_search_array['careerlevel']) ? $jsjob_search_array['careerlevel'] : null;
            jsjobs::$_search['job']['jobstatus'] = isset($jsjob_search_array['jobstatus']) ? $jsjob_search_array['jobstatus'] : null;
            jsjobs::$_search['jobs']['currencyid'] = isset($jsjob_search_array['currencyid']) ? $jsjob_search_array['currencyid'] : null;
            jsjobs::$_search['jobs']['salarytype'] = isset($jsjob_search_array['salarytype']) ? $jsjob_search_array['salarytype'] : null;
            jsjobs::$_search['jobs']['salaryfixed'] = isset($jsjob_search_array['salaryfixed']) ? $jsjob_search_array['salaryfixed'] : null;
            jsjobs::$_search['jobs']['salaryduration'] = isset($jsjob_search_array['salaryduration']) ? $jsjob_search_array['salaryduration'] : null;
            jsjobs::$_search['jobs']['salarymin'] = isset($jsjob_search_array['salarymin']) ? $jsjob_search_array['salarymin'] : null;
            jsjobs::$_search['jobs']['salarymax'] = isset($jsjob_search_array['salarymax']) ? $jsjob_search_array['salarymax'] : null;
            jsjobs::$_search['jobs']['salaryrangetype'] = isset($jsjob_search_array['salaryrangetype']) ? $jsjob_search_array['salaryrangetype'] : null;
            jsjobs::$_search['jobs']['tags'] = isset($jsjob_search_array['tags']) ? $jsjob_search_array['tags'] : null;
        }
    }

    function getCookiesSavedSearchDataJob($search_userfields){
        $jsjob_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_jobs']) && $wpjp_search_cookie_data['search_from_jobs'] == 1){
            if(is_admin()){
                $jsjob_search_array['searchtitle'] = $wpjp_search_cookie_data['searchtitle'];
                $jsjob_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
                $jsjob_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
                $jsjob_search_array['searchjobtype'] = $wpjp_search_cookie_data['searchjobtype'];
                $jsjob_search_array['status'] = $wpjp_search_cookie_data['status'];
                $jsjob_search_array['featured'] = $wpjp_search_cookie_data['featured'];
                $jsjob_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
                $jsjob_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
                $jsjob_search_array['location'] = $wpjp_search_cookie_data['location'];
                $jsjob_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                $jsjob_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
            }else{
                $jsjob_search_array['jobtitle'] = $wpjp_search_cookie_data['jobtitle'];
                $jsjob_search_array['city'] = $wpjp_search_cookie_data['city'];
            }
        }
        return $jsjob_search_array;
    }

    function getMessagekey(){
        $key = 'job';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}
?>
