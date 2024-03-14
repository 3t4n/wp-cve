UPDATE `#__js_job_fieldsordering` SET `required` = '0' , `sys` = '0', `cannotunpublish` = '0' WHERE `field` = 'description' AND `fieldfor` = '2';
INSERT INTO `#__js_job_slug` (`id`, `slug`, `defaultslug`, `filename`, `description`, `status`) VALUES (NULL, 'jsjobs-lost-password', 'jsjobs-lost-password', 'passwordlostform', 'slug for lost password form', '1'), (NULL, 'jsjobs-reset-new-password', 'jsjobs-reset-new-password', 'resetnewpasswordform', 'Reset password form', '1');
REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('productcode','jsjobs','default');
REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode','1.1.2','default');
