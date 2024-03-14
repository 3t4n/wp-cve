<?php
/**
 * @package CTXFeed\V5\File
 */
namespace CTXFeed\V5\File;

/**
 * Interface for file handling in CTXFeed.
 *
 * This interface defines the structure for creating files, including headers, footers,
 * and body content specific to the file type.
 */

interface FileInterface {
	/**
	 * Creates the header and footer for a file.
	 *
	 * This method should return an array with predefined structures for the file's header and footer.
	 * It is crucial for defining the starting and ending points of the file.
	 *
	 * @return array An associative array with 'header' and 'footer' as keys.
	 */
	public function make_header_footer();

	/**
	 * Generates the main body of the file.
	 *
	 * This method is responsible for creating the central content of the file.
	 * It should return the content as a string or false on failure.
	 *
	 * @return false|string The generated body content as a string, or false on failure.
	 */
	public function make_body();

}

