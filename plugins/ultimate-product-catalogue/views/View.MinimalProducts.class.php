<?php

/**
 * Class to display a catalog on the front end.
 *
 * @since 5.0.0
 */
class ewdupcpViewMinimalProducts extends ewdupcpView {

	// Array holding all view items to be displayed
	public $items = array();

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 5.0.0
	 */
	public function render() {
		global $ewd_upcp_controller;

		$this->set_items();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();
		
		$this->add_custom_styling();

		$template = $this->find_template( 'minimal-products' );
		
		if ( $template ) {
			include( $template );
		}
		$output = ob_get_clean();

		return apply_filters( 'ewd_upcp_minimal_products_output', $output, $this );
	}

	/**
	 * Loop through requested items, printing them out
	 *
	 * @since 5.0.0
	 */
	public function print_items() {
		
		foreach ( $this->items as $count => $item ) {

			if ( $count >= $this->product_count ) { break; }

			echo $item->render_view( 'minimal' );
		}
	}

	/**
	 * Sets the items in this minimal products listing
	 *
	 * @since 5.0.0
	 */
	public function set_items() {

		$product_ids = array();
		$category_ids = array();

		// overwrite the default product_count attribute if specific product_ids are provided
		$this->product_count = ! empty( $this->product_ids ) ? max( $this->product_count, sizeof( explode( ',', $this->product_ids ) ) ) : $this->product_count;

		if ( ! empty( $this->catalogue_id ) ) {

			if ( get_post_type( $this->catalogue_id ) != EWD_UPCP_CATALOG_POST_TYPE ) {

				$args = array(
					'post_type'		=> EWD_UPCP_CATALOG_POST_TYPE,
					'meta_query' 	=> array(
						array(
							'key'		=> 'old_catalog_id',
							'value'		=> $this->catalogue_id
						)
					)
				);

				$posts = get_posts( $args );

				$this->catalogue_id = ! empty( $posts ) ? reset( $posts )->ID : null;
			}

			$catalog_items = get_post_meta( intval( $this->catalogue_id ), 'items', true );

			foreach ( $catalog_items as $catalog_item ) {

				if ( $catalog_item->type == 'product' ) { $product_ids[] = $catalog_item->id; }
				if ( $catalog_item->type == 'category' ) { $category_ids[] = $catalog_item->id; }
			}
		}

		if ( ! empty( $this->product_ids ) ) {

			$product_ids = ! empty( $product_ids ) ?  array_intersect( $product_ids, explode( ',', $this->product_ids ) ) : explode( ',', $this->product_ids );
		}

		if ( ! empty( $this->category_id ) ) {

			$category_ids = ! empty( $category_ids ) ?  array_intersect( $category_ids, explode( ',', $this->category_id ) ) : explode( ',', $this->category_id );
		}

		if ( ! empty( $this->subcategory_id ) ) {

			$category_ids = ! empty( $category_ids ) ? array_intersect( $category_ids, explode( ',', $this->subcategory_id ) ) : explode( ',', $this->subcategory_id );
		}

		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> EWD_UPCP_PRODUCT_POST_TYPE
		);

		if ( ! empty( $product_ids ) ) { 

			$args['post__in'] = $product_ids; 
		}

		if ( ! empty( $category_ids ) ) { 

			$tax_query = array(
				array(
					'taxonomy'	=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
					'field'		=> 'term_id',
					'terms'		=> $category_ids
				)
			);

			$args['tax_query'] = $tax_query; 
		}

		$posts = get_posts( $args );

		$posts = $this->sort_product_posts( $posts );

		$product_posts = array_slice( $posts, 0, $this->product_count );

		foreach ( $product_posts as $product_post ) {

			$product = new ewdupcpProduct();

			$product->load_post( $product_post );

			$args = array(
				'product'		=> $product,
				'catalogue_url'	=> $this->catalogue_url
			);

			$this->items[] = new ewdupcpViewCatalogProduct( $args );
		}
	}

	/**
	 * Takes an array of product posts and sorts using the appropriate comparison function
	 * @since 5.2.0
	 */
	public function sort_product_posts( $product_posts ) {

		if ( $this->catalogue_search == 'title' ) {

			usort( $product_posts, array( $this, 'sort_product_by_title' ) );
		}
		elseif ( $this->catalogue_search == 'recent' ) {

			usort( $product_posts, array( $this, 'sort_product_by_recent' ) );
		}
		elseif ( $this->catalogue_search == 'popular' ) {

			usort( $product_posts, array( $this, 'sort_product_by_popular' ) );
		}
		elseif ( $this->catalogue_search == 'rand' ) {

			shuffle( $product_posts );
		}

		return $product_posts;
	}

	/**
	 * Compares two product posts baed on their titles
	 * @since 5.2.0
	 */
	public function sort_product_by_title( $a, $b ) {

		return strnatcmp( $a->post_title, $b->post_title );
	}

	/**
	 * Compares two product posts baed on their published dates
	 * @since 5.2.0
	 */
	public function sort_product_by_recent( $a, $b ) {

		return strtotime( $b->post_date) - strtotime( $a->post_date );
	}

	/**
	 * Compares two product posts baed on their view counts
	 * @since 5.2.0
	 */
	public function sort_product_by_popular( $a, $b ) {

		$a_view_count = get_post_meta( $a->ID, 'views', true ); 
		$b_view_count = get_post_meta( $b->ID, 'views', true );

		return $b_view_count - $a_view_count;
	}

	/**
	 * Get the initial submit product css classes
	 * @since 5.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_upcp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-upcp-minimal-products-div',
				'ewd-upcp-minimal-products-' . $this->products_wide
			)
		);

		return apply_filters( 'ewd_upcp_minimal_product_classes', $classes, $this );
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 5.0.0
	 */
	public function enqueue_assets() {
		
		wp_enqueue_style( 'ewd-upcp-css' );

		wp_enqueue_script( 'ewd-upcp-js' );

	}
}
