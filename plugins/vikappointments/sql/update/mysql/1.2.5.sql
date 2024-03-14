--
-- Alter for table `#__vikappointments_employee`
--

ALTER TABLE `#__vikappointments_employee`
ADD COLUMN `ical_url` varchar(2048) DEFAULT NULL;

--
-- Alter for table `#__vikappointments_ser_emp_assoc`
--

ALTER TABLE `#__vikappointments_ser_emp_assoc`
ADD COLUMN `ical_url` varchar(2048) DEFAULT NULL AFTER `description`;

--
-- Alter for table `#__vikappointments_option`
--

ALTER TABLE `#__vikappointments_option`
ADD COLUMN `duration` int(6) unsigned DEFAULT 0 AFTER `id_tax`,
ADD COLUMN `level` tinyint(1) DEFAULT 1 COMMENT 'the access view level' AFTER `displaymode`;

--
-- Alter for table `#__vikappointments_option_value`
--

ALTER TABLE `#__vikappointments_option_value`
ADD COLUMN `inc_duration` int(6) unsigned DEFAULT 0 AFTER `inc_price`;

--
-- Dumping data for table `#__vikappointments_config`
--

INSERT INTO `#__vikappointments_config`
(         `param`, `setting`) VALUES
('cron_log_flush',         0);
