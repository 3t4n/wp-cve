=== Job Board Manager ===
	Contributors: PickPlugins
	Donate link: https://www.pickplugins.com/item/job-board-manager-create-job-site-for-wordpress/?ref=wordpress.org
	Tags:  Job Board Manager, Job Board, job portal, Job, Job Poster, job manager, job, job list, job listing, Job Listings, job lists, job management, job manager,
	Requires at least: 4.1
	Tested up to: 6.3
	Stable tag: 2.1.56
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Job Board Manager create advance job site.

== Description ==


**Job Board Manager** plugin created for manage job site easily. it allows you to create job board manager website within few minute without any complex setup, it has the different shortcode for displaying job archive, job submission, account, logged-in user job list and more. job single page is optimized for SEO by following schema.org markup. this plugin has the variety of action and filter hook to extend functionality as needed.


### Job Board Manager by [http://pickplugins.com](http://pickplugins.com)

* [Live Demo](https://www.pickplugins.com/demo/job-board-manager/?ref=wordpress.org)
* [Documentation](https://www.pickplugins.com/documentation/job-board-manager/?ref=wordpress.org)
* [Support](https://www.pickplugins.com/support/?ref=wordpress.org)


**Advance Job Archive**

You can display list of jobs via shortcode `[job_bm_archive]` you can display job list by filtering query arguments via filter hook, this can help you to build external search form to apply form data to filter jobs. you can also remove existing job list elements and add your own elements via action hook.
`
[job_bm_archive]
`

**Advance Dashboard**

This plugin has account dashboard is based on tabs and content, you can display any where via shortcode `[job_bm_dashboard]`, tabs can be added via filter hooks and tab content can be displayed via action hook based on each tab. you can display custom code or HTML via action hook.
`
[job_bm_dashboard]
`

**Job Submit form**

you can display job submit form via shortcode `[job_submit_form]` anywhere, you can add custom input fields via action hook and validated form data and sanitize before saving on database, and you can also display custom error message before submit form data. you can also remove existing input field by `remove_action` hook.
`
[job_bm_job_submit]
`

**Job Edit form**

Allow, user to edit their own job after publish the job, you can display job edit form any where via shortcode `[job_bm_job_edit]` you can add custom input fields via action hook and validated form data and sanitize before saving on database, and you can also display custom error message before submit form data.
`
[job_bm_job_edit]
`

**Custom Registration Form**

You can add custom input fields under registration forms and validated data and save under user meta field. you can also remove existing input field by `remove_action` hook. there is default registration form under dahsboard for no looged-in user, also you can display registration form via shrotcode to anywhere, you can use following shortcode to display user registration form
`
[job_bm_registration_form]
`

**Job categories**

Display job categories in grid view with thumbnail, title and job count. you can display popular job categories in this grid view.
`
[job_bm_job_categories]
`



**Application**

User can apply for the jobs, job poster can see application from dashboard, can give star ratings, make trash, hire applicant, and communicate via email, application also display resume or attachment if available.

**Application Methods**

You can display multiple application method for each jobs, You can also add custom application methods via filter and action hooks.

**Job Expiry**

This one is must have feature for job board plugin, so we included plugin itself, you can check if any job passed certain days and marked as them as expired, you can also trash or draft them so people will not able to see or access.

**Notification Mail**

There is 11 ready notification email templates added plugin itself, each action has different notification email, you can also add custom email notification and templates by action and filter hook.

**Translation**

Please contribute here [https://translate.wordpress.org/projects/wp-plugins/job-board-manager/](https://translate.wordpress.org/projects/wp-plugins/job-board-manager/)



== Installation ==

1. Install as regular WordPress plugin.<br />
2. Go your plugin setting via WordPress Dashboard and find "<strong>Job Board Manager</strong>" activate it.<br />




== Screenshots ==

1. Job archive display
2. Job Single page display
3. Dashboard - My jobs
4. Dashboard - Applications
5. Dashboard - My Applications
6. Job Submit Form
7. Application Conversation


== Frequently Asked Questions ==

= Single job page showing 404 error , how to solve ? =

Please go "Settings > Permalink Settings" and save again to reset permalink.



== Changelog ==


	= 2.1.55 =
     * 2023-05-21 - fix - Default user role issue fixed on register.

	= 2.1.54 =
     * 2023-05-21 - fix - Job Application submission issue fixed.

	= 2.1.53 =
     * 2023-05-15 - fix - Missing transaltion string issue fixed.

	= 2.1.52 =
     * 2023-04-15 - update - External link application method button showing separately.

	= 2.1.51 =
     * 2022-06-09 - fix - settings framework js issue fixed.

	= 2.1.50 =
     * 2022-06-09 - fix - Update readme


	= 2.1.49 =
     * 2022-05-09 - fix - Ajax application submission issue fixed.

	= 2.1.48 =
    * 2022-05-08 - fix - Job application submittion issue fixed.


	= 2.1.47 =
     * 2021-08-16 - fix - Invalid argument supplied for foreach issue fixed

	= 2.1.46 =
     * 2021-07-27 - fix - Array to string conversion issue fixed


	= 2.1.45 =
     * 2021-06-19 - add - job categories shortcode

	= 2.1.44 =
     * 2021-04-17 - fix - minor security issue updated
     * 2021-04-17 - fix - update chart.js library
     * 2021-04-17 - update - update settings framework


	= 2.1.43 =
     * 2021-04-13 - fix - security issue updated

	= 2.1.42 =
     * 2020-06-23 - fix - application message not saving issue fixed.


	= 2.1.41 =
     * 2020-06-13 - update - ajax based job application submission

	= 2.1.40 =
     * 2020-05-11 - add - notification mail for application submit.


	= 2.1.39 =
     * 2020-04-02 - add - cron to update expired date if missing.

	= 2.1.38 =
     * 2020-04-02 - add - job expire in added on job archive meta list.
     * 2020-04-02 - add - job expire in added on job single meta list.
     * 2020-04-02 - add - job Publish date added on job single meta list.
     * 2020-04-02 - add - application method External website empty link text added.


	= 2.1.37 =
    * 2020-04-02 - add - update add-on list.
    * 2020-04-02 - fix - minimum salary saving issue fixed.
    * 2020-04-02 - add - display featured job at top on job archive.
    * 2020-04-02 - add - salary added to job archive.
    * 2020-04-02 - add - rename "job_bm_JobData" to "job_bm_job_data"
    * 2020-04-02 - add - job xml page template added.


	= 2.1.36 =
    * 2020-04-02 - add - xml feed scraper add-on listed.
    * 2020-04-02 - fix - broken add-on link issue fixed.


	= 2.1.35 =
    * 2020-02-04 - add - filter hook for application mehtod "External website" text

	= 2.1.34 =
    * 2020-02-02 - add - added new application method "External website"
    * 2020-02-02 - add - added new input field job link.
    * 2020-02-02 - add - added application methods for job to override global settings.

	= 2.1.33 =
    * 2020-01-25 - fix - spelling correction

	= 2.1.32 =
    * 2020-01-23 - fix - responsive issue fixed for settings page.

	= 2.1.31 =
    * 13/01/2020 - fix - featured job color issue fixed

	= 2.1.30 =
    * 17/12/2019 - fix - responsive issue fixed for application form

	= 2.1.29 =
    * 06/12/2019 - fix - single job wrapper id echo issue fixed
    * 06/12/2019 - fix - array to string convert issue on single job for job categories.
    * 15/12/2019 - add - display job on single tag page.


	= 2.1.28 =
    * 05/10/2019 - add - filter hook job_bm_job_import_query_args added
    * 24/10/2019 - fix - company website not save issue fixed.
    * 24/10/2019 - fix - missing translate string issue fixed.
    * 24/10/2019 - fix - empty recapctha site key issue fixed.


	= 2.1.27 =
    * 05/10/2019 - add - filter hook job_bm_schema_job_posting added
    * 05/10/2019 - fix - currency field hidden issue fixed.
    * 05/10/2019 - remove - filter hook job_bm_job_submit_allowed_html_tags removed




	= 2.1.26 =
    * 22/09/2019 - add - filter hook job_bm_single_job_salary_html_$salary_type added
    * 22/09/2019 - add - non-logged user will see file input on job submit form instead of media uploader.


	= 2.1.25 =
    * 19/09/2019 - fix - function show_current_user_attachment rename to job_bm_show_current_user_attachments

	= 2.1.24 =
    * 19/09/2019 - add - job submit form - set default Total vacancies 1
    * 19/09/2019 - add - job submit form - set default Contact email to logged-in user email
    * 19/09/2019 - add - job submit form - set default Years of experience 1
    * 19/09/2019 - add - Add edit link to job preview page.
    * 13/09/2019 - add - filter hook job_bm_single_job_preview_html added
    * 13/09/2019 - fix - job edit media uploader not working issue fixed.
    * 13/09/2019 - add - media uploader - restricted media file for logged-in user.
    * 13/09/2019 - add - allow user create account job submission


	= 2.1.23 =
    * 15/09/2019 - add - add stats menu to display job post and application post by date.
    * 15/09/2019 - add - filter hook job_bm_stats_tabs added
    * 15/09/2019 - add - action hook job_bm_stats_tabs_content_ added


	= 2.1.22 =
    * 13/09/2019 - add - filter hook job_bm_my_jobs_add_job_link_attr added
    * 13/09/2019 - remove - remove common.css from job submit and job edit form.


	= 2.1.21 =
    * 10/09/2019 - add - auto login after registration success.
    * 10/09/2019 - add - Redirect on custom page after registration success.

	= 2.1.20 =
    * 09/09/2019 - update - submit text update to search on job archive search form

	= 2.1.19 =
    * 30/08/2019 - add - added new filter hook job_bm_job_archive_loop_class
    * 30/08/2019 - add - added new filter hook job_bm_job_archive_loop_meta
    * 31/08/2019 - fix - job category link on single job meta
    * 31/08/2019 - add - added new filter hook job_bm_single_job_meta
    * 31/08/2019 - add - added new filter hook job_bm_single_job_infos
    * 03/09/2019 - add - added input field salary duration added on job submission, job edit.
    * 03/09/2019 - add - missing input field job_bm_salary_currency added on job edit shortcode.




	= 2.1.18 =
    * 29/08/2019 - fix - color saving issue under Style settings issue fixed.

	= 2.1.17 =
    * 24/08/2019 - fix - email templates settings save issue fixed.
    * 24/08/2019 - fix - add-on link issue fixed.

	= 2.1.16 =
    * 24/08/2019 - add - added new action hook job_bm_job_archive_search_form
    * 24/08/2019 - add - added cat_ids attr on job archive shortcode.

	= 2.1.15 =
    * 22/08/2019 - add - added class class_get_job_data for extract job data.
    * 22/08/2019 - add - added location attr on job archive shortcode.

	= 2.1.14 =
    * 19/08/2019 - fix - missing translation issue fixed

	= 2.1.13 =
    * 18/08/2019 - fix - var_dump string output issue fixed

	= 2.1.12 =
    * 17/08/2019 - add - added filter hook 'job_bm_job_archive_loop_item_company'
    * 17/08/2019 - add - added filter hook 'job_bm_job_archive_loop_item_location'
    * 18/08/2019 - add - added some shortcode [job_bm_archive] attributes



	= 2.1.11 =
    * 16/08/2019 - fix - job edit issue fixed


	= 2.1.10 =
    * 07/08/2019 - remove - Option removed "Notify email on job submit", move to email template settings
    * 07/08/2019 - remove - Option removed "Notify email on job published" move to email template settings
    * 07/08/2019 - update - filter hook "job_bm_application_submit_errors" alter by job_bm_application_submit_errors_$id
    * 07/08/2019 - add - action hook "job_bm_settings_save" added


	= 2.1.9 =
    * 07/08/2019 - add - help section added on settings page.
    * 07/08/2019 - add - user avatar added on application conversation.
    * 07/08/2019 - add - application method icon added.


	= 2.1.8 =
    * 06/08/2019 - add - single application page added.
    * 06/08/2019 - add - action hook job_bm_before_single_application added.
    * 06/08/2019 - add - action hook job_bm_single_application_main added.
    * 06/08/2019 - add - action hook job_bm_single_application_main_no_access added.
    * 06/08/2019 - add - action hook job_bm_after_single_application added.
    * 06/08/2019 - add - application linked under my application and applications navs.
    * 06/08/2019 - fix - application received count issue fixed.



	= 2.1.7 =
    * 05/08/2019 - add - action hook job_bm_registration_form_before added
    * 05/08/2019 - add - action hook job_bm_registration_form added
    * 05/08/2019 - add - action hook job_bm_registration_form_after added
    * 05/08/2019 - add - action hook job_bm_registration_submit added
    * 04/08/2019 - add - filter hook job_bm_registration_thank_you added


	= 2.1.6 =
    * 02/08/2019 - add - filter hook job_bm_application_method_none_text added.
    * 03/08/2019 - fix - application access issue fixed.
    * 03/08/2019 - fix - application received count access issue fixed.
    * 03/08/2019 - add - application comments added.
    * 04/08/2019 - add - email notification "Application hire" added
    * 04/08/2019 - add - email notification "New comment on application" added
    * 04/08/2019 - add - email notification "Your application hire removed" added
    * 04/08/2019 - add - email notification "Your application rated" added
    * 04/08/2019 - add - email notification "Application submitted" added
    * 04/08/2019 - add - email notification "Application trashed" added
    * 04/08/2019 - add - email notification "Job Edited" added
    * 04/08/2019 - add - email notification "Job featured" added
    * 04/08/2019 - add - email notification "Job trashed" added
    * 04/08/2019 - add - filter hook job_bm_mail_headers added
    * 04/08/2019 - add - action hook job_bm_activation added




	= 2.1.5 =
    * 31/07/2019 - update - My applications redesigned.
    * 31/07/2019 - add - User can trash their own application,
    * 31/07/2019 - add - User can see application ratings.
    * 31/07/2019 - add - User can see application hired or not hired.
    * 31/07/2019 - add - added dashboard notice.
    * 31/07/2019 - add - filter hook job_bm_notice_message added.
    * 31/07/2019 - add - filter hook job_bm_notice_classes added.


	= 2.1.4 =
    * 31/07/2019 - add - admin metabox added for job.
    * 31/07/2019 - add - filter hook job_bm_metabox_job_navs added
    * 31/07/2019 - add - action hook job_bm_metabox_job_content_$id added
    * 31/07/2019 - add - action hook job_bm_meta_box_save_job added
    * 31/07/2019 - fix - translation issue fixed for missed string.



	= 2.1.3 =
    * 30/07/2019 - add - My Jobs content redesigned, display featured icon, application count icon, hired count icons

	= 2.1.2 =
    * 29/07/2019 - add - job_bm_my_jobs action hook added.
    * 29/07/2019 - update - trash job instead of delete when user click delete button on "My Jobs"
    * 29/07/2019 - add - Dashboard user stats added on account tab.
    * 29/07/2019 - add - My Jobs content redesigned, display featured icon, application count icon, hired count icons


	= 2.1.1 =
    * 29/07/2019 - fix - application access issue fixed.

	= 2.1.0 =
    * 27/07/2019 - update - action hook job_bm_action_before_job_list removed and alter by job_bm_job_archive_loop_before
    * 27/07/2019 - update - action hook job_bm_action_after_job_list removed and alter by job_bm_job_archive_loop_after
    * 27/07/2019 - add - action hook job_bm_job_archive_loop added
    * 27/07/2019 - add - action hook job_bm_job_archive_loop_no_post added
    * 27/07/2019 - add - action hook job_bm_dashboard added
    * 27/07/2019 - add - action hook job_bm_dashboard_logged_in added
    * 27/07/2019 - add - action hook job_bm_dashboard_logged_out added
    * 27/07/2019 - add - action hook job_bm_dashboard_tabs_content_$id added
    * 27/07/2019 - add - Logout menu added on dashboard menus
    * 27/07/2019 - add - My applications menu added on dashboard menus
    * 27/07/2019 - add - Applications menu added on dashboard menus
    * 27/07/2019 - add - Applications features added, Hire, Trash, Star rating added.
    * 27/07/2019 - update - action hook job_bm_action_before_single_job removed and alter by job_bm_before_single_job
    * 27/07/2019 - update - action hook job_bm_action_after_single_job removed and alter by job_bm_after_single_job
    * 27/07/2019 - update - action hook job_bm_action_single_job_main removed and alter by job_bm_single_job_main
    * 27/07/2019 - add - action hook job_bm_application_methods_$id added
    * 27/07/2019 - add - action hook job_bm_job_edit_before added
    * 27/07/2019 - add - action hook job_bm_job_edit_form added
    * 27/07/2019 - add - action hook job_bm_job_edit_after added
    * 27/07/2019 - add - action hook job_bm_job_edit_data added
    * 27/07/2019 - add - action hook job_bm_job_edit_login_required added
    * 27/07/2019 - add - filter hook job_bm_job_edit_invalid_job_id_text added
    * 27/07/2019 - add - filter hook job_bm_job_edit_login_required_text added
    * 27/07/2019 - add - filter hook job_bm_job_edit_unauthorized_text added
    * 27/07/2019 - add - action hook job_bm_job_edited added
    * 27/07/2019 - update - action hook job_bm_action_before_job_submit removed and alter by job_bm_job_submit_before
    * 27/07/2019 - update - action hook job_bm_action_job_submit_main removed and alter by job_bm_job_submit_form
    * 27/07/2019 - update - action hook job_bm_action_after_job_submit removed and alter by job_bm_job_submit_after
    * 27/07/2019 - add - action hook job_bm_job_submitted added
    * 27/07/2019 - add - action hook job_bm_job_submit_login_required added
    * 27/07/2019 - add - filter hook job_bm_job_submit_login_required_text added
    * 27/07/2019 - update - action hook job_bm_action_before_client_job_list removed and alter by job_bm_my_jobs_before
    * 27/07/2019 - update - action hook job_bm_action_after_client_job_list removed and alter by job_bm_my_jobs_after
    * 27/07/2019 - update - optimize CSS and Scripts file load where the shrotcode loaded.
    * 27/07/2019 - add - filter hook job_bm_application_methods_form_$id added
    * 27/07/2019 - update - filter hook job_bm_filters_apply_method removed and alter by job_bm_application_methods
    * 27/07/2019 - update - filter hook job_bm_filters_salary_range removed and alter by job_bm_salary_range
    * 27/07/2019 - update - filter hook job_bm_filters_job_status removed and alter by job_bm_job_status
    * 27/07/2019 - update - filter hook job_bm_filters_job_level removed and alter by job_bm_job_level
    * 27/07/2019 - update - filter hook job_bm_filters_job_type_bg_color removed and alter by job_bm_job_type_bg_color
    * 27/07/2019 - update - filter hook job_bm_filters_job_status_bg_color removed and alter by job_bm_job_status_bg_color
    * 27/07/2019 - update - filter hook job_bm_filter_create_pages removed and alter by job_bm_create_pages
    * 27/07/2019 - update - action hook job_bm_filters_tutorials removed and alter by job_bm_tutorials
    * 27/07/2019 - update - filter hook job_bm_filters_email_templates_data removed and alter by job_bm_email_templates_data
    * 27/07/2019 - update - filter hook job_bm_filters_salary_type removed and alter by job_bm_salary_type
    * 27/07/2019 - update - filter hook job_bm_filters_report_html_job removed and alter by job_bm_report_html_job
    * 27/07/2019 - update - filter hook job_bm_filters_reports_tabs removed and alter by job_bm_reports_tabs
    * 27/07/2019 - update - filter hook job_bm_filters_faq removed and alter by job_bm_faqs
    * 27/07/2019 - update - action hook job_bm_emails_templates_parameters removed and alter by job_bm_emails_templates_param
    * 27/07/2019 - remove - filter hook job_bm_settings_section_options removed
    * 27/07/2019 - remove - filter hook job_bm_filter_sidebar_sections removed
    * 27/07/2019 - remove - filter hook job_bm_settings_section_pages removed
    * 27/07/2019 - remove - filter hook job_single_meta_items removed
    * 27/07/2019 - remove - filter hook job_bm_filters_apply_method_html removed
    * 27/07/2019 - remove - filter hook job_list_item_end removed
    * 27/07/2019 - remove - filter hook job_bm_filters_job_list_grid_items removed
    * 27/07/2019 - remove - filter hook job_bm_settings_section_notification removed
    * 27/07/2019 - remove - filter hook job_bm_settings_section_job_post removed
    * 27/07/2019 - remove - filter hook job_bm_filters_apply_allowed_job_status removed
    * 27/07/2019 - remove - filter hook job_bm_filters_account_tab_my_jobs removed
    * 27/07/2019 - remove - filter hook job_bm_job_meta_scripts removed





	= 2.0.31 =
    * 22/04/2019 - fix - settings page php error issue fixed

	= 2.0.30 =
    * 02/03/2019 - fix - small translation issue fixed.

	= 2.0.29 =
    * 13/07/2018 - fix - Content format issue fixed.

	= 2.0.28 =
    * 04/02/2018 - fix - Conflict with Yoast plugin.
    * 04/02/2018 - add - Ready for translate.wordpress.org.

	= 2.0.27 =
    * 26/11/2017 - add - added new shortcode [job_bm_dashboard]
    * 26/11/2017 - add - added new shortcode [job_bm_my_jobs]
    * 26/11/2017 - remove - remove shortcode [client_job_list] replace by  [job_bm_my_jobs]



	= 2.0.26 =
    * 14/11/2017 - update - only admin can change the job option Job status, Expiry date, Featured job.
    * 14/11/2017 - update - expiry date format update.

	= 2.0.25 =
    * 10/11/2017 - remove - remove job option "Display company name?".
    * 10/11/2017 - remove - remove job option "Display company address?".
    * 10/11/2017 - remove - remove job option "Job status".
    * 10/11/2017 - remove - remove job option "Short content".
    * 10/11/2017 - remove - remove job option "Job responsibilities".
    * 10/11/2017 - remove - remove job option "Education requirements".
    * 10/11/2017 - remove - remove job option "Experience requirements".
    * 10/11/2017 - remove - remove job option "Skills requirements".
    * 10/11/2017 - remove - remove job option "Qualifications".
    * 10/11/2017 - remove - remove job option "Expiry date".
    * 10/11/2017 - remove - remove job option "Featured job".





	= 2.0.23 =
    * 21/08/2017 - fix - single job sidebar salary currency issue fixed.

	= 2.0.22 =
    * 03/04/2017 - fix - single job sidebar info missing issue fixed.
    * 03/04/2017 - added - Search input on job archive page.	
	

	= 2.0.21 =
    * 21/12/2016 - fix - default empty logo issue on single job page.

	= 2.0.20 =
    * 13/11/2016 - add - translation file update

	= 2.0.19 =
    * 13/11/2016 - add - job view count.
    * 16/11/2016 - add - Minor php error fixed.	

	= 2.0.18 =
    * 12/11/2016 - fix - excerpt word count issue fixed.

	= 2.0.17 =
    * 12/11/2016 - update - security issue update.

	= 2.0.16 =
    * 11/11/2016 - update - security issue update.

	= 2.0.15 =
    * 21/10/2016 - update - admin editing is back.

	= 2.0.14 =
    * 06/10/2016 - add - reset eamil templates.
    * 10/10/2016 - add - redirect job preview after submit job.
    * 10/10/2016 - add - job submission type step by step and accordion.
    * 18/10/2016 - add - added FAQ on help page.
	

	= 2.0.13 =
    * 08/09/2016 - add - categories added on archive meta.
    * 08/09/2016 - fix - social share issue fixed.	
    * 08/09/2016 - add - create demo job category on plugin activation.
    * 09/09/2016 - removed - Field Editor feature removed.
    * 09/09/2016 - add - company logo upload on job submission.		


	= 2.0.12 =
    * 03/09/2016 - Fix - transaltion issue fixed.
	
	= 2.0.11 =
    * 02/09/2016 - Fix - Single job page CSS minor issue fixed.

	= 2.0.10 =
    * 01/09/2016 - add - salary range added.
	
	= 2.0.9 =
    * 10/08/2016 - add - report for custom two date.

	= 2.0.8 =
    * 27/07/2016 - add - report menu added for job stats.

	= 2.0.7 =
    * 07/07/2016 - add - default apply method "none" to hide.
	* 24/07/2016 - add - Portuguese  translation file.

	= 2.0.6 =
    * 02/07/2016 - add - Dashboard widget to display last 7 days stats.

	= 2.0.5 =
    * 01/07/2016 - add - added action on my account.
	

	= 2.0.4 =
    * 29/06/2016 - add - German translation added, by Britta Skulima.

	= 2.0.3 =
    * 29/06/2016 - add - validations only for required fields.
    * 29/06/2016 - fix - job category issue on edit page.	

	= 2.0.2 =
    * 27/06/2016 - add - Job Meta fields editor.

	= 2.0.1 =
    * 23/06/2016 - add - application can't submit on expired job.
    * 23/06/2016 - update - Client job list update UI.
    * 23/06/2016 - add - Client can delete their own jobs.	
    * 23/06/2016 - add - data validation of job submit.	
    * 23/06/2016 - add - Job edit page.
    * 23/06/2016 - add - Logged in user will Redirect to preview job after job submit.	
	* 23/06/2016 - add - new option "Can user delete jobs ?".
	* 23/06/2016 - add - new option "Can user edit jobs ?".
	* 23/06/2016 - add - Share button on single job page.	

	= 2.0.0 =
    * 30/05/2016 - add - added job categories.
    * 30/05/2016 - add - added job tags.
    * 30/05/2016 - add - added welcome page to save initial settings.	
		

	= 1.0.30 =
    * 22/04/2016 - add - Italian translation file.
	
	= 1.0.29 =
    * 22/04/2016 - update - minor update.

	= 1.0.28 =
    * 21/04/2016 - update - single job page improvement.
    * 21/04/2016 - removed - featured image for job post type removed.	

	= 1.0.27 =
    * 20/04/2016 - fix - single job width for mobile device issue fixed.

	= 1.0.26 =
    * 07/04/2016 - add - added video tutorials.
	
	= 1.0.25 =
    * 02/04/2016 - update - Minor CSS update.
    * 02/04/2016 - update - Minor PHP issue update.
    * 02/04/2016 - Remove - $ symbol removed form salary on single job sidebar.
    * 02/04/2016 - fix - featured job background color issue fixed.

	= 1.0.24 =
    * 21/03/2016 - removed - removed apply method from job post meta.
    * 21/03/2016 - add - add option apply methods for jobs.	

	= 1.0.23 =
    * 18/03/2016 - fix - Salary currency issue fixed.
	
	= 1.0.22 =
    * 17/03/2016 - fix - Featured job checkbox issue fixed.
    * 17/03/2016 - add - new filter added "job_bm_filter_file_upload_extensions".
    * 17/03/2016 - removed - option removed "Hide Admin Bar to Revoke Access".

	= 1.0.21 =
    * 12/03/2016 - fix - single job minor css issue fixed.

	= 1.0.20 =
    * 16/02/2016 - fix - Fix minor php issue.
    * 16/02/2016 - add - Added log file.	

	= 1.0.19 =
    * 11/02/2016 - add - added more fields.

	= 1.0.18 =
    * 06/02/2016 - remove - removed single job short-code.

	= 1.0.17 =
    * 03/02/2016 - update - demo company logo update.
    * 03/02/2016 - update - demo email logo update.
    * 03/02/2016 - update - Removed option "Listing Duration".       

	= 1.0.16 =
    * 02/02/2016 - add - Customize email templates.

	= 1.0.15 =
    * 02/02/2016 - add - admin UI update.

	= 1.0.13 =
    * 26/12/2015 - fix - fixed minor php issue.
    
	= 1.0.13 =
    * 18/11/2015 - add - auto display job content on job single page.
    
	= 1.0.12 =
    * 09/11/2015 - add - Excerpt display from content automatically or short content.
    * 09/11/2015 - add - Setting UI update.    

	= 1.0.11 =
    * 16/09/2015 - fix - minor php error fixed in job list.
    
	= 1.0.10 =
    * 08/09/2015 - add - sanitization for job front submission form.

	= 1.0.9 =
    * 03/09/2015 - add - option for pages.
    * 03/09/2015 - add - display job list by job type, job status, expiry date.
    
	= 1.0.8 =
    * 02/09/2015 - add - Client job list.

    
	= 1.0.7 =
    * 01/09/2015 - fix - job submission email issue fixed.
    
	= 1.0.6 =
    * 30/08/2015 - add - date picker for job submit form.
    * 30/08/2015 - add - new job status 'expired' added.
    * 30/08/2015 - add - Emails Templates for email notification.    

	= 1.0.5 =
    * 24/08/2015 - add - front-end job submit form validation check.
    
	= 1.0.4 =
    * 24/08/2015 - add - front-end job submit form.
    * 24/08/2015 - add - reCAPTCHA for job submit form.
    * 24/08/2015 - add - New Submitted Job Status.       
    
	= 1.0.3 =
    * 14/08/2015 - add - company page link to job & job list.
    
	= 1.0.2 =
    * 10/08/2015 - add - company page link to job & job list.

	= 1.0.1 =
    * 10/08/2015 - add -Menu page for addons list.
    
	= 1.0.0 =
    * 05/08/2015 Initial release.
