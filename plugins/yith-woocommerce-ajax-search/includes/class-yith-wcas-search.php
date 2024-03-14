<?php
/**
 * Main class
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * YITH_WCAS_Search class.
 */
if ( ! class_exists( 'YITH_WCAS_Search' ) ) {
	/**
	 * WooCommerce Ajax Search
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS_Search {

		use YITH_WCAS_Trait_Singleton;

		/**
		 * Search History Class
		 *
		 * @var YITH_WCAS_Search_History;
		 */
		public $history;

		/**
		 * Search string
		 *
		 * @var string
		 */
		public $search_string = '';

		/**
		 * Constructor
		 *
		 * @since 2.0.0
		 */
		private function __construct() {
			if ( class_exists( 'YITH_WCAS_Search_History' ) ) {
				$this->history = YITH_WCAS_Search_History::get_instance();
			}

			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 9999999 );
			add_filter( 'get_search_query', array( $this, 'filter_search_query' ) );
		}


		/**
		 * Manage the global query
		 *
		 * @param WP_Query $query Obj.
		 *
		 * @return void
		 */
		public function pre_get_posts( $query ) {

			if ( ! isset( $_GET['ywcas'] ) ) {  //phpcs:ignore
				return;
			}

			global $wp_the_query, $wp_query;
			$lang                = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : ywcas_get_current_language();  //phpcs:ignore
			$fiilter_taxonomy    = isset( $_GET['ywcas_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['ywcas_filter'] ) ) : false; //phpcs:ignore
			$search_on           = ywcas_get_default_product_post_type();
			$this->search_string = $wp_the_query->query_vars['s'] ?? '';

			if ( ( ! empty( $this->search_string ) || $fiilter_taxonomy ) && ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				if ( $fiilter_taxonomy ) {
					$taxonomy = $wp_query ? get_queried_object() : false;
					if ( $taxonomy ) {
						$results = YITH_WCAS_Data_Search_Engine::get_instance()->search( $fiilter_taxonomy, $search_on, 0, $lang, false, 0, 0, true );
						$post_in = array_map( 'intval', wp_list_pluck( $results['results'], 'post_id' ) );

						if ( empty( $post_in ) ) {
							$post_in = array( - 1 );
						}
						$query->set( 'post__in', $post_in );
					}

				} else {
					$results = YITH_WCAS_Data_Search_Engine::get_instance()->search( $this->search_string, $search_on, 0, $lang, false, 0, 0, true );

					if ( $results['fuzzyToken'] ) {
						$this->search_string = $results['fuzzyToken'];
					}

					$post_in = array_map( 'intval', wp_list_pluck( $results['results'], 'post_id' ) );

					if ( empty( $post_in ) ) {
						$post_in = array( - 1 );
					}
					$query->set( 'post__in', $post_in );
					$query->set( 'orderby', 'post__in' );

					$query->set( 's', '' );
				}
			}


		}


		/**
		 * Api search
		 *
		 * @param WP_REST_Request $request The request.
		 *
		 * @return array|WP_Error
		 */
		public function api_search( $request ) {
			if ( ! isset( $request['query'] ) && ! isset( $request['test'] ) ) {
				return new WP_Error( 'ywcas_empty_query', __( 'Unable to make a search with an empty query!', 'yith-woocommerce-ajax-search' ), array( 'status' => 413 ) );
			}
			$default_settings = ywcas()->settings->get_classic_default_settings();

			$query          = $request['query'];
			$category       = $request['category'] ?? 0;
			$lang           = $request['lang'] ?? ywcas_get_current_language();
			$show_category  = $request['showCategories'] ?? 'no';
			$is_test        = isset( $request['test'] ) && $request['test'];
			$num_of_results = $request['maxResults'] ?? $default_settings['maxResults'];
			$page           = ! empty( $request['page'] ) ? $request['page'] : 0;
			$limited        = 0 === $page;

			if ( $is_test ) {
				$query = YITH_WCAS_Data_Index_Token::get_instance()->get_best_token( $lang );
			}

			$search_on = ywcas_get_default_product_post_type();

			$results = YITH_WCAS_Data_Search_Engine::get_instance()->search( $query, $search_on, $category, $lang, $limited, $page, $num_of_results, true );
			if ( isset( $results['results'] ) && 'yes' === $show_category ) {
				$results['categories'] = YITH_WCAS_Data_Search_Engine::get_instance()->add_categories( $results['results'] );
			}
			$results['query'] = $query;
			$related_content  = apply_filters( 'ywcas_related_content_post_type', array() );

			if ( ! empty( $related_content ) ) {
				$results['related_content'] = YITH_WCAS_Data_Search_Engine::get_instance()->search( $query, $related_content, $category, $lang, true, 0, $num_of_results, false );
			}

			// Register no results query string to statistic v2.1.0.
			if ( isset( $results['totalResults'], $results['results'] ) && $results['totalResults'] === 0 && count( $results['results'] ) === 0 && apply_filters( 'ywcas_register_not_results_query', true ) ) {
				YITH_WCAS_Data_Search_Engine::get_instance()->get_logger_reference( $query, 0, 0, $lang );
			}

			return $results;
		}

		/**
		 * Force the search query string
		 *
		 * @param string $query The default search query.
		 *
		 * @return string
		 */
		public function filter_search_query( $query ) {

			if ( isset( $_GET['ywcas'], $_GET['s'] ) ) { //phpcs:ignore
				return sanitize_text_field( wp_unslash( $_GET['s'] ) ); //phpcs:ignore
			}

			return $query;
		}

	}
}
