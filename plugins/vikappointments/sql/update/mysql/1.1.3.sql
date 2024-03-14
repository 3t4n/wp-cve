ALTER TABLE `#__vikappointments_subscription`
ADD COLUMN `ordering` int(10) unsigned DEFAULT 1;

ALTER TABLE `#__vikappointments_ser_emp_assoc`
ADD COLUMN `ordering` int(10) unsigned DEFAULT 1;