<?php
/**
 * Class PclTypeAdapterMysql.
 *
 * @file class-pcltypeadaptermysql.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

use Exception;
use PDO;

/**
 * Class PclTypeAdapterMysql.
 *
 * @noinspection PhpUnused
 */
class PclTypeAdapterMysql {

	/**
	 * DB Handler.
	 *
	 * @var mixed|null $db_handler Database handler.
	 */
	protected $db_handler = null;

	/**
	 * Array of settings to dump.
	 *
	 * @var array $dump_settings Dump settings variable.
	 */
	protected $dump_settings = array();

	/**
	 * Definder.
	 *
	 * @var string DEFINER_RE
	 */
	const DEFINER_RE = 'DEFINER=`(?:[^`]|``)*`@`(?:[^`]|``)*`';

	/**
	 * Mysql Types.
	 *
	 * @var array $mysql_types Numerical Mysql types.
	 */
	public $mysql_types = array(
		'numerical' => array(
			'bit',
			'tinyint',
			'smallint',
			'mediumint',
			'int',
			'integer',
			'bigint',
			'real',
			'double',
			'float',
			'decimal',
			'numeric',
		),
		'blob'      => array(
			'tinyblob',
			'blob',
			'mediumblob',
			'longblob',
			'binary',
			'varbinary',
			'bit',
			'geometry',
			'point',
			'linestring',
			'polygon',
			'multipoint',
			'multilinestring',
			'multipolygon',
			'geometrycollection',
		),
	);

	/**
	 * Class constructor.
	 *
	 * @param PDO|null   $db_handler DB Handler.
	 * @param array|null $dump_settings Dump settings.
	 */
	public function __construct( ?PDO $db_handler = null, ?array $dump_settings = array() ) {
		$this->db_handler    = $db_handler;
		$this->dump_settings = $dump_settings;
	}

	/**
	 * Create string.
	 *
	 * @param PDO|null   $db_handler Database Handler.
	 * @param array|null $dump_settings Array of settings to dump.
	 *
	 * @throws Exception Standart Exception returned.
	 */
	public static function create( ?PDO $db_handler = null, ?array $dump_settings = array() ): PclTypeAdapterMysql {
		return new PclTypeAdapterMysql( $db_handler, $dump_settings );
	}

	/**
	 * Databases.
	 *
	 * @param string $db_name Database name.
	 * @return string
	 */
	public function databases( string $db_name ): string {
		$result_set    = $this->db_handler->query( "SHOW VARIABLES LIKE 'character_set_database';" );
		$character_set = $result_set->fetchColumn( 1 );
		$result_set->closeCursor();

		$result_set   = $this->db_handler->query( "SHOW VARIABLES LIKE 'collation_database';" );
		$collation_db = $result_set->fetchColumn( 1 );
		$result_set->closeCursor();

		$result  = "CREATE DATABASE /*!32312 IF NOT EXISTS*/ `$db_name`";
		$result .= " /*!40100 DEFAULT CHARACTER SET $character_set ";
		$result .= " COLLATE $collation_db */;" . PHP_EOL . PHP_EOL;
		$result .= "USE `$db_name`;" . PHP_EOL . PHP_EOL;

		return $result;
	}

	/**
	 * Show create table.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function show_create_table( string $table_name ): string {
		return "SHOW CREATE TABLE `$table_name`";
	}

	/**
	 * Show create view.
	 *
	 * @param string $view_name View name.
	 * @return string
	 */
	public function show_create_view( string $view_name ): string {
		return "SHOW CREATE VIEW `$view_name`";
	}

	/**
	 * Show create trigger.
	 *
	 * @param string $trigger_name Trigger name.
	 * @return string
	 */
	public function show_create_trigger( string $trigger_name ): string {
		return "SHOW CREATE TRIGGER `$trigger_name`";
	}

	/**
	 * Show create procedure.
	 *
	 * @param string $procedure_name Procedure name.
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function show_create_procedure( string $procedure_name ): string {
		return "SHOW CREATE PROCEDURE `$procedure_name`";
	}

	/**
	 * Show create function.
	 *
	 * @param string $function_name Function name.
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function show_create_function( string $function_name ): string {
		return "SHOW CREATE FUNCTION `$function_name`";
	}

	/**
	 * Show create event.
	 *
	 * @param string $event_name Event name.
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function show_create_event( string $event_name ): string {
		return "SHOW CREATE EVENT `$event_name`";
	}

	/**
	 * Create table.
	 *
	 * @param array $row Row.
	 * @throws Exception Standart Exception returned.
	 */
	public function create_table( array $row ): string {
		if ( ! isset( $row['Create Table'] ) ) {
			throw new Exception( 'Error getting table code, unknown output' );
		}

		$create_table = $row['Create Table'];
		if ( $this->dump_settings['reset-auto-increment'] ) {
			$match        = '/AUTO_INCREMENT=\d+/';
			$replace      = '';
			$create_table = preg_replace( $match, $replace, $create_table );
		}

		$ret  = '/*!40101 SET @saved_cs_client = @@character_set_client */;' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_client = ' . $this->dump_settings['default-character-set'] . ' */;' . PHP_EOL;
		$ret .= $create_table . ';' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_client = @saved_cs_client */;' . PHP_EOL . PHP_EOL;

		return $ret;
	}

	/**
	 * Create view.
	 *
	 * @param array $row Row.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function create_view( array $row ): string {

		if ( ! isset( $row['Create View'] ) ) {
			throw new Exception( 'Error getting view structure, unknown output' );
		}

		$view_stmt = $row['Create View'];

		$definer_str = $this->dump_settings['skip-definer'] ? '' : '/*!50013 \2 */' . PHP_EOL;

		$view_stmt_replaced = preg_replace(
			'/^(CREATE(?:\s+ALGORITHM=(?:UNDEFINED|MERGE|TEMPTABLE))?)\s+('
			. self::DEFINER_RE . '(?:\s+SQL SECURITY DEFINER|INVOKER)?)?\s+(VIEW .+)$/',
			'/*!50001 \1 */' . PHP_EOL . $definer_str . '/*!50001 \3 */',
			$view_stmt,
			1
		);

		if ( false !== $view_stmt_replaced ) {
			$view_stmt = $view_stmt_replaced;
		}

		return $view_stmt . ';' . PHP_EOL . PHP_EOL;
	}

	/**
	 * Create trigger.
	 *
	 * @param array $row Row.
	 *
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function create_trigger( array $row ): string {

		if ( ! isset( $row['SQL Original Statement'] ) ) {
			throw new Exception( 'Error getting trigger code, unknown output' );
		}

		$trigger_stmt = $row['SQL Original Statement'];
		$definer_str  = $this->dump_settings['skip-definer'] ? '' : '/*!50017 \2*/ ';

		$trigger_stmt_replaced = preg_replace(
			'/^(CREATE)\s+(' . self::DEFINER_RE . ')?\s+(TRIGGER\s.*)$/s',
			'/*!50003 \1*/ ' . $definer_str . '/*!50003 \3 */',
			$trigger_stmt,
			1
		);

		if ( false !== $trigger_stmt_replaced ) {
			$trigger_stmt = $trigger_stmt_replaced;
		}

		return 'DELIMITER ;;' . PHP_EOL . $trigger_stmt . ';;' . PHP_EOL . 'DELIMITER ;' . PHP_EOL . PHP_EOL;
	}

	/**
	 * Create procedure.
	 *
	 * @param array $row Row.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function create_procedure( array $row ): string {

		$ret = '';
		if ( ! isset( $row['Create Procedure'] ) ) {
			throw new Exception( 'Error getting procedure code, unknown output. Please check: https://bugs.mysql.com/bug.php?id=14564' );
		}
		$procedure_stmt = $row['Create Procedure'];
		if ( $this->dump_settings['skip-definer'] ) {
			$procedure_stmt_replaced = preg_replace(
				'/^(CREATE)\s+(' . self::DEFINER_RE . ')?\s+(PROCEDURE\s.*)$/s',
				'\1 \3',
				$procedure_stmt,
				1
			);
			if ( false !== $procedure_stmt_replaced ) {
				$procedure_stmt = $procedure_stmt_replaced;
			}
		}

		$ret .= '/*!50003 DROP PROCEDURE IF EXISTS `';
		$ret .= $row['Procedure'] . '` */;' . PHP_EOL;
		$ret .= '/*!40101 SET @saved_cs_client     = @@character_set_client */;' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_client = ' . $this->dump_settings['default-character-set'] . ' */;' . PHP_EOL;
		$ret .= 'DELIMITER ;;' . PHP_EOL;
		$ret .= $procedure_stmt . ' ;;' . PHP_EOL;
		$ret .= 'DELIMITER ;' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_client = @saved_cs_client */;' . PHP_EOL . PHP_EOL;

		return $ret;
	}

	/**
	 * Create function.
	 *
	 * @param array $row Row.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function create_function( array $row ): string {

		$ret = '';
		if ( ! isset( $row['Create Function'] ) ) {
			throw new Exception( 'Error getting function code, unknown output. Please check: https://bugs.mysql.com/bug.php?id=14564' );
		}
		$function_stmt        = $row['Create Function'];
		$character_set_client = $row['character_set_client'];
		$collation_connection = $row['collation_connection'];
		$sql_mode             = $row['sql_mode'];
		if ( $this->dump_settings['skip-definer'] ) {

			$function_stmt_replaced = preg_replace(
				'/^(CREATE)\s+(' . self::DEFINER_RE . ')?\s+(FUNCTION\s.*)$/s',
				'\1 \3',
				$function_stmt,
				1
			);

			if ( false !== $function_stmt_replaced ) {
				$function_stmt = $function_stmt_replaced;
			}
		}

		$ret .= '/*!50003 DROP FUNCTION IF EXISTS `';
		$ret .= $row['Function'] . '` */;' . PHP_EOL;
		$ret .= '/*!40101 SET @saved_cs_client     = @@character_set_client */;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_cs_results     = @@character_set_results */ ;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_col_connection = @@collation_connection */ ;' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_client = ' . $character_set_client . ' */;' . PHP_EOL;
		$ret .= '/*!40101 SET character_set_results = ' . $character_set_client . ' */;' . PHP_EOL;
		$ret .= '/*!50003 SET collation_connection  = ' . $collation_connection . ' */ ;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;;' . PHP_EOL;
		$ret .= "/*!50003 SET sql_mode              = '" . $sql_mode . "' */ ;;" . PHP_EOL;
		$ret .= '/*!50003 SET @saved_time_zone      = @@time_zone */ ;;' . PHP_EOL;
		$ret .= "/*!50003 SET time_zone             = 'SYSTEM' */ ;;" . PHP_EOL;
		$ret .= 'DELIMITER ;;' . PHP_EOL;
		$ret .= $function_stmt . ' ;;' . PHP_EOL;
		$ret .= 'DELIMITER ;' . PHP_EOL;
		$ret .= '/*!50003 SET sql_mode              = @saved_sql_mode */ ;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_client  = @saved_cs_client */ ;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_results = @saved_cs_results */ ;' . PHP_EOL;
		$ret .= '/*!50003 SET collation_connection  = @saved_col_connection */ ;' . PHP_EOL;
		$ret .= '/*!50106 SET TIME_ZONE= @saved_time_zone */ ;' . PHP_EOL . PHP_EOL;

		return $ret;
	}

	/**
	 * Create event.
	 *
	 * @param array $row Row.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 * @noinspection PhpUnused
	 */
	public function create_event( array $row ): string {
		$ret = '';
		if ( ! isset( $row['Create Event'] ) ) {
			throw new Exception( 'Error getting event code, unknown output. Please check: https://stackoverflow.com/questions/10853826/mysql-5-5-create-event-gives-syntax-error' );
		}

		$event_name  = $row['Event'];
		$event_stmt  = $row['Create Event'];
		$sql_mode    = $row['sql_mode'];
		$definer_str = $this->dump_settings['skip-definer'] ? '' : '/*!50117 \2*/ ';

		$event_stmt_replaced = preg_replace(
			'/^(CREATE)\s+(' . self::DEFINER_RE . ')?\s+(EVENT .*)$/',
			'/*!50106 \1*/ ' . $definer_str . '/*!50106 \3 */',
			$event_stmt,
			1
		);

		if ( false !== $event_stmt_replaced ) {
			$event_stmt = $event_stmt_replaced;
		}

		$ret .= '/*!50106 SET @save_time_zone= @@TIME_ZONE */ ;' . PHP_EOL;
		$ret .= '/*!50106 DROP EVENT IF EXISTS `' . $event_name . '` */;' . PHP_EOL;
		$ret .= 'DELIMITER ;;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_cs_client      = @@character_set_client */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_cs_results     = @@character_set_results */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_col_connection = @@collation_connection */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_client  = utf8 */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_results = utf8 */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET collation_connection  = utf8_general_ci */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;;' . PHP_EOL;
		$ret .= "/*!50003 SET sql_mode              = '" . $sql_mode . "' */ ;;" . PHP_EOL;
		$ret .= '/*!50003 SET @saved_time_zone      = @@time_zone */ ;;' . PHP_EOL;
		$ret .= "/*!50003 SET time_zone             = 'SYSTEM' */ ;;" . PHP_EOL;
		$ret .= $event_stmt . ' ;;' . PHP_EOL;
		$ret .= '/*!50003 SET time_zone             = @saved_time_zone */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET sql_mode              = @saved_sql_mode */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_client  = @saved_cs_client */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET character_set_results = @saved_cs_results */ ;;' . PHP_EOL;
		$ret .= '/*!50003 SET collation_connection  = @saved_col_connection */ ;;' . PHP_EOL;
		$ret .= 'DELIMITER ;' . PHP_EOL;
		$ret .= '/*!50106 SET TIME_ZONE= @save_time_zone */ ;' . PHP_EOL . PHP_EOL;

		return $ret;
	}

	/**
	 * Show tables.
	 *
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function show_tables(): string {
		$args    = func_get_args();
		$result  = 'SELECT TABLE_NAME AS tbl_name ';
		$result .= 'FROM INFORMATION_SCHEMA.TABLES ';
		$result .= "WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='${args[0]}'";

		return $result;
	}

	/**
	 * Show views.
	 *
	 * @param string $tbl_name Table name.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function show_views( string $tbl_name ): string {
		$result  = 'SELECT TABLE_NAME AS tbl_name ';
		$result .= 'FROM INFORMATION_SCHEMA.TABLES ';
		$result .= "WHERE TABLE_TYPE='VIEW' AND TABLE_SCHEMA='$tbl_name'";
		return $result;
	}

	/**
	 * Show triggers.
	 *
	 * @param string $db_name Database name.
	 * @return string
	 */
	public function show_triggers( string $db_name ): string {
		return "SHOW TRIGGERS FROM `$db_name`;";
	}

	/**
	 * Show columns.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function show_columns( string $table_name ): string {
		return "SHOW COLUMNS FROM `$table_name`;";
	}

	/**
	 * Show procedures.
	 *
	 * @param string $db_name Database name.
	 * @return string
	 */
	public function show_procedures( string $db_name ): string {
		$result  = 'SELECT SPECIFIC_NAME AS procedure_name ';
		$result .= 'FROM INFORMATION_SCHEMA.ROUTINES ';
		$result .= "WHERE ROUTINE_TYPE='PROCEDURE' AND ROUTINE_SCHEMA='$db_name'";
		return $result;
	}

	/**
	 * Show functions.
	 *
	 * @param string $db_name Database name.
	 * @return string
	 */
	public function show_functions( string $db_name ): string {
		$result  = 'SELECT SPECIFIC_NAME AS function_name ';
		$result .= 'FROM INFORMATION_SCHEMA.ROUTINES ';
		$result .= "WHERE ROUTINE_TYPE='FUNCTION' AND ROUTINE_SCHEMA='$db_name'";
		return $result;
	}

	/**
	 * Get query string to ask for names of events from current database.
	 *
	 * @param string $db_name Name of database.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function show_events( string $db_name ): string {
		$result  = 'SELECT EVENT_NAME AS event_name ';
		$result .= "FROM INFORMATION_SCHEMA.EVENTS WHERE EVENT_SCHEMA='$db_name'";
		return $result;
	}

	/**
	 * Setup transaction.
	 *
	 * @return string
	 */
	public function setup_transaction(): string {
		return 'SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ';
	}

	/**
	 * Start transaction.
	 *
	 * @return string
	 */
	public function start_transaction(): string {
		return 'START TRANSACTION /*!40100 WITH CONSISTENT SNAPSHOT */';
	}


	/**
	 * Commit transaction.
	 *
	 * @return string
	 */
	public function commit_transaction(): string {
		return 'COMMIT';
	}

	/**
	 * Lock table.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function lock_table( string $table_name ): string {
		$this->db_handler->exec( "LOCK TABLES `$table_name` READ LOCAL" );
		return '';
	}

	/**
	 * Unlock table.
	 *
	 * @return string
	 */
	public function unlock_table(): string {
		$this->db_handler->exec( 'UNLOCK TABLES' );
		return '';
	}

	/**
	 * Start add lock table.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function start_add_lock_table( string $table_name ): string {
		return "LOCK TABLES `$table_name` WRITE;" . PHP_EOL;
	}

	/**
	 * End add lock table.
	 *
	 * @return string
	 */
	public function end_add_lock_table(): string {
		return 'UNLOCK TABLES;' . PHP_EOL;
	}

	/**
	 * Start add disable keys.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function start_add_disable_keys( string $table_name ): string {
		return "/*!40000 ALTER TABLE `$table_name` DISABLE KEYS */;" . PHP_EOL;
	}

	/**
	 * End add disable keys.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 * @throws Exception Standart Exception returned.
	 */
	public function end_add_disable_keys( string $table_name ): string {
		return "/*!40000 ALTER TABLE `$table_name` ENABLE KEYS */;" . PHP_EOL;
	}

	/**
	 * Start disable autocommit.
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function start_disable_autocommit(): string {
		return 'SET autocommit=0;' . PHP_EOL;
	}

	/**
	 * End disable autocommit.
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function end_disable_autocommit(): string {
		return 'COMMIT;' . PHP_EOL;
	}

	/**
	 * Add drop database.
	 *
	 * @param string $db_name DB name.
	 * @return string
	 */
	public function add_drop_database( string $db_name ): string {
		return "/*!40000 DROP DATABASE IF EXISTS `$db_name`*/;" . PHP_EOL . PHP_EOL;
	}

	/**
	 * Add drop trigger.
	 *
	 * @param string $trigger_name Trigger name.
	 * @return string
	 */
	public function add_drop_trigger( string $trigger_name ): string {
		return "DROP TRIGGER IF EXISTS `$trigger_name`;" . PHP_EOL;
	}

	/**
	 * Drop table.
	 *
	 * @param string $table_name Table name.
	 * @return string
	 */
	public function drop_table( string $table_name ): string {
		return "DROP TABLE IF EXISTS `$table_name`;" . PHP_EOL;
	}

	/**
	 * Drop view.
	 *
	 * @param string $view_name View name.
	 * @return string
	 */
	public function drop_view( string $view_name ): string {
		return "DROP TABLE IF EXISTS `$view_name`;" . PHP_EOL . "/*!50001 DROP VIEW IF EXISTS `$view_name`*/;" . PHP_EOL;
	}

	/**
	 * Get Database Header.
	 *
	 * @param string $db_name Database name.
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function get_database_header( string $db_name ): string {
		return '--' . PHP_EOL . "-- Current Database: `$db_name`" . PHP_EOL . '--' . PHP_EOL . PHP_EOL;
	}

	/**
	 * Decode column metadata and fill info structure.
	 * type, is_numeric and is_blob will always be available.
	 *
	 * @param array $col_type Array returned from "SHOW COLUMNS FROM tableName".
	 * @return array
	 */
	public function parse_column_type( array $col_type ): array {
		$col_info  = array();
		$col_parts = explode( ' ', $col_type['Type'] );

		$fparen = strpos( $col_parts[0], '(' );

		if ( false !== $fparen ) {
			$col_info['type']       = substr( $col_parts[0], 0, $fparen );
			$col_info['length']     = str_replace( ')', '', substr( $col_parts[0], $fparen + 1 ) );
			$col_info['attributes'] = $col_parts[1] ?? null;
		} else {
			$col_info['type'] = $col_parts[0];
		}
		$col_info['is_numeric'] = in_array( $col_info['type'], $this->mysql_types['numerical'], true );
		$col_info['is_blob']    = in_array( $col_info['type'], $this->mysql_types['blob'], true );
		$col_info['is_virtual'] = strpos( $col_type['Extra'], 'VIRTUAL GENERATED' ) !== false || strpos( $col_type['Extra'], 'STORED GENERATED' ) !== false;

		return $col_info;
	}

	/**
	 * Backup parameters.
	 *
	 * @return string
	 */
	public function backup_parameters(): string {

		$ret  = '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;' . PHP_EOL;
		$ret .= '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;' . PHP_EOL;
		$ret .= '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;' . PHP_EOL;
		$ret .= '/*!40101 SET NAMES ' . $this->dump_settings['default-character-set'] . ' */;' . PHP_EOL;

		if ( false === $this->dump_settings['skip-tz-utc'] ) {
			$ret .= '/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;' . PHP_EOL . "/*!40103 SET TIME_ZONE='+00:00' */;" . PHP_EOL;
		}

		if ( $this->dump_settings['no-autocommit'] ) {
			$ret .= '/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;' . PHP_EOL;
		}

		$ret .= '/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;' . PHP_EOL;
		$ret .= '/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;' . PHP_EOL;
		$ret .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;" . PHP_EOL;
		$ret .= '/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;' . PHP_EOL . PHP_EOL;

		return $ret;
	}

	/**
	 * Restore parameters.
	 *
	 * @return string
	 */
	public function restore_parameters(): string {

		$ret = '';
		if ( false === $this->dump_settings['skip-tz-utc'] ) {
			$ret .= '/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;' . PHP_EOL;
		}

		if ( $this->dump_settings['no-autocommit'] ) {
			$ret .= '/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;' . PHP_EOL;
		}

		$ret .= '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;' . PHP_EOL;
		$ret .= '/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;' . PHP_EOL;
		$ret .= '/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;' . PHP_EOL;
		$ret .= '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;' . PHP_EOL;
		$ret .= '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;' . PHP_EOL;
		$ret .= '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;' . PHP_EOL;
		$ret .= '/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;' . PHP_EOL . PHP_EOL;

		return $ret;
	}
}
