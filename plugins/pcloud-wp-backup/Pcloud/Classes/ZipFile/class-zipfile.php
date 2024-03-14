<?php
/**
 * Class ZipFile.
 *
 * @file class-zipfile.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile;

use ArrayAccess;
use Countable;
use DateTimeInterface;
use DirectoryIterator;
use Exception;
use Iterator;
use IteratorIterator;
use Pcloud\Classes\ZipFile\Constants\UnixStat;
use Pcloud\Classes\ZipFile\Constants\ZipCompressionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipOptions;
use Pcloud\Classes\ZipFile\Constants\ZipPlatform;
use Pcloud\Classes\ZipFile\IO\ZipWriter;
use Pcloud\Classes\ZipFile\Model\Data\ZipFileData;
use Pcloud\Classes\ZipFile\Model\Data\ZipNewData;
use Pcloud\Classes\ZipFile\Model\ImmutableZipContainer;
use Pcloud\Classes\ZipFile\Model\ZipContainer;
use Pcloud\Classes\ZipFile\Model\ZipEntry;
use Pcloud\Classes\ZipFile\Util\FilesUtil;
use Pcloud\Classes\ZipFile\Util\StringUtil;
use RecursiveIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Create, open .ZIP files, modify, get info and extract files.
 *
 * Implemented support traditional PKWARE encryption and WinZip AES encryption.
 * Implemented support ZIP64.
 */
class ZipFile implements Countable, ArrayAccess, Iterator {

	/**
	 * ZIP container.
	 *
	 * @var ZipContainer|null $zip_container
	 */
	protected $zip_container;

	/**
	 * Time now.
	 *
	 * @var int $now
	 */
	private $now;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->now           = time();
		$this->zip_container = $this->create_zip_container();
	}

	/**
	 * Create zip writer.
	 *
	 * @return ZipWriter
	 */
	protected function create_zip_writer(): ZipWriter {
		return new ZipWriter( $this->zip_container );
	}

	/**
	 * Create zip container.
	 *
	 * @param ImmutableZipContainer|null $source_container Source container.
	 * @return ZipContainer
	 */
	protected function create_zip_container( ?ImmutableZipContainer $source_container = null ): ZipContainer {
		return new ZipContainer( $source_container );
	}

	/**
	 * Returns the number of entries in this ZIP file.
	 *
	 * @return int
	 */
	public function count(): int {
		return $this->zip_container->count();
	}

	/**
	 * Add entry from the string.
	 *
	 * @param string         $entry_name Zip entry name.
	 * @param string         $contents String contents.
	 * @param int|null|mixed $compression_method Compression method. If null, then auto choosing method.
	 *
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function add_from_string( string $entry_name, string $contents, $compression_method = null ): self {

		$entry_name = $this->normalize_entry_name( $entry_name );

		$length = strlen( $contents );

		if ( null === $compression_method || ZipEntry::UNKNOWN === $compression_method ) {
			if ( $length < 512 ) {
				$compression_method = ZipCompressionMethod::STORED;
			} else {
				$mime_type          = FilesUtil::get_mime_type_from_string( $contents );
				$compression_method = FilesUtil::is_bad_compression_mime_type( $mime_type )
					? ZipCompressionMethod::STORED
					: ZipCompressionMethod::DEFLATED;
			}
		}

		$zip_entry = new ZipEntry( $entry_name );
		$zip_entry->set_data( new ZipNewData( $zip_entry, $contents ) );
		$zip_entry->set_uncompressed_size( $length );
		$zip_entry->set_compression_method( $compression_method );
		$zip_entry->set_created_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_extracted_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_unix_mode( 0100644 );
		$zip_entry->set_time( time() );

		$this->add_zip_entry( $zip_entry );

		return $this;
	}

	/**
	 * Normalize entry name.
	 *
	 * @param string $entry_name Entry name.
	 * @return string
	 * @throws Exception Throws exception.
	 */
	protected function normalize_entry_name( string $entry_name ): string {

		$entry_name = ltrim( $entry_name, '\\/' );
		if ( '\\' === DIRECTORY_SEPARATOR ) {
			$entry_name = str_replace( '\\', '/', $entry_name );
		}
		if ( '' === $entry_name ) {
			throw new Exception( 'Empty entry name' );
		}
		return $entry_name;
	}

	/**
	 * Add spl file.
	 *
	 * @param SplFileInfo|ZipEntry $file File.
	 * @param string|null|mixed    $entry_name Entry name.
	 * @param array                $options File options.
	 * @return false|ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function add_spl_file( $file, $entry_name = null, array $options = array() ) {
		if ( $file instanceof DirectoryIterator ) {
			throw new Exception( 'File should not be \DirectoryIterator.' );
		}
		$default_options = array(
			ZipOptions::COMPRESSION_METHOD => null,
			ZipOptions::MODIFIED_TIME      => null,
		);

		/**
		 * We are increasing the number of optins.
		 *
		 * @noinspection AdditionOperationOnArraysInspection
		 */
		$options += $default_options;

		if ( ! $file->isReadable() ) {
			return false;
		}

		if ( null === $entry_name ) {
			$entry_name = $file->getBasename();
		}

		$entry_name = $this->normalize_entry_name( $entry_name );
		$entry_name = $file->isDir() ? rtrim( $entry_name, '/\\' ) . '/' : $entry_name;

		$zip_entry = new ZipEntry( $entry_name );
		$zip_entry->set_created_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_extracted_os( ZipPlatform::OS_UNIX );

		$zip_data   = null;
		$file_perms = $file->getPerms();

		if ( $file->isLink() ) {

			$link_target        = $file->getLinkTarget();
			$length_link_target = strlen( $link_target );

			$zip_entry->set_compression_method( ZipCompressionMethod::STORED );
			$zip_entry->set_uncompressed_size( $length_link_target );
			$zip_entry->set_compressed_size( $length_link_target );
			$zip_entry->set_crc( crc32( $link_target ) );
			$file_perms |= UnixStat::UNX_IFLNK;

			$zip_data = new ZipNewData( $zip_entry, $link_target );

		} elseif ( $file->isFile() ) {

			if ( isset( $options[ ZipOptions::COMPRESSION_METHOD ] ) ) {
				$compression_method = $options[ ZipOptions::COMPRESSION_METHOD ];
			} elseif ( $file->getSize() < 512 ) {
				$compression_method = ZipCompressionMethod::STORED;
			} else {
				$compression_method = FilesUtil::is_bad_compression_file( $file->getPathname() )
					? ZipCompressionMethod::STORED
					: ZipCompressionMethod::DEFLATED;
			}

			$zip_entry->set_compression_method( $compression_method );

			$zip_data = new ZipFileData( $zip_entry, $file );

		} elseif ( $file->isDir() ) {

			$zip_entry->set_compression_method( ZipCompressionMethod::STORED );
			$zip_entry->set_uncompressed_size( 0 );
			$zip_entry->set_compressed_size( 0 );
			$zip_entry->set_crc( 0 );
		}

		$zip_entry->set_unix_mode( $file_perms );

		$timestamp = null;

		if ( isset( $options[ ZipOptions::MODIFIED_TIME ] ) ) {

			$mtime = $options[ ZipOptions::MODIFIED_TIME ];

			if ( $mtime instanceof DateTimeInterface ) {
				$timestamp = $mtime->getTimestamp();
			} elseif ( is_numeric( $mtime ) ) {
				$timestamp = (int) $mtime;
			} elseif ( is_string( $mtime ) ) {
				$timestamp = strtotime( $mtime );

				if ( false === $timestamp ) {
					$timestamp = null;
				}
			}
		}

		if ( null === $timestamp ) {
			$timestamp = $this->now;
		}

		$zip_entry->set_time( $timestamp );
		$zip_entry->set_data( $zip_data );

		$this->add_zip_entry( $zip_entry );

		return $zip_entry;
	}

	/**
	 * Add zip entry.
	 *
	 * @param ZipEntry $zip_entry Zip Entry.
	 * @return void
	 */
	protected function add_zip_entry( ZipEntry $zip_entry ) {
		$this->zip_container->add_entry( $zip_entry );
	}

	/**
	 * Add entry from the file.
	 *
	 * @param string            $filepath Destination file.
	 * @param string|null|mixed $entry_name Zip Entry name.
	 * @param int|null|mixed    $compression_method Compression method. If null, then auto choosing method.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function add_file( string $filepath, $entry_name = null, $compression_method = null ): self {
		if ( ! file_exists( $filepath ) || ! is_readable( $filepath ) ) {
			return $this;
		}
		$this->add_spl_file(
			new SplFileInfo( $filepath ),
			$entry_name,
			array(
				ZipOptions::COMPRESSION_METHOD => $compression_method,
			)
		);
		return $this;
	}

	/**
	 * Add entry from the stream.
	 *
	 * @param resource       $stream Stream resource.
	 * @param string         $entry_name ZIP Entry name.
	 * @param int|null|mixed $compression_method Compression method. If null, then auto choosing method.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function add_from_stream( $stream, string $entry_name, $compression_method = null ): self {

		if ( ! is_resource( $stream ) ) {
			throw new Exception( 'Stream is not resource' );
		}

		$entry_name = $this->normalize_entry_name( $entry_name );
		$zip_entry  = new ZipEntry( $entry_name );
		$fstat      = fstat( $stream );

		if ( false !== $fstat ) {

			$unix_mode = $fstat['mode'];
			$length    = $fstat['size'];

			if ( null === $compression_method || ZipEntry::UNKNOWN === $compression_method ) {
				if ( $length < 512 ) {
					$compression_method = ZipCompressionMethod::STORED;
				} else {
					rewind( $stream );
					$buffer_contents = stream_get_contents( $stream, min( 1024, $length ) );
					rewind( $stream );
					$mime_type          = FilesUtil::get_mime_type_from_string( $buffer_contents );
					$compression_method = FilesUtil::is_bad_compression_mime_type( $mime_type )
						? ZipCompressionMethod::STORED
						: ZipCompressionMethod::DEFLATED;
				}
				$zip_entry->set_uncompressed_size( $length );
			}
		} else {

			$unix_mode = 0100644;

			if ( null === $compression_method || ZipEntry::UNKNOWN === $compression_method ) {
				$compression_method = ZipCompressionMethod::DEFLATED;
			}
		}

		$zip_entry->set_created_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_extracted_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_unix_mode( $unix_mode );
		$zip_entry->set_compression_method( $compression_method );
		$zip_entry->set_time( time() );
		$zip_entry->set_data( new ZipNewData( $zip_entry, $stream ) );

		$this->add_zip_entry( $zip_entry );

		return $this;
	}

	/**
	 * Add an empty directory in the zip archive.
	 *
	 * @param string $dir_name Directory name.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function add_empty_dir( string $dir_name ): self {

		$dir_name = $this->normalize_entry_name( $dir_name );
		$dir_name = rtrim( $dir_name, '\\/' ) . '/';

		$zip_entry = new ZipEntry( $dir_name );
		$zip_entry->set_compression_method( ZipCompressionMethod::STORED );
		$zip_entry->set_uncompressed_size( 0 );
		$zip_entry->set_compressed_size( 0 );
		$zip_entry->set_crc( 0 );
		$zip_entry->set_created_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_extracted_os( ZipPlatform::OS_UNIX );
		$zip_entry->set_unix_mode( 040755 );
		$zip_entry->set_time( time() );

		$this->add_zip_entry( $zip_entry );

		return $this;
	}

	/**
	 * Add directories from directory iterator.
	 *
	 * @param Iterator       $iterator Directory iterator.
	 * @param string         $local_path Add files to this directory, or the root.
	 * @param int|null|mixed $compression_method Compression method.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function add_files_from_iterator( Iterator $iterator, string $local_path = '/', $compression_method = null ): ZipFile {
		if ( empty( $local_path ) ) {
			$local_path = '';
		}

		$local_path = trim( $local_path, '\\/' );

		$iterator = $iterator instanceof RecursiveIterator
			? new RecursiveIteratorIterator( $iterator )
			: new IteratorIterator( $iterator );

		/**
		 * Files list.
		 *
		 * @var string[] $files
		 */
		$files = array();
		foreach ( $iterator as $file ) {
			if ( $file instanceof SplFileInfo ) {
				if ( $file->getBasename() === '..' ) {
					continue;
				}

				if ( $file->getBasename() === '.' ) {
					$files[] = dirname( $file->getPathname() );
				} else {
					$files[] = $file->getPathname();
				}
			}
		}

		if ( empty( $files ) ) {
			return $this;
		}

		natcasesort( $files );
		$path = array_shift( $files );

		$this->do_add_files( $path, $files, $local_path, $compression_method );

		return $this;
	}

	/**
	 * Add files.
	 *
	 * @param string         $file_system_dir File System Directory.
	 * @param array          $files The files.
	 * @param string         $zip_path Zip Path.
	 * @param int|null|mixed $compression_method Compression method.
	 *
	 * @throws Exception Throws Exception.
	 */
	private function do_add_files( string $file_system_dir, array $files, string $zip_path, $compression_method = null ) {

		$file_system_dir = rtrim( $file_system_dir, '/\\' ) . DIRECTORY_SEPARATOR;

		if ( ! empty( $zip_path ) ) {
			$zip_path = trim( $zip_path, '\\/' ) . '/';
		} else {
			$zip_path = '/';
		}

		foreach ( $files as $file ) {
			$filename = str_replace( $file_system_dir, $zip_path, $file );
			$filename = ltrim( $filename, '\\/' );

			if ( is_dir( $file ) && FilesUtil::is_empty_dir( $file ) ) {
				$this->add_empty_dir( $filename );
			} elseif ( is_file( $file ) ) {
				$this->add_file( $file, $filename, $compression_method );
			}
		}
	}

	/**
	 * Add array data to archive.
	 * Keys is local names.
	 * Values is contents.
	 *
	 * @param array $map_data Associative array for added to zip.
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function add_all( array $map_data ) {
		foreach ( $map_data as $local_name => $content ) {
			$this[ $local_name ] = $content;
		}
	}

	/**
	 * Delete entry by name.
	 *
	 * @param string $entry_name ZIP Entry name.
	 * @return ZipFile
	 * @throws Exception If entry not found.
	 */
	public function delete_from_name( string $entry_name ): self {
		$entry_name = ltrim( $entry_name, '\\/' );
		if ( ! $this->zip_container->delete_entry( $entry_name ) ) {
			throw new Exception( $entry_name );
		}
		return $this;
	}

	/**
	 * Save as file.
	 *
	 * @param string $filename Output filename.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function save_as_file( string $filename ): self {

		$temp_filename = $filename . '.temp' . uniqid();

		/**
		 * We are setting the error handler.
		 *
		 * @psalm-suppress InvalidArgument
		 */
		set_error_handler(
			static function ( int $error_number, string $error_string ) {
				throw new Exception( $error_string, $error_number );
			}
		);

		$handle = fopen( $temp_filename, 'w+b' );
		restore_error_handler();

		$this->save_as_stream( $handle );

		if ( ! rename( $temp_filename, $filename ) ) {
			if ( is_file( $temp_filename ) ) {
				unlink( $temp_filename );
			}

			throw new Exception( sprintf( 'Cannot move %s to %s', $temp_filename, $filename ) );
		}

		return $this;
	}

	/**
	 * Save as stream.
	 *
	 * @param resource $handle Output stream resource.
	 * @return ZipFile
	 * @throws Exception Throws Exception.
	 */
	public function save_as_stream( $handle ): self {

		if ( ! is_resource( $handle ) ) {
			throw new Exception( 'handle is not resource' );
		}

		$this->write_zip_to_stream( $handle );
		fclose( $handle );

		return $this;
	}

	/**
	 * Write zip to stream.
	 *
	 * @param resource $handle Resource handle.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_zip_to_stream( $handle ) {
		$this->on_before_save();
		$this->create_zip_writer()->write( $handle );
	}

	/**
	 * Event before save or output.
	 *
	 * @return void
	 */
	protected function on_before_save() {
	}

	/**
	 * Close zip archive and release input stream.
	 *
	 * @return void
	 */
	public function close() {
		$this->zip_container = $this->create_zip_container();
		gc_collect_cycles();
	}

	/**
	 * Release all resources.
	 */
	public function __destruct() {
		$this->close();
	}

	/**
	 * Offset to set.
	 *
	 * @param mixed                                         $offset The offset to assign the value to.
	 * @param string|DirectoryIterator|SplFileInfo|resource $value The value to set.
	 * @throws Exception Throws Exception.
	 */
	public function offsetSet( $offset, $value ) {

		if ( null === $offset ) {
			throw new Exception( 'Key must not be null, but must contain the name of the zip entry.' );
		}

		$offset = ltrim( (string) $offset, '\\/' );

		if ( '' === $offset ) {
			throw new Exception( 'Key is empty, but must contain the name of the zip entry.' );
		}

		if ( $value instanceof DirectoryIterator ) {
			$this->add_files_from_iterator( $value, $offset );
		} elseif ( $value instanceof SplFileInfo ) {
			$this->add_spl_file( $value, $offset );
		} elseif ( StringUtil::ends_with( $offset, '/' ) ) {
			$this->add_empty_dir( $offset );
		} elseif ( is_resource( $value ) ) {
			$this->add_from_stream( $value, $offset );
		} else {
			$this->add_from_string( $offset, (string) $value );
		}
	}

	/**
	 * Offset to unset.
	 *
	 * @param mixed $offset Zip entry name.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	public function offsetUnset( $offset ) {
		$this->delete_from_name( $offset );
	}

	/**
	 * Return the current element.
	 *
	 * @return string|null
	 * @throws Exception Throws Exception.
	 */
	public function current(): ?string {
		return $this->offsetGet( $this->key() );
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param mixed $offset Zip entry name.
	 * @return int|null
	 * @throws Exception Throws Exception.
	 */
	public function offsetGet( $offset ): ?int {
		return null;
	}

	/**
	 * Return the key of the current element.
	 *
	 * @return string|null Scalar on success, or null on failure.
	 */
	public function key(): ?string {
		return key( $this->zip_container->get_entries() );
	}

	/**
	 * Move forward to next element.
	 *
	 * @return void
	 */
	public function next() {
		next( $this->zip_container->get_entries() );
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @return bool The return value will cast to boolean and then evaluated.
	 *              Returns true on success or false on failure.
	 */
	public function valid(): bool {
		$key = $this->key();
		return null !== $key && isset( $this->zip_container->get_entries()[ $key ] );
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param mixed $offset an offset to check for.
	 * @return bool true on success or false on failure.
	 *              The return value will cast to boolean if non-boolean was returned.
	 */
	public function offsetExists( $offset ): bool {
		return isset( $this->zip_container->get_entries()[ $offset ] );
	}

	/**
	 * Rewind the Iterator to the first element.
	 *
	 * @return void
	 */
	public function rewind() {
		reset( $this->zip_container->get_entries() );
	}
}
