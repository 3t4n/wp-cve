UPDATE `#__js_job_categories` SET alias = REPLACE(alias, '&' , 'and');

REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.2', 'default');
