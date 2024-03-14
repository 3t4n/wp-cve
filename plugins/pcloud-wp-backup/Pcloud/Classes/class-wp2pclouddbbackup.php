<?php
/**
 * WP2PcloudDBBackup class
 *
 * @file class-wp2pclouddbbackup.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

use Exception;

/**
 * Class WP2PcloudDBBackup
 */
class WP2PcloudDBBackup {

	/**
	 * Save file location
	 *
	 * @var string $save_file
	 */
	private $save_file;

	/**
	 * Class constructor
	 */
	public function __construct() {

		if ( PCLOUD_DEBUG && ! defined( 'WP_DEBUG_DISPLAY' ) ) {
			define( 'WP_DEBUG_DISPLAY', true );
		}

		$this->save_file = tempnam( sys_get_temp_dir(), 'sqlarchive' );
	}

	/**
	 * Initiates Database backup
	 *
	 * @throws Exception Throws standart Exception.
	 */
	public function start() {

		wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='start_db_backup'>Starting Database Backup</span>" );
		wp2pclouddebugger::log( 'db_backup->start()' );

		$dump_settings = array(
			'exclude-tables'     => array(),
			'compress'           => PclMysqlDump::NONE,
			'no-data'            => false,
			'add-drop-table'     => true,
			'single-transaction' => true,
			'lock-tables'        => false,
			'add-locks'          => false,
			'extended-insert'    => false,
			'disable-keys'       => true,
			'skip-triggers'      => false,
			'add-drop-trigger'   => true,
			'routines'           => true,
			'databases'          => false,
			'add-drop-database'  => false,
			'hex-blob'           => true,
			'no-create-info'     => false,
			'no-autocommit'      => false,
		);

		$dump = new PclMysqlDump( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, $dump_settings );

		try {
			$dump->start( $this->save_file );

			wp2pclouddebugger::log( 'db_backup->start() - process succeeded!' );
			wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='db_backup_finished'>Database Backup Finished</span>" );

		} catch ( Exception $e ) {
			$msg = $e->getMessage();

			wp2pclouddebugger::log( 'db_backup->start() - Failed:' . $msg );
			wp2pcloudlogger::info( '<span>Plugin error: ' . $msg . '</span>' );
		}

		return $this->save_file;
	}
}
