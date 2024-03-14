<?php
namespace CTXFeed\V5\File;

/**
 * FileInfo class for handling file operations.
 *
 * This class acts as a wrapper for different file types, delegating file operation
 * responsibilities to the specified FileInterface implementation.
 */
class FileInfo {

	/**
	 * File object implementing FileInterface.
	 *
	 * @var FileInterface
	 */
	private $file;

	/**
	 * Constructs a FileInfo object with a given file handler.
	 *
	 * @param FileInterface $file An instance of a class that implements FileInterface.
	 */
	public function __construct( FileInterface $file) {
		$this->file   = $file;
	}

	/**
	 * Delegates the creation of header and footer to the file handler.
	 *
	 * This method calls the make_header_footer method of the injected FileInterface
	 * implementation and returns its result.
	 *
	 * @return array An array containing header and footer information.
	 */
	public function make_header_footer() {
		return $this->file->make_header_footer();
	}

	/**
	 * Delegates the creation of the file body to the file handler.
	 *
	 * This method calls the make_body method of the injected FileInterface
	 * implementation and returns its result.
	 *
	 * @return false|string The content of the file body or false on failure.
	 */
	public function make_body() {
		return $this->file->make_body();
	}

}
