<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jsjobsadmin {

    function __construct() {
        add_action('admin_menu', array($this, 'mainmenu'));
    }

    function mainmenu() {
        add_menu_page(__('Control Panel', 'js-jobs'), // Page title
                __('JS Jobs', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs', //menu slug
                array($this, 'showAdminPage'), // function name
                plugins_url('js-jobs/includes/images/admin_jsjobs1.png')
        );
        add_submenu_page('jsjobs', // parent slug
                __('Companies', 'js-jobs'), // Page title
                __('Companies', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_company', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Jobs', 'js-jobs'), // Page title
                __('Jobs', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_job', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Resume', 'js-jobs'), // Page title
                __('Resume', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_resume', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Configurations', 'js-jobs'), // Page title
                __('Configurations', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_configuration', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Departments', 'js-jobs'), // Page title
                __('Departments', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_departments', //menu slug
                array($this, 'showAdminPage') // function name
        );
        /*
        add_submenu_page('jsjobs', // parent slug
                __('Credits Pack', 'js-jobs'), // Page title
                __('Credits Pack', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Credits', 'js-jobs'), // Page title
                __('Credits', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Credits Log', 'js-jobs'), // Page title
                __('Credits Log', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        */
        add_submenu_page('jsjobs', // parent slug
                __('Reports', 'js-jobs'), // Page title
                __('Reports', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_report', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Messages', 'js-jobs'), // Page title
                __('Message', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Folder', 'js-jobs'), // Page title
                __('Folder', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Categories', 'js-jobs'), // Page title
                __('Categories', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_category', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Salary Range', 'js-jobs'), // Page title
                __('Salary Range', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_salaryrange', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Users', 'js-jobs'), // Page title
                __('Users', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_user', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Email Templates', 'js-jobs'), // Page title
                __('Email Templates', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_emailtemplate', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Pro Installer', 'js-jobs'), // Page title
                __('Pro Installer', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_proinstaller', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Email Templates Options', 'js-jobs'), // Page title
                __('Email Templates Options', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_emailtemplatestatus', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Countries', 'js-jobs'), // Page title
                __('Countries', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_country', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Ages', 'js-jobs'), // Page title
                __('Ages', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_age', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Career Level', 'js-jobs'), // Page title
                __('Career Levels', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_careerlevel', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Cities', 'js-jobs'), // Page title
                __('Cities', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_city', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Companies', 'js-jobs'), // Page title
                __('Companies', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_company', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Currency', 'js-jobs'), // Page title
                __('Currency', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_currency', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Custom Fields', 'js-jobs'), // Page title
                __('Custom Fields', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_customfield', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Employer Packages', 'js-jobs'), // Page title
                __('Employer Packages', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Experience', 'js-jobs'), // Page title
                __('Experience', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_experience', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Field Ordering', 'js-jobs'), // Page title
                __('Field Ordering', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_fieldordering', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Highest Education', 'js-jobs'), // Page title
                __('Highest Education', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_highesteducation', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Job Alert', 'js-jobs'), // Page title
                __('Job Alert', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Job Apply', 'js-jobs'), // Page title
                __('Job Apply', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_jobapply', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Job Seeker Packages', 'js-jobs'), // Page title
                __('Job Seeker Packages', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Job Status', 'js-jobs'), // Page title
                __('Job Status', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_jobstatus', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Job Types', 'js-jobs'), // Page title
                __('Job Types', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_jobtype', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Messages', 'js-jobs'), // Page title
                __('Messages', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Payment History', 'js-jobs'), // Page title
                __('Payment History', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_paymenthistory', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Payment Method Configuration', 'js-jobs'), // Page title
                __('Payment Method Configuration', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_paymenthistorymethodconfiguration', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Payment Method Configuration', 'js-jobs'), // Page title
                __('Payment Method Configuration', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_paymentmethodconfiguration', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Salary Range Types', 'js-jobs'), // Page title
                __('Salary Range Types', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_salaryrangetype', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Shifts', 'js-jobs'), // Page title
                __('Shifts', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_shift', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('States', 'js-jobs'), // Page title
                __('States', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_state', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('System Errors', 'js-jobs'), // Page title
                __('System Errors', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_systemerror', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Cover letter', 'js-jobs'), // Page title
                __('Cover letter', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_coverletter', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Users', 'js-jobs'), // Page title
                __('Users', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_user', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Address Data', 'js-jobs'), // Page title
                __('Address Data', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_addressdata', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Activity Log', 'js-jobs'), // Page title
                __('Activity Log', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_activitylog', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('JS Jobs', 'js-jobs'), // Page title
                __('JS Jobs', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_common', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Purchase History', 'js-jobs'), // Page title
                __('Purchase History', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_purchasehistory', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Translations'), // Page title
                __('Translations'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=translations', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('Shortcodes', 'js-jobs'), // Page title
                __('Shortcodes', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=shortcodes', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs', // parent slug
                __('System Errors', 'js-jobs'), // Page title
                __('System Errors', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_systemerror', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('Tags', 'js-jobs'), // Page title
                __('Tags', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs&jsjobslt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('JS Jobs Settings', 'js-jobs'), // Page title
                __('JS Jobs Settings', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_postinstallation', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('jsjobs_hide', // parent slug
                __('JS Jobs Slug', 'js-jobs'), // Page title
                __('JS Jobs Slug', 'js-jobs'), // menu title
                'jsjobs', // capability
                'jsjobs_slug', //menu slug
                array($this, 'showAdminPage') // function name
        );
    }

    function showAdminPage() {
        jsjobs::addStyleSheets();
        $page = JSJOBSrequest::getVar('page');
        $page = jsjobslib::jsjobs_str_replace('jsjobs_', '', $page);
        JSJOBSincluder::include_file($page);
    }

}

$jsjobsAdmin = new jsjobsadmin();
?>
