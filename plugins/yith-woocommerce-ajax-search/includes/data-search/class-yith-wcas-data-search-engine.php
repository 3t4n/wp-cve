<?php
/**
 * Search Engine class
 *
 * @author  YITH
 * @package YITH/Search/DataSearch
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Data_Search_Engine' ) ) {
	/**
	 * Recover the data from database
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS_Data_Search_Engine {

		use YITH_WCAS_Trait_Singleton;

		/**
		 * Type of search
		 *
		 * @var int
		 */
		protected $type_of_search = 'OR';

		/**
		 * No result query to avoid double registration
		 *
		 * @var string
		 */
		protected $noresult_query = '';
		/**
		 * Token ids found
		 *
		 * @var array
		 */
		protected $lookup_ids = array();

		/**
		 * Fuzzy prefix length
		 *
		 * @var int
		 */
		protected $fuzzy_prefix_length = 1;

		/**
		 * Fuzzy max token to find
		 *
		 * @var int;
		 */
		protected $fuzzy_max_tokens = 1000;

		/**
		 * Fuzzy max token to find
		 *
		 * @var int;
		 */
		protected $fuzzy_query_tokens = array();


		/**
		 * Search for results
		 *
		 * @param   string  $query_string    Query string.
		 * @param   array   $post_type       Post type.
		 * @param   int     $category        Search in specific category.
		 * @param   string  $lang            Language.
		 * @param   bool    $limited         Limited Search.
		 * @param   int     $page            The index to paginate the results.
		 * @param   int     $num_of_results  Number of results.
		 * @param   bool    $debug           Debug.
		 *
		 * @return array
		 */
		public function search( $query_string, $post_type, $category, $lang, $limited = true, $page = 0, $num_of_results = 0, $debug = false ) {
			$best_token   = false;
			$start_search = hrtime( true ); //phpcs:ignore
			$debug && $this->logger->log( 'Start search for ' . $query_string );
			$search_result_data   = false;
			$need_store_transient = false;
			$total_results        = 0;

			if ( ! $limited && $page > 0 ) {
				$search_result_data = get_transient( 'ywcas_stored_query_' . $query_string );
				$total_results      = is_array( $search_result_data ) ? count( $search_result_data ) : 0;
			}

			if ( ! $total_results ) {
				$need_store_transient = apply_filters('ywcas_store_search_query', true );
				$synonimus            = YITH_WCAS_Data_Index_Tokenizer::get_synonymous( $query_string );

				$query_tokens = $this->get_search_tokens( $synonimus );

				$debug && $this->logger->log( 'Execution time 0 ' . ( hrtime( true ) - $start_search ) / 1e+9 );
				$debug && $this->logger->log( 'Tokens ' . print_r( $query_tokens, 1 ) );

				$search_result_data = $this->get_search_results( $query_tokens, $post_type, $category, $lang );

				$debug && $this->logger->log( 'Execution time 1 ' . ( hrtime( true ) - $start_search ) / 1e+9 );


				// If the process no return results we can check for fuzzy strings.
				if ( apply_filters( 'yith_wcas_force_fuzzy_search', empty( $search_result_data ) && 'yes' === ywcas()->settings->get_enable_search_fuzzy() ) ) {
					$best_token = $this->get_fuzzy_query_string( $query_tokens, $lang );
					if ( $best_token ) {
						$search_result_data = $this->get_search_results( array( $best_token ), $post_type, $category, $lang );
						$best_token         = empty( $search_result_data ) ? '' : $best_token;
					} elseif ( ! $search_result_data && apply_filters( 'ywcas_search_for_sound', false !== strpos( $lang, 'en_' ) ) ) {
						$soundex_result     = $this->get_soundex_search_results( $query_tokens, $post_type, $lang, $category );
						$search_result_data = $soundex_result ? array_merge( $search_result_data, $soundex_result ) : $search_result_data;
					}
				}

				$search_result_data = $this->filter_results( $search_result_data );
				$search_result_data = array_unique( $search_result_data, SORT_REGULAR );
				$total_results      = count( $search_result_data );
				$search_result_data = apply_filters( 'ywcas_search_result_data', array_values( $search_result_data ), $query_string, $post_type, $lang );
				usort( $search_result_data, fn( $a, $b ) => $b['score'] <=> $a['score'] );
			}
			if ( $limited ) {
				$default_settings = ywcas()->settings->get_classic_default_settings();
				$num_of_results   = ! $num_of_results ? $default_settings['maxResults'] : $num_of_results;
				if ( $search_result_data > $num_of_results ) {
					$search_result_data = array_slice( $search_result_data, 0, $num_of_results );
				}
			} elseif ( $page > 0 ) {
				if ( $need_store_transient ) {
					set_transient( 'ywcas_stored_query_' . $query_string, $search_result_data, MINUTE_IN_SECONDS * 5 );
				}
				$offset = ( $page - 1 ) * $num_of_results;

				$search_result_data = array_slice( $search_result_data, $offset, $num_of_results );
			}

			$stop_search = hrtime( true );
			$debug && $this->logger->log( 'Execution time ' . ( $stop_search - $start_search ) / 1e+9 );

			return array(
				'fuzzyToken'   => $best_token,
				'totalResults' => $total_results,
				'results'      => $search_result_data,
			);
		}

		/**
		 * Save the query inside the database and retrieve the id of the logger.
		 *
		 * @param   string  $query_string   Query string.
		 * @param   int     $total_results  Number of result.
		 * @param   int     $item_id        Item ID.
		 * @param   string  $lang           Language.
		 *
		 * @return int
		 */
		public function get_logger_reference( $query_string, $total_results, $item_id, $lang ) {
			if ( empty( $query_string ) || ! empty( $this->noresult_query ) ) {
				return 0;
			}
			$this->noresult_query = $query_string;

			return YITH_WCAS_Data_Search_Query_Log::insert(
				array(
					'user_id'         => get_current_user_id(),
					'query'           => $query_string,
					'search_date'     => current_time( 'mysql' ),
					'num_results'     => $total_results,
					'clicked_product' => $item_id,
					'lang'            => $lang,
				)
			);
		}


		/**
		 * Get results from tokens
		 *
		 * @param   array   $query_tokens  Tokens.
		 * @param   string  $post_type     Post type.
		 * @param   int     $category      Category.
		 * @param   string  $lang          Current language.
		 *
		 * @return array
		 */
		public function get_search_results( $query_tokens, $post_type, $category, $lang ) {
			if ( ! $query_tokens ) {
				return array();
			}

			$data_index_by_tokens = $this->get_data_index_by_tokens( $query_tokens, $lang );

			if ( ! $data_index_by_tokens ) {
				return array();
			}

			$search_results = $this->cross_results( $data_index_by_tokens );
			if ( ! $search_results ) {
				return array();
			}

			$ids     = array_column( $search_results, 'post_id' );
			$results = YITH_WCAS_Data_Index_Lookup::get_instance()->get_data_by_id( $ids, $post_type, $category );
			$results = $this->add_score( $results, $search_results );

			return $this->filter_results( $results );
		}


		/**
		 * Add the score inside the results adding the boost.
		 *
		 * @param   array  $results         Results from lookup.
		 * @param   array  $search_results  Results from relashionship.
		 *
		 * @return array
		 * @since 2.1.0
		 */
		public function add_score( $results, $search_results ) {

			foreach ( $search_results as $search_result ) {
				$key = array_search( $search_result['post_id'], array_column( $results, 'post_id' ) );

				if ( false !== $key ) {
					$boost                    = $results[ $key ]['boost'] ?? 0;
					$results[ $key ]['score'] = $boost > 0 ? $boost * $search_result['score'] : $search_result['score'];
				}
			}

			return $results;
		}


		/**
		 * Return the fuzzy query string
		 *
		 * @param $query_tokens
		 *
		 * @return void
		 */
		public function get_fuzzy_query_string( $query_tokens, $lang ) {
			if ( ! $query_tokens ) {
				return array();
			}

			$best_token = array();

			foreach ( $query_tokens as $query_token ) {
				if ( strlen( $query_token ) < $this->fuzzy_prefix_length + 1 ) {
					continue;
				}
				$token_names = array();
				$token       = substr( $query_token, 0, $this->fuzzy_prefix_length );
				$token       = $this->prepare_token( $token );

				$token_results = YITH_WCAS_Data_Index_Token::get_instance()->search_similar_token( $token, $lang, $this->fuzzy_max_tokens );


				$tokens_grouped_by_distance_token = array();
				if ( $token_results ) {
					foreach ( $token_results as $token_result ) {

						$distance = levenshtein( $token_result['token'], $query_token );
						if ( $distance <= ywcas()->settings->get_fuzzy_distance() ) {
							$tokens_grouped_by_distance_token[ $distance ][] = $token_result['token'];
						}
					}


					if ( $tokens_grouped_by_distance_token ) {
						asort( $tokens_grouped_by_distance_token );

						foreach ( $tokens_grouped_by_distance_token as $token_name_group ) {
							$token_names = array_merge( $token_names, $token_name_group );
						}
					}

					if ( $token_names ) {
						$best_token = $token_names[0];
					}
				}
			}

			return $best_token;

		}


		/**
		 * Search data index for soundex strings
		 *
		 * @param   array   $query_tokens  Tokens.
		 * @param   string  $post_type     Post Type.
		 * @param   string  $lang          Current languages.
		 * @param   int     $category      Category.
		 *
		 * @return array
		 */
		public function get_soundex_search_results( $query_tokens, $post_type, $lang, $category = 0 ) {
			$results = array();

			if ( ! $query_tokens ) {
				return $results;
			}
			$data_index_by_tokens = array();
			foreach ( $query_tokens as $query_token ) {
				$token_results = YITH_WCAS_Data_Index_Token::get_instance()->search_soundex_token( $query_token, $lang );
				if ( $token_results ) {
					$docs             = YITH_WCAS_Data_Index_Relationship::get_instance()->search_post_id( $token_results );
					$this->lookup_ids = array_merge( $this->lookup_ids, $docs );
					if ( $docs ) {
						$data_index_by_tokens[ $query_token ] = $docs;
					}
				}
			}

			if ( ! $data_index_by_tokens ) {
				return $results;
			}

			$search_results = $this->cross_results( $data_index_by_tokens );
			if ( ! $search_results ) {
				return $results;
			}
			$ids     = array_column( $search_results, 'post_id' );
			$results = YITH_WCAS_Data_Index_Lookup::get_instance()->get_data_by_id( $ids, $post_type, $category );
			$results = $this->add_score( $results, $search_results );

			return $this->filter_results( $results );
		}

		/**
		 * Prepare the token for the query
		 *
		 * @param   string  $token  String to prepare.
		 *
		 * @return string
		 */
		public function prepare_token( $token ) {
			return '%' . $token . '%';
		}

		/**
		 * Return the search tokens as array ordered from word length
		 *
		 * @param   string  $query_string  Query string.
		 *
		 * @return array
		 */
		public function get_search_tokens( $query_string ) {
			$tokens = YITH_WCAS_Data_Index_Tokenizer::tokenize( $query_string, 'search' );
			usort(
				$tokens,
				function ( $a, $b ) {
					return strlen( $b ) - strlen( $a );
				}
			);

			return $tokens;
		}

		/**
		 * Search data index for each token.
		 *
		 * @param   array   $query_tokens  List of tokens.
		 * @param   string  $lang          Current languages.
		 *
		 * @return array
		 */
		public function get_data_index_by_tokens( $query_tokens, $lang ) {
			$documents = array();

			foreach ( $query_tokens as $query_token ) {
				// searching the exact token.
				$token_result_raw = YITH_WCAS_Data_Index_Token::get_instance()->search( $query_token, $lang );


				$query_token = $this->prepare_token( $query_token );
				// searching the generic token.
				$token_result = YITH_WCAS_Data_Index_Token::get_instance()->search( $query_token, $lang );

				$token_result = array_unique( array_merge( $token_result_raw, $token_result ) );

				if ( $token_result ) {
					$docs = YITH_WCAS_Data_Index_Relationship::get_instance()->search_post_id( $token_result );
					if ( $docs ) {
						$documents[ $query_token ] = ywcas_remove_duplicated_results( $docs );
					}
				}
			}

			return $documents;
		}

		/**
		 * Cross the data index to find the better result
		 *
		 * @param   array  $data_index_by_tokens  Data index bu Token.
		 *
		 * @return array
		 */
		public function cross_results( $data_index_by_tokens ) {
			$search_result = array();

			if ( $data_index_by_tokens ) {
				if ( count( $data_index_by_tokens ) > 1 ) {
					// if the tokens are more than one, to find the best results we make the sum of score if the result is recurring for different tokens.
					foreach ( $data_index_by_tokens as $data ) {
						foreach ( $data as $item ) {
							if ( isset( $search_result[ $item['post_id'] ] ) ) {
								$search_result[ $item['post_id'] ]['score'] = $search_result[ $item['post_id'] ]['score'] + $item['score'];
							} else {
								$search_result[ $item['post_id'] ] = $item;
							}
						}
					}
				} else {
					$search_result = current( $data_index_by_tokens );
				}
			}

			return $search_result;
		}

		/**
		 * Return the max number of results.
		 *
		 * @param   array  $search_result  Result to filter.
		 *
		 * @return array
		 */
		public function filter_results( $search_result ) {
			$main_results       = array();
			$include_variations = 'yes' === ywcas()->settings->get_include_variations();
			if ( $search_result ) {
				foreach ( $search_result as $result ) {
					if ( isset( $result['url'] ) ) {
						$result['url'] = apply_filters( 'wpml_permalink', $result['url'], substr( $result['lang'], 0, 2 ) );
					}
					if ( isset( $result['thumbnail'] ) ) {
						$result['thumbnail'] = maybe_unserialize( $result['thumbnail'] );
						if ( ! isset( $result['thumbnail']['small'] ) ) {
							$thumb               = $result['thumbnail'];
							$result['thumbnail'] = array(
								'small' => $thumb,
								'big'   => $thumb
							);

						}
					}

					if ( isset( $result['parent_category'] ) ) {
						$result['parent_category'] = maybe_unserialize( $result['parent_category'] );
					}
					if ( isset( $result['tags'] ) ) {
						$result['tags'] = maybe_unserialize( $result['tags'] );
					}
					if ( isset( $result['custom_taxonomies'] ) ) {
						$result['custom_taxonomies'] = maybe_unserialize( $result['custom_taxonomies'] );
					}
					if ( ! $include_variations && isset( $result['product_type'] ) && 'variation' === $result['product_type'] ) {
						if ( ! in_array( $result['post_parent'], $main_results ) ) {
							$parent                                 = YITH_WCAS_Data_Index_Lookup::get_instance()->get_element_by_post_id( $result['post_parent'] );
							$parent['score']                        = $result['score'];
							$main_results[ $result['post_parent'] ] = $parent;
						}
					} else {
						$main_results[ $result['post_id'] ] = $result;
					}
				}
			}

			return $main_results;
		}

		/**
		 * Get category info
		 *
		 * @param   array  $results  Results.
		 *
		 * @return array
		 */
		public function add_categories( $results ) {
			$categories = array();
			if ( ! class_exists( 'YITH_WCAS_Data_Index_Taxonomy' ) ) {
				return $categories;
			}

			foreach ( $results as $result ) {
				$parent_categories = (array) maybe_unserialize( $result['parent_category'] );
				$categories        = array_merge( $categories, $parent_categories );
			}
			if ( ! $categories ) {
				return array();
			}

			return YITH_WCAS_Data_Index_Taxonomy::get_instance()->get_taxnomies( $categories );
		}
	}

}