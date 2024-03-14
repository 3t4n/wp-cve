<?php
namespace CTXFeed\V5\File
/**
 * @package CTXFeed\V5\File
 */
;

/**
 * TXT file creation class implementing the FileInterface.
 *
 * This class is responsible for creating TXT formatted files based on provided data and configuration.
 */
class TXT implements FileInterface {

	/**
	 * Data to be written to the TXT file.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Configuration settings for the TXT file creation.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor for the TXT class.
	 *
	 * Initializes the TXT file with provided data and configuration.
	 *
	 * @param array  $data   Data for the TXT file.
	 * @param Config $config Configuration settings for the TXT file.
	 */
	public function __construct( $data, $config ) {

		$this->data = $data;
		$this->config = $config;
	}

	/**
	 * Creates the header and footer for the TXT file.
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

		if ( ! empty( $this->data ) && is_array( $this->data ) ) {
			$first = $this->implode_all( $delimiter, $enclosure, $this->data, 'key' ) . "\n";

			$header_footer = [
				'header' => $first,
				'footer' => '',
			];
		}

		return apply_filters( "ctx_make_{$this->config->feedType}_feed_header_footer", $header_footer, $this->data, $this->config );
	}

	/**
	 * Creates the body of the TXT file.
	 *
	 * @return string The formatted body content of the TXT file.
	 */
	public function make_body(  ) {

		$column    = '';
		$enclosure = ! $this->config->get_enclosure() ? '' : $this->config->get_enclosure();
		$delimiter = $this->config->get_delimiter();

		foreach ( $this->data as $product ) {
			$column .= $this->implode_all( $delimiter, $enclosure, $product ) . "\n";
		}

		return apply_filters( "ctx_make_{$this->config->feedType}_feed_body", $column, $this->data, $this->config );
	}

	/**
	 * Helper method to convert multi-dimensional arrays to a string.
	 *
	 * @param string $delimiter Delimiter for separating elements.
	 * @param string $enclosure Enclosure for wrapping elements.
	 * @param array  $array     Array to be imploded.
	 * @param string $kv        Key or value indicator for processing.
	 *
	 * @return string Imploded string.
	 */
	private function implode_all( $delimiter, $enclosure, $arr, $kv = 'value' ) {
		foreach ( $arr as $i => $i_value ) {
			if ( is_array( $i_value ) ) {
				if ( 'value' === $kv ) {
					$arr[ $i ] = $enclosure . $this->implode_all( $delimiter, $enclosure, $i_value, $kv ) . $enclosure;
				} else {
					$arr[ $i ] = $enclosure . array_key_first( $i_value ) . $enclosure;
				}
			}
		}

		return implode( $delimiter, $arr );
	}
}

