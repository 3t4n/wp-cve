<?php
/**
 * Class to manage the Data Index Token table
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recover the data from database
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Index_Token {

	use YITH_WCAS_Trait_Singleton;


	/**
	 * Construction
	 *
	 * @return void
	 */
	private function __construct() {

	}


	/**
	 * Insert the post on database
	 *
	 * @param array $data Array of value.
	 *
	 * @return int
	 */
	public function insert( $data ) {
		global $wpdb;
		$result = $wpdb->insert( $wpdb->yith_wcas_index_token, $data, self::get_format() );

		return $result ? $wpdb->insert_id : 0;
	}

	/**
	 * Insert the post on database
	 *
	 * @param int   $token_id Token id.
	 * @param array $data Array of value.
	 *
	 * @return int|false
	 */
	public function update( $token_id, $data ) {
		global $wpdb;

		return $wpdb->update(
			$wpdb->yith_wcas_index_token,
			$data,
			array( 'token_id' => $token_id ),
			array(
				'%d',
				'%d',
				'%s',
			),
			array( '%d' )
		);
	}

	/**
	 * Remove the token from table
	 *
	 * @param int $token_id Token id to remove.
	 *
	 * @return void
	 */
	public function remove( $token_id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->yith_wcas_index_token, array( 'token_id' => $token_id ), array( '%d' ) );
	}


	/**
	 * Get the format of columns
	 *
	 * @return array
	 */
	protected static function get_format() {
		return array(
			'%s', // token.
			'%d', // frequency.
			'%d', // doc_frequency.
			'%s', // lang.
		);
	}

	/**
	 * Clear the table
	 *
	 * @return void
	 */
	public function clear_table() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE $wpdb->yith_wcas_index_token" );
		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_token DROP INDEX index_token_lang_freq_desc" ); //phpcs:ignore
	}


	/**
	 * Reindex the table
	 *
	 * @return void
	 */
	public function index_table() {
		global $wpdb;

		$wpdb->query( "ALTER TABLE $wpdb->yith_wcas_index_token ADD INDEX index_token_lang_freq_desc (token,lang,frequency DESC)" ); //phpcs:ignore
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
		return $wpdb->get_row( $wpdb->prepare( "SELECT * from $wpdb->yith_wcas_index_token WHERE token_id LIKE %d", $token_id ), ARRAY_A );
	}

	/**
	 * Return the token
	 *
	 * @param string $token Token.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	public function get_token( $token, $lang ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * from $wpdb->yith_wcas_index_token WHERE token LIKE %s and lang LIKE %s", $token, $lang ), ARRAY_A );
	}

	/**
	 * Return the token
	 *
	 * @param string $tokens Tokens.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	public function get_tokens( $tokens, $lang ) {
		global $wpdb;

		$tokens = array_map( 'esc_sql', $tokens );
		$tokens = implode( "','", $tokens );

		return $wpdb->get_results( "SELECT * from $wpdb->yith_wcas_index_token WHERE token IN ('" . $tokens . "') AND lang like '".$lang."'" , ARRAY_A ); //phpcs:ignore
	}

	/**
	 * Search similar token for fuzzy search
	 *
	 * @param string $token String to search.
	 * @param string $lang Language.
	 * @param int    $limited Limit the query.
	 *
	 * @return array
	 */
	public function search_similar_token( $token, $lang, $limited = 500 ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT it.token as token, it.frequency as frequency FROM $wpdb->yith_wcas_index_token as it LEFT JOIN $wpdb->yith_wcas_index_relationship ir ON it.token_id = ir.token_id WHERE  ir.source_type LIKE %s AND it.token LIKE %s AND it.lang like %s ORDER BY it.frequency DESC LIMIT %d ", 'product', $token, $lang, $limited ), ARRAY_A );
		return $result;

	}

	/**
	 * Search the token
	 *
	 * @param string $token String to search.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	public function search( $token, $lang ) {
		global $wpdb;
		$result = $wpdb->get_col( $wpdb->prepare( "SELECT token_id FROM $wpdb->yith_wcas_index_token WHERE token LIKE %s AND lang like %s ORDER BY frequency DESC ", $token, $lang ) );
		return $result;
	}

	/**
	 * Search soundex strings of a token. Only for English
	 *
	 * @param string $token Token.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	public function search_soundex_token( $token, $lang ) {
		global $wpdb;
		$result = $wpdb->get_col( $wpdb->prepare( "SELECT token_id FROM $wpdb->yith_wcas_index_token WHERE token SOUNDS LIKE  %s AND lang like %s  ORDER BY frequency DESC ", $token, $lang ) );

		return $result;
	}

	/**
	 * Search the best token
	 *
	 * @param string $lang Language.
	 *
	 * @return string
	 */
	public function get_best_token( $lang ) {
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT token FROM $wpdb->yith_wcas_index_token WHERE LENGTH(token) > 3 AND lang like %s ORDER BY doc_frequency DESC, frequency DESC  LIMIT 1 ", $lang ) );

		return $result ?? '';
	}

}
