<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BWFAN_DB
 * @package Autonami
 * @author XlPlugins
 */
#[AllowDynamicProperties]
class BWFAN_DB {
	private static $ins = null;

	protected $tables_created = false;
	protected $method_run = [];

	/**
	 * BWFAN_DB constructor.
	 */
	public function __construct() {
		global $wpdb;
		$wpdb->bwfan_abandonedcarts      = $wpdb->prefix . 'bwfan_abandonedcarts';
		$wpdb->bwfan_automations         = $wpdb->prefix . 'bwfan_automations';
		$wpdb->bwfan_automationmeta      = $wpdb->prefix . 'bwfan_automationmeta';
		$wpdb->bwfan_tasks               = $wpdb->prefix . 'bwfan_tasks';
		$wpdb->bwfan_taskmeta            = $wpdb->prefix . 'bwfan_taskmeta';
		$wpdb->bwfan_task_claim          = $wpdb->prefix . 'bwfan_task_claim';
		$wpdb->bwfan_logs                = $wpdb->prefix . 'bwfan_logs';
		$wpdb->bwfan_logmeta             = $wpdb->prefix . 'bwfan_logmeta';
		$wpdb->bwfan_message_unsubscribe = $wpdb->prefix . 'bwfan_message_unsubscribe';
		$wpdb->bwfan_contact_automations = $wpdb->prefix . 'bwfan_contact_automations';

		/** v2 */
		$wpdb->bwfan_automation_contact          = $wpdb->prefix . 'bwfan_automation_contact';
		$wpdb->bwfan_automation_contact_claim    = $wpdb->prefix . 'bwfan_automation_contact_claim';
		$wpdb->bwfan_automation_contact_trail    = $wpdb->prefix . 'bwfan_automation_contact_trail';
		$wpdb->bwfan_automation_complete_contact = $wpdb->prefix . 'bwfan_automation_complete_contact';
		$wpdb->bwfan_automation_step             = $wpdb->prefix . 'bwfan_automation_step';

		add_action( 'plugins_loaded', [ $this, 'load_db_classes' ], 8 );

		add_action( 'admin_init', [ $this, 'version_1_0_0' ], 10 );
		add_action( 'admin_init', [ $this, 'db_update' ], 11 );
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_DB
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Include all the DB Table files
	 */
	public static function load_db_classes() {
		self::load_class_files( __DIR__ . '/db' );
	}

	public static function load_class_files( $dir ) {
		foreach ( glob( $dir . '/class-*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );
			if ( isset( $file_data['basename'] ) && 'index.php' === $file_data['basename'] ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}

	/**
	 * loading table related classes
	 *
	 * @return void
	 */
	public static function load_table_classes() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$dir = __DIR__ . '/db/tables';
		/** Load base class of verify tables */
		include_once( $dir . "/bwfan-db-tables-base.php" );

		self::load_class_files( $dir );
	}

	/**
	 * Version 1.0 update
	 */
	public function version_1_0_0() {
		if ( false !== get_option( 'bwfan_ver_1_0', false ) ) {
			return;
		}

		self::load_table_classes();

		$table_instance = new BWFAN_DB_Table_Options();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automations();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automationmeta();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Message_Unsubscribe();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_AbandonedCarts();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$this->tables_created = true;

		$this->method_run[] = '1.0.0';

		do_action( 'bwfan_db_1_0_tables_created' );

		update_option( 'bwfan_ver_1_0', date( 'Y-m-d' ), true );

		/** Unique key to share in rest calls */
		$unique_key = md5( time() );
		update_option( 'bwfan_u_key', $unique_key, true );

		/** Update v1 automation status */
		update_option( 'bwfan_automation_v1', '0', true );

		/** Scheduling actions one-time */
		$this->schedule_actions();

		/** Auto global settings */
		if ( BWFAN_Plugin_Dependency::woocommerce_active_check() ) {
			$global_option = get_option( 'bwfan_global_settings', array() );

			$global_option['bwfan_ab_enable'] = true;
			update_option( 'bwfan_global_settings', $global_option, true );
		}

		/** Cache handling */
		if ( class_exists( 'BWF_JSON_Cache' ) && method_exists( 'BWF_JSON_Cache', 'run_json_endpoints_cache_handling' ) ) {
			BWF_JSON_Cache::run_json_endpoints_cache_handling();
		}

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	protected function schedule_actions() {
		$ins = BWFAN_Admin::get_instance();
		$ins->maybe_set_as_ct_worker();
		$ins->schedule_abandoned_cart_cron();
	}

	/**
	 * Create v1 automation related tables
	 *
	 * @return void
	 */
	public function db_create_v1_automation_tables() {
		self::load_table_classes();

		$table_instance = new BWFAN_DB_Table_Tasks();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Taskmeta();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Task_Claim();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Logs();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Logmeta();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Contact_Automations();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		/** Update v1 automation status */
		update_option( 'bwfan_automation_v1', '1', true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * Perform DB updates or once occurring updates
	 */
	public function db_update() {
		$db_changes = array(
			'2.0.10.1' => '2_0_10_1',
			'2.0.10.2' => '2_0_10_2',
			'2.0.10.3' => '2_0_10_3',
			'2.0.10.4' => '2_0_10_4',
			'2.0.10.5' => '2_0_10_5',
			'2.0.10.6' => '2_0_10_6',
			'2.0.10.7' => '2_0_10_7',
			'2.0.10.8' => '2_0_10_8',
			'2.4.2'    => '2_4_2',
			'2.4.4'    => '2_4_4',
			'2.6.1'    => '2_6_1',
			'2.6.2'    => '2_6_2',
			'2.6.3'    => '2_6_3',
			'2.7.0'    => '2_7_0',
			'2.8.0'    => '2_8_0',
		);
		$db_version = get_option( 'bwfan_db', '2.0' );

		foreach ( $db_changes as $version_key => $version_value ) {
			if ( version_compare( $db_version, $version_key, '<' ) ) {
				self::load_table_classes();

				$function_name = 'db_update_' . $version_value;
				$this->$function_name( $version_key );
			}
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_1( $version_key ) {
		global $wpdb;
		$db_errors = [];

		if ( ! is_array( $this->method_run ) || ! in_array( '1.0.0', $this->method_run, true ) ) {
			$column_exists = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->prefix}bwfan_automations LIKE 'start'" );
			if ( empty( $column_exists ) ) {
				/** Add new columns in bwfan_automations table */
				$query = "ALTER TABLE {$wpdb->prefix}bwfan_automations
				ADD COLUMN `start` bigint(10) UNSIGNED NOT NULL,
				ADD COLUMN `v` tinyint(1) UNSIGNED NOT NULL default 1,
				ADD COLUMN `benchmark` varchar(150) NOT NULL,
				ADD COLUMN `title` varchar(255) NULL;";
				$wpdb->query( $query );
				if ( ! empty( $wpdb->last_error ) ) {
					$db_errors[] = 'bwfan_automations alter table - ' . $wpdb->last_error;
				}
			}
		}

		$table_instance = new BWFAN_DB_Table_Automation_Step();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automation_Contact();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automation_Complete_Contact();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automation_Contact_Claim();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automation_Contact_Trail();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_2( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$db_errors = [];

		/** Check if 'next' column exists before attempting to drop it */
		$column_exists = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->prefix}bwfan_automation_contact LIKE 'next'" );
		if ( $column_exists ) {
			/** Drop next column */
			$drop_col = "ALTER TABLE {$wpdb->prefix}bwfan_automation_contact DROP COLUMN `next`";
			$wpdb->query( $drop_col );
			if ( ! empty( $wpdb->last_error ) ) {
				$db_errors[] = 'bwfan_automation_contact drop call - ' . $wpdb->last_error;
			}
		}

		$table_instance = new BWFAN_DB_Table_Automation_Contact();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$table_instance = new BWFAN_DB_Table_Automation_Complete_Contact();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_3( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		$db_errors = [];

		if ( ! is_array( $this->method_run ) || ! in_array( '2.0.10.2', $this->method_run, true ) ) {
			/** Alter bwfan_automation_complete_contact table */
			$column_exists = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->prefix}bwfan_automation_complete_contact LIKE 'event'" );
			if ( empty( $column_exists ) ) {
				$query = "ALTER TABLE {$wpdb->prefix}bwfan_automation_complete_contact
    			CHANGE `trail` `trail` VARCHAR(40) NULL COMMENT 'Trail ID',
		    	ADD COLUMN `event` varchar(120) NOT NULL;";
				$wpdb->query( $query );
				if ( ! empty( $wpdb->last_error ) ) {
					$db_errors[] = 'bwfan_automation_complete_contact alter table - ' . $wpdb->last_error;
				}
			}

			/** Alter bwfan_automation_contact table */
			$column_exists = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->prefix}bwfan_automation_contact LIKE 'last_time'" );
			if ( empty( $column_exists ) ) {
				$query = "ALTER TABLE {$wpdb->prefix}bwfan_automation_contact
    			CHANGE `trail` `trail` VARCHAR(40) NULL COMMENT 'Trail ID',
		    	ADD COLUMN `last_time` bigint(12) UNSIGNED NOT NULL;";
				$wpdb->query( $query );
				if ( ! empty( $wpdb->last_error ) ) {
					$db_errors[] = 'bwfan_automation_contact alter table - ' . $wpdb->last_error;
				}
			}
		}

		$table_instance = new BWFAN_DB_Table_Automation_Contact_Trail();
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_4( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/** Marking option key autoload false */
		$global_option             = get_option( 'bwfan_global_settings', array() );
		$global_option['2_0_10_4'] = 1;
		update_option( 'bwfan_global_settings', $global_option, true );

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_5( $version_key ) {
		if ( ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) || ! class_exists( 'BWFCRM_Contact' ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		/** Automation complete contact */
		$query = "SELECT MAX(`ID`) FROM `{$wpdb->prefix}bwfan_automation_complete_contact`";

		$max_completed_aid = $wpdb->get_var( $query );
		if ( intval( $max_completed_aid ) > 0 ) {
			update_option( 'bwfan_max_automation_completed', $max_completed_aid );
			if ( ! bwf_has_action_scheduled( 'bwfan_store_automation_completed_ids' ) ) {
				bwf_schedule_recurring_action( time() + 60, 120, 'bwfan_store_automation_completed_ids' );
			}
		}

		/** Automation contact */
		$query = "SELECT MAX(`ID`) FROM `{$wpdb->prefix}bwfan_automation_contact`";

		$max_active_aid = $wpdb->get_var( $query );
		if ( intval( $max_active_aid ) > 0 ) {
			update_option( 'bwfan_max_active_automation', $max_active_aid );
			if ( ! bwf_has_action_scheduled( 'bwfan_store_automation_active_ids' ) ) {
				bwf_schedule_recurring_action( time(), 120, 'bwfan_store_automation_active_ids' );
			}
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_6( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$db_errors = [];

		/** Automation contact */
		$query = $wpdb->prepare( "SELECT MIN(`ID`) FROM `{$wpdb->prefix}bwfan_automations` WHERE `v` = %d", 1 );

		$automation_v1 = $wpdb->get_var( $query );
		$automation_v1 = ( 0 === intval( $automation_v1 ) ) ? '0' : '1';
		update_option( 'bwfan_automation_v1', $automation_v1, true );

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_7( $version_key ) {
		BWFAN_Recipe_Loader::get_recipes_array( true );

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_8( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/**
		 * Check if table exists and no column is missing
		 */
		$table_instance = new BWFAN_DB_Table_Message_Unsubscribe();
		if ( $table_instance->is_exists() ) {
			$missing_columns = $table_instance->check_missing_columns();
			if ( empty( $missing_columns ) ) {
				update_option( 'bwfan_db', $version_key, true );
				$this->method_run[] = $version_key;

				return;
			}
		}

		/** Create missing columns in a table */

		$db_errors = [];

		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	public function db_update_2_4_2( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		/** Automation steps meta data normalize */
		$query  = "SELECT MIN(`ID`) as `ID` FROM `{$wpdb->prefix}bwfan_automations` WHERE `v` = 2 LIMIT 0, 1";
		$min_id = $wpdb->get_var( $query ); // phpcs:disable WordPress.DB.PreparedSQL
		if ( $min_id > 0 ) {
			/** schedule recurring event */
			bwf_schedule_recurring_action( time(), ( 5 * MINUTE_IN_SECONDS ), 'bwfan_update_meta_automations_v2' );

			update_option( 'bwfan_automation_v2_meta_normalize', $min_id, false );
		}

		/** Delete some repetitive actions to delete duplicated actions */
		$query  = "SELECT count(*) AS `count` FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ('bwfan_run_midnight_cron', 'bwfan_5_minute_worker', 'bwfan_run_midnight_connectors_sync')";
		$result = $wpdb->get_var( $query ); // phpcs:disable WordPress.DB.PreparedSQL
		if ( ! empty( $result ) ) {
			/** Delete the rows */
			$query = "DELETE FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ('bwfan_run_midnight_cron', 'bwfan_5_minute_worker', 'bwfan_run_midnight_connectors_sync')";
			$wpdb->query( $query );
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	public function db_update_2_4_4( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		$table_instance = new BWFAN_DB_Table_Options();
		if ( $table_instance->is_exists() ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		$db_errors = [];

		/** Create options table */
		$table_instance->create_table();
		if ( ! empty( $table_instance->db_errors ) ) {
			$db_errors[] = $table_instance->db_errors;
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	public function db_update_2_6_1( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$query = "TRUNCATE TABLE `{$wpdb->prefix}bwf_actions`";
		$wpdb->query( $query );

		/** Scheduling Broadcast action */
		if ( true === bwfan_is_autonami_pro_active() && ! bwf_has_action_scheduled( 'bwfcrm_broadcast_run_queue' ) ) {
			bwf_schedule_recurring_action( time(), 60, 'bwfcrm_broadcast_run_queue', array(), 'bwfcrm' );

			BWFAN_PRO_Common::run_midnight_cron();
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	public function db_update_2_6_2( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$query = "ALTER TABLE {$wpdb->prefix}bwfan_automations
    			CHANGE `benchmark` `benchmark` longtext;";
		$wpdb->query( $query );
		$db_errors = [];
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automations alter table - ' . $wpdb->last_error;
		}

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	public function db_update_2_6_3( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/** Cache handling */
		if ( class_exists( 'BWF_JSON_Cache' ) && method_exists( 'BWF_JSON_Cache', 'run_json_endpoints_cache_handling' ) ) {
			BWF_JSON_Cache::run_json_endpoints_cache_handling();
		}

		global $wpdb;
		$query = "SELECT COUNT(ct.ID) FROM `{$wpdb->prefix}bwfan_automation_contact_trail` AS ct JOIN `{$wpdb->prefix}bwfan_automation_complete_contact` AS cc ON ct.tid = cc.trail WHERE ct.status = 2 LIMIT 0,1";

		if ( intval( $wpdb->get_var( $query ) > 0 ) ) {
			/** Scheduling recurring action */
			bwf_schedule_recurring_action( time(), 300, 'bwfan_update_contact_trail', array(), 'bwfan' );
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	public function db_update_2_7_0( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/** Cache handling */
		if ( class_exists( 'BWF_JSON_Cache' ) && method_exists( 'BWF_JSON_Cache', 'run_json_endpoints_cache_handling' ) ) {
			BWF_JSON_Cache::run_json_endpoints_cache_handling();
		}

		/** Save main option settings as autoload true */
		$global_settings = get_option( 'bwfan_global_settings', array() );
		if ( ! empty( $global_settings ) ) {
			delete_option( 'bwfan_global_settings' );
			update_option( 'bwfan_global_settings', $global_settings, true );
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	public function db_update_2_8_0( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/** Reset logs clear action */
		if ( BWFAN_Common::bwf_has_action_scheduled( 'bwfan_delete_logs' ) ) {
			/** Un-schedule action */
			bwf_unschedule_actions( "bwfan_delete_logs" );

			$store_time = BWFAN_Common::get_store_time( 4 );
			bwf_schedule_recurring_action( $store_time, DAY_IN_SECONDS, 'bwfan_delete_logs' );
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}
}

if ( class_exists( 'BWFAN_DB' ) ) {
	BWFAN_Core::register( 'db', 'BWFAN_DB' );
}
