<?php
/**
 * Related Posts Snippet
 *
 * @package    Powerkit
 * @subpackage Modules
 */

/**
 * Related Posts Class
 */
class CNVS_Related_Posts_Snippet {

	/**
	 * Post ID
	 *
	 * @var int
	 */
	public $post_id = null;

	/**
	 * Options
	 *
	 * @var int
	 */
	public $args = array();

	/**
	 * Need Posts Count
	 *
	 * @var int
	 */
	public $need_posts_count = 0;

	/**
	 * Posts Offset
	 *
	 * @var int|bool
	 */
	public $founded_posts = false;

	/**
	 * Parent Terms
	 *
	 * @var array
	 */
	public $parent_terms = array();

	/**
	 * Posts List
	 *
	 * @var array
	 */
	private $posts_list = array();

	/**
	 * Exclude Posts
	 *
	 * @var array
	 */
	private $exclude_posts = array();

	/**
	 * Exclude Cats
	 *
	 * @var array
	 */
	private $exclude_cats = array();

	/**
	 * Constructor. Set up cacheable values and settings.
	 *
	 * @param array $args Related posts options.
	 */
	public function __construct( $args = array() ) {
		global $post;

		// Set Post ID.
		if ( is_object( $post ) ) {
			$this->args['post_id'] = intval( $post->ID );
		}

		$this->args = array_merge( array(
			'ids'           => null,
			'category'      => null,
			'tag'           => null,
			'time_frame'    => null,
			'orderby'       => 'date',
			'order'         => 'DESC',
			'count'         => 1,
			'offset'        => 0,
			'exclude_posts' => array(),
			'exclude_cats'  => array(),
			'output_type'   => 'term, parent_term', // Variables: all, posts, tags, term, parent_term, post_type.
			'post_id'       => $this->args['post_id'],
		), $args );

		// Check Post ID.
		if ( (int) $this->args['post_id'] <= 0 ) {
			return false;
		}

		// Set Options.
		$this->ids              = $this->args['ids'];
		$this->cats             = $this->args['category'];
		$this->tags             = $this->args['tag'];
		$this->time_frame       = $this->args['time_frame'];
		$this->orderby          = $this->args['orderby'];
		$this->order            = $this->args['order'];
		$this->need_posts_count = $this->args['count'] + $this->args['offset'];
		$this->offset           = $this->args['offset'];
		$this->post_terms       = $this->get_post_terms( $this->args['post_id'] );
		$this->post_type        = get_post_type( $this->args['post_id'] );

		// Exclude Options.
		$this->exclude_cats    = is_array( $this->args['exclude_cats'] ) ? array_map( 'intval', $this->args['exclude_cats'] ) : array();
		$this->exclude_posts   = is_array( $this->args['exclude_posts'] ) ? array_map( 'intval', $this->args['exclude_posts'] ) : array();
		$this->exclude_posts[] = (int) $this->args['post_id'];

		// Check Output Type.
		if ( is_string( $this->args['output_type'] ) ) {
			$output_type = explode( ',', $this->args['output_type'] );
		} elseif ( is_array( $this->args['output_type'] ) ) {
			$output_type = $this->args['output_type'];
		} else {
			$output_type = array();
		}

		// Set Output Type.
		if ( trim( $this->ids ) ) {
			$output_type = array( 'posts' );

		} elseif ( trim( $this->cats ) ) {

			$output_type = array( 'posts' );

		} elseif ( trim( $this->tags ) ) {

			$output_type = array( 'posts' );

		} elseif ( in_array( 'all', $output_type, true ) ) {

			$output_type = array( 'tags', 'term', 'parent_term', 'post_type' );
		}

		$this->args['output_type'] = array_map( 'trim', $output_type );
	}

	/**
	 * Get Posts
	 *
	 * @param array $args Query args.
	 */
	private function query_get_posts( $args ) {

		// Set Query Params.
		$args['no_found_rows']       = true;
		$args['ignore_sticky_posts'] = 1;
		$args['post__not_in']        = $this->exclude_posts;
		$args['category__not_in']    = $this->exclude_cats;

		$args = $this->query_filter_args( $args );

		// Result.
		$new_posts_list = new WP_Query( $args );

		if ( isset( $new_posts_list->posts ) && is_array( $new_posts_list->posts ) ) {
			$this->posts_list = array_merge( $this->posts_list, $new_posts_list->posts );

			// Exclude posts list.
			foreach ( $new_posts_list->posts as $obj_post ) {
				$this->exclude_posts[] = $obj_post->ID;

				// Needed count posts.
				$this->need_posts_count--;
			}
		}
	}

	/**
	 * Pre posts process.
	 */
	public function posts_process() {
		if ( count( $this->args['output_type'] ) <= 1 ) {
			return;
		}

		// Process.
		if ( $this->posts_list ) {
			$ids = array();
			foreach ( $this->posts_list as $post ) {
				$ids[] = $post->ID;
			}

			// Set new args.
			$args = array(
				'post__in' => $ids,
			);

			// Filter args.
			$args = $this->query_filter_args( $args );

			$the_query = new WP_Query( $args );

			// Rewrite posts.
			$this->posts_list = $the_query->posts;
		}
	}

	/**
	 * Set custom args.
	 *
	 * @param array $args The query args.
	 */
	public function query_filter_args( $args = array() ) {
		// Post offset.
		$args['offset'] = $this->offset;

		// Post order.
		$args['order']   = $this->order;
		$args['orderby'] = $this->orderby;

		$type_post_views = cnvs_post_views_enabled();

		// Post Views.
		if ( $type_post_views && ( 'views' === $this->orderby || 'post_views' === $this->orderby ) ) {
			$args['orderby'] = $type_post_views;
			// Don't hide posts without views.
			$args['views_query']['hide_empty'] = false;
		}

		// Time Frame.
		if ( $this->time_frame ) {
			$args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => $this->time_frame . ' ago',
				),
			);
		}

		return $args;
	}

	/**
	 * Get Posts by filters
	 */
	public function get_posts_by_filters() {

		$args = array();

		// Count.
		$args['posts_per_page'] = $this->args['count'];

		// Filter by posts.
		if ( $this->ids ) {
			$ids = explode( ',', $this->ids );

			$args['post__in'] = array_map( 'trim', $ids );
		}

		// Filter by categories.
		if ( $this->cats ) {
			$cats = str_replace( ' ', '', $this->cats );

			$args['category_name'] = $cats;
		}

		// Filter by tags.
		if ( $this->tags ) {
			$tags = str_replace( ' ', '', $this->tags );

			$args['tag'] = $tags;
		}

		// Result.
		$this->query_get_posts( $args );
	}

	/**
	 * Get Related By Tags
	 */
	public function get_output_type_tags() {
		if ( $this->need_posts_count <= 0 ) {
			return false;
		}

		// Get Tags.
		$tags = get_the_terms( $this->args['post_id'], 'post_tag' );

		if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {

			if ( 'video' === get_post_type( $this->args['post_id'] ) ) {
				$terms_array = array();
				$tax_data    = array();

				// Terms array.
				foreach ( $tags as $post_term ) {
					$terms_array[ $post_term->taxonomy ][] = $post_term->term_id;
				}

				// Tax data.
				foreach ( $terms_array as $p_tax => $p_terms ) {

					if ( is_array( $p_terms ) ) {
						$terms_array[ $p_tax ] = array_unique( $terms_array[ $p_tax ] );
					}

					$tax_data[] = array(
						'taxonomy' => $p_tax,
						'field'    => 'id',
						'terms'    => $terms_array[ $p_tax ],
					);
				}

				// Query.
				if ( ! empty( $tax_data ) ) {
					$args = array(
						'post__not_in'   => $this->exclude_posts,
						'posts_per_page' => $this->need_posts_count,
						'post_type'      => $this->post_type,
						'tax_query'      => array(
							'relation' => 'OR',
						),
					);

					$args['tax_query'] = array_merge( $args['tax_query'], $tax_data );

					// Result.
					$this->query_get_posts( $args );
				}
			} else {
				// Tags ID's.
				$tag_ids = array();
				foreach ( $tags as $individual_tag ) {
					$tag_ids[] = $individual_tag->term_id;
				}

				// Query.
				$args = array(
					'tag__in'        => $tag_ids,
					'post__not_in'   => $this->exclude_posts,
					'posts_per_page' => $this->need_posts_count,
					'post_type'      => $this->post_type,
				);

				// Result.
				$this->query_get_posts( $args );
			}
		}
	}

	/**
	 * Get Related By Current Term
	 */
	public function get_output_type_current_term() {
		if ( $this->need_posts_count <= 0 ) {
			return false;
		}

		// Post terms.
		if ( ! empty( $this->post_terms ) ) {
			$terms_array    = array();
			$tax_data       = array();
			$exclude_parent = array();

			// Terms array.
			foreach ( $this->post_terms as $post_term ) {
				if ( $post_term->parent ) {
					$exclude_parent[ $post_term->taxonomy ][] = $post_term->parent;
				}
			}

			foreach ( $this->post_terms as $post_term ) {
				$exclude = isset( $exclude_parent[ $post_term->taxonomy ] ) ? $exclude_parent[ $post_term->taxonomy ] : array();

				if ( ! in_array( $post_term->term_id, (array) $exclude, true ) ) {
					$terms_array[ $post_term->taxonomy ][] = $post_term->term_id;
				}
			}

			// Tax data.
			foreach ( $terms_array as $p_tax => $p_terms ) {

				if ( is_array( $p_terms ) ) {
					$terms_array[ $p_tax ] = array_unique( $terms_array[ $p_tax ] );
				}

				$tax_data[] = array(
					'taxonomy' => $p_tax,
					'field'    => 'id',
					'terms'    => $terms_array[ $p_tax ],
				);
			}

			// Query.
			if ( ! empty( $tax_data ) ) {
				$args = array(
					'post__not_in'   => $this->exclude_posts,
					'posts_per_page' => $this->need_posts_count,
					'post_type'      => $this->post_type,
					'tax_query'      => array(
						'relation' => 'OR',
					),
				);

				$args['tax_query'] = array_merge( $args['tax_query'], $tax_data );

				// Result.
				$this->query_get_posts( $args );
			}
		}
	}


	/**
	 * Get Related By Parent Term
	 */
	public function get_output_type_parent_term() {

		if ( $this->need_posts_count <= 0 ) {
			return false;
		}

		// Parent terms.
		if ( ! empty( $this->post_terms ) ) {
			$terms_array = array();
			$tax_data    = array();

			// Terms array.
			foreach ( $this->post_terms as $post_term ) {
				if ( 0 !== $post_term->parent ) {
					$terms_array[ $post_term->taxonomy ][] = $post_term->parent;
				}
			}

			// Tax data.
			foreach ( $terms_array as $p_tax => $p_terms ) {
				if ( is_array( $p_terms ) ) {
					$terms_array[ $p_tax ] = array_unique( $terms_array[ $p_tax ] );
				}

				$tax_data[] = array(
					'taxonomy' => $p_tax,
					'field'    => 'id',
					'terms'    => $terms_array[ $p_tax ],
				);
			}

			// Query.
			if ( ! empty( $tax_data ) ) {
				$args = array(
					'post__not_in'   => $this->exclude_posts,
					'posts_per_page' => $this->need_posts_count,
					'post_type'      => $this->post_type,
					'tax_query'      => array(
						'relation' => 'OR',
					),
				);

				$args['tax_query'] = array_merge( $args['tax_query'], $tax_data );

				// Result.
				$this->query_get_posts( $args );
			}
		}
	}

	/**
	 * Get Related By Post Type
	 */
	public function get_output_type_post_type() {
		if ( $this->need_posts_count <= 0 ) {
			return false;
		}

		// Posts by Post type.
		$args = array(
			'post__not_in'   => $this->exclude_posts,
			'posts_per_page' => $this->need_posts_count,
			'post_type'      => $this->post_type,
		);

		// Result.
		$this->query_get_posts( $args );
	}

	/**
	 * Get Post terms
	 *
	 * @param  array $post_id       Post ID.
	 * @param  array $exclude_tags  Exclude post tags taxonomy. Optional.
	 * @return array Post Terms.
	 */
	public function get_post_terms( $post_id, $exclude_tags = true ) {

		// Get Taxonomies.
		$taxonomies = get_taxonomies( array(
			'public' => true,
		), 'names' );

		if ( ! is_array( $taxonomies ) ) {
			return false;
		}

		// Exclude post tags.
		if ( $exclude_tags ) {
			if ( isset( $taxonomies['post_tag'] ) ) {
				unset( $taxonomies['post_tag'] );
			}
		}

		// Add Post Terms.
		$post_terms = array();
		foreach ( $taxonomies as $tax_name => $tax_data ) {
			$tax_terms = get_the_terms( $post_id, $tax_name );

			if ( is_array( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
				$post_terms = array_merge( $post_terms, $tax_terms );
			}
		}

		return $post_terms;
	}

	/**
	 * Output posts
	 *
	 * @return array Related posts
	 */
	public function output() {

		// Check Post ID.
		if ( intval( $this->args['post_id'] ) <= 0 ) {
			return array();
		}

		// Get Related Posts.
		foreach ( $this->args['output_type'] as $related ) {
			switch ( $related ) {
				case 'posts':
					$this->get_posts_by_filters();
					break;
				case 'tags':
					$this->get_output_type_tags();
					break;
				case 'term':
					$this->get_output_type_current_term();
					break;
				case 'parent_term':
					$this->get_output_type_parent_term();
					break;
				case 'post_type':
					$this->get_output_type_post_type();
					break;
			}
		}

		// Posts process.
		$this->posts_process();

		// Return posts.
		return $this->posts_list;
	}
}

/**
 * Get Related Posts
 *
 * @param array $args Related posts settings.
 */
function cnvs_get_related_posts( $args = array() ) {

	// Create Related Posts Object.
	$related = new CNVS_Related_Posts_Snippet( $args );

	return $related->output();
}
