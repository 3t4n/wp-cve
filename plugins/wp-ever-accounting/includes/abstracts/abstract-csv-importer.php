<?php
/**
 * CSV Importer Main Class.
 *
 * @since       1.0.2
 * @subpackage  Abstracts
 * @package     EverAccounting
 */

namespace EverAccounting\Abstracts;

defined( 'ABSPATH' ) || exit();

/**
 * Class CSV_Importer
 *
 * @since   1.0.
 *
 * @package EverAccounting\Abstracts
 */
abstract class CSV_Importer {
	/**
	 * Capability needed to perform the current import.
	 *
	 * @since  1.0.2
	 * @var    string
	 */
	protected $capability = 'ea_import';

	/**
	 * CSV file.
	 *
	 * @since  1.0.2
	 * @var string
	 */
	protected $file = '';

	/**
	 * Importer parameters.
	 *
	 * @since  1.0.2
	 * @var array
	 */
	protected $params = array();

	/**
	 * Original headers.
	 * key => label pair value
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $headers = array();

	/**
	 * Column mapping. csv_heading => schema_heading.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $mapping = array();

	/**
	 * Raw keys - CSV raw headers.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $raw_keys = array();

	/**
	 * Mapped keys - CSV headers.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $mapped_keys = array();

	/**
	 * Positions of the file.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $positions = array();

	/**
	 * Parsed data.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $parsed_data = array();

	/**
	 * Raw data.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	protected $raw_data = array();

	/**
	 * Start time of current import.
	 *
	 * (default value: 0)
	 *
	 * @since 1.0.2
	 * @var int
	 */
	protected $start_time = 0;

	/**
	 * Initialize importer.
	 *
	 * @param string $file File to read.
	 * @param array  $params Arguments for the parser.
	 */
	public function __construct( $file, $params = array() ) {
		$default_args = array(
			'position'         => 0, // File pointer start.
			'end_position'     => - 1, // File pointer end.
			'limit'            => 100, // Max lines to read.
			'mapping'          => array(), // Column mapping. csv_heading => schema_heading.
			'parse'            => true, // Whether to sanitize and format data.
			'update_existing'  => false, // Whether to update existing items.
			'delimiter'        => ',', // CSV delimiter.
			'prevent_timeouts' => true, // Check memory and time usage and abort if reaching limit.
			'enclosure'        => '"', // The character used to wrap text in the CSV.
			'escape'           => "\0", // PHP uses '\' as the default escape character. This is not RFC-4180 compliant. This disables the escape character.
		);

		$this->params  = wp_parse_args( $params, $default_args );
		$this->file    = $file;
		$this->headers = $this->get_headers();
		$this->set_mapping( $this->params['mapping'] );

		$this->read_file();
	}


	/**
	 * Get database column and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	abstract protected function get_headers();

	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	abstract public function get_required();

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	abstract protected function get_formatting_callback();

	/**
	 * Process a single item and save.
	 *
	 * @param array $data Raw CSV data.
	 *
	 * @return string|\WP_Error
	 */
	abstract protected function import_item( $data );

	/**
	 * Maps CSV columns to their corresponding import fields.
	 *
	 * @param array $mapping Mapping.
	 *
	 * @since 1.0.2
	 */
	public function set_mapping( $mapping = array() ) {
		if ( ! empty( $mapping ) && is_array( $mapping ) ) {
			$this->mapping = $mapping;
		} else {
			$this->mapping = $this->headers;
		}
	}

	/**
	 * Can user import?
	 *
	 * @return bool Whether the user can import or not
	 * @since  1.0.2
	 */
	public function can_import() {
		return (bool) current_user_can( apply_filters( 'eaccounting_import_capability', $this->capability ) );
	}

	/**
	 * Read CSV file.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	protected function read_file() {
		$valid_filetypes = apply_filters(
			'eaccounting_import_csv_filetypes',
			array(
				'csv' => 'text/csv',
				'txt' => 'text/plain',
			)
		);

		$filetype = wp_check_filetype( $this->file, $valid_filetypes );
		if ( ! in_array( $filetype['type'], $valid_filetypes, true ) ) {
			wp_die( esc_html__( 'Invalid file type. The importer supports CSV and TXT file formats.', 'wp-ever-accounting' ) );
		}

		$handle = fopen( $this->file, 'r' ); // @codingStandardsIgnoreLine.

		if ( false !== $handle ) {
			$this->raw_keys = version_compare( PHP_VERSION, '5.3', '>=' ) ? array_map( 'trim', fgetcsv( $handle, 0, $this->params['delimiter'], $this->params['enclosure'], $this->params['escape'] ) ) : array_map( 'trim', fgetcsv( $handle, 0, $this->params['delimiter'], $this->params['enclosure'] ) );

			// Remove BOM signature from the first item.
			if ( isset( $this->raw_keys[0] ) ) {
				if ( 'efbbbf' === substr( bin2hex( $this->raw_keys[0] ), 0, 6 ) ) {
					$this->raw_keys[0] = str_replace( $this->params['enclosure'], '', substr( $this->raw_keys[0], 3 ) );
				}
			}

			if ( 0 !== $this->params['position'] ) {
				fseek( $handle, (int) $this->params['position'] );
			}

			while ( 1 ) {
				$row = version_compare( PHP_VERSION, '5.3', '>=' ) ? fgetcsv( $handle, 0, $this->params['delimiter'], $this->params['enclosure'], $this->params['escape'] ) : fgetcsv( $handle, 0, $this->params['enclosure'], $this->params['escape'] );

				if ( false !== $row ) {
					$this->raw_data[]                            = $row;
					$this->positions[ count( $this->raw_data ) ] = ftell( $handle );

					if ( ( $this->params['end_position'] > 0 && ftell( $handle ) >= $this->params['end_position'] ) || 0 === -- $this->params['limit'] ) {
						break;
					}
				} else {
					break;
				}
			}

			$this->params['position'] = ftell( $handle );
		}

		$this->set_mapped_keys();

		if ( $this->params['parse'] ) {
			$this->set_parsed_data();
		}
	}

	/**
	 * Set csv keys to database equivalent column.
	 *
	 * First match csv column to database column then
	 * Check if those keys are allowed.
	 *
	 * @since 1.0.2
	 */
	protected function set_mapped_keys() {
		$mapping = array_flip( $this->mapping );
		foreach ( $this->raw_keys as $key ) {
			$mapped_key          = isset( $mapping[ $key ] ) ? $mapping[ $key ] : '';
			$this->mapped_keys[] = isset( $this->headers[ $mapped_key ] ) ? $mapped_key : '';
		}
	}

	/**
	 * Map and format raw data to known fields.
	 */
	protected function set_parsed_data() {
		$parse_functions = $this->formatting_callback();
		$mapped_keys     = $this->get_mapped_keys();
		$use_mb          = function_exists( 'mb_convert_encoding' );
		// Parse the data.
		foreach ( $this->raw_data as $row_index => $row ) {
			// Skip empty rows.
			if ( ! count( array_filter( $row ) ) ) {
				continue;
			}

			$this->parsing_raw_data_index = $row_index;

			$data = array();

			foreach ( $row as $id => $value ) {
				// Skip ignored columns.
				if ( empty( $mapped_keys[ $id ] ) ) {
					continue;
				}

				// Convert UTF8.
				if ( $use_mb ) {
					$encoding = mb_detect_encoding( $value, mb_detect_order(), true );
					if ( $encoding ) {
						$value = mb_convert_encoding( $value, 'UTF-8', $encoding );
					} else {
						$value = mb_convert_encoding( $value, 'UTF-8', 'UTF-8' );
					}
				} else {
					$value = wp_check_invalid_utf8( $value, true );
				}

				$data[ $mapped_keys[ $id ] ] = call_user_func( $parse_functions[ $id ], $this->unescape_data( $value ) );
			}

			// make all fields filled with empty.
			$default             = array_fill_keys( array_keys( $this->headers ), '' );
			$this->parsed_data[] = wp_parse_args( $data, $default );
		}

	}


	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function formatting_callback() {
		/**
		 * Columns not mentioned here will get parsed with 'wc_clean'.
		 * column_name => callback.
		 */
		$data_formatting = $this->get_formatting_callback();

		$callbacks = array();

		// Figure out the parse function for each column.
		foreach ( $this->get_mapped_keys() as $index => $heading ) {
			$callback = array( $this, 'parse_text_field' );

			if ( isset( $data_formatting[ $heading ] ) ) {
				$callback = $data_formatting[ $heading ];
			}

			$callbacks[] = $callback;
		}

		return $callbacks;
	}

	/**
	 * Import data.
	 *
	 * @return int[]
	 * @since 1.0.2
	 */
	public function import() {
		$this->start_time = time();
		$index            = 0;

		$data = array(
			'imported' => 0,
			'skipped'  => 0,
		);

		foreach ( $this->parsed_data as $parsed_data_key => $parsed_data ) {

			$result = $this->import_item( $parsed_data );
			if ( ! is_wp_error( $result ) && $result ) {
				$data['imported'] = (int) $data['imported'] + 1;
			} else {
				$data['skipped'] = (int) $data['skipped'] + 1;
			}

			$index ++;

			if ( $this->params['prevent_timeouts'] && ( $this->time_exceeded() || $this->memory_exceeded() ) ) {
				$this->position = $this->positions[ $index ];
				break;
			}
		}

		return $data;
	}


	/**
	 * Get file mapped headers.
	 *
	 * @return array
	 */
	public function get_mapped_keys() {
		return ! empty( $this->mapped_keys ) ? $this->mapped_keys : $this->raw_keys;
	}

	/**
	 * Get file raw headers.
	 *
	 * @return array
	 */
	public function get_raw_keys() {
		return $this->raw_keys;
	}

	/**
	 * Get raw data.
	 *
	 * @return array
	 */
	public function get_raw_data() {
		return $this->raw_data;
	}

	/**
	 * Get raw data.
	 *
	 * @return array
	 */
	public function get_parsed_data() {
		return $this->parsed_data;
	}

	/**
	 * Get sample data.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_sample() {
		return current( $this->get_raw_data() );
	}

	/**
	 * Get file pointer position from the last read.
	 *
	 * @return int
	 */
	public function get_position() {
		return $this->params['position'];
	}

	/**
	 * Get file pointer position as a percentage of file size.
	 *
	 * @return int
	 */
	public function get_percent_complete() {
		$size = filesize( $this->file );
		if ( ! $size || ! $this->params['position'] ) {
			return 0;
		}

		$percent = absint( min( round( ( $this->params['position'] / $size ) * 100 ), 100 ) );
		if ( 100 === $percent ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$wp_filesystem->delete( $this->file );
		}

		return $percent;
	}

	/**
	 * Time exceeded.
	 *
	 * Ensures the batch never exceeds a sensible time limit.
	 * A timeout limit of 30s is common on shared hosting.
	 *
	 * @return bool
	 */
	protected function time_exceeded() {
		$finish = $this->start_time + 20; // 20 seconds
		$return = false;
		if ( time() >= $finish ) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Memory exceeded
	 *
	 * Ensures the batch process never exceeds 90%
	 * of the maximum WordPress memory.
	 *
	 * @return bool
	 */
	protected function memory_exceeded() {
		$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
		$current_memory = memory_get_usage( true );
		$return         = false;
		if ( $current_memory >= $memory_limit ) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Get memory limit
	 *
	 * @return int
	 */
	protected function get_memory_limit() {
		if ( function_exists( 'ini_get' ) ) {
			$memory_limit = ini_get( 'memory_limit' );
		} else {
			// Sensible default.
			$memory_limit = '128M';
		}

		if ( ! $memory_limit || - 1 === intval( $memory_limit ) ) {
			// Unlimited, set to 32GB.
			$memory_limit = '32000M';
		}

		return intval( $memory_limit ) * 1024 * 1024;
	}

	/**
	 * The exporter prepends a ' to escape fields that start with =, +, - or @.
	 * Remove the prepended ' character preceding those characters.
	 *
	 * @param string $value A string that may or may not have been escaped with '.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	protected function unescape_data( $value ) {
		$active_content_triggers = array( "'=", "'+", "'-", "'@" );

		if ( in_array( mb_substr( $value, 0, 2 ), $active_content_triggers, true ) ) {
			$value = mb_substr( $value, 1 );
		}

		return $value;
	}

	/**
	 * Parse generalized text field.
	 *
	 * @param string $value Value.
	 *
	 * @return array|string
	 * @since 1.0.2
	 */
	public function parse_text_field( $value ) {
		return eaccounting_clean( $this->unescape_data( $value ) );
	}

	/**
	 * Parse a field that is generally '1' or '0' but can be something else.
	 *
	 * @param string $value Field value.
	 *
	 * @return bool|string
	 * @since 1.0.2
	 */
	public function parse_bool_field( $value ) {
		if ( '0' === $value ) {
			return false;
		}

		if ( '1' === $value ) {
			return true;
		}

		// Don't return explicit true or false for empty fields or values like 'notify'.
		return eaccounting_clean( $value );
	}

	/**
	 * Parse a float value field.
	 *
	 * @param string $value Field value.
	 *
	 * @return float|string
	 * @since 1.0.2
	 */
	public function parse_float_field( $value ) {
		if ( '' === $value ) {
			return $value;
		}

		// Remove the ' prepended to fields that start with - if needed.
		$value = $this->unescape_data( $value );

		return floatval( $value );
	}

	/**
	 * Parse dates from a CSV.
	 * Dates requires the format YYYY-MM-DD and time is optional.
	 *
	 * @param string $value Field value.
	 *
	 * @return string|null
	 * @since 1.0.2
	 */
	public function parse_date_field( $value ) {
		if ( empty( $value ) ) {
			return null;
		}

		if ( preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])([ 01-9:]*)$/', $value ) ) {
			// Don't include the time if the field had time in it.
			return current( explode( ' ', $value ) );
		}

		return null;
	}

	/**
	 * Just skip current field.
	 *
	 * By default is applied eaccounting_clean() to all not listed fields
	 * in self::get_formatting_callback(), use this method to skip any formatting.
	 *
	 * @param string $value Field value.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function parse_skip_field( $value ) {
		return $value;
	}

	/**
	 * Parse an int value field
	 *
	 * @param int $value field value.
	 *
	 * @return int
	 * @since 1.0.2
	 */
	public function parse_int_field( $value ) {
		// Remove the ' prepended to fields that start with - if needed.
		$value = $this->unescape_data( $value );

		return intval( $value );
	}

	/**
	 * Parse a description value field
	 *
	 * @param string $description field value.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function parse_description_field( $description ) {
		$parts = explode( "\\\\n", $description );
		foreach ( $parts as $key => $part ) {
			$parts[ $key ] = str_replace( '\n', "\n", $part );
		}

		return implode( '\\\n', $parts );
	}

	/**
	 * Parse a country value field
	 *
	 * @param string $country field value.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function parse_country_field( $country ) {
		$country = eaccounting_clean( $country );

		return array_key_exists( $country, eaccounting_get_countries() ) ? $country : '';
	}

	/**
	 * Parse a currency code value field
	 *
	 * @param string $currency field value.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function parse_currency_code_field( $currency ) {
		$currency = eaccounting_clean( $currency );
		$exist    = eaccounting_get_currency( $currency );

		return $exist->get_id() ? $currency : '';
	}

}
