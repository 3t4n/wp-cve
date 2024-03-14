INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('google_map_api_key', '', 'default');

UPDATE `#__js_job_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'category' AND fieldfor = 1;

UPDATE `#__js_job_fieldsordering` SET fieldtitle = 'Highest Education' WHERE field = 'heighesteducation' AND fieldfor = 2;

UPDATE `#__js_job_fieldsordering` SET fieldtitle = 'Highest Education' WHERE field = 'heighestfinisheducation' AND fieldfor = 3;

REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.1', 'default');
