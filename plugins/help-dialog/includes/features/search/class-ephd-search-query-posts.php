<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle search query.
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Search_Query_Posts {

	const MAX_KEY_WORDS = 5;

	public $articles_total = 0;

	private $post_type = null;
	private $sanitized_raw_user_input = '';
	private $search_keywords = [];

	private $is_debug = null;
	private $is_admin = null;

	/**
	 * Search Posts
	 *
	 * @param $post_type
	 * @param $filtered_user_input - all keywords (escaped) that user entered
	 * @param int $batch_size
	 * @param int $results_page_num
	 *
	 * @return array|false
	 */
	public function search_posts_articles( $post_type, $filtered_user_input, $batch_size, $results_page_num=0 ) {

		$this->audit( '====================  START  ==================', date("Y-m-d h:i:s a") );

		$this->post_type = $post_type;
		$this->audit( 'post_type' , $this->post_type );

		$this->sanitized_raw_user_input = $filtered_user_input;
		$this->audit( 'sanitized_raw_user_input' , $this->sanitized_raw_user_input );

		$this->audit( 'batch_size', $batch_size );
		$this->audit( 'results_page_num', $results_page_num );

		// get individual search keywords;
		$this->search_keywords = EPHD_Search_Query_Extras::get_search_keywords( false, $filtered_user_input );
		$this->audit( 'search_keywords', $this->search_keywords );

		// get all articles
		$all_articles_results = $this->search_articles();

		// count number of articles
		$articles_total = count( $all_articles_results );
		$this->articles_total = $articles_total;

		// find from
		$results_page_num = empty( $results_page_num ) || $results_page_num < 0 ? 1 : $results_page_num;
		$results_from = ( $results_page_num - 1 ) * $batch_size;
		$results_from = ( $results_from < 0 ) ? 0 : $results_from;

		// search FAQ Articles if any left
		$article_result = array();
		$articles_left = $articles_total - $results_from;
		$this->audit( 'results_from', $results_from );
		$this->audit( 'articles_left', $articles_left );

		if ( $articles_left > 0 ) {
			$article_limit = $articles_left > $batch_size ? $batch_size : $articles_left;
			$article_result = array_slice( $all_articles_results, $results_from, $article_limit );
			$this->audit( 'article_result', $article_result );
			if ( $article_result === false ) {
				return false;
			}
		}

		// add empty element if found more than batch size of search results
		if ( count( $article_result ) > $batch_size ) {
			$article_result = array_splice( $article_result, 0, $batch_size );
			$article_result = array_merge( $article_result, array('') );
		}

		$this->audit( 'search_result (2)', $article_result );

		$this->audit( '====================  END  ==================', date("Y-m-d h:i:s a") );

		return $article_result;
	}

	/**
	 * Create a custom query to search articles. Return either total or list of articles.
	 *
	 * @param int $results_from
	 * @param int $initial_limit
	 *
	 * @return array|false
	 */
	private function search_articles( $results_from=0, $initial_limit=0 ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		$sql = $this->generate_article_search_query( $results_from, $initial_limit );
		$this->audit( 'sql (articles)', $sql );

		$search_result = $wpdb->get_results( $sql );

		// check if error occurred
		if ( ! empty( $wpdb->last_error ) || $search_result === null ) {
			EPHD_Logging::add_log( "DB failure: " . $wpdb->last_error );
			return false;
		}

		$this->audit( 'sql result (articles)', $search_result );

		// filter post by current language

		$current_language = EPHD_Multilang_Utilities::get_current_language();

		$filtered_search_result = array();
		foreach ( $search_result as $key => $article ) {
			if ( ! empty( $article->ID ) ) {
				$post_language = EPHD_Multilang_Utilities::get_post_language( $article->ID );
				if ( $current_language ==  $post_language || empty( $post_language ) ) {
					$filtered_search_result[$key] = $article;
				}
			}
		}

		return $filtered_search_result;
	}

	private function generate_article_search_query( $results_from, $initial_limit ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		$post_tbl = $wpdb->prefix . 'posts';
		$post_meta_tbl = $wpdb->prefix . 'postmeta';

		/** SELECT clause */
		$select = " SELECT wp.* ";

		/** FROM clause */
		$from = " FROM $post_tbl wp ";

		/** WHERE clause */
		$search_sql = $this->where_like_keywords();

		// OLD installation
		$where_post_status = " wp.post_status = 'publish' ";
		if ( is_user_logged_in() ) {
			$where_post_status .= current_user_can( 'read_private_posts' )
					? " OR wp.post_status = 'private' "
					: " OR ( wp.post_status = 'private' AND wp.post_author = " . get_current_user_id() . " ) ";
		}

		$where = " WHERE $search_sql AND (wp.post_type = '{$this->post_type}') AND ( $where_post_status ) ";

		/** ORDER_BY clause */
		$order_by = $this->assemble_order_by();
		$order_by = " $order_by, wp.post_date DESC ";

		$sql = $select . $from . $where . $order_by;

		return $sql;
	}

	/**
	 * Create search SQL based on individual search keywords with LIKE for:
	 *   - Title
	 *   - Excerpt
	 *   - Content
	 *
	 * @return string
	 */
	private function where_like_keywords() {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// generate full LIKE clause
		$and = '';
		$search_sql = '';
		foreach ( $this->search_keywords as $search_keyword ) {

			// REGEXP '\\bterm' - not supported in MySQL 5.7 and lower
			$search_like = '([[:space:][:punct:]]|^)' . $wpdb->esc_like( $search_keyword );
			// TODO $search_like = '([^[:alnum:]|-|_|@]|^)' . $wpdb->esc_like( $search_keyword ) . '';

			// 1. find post title that contains the keyword
			$like_clause = " ( ( LOWER( wp.post_title ) REGEXP %s) ";
			$search_like_args = [$search_like];

			// if the keyword is an HTML tag or CSS style then exclude them from a) excerpt and b) content search
			if ( in_array($search_keyword, EPHD_Search_Query_Extras::html_css_keywords()) ) {

				// REGEXP '\\bterm' - not supported in MySQL 5.7 and lower
				$search_regex = '([[:space:][:punct:]]|^)' . $wpdb->esc_like( $search_keyword );
				$like_clause .= " OR ( LOWER( wp.post_excerpt ) REGEXP %s) OR ( LOWER( wp.post_content ) REGEXP %s) ";
				array_push( $search_like_args, $search_regex, $search_regex );

			} else {

				$like_clause .= " OR ( LOWER( wp.post_excerpt ) REGEXP %s) OR ( LOWER( wp.post_content ) REGEXP %s) ";
				array_push($search_like_args, $search_like, $search_like);
			}

			$like_clause .= ' ) ';

			$search_sql .= $wpdb->prepare( " {$and} " . $like_clause, $search_like_args );

			$and = 'AND';
		}

		return $search_sql;
	}

	/**
	 * Create Order By SQL.
	 *
	 * @return string
	 */
	private function assemble_order_by() {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// first order search by title
		$order_by_search_title = array();
		foreach ( $this->search_keywords as $search_keyword ) {
			// REGEXP '\\bterm' - not supported in MySQL 5.7 and lower
			$search_like = '([[:space:][:punct:]]|^)' . $wpdb->esc_like( $search_keyword );
			// TODO $search_like = '([^[:alnum:]|-|_|@]|^)' . $wpdb->esc_like( $search_keyword ) . '';
			$order_by_search_title[] = $wpdb->prepare( "wp.post_title REGEXP %s", $search_like );
		}

		// for single search keyword return simple order by
		if ( count( $this->search_keywords )  == 1 ) {
			return ' ORDER BY ' . reset( $order_by_search_title ) . ' DESC';
		}

		// REGEXP '\\bterm' - not supported in MySQL 5.7 and lower
		$like = '([[:space:][:punct:]]|^)' . $wpdb->esc_like( $this->sanitized_raw_user_input );

		// next try exact match to search phrase
		$search_order_by = $wpdb->prepare( "WHEN wp.post_title REGEXP %s THEN 1 ", $like );

		// try AND and OR for up to 5 keywords
		$num_keywords = count( $order_by_search_title );
		if ( $num_keywords <= self::MAX_KEY_WORDS ) {
			$search_order_by .= 'WHEN ' . implode( ' AND ', $order_by_search_title ) . ' THEN 2 ';
			if ( $num_keywords > 1 ) {
				$search_order_by .= 'WHEN ' . implode( ' OR ', $order_by_search_title ) . ' THEN 3 ';
			}
		}

		// also try to match full phrase in excerpt and content
		$search_order_by .= $wpdb->prepare( "WHEN wp.post_content REGEXP %s THEN 4 ", $like );
		$search_order_by .= $wpdb->prepare( "WHEN wp.post_excerpt REGEXP %s THEN 5 ", $like );

		$search_order_by = 'ORDER BY (CASE ' . $search_order_by . 'ELSE 6 END) ';

		return $search_order_by;
	}

	private function audit( $label, $data, $object_props=[] ) {
		return; // TODO FUTURE
	}
}
