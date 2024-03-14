<?php

/**
 * Class to display the main product filters.
 *
 * @since 3.0.0
 */
class ewduwcfViewFiltering extends ewduwcfView {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();
		$this->add_custom_styling();

		$template = $this->find_template( 'filtering' );
		if ( $template ) {
			include( $template );
		}
		
		$output = ob_get_clean();

		return apply_filters( 'ewd_uwcf_review_output', $output, $this );
	}

	/**
	 * Print the shortcode attributes that were passed in
	 * @since 3.0.0
	 */
	public function print_shortcode_args() {
		
		$template = $this->find_template( 'filtering-shortcode-args' );
		if ( $template ) {
			include( $template );
		}

	}

	/**
	 * Print the 'Reset All' filters button, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_reset_all_button() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'reset-all-button' ) ) { return; }

		$template = $this->find_template( 'filtering-reset-all' );
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Text Search' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_text_search() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'text-search' ) ) { return; }

		$template = $this->find_template( 'filtering-text-search' );
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Ratings' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_price_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'price-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( 'price-filtering-display' ) == 'slider' ) { $template = $this->find_template( 'filtering-price-slider' ); }
		else { $template = $this->find_template( 'filtering-price-text' ); }
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Ratings' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_ratings_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'ratings-filtering' ) ) { return; }

		$template = $this->find_template( 'filtering-ratings' );
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'In-Stock' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_instock_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'instock-filtering' ) ) { return; }

		$template = $this->find_template( 'filtering-instock' );
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'On-Sale' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_onsale_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'onsale-filtering' ) ) { return; }

		$template = $this->find_template( 'filtering-onsale' );
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the Color' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_color_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ) == 'list' ) { $template = $this->find_template( 'filtering-list' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ) == 'tiles' ) { $template = $this->find_template( 'filtering-tiles' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ) == 'swatch' ) { $template = $this->find_template( 'filtering-swatch' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ) == 'checklist' ) { $template = $this->find_template( 'filtering-checklist' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ) == 'dropdown' ) { $template = $this->find_template( 'filtering-dropdown' ); }
		
		$args = array(
			'hide_empty' 	=> $ewd_uwcf_controller->settings->get_setting( 'category-filtering-hide-empty' ),
			'taxonomy' 		=> 'product_color',
			'orderby' 		=> 'meta_value_num',
			'meta_query' 	=> array(
				'relation'		=> 'OR',
				array(
					'key' 			=>'EWD_UWCF_Term_Order',
					'compare' 		=> 'EXISTS',
				),
				array(
					'key' 			=>'EWD_UWCF_Term_Order',
					'compare' 		=> 'NOT EXISTS',
				)
			)
		);

		$terms = get_terms( $args );

		$this->filtering_args = array(
			'type'					=> 'color',
			'terms' 				=> $terms,
			'selected_values'		=> isset( $_GET['product_color'] ) ? explode( ',', sanitize_text_field( $_GET['product_color'] ) ) : array(),
			'disable_text' 			=> $ewd_uwcf_controller->settings->get_setting( 'color-filtering-disable-text' ),
			'show_product_count'	=> $ewd_uwcf_controller->settings->get_setting( 'color-filtering-show-product-count' ),
			'disable_color'			=> $ewd_uwcf_controller->settings->get_setting( 'color-filtering-disable-color' ),
			'color_shape'			=> $ewd_uwcf_controller->settings->get_setting( 'styling-color-filter-shape' )
		);

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Size' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_size_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'size-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( 'size-filtering-display' ) == 'list' ) { $template = $this->find_template( 'filtering-list' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'size-filtering-display' ) == 'checklist' ) { $template = $this->find_template( 'filtering-checklist' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'size-filtering-display' ) == 'dropdown' ) { $template = $this->find_template( 'filtering-dropdown' ); }
		
		$args = array(
			'hide_empty' 	=> $ewd_uwcf_controller->settings->get_setting( 'size-filtering-hide-empty' ),
			'taxonomy' 		=> 'product_size',
			'orderby' 		=> 'meta_value_num',
			'meta_query' 	=> array(
				'relation'		=> 'OR',
				array(
					'key' 			=>'EWD_UWCF_Term_Order',
					'compare' 		=> 'EXISTS',
				),
				array(
					'key' 			=>'EWD_UWCF_Term_Order',
					'compare' 		=> 'NOT EXISTS',
				)
			)
		);

		$terms = get_terms( $args );

		$this->filtering_args = array(
			'type'					=> 'size',
			'terms' 				=> $terms,
			'selected_values'		=> isset( $_GET['product_size'] ) ? explode( ',', sanitize_text_field( $_GET['product_size'] ) ) : array(),
			'disable_text' 			=> $ewd_uwcf_controller->settings->get_setting( 'size-filtering-disable-text' ),
			'show_product_count'	=> $ewd_uwcf_controller->settings->get_setting( 'size-filtering-show-product-count' ),
		);

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Category' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_category_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'category-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( 'category-filtering-display' ) == 'list' ) { $template = $this->find_template( 'filtering-list' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'category-filtering-display' ) == 'checklist' ) { $template = $this->find_template( 'filtering-checklist' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'category-filtering-display' ) == 'dropdown' ) { $template = $this->find_template( 'filtering-dropdown' ); }
		
		$args = array(
			'hide_empty' 	=> $ewd_uwcf_controller->settings->get_setting( 'category-filtering-hide-empty' ),
			'taxonomy' 		=> 'product_cat'
		);

		$terms = get_terms( $args );

		$this->filtering_args = array(
			'type'					=> 'category',
			'terms' 				=> $terms,
			'selected_values'		=> isset( $_GET['product_cat'] ) ? explode( ',', sanitize_text_field( $_GET['product_cat'] ) ) : array(),
			'disable_text' 			=> $ewd_uwcf_controller->settings->get_setting( 'category-filtering-disable-text' ),
			'show_product_count'	=> $ewd_uwcf_controller->settings->get_setting( 'category-filtering-show-product-count' ),
		);

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Tags' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_tag_filtering() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'tag-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-display' ) == 'list' ) { $template = $this->find_template( 'filtering-list' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-display' ) == 'checklist' ) { $template = $this->find_template( 'filtering-checklist' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-display' ) == 'dropdown' ) { $template = $this->find_template( 'filtering-dropdown' ); }
		
		$args = array(
			'hide_empty' 	=> $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-hide-empty' ),
			'taxonomy' 		=> 'product_tag'
		);

		$terms = get_terms( $args );

		$this->filtering_args = array(
			'type'					=> 'tag',
			'terms' 				=> $terms,
			'selected_values'		=> isset( $_GET['product_tag'] ) ? explode( ',', sanitize_text_field( $_GET['product_tag'] ) ) : array(),
			'disable_text' 			=> $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-disable-text' ),
			'show_product_count'	=> $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-show-product-count' ),
		);

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the 'Attributes' filtering area, if enabled
	 * @since 3.0.0
	 */
	public function maybe_print_attribute_filtering() {
		global $ewd_uwcf_controller;

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			$this->maybe_print_filtering_for_attribute( $attribute_taxonomy );
		}
	}

	/**
	 * Print the filtering area for a single attribute
	 * @since 3.0.0
	 */
	public function maybe_print_filtering_for_attribute( $attribute_taxonomy ) {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-filtering' ) ) { return; }

		if ( $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-display' ) == 'list' ) { $template = $this->find_template( 'filtering-list' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-display' ) == 'checklist' ) { $template = $this->find_template( 'filtering-checklist' ); }
		elseif ( $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-display' ) == 'dropdown' ) { $template = $this->find_template( 'filtering-dropdown' ); }
		
		$args = array(
			'hide_empty' 	=> $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-hide-empty' ),
			'taxonomy' 		=> 'pa_' . $attribute_taxonomy->attribute_name
		);

		$terms = get_terms( $args );

		$this->filtering_args = array(
			'type'					=> 'attribute',
			'attribute_name'		=> $attribute_taxonomy->attribute_name,
			'terms' 				=> $terms,
			'selected_values'		=> isset( $_GET[ $attribute_taxonomy->attribute_name] ) ? explode( ',', sanitize_text_field( $_GET[ $attribute_taxonomy->attribute_name] ) ) : array(),
			'disable_text' 			=> $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-disable-text' ),
			'show_product_count'	=> $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-show-product-count' ),
		);

		if ( $template ) {
			include( $template );
		}
	}

	public function get_color_style( $term ) {

		$color = get_term_meta( $term->term_id, 'EWD_UWCF_Color', true );

		$style = strpos($color, 'http' ) === false ? 'style="background: ' . esc_attr( $color ) . ';"'  : 'style="background:url(\'' . esc_attr( $color ) . '\'); background-size: cover;"';

		return $style;
	}

	public function get_max_wc_price() {
		global $wpdb;

		return $wpdb->get_var("SELECT MAX(CAST(meta_value as DECIMAL(12,2))) FROM $wpdb->postmeta INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->posts.post_type = 'product' AND $wpdb->postmeta.meta_key = '_price' AND $wpdb->posts.post_status = 'publish'");
	}

	/**
	 * Appends $key => $value filtering to the current URL,
	 * overwriting the current $key value if it exists
	 * @since 3.0.0
	 */
	public function get_filtering_url( $key, $value ) {
		global $wp;

		$query_args = array(
			$key 		=> $value,
			'ewd_uwcf' 	=> 1
		);

		return home_url( add_query_arg( $query_args, $wp->request ) );
	}

	/**
	 * Get the initial reviews css classes
	 * @since 3.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_uwcf_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-uwcf-review-div',
				'ewd-uwcf-review-format-' . $ewd_uwcf_controller->settings->get_setting( 'review-format' ),
			)
		);

		if ( $ewd_uwcf_controller->settings->get_setting( 'read-more-ajax' ) ) { $classes[] = 'ewd-uwcf-ajax-read-more'; }

		return apply_filters( 'ewd_uwcf_review_classes', $classes, $this );
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 3.0.0
	 */
	public function enqueue_assets() {
		global $ewd_uwcf_controller;

		$args = array(
			'post_type' => 'product', 
			'posts_per_page' => -1
		);

		$products = get_posts( $args );

		$filtering_data = array(
			'products' => $products
		);

		$ewd_uwcf_controller->add_front_end_php_data( 'ewd-uwcf-js', 'ewd_uwcf_php_data', $filtering_data );

		wp_enqueue_script( 'ewd-uwcf-js' );
	
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'ewd-uwcf-css' );
	}
}
