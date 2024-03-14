<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_DB_Manage {
	private static $collation = '';
	private static $wpdb;
	private static $db_version = '0.9.5.5'; //Change DB version only when db changes are there - this will make sure rechecking each time(php instance) through all versions update functions
	private static $current_db_version; //update purpose

	//Comment taken from WordPress
	/*
	 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
	 * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
	 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
	 */
	private static $max_index_length = 191;

	public static function on_plugin_activate() {
		self::install_or_update();
	}

	private static function install_or_update() {
		$current = get_option('wpcal_db_version', '0');
		if (!empty($current)) {
			//already installed, need to do update
			self::update();
		} else {
			self::install();
		}
	}

	private static function install() {
		$current = get_option('wpcal_db_version', '0');
		if (!empty($current)) {
			//already installed, need to do update
			return false;
		}
		self::db_init();
		self::create_tables();
		self::may_add_current_wp_admin_as_wpcal_admin();
	}

	private static function db_init() {
		global $wpdb;
		self::$collation = self::get_collation();
		self::$wpdb = $wpdb;
	}

	private static function create_tables() {
		self::create_table_admins();
		self::create_table_availability_dates();
		self::create_table_availability_periods();
		self::create_table_background_tasks();
		self::create_table_bookings();
		self::create_table_calendars();
		self::create_table_calendar_accounts();
		self::create_table_calendar_events();
		self::create_table_notices();
		self::create_table_services();
		self::create_table_service_admins();
		self::create_table_service_availability();
		self::create_table_service_availability_slots_cache();
		self::create_table_tp_accounts();
		self::create_table_tp_resources();

		add_option('wpcal_install_version', WPCAL_VERSION); //it will not update if there is exisiting one
		add_option('wpcal_db_version', self::$db_version); //it will not update if there is exisiting one
		add_option('wpcal_version', WPCAL_VERSION); //it will not update if there is exisiting one
		add_option('wpcal_last_validate_attempt', 0); //it will not update if there is exisiting one
	}

	private static function create_table_admins() {
		$table_name = self::$wpdb->prefix . "wpcal_admins";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`admin_user_id` bigint(20) unsigned NOT NULL,
			`admin_type` enum('administrator') NOT NULL,
			`status` tinyint(4) NOT NULL DEFAULT '1',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `admin_user_id` (`admin_user_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_availability_dates() {
		$table_name = self::$wpdb->prefix . "wpcal_availability_dates";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`day_index_list` varchar(20) DEFAULT NULL,
			`date_range_type` enum('relative','from_to','infinite') NOT NULL,
			`from_date` date DEFAULT NULL,
			`to_date` date DEFAULT NULL,
			`date_misc` varchar(45) DEFAULT NULL,
			`type` enum('default','custom') NOT NULL,
			`is_available` tinyint(1) unsigned NOT NULL DEFAULT '1',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `from_date` (`from_date`),
			KEY `to_date` (`to_date`),
			KEY `day_index_list` (`day_index_list`),
			KEY `date_range_type` (`date_range_type`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_availability_periods() {
		$table_name = self::$wpdb->prefix . "wpcal_availability_periods";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`availability_date_id` bigint(20) unsigned NOT NULL,
			`from_time` time NOT NULL,
			`to_time` time NOT NULL,
			PRIMARY KEY (`id`),
			KEY `availability_date_id` (`availability_date_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_background_tasks() {
		$table_name = self::$wpdb->prefix . "wpcal_background_tasks";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`task_name` varchar(128) NOT NULL,
			`status` enum('pending','running','completed','error','retry','manual', 'cancelled') NOT NULL DEFAULT 'pending',
			`scheduled_time_ts` int(10) unsigned NOT NULL DEFAULT '0',
			`expiry_ts` int(10) unsigned DEFAULT NULL,
			`main_arg_name` varchar(128) DEFAULT NULL,
			`main_arg_value` varchar(128) DEFAULT NULL,
			`task_args` mediumtext,
			`error_info` text,
			`dependant_id` bigint(20) DEFAULT NULL,
			`retry_attempts` tinyint(3) unsigned NOT NULL DEFAULT '0',
			`next_retry` int(10) unsigned DEFAULT NULL,
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `task_name` (`task_name`),
			KEY `status` (`status`),
			KEY `main_arg_name` (`main_arg_name`),
			KEY `main_arg_value` (`main_arg_value`),
			KEY `scheduled_time_ts` (`scheduled_time_ts`),
			KEY `next_retry` (`next_retry`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_bookings() {
		$table_name = self::$wpdb->prefix . "wpcal_bookings";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`service_id` bigint(20) unsigned NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT '1',
			`unique_link` varchar(64) NOT NULL DEFAULT '',
			`admin_user_id` bigint(20) unsigned NOT NULL,
			`invitee_wp_user_id` bigint(20) unsigned DEFAULT NULL,
			`invitee_name` varchar(256) NOT NULL,
			`invitee_email` varchar(256) NOT NULL,
			`invitee_question_answers` mediumtext,
			`invitee_tz` varchar(128) DEFAULT NULL,
			`location` text,
			`booking_from_time` int(10) unsigned NOT NULL,
			`booking_to_time` int(10) unsigned NOT NULL,
			`booking_ip` varchar(45) DEFAULT NULL,
			`page_used_for_booking` text,
			`event_added_calendar_provider` enum('google_calendar') DEFAULT NULL,
			`event_added_calendar_id` bigint(20) unsigned DEFAULT NULL,
			`event_added_tp_cal_id` varchar(256) DEFAULT NULL,
			`event_added_tp_event_id` varchar(256) DEFAULT NULL,
			`meeting_tp_resource_id` bigint(20) unsigned DEFAULT NULL,
			`rescheduled_booking_id` bigint(20) unsigned DEFAULT NULL,
			`reschedule_cancel_reason` mediumtext,
			`reschedule_cancel_user_id` bigint(20) unsigned DEFAULT NULL,
			`reschedule_cancel_action_ts` int(10) unsigned DEFAULT NULL,
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `service_id` (`service_id`),
			KEY `status` (`status`),
			KEY `unique_link` (`unique_link`),
			KEY `admin_user_id` (`admin_user_id`),
			KEY `booking_from_time` (`booking_from_time`),
			KEY `booking_to_time` (`booking_to_time`),
			KEY `rescheduled_booking_id` (`rescheduled_booking_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_calendars() {
		$table_name = self::$wpdb->prefix . "wpcal_calendars";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`calendar_account_id` bigint(20) unsigned NOT NULL,
			`name` varchar(256) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`tp_cal_id` varchar(256) NOT NULL,
			`is_conflict_calendar` tinyint(1) NOT NULL DEFAULT '0',
			`is_add_events_calendar` tinyint(1) NOT NULL DEFAULT '0',
			`is_readable` tinyint(1) NOT NULL,
			`is_writable` tinyint(1) NOT NULL,
			`is_primary` tinyint(1) NOT NULL,
			`timezone` varchar(128) NOT NULL,
			`list_events_sync_token` varchar(128) DEFAULT NULL,
			`list_events_sync_status` enum('started','running','completed','error') DEFAULT NULL,
			`list_events_sync_status_update_ts` int(10) unsigned DEFAULT NULL,
			`list_events_sync_last_update_ts` int(10) unsigned DEFAULT NULL,
			`events_webhook_channel_id` varchar(256) DEFAULT NULL,
			`events_webhook_resource_id` varchar(256) DEFAULT NULL,
			`events_webhook_expiry_ts` int(10) unsigned DEFAULT NULL,
			`events_webhook_not_supported` tinyint(1) unsigned DEFAULT NULL,
			`events_webhook_updated_ts` int(10) unsigned DEFAULT NULL,
			`events_webhook_last_received_ts` int(10) unsigned DEFAULT NULL,
			`do_fresh_sync` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `calendar_account_id` (`calendar_account_id`),
			KEY `tp_cal_id` (`tp_cal_id`(" . self::$max_index_length . ")),
			KEY `status` (`status`),
			KEY `is_conflict_calendar` (`is_conflict_calendar`),
			KEY `is_add_events_calendar` (`is_add_events_calendar`),
			KEY `list_events_sync_last_update_ts` (`list_events_sync_last_update_ts`),
			KEY `events_webhook_resource_id` (`events_webhook_resource_id`(" . self::$max_index_length . "))
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_calendar_accounts() {
		$table_name = self::$wpdb->prefix . "wpcal_calendar_accounts";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`admin_user_id` bigint(20) unsigned NOT NULL,
			`provider` enum('google_calendar') NOT NULL,
			`status` tinyint(1) NOT NULL,
			`tp_user_id` varchar(1000) DEFAULT NULL,
			`account_email` varchar(1000) NOT NULL,
			`api_token` text,
			`last_token_fetched_ts` int(10) unsigned NOT NULL DEFAULT '0',
			`last_token_fetch_attempt_ts` int(10) unsigned NOT NULL DEFAULT '0',
			`list_calendars_sync_token` varchar(256) DEFAULT NULL,
			`list_calendars_sync_last_update_ts` int(10) unsigned DEFAULT NULL,
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `admin_user_id` (`admin_user_id`),
			KEY `provider` (`provider`),
			KEY `status` (`status`),
			KEY `last_token_fetched_ts` (`last_token_fetched_ts`),
			KEY `last_token_fetch_attempt_ts` (`last_token_fetch_attempt_ts`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_calendar_events() {
		$table_name = self::$wpdb->prefix . "wpcal_calendar_events";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`calendar_id` bigint(20) unsigned NOT NULL,
			`status` tinyint(1) NOT NULL,
			`tp_event_id` varchar(256) NOT NULL,
			`is_wpcal_event` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`tp_summary` varchar(1000) DEFAULT NULL,
			`from_time` int(10) unsigned NOT NULL,
			`to_time` int(10) unsigned NOT NULL,
			`tp_created` int(10) unsigned DEFAULT NULL,
			`tp_updated` int(10) unsigned DEFAULT NULL,
			`tp_event_status` varchar(255) DEFAULT NULL,
			`tp_self_attendee_status` varchar(255) DEFAULT NULL,
			`tp_is_busy` tinyint(1) unsigned NOT NULL DEFAULT '1',
			`is_consider_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '1',
			`tp_event_link` varchar(1000) DEFAULT NULL,
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `calendar_id` (`calendar_id`),
			KEY `status` (`status`),
			KEY `tp_event_id` (`tp_event_id`(" . self::$max_index_length . ")),
			KEY `from_time` (`from_time`),
			KEY `to_time` (`to_time`),
			KEY `is_wpcal_event` (`is_wpcal_event`),
			KEY `tp_is_busy` (`tp_is_busy`),
			KEY `is_consider_confirmed` (`is_consider_confirmed`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_notices() {
		$table_name = self::$wpdb->prefix . "wpcal_notices";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`slug` varchar(128) NOT NULL,
			`slug_version` smallint(5) unsigned NOT NULL DEFAULT '0',
			`status` enum('pending','started','completed','dismissed','error','replaced','revoked') NOT NULL,
			`category` varchar(128) NOT NULL,
			`title` text,
			`descr` longtext,
			`source` enum('server','cron_server','plugin') NOT NULL,
			`type` enum('info','success','warning','error') NOT NULL,
			`display_type` enum('notice') NOT NULL,
			`notice_data` text,
			`display_in` enum('wp_admin_and_wpcal_admin','wp_admin','wpcal_admin') NOT NULL,
			`display_in_condition` text,
			`display_to` enum('wp_admins','wp_admin','wpcal_admins','wpcal_admin') DEFAULT NULL,
			`display_user_ids` longtext,
			`dismiss_type` enum('not_dismissible','dismissible','sub_notice_dismissible') NOT NULL,
			`dismiss_by` enum('any_one','individual') NOT NULL,
			`dismissed_user_ids` longtext,
			`from_time_ts` int(10) unsigned DEFAULT NULL,
			`to_time_ts` int(10) unsigned DEFAULT NULL,
			`sub_notices` text,
			`must_revalidate` tinyint(1) NOT NULL DEFAULT '0',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `status` (`status`),
			KEY `display_in` (`display_in`),
			KEY `dismiss_type` (`dismiss_type`),
			KEY `from_time_ts` (`from_time_ts`),
			KEY `to_time_ts` (`to_time_ts`),
			FULLTEXT KEY `dismissed_user_ids` (`dismissed_user_ids`),
			FULLTEXT KEY `display_user_ids` (`display_user_ids`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_services() {
		$table_name = self::$wpdb->prefix . "wpcal_services";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(191) NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT '1',
			`locations` mediumtext NOT NULL,
			`descr` text,
			`post_id` bigint(20) unsigned DEFAULT NULL,
			`color` varchar(100) DEFAULT NULL,
			`relationship_type` enum('1to1','1ton') NOT NULL,
			`timezone` varchar(100) NOT NULL,
			`duration` smallint(5) unsigned NOT NULL COMMENT 'in mintues',
			`display_start_time_every` smallint(5) unsigned NOT NULL COMMENT 'in mintues',
			`max_booking_per_day` int(10) unsigned DEFAULT NULL,
			`min_schedule_notice` varchar(256) NOT NULL DEFAULT '0' COMMENT 'in json object',
			`event_buffer_before` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'in mintues',
			`event_buffer_after` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'in mintues',
			`is_manage_private` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`invitee_notify_by` enum('calendar_invitation','email') NOT NULL DEFAULT 'calendar_invitation',
			`invitee_questions` mediumtext,
			`last_cached_slots_generated` int(10) unsigned DEFAULT NULL,
			`refresh_cache` tinyint(1) unsigned NOT NULL DEFAULT '1',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `name` (`name`),
			KEY `status` (`status`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_service_admins() {
		$table_name = self::$wpdb->prefix . "wpcal_service_admins";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`admin_user_id` bigint(20) NOT NULL,
			`service_id` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `admin_user_id_service_id` (`admin_user_id`,`service_id`),
			KEY `admin_user_id` (`admin_user_id`),
			KEY `service_id` (`service_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_service_availability() {
		$table_name = self::$wpdb->prefix . "wpcal_service_availability";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`service_id` bigint(20) NOT NULL,
			`availability_date_id` bigint(20) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `service_id_availability_date_id` (`service_id`,`availability_date_id`),
			KEY `service_id` (`service_id`),
			KEY `availability_date_id` (`availability_date_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_service_availability_slots_cache() {
		$table_name = self::$wpdb->prefix . "wpcal_service_availability_slots_cache";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`service_id` bigint(20) unsigned NOT NULL,
			`availability_date` date NOT NULL,
			`is_available` tinyint(1) unsigned NOT NULL,
			`is_all_booked` tinyint(1) unsigned NOT NULL,
			`cache_created_ts` int(10) unsigned NOT NULL,
			`slots` longtext NOT NULL,
			UNIQUE KEY `service_id_availability_date` (`service_id`,`availability_date`),
			KEY `availability_date` (`availability_date`),
			KEY `service_id` (`service_id`)
		  ) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_tp_accounts() {
		$table_name = self::$wpdb->prefix . "wpcal_tp_accounts";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`admin_user_id` bigint(20) unsigned NOT NULL,
			`provider` enum('zoom_meeting','gotomeeting_meeting') NOT NULL,
			`provider_type` enum('meeting') NOT NULL,
			`status` tinyint(1) NOT NULL,
			`tp_user_id` varchar(1000) DEFAULT NULL,
			`tp_account_email` varchar(1000) NOT NULL,
			`api_token` text,
			`last_token_fetched_ts` int(10) unsigned NOT NULL DEFAULT '0',
			`last_token_fetch_attempt_ts` int(10) unsigned NOT NULL DEFAULT '0',
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `admin_user_id` (`admin_user_id`),
			KEY `provider` (`provider`),
			KEY `status` (`status`),
			KEY `last_token_fetched_ts` (`last_token_fetched_ts`),
			KEY `last_token_fetch_attempt_ts` (`last_token_fetch_attempt_ts`)
			) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function create_table_tp_resources() {
		$table_name = self::$wpdb->prefix . "wpcal_tp_resources";

		$query = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`for_type` enum('booking') NOT NULL,
			`for_id` bigint(20) NOT NULL,
			`type` enum('meeting') NOT NULL,
			`status` enum('active','cancelled','deleted') NOT NULL,
			`provider` enum('zoom_meeting','gotomeeting_meeting') NOT NULL,
			`tp_account_id` bigint(20) unsigned NOT NULL COMMENT 'local data',
			`tp_user_id` varchar(1000) DEFAULT NULL,
			`tp_account_email` varchar(1000) DEFAULT NULL,
			`tp_id` varchar(1000) NOT NULL,
			`tp_data` mediumtext,
			`added_ts` int(10) unsigned NOT NULL,
			`updated_ts` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB " . self::$collation;

		return self::do_create_table($table_name, $query);
	}

	private static function may_add_current_wp_admin_as_wpcal_admin() {
		//should be called only in install

		$table_admins = self::$wpdb->prefix . "wpcal_admins";
		if (!self::is_table_exist($table_admins)) {
			return false;
		}

		$query_count = "SELECT COUNT(*) FROM `$table_admins`";
		$is_any_admin_already_added = self::$wpdb->get_var($query_count);
		if ($is_any_admin_already_added) {
			return false;
		}

		$current_user_id = get_current_user_id();
		if (empty($current_user_id)) {
			return false;
		}

		$insert_data = [
			'admin_user_id' => $current_user_id,
			'admin_type' => 'administrator',
			'status' => '1',
			'added_ts' => time(),
			'updated_ts' => time(),
		];

		$result = self::$wpdb->insert($table_admins, $insert_data);

		if ($result !== false) {
			return true;
		}
		return false;
	}

	public static function update() {

		$old_db_version = self::$current_db_version = get_option('wpcal_db_version', '0.0');

		self::db_init();

		self::non_db_update();

		if (version_compare(self::$current_db_version, self::$db_version, '>=')) {
			return true;
		}

		if (version_compare('0.1.0', self::$current_db_version, '>')) {
			// Probably DB install never happen - so don't update
			return false;
		}

		self::update_v_0_1_0();
		self::update_v_0_9_1_0();
		self::update_v_0_9_3_0();
		self::update_v_0_9_4_0();
		self::update_v_0_9_5_0();
		self::update_v_0_9_5_2();
		self::update_v_0_9_5_3();
		self::update_v_0_9_5_4();
		self::update_v_0_9_5_5();

		self::set_force_validate();

		if ($old_db_version !== self::$current_db_version) {
			update_option('wpcal_db_version', self::$current_db_version);
		}
	}

	public static function non_db_update() {
		$wpcal_version = get_option('wpcal_version', '0.0');
		if (version_compare($wpcal_version, WPCAL_VERSION, '<')) {

			try {
				self::may_add_after_update_whats_new_notice($wpcal_version);
			} catch (WPCal_Exception $e) {
				$error = $e->getError();
				if ($error === 'skip') {
					return;
				}
			}
			update_option('wpcal_version', WPCAL_VERSION);
			return true;
		}
	}

	public static function may_add_after_update_whats_new_notice($wpcal_version) {

		if (version_compare(self::$current_db_version, '0.9.5.0', '<')) {
			throw new WPCal_Exception('skip'); //notice can be added only after 0.9.5.0dev2 version
		}

		if (version_compare($wpcal_version, '0.9.5.0', '<') && WPCAL_VERSION == '0.9.5.4') {

			//v0.9.5.0

			$title = 'What\'s new in v0.9.5?';
			$descr = '
		<ul>
			<li>Manage other admin’s event types and bookings.</li>
			<li>PHP Advance email and calendar event customization.</li>
			<li>Quick sync events using Google Calendar webhooks (Activated in batches).</li>
			<li>and a lot more...</li>
		</ul>
		<p style="font-weight: bold; margin-top: 10px;">There are important changes for existing plugin users. Please read more details about this in the blog.
		</p>
		<a class="btn" href="https://wpcal.io/whats-new-in-v0-9-5/?utm_source=wpcal_plugin&utm_medium=after_update_notice" target="_blank">Open the blog post ↗</a>';
			$notice_data = [
				'title' => $title,
				'descr' => $descr,
				'slug' => 'after_update_whats_new_0_9_5_0',
				'category' => 'after_update_whats_new',
				'type' => 'info',
				'display_in' => 'wp_admin_and_wpcal_admin',
				'display_to' => 'wpcal_admins', //if no longer wpcal admin, this notice will not display
				'display_user_ids' => WPCal_Admins::get_all_admins($_status = 1),
				'dismiss_type' => 'dismissible',
				'dismiss_by' => 'individual',
			];
			$options = ['remove_old_notice_by' => 'category_and_slug'];
			WPCal_Manage_Notices::add_notice($notice_data, $options);
		}

		self::add_notices_after_update_v_0_9_5_5($wpcal_version);

	}

	private static function set_force_validate() {
		WPCal_License::force_validate_next_instance();
	}

	private static function update_v_0_1_0() {
		$v = '0.1.0';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		self::$current_db_version = $v;
	}

	private static function update_v_0_9_1_0() {
		$v = '0.9.1.1';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		self::create_table_tp_accounts();
		self::create_table_tp_resources();

		$table_services = self::$wpdb->prefix . "wpcal_services";
		if (self::is_table_exist($table_services) && self::is_column_exist($table_services, 'location')) {
			$alter_query = "ALTER TABLE `$table_services`
			CHANGE `color` `color` varchar(100) NULL AFTER `post_id`,
			CHANGE `location` `locations` mediumtext  NOT NULL AFTER `status`";
			self::$wpdb->query($alter_query);
		}

		$table_bookings = self::$wpdb->prefix . "wpcal_bookings";
		if (self::is_table_exist($table_bookings) && !self::is_column_exist($table_bookings, 'meeting_tp_resource_id')) {
			$alter_query = "ALTER TABLE `$table_bookings`
			ADD `meeting_tp_resource_id` bigint(20) unsigned NULL AFTER `event_added_tp_event_id`";
			self::$wpdb->query($alter_query);
		}

		$table_calendar_accounts = self::$wpdb->prefix . "wpcal_calendar_accounts";
		if (self::is_table_exist($table_calendar_accounts) && !self::is_column_exist($table_calendar_accounts, 'tp_user_id')) {
			$alter_query = "ALTER TABLE `$table_calendar_accounts`
			ADD `tp_user_id` varchar(1000) NULL AFTER `status`,
			CHANGE `account_email` `account_email` varchar(1000) NOT NULL AFTER `tp_user_id`";
			self::$wpdb->query($alter_query);
		}

		$table_background_tasks = self::$wpdb->prefix . "wpcal_background_tasks";
		if (self::is_table_exist($table_background_tasks) && !self::is_column_exist($table_background_tasks, 'dependant_id')) {
			$alter_query = "ALTER TABLE `$table_background_tasks`
			CHANGE `status` `status` enum('pending','running','completed','error','retry','manual') NOT NULL DEFAULT 'pending' AFTER `task_name`,
			ADD `dependant_id` bigint(20) NULL AFTER `error_info`";
			self::$wpdb->query($alter_query);
		}

		self::update_v_0_9_1_0_change_service_locations_json();
		self::update_v_0_9_1_0_change_booking_location_json();
		self::update_v_0_9_1_0_encode_calendar_accounts_api_token();

		//need to check and do the alter. After query run check - Need to IMPROVE LATER

		self::$current_db_version = $v;
	}

	private static function update_v_0_9_1_0_change_service_locations_json() {
		$table_services = self::$wpdb->prefix . "wpcal_services";
		$query = "SELECT `id`, `locations` FROM `$table_services`";
		$rows = self::$wpdb->get_results($query);
		foreach ($rows as $row) {
			if ($row->locations) {
				$decoded = json_decode($row->locations, true);
				if ($decoded === null) {
					$location_array = ['type' => 'physical', 'form' => ['location' => '', 'location_extra' => '']];
					$location_array['form']['location'] = $row->locations;
					$locations = [$location_array];
					$encoded = json_encode($locations);
					self::$wpdb->update($table_services, ['locations' => $encoded], ['id' => $row->id]);
				}
			}
		}
	}

	private static function update_v_0_9_1_0_change_booking_location_json() {
		$table_bookings = self::$wpdb->prefix . "wpcal_bookings";
		$query = "SELECT `id`, `location` FROM `$table_bookings`";
		$rows = self::$wpdb->get_results($query);
		foreach ($rows as $row) {
			if ($row->location) {
				$decoded = json_decode($row->location, true);
				if ($decoded === null) {
					$location_array = ['type' => 'physical', 'form' => ['location' => '', 'location_extra' => '']];
					$location_array['form']['location'] = $row->location;
					$encoded = json_encode($location_array);
					self::$wpdb->update($table_bookings, ['location' => $encoded], ['id' => $row->id]);
				}
			}
		}
	}

	private static function update_v_0_9_1_0_encode_calendar_accounts_api_token() {
		$table_calendar_accounts = self::$wpdb->prefix . "wpcal_calendar_accounts";
		$query = "SELECT `id`, `api_token` FROM `$table_calendar_accounts`";
		$rows = self::$wpdb->get_results($query);
		foreach ($rows as $row) {
			$row->api_token = trim($row->api_token);
			if (!empty($row->api_token) && substr($row->api_token, 0, 1) == '{') {
				$encoded_token = wpcal_encode_token($row->api_token);
				self::$wpdb->update($table_calendar_accounts, ['api_token' => $encoded_token], ['id' => $row->id]);
			}
		}
	}

	private static function update_v_0_9_3_0() {
		$v = '0.9.3.0';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		$table_services = self::$wpdb->prefix . "wpcal_services";
		if (self::is_table_exist($table_services) && !self::is_column_exist($table_services, 'timezone')) {
			$alter_query = "ALTER TABLE `$table_services`
			ADD `timezone` varchar(100) NOT NULL AFTER `relationship_type`";
			self::$wpdb->query($alter_query);
		}

		self::update_v_0_9_3_0_set_wp_timezone_to_service_timezone();

		//need to check and do the alter. After query run check - Need to IMPROVE LATER

		if (
			self::is_table_exist($table_services) &&
			self::is_column_exist($table_services, 'timezone')
		) {
			self::$current_db_version = $v;
		}
	}

	private static function update_v_0_9_3_0_set_wp_timezone_to_service_timezone() {
		$table_services = self::$wpdb->prefix . "wpcal_services";

		if (!self::is_table_exist($table_services) || !self::is_column_exist($table_services, 'timezone')) {
			return;
		}

		$wp_timezone_str = wp_timezone()->getName();

		self::$wpdb->update($table_services, ['timezone' => $wp_timezone_str], ['timezone' => '']);
	}

	private static function update_v_0_9_4_0() {
		$v = '0.9.4.0';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		$table_background_tasks = self::$wpdb->prefix . "wpcal_background_tasks";
		if (self::is_table_exist($table_background_tasks) && self::is_column_with_type_exist($table_background_tasks, 'task_args', 'text')) {
			$alter_query = "ALTER TABLE `$table_background_tasks`
			CHANGE `status` `status` enum('pending','running','completed','error','retry','manual','cancelled') NOT NULL DEFAULT 'pending' AFTER `task_name`,
			CHANGE `task_args` `task_args` mediumtext NULL AFTER `main_arg_value`"; //'cancelled' added in ENUM

			self::$wpdb->query($alter_query);
		}

		add_option('wpcal_install_version', '0.9.3.99'); //it will not update if there is exisiting one, this is for older version before wpcal_install_version is introduced.

		if (empty(get_option('wpcal_setting_time_format'))) {
			update_option('wpcal_setting_time_format', '24hrs');
		}

		self::update_v_0_9_4_0_change_min_schedule_notice_type_time_days_before();
		self::update_v_0_9_4_0_mark_onboarding_checklist_dimissed_for_old_installs_old_admins();

		//need to check and do the alter. After query run check - Need to IMPROVE LATER

		if (
			self::is_column_with_type_exist($table_background_tasks, 'task_args', 'mediumtext ')
		) {
			self::$current_db_version = $v;
		}
	}

	private static function update_v_0_9_4_0_change_min_schedule_notice_type_time_days_before() {
		//Convert 23:59:59 1 day to 00:00:00 hrs 0 days for existing users.

		$table_services = self::$wpdb->prefix . "wpcal_services";
		$query = "SELECT `id`, `min_schedule_notice` FROM `$table_services`";
		$rows = self::$wpdb->get_results($query);
		foreach ($rows as $row) {
			if (!$row->min_schedule_notice) {
				continue;
			}
			$min_schedule_notice = json_decode($row->min_schedule_notice, true);
			if (empty($min_schedule_notice) || !is_array($min_schedule_notice)) {
				continue;
			}

			if ($min_schedule_notice['days_before_time'] == '23:59:59' && $min_schedule_notice['days_before'] > 0) {
				$min_schedule_notice['days_before_time'] = '00:00:00';
				$min_schedule_notice['days_before'] = $min_schedule_notice['days_before'] - 1;
				$encoded = json_encode($min_schedule_notice);
				self::$wpdb->update($table_services, ['min_schedule_notice' => $encoded], ['id' => $row->id]);
			}
		}
	}

	private static function update_v_0_9_4_0_mark_onboarding_checklist_dimissed_for_old_installs_old_admins() {

		$table_wp_usermeta = self::$wpdb->prefix . "usermeta";
		$query = "SELECT `umeta_id`, `meta_value` FROM `$table_wp_usermeta` WHERE `meta_key` = 'wpcal_admin_notices'";
		$rows = self::$wpdb->get_results($query);
		foreach ($rows as $row) {
			if (!$row->meta_value) {
				continue;
			}
			$meta_value = maybe_unserialize($row->meta_value);
			if (empty($meta_value) || !is_array($meta_value)) {
				continue;
			}

			if (!isset($meta_value['onboarding_checklist'])) {
				$meta_value['onboarding_checklist'] = ['status' => 'dismissed'];
				$meta_value_serialized = maybe_serialize($meta_value);
				self::$wpdb->update($table_wp_usermeta, ['meta_value' => $meta_value_serialized], ['umeta_id' => $row->umeta_id]);
			}
		}
	}

	private static function update_v_0_9_5_0() {
		$v = '0.9.5.0';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		$table_admins = self::$wpdb->prefix . "wpcal_admins";

		self::create_table_admins();
		self::create_table_notices();

		self::update_v_0_9_5_0_table_service_admins_delete_duplicates();
		self::update_v_0_9_5_0_table_service_availability_delete_duplicates();

		$table_services = self::$wpdb->prefix . "wpcal_services";
		if (self::is_table_exist($table_services) && !self::is_column_exist($table_services, 'is_manage_private')) {
			$alter_query = "ALTER TABLE `$table_services`
			ADD `is_manage_private` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `event_buffer_after`,
			ADD `invitee_notify_by` enum('calendar_invitation','email') NOT NULL DEFAULT 'calendar_invitation' AFTER `is_manage_private`";
			self::$wpdb->query($alter_query);
		}

		$table_service_admins = self::$wpdb->prefix . 'wpcal_service_admins';
		if (!self::is_index_exist($table_service_admins, 'admin_user_id_service_id')) {
			$alter_query2 = "ALTER TABLE `$table_service_admins`
			ADD UNIQUE `admin_user_id_service_id` (`admin_user_id`, `service_id`)";
			self::$wpdb->query($alter_query2);
		}

		//CHANGE `availability_dates_id` TO `availability_date_id`
		$table_service_availability = self::$wpdb->prefix . 'wpcal_service_availability';
		if (self::is_table_exist($table_service_availability) && self::is_column_exist($table_service_availability, 'availability_dates_id')) {
			$alter_query3 = "ALTER TABLE `$table_service_availability`
			CHANGE `availability_dates_id` `availability_date_id` bigint(20) NOT NULL AFTER `service_id`";
			self::$wpdb->query($alter_query3);
		}

		//DROP INDEX `availability_dates_id`, ADD INDEX `availability_date_id`
		if (self::is_index_exist($table_service_availability, 'availability_dates_id')) {
			$alter_query4 = "ALTER TABLE `$table_service_availability`
			ADD INDEX `availability_date_id` (`availability_date_id`),
			DROP INDEX `availability_dates_id`";
			self::$wpdb->query($alter_query4);
		}

		if (!self::is_index_exist($table_service_availability, 'service_id_availability_date_id')) {
			$alter_query5 = "ALTER TABLE `$table_service_availability`
			ADD UNIQUE `service_id_availability_date_id` (`service_id`, `availability_date_id`)";
			self::$wpdb->query($alter_query5);
		}

		//ADD `events_webhook_*` fields TO `calendars`
		$table_calendars = self::$wpdb->prefix . 'wpcal_calendars';
		if (self::is_table_exist($table_calendars) && !self::is_column_exist($table_calendars, 'events_webhook_channel_id')) {
			$alter_query6 = "ALTER TABLE `$table_calendars`
			ADD `list_events_sync_status_update_ts` int(10) unsigned NULL AFTER `list_events_sync_status`,
			ADD `events_webhook_channel_id` varchar(256) NULL AFTER `list_events_sync_last_update_ts`,
			ADD `events_webhook_resource_id` varchar(256) NULL AFTER `events_webhook_channel_id`,
			ADD `events_webhook_expiry_ts` int(10) unsigned NULL AFTER `events_webhook_resource_id`,
			ADD `events_webhook_not_supported` tinyint(1) unsigned NULL AFTER `events_webhook_expiry_ts`,
			ADD `events_webhook_updated_ts` int(10) unsigned NULL AFTER `events_webhook_not_supported`,
			ADD `events_webhook_last_received_ts` int(10) unsigned NULL AFTER `events_webhook_updated_ts`";
			self::$wpdb->query($alter_query6);
		}

		if (self::is_table_exist($table_calendars) && self::is_column_exist($table_calendars, 'events_webhook_resource_id') && !self::is_index_exist($table_calendars, 'events_webhook_resource_id')) {
			$alter_query7 = "ALTER TABLE `$table_calendars`
			ADD INDEX `events_webhook_resource_id` (`events_webhook_resource_id`)";
			self::$wpdb->query($alter_query7);
		}

		$table_bookings = self::$wpdb->prefix . 'wpcal_bookings';
		if (self::is_table_exist($table_bookings) && !self::is_column_exist($table_bookings, 'event_added_calendar_provider')) {
			$alter_query8 = "ALTER TABLE `$table_bookings`
				ADD `event_added_calendar_provider` enum('google_calendar') NULL AFTER `page_used_for_booking`";
			self::$wpdb->query($alter_query8);
		}

		//need to check and do the alter. After query run check - Need to IMPROVE LATER

		self::update_v_0_9_5_0_mark_old_services_column_is_manage_private_as_true();
		self::update_v_0_9_5_0_add_exisiting_service_admins_as_wpcal_admins();

		self::update_v_0_9_5_0_table_bookings_update_event_added_calendar_provider();

		if (
			self::is_table_exist($table_admins) &&
			self::is_column_exist($table_services, 'is_manage_private') &&
			self::is_column_exist($table_service_availability, 'availability_date_id') &&
			self::is_index_exist($table_service_admins, 'admin_user_id_service_id') &&
			self::is_index_exist($table_service_availability, 'service_id_availability_date_id') &&
			self::is_column_exist($table_calendars, 'events_webhook_channel_id')
			&&
			self::is_index_exist($table_calendars, 'events_webhook_resource_id') &&
			self::is_column_exist($table_bookings, 'event_added_calendar_provider')
		) {
			self::$current_db_version = $v;
		}
	}

	private static function update_v_0_9_5_0_mark_old_services_column_is_manage_private_as_true() {
		$table_services = self::$wpdb->prefix . "wpcal_services";

		if (!self::is_column_exist($table_services, 'is_manage_private')) {
			return;
		}

		$query_count = "SELECT COUNT(*)  FROM `$table_services` WHERE `is_manage_private` = '1'";
		$private_count = self::$wpdb->get_var($query_count);

		if ($private_count) {
			//already some events are marked as private
			return;
		}

		$query = "UPDATE `$table_services` SET `is_manage_private` = '1' WHERE 1 = 1";
		$result = self::$wpdb->query($query);
		return $result;
	}

	private static function update_v_0_9_5_0_add_exisiting_service_admins_as_wpcal_admins() {

		$table_admins = self::$wpdb->prefix . "wpcal_admins";
		if (!self::is_table_exist($table_admins)) {
			return;
		}

		$query_count = "SELECT COUNT(*)  FROM `$table_admins`";
		$is_any_admin_already_added = self::$wpdb->get_var($query_count);
		if ($is_any_admin_already_added) {
			return;
		}

		$table_service_admins = self::$wpdb->prefix . 'wpcal_service_admins';
		$query = "SELECT `admin_user_id`  FROM `$table_service_admins` GROUP BY `admin_user_id`"; //lets select all admins lets not check anything else now

		$admin_user_ids = self::$wpdb->get_col($query);
		if (empty($admin_user_ids)) {
			return;
		}

		//make sure those admin's still present in WP
		$table_wp_users = self::$wpdb->prefix . 'users';
		$query2 = "SELECT `ID`  FROM `$table_wp_users` WHERE `ID` IN (" . implode(',', $admin_user_ids) . ")";

		$final_admin_user_ids = self::$wpdb->get_col($query2);
		if (empty($final_admin_user_ids)) {
			return;
		}

		$default_data = [
			'admin_type' => 'administrator',
			'status' => '1',
			'added_ts' => time(),
			'updated_ts' => time(),
		];

		foreach ($final_admin_user_ids as $admin_user_id) {
			$admin_data = $default_data;
			$admin_data['admin_user_id'] = $admin_user_id;
			self::$wpdb->insert($table_admins, $admin_data);
		}
	}

	private static function update_v_0_9_5_0_table_service_admins_delete_duplicates() {

		$table_service_admins = self::$wpdb->prefix . 'wpcal_service_admins';

		$query = "DELETE `service_admin2` FROM `$table_service_admins` as `service_admin`
			INNER  JOIN `$table_service_admins` as `service_admin2`
			WHERE
				`service_admin`.`id` < `service_admin2`.`id` AND
				`service_admin`.`admin_user_id` = `service_admin2`.`admin_user_id` AND
				`service_admin`.`service_id` = `service_admin2`.`service_id`";
		self::$wpdb->query($query);
	}

	private static function update_v_0_9_5_0_table_service_availability_delete_duplicates() {

		$table_service_availability = self::$wpdb->prefix . 'wpcal_service_availability';

		if (self::is_table_exist($table_service_availability) && self::is_column_exist($table_service_availability, 'availability_dates_id')) {
			//run before CHANGE `availability_dates_id` TO `availability_date_id`

			$query = "DELETE `service_availability2` FROM `$table_service_availability` as `service_availability`
				INNER  JOIN `$table_service_availability` as `service_availability2`
				WHERE
					`service_availability`.`id` < `service_availability2`.`id` AND
					`service_availability`.`availability_dates_id` = `service_availability2`.`availability_dates_id` AND
					`service_availability`.`service_id` = `service_availability2`.`service_id`";
			self::$wpdb->query($query);
		}
	}

	private static function update_v_0_9_5_0_table_bookings_update_event_added_calendar_provider() {

		$table_bookings = self::$wpdb->prefix . 'wpcal_bookings';

		if (self::is_table_exist($table_bookings) && self::is_column_exist($table_bookings, 'event_added_calendar_provider')) {
			//run after ALTER TABLE `$table_calendars` ADD `event_added_calendar_provider`

			//currently google_calendar is only calendar integration available

			$query = "UPDATE `$table_bookings` SET `event_added_calendar_provider` = 'google_calendar' WHERE `event_added_calendar_id` != '' AND `event_added_tp_cal_id` != '' AND `event_added_calendar_provider` IS NULL";
			self::$wpdb->query($query);
		}
	}

	private static function update_v_0_9_5_2() {
		$v = '0.9.5.2';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		self::update_v_0_9_5_2_set_expiry_to_certain_old_background_task();
		self::update_v_0_9_5_2_if_no_active_wpcal_admin_may_add_current_admin();

		self::$current_db_version = $v;
	}

	private static function update_v_0_9_5_2_set_expiry_to_certain_old_background_task() {
		$table_background_tasks = self::$wpdb->prefix . "wpcal_background_tasks";
		$table_bookings = self::$wpdb->prefix . "wpcal_bookings";

		// update all the direct booking related background tasks
		$query1 = "UPDATE `$table_background_tasks` `bg_tasks` JOIN `$table_bookings` `bookings`
		ON `bg_tasks`.`main_arg_value` = `bookings`.`id` AND `bg_tasks`.`main_arg_name` = 'booking_id'
		SET `bg_tasks`.`expiry_ts` = `bookings`.`booking_to_time` + " . WPCAL_ADD_BOOKING_BG_TASK_RELATIVE_EXPIRY . "
		WHERE `bg_tasks`.`expiry_ts` IS NULL AND `bg_tasks`.`status` IN('pending', 'running', 'retry')";
		self::$wpdb->query($query1);

		// update 'send_mail' main_arg_name = 'api_error_need_action_google_calendar'
		$query2 = "UPDATE `$table_background_tasks` `bg_tasks`
		SET `bg_tasks`.`expiry_ts` = `bg_tasks`.`added_ts` + " . WPCAL_ADMIN_ACTION_REQUIRED_MAIL_RELATIVE_EXPIRY . "
		WHERE `bg_tasks`.`expiry_ts` IS NULL AND `bg_tasks`.`status` IN('pending', 'running', 'retry')";
		self::$wpdb->query($query2);

		// To cover all the remaining just in case
		$expiry_ts = time() + 86400;
		$query3 = "UPDATE `$table_background_tasks` `bg_tasks`
		SET `bg_tasks`.`expiry_ts` = '" . $expiry_ts . "'
		WHERE `bg_tasks`.`expiry_ts` IS NULL AND `bg_tasks`.`status` IN('pending', 'running', 'retry')";
		self::$wpdb->query($query3);
	}

	private static function update_v_0_9_5_2_if_no_active_wpcal_admin_may_add_current_admin() {
		WPCal_Admins::may_add_current_user_as_wpcal_admin_while_no_active_wpcal_admins();
	}

	private static function update_v_0_9_5_3() {
		$v = '0.9.5.3';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		add_option('wpcal_last_validate_attempt', 0); //it will not update if there is exisiting one

		$table_calendars = self::$wpdb->prefix . "wpcal_calendars";
		if (!self::is_table_exist($table_calendars)) { //if default DB ROW_FORMAT is COMPACT then this table is not created for certain sites
			self::create_table_calendars();
		}

		if (
			self::is_table_exist($table_calendars) &&
			self::is_index_exist($table_calendars, 'events_webhook_resource_id') &&
			!self::is_index_exist_with_limit($table_calendars, 'events_webhook_resource_id', self::$max_index_length)
		) {
			$alter_query = "ALTER TABLE `$table_calendars`
				DROP INDEX `events_webhook_resource_id`,
				ADD INDEX `events_webhook_resource_id` (`events_webhook_resource_id`(" . self::$max_index_length . "))";
			self::$wpdb->query($alter_query);
		}

		self::update_v_0_9_5_3_schedule_generate_missing_thumnails();

		self::$current_db_version = $v;
	}

	private static function update_v_0_9_5_3_schedule_generate_missing_thumnails() {
		$admin_user_ids = WPCal_Admins::get_all_admins();

		if (empty($admin_user_ids)) {
			return;
		}

		$admin_user_ids_imploded = wpcal_implode_for_sql($admin_user_ids);

		$table_wp_usermeta = self::$wpdb->prefix . "usermeta";
		$query = "SELECT `meta_value` FROM `$table_wp_usermeta` WHERE `user_id` IN($admin_user_ids_imploded) AND `meta_key` = 'wpcal_admin_profile_settings' ";
		$user_metas = self::$wpdb->get_col($query);

		$expiry_ts = time() + (10 * 86400);

		foreach ($user_metas as $_user_meta) {
			$user_meta = maybe_unserialize($_user_meta);
			if (!empty($user_meta['avatar_attachment_id'])) {
				//schedule, its ok not check required image present or not, it will taken care
				$task_details = [
					'task_name' => 'generate_missing_thumnails',
					'main_arg_name' => 'attachment_id', // attachment_id is nothing but a post_id
					'main_arg_value' => $user_meta['avatar_attachment_id'],
					'expiry_ts' => $expiry_ts,
				];
				$added_task_id = WPCal_Background_Tasks::add_task($task_details);
			}
		}
	}

	private static function update_v_0_9_5_4() {
		$v = '0.9.5.4';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		#change-1
		$table_admins = self::$wpdb->prefix . "wpcal_admins";
		$delete_admin_with_zero_id_result = self::$wpdb->delete($table_admins, ['admin_user_id' => 0]);

		#change-2
		$table_tp_accounts = self::$wpdb->prefix . "wpcal_tp_accounts";
		if (!self::is_column_exist($table_tp_accounts, 'last_token_fetched_ts')) {
			$alter_query1 = "ALTER TABLE `$table_tp_accounts`
			ADD `last_token_fetched_ts` int unsigned NOT NULL DEFAULT '0' AFTER `api_token`,
			ADD `last_token_fetch_attempt_ts` int unsigned NOT NULL DEFAULT '0' AFTER `last_token_fetched_ts`,
			ADD INDEX `last_token_fetched_ts` (`last_token_fetched_ts`),
			ADD INDEX `last_token_fetch_attempt_ts` (`last_token_fetch_attempt_ts`)";
			self::$wpdb->query($alter_query1);
		}

		#change-3
		if (self::is_column_exist($table_tp_accounts, 'last_token_fetched_ts')) {
			$update_query1 = "UPDATE `$table_tp_accounts` SET `last_token_fetched_ts` = `added_ts` WHERE `last_token_fetched_ts` = 0";
			self::$wpdb->query($update_query1);
		}

		#change-4
		$table_calendar_accounts = self::$wpdb->prefix . "wpcal_calendar_accounts";
		if (!self::is_column_exist($table_calendar_accounts, 'last_token_fetched_ts')) {
			$alter_query2 = "ALTER TABLE `$table_calendar_accounts`
			ADD `last_token_fetched_ts` int unsigned NOT NULL DEFAULT '0' AFTER `api_token`,
			ADD `last_token_fetch_attempt_ts` int unsigned NOT NULL DEFAULT '0' AFTER `last_token_fetched_ts`,
			ADD INDEX `last_token_fetched_ts` (`last_token_fetched_ts`),
			ADD INDEX `last_token_fetch_attempt_ts` (`last_token_fetch_attempt_ts`)";
			self::$wpdb->query($alter_query2);
		}

		#change-5
		if (self::is_column_exist($table_calendar_accounts, 'last_token_fetched_ts')) {
			$update_query2 = "UPDATE `$table_calendar_accounts` SET `last_token_fetched_ts` = IF(IFNULL(`list_calendars_sync_last_update_ts`, 0) > `added_ts`, IFNULL(`list_calendars_sync_last_update_ts`, 0), `added_ts`) WHERE `last_token_fetched_ts` = 0";
			self::$wpdb->query($update_query2);
		}

		if (
			self::is_column_exist($table_tp_accounts, 'last_token_fetched_ts') &&
			self::is_column_exist($table_calendar_accounts, 'last_token_fetched_ts')
		) {
			self::$current_db_version = $v;
		}
	}

	private static function update_v_0_9_5_5() {
		$v = '0.9.5.5';
		if (version_compare(self::$current_db_version, $v, '>=')) {
			return;
		}

		#change-1
		$table_calendar_events = self::$wpdb->prefix . "wpcal_calendar_events";
		if (!self::is_column_exist($table_calendar_events, 'tp_summary')) {
			$alter_query1 = "ALTER TABLE `$table_calendar_events`
			ADD `is_wpcal_event` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `tp_event_id`,
			ADD `tp_summary` varchar(1000) NULL AFTER `is_wpcal_event`,
			ADD `tp_created` int(10) unsigned NULL AFTER `to_time`,
			ADD `tp_updated` int(10) unsigned NULL AFTER `tp_created`,
			ADD `tp_event_status` varchar(255) NULL AFTER `tp_updated`,
			ADD `tp_self_attendee_status` varchar(255) NULL AFTER `tp_event_status`,
			ADD `tp_is_busy` tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER `tp_self_attendee_status`,
			ADD `is_consider_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER `tp_is_busy`,
			ADD `tp_event_link` varchar(1000) NULL AFTER `is_consider_confirmed`,
			ADD INDEX `is_wpcal_event` (`is_wpcal_event`),
			ADD INDEX `tp_is_busy` (`tp_is_busy`),
			ADD INDEX `is_consider_confirmed` (`is_consider_confirmed`)";
			self::$wpdb->query($alter_query1);
		}

		#change-2
		$table_calendars = self::$wpdb->prefix . "wpcal_calendars";
		if (!self::is_column_exist($table_calendars, 'do_fresh_sync')) {
			$alter_query2 = "ALTER TABLE `$table_calendars`
			ADD `do_fresh_sync` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `events_webhook_last_received_ts`";
			self::$wpdb->query($alter_query2);
		}

		#change-3 one-off
		self::$wpdb->update($table_calendars, ['do_fresh_sync' => '1'], ['is_conflict_calendar' => '1']);

		#change-4 one-off
		self::v_0_9_5_5_enable_cal_conflict_free_as_busy_for_all_wpcal_admins();

		if (
			self::is_column_exist($table_calendar_events, 'tp_summary') &&
			self::is_column_exist($table_calendars, 'do_fresh_sync')
		) {
			self::$current_db_version = $v;
		}
	}

	private static function add_notices_after_update_v_0_9_5_5($wpcal_db_version) {
		self::may_add_notice_for_changes_n_days_service_settings_v_0_9_5_5($wpcal_db_version);
	}

	private static function may_add_notice_for_changes_n_days_service_settings_v_0_9_5_5($wpcal_db_version) {
		if (version_compare($wpcal_db_version, '0.9.5.5', '<') && version_compare(WPCAL_VERSION, '0.9.5.5', '>=')) {
			//v0.9.5.5

			$table_services = self::$wpdb->prefix . "wpcal_services";
			$table_availability_dates = self::$wpdb->prefix . "wpcal_availability_dates";
			$table_service_availability = self::$wpdb->prefix . "wpcal_service_availability";

			$query = "SELECT count(*) FROM `$table_services` `service`
				JOIN `$table_service_availability` `service_avail` ON `service`.`id` = `service_avail`.`service_id`
				JOIN `$table_availability_dates` `service_avail_dates` ON `service_avail`.`availability_date_id` = `service_avail_dates`.`id`
				WHERE `service`.`status` > -2 AND `service_avail_dates`.`type` = 'default' AND `service_avail_dates`.`date_range_type` = 'relative'
				AND `service_avail_dates`.`date_misc` LIKE '+%D'";
			$relative_services = self::$wpdb->get_var($query);

			if (empty($relative_services)) {
				return false; // no relative services so no need this notification.
			}

			$title = 'Important changes - N days rolling into future setting';
			$descr = '
			<p style="margin-top: 10px;">Changes to the Event type N days rolling into future in the Event Type Duration & Timings settings. In certain cases it was not including the current day. Now we have fixed it.<br>
			Say if it 5 days into future, the 5 days are including the current day.<br>
			Please check all your <a href="' . admin_url('admin.php?page=wpcal_admin#/event-types') . '">event types</a> where you have set N days rolling into future in Event Type Duration & Timings setting and make changes if necessary.
			</p>';
			$notice_data = [
				'title' => $title,
				'descr' => $descr,
				'slug' => 'after_update_imp_notice_0_9_5_5',
				'category' => 'after_update_imp_notice',
				'type' => 'info',
				'display_in' => 'wp_admin_and_wpcal_admin',
				'display_to' => 'wpcal_admins', //if no longer wpcal admin, this notice will not display
				'display_user_ids' => WPCal_Admins::get_all_admins($_status = 1),
				'dismiss_type' => 'dismissible',
				'dismiss_by' => 'individual',
			];
			$options = ['remove_old_notice_by' => 'category_and_slug'];
			WPCal_Manage_Notices::add_notice($notice_data, $options);
		}
	}

	private static function v_0_9_5_5_enable_cal_conflict_free_as_busy_for_all_wpcal_admins() { // exists admins both active and disabled.
		$admins = WPCal_Admins::get_all_admins();
		foreach ($admins as $admin_user_id) {
			$admin_user = get_user_by('id', $admin_user_id);
			if (!$admin_user) {
				//  WPCal Admin is deleted in WP Users
				continue;
			}

			$admin_setting_obj = new WPCal_Admin_Settings($admin_user_id);
			$admin_setting_obj->update_all(['cal_conflict_free_as_busy' => '1']);
		}
	}

	//---------------------------------------------------->

	private static function do_create_table($table_name, $query) {
		self::$wpdb->query($query);

		// $last_db_error = $GLOBALS['wpdb']->last_error;

		// if(!is_table_exist($table_name)){
		// 	$query_error = get_error_msg('create_table_error').' Table:('.$table_name.')';
		// 		if($last_db_error){
		// 			$query_error = get_error_msg('create_table_error').' Error:('.$last_db_error.') Table:('.$table_name.')';
		// 		}
		// 		throw new Exception('create_table_error', $query_error);
		// }
	}

	private static function do_query($query) {
		return self::$wpdb->query($query);

		// $last_db_error = $GLOBALS['wpdb']->last_error;
	}

	public static function is_table_exist($table) {
		if (empty(self::$wpdb)) {
			self::db_init();
		}
		$escaped_table_name = self::esc_table_name($table);
		if (self::$wpdb->get_var("SHOW TABLES LIKE '$escaped_table_name'") == $table) {
			return true;
		}
		return false;
	}

	private static function is_column_exist($table, $column) {
		$db_name = DB_NAME;
		$query = "SELECT *
		FROM information_schema.COLUMNS
		WHERE
			TABLE_SCHEMA = '$db_name' AND
			TABLE_NAME = %s AND
			COLUMN_NAME = %s";
		$query = $GLOBALS['wpdb']->prepare($query, $table, $column);
		$column_exists = $GLOBALS['wpdb']->get_results($query);

		return !empty($column_exists);
	}

	private static function is_column_with_type_exist($table, $column, $type) {
		$db_name = DB_NAME;
		$query = "SELECT *
		FROM information_schema.COLUMNS
		WHERE
			TABLE_SCHEMA = '$db_name' AND
			TABLE_NAME = %s AND
			COLUMN_NAME = %s AND
			DATA_TYPE = %s";
		$query = $GLOBALS['wpdb']->prepare($query, $table, $column, $type);
		$column_exists = $GLOBALS['wpdb']->get_results($query);

		return !empty($column_exists);
	}

	private static function is_index_exist($table, $index) {
		$db_name = DB_NAME;
		$query = "SELECT *
		FROM information_schema.STATISTICS
		WHERE
			TABLE_SCHEMA = '$db_name' AND
			TABLE_NAME = %s AND
			INDEX_NAME = %s";

		$query = $GLOBALS['wpdb']->prepare($query, $table, $index);
		$column_exists = $GLOBALS['wpdb']->get_results($query);

		return !empty($column_exists);
	}

	private static function is_index_exist_with_limit($table, $index, $limit) {
		$db_name = DB_NAME;
		$query = "SELECT *
		FROM information_schema.STATISTICS
		WHERE
			TABLE_SCHEMA = '$db_name' AND
			TABLE_NAME = %s AND
			INDEX_NAME = %s AND
			SUB_PART = %d";

		$query = $GLOBALS['wpdb']->prepare($query, $table, $index, $limit);
		$column_exists = $GLOBALS['wpdb']->get_results($query);

		return !empty($column_exists);
	}

	private static function esc_table_name($table) {
		$tmp_replacer = '||**^**||';
		$search = array('\\_', '_', $tmp_replacer);
		$replace = array($tmp_replacer, '\\_', '\\_');
		return str_replace($search, $replace, $table); //do left to right if already escapsed string comes in, i am trying to maintain that with left to right replacement of str_replace with $tmp_replacer //why escaping the (_) because it is single character whild card in mysql
	}

	private static function get_collation() {
		global $wpdb;
		if (method_exists($wpdb, 'get_charset_collate')) {
			$charset_collate = $wpdb->get_charset_collate();
		}

		return !empty($charset_collate) ? $charset_collate : ' DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci ';
	}
}
