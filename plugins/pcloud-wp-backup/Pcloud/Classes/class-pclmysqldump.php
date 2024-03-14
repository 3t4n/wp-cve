<?php
/**
 * PHP version of mysqldump cli that comes with MySQL.
 *
 * Tags: mysql mysqldump pdo php7 php5 database php sql hhvm mariadb mysql-backup.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

use Exception;
use PDO;
use PDOException;

/**
 * Class PclMysqlDump
 */
class PclMysqlDump {

	// Same as mysqldump.
	const MAXLINE_SIZE = 1000000;

	// List of available compression methods as constants.
	const NONE = 'None';

	// List of available connection strings.
	const UTF8 = 'utf8';

	/**
	 * File handler
	 *
	 * @var null|resource $file_handler
	 */
	private $file_handler = null;

	/**
	 * Database username.
	 *
	 * @var string
	 */
	public $user;

	/**
	 * Database password.
	 *
	 * @var string
	 */
	public $pass;

	/**
	 * Connection string for PDO.
	 *
	 * @var string
	 */
	public $dsn;

	/**
	 * Destination file_name, defaults to stdout.
	 *
	 * @var string $file_name
	 */
	public $file_name = 'php://stdout';

	/**
	 * MySQL Tables.
	 *
	 * @var array $tables
	 */
	private $tables = array();

	/**
	 * MySQL Views.
	 *
	 * @var array $views
	 */
	private $views = array();

	/**
	 * MySQL Triggers.
	 *
	 * @var array $triggers
	 */
	private $triggers = array();

	/**
	 * MySQL Procedures.
	 *
	 * @var array $procedures
	 */
	private $procedures = array();

	/**
	 * MySQL Functions.
	 *
	 * @var array $functions
	 */
	private $functions = array();

	/**
	 * MySQL Events.
	 *
	 * @var array $events
	 */
	private $events = array();

	/**
	 * MySQL DB handler.
	 *
	 * @var PDO|null $db_handler
	 */
	private $db_handler = null;

	/**
	 * MySQL DB type.
	 *
	 * @var string $db_type
	 */
	private $db_type = '';

	/**
	 * MySQL type adapter.
	 *
	 * @var PclTypeAdapterMysql|null $type_adapter
	 */
	private $type_adapter;

	/**
	 * MySQL dump settings.
	 *
	 * @var array|null $dump_settings
	 */
	private $dump_settings;

	/**
	 * MySQL PDO settings.
	 *
	 * @var array|null $pdo_settings
	 */
	private $pdo_settings;

	/**
	 * MySQL table column types
	 *
	 * @var array $table_column_types
	 */
	private $table_column_types = array();

	/**
	 * Database name, parsed from dsn.
	 *
	 * @var string|null $db_name Database name.
	 */
	private $db_name;

	/**
	 * Dsn string parsed as an array.
	 *
	 * @var array|null $dsn_array
	 */
	private $dsn_array = array();

	/**
	 * Keyed on table name, with the value as the conditions.
	 * e.g. - 'users' => 'date_registered > NOW() - INTERVAL 6 MONTH'
	 *
	 * @var array $table_wheres
	 */
	private $table_wheres = array();

	/**
	 * Table limits
	 *
	 * @var array $table_limits Table limits.
	 */
	private $table_limits = array();

	/**
	 * Constructor of Mysqldump. Note that in the case of an SQLite database
	 * connection, the file_name must be in the $db parameter.
	 *
	 * @param string     $dsn PDO DSN connection string.
	 * @param string     $user SQL account username.
	 * @param string     $pass SQL account password.
	 * @param array|null $dump_settings SQL database settings.
	 * @param array|null $pdo_settings PDO configured attributes.
	 *
	 * @throws Exception Throws standart Exception.
	 */
	public function __construct( string $dsn = '', string $user = '', string $pass = '', ?array $dump_settings = array(), ?array $pdo_settings = array() ) {
		$dump_settings_default = array(
			'include-tables'        => array(),
			'exclude-tables'        => array(),
			'include-views'         => array(),
			'compress'              => self::NONE,
			'init_commands'         => array(),
			'no-data'               => array(),
			'reset-auto-increment'  => false,
			'add-drop-database'     => false,
			'add-drop-table'        => false,
			'add-drop-trigger'      => true,
			'add-locks'             => true,
			'complete-insert'       => false,
			'databases'             => false,
			'default-character-set' => self::UTF8,
			'disable-keys'          => true,
			'extended-insert'       => true,
			'events'                => false,
			'hex-blob'              => true, /* faster than escaped content */
			'insert-ignore'         => false,
			'net_buffer_length'     => self::MAXLINE_SIZE,
			'no-autocommit'         => true,
			'no-create-info'        => false,
			'lock-tables'           => true,
			'routines'              => false,
			'single-transaction'    => true,
			'skip-triggers'         => false,
			'skip-tz-utc'           => false,
			'skip-comments'         => true,
			'skip-dump-date'        => false,
			'skip-definer'          => false,
			'where'                 => '',
		);

		$pdo_settings_default = array(
			PDO::ATTR_PERSISTENT => true, // phpcs:ignore
			PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION, // phpcs:ignore
		);

		$this->user = $user;
		$this->pass = $pass;
		$this->parse_dsn( $dsn );

		// This drops MYSQL dependency, only use the constant if it's defined.
		if ( 'mysql' === $this->db_type ) {
			$pdo_settings_default[ PDO::MYSQL_ATTR_USE_BUFFERED_QUERY ] = false; // phpcs:ignore
		}

		$this->pdo_settings                     = array_replace_recursive( $pdo_settings_default, $pdo_settings );
		$this->dump_settings                    = array_replace_recursive( $dump_settings_default, $dump_settings );
		$this->dump_settings['init_commands'][] = 'SET NAMES ' . $this->dump_settings['default-character-set'];

		if ( false === $this->dump_settings['skip-tz-utc'] ) {
			$this->dump_settings['init_commands'][] = "SET TIME_ZONE='+00:00'";
		}

		$diff = array_diff( array_keys( $this->dump_settings ), array_keys( $dump_settings_default ) );
		if ( count( $diff ) > 0 ) {
			throw new Exception( 'Unexpected value in dumpSettings: (' . implode( ',', $diff ) . ')' );
		}

		if ( ! is_array( $this->dump_settings['include-tables'] ) || ! is_array( $this->dump_settings['exclude-tables'] ) ) {
			throw new Exception( 'Include-tables and exclude-tables should be arrays' );
		}

		// If no include-views is passed in, dump the same views as tables, mimic mysqldump behaviour.
		if ( ! isset( $dump_settings['include-views'] ) ) {
			$this->dump_settings['include-views'] = $this->dump_settings['include-tables'];
		}
	}

	/**
	 * Destructor of Mysqldump. Unsets dbHandlers and database objects.
	 */
	public function __destruct() {
		$this->db_handler = null;
	}

	/**
	 * Get Table Where
	 *
	 * @param string $table_name The Table name.
	 * @return boolean|mixed
	 */
	public function get_table_where( string $table_name ) {
		if ( ! empty( $this->table_wheres[ $table_name ] ) ) {
			return $this->table_wheres[ $table_name ];
		} elseif ( $this->dump_settings['where'] ) {
			return $this->dump_settings['where'];
		}

		return false;
	}

	/**
	 * Returns the LIMIT for the table.  Must be numeric to be returned.
	 *
	 * @param string $table_name Table name.
	 *
	 * @return boolean
	 */
	public function get_table_limit( string $table_name ) {

		if ( ! isset( $this->table_limits[ $table_name ] ) ) {
			return false;
		}

		$limit = $this->table_limits[ $table_name ];
		if ( ! is_numeric( $limit ) ) {
			return false;
		}

		return $limit;
	}

	/**
	 * Parse DSN string and extract dbname value
	 * Several examples of a DSN string
	 *   mysql:host=localhost;dbname=testdb
	 *   mysql:host=localhost;port=3307;dbname=testdb
	 *   mysql:unix_socket=/tmp/mysql.sock;dbname=testdb
	 *
	 * @param string $dsn DNS string to parse.
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function parse_dsn( string $dsn ) {

		$pos = strpos( $dsn, ':' );
		if ( empty( $dsn ) || ( false === $pos ) ) {
			throw new Exception( 'Empty DSN string' );
		}

		$this->dsn     = $dsn;
		$this->db_type = strtolower( substr( $dsn, 0, $pos ) );

		if ( empty( $this->db_type ) ) {
			throw new Exception( 'Missing database type from DSN string' );
		}

		$dsn = substr( $dsn, $pos + 1 );

		foreach ( explode( ';', $dsn ) as $kvp ) {
			$kvp_arr                                      = explode( '=', $kvp );
			$this->dsn_array[ strtolower( $kvp_arr[0] ) ] = $kvp_arr[1];
		}

		if ( empty( $this->dsn_array['host'] ) && empty( $this->dsn_array['unix_socket'] ) ) {
			throw new Exception( 'Missing host from DSN string' );
		}

		if ( empty( $this->dsn_array['dbname'] ) ) {
			throw new Exception( 'Missing database name from DSN string' );
		}

		$this->db_name = $this->dsn_array['dbname'];

	}

	/**
	 * Connect with PDO.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function connect() {

		try {
			$this->db_handler = new PDO( // phpcs:ignore
				$this->dsn,
				$this->user,
				$this->pass,
				$this->pdo_settings
			);

			foreach ( $this->dump_settings['init_commands'] as $stmt ) {
				$this->db_handler->exec( $stmt );
			}
		} catch ( PDOException $e ) {
			throw new Exception(
				'Connection to ' . $this->db_type . ' failed with message: ' .
				$e->getMessage()
			);
		}

		$this->db_handler->setAttribute( PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL ); // phpcs:ignore
		$this->type_adapter = PclTypeAdapterMysql::create( $this->db_handler, $this->dump_settings );
	}

	/**
	 * Primary function, triggers dumping.
	 *
	 * @param string $file_name Name of file to write sql dump to.
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	public function start( string $file_name = '' ) {

		if ( ! empty( $file_name ) ) {
			$this->file_name = $file_name;
		}

		$this->connect();
		$this->compress_open( $this->file_name );
		$this->compress_write( $this->get_dump_file_header() );
		$this->compress_write(
			$this->type_adapter->backup_parameters()
		);

		if ( $this->dump_settings['databases'] ) {
			$this->compress_write(
				$this->type_adapter->get_database_header( $this->db_name )
			);
			if ( $this->dump_settings['add-drop-database'] ) {
				$this->compress_write(
					$this->type_adapter->add_drop_database( $this->db_name )
				);
			}
		}

		$this->get_database_structure_tables();
		$this->get_database_structure_views();
		$this->get_database_structure_triggers();
		$this->get_database_structure_procedures();
		$this->get_database_structure_functions();
		$this->get_database_structure_events();

		if ( $this->dump_settings['databases'] ) {
			$this->compress_write(
				$this->type_adapter->databases( $this->db_name )
			);
		}

		if ( count( $this->dump_settings['include-tables'] ) > 0 ) {
			$name = implode( ',', $this->dump_settings['include-tables'] );
			throw new Exception( 'Table (' . $name . ') not found in database' );
		}

		$this->export_tables();
		$this->export_triggers();
		$this->export_functions();
		$this->export_procedures();
		$this->export_views();
		$this->export_events();

		$this->compress_write(
			$this->type_adapter->restore_parameters()
		);
		$this->compress_write( $this->get_dump_file_footer() );
		$this->compress_close();
	}

	/**
	 * Returns header for dump file.
	 *
	 * @return string
	 */
	private function get_dump_file_header(): string {
		return PHP_EOL . 'set FOREIGN_KEY_CHECKS=0;' . PHP_EOL;
	}

	/**
	 * Returns footer for dump file.
	 *
	 * @return string
	 */
	private function get_dump_file_footer(): string {
		return PHP_EOL . 'set FOREIGN_KEY_CHECKS=1;' . PHP_EOL;
	}

	/**
	 * Reads table names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 * @throws Exception Standart Exception can be thrown.
	 */
	private function get_database_structure_tables() {

		if ( empty( $this->dump_settings['include-tables'] ) ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_tables( $this->db_name ) ) as $row ) {
				$this->tables[] = current( $row );
			}
		} else {
			foreach ( $this->db_handler->query( $this->type_adapter->show_tables( $this->db_name ) ) as $row ) {
				if ( in_array( current( $row ), $this->dump_settings['include-tables'], true ) ) {
					$this->tables[] = current( $row );
					$elem           = array_search( current( $row ), $this->dump_settings['include-tables'], true );
					unset( $this->dump_settings['include-tables'][ $elem ] );
				}
			}
		}
	}

	/**
	 * Reads view names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 * @throws Exception Standart Exception can be thrown.
	 */
	private function get_database_structure_views() {
		if ( empty( $this->dump_settings['include-views'] ) ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_views( $this->db_name ) ) as $row ) {
				$this->views[] = current( $row );
			}
		} else {
			foreach ( $this->db_handler->query( $this->type_adapter->show_views( $this->db_name ) ) as $row ) {
				if ( in_array( current( $row ), $this->dump_settings['include-views'], true ) ) {
					$this->views[] = current( $row );
					$elem          = array_search( current( $row ), $this->dump_settings['include-views'], true );
					unset( $this->dump_settings['include-views'][ $elem ] );
				}
			}
		}
	}

	/**
	 * Reads trigger names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 */
	private function get_database_structure_triggers() {
		if ( false === $this->dump_settings['skip-triggers'] ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_triggers( $this->db_name ) ) as $row ) {
				$this->triggers[] = $row['Trigger'];
			}
		}
	}

	/**
	 * Reads procedure names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 */
	private function get_database_structure_procedures() {
		if ( $this->dump_settings['routines'] ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_procedures( $this->db_name ) ) as $row ) {
				$this->procedures[] = $row['procedure_name'];
			}
		}
	}

	/**
	 * Reads functions names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 */
	private function get_database_structure_functions() {
		if ( $this->dump_settings['routines'] ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_functions( $this->db_name ) ) as $row ) {
				$this->functions[] = $row['function_name'];
			}
		}
	}

	/**
	 * Reads event names from database.
	 * Fills $this->tables array, so they will be dumped later.
	 *
	 * @return void
	 * @throws Exception Standart Exception can be thrown.
	 */
	private function get_database_structure_events() {
		if ( $this->dump_settings['events'] ) {
			foreach ( $this->db_handler->query( $this->type_adapter->show_events( $this->db_name ) ) as $row ) {
				$this->events[] = $row['event_name'];
			}
		}
	}

	/**
	 * Compare if $table name matches with a definition inside $arr
	 *
	 * @param string $table Database table.
	 * @param array  $arr With strings or patterns.
	 *
	 * @return boolean
	 */
	private function matches( string $table, array $arr ): bool {
		$match = false;
		foreach ( $arr as $pattern ) {
			if ( preg_match( '/' . $pattern . '/', $table ) === 1 ) {
				$match = true;
			}
		}
		return in_array( $table, $arr, true ) || $match;
	}

	/**
	 * Exports all the tables selected from database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_tables() {
		foreach ( $this->tables as $table ) {
			if ( $this->matches( $table, $this->dump_settings['exclude-tables'] ) ) {
				continue;
			}
			$this->get_table_structure( $table );
			if ( false === $this->dump_settings['no-data'] ) {
				$this->list_values( $table );
			} elseif ( true === $this->dump_settings['no-data'] || $this->matches( $table, $this->dump_settings['no-data'] ) ) {
				continue;
			} else {
				$this->list_values( $table );
			}
		}
	}

	/**
	 * Exports all the views found in database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_views() {
		if ( false === $this->dump_settings['no-create-info'] ) {
			foreach ( $this->views as $view ) {
				if ( $this->matches( $view, $this->dump_settings['exclude-tables'] ) ) {
					continue;
				}
				$this->table_column_types[ $view ] = $this->gettable_column_types( $view );
				$this->get_view_structure_table( $view );
			}
			foreach ( $this->views as $view ) {
				if ( $this->matches( $view, $this->dump_settings['exclude-tables'] ) ) {
					continue;
				}
				$this->get_view_structure_view( $view );
			}
		}
	}

	/**
	 * Exports all the triggers found in database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_triggers() {
		foreach ( $this->triggers as $trigger ) {
			$this->get_trigger_structure( $trigger );
		}

	}

	/**
	 * Exports all the procedures found in database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_procedures() {
		foreach ( $this->procedures as $procedure ) {
			$this->get_procedure_structure( $procedure );
		}
	}

	/**
	 * Exports all the functions found in database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_functions() {
		foreach ( $this->functions as $function ) {
			$this->get_function_structure( $function );
		}
	}

	/**
	 * Exports all the events found in database
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function export_events() {
		foreach ( $this->events as $event ) {
			$this->get_event_structure( $event );
		}
	}

	/**
	 * Table structure extractor
	 *
	 * @param string $table_name Name of table to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_table_structure( string $table_name ) {
		if ( ! $this->dump_settings['no-create-info'] ) {

			$ret = '--' . PHP_EOL . "-- Table structure for table `$table_name`" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;

			$stmt = $this->type_adapter->show_create_table( $table_name );
			foreach ( $this->db_handler->query( $stmt ) as $r ) {
				$this->compress_write( $ret );
				if ( $this->dump_settings['add-drop-table'] ) {
					$this->compress_write(
						$this->type_adapter->drop_table( $table_name )
					);
				}
				$this->compress_write(
					$this->type_adapter->create_table( $r )
				);
				break;
			}
		}
		$this->table_column_types[ $table_name ] = $this->gettable_column_types( $table_name );
	}

	/**
	 * Store column types to create data dumps and for Stand-In tables
	 *
	 * @param string $table_name Name of table to export.
	 * @return array type column types detailed
	 */
	private function gettable_column_types( string $table_name ): array {
		$column_types = array();
		$columns      = $this->db_handler->query(
			$this->type_adapter->show_columns( $table_name )
		);
		$columns->setFetchMode( PDO::FETCH_ASSOC ); // phpcs:ignore

		foreach ( $columns as $col ) {
			$types                         = $this->type_adapter->parse_column_type( $col );
			$column_types[ $col['Field'] ] = array(
				'is_numeric' => $types['is_numeric'],
				'is_blob'    => $types['is_blob'],
				'type'       => $types['type'],
				'type_sql'   => $col['Type'],
				'is_virtual' => $types['is_virtual'],
			);
		}

		return $column_types;
	}

	/**
	 * View structure extractor, create table (avoids cyclic references)
	 *
	 * @param string $view_name Name of view to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_view_structure_table( string $view_name ) {
		$ret = '--' . PHP_EOL . "-- Stand-In structure for view `$view_name`" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
		$this->compress_write( $ret );

		$stmt = $this->type_adapter->show_create_view( $view_name );

		$this->db_handler->query( $stmt );
		if ( $this->dump_settings['add-drop-table'] ) {
			$this->compress_write(
				$this->type_adapter->drop_view( $view_name )
			);
		}
		$this->compress_write(
			$this->create_stand_in_table( $view_name )
		);
	}

	/**
	 * Write a "CREATE TABLE" statement for the table Stand-In, show create
	 * table would return a "CREATE ALGORITHM" when used on a view
	 *
	 * @param string $view_name Name of view to export.
	 *
	 * @return string
	 */
	public function create_stand_in_table( string $view_name ): string {
		$ret = array();
		foreach ( $this->table_column_types[ $view_name ] as $k => $v ) {
			$ret[] = "`$k` ${v['type_sql']}";
		}
		$ret = implode( PHP_EOL . ',', $ret );

		return "CREATE TABLE IF NOT EXISTS `$view_name` (" . PHP_EOL . $ret . PHP_EOL . ');' . PHP_EOL;
	}

	/**
	 * View structure extractor, create view
	 *
	 * @param string $view_name Name of view to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_view_structure_view( string $view_name ) {
		if ( ! $this->dump_settings['skip-comments'] ) {
			$ret = '--' . PHP_EOL . "-- View structure for view `$view_name`" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
			$this->compress_write( $ret );
		}
		$stmt = $this->type_adapter->show_create_view( $view_name );

		foreach ( $this->db_handler->query( $stmt ) as $r ) {
			$this->compress_write( $this->type_adapter->drop_view( $view_name ) );
			$this->compress_write( $this->type_adapter->create_view( $r ) );
			break;
		}
	}

	/**
	 * Trigger structure extractor
	 *
	 * @param string $trigger_name Name of trigger to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_trigger_structure( string $trigger_name ) {
		$stmt = $this->type_adapter->show_create_trigger( $trigger_name );
		foreach ( $this->db_handler->query( $stmt ) as $r ) {
			if ( $this->dump_settings['add-drop-trigger'] ) {
				$this->compress_write( $this->type_adapter->add_drop_trigger( $trigger_name ) );
			}
			$this->compress_write( $this->type_adapter->create_trigger( $r ) );

			return;
		}
	}

	/**
	 * Procedure structure extractor
	 *
	 * @param string $procedure_name Name of procedure to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_procedure_structure( string $procedure_name ) {
		if ( ! $this->dump_settings['skip-comments'] ) {
			$ret = '--' . PHP_EOL . "-- Dumping routines for database '" . $this->db_name . "'" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
			$this->compress_write( $ret );
		}
		$stmt = $this->type_adapter->show_create_procedure( $procedure_name );
		foreach ( $this->db_handler->query( $stmt ) as $r ) {
			$this->compress_write( $this->type_adapter->create_procedure( $r ) );
		}
	}

	/**
	 * Function structure extractor
	 *
	 * @param string $function_name Name of function to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_function_structure( string $function_name ) {
		if ( ! $this->dump_settings['skip-comments'] ) {
			$ret = '--' . PHP_EOL . "-- Dumping routines for database '" . $this->db_name . "'" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
			$this->compress_write( $ret );
		}
		$stmt = $this->type_adapter->show_create_function( $function_name );
		foreach ( $this->db_handler->query( $stmt ) as $r ) {
			$this->compress_write( $this->type_adapter->create_function( $r ) );
			break;
		}
	}

	/**
	 * Event structure extractor
	 *
	 * @param string $event_name Name of event to export.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function get_event_structure( string $event_name ) {
		if ( ! $this->dump_settings['skip-comments'] ) {
			$ret = '--' . PHP_EOL . "-- Dumping events for database '" . $this->db_name . "'" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
			$this->compress_write( $ret );
		}
		$stmt = $this->type_adapter->show_create_event( $event_name );
		foreach ( $this->db_handler->query( $stmt ) as $r ) {
			$this->compress_write( $this->type_adapter->create_event( $r ) );
			break;
		}
	}

	/**
	 * Prepare values for output
	 *
	 * @param string $table_name Name of table which contains rows.
	 * @param array  $row Associative array of column names and values to be quoted.
	 * @return array
	 */
	private function prepare_column_values( string $table_name, array $row ): array {
		$ret          = array();
		$column_types = $this->table_column_types[ $table_name ];

		foreach ( $row as $col_name => $col_value ) {
			$ret[] = $this->escape( $col_value, $column_types[ $col_name ] );
		}
		return $ret;
	}

	/**
	 * Escape values with quotes when needed.
	 *
	 * @param string|null $col_value Name of column value.
	 * @param array       $col_type Associative array of column type.
	 * @return string
	 */
	private function escape( ?string $col_value, array $col_type ): string {
		if ( is_null( $col_value ) ) {
			return 'NULL';
		} elseif ( $this->dump_settings['hex-blob'] && $col_type['is_blob'] ) {

			if ( 'bit' === $col_type['type'] || ! empty( $col_value ) ) {
				return "0x$col_value";
			} else {
				return "''";
			}
		} elseif ( $col_type['is_numeric'] ) {
			return $col_value;
		}

		return $this->db_handler->quote( $col_value );
	}

	/**
	 * Table rows extractor
	 *
	 * @param string $table_name Name of table to export.
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	private function list_values( string $table_name ) {

		$this->prepare_list_values( $table_name );

		$only_once = true;
		$line_size = 0;
		$col_names = array();

		$col_stmt = $this->get_column_stmt( $table_name );

		if ( $this->dump_settings['complete-insert'] ) {
			$col_names = $this->get_column_names( $table_name );
		}

		$stmt = 'SELECT ' . implode( ',', $col_stmt ) . " FROM `$table_name`";

		$condition = $this->get_table_where( $table_name );

		if ( $condition ) {
			$stmt .= " WHERE $condition";
		}

		$limit = $this->get_table_limit( $table_name );
		if ( false !== $limit ) {
			$stmt .= sprintf( ' LIMIT %s', $limit );
		}

		$result_set = $this->db_handler->query( $stmt );
		$result_set->setFetchMode( PDO::FETCH_ASSOC ); // phpcs:ignore

		$ignore = $this->dump_settings['insert-ignore'] ? '  IGNORE' : '';

		$count = 0;
		foreach ( $result_set as $row ) {
			$count ++;
			$vals = $this->prepare_column_values( $table_name, $row );
			if ( $only_once || ! $this->dump_settings['extended-insert'] ) {
				if ( $this->dump_settings['complete-insert'] ) {
					$line_size += $this->compress_write( "INSERT$ignore INTO `$table_name` (" . implode( ', ', $col_names ) . ') VALUES (' . implode( ',', $vals ) . ')' );
				} else {
					$line_size += $this->compress_write(
						"INSERT$ignore INTO `$table_name` VALUES (" . implode( ',', $vals ) . ')'
					);
				}
				$only_once = false;
			} else {
				$line_size += $this->compress_write( ',(' . implode( ',', $vals ) . ')' );
			}

			if ( ( $line_size > $this->dump_settings['net_buffer_length'] ) || ! $this->dump_settings['extended-insert'] ) {
				$only_once = true;
				$line_size = $this->compress_write( ';' . PHP_EOL );
			}
		}
		$result_set->closeCursor();

		if ( ! $only_once ) {
			$this->compress_write( ';' . PHP_EOL );
		}

		$this->end_list_values( $table_name, $count );
	}

	/**
	 * Table rows extractor, append information prior to dump
	 *
	 * @param string $table_name Name of table to export.
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	public function prepare_list_values( string $table_name ) {
		if ( ! $this->dump_settings['skip-comments'] ) {
			$this->compress_write( '--' . PHP_EOL . "-- Dumping data for table `$table_name`" . PHP_EOL . '--' . PHP_EOL . PHP_EOL );
		}

		if ( $this->dump_settings['single-transaction'] ) {
			$this->db_handler->exec( $this->type_adapter->setup_transaction() );
			$this->db_handler->exec( $this->type_adapter->start_transaction() );
		}

		if ( $this->dump_settings['lock-tables'] && ! $this->dump_settings['single-transaction'] ) {
			$this->type_adapter->lock_table( $table_name );
		}

		if ( $this->dump_settings['add-locks'] ) {
			$this->compress_write(
				$this->type_adapter->start_add_lock_table( $table_name )
			);
		}

		if ( $this->dump_settings['disable-keys'] ) {
			$this->compress_write( $this->type_adapter->start_add_disable_keys( $table_name ) );
		}

		/**
		 * Disable autocommit for faster reload
		 */
		if ( $this->dump_settings['no-autocommit'] ) {
			$this->compress_write( $this->type_adapter->start_disable_autocommit() );
		}
	}

	/**
	 * Table rows extractor, close locks and commits after dump
	 *
	 * @param string $table_name Name of table to export.
	 * @param int    $count Number of rows inserted.
	 *
	 * @return void
	 * @throws Exception Standart Exception returned.
	 */
	public function end_list_values( string $table_name, int $count = 0 ) {
		if ( $this->dump_settings['disable-keys'] ) {
			$this->compress_write(
				$this->type_adapter->end_add_disable_keys( $table_name )
			);
		}

		if ( $this->dump_settings['add-locks'] ) {
			$this->compress_write(
				$this->type_adapter->end_add_lock_table()
			);
		}

		if ( $this->dump_settings['single-transaction'] ) {
			$this->db_handler->exec( $this->type_adapter->commit_transaction() );
		}

		if ( $this->dump_settings['lock-tables'] && ! $this->dump_settings['single-transaction'] ) {
			$this->type_adapter->unlock_table();
		}

		/**
		 * Commit to enable autocommit
		 */
		if ( $this->dump_settings['no-autocommit'] ) {
			$this->compress_write(
				$this->type_adapter->end_disable_autocommit()
			);
		}

		$this->compress_write( PHP_EOL );
		if ( ! $this->dump_settings['skip-comments'] ) {
			$this->compress_write( '-- Dumped table `' . $table_name . "` WITH $count row(s)" . PHP_EOL . '--' . PHP_EOL . PHP_EOL );
		}
	}

	/**
	 * Build SQL List of all columns on current table which will be used for selecting
	 *
	 * @param string $table_name Name of table to get columns.
	 * @return array SQL sentenced with columns for select.
	 */
	public function get_column_stmt( string $table_name ): array {
		$col_stmt = array();
		foreach ( $this->table_column_types[ $table_name ] as $col_name => $col_type ) {

			if ( 'bit' === $col_type['type'] && $this->dump_settings['hex-blob'] ) {
				$col_stmt[] = "LPAD(HEX(`$col_name`),2,'0') AS `$col_name`";
			} elseif ( $col_type['is_blob'] && $this->dump_settings['hex-blob'] ) {
				$col_stmt[] = "HEX(`$col_name`) AS `$col_name`";
			} elseif ( $col_type['is_virtual'] ) {
				$this->dump_settings['complete-insert'] = true;
			} else {
				$col_stmt[] = "`$col_name`";
			}
		}

		return $col_stmt;
	}

	/**
	 * Build SQL List of all columns on current table which will be used for inserting
	 *
	 * @param string $table_name Name of table to get columns.
	 * @return array columns for sql sentence for insert.
	 */
	public function get_column_names( string $table_name ): array {
		$col_names = array();
		foreach ( $this->table_column_types[ $table_name ] as $col_name => $col_type ) {

			if ( $col_type['is_virtual'] ) {
				$this->dump_settings['complete-insert'] = true;
			} else {
				$col_names[] = "`$col_name`";
			}
		}

		return $col_names;
	}

	/**
	 * Open backup DB file
	 *
	 * @param string $file_name DB File name.
	 * @return bool
	 * @throws Exception Standart Exception returned.
	 */
	public function compress_open( string $file_name ): bool {
		$this->file_handler = fopen( $file_name, 'wb' ); // phpcs:ignore
		if ( false === $this->file_handler ) {
			throw new Exception( 'Output file is not writable' );
		}

		return true;
	}

	/**
	 * Write method
	 *
	 * @param string $str String to write.
	 * @return int
	 * @throws Exception Standart Exception returned.
	 */
	public function compress_write( string $str ): int {

		if ( ! is_null( $this->file_handler ) ) {

			$bytes_written = fwrite( $this->file_handler, $str ); // phpcs:ignore
			if ( false === $bytes_written ) {
				throw new Exception( 'Writting to file failed! Probably, there is no more free space left?' );
			}

			return $bytes_written;
		} else {
			return 0;
		}
	}

	/**
	 * Close method.
	 *
	 * @return bool
	 */
	public function compress_close(): bool {
		if ( ! is_null( $this->file_handler ) ) {
			return fclose( $this->file_handler ); // phpcs:ignore
		} else {
			return false;
		}
	}
}

