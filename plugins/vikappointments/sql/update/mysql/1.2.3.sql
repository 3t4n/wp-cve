--
-- Alter for table `#__vikappointments_wpshortcodes`
--

ALTER TABLE `#__vikappointments_wpshortcodes`
ADD COLUMN `parent_id` int(10) unsigned DEFAULT 0;

--
-- Alter for table `#__vikappointments_gpayments`
--

ALTER TABLE `#__vikappointments_gpayments`
ADD COLUMN `selfconfirm` tinyint(1) NOT NULL DEFAULT 0 AFTER `setconfirmed`,
ADD COLUMN `trust` int(4) unsigned DEFAULT 0 AFTER `selfconfirm`;

--
-- Dumping data for table `#__vikappointments_config`
--

INSERT INTO `#__vikappointments_config`
(       `param`, `setting`) VALUES
( 'selfconfirm',         0),
( 'wizardstate',         1),
(  'backuptype',    'full'),
('backupfolder',        '');
