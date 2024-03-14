<?php
/**
 * @package CTXFeed\V5\File
 */

namespace CTXFeed\V5\File;

use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\File\CSV;
use CTXFeed\V5\File\TXT;
use CTXFeed\V5\File\XLS;
use CTXFeed\V5\File\XML;
use CTXFeed\V5\File\JSON;

/**
 * FileFactory class responsible for creating file instances.
 *
 * This class provides a factory method to instantiate file objects based on the file type
 * specified in the configuration.
 */
class FileFactory {
	/**
	 * Creates and returns a FileInfo object based on the specified file type.
	 *
	 * This method dynamically determines the file class to instantiate based on the feed type
	 * specified in the config object. It defaults to CSV if the specified class does not exist.
	 *
	 * @param array  $data   Data to be used in the file creation.
	 * @param Config $config Configuration object containing feed type and other settings.
	 *
	 * @return FileInfo An instance of FileInfo encapsulating the file object.
	 */
	public static function get_file_data( $data, $config ) {
		$type  = $config->feedType;
		$class = "\CTXFeed\V5\File\\".\strtoupper( $type );

		if ( \class_exists( $class ) ) {
			return new FileInfo( new $class( $data, $config ) );
		}

		return new FileInfo( new CSV( $data, $config ) );
	}

}
