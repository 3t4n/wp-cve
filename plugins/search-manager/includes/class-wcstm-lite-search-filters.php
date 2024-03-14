<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * WCSTM Lite Search Filters
 */
class WCSTM_Search_Lite_Filters {

	private static $instance;

	protected $settings;

	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	public function set_options( $settings ) {

		$this->settings = $settings;

	}

	public function get_option( $section, $option ) {

		if ( false === is_array( $this->settings[ $section ] ) ) {
			return false;
		}

		foreach ( $this->settings[ $section ] as $key => $field ) {
			if ( $key == $option ) {
				return $field;
			}
		}

	}

	public function is_filter( $section, $option, $query ) {

		if ( $query->is_main_query()
		     && false === is_admin()
		     && $this->get_option( $section, $option )
		     && is_search()
		     && $query->get( 's' )
		) {
			return true;
		}

		return false;

	}

	public function is_wc_search( $query ) {

		return isset( $query->query['post_type'] ) && 'product' == $query->query['post_type'];

	}

	public function search_in_post_comments( $where, $query ) {

		global $wp, $wpdb;

		if ( $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'wordpress', 'in_comments', $query ) ) {

			$post_ids = array();

			$comments = new WP_Comment_Query( array(
				'search'    => $query->get( 's' ),
				'post_type' => 'post'
			) );
			foreach ( $comments->comments as $comment ) {
				$post_ids[] = $comment->comment_post_ID;
			}
			if ( $post_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $post_ids ) . "))";
			}
		}

		return $where;

	}

	public function search_in_product_comments( $where, $query ) {

		global $wp, $wpdb;

		if ( false === $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'woocommerce', 'in_comments', $query ) ) {

			$post_ids = array();

			$comments = new WP_Comment_Query( array(
					'search'    => $query->get( 's' ),
					'post_type' => 'product'
				)
			);
			foreach ( $comments->comments as $comment ) {
				$post_ids[] = $comment->comment_post_ID;
			}
			if ( $post_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $post_ids ) . "))";
			}
		}

		return $where;

	}

	public function search_in_product_sku( $where, $query ) {

		global $wpdb;

		if ( false === $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'woocommerce', 'in_sku', $query ) ) {

			$args = array(
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'post_type'      => 'product',
				'meta_query'     => array(
					array(
						'key'     => '_sku',
						'value'   => $query->get( 's' ),
						'compare' => 'LIKE'
					)
				),
			);

			$product_ids = get_posts( $args );

			if ( $product_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . "))";
			}

		}

		return $where;

	}

	public function search_in_short_desc( $where, $query ) {

		global $wpdb;

		if ( false === $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'woocommerce', 'in_short_desc', $query ) ) {
			return $where .= " OR ({$wpdb->posts}.post_excerpt LIKE '%{$query->get('s')}%' AND {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type='product')";
		}

		return $where;

	}

	public function search_in_excerpt( $where, $query ) {

		global $wpdb;

		if ( $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'wordpress', 'in_excerpts', $query ) ) {
			return $where .= " OR ({$wpdb->posts}.post_excerpt LIKE '%{$query->get('s')}%' AND {$wpdb->posts}.post_status = 'publish')";
		}

		return $where;

	}

	public function search_in_product_category( $where, $query ) {

		global $wpdb;

		if ( false === $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'woocommerce', 'in_categories', $query ) ) {
			$product_cat_ids = array();
			$product_cats    = get_terms( 'product_cat', array( 'name__like' => $query->get( 's' ) ) );
			foreach ( $product_cats as $cat ) {
				$product_cat_ids[] = $cat->term_id;
			}

			$args = array(
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'post_type'      => 'product',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $product_cat_ids,
					),
				)
			);

			$product_ids = get_posts( $args );

			if ( $product_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . "))";
			}
		}

		return $where;

	}

	public function search_in_post_category( $where, $query ) {

		global $wpdb;

		if ( $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'wordpress', 'in_categories', $query ) ) {
			$product_cat_ids = array();
			$product_cats    = get_terms( 'category', array( 'name__like' => $query->get( 's' ) ) );

			foreach ( $product_cats as $cat ) {
				$product_cat_ids[] = $cat->term_id;
			}

			$args = array(
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'post_type'      => 'post',
				'tax_query'      => array(
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $product_cat_ids,
					),
				)
			);

			$product_ids = get_posts( $args );

			if ( $product_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . "))";
			}
		}

		return $where;

	}

	public function search_in_product_tags( $where, $query ) {

		global $wpdb;

		if ( false === $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'woocommerce', 'in_tags', $query ) ) {
			$product_tax_ids = array();
			$product_tax     = get_terms( 'product_tag', array( 'name__like' => $query->get( 's' ) ) );
			foreach ( $product_tax as $tax ) {
				$product_tax_ids[] = $tax->term_id;
			}

			$args = array(
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'post_type'      => 'product',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_tag',
						'field'    => 'id',
						'terms'    => $product_tax_ids,
					),
				)
			);

			$product_ids = get_posts( $args );

			if ( $product_ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . "))";
			}
		}

		return $where;

	}

	public function search_in_post_tags( $where, $query ) {

		global $wpdb;

		if ( $this->is_wc_search( $query ) ) {
			return $where;
		}

		if ( $this->is_filter( 'wordpress', 'in_tags', $query ) ) {
			$tax_ids  = array();
			$post_tax = get_terms( 'post_tag', array( 'name__like' => $query->get( 's' ) ) );
			foreach ( $post_tax as $tax ) {
				$tax_ids[] = $tax->term_id;
			}

			$args = array(
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'post_type'      => 'post',
				'tax_query'      => array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'id',
						'terms'    => $tax_ids,
					),
				)
			);

			$ids = get_posts( $args );

			if ( $ids ) {
				$where .= " OR ({$wpdb->posts}.ID IN (" . implode( ',', $ids ) . "))";
			}
		}

		return $where;

	}

}