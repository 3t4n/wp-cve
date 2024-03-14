<?php
/**
 * Installation related functions and actions.
 *
 * @package wphr
 */

// don't call the file directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Installer Class
 *
 * @package wphr
 */
class clsWP_HR_Installer {

	use \WPHR\HR_MANAGER\Framework\Traits\Hooker;

	/**
	 * Binding all events
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function __construct() {
		$this->set_default_modules();

		register_activation_hook(WPHR_FILE, array($this, 'activate'));
		register_deactivation_hook(WPHR_FILE, array($this, 'deactivate'));
		//register_uninstall_hook(WPHR_FILE, array($this, 'on_uninstall'));

		$this->action('admin_menu', 'welcome_screen_menu');
		$this->action('admin_head', 'welcome_screen_menu_remove');
		$this->action('admin_head', 'change_admin_menu_icon_hook');
	}

	/**
	 * Placeholder for activation function
	 * Nothing being called here yet.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function activate() {
		$current_wphr_version = get_option('wp_wphr_version', null);
		$current_db_version = get_option('wp_wphr_db_version', null);

		$this->create_tables();
		$this->populate_data();

		if (is_null($current_wphr_version)) {
			$this->set_role();
		}

		$this->create_roles(); // @TODO: Needs to change later :)
		$this->create_cron_jobs();
		$this->setup_default_emails();

		// does it needs any update?
		$updater = new \WPHR\HR_MANAGER\Updates();
		$updater->perform_updates();

		if (is_null($current_wphr_version) && is_null($current_db_version) && apply_filters('wphr_enable_setup_wizard', true)) {
			set_transient('_wphr_activation_redirect', 1, 30);
		}

		// update to latest version
		$latest_version = wphr_get_version();
		update_option('wp_wphr_version', $latest_version);
		update_option('wp_wphr_db_version', $latest_version);
	}

	/**
	 * Include required files to prevent fatal errors
	 *
	 * @return void
	 */
	function includes_files() {
		include_once WPHR_MODULES . '/hrm/includes/functions-capabilities.php';
	}

	/**
	 * Set default mail subject, heading and body
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function setup_default_emails() {

		//Employee welcome
		$welcome = [
			'subject' => 'Welcome {full_name} to {company_name}',
			'heading' => 'Welcome Onboard {first_name}!',
			'body' => 'Dear {full_name},

Welcome aboard as a <strong>{job_title}</strong> in our <strong>{dept_title}</strong> team at <strong>{company_name}</strong>! I am pleased to have you working with us. You were selected for employment due to the attributes that you displayed that appear to match the qualities I look for in an employee.

I’m looking forward to seeing you grow and develop into an outstanding employee that exhibits a high level of care, concern, and compassion for others. I hope that you will find your work to be rewarding, challenging, and meaningful.

Your <strong>{type}</strong> employment will start from <strong>{joined_date}</strong> and you will be reporting to <strong>{reporting_to}</strong>.

Please take your time and review our yearly goals so that you can know what is expected and make a positive contribution. Again, I look forward to seeing you grow as a professional while enhancing the lives of the clients entrusted in your care.

Sincerely,
Manager Name
CEO, Company Name

{login_info}',
		];
		$is_exist = true;
		$is_exist = get_option('wphr_email_settings_employee-welcome', false);
		if (!$is_exist) {
			update_option('wphr_email_settings_employee-welcome', $welcome);
		}

		//New Leave Request
		$new_leave_request = [
			'subject' => 'New leave request received from {employee_name}',
			'heading' => 'New Leave Request',
			'body' => 'Hello,

A new leave request has been received from {employee_url}.

<strong>Leave type:</strong> {leave_type}
<strong>Date:</strong> {date_from} to {date_to}
<strong>Duration:</strong> {no_days}
<strong>Reason:</strong> {reason}

Please approve/reject this leave application by going following:

{requests_url}

Thanks.',
		];
		if (!$is_exist) {
			update_option('wphr_email_settings_new-leave-request', $new_leave_request);
		}

		//Approved Leave Request
		$approved_request = [
			'subject' => 'Your leave request has been approved',
			'heading' => 'Leave Request Approved',
			'body' => 'Hello {employee_name},

Your <strong>{leave_type}</strong> type leave request for <strong>{no_days}</strong> from {date_from} to {date_to} has been approved.

Regards
Manager Name
Company',
		];
		if (!$is_exist) {
			update_option('wphr_email_settings_approved-leave-request', $approved_request);
		}

		//Rejected Leave Request
		$reject_request = [
			'subject' => 'Your leave request has been rejected',
			'heading' => 'Leave Request Rejected',
			'body' => 'Hello {employee_name},

Your <strong>{leave_type}</strong> type leave request for <strong>{no_days}</strong> from {date_from} to {date_to} has been rejected.

The reason of rejection is: {reject_reason}

Regards
Manager Name
Company',
		];
		if (!$is_exist) {
			update_option('wphr_email_settings_rejected-leave-request', $reject_request);
		}

		// New Task Assigned
		$new_task_assigned = [
			'subject' => 'New task has been assigned to you',
			'heading' => 'New Task Assigned',
			'body' => 'Hello {employee_name},

A new task <strong>{task_title}</strong> has been assigned to you by {created_by}.
Due Date: {due_date}

Regards
Manager Name
Company',
		];
		if (!$is_exist) {
			update_option('wphr_email_settings_new-task-assigned', $new_task_assigned);
		}
	}

	/**
	 * Create cron jobs
	 *
	 * @return void
	 */
	public function create_cron_jobs() {
		wp_schedule_event(time(), 'per_minute', 'wphr_per_minute_scheduled_events');
		wp_schedule_event(time(), 'daily', 'wphr_daily_scheduled_events');
		wp_schedule_event(time(), 'weekly', 'wphr_weekly_scheduled_events');
	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {

		//When plugin deactivate then first check option here
		$plugin_deactivate_option = wphr_get_option('dactivatedata_id', 'wphr_settings_deactivation', 1);
		if ($plugin_deactivate_option == 'yes') {

			global $wpdb;
			$tbl_array = [
				$wpdb->prefix . "wphr_hr_employee_notes",
				$wpdb->prefix . "wphr_hr_employee_performance ",
				$wpdb->prefix . "wphr_company_locations",
				$wpdb->prefix . "wphr_files_upload",
				$wpdb->prefix . "wphr_hr_depts",
				$wpdb->prefix . "wphr_hr_upload ",
				$wpdb->prefix . "wphr_hr_designations",
				$wpdb->prefix . "wphr_hr_employees",
				$wpdb->prefix . "wphr_hr_employee_history",

				$wpdb->prefix . "wphr_hr_leave_policies",
				$wpdb->prefix . "wphr_hr_holiday ",
				$wpdb->prefix . "wphr_hr_leave_entitlements",
				$wpdb->prefix . "wphr_hr_leaves",
				$wpdb->prefix . "wphr_hr_leave_requests",
				$wpdb->prefix . "wphr_hr_work_exp",
				$wpdb->prefix . "wphr_hr_education ",
				$wpdb->prefix . "wphr_hr_dependents",
				$wpdb->prefix . "wphr_hr_employee_performance",
				$wpdb->prefix . "wphr_hr_announcement",

				$wpdb->prefix . "wphr_peoples",
				$wpdb->prefix . "wphr_peoplemeta",
				$wpdb->prefix . "wphr_people_types",
				$wpdb->prefix . "wphr_people_type_relations",
				$wpdb->prefix . "wphr_audit_log",

			];

			foreach ($tbl_array as $tbl_name) {
				$wpdb->query("DROP TABLE IF EXISTS $tbl_name");
			}

		} else {

		}

		wp_clear_scheduled_hook('wphr_per_minute_scheduled_events');
		wp_clear_scheduled_hook('wphr_daily_scheduled_events');
		wp_clear_scheduled_hook('wphr_weekly_scheduled_events');

	}

	/**
	 * Welcome screen menu page cb
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function welcome_screen_menu() {
		add_dashboard_page(__('Welcome to WPHR Manager', 'wphr'), 'WPHR Manager', 'manage_options', 'wphr-welcome', array($this, 'welcome_screen_content'));
	}

	/**
	 * Welcome screen menu remove
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function welcome_screen_menu_remove() {
		remove_submenu_page('index.php', 'wphr-welcome');

	}

	public function change_admin_menu_icon_hook() {
		echo '<style type="text/css" media="screen">
            .dashicons-hr-icon:before {
                font-family: Fontawesome !important;
                content: "\f2bd";
            }
            .dashicons-hr-leave-icon:before {
                font-family: FontAwesome !important;
                content: "\f235";
            }
            .dashicons-hr-settings-icon:before {
                font-family: Fontawesome !important;
                content: "\f085";
            }
            .dashicons-attendance-icon:before {
                font-family: Fontawesome !important;
                content: "\f00c";
            }
            .dashicons-recruitment-icon:before {
                font-family: Fontawesome !important;
                content: "\f2b5";
            }
            </style>';
	}

	/**
	 * Render welcome screen content
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function welcome_screen_content() {
		include WPHR_VIEWS . '/welcome-screen.php';
	}

	/**
	 * Create necessary table for wphr & HRM
	 *
	 * @since 1.0
	 *
	 * @return  void
	 */
	public function create_tables() {
		global $wpdb;

		$collate = '';

		if ($wpdb->has_cap('collation')) {
			if (!empty($wpdb->charset)) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if (!empty($wpdb->collate)) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		$table_schema = [

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_company_locations` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `company_id` int(11) unsigned DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `address_1` varchar(255) DEFAULT NULL,
                `address_2` varchar(255) DEFAULT NULL,
                `city` varchar(100) DEFAULT NULL,
                `state` varchar(100) DEFAULT NULL,
                `zip` int(6) DEFAULT NULL,
                `country` varchar(5) DEFAULT NULL,
                `fax` varchar(20) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `company_id` (`company_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_files_upload` (
             `id` int(19) NOT NULL AUTO_INCREMENT,
             `user_id` int(255) NOT NULL,
             `created_by` varchar(255) NOT NULL,
             `name` varchar(255) NOT NULL,
             `size` int(11) NOT NULL,
             `downloads` varchar(255) NOT NULL,
             `datetime` timestamp NULL DEFAULT NULL,
             `last_download` datetime DEFAULT NULL,
             PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_depts` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL DEFAULT '',
                `description` text,
                `lead` int(11) unsigned DEFAULT '0',
                `parent` int(11) unsigned DEFAULT '0',
                `status` tinyint(1) unsigned DEFAULT '1',
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_upload` (
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                 `uploaded_on` datetime NOT NULL,
                 `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_designations` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL DEFAULT '',
                `description` text,
                `status` tinyint(1) DEFAULT '1',
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_employees` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
                `employee_id` varchar(20) DEFAULT NULL,
                `designation` int(11) unsigned NOT NULL DEFAULT '0',
                `job_title_detail` varchar(255) NOT NULL DEFAULT '',
                `department` int(11) unsigned NOT NULL DEFAULT '0',
                `location` int(10) unsigned NOT NULL DEFAULT '0',
                `hiring_source` varchar(20) NOT NULL,
                `hiring_date` date NOT NULL,
                `termination_date` date NOT NULL,
                `date_of_birth` date NOT NULL,
                `reporting_to` bigint(20) unsigned NOT NULL DEFAULT '0',
                `pay_rate` int(11) unsigned NOT NULL DEFAULT '0',
                `pay_type` varchar(20) NOT NULL DEFAULT '',
                `type` varchar(20) NOT NULL,
                `status` varchar(10) NOT NULL DEFAULT '',
                `deleted_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_employee_history` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
                `module` varchar(20) DEFAULT NULL,
                `category` varchar(20) DEFAULT NULL,
                `type` varchar(20) DEFAULT NULL,
                `comment` text,
                `data` longtext,
                `date` datetime NOT NULL,
                `additional` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `module` (`module`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_employee_notes` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
                `comment` text NOT NULL,
                `comment_by` bigint(20) unsigned NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                `additional` text COLLATE utf8mb4_unicode_520_ci,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_leave_policies` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(20) DEFAULT NULL,
                `value` mediumint(5) DEFAULT NULL,
                `color` varchar(7) DEFAULT NULL,
                `department` int(11) NOT NULL,
                `designation` int(11) NOT NULL,
                `gender` varchar(50) NOT NULL,
                `marital` varchar(50) NOT NULL,
                `description` LONGTEXT NOT NULL,
                `location` INT(3) NOT NULL,
                `effective_date` TIMESTAMP NOT NULL,
                `activate` INT(2) NOT NULL,
                `execute_day` INT(11) NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_holiday` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL,
                `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                `description` text NOT NULL,
                `range_status` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_leave_entitlements` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL,
                `policy_id` int(11) unsigned DEFAULT NULL,
                `days` mediumint(4) DEFAULT NULL,
                `from_date` datetime NOT NULL,
                `to_date` datetime NOT NULL,
                `comments` text,
                `status` tinyint(2) unsigned NOT NULL,
                `created_by` bigint(20) unsigned DEFAULT NULL,
                `created_on` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_leaves` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `request_id` bigint(20) unsigned NOT NULL,
                `date` date NOT NULL,
                `length_hours` decimal(6,2) unsigned NOT NULL,
                `length_days` decimal(6,2) NOT NULL,
                `start_time` time NOT NULL,
                `end_time` time NOT NULL,
                `duration_type` tinyint(4) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                KEY `request_id` (`request_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_leave_requests` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL,
                `policy_id` int(11) unsigned NOT NULL,
                `days` tinyint(3) unsigned DEFAULT NULL,
                `start_date` datetime NOT NULL,
                `end_date` datetime NOT NULL,
                `comments` text,
                `reason` text NOT NULL,
                `status` tinyint(2) unsigned DEFAULT NULL,
                `created_by` bigint(20) unsigned DEFAULT NULL,
                `updated_by` bigint(20) unsigned DEFAULT NULL,
                `created_on` datetime NOT NULL,
                `updated_on` datetime DEFAULT NULL,
                `last_date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `policy_id` (`policy_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_work_exp` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `employee_id` int(11) DEFAULT NULL,
                `company_name` varchar(100) DEFAULT NULL,
                `job_title` varchar(100) DEFAULT NULL,
                `from` date DEFAULT NULL,
                `to` date DEFAULT NULL,
                `description` text,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `employee_id` (`employee_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_education` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `employee_id` int(11) unsigned DEFAULT NULL,
                `school` varchar(100) DEFAULT NULL,
                `degree` varchar(100) DEFAULT NULL,
                `field` varchar(100) DEFAULT NULL,
                `finished` int(4) unsigned DEFAULT NULL,
                `notes` text,
                `interest` text,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `employee_id` (`employee_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_dependents` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `employee_id` int(11) DEFAULT NULL,
                `name` varchar(100) DEFAULT NULL,
                `relation` varchar(100) DEFAULT NULL,
                `dob` date DEFAULT NULL,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `employee_id` (`employee_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_employee_performance` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `employee_id` int(11) unsigned DEFAULT NULL,
                `reporting_to` int(11) unsigned DEFAULT NULL,
                `job_knowledge` varchar(100) DEFAULT NULL,
                `work_quality` varchar(100) DEFAULT NULL,
                `attendance` varchar(100) DEFAULT NULL,
                `communication` varchar(100) DEFAULT NULL,
                `dependablity` varchar(100) DEFAULT NULL,
                `reviewer` int(11) unsigned DEFAULT NULL,
                `comments` text,
                `completion_date` datetime DEFAULT NULL,
                `goal_description` text,
                `employee_assessment` text,
                `supervisor` int(11) unsigned DEFAULT NULL,
                `supervisor_assessment` text,
                `type` text,
                `performance_date` datetime DEFAULT NULL,
                `additional` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
                PRIMARY KEY (`id`),
                KEY `employee_id` (`employee_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_hr_announcement` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL,
                `post_id` bigint(11) NOT NULL,
                `status` varchar(30) NOT NULL,
                `email_status` varchar(30) NOT NULL,
                PRIMARY KEY (id)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_peoples` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned DEFAULT '0',
                `first_name` varchar(60) DEFAULT NULL,
                `last_name` varchar(60) DEFAULT NULL,
                `company` varchar(60) DEFAULT NULL,
                `email` varchar(100) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `mobile` varchar(20) DEFAULT NULL,
                `other` varchar(50) DEFAULT NULL,
                `website` varchar(100) DEFAULT NULL,
                `fax` varchar(20) DEFAULT NULL,
                `notes` text,
                `street_1` varchar(255) DEFAULT NULL,
                `street_2` varchar(255) DEFAULT NULL,
                `city` varchar(80) DEFAULT NULL,
                `state` varchar(50) DEFAULT NULL,
                `postal_code` varchar(10) DEFAULT NULL,
                `country` varchar(20) DEFAULT NULL,
                `currency` varchar(5) DEFAULT NULL,
                `created_by` BIGINT(20) DEFAULT NULL,
                `created` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_peoplemeta` (
                `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `wphr_people_id` bigint(20) DEFAULT NULL,
                `meta_key` varchar(255) DEFAULT NULL,
                `meta_value` longtext,
                PRIMARY KEY (`meta_id`),
                KEY `wphr_people_id` (`wphr_people_id`),
                KEY `meta_key` (`meta_key`(191))
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_people_types` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(20) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_people_type_relations` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `people_id` bigint(20) unsigned DEFAULT NULL,
                `people_types_id` int(11) unsigned DEFAULT NULL,
                `deleted_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `people_id` (`people_id`),
                KEY `people_types_id` (`people_types_id`)
            ) $collate;",

			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_audit_log` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `component` varchar(50) NOT NULL DEFAULT '',
                `sub_component` varchar(50) NOT NULL DEFAULT '',
                `data_id` bigint(20) DEFAULT NULL,
                `old_value` longtext,
                `new_value` longtext,
                `message` longtext,
                `changetype` varchar(10) DEFAULT NULL,
                `created_by` bigint(20) unsigned DEFAULT NULL,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

		];

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		foreach ($table_schema as $table) {

			dbDelta($table);
		}

		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_hr_employees' AND COLUMN_NAME = 'job_title_detail'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_hr_employees` add `job_title_detail` varchar(255) after `designation`");
		}
		$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` modify `zip` varchar(255)");

		//Adding another field location for holiday by location
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_hr_holiday' AND COLUMN_NAME = 'location_id'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_hr_holiday` add `location_id` varchar(255) NULL DEFAULT 0");
		}
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_company_locations' AND COLUMN_NAME = 'office_end_time'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_start_time` TIME NULL DEFAULT NULL after `phone`");
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_end_time` TIME NULL DEFAULT NULL after `office_start_time`");
		}
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_company_locations' AND COLUMN_NAME = 'office_working_hours'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_working_hours` int(2) DEFAULT 9 after `office_end_time`");
		}
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_company_locations' AND COLUMN_NAME = 'office_timezone'");
		if (!$res) {
			$gmt_offset = get_option('gmt_offset');
			$wpdb->query($wpdb->prepare("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_timezone` varchar(10) DEFAULT %s after `phone`", $gmt_offset));
		}
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_hr_employees' AND COLUMN_NAME = 'send_mail_to_reporter'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_hr_employees` add `send_mail_to_reporter` varchar(2) after `reporting_to`");
		}
		$res = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $wpdb->dbname . "' AND TABLE_NAME = '{$wpdb->prefix}wphr_hr_employees' AND COLUMN_NAME = 'manage_leave_by_reporter'");
		if (!$res) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_hr_employees` add `manage_leave_by_reporter` varchar(2) after `send_mail_to_reporter`");

			$wpdb->query("UPDATE `{$wpdb->prefix}wphr_hr_employees` SET `send_mail_to_reporter` = 'on', `manage_leave_by_reporter` = 'on' WHERE `reporting_to` > 0");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_employee_history', 'additional')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employee_history` ADD additional varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_employee_notes', 'additional')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employee_notes` ADD additional text COLLATE utf8mb4_unicode_520_ci");
		}
		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_employee_performance', 'additional')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employee_performance` ADD additional varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_employees', 'leave_year')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employees` ADD leave_year int(2) DEFAULT 1");
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employees` ADD apply_leave_year varchar(2) DEFAULT NULL");
		}
		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_employees', 'anniversary_permission')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employees` ADD anniversary_permission varchar(10) DEFAULT NULL");
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employees` ADD work_permission varchar(10) DEFAULT NULL");
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_employees` ADD  inout_office varchar(10) DEFAULT NULL");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_leave_requests', 'is_archived')) {
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wphr_hr_leave_requests` ADD is_archived int NULL DEFAULT 0");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_company_locations', 'office_financial_year_start')) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_financial_year_start` int(2) DEFAULT 1 after `office_working_hours`");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_company_locations', 'office_financial_day_start')) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_company_locations` add `office_financial_day_start` int(2) DEFAULT 1 after `office_working_hours`");
		}

		if (!check_table_column_exists($wpdb->prefix . 'wphr_hr_depts', 'employee_label')) {
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}wphr_hr_depts` add `employee_label` varchar(50) DEFAULT NULL after `title`");
		}
	}

	/**
	 * Populate tables with initial data
	 *
	 * @return void
	 */
	public function populate_data() {
		global $wpdb;

		// check if people_types exists
		if (!$wpdb->get_var("SELECT id FROM `{$wpdb->prefix}wphr_people_types` LIMIT 0, 1")) {
			$sql = "INSERT INTO `{$wpdb->prefix}wphr_people_types` (`id`, `name`)
                    VALUES (1,'contact'), (2,'company'), (3,'customer'), (4,'vendor');";

			$wpdb->query($sql);
		}

		//Accounting

		// check if classes exists
		if (!$wpdb->get_var("SELECT id FROM `{$wpdb->prefix}wphr_ac_chart_classes` LIMIT 0, 1")) {
			$sql = "INSERT INTO `{$wpdb->prefix}wphr_ac_chart_classes` (`id`, `name`)
                    VALUES (1,'Assets'), (2,'Liabilities'), (3,'Expenses'), (4,'Income'), (5,'Equity');";

			$wpdb->query($sql);
		}

		// check if chart types exists
		if (!$wpdb->get_var("SELECT id FROM `{$wpdb->prefix}wphr_ac_chart_types` LIMIT 0, 1")) {
			$sql = "INSERT INTO `{$wpdb->prefix}wphr_ac_chart_types` (`id`, `name`, `class_id`)
                    VALUES (1,'Current Asset',1), (2,'Fixed Asset',1), (3,'Inventory',1),
                        (4,'Non-current Asset',1), (5,'Prepayment',1), (6,'Bank & Cash',1), (7,'Current Liability',2),
                        (8,'Liability',2), (9,'Non-current Liability',2), (10,'Depreciation',3),
                        (11,'Direct Costs',3), (12,'Expense',3), (13,'Revenue',4), (14,'Sales',4),
                        (15,'Other Income',4), (16,'Equity',5);";

			$wpdb->query($sql);
		}

		// check if ledger exists
		if (!$wpdb->get_var("SELECT id FROM `{$wpdb->prefix}wphr_ac_ledger` LIMIT 0, 1")) {

			$sql = "INSERT INTO `{$wpdb->prefix}wphr_ac_ledger` (`id`, `code`, `name`, `description`, `parent`, `type_id`, `currency`, `tax`, `cash_account`, `reconcile`, `system`, `active`)
                        VALUES
                        (1,'120','Accounts Receivable',NULL,0,1,'',NULL,0,0,1,1),
                        (2,'140','Inventory',NULL,0,3,'',NULL,0,0,1,1),
                        (3,'150','Office Equipment',NULL,0,2,'',NULL,0,0,1,1),
                        (4,'151','Less Accumulated Depreciation on Office Equipment',NULL,0,2,'',NULL,0,0,1,1),
                        (5,'160','Computer Equipment',NULL,0,2,'',NULL,0,0,1,1),
                        (6,'161','Less Accumulated Depreciation on Computer Equipment',NULL,0,2,'',NULL,0,0,1,1),
                        (7,'090','Petty Cash',NULL,0,6,'',NULL,1,1,0,1),
                        (8,'200','Accounts Payable',NULL,0,7,'',NULL,0,0,1,1),
                        (9,'205','Accruals',NULL,0,7,'',NULL,0,0,0,1),
                        (10,'210','Unpaid Expense Claims',NULL,0,7,'',NULL,0,0,1,1),
                        (11,'215','Wages Payable',NULL,0,7,'',NULL,0,0,1,1),
                        (12,'216','Wages Payable - Payroll',NULL,0,7,'',NULL,0,0,0,1),
                        (13,'220','Sales Tax',NULL,0,7,'',NULL,0,0,1,1),
                        (14,'230','Employee Tax Payable',NULL,0,7,'',NULL,0,0,0,1),
                        (15,'235','Employee Benefits Payable',NULL,0,7,'',NULL,0,0,0,1),
                        (16,'236','Employee Deductions payable',NULL,0,7,'',NULL,0,0,0,1),
                        (17,'240','Income Tax Payable',NULL,0,7,'',NULL,0,0,0,1),
                        (18,'250','Suspense',NULL,0,7,'',NULL,0,0,0,1),
                        (19,'255','Historical Adjustments',NULL,0,7,'',NULL,0,0,1,1),
                        (20,'260','Rounding',NULL,0,7,'',NULL,0,0,1,1),
                        (21,'835','Revenue Received in Advance',NULL,0,7,'',NULL,0,0,0,1),
                        (22,'855','Clearing Account',NULL,0,7,'',NULL,0,0,0,1),
                        (23,'290','Loan',NULL,0,9,'',NULL,0,0,0,1),
                        (24,'500','Costs of Goods Sold',NULL,0,11,'',NULL,0,0,1,1),
                        (25,'600','Advertising',NULL,0,12,'',NULL,0,0,0,1),
                        (26,'605','Bank Service Charges',NULL,0,12,'',NULL,0,0,0,1),
                        (27,'610','Janitorial Expenses',NULL,0,12,'',NULL,0,0,0,1),
                        (28,'615','Consulting & Accounting',NULL,0,12,'',NULL,0,0,0,1),
                        (29,'620','Entertainment',NULL,0,12,'',NULL,0,0,0,1),
                        (30,'624','Postage & Delivary',NULL,0,12,'',NULL,0,0,0,1),
                        (31,'628','General Expenses',NULL,0,12,'',NULL,0,0,0,1),
                        (32,'632','Insurance',NULL,0,12,'',NULL,0,0,0,1),
                        (33,'636','Legal Expenses',NULL,0,12,'',NULL,0,0,0,1),
                        (34,'640','Utilities',NULL,0,12,'',NULL,0,0,1,1),
                        (35,'644','Automobile Expenses',NULL,0,12,'',NULL,0,0,0,1),
                        (36,'648','Office Expenses',NULL,0,12,'',NULL,0,0,1,1),
                        (37,'652','Printing & Stationary',NULL,0,12,'',NULL,0,0,0,1),
                        (38,'656','Rent',NULL,0,12,'',NULL,0,0,1,1),
                        (39,'660','Repairs & Maintenance',NULL,0,12,'',NULL,0,0,0,1),
                        (40,'664','Wages & Salaries',NULL,0,12,'',NULL,0,0,0,1),
                        (41,'668','Payroll Tax Expense',NULL,0,12,'',NULL,0,0,0,1),
                        (42,'672','Dues & Subscriptions',NULL,0,12,'',NULL,0,0,0,1),
                        (43,'676','Telephone & Internet',NULL,0,12,'',NULL,0,0,0,1),
                        (44,'680','Travel',NULL,0,12,'',NULL,0,0,0,1),
                        (45,'684','Bad Debts',NULL,0,12,'',NULL,0,0,0,1),
                        (46,'700','Depreciation',NULL,0,10,'',NULL,0,0,1,1),
                        (47,'710','Income Tax Expense',NULL,0,12,'',NULL,0,0,0,1),
                        (48,'715','Employee Benefits Expense',NULL,0,12,'',NULL,0,0,0,1),
                        (49,'800','Interest Expense',NULL,0,12,'',NULL,0,0,0,1),
                        (50,'810','Bank Revaluations',NULL,0,12,'',NULL,0,0,1,1),
                        (51,'815','Unrealized Currency Gains',NULL,0,12,'',NULL,0,0,1,1),
                        (52,'820','Realized Currency Gains',NULL,0,12,'',NULL,0,0,1,1),
                        (53,'825','Sales Discount',NULL,0,12,'',NULL,0,0,1,1),
                        (54,'400','Sales',NULL,0,13,'',NULL,0,0,0,1),
                        (55,'460','Interest Income',NULL,0,13,'',NULL,0,0,0,1),
                        (56,'470','Other Revenue',NULL,0,13,'',NULL,0,0,0,1),
                        (57,'475','Purchase Discount',NULL,0,13,'',NULL,0,0,1,1),
                        (58,'300','Owners Contribution',NULL,0,16,'',NULL,0,0,0,1),
                        (59,'310','Owners Draw',NULL,0,16,'',NULL,0,0,0,1),
                        (60,'320','Retained Earnings',NULL,0,16,'',NULL,0,0,1,1),
                        (61,'330','Common Stock',NULL,0,16,'',NULL,0,0,0,1),
                        (62,'092','Savings Account',NULL,0,6,'',NULL,1,1,0,1);";

			$wpdb->query($sql);
		}

		// check if banks exists
		if (!$wpdb->get_var("SELECT id FROM `{$wpdb->prefix}wphr_ac_banks` LIMIT 0, 1")) {
			$sql = "INSERT INTO `{$wpdb->prefix}wphr_ac_banks` (`id`, `ledger_id`, `account_number`, `bank_name`)
                    VALUES  (1,7,'',''), (2,62,'012345689','ABC Bank');";

			$wpdb->query($sql);
		}

		// Subscription pages
		$subscription_settings = get_option('wphr_settings_wphr-crm_subscription', []);

		if (empty($subscription_settings)) {
			// insert default wphr subscription form settings
			$args = [
				'post_title' => __('wphr Subscription', 'wphr'),
				'post_content' => '',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
			];

			$page_id = wp_insert_post($args);

			$settings = [
				'is_enabled' => 'yes',
				'email_subject' => sprintf(__('Confirm your subscription to %s', 'wphr'), get_bloginfo('name')),
				'email_content' => sprintf(
					__("Hello!\n\nThanks so much for signing up for our newsletter.\nWe need you to activate your subscription to the list(s): [contact_groups_to_confirm] by clicking the link below: \n\n[activation_link]Click here to confirm your subscription.[/activation_link]\n\nThank you,\n\n%s", 'wphr'),
					get_bloginfo('name')
				),
				'page_id' => $page_id,
				'confirm_page_title' => __('You are now subscribed!', 'wphr'),
				'confirm_page_content' => __("We've added you to our email list. You'll hear from us shortly.", 'wphr'),
				'unsubs_page_title' => __('You are now unsubscribed', 'wphr'),
				'unsubs_page_content' => __('You are successfully unsubscribed from list(s):', 'wphr'),
			];

			update_option('wphr_settings_wphr-crm_subscription', $settings);
		}
		/**
		 *
		 * Add default data to HR manager
		 */

		$args = array(
			'role' => wphr_hr_get_manager_role(),
		);

		$users = new \WP_User_Query($args);
		$user_list = $users->get_results();
		if ($user_list) {
			foreach ($user_list as $user) {
				$employee_id = $user->ID;
				if (get_user_meta($employee_id, 'manage_leave_of_employees', true) == '') {
					update_user_meta($employee_id, 'manage_leave_of_employees', 1);
				}
				if (get_user_meta($employee_id, 'receive_mail_for_leaves', true) == '') {
					update_user_meta($employee_id, 'receive_mail_for_leaves', 1);
				}
			}
		}
	}

	/**
	 * Set default module for initial wphr setup
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function set_default_modules() {

		if (get_option('wp_wphr_version')) {
			return;
		}

		$default = [
			'hrm' => [
				'title' => __('HR Management', 'wphr'),
				'slug' => 'wphr-hrm',
				'description' => __('Human Resource Mnanagement', 'wphr'),
				'callback' => '\WPHR\HR_MANAGER\HRM\Human_Resource',
				'modules' => apply_filters('wphr_hr_modules', [])
			],
			'wphr-hr-frontend' => [
				'title' => __('HR Frontend', 'wphr'),
				'slug' => 'wphr-hr-frontend',
				'description' => __('Human Resource Frontend', 'wphr'),
				'callback' => '\WPHR\HR_MANAGER\WP_HR_FRONTEND\WPHR_HR_Frontend',
				'modules' => apply_filters('wphr_hr_modules', [])
			],
		];

		update_option('wphr_modules', $default);
	}

	/**
	 * Create user roles and capabilities
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function create_roles() {
		$this->includes_files();

		$roles_hr = wphr_hr_get_roles();

		if ($roles_hr) {
			foreach ($roles_hr as $key => $role) {
				add_role($key, $role['name'], $role['capabilities']);
			}
		}
	}

	/**
	 * Set wphr_hr_manager role for admin user
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function set_role() {
		$this->includes_files();

		$admins = get_users(array('role' => 'administrator'));

		if ($admins) {
			foreach ($admins as $user) {
				$user->add_role(wphr_hr_get_manager_role());
				/*$user->add_role( wphr_crm_get_manager_role() );
                $user->add_role( wphr_ac_get_manager_role() );*/
			}
		}
	}
}

new clsWP_HR_Installer();
