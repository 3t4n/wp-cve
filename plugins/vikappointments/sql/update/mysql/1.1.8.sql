ALTER TABLE `#__vikappointments_custfields`
ADD COLUMN `description` varchar(2048) DEFAULT NULL AFTER `formname`;

ALTER TABLE `#__vikappointments_lang_customf`
ADD COLUMN `description` varchar(2048) DEFAULT NULL AFTER `name`;