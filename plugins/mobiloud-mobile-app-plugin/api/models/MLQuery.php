<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 */
class MLQuery {

	/**
	 * @var array
	 */
	public $excluded_cats_ids;
	/**
	 * @var array
	 */
	public $included_cats_ids;
	/**
	 * @var array
	 */
	public $included_tax_ids;
	/**
	 * @var array
	 */
	public $term_arr;
	/**
	 * @var bool
	 */
	public $user_term;
	/**
	 * @var bool
	 */
	public $category;
	/**
	 * @var bool
	 */
	public $taxonomy;
	/**
	 * @var bool
	 */
	public $permalink_is_taxonomy;
	/**
	 * @var int
	 */
	public $user_offset;
	/**
	 * @var bool
	 */
	public $user_category;
	/**
	 * @var bool
	 */
	public $user_category_id;
	/**
	 * @var bool
	 */
	public $user_category_filter;
	/**
	 * @var bool
	 */
	public $user_search;
	/**
	 * @var bool
	 */
	public $user_permalink;
	/**
	 * @var int
	 */
	public $user_limit;
	/**
	 * @var array Post or/and Get variables from current request.
	 */
	public $ml_request;

	/**
	 * @var array
	 */
	public $query_array;

	/**
	 * @var array
	 */
	public $post_types;
	/**
	 * @var mixed
	 */
	public $user_post_count;
	/**
	 * @var int
	 */
	public $image_format;
	/**
	 * @var int
	 */
	public $real_offset;
	/**
	 * @var array List of categories ID.
	 */
	public $list_cat;
	/**
	 * @var integer Post count.
	 */
	public $post_count;

	/**
	 *
	 */
	public function set_request_method( $method = 'both' ) {
		if ( $method === 'get' ) {
			$this->ml_request = $_GET; // phpcs:ignore WordPress.Security.NonceVerification
		}
		if ( $method === 'post' ) {
			$this->ml_request = $_POST; // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( $method === 'both' ) {
			$this->ml_request = $_POST; // phpcs:ignore WordPress.Security.NonceVerification
			if ( count( $_GET ) > count( $this->ml_request ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->ml_request = $_GET; // phpcs:ignore WordPress.Security.NonceVerification
			}
		}
	}

	public function is_custom_request() {
		return ( ! empty( $this->excluded_cats_ids ) || ! empty( $this->user_search ) || ! empty( $this->user_category_filter ) );
		// return ( ! empty( $this->excluded_cats_ids ) || ! empty( $this->user_search ) || ! empty( $this->user_category_filter ) || $this->user_taxonomy );
	}


	public function __construct() {
		$this->post_types            = array();
		$this->excluded_cats_ids     = array();
		$this->included_cats_ids     = array();
		$this->included_tax_ids      = array();
		$this->term_arr              = array();
		$this->user_term             = false;
		$this->category              = false;
		$this->user_post_count       = false;
		$this->taxonomy              = false;
		$this->permalink_is_taxonomy = false;

		$this->set_request_method();

		$this->user_offset          = ( isset( $this->ml_request['offset'] ) ? $this->ml_request['offset'] : 0 );
		$this->user_category        = ( isset( $this->ml_request['category'] ) ? $this->ml_request['category'] : false );
		$this->user_category_id     = ( isset( $this->ml_request['category_id'] ) ? $this->ml_request['category_id'] : false );
		$this->user_category_filter = ( isset( $this->ml_request['categories'] ) ? $this->ml_request['categories'] : false );
		$this->user_search          = ( isset( $this->ml_request['search'] ) ? $this->ml_request['search'] : false );
		$this->user_permalink       = ( isset( $this->ml_request['permalink'] ) ? $this->ml_request['permalink'] : false );
		$this->user_limit           = ( isset( $this->ml_request['limit'] ) ? $this->ml_request['limit'] : Mobiloud::get_option( 'ml_articles_per_request', 15 ) );
		$this->image_format         = ( isset( $this->ml_request['image_format'] ) ? $this->ml_request['image_format'] : 1 );
		$this->list_cat             = ( isset( $this->ml_request['list_cat'] ) ? explode( ',', sanitize_text_field( $this->ml_request['list_cat'] ) ) : null );
		if ( is_null( $this->list_cat ) && ! empty( $this->user_category_filter ) ) {
			$array_filter = array();
			$array_filter_items = explode( ',', $this->user_category_filter );
			foreach ( $array_filter_items as $afi ) {
				$tcat = get_category_by_slug( $afi );
				if ( ! $tcat ) {
					$tcat = get_category( $afi );
				}
				if ( $tcat ) {
					array_push( $array_filter, $tcat->term_id );
				}
			}
			if ( count( $array_filter ) ) {
				$this->list_cat = $array_filter;
			}
		}
		if ( is_null( $this->list_cat ) && isset( $this->ml_request['taxonomy'] ) &&  'category' === $this->ml_request['taxonomy'] && isset( $this->ml_request['term_id'] ) ) {
			$array_filter = array_map( 'intval', explode( ',', sanitize_text_field( $this->ml_request['term_id'] ) ) );
			$this->list_cat = $array_filter;
		}

		if ( $this->user_limit > 30 ) {
			$this->user_limit = 30;
		}
	}

	/**
	 * @param $user_limit
	 * @param $excluded_cats_ids
	 * @param $includedPostTypes
	 * @param $real_offset
	 * @param $user_search
	 * @param $term_arr
	 * @param $user_category_filter
	 *
	 * @return array
	 */
	public function build_query_array() {
		$user_limit           = $this->user_limit;
		$excluded_cats_ids    = $this->excluded_cats_ids;
		$post_types           = $this->post_types;
		$real_offset          = $this->real_offset;
		$user_search          = $this->user_search;
		$term_arr             = $this->term_arr;
		$user_category_filter = $this->user_category_filter;
		$included_cats_ids    = $this->included_cats_ids;
		$included_tax_ids     = $this->included_tax_ids;

		if ( $user_category_filter ) {
			$arrayFilter       = array();
			$excluded_cats_ids = array_merge( $excluded_cats_ids, $included_cats_ids );

			$arrayFilterItems = explode( ',', $user_category_filter );
			foreach ( $arrayFilterItems as $afi ) {
				$tcat = get_category_by_slug( $afi );
				if ( ! $tcat ) {
					$tcat = get_category( $afi );
				}
				if ( $tcat ) {
					array_push( $arrayFilter, $tcat->term_id );
				}
			}

			$included_cats_ids = $arrayFilter;
			$excluded_cats_ids = array_diff( $excluded_cats_ids, $included_cats_ids );
		}

		$tax_query = array( 'relation' => 'OR' );
		if ( ! ( empty( $included_cats_ids ) && ! empty( $included_tax_ids ) ) ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => $included_cats_ids,
			);
			$tax_query[] = array(
				'taxonomy'         => 'category',
				'field'            => 'term_id',
				'terms'            => $excluded_cats_ids,
				'operator'         => 'NOT IN',
				'include_children' => false,
			);
		}
		if ( ! empty( $included_tax_ids ) ) {
			foreach ( $included_tax_ids as $tax => $terms ) {
				$tax_query[] = array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $terms,
				);

			}
		}

		$query_array = array(
			'posts_per_page' => $user_limit,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'offset'         => $real_offset,
			's'              => $user_search,
		);

		if ( $user_category_filter ) {
			if ( isset( $this->ml_request['order'] ) ) {
				$query_array['order'] = $this->ml_request['order'];
			}
			if ( isset( $this->ml_request['orderby'] ) ) {
				$query_array['orderby'] = 'name' === $this->ml_request['orderby'] ? 'title' : $this->ml_request['orderby'];
			}
		}
		if ( empty( $excluded_cats_ids ) && empty( $included_tax_ids ) ) {
			$tax_query = array();
		} else {
			$query_array['tax_query'] = $tax_query; // WPCS: slow query ok.
		}

		if ( count( $term_arr ) && $term_arr['term'] ) {
			unset( $query_array['post_type'] );
		}

		if ( isset( $this->ml_request['categories'] ) ) {
		} elseif ( count( $term_arr ) && $term_arr['term'] ) {
			// given permalink is taxonomy, replace current query
			$query_array['tax_query'] = array( // WPCS: slow query ok.
				array(
					'taxonomy' => $term_arr['term']->taxonomy,
					'field'    => 'id',
					'terms'    => $term_arr['term']->term_id,
				),
			);
		}

		if ( ! empty( $user_search ) ) {
			$query_array = array(
				's'              => $user_search,
				'posts_per_page' => $user_limit,
				'offset'         => $real_offset,
				'post_status'    => 'publish',
			);
			// filter ml_restrict_search_results($value, $user_search): true = restrict search results to same post types, categories and taxonomies as set for Home Screen
			if ( apply_filters( 'ml_restrict_search_results', Mobiloud::get_option( 'ml_home_article_list_enabled', true ) && Mobiloud::get_option( 'ml_restrict_search_results' ), $user_search ) ) {
				if ( ! empty( $tax_query ) ) {
					$query_array['tax_query'] = $tax_query; // WPCS: slow query ok.
				}
				$query_array['post_type'] = $post_types;
			}
		}

		$this->query_array = $query_array;
	}
}
