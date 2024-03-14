<?php

/**
 * JS JOBS Uninstall
 *
 * Uninstalling JS JOBS tables, and pages.
 *
 * @author 		Ahmed Bilal
 * @category 	Core
 * @package 	JS JOBS/Uninstaller
 * @version     1.0
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();
delete_option('jsjobs_do_activation_redirect');
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_ages");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_careerlevels");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_categories");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_cities");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_companies");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_companycities");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_config");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_countries");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_coverletters");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_currencies");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_departments");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_emailtemplates");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_experiences");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_fieldsordering");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_folderresumes");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_folders");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_heighesteducation");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobalertcities");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobalertsetting");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobapply");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobcities");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobs");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobsearches");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobshortlist");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobstatus");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobs_temp");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobs_temp_time");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobtypes");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_messages");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_paymentmethodconfig");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resume");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumeaddresses");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumeemployers");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumefiles");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumeinstitutes");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumelanguages");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumereferences");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_resumesearches");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_salaryrange");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_salaryrangetypes");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_shifts");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_states");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_subcategories");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_activitylog");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_credits");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_credits_actions");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_credits_log");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_credits_pack");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_emailtemplates_config");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_employer_view_resume");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_goldfeaturedhistory");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_jobseeker_view_company");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_notifications");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_purchasehistory");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_socialprofiles");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_system_errors");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_tags");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_users");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}js_job_slug");
