<?php
/** MainWP Child Reports WPDB Driver. */

namespace WP_MainWP_Stream;

/**
 * Class DB_Driver_WPDB.
 * @package WP_MainWP_Stream
 *
 * @uses \WP_MainWP_Stream\DB_Driver
 */
class DB_Driver_WPDB implements DB_Driver {
	/**
	 * Holds Query class
	 *
	 * @var Query
	 */
	protected $query;

	/**
	 * Hold records table name
	 *
	 * @var string
	 */
	public $table;

	/**
	 * Hold meta table name
	 *
	 * @var string
	 */
	public $table_meta;

	/**
	 * DB_Driver_WPDB constructor.
	 *
	 * Run each time the class is called.
	 *
	 * @uses \WP_MainWP_Stream\Query
	 */
	public function __construct() {
		$this->query = new Query( $this );

		/** @global object $wpdb WordPress Database instance. */
		global $wpdb;

		$prefix = apply_filters( 'wp_mainwp_stream_db_tables_prefix', $wpdb->base_prefix );

		$this->table      = $prefix . 'mainwp_stream';
		$this->table_meta = $prefix . 'mainwp_stream_meta';

		$wpdb->mainwp_stream     = $this->table;
		$wpdb->mainwp_streammeta = $this->table_meta;

		// Hack for get_metadata.
		$wpdb->recordmeta = $this->table_meta;
	}

	/**
	 * Insert a record.
	 *
	 * @param array $data Data to insert.
	 *
	 * @return int
	 */
	public function insert_record( $data ) {

		/** @global object $wpdb WordPress Database instance. */
		global $wpdb;

		if ( defined( 'WP_IMPORTING' ) && WP_IMPORTING ) {
			return false;
		}

		$meta = $data['meta'];
		unset( $data['meta'] );

		$result = $wpdb->insert( $this->table, $data );
		if ( ! $result ) {
			return false;
		}

		$record_id = $wpdb->insert_id;

		// Insert record meta
		foreach ( (array) $meta as $key => $vals ) {
			foreach ( (array) $vals as $val ) {
				$this->insert_meta( $record_id, $key, $val );
			}
		}

		return $record_id;
	}

	/**
	 * Insert record meta
	 *
	 * @param int    $record_id
	 * @param string $key
	 * @param string $val
	 *
	 * @return array
	 */
	public function insert_meta( $record_id, $key, $val ) {

		/** @global object $wpdb WordPress Database instance. */
		global $wpdb;

		$result = $wpdb->insert(
			$this->table_meta,
			array(
				'record_id'  => $record_id,
				'meta_key'   => $key,
				'meta_value' => $val,
			)
		);

		return $result;
	}

	/**
	 * Retrieve records
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_records( $args ) {
		return $this->query->query( $args );
	}

	/**
	 * Returns array of existing values for requested column.
	 * Used to fill search filters with only used items, instead of all items.
	 *
	 * GROUP BY allows query to find just the first occurrence of each value in the column,
	 * increasing the efficiency of the query.
	 *
	 * @param string $column
	 *
	 * @return array
	 */
	public function get_column_values( $column ) {

		/** @global object $wpdb WordPress Database instance. */
		global $wpdb;

		return (array) $wpdb->get_results(
			"SELECT DISTINCT $column FROM $wpdb->mainwp_stream", // @codingStandardsIgnoreLine can't prepare column name
			'ARRAY_A'
		);
	}

	/**
	 * Public getter to return table names
	 *
	 * @return array
	 */
	public function get_table_names() {
		return array(
			$this->table,
			$this->table_meta,
		);
	}

	/**
	 * Init storage.
	 *
	 * @param \WP_MainWP_Stream\Plugin $plugin Instance of the plugin.
	 * @return \WP_MainWP_Stream\Install
	 *
	 * @uses \WP_MainWP_Stream\Install
	 */
	public function setup_storage( $plugin ) {
		return new Install( $plugin );
	}

	/**
	 * Purge storage.
	 *
	 * @param \WP_MainWP_Stream\Plugin $plugin Instance of the plugin.
	 * @return \WP_MainWP_Stream\Uninstall
	 *
	 * @uses \WP_MainWP_Stream\Uninstall
	 */
	public function purge_storage( $plugin ) {
		$uninstall = new Uninstall( $plugin );
		add_action( 'wp_ajax_wp_mainwp_stream_uninstall', array( $uninstall, 'ajax_uninstall' ) );

		return $uninstall;
	}

}
