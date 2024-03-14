INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES
('tell_a_friend_captcha', '1', 'captcha'),
('auto_assign_free_package', '1', 'creditpack'),
('free_package_purchase_only_once', '1', 'creditpack'),
('free_package_auto_approve', '1', 'creditpack'),
('register_jobseeker_redirect_page', '146', 'register'),
('register_employer_redirect_page', '', 'register'),
('visitor_add_resume_redirect_page', '146', 'visitor'),
('visitor_add_job_redirect_page', '', 'visitor'),
('temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel'),
('temp_employer_dashboard_useful_links', '1', 'emcontrolpanel'),
('temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel'),
('temp_employer_dashboard_saved_search', '1', 'emcontrolpanel'),
('temp_employer_dashboard_credits_log', '1', 'emcontrolpanel'),
('temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel'),
('temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_useful_links', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_saved_search', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_credits_log', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel'),
('vis_temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel'),
('temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_shortlisted_jobs', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel'),
('temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_shortlisted_jobs', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel'),
('vis_temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel'),
('slug_prefix', 'jm-', 'default');


ALTER TABLE `#__js_job_fieldsordering` ADD `search_ordering` TINYINT NULL AFTER `cannotsearch`;


CREATE TABLE `#__js_job_slug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) CHARACTER SET utf8 NOT NULL,
  `defaultslug` varchar(100) CHARACTER SET utf8 NOT NULL,
  `filename` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=64;

INSERT INTO `#__js_job_slug` (`id`, `slug`, `defaultslug`, `filename`, `description`, `status`) VALUES
(1, 'new-in-jsjobs', 'new-in-jsjobs', 'newinjsjobs', 'slug for new in js jobs page', 1),
(2, 'jsjobs-login', 'jsjobs-login', 'login', 'slug for login page', 1),
(3, 'jobseeker-control-panel', 'jobseeker-control-panel', 'controlpanel', 'slug for jobseeker control panel', 1),
(4, 'employer-control-panel', 'employer-control-panel', 'controlpanel', 'slug for employer control panel', 1),
(5, 'jobseeker-my-stats', 'jobseeker-my-stats', 'mystats', 'slug for job seeker my stats page', 1),
(6, 'employer-my-stats', 'employer-my-stats', 'mystats', 'slug for employer my stats page', 1),
(7, 'resumes', 'resumes', 'resumes', 'slug for resume main listing page', 1),
(8, 'jobs', 'jobs', 'jobs', 'slug for job main listing page', 1),
(9, 'my-companies', 'my-companies', 'mycompanies', 'slug for my companies page', 1),
(10, 'add-company', 'add-company', 'addcompany', 'slug for add company page', 1),
(11, 'my-jobs', 'my-jobs', 'myjobs', 'slug for my jobs page', 1),
(12, 'add-job', 'add-job', 'addjob', 'slug for add job page', 1),
(13, 'my-departments', 'my-departments', 'mydepartments', 'slug for my departments page', 1),
(14, 'add-department', 'add-department', 'adddepartment', 'slug for add department page', 1),
(15, 'department', 'department', 'viewdepartment', 'slug for view department page', 1),
(16, 'cover-letter', 'cover-letter', 'viewcoverletter', 'slug for view cover letter page', 1),
(17, 'company', 'company', 'viewcompany', 'slug for view company page', 1),
(18, 'resume', 'resume', 'viewresume', 'slug for view resume page', 1),
(19, 'job', 'job', 'viewjob', 'slug for view job page', 1),
(20, 'my-folders', 'my-folders', 'myfolders', 'slug for my folders page', 1),
(21, 'add-folder', 'add-folder', 'addfolder', 'slug for add folder page', 1),
(22, 'folder', 'folder', 'viewfolder', 'slug for view folder page', 1),
(23, 'folder-resumes', 'folder-resumes', 'folderresume', 'slug for folder resume page', 1),
(24, 'jobseeker-messages', 'jobseeker-messages', 'jobseekermessages', 'slug for job seeker messages page', 1),
(25, 'employer-messages', 'employer-messages', 'employermessages', 'slug for employer messages page', 1),
(26, 'message', 'message', 'sendmessage', 'slug for send message page', 1),
(27, 'job-messages', 'job-messages', 'jobmessages', 'slug for job messages page', 1),
(28, 'job-types', 'job-types', 'jobsbytypes', 'slug for jobs by types page', 1),
(29, 'messages', 'messages', 'messages', 'slug for messages page', 1),
(30, 'resume-search', 'resume-search', 'resumesearch', 'slug for resume search page', 1),
(31, 'resume-save-searches', 'resume-save-searches', 'resumesavesearch', 'slug for resume save search page', 1),
(32, 'resume-categories', 'resume-categories', 'resumebycategory', 'slug for resume by category page', 1),
(33, 'resume-rss', 'resume-rss', 'resumerss', 'slug for resume rss page', 1),
(34, 'employer-credits', 'employer-credits', 'employercredits', 'slug for employer credits page', 1),
(35, 'jobseeker-credits', 'jobseeker-credits', 'jobseekercredits', 'slug for job seeker credits page', 1),
(36, 'employer-purchase-history', 'employer-purchase-history', 'employerpurchasehistory', 'slug for employer purchase history page', 1),
(37, 'employer-my-stats', 'employer-my-stats', 'employermystats', 'employer my stats page', 1),
(38, 'jobseker-my-stats', 'jobseker-my-stats', 'jobseekerstats', 'slug for job seeker stats page', 1),
(39, 'employer-register', 'employer-register', 'regemployer', 'slug for register as employer page', 1),
(40, 'jobseeker-register', 'jobseeker-register', 'regjobseeker', 'reg job seeker page', 1),
(41, 'user-register', 'user-register', 'userregister', 'slug for user register page', 1),
(42, 'add-resume', 'add-resume', 'addresume', 'slug for add resume page', 1),
(43, 'my-resumes', 'my-resumes', 'myresumes', 'slug for my resumes page', 1),
(44, 'add-cover-letter', 'add-cover-letter', 'addcoverletter', 'slug for add cover letter page', 1),
(45, 'companies', 'companies', 'companies', 'slug for companies page', 1),
(46, 'my-applied-jobs', 'my-applied-jobs', 'myappliedjobs', 'slug for my applied jobs page', 1),
(47, 'job-applied-resume', 'job-applied-resume', 'jobappliedresume', 'slug for job applied resume page', 1),
(48, 'my-cover-letters', 'my-cover-letters', 'mycoverletters', 'slug for my cover letters page', 1),
(49, 'job-search', 'job-search', 'jobsearch', 'slug for job search page', 1),
(50, 'job-save-searches', 'job-save-searches', 'jobsavesearch', 'slug for job save search page', 1),
(51, 'job-alert', 'job-alert', 'jobalert', 'slug for job alert page', 1),
(52, 'job-rss', 'job-rss', 'jobrss', 'slug for job rss page', 1),
(53, 'shortlisted-jobs', 'shortlisted-jobs', 'shortlistedjobs', 'slug for shortlisted jobs page', 1),
(54, 'jobseeker-purchase-history', 'jobseeker-purchase-history', 'jobseekerpurchasehistory', 'slug for job seeker purchase history page', 1),
(55, 'jobseeker-rate-list', 'jobseeker-rate-list', 'ratelistjobseeker', 'slug for rate list job seeker page', 1),
(56, 'employer-rate-list', 'employer-rate-list', 'ratelistemployer', 'slug for rate list employer page', 1),
(57, 'jobseeker-credits-log', 'jobseeker-credits-log', 'jobseekercreditslog', 'slug for job seeker credits log page', 1),
(58, 'employer-credits-log', 'employer-credits-log', 'employercreditslog', 'slug for employer credits log page', 1),
(59, 'job-categories', 'job-categories', 'jobsbycategories', 'slug for jobs by categories page', 1),
(60, 'newest-jobs', 'newest-jobs', 'newestjobs', 'slug for newest jobs page', 1),
(61, 'job-by-types', 'job-by-types', 'jobsbytypes', 'slug for jobs by types page', 1),
(62, 'resume-pdf', 'resume-pdf', 'pdf', 'slug for pdf page', 1),
(63, 'resume-print', 'resume-print', 'printresume', 'slug for print resume page', 1);

INSERT INTO `#__js_job_fieldsordering` (`field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES  
('institute_date_from', 'Date From', 55, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
('institute_date_to', 'Date to', 56, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, 1, 0, 1, '', 0, 0, 0, 0, 0, '');


REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('productcode','jsjobs','default');
REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.4', 'default');

SET sql_mode = 'ALLOW_INVALID_DATES';

ALTER TABLE #__js_job_jobs MODIFY COLUMN latitude VARCHAR(1000);
ALTER TABLE #__js_job_jobs MODIFY COLUMN longitude VARCHAR(1000);

ALTER TABLE #__js_job_resumeinstitutes MODIFY COLUMN fromdate VARCHAR(60);
ALTER TABLE #__js_job_resumeinstitutes MODIFY COLUMN todate VARCHAR(60);

