<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSshortcodes {

    function __construct() {

        add_shortcode('jsjobs_employer_controlpanel', array($this, 'show_employer_controlpanel'));
        add_shortcode('jsjobs_jobseeker_controlpanel', array($this, 'show_jobseeker_controlpanel'));

        add_shortcode('jsjobs_all_companies', array($this, 'show_all_companies'));
        add_shortcode('jsjobs_job_search', array($this, 'show_job_search'));
        add_shortcode('jsjobs_job', array($this, 'show_job'));
        add_shortcode('jsjobs_job_categories', array($this, 'show_job_categories'));
        add_shortcode('jsjobs_job_types', array($this, 'show_job_types'));
        add_shortcode('jsjobs_my_appliedjobs', array($this, 'show_my_appliedjobs'));
        add_shortcode('jsjobs_my_companies', array($this, 'show_my_companies'));
        add_shortcode('jsjobs_my_coverletter', array($this, 'show_my_coverletter'));
        add_shortcode('jsjobs_my_departments', array($this, 'show_my_departments'));
        add_shortcode('jsjobs_my_jobs', array($this, 'show_my_jobs'));
        add_shortcode('jsjobs_my_resumes', array($this, 'show_my_resumes'));
        add_shortcode('jsjobs_add_company', array($this, 'show_add_company'));
        add_shortcode('jsjobs_add_coverletter', array($this, 'show_add_coverletter'));
        add_shortcode('jsjobs_add_department', array($this, 'show_add_department'));
        add_shortcode('jsjobs_add_job', array($this, 'show_add_job'));
        add_shortcode('jsjobs_add_resume', array($this, 'show_add_resume'));
        add_shortcode('jsjobs_resume_search', array($this, 'show_resume_search'));
        add_shortcode('jsjobs_employer_registration', array($this, 'show_employer_registration'));
        add_shortcode('jsjobs_jobseeker_registration', array($this, 'show_jobseeker_registration'));

        add_shortcode('jsjobs_jobseeker_my_stats', array($this, 'show_jobseeker_my_stats'));
        add_shortcode('jsjobs_employer_my_stats', array($this, 'show_employer_my_stats'));
        add_shortcode('jsjobs_login_page', array($this, 'show_login_page'));

        add_shortcode('jsjobs_searchjob', array($this, 'show_searchjob'));
    }

    function show_employer_controlpanel($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'employer',
            'jsjobslt' => 'controlpanel',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline =JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'employer');
            $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
            $employerarray = array('addcompany', 'mycompanies', 'adddepartment', 'mydepartments', 'addfolder', 'myfolders', 'addjob', 'myjobs');
            $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
            if (in_array($layout, $employerarray) && $isouruser == false && $isguest == false) {
                JSJOBSincluder::include_file('newinjsjobs', 'common');
            } else {
                JSJOBSincluder::include_file($module);
            }
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_controlpanel($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'jobseeker',
            'jsjobslt' => 'controlpanel',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'jobseeker');
            $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
            $jobseekerarray = array('addcoverletter', 'mycoverletters', 'myresumes','myappliedjobs');
            $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
            if (in_array($layout, $jobseekerarray) && $isouruser == false && $isguest == false) {
                JSJOBSincluder::include_file('newinjsjobs', 'common');
            } else {
                JSJOBSincluder::include_file($module);
            }
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_all_companies($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'company',
            'jsjobslt' => 'companies',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'company');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_search($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'jobsearch',
            'jsjobslt' => 'jobsearch',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'jobsearch');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'job',
            'jsjobslt' => 'jobs',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'job');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_categories($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'job',
            'jsjobslt' => 'jobsbycategories',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'job');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_types($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'job',
            'jsjobslt' => 'jobsbytypes',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'job');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_appliedjobs($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'jobapply',
            'jsjobslt' => 'myappliedjobs',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'jobapply');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_companies($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'company',
            'jsjobslt' => 'mycompanies',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'company');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_coverletter($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'coverletter',
            'jsjobslt' => 'mycoverletters',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'coverletter');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_departments($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'departments',
            'jsjobslt' => 'mydepartments',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'departments');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_jobs($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'job',
            'jsjobslt' => 'myjobs',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'job');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }    

    function show_my_resumes($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'resume',
            'jsjobslt' => 'myresumes',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'resume');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_add_company($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'company',
            'jsjobslt' => 'addcompany',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'company');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
    
    function show_add_coverletter($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'coverletter',
            'jsjobslt' => 'addcoverletter',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'coverletter');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
    
    function show_add_department($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'departments',
            'jsjobslt' => 'adddepartment',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'departments');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
    
    function show_add_job($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'job',
            'jsjobslt' => 'addjob',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'job');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_add_resume($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'resume',
            'jsjobslt' => 'addresume',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'resume');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
    
    function show_resume_search($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'resumesearch',
            'jsjobslt' => 'resumesearch',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'resumesearch');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
    
    function show_employer_registration($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'user',
            'jsjobslt' => 'regemployer',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'user');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_registration($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'user',
            'jsjobslt' => 'regjobseeker',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'user');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_my_stats($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'jobseeker',
            'jsjobslt' => 'mystats',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'jobseeker');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;        
    }

    function show_employer_my_stats($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'employer',
            'jsjobslt' => 'mystats',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        jsjobs::$_data['sanitized_args'] = $sanitized_args;
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'employer');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_login_page($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jsjobsme' => 'jsjobs',
            'jsjobslt' => 'login',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jsjobs::$_data['sanitized_args']) && !empty(jsjobs::$_data['sanitized_args'])){
            jsjobs::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jsjobs::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jsjobs::setPageID($pageid);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } elseif (JSJOBSincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSJOBSlayout::getUserDisabledMsg();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme', null, 'jsjobs');
            JSJOBSincluder::include_file($module);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_searchjob($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => __('Search job', 'js-jobs'),
            'showtitle' => '1',
            'jobtitle' => '1',
            'category' => '1',
            'jobtype' => '1',
            'jobstatus' => '1',
            'salaryrange' => '1',
            'shift' => '1',
            'duration' => '1',
            'startpublishing' => '1',
            'stoppublishing' => '1',
            'company' => '1',
            'address' => '1',
            'columnperrow' => '1',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);
        jsjobs::addStyleSheets();
        $offline = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSJOBSlayout::getSystemOffline();
        } else {
            $module = JSJOBSrequest::getVar('jsjobsme');
            if($module != null){
                $pageid = get_the_ID();
                jsjobs::setPageID($pageid);
                jsjobs::addStyleSheets();
                JSJOBSincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $modules_html = JSJOBSincluder::getJSModel('jobsearch')->getSearchJobs_Widget($arr->title, $arr->showtitle, $arr->jobtitle, $arr->category, $arr->jobtype, $arr->jobstatus, $arr->salaryrange, $arr->shift, $arr->duration, $arr->startpublishing, $arr->stoppublishing, $arr->company, $arr->address, $arr->columnperrow);
            echo wp_kses($modules_html, JSJOBS_ALLOWED_TAGS);
        }
        unset(jsjobs::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }
}

$shortcodes = new JSJOBSshortcodes();
?>
