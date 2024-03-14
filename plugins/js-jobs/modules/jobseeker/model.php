<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobseekerModel {

    function getJobsByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $date = date("Y-m-d");
        $query = "SELECT  job.endgolddate,job.endfeatureddate,job.id,job.uid,job.title,job.isgoldjob,job.isfeaturedjob,job.noofjobs,job.city,job.status,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,company.name AS companyname,company.id AS companyid,company.logofilename,CONCAT(company.alias,'-',company.id) AS compnayaliasid,
                cat.cat_title, jobtype.title AS jobtypetitle,currency.symbol AS currencysymbol,srto.rangestart,srfrom.rangeend,salaryrangetype.title AS srangetypetitle,job.startpublishing,job.stoppublishing
                ,LOWER(jobtype.title) AS jobtypetit

                FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume
                JOIN " . jsjobs::$_db->prefix . "js_job_jobs AS job ON ( job.jobcategory = resume.job_category AND job.jobtype = resume.jobtype )
                JOIN " . jsjobs::$_db->prefix . "js_job_companies AS company ON  company.id = job.companyid
                JOIN `" . jsjobs::$_db->prefix . "js_job_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_currencies` AS currency ON currency.id = job.currencyid
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS srto ON srto.id = job.salaryrangefrom
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrange` AS srfrom ON srfrom.id = job.salaryrangeto
                LEFT JOIN `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryrangetype
                WHERE resume.uid = " . $uid . " AND(DATE(job.startpublishing) <= '" . $date . "' AND DATE(job.stoppublishing) >= '" . $date . "')
                GROUP BY job.id
                ORDER BY job.jobcategory LIMIT 0,5";
        $results = jsjobsdb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($d->city);
            $d->salary = JSJOBSincluder::getJSModel('common')->getSalaryRangeView($d->currencysymbol, $d->rangestart, $d->rangeend, $d->srangetypetitle);

            $data[] = $d;
        }
        jsjobs::$_data[0]['jobs'] = $data;
        return;
    }

    function getConfigurationForControlPanel() {
        // configuration for layout
        $config =  JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jscontrolpanel');
        $config['show_applied_resume_status'] = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('show_applied_resume_status');
        jsjobs::$_data['config'] = $config;
    }

    function resumeIsAutoApproved($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT application_title,status 
        FROM  " . jsjobs::$_db->prefix . "js_job_resume
        WHERE uid=" . $uid . " AND status=1";
        jsjobs::$_data[0]['isapproved'] = jsjobsdb::get_results($query);
    }

    function getMyStats($uid){
        if(!is_numeric($uid))
            return false;

        $query = "SELECT COUNT(resume.id) AS totalresume 
                    FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume
                    WHERE resume.uid =". $uid;
        jsjobs::$_data[0]['totalresume'] = jsjobsdb::get_var($query);
        
        $query = "SELECT COUNT(coverletter.id) AS totalcoverletter 
                    FROM " . jsjobs::$_db->prefix . "js_job_coverletters AS coverletter
                    WHERE coverletter.uid = ". $uid;
        jsjobs::$_data[0]['totalcoverletter'] = jsjobsdb::get_var($query);

        $query = "SELECT COUNT(resume.id) AS totalapplied
                    FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume
                    JOIN " . jsjobs::$_db->prefix . "js_job_jobapply AS jobapply ON jobapply.cvid = resume.id
                    WHERE resume.uid =". $uid;
        jsjobs::$_data[0]['totalapplied'] = jsjobsdb::get_var($query);

        return true;
    }

    function getMessagekey(){
        $key = 'jobseeker';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getResumeStatusByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT jobapply.action_status, job.title, job.city, resume.application_title, resume.photo,job.id AS jobid
                    , resume.email_address,jobcat.cat_title,resume.id,jobapply.apply_date As jobapply,company.id AS companyid,company.logofilename AS companylogo
                    FROM " . jsjobs::$_db->prefix . "js_job_resume AS resume 
                    JOIN " . jsjobs::$_db->prefix . "js_job_categories AS jobcat ON jobcat.id = resume.job_category
                    JOIN " . jsjobs::$_db->prefix . "js_job_jobapply AS jobapply ON jobapply.cvid = resume.id
                    JOIN " . jsjobs::$_db->prefix . "js_job_jobs AS job ON job.id = jobapply.jobid
                    JOIN " . jsjobs::$_db->prefix . "js_job_companies AS company ON company.id = job.companyid
                    WHERE resume.uid = " . $uid . " GROUP BY jobapply.id LIMIT 0,5";
        jsjobs::$_data[0]['resume'] = jsjobsdb::get_results($query);
    }

    function getLatestJobs() {
        $query = "SELECT job.id,job.title,company.name,job.city,company.logofilename AS companylogo,company.id AS companyid,job.created
                    FROM " . jsjobs::$_db->prefix . "js_job_jobs AS job
                    JOIN " . jsjobs::$_db->prefix . "js_job_companies AS company on  job.companyid=company.id
                    WHERE job.status=1 AND (job.stoppublishing) >= CURDATE() AND (job.startpublishing) <= CURDATE()
                    ORDER BY job.created DESC
                    LIMIT 0,4";
        $data = jsjobsdb::get_results($query);
        foreach ($data as $job) {
            $job->location = JSJOBSincluder::getJSModel('city')->getLocationDataForView($job->city);
        }
        jsjobs::$_data[0]['latestjobs'] = $data;
    }

}

?>
