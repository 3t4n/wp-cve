<?php

/**
 * Class to display show product filtering for individual shop items.
 *
 * @since 3.0.0
 */
class ewduwcfViewProductFilters extends ewduwcfView {

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

		$this->maybe_print_color_filters();
		$this->maybe_print_size_filters();
		$this->maybe_print_categories_filters();
		$this->maybe_print_tags_filters();
		$this->maybe_print_attribute_filters();
		
		$output = ob_get_clean();

		return apply_filters( 'ewd_uwcf_review_output', $output, $this );
	}

	/**
	 * Print color filtering thumbnails
	 * @since 3.0.0
	 */
	public function maybe_print_color_filters() {
		global $ewd_uwcf_controller;

		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) or empty( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display-thumbnail-colors' ) ) ) { return; }

		if ( empty( wp_get_post_terms( get_the_ID(), 'product_color' ) ) ) { return; }

		$template = $this->find_template( 'product-filters-colors' );
		if ( $template ) {
			include( $template );
		}

	}

	/**
	 * Get all color taxonomy terms
	 * @since 3.0.0
	 */
	public function get_color_terms() {

		$terms = wp_get_post_terms( get_the_ID(), 'product_color' );

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * Returns the shape class for the color filtering thumbnails
	 * @since 3.0.0
	 */
	public function get_color_shape_class() {
		global $ewd_uwcf_controller;

		return $ewd_uwcf_controller->settings->get_setting( 'styling-color-filter-shape' ) == 'circle' ? 'ewd-uwcf-rcorners' : '';
	}

	/**
	 * Print size filtering thumbnails
	 * @since 3.0.0
	 */
	public function maybe_print_size_filters() {
		global $ewd_uwcf_controller;

		if ( empty( $ewd_uwcf_controller->settings->get_setting( 'size-filtering' ) ) or empty( $ewd_uwcf_controller->settings->get_setting( 'size-filtering-display-thumbnail-sizes' ) ) ) { return; }

		if ( empty( wp_get_post_terms( get_the_ID(), 'product_size' ) ) ) { return; }

		$template = $this->find_template( 'product-filters-sizes' );
		if ( $template ) {
			include( $template );
		}

	}

	/**
	 * Get all size taxonomy terms
	 * @since 3.0.0
	 */
	public function get_size_terms() {

		$terms = wp_get_post_terms( get_the_ID(), 'product_size' );

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * Print category filtering thumbnails
	 * @since 3.0.0
	 */
	public function maybe_print_categories_filters() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'category-filtering-display-thumbnail-cats' ) ) { return; }

		if ( empty( wp_get_post_terms( get_the_ID(), 'product_cat' ) ) ) { return; }

		$template = $this->find_template( 'product-filters-categories' );
		if ( $template ) {
			include( $template );
		}

	}

	/**
	 * Get all category taxonomy terms
	 * @since 3.0.0
	 */
	public function get_category_terms() {

		$terms = wp_get_post_terms( get_the_ID(), 'product_cat' );

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * Print tag filtering thumbnails
	 * @since 3.0.0
	 */
	public function maybe_print_tags_filters() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'tag-filtering-display-thumbnail-tags' ) ) { return; }

		if ( empty( wp_get_post_terms( get_the_ID(), 'product_tag' ) ) ) { return; }

		$template = $this->find_template( 'product-filters-tags' );
		if ( $template ) {
			include( $template );
		}

	}

	/**
	 * Get all tag taxonomy terms
	 * @since 3.0.0
	 */
	public function get_tag_terms() {

		$terms = wp_get_post_terms( get_the_ID(), 'product_tag' );

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * Print tag filtering thumbnails
	 * @since 3.0.0
	 */
	public function maybe_print_attribute_filters() {
		global $ewd_uwcf_controller;

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

    		if ( $attribute_taxonomy->attribute_name == 'ewd_uwcf_colors' or $attribute_taxonomy->attribute_name == 'ewd_uwcf_sizes' ) { continue; }

			if ( ! $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-display-thumbnail-terms' ) ) { continue; }

			if ( empty(  wp_get_post_terms( get_the_ID(), 'pa_' . $attribute_taxonomy->attribute_name ) ) ) { continue; }

			$this->current_attribute = $attribute_taxonomy;

			$template = $this->find_template( 'product-filters-attributes' );
			if ( $template ) {
				include( $template );
			}
		}

	}

	/**
	 * Get all tag taxonomy terms
	 * @since 3.0.0
	 */
	public function get_attribute_terms() {

		$terms = wp_get_post_terms( get_the_ID(), 'pa_' . $this->current_attribute->attribute_name );

		return is_wp_error( $terms ) ? array() : $terms;
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

		wp_enqueue_script( 'ewd-uwcf-js' );
	
	    wp_enqueue_style( 'jquery-ui' );
	    wp_enqueue_style( 'ewd-uwcf-css' );
	}
}
