<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSEmployerModel {

    function getEmployerCpTabData($uid){
        if(!is_numeric($uid)) return;
        $query="select res.id,job.title as jobtitle,job.id AS jobid,ja.action_status AS applystatus,res.application_title AS resumetitle,
                concat(res.first_name,' ',res.last_name) AS resumename ,ja.apply_date AS jobaplly ,
                res.photo
                from " . jsjobs::$_db->prefix . "js_job_resume AS res
                join  " . jsjobs::$_db->prefix . "js_job_jobapply AS ja on res.id=ja.cvid
                join " . jsjobs::$_db->prefix . "js_job_jobs AS job on ja.jobid=job.id 
                where job.uid=".$uid." GROUP BY job.id ORDER BY jobaplly DESC LIMIT 5 ";
        $applied_jobs= jsjobsdb::get_results($query);
        if(!empty($applied_jobs)){
            jsjobs::$_data[0]['cpappliedresume'] = jsjobsdb::get_results($query);
        }
        return;
    }

    function getNewestResumeForEmployer($guestflag) {
        if($guestflag == false){
            $query = "SELECT resume.id,resume.first_name,resume.middle_name,resume.last_name,resume.application_title,resume.email_address,category.cat_title,resume.experienceid,resume.created,jobtype.title AS jobtypetitle,resume.photo
                ,resume.isgoldresume,resume.isfeaturedresume,resume.status,salaryrangestart.rangestart,salaryrangeend.rangeend,salaryrangetype.title AS rangetype, currency.symbol,city.cityName AS cityname,state.name AS statename,country.name AS countryname
                ,resume.endgolddate,resume.endfeatureddate,exp.title AS total_experience,resume.params,resume.last_modified,LOWER(jobtype.title) AS jobtypetit
                ,'resumeaddress' AS address_city
                FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume 
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS category ON category.id = resume.job_category 
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_experiences` AS exp ON exp.id = resume.experienceid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangestart ON salaryrangestart.id = resume.desiredsalarystart
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS salaryrangeend ON salaryrangeend.id = resume.desiredsalaryend
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = resume.djobsalaryrangetype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = resume.dcurrencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_cities` AS city ON city.id = (SELECT address_city FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_countries` AS country ON country.id = city.countryid
                WHERE resume.status = 1 ORDER BY resume.created desc LIMIT 0,5 ";
            $results = jsjobs::$_db->get_results($query);
            $data = array();
            foreach ($results AS $d) {
                $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->symbol, $d->rangestart, $d->rangeend, $d->rangetype);
                $d->location = JSJOBSincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
                $data[] = $d;
            }
            jsjobs::$_data[0]['newestresume'] = $data;

        }
        jsjobs::$_data['config'] = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('emcontrolpanel');
    }

    function getApplliedResumeBYUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT resume.id, resume.photo,resume.application_title , resume.email_address,resume.first_name,resume.middle_name,resume.last_name,cat.cat_title,resumeaddress.address_city
                    From " . jsjobs::$_db->prefix . "js_job_jobapply AS jobapply
                    JOIN " . jsjobs::$_db->prefix . "js_job_jobs AS job ON job.id = jobapply.jobid
                    JOIN " . jsjobs::$_db->prefix . "js_job_resume AS resume ON resume.id = jobapply.cvid
                    JOIN " . jsjobs::$_db->prefix . "js_job_categories AS cat ON cat.id = resume.job_category
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_resumeaddresses AS resumeaddress ON resume.id=resumeaddress.resumeid
                    WHERE job.uid = " . $uid . " LIMIT 0,3";
        jsjobs::$_data[0]['appliedresume'] = jsjobsdb::get_results($query);
    }

    function getMyStats($uid){
        if(!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(company.id) AS totalcompany 
                    FROM " . jsjobs::$_db->prefix . "js_job_companies AS company
                    WHERE company.uid =". $uid;
        jsjobs::$_data[0]['totalcompanies'] = jsjobsdb::get_var($query);
        
        $query = "SELECT COUNT(job.id) AS totaljob 
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    WHERE job.uid = ". $uid;
        jsjobs::$_data[0]['totaljobs'] = jsjobsdb::get_var($query);

        $query = "SELECT COUNT(job.id) AS totaljobs
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.uid =". $uid;
        jsjobs::$_data[0]['activejobs'] = jsjobsdb::get_var($query);

        $query = "SELECT COUNT(job.id) AS totaljobs
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    JOIN " . jsjobs::$_db->prefix . "js_job_jobapply AS jobapply ON jobapply.jobid = job.id
                    WHERE job.uid =". $uid;
        jsjobs::$_data[0]['totalapplied'] = jsjobsdb::get_var($query);

        $query = "SELECT COUNT(job.id) AS totaljobs
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.uid =". $uid;
        jsjobs::$_data[0]['jobspublish'] = jsjobsdb::get_var($query);   

        $query = "SELECT COUNT(job.id) AS totaljobs
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    WHERE job.status = 1 AND DATE(job.stoppublishing) < CURDATE() AND job.uid =". $uid;
        jsjobs::$_data[0]['jobsexpire'] = jsjobsdb::get_var($query);   

        $query = "SELECT COUNT(department.id) AS totaldepartments 
            FROM `" . jsjobs::$_db->prefix . "js_job_departments` AS department
            JOIN `" . jsjobs::$_db->prefix . "js_job_companies` AS company ON company.id = department.companyid
            WHERE department.uid = " . $uid;
        jsjobs::$_data[0]['totaldepartments'] = jsjobsdb::get_var($query);   

        return true;
    }

    function getLatestResume(){
        $query = "SELECT resume.id, resume.photo,resume.application_title , resume.email_address,resume.first_name,resume.middle_name,resume.last_name,resumeaddress.address_city,resume.created
                    From " . jsjobs::$_db->prefix . "js_job_resume AS resume 
                    LEFT JOIN " . jsjobs::$_db->prefix . "js_job_resumeaddresses AS resumeaddress ON resume.id=resumeaddress.resumeid
                    WHERE resume.status = 1 AND resume.searchable = 1 GROUP BY resume.id ORDER BY resume.created desc LIMIT 0,4";
        $data = jsjobsdb::get_results($query);
        foreach ($data as $resume) {
            $resume->name = $resume->first_name.' '.$resume->middle_name.' '.$resume->last_name;
            $resume->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($resume->address_city);
        }
        jsjobs::$_data[0]['latestresume'] = $data;
    }

    function getMessagekey(){
        $key = 'employer';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
