<?php
/**
 * Class to manage the Data Index Relationship table
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manage the Data Index Relationship table
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Index_Relationship {

	use YITH_WCAS_Trait_Singleton;

	/**
	 * Construction
	 *
	 * @return void
	 */
	private function __construct() {

	}


	/**
	 * Insert the relationship content on database
	 *
	 * @param array $data Array of value.
	 *
	 * @return mixed
	 */
	public function insert( $data ) {
		global $wpdb;
		$result = $wpdb->insert( $wpdb->yith_wcas_index_relationship, $data, self::get_format() );
		return $result ? $wpdb->insert_id : 0;
	}

	/**
	 * Update the relationship on database
	 *
	 * @param int   $token_id Token id.
	 * @param int   $post_id Post id.
	 * @param array $data Array of value.
	 *
	 * @return mixed
	 */
	public function update( $token_id, $post_id, $data ) {
		global $wpdb;
		return $wpdb->update(
			$wpdb->yith_wcas_index_relationship,
			$data,
			array(
				'token_id' => $token_id,
				'post_id'  => $post_id,
			),
			self::get_format(),
			array( '%d', '%d' )
		);
	}


	/**
	 * Get the format of columns
	 *
	 * @return array
	 */
	protected static function get_format() {
		return array(
			'%d', // token_id.
			'%d', // lookup_id.
			'%d', // frequency.
			'%s', // source type.
			'%s', // position.
		);
	}

	/**
	 * Clear the table
	 *
	 * @return void
	 */
	public function clear_table() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE $wpdb->yith_wcas_index_relationship" );
		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_relationship DROP INDEX index_r_post_id" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_relationship DROP INDEX index_r_token_id" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
	}


	/**
	 * Reindex the table
	 *
	 * @return void
	 */
	public function index_table() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_relationship ADD INDEX index_r_post_id (post_id)" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_relationship ADD INDEX index_r_token_id (token_id)" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
	}

	/**
	 * Return the token by id
	 *
	 * @param int $token_id Token id.
	 *
	 * @return array
	 */
	public function get_token_by_id( $token_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * from $wpdb->yith_wcas_index_relationship WHERE token_id LIKE %d", $token_id ), ARRAY_A );
	}

	/**
	 * Return the token
	 *
	 * @param string $token Token.
	 *
	 * @return array
	 */
	public function get_token( $token ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * from $wpdb->yith_wcas_index_relationship WHERE token LIKE %s", $token ), ARRAY_A );
	}

	/**
	 * Search the token
	 *
	 * @param string $token String to search.
	 *
	 * @return array
	 */
	public function search_post_id_by_query_tokens( $token ) {
		global $wpdb;
		return $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT rel.post_id FROM $wpdb->yith_wcas_index_relationship as rel WHERE rel.token_id IN ( SELECT token_id FROM $wpdb->yith_wcas_index_token as tk WHERE tk.token LIKE %s ORDER BY tk.frequency DESC  ) ORDER BY rel.frequency DESC, CASE rel.source_type
        WHEN 'product' THEN 4
        WHEN 'product_variation' THEN 3
        WHEN 'post' THEN 2
        WHEN 'page' THEN 1
    END  DESC",
				$token
			)
		);
	}

	/**
	 * Search the token
	 *
	 * @param array $tokens tokens to search.
	 *
	 * @return array
	 */
	public function search_post_id( $tokens ) {
		global $wpdb;
		$fields = ywcas()->settings->get_search_fields();
		$when   = '';
		foreach ( $fields as $field ) {
			$when .= sprintf( " WHEN position like '%s' THEN %d", '%' . $field['type'] . '%', 50 - intval( $field['priority'] ) );
		};
		$result = $wpdb->get_results(
			"SELECT post_id, CASE  " . $when . " END as score FROM $wpdb->yith_wcas_index_relationship WHERE token_id IN (" . implode( ',', $tokens ) . ') ORDER BY  CASE  ' . $when . ' END DESC, frequency DESC', ARRAY_A //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);
		return $result;
	}

	/**
	 * Search the token
	 *
	 * @param string $tokens Strings to search.
	 *
	 * @return array|object|null Database query results.
	 */
	public function search( $tokens ) {
		global $wpdb;
		$result = $wpdb->get_results(
			"SELECT DISTINCT lu.*, rel.position,  rel.frequency,  CASE source_type
        WHEN 'product' THEN 4
        WHEN 'product_variation' THEN 3
        WHEN 'post' THEN 2
        WHEN 'page' THEN 1
    END As weigth FROM $wpdb->yith_wcas_index_relationship as rel JOIN $wpdb->yith_wcas_data_index_lookup as lu ON rel.post_id = lu.post_id WHERE token_id IN (" . implode( ',', $tokens ) . ') ORDER BY frequency DESC, weigth  DESC' //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);

		return $result;
	}


	/**
	 * Remove the lookup id from a relationship.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return mixed
	 */
	public function remove_lookup( $post_id ) {
		global $wpdb;
		return $wpdb->delete( $wpdb->yith_wcas_index_relationship, array( 'post_id' => $post_id ), array( '%d' ) );

	}
}
