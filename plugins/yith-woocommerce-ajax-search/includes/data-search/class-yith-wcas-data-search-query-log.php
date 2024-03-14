<?php
/**
 * Logger of the user query class
 *
 * @author  YITH
 * @package YITH/Search/DataSearch
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recover and save the query data from database
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Search_Query_Log {

	/**
	 * Get the format of columns
	 *
	 * @return array
	 */
	protected static function get_format() {
		return array(
			'%d', // user_id.
			'%s', // query string.
			'%s', // search_date.
			'%d', // num of result.
			'%d', // post clicked.
			'%s', // lang.
		);
	}

	/**
	 * Clear the table
	 *
	 * @return void
	 */
	public static function clear_table() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE $wpdb->yith_wcas_query_log" );
	}

	/**
	 * Insert the log on database
	 *
	 * @param   array  $data  Array of value.
	 *
	 * @return mixed
	 */
	public static function insert( $data ) {
		global $wpdb;
		$result = $wpdb->insert( $wpdb->yith_wcas_query_log, $data, self::get_format() );

		return $result ? $wpdb->insert_id : 0;
	}

	/**
	 * Return the search history by user
	 *
	 * @param   int     $user_id  User id.
	 * @param   string  $lang     Language.
	 * @param   int     $limit    Limit.
	 *
	 * @return array
	 */
	public static function user_history_searches( $user_id, $lang, $limit = 3 ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT query FROM $wpdb->yith_wcas_query_log WHERE user_id = %d AND lang LIKE %s AND num_results > 0 ORDER BY search_date DESC LIMIT %d", $user_id, $lang, $limit ), ARRAY_A );
	}

	/**
	 * Return all the search history by user
	 *
	 * @param   int  $user_id  User id.
	 *
	 * @return array
	 */
	public static function all_user_searches( $user_id ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->yith_wcas_query_log WHERE user_id = %d ORDER BY search_date", $user_id ), ARRAY_A );
	}

	/**
	 * Return all the search history by user
	 *
	 * @param   int  $user_id  User id.
	 *
	 * @return bool|int|mysqli_result|resource
	 */
	public static function delete_all_user_searches( $user_id ) {
		global $wpdb;

		return $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->yith_wcas_query_log WHERE user_id =%d", $user_id ) ); //phpcs:ignore
	}


	/**
	 * Return the popular searches
	 *
	 * @param   string  $lang   Language.
	 * @param   int     $limit  Limit.
	 *
	 * @return array
	 */
	public static function popular( $lang, $limit = 10 ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT query FROM $wpdb->yith_wcas_query_log WHERE lang LIKE %s AND num_results > 0 GROUP BY query ORDER BY COUNT( query ) DESC LIMIT %d", $lang, $limit ), ARRAY_A );
	}

	/**
	 * Return the top clicked products as ids and the number of click
	 *
	 * @param   string    $from    Date from.
	 * @param   string    $to      Date to.
	 * @param   int|bool  $limit   Limit of results.
	 * @param   int       $offset  Offset of results.
	 *
	 * @return array
	 * @since 2.1.0
	 */
	public static function get_top_clicked_products( $from = '', $to = '', $limit = false, $offset = 0 ) {
		global $wpdb;

		$where = ( '' !== $from ) ? " AND search_date > '".$from."' " : '';
		$where .= ( '' !== $to ) ? " AND search_date < '".$to."' " : '';
		$limit_string = $limit ? ' LIMIT '. $limit : '';
		$offset_string = 0 === $offset ? '' : ' OFFSET ' . $offset;

		return $wpdb->get_results( "SELECT clicked_product as product_id, COUNT( clicked_product ) as clicks FROM $wpdb->yith_wcas_query_log WHERE num_results > 0  $where GROUP BY clicked_product ORDER BY COUNT( clicked_product ) DESC $limit_string $offset_string", ARRAY_A );
	}

	/**
	 * Return the top search query and the number of occurrences
	 *
	 * @param   string    $from    Date from.
	 * @param   string    $to      Date to.
	 * @param   int|bool  $limit   Limit of results.
	 * @param   int       $offset  Offset of results.
	 *
	 * @return array
	 * @since 2.1.0
	 */
	public static function get_top_searches( $from = '', $to = '', $limit = false, $offset = 0 ) {
		global $wpdb;


		$where = ( '' !== $from ) ? " AND search_date > '".$from."' " : '';
		$where .= ( '' !== $to ) ? " AND search_date < '".$to."' " : '';
		$limit_string = $limit ? ' LIMIT '. $limit : '';
		$offset_string = 0 === $offset ? '' : ' OFFSET ' . $offset;

		return $wpdb->get_results(  "SELECT query, COUNT(query) as searches FROM $wpdb->yith_wcas_query_log WHERE num_results > 0 $where GROUP BY query ORDER BY COUNT( query ) DESC $limit_string $offset_string", ARRAY_A );
	}

	/**
	 * Return the top no results query and the number of occurrences
	 *
	 * @param   string    $from    Date from.
	 * @param   string    $to      Date to.
	 * @param   int|bool  $limit   Limit of results.
	 * @param   int       $offset  Offset of results.
	 *
	 * @return array
	 * @since 2.1.0
	 */
	public static function get_top_no_results( $from = '', $to = '', $limit = false, $offset = 0 ) {
		global $wpdb;

		$where = ( '' !== $from ) ? " AND search_date > '".$from."' " : '';
		$where .= ( '' !== $to ) ? " AND search_date < '".$to."' " : '';
		$limit_string = $limit ? ' LIMIT '. $limit : '';
		$offset_string = 0 === $offset ? '' : ' OFFSET ' . $offset;

		return $wpdb->get_results("SELECT query, COUNT(query) as no_results FROM $wpdb->yith_wcas_query_log WHERE num_results = 0 $where GROUP BY query ORDER BY COUNT( query ) DESC $limit_string $offset_string", ARRAY_A );
	}


}
