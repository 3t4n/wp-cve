<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJsjobsModel {

    function getCPJobs() {
        $query = "SELECT comp.name,comp.logofilename,cat.cat_title ,job.city
            FROM " . jsjobs::$_db->prefix . "js_job_jobs as job
            JOIN " . jsjobs::$_db->prefix . "js_job_companies as comp on comp.id = job.companyid
            JOIN " . jsjobs::$_db->prefix . "js_job_categories as cat on cat.id = job.jobcategory";
        jsjobs::$_data[0]['jobs'] = jsjobsdb::get_results($query);
    }

    function getAdminControlPanelData() {
        jsjobs::$_data[0]['today_stats'] = JSJOBSincluder::getJSModel('jsjobs')->getTodayStats();
        JSJOBSincluder::getJSModel('jsjobs')->getNewestJObs();
        // Data for the control panel graph
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs`";
        jsjobs::$_data['totaljobs'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies`";
        jsjobs::$_data['totalcompanies'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume`";
        jsjobs::$_data['totalresume'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply`";
        jsjobs::$_data['totaljobapply'] = jsjobs::$_db->get_var($query);
        $curdate = date('Y-m-d');
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE DATE(startpublishing) <= '$curdate' AND DATE(stoppublishing) >= '$curdate' AND status = 1";
        jsjobs::$_data['totalactivejobs'] = jsjobs::$_db->get_var($query);
        $newindays = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newdays');
        if ($newindays == 0) {
            $newindays = 7;
        }
        $time = jsjobslib::jsjobs_strtotime($curdate . ' -' . $newindays . ' days');
        $lastdate = date("Y-m-d", $time);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE DATE(created) >= DATE('$lastdate') AND DATE(created) <= DATE('$curdate')";
        jsjobs::$_data['totalnewjobs'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE DATE(created) >= DATE('$lastdate') AND DATE(created) <= DATE('$curdate')";
        jsjobs::$_data['totalnewcompanies'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE DATE(created) >= DATE('$lastdate') AND DATE(created) <= DATE('$curdate')";
        jsjobs::$_data['totalnewresume'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE DATE(apply_date) >= DATE('$lastdate') AND DATE(apply_date) <= DATE('$curdate')";
        jsjobs::$_data['totalnewjobapply'] = jsjobs::$_db->get_var($query);

        $curdate = date('Y-m-d');
        $fromdate = date('Y-m-d', jsjobslib::jsjobs_strtotime("now -1 month"));
        jsjobs::$_data['curdate'] = $curdate;
        jsjobs::$_data['fromdate'] = $fromdate;
        $query = "SELECT job.startpublishing AS created
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE date(job.startpublishing) >= '" . $fromdate . "' AND date(job.startpublishing) <= '" . $curdate . "' ORDER BY job.startpublishing";
        $alljobs = jsjobs::$_db->get_results($query);
        $jobs = array();
        foreach ($alljobs AS $job) {
            $date = date('Y-m-d', jsjobslib::jsjobs_strtotime($job->created));
            $jobs[$date] = isset($jobs[$date]) ? ($jobs[$date] + 1) : 1;
        }
        $query = "SELECT company.created
                    FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS company WHERE date(company.created) >= '" . $fromdate . "' AND date(company.created) <= '" . $curdate . "' ORDER BY company.created";
        $allcompanies = jsjobs::$_db->get_results($query);
        $companies = array();
        foreach ($allcompanies AS $company) {
            $date = date('Y-m-d', jsjobslib::jsjobs_strtotime($company->created));
            $companies[$date] = isset($companies[$date]) ? ($companies[$date] + 1) : 1;
        }
        $query = "SELECT resume.created
                    FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume WHERE date(resume.created) >= '" . $fromdate . "' AND date(resume.created) <= '" . $curdate . "'  ORDER BY resume.created";
        $allresume = jsjobs::$_db->get_results($query);
        $resumes = array();
        foreach ($allresume AS $resume) {
            $date = date('Y-m-d', jsjobslib::jsjobs_strtotime($resume->created));
            $resumes[$date] = isset($resumes[$date]) ? ($resumes[$date] + 1) : 1;
        }
        $query = "SELECT job.startpublishing AS created
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job WHERE date(job.startpublishing) >= '" . $fromdate . "' AND date(job.startpublishing) <= '" . $curdate . "' AND job.status = 1 ORDER BY job.created";
        $allactivejob = jsjobs::$_db->get_results($query);
        $activejobs = array();
        foreach ($allactivejob AS $ajob) {
            $date = date('Y-m-d', jsjobslib::jsjobs_strtotime($ajob->created));
            $activejobs[$date] = isset($activejobs[$date]) ? ($activejobs[$date] + 1) : 1;
        }
        jsjobs::$_data['stack_chart_horizontal']['title'] = "['" . __('Dates', 'js-jobs') . "','" . __('Jobs', 'js-jobs') . "','" . __('Companies', 'js-jobs') . "','" . __('Resume', 'js-jobs') . "','" . __('Active Jobs', 'js-jobs') . "']";
        jsjobs::$_data['stack_chart_horizontal']['data'] = '';
        for ($i = 29; $i >= 0; $i--) {
            $checkdate = date('Y-m-d', jsjobslib::jsjobs_strtotime($curdate . " -$i days"));
            if ($i != 29) {
                jsjobs::$_data['stack_chart_horizontal']['data'] .= ',';
            }
            jsjobs::$_data['stack_chart_horizontal']['data'] .= "['" . date_i18n('Y-M-d', jsjobslib::jsjobs_strtotime($checkdate)) . "',";
            $job = isset($jobs[$checkdate]) ? $jobs[$checkdate] : 0;
            $company = isset($companies[$checkdate]) ? $companies[$checkdate] : 0;
            $resume = isset($resumes[$checkdate]) ? $resumes[$checkdate] : 0;
            $ajob = isset($activejobs[$checkdate]) ? $activejobs[$checkdate] : 0;
            jsjobs::$_data['stack_chart_horizontal']['data'] .= "$job,$company,$resume,$ajob]";
        }
        return;
    }

    function storeServerSerailNumber($data) {
        if (empty($data))
            return false;
        // DB class limitations
        if ($data['server_serialnumber']) {
            $query = "UPDATE  `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue='" . $data['server_serialnumber'] . "' WHERE configname='server_serial_number'";

            if (!jsjobsdb::query($query))
                return JSJOBS_SAVE_ERROR;
            else
                return JSJOBS_SAVED;
        } else
            return JSJOBS_SAVE_ERROR;
    }

    function getNewestJobs() {
        $query = "SELECT job.id,job.title,job.startpublishing,job.stoppublishing,company.name,job.city
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    JOIN " . jsjobs::$_db->prefix . "js_job_companies AS company on  job.companyid=company.id
                    ORDER BY job.created DESC
                    LIMIT 0,5";
        jsjobs::$_data[0]['latestjobs'] = jsjobsdb::get_results($query);
    }

    function getTodayStats() {

        $query = "SELECT count(id) AS totalcompanies
		FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE company.status=1 AND company.created >= CURDATE();";

        $companies = jsjobsdb::get_row($query);
        $query = "SELECT count(id) AS totaljobs
		FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job WHERE job.status=1 AND job.created >= CURDATE();";

        $jobs = jsjobsdb::get_row($query);
        $query = "SELECT count(id) AS totalresume
		FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume WHERE resume.status=1 AND resume.created >= CURDATE();";

        $resumes = jsjobsdb::get_row($query);

        $query = "SELECT count(user.id) AS totalemployer
                    FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                    WHERE user.roleid = 1 AND DATE(user.created) = CURDATE()";

        $employer = jsjobsdb::get_row($query);

        $query = "SELECT count(user.id) AS totaljobseeker
                    FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                    WHERE user.roleid = 2 AND DATE(user.created) = CURDATE()";

        $jobseeker = jsjobsdb::get_row($query);

        jsjobs::$_data[0]['companies'] = $companies;
        jsjobs::$_data[0]['jobs'] = $jobs;
        jsjobs::$_data[0]['resumes'] = $resumes;
        jsjobs::$_data[0]['employer'] = $employer;
        jsjobs::$_data[0]['jobseeker'] = $jobseeker;
        return;
    }

    function getConcurrentRequestData() {

//         $query = "SELECT configname,configvalue FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configfor = hostdata";
//         $result = jsjobsdb::get_results($query);
//         foreach ($result AS $res) {
//             $return[$res->configname] = $res->configvalue;
//         }
//         return $return;
    }

    function getMultiCityDataForView($id, $for) {
        if (!is_numeric($id))
            return false;

        $query = "select mcity.id AS id,country.name AS countryName,city.cityName AS cityName,state.name AS stateName";
        switch ($for) {
            case 1:
                $query.=" FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` AS mcity";
                $query.=" LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job ON mcity.jobid=job.id";
                break;
            case 2:
                $query.=" FROM `" . jsjobs::$_db->prefix . "js_job_companycities` AS mcity";
                $query.=" LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON mcity.companyid=company.id";
                break;
        }
        $query.=" LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON mcity.cityid=city.id
				  LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON city.stateid=state.id
				  LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON city.countryid=country.id";
        switch ($for) {
            case 1:
                $query.=" where mcity.jobid=" . $id;
                break;
            case 2:
                $query.=" where mcity.companyid=" . $id;
                break;
        }
        $query.=" ORDER BY country.name";

        $cities = jsjobsdb::get_results($query);
        $mloc = array();
        $mcountry = array();
        $finalloc = "";
        foreach ($cities AS $city) {
            if ($city->countryName != null)
                $mcountry[] = $city->countryName;
        }
        if (!empty($mcountry)) {
            $country_total = array_count_values($mcountry);
        } else {
            $country_total = array();
        }
        $i = 0;
        foreach ($country_total AS $key => $val) {
            foreach ($cities AS $city) {
                if ($key == $city->countryName) {
                    $i++;
                    if ($val == 1) {
                        $finalloc.="[" . $city->cityName . ", " . $key . " ] ";
                        $i = 0;
                    } elseif ($i == $val) {
                        $finalloc.=$city->cityName . ", " . $key . " ] ";
                        $i = 0;
                    } elseif ($i == 1)
                        $finalloc.= "[" . $city->cityName . ", ";
                    else
                        $finalloc.=$city->cityName . ", ";
                }
            }
        }
        return $finalloc;
    }

    function getJsjobsStats() {

        $query = "SELECT count(id) AS totalcompanies,(SELECT count(company.id) FROM " . jsjobs::$_db->prefix . "js_job_companies AS company WHERE company.status=1 ) AS activecompanies
		FROM " . jsjobs::$_db->prefix . "js_job_companies ";

        $companies = jsjobsdb::get_row($query);

        $query = "SELECT count(id) AS totaljobs,(SELECT count(job.id) FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job WHERE job.status=1 AND job.stoppublishing >= CURDATE())  AS activejobs
		FROM " . jsjobs::$_db->prefix . "js_job_jobs ";

        $jobs = jsjobsdb::get_row($query);

        $query = "SELECT count(id) AS totalresumes,(SELECT count(resume.id) FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume WHERE resume.status=1 ) AS activeresumes
		FROM " . jsjobs::$_db->prefix . "js_job_resume ";

        $resumes = jsjobsdb::get_row($query);

        $query = "SELECT (SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies WHERE isfeaturedcompany=1) AS totalfeaturedcompanies,
				(SELECT count(featuredcompany.id) FROM " . jsjobs::$_db->prefix . "js_job_companies  AS featuredcompany
				JOIN  " . jsjobs::$_db->prefix . "js_job_employerpackages AS package ON package.id=featuredcompany.packageid
				WHERE  featuredcompany.status=1 AND featuredcompany.isfeaturedcompany=1  AND DATE_ADD(featuredcompany.startfeatureddate,INTERVAL package.featuredcompaniesexpireindays DAY) >= CURDATE() ) AS activefeaturedcompanies
		FROM " . jsjobs::$_db->prefix . "js_job_companies";

        $featuredcompanies = jsjobsdb::get_row($query);
        $query = "SELECT ( SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_companies WHERE isgoldcompany=1) AS totalgoldcompanies,(SELECT count(goldcompany.id) FROM " . jsjobs::$_db->prefix . "js_job_companies  AS goldcompany
		JOIN  " . jsjobs::$_db->prefix . "js_job_employerpackages AS package ON package.id=goldcompany.packageid
		WHERE  goldcompany.status= 1 AND goldcompany.isgoldcompany=1 AND DATE_ADD(goldcompany.startgolddate,INTERVAL package.goldcompaniesexpireindays DAY) >= CURDATE() )AS activegoldcompanies
		";

        $goldcompanies = jsjobsdb::get_row($query);

        $query = "SELECT ( SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobs WHERE isfeaturedjob=1 ) AS totalfeaturedjobs,(SELECT count(featuredjob.id) FROM " . jsjobs::$_db->prefix . "js_job_jobs AS featuredjob
		JOIN  " . jsjobs::$_db->prefix . "js_job_employerpackages AS package ON package.id=featuredjob.packageid
		WHERE  featuredjob.status= 1 AND featuredjob.isfeaturedjob= 1  AND DATE_ADD(featuredjob.created,INTERVAL package.featuredjobsexpireindays DAY) >= CURDATE() ) AS activefeaturedjobs
		";

        $featuredjobs = jsjobsdb::get_row($query);

        $query = "SELECT ( SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_jobs WHERE isgoldjob=1) AS totalgoldjobs,(SELECT count(goldjob.id) FROM " . jsjobs::$_db->prefix . "js_job_jobs  AS goldjob
		JOIN  " . jsjobs::$_db->prefix . "js_job_employerpackages AS package ON package.id=goldjob.packageid
		WHERE  goldjob.status= 1 AND goldjob.isgoldjob=1  AND DATE_ADD(goldjob.created,INTERVAL package.goldjobsexpireindays DAY) >= CURDATE() ) AS activegoldjobs
		";

        $goldjobs = jsjobsdb::get_row($query);

        $query = "SELECT ( SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_resume WHERE isfeaturedresume=1 ) AS totalfeaturedresumes,(SELECT count(featuredresume.id) FROM " . jsjobs::$_db->prefix . "js_job_resume  AS featuredresume
		JOIN  " . jsjobs::$_db->prefix . "js_job_jobseekerpackages AS package ON package.id=featuredresume.packageid
		WHERE  featuredresume.status= 1 AND featuredresume.isfeaturedresume= 1  AND DATE_ADD(featuredresume.created,INTERVAL package.freaturedresumeexpireindays DAY) >= CURDATE() ) AS activefeaturedresumes
		";

        $featuredresumes = jsjobsdb::get_row($query);

        $query = "SELECT ( SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_resume WHERE isgoldresume=1 ) AS totalgoldresumes,(SELECT count(goldresume.id) FROM " . jsjobs::$_db->prefix . "js_job_resume  AS goldresume
		JOIN  " . jsjobs::$_db->prefix . "js_job_jobseekerpackages AS package ON package.id=goldresume.packageid
		WHERE  goldresume.status= 1  AND goldresume.isgoldresume= 1  AND DATE_ADD(goldresume.created,INTERVAL package.goldresumeexpireindays DAY) >= CURDATE() ) AS activegoldresumes
		";

        $goldresumes = jsjobsdb::get_row($query);

        $totalpaidamount = 'Recalculate';

        $query = "SELECT count(user.id) AS totalemployer
                    FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                    WHERE user.roleid = 1";

        $totalemployer = jsjobsdb::get_row($query);

        $query = "SELECT count(user.id) AS totaljobseeker
                    FROM " . jsjobs::$_db->prefix . "js_job_users AS user
                    WHERE user.role=2";

        $totaljobseeker = jsjobsdb::get_row($query);

        jsjobs::$_data[0]['companies'] = $companies;
        jsjobs::$_data[0]['jobs'] = $jobs;
        jsjobs::$_data[0]['resumes'] = $resumes;
        jsjobs::$_data[0]['featuredcompanies'] = $featuredcompanies;
        jsjobs::$_data[0]['goldcompanies'] = $goldcompanies;
        jsjobs::$_data[0]['featuredjobs'] = $featuredjobs;
        jsjobs::$_data[0]['goldjobs'] = $goldjobs;
        jsjobs::$_data[0]['featuredresumes'] = $featuredresumes;
        jsjobs::$_data[0]['goldresumes'] = $goldresumes;
        jsjobs::$_data[0]['totalpaidamount'] = $totalpaidamount;
        jsjobs::$_data[0]['totalemployer'] = $totalemployer;
        jsjobs::$_data[0]['totaljobseeker'] = $totaljobseeker;
        return;
    }


    function widgetTotalStatsData() {
        $query = "SELECT count(id) AS totalcompanies
        FROM " . jsjobs::$_db->prefix . "js_job_companies ";

        $companies = jsjobsdb::get_row($query);

        $query = "SELECT count(id) AS totaljobs,(SELECT count(job.id) FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job WHERE job.status=1 AND job.stoppublishing >= CURDATE())  AS activejobs
        FROM " . jsjobs::$_db->prefix . "js_job_jobs ";

        $jobs = jsjobsdb::get_row($query);

        $query = "SELECT count(id) AS totalresumes
        FROM " . jsjobs::$_db->prefix . "js_job_resume ";

        $resumes = jsjobsdb::get_row($query);

        $query = "SELECT count(DISTINCT jobid) AS appliedjobs
        FROM " . jsjobs::$_db->prefix . "js_job_jobapply ";

        $aplliedjobs = jsjobsdb::get_row($query);


        jsjobs::$_data['widget']['companies'] = $companies;
        jsjobs::$_data['widget']['jobs'] = $jobs;
        jsjobs::$_data['widget']['resumes'] = $resumes;
        jsjobs::$_data['widget']['aplliedjobs'] = $aplliedjobs;
        return true;
    }

    function widgetLastWeekData() {
        $newindays = 7;
        $curdate = date('Y-m-d');
        $time = jsjobslib::jsjobs_strtotime($curdate . ' -' . $newindays . ' days');
        $lastdate = date("Y-m-d", $time);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE DATE(created) >= DATE('" . $lastdate . "') AND DATE(created) <= '" . $curdate . "'";
        jsjobs::$_data['widget']['newjobs'] = jsjobs::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE DATE(created) >= DATE('" . $lastdate . "') AND DATE(created) <= DATE('" . $curdate . "')";
        jsjobs::$_data['widget']['newcompanies'] = jsjobs::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE DATE(created) >= DATE('" . $lastdate . "') AND DATE(created) <= DATE('" . $curdate . "')";
        jsjobs::$_data['widget']['newresume'] = jsjobs::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE DATE(apply_date) >= '" . $lastdate . "' AND DATE(apply_date) <= '" . $curdate . "'";
        jsjobs::$_data['widget']['newjobapply'] = jsjobs::$_db->get_var($query);
        if(!jsjobs::$_data['widget']['newjobapply']) jsjobs::$_data['widget']['newjobapply'] = 0;

        jsjobs::$_data['widget']['startdate'] = date('d M, Y', jsjobslib::jsjobs_strtotime($lastdate));
        jsjobs::$_data['widget']['enddate'] = date('d M, Y', jsjobslib::jsjobs_strtotime($curdate));
        return true;
    }

    function getDataForWidgetPopup() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $dataid = JSJOBSrequest::getVar('dataid');
        $newindays = 7;
        $curdate = date('Y-m-d');
        $time = jsjobslib::jsjobs_strtotime($curdate . ' -' . $newindays . ' days');
        $lastdate = date("Y-m-d", $time);
        if ($dataid == 1) { //job
            $query = "SELECT job.companyid AS id,job.title,job.isgoldjob AS isgold,isfeaturedjob AS isfeatured
                        ,job.status,cat.cat_title,job.city,comp.logofilename AS photo
            FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS comp ON comp.id = job.companyid
            JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
            WHERE DATE(job.created) >= DATE('" . $lastdate . "') AND DATE(job.created) <= DATE('" . $curdate . "')
            ORDER BY job.created DESC LIMIT 5";
            $results = jsjobs::$_db->get_results($query);
        }
        if ($dataid == 2) { //company
            $query = "SELECT comp.id ,comp.name AS title,comp.isgoldcompany AS isgold,comp.isfeaturedcompany AS isfeatured
                        ,comp.city,comp.status,comp.logofilename AS photo,cat.cat_title
            FROM `" . jsjobs::$_db->prefix . "js_job_companies` AS comp
            JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = comp.category
            WHERE DATE(comp.created) >= DATE('" . $lastdate . "') AND DATE(comp.created) <= DATE('" . $curdate . "')
            ORDER BY comp.created DESC LIMIT 5";
            $results = jsjobs::$_db->get_results($query);
        }
        if ($dataid == 3) {     //resume
            $query = "SELECT resume.id, CONCAT(resume.application_title,' ( ',resume.first_name,' ',resume.last_name,' )' ) AS title
            ,resume.isgoldresume AS isgold
            ,resume.isfeaturedresume AS isfeatured,resume.status,edu.title as education,cat.cat_title,resume.photo
            FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
            JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = resume.job_category
            LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_heighesteducation` AS edu ON edu.id = resume.heighestfinisheducation
            WHERE DATE(resume.created) >= DATE('" . $lastdate . "') AND DATE(resume.created) <= DATE('" . $curdate . "')
            ORDER BY resume.created DESC LIMIT 5";
            $results = jsjobs::$_db->get_results($query);
        }
        if ($dataid == 4) {  //jobappply
            $query = "SELECT  comp.id,comp.logofilename AS logo,job.title AS title
                    ,CONCAT(resume.application_title,' / ',resume.first_name,' ',resume.last_name) AS name
                    ,jobapp.apply_date,jobapp.action_status as status,job.isgoldjob AS isgold
                    ,job.isfeaturedjob AS isfeatured
            FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapp
            JOIN `" . jsjobs::$_db->prefix . "js_job_resume` AS resume ON resume.id = jobapp.cvid
            JOIN `" . jsjobs::$_db->prefix . "js_job_jobs` AS job on job.id = jobapp.jobid
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS comp ON comp.id = job.companyid
            WHERE DATE(jobapp.apply_date) >= DATE('" . $lastdate . "') AND DATE(jobapp.apply_date) <= DATE('" . $curdate . "')
            ORDER BY jobapp.apply_date DESC LIMIT 5";
            $results = jsjobs::$_db->get_results($query);
        }
        $html = $this->generatePopup($results, $dataid);
        return $html;
    }

//function to denerate popup from new jobs companies and resume
    function generatePopup($results, $dataid) {
        if ($dataid == 1) {
            $title = __('Newest Jobs', 'js-jobs');
        } elseif ($dataid == 2) {
            $title = __('Newest Companies', 'js-jobs');
        } elseif ($dataid == 3) {
            $title = __('Newest Resumes', 'js-jobs');
        } elseif ($dataid == 4) {
            $title = __('Newest Applied Jobs', 'js-jobs');
        }
        $html = '';
        $html = '<span class="popup-top">
                    <span id="popup_title" >
                    ' . $title . '
                    </span>
                    <img id="popup_cross" src="' . JSJOBS_PLUGIN_URL . 'includes/images/popup-close.png">
                </span>
                <div class="widget-popup-body">';
        if (empty($results)) {
            $error = '
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            ' . __("Oops...", "js-jobs") . '
                        </span>
                    </div>
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="' . JSJOBS_PLUGIN_URL . 'includes/images/norecordfound.png"/>
                         </span>
                         <span class="message-text">
                            ' . __('Record Not Found', 'js-jobs') . '
                         </span>
                    </div>
                    <div class="footer">
                        <a href ="' . 'admin.php?page=jsjobs' . '">' . __('Back to control panel', 'js-jobs') . '</a>
                    </div>
                </div>
        ';
            $html .= ' ' . $error . '</div>';
            return $html;
        }

        //popup layout for new job /company/resume
        if ($dataid != 4) {
            //1 = newest jobs
            //2 = newest compnay
            //3 = newest resume
            //4 = applied jobs

            foreach ($results as $data) {
                //photo / logo
                //for company and job
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $wpdir = wp_upload_dir();
                if ($dataid == 1 || $dataid == 2) {
                    if ($data->photo != "") {
                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $data->id . '/logo/' . $data->photo;
                    } else {
                        $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
                    }
                } elseif ($data->photo != "") {
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $data->id . '/photo/' . $data->photo;
                } else {
                    $path = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                }

                $picstyle = '';
                //bottom link

                if ($dataid == 1) {
                    $link = 'admin.php?page=jsjobs_job&jsjobslt=jobs';
                }
                if ($dataid == 2) {
                    $link = 'admin.php?page=jsjobs_company&jsjobslt=companies';
                }
                if ($dataid == 3) {
                    $link = 'admin.php?page=jsjobs_resume&jsjobslt=resumes';
                    $picstyle = 'resume-img';
                }


                //city //resume has education not city
                if ($dataid != 3) {
                    $data->city = JSJOBSincluder::getJSModel('city')->getLocationDataForView($data->city);
                }
                //flags expressing status
                if ($data->status == 0) {
                    $flaghtml = '<div class="pending-badge badges">
                                        <span class="flag pending"><span></span>' . __('Pending', 'js-jobs') . '</span>
                                        </div>';
                } elseif ($data->status == 1) {
                    $flaghtml = '<div class="approved-badge badges">
                                        <span class="flag approved"><span></span>' . __('Approved', 'js-jobs') . '</span>
                                        </div>';
                } else {
                    $flaghtml = '<div class="rejected-badge badges">
                                        <span class="flag rejected"><span></span>' . __('Rejected', 'js-jobs') . '</span>
                                        </div>';
                }


                $html .= '<div class="widget-data-wrapper">
                                    <div class="left-data ' . $picstyle . '">
                                    <img class="left-data-img" src="' . $path . '"/>
                                    </div>
                                    <div class="right-data">
                                        <div class="data-title">
                                        ' . $data->title;
                if ($data->isgold == 1) {
                    $html .= '<span id="badge_gold" class="gold badge gold">' . __('Gold', 'js-jobs') . '</span>';
                }
                if ($data->isfeatured == 1) {
                    $html .= '<span id="badge_featured" class="feature badge featured">' . __('Featured', 'js-jobs') . '</span>';
                }

                $html .= '</div>
                                        <div class="data-data">
                                            <span class="heading">
                                            ' . __('Category', 'js-jobs') . ' :
                                            </span>
                                            <span class="text">
                                            ' . $data->cat_title . '
                                            </span>
                                        </div>';
                if ($dataid != 3) {
                    $html .= '<div class="data-data">
                                                    <span class="heading">
                                                ' . __('Location', 'js-jobs') . ' :
                                                </span>
                                                <span class="text">
                                                ' . $data->city . '
                                                </span>';
                } else {
                    $html .= '<div class="data-data">
                                                    <span class="heading">
                                                ' . __('Highest Education', 'js-jobs') . ' :
                                                </span>
                                                <span class="text">
                                                ' . $data->education . '
                                                </span>';
                }
                $html .='
                                        </div>
                                        ' . $flaghtml . '
                                    </div>
                                </div>';
            }
        } elseif ($dataid == 4) {
            $html .= $this->getAppliedJobPopup($results);
            return $html;
        }
        $html .= '<a href = "' . $link . '" class="popup-bottom-button">' . __('Show More', 'js-jobs') . '</a></div>';
        return $html;
    }

//function to create popup of newest applied jobs
    function getAppliedJobPopup($results) {
        $html = '';
        $wpdir = wp_upload_dir();
        foreach ($results as $data) {
            //photo / logo
            if ($data->logo != "") {
                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $data->id . '/logo/' . $data->logo;
            } else {
                $path = JSJOBS_PLUGIN_URL . '/includes/images/default_logo.png';
            }


            //flags expressing status
            $flaghtml = '';
            if ($data->status == 2) {
                $flaghtml = '<div class="spam-badge badges">
                                        <span class="flag spam"><span></span>' . __('Spam', 'js-jobs') . '</span>
                                        </div>';
            } elseif ($data->status == 3) {
                $flaghtml = '<div class="hired-badge badges">
                                        <span class="flag hired"><span></span>' . __('Hired', 'js-jobs') . '</span>
                                        </div>';
            } elseif ($data->status == 4) {
                $flaghtml = '<div class="reject-badge badges">
                                        <span class="flag reject"><span></span>' . __('Rejected', 'js-jobs') . '</span>
                                        </div>';
            } elseif ($data->status == 5) {
                $flaghtml = '<div class="shortlisted-badge badges">
                                        <span class="flag shortlisted"><span></span>' . __('Short listed', 'js-jobs') . '</span>
                                        </div>';
            }


            $html .= '<div class="widget-data-wrapper">
                                    <div class="left-data">
                                        <img class="left-data-img" src="' . $path . '"/>
                                    </div>
                                    <div class="right-data">
                                        <div class="data-title">
                                        ' . $data->title;
            if ($data->isgold == 1) {
                $html .= '<span id="badge_gold" class="gold badge gold">' . __('Gold', 'js-jobs') . '</span>';
            }
            if ($data->isfeatured == 1) {
                $html .= '<span id="badge_featured" class="feature badge featured">' . __('Featured', 'js-jobs') . '</span>';
            }

            $html .= '</div>
                                        <div class="data-data">
                                            <span class="heading">
                                            ' . __('Applicant', 'js-jobs') . ' :
                                            </span>
                                            <span class="text">
                                            ' . $data->name . '
                                            </span>
                                        </div>';
            $html .= '<div class="data-data">
                                                    <span class="heading">
                                                ' . __('Applied Date', 'js-jobs') . ' :
                                                </span>
                                                <span class="text">
                                                ' . $data->apply_date . '
                                                </span>';

            $html .='
                                        </div>
                                        ' . $flaghtml . '
                                    </div>

                        </div>';
        }
        $html .= '</div>';
        return $html;
    }

    function getNewestUsers($role) {
        if (!is_numeric($role))
            return false;
        $query = "SELECT u.id,CONCAT(u.first_name,' ',u.last_name) AS username,u.emailaddress AS email,u.created AS created
        FROM `" . jsjobs::$_db->prefix . "js_job_users` AS u
        WHERE u.roleid = " . $role . " ORDER BY u.created DESC LIMIT 5";

        $results = jsjobs::$_db->get_results($query);
        //company logo for employer
        if ($role == 1) {
            $data = array();
            foreach ($results AS $d) {
                $query = "SELECT logofilename AS photo,id AS companyid FROM `" . jsjobs::$_db->prefix . "js_job_companies`
                WHERE uid = " . $d->id . " ORDER BY logofilename DESC LIMIT 1";
				$result = jsjobs::$_db->get_row($query);
                if($result){
                    $d->photo = $result->photo;
                    $d->companyid = $result->companyid;
                }else{
                    $d->photo = '';
                    $d->companyid = '';
                }
                $data[] = $d;
            }
            $results = $data;
        }
        //resume photo  for jobseeker
        if ($role == 2) {
            $data = array();
            foreach ($results AS $d) {
                $query = "SELECT photo,id AS resumeid FROM `" . jsjobs::$_db->prefix . "js_job_resume`
                WHERE uid = " . $d->id . " ORDER BY photo DESC LIMIT 1";
				$result = jsjobs::$_db->get_row($query);
                if($result){
                    $d->photo = $result->photo;
                    $d->resumeid = $result->resumeid;
                }else{
                    $d->photo = '';
                    $d->resumeid = '';
                }
                $data[] = $d;
            }
            $results = $data;
        }
        $html = $this->genrateUserWidget($results, $role);
        return $html;
    }

    function genrateUserWidget($results, $role) {
        $html = '';
        $html .= '<div id="js-jobs-widget-wrapper">';
        $wpdir = wp_upload_dir();
        foreach ($results as $data) {
            //name
            $name = $data->username;
            //photo code
            $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            if ($role == 1) {
                if ($data->photo != "") {
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $data->companyid . '/logo/' . $data->photo;
                } else {
                    $path = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
                }
            } elseif ($data->photo != "") {
                $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $data->resumeid . '/photo/' . $data->photo;
            } else {
                $path = JSJOBS_PLUGIN_URL . '/includes/images/users.png';
            }
            //photo code
            $dateformat = jsjobs::$_configuration['date_format'];
            $html .= '<div class="users-widget-data">
                                    <img class="photo" src="' . $path . '"/>
                                    <div class="widget-data-upper">
                                        <a href="'.admin_url('admin.php?page=jsjobs_user&jsjobslt=userdetail&id='.$data->id).'">
                                            '. $name .'
                                        </a>
                                        <span class="Widget-data-date">( ' . date_i18n($dateformat, jsjobslib::jsjobs_strtotime($data->created)) . ' )</span>
                                    </div>
                                    <div class="widget-data-lower">
                                        ' . $data->email . '
                                    </div>
                                </div>';
        }

        $html .= '</div>';
        return $html;
    }

    function getStepTwoValidate() {
        $basepath = WP_CONTENT_DIR;
        JSJOBSincluder::getJSModel('common')->makeDir($basepath . '/plugins');
        jsjobs::$_data['dir'] = jsjobslib::jsjobs_substr(sprintf('%o', fileperms(JSJOBS_PLUGIN_PATH)), -3);

        if (!is_writable(JSJOBS_PLUGIN_PATH)) {
            jsjobs::$_data['dir'] = 0;
        }
        jsjobs::$_data['tmp_dir'] = jsjobslib::jsjobs_substr(sprintf('%o', fileperms($basepath . '/plugins')), -3);
        if (!is_writable($basepath . '/plugins')) {
            jsjobs::$_data['tmp_dir'] = 0;
        }

        $query = 'CREATE TABLE js_test_table(
                    id int,
                    name varchar(255)
                );';
        jsjobs::$_data['create_table'] = 0;
        if (jsjobsdb::query($query)) {
            jsjobs::$_data['create_table'] = 1;
        }
        $query = 'INSERT INTO js_test_table(id,name) VALUES (1,\'Name 1\'),(2,\'Name 2\');';
        jsjobs::$_data['insert_record'] = 0;
        if (jsjobsdb::query($query)) {
            jsjobs::$_data['insert_record'] = 1;
        }
        $query = 'UPDATE js_test_table SET name = \'JS Jobs\' WHERE id = 1;';
        jsjobs::$_data['update_record'] = 0;
        if (jsjobsdb::query($query)) {
            jsjobs::$_data['update_record'] = 1;
        }
        $query = 'DELETE FROM js_test_table;';
        jsjobs::$_data['delete_record'] = 0;
        if (jsjobsdb::query($query)) {
            jsjobs::$_data['delete_record'] = 1;
        }
        $query = 'DROP TABLE js_test_table;';
        jsjobs::$_data['drop_table'] = 0;
        if (jsjobsdb::query($query)) {
            jsjobs::$_data['drop_table'] = 1;
        }
        if (jsjobs::$_data['tmp_dir'] >= 755) {
            $fileurl = 'https://setup.joomsky.com/jsjobswp/logo.png';
            $filepath = WP_CONTENT_DIR;
            $filepath = $filepath .'/plugins/logo.png';
            $tmpfile = download_url( $fileurl);
            copy( $tmpfile, $filepath );
            @unlink( $tmpfile ); // must unlink afterwards

            jsjobs::$_data['file_downloaded'] = 0;
            if (file_exists($filepath)) {
                jsjobs::$_data['file_downloaded'] = 1;
            }
            $url = 'https://setup.joomsky.com/jsjobswp/logo.png';
            $response = wp_remote_post( $url, array('timeout'=>7,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){

            }else{
                if(!is_wp_error($response)){
                    echo wp_kses($response['response']['message'], JSJOBS_ALLOWED_TAGS);
                }else{
                    echo wp_kses($response->get_error_message(), JSJOBS_ALLOWED_TAGS);
                }
                jsjobs::$_data['file_downloaded'] = 0;
            }
        } else
            jsjobs::$_data['file_downloaded'] = 0;
        return jsjobs::$_data;
    }

    function getmyversionlist($data) {
        if(jsjobslib::jsjobs_trim($data['transactionkey']) == ''){
            $response = '["0","Please insert product key"]';
            return $response;
        }
        $post_data = array();
        $post_data['transactionkey'] = $data['transactionkey'];
        update_option( 'jsjobs_transaction_key', $data['transactionkey'] );
        $post_data['serialnumber'] = $data['serialnumber'];
        $post_data['domain'] = $data['domain'];
        $post_data['producttype'] = $data['producttype'];
        $post_data['productcode'] = $data['productcode'];
        $post_data['productversion'] = $data['productversion'];
        $post_data['JVERSION'] = $data['JVERSION'];
        $post_data['count'] = JSJOBSincluder::getJSModel('configuration')->getCountConfig();
        $post_data['installerversion'] = $data['installerversion'];
        $url = JCONSTV;
        $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
        if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
            $result = $response['body'];
        }else{
            $result = false;
            if(!is_wp_error($response)){
               $error = $response['response']['message'];
           }else{
                $error = $response->get_error_message();
           }
        }

        if($result) {
            $response = $result;
        }else{
            $response = '["0","'.$error.'"]';
        }
        return $response;
    }


    function getListTranslations() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $result = array();
        $result['error'] = false;

        $path = JSJOBS_PLUGIN_PATH.'languages';

        if( ! is_writeable($path)){
            $result['error'] = __('Dir is not writable','js-jobs').' '.$path;
        }else{
            if($this->isConnected()){
                $version = JSJOBSIncluder::getJSModel('configuration')->getConfigByFor('default');
                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data = array();
                $post_data['product'] ='js-jobs-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = $version['producttype'];
                $post_data['productcode'] = 'jsjobs';
                $post_data['productversion'] = $version['versioncode'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['method'] = 'getTranslations';
                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>45,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $call_result = $response['body'];
                }else{
                    $call_result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                   }else{
                        $error = $response->get_error_message();
                   }
                }

                $result['data'] = jsjobslib::jsjobs_htmlentities($call_result);
                if(!$call_result){
                    $result['error'] = $error;
                }
            }else{
                $result['error'] = __('Unable to connect to server','js-jobs');
            }
        }
        $result = json_encode($result);
        return $result;
    }

    function makeLanguageCode($lang_name){
        $langarray = wp_get_installed_translations('core');
        if (isset($langarray['default']) && $langarray['default'] != null) {
        $langarray = $langarray['default'];
        $match = false;
        if(array_key_exists($lang_name, $langarray)){
            $lang_name = $lang_name;
            $match = true;
        }else{
            $m_lang = '';
            foreach($langarray AS $k => $v){
                if($lang_name[0].$lang_name[1] == $k[0].$k[1]){
                    $m_lang .= $k.', ';
                }
            }

            if($m_lang != ''){
                $m_lang = jsjobslib::jsjobs_substr($m_lang, 0,jsjobslib::jsjobs_strlen($m_lang) - 2);
                $lang_name = $m_lang;
                $match = 2;
            }else{
                $lang_name = $lang_name;
                $match = false;
            }
        }
        } else {
            $lang_name = $lang_name;
            $match = false;
        }

        return array('match' => $match , 'lang_name' => $lang_name);
    }

    function validateAndShowDownloadFileName( ){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $lang_name = JSJOBSrequest::getVar('langname');
        if($lang_name == '') return '';
        $result = array();
        $f_result = $this->makeLanguageCode($lang_name);
        $path = JSJOBS_PLUGIN_PATH.'languages';
        $result['error'] = false;
        if($f_result['match'] === false){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-jobs');
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writeable','js-jobs').': '.$path;
        }else{
            $result['input'] = "<input id='languagecode' class='text_area' type='text' value='".$lang_name."' name='languagecode'>";
            if($f_result['match'] === 2){
                $result['input'] .= '<div id="js-emessage-wrapper" style="display:block;margin:20px 0px 20px;">';
                $result['input'] .= __('Required language is not installed but similar language[s] like').': "<b>'.$f_result['lang_name'].'</b>" '.__('is found in your system','js-jobs');
                $result['input'] .= '</div>';

            }
            $result['path'] = __('Language code','js-jobs');
        }
        $result = json_encode($result);
        return $result;
    }

    function getLanguageTranslation(){
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $lang_name = JSJOBSrequest::getVar('langname');
        $language_code = JSJOBSrequest::getVar('filename');

        $result = array();
        $result['error'] = false;
        $path = JSJOBS_PLUGIN_PATH.'languages';

        $path = WP_LANG_DIR . '/plugins/';
        if(!is_dir($path)){
            mkdir($path);
        }

        if($lang_name == '' || $language_code == ''){
            $result['error'] = __('Empty values','js-jobs');
            return json_encode($result);
        }

        $final_path = $path.'/js-jobs-'.$language_code.'.po';


        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];

        if(!array_key_exists($language_code, $langarray)){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-jobs');
            return json_encode($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writable','js-jobs').': '.$path;
            return json_encode($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = __('File is not writable','js-jobs').': '.$final_path;
        }else{
            if($this->isConnected()){

                $version = JSJOBSIncluder::getJSModel('configuration')->getConfigByFor('version');
                if($version['versiontype']){
                    $versiontype = $version['versiontype'];
                }else{
                    $versiontype = JSJOBSIncluder::getJSModel('configuration')->getConfigValue('versiontype');
                }

                if($version['version']){
                    $productversion = $version['version'];
                }else{
                    $productversion = JSJOBSIncluder::getJSModel('configuration')->getConfigValue('version');
                }
                if(!$productversion){
                    $productversion = JSJOBSIncluder::getJSModel('configuration')->getConfigValue('versioncode');
                }

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data = array();
                $post_data['product'] ='js-jobs-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = $versiontype;
                $post_data['productcode'] = 'jsjobs';
                $post_data['productversion'] = $productversion;
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['translationcode'] = $lang_name;
                $post_data['method'] = 'getTranslationFile';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>45,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $result = $response['body'];
                }else{
                    $result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                   }else{
                        $error = $response->get_error_message();
                   }
                }
                if($result){
                    $result = json_decode($result, true);
                }else{
                    $result = array();
                }

                $ret = $this->writeLanguageFile( $final_path , $result['file']);
                $ret = $this->copyLanguageFileIntoTheme( $path,$lang_name);

                // if($ret != false){
                //     $url = "http://www.joomsky.com/translations/api/1.0/index.php";
                //     $post_data['product'] ='js-jobs-wp';
                //     $post_data['domain'] = get_site_url();
                //     $post_data['producttype'] = $version['versiontype'];
                //     $post_data['productcode'] = 'jsjobs';
                //     $post_data['productversion'] = $version['version'];
                //     $post_data['JVERSION'] = get_bloginfo('version');
                //     $post_data['folder'] = $array['foldername'];
                //     $ch = curl_init();
                //     curl_setopt($ch, CURLOPT_URL, $url);
                //     curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                //     $response = curl_exec($ch);
                //     curl_close($ch);
                // }
                $result['data'] = __('File Downloaded Successfully','js-jobs');
            }else{
                $result['error'] = __('Unable to connect to server','js-jobs');
            }
        }

        $result = json_encode($result);

        return $result;

    }

    function writeLanguageFile( $path , $url ){
        $result = true;
		include(ABSPATH . "wp-admin/includes/admin.php");
		$tmpfile = download_url( $url);
		copy( $tmpfile, $path );
		@unlink( $tmpfile ); // must unlink afterwards

        //make mo for po file
        $this->phpmo_convert($path);
        return $result;
    }

    function copyLanguageFileIntoTheme( $sourcepath,$language_code ){
        $theme = get_option( 'template' );
        if($theme == 'job-manager'){
            $source_po = $sourcepath.'/js-jobs-'.$language_code.'.po';
            $source_mo = $sourcepath.'/js-jobs-'.$language_code.'.mo';

            $path = get_template_directory()."/languages/";
            $path_po = $path.$language_code.'.po';
            $path_mo = $path.$language_code.'.mo';

            copy( $source_po, $path_po );
            copy( $source_mo, $path_mo );

        }
        return true;
    }

    function isConnected(){

        $connected = @fsockopen("www.google.com", 80);
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function phpmo_convert($input, $output = false) {
        if ( !$output )
            $output = jsjobslib::jsjobs_str_replace( '.po', '.mo', $input );
        $hash = $this->phpmo_parse_po_file( $input );
        if ( $hash === false ) {
            return false;
        } else {
            $this->phpmo_write_mo_file( $hash, $output );
            return true;
        }
    }

    function phpmo_clean_helper($x) {
        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->phpmo_clean_helper($v);
            }
        } else {
            if ($x[0] == '"')
                $x = jsjobslib::jsjobs_substr($x, 1, -1);
            $x = jsjobslib::jsjobs_str_replace("\"\n\"", '', $x);
            $x = jsjobslib::jsjobs_str_replace('$', '\\$', $x);
        }
        return $x;
    }
    /* Parse gettext .po files. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
    function phpmo_parse_po_file($in) {
    if (!file_exists($in)){ return false; }
    $ids = array();
    $strings = array();
    $language = array();
    $lines = file($in);
    foreach ($lines as $line_num => $line) {
        if (jsjobslib::jsjobs_strstr($line, 'msgid')){
            //$endpos = strrchr($line, '"');
            $endpos = strrpos($line, '"',7);
            if($endpos > 7){ // to avoid msgid ""
                $id = jsjobslib::jsjobs_substr($line, 7, $endpos-7);
                $ids[] = $id;
            }
        }elseif(jsjobslib::jsjobs_strstr($line, 'msgstr')){
            //$endpos = strrchr($line, '"');
            $endpos = strrpos($line, '"',8);
            if($endpos > 8){ // to avoid msgstr ""
                $string = jsjobslib::jsjobs_substr($line, 8, $endpos-8);
                $strings[] = array($string);
            }
        }else{}
    }
    for ($i=0; $i<count($ids); $i++){
        //Shoaib
        if(isset($ids[$i]) && isset($strings[$i])){
            /*if($entry['msgstr'][0] == '""'){
                continue;
            }*/
            $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
        }
    }
    return $language;
    }
    /* Write a GNU gettext style machine object. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
    function phpmo_write_mo_file($hash, $out) {
        // sort by msgid
        ksort($hash, SORT_STRING);
        // our mo file data
        $mo = '';
        // header data
        $offsets = array ();
        $ids = '';
        $strings = '';
        foreach ($hash as $entry) {
            $id = $entry['msgid'];
            $str = implode("\x00", $entry['msgstr']);
            // keep track of offsets
            $offsets[] = array (
                            jsjobslib::jsjobs_strlen($ids), jsjobslib::jsjobs_strlen($id), jsjobslib::jsjobs_strlen($strings), jsjobslib::jsjobs_strlen($str)
                            );
            // plural msgids are not stored (?)
            $ids .= $id . "\x00";
            $strings .= $str . "\x00";
        }
        // keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start = 7 * 4 + sizeof($hash) * 4 * 4;
        // values start right after the keys
        $value_start = $key_start +jsjobslib::jsjobs_strlen($ids);
        // first all key offsets, then all value offsets
        $key_offsets = array ();
        $value_offsets = array ();
        // calculate
        foreach ($offsets as $v) {
            list ($o1, $l1, $o2, $l2) = $v;
            $key_offsets[] = $l1;
            $key_offsets[] = $o1 + $key_start;
            $value_offsets[] = $l2;
            $value_offsets[] = $o2 + $value_start;
        }
        $offsets = array_merge($key_offsets, $value_offsets);
        // write header
        $mo .= pack('Iiiiiii', 0x950412de, // magic number
        0, // version
        sizeof($hash), // number of entries in the catalog
        7 * 4, // key index offset
        7 * 4 + sizeof($hash) * 8, // value index offset,
        0, // hashtable size (unused, thus 0)
        $key_start // hashtable offset
        );
        // offsets
        foreach ($offsets as $offset)
            $mo .= pack('i', $offset);
        // ids
        $mo .= $ids;
        // strings
        $mo .= $strings;
        file_put_contents($out, $mo);
    }
    function getMessagekey(){
        $key = 'jsjobs';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function installPluginFromAjax(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'install-plugin-ajax') ) {
             die( 'Security check Failed' ); 
        }
        if(current_user_can( 'install_plugins' )){
            $pluginslug = JSJOBSrequest::getVar('pluginslug');
            $plugins_ar = array('js-vehicle-manager','js-support-ticket','learn-manager');
            if(!in_array($pluginslug, $plugins_ar)){
                die('Plugin not found');;
            }
            if(file_exists(plugins_url($pluginslug.'/'.$pluginslug.'.php'))){
                return false;
            }
            if($pluginslug != ""){
                require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
                require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
                require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
                require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

                // Get Plugin Info
                $api = plugins_api( 'plugin_information',
                    array(
                        'slug' => $pluginslug,
                        'fields' => array(
                            'short_description' => false,
                            'sections' => false,
                            'requires' => false,
                            'rating' => false,
                            'ratings' => false,
                            'downloaded' => false,
                            'last_updated' => false,
                            'added' => false,
                            'tags' => false,
                            'compatibility' => false,
                            'homepage' => false,
                            'donate_link' => false,
                        ),
                    )
                );
                $skin     = new WP_Ajax_Upgrader_Skin();
                $upgrader = new Plugin_Upgrader( $skin );
                $upgrader->install( $api->download_link );
                if(file_exists(plugins_url($pluginslug.'/'.$pluginslug.'.php'))){
                    return true;
                }
            }
        }    
        return false;
    }

    function activatePluginFromAjax(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'activate-plugin-ajax') ) {
             die( 'Security check Failed' ); 
        }
        if(current_user_can( 'activate_plugins')){
            $pluginslug = JSJOBSrequest::getVar('pluginslug');
            $plugins_ar = array('js-vehicle-manager','js-support-ticket','learn-manager');
            if(!in_array($pluginslug, $plugins_ar)){
                die('Plugin not found');;
            }
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            if(file_exists(plugins_url($pluginslug.'/'.$pluginslug.'.php'))){
                $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                if($isactivate){
                    return false;
                }
                if($pluginslug != ""){
                    if(!defined( 'WP_ADMIN')){
                        define( 'WP_ADMIN', TRUE );
                    }
                    // define( 'WP_NETWORK_ADMIN', TRUE ); // Need for Multisite
                    if(!defined( 'WP_USER_ADMIN')){
                        define( 'WP_USER_ADMIN', TRUE );
                    }

                    ob_get_clean();
                    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                    // $isactivate = $this->run_activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                    $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                    if($isactivate){
                        return true;
                    }
                }
            }
        }
        return false;
    }



}

?>
