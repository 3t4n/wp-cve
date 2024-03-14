<?php
/**
 * WC_Product_Sells
 *
 * Process WooCommerce cross sells and up sells
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Settings;
use WP_DataSync\App\Log;

class WC_Product_Sells {

	/**
	 * @var string
	 */

	private $type;

	/**
	 * @var array
	 */

	private $sell_ids;

	/**
	 * @var string
	 */

	private $relational_id;

	/**
	 * @var string
	 */

	private $relational_key;

	/**
	 * @var int
	 */

	private $product_id;

	/**
	 * @var WC_Product_Sells
	 */

	public static $instance;

	/**
	 * WC_Product_Sells constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return WC_Product_Sells
	 */

	public static function instance() {

		if ( self::$instance === NULL ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Set properties.
	 *
	 * @param $values array
	 */

	public function set_properties( $values ) {

		foreach ( $values as $key => $value ) {
			$this->$key = $value;
		}

		return Settings::is_checked( "wp_data_sync_process_$this->type" );

	}

	/**
	 * Set the sell ID relationship.
	 */

	public function set_relation() {
		update_post_meta( $this->product_id, $this->relational_key, $this->relational_id );
	}

	/**
	 * Set sell IDs.
	 */

	public function stage_sell_ids() {

		if ( is_array( $this->sell_ids ) ) {

			foreach ( $this->sell_ids as $sell_id ) {

				if ( ! $this->sell_id_exists( $sell_id ) ) {

					$this->insert_sell_id( $sell_id );

					Log::write( 'product-sells', $sell_id, 'Insert Sell ID' );

				}

			}

		}

	}

	/**
	 * Sell id exists.
	 *
	 * @param $sell_id
	 *
	 * @return bool
	 */

	public function sell_id_exists( $sell_id ) {

		global $wpdb;

		$table = self::table();

		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT id
				FROM $table
				WHERE type = %s
				AND sell_id = %s
				AND product_id = %d
				AND relational_id = %s
				AND relational_key = %s
				",
				esc_sql( $this->type ),
				esc_sql( $sell_id ),
				intval( $this->product_id ),
				esc_sql( $this->relational_id ),
				esc_sql( $this->relational_key )
			)
		);

		if ( empty( $exists ) || is_wp_error( $exists ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Insert sell id.
	 *
	 * @param $sell_id
	 */

	public function insert_sell_id( $sell_id ) {

		global $wpdb;

		$wpdb->insert( self::table(), [
			'type'           => $this->type,
			'sell_id'        => $sell_id,
			'product_id'     => $this->product_id,
			'relational_id'  => $this->relational_id,
			'relational_key' => $this->relational_key
		] );

	}

	/**
	 * Relate the unrelated IDs.
	 */

	public function relate_ids() {

		if ( $unrelated = $this->get_unrelated() ) {

			foreach ( $unrelated as $row ) {

				if ( $post_id = $this->relation_exists( $row ) ) {

					$this->update_relation( $row, $post_id );
					$this->set_product_ids( $row, $post_id );

					Log::write( 'product-sells', $row, "Ralation Exists: $post_id" );

				}

			}

		}

	}

	/**
	 * Get the unrelated rows.
	 *
	 * @return array|bool|object
	 */

	public function get_unrelated() {

		global $wpdb;

		$table = self::table();

		$unrelated = $wpdb->get_results(
			"
			SELECT *
			FROM $table
			WHERE post_id = 0
			"
		);

		if ( null === $unrelated || is_wp_error( $unrelated ) ) {
			return false;
		}

		return $unrelated;

	}

	/**
	 * Relation exists.
	 *
	 * @param $row
	 *
	 * @return bool|int
	 */

	public function relation_exists( $row ) {

		global $wpdb;

		$post_id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT post_id
				FROM $wpdb->postmeta
				WHERE meta_key = %s
				AND meta_value = %s
				",
				esc_sql( $row->relational_key ),
				esc_sql( $row->sell_id )
			)
		);

		if ( null === $post_id || is_wp_error( $post_id ) ) {
			return false;
		}

		return (int) $post_id;

	}

	/**
	 * Update relation.
	 *
	 * @param $row
	 * @param $post_id
	 */

	public function update_relation( $row, $post_id ) {

		global $wpdb;

		$wpdb->update(
			self::table(),
			[ 'post_id' => $post_id ],
			[ 'id'      => $row->id ]
		);

	}

	/**
	 * Set product IDs.
	 *
	 * @param $row
	 * @param $post_id
	 */

	public function set_product_ids( $row, $post_id ) {

		if( $product_ids = $this->get_product_ids( $row, $post_id ) ) {

			update_post_meta( $post_id, $row->type, $product_ids );

			Log::write( 'product-sells', $product_ids, "Ralated $row->type IDs: $post_id" );

		}

	}

	/**
	 * Get product IDs.
	 *
	 * @param $row
	 * @param $post_id
	 *
	 * @return array|bool
	 */

	public function get_product_ids( $row, $post_id ) {

		global $wpdb;

		$table = self::table();

		$product_ids = $wpdb->get_col( $wpdb->prepare(
			"
			SELECT product_id
			FROM $table
			WHERE post_id = %d
			AND type = %s
			",
			intval( $post_id ),
			esc_sql( $row->type )
		) );

		if ( empty( $product_ids ) || is_wp_error( $product_ids ) ) {
			return false;
		}

		return $product_ids;

	}

	/**
	 * Save the sell ids.
	 */

	public function save() {

		$this->set_relation();
		$this->stage_sell_ids();
		$this->relate_ids();

	}

	/**
	 * Database tabke name.
	 *
	 * @return string
	 */

	public static function table() {

		global $wpdb;

		return $wpdb->prefix . 'data_sync_product_sells';

	}

	/**
	 * Create the sells database table.
	 */

	public static function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
		$table           = self::table();

		$sql = "
			CREATE TABLE IF NOT EXISTS $table (
  			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  			type varchar(40) NOT NULL,
  			sell_id varchar(300) NOT NULL,
  			product_id bigint(20) NOT NULL,
  			relational_id varchar(300) NOT NULL,
  			relational_key varchar(300) NOT NULL,
  			post_id bigint(20) NOT NULL DEFAULT 0,
  			PRIMARY KEY (id),
  			KEY type (type),
			KEY sell_id (sell_id),
			KEY relational_id (relational_id),
			KEY relational_key (relational_key)
			) $charset_collate;
        ";

		dbDelta( $sql );

	}

}