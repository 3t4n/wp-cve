<?php

namespace Codemanas\Typesense\Helpers;

use Codemanas\Typesense\Backend\Admin;
use WpOrg\Requests\Exception;

class Logger {
	private string $logBaseDir = '';
	private string $logBaseDirName = '/cm-typesense/';
	private $settings = [];

	public function __construct() {
		$this->settings = Admin::get_default_settings();
		//any time
		$this->mayBeMakeBaseDir();
//		get_option('cm')
	}

	public function mayBeMakeBaseDir() {
		try {
			$uploads_folder = wp_get_upload_dir();
			if ( ! is_dir( $uploads_folder['basedir'] . $this->logBaseDirName ) ) {
				//Create our directory if it does not exist
				mkdir( $uploads_folder['basedir'] . $this->logBaseDirName );
				file_put_contents( $uploads_folder['basedir'] . $this->logBaseDirName . '.htaccess', 'deny from all' );
				file_put_contents( $uploads_folder['basedir'] . $this->logBaseDirName . 'index.html', '' );
			}
			$this->logBaseDir = $uploads_folder['basedir'] . '/cm-typesense/';
		} catch ( \Exception $e ) {
			echo esc_html( $e->getMessage() );
		}

	}

	/**
	 * @return string
	 */
	public function getFormattedNameForLog(): string {
		return gmdate( 'Y-m-d', strtotime( 'now' ) );
	}

	/**
	 * @param $logType
	 * @param $filename
	 *
	 * @return bool|void
	 */
	public function deleteFile( $logType, $filename ) {

		//verify that current user has the permisssions
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( $logType == 'error' ) {
			$logDir = $this->logBaseDir . 'error/';
		} elseif ( $logType == 'debug' ) {
			$logDir = $this->logBaseDir . 'debug/';
		} else {
			return false;
		}

		if ( file_exists( $logDir . $filename ) ) {
			return unlink( $logDir . $filename );
		}

		return false;
	}

	public function deleteAllFiles( $logType ) {
		//verify that current user has the permisssions
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( $logType == 'error' ) {
			$logDir = $this->logBaseDir . 'error/';
		} elseif ( $logType == 'debug' ) {
			$logDir = $this->logBaseDir . 'debug/';
		} else {
			return false;
		}

		$files = glob( $logDir . '*.txt' );


		try {
			if ( ! empty( $files ) ) {
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						unlink( $file );
					}
				}

				return true;
			}
		} catch ( \Exception $e ) {
			return false;
		}


		return false;
	}

	/**
	 * @param $data
	 */
	public function logError( $data ) {
		if ( ! $this->settings['error_log'] ) {
			return;
		}
		$fileName = $this->getFormattedNameForLog() . '.txt';
		if ( $this->logBaseDirName != '' && ! is_dir( $this->logBaseDir . 'error/' ) ) {
			mkdir( $this->logBaseDir . 'error/' );
			file_put_contents( $this->logBaseDir . 'error/' . '.htaccess', 'deny from all' );
			file_put_contents( $this->logBaseDir . 'error/' . 'index.html', '' );
		}
		$timestamp = gmdate( 'Y-m-d::H:i:s', strtotime( 'now' ) ) . '-UTC';
		$logData   = '=== START ====' . PHP_EOL . '::Time:: ' . $timestamp . PHP_EOL . var_export( $data, true ) . PHP_EOL . '===END===' . PHP_EOL . PHP_EOL;
		file_put_contents( $this->logBaseDir . 'error/' . $fileName, $logData, FILE_APPEND );
	}

	public function logDebugData( $data ) {
		if ( ! $this->settings['debug_log'] ) {
			return;
		}
		$fileName = $this->getFormattedNameForLog() . '.txt';
		if ( $this->logBaseDirName != '' && ! is_dir( $this->logBaseDir . 'debug/' ) ) {
			mkdir( $this->logBaseDir . 'debug/' );
			file_put_contents( $this->logBaseDir . 'debug/' . '.htaccess', 'deny from all' );
			file_put_contents( $this->logBaseDir . 'debug/' . 'index.html', '' );
		}
		$timestamp = gmdate( 'Y-m-d::H:i:s', strtotime( 'now' ) ) . '-UTC';
		$logData   = '=== START ====' . PHP_EOL . '::Time:: ' . $timestamp . PHP_EOL . var_export( $data, true ) . PHP_EOL . '===END===' . PHP_EOL . PHP_EOL;
		file_put_contents( $this->logBaseDir . 'debug/' . $fileName, $logData, FILE_APPEND );
	}

	public function readAllErrorLogFiles( $log_type ): array {
		$files     = ( $log_type == 'error' ) ? glob( $this->logBaseDir . 'error/*.txt' ) : glob( $this->logBaseDir . 'debug/*.txt' );
		$fileNames = [];
		if ( count( $files ) > 0 ) {
			foreach ( $files as $filePath ) {
				$strPosLength = strrpos( $filePath, '/' );
				$fileName     = substr( substr( $filePath, $strPosLength + 1 ), '0' );
				$fileNames[]  = [ 'name' => $fileName ];
			}
		}

		return array_reverse( $fileNames );
	}

	public function readFile( $filePath ) {
		if ( ! file_exists( $filePath ) ) {
			return null;
		}

		return file_get_contents( $filePath );

	}

	public function readErrorFile( $filename ) {
		return $this->readFile( $this->logBaseDir . 'error/' . $filename );
	}

	public function readDebugFile( $filename ) {
		return $this->readFile( $this->logBaseDir . 'debug/' . $filename );
	}
}