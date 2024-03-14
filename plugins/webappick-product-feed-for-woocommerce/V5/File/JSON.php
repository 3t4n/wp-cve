<?php
/**
 * @package CTXFeed\V5\File
 */

namespace CTXFeed\V5\File;

/**
 * JSON file creation class implementing the FileInterface.
 *
 * This class is responsible for creating JSON formatted files based on provided data and configuration.
 */
class JSON implements FileInterface {

	/**
	 * Data to be written to the JSON file.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Configuration settings for the JSON file creation.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor for the JSON class.
	 *
	 * Initializes the JSON file with provided data and configuration.
	 *
	 * @param array  $data   Data to be serialized into JSON.
	 * @param Config $config Configuration settings for the JSON file.
	 */
	public function __construct( $data, $config ) {

		$this->data   = $data;
		$this->config = $config;
	}

	/**
	 * Creates the header and footer for the JSON file.
	 *
	 * This method returns an array with empty strings for 'header' and 'footer' as JSON files
	 * typically do not have distinct headers or footers.
	 *
	 * @return array An array with 'header' and 'footer' keys, both set to empty strings.
	 */
	public function make_header_footer() {
		$header_footer = [
			'header' => '',
			'footer' => '',
		];

		return apply_filters( "ctx_make_{$this->config->feedType}_feed_header_footer", $header_footer, $this->data, $this->config );
	}

	/**
	 * Generates the body of the JSON file.
	 *
	 * This method serializes the provided data into a JSON format. It can be extended to
	 * manipulate the data structure before serialization.
	 *
	 * @return string  string representing the body of the file.
	 */
	public function make_body() {

		$content = $this->data;

		//TODO: Multi dimension to single array.
		return apply_filters( "ctx_make_{$this->config->feedType}_feed_body", $content, $this->data, $this->config );
	}

}

