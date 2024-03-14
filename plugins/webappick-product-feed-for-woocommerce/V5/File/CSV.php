<?php
/**
 * @package CTXFeed\V5\File
 */

namespace CTXFeed\V5\File;

use CTXFeed\V5\Utility\Config;

/**
 * CSV file generation class implementing FileInterface.
 *
 * This class is responsible for creating CSV files based on provided data and configuration.
 */

class CSV implements FileInterface {

	/**
	 * Data to be written to the CSV file.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Configuration for the CSV file generation.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor for CSV class.
	 *
	 * Initializes the CSV file with data and configuration.
	 *
	 * @param array  $data   Data for the CSV file.
	 * @param Config $config Configuration settings for the CSV file.
	 */
	public function __construct( $data, $config ) {

		$this->data   = $data;
		$this->config = $config;
	}

	/**
	 * Creates the header and footer for the CSV file.
	 *
	 * @return array An array with 'header' and 'footer' keys.
	 */
	public function make_header_footer() {
		$header_footer = [
			'header' => '',
			'footer' => '',
		];


		$enclosure = $this->config->get_enclosure();
		$delimiter = $this->config->get_delimiter();

		if ( ! empty( $this->data ) && \is_array( $this->data ) ) {
			$first = $this->implode_all( $delimiter, $enclosure, $this->data, 'key' ) . "\n";

			$header_footer = [
				'header' => $first,
				'footer' => '',
			];
		}

		return \apply_filters( "ctx_make_{$this->config->feedType}_feed_header_footer", $header_footer, $this->data, $this->config );
	}

	/**
	 * Creates the body of the CSV file.
	 *
	 * @return string CSV formatted string representing the body of the file.
	 */
	public function make_body() {

		$column    = '';
		$enclosure = $this->config->get_enclosure();
		$delimiter = $this->config->get_delimiter();

		foreach ( $this->data as $product ) {
			$column .= $this->implode_all( $delimiter, $enclosure, $product ) . "\n";
		}

		return \apply_filters( "ctx_make_{$this->config->feedType}_feed_body", $column, $this->data, $this->config );
	}

	/**
	 * Helper method to convert multi-dimensional arrays to a CSV string.
	 *
	 * @param string $delimiter Delimiter for the CSV.
	 * @param string $enclosure Enclosure for the CSV.
	 * @param array  $arr     Array to be imploded.
     * @param array  $kv      String Default is value
	 * @return string Imploded string.
	 */
	private function implode_all( $delimiter, $enclosure, $arr, $kv = 'value' ) {
		foreach ( $arr as $i => $i_value ) {
			if ( \is_array( $i_value ) ) {
				if ( 'value' === $kv ) {
					$arr[ $i ] = $enclosure . $this->implode_all( $delimiter, $enclosure, $i_value, $kv ) . $enclosure;
				} else {
					$arr[ $i ] = $enclosure . \array_key_first( $i_value ) . $enclosure;
				}
			}
		}

		return \implode( $delimiter, $arr );
	}

}

