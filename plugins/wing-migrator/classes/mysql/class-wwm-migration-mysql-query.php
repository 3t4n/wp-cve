<?php

/**
 * Class Wwm_Mysql_Query
 * query builder for database dump
 */
class Wwm_Mysql_Query {
	/** @var string */
	private $change_prefix;
	/** @var string */
	private $current_prefix;
	/** @var array */
	public $REPLACE_DATA_TARGET;


	/**
	 * Wwm_Mysql_Query constructor.
	 * @param string $change_prefix
	 */
	public function __construct( $change_prefix = null ) {
		global $wpdb;
		$this->REPLACE_DATA_TARGET = array(
			array( "table" => "options", "column" => "option_name", "prefix_empty_target_value" => array( "user_roles" ) ),
			array( "table" => "usermeta", "column" => "meta_key", "prefix_empty_target_value" => array( "capabilities", "user_level" ) ),
		);

		$this->current_prefix = $wpdb->prefix;
		if ( ! isset( $change_prefix ) ) {
			$this->change_prefix = $this->current_prefix;
		} else {
			$this->change_prefix = $change_prefix;
		}
	}

	/**
	 * Get dump header query
	 * @return string[]
	 */
	public function get_dump_header_query() {
		$headers = array();
		array_push( $headers, "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;" );
		array_push( $headers, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;" );
		array_push( $headers, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;" );
		array_push( $headers, "/*!40101 SET NAMES utf8 */;" );
		array_push( $headers, "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;" );
		array_push( $headers, "/*!40103 SET TIME_ZONE='+00:00' */;" );
		array_push( $headers, "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;" );
		array_push( $headers, "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;" );
		array_push( $headers, "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;" );
		array_push( $headers, "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;" );
		return $headers;
	}

	/**
	 * Get dump footer query
	 * @return string[]
	 */
	public function get_dump_footer_query() {
		$footers = array();
		array_push( $footers, "/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;" );
		array_push( $footers, "/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;" );
		array_push( $footers, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;" );
		return $footers;
	}

	/**
	 * Get insert table header query
	 * @param $table_name
	 * @return string
	 */
	public function get_insert_header_query( $table_name ) {
		return "/*!40000 ALTER TABLE `{$table_name}` DISABLE KEYS */;";
	}

	/**
	 * Get insert table footer query
	 * @param $table_name
	 * @return string
	 */
	public function get_insert_footer_query( $table_name ) {
		return "/*!40000 ALTER TABLE `{$table_name}` ENABLE KEYS */;";
	}

	/**
	 * Get table list
	 * @return string[]
	 */
	public function get_table_names() {
		global $wpdb;
		$table_names = array();
		foreach ( $wpdb->get_results( "SHOW TABLES", ARRAY_N ) as $table ) {
			$table_name = $table[ 0 ];
			array_push( $table_names, $table_name );
		}
		return $table_names;
	}

	/**
	 * Check whether it is the dump target table
	 * @param string $table_name
	 * @return bool
	 */
	public function is_dump_target_table( $table_name ) {
		if ( $this->current_prefix != "wp_"
			&& preg_match( "/^wp_/", $table_name )
			&& ! preg_match( "/^{$this->current_prefix}/", $table_name ) ) {
			// When the migration source DB prefix is other than "wp_", if the "wp_" prefix table exists, it is excluded from migration
			return false;
		}
		return true;
	}

	/**
	 * Replace table name prefix
	 * @param string $table_name
	 * @return null|string
	 */
	public function replace_table_names( $table_name ) {
		return preg_replace( "/^{$this->current_prefix}/", $this->change_prefix, $table_name );
	}

	/**
	 * Get table row count
	 * @param string $table_name
	 * @return int
	 */
	public function get_row_count( $table_name ) {
		global $wpdb;
		$row_count = $wpdb->get_row( "SELECT COUNT(1) FROM {$table_name}", ARRAY_N );
		return $row_count[ 0 ];
	}

	/**
	 * Get "Create Table" query
	 * if change_prefix is set, replace prefix.
	 * @param string $table_name
	 * @return null|string
	 */
	public function get_create_table_query( $table_name ) {
		global $wpdb;
		$create_table = $wpdb->get_row( "SHOW CREATE TABLE {$table_name}", ARRAY_A );
		if ( empty( $create_table ) ) {
			return null;
		}
		$create_table_query = $create_table[ 'Create Table' ];
		$replaced_create_table_query = preg_replace( "/CREATE TABLE `{$this->current_prefix}/", "CREATE TABLE `{$this->change_prefix}", $create_table_query );
		return $replaced_create_table_query . ';';
	}

	/**
	 * Get drop table query
	 * @param $table_name
	 * @return string
	 */
	public function get_drop_table_query( $table_name ) {
		return "DROP TABLE IF EXISTS `{$table_name}`;";
	}

	/**
	 * Get table structure from 'SHOW COLUMNS'
	 * @param string $table_name
	 * @return array
	 *      array[$field_name]
	 *              ['Field']
	 *              ['Type']
	 *              ['Null']
	 *              ['Key']
	 *              ['Default']
	 *              ['Extra']
	 */
	public function get_table_structure( $table_name ) {
		global $wpdb;
		$structures = $wpdb->get_results( "SHOW COLUMNS IN {$table_name}", ARRAY_A );
		$table_structure = array();

		foreach ( $structures as $structure ) {
			$table_structure[ $structure[ 'Field' ] ] = $structure;
		}
		return $table_structure;
	}

	/**
	 * Get column for select query
	 * set hex in blob type
	 * @param string $column_name
	 * @param array $column_structures
	 * @return string
	 */
	public function get_select_column( $column_name, $column_structures ) {
		$column_type = $column_structures[ 'Type' ];
		if ( strpos( $column_type, 'blob' ) !== false ) {
			return "HEX(`{$column_name}`) as `{$column_name}`";
		}
		return "`{$column_name}`";
	}

	/**
	 * Get data
	 * @param string $table_name
	 * @param string[] $columns
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function get_results( $table_name, $columns, $limit, $offset ) {
		global $wpdb;
		$select_query = "SELECT " . implode( ',', $columns ) . " FROM {$table_name} LIMIT ${offset},${limit}";
		$results = $wpdb->get_results( $select_query, ARRAY_A );
		return $results;
	}

	/**
	 * Get insert query
	 * @param string $table_name
	 * @param array $results result of select query
	 * @param array $table_structure
	 * @return string insert query
	 */
	public function get_insert_query( $table_name, $results, $table_structure ) {
		$insert_row = array();
		$replaced_table_name = $this->replace_table_names( $table_name );
		$insert_query = "INSERT INTO `{$replaced_table_name}` VALUES \n";

		foreach ( $results as $table_row ) {
			$insert_values = array();
			foreach ( $table_row as $column_name => $value ) {
				$insert_value = $this->get_insert_value( $table_name, $column_name, $value, $table_structure );
				array_push( $insert_values, $insert_value );
			}
			$row = '(' . implode( ',', array_values( $insert_values ) ) . ')';
			array_push( $insert_row, $row );
		}
		$insert_query .= implode( ",\n", $insert_row ) . ';';
		return $insert_query;
	}

	/**
	 * Get value for "INSERT" query
	 * @param $table_name
	 * @param $column_name
	 * @param $value
	 * @param $table_structure
	 * @return string
	 */
	public function get_insert_value( $table_name, $column_name, $value, $table_structure ) {
		if ( $value === null ) {
			return "null";
		}
		$value = $this->replace_value( $table_name, $column_name, $value );
		$column_type = $table_structure[ $column_name ][ 'Type' ];
		if ( strpos( $column_type, 'int' ) !== false
			|| strpos( $column_type, 'float' ) !== false
			|| strpos( $column_type, 'decimal' ) !== false
			|| strpos( $column_type, 'double' ) !== false
			|| strpos( $column_type, 'bool' ) !== false ) {
			return $value;
		} elseif ( strpos( $column_type, 'blob' ) !== false ) {
			return 'UNHEX("' . $value . '")';
		}

		$esc_value = esc_sql( $value );
		global $wpdb;
		if ( method_exists( $wpdb, 'remove_placeholder_escape' ) ) {
			$esc_value = $wpdb->remove_placeholder_escape( $esc_value );
		}
		return '"' . $esc_value . '"';
	}

	/**
	 * Replace when the prefix exists as value
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $value
	 * @return string
	 */
	private function replace_value( $table_name, $column_name, $value ) {
		foreach ( $this->REPLACE_DATA_TARGET as $target ) {
			if ( $table_name === "{$this->current_prefix}{$target["table"]}"
				&& $column_name === $target[ "column" ] ) {

				if ( empty( $this->current_prefix ) && ! in_array( $value, $target[ "prefix_empty_target_value" ] ) ) {
					continue;
				};

				return preg_replace( "/^{$this->current_prefix}/", $this->change_prefix, $value );
			}
		}
		return $value;
	}

	/**
	 * Execute query
	 * @param string $query
	 * @return bool
	 */
	public function execute_query( $query ) {
		global $wpdb;
		$result = $wpdb->query( $query );
		if ( $wpdb->last_error !== '' ) {
			return false;
		}
		if ( $result ) {
			return true;
		}
		// ignore comment or empty data
		if ( strncmp( $query, '/*!', strlen( '/*!' ) ) === 0
			|| $query === '' ) {
			return true;
		}
		return false;
	}

	/**
	 * Update site url
	 * @param $site_url
	 * @return int|false
	 */
	public function update_site_url( $site_url ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"UPDATE {$wpdb->options} SET option_value = %s WHERE option_name = 'siteurl' OR option_name = 'home'",
			$site_url
		);
		return $wpdb->query( $sql );
	}

	/**
	 * Get table size list
	 * @return array
	 */
	public function get_table_size_list() {
		global $wpdb;
		$table_size = array();
		try {
			$query = 'SELECT table_name, table_rows, truncate((data_length+index_length)/1024/1024, 2) AS table_size FROM information_schema.tables';
			$results = $wpdb->get_results( $query, ARRAY_A );
			foreach ( $results as $result ) {
				$table_size[ $result[ 'table_name' ] ] = array(
					'table_size' => $result[ 'table_size' ],
					'table_rows' => $result[ 'table_rows' ]
				);
			}
		} catch ( Exception $exception ) {
		}
		return $table_size;
	}
}


class Wwm_Mysql_Query_Backup {

	/** @var Wwm_Logger */
	private $logger;
	/** @var resource */
	private $dump_file;
	/** @var string string */
	public $file_name;
	/** @var string string */
	public $file_path;
	/** @var Wwm_Backup_Info */
	private $wwm_info;
	/** @var Wwm_Job_Info */
	private $job_info;
	/** @var array */
	private $finished_tables;
	/** @var array */
	private $exclude_tables;

	private static $INSERT_SUCCESS = 0;
	private static $INSERT_NEED_RETRY = 1;
	private static $INSERT_SKIPPED = 2;

	/**
	 * Wwm_Mysql_Query_Backup constructor.
	 * @param string $dir_path backup file dir
	 * @param string $file_name sql file name
	 * @param Wwm_Backup_Info $wwm_info
	 */
	public function __construct( $dir_path, $file_name, $wwm_info ) {

		$this->file_name = $file_name;
		$this->file_path = $dir_path . DIRECTORY_SEPARATOR . $this->file_name;
		$this->wwm_info = $wwm_info;
		$this->logger = $wwm_info->get_logger();
		$this->job_info = $wwm_info->get_job_info();
		$this->exclude_tables = $this->wwm_info->get_exclude_db_tables();
		if ( isset( $this->job_info ) ) {
			$task_detail = $this->job_info->get_current_task_detail();
			$this->finished_tables = $task_detail[ 'database' ][ 'finished_tables' ];
		}
	}

	public function __destruct() {
		if ( isset( $this->dump_file ) && is_resource( $this->dump_file ) ) {
			@fclose( $this->dump_file );
		}
	}

	public function file_delete() {
		if ( file_exists( $this->file_path ) ) {
			@unlink( $this->file_path );

		}
	}

	private function write_line( $string ) {
		fwrite( $this->dump_file, $string . "\n" );
	}

	/**
	 * Get target table names
	 * @param Wwm_Mysql_Query $mysql_query
	 * @return string[]
	 */
	private function get_target_table_names( $mysql_query ) {
		$table_names = $mysql_query->get_table_names();
		$target_table_names = array();
		$skip_table_names = array();
		foreach ( $table_names as $table_name ) {
			if ( ! $mysql_query->is_dump_target_table( $table_name ) ) {
				array_push( $skip_table_names, $table_name );
				continue;
			}
			array_push( $target_table_names, $table_name );
		}
		if( $this->job_info->get_retry_count() == 0 ){
			// Display skip tables for the first time only.
			$this->logger->info( 'skip_tables:' . implode( ',', $skip_table_names ) );
		}
		return $target_table_names;
	}

	/**
	 * write query header to dump file
	 * @param string $change_prefix
	 * @return bool
	 */
	public function dump_header( $change_prefix ) {
		try {
			$this->dump_file = fopen( $this->file_path, 'a' );
			$mysql_query = new Wwm_Mysql_Query( $change_prefix );
			$headers = $mysql_query->get_dump_header_query();
			foreach ( $headers as $header ) {
				$this->write_line( $header );
			}
			@fclose( $this->dump_file );
			return true;
		} catch ( Exception $e ) {
			$this->logger->exception( "dump header error", $e );
			@fclose( $this->dump_file );
			return false;
		}
	}

	/**
	 * write query footer to dump file
	 * @param string $change_prefix
	 * @return bool
	 */
	public function dump_footer( $change_prefix ) {
		try {
			$this->dump_file = fopen( $this->file_path, 'a' );
			$mysql_query = new Wwm_Mysql_Query( $change_prefix );
			$footers = $mysql_query->get_dump_header_query();
			foreach ( $footers as $footer ) {
				$this->write_line( $footer );
			}
			@fclose( $this->dump_file );
			return true;
		} catch ( Exception $e ) {
			$this->logger->exception( "dump footer error", $e );
			@fclose( $this->dump_file );
			return false;
		}
	}

	/**
	 * write "DROP TABLE" and "CREATE TABLE" query to dump file
	 * @param string $change_prefix
	 * @return bool
	 */
	public function dump_create_table( $change_prefix ) {
		try {
			$this->dump_file = fopen( $this->file_path, 'a' );
			$mysql_query = new Wwm_Mysql_Query( $change_prefix );
			$table_size_list = $mysql_query->get_table_size_list();

			global $wpdb;
			$this->logger->info( '***** prefix *****' );
			$this->logger->info( 'db_prefix:' .  $wpdb->prefix );
			$this->logger->info( 'change_prefix:' .  $change_prefix );

			$this->logger->info( '***** tables *****' );
			$target_table_names = $this->get_target_table_names( $mysql_query );

			// drop table
			foreach ( $target_table_names as $target_table_name ) {
				if ( array_key_exists( $target_table_name, $table_size_list ) ) {
					$table_size = $table_size_list[ $target_table_name ][ 'table_size' ];
					$table_rows = $table_size_list[ $target_table_name ][ 'table_rows' ];
					$this->logger->info( $target_table_name . ' (' . $table_rows . 'rows / ' . $table_size . 'MB)' );
				} else {
					$this->logger->info( $target_table_name );
				}
				$replaced_table_name = $mysql_query->replace_table_names( $target_table_name );
				$drop_table_query = $mysql_query->get_drop_table_query( $replaced_table_name );
				$this->write_line( $drop_table_query );
			}

			// create table
			foreach ( $target_table_names as $target_table_name ) {
				$create_table_query = $mysql_query->get_create_table_query( $target_table_name );
				$this->write_line( $create_table_query );
			}

			@fclose( $this->dump_file );
			return true;
		} catch ( Exception $e ) {
			$this->logger->exception( "dump_create_table error", $e );
			@fclose( $this->dump_file );
			return false;
		}
	}

	/**
	 * write "INSERT" query to dump file
	 * @param string $change_prefix
	 * @return bool
	 */
	public function dump_data( $change_prefix ) {

		try {
			$this->dump_file = fopen( $this->file_path, 'a' );
			$mysql_query = new Wwm_Mysql_Query( $change_prefix );
			$target_table_names = array();

			// filtered finished table
			foreach ( $this->get_target_table_names( $mysql_query ) as $target_table_name ) {
				if ( in_array( $target_table_name, $this->finished_tables ) ) {
					continue;
				}
				if ( in_array( $target_table_name, $this->exclude_tables ) ) {
					continue;
				}
				array_push( $target_table_names, $target_table_name );
			}

			$priority_table_names = array();
			$normal_table_names = array();

			// classify table
			foreach ( $target_table_names as $target_table_name ) {
				if ( strpos( $target_table_name, 'options' ) !== false
					|| strpos( $target_table_name, 'users' ) !== false
					|| strpos( $target_table_name, 'usermeta' ) !== false ) {
					array_push( $priority_table_names, $target_table_name );
				} else {
					array_push( $normal_table_names, $target_table_name );
				}
			}

			if ( count( $priority_table_names ) !== 0 ) {
				$this->logger->info( '---------- priority table insert----------' );
			}
			foreach ( $priority_table_names as $table_name ) {
				$this->logger->info( 'table data create: ' . $table_name );

				$result = $this->create_insert_table( $mysql_query, $table_name );
				if ( $result === static::$INSERT_NEED_RETRY ) {
					@fclose( $this->dump_file );
					return false;
				}
			}
			if ( count( $normal_table_names ) !== 0 ) {
				$this->logger->info( '---------- normal table insert----------' );
			}
			foreach ( $normal_table_names as $table_name ) {
				$this->logger->info( 'table data create: ' . $table_name );
				$result = $this->create_insert_table( $mysql_query, $table_name );
				if ( $result === static::$INSERT_NEED_RETRY ) {
					@fclose( $this->dump_file );
					return false;
				}
			}

			@fclose( $this->dump_file );
			return true;
		} catch ( Exception $e ) {
			$this->logger->exception( "dump_data error", $e );
			@fclose( $this->dump_file );
			return false;
		}
	}

	/**
	 * @param Wwm_Mysql_Query $mysql_query
	 * @param string $table_name
	 * @return int
	 */
	private function create_insert_table( $mysql_query, $table_name ) {
		if ( $this->job_info->need_retry() ) {
			$this->logger->info( 'need retry' );
			return static::$INSERT_NEED_RETRY;
		}
		$row_count = $mysql_query->get_row_count( $table_name );
		if ( $row_count === 0 ) {
			array_push( $this->finished_tables, $table_name );
			$this->job_info->update_current_task_detail( 'database', 'finished_tables', $this->finished_tables );
			$this->wwm_info->update_job_info( $this->job_info );
			return static::$INSERT_SKIPPED;
		}
		if ( $this->job_info->is_exceeded_backup_threshold( 'db_table_row', $row_count )
			&& $this->job_info->fetch_current_task_detail( 'database', 'retry_table' ) !== $table_name ) {
			// force retry for large table data
			$this->logger->warning( 'Large tables found in ' . $table_name . '(' . $row_count . '). Database dump may fail.' );
			$this->job_info->update_current_task_detail( 'database', 'retry_table', $table_name );
			$this->wwm_info->update_job_info( $this->job_info );
			return static::$INSERT_NEED_RETRY;
		}

		$table_structure = $mysql_query->get_table_structure( $table_name );
		$replaced_table_name = $mysql_query->replace_table_names( $table_name );
		$columns = array();
		foreach ( $table_structure as $column_name => $column_structure ) {
			$select_column = $mysql_query->get_select_column( $column_name, $column_structure );
			array_push( $columns, $select_column );
		}

		$limit = WWM_BACKUP_BULK_INSERT_LIMIT;
		$offset = $this->job_info->fetch_current_task_detail( 'database', 'finished_table_offset' );
		$is_interrupt = false;

		if ( $offset === 0 ) {
			// write insert query
			$this->write_line( $mysql_query->get_insert_header_query( $replaced_table_name ) );
		} else {
			$this->logger->info( 'continue offset:' . $offset );
		}

		while ( true ) {
			$results = $mysql_query->get_results( $table_name, $columns, $limit, $offset );
			if ( count( $results ) === 0 ) {
				break;
			}
			$insert_query = $mysql_query->get_insert_query( $table_name, $results, $table_structure );
			$this->write_line( $insert_query );
			$offset += $limit;

			if ( $this->job_info->need_retry() ) {
				$is_interrupt = true;
				break;
			}
		}

		if ( $is_interrupt ) {
			// set offset
			$this->job_info->update_current_task_detail( 'database', 'finished_table_offset', $offset );
			$this->wwm_info->update_job_info( $this->job_info );
			return static::$INSERT_NEED_RETRY;
		}

		// reset offset
		$this->job_info->update_current_task_detail( 'database', 'finished_table_offset', 0 );

		$this->write_line( $mysql_query->get_insert_footer_query( $replaced_table_name ) );
		array_push( $this->finished_tables, $table_name );
		$this->job_info->update_current_task_detail( 'database', 'finished_tables', $this->finished_tables );
		$this->wwm_info->update_job_info( $this->job_info );
		return static::$INSERT_SUCCESS;
	}
}


class Wwm_Mysql_Query_Restore {

	/** @var Wwm_Restore_Info */
	private $wwm_info;
	/** @var Wwm_Job_Info */
	private $job_info;
	/** @var Wwm_Logger */
	private $logger;

	public static $RESULT_SUCCESS = 0;
	public static $RESULT_NEED_RETRY = 1;

	/**
	 * Wwm_Mysql_Query_Restore constructor.
	 * @param Wwm_Restore_Info $wwm_info
	 */
	public function __construct( $wwm_info ) {
		$this->wwm_info = $wwm_info;
		$this->job_info = $wwm_info->get_job_info();
		$this->logger = $wwm_info->get_logger();
	}

	private function check_first_query( $buffer ) {
		return strncmp( $buffer, '/*!', strlen( '/*!' ) ) === 0
			|| strncmp( $buffer, 'DROP TABLE', strlen( 'DROP TABLE' ) ) === 0
			|| strncmp( $buffer, 'CREATE TABLE', strlen( 'CREATE TABLE' ) ) === 0
			|| strncmp( $buffer, 'INSERT INTO', strlen( 'INSERT INTO' ) ) === 0;
	}

	private function is_insert_required_table( $sql ) {
		return strpos( $sql, 'users' ) !== false
			&& stripos( $sql, 'ALTER TABLE' ) !== false
			&& stripos( $sql, 'ENABLE KEYS' ) !== false;
	}

	public function restore( $sql_file_path ) {
		$handle = @fopen( $sql_file_path, "r" );
		$mysql_query = new Wwm_Mysql_Query();
		$query_count = 0;
		$finished_query_count = $this->job_info->fetch_current_task_detail( 'database', 'finished_query_count' );
		$is_create_required_table = $this->job_info->fetch_current_task_detail( 'database', 'create_required_table' );

		$this->logger->info( 'query offset: ' . $finished_query_count );
		try {
			$sql = '';

			while ( ( $buffer = fgets( $handle, 4096 ) ) !== false ) {
				$buf = preg_replace( '/\n|\r|\r\n/', '', $buffer );
				if ( $this->check_first_query( $buf ) === false ) {
					$sql .= $buf;
					continue;
				}
				if ( $this->job_info->need_retry() && $is_create_required_table ) {
					@fclose( $handle );
					$this->job_info->update_current_task_detail( 'database', 'finished_query_count', $query_count );
					return static::$RESULT_NEED_RETRY;
				}
				if ( $finished_query_count === 0 || $query_count >= $finished_query_count ) {
					$res = $mysql_query->execute_query( $sql );
					if ( ! $res ) {
						$this->logger->warning( 'fail to execute query: ' . $sql );
					}
					if ( $this->is_insert_required_table( $sql ) ) {
						$this->job_info->update_current_task_detail( 'database', 'create_required_table', true );
						$is_create_required_table = true;
					}
				}
				$sql = $buf;
				$query_count++;
			}
			$res = $mysql_query->execute_query( $sql );
			if ( ! $res ) {
				$this->logger->warning( 'fail to execute query: ' . $sql );
				throw new Exception( 'fail to execute query' );
			}
			if ( ! feof( $handle ) ) {
				throw new Exception( 'unexpected fgets' );
			}
			@fclose( $handle );
			$this->job_info->update_current_task_detail( 'database', 'finished_query_count', $query_count );

			return static::$RESULT_SUCCESS;
		} catch ( Exception $e ) {
			$this->logger->exception( "restore error", $e );
			@fclose( $handle );
			$this->job_info->update_current_task_detail( 'database', 'finished_query_count', $query_count );

			return static::$RESULT_NEED_RETRY;
		}
	}

	public function update_site_url( $site_url ) {
		if ( empty( $site_url ) ) {
			$this->logger->info( 'no change site url' );
			return;
		}
		$this->logger->info( 'change to ' . $site_url );
		$mysql_query = new Wwm_Mysql_Query();
		$result = $mysql_query->update_site_url( $site_url );
		if ( $result === false ) {
			$this->logger->warning( 'fail to change site url' );
		}
	}
}