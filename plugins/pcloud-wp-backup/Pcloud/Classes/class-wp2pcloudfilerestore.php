<?php
/**
 * WP2PcloudFileRestore class
 *
 * @file class-wp2pcloudfilerestore.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

use PDO;
use PDOException;
use ZipArchive;

/**
 * Class WP2PcloudFileRestore
 */
class WP2PcloudFileRestore {

	/**
	 * Path where the files will be restored
	 *
	 * @var string $restore_path
	 */
	private $restore_path;

	/**
	 * Class constructor
	 */
	public function __construct() {

		$this->restore_path = PCLOUD_TEMP_DIR . '/';

		if ( ! is_dir( $this->restore_path ) ) {
			mkdir( $this->restore_path );
		}

		if ( ! is_dir( $this->restore_path ) ) {
			wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='err_temp_folder_fail2mk'>ERROR: Temporary folder can not be created!</span> [" . $this->restore_path . ']' );
			wp2pclouddebugger::log( 'Failed to create Temporary folder!' );
			wp2pcloudfuncs::set_operation();
			die();
		}

		WP_Filesystem();
	}

	/**
	 * Download files by chunks
	 *
	 * @param string $url Final URL from which the backup file will be downloaded.
	 * @param int    $offset Offset of the downloaded file.
	 * @param string $archive_file Output archive file.
	 *
	 * @return int
	 */
	public function download_chunk_curl( string $url, int $offset = 0, string $archive_file = 'tmp.zip' ) {

		$chunksize = 2 * ( 1000 * 1000 ); // 2 MB

		$errstr = '';

		$args = array(
			'headers' => array(
				'Range' => 'bytes=' . $offset . '-' . ( $offset + ( $chunksize - 1 ) ),
			),
		);

		$content      = false;
		$api_response = wp_remote_get( $url, $args );
		if ( is_array( $api_response ) && ! is_wp_error( $api_response ) ) {
			$response_raw = wp_remote_retrieve_body( $api_response );
			if ( ! is_wp_error( $response_raw ) ) {
				$content = $response_raw;
			}
		} else {
			$errstr = $api_response->get_error_message();
		}

		if ( ! $content ) {

			wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='err_failed2open_conn'>Failed to open connection to the backup file:</span> [url: $url] " . $errstr );
			wp2pclouddebugger::log( 'download_chunk_curl() - Failed to open connection to the backup file: [ url: ' . $url . ', err: ' . $errstr . ' ]' );

		} else {

			$o_handle = fopen( $archive_file, 'ab' ); // phpcs:ignore
			if ( ! $o_handle ) {

				wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='err_open_output_f'>Error opening the output file!</span>" );
				wp2pclouddebugger::log( 'download_chunk_curl() - Error opening the output file!' );

				return $offset;
			} else {
				fwrite( $o_handle, $content ); // phpcs:ignore
			}

			fclose( $o_handle ); // phpcs:ignore

			$offset += $chunksize;
		}

		return $offset;
	}

	/**
	 * Extract the archive
	 *
	 * @param string $archive_file Output archive file.
	 * @return void
	 */
	public function extract( string $archive_file ) {
		$zip = new ZipArchive();
		$res = $zip->open( $archive_file );
		if ( is_bool( $res ) && $res ) {
			for ( $i = 0; $i < $zip->{'numFiles'}; $i ++ ) {
				$zip->extractTo( $this->restore_path, array( $zip->getNameIndex( $i ) ) );
			}
			wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='zip_file_extracted'>ZIP file ( archive ), successfully extracted!</span>" );
			wp2pclouddebugger::log( 'restore->extract() - ZIP file ( archive ), successfully extracted!' );

		} else {

			wp2pcloudlogger::info( "<span>File: $archive_file</span>" );
			wp2pcloudlogger::info( "<span class='pcl_transl' data-i10nk='zip_file_extract_fail'>Failed to extract the archive, check the ZIP file for issues!</span>" );
			wp2pclouddebugger::log( 'restore->extract() - ZIP file ( archive ), successfully extracted!' );

			$zip_file_functions_errors = array(
				0                        => 'OK',
				ZIPARCHIVE::ER_EXISTS    => 'File already exists.',
				ZIPARCHIVE::ER_INCONS    => 'Zip archive inconsistent.',
				ZIPARCHIVE::ER_INVAL     => 'Invalid argument.',
				ZIPARCHIVE::ER_MEMORY    => 'Malloc failure.',
				ZIPARCHIVE::ER_NOENT     => 'No such file.',
				ZIPARCHIVE::ER_NOZIP     => 'Not a zip archive.',
				ZIPARCHIVE::ER_OPEN      => 'Can not open file.',
				ZIPARCHIVE::ER_READ      => 'Read error.',
				ZIPARCHIVE::ER_SEEK      => 'Seek error.',
				ZIPARCHIVE::ER_MULTIDISK => 'Multi-disk zip archives not supported.',
			);

			if ( isset( $zip_file_functions_errors[ $res ] ) ) {
				wp2pcloudlogger::info( '<span>ZIP Error: ' . $zip_file_functions_errors[ $res ] . '</span>' );
				wp2pclouddebugger::log( 'restore->extract() - ZIP Error:' . $zip_file_functions_errors[ $res ] );
			} else {
				wp2pclouddebugger::log( 'restore->extract() - ZIP seems OK' );
			}
		}
	}

	/**
	 * Restore the database archive
	 *
	 * @return void
	 */
	public function restore_db() {

		global $wp_filesystem;

		$sql = $this->restore_path . 'backup.sql';
		if ( ! is_file( $sql ) ) {

			wp2pclouddebugger::log( 'restore->restore_db() - Failed to restore Database, backup.sql not found in the archive!' );
			return;
		} else {
			wp2pclouddebugger::log( 'restore->restore_db() - SQL db file found!' );
		}

		$q_ex_num = 0;

		$db = null;

		try {

			$dns = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
			$db  = new PDO( // phpcs:ignore
				$dns,
				DB_USER,
				DB_PASSWORD,
				array(
					PDO::ATTR_EMULATE_PREPARES   => false, // phpcs:ignore
					PDO::ATTR_PERSISTENT         => false, // phpcs:ignore
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // phpcs:ignore
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // phpcs:ignore
				)
			);

		} catch ( PDOException $e ) {
			$dbg_ex = $e->getMessage();
			wp2pcloudlogger::info( 'Connection to Database failed with message: ' . $dbg_ex );
			wp2pclouddebugger::log( 'restore->restore_db() - Connection to Database failed with message: ' . $dbg_ex );
		}

		if ( ! is_null( $db ) ) {

			if ( ! empty( DB_COLLATE ) ) {

				wp2pcloudlogger::info( 'Database collation detected: ' . DB_COLLATE );

				try {
					$db->exec( 'SET NAMES ' . DB_COLLATE );
				} catch ( PDOException $e ) {
					$dbg_ex = $e->getMessage();
					wp2pclouddebugger::log( 'restore->restore_db() - Failed to change the Database collation! Error: ' . $dbg_ex );
					wp2pcloudlogger::info( 'Failed to change the Database collation! Error: ' . $dbg_ex );
				}
			}

			$full_sql_data = '';
			if ( $wp_filesystem->exists( $sql ) ) {
				$sql_data = $wp_filesystem->get_contents( $sql );
				if ( ! is_bool( $sql_data ) ) {
					$full_sql_data = $sql_data;
				}
			}

			$q = explode( ";\n", $full_sql_data );
			foreach ( $q as $v ) {
				$v = trim( $v );
				if ( empty( $v ) ) {
					continue;
				}
				try {
					$db->query( $v );
					$q_ex_num ++;
				} catch ( PDOException $e ) {
					$dbg_ex = $e->getMessage();
					$q      = substr( $full_sql_data, 0, 100 );
					wp2pclouddebugger::log( 'restore->restore_db() - Failed to execute database query: ' . $q . ' ! Error: ' . $dbg_ex );
					wp2pcloudlogger::info( 'Failed to execute database query: ' . $e->getMessage() );
				}
			}
		} else {
			wp2pclouddebugger::log( 'restore->restore_db() - Connection to Database failed!' );
			wp2pcloudlogger::info( 'Connection to Database failed!' );
		}

		if ( $q_ex_num > 3 ) {
			wp2pcloudlogger::info( "Database restored, $q_ex_num queries executed!" );
			wp2pclouddebugger::log( "restore->restore_db() - Database restored, $q_ex_num queries executed!" );
		} else {
			wp2pcloudlogger::info( 'Failed to restore Database, no queries executed, check the backup.sql file!' );
			wp2pclouddebugger::log( 'restore->restore_db() - Failed to restore Database, no queries executed, check the backup.sql file!' );
		}

		if ( $wp_filesystem->exists( $sql ) ) {
			unlink( $sql );
			wp2pclouddebugger::log( 'restore->restore_db() - backup.sql removed!' );
		} else {
			wp2pclouddebugger::log( 'restore->restore_db() - Failed to remove the backup.sql file!' );
		}
	}

	/**
	 * Restore the extracted files into the WP structure
	 *
	 * @param string $source_directory BackUp files source directory.
	 * @param string $destination_directory BackUp files destination directory.
	 * @param string $child_folder Child folders if any.
	 *
	 * @return void
	 */
	public function restore_files( string $source_directory, string $destination_directory, string $child_folder = '' ) {

		$skip = array( 'pcloud_tmp', '.idea', '.', '..' );

		if ( ! is_dir( $source_directory ) ) {
			wp2pclouddebugger::log( 'restore->restore_files() - source_directory does not exists! [ ' . $source_directory . ' ]' );
			return;
		}

		$all_files = scandir( $source_directory );
		if ( is_bool( $all_files ) ) {
			wp2pclouddebugger::log( 'restore->restore_files() - source_directory can not be scanned!' );
			return;
		}

		$files = array_diff( $all_files, $skip );

		if ( ! is_dir( $destination_directory ) ) {
			mkdir( $destination_directory );
		}

		if ( '' !== $child_folder ) {

			if ( ! is_dir( $destination_directory . DIRECTORY_SEPARATOR . $child_folder ) ) {
				mkdir( $destination_directory . DIRECTORY_SEPARATOR . $child_folder );
			}

			foreach ( $files as $file ) {

				if ( is_dir( $source_directory . DIRECTORY_SEPARATOR . $file ) === true ) {
					$this->restore_files(
						$source_directory . DIRECTORY_SEPARATOR . $file,
						$destination_directory . DIRECTORY_SEPARATOR . $child_folder . DIRECTORY_SEPARATOR . $file
					);
				} else {
					copy(
						$source_directory . DIRECTORY_SEPARATOR . $file,
						$destination_directory . DIRECTORY_SEPARATOR . $child_folder . DIRECTORY_SEPARATOR . $file
					);
				}
			}
		}

		foreach ( $files as $file ) {
			if ( is_dir( $source_directory . DIRECTORY_SEPARATOR . $file ) === true ) {
				$this->restore_files( $source_directory . DIRECTORY_SEPARATOR . $file, $destination_directory . DIRECTORY_SEPARATOR . $file );
			} else {
				copy( $source_directory . DIRECTORY_SEPARATOR . $file, $destination_directory . DIRECTORY_SEPARATOR . $file );
			}
		}
	}

	/**
	 * Remove files in directory
	 *
	 * @param string $archive_file Archive file to be removed.
	 * @return void
	 */
	public function remove_files( string $archive_file ) {
		unlink( $archive_file );
		wp2pclouddebugger::log( 'restore->remove_files() - Start!' );
		$this->recurse_rm_dir( $this->restore_path );
		wp2pclouddebugger::log( 'restore->remove_files() - Completed!' );
	}

	/**
	 * Recursive removal of directories.
	 *
	 * @param string $dir Directory name.
	 *
	 * @return void
	 */
	private function recurse_rm_dir( string $dir ) {

		$dir_cnt = scandir( $dir );
		if ( is_array( $dir_cnt ) ) {
			$files = array_diff( $dir_cnt, array( '.', '..' ) );
			foreach ( $files as $file ) {
				( is_dir( "$dir/$file" ) ) ? $this->recurse_rm_dir( "$dir/$file" ) : unlink( "$dir/$file" );
			}
		}
		rmdir( $dir );
	}
}
