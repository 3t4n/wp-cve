REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('productcode','jsjobs','default');
REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.6', 'default');

SET sql_mode=ALLOW_INVALID_DATES;
ALTER TABLE #__js_job_resumefiles MODIFY filetype VARCHAR(255);