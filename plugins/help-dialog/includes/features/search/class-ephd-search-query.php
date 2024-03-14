<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle search query.
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Search_Query {

	const MAX_KEY_WORDS = 5;

	public $articles_total = 0;
	public $tags_total = 0;
	public $is_wpml_enabled = false;

	private $kb_id = 1;
	private $sanitized_raw_user_input = '';
	private $search_keywords = [];
	private $user_category_ids = [];
	private $is_amag = false;
	private $is_debug = null;
	private $is_admin = null;

	/**
	 * Display the article link in KB Search
	 *
	 * @param $kb_id
	 * @param $filtered_user_input - all keywords (escaped) that user entered
	 * @param $user_category_ids
	 * @param int $batch_size
	 * @param int $results_page_num
	 *
	 * @return array|false
	 */
	public function kb_search_articles( $kb_id, $filtered_user_input, $user_category_ids, $batch_size, $results_page_num=0 ) {

		$this->audit( '====================  START  ==================', date( "Y-m-d h:i:s a" ) );

		$this->kb_id = $kb_id;
		$this->sanitized_raw_user_input = $filtered_user_input;
		$this->user_category_ids = $user_category_ids;
		$this->audit( 'kb_id', $this->kb_id );
		$this->audit( 'sanitized_raw_user_input' , $this->sanitized_raw_user_input );
		$this->audit( 'user_category_ids' , $this->user_category_ids );

		$this->audit( 'batch_size', $batch_size );
		$this->audit( 'results_page_num', $results_page_num );
		$this->audit( 'is_link_editor_enabled', EPHD_Utilities::is_link_editor_enabled() );

		$this->is_wpml_enabled = EPHD_KB_Core_Utilities::is_wpml_enabled_addon( $this->kb_id );
		$this->audit( 'is_wpml_enabled', $this->is_wpml_enabled );

		$this->is_amag = false; // FUTURE TO DO in PRO EPHD_Utilities::is_amag_on();
		$this->audit( 'is_amag', $this->is_amag );

		// get individual search keywords;
		$this->search_keywords = EPHD_Search_Query_Extras::get_search_keywords( $this->kb_id, $filtered_user_input );
		$this->audit( 'search_keywords', $this->search_keywords );

		// if Access Manager is enabled, ensure only categories that user can access are visible
		/* FUTURE TODO if ( ! empty($this->user_category_ids) && $this->is_amag) {

			$user_selected_categories = array();
			foreach( $this->user_category_ids as $user_category_id ) {
				$user_selected_category = EPHD_Core_Utilities::get_kb_category_unfiltered( $this->kb_id, $user_category_id );
				if ( ! empty($user_selected_category) ) {
					$user_selected_categories[] = $user_selected_category;
				}
			}

			$this->user_category_ids = array();
			$user_filtered_categories = EPHD_KB_Core::filter_user_categories( $user_selected_categories );
			foreach($user_filtered_categories as $user_filtered_category) {
				$this->user_category_ids[] = $user_filtered_category->term_id;
			}
		} */

		// count number of articles
		$articles_total = $this->search_articles( true );
		$this->audit( 'articles_total', $articles_total );
		if ( $articles_total === false ) {
			return false;
		}
		$this->articles_total = $articles_total;

		// find from
		$results_page_num = empty( $results_page_num ) || $results_page_num < 0 ? 1 : $results_page_num;
		$results_from = ( $results_page_num - 1 ) * $batch_size;
		$results_from = ( $results_from < 0 ) ? 0 : $results_from;

		// search KB Articles if any left
		$article_result = array();
		$articles_left = $articles_total - $results_from;
		$this->audit( 'results_from', $results_from );
		$this->audit( 'articles_left', $articles_left );

		if ( $articles_left > 0 ) {
			$article_limit = $articles_left > $batch_size ? $batch_size : $articles_left;
			$article_result = $this->search_articles( false, $results_from, $article_limit );
			$this->audit( 'article_result', $article_result );
			if ( $article_result === false ) {
				return false;
			}
		}

		// search Tags if any left
		$tag_result = array();
		if ( $batch_size > $articles_left ) {
			$tag_limit = $articles_left > 0 ? $batch_size - $articles_left : $batch_size;
			$tag_results_from = $articles_left > 0 ? 0 : ( $results_from - $articles_total );
			$tag_result = $this->search_tags( $tag_results_from, $tag_limit );
			$this->audit( 'tag_result', $tag_result );
			if ( $tag_result === false ) {
				return false;
			}
		}

		// combine article and tag searches
		$search_result = $article_result;
		$article_result_ids = array();
		foreach( $article_result as $article_record ) {
			$article_result_ids[] = $article_record->ID;
		}

		$this->tags_total = 0;
		foreach( $tag_result as $tag_record ) {
			if ( ! in_array( $tag_record->ID, $article_result_ids ) ) {
				$search_result[] = $tag_record;
				$article_result_ids[] = $tag_record->ID;
				$this->tags_total++;
			}
		}
		$this->audit( 'tag_result (1)', $search_result );

		// add empty element if found more than batch size of search results
		if ( count($search_result) > $batch_size ) {
			$search_result = array_splice( $search_result, 0, $batch_size );
			$search_result = array_merge( $search_result, array( '' ) );
		}

		$this->audit( 'search_result (2)', $search_result );

		$this->audit( '====================  END  ==================', date( "Y-m-d h:i:s a" ) );

		return $search_result;
	}

	/**
	 * Create a custom query to search articles. Return either total or list of articles.
	 *
	 * @param $return_total
	 * @param int $results_from
	 * @param int $initial_limit
	 *
	 * @return array|false|int
	 */
	private function search_articles( $return_total, $results_from=0, $initial_limit=0 ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		$return_total_orig = $return_total;
		$return_total = $this->is_amag || $this->is_wpml_enabled ? false : $return_total;

		$sql = $this->generate_article_search_query( $return_total, $results_from, $initial_limit );
		$this->audit( 'sql (articles)', $sql );

		if ( $return_total ) {
			$search_result = $wpdb->get_var( $sql );
		} else {
			$search_result = $wpdb->get_results( $sql );
		}

		// check if error occurred
		if ( ! empty( $wpdb->last_error ) || $search_result === null ) {
			EPHD_Logging::add_log( "DB failure: " . $wpdb->last_error );
			return false;
		}

		$this->audit( 'sql result (articles)', $search_result );

		// WPML filter language
		if ( $this->is_wpml_enabled ) {

			$articles_seq_data = EPHD_KB_Core_Utilities::get_kb_option( $this->kb_id, EPHD_KB_Core_Utilities::KB_ARTICLES_SEQUENCE, array(), true );
			$articles_seq_data = EPHD_WPML::apply_article_language_filter( $articles_seq_data );

			$articles_filtered = array();
			foreach( $articles_seq_data as $category_id => $articles_array ) {

				$ix = 0;
				foreach( $articles_array as $article_id => $article_title ) {
					if ( $ix ++ < 2 ) {
						continue;
					}
					$articles_filtered[] = $article_id;
				}
			}

			$new_result = array();
			foreach( $search_result as $post ) {
				if ( in_array($post->ID, $articles_filtered) ) {
					$new_result[] = $post;
				}
			}
			$search_result = $new_result;
		}

		// if Access Manager enabled then filter returned articles
		/* FUTURE TODO if ( $this->is_amag ) {
			$search_result = EPHD_KB_Core::found_posts( $search_result );
		} */

		// if we just need count then return it
		if ( $this->is_wpml_enabled || $this->is_amag ) {
			return isset($search_result) && is_array($search_result) ? ( $return_total_orig ? count($search_result) : array_splice($search_result, $results_from, $initial_limit) ) : false;
		}

		return isset($search_result) && is_array($search_result) ? $search_result : ( $return_total_orig ? $search_result : false );
	}

	private function generate_article_search_query( $return_total, $results_from, $initial_limit ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		$post_tbl = $wpdb->prefix . 'posts';
		$post_meta_tbl = $wpdb->prefix . 'postmeta';
		$kb_post_type  = EPHD_KB_Core_Utilities::get_post_type( $this->kb_id );

		$return_total = $this->is_amag || $this->is_wpml_enabled ? false : $return_total;

		/** SELECT clause */
		$select = " SELECT " . ( $return_total ? ' COUNT(DISTINCT wp.ID) ' : " wp.* " );

		/** FROM clause */
		$from = " FROM $post_tbl wp " . ( EPHD_Utilities::is_link_editor_enabled() ? " LEFT JOIN $post_meta_tbl wpp ON ( wp.ID = wpp.post_id ) " : '' );
		if ( ! empty($this->user_category_ids) ) {
			$from .= "INNER JOIN {$wpdb->prefix}term_relationships tr ON wp.ID = tr.object_id
					  INNER JOIN {$wpdb->prefix}terms t ON t.term_id = tr.term_taxonomy_id ";
		}

		/** WHERE clause */
		$search_sql = $this->where_like_keywords();

		// OLD installation or AMAG
		$where_post_status = " wp.post_status = 'publish' ";
		if ( $this->is_amag ) {
			$where_post_status .= " OR wp.post_status = 'private' ";

		// NEW installation:
		// - in SQL for logged-in users select only those articles which they are allowed to see
		} else if ( is_user_logged_in() ) {
			$where_post_status .= current_user_can( 'read_private_posts' )
					? " OR wp.post_status = 'private' "
					: " OR ( wp.post_status = 'private' AND wp.post_author = " . get_current_user_id() . " ) ";
		}

		$where = " WHERE $search_sql AND (wp.post_type = '{$kb_post_type}') AND ( $where_post_status ) ";

		// check if categories is selected, search only in selected categories
		if ( ! empty($this->user_category_ids) ) {
			$where .= ' AND (t.term_id in (' . implode(',', $this->user_category_ids) . ')) ';
		}

		/** GROUP BY clause */
		$group_by = $return_total || ! EPHD_Utilities::is_link_editor_enabled() ? '' : " GROUP BY wp.ID  ";

		/** ORDER_BY clause */
		$order_by = $this->assemble_order_by();
		$order_by = " $order_by, wp.post_date DESC ";
		$order_by = $return_total ? '' : $order_by;

		/** LIMIT clause */
		$limit = $return_total || $this->is_amag || $this->is_wpml_enabled ? '' : " LIMIT " . $results_from . ', ' . $initial_limit;

		$sql = $select . $from . $where . $group_by . $order_by . $limit;

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

			// for the Link Editor search article meta data for search terms as well
			if ( EPHD_Utilities::is_link_editor_enabled() ) {
				$like_clause .= " OR ( wpp.meta_key = 'kb"."lk_search_terms' AND wpp.meta_value REGEXP %s ) ";
				array_push($search_like_args, $search_like);
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
		if ( count($this->search_keywords)  == 1 ) {
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

	/**
	 * Search KB Tags in Articles against search keywords.
	 *
	 * @param int $results_from - starting position (for paging)
	 * @param int $limit - number of results per page
	 *
	 * @return array|false
	 */
	private function search_tags( $results_from=0, $limit=0 ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// get individual search keywords WITHOUT stop words
		$tags_search_keywords_no_filler = EPHD_Search_Query_Extras::get_search_keywords( $this->kb_id, $this->sanitized_raw_user_input, false );
		$this->audit( 'tags_search_keywords_no_filler', $tags_search_keywords_no_filler );

		// get individual search keywords but keep stop words
		$tags_search_keywords = EPHD_Search_Query_Extras::get_search_keywords( $this->kb_id, $this->sanitized_raw_user_input );
		$this->audit( 'tags_search_keywords (1)', $tags_search_keywords );

		// get individual search keywords
		$kb_tag_taxonomy = EPHD_KB_Core_Utilities::get_tag_taxonomy_name( $this->kb_id );
		$kb_category_taxonomy = EPHD_KB_Core_Utilities::get_category_taxonomy_name( $this->kb_id );
		$this->audit( 'kb_tag_taxonomy', $kb_tag_taxonomy );
		$this->audit( 'kb_category_taxonomy', $kb_category_taxonomy );

		// add 2-words combinations after stop word filler
		$additional_two_keywords = array();
		for ( $i = 1; $i < count( $tags_search_keywords ); $i++ ) {
			$additional_two_keywords[] = $tags_search_keywords[$i-1] . ' ' . $tags_search_keywords[$i];
		}

		// add 3-words combinations after stop word filler
		$additional_three_keywords = array();
		for ( $i = 2; $i < count( $tags_search_keywords ); $i++ ) {
			$additional_three_keywords[] = $tags_search_keywords[$i-2] . ' ' . $tags_search_keywords[$i-1] . ' ' . $tags_search_keywords[$i];
		}

		// finally add two and three word combination to search for
		$tags_search_keywords = array_merge($additional_two_keywords, $tags_search_keywords);
		$tags_search_keywords = array_merge($additional_three_keywords, $tags_search_keywords);
		$this->audit( 'tags_search_keywords (2)', $tags_search_keywords );

		// add 2-words combinations WITHOUT filler
		$additional_two_keywords = array();
		for ( $i = 1; $i < count( $tags_search_keywords_no_filler ); $i++ ) {
			$additional_two_keywords[] = $tags_search_keywords_no_filler[$i-1] . ' ' . $tags_search_keywords_no_filler[$i];
		}

		// add 3-words combinations WITHOUT filler
		$additional_three_keywords = array();
		for ( $i = 2; $i < count( $tags_search_keywords_no_filler ); $i++ ) {
			$additional_three_keywords[] = $tags_search_keywords_no_filler[$i-2] . ' ' . $tags_search_keywords_no_filler[$i-1] . ' ' . $tags_search_keywords_no_filler[$i];
		}

		// finally add two and three word combination to search for
		$tags_search_keywords = array_merge($additional_two_keywords, $tags_search_keywords);
		$tags_search_keywords = array_merge($additional_three_keywords, $tags_search_keywords);
		$this->audit( 'tags_search_keywords (3)', $tags_search_keywords );

		/** WHERE clause */
		$count_condition = $this->is_amag ? '' : 'AND count > 0';
		// check if categories is selected, search only in selected categories
		if ( empty($this->user_category_ids) ) {
			$where = "WHERE taxonomy = '$kb_tag_taxonomy' " . $count_condition . " ) terms ON terms.term_id = t.term_id AND name = %s ";
		} else {
			$where = "WHERE (taxonomy = '$kb_tag_taxonomy' OR taxonomy = '$kb_category_taxonomy') " . $count_condition .
			         " ) terms ON (terms.term_id = t.term_id AND name = %s AND t.term_id in (" . implode(',', $this->user_category_ids) . ") )";
		}

		// OLD installation or AMAG
		$where_post_status = " p.post_status = 'publish' ";
		if ( $this->is_amag ) {
			$where_post_status .= " OR p.post_status = 'private' ";

		// NEW installation:
		// - in SQL for logged-in users select only those articles which they are allowed to see
		} else if ( is_user_logged_in() ) {
			$where_post_status .= current_user_can( 'read_private_posts' )
				? " OR p.post_status = 'private' "
				: " OR ( p.post_status = 'private' AND p.post_author = " . get_current_user_id() . " ) ";
		}

		$where .= " AND ( $where_post_status ) ";

		/** LIMIT clause */
		$limit = " LIMIT " . $results_from . ', ' . $limit;

		$search_result = array();
		foreach ( $tags_search_keywords as $search_keyword ) {

			$sql = "SELECT " . ' * ' . "
				FROM {$wpdb->prefix}posts p
					INNER JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
					INNER JOIN {$wpdb->prefix}terms t ON t.term_id = tr.term_taxonomy_id
					INNER JOIN (SELECT term_id
								FROM {$wpdb->prefix}term_taxonomy " .
			                    $where . $limit;

			$sql = $wpdb->prepare( $sql, $search_keyword );
			$this->audit( 'sql (tags)', $sql );

			$result = $wpdb->get_results( $sql );
			if ( ! empty($wpdb->last_error) || $result === null ) {
				EPHD_Logging::add_log( "DB failure: " . $wpdb->last_error );
				return false;
			}

			$this->audit( 'sql result (tags)', $result, ['ID', 'post_title', 'post_status'] );

			$search_result = array_merge( $search_result, $result );
		}

		if ( $this->is_wpml_enabled ) {
			// do not return articles that do not belong to the current language
			$current_article_ids = EPHD_Utilities::is_wpml_article_active( $search_result, true );
			foreach( $search_result as $key => $item ) {
				if ( ! in_array($item->ID, $current_article_ids) ) {
					unset($search_result[$key]);
				}
			}
		}

		// if Access Manager enabled then filter returned articles
		/* FUTURE TO DO if ( $this->is_amag ) {
			$search_result = EPHD_KB_Core::found_posts( $search_result );
		} */

		return $search_result;
	}

	private function audit( $label, $data, $object_props=[] ) {
		return; // TODO FUTURE
	}
}
