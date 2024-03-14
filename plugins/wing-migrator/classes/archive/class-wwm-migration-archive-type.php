<?php

/**
 * Interface Wwm_Archive_Interface
 */
interface Wwm_Archive_Interface {
	public function open( $filename, $is_create );

	public function close();

	public function add_file( $filename, $local_name );

	public function bulk_add_file( $filename, $local_name );

	public function flush_bulk_add_file();

	public function add_from_string( $local_name, $contents );

	public function add_empty_dir( $dir_name );

	public function extract_to( $destination, $entries );

	public function get_num_files();

	public function get_name_index( $index );
}


class Wwm_Archive_Zip implements Wwm_Archive_Interface {
	/** @var ZipArchive */
	private $zip;

	public function __construct() {
	}

	/**
	 * Open zip file
	 * @param string $filename
	 * @param boolean $is_create
	 */
	public function open( $filename, $is_create ) {
		$this->zip = new ZipArchive();
		if ( $is_create === true ) {
			$zip_status = $this->zip->open( $filename, ZIPARCHIVE::CREATE );
		} else {
			$zip_status = $this->zip->open( $filename );
		}
		if ( $zip_status !== true ) {
			throw new Exception( 'fail to open zip file.' );
		}
	}

	/**
	 * Close zip file
	 */
	public function close() {
		if ( isset( $this->zip ) ) {
			$this->zip->close();
		}
		$this->zip = null;
	}

	/**
	 * add file to zip
	 * @param string $filename - target file path
	 * @param string $local_name - file path in zip file
	 * @return bool
	 */
	public function add_file( $filename, $local_name ) {
		return $this->zip->addFile( $filename, $local_name );
	}

	/**
	 * add contents to zip file
	 * @param string $local_name - file path in zip file
	 * @param string $contents - data string
	 * @return bool
	 */
	public function add_from_string( $local_name, $contents ) {
		return $this->zip->addFromString( $local_name, $contents );
	}

	/**
	 * add empty dir to zip file
	 * @param $dir_name
	 * @return bool
	 */
	public function add_empty_dir( $dir_name ) {
		return $this->zip->addEmptyDir( $dir_name );
	}

	/**
	 * bulk add file to zip
	 * alias to add_file method
	 * @param $filename
	 * @param $local_name
	 * @return bool
	 */
	public function bulk_add_file( $filename, $local_name ) {
		return $this->add_file( $filename, $local_name );
	}

	/**
	 *
	 */
	public function flush_bulk_add_file() {
		// void
	}

	/**
	 * extract files
	 * @param string $destination
	 * @param array|string $entries
	 * @return bool
	 */
	public function extract_to( $destination, $entries ) {
		$current_locale = setlocale( LC_ALL, '0' );
		$locale_changed = false;
		if ( $current_locale == 'C' ) {
			setlocale( LC_ALL, 'en_US.UTF-8' );
			$locale_changed = true;
		}
		$result = $this->zip->extractTo( $destination, $entries );
		if ( $locale_changed ) {
			setlocale( LC_ALL, $current_locale );
		}
		return $result;
	}

	/**
	 * get filename from index
	 * @param string $index
	 * @return string filename
	 */
	public function get_name_index( $index ) {
		if ( defined( "ZipArchive::FL_ENC_RAW" ) ) {
			$filename = $this->zip->getNameIndex( $index, ZipArchive::FL_ENC_RAW );
		} else {
			$filename = $this->zip->getNameIndex( $index );

		}
		return $filename;
	}

	/**
	 * get file numbers
	 * @return integer
	 */
	public function get_num_files() {
		return $this->zip->numFiles;
	}
}


class Wwm_Archive_Tar implements Wwm_Archive_Interface {
	/** @var PharData */
	protected $tar;
	/** @var array */
	private $add_files;

	public function __construct() {
	}

	/**
	 * Open tar file
	 * @param string $filename
	 * @param boolean $is_create
	 * @throws Exception
	 */
	public function open( $filename, $is_create ) {
		try {
			$this->tar = new PharData( $filename, Phar::CURRENT_AS_FILEINFO | Phar::KEY_AS_FILENAME );
		} catch ( UnexpectedValueException $e ) {
			throw new Exception( 'fail to open tar file.' );
		}
	}

	/**
	 * Close tar file
	 */
	public function close() {
		$this->tar = null;
	}

	/**
	 * add file to tar file
	 * @param string $filename
	 * @param string $local_name
	 * @return bool
	 */
	public function add_file( $filename, $local_name ) {
		try {
			$this->tar->addFile( $filename, $local_name );
			return true;
		} catch ( Exception $exception ) {
			return false;
		}
	}

	/**
	 *
	 * @param string $local_name
	 * @param string $contents
	 * @return bool
	 */
	public function add_from_string( $local_name, $contents ) {
		try {
			$this->tar->addFromString( $local_name, $contents );
			return true;
		} catch ( Exception $exception ) {
			return false;
		}
	}

	/**
	 * add empty dir
	 * @param $dir_name
	 * @return bool
	 */
	public function add_empty_dir( $dir_name ) {
	}

	/**
	 * bulk add file
	 * @param $filename
	 * @param $local_name
	 */
	public function bulk_add_file( $filename, $local_name ) {
		$this->add_files[ $local_name ] = $filename;
		if ( count( $this->add_files ) % 100 === 0 ) {
			$this->flush_bulk_add_file();
			$this->add_files = array();
		}
	}

	/**
	 * flush bulk add file
	 */
	public function flush_bulk_add_file() {
		$this->tar->buildFromIterator( new ArrayIterator( $this->add_files ) );
	}

	/**
	 * extract files
	 * @param string $destination
	 * @param array|string $entries
	 * @return bool
	 */
	public function extract_to( $destination, $entries ) {
		return $this->tar->extractTo( $destination, $entries, true );
	}

	/**
	 */
	public function get_name_index( $index ) {
		// void
		throw new Exception( 'tar is no implement get_name_index method' );
	}

	/**
	 * get file numbers
	 * @return int
	 */
	public function get_num_files() {
		return $this->tar->count();
	}
}
