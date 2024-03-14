<?php
namespace CTXFeed\V5\File;
/**
 * @package CTXFeed\V5\File
 */

/**
 * XLS file creation class implementing the FileInterface.
 *
 * This class is responsible for creating XLS formatted files based on provided data and configuration.
 */
class XLS implements FileInterface {

	/**
	 * Data to be written to the XLS file.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Configuration settings for the XLS file creation.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor for the XLS class.
	 *
	 * Initializes the XLS file with provided data and configuration.
	 *
	 * @param array  $data   Data for the XLS file.
	 * @param Config $config Configuration settings for the XLS file.
	 */
	public function __construct( $data, $config ) {

		$this->data = $data;
		$this->config = $config;
	}

	/**
	 * Creates the header and footer for the XLS file.
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
	 * Creates the body of the XLS file.
	 *
	 * @return string The formatted body content of the XLS file.
	 */
	public function make_body(  ) {

		$column    = '';
		$enclosure = ! $this->config->get_enclosure() ? '"' : $this->config->get_enclosure();
		$delimiter = $enclosure . $this->config->get_delimiter() . $enclosure;

		foreach ( $this->data as $product ) {
			$column .= $enclosure . $this->implode_all( $delimiter, $enclosure, $product ) . $enclosure . "\n";
		}

		return apply_filters( "ctx_make_{$this->config->feedType}_feed_body", $column, $this->data, $this->config );
	}

	/**
	 * Helper method to convert multi-dimensional arrays to a string.
	 *
	 * @param string $delimiter Delimiter for separating elements.
	 * @param string $enclosure Enclosure for wrapping elements.
	 * @param array  $array     Array to be imploded.
	 *
	 * @return string Imploded string.
	 */
	private function implode_all( $delimiter, $enclosure, $arr ) {
		foreach ( $arr as $i => $i_value ) {
			if ( is_array( $i_value ) ) {
				$arr[ $i ] = $this->implode_all( $delimiter, $enclosure, $i_value );
			}
		}

		return implode( $delimiter, $arr );
	}
}

