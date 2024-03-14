<?php
/**
 * Searchanise Fulltext search
 *
 * @package Searchanise/Search
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Fulltext search implementation
 */
class Fulltext_Search extends Abstract_Extension {

	// Request errors.
	const ERROR_EMPTY_API_KEY                   = 'EMPTY_API_KEY';
	const ERROR_INVALID_API_KEY                 = 'INVALID_API_KEY';
	const ERROR_TO_BIG_START_INDEX              = 'TO_BIG_START_INDEX';
	const ERROR_SEARCH_DATA_NOT_IMPORTED        = 'SEARCH_DATA_NOT_IMPORTED';
	const ERROR_FULL_IMPORT_PROCESSED           = 'FULL_IMPORT_PROCESSED';
	const ERROR_FACET_ERROR_TOO_MANY_ATTRIBUTES = 'FACET_ERROR_TOO_MANY_ATTRIBUTES';
	const ERROR_NEED_RESYNC_YOUR_CATALOG        = 'NEED_RESYNC_YOUR_CATALOG';
	const ERROR_FULL_FEED_DISABLED              = 'FULL_FEED_DISABLED';
	const ERROR_ENGINE_REMOVED                  = 'ENGINE_REMOVED';
	const ERROR_ENGINE_DISABLED                 = 'ENGINE_DISABLED';

	// Request types.
	const TYPE_TEXT_FIND     = 'TEXT_FIND';
	const TYPE_ADVANCED_FIND = 'ADVANCED_FIND';

	// Default sorting values.
	const DEFAULT_SORT_BY    = 'relevance';
	const DEFAULT_SORT_ORDER = 'asc';

	// Cache data.
	const CACHE_ENABLED        = true;
	const CACHE_PREFIX         = 'se_results_';
	const CACHE_LAST_PREFIX    = 'se_last_results_';
	const CACHE_TIME           = 60;
	const CACHE_LAST_TIME      = 10;
	const CACHE_DEBUG_VAR_NAME = 'nc';
	const CACHE_DEBUG_KEY      = 'Y';

	// Sorting mapping.
	const ORDER_MAP = array(
		'relevance'  => 'relevance',
		'id'         => 'id',
		'title'      => 'title',
		'popularity' => 'sales_amount',
		'rating'     => 'reviews_average_score',
		'date'       => 'created',
		'modified'   => 'modified',
		'price'      => 'price',
		'menu_order' => 'menu_order',
		'sku'        => 'product_code',
		// TODO: 'rand'.
	);

	const RATING_MAP = array(
		'fivestar' => 5,
		'fourstar' => 4,
		'threestar' => 3,
		'twostar' => 2,
		'onestar' => 1,
		'nostar' => 0,
	);

	/**
	 * Searchanise search results
	 *
	 * @var array
	 */
	protected $search_result = array();

	/**
	 * Search product ids
	 *
	 * @var array
	 */
	protected $product_ids = array();

	/**
	 * List of all attributes with count
	 *
	 * @var array
	 */
	protected $attributes_count = array();

	/**
	 * Search params
	 *
	 * @var array
	 */
	protected $search_params = array();

	/**
	 * Current lang code
	 *
	 * @var string
	 */
	private $lang_code;

	/**
	 * If search processed flag
	 *
	 * @var bool
	 */
	private $search_processed = false;

	/**
	 * Last hash query
	 *
	 * @var string
	 */
	private $last_query_hash = '';

	/**
	 * Search constructor
	 */
	public function __construct() {
		$this->lang_code = Api::get_instance()->get_currently_language();
		parent::__construct();
	}

	/**
	 * Check if extension is active
	 *
	 * @return boolean
	 */
	public function is_active() {
		return Api::get_instance()->get_enabled_searchanise_search() && ! is_admin();
	}

	/**
	 * Returns hooks & filters priority
	 *
	 * @return int
	 */
	public function get_priority() {
		return PHP_INT_MAX;
	}

	/**
	 * Returns actions list
	 *
	 * @return array
	 */
	public function get_hooks() {
		return array(
			'pre_get_posts',
		);
	}

	/**
	 * Returns filters list
	 *
	 * @return array
	 */
	public function get_filters() {
		return array(
			'posts_clauses_request',
			'found_posts',
			'posts_orderby',
			'woocommerce_catalog_orderby',
			'the_posts',
			'posts_pre_query',
			'woocommerce_get_filtered_term_product_counts_query',
			'woocommerce_layered_nav_count_maybe_cache',
			'woocommerce_price_filter_widget_min_amount',
			'woocommerce_price_filter_widget_max_amount',
			'woocommerce_price_filter_widget_step',
			'rest_post_dispatch',
		);
	}

	/**
	 * Filters the REST API response.
	 *
	 * Allows modification of the response before returning.
	 *
	 * @since 4.4.0
	 * @since 4.5.0 Applied to embedded responses.
	 *
	 * @param \WP_HTTP_Response $result  Result to send to the client. Usually a `WP_REST_Response`.
	 * @param \WP_REST_Server   $server  Server instance.
	 * @param \WP_REST_Request  $request Request used to generate the response.
	 */
	public function restPostDispatch( \WP_HTTP_Response $result, \WP_REST_Server $server, \WP_REST_Request $request ) {
		$api_key = Api::get_instance()->get_api_key( $this->lang_code );
		$last_cache_key = md5(
			wp_json_encode(
				array(
					'api_key'   => $api_key,
					'lang_code' => $this->lang_code,
				)
			)
		);

		$last_result = get_transient( self::CACHE_LAST_PREFIX . $last_cache_key );
		$route = $request->get_route();
		$data = $result->get_data();

		if ( $last_result && preg_match( '/\/products\/collection-data/', $route, $matches ) ) {
			foreach ( $last_result['facets'] as $facet ) {
				if ( 'price' == $facet['attribute'] ) {
					$price_facet = $facet;
				}

				if ( 'stock_status' == $facet['attribute'] ) {
					$stock_status_facet = $facet;
				}

				if ( 'reviews_average_score_titles' == $facet['attribute'] ) {
					$rating_facet = $facet;
				}
			}

			// Replace price range.
			if ( isset( $data['price_range'] ) ) {
				if ( empty( $price_facet ) ) {
					$data['price_range'] = null;
				} else {
					$decmal_number = $data['price_range']->currency_minor_unit;

					$data['price_range']->min_price = (string) ( $price_facet['buckets'][0]['from'] * pow( 10, $decmal_number ) );
					$data['price_range']->max_price = (string) ( $price_facet['buckets'][0]['to'] * pow( 10, $decmal_number ) );
				}
			}

			// Replace stock status.
			if ( isset( $data['stock_status_counts'] ) ) {
				if ( empty( $stock_status_facet ) ) {
					$data['stock_status_counts'] = null;
				} else {
					foreach ( $data['stock_status_counts'] as &$stock ) {
						if ( 'instock' == $stock->status ) {
							foreach ( $stock_status_facet['buckets'] as $stock_bucket ) {
								if ( 'instock' == $stock_bucket['value'] ) {
									$stock->count = $stock_bucket['count'];
								}
							}
						}
					}
				}
			}

			// Taxonomy counts.
			if ( isset( $data['attribute_counts'] ) ) {
				$attributes = $request->get_param( 'calculate_attribute_counts' );

				foreach ( $attributes as $attr ) {
					foreach ( $last_result['facets'] as $facet ) {
						$facet_attribute = str_replace( Async::CUSTOM_TAXONOMY_PREFIX, '', $facet['attribute'] );

						if ( wc_attribute_taxonomy_name( $facet_attribute ) != $attr['taxonomy'] ) {
							continue;
						}

						$terms = get_terms(
							array(
								'taxonomy'   => $attr['taxonomy'],
								'hide_empty' => false,
							)
						);

						foreach ( $terms as $term ) {
							$found = false;

							foreach ( $facet['buckets'] as $facet_bucket ) {
								if ( $facet_bucket['value'] == $term->slug ) {
									foreach ( $data['attribute_counts'] as &$attribute_count ) {
										if ( $attribute_count->term == $term->term_id ) {
											$attribute_count->count = $facet_bucket['count'];
											$found = true;
											break;
										}
									}

									unset( $attribute_count );
								}
							}

							if ( ! $found ) {
								foreach ( $data['attribute_counts'] as $k => $attribute_count ) {
									if ( $attribute_count->term == $term->term_id ) {
										unset( $data['attribute_counts'][ $k ] );
									}
								}
							}
						}
					}
				}
			}

			// Rating counts.
			if ( isset( $data['rating_counts'] ) ) {
				if ( empty( $rating_facet ) ) {
					$data['rating_counts'] = array();
				} else {
					foreach ( $data['rating_counts'] as $rating_counts ) {
						foreach ( $rating_facet['buckets'] as $rating_facet_bucket ) {
							if ( self::RATING_MAP[ $rating_facet_bucket['value'] ] == $rating_counts->rating ) {
								$rating_counts->count = $rating_facet_bucket['count'];
								break;
							}
						}
					}
				}
			}
		}

		if ( $last_result && preg_match( '/\/products\/attributes\/([\d]+)\/terms/', $route, $matches ) ) {
			$ids       = array_flip( wc_get_attribute_taxonomy_ids() );
			$term_id   = $matches[1];
			$term_slug = $ids[ $term_id ];

			foreach ( $last_result['facets'] as $facet ) {
				$facet_attribute = str_replace( Async::CUSTOM_TAXONOMY_PREFIX, '', $facet['attribute'] );

				if ( $facet_attribute == $term_slug ) {
					foreach ( $data as $k => &$attr ) {
						$found = false;

						foreach ( $facet['buckets'] as $facet_bucket ) {
							if ( $facet_bucket['value'] == $attr['slug'] ) {
								$attr['count'] = $facet_bucket['count'];
								$found = true;
							}
						}

						if ( ! $found ) {
							unset( $data[ $k ] );
						}
					}

					unset( $attr );
					break;
				}
			}

			$data = array_values( $data );
		}

		$result->set_data( $data );

		return $result;
	}

	/**
	 * Check if this is a search results page
	 *
	 * @return boolean
	 */
	public function isSearchRequest() {
		return is_search() && $this->search_processed;
	}

	/**
	 * Returns did you mean text
	 *
	 * @return string
	 */
	public function getDidYouMeanText() {
		$result = '';

		if (
			$this->checkSearchResults()
			&& $this->getTotalProducts() == 0
		) {
			$sugs = $this->getSuggestions();

			if ( ! empty( $sugs ) ) {
				$suggestions_max_results = Api::get_instance()->get_suggestions_max_results();
				$search_params = $this->getSearchParams();
				$text_find = $search_params['q'];
				$message = __( 'Did you mean:', 'woocommerce-searchanise' );
				$links = array();
				$sug_count = 0;

				foreach ( $sugs as $sug ) {
					if ( ! empty( $sug ) && $sug != $text_find ) {
						$url = $this->getSuggestionLink( $sug, $this->lang_code );
						$links[] = "<a href='{$url}'>{$sug}</a>";
						$sug_count++;
					}

					if ( $sug_count >= $suggestions_max_results ) {
						break;
					}
				}

				if ( ! empty( $links ) ) {
					$result = $message . ' ' . implode( ', ', $links ) . '?';
				}
			}
		}

		return $result;
	}

	/**
	 * WooCommmerce query filter for terms counts
	 *
	 * @param array $query Query.
	 *
	 * @return array
	 */
	public function getFilteredTermProductCountsQuery( $query ) {
		global $wp_query;

		if (
			$this->checkSearchResults()
			|| ( Api::get_instance()->is_navigation_enabled( $this->lang_code ) && $wp_query->is_tax( 'product_cat' ) )
		) {
			// We need to calculate query hash here to use it in future.
			$this->last_query_hash = md5( implode( ' ', $query ) );
		}

		return $query;
	}

	/**
	 * Filter store a transient of the count values.
	 *
	 * @param boolean $flag Cache flag.
	 *
	 * @return boolean
	 */
	public function layeredNavCountMaybeCache( $flag ) {
		// Make sure that WooCommerce use cache to display filters.
		return true;
	}

	/**
	 * Filters price slider step
	 *
	 * @param int $step Step value.
	 *
	 * @return int
	 */
	public function priceFilterWidgetStep( $step ) {
		if ( $this->checkSearchResults() ) {
			$step = 1;
		}

		return $step;
	}

	/**
	 * Filters min price slider value
	 *
	 * @param number $price Price.
	 *
	 * @return number
	 */
	public function priceFilterWidgetMinAmount( $price ) {
		if ( $this->checkSearchResults() ) {
			$counts = $this->getCountAttribute( 'price' );

			foreach ( $counts as $range => $cnt ) {
				list($min, $max) = explode( ',', $range );
				break;
			}

			$rate = Api::get_instance()->get_currency_rate();
			if ( ! empty( $rate ) && 1 != $rate ) {
				$min /= $rate;
			}

			$price = floor( $min );
		}

		return $price;
	}

	/**
	 * Filters max price slider value
	 *
	 * @param number $price Price.
	 *
	 * @return number
	 */
	public function priceFilterWidgetMaxAmount( $price ) {
		if ( $this->checkSearchResults() ) {
			$counts = $this->getCountAttribute( 'price' );

			foreach ( $counts as $range => $cnt ) {
				list($min, $max) = explode( ',', $range );
			}

			$rate = Api::get_instance()->get_currency_rate();
			if ( ! empty( $rate ) && 1 != $rate ) {
				$max /= $rate;
			}

			$price = ceil( $max );
		}

		return $price;
	}

	/**
	 * WooCommerce catalog sort by filter
	 *
	 * @param array $sortby SortBy data.
	 *
	 * @return array
	 */
	public function catalogOrderby( $sortby ) {
		if ( $this->checkSearchResults() && $this->isSearchRequest() ) {
			foreach ( $sortby as $name => $title ) {
				$sort_mapping = $this->getSortMapping();

				list($name_part) = explode( ' ', $name );
				if ( preg_match( '/(.*)[-_](desc|asc)$/', $name_part, $matches ) ) {
					$name_part = $matches[1];
				}

				if ( ! key_exists( $name_part, $sort_mapping ) ) {
					unset( $sortby[ $name ] );
				}

				// Additional check rating.
				if ( 'rating' == $name_part ) {
					if ( ! $this->isReviewEnabled() ) {
						unset( $sortby[ $name ] );
					}
				}
			}
		}

		return $sortby;
	}

	/**
	 * Filters all query clauses at once, for convenience.
	 *
	 * For use by caching plugins.
	 *
	 * Covers the WHERE, GROUP BY, JOIN, ORDER BY, DISTINCT,
	 * fields (SELECT), and LIMITS clauses.
	 *
	 * @since 3.1.0
	 *
	 * @param string[] $clauses {
	 *     Associative array of the clauses for the query.
	 *
	 *     @type string $where    The WHERE clause of the query.
	 *     @type string $groupby  The GROUP BY clause of the query.
	 *     @type string $join     The JOIN clause of the query.
	 *     @type string $orderby  The ORDER BY clause of the query.
	 *     @type string $distinct The DISTINCT clause of the query.
	 *     @type string $fields   The SELECT clause of the query.
	 *     @type string $limits   The LIMIT clause of the query.
	 * @param WP_Query $wp_query  The WP_Query instance (passed by reference).
	 */
	public function postsClausesRequest( $clauses, $wp_query ) {
		global $wpdb;

		if ( $this->checkSearchResults() && $this->isSearchRequest() ) {
			$search_params = $this->getSearchParams();
			$product_ids_str = implode( ',', $this->getProductIds() );

			if ( empty( $product_ids_str ) ) {
				// To avoid empty IN () SQL query.
				$product_ids_str = '0';
			}

			// Reset condition params.
			$clauses['where']   = " AND {$wpdb->prefix}posts.ID IN ({$product_ids_str})";
			$clauses['limits']  = "LIMIT 0, {$search_params['maxResults']}";
			$clauses['orderby'] = "FIELD({$wpdb->prefix}posts.ID, {$product_ids_str})";
			$clauses['join']    = '';
		}

		return $clauses;
	}

	/**
	 * Filters the number of found posts for the query.
	 *
	 * @since 2.1.0
	 *
	 * @param int      $found_posts The number of posts found.
	 * @param WP_Query $wp_query    The WP_Query instance (passed by reference).
	 */
	public function foundPosts( $found_posts, $wp_query ) {
		if ( $this->checkSearchResults() && $this->isSearchRequest() ) {
			$found_posts = $this->getTotalProducts();
		}

		return $found_posts;
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * Note: If using conditional tags, use the method versions within the passed instance
	 * (e.g. $this->is_main_query() instead of is_main_query()). This is because the functions
	 * like is_main_query() test against the global $wp_query instance, not the passed one.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Query $wp_query The WP_Query instance (passed by reference).
	 */
	public function preGetPosts( $wp_query ) {
		if ( is_search() && is_shop() ) {
			$this->search_processed = $this->executeSearchRequest( $wp_query->query, $wp_query->query_vars, self::TYPE_TEXT_FIND, $this->lang_code );

		} elseif ( Api::get_instance()->is_navigation_enabled( $this->lang_code ) && $wp_query->is_tax( 'product_cat' ) ) {
			$taxonomies = wc_get_attribute_taxonomies();
			foreach ( $taxonomies as $taxonomy ) {
				$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
				$fn_wc_layered_nav_counts = function ( $pre_transient, $transient ) {
					if ( empty( $this->last_query_hash ) ) {
						return $pre_transient;
					}

					return array(
						$this->last_query_hash => 0,
					);
				};

				add_filter(
					'pre_transient_wc_layered_nav_counts_' . sanitize_title( $taxonomy_name ),
					$fn_wc_layered_nav_counts,
					PHP_INT_MAX,
					2
				);
				add_filter(
					'wc_layered_nav_counts_' . sanitize_title( $taxonomy_name ),
					$fn_wc_layered_nav_counts,
					PHP_INT_MAX,
					2
				);
			}
		}
	}

	/**
	 * Filters the posts array before the query takes place.
	 *
	 * Return a non-null value to bypass WordPress' default post queries.
	 *
	 * Filtering functions that require pagination information are encouraged to set
	 * the `found_posts` and `max_num_pages` properties of the WP_Query object,
	 * passed to the filter by reference. If WP_Query does not perform a database
	 * query, it will not have enough information to generate these values itself.
	 *
	 * @since 4.6.0
	 *
	 * @param WP_Post[]|int[]|null $posts Return an array of post data to short-circuit WP's query,
	 *                                    or null to allow WP to run its normal queries.
	 * @param WP_Query             $query The WP_Query instance (passed by reference).
	 */
	public function postsPreQuery( $posts, $query ) {
		if ( is_product_category() && Api::get_instance()->is_navigation_enabled( $this->lang_code ) && $query->is_tax( 'product_cat' ) ) {
			$posts = array();
		}

		return $posts;
	}

	/**
	 * Filters the array of retrieved posts after they've been fetched and
	 * internally processed.
	 *
	 * @since 1.5.0
	 *
	 * @param array    $posts The array of retrieved posts.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	public function thePosts( $posts, $query ) {
		$this->search_processed = false;
		return $posts;
	}

	/**
	 * Get sorting parameters from WP request
	 *
	 * @param array  $query       Search query.
	 * @param array  $query_vars  Search query variables.
	 * @param string $sort_by     Default sort by.
	 * @param string $sort_order  Default sort order.
	 *
	 * @return string[] array($sort_by, $sort_order)
	 */
	private function getSortings( $query, $query_vars, $sort_by, $sort_order ) {
		if ( ! empty( $query['orderby'] ) ) {
			$sort_by = $query['orderby'];
		}

		if ( ! empty( $query_vars['order'] ) ) {
			$sort_order = strtolower( $query_vars['order'] );
		}

		$sort_mapping = $this->getSortMapping();

		// WooCommerce allow to ordering by more then one attribute
		// So, process only the first one.
		list($sort_by) = explode( ' ', $sort_by );
		$sort_order_override = false;

		if ( preg_match( '/(.*)[-_](desc|asc)$/', $sort_by, $matches ) ) {
			$sort_by = $matches[1];
			$sort_order = $matches[2];
			$sort_order_override = true;
		}

		if ( isset( $sort_mapping[ $sort_by ] ) ) {
			$sort_by = $sort_mapping[ $sort_by ];
		}

		$sort_order = strtolower( $sort_order );

		// Adjust ordering.
		if ( false == $sort_order_override ) {
			// Sorting by rating, bestsellers and date has only desc sort_order value.
			if ( in_array( $sort_by, array( 'reviews_average_score', 'sales_amount', 'created' ) ) ) {
				$sort_order = 'desc';
			}

			// Relevance should has asc order.
			if ( 'relevance' == $sort_by ) {
				$sort_order = 'asc';
			}
		}

		/**
		 * Filters sortings for Searchanise
		 *
		 * @since 1.0.0
		 *
		 * @param array ($sort_by, $sort_order) Sort data.
		 * @param array $sort_mapping Sorting mapping.
		 */
		return (array) apply_filters( 'se_get_sortings', array( $sort_by, $sort_order ), $sort_mapping );
	}

	/**
	 * Prepare search request params and execute query
	 *
	 * @param array  $query      Search query.
	 * @param array  $query_vars Search query vars.
	 * @param string $type       Query type.
	 * @param string $lang_code  Lang code.
	 *
	 * @return boolean
	 */
	private function executeSearchRequest( $query, $query_vars, $type, $lang_code ) {
		if (
			! Api::get_instance()->get_enabled_searchanise_search()
			|| ! Api::get_instance()->is_search_allowed( $lang_code )
			|| ( empty( $query_vars['wc_query'] ) || 'product_query' != $query_vars['wc_query'] )
		) {
			return false;
		}

		$params = array();

		$params['restrictBy']['status'] = 'publish';
		$params['restrictBy']['visibility'] = 'visible|catalog|search';
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$params['restrictBy']['is_in_stock'] = 'Y';
		}

		if ( self::TYPE_TEXT_FIND == $type ) {
			// Text search.
			$params['q'] = '';
			if ( ! empty( $query_vars['s'] ) ) {
				$params['q'] = strtolower( trim( $query_vars['s'] ) );
			}

			$params['facets']                = 'true';
			$params['suggestions']           = 'true';
			$params['query_correction']      = 'false';
			$params['suggestionsMaxResults'] = Api::get_instance()->get_suggestions_max_results();

		} else {
			// Advanced text search.
			$params['q'] = '';
			// TODO: Remove $_REQUEST from here.
			if ( ! empty( $_REQUEST['s'] ) ) {
				$params['q'] = strtolower( trim( sanitize_key( $_REQUEST['s'] ) ) );
			}

			$params['facets']           = 'false';
			$params['suggestions']      = 'false';
			$params['query_correction'] = 'false';
		}

		// Prepare sortings.
		list($sort_by, $sort_order) = $this->getDefaultSortings();
		list($sort_by, $sort_order) = $this->getSortings( $query, $query_vars, $sort_by, $sort_order );

		// Prepare limits.
		list($start_index, $max_results) = $this->getLimits( $query_vars );

		// Assign vars.
		$params['maxResults'] = (int) $max_results;
		$params['startIndex'] = (int) $start_index;
		$params['sortBy']     = $sort_by;
		$params['sortOrder']  = $sort_order;
		$params['recentlyViewedProducts'] = Api::get_instance()->get_recently_viewed_product_ids();

		// Prepare facets.
		$this->prepareFiltersFromRequest( $params, $_REQUEST, $lang_code );

		// There is no correct WooCommerce hooks for attribute counts
		// But WooCommerce use cache to store the attributes count
		// So, we have to use Wordpress cache hooks to replace cache results.
		$taxonomies = wc_get_attribute_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
			$that = $this;
			$fn_wc_layered_nav_counts = function ( $pre_transient, $transient ) use ( $taxonomy, $that ) {
				if ( empty( $this->last_query_hash ) || ! $that->checkSearchResults() ) {
					return $pre_transient;
				}

				$transient_counts = array();
				$counts = $that->getCountAttribute( $taxonomy->attribute_name );
				$terms = get_terms(
					array(
						'taxonomy' => wc_attribute_taxonomy_name( $taxonomy->attribute_name ),
						'hide_empty' => true,
					)
				);

				// WooCommerce use format [term_id => count] for attribute counts
				// So, convert to this format.
				foreach ( $terms as $term ) {
					if ( isset( $counts[ $term->slug ] ) ) {
						$transient_counts[ $term->term_id ] = $counts[ $term->slug ];
					} else {
						$transient_counts[ $term->term_id ] = 0;
					}
				}

				return array(
					$this->last_query_hash => $transient_counts,
				);
			};

			add_filter(
				'pre_transient_wc_layered_nav_counts_' . sanitize_title( $taxonomy_name ),
				$fn_wc_layered_nav_counts,
				PHP_INT_MAX,
				2
			);
			add_filter(
				'wc_layered_nav_counts_' . sanitize_title( $taxonomy_name ),
				$fn_wc_layered_nav_counts,
				PHP_INT_MAX,
				2
			);
		}

		/**
		 * Filters search params
		 *
		 * @since 1.0.0
		 *
		 * @param array $params Search params
		 * @param array $query Search query
		 * @param array $query_vars Search query vars
		 * @param string $type Query type
		 * @param string $lang_code Lang code
		 */
		$params = apply_filters( 'se_prepare_search_params', $params, $query, $query_vars, $type, $lang_code );

		$this->setSearchParams( $params );

		return $this->sendSearchAndRequest( $params, $lang_code );
	}

	/**
	 * Parse filters from request and adds them to Searchanise search params
	 *
	 * @param array  $params    Searchanise search params.
	 * @param array  $request   Search request.
	 * @param string $lang_code Lang code.
	 */
	private function prepareFiltersFromRequest( array &$params, array $request, $lang_code ) {
		if ( empty( $request ) ) {
			return false;
		}

		$min_price     = isset( $request['min_price'] ) ? wc_clean( wp_unslash( $request['min_price'] ) ) : '';
		$max_price     = isset( $request['max_price'] ) ? wc_clean( wp_unslash( $request['max_price'] ) ) : '';
		$rating_filter = isset( $request['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $request['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.

		// Prepare price filter.
		if ( '' !== $min_price || '' !== $max_price ) {
			$rate = Api::get_instance()->get_currency_rate();

			if ( ! empty( $rate ) && 1.0 != $rate ) {
				if ( null !== $min_price ) {
					$min_price *= $rate;
				}

				if ( null !== $max_price ) {
					$max_price *= $rate;
				}
			}

			$params['restrictBy']['price'] = "{$min_price},{$max_price}";

			// Adds usergroup min price.
			$user_groups = Api::get_instance()->get_current_usergroup_ids();
			if ( ! empty( $user_groups ) ) {
				$_prices = array();

				foreach ( $user_groups as $usergroup_id ) {
					$_prices[] = Api::LABEL_FOR_PRICES_USERGROUP . $usergroup_id;
				}

				$params['union']['price']['min'] = implode( '|', $_prices );
			}
		}

		// Prepare review filter.
		if ( ! empty( $rating_filter ) && $this->isReviewEnabled() ) {
			$params['restrictBy']['reviews_average_score'] = implode( '|', $rating_filter );
		}

		// Preapre attributes filter.
		foreach ( $request as $key => $value ) {
			if ( 0 === strpos( $key, 'filter_' ) ) {
				$attribute    = wc_sanitize_taxonomy_name( str_replace( 'filter_', '', $key ) );
				$taxonomy     = wc_attribute_taxonomy_name( $attribute );
				$filter_terms = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();

				if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) || ! wc_attribute_taxonomy_id_by_name( $attribute ) ) {
					// Invalid attribute filter.
					continue;
				}

				$filter_terms = array_map( 'sanitize_title', $filter_terms );
				$query_type   = ! empty( $request[ 'query_type_' . $attribute ] ) && in_array( $request[ 'query_type_' . $attribute ], array( 'and', 'or' ), true ) ? wc_clean( wp_unslash( $request[ 'query_type_' . $attribute ] ) ) : ''; // WPCS: sanitization ok, input var ok, CSRF ok.
				$query_type   = ! empty( $query_type ) ? $query_type : 'and';
				$attribute_id = Async::get_taxonomy_id( $attribute );

				if ( 'and' == $query_type ) {
					$params['restrictBy'][ $attribute_id ] = implode( ',', $filter_terms );
				} else {
					$params['restrictBy'][ $attribute_id ] = implode( '|', $filter_terms );
				}
			}
		}

		/**
		 * Filters Searchanise filters from request
		 *
		 * @since 1.0.0
		 *
		 * @param array $params     Searchanise search params.
		 * @param array $request    Search request.
		 * @param string $lang_code Lang code.
		 */
		$params = apply_filters( 'se_prepare_filters_from_request', $params, $request, $lang_code );
	}

	/**
	 * Process product_cat attribute
	 *
	 * @param array $params Request params.
	 * @param array $query Search query.
	 */
	public function restrictProductCat( &$params, $query ) {
		if ( ! empty( $query['product_cat'] ) ) {
			$cat_ids = array();
			$cats    = (array) $query['product_cat'];

			foreach ( $cats as $c ) {
				$category = get_term_by( 'slug', $c, 'product_cat' );

				if ( $category instanceof \WP_Term ) {
					$cat_ids[] = $category->term_id;

					while ( 0 != $category->parent ) {
						$category  = get_term_by( 'term_id', $category->parent, 'product_cat' );
						$cat_ids[] = $category->term_id;
					}
				}
			}

			if ( ! empty( $cat_ids ) ) {
				$params['restrictBy']['category_ids'] = implode( '|', $cat_ids );
			}
		}

		return true;
	}

	/**
	 * Preapare query limits
	 *
	 * @param array $query_vars Query variables.
	 *
	 * @return array
	 */
	private function getLimits( $query_vars ) {
		$max_results = ! empty( $query_vars['posts_per_page'] ) ? (int) $query_vars['posts_per_page'] : (int) get_option( 'posts_per_page' );
		$start_index = 0;
		$current_page = ! empty( $query_vars['paged'] ) ? (int) abs( $query_vars['paged'] ) : 1;
		$start_index = $current_page > 1 ? ( $current_page - 1 ) * $max_results : 0;

		/**
		 * Filters search limits
		 *
		 * @since 1.0.0
		 *
		 * @param array ($start_index, $max_results).
		 * @param array $query_var Search variables.
		 */
		return apply_filters( 'se_get_limits', array( $start_index, $max_results ), $query_vars );
	}

	/**
	 * Returns default sortings
	 *
	 * @return array
	 */
	private function getDefaultSortings() {
		$sort_by    = self::DEFAULT_SORT_BY;
		$sort_order = self::DEFAULT_SORT_ORDER;

		$wc_orderby = get_option( 'woocommerce_default_catalog_orderby' );
		list($wc_orderby) = explode( ' ', $wc_orderby );
		if ( ! empty( $wc_orderby ) ) {
			if ( preg_match( '/(.*)[-_](desc|asc)$/', $wc_orderby, $matches ) ) {
				$sort_by = $matches[1];
				$sort_order = $matches[2];

			} else {
				$sort_by = $wc_orderby;
			}
		}

		$ordering = array( $sort_by, $sort_order );

		/**
		 * Filters default sortings
		 *
		 * @since 1.0.0
		 *
		 * @param array $ordering array($sort_by, $sort_order)
		 */
		return apply_filters( 'se_get_default_sort_ordering', $ordering );
	}

	/**
	 * Send search request to Searchanise
	 *
	 * @param array  $params    Prepared params.
	 * @param string $lang_code Lang code.
	 *
	 * @return boolean
	 */
	private function sendSearchAndRequest( array $params, $lang_code ) {
		$this->setSearchResult();
		$this->last_query_hash = '';
		$api_key = Api::get_instance()->get_api_key( $lang_code );

		if ( empty( $api_key ) ) {
			return false;
		}

		$default_params = array(
			'items'  => 'true',
			'facets' => 'true',
			'output' => 'json',
		);

		$params = array_merge( $default_params, $params );

		if ( empty( $params['restrictBy'] ) ) {
			unset( $params['restrictBy'] );
		}

		if ( empty( $params['union'] ) ) {
			unset( $params['union'] );
		}

		if ( defined( 'SE_API' ) ) {
			$params['api'] = SE_API;
		}

		// Check if results cached.
		$cache_key = md5(
			wp_json_encode(
				array_merge(
					$params,
					array(
						'api_key'   => $api_key,
						'lang_code' => $lang_code,
					)
				)
			)
		);
		$last_cache_key = md5(
			wp_json_encode(
				array(
					'api_key'   => $api_key,
					'lang_code' => $lang_code,
				)
			)
		);

		$result = get_transient( self::CACHE_PREFIX . $cache_key );

		if ( empty( $result ) || $this->getIsUseRequestCache() != true ) {
			$query = http_build_query( $params );

			Logger::get_instance()->debug( SE_SERVICE_URL . '/search?api_key=' . $api_key . '&' . $query );

			if ( strlen( $query ) > SE_MAX_SEARCH_REQUEST_LENGTH ) {
				$result = wp_remote_post(
					SE_SERVICE_URL . '/search?api_key=' . $api_key,
					array(
						'timeout' => SE_REQUEST_TIMEOUT,
						'headers' => array(
							'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
						),
						'body' => $params,
					)
				);

				if ( ! is_wp_error( $result ) ) {
					$received = wp_remote_retrieve_body( $result );
				} else {
					$request_error = $result->get_error_message();
					/* translators: %s: request error */
					Logger::get_instance()->debug( sprintf( __( 'Error occurs during request %s' ), $request_error ) );
				}
			} else {
				$result = wp_remote_get(
					SE_SERVICE_URL . '/search',
					array(
						'timeout' => SE_REQUEST_TIMEOUT,
						'body'    => array_merge( $params, array( 'api_key' => $api_key ) ),
					)
				);

				if ( ! is_wp_error( $result ) ) {
					$received = wp_remote_retrieve_body( $result );
				} else {
					$request_error = $result->get_error_message();
					/* translators: %s: request error */
					Logger::get_instance()->debug( sprintf( __( 'Error occurs during request %s' ), $request_error ) );
				}
			}

			// Invalid data.
			if ( empty( $received ) ) {
				return false;
			}

			$result = json_decode( trim( $received ), true );

			Logger::get_instance()->debug( $result );

			// Bad json data.
			if ( is_null( $result ) ) {
				return false;
			}

			if ( isset( $result['error'] ) ) {
				switch ( $result['error'] ) {
					case self::ERROR_EMPTY_API_KEY:
					case self::ERROR_TO_BIG_START_INDEX:
					case self::ERROR_SEARCH_DATA_NOT_IMPORTED:
					case self::ERROR_FACET_ERROR_TOO_MANY_ATTRIBUTES:
						// Nothing.
						break;

					case self::ERROR_ENGINE_DISABLED:
						// TODO: Do not send search request in future.
						break;

					case self::ERROR_ENGINE_REMOVED:
						Api::get_instance()->set_export_status( Api::EXPORT_STATUS_NONE, $lang_code );
						break;

					case self::ERROR_INVALID_API_KEY:
						Api::get_instance()->delete_keys( $lang_code );
						if ( Api::get_instance()->signup( $lang_code, false ) == true ) {
							Api::get_instance()->queue_import( $lang_code, false );
						}
						break;

					case self::ERROR_NEED_RESYNC_YOUR_CATALOG:
						Api::get_instance()->queue_import( $lang_code, false );
						break;

					case self::ERROR_FULL_FEED_DISABLED:
						// TODO: setUseFullFeed(false);.
						break;
				}

				Logger::get_instance()->debug( $result['error'] );

				return false;
			}

			// Cache results.
			if ( $this->getIsUseRequestCache() == true ) {
				set_transient( self::CACHE_PREFIX . $cache_key, $result, self::CACHE_TIME );
			}
		} //endif empty($result).

		$result['facets'][0]['buckets'][0] = array(
			'value' => '0.0000,95.0000',
			'title' => '0.0000,95.0000',
			'from' => 0,
			'left' => 0,
			'right' => 95.00,
			'to' => 95.00,
			'count' => 18,
		);

		set_transient( self::CACHE_LAST_PREFIX . $last_cache_key, $result, self::CACHE_LAST_TIME );

		if ( empty( $result ) || ! is_array( $result ) || ! isset( $result['totalItems'] ) ) {
			return false;
		}

		$this->setSearchResult( $result );

		add_action(
			'woocommerce_no_products_found',
			function () {
				// Remove default action.
				remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );

				// New no product message.
				$message = __( 'No products were found matching your selection.', 'woocommerce' );
				$did_you_mean = $this->getDidYouMeanText();

				echo '<p class="woocommerce-info"><span>' . esc_html( $message ) . ' ' . wp_kses( $did_you_mean, array( 'a' => array( 'href' => array() ) ) ) . '</span></p>';
			},
			9
		);

		return true;
	}

	/**
	 * Return true if cache should be used
	 *
	 * @return boolean
	 */
	private function getIsUseRequestCache() {
		$val = self::CACHE_ENABLED;

		if ( isset( $_REQUEST[ self::CACHE_DEBUG_VAR_NAME ] ) && self::CACHE_DEBUG_KEY == $_REQUEST[ self::CACHE_DEBUG_VAR_NAME ] ) {
			$val = false;
		}

		/**
		 * Gets request cache
		 *
		 * @since 1.0.0
		 *
		 * @param string $val
		 */
		return (bool) apply_filters( 'se_get_is_use_request_cache', $val );
	}

	/**
	 * Check if soring by review is enabled
	 *
	 * @return boolean
	 */
	private function isReviewEnabled() {
		static $enabled = null;

		if ( null === $enabled ) {
			if ( 'yes' !== get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
				$enabled = false;

			} else {
				$count = get_comments(
					array(
						'count'      => true,
						'post_type'  => 'product',
						'meta_key'   => 'rating', // WPCS: slow query ok.
						'meta_value' => array( 1, 2, 3, 4, 5 ), // WPCS: slow query ok.
					)
				);
				$enabled = $count > 0;
			}
		}

		return $enabled;
	}

	/**
	 * Store search params
	 *
	 * @param array $params Prepared search params.
	 */
	private function setSearchParams( $params = array() ) {
		$this->search_params = $params;
	}

	/**
	 * Return stored search params
	 *
	 * @return array
	 */
	private function getSearchParams() {
		return $this->search_params;
	}

	/**
	 * Store Searchanise search result
	 *
	 * @param array $result Searchanise results.
	 */
	private function setSearchResult( array $result = array() ) {
		$this->search_result = $result;

		$this->setProductIds();
		$this->setAttributesCount();
	}

	/**
	 * Returns stored Searchanise results
	 *
	 * @return array
	 */
	public function getSearchResult() {
		return $this->search_result;
	}

	/**
	 * Check if Searchanise results available
	 *
	 * @return boolean
	 */
	public function checkSearchResults() {
		return ! empty( $this->search_result );
	}

	/**
	 * Set product ids list from Searchanise response
	 *
	 * @param array $product_ids Product ids.
	 */
	private function setProductIds( $product_ids = array() ) {
		$this->product_ids = $product_ids;
	}

	/**
	 * Return stored product ids
	 *
	 * @return array
	 */
	private function getProductIds() {
		if ( empty( $this->product_ids ) ) {
			$res = $this->getSearchResult();

			if ( ! empty( $res['items'] ) ) {
				$this->product_ids = array_map(
					function ( $v ) {
						return $v['product_id'];
					},
					$res['items']
				);
			}
		}

		return $this->product_ids;
	}

	/**
	 * Returns total products from Searchanise request
	 *
	 * @return int
	 */
	private function getTotalProducts() {
		$res = $this->getSearchResult();

		return ! empty( $res['totalItems'] ) ? (int) $res['totalItems'] : 0;
	}

	/**
	 * Returns suggestions list from Searchanise request
	 *
	 * @return array
	 */
	private function getSuggestions() {
		$res = $this->getSearchResult();

		return ! empty( $res['suggestions'] ) ? $res['suggestions'] : array();
	}

	/**
	 * Set attributes count from Searchanise request
	 *
	 * @param array $count Attribute counts.
	 */
	private function setAttributesCount( $count = array() ) {
		$this->attributes_count = $count;
	}

	/**
	 * Returns all attributes counts
	 *
	 * @return array
	 */
	private function getAttributesCount() {
		return $this->attributes_count;
	}

	/**
	 * Returns available sorting order for Searchanise request
	 *
	 * @return array
	 */
	private function getSortMapping() {
		/**
		 * Filters sorting mapping values
		 *
		 * @since 1.0.0
		 *
		 * @param array Mapping values
		 */
		return apply_filters( 'se_get_sort_mapping', self::ORDER_MAP );
	}

	/**
	 * Generate suggestion link
	 *
	 * @param string $sug       Suggestion.
	 * @param string $lang_code Lang code.
	 *
	 * @return string
	 */
	private function getSuggestionLink( $sug, $lang_code ) {
		return Api::get_instance()->get_frontend_url(
			$this->lang_code,
			array(
				's'         => $sug,
				'post_type' => 'product',
			)
		);
	}

	/**
	 * Calculate filters count for attribute
	 *
	 * @param string $filter Filter name.
	 *
	 * @return int|null
	 */
	private function getCountAttribute( $filter ) {
		if ( empty( $filter ) ) {
			return null;
		}

		if ( ! $this->checkAttributeCount( $filter ) ) {
			$vals = array();
			$res = $this->getSearchResult();

			if ( ! empty( $res['facets'] ) ) {
				foreach ( $res['facets'] as $facet ) {
					if ( $facet['attribute'] == $filter ) {
						if ( ! empty( $facet['buckets'] ) ) {
							foreach ( $facet['buckets'] as $bucket ) {
								if ( $bucket['count'] > 0 ) {
									$vals[ $bucket['value'] ] = $bucket['count'];
								}
							}
						}
					}
				}
			}

			$this->setAttributeCount( $vals, $filter );
		}

		return $this->getAttributeCount( $filter );
	}

	/**
	 * Checks if counts already stored for filter
	 *
	 * @param string $filter Filter name.
	 *
	 * @return boolean
	 */
	private function checkAttributeCount( $filter ) {
		return isset( $this->attributes_count[ $filter ] );
	}

	/**
	 * Set values count for filter
	 *
	 * @param int    $value  Filters count value.
	 * @param string $filter Attribute filter.
	 */
	private function setAttributeCount( $value, $filter ) {
		if ( empty( $this->attributes_count ) ) {
			$this->attributes_count = array();
		}

		$this->attributes_count[ $filter ] = $value;
	}

	/**
	 * Returns filters count for attribute
	 *
	 * @param string $filter Filter name.
	 *
	 * @return int|null
	 */
	private function getAttributeCount( $filter ) {
		if ( isset( $this->attributes_count[ $filter ] ) ) {
			return $this->attributes_count[ $filter ];
		}

		return null;
	}
}
