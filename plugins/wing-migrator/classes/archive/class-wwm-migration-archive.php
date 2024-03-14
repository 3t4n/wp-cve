<?php

class Wwm_Archive_Compress {
	/** @var Wwm_Archive_Interface */
	private $archive_method;
	/** @var string */
	private $archive_file_path;
	/** @var Wwm_Backup_Info */
	private $wwm_info;
	/** @var Wwm_Job_Info */
	private $job_info;
	/** @var Wwm_Logger */
	private $logger;
	/** @var Wwm_Archived_Dir */
	public $archived_dir_data;
	/** @var array */
	public $compressed_dir = array();

	public $BACKUP_EXCLUDE_DIRS;

	/**
	 * Wwm_Archive_Compress constructor.
	 * @param Wwm_Archive_Interface $archive_method
	 * @param string $archive_file_path
	 * @param Wwm_Backup_Info $wwm_info
	 */
	public function __construct( $archive_method, $archive_file_path, $wwm_info ) {
		$this->BACKUP_EXCLUDE_DIRS = array(
			'/mu-plugins',
			'/plugins/' . WWM_MIGRATION_PLUGIN_NAME,
			'/plugins/all-in-one-wp-migration',
			'/uploads/backwpup-',
			'/uploads/bk_'
		);

		$this->archive_method = $archive_method;
		$this->archive_file_path = $archive_file_path;
		$this->wwm_info = $wwm_info;
		$this->job_info = $wwm_info->get_job_info();
		$this->logger = $wwm_info->get_logger();
		$this->archived_dir_data = new Wwm_Archived_Dir( $this->wwm_info->get_backup_dir_path() );
		$this->compressed_dir = $this->archived_dir_data->get();

		$this->archive_method->open( $archive_file_path, true );

	}

	/**
	 * match exclude dir
	 * @param $file_path
	 * @return bool
	 */
	private function is_exclude_dir( $file_path ) {
		foreach ( $this->BACKUP_EXCLUDE_DIRS as $backup_exclude_dir ) {
			if ( strpos( $file_path, $backup_exclude_dir ) !== false ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Close
	 */
	public function close() {
		$this->archive_method->close();
	}

	/**
	 * Wwm_Archive_Compress destructor.
	 */
	public function __destruct() {
		$this->archive_method->close();
	}

	/**
	 * Write archived dir data to file
	 */
	public function update_compressed_dir() {
		$this->archived_dir_data->update( $this->compressed_dir );
	}

	/**
	 * Add file to compress data
	 * @param string $file_path
	 * @param string $file_name
	 */
	public function add_file( $file_path, $file_name ) {
		$result = $this->archive_method->add_file( $file_path, $file_name );
		$this->logger->info( 'add file ' . $file_name );
		if ( $result === false ) {
			$this->logger->warning( 'fail to add file ' . $file_name );
		}
	}

	/**
	 * Add string value to compress data
	 * @param string $file_name
	 * @param string $content
	 */
	public function add_from_string( $file_name, $content ) {
		$result = $this->archive_method->add_from_string( $file_name, $content );
		if ( $result === false ) {
			$this->logger->warning( 'fail to add file to content ' . $file_name );
		}

	}

	/**
	 * Add Wp content directory file to compress data
	 * @param $target_directory
	 * @return bool
	 */
	public function add_wp_content_dir( $target_directory ) {
		if ( $target_directory === null ) {
			$iterator_path = WP_CONTENT_DIR;
		} else {
			$iterator_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $target_directory;
		}
		$this->logger->info( 'target directory: ' . $iterator_path );

		$file_iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $iterator_path, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		$file_count = 0;
		$dir_count = 0;
		$current_dir = '';
		foreach ( $file_iterator as $file_path => $file_info ) {
			if ( $this->is_exclude_dir( $file_path ) ) {
				continue;
			}

			$local_path = 'wp-content' . DIRECTORY_SEPARATOR . str_replace( WP_CONTENT_DIR . DIRECTORY_SEPARATOR, '', $file_path );
			if ( $file_info->isDir() ) {
				if ( $current_dir !== '' ) {
					array_push( $this->compressed_dir, $current_dir );
				}
				if ( $this->job_info->need_retry() ) {
					sleep( 1 );
					$this->logger->info( 'current dir: ' . $file_path );
					$this->logger->info( 'need retry dir:' . $dir_count . ' file: ' . $file_count );
					$this->update_compressed_dir();
					return false;
				}
				if ( ! in_array( $file_path, $this->compressed_dir ) ) {
					$dir_count++;
				}
				$this->archive_method->add_empty_dir( $local_path );
				$current_dir = $file_path;
				continue;
			}
			if ( in_array( $file_info->getPath(), $this->compressed_dir ) ) {
				continue;
			}

			$file_size = $file_info->getSize();
			if ( $file_size > 50 * 1024 * 1024 ) {
				$file_size_mb = floor( $file_size / 1024 / 1024 );
				$this->logger->warning( 'large file: ' . $file_path . '(' . $file_size_mb . ' MB)' );
			}

			if ( $this->wwm_info->check_force_stop() ) {
				$this->logger->warning( 'compress force stop' );
				return true;
			}
			$this->archive_method->bulk_add_file( $file_path, $local_path );
			$file_count++;
		}
		array_push( $this->compressed_dir, $current_dir );
		$this->archive_method->flush_bulk_add_file();

		$this->logger->info( 'additional files ' . $file_count );
		$this->update_compressed_dir();
		return true;
	}
}


class Wwm_Archive_Extract {
	/** @var Wwm_Archive_Interface */
	private $archive_method;
	/** @var Wwm_Restore_Info */
	private $wwm_info;
	/** @var Wwm_Job_Info */
	private $job_info;
	/** @var Wwm_Logger */
	private $logger;
	/** @var array */
	public $compressed_dir = array();

	public $RESTORE_EXCLUDE_DIRS;
	public $CACHE_FILES;

	/**
	 * Wwm_Archive_Extract constructor.
	 * @param Wwm_Archive_Interface $archive_method
	 * @param string $archive_file_path
	 * @param Wwm_Restore_Info $wwm_info
	 */
	public function __construct( $archive_method, $archive_file_path, $wwm_info ) {
		$this->CACHE_FILES = array(
			'wp-content/object-cache.php',
			'wp-content/advanced-cache.php'
		);
		$this->RESTORE_EXCLUDE_DIRS = array_merge( $this->CACHE_FILES, array(
			'/mu-plugins',
			'/plugins/' . WWM_MIGRATION_PLUGIN_NAME,
			'backup.json'
		) );

		$this->archive_method = $archive_method;
		$this->wwm_info = $wwm_info;
		$this->job_info = $wwm_info->get_job_info();
		$this->logger = $wwm_info->get_logger();

		$this->archive_method->open( $archive_file_path, false );
	}

	/**
	 * Extract wp-content directory files
	 * @param null $entries
	 */
	private function extract_to_wp_content( $entries = null ) {
		$destination = preg_replace( "/[\/|\\\\]wp-content/", '', WP_CONTENT_DIR );
		$result = $this->archive_method->extract_to( $destination, $entries );
		if ( $result === false ) {
			$this->logger->warning( 'fail to extract file.' );
			foreach ( $entries as $entry ) {
				$this->logger->warning( $entry );
			}
		}
	}

	/**
	 * Extract specific files
	 * @param string $destination
	 * @param array $entries
	 */
	public function extract_to( $destination, $entries ) {
		if ( is_array( $entries ) === false ) {
			$entries = array( $entries );
		}
		$this->archive_method->extract_to( $destination, $entries );
	}

	/**
	 * Extract a file and get contents
	 * @param string $destination
	 * @param string $entry
	 * @return string
	 */
	public function extract_and_get_content( $destination, $entry ) {
		$this->extract_to( $destination, array( $entry ) );
		return file_get_contents( $destination . DIRECTORY_SEPARATOR . $entry );
	}

	/**
	 * @param array $exclude_files
	 * @return bool
	 */
	public function extract_wp_content_dir( $exclude_files ) {
		if ( $this->archive_method instanceof Wwm_Archive_Zip ) {
			// Zip
			$entries = array();
			$bulk_extract_count = 5;
			$offset = $this->job_info->fetch_current_task_detail( 'file', 'finished_file_offset' );
			$this->logger->info( 'extract offset: ' . $offset );
			for ( $i = $offset; $i < $this->archive_method->get_num_files(); $i++ ) {
				$file_name = $this->archive_method->get_name_index( $i );
				if ( in_array( $file_name, $exclude_files ) ) {
					// exclude sql dump file
					continue;
				}
				$match_exclude_dir = false;
				foreach ( $this->RESTORE_EXCLUDE_DIRS as $backup_exclude_dir ) {
					if ( strpos( $file_name, $backup_exclude_dir ) !== false ) {
						$match_exclude_dir = true;
						break;
					}
				}
				if ( $match_exclude_dir ) {
					continue;
				}
				array_push( $entries, $file_name );
				if ( count( $entries ) === $bulk_extract_count ) {
					$this->extract_to_wp_content( $entries );
					$entries = array();
					if ( $this->wwm_info->check_force_stop() ) {
						$this->logger->warning( 'zip compress force stop' );
						$this->job_info->update_current_task_detail( 'file', 'finished_file_offset', $i );
						return false;
					} elseif ( $this->job_info->need_retry() ) {
						$this->job_info->update_current_task_detail( 'file', 'finished_file_offset', $i );
						return false;
					}

				}
			}
			if ( count( $entries ) !== 0 ) {
				$this->extract_to_wp_content( $entries );
			}
			$this->job_info->update_current_task_detail( 'file', 'finished_file_offset', $i );
			return true;

		} elseif ( $this->archive_method instanceof Wwm_Archive_Tar ) {
			// Tar
			$this->extract_to_wp_content();
			return true;
		} else {
			throw new Exception( 'invalid archive_method' );
		}
	}
}


class Wwm_Archived_Dir {
	/** @var string */
	private $archived_list_file_path;

	/**
	 * Wwm_Archived_Dir constructor.
	 * @param string $target_dir_path
	 */
	public function __construct( $target_dir_path ) {
		$this->archived_list_file_path = $target_dir_path . DIRECTORY_SEPARATOR . '.archived_dir.txt';
	}

	/**
	 * get archived dir list from file
	 */
	public function get() {
		$archived_dirs = array();
		if ( ! isset( $this->archived_list_file_path ) ) {
			return $archived_dirs;
		}
		$handle = @fopen( $this->archived_list_file_path, 'r' );
		if ( $handle ) {
			while ( $line = fgets( $handle ) ) {
				array_push( $archived_dirs, rtrim( $line ) );
			}
		}
		return $archived_dirs;
	}

	/**
	 * update compress_dir data to file
	 * @param $archived_dirs
	 */
	public function update( $archived_dirs ) {
		if ( ! isset( $this->archived_list_file_path ) ) {
			return;
		} elseif ( empty( $archived_dirs ) ) {
			return;
		}
		$handle = @fopen( $this->archived_list_file_path, 'w' );
		foreach ( $archived_dirs as $dir ) {
			fwrite( $handle, $dir . "\n" );
		}
	}

	/**
	 * delete archived_list file
	 */
	public function delete() {
		if ( ! isset( $this->archived_list_file_path ) ) {
			return;
		} elseif ( ! is_file( $this->archived_list_file_path ) ) {
			return;
		}
		@unlink( $this->archived_list_file_path );
	}
}

