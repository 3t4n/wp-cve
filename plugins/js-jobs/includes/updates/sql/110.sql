UPDATE `#__js_job_fieldsordering` SET `sys`='0',`cannotunpublish`=0 WHERE `field`='application_title';
UPDATE `#__js_job_fieldsordering` SET `fieldtitle`='Desired Salary' WHERE `field`='desired_salary' AND `fieldtitle`='Desire Salary';

INSERT INTO `#__js_job_fieldsordering` 
(`field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES 
('termsandconditions', 'Terms And Conditions', '25', '', '1', '0', '0', '0', '0', '1', '0', '', '', '0', '0', NULL, '1', '0', '1', '', '0', '0', '0', '0', '0', '');

INSERT INTO `#__js_job_fieldsordering` 
(`field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES 
('termsandconditions', 'Terms And Conditions', '39', '', '2', '0', '0', '0', '0', '1', '0', '', '', '0', '0', NULL, '1', '0', '1', '', '0', '0', '0', '0', '0', '');

INSERT INTO `#__js_job_fieldsordering` 
(`field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES 
('termsandconditions', 'Terms And Conditions', '35', '1', '3', '0', '0', '0', '0', '1', '0', '', '', '0', '0', NULL, '1', '0', '1', '', '0', '0', '0', '0', '0', '');

INSERT INTO `#__js_job_config` SET configfor='default', configname='terms_and_conditions_page_resume', configvalue=0;
INSERT INTO `#__js_job_config` SET configfor='default', configname='terms_and_conditions_page_company', configvalue=0;
INSERT INTO `#__js_job_config` SET configfor='default', configname='terms_and_conditions_page_job', configvalue=0;

INSERT INTO `#__js_job_config` SET configfor='default', configname='job_resume_show_all_categories', configvalue=1;


INSERT INTO `#__js_job_config` SET configfor='default', configname='system_has_cover_letter', configvalue=1;
UPDATE `#__js_job_fieldsordering` SET `showonlisting` = 1 , `cannotshowonlisting` = 0 WHERE `fieldfor` = 3 AND `field` IN('jobtype','application_title','email_address','job_category','desired_salary','total_experience');


REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('productcode','jsjobs','default');
REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode','1.1.0','default');