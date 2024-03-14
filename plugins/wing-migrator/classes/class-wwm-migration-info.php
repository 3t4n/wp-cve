<?php

abstract class Wwm_Info {
	/** @var string */
	protected $status;
	/** @var string */
	protected $backup_url;
	/** @var string */
	protected $backup_key;
	/** @var string */
	protected $backup_type;
	/** @var string */
	protected $start_datetime;
	/** @var integer */
	protected $start_timestamp;
	/** @var string */
	protected $finish_datetime;
	/** @var bool */
	protected $force_stop = false;
	/** @var bool */
	protected $info_exists = false;
	/** @var Wwm_Job_Info */
	protected $job_info;
	/** @var bool */
	protected $running = false;
	/** @var bool */
	protected $cron_disabled = false;
	/** @var bool */
	protected $object_cache_enabled = false;

	public function __construct() {
		$this->reload();
	}

	abstract public function get_wp_option_key();

	abstract public function to_array();

	abstract protected function set_reload_data( $data );

	public function reload() {
		$serialized_data = get_option( $this->get_wp_option_key(), null );
		if ( $serialized_data != null ) {
			$s = unserialize( $serialized_data );
			$this->set_reload_data( $s );
		}
	}

	public function update() {
		$s = serialize( $this );
		return update_option( $this->get_wp_option_key(), $s, '', 'no' );
	}

	public function delete() {
		delete_option( $this->get_wp_option_key() );
		$this->status = null;
		$this->backup_type = null;
		$this->backup_key = null;
		$this->backup_url = null;
		$this->start_datetime = null;
		$this->finish_datetime = null;
		$this->force_stop = false;
		$this->job_info = null;
		$this->cron_disabled = false;
		$this->object_cache_enabled = false;
		$this->running = false;
	}

	public function check_force_stop() {
		if ( date( 's' ) !== '00' ) {
			return false;
		}
		$this->update();
		return $this->force_stop;
	}

	public function get_status() {
		if ( ! isset( $this->status ) || is_null( $this->status ) ) {
			return WWM_MIGRATION_STATUS_NO_DATA;
		}
		return $this->status;
	}

	public function set_status( $status ) {
		$this->status = $status;
		return $this;
	}

	public function get_backup_type() {
		return $this->backup_type;
	}

	public function set_backup_type( $backup_type ) {
		$this->backup_type = $backup_type;
		return $this;
	}

	public function get_backup_key() {
		return $this->backup_key;
	}

	public function set_backup_key( $backup_key ) {
		$this->backup_key = $backup_key;
		return $this;
	}

	public function get_backup_url() {
		return $this->backup_url;
	}

	public function set_backup_url( $backup_url ) {
		$this->backup_url = $backup_url;
		return $this;
	}

	public function get_start_datetime() {
		return $this->start_datetime;
	}

	public function set_start_datetime( $start_datetime ) {
		$this->start_datetime = $start_datetime;
		return $this;
	}

	public function get_start_timestamp() {
		return $this->start_timestamp;
	}

	public function set_start_timestamp( $start_timestamp ) {
		$this->start_timestamp = $start_timestamp;
		return $this;
	}

	public function get_finish_datetime() {
		return $this->finish_datetime;
	}

	public function set_finish_datetime( $finish_datetime ) {
		$this->finish_datetime = $finish_datetime;
		return $this;
	}

	public function is_force_stop() {
		return $this->force_stop;
	}

	public function set_force_stop( $force_stop ) {
		$this->force_stop = $force_stop;
		return $this;
	}

	public function is_info_exists() {
		return $this->info_exists;
	}

	public function get_job_info() {
		return $this->job_info;
	}

	public function set_job_info( $job_info ) {
		$this->job_info = $job_info;
		return $this;
	}

	public function update_job_info( $job_info ) {
		$this->set_job_info( $job_info );
		$this->update();
	}

	public function is_running() {
		return $this->running;
	}

	public function set_running( $running ) {
		$this->running = $running;
		return $this;
	}

	public function is_cron_disabled() {
		return $this->cron_disabled;
	}

	public function set_cron_disabled( $cron_disabled ) {
		$this->cron_disabled = $cron_disabled;
		return $this;
	}

	public function set_cron_setting() {
		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
			$this->set_cron_disabled( true );
		}
	}

	public function is_object_cache_enabled() {
		return $this->object_cache_enabled;
	}

	public function set_object_cache_enabled( $object_cache_enabled ) {
		$this->object_cache_enabled = $object_cache_enabled;
		return $this;
	}

	public function set_object_cache_setting() {
		$object_cache_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'object-cache.php';
		$advanced_cache_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'advanced-cache.php';
		if ( wp_using_ext_object_cache()
			|| file_exists( $object_cache_path )
			|| file_exists( $advanced_cache_path ) ) {
			$this->set_object_cache_enabled( true );
		}
	}
}


class Wwm_Backup_Info extends Wwm_Info {
	/** @var string */
	private $site_url;
	/** @var string */
	private $backup_dir_path;
	/** @var string archive file name ( ex. urabnrb.zip ) */
	private $backup_file_name;
	/** @var string database dump file name ( ex. optabnlx.sql) */
	private $backup_db_file_name;
	/** @var string database changing prefix */
	private $prefix;
	/** @var array exclude database tables for mysql dump */
	private $exclude_db_tables = array();

	public function __construct() {
		parent::__construct();
	}

	public function get_wp_option_key() {
		return WWM_BACKUP_INFO_WP_OPTION_KEY;
	}

	protected function set_reload_data( $data ) {
		if ( $data instanceof Wwm_Backup_Info ) {
			// common data
			$this->status = $data->get_status();
			$this->backup_url = $data->get_backup_url();
			$this->backup_key = $data->get_backup_key();
			$this->backup_type = $data->get_backup_type();
			$this->start_datetime = $data->get_start_datetime();
			$this->finish_datetime = $data->get_finish_datetime();
			$this->force_stop = $data->is_force_stop();
			$this->info_exists = true;
			$this->job_info = $data->get_job_info();
			$this->running = $data->is_running();
			$this->cron_disabled = $data->is_cron_disabled();
			// backup data
			$this->site_url = $data->get_site_url();
			$this->backup_dir_path = $data->get_backup_dir_path();
			$this->backup_file_name = $data->get_backup_file_name();
			$this->backup_db_file_name = $data->get_backup_db_file_name();
			$this->prefix = $data->get_prefix();
			$this->exclude_db_tables = $data->get_exclude_db_tables();
		}
	}

	public function to_array() {
		return array(
			'status' => $this->get_status(),
			'cron_disabled' => $this->is_cron_disabled(),
			'object_cache_enabled' => $this->is_object_cache_enabled(),
			'backup_key' => $this->get_backup_key(),
			'site_url' => $this->get_site_url(),
			'backup_url' => $this->get_backup_url(),
			'backup_dir_path' => $this->get_backup_dir_path(),
			'backup_type' => $this->get_backup_type(),
			'backup_file_name' => $this->get_backup_file_name(),
			'backup_db_file_name' => $this->get_backup_db_file_name(),
			'start_datetime' => $this->get_start_datetime(),
			'finish_datetime' => $this->get_finish_datetime(),
			'prefix' => $this->get_prefix(),
			'exclude_db_tables' => $this->get_exclude_db_tables()
		);
	}

	public function delete() {
		parent::delete();
		$this->site_url = null;
		$this->backup_dir_path = null;
		$this->backup_file_name = null;
		$this->backup_db_file_name = null;
		$this->exclude_db_tables = array();
	}

	public function get_backup_file_path() {
		return $this->backup_dir_path . DIRECTORY_SEPARATOR . $this->backup_file_name;
	}

	public function get_backup_db_file_path() {
		return $this->backup_dir_path . DIRECTORY_SEPARATOR . $this->backup_db_file_name;
	}

	public function get_site_url() {
		return $this->site_url;
	}

	public function set_site_url( $site_url ) {
		$this->site_url = $site_url;
		return $this;
	}

	public function get_backup_dir_path() {
		return $this->backup_dir_path;
	}

	public function set_backup_dir_path( $backup_dir_path ) {
		$this->backup_dir_path = $backup_dir_path;
		return $this;
	}

	public function get_backup_file_name() {
		return $this->backup_file_name;
	}

	public function set_backup_file_name( $backup_file_name ) {
		$this->backup_file_name = $backup_file_name;
		return $this;
	}

	public function get_backup_db_file_name() {
		return $this->backup_db_file_name;
	}

	public function set_backup_db_file_name( $backup_db_file_name ) {
		$this->backup_db_file_name = $backup_db_file_name;
		return $this;
	}

	public function get_logger() {
		$log_file_path = $this->backup_dir_path . DIRECTORY_SEPARATOR . 'backup.log';
		return new Wwm_Logger( $log_file_path );
	}

	public function get_prefix() {
		return $this->prefix;
	}

	public function set_prefix( $prefix ) {
		$this->prefix = $prefix;
		return $this;
	}

	public function get_exclude_db_tables() {
		return $this->exclude_db_tables;
	}

	public function set_exclude_db_tables( $exclude_db_tables ) {
		$this->exclude_db_tables = $exclude_db_tables;
		return $this;
	}

}


class Wwm_Restore_Info extends Wwm_Info {
	/** @var string */
	private $restore_dir_name;
	/** @var string */
	private $restore_dir_path;
	/** @var string */
	private $backup_file_url;
	/** @var string */
	private $site_url;
	/** @var string */
	private $restore_file_name;
	/** @var string backup dump file name ( ex. optabnlx.sql) */
	private $restore_db_file_name;

	public function __construct() {
		parent::__construct();
	}

	protected function set_reload_data( $data ) {
		if ( $data instanceof Wwm_Restore_Info ) {
			// common data
			$this->status = $data->get_status();
			$this->backup_url = $data->get_backup_url();
			$this->backup_key = $data->get_backup_key();
			$this->backup_type = $data->get_backup_type();
			$this->start_datetime = $data->get_start_datetime();
			$this->finish_datetime = $data->get_finish_datetime();
			$this->force_stop = $data->is_force_stop();
			$this->info_exists = true;
			$this->job_info = $data->get_job_info();
			$this->running = $data->is_running();
			$this->cron_disabled = $data->is_cron_disabled();
			// restore data
			$this->restore_dir_name = $data->get_restore_dir_name();
			$this->restore_dir_path = $data->get_restore_dir_path();
			$this->backup_file_url = $data->get_backup_file_url();
			$this->site_url = $data->get_site_url();
			$this->restore_file_name = $data->get_restore_file_name();
			$this->restore_db_file_name = $data->get_restore_db_file_name();
		}
	}

	public function to_array() {
		return array(
			'status' => $this->get_status(),
			'cron_disabled' => $this->is_cron_disabled(),
			'object_cache_enabled' => $this->is_object_cache_enabled(),
			'backup_key' => $this->get_backup_key(),
			'backup_url' => $this->get_backup_url(),
			'restore_dir_name' => $this->get_restore_dir_name(),
			'restore_dir_path' => $this->get_restore_dir_path(),
			'start_datetime' => $this->get_start_datetime(),
			'finish_datetime' => $this->get_finish_datetime(),
			'force_stop' => $this->is_force_stop(),
			'backup_file_url' => $this->get_backup_file_url(),
			'site_url' => $this->get_site_url(),
			'restore_file_path' => $this->get_restore_file_path(),
		);
	}

	public function delete() {
		parent::delete();
		$this->restore_dir_path = null;
	}

	public function get_wp_option_key() {
		return WWM_RESTORE_INFO_WP_OPTION_KEY;
	}

	public function get_restore_dir_name() {
		return $this->restore_dir_name;
	}

	public function set_restore_dir_name( $restore_dir_name ) {
		$this->restore_dir_name = $restore_dir_name;
		return $this;
	}

	public function get_restore_dir_path() {
		return $this->restore_dir_path;
	}

	public function set_restore_dir_path( $restore_dir_path ) {
		$this->restore_dir_path = $restore_dir_path;
		return $this;
	}

	public function get_backup_file_url() {
		return $this->backup_file_url;
	}

	public function set_backup_file_url( $backup_file_url ) {
		$this->backup_file_url = $backup_file_url;
		return $this;
	}

	public function get_site_url() {
		return $this->site_url;
	}

	public function set_site_url( $site_url ) {
		$this->site_url = $site_url;
		return $this;
	}

	public function get_restore_file_name() {
		return $this->restore_file_name;
	}

	public function set_restore_file_name( $restore_file_name ) {
		$this->restore_file_name = $restore_file_name;
		return $this;
	}

	public function get_restore_db_file_name() {
		return $this->restore_db_file_name;
	}

	public function set_restore_db_file_name( $restore_db_file_name ) {
		$this->restore_db_file_name = $restore_db_file_name;
		return $this;
	}

	public function get_logger() {
		$log_file_path = $this->restore_dir_path . DIRECTORY_SEPARATOR . 'restore.log';
		return new Wwm_Logger( $log_file_path );
	}

	public function get_restore_file_path() {
		return $this->restore_dir_path . DIRECTORY_SEPARATOR . $this->restore_file_name;
	}

	public function get_restore_db_file_path() {
		return $this->restore_dir_path . DIRECTORY_SEPARATOR . $this->restore_db_file_name;
	}

	public function force_update() {
		$update_res = $this->update();
		if ( $update_res ) {
			return;
		}
		global $wpdb;
		$row = $wpdb->get_row( "SELECT * FROM {$wpdb->options} WHERE option_name = '{$this->get_wp_option_key()} LIMIT 1'" );
		$data = array(
			'option_name' => $this->get_wp_option_key(),
			'option_value' => serialize( $this ),
			'autoload' => 'yes'
		);
		if ( $row === null ) {
			// insert
			$wpdb->insert( $wpdb->options, $data );
			return;
		}
		// update
		$option_id = $row[ 0 ][ 'option_id' ];
		if ( ! isset( $option_id ) || $option_id !== '' ) {
			return;
		}
		$where = array(
			'option_id' => $option_id
		);
		$wpdb->update( $wpdb->options, $data, $where );

	}

}


class Wwm_Job_Info {
	/** @var string */
	private $current_task;
	/** @var array */
	private $current_task_detail = array();
	/** @var int */
	private $current_task_started_time;
	/** @var int */
	private $retry_count = 0;
	/** @var int */
	private $task_timeout = WWM_TASK_DEFAULT_TIMEOUT;
	/** @var int */
	private $max_retry = WWM_TASK_DEFAULT_MAX_RETRY;

	public function get_current_task() {
		return $this->current_task;
	}

	public function set_current_task( $current_task ) {
		$this->current_task = $current_task;
		return $this;
	}

	public function get_current_task_detail() {
		return $this->current_task_detail;
	}

	public function set_current_task_detail( $current_task_detail ) {
		$this->current_task_detail = $current_task_detail;
		return $this;
	}

	public function fetch_current_task_detail( $category, $key ) {
		if ( ! isset( $this->current_task_detail[ $category ][ $key ] ) ) {
			return null;
		}
		return $this->current_task_detail[ $category ][ $key ];
	}

	public function update_current_task_detail( $category, $key, $value ) {
		$this->current_task_detail[ $category ][ $key ] = $value;
	}

	public function get_current_task_started_time() {
		return $this->current_task_started_time;
	}

	public function set_current_task_started_time( $current_task_started_time ) {
		$this->current_task_started_time = $current_task_started_time;
		return $this;
	}

	public function get_retry_count() {
		return $this->retry_count;
	}

	public function set_retry_count( $retry_count ) {
		$this->retry_count = $retry_count;
		return $this;
	}

	public function increment_retry_count() {
		++$this->retry_count;
	}

	public function get_task_timeout() {
		return $this->task_timeout;
	}

	public function set_task_timeout( $task_timeout ) {
		$this->task_timeout = $task_timeout;
		return $this;
	}

	public function init_backup_task_detail() {
		$this->current_task_detail = array(
			'database' => array(
				'dump_header' => false,
				'create_table' => false,
				'finished_tables' => array(),
				'finished_table_offset' => 0,
				'retry_table' => ''
			),
			'file' => array(
				'add_common_file' => false,
				'add_upload_file' => false,
				'add_theme_file' => false,
				'add_plugin_file' => false,
				'finished_directories' => array(),
			)
		);
	}

	public function init_restore_task_detail() {
		$this->current_task_detail = array(
			'database' => array(
				'finished_query_count' => 0,
				'create_required_table' => false
			),
			'file' => array(
				'extract_sql_file' => false,
				'finished_file_offset' => 0,
			)
		);

	}

	public function need_retry() {
		if ( ! isset( $this->current_task_started_time ) ) {
			return false;
		}
		return time() > $this->current_task_started_time + $this->task_timeout;
	}

	public function get_max_retry() {
		return $this->max_retry;
	}

	public function set_max_retry( $max_retry ) {
		$this->max_retry = $max_retry;
		return $this;
	}

	public function is_max_retry_exceeded() {
		return $this->max_retry < $this->retry_count;
	}

	private function get_backup_retry_threshold() {
		return array(
			'db_table_row' => WWM_BACKUP_RETRY_THRESHOLD_DB_TABLE_ROW,
		);
	}

	/**
	 * Whether value is exceeded for backup threshold or not
	 * @param string $key
	 * @param int $value
	 * @return bool
	 */
	public function is_exceeded_backup_threshold( $key, $value ) {
		$threshold = $this->get_backup_retry_threshold();
		if ( ! isset( $threshold[ $key ] ) ) {
			return false;
		}
		return $threshold[ $key ] < $value;
	}

}