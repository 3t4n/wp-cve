--
-- Table structure for table `#__vikappointments_user_notes`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_user_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned DEFAULT 0,
  `id_parent` int(10) unsigned DEFAULT 0,
  `group` varchar(32) DEFAULT NULL COMMENT 'to which section the note belongs',
  `title` varchar(128) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `attachments` varchar(1024) DEFAULT NULL COMMENT 'JSON array of files',
  `status` tinyint(1) DEFAULT 0,
  `tags` varchar(256) DEFAULT NULL COMMENT 'comma-separated list of tags',
  `createdon` datetime DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `author` int(10) DEFAULT NULL,
  `secret` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_tag`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `color` varchar(8) DEFAULT NULL,
  `icon` varchar(48) DEFAULT 'fas fa-bookmark',
  `group` varchar(32) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `author` int(10) unsigned DEFAULT NULL,
  `ordering` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_status_code`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_status_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `code` varchar(16) NOT NULL,
  `color` varchar(8) DEFAULT NULL,
  `ordering` int(10) unsigned DEFAULT 1,
  `appointments` tinyint(1) DEFAULT 1,
  `packages` tinyint(1) DEFAULT 1,
  `subscriptions` tinyint(1) DEFAULT 1,
  `approved` tinyint(1) DEFAULT 0,
  `reserved` tinyint(1) DEFAULT 0,
  `expired` tinyint(1) DEFAULT 0,
  `cancelled` tinyint(1) DEFAULT 0,
  `paid` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_tax`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_tax` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '',
  `description` varchar(1024) DEFAULT '',
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_tax_rule`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_tax_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tax` int(10) unsigned NOT NULL,
  `name` varchar(128) DEFAULT '',
  `operator` varchar(16) NOT NULL,
  `amount` decimal(10,4) DEFAULT 0.0,
  `cap` decimal(10,2) DEFAULT 0.0,
  `apply` tinyint(1) DEFAULT 1 COMMENT '1 base cost, 2 on cascade',
  `breakdown` varchar(1024) DEFAULT NULL COMMENT 'JSON representation of taxes breakdown',
  `ordering` tinyint(2) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_option_group`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_option_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(2048) DEFAULT '',
  `ordering` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_invoice`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `inv_number` varchar(32) NOT NULL,
  `inv_date` datetime NOT NULL,
  `file` varchar(32) NOT NULL,
  `createdon` datetime NOT NULL,
  `group` varchar(48) DEFAULT 'reservation' COMMENT 'to which table it refers',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_tax`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_tax` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `id_tax` int(10) unsigned NOT NULL,
  `tag` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_tax_rule`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_tax_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `breakdown` varchar(1024) DEFAULT NULL,
  `id_tax_rule` int(10) unsigned NOT NULL,
  `id_parent` int(10) unsigned NOT NULL,
  `tag` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_status_code`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_status_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` varchar(512) NOT NULL,
  `id_status_code` int(10) unsigned NOT NULL,
  `tag` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_option_value`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_option_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `tag` varchar(8) NOT NULL,
  `id_variation` int(10) unsigned NOT NULL,
  `id_parent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_option_group`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_option_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(1024) DEFAULT '',
  `tag` varchar(8) NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_stats_widget`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_stats_widget` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned DEFAULT 0 NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `widget` varchar(64) NOT NULL,
  `position` varchar(64) NOT NULL,
  `location` varchar(16) NOT NULL,
  `size` varchar(32) DEFAULT NULL,
  `ordering` int(4) unsigned DEFAULT 1,
  `params` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_api_login`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_api_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application` varchar(64) DEFAULT '',
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `ips` varchar(256) DEFAULT '',
  `active` tinyint(1) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `denied` varchar(256) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_api_login_event_options`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_api_login_event_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_login` int(10) unsigned NOT NULL,
  `id_event` varchar(128) NOT NULL,
  `options` text DEFAULT NULL COMMENT 'JSON configuration for event/user relation',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_api_login_logs`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_api_login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_login` int(10) DEFAULT -1,
  `status` tinyint(1) DEFAULT 1,
  `content` varchar(512) NOT NULL,
  `payload` text DEFAULT NULL,
  `ip` varchar(24) DEFAULT '',
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_api_ban`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_api_ban` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(24) DEFAULT '',
  `fail_count` int(4) DEFAULt 0,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_webhook`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_webhook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `hook` varchar(128) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `secret` varchar(128) DEFAULT NULL,
  `published` tinyint(1) DEFAULT 0,
  `params` varchar(2048) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `lastping` datetime DEFAULT NULL,
  `failed` int(4) unsigned DEFAULT 0,
  `logkey` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Alter for table `#__vikappointments_reservation`
--

ALTER TABLE `#__vikappointments_reservation`
CHANGE `status` `status` varchar(16) DEFAULT 'W',
ADD COLUMN `total_net` decimal(10,2) DEFAULT 0.0 AFTER `tot_paid`,
ADD COLUMN `total_tax` decimal(10,2) DEFAULT 0.0 AFTER `total_net`,
ADD COLUMN `service_price` decimal(10,2) DEFAULT 0.0 AFTER `total_tax`,
ADD COLUMN `service_net` decimal(10,2) DEFAULT 0.0 AFTER `service_price`,
ADD COLUMN `service_tax` decimal(10,2) DEFAULT 0.0 AFTER `service_net`,
ADD COLUMN `service_gross` decimal(10,2) DEFAULT 0.0 AFTER `service_tax`,
ADD COLUMN `service_discount` decimal(10,2) DEFAULT 0.0 AFTER `service_gross`,
ADD COLUMN `payment_charge` decimal(5,2) DEFAULT 0.0 AFTER `service_discount`,
ADD COLUMN `payment_tax` decimal(5,2) DEFAULT 0.0 AFTER `payment_charge`,
ADD COLUMN `discount` decimal(10,2) DEFAULT 0.0 AFTER `payment_tax`,
ADD COLUMN `tax_breakdown` varchar(1024) DEFAULT NULL AFTER `discount`,
ADD COLUMN `user_timezone` varchar(32) DEFAULT NULL AFTER `id_user`,
ADD COLUMN `tz_offset` varchar(8) DEFAULT NULL AFTER `checkin_ts`,
ADD COLUMN `attendees` text DEFAULT NULL AFTER `custom_f`,
ADD COLUMN `icaluid` varchar(128) DEFAULT NULL;

--
-- Alter for table `#__vikappointments_res_opt_assoc`
--

ALTER TABLE `#__vikappointments_res_opt_assoc`
ADD COLUMN `net` decimal(10,2) DEFAULT 0.0 AFTER `inc_price`,
ADD COLUMN `tax` decimal(10,2) DEFAULT 0.0 AFTER `net`,
ADD COLUMN `gross` decimal(10,2) DEFAULT 0.0 AFTER `tax`,
ADD COLUMN `discount` decimal(10,2) DEFAULT 0.0 AFTER `gross`,
ADD COLUMN `tax_breakdown` varchar(1024) DEFAULT NULL AFTER `discount`;

--
-- Alter for table `#__vikappointments_service`
--

ALTER TABLE `#__vikappointments_service`
ADD COLUMN `random_emp` tinyint(1) unsigned DEFAULT 0 AFTER `choose_emp`,
ADD COLUMN `mindate` int(4) DEFAULT -1 NOT NULL AFTER `minrestr`,
ADD COLUMN `maxdate` int(4) DEFAULT -1 NOT NULL AFTER `mindate`,
ADD COLUMN `id_tax` int(10) unsigned DEFAULT 0 AFTER `price`,
ADD COLUMN `attachments` varchar(1024) DEFAULT NULL;

--
-- Alter for table `#__vikappointments_ser_emp_assoc`
--

ALTER TABLE `#__vikappointments_ser_emp_assoc`
ADD COLUMN `global` tinyint(1) DEFAULT 0 AFTER `id_employee`,
ADD COLUMN `max_capacity` int(5) DEFAULT 1 AFTER `sleep`;

--
-- Alter for table `#__vikappointments_emp_worktime`
--

ALTER TABLE `#__vikappointments_emp_worktime`
ADD COLUMN `tsdate` DATE DEFAULT NULL COMMENT 'Y-m-d format of ts in UTC' AFTER `ts`;

--
-- Alter for table `#__vikappointments_option`
--

ALTER TABLE `#__vikappointments_option`
ADD COLUMN `id_tax` int(10) unsigned DEFAULT 0 AFTER `price`,
ADD COLUMN `maxqpeople` tinyint(1) DEFAULT 0 COMMENT '1 when the maxq depends on number of participants' AFTER `maxq`,
ADD COLUMN `id_group` int(10) unsigned DEFAULT 0 AFTER `displaymode`;

--
-- Alter for table `#__vikappointments_custfields`
--

ALTER TABLE `#__vikappointments_custfields`
CHANGE `rule` `rule` varchar(32) DEFAULT '0',
ADD COLUMN `locale` varchar(8) DEFAULT '*' AFTER `poplink`,
ADD COLUMN `repeat` tinyint(1) unsigned DEFAULT 0 COMMENT 'repeat for each attendee' AFTER `locale`;

--
-- Alter for table `#__vikappointments_package`
--

ALTER TABLE `#__vikappointments_package`
ADD COLUMN `id_tax` int(10) unsigned DEFAULT 0 AFTER `level`;

--
-- Alter for table `#__vikappointments_package_order`
--

ALTER TABLE `#__vikappointments_package_order`
CHANGE `status` `status` varchar(16) DEFAULT 'W',
ADD COLUMN `total_net` decimal(10,2) DEFAULT 0.0 AFTER `tot_paid`,
ADD COLUMN `total_tax` decimal(10,2) DEFAULT 0.0 AFTER `total_net`,
ADD COLUMN `payment_charge` decimal(5,2) DEFAULT 0.0 AFTER `total_tax`,
ADD COLUMN `payment_tax` decimal(5,2) DEFAULT 0.0 AFTER `payment_charge`,
ADD COLUMN `discount` decimal(10, 2) DEFAULT 0.0 AFTER `payment_tax`,
ADD COLUMN `coupon` varchar(128) DEFAULT NULL AFTER `discount`;

--
-- Alter for table `#__vikappointments_package_order_item`
--

ALTER TABLE `#__vikappointments_package_order_item`
ADD COLUMN `net` decimal(10,2) DEFAULT 0.0 AFTER `price`,
ADD COLUMN `tax` decimal(10,2) DEFAULT 0.0 AFTER `net`,
ADD COLUMN `gross` decimal(10,2) DEFAULT 0.0 AFTER `tax`,
ADD COLUMN `discount` decimal(10,2) DEFAULT 0.0 AFTER `gross`,
ADD COLUMN `tax_breakdown` varchar(1024) DEFAULT NULL AFTER `discount`;

--
-- Alter for table `#__vikappointments_subscription`
--

ALTER TABLE `#__vikappointments_subscription`
ADD COLUMN `description` text DEFAULT NULL AFTER `name`,
ADD COLUMN `id_tax` int(10) unsigned DEFAULT 0 AFTER `price`,
ADD COLUMN `group` tinyint(1) unsigned DEFAULT 1 COMMENT 'available for customers (0) or for employees (1)' AFTER `trial`,
ADD COLUMN `services` varchar(256) DEFAULT NULL COMMENT 'all the available services (for customers group only)' AFTER `group`;

--
-- Alter for table `#__vikappointments_subscr_order`
--

ALTER TABLE `#__vikappointments_subscr_order`
CHANGE `id_employee` `id_employee` int(10) unsigned DEFAULT 0 COMMENT 'for employees only',
CHANGE `status` `status` varchar(16) DEFAULT 'W',
ADD COLUMN `id_user` int(10) unsigned DEFAULT 0 COMMENT 'for customers only' AFTER `sid`,
ADD COLUMN `total_net` decimal(10,2) DEFAULT 0.0 AFTER `tot_paid`,
ADD COLUMN `total_tax` decimal(10,2) DEFAULT 0.0 AFTER `total_net`,
ADD COLUMN `payment_charge` decimal(5,2) DEFAULT 0.0 AFTER `total_tax`,
ADD COLUMN `payment_tax` decimal(5,2) DEFAULT 0.0 AFTER `payment_charge`,
ADD COLUMN `discount` decimal(10, 2) DEFAULT 0.0 AFTER `payment_tax`,
ADD COLUMN `coupon` varchar(128) DEFAULT NULL AFTER `discount`;

--
-- Alter for table `#__vikappointments_users`
--

ALTER TABLE `#__vikappointments_users`
CHANGE `jid` `jid` int(10) DEFAULT 0,
ADD COLUMN `lifetime` tinyint(1) unsigned DEFAULT 0 COMMENT 'whether the user owns a lifetime subscription' AFTER `credit`,
ADD COLUMN `active_to_date` datetime DEFAULT NULL COMMENT 'the subscription expiration date' AFTER `lifetime`,
ADD COLUMN `active_since` datetime DEFAULT NULL COMMENT 'the first subscription registration date' AFTER `active_to_date`;

--
-- Alter for table `#__vikappointments_gpayments`
--

ALTER TABLE `#__vikappointments_gpayments`
ADD COLUMN `id_tax` int(10) unsigned DEFAULT 0 AFTER `charge`;

--
-- Alter for table `#__vikappointments_coupon`
--

ALTER TABLE `#__vikappointments_coupon`
ADD COLUMN `maxperuser` int(6) unsigned DEFAULT 0 AFTER `used_quantity`,
ADD COLUMN `applicable` varchar(16) DEFAULT 'appointments' COMMENT 'the group to which the coupon is applicable' AFTER `maxperuser`;

--
-- Alter for table `#__vikappointments_cities`
--

ALTER TABLE `#__vikappointments_cities`
DROP INDEX `idx_city_2_code`;

--
-- Alter for table `#__vikappointments_special_rates`
--

ALTER TABLE `#__vikappointments_special_rates`
CHANGE `fromdate` `fromdate` datetime DEFAULT NULL COMMENT 'start publishing',
CHANGE `todate` `todate` datetime DEFAULT NULL COMMENT 'end publishing';

--
-- Alter for table `#__vikappointments_lang_subscr`
--

ALTER TABLE `#__vikappointments_lang_subscr`
ADD COLUMN `description` text DEFAULT NULL AFTER `name`;

--
-- INT to DATE conversions
--

ALTER TABLE `#__vikappointments_reservation`
CHANGE `checkin_ts` `__checkin_ts` int(12) DEFAULT NULL,
ADD COLUMN `checkin_ts` datetime NOT NULL,
CHANGE `createdon` `__createdon` int(12) DEFAULT NULL,
ADD COLUMN `createdon` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_subscr_order`
CHANGE `createdon` `__createdon` int(12) DEFAULT NULL,
ADD COLUMN `createdon` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_package`
CHANGE `start_ts` `__start_ts` int(12) DEFAULT NULL,
ADD COLUMN `start_ts` datetime DEFAULT NULL,
CHANGE `end_ts` `__end_ts` int(12) DEFAULT NULL,
ADD COLUMN `end_ts` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_package_order`
CHANGE `createdon` `__createdon` int(12) DEFAULT NULL,
ADD COLUMN `createdon` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_package_order_item`
CHANGE `modifiedon` `__modifiedon` int(12) DEFAULT NULL,
ADD COLUMN `modifiedon` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_waitinglist`
CHANGE `timestamp` `__timestamp` int(12) DEFAULT NULL,
ADD COLUMN `timestamp` date DEFAULT NULL,
CHANGE `created_on` `__created_on` int(12) DEFAULT NULL,
ADD COLUMN `created_on` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_reviews`
CHANGE `timestamp` `__timestamp` int(12) DEFAULT NULL,
ADD COLUMN `timestamp` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_service`
CHANGE `start_publishing` `__start_publishing` int(11) DEFAULT NULL,
ADD COLUMN `start_publishing` datetime DEFAULT NULL,
CHANGE `end_publishing` `__end_publishing` int(11) DEFAULT NULL,
ADD COLUMN `end_publishing` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_employee`
ADD COLUMN `active_to_date` datetime DEFAULT NULL AFTER `active_to`,
CHANGE `active_since` `__active_since` int(12) DEFAULT NULL,
ADD COLUMN `active_since` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_coupon`
CHANGE `dstart` `__dstart` int(12) DEFAULT NULL,
ADD COLUMN `dstart` datetime DEFAULT NULL,
CHANGE `dend` `__dend` int(12) DEFAULT NULL,
ADD COLUMN `dend` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_cronjob`
CHANGE `createdon` `__createdon` int(12) DEFAULT NULL,
ADD COLUMN `createdon` datetime DEFAULT NULL;

ALTER TABLE `#__vikappointments_cronjob_log`
CHANGE `createdon` `__createdon` int(12) DEFAULT NULL,
ADD COLUMN `createdon` datetime DEFAULT NULL;

--
-- Dumping data for table `#__vikappointments_status_code`
--

INSERT INTO `#__vikappointments_status_code`
(     `name`, `code`,  `color`, `ordering`, `approved`, `reserved`, `expired`, `cancelled`, `paid`, `appointments`, `packages`, `subscriptions`) VALUES
('Confirmed',    'C', '008000',          1,          1,          1,         0,           0,      0,              1,          1,               1),
(     'Paid',    'P', '339CCC',          2,          1,          1,         0,           0,      1,              1,          1,               1),
(  'Pending',    'W', 'FF7000',          3,          0,          1,         0,           0,      0,              1,          1,               1),
(  'Removed',    'E', '990000',          4,          0,          0,         1,           0,      0,              1,          0,               0),
('Cancelled',    'X', 'F01B17',          5,          0,          0,         0,           1,      0,              1,          1,               1),
( 'Refunded',    'R', '8116C9',          6,          0,          0,         0,           1,      1,              1,          1,               1),
(  'No-Show',    'N', '828282',          7,          1,          1,         0,           0,      0,              1,          0,               0);

--
-- Dumping data for table `#__vikappointments_stats_widget`
--

-- Dashboard Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                         `widget`, `position`,  `location`,        `size`, `ordering`) VALUES
(   'dashboard_appointments_table',      'top', 'dashboard',            '',          1),
('dashboard_appointments_calendar',      'top', 'dashboard',       'small',          2);

-- Finance Widgets

INSERT INTO `#__vikappointments_stats_widget`
(               `widget`, `position`,  `location`,  `size`, `ordering`) VALUES
('finance_revenue_chart',      'top',   'finance',      '',          1),
(      'finance_overall',      'top',   'finance', 'small',          2),
('finance_coupons_table',   'bottom',   'finance',      '',          3),
('finance_coupons_chart',   'bottom',   'finance',      '',          4);

-- Appointments Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                      `widget`, `position`,    `location`,  `size`, `ordering`) VALUES
(  'appointments_revenue_chart',      'top', 'appointments',      '',          1),
(   'appointments_status_count',      'top', 'appointments', 'small',          2),
(  'appointments_revenue_table',   'bottom', 'appointments',      '',          3),
('appointments_customers_chart',   'bottom', 'appointments', 'small',          4);

-- Services Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                  `widget`, `position`, `location`,  `size`, `ordering`) VALUES
(  'services_revenue_chart',      'top', 'services',      '',          1),
(  'services_revenue_count',      'top', 'services', 'small',          2),
('services_employees_count',   'bottom', 'services', 'small',          3),
('services_employees_chart',   'bottom', 'services',      '',          4);

-- Employees Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                  `widget`, `position`,  `location`,  `size`, `ordering`) VALUES
( 'employees_revenue_chart',      'top', 'employees',      '',          1),
( 'employees_revenue_count',      'top', 'employees', 'small',          2),
('employees_services_count',   'bottom', 'employees', 'small',          3),
('employees_services_chart',   'bottom', 'employees',      '',          4);

-- Customers Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                          `widget`, `position`,  `location`,  `size`, `ordering`) VALUES
(    'customers_appointments_chart',      'top', 'customers',      '',          1),
( 'customers_appointments_weekdays',      'top', 'customers',      '',          2),
(          'customers_status_count',   'middle', 'customers',      '',          3),
(               'customers_overall',   'middle', 'customers',      '',          4),
(    'customers_preferred_services',   'bottom', 'customers',      '',          5);

-- Packages Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                `widget`, `position`, `location`,  `size`, `ordering`) VALUES
(  'packages_items_chart',      'top', 'packages',      '',          1),
(  'packages_items_count',      'top', 'packages', 'small',          2),
('packages_revenue_chart',   'middle', 'packages',      '',          3),
( 'packages_status_count',   'middle', 'packages',      '',          4),
(          'packages_rog',   'middle', 'packages',      '',          5),
('packages_revenue_table',   'bottom', 'packages',      '',          6);

-- Subscriptions Widgets

INSERT INTO `#__vikappointments_stats_widget`
(                     `widget`, `position`,      `location`,  `size`, `ordering`) VALUES
(  'subscriptions_items_chart',      'top', 'subscriptions',      '',          1),
(  'subscriptions_items_count',      'top', 'subscriptions', 'small',          2),
('subscriptions_revenue_chart',   'middle', 'subscriptions',      '',          3),
( 'subscriptions_status_count',   'middle', 'subscriptions',      '',          4),
(          'subscriptions_rog',   'middle', 'subscriptions',      '',          5),
('subscriptions_revenue_table',   'bottom', 'subscriptions',      '',          6);

--
-- Dumping data for table `#__vikappointments_config`
--

INSERT INTO `#__vikappointments_config`
(             `param`, `setting`) VALUES
(           'mindate',         0),
(           'maxdate',         0),
('calendarlayoutsite', 'monthly'),
(  'calendarweekdays',         5),
(            'deftax',        ''),
(          'usetaxbd',         0),
(        'invoiceobj',        ''),
(     'showcountdown',         0),
(    'packsmandatory',         0),
(     'subscrreguser',         1),
(   'subscrmandatory',         0),
(   'subscrthreshold',         1),
(   'exportresparams',      '{}'),
(             'apifw',         0),
(        'apilogmode',         1),
(       'apilogflush',         7),
(        'apimaxfail',        20),
(   'webhooksmaxfail',         5),
(    'webhooksuselog',         1),
(  'webhookslogspath',        ''),
(     'webhooksgroup',     'day');
