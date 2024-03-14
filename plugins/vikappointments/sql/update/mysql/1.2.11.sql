--
-- Alter for table `#__vikappointments_package`
--

ALTER TABLE `#__vikappointments_package`
ADD COLUMN `validity` int(6) unsigned DEFAULT 0 AFTER `end_ts`;

--
-- Alter for table `#__vikappointments_package_order_item`
--

ALTER TABLE `#__vikappointments_package_order_item`
ADD COLUMN `validthru` datetime DEFAULT NULL AFTER `used_app`;

--
-- Alter for table `#__vikappointments_gpayments`
--

ALTER TABLE `#__vikappointments_gpayments`
MODIFY `params` varchar(2048) DEFAULT NULL;

--
-- Alter for table `#__vikappointments_cust_mail`
--

ALTER TABLE `#__vikappointments_cust_mail`
ADD COLUMN `attachments` varchar(1024) DEFAULT NULL AFTER `content`,
ADD COLUMN `id_payment` int(10) unsigned DEFAULT 0 AFTER `id_employee`;

--
-- Alter for table `#__vikappointments_cust_mail`
--

ALTER TABLE `#__vikappointments_cronjob`
ADD COLUMN `resume_notif` datetime DEFAULT NULL COMMENT 'the date when the notifications will be resumed';

--
-- Dumping data for table `#__vikappointments_config`
--

DELETE FROM `#__vikappointments_config` WHERE `param` = 'askconfirm';
