<?php
/**
 * Class for logging events and errors
 *
 * @package     Card_Oracle
 * @subpackage  Logging
 * @copyright   Copyright (c) 2015, Pippin Williamson, (c) 2020 Christopher Graham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.24.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CO_Logging Class
 *
 * A general use class for logging events and errors.
 *
 * @since 0.16.0
 */
class CO_Logging {

	/**
	 * Flag for directory
	 *
	 * @since 0.16.0
	 * @var boolean $is_writable Flag of whether the log directory is writing.
	 */
	public $is_writable = true;

	/**
	 * Filename for log.
	 *
	 * @since 0.16.0
	 * @var string  $filename Name of log file.
	 */
	private $filename = '';

	/**
	 * File handle for log.
	 *
	 * @since 0.16.0
	 * @var string  $file File handle for log file.
	 */
	private $file = '';

	/**
	 * Set up the Card Oracle Logging Class
	 *
	 * @since 0.16.0
	 */
	public function __construct() {

		// Create the log post type.
		add_action( 'init', array( $this, 'register_post_type' ), 1 );

		// Create types taxonomy and default types.
		add_action( 'init', array( $this, 'register_taxonomy' ), 1 );

		add_action( 'plugins_loaded', array( $this, 'setup_log_file' ), 0 );

	}

	/**
	 * Sets up the log file if it is writable
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function setup_log_file() {
		$this->filename = wp_hash( home_url( '/' ) ) . '-debug.log';
		$this->file     = trailingslashit( CARD_ORACLE_LOG_DIR ) . $this->filename;

		if ( ! is_writeable( CARD_ORACLE_LOG_DIR ) ) {
			$this->is_writable = false;
		}
	}

	/**
	 * Registers the co_log Post Type
	 *
	 * @since 0.16.0
	 * @return void
	 */
	public function register_post_type() {
		/* Logs post type */
		$log_args = array(
			'labels'              => array( 'name' => __( 'Logs', 'card-oracle' ) ),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => false,
			'query_var'           => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor' ),
			'can_export'          => true,
		);

		register_post_type( 'co_log', $log_args );
	}

	/**
	 * Registers the Type Taxonomy
	 *
	 * The "Type" taxonomy is used to determine the type of log entry
	 *
	 * @since 0.16.0
	 * @return void
	 */
	public function register_taxonomy() {
		register_taxonomy( 'co_log_type', 'co_log', array( 'public' => false ) );
	}

	/**
	 * Log types
	 *
	 * Sets up the default log types and allows for new ones to be created
	 *
	 * @since 0.25.0
	 * @return  array $terms
	 */
	public function log_types() {
		$terms = array(
			'api_request',
			'error',
			'event',
			'sale',
		);

		return apply_filters( 'co_log_types', $terms );
	}

	/**
	 * Check if a log type is valid
	 *
	 * Checks to see if the specified type is in the registered list of types
	 *
	 * @since 0.16.0
	 * @uses CO_Logging::log_types()
	 * @param string $type Log type.
	 * @return bool Whether log type is valid
	 */
	public function valid_type( $type ) {
		return in_array( $type, $this->log_types(), true );
	}

	/**
	 * Create new log entry
	 *
	 * This is just a simple and fast way to log something. Use $this->insert_log()
	 * if you need to store custom meta data
	 *
	 * @since 0.16.0
	 * @uses CO_Logging::insert_log()
	 * @param string $title Log entry title.
	 * @param string $message Log entry message.
	 * @param int    $parent Log entry parent.
	 * @param string $type Log type (default: null).
	 * @return int Log ID
	 */
	public function add( $title = '', $message = '', $parent = 0, $type = null ) {
		$log_data = array(
			'post_title'   => $title,
			'post_content' => $message,
			'post_parent'  => $parent,
			'log_type'     => $type,
		);

		return $this->insert_log( $log_data );
	}

	/**
	 * Easily retrieves log items for a particular object ID
	 *
	 * @since 0.16.0
	 * @uses CO_Logging::get_connected_logs()
	 * @param int    $object_id (default: 0).
	 * @param string $type Log type (default: null).
	 * @param int    $paged Page number (default: null).
	 * @return array Array of the connected logs
	 */
	public function get_logs( $object_id = 0, $type = null, $paged = null ) {
		return $this->get_connected_logs(
			array(
				'post_parent' => $object_id,
				'paged'       => $paged,
				'log_type'    => $type,
			)
		);
	}

	/**
	 * Run a quick check to see if the debug log constant is set.
	 *
	 * @since 1.1.1
	 * @return boolean
	 */
	public function has_debug_constant() {
		return defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ? true : false;
	}

	/**
	 * Run a quick check to see if the debug log file is empty.
	 *
	 * @since 0.24.1
	 * @return boolean
	 */
	public function check_file_data( $file = '' ) {
		// If no file specified use debug.log file.
		if ( ! $file ) {
			$file = WP_CONTENT_DIR . '/debug.log';
		}

		// If the constant isn't set, return false right away.
		if ( ! $this->has_debug_constant() ) {
			return false;
		}

		// If no file exists at all, create an empty one.
		if ( false === file_exists( $file ) ) {
			file_put_contents( $file, '' );
		}

		// If the file is empty, return that.
		return 0 === filesize( $file ) ? false : true;
	}

	/**
	 * Stores a log entry
	 *
	 * @since 0.16.0
	 * @uses CO_Logging::valid_type()
	 * @param array $log_data Log entry data.
	 * @param array $log_meta Log entry meta.
	 * @return int The ID of the newly created log item
	 */
	public function insert_log( $log_data = array(), $log_meta = array() ) {
		$defaults = array(
			'post_type'    => 'co_log',
			'post_status'  => 'publish',
			'post_parent'  => 0,
			'post_content' => '',
			'log_type'     => false,
		);

		$args = wp_parse_args( $log_data, $defaults );

		do_action( 'co_pre_insert_log', $log_data, $log_meta );

		// Store the log entry.
		$log_id = wp_insert_post( $args );

		// Set the log type, if any.
		if ( $log_data['log_type'] && $this->valid_type( $log_data['log_type'] ) ) {
			wp_set_object_terms( $log_id, $log_data['log_type'], 'co_log_type', false );
		}

		// Set log meta, if any.
		if ( $log_id && ! empty( $log_meta ) ) {
			foreach ( (array) $log_meta as $key => $meta ) {
				update_post_meta( $log_id, '_co_log_' . sanitize_key( $key ), $meta );
			}
		}

		do_action( 'co_post_insert_log', $log_id, $log_data, $log_meta );

		return $log_id;
	}

	/**
	 * Update and existing log item
	 *
	 * @since 0.16.0
	 * @param array $log_data Log entry data.
	 * @param array $log_meta Log entry meta.
	 */
	public function update_log( $log_data = array(), $log_meta = array() ) {

		do_action( 'co_pre_update_log', $log_data, $log_meta );

		$defaults = array(
			'post_type'   => 'co_log',
			'post_status' => 'publish',
			'post_parent' => 0,
		);

		$args = wp_parse_args( $log_data, $defaults );

		// Store the log entry.
		$log_id = wp_update_post( $args );

		if ( $log_id && ! empty( $log_meta ) ) {
			foreach ( (array) $log_meta as $key => $meta ) {
				if ( ! empty( $meta ) ) {
					update_post_meta( $log_id, '_co_log_' . sanitize_key( $key ), $meta );
				}
			}
		}

		do_action( 'co_post_update_log', $log_id, $log_data, $log_meta );
	}

	/**
	 * Our abstracted function for viewing the log file.
	 *
	 * @since 1.1.1
	 * @param  string $file  The filepath we are working with.
	 */
	public function view_log( $file = '' ) {
		// If no file specified use debug.log file.
		if ( ! $file ) {
			$file = WP_CONTENT_DIR . '/debug.log';
		}

		// Check to make sure we have a file.
		if ( ! $this->check_file_data( $file ) ) {
			$data = '<tr><td>' . esc_html__( 'Your debug file is empty.', 'card-oracle' ) . '</td></tr>';
		}

		// Parse out the data.
		$data = $this->parse_log( $file );

		// Trim and break it up.
		$data = nl2br( trim( $data ) );

		// Now convert the line break markup to an empty div.
		$data = str_replace( array( '<br>', '<br />' ), '</td></tr><tr><td>', $data );

		// Convert my pre tags to spans so we can style them.
		$data = str_replace( array( '<pre>', '</pre>' ), array( '<span class="prewrap">', '</span>' ), $data );

		// Generate and return the actual output.
		echo '<p class="codeblock"><code>' . wp_kses_post( $data ) . '</code></p>';
	}

	/**
	 * Parse my log file from the end in case it's too big.
	 *
	 * @link http://stackoverflow.com/questions/6451232/php-reading-large-files-from-end/6451391#6451391
	 *
	 * @param  string  $file   The filepath we are working with.
	 * @param  integer $count  Our line count that we're working with.
	 * @param  integer $size   How many bytes we safely wanna check.
	 *
	 * @return string
	 */
	public function parse_log( $file = '', $count = 100, $size = 512 ) {

		// Set my empty.
		$lines = array();

		// We will always have a fragment of a non-complete line, so keep this in here till we have our next entire line.
		$left = '';

		// Open our file.
		$readf = fopen( $file, 'r' );

		// Go to the end of the file.
		fseek( $readf, 0, SEEK_END );

		do {

			// Confirm we can actually go back $size bytes.
			$check = $size;

			if ( ftell( $readf ) <= $size ) {
				$check = ftell( $readf );
			}

			// Bail on an empty file.
			if ( empty( $check ) ) {
				break;
			}

			// Go back as many bytes as we can and read them to $data,
			// and then move the file pointer back to where we were.
			fseek( $readf, - $check, SEEK_CUR );

			// Set the data.
			$data  = fread( $readf, $check );

			// Include the "leftovers".
			$data .= $left;

			// Seek back into it.
			fseek( $readf, - $check, SEEK_CUR );

			// Split lines by \n. Then reverse them, now the last line is most likely
			// not a complete line which is why we do not directly add it, but
			// append it to the data read the next time.
			$split = array_reverse( explode( "\n", $data ) );
			$newls = array_slice( $split, 0, - 1 );
			$lines = array_merge( $lines, $newls );
			$left  = $split[ count( $split ) - 1 ];

		} while ( count( $lines ) < $count && ftell( $readf ) !== 0 );

		// Check and add the extra line.
		if ( ftell( $readf ) == 0 ) {
			$lines[] = $left;
		}

		// Close the file we just dealt with.
		fclose( $readf );

		// Usually, we will read too many lines, correct that here.
		$array = array_slice( $lines, 0, $count );
		$array = array_reverse( array_filter( $array, 'strlen' ) );

		// Convert my array to a large string.
		return implode( "\n", $array );
	}

	/**
	 * Retrieve all connected logs
	 *
	 * Used for retrieving logs related to particular items, such as a specific purchase.
	 *
	 * @access private
	 * @since 0.16.0
	 * @param array $args Query arguments.
	 * @return mixed array if logs were found, false otherwise
	 */
	public function get_connected_logs( $args = array() ) {
		$defaults = array(
			'post_type'      => 'co_log',
			'posts_per_page' => 20,
			'post_status'    => 'publish',
			'paged'          => get_query_var( 'paged' ),
			'log_type'       => false,
		);

		$query_args = wp_parse_args( $args, $defaults );

		if ( $query_args['log_type'] && $this->valid_type( $query_args['log_type'] ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'co_log_type',
					'field'    => 'slug',
					'terms'    => $query_args['log_type'],
				),
			);
		}

		$logs = get_posts( $query_args );

		if ( $logs ) {
			return $logs;
		}

		// No logs found.
		return false;
	}

	/**
	 * Retrieves number of log entries connected to particular object ID
	 *
	 * @since 0.16.0
	 * @param int    $object_id (default: 0).
	 * @param string $type Log type (default: null).
	 * @param array  $meta_query Log meta query (default: null).
	 * @param array  $date_query Log data query (default: null) (since 1.9).
	 * @return int Log count
	 */
	public function get_log_count( $object_id = 0, $type = null, $meta_query = null, $date_query = null ) {

		$query_args = array(
			'post_parent'    => $object_id,
			'post_type'      => 'co_log',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		);

		if ( ! empty( $type ) && $this->valid_type( $type ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'co_log_type',
					'field'    => 'slug',
					'terms'    => $type,
				),
			);
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		if ( ! empty( $date_query ) ) {
			$query_args['date_query'] = $date_query;
		}

		$logs = new WP_Query( $query_args );

		return (int) $logs->post_count;
	}

	/**
	 * Delete a log
	 *
	 * @since 0.16.0
	 * @uses CO_Logging::valid_type
	 * @param int    $object_id (default: 0).
	 * @param string $type Log type (default: null).
	 * @param array  $meta_query Log meta query (default: null).
	 * @return void
	 */
	public function delete_logs( $object_id = 0, $type = null, $meta_query = null ) {
		$query_args = array(
			'post_parent'    => $object_id,
			'post_type'      => 'co_log',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		);

		if ( ! empty( $type ) && $this->valid_type( $type ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'co_log_type',
					'field'    => 'slug',
					'terms'    => $type,
				),
			);
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		$logs = get_posts( $query_args );

		if ( $logs ) {
			foreach ( $logs as $log ) {
				wp_delete_post( $log, true );
			}
		}
	}

	/**
	 * Retrieve the log data
	 *
	 * @since 2.8.7
	 * @return string
	 */
	public function get_file_contents() {
		return $this->get_file();
	}

	/**
	 * Log message to file
	 *
	 * @since 2.8.7
	 * @param string $message Message to log.
	 * @return void
	 */
	public function log_to_file( $message = '' ) {
		$message = gmdate( 'Y-M-d H:i:s' ) . ' - ' . $message . "\r\n";
		$this->write_to_log( $message );
	}

	/**
	 * Retrieve the file data is written to
	 *
	 * @since 2.8.7
	 * @return string
	 */
	protected function get_file() {
		$file = '';

		if ( file_exists( $this->file ) ) {
			if ( ! is_writeable( $this->file ) ) {
				$this->is_writable = false;
			}
			$file = file_get_contents( $this->file ); // WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		} else {
			file_put_contents( $this->file, '' ); // WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
			chmod( $this->file, 0664 );
		}

		return $file;
	}

	/**
	 * Write the log message
	 *
	 * @since 2.8.7
	 * @param string $message Message to log.
	 * @return void
	 */
	protected function write_to_log( $message = '' ) {
		$file  = $this->get_file();
		$file .= $message;
		@file_put_contents( $this->file, $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
	}

	/**
	 * Delete the log file or removes all contents in the log file if we cannot delete it
	 *
	 * @since 2.8.7
	 * @return boolean
	 */
	public function clear_log_file() {
		unlink( $this->file );

		if ( file_exists( $this->file ) ) {

			// it's still there, so maybe server doesn't have delete rights.
			chmod( $this->file, 0664 ); // Try to give the server delete rights.
			unlink( $this->file );

			// See if it's still there.
			if ( file_exists( $this->file ) ) {
				// Remove all contents of the log file if we cannot delete it.
				if ( ! is_writeable( $this->file ) ) {
					return false;
				}

				file_put_contents( $this->file, '' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
			}
		}

		$this->file = '';
		return true;
	}

	/**
	 * Return the location of the log file that CO_Logging will use.
	 *
	 * Note: Do not use this file to write to the logs, please use the `co_debug_log` function to do so.
	 *
	 * @since 2.9.1
	 *
	 * @return string
	 */
	public function get_log_file_path() {
		return $this->file;
	}
}

// Initiate the logging system.
$GLOBALS['co_logs'] = new CO_Logging();

/**
 * Record a log entry
 *
 * This is just a simple wrapper function for the log class add() function
 *
 * @since 1.3.3
 *
 * @param string $title Post title.
 * @param string $message Message to log.
 * @param int    $parent Parent post, if any.
 * @param null   $type Message type.
 *
 * @global $co_logs Card Oracle Logs Object
 *
 * @uses CO_Logging::add()
 *
 * @return mixed ID of the new log entry
 */
function co_record_log( $title = '', $message = '', $parent = 0, $type = null ) {
	global $co_logs;
	$log = $co_logs->add( $title, $message, $parent, $type );
	return $log;
}

/**
 * Logs a message to the debug log file
 *
 * @since 0.16.0 Added the 'force' option.
 *
 * @param string  $message Message to log.
 * @param boolean $json_flags Change the json_encode output with the flags.
 * @global $co_logs Card Oracle Logs Object
 * @return void
 */
function co_debug_log( $message = '', $json_flags = 0 ) {
	global $co_logs;

	if ( ! $co_logs->has_debug_constant() ) {
		return;
	}

	$message = is_array( $message ) ? wp_json_encode( $message, $json_flags ) : $message;

	if ( function_exists( 'mb_convert_encoding' ) ) {
		$message = mb_convert_encoding( $message, 'UTF-8' );
	}

	$co_logs->log_to_file( $message );
}
