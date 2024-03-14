<?php
/**
 * Database
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta;

/**
 * Class DB
 *
 * @package NovaPoshta
 */
class DB {

	/**
	 * Table name for cities
	 *
	 * @var string
	 */
	private $cities_table;

	/**
	 * Table name for warehouses
	 *
	 * @var string
	 */
	private $warehouses_table;

	/**
	 * Language object
	 *
	 * @var Language
	 */
	private $language;

	/**
	 * DB constructor.
	 *
	 * @param Language $language Language object.
	 */
	public function __construct( Language $language ) {

		global $wpdb;
		$this->cities_table     = $wpdb->prefix . 'np_cities';
		$this->warehouses_table = $wpdb->prefix . 'np_warehouses';
		$this->language         = $language;
	}

	/**
	 * Delete tables
	 */
	public function drop() {

		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->warehouses_table ); // phpcs:ignore
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->cities_table ); // phpcs:ignore
	}

	/**
	 * Create tables
	 */
	public function create() {

		global $wpdb;
		$cities_sql = 'CREATE TABLE ' . $this->cities_table . '
			(
		        city_id               VARCHAR(36)  NOT NULL UNIQUE,
		        description_ru        VARCHAR(100) NOT NULL,
		        description_ua        VARCHAR(100) NOT NULL,
		        area                  VARCHAR(100) NOT NULL
	        ) ' . $wpdb->get_charset_collate();

		$warehouses_sql = 'CREATE TABLE ' . $this->warehouses_table . '
			(
		        `warehouse_id`        VARCHAR(36)  NOT NULL UNIQUE,
		        `city_id`             VARCHAR(36)  NOT NULL,
		        `description_ru`      VARCHAR(100) NOT NULL,
		        `description_ua`      VARCHAR(100) NOT NULL,
		        `order`               INT(4)       UNSIGNED NOT NULL
            ) ' . $wpdb->get_charset_collate();

		$this->maybe_create_table( $this->cities_table, $cities_sql );
		$this->maybe_create_table( $this->warehouses_table, $warehouses_sql );
	}

	/**
	 * Maybe create table
	 *
	 * @param string $table_name Table name.
	 * @param string $create_ddl Table create SQL.
	 */
	private function maybe_create_table( string $table_name, string $create_ddl ) {

		if ( ! function_exists( 'maybe_create_table' ) ) {
			// @codeCoverageIgnoreStart
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			// @codeCoverageIgnoreEnd
		}
		maybe_create_table( $table_name, $create_ddl );
	}

	/**
	 * List of the cities
	 *
	 * @param string $search Search string.
	 * @param int    $limit  Limit cities in result.
	 *
	 * @return array
	 */
	public function cities( string $search, int $limit ): array {

		global $wpdb;
		$field_name = 'description_' . $this->language->get_current_language();
		$sql        = 'SELECT * FROM ' . $this->cities_table;
		$search     = str_replace( [ '`', 'ʼ', '"' ], '\'', $search );

		if ( $search ) {
			//phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$sql .= $wpdb->remove_placeholder_escape(
				$wpdb->prepare(
					' WHERE description_ru LIKE %s OR description_ua LIKE %s',
					'%' . $wpdb->esc_like( $search ) . '%',
					'%' . $wpdb->esc_like( $search ) . '%'
				)
			);
			//phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
		}
		$sql .= $wpdb->remove_placeholder_escape(
			$wpdb->prepare(
				' ORDER BY CASE
				    WHEN description_ua LIKE %s THEN 1
				    WHEN description_ru LIKE %s THEN 1
				    WHEN description_ua LIKE %s THEN 2
				    WHEN description_ru LIKE %s THEN 2
				    WHEN description_ua LIKE %s THEN 4
				    WHEN description_ru LIKE %s THEN 4
				    ELSE 3
			    END',
				$wpdb->esc_like( $search ),
				$wpdb->esc_like( $search ),
				$wpdb->esc_like( $search ) . '%',
				$wpdb->esc_like( $search ) . '%',
				'%' . $wpdb->esc_like( $search ),
				'%' . $wpdb->esc_like( $search )
			)
		);
		if ( $limit ) {
			$sql .= $wpdb->prepare( ' LIMIT %d', $limit );
		}

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$cities = $wpdb->get_results( $sql );
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		return wp_list_pluck( $cities, $field_name, 'city_id' );
	}

	/**
	 * Update cities
	 *
	 * @param array $cities List of the cities.
	 */
	public function update_cities( array $cities ) {

		global $wpdb;
		$sql = 'INSERT INTO ' . $this->cities_table . ' (`city_id`, `description_ru`, `description_ua`, `area`) VALUES ';
		foreach ( $cities as $city ) {
			$sql .= $wpdb->prepare(
				'(%s, %s, %s, %s),',
				$city['Ref'],
				! empty( $city['DescriptionRu'] ) ? $city['DescriptionRu'] : $city['Description'],
				$city['Description'],
				$city['Area']
			);
		}
		$sql = rtrim( $sql, ',' );

		$sql .= ' ON DUPLICATE KEY UPDATE `description_ru`=VALUES(`description_ru`), `description_ua`=VALUES(`description_ua`), `area`=VALUES(`area`)';

		$wpdb->query( $sql ); // phpcs:ignore
	}

	/**
	 * Get city name
	 *
	 * @param string $city_id City ID.
	 *
	 * @return string
	 */
	public function city( string $city_id ): string {

		global $wpdb;
		$field_name = 'description_' . $this->language->get_current_language();

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$description = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT `description_ru`, `description_ua` FROM ' . $this->cities_table . ' WHERE city_id = %s',
				$city_id
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		return isset( $description[ $field_name ] ) ? $description[ $field_name ] : '';
	}

	/**
	 * Get city area.
	 *
	 * @param string $city_id City ID.
	 *
	 * @return string
	 */
	public function area( string $city_id ): string {

		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$area = $wpdb->get_var(
			$wpdb->prepare( 'SELECT `area` FROM ' . $this->cities_table . ' WHERE city_id = %s', $city_id )
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		return (string) $area;
	}

	/**
	 * Get list of the warehouses.
	 *
	 * @param string $city_id City ID.
	 *
	 * @return array
	 */
	public function warehouses( string $city_id ): array {

		global $wpdb;
		$field_name = 'description_' . $this->language->get_current_language();

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$sql = $wpdb->prepare(
			'SELECT warehouse_id, description_ru, description_ua FROM ' . $this->warehouses_table .
			' WHERE city_id = %s  ORDER BY LENGTH(`order`), `order`',
			$city_id
		);

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$warehouses = $wpdb->get_results( $sql );
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		return wp_list_pluck( $warehouses, $field_name, 'warehouse_id' );
	}

	/**
	 * Update warehouses
	 *
	 * @param array $warehouses List of the warehouses.
	 */
	public function update_warehouses( array $warehouses ) {

		global $wpdb;
		$sql = 'INSERT INTO ' . $this->warehouses_table . ' (`warehouse_id`,`city_id`, `description_ru`, `description_ua`, `order`) VALUES ';
		foreach ( $warehouses as $key => $warehouse ) {
			$sql .= $wpdb->prepare(
				'(%s, %s, %s, %s, %d),',
				$warehouse['Ref'],
				$warehouse['CityRef'],
				$warehouse['DescriptionRu'],
				$warehouse['Description'],
				$key
			);
		}
		$sql = rtrim( $sql, ',' );

		$sql .= ' ON DUPLICATE KEY UPDATE `city_id`=VALUES(`city_id`), `description_ru`=VALUES(`description_ru`), `description_ua`=VALUES(`description_ua`), `order`=VALUES(`order`)';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( $sql );
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Get warehouse description
	 *
	 * @param string $warehouse_id Warehouse ID.
	 *
	 * @return string
	 */
	public function warehouse( string $warehouse_id ): string {

		global $wpdb;
		$field_name = 'description_' . $this->language->get_current_language();

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$warehouse = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT `description_ru`, `description_ua` FROM ' . $this->warehouses_table . ' WHERE warehouse_id = %s',
				$warehouse_id
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		return isset( $warehouse[ $field_name ] ) ? $warehouse[ $field_name ] : '';
	}
}
