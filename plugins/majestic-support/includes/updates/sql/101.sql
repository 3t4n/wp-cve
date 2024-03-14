REPLACE INTO `#__mjtc_support_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode','1.0.1','default');
REPLACE INTO `#__mjtc_support_config` (`configname`, `configvalue`, `configfor`) VALUES ('productversion','101','default');

ALTER TABLE `#__mjtc_support_tickets` ADD `internalid` varchar(35) DEFAULT NULL AFTER `ticketid`;

UPDATE `#__mjtc_support_fieldsordering` SET cannotunpublish = 0 WHERE field = 'email';
UPDATE `#__mjtc_support_fieldsordering` SET cannotunpublish = 0 WHERE field = 'fullname';
UPDATE `#__mjtc_support_fieldsordering` SET cannotunpublish = 0 WHERE field = 'priority';
UPDATE `#__mjtc_support_fieldsordering` SET cannotunpublish = 0 WHERE field = 'issuesummary';
