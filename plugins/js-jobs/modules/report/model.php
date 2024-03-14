<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSReportModel {

    function getChartColor() {
        $colors = array('#3366CC', '#DC3912', '#FF9900', '#109618', '#990099', '#B77322', '#8B0707', '#AAAA11', '#316395', '#DD4477', '#3B3EAC', '#ADD042', '#9D98CA', '#ED3237', '#585570', '#4E5A62', '#5CC6D0');
        return $colors;
    }

    function getOverallReports() {
        //Line Chart Data
        $curdate = date('Y-m-d');
        $dates = '';
        $fromdate = date('Y-m-d', jsjobslib::jsjobs_strtotime("now -1 month"));
        $nextdate = $curdate;
        //Query to get Data
        $query = "SELECT created FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "'";
        $jobs = jsjobs::$_db->get_results($query);

        $query = "SELECT created FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "'";
        $resume = jsjobs::$_db->get_results($query);

        $query = "SELECT created FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "'";
        $companies = jsjobs::$_db->get_results($query);

        $query = "SELECT apply_date FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` WHERE date(apply_date) >= '" . $fromdate . "' AND date(apply_date) <= '" . $curdate . "'";
        $appliedresume = jsjobs::$_db->get_results($query);

        $date_jobs = array();
        $date_companies = array();
        $date_resume = array();
        $date_appliedresume = array();
        foreach ($jobs AS $job) {
            if (!isset($date_jobs[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($job->created))]))
                $date_jobs[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($job->created))] = 0;
            $date_jobs[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($job->created))] = $date_jobs[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($job->created))] + 1;
        }
        foreach ($resume AS $rs) {
            if (!isset($date_resume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($rs->created))]))
                $date_resume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($rs->created))] = 0;
            $date_resume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($rs->created))] = $date_resume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($rs->created))] + 1;
        }
        foreach ($companies AS $company) {
            if (!isset($date_companies[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($company->created))]))
                $date_companies[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($company->created))] = 0;
            $date_companies[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($company->created))] = $date_companies[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($company->created))] + 1;
        }
        foreach ($appliedresume AS $ar) {
            if (!isset($date_appliedresume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($ar->apply_date))]))
                $date_appliedresume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($ar->apply_date))] = 0;
            $date_appliedresume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($ar->apply_date))] = $date_appliedresume[date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($ar->apply_date))] + 1;
        }
        $job_s = 0;
        $company_s = 0;
        $resume_s = 0;
        $appliedresume_s = 0;
        $json_array = "";

        do {
            $year = date_i18n('Y', jsjobslib::jsjobs_strtotime($nextdate));
            $month = date_i18n('m', jsjobslib::jsjobs_strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d', jsjobslib::jsjobs_strtotime($nextdate));
            $job_tmp = isset($date_jobs[$nextdate]) ? $date_jobs[$nextdate] : 0;
            $resume_tmp = isset($date_resume[$nextdate]) ? $date_resume[$nextdate] : 0;
            $company_tmp = isset($date_companies[$nextdate]) ? $date_companies[$nextdate] : 0;
            $appliedresume_tmp = isset($date_appliedresume[$nextdate]) ? $date_appliedresume[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$job_tmp,$resume_tmp,$company_tmp,$appliedresume_tmp],";
            $job_s += $job_tmp;
            $company_s += $company_tmp;
            $resume_s += $resume_tmp;
            $appliedresume_s += $appliedresume_tmp;
            if($nextdate == $fromdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', jsjobslib::jsjobs_strtotime($nextdate . " -1 days"));
        } while ($nextdate != $fromdate);

        jsjobs::$_data['totaljobs'] = $job_s;
        jsjobs::$_data['totalcompany'] = $company_s;
        jsjobs::$_data['totalresume'] = $resume_s;
        jsjobs::$_data['totalappliedresume'] = $appliedresume_s;

        jsjobs::$_data['line_chart_json_array'] = $json_array;

        $query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobcategory = cat.id) AS jobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat 
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = jsjobs::$_db->get_results($query);
        $query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE category = cat.id) AS companies                    
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat 
                    ORDER BY companies DESC LIMIT 5";
        $companies = jsjobs::$_db->get_results($query);
        $query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE job_category = cat.id) AS resumes 
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat 
                    ORDER BY resumes DESC LIMIT 5";
        $resume = jsjobs::$_db->get_results($query);
        jsjobs::$_data['catbar1'] = '';
        jsjobs::$_data['catbar2'] = '';
        jsjobs::$_data['catpie'] = '';
        $colors = $this->getChartColor();
        for ($i = 0; $i < 5; $i++) {
            $job = $jobs[$i];
            $company = $companies[$i];
            $resum = $resume[$i];
            jsjobs::$_data['catbar1'] .= "['" . $job->cat_title . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
            jsjobs::$_data['catbar2'] .= "['" . $resum->cat_title . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
            jsjobs::$_data['catpie'] .= "['" . $company->cat_title . "', " . $company->companies . "],";
        }

        $query = "SELECT city.cityName,(SELECT COUNT(jobid) FROM `" . jsjobs::$_db->prefix . "js_job_jobcities` WHERE cityid = city.id ) AS jobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city 
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = jsjobs::$_db->get_results($query);
        $query = "SELECT city.cityName,(SELECT COUNT(companyid) FROM `" . jsjobs::$_db->prefix . "js_job_companycities` WHERE cityid = city.id) AS companies 
                    FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city 
                    ORDER BY companies DESC LIMIT 5";
        $companies = jsjobs::$_db->get_results($query);
        $query = "SELECT city.cityName,(SELECT COUNT(resumeid) FROM `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` WHERE address_city = city.id) AS resumes 
                    FROM `" . jsjobs::$_db->prefix . "js_job_cities` AS city 
                    ORDER BY resumes DESC LIMIT 5";
        $resume = jsjobs::$_db->get_results($query);
        jsjobs::$_data['citybar1'] = '';
        jsjobs::$_data['citybar2'] = '';
        jsjobs::$_data['citypie'] = '';
        for ($i = 0; $i < 5; $i++) {
            $job = $jobs[$i];
            $company = $companies[$i];
            $resum = $resume[$i];
            jsjobs::$_data['citybar1'] .= "['" . $job->cityName . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
            jsjobs::$_data['citybar2'] .= "['" . $resum->cityName . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
            jsjobs::$_data['citypie'] .= "['" . $company->cityName . "', " . $company->companies . "],";
        }

        $query = "SELECT jobtype.title,(SELECT COUNT(jobid) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobtype = jobtype.id ) AS jobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype 
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = jsjobs::$_db->get_results($query);
        $query = "SELECT jobtype.title,(SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE jobtype = jobtype.id) AS resumes 
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobtypes` AS jobtype 
                    ORDER BY resumes DESC LIMIT 5";
        $resume = jsjobs::$_db->get_results($query);
        jsjobs::$_data['jobtypebar1'] = '';
        jsjobs::$_data['jobtypebar2'] = '';
        for ($i = 0; $i < 5; $i++) {
            if (isset($jobs[$i]) && isset($jobs[$i])) {
                $job = $jobs[$i];
                $resum = $resume[$i];
                jsjobs::$_data['jobtypebar1'] .= "['" . $job->title . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
                jsjobs::$_data['jobtypebar2'] .= "['" . $resum->title . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . __('Jobs', 'js-jobs') . "' ],";
            }
        }
    }
    function getMessagekey(){
        $key = 'report';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }
}
?>
