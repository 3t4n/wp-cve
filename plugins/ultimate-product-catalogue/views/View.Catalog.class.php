<?php

/**
 * Class to display a catalog on the front end.
 *
 * @since 5.0.0
 */
class ewdupcpViewCatalog extends ewdupcpView {

	// Saves the price of the minimum priced object in the catalog
	public $sidebar_min_price = 1000000;

	// Saves the price of the maximum priced object in the catalog
	public $sidebar_max_price = 0;

	// Stores categories available based on products in the catalogue
	public $sidebar_categories = array();

	// Stores sub-categories available based on products in the catalogue
	public $sidebar_subcategories = array();

	// Stores tags available based on products in the catalogue
	public $sidebar_tags = array();

	// Stores custom fields available based on products in the catalogue
	public $sidebar_custom_fields = array();

	// Stores what string, if any, should be used to filter products
	public $filtering_text = '';

	// Stores which categories, if any, should be used to filter products
	public $filtering_categories = array();

	// Stores which sub-categories, if any, should be used to filter products
	public $filtering_subcategories = array();

	// Stores which tags, if any, should be used to filter products
	public $filtering_tags = array();

	// Stores which custom fields, if any, should be used to filter products
	public $filtering_custom_fields = array();

	// Stores which products should be used for the product comparison feature
	public $comparison_products = array();

	// Stores which products and categories should be displayed in the catalog
	public $items = array();

	// Stores the count of how many individual products are in the catalog
	public $product_count = 0;

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 5.0.0
	 */
	public function render() {
		global $ewd_upcp_controller;

		if ( empty( $this->id ) ) { return; }

		$this->set_variables();

		$this->set_items();

		$this->set_pagination_data();

		$this->set_sidebar_ordering();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();
		
		$this->add_custom_styling();

		if ( ! empty( $this->selected_product_id ) ) { $template = $this->find_template( 'catalog-single-product' ); }
		elseif ( ! empty( $this->comparison_products ) ) { $template = $this->find_template( 'product-comparison' ); }
		elseif ( ! empty( $_POST['ewd_upcp_submit_cart'] ) ) { $template = $this->find_template( 'catalog-inquiry-form' ); }
		elseif ( ! empty( $this->overview_mode ) ) { $template = $this->find_template( 'catalog-overview' ); }
		else { $template = $this->find_template( 'catalog' ); }
		
		if ( $template ) {
			include( $template );
		}
		$output = ob_get_clean();

		return apply_filters( 'ewd_upcp_catalog_output', $output, $this );
	}

	/**
	 * Renders a single product page, based on selected page
	 *
	 * @since 5.0.0
	 */
	public function print_single_product() {

		if ( empty( $this->selected_product_id ) ) { return; }
		
		echo $this->items->render();
	}

	public function maybe_print_cart_form() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-cart' ) ) and ( empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-checkout' ) ) or empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-sync' ) ) ) ) { return; }

		$template = $this->find_template( 'catalog-cart' );

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print HTML needed for the product comparison submission form, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_product_comparison_form() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-comparison' ) ) ) { return; }

		$template = $this->find_template( 'catalog-product-comparison' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print HTML needed for lightbox mode to work, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_lightbox() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'lightbox-mode' ) ) ) { return; }

		$template = $this->find_template( 'catalog-lightbox' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print attributes used in the shortcode, so they can be updated and
	 * accessed during AJAX requests
	 *
	 * @since 5.0.0
	 */
	public function print_shortcode_attributes() {
		
		$template = $this->find_template( 'catalog-shortcode-attributes' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print information (name and/or description) for this catalog
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_catalog_information() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'show-catalog-information' ) == 'none' ) { return; }

		$template = $this->find_template( 'catalog-information' );
			
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the view toggles switches and accompanying color bar
	 *
	 * @since 5.0.0
	 */
	public function print_catalog_header() {
		
		$template = $this->find_template( 'catalog-header-bar' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print pagination controls, depending on location selected and product count
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_pagination( $location ) {
		global $ewd_upcp_controller;

		if ( $this->max_pages <= 1 ) { return; }

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'infinite-scroll' ) ) ) { return; }

		if ( $location == 'top' and $ewd_upcp_controller->settings->get_setting( 'pagination-location' ) == 'bottom' ) { return; }

		if ( $location == 'bottom' and $ewd_upcp_controller->settings->get_setting( 'pagination-location' ) == 'top' ) { return; }
		
		$template = $this->find_template( 'catalog-pagination' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Check if thumbnail view is excluded, print it if it is not
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_thumbnail_display() {

		if ( in_array( 'thumbnail', $this->excluded_views ) ) { return; }

		$template = $this->find_template( 'catalog-view-thumbnail' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Check if list view is excluded, print it if it is not
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_list_display() {

		if ( in_array( 'list', $this->excluded_views ) ) { return; }

		$template = $this->find_template( 'catalog-view-list' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Check if detail view is excluded, print it if it is not
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_detail_display() {

		if ( in_array( 'detail', $this->excluded_views ) ) { return; }

		$template = $this->find_template( 'catalog-view-detail' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Loops through the items included in this catalog, printing them out in the selected display
	 *
	 * @since 5.0.0
	 */
	public function print_view( $view ) {

		$product_count = 0;

		foreach ( $this->items as $item ) {

			if ( $item->type == 'category' ) { 

				$this->category_id = $item->id;

				foreach ( $item->products as $product ) { 
					
					if ( $product_count >= ( $this->products_per_page * $this->current_page ) ) { 

						if ( ! empty( $this->category_displaying ) ) { $this->print_category_footer(); }

						return; 
					}
					
					$product_count++;

					if ( $product_count <= ( $this->products_per_page * ( $this->current_page - 1 ) ) ) { continue; }

					if ( empty( $this->category_displaying ) ) { $this->print_category_header(); }
					
					echo $product->render_view( $view );
				}

				if ( ! empty( $this->category_displaying ) ) { $this->print_category_footer(); }
			}
			else {
				
				if ( $product_count >= ( $this->products_per_page * $this->current_page ) ) { return; }
				
				$product_count++;
				
				if ( $product_count <= ( $this->products_per_page * ( $this->current_page - 1 ) ) ) { continue; }
				
				echo $item->product->render_view( $view ); 
			}
		}
	}

	/**
	 * Print the header area for a category being displayed in the catalog
	 *
	 * @since 5.0.0
	 */
	public function print_category_header() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'styling-category-heading-style' ) == 'none' ) { return; }

		$this->category_displaying = true;

		$template = $this->find_template( 'catalog-category-header' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the image for a category being displayed in the catalog, if necessary
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_category_heading_image() {
		global $ewd_upcp_controller;

		if ( ! in_array( 'main', $ewd_upcp_controller->settings->get_setting( 'display-category-image' ) ) ) { return; } 

		if ( empty( get_term_meta( $this->category_id, 'image', true ) ) ) { return; } 

		$template = $this->find_template( 'catalog-category-header-image' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the description for a category being displayed in the catalog, if necessary
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_category_heading_description() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'show-category-descriptions' ) ) ) { return; } 

		$template = $this->find_template( 'catalog-category-header-description' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the footer for a category being displayed in the catalog
	 *
	 * @since 5.0.0
	 */
	public function print_category_footer() {

		if ( empty( $this->category_displaying ) ) { return; }

		$this->category_displaying = false;

		$template = $this->find_template( 'catalog-category-footer' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product comparison template
	 *
	 * @since 5.0.0
	 */
	public function print_product_comparison() {

		$template = $this->find_template( 'product-comparison' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Loop through products selected for comparison and print them out
	 *
	 * @since 5.0.0
	 */
	public function print_product_comparison_products() {

		$template = $this->find_template( 'product-comparison-product' );

		foreach ( $this->items as $item ) { 

			$this->comparison_product = $item;

			if ( $template ) {
				include( $template );
			}
		}
	}

	/**
	 * Print a sidebar for the catalog, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sidebar() {

		if ( empty( $this->sidebar ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a toggle for the sidebar, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sidebar_toggle() {
		global $ewd_upcp_controller;

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'disable-toggle-sidebar-on-mobile' ) ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-toggle' );
		
		if ( $template ) {
			include( $template );
		}
	}

	public function maybe_print_sidebar_clear_all() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'clear-all-filtering' ) ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-clear-all' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Loops through all of the sidebar items and prints them, if enabled
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_items() {
		global $ewd_upcp_controller;

		foreach ( json_decode( $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-items-order' ) ) as $sidebar_item => $label ) {

			if ( $sidebar_item == 'sort' ) { $this->maybe_print_sidebar_sort(); }
			if ( $sidebar_item == 'search' ) { $this->maybe_print_sidebar_search(); }
			if ( $sidebar_item == 'price_filter' ) { $this->print_sidebar_price_filter(); }
			if ( $sidebar_item == 'categories' ) { $this->print_sidebar_category_filters(); }
			if ( $sidebar_item == 'subcategories' ) { $this->print_sidebar_subcategory_filters(); }
			if ( $sidebar_item == 'tags' ) { $this->maybe_print_sidebar_tag_filters(); }
			if ( $sidebar_item == 'custom_fields' ) { $this->print_sidebar_custom_field_filters(); }
		}
	}

	/**
	 * Print a sorting dropdown, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sidebar_sort() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-sort' ) ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-sort' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a text search box, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sidebar_search() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-search' ) ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-search' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a price filtering slider, if enabled
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_price_filter() {
		global $ewd_upcp_controller;

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'disable-price-filter' ) ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-price-filter' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print sidebar category filtering, if enabled
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_category_filters() {
		global $ewd_upcp_controller;

		if ( empty( $this->sidebar_categories ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-categories' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print sidebar sub-category filtering, if enabled
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_subcategory_filters() {
		global $ewd_upcp_controller;

		if ( empty( $this->sidebar_subcategories ) ) { return; }

		if ( $ewd_upcp_controller->settings->get_setting( 'sidebar-layout' ) == 'hierarchical' and $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-categories-control-type' ) != 'dropdown' ) { return; }

		$template = $this->find_template( 'catalog-sidebar-subcategories' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print sidebar tag filtering, if enabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sidebar_tag_filters() {
		global $ewd_upcp_controller;

		if ( empty( $this->sidebar_tags ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-tags' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print sidebar custom field filtering, if enabled
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_custom_field_filters() {
		global $ewd_upcp_controller;

		if ( empty( $this->sidebar_custom_fields ) ) { return; }

		$template = $this->find_template( 'catalog-sidebar-custom-fields' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Loop through the sidebar categories and print them
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_categories() {
		global $ewd_upcp_controller;

		$control_type = $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-categories-control-type' );

		if ( $control_type == 'dropdown' ) {

			echo '<select name="ewd-upcp-catalog-sidebar-categories-dropdown">';
			echo '<option value="all">' . __( 'All', 'ultimate-product-catalogue' ) . '</option>';
		}

		foreach ( $this->sidebar_categories as $category ) {

			$this->taxonomy_type = 'category';
			$this->taxonomy_term = $category;

			if ( $control_type == 'radio' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-radio' ); }
			elseif ( $control_type == 'checkbox' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-checkbox' ); }
			elseif ( $control_type == 'dropdown' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-dropdown' );}

			if ( $template ) {
				include( $template );
			}

			if ( $ewd_upcp_controller->settings->get_setting( 'sidebar-layout' ) == 'hierarchical' and $control_type != 'dropdown' ) { 

				$this->print_sidebar_hierarchical_subcategories();
			}
		}

		if ( $control_type == 'dropdown' ) {

			echo '</select>';
		}
	}

	/**
	 * Print a set of subcategories below their parent category
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_hierarchical_subcategories() {

		$template = $this->find_template( 'catalog-sidebar-hierarchical-subcategories' );

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Loop through the sidebar sub-categories and print them
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_subcategories( $parent_id = 0 ) {
		global $ewd_upcp_controller;

		$this->taxonomy_type = 'subcategory';

		$control_type = $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-subcategories-control-type' );

		if ( $control_type == 'dropdown' ) {

			echo '<select name="ewd-upcp-catalog-sidebar-subcategories-dropdown">';
			echo '<option value="all">' . __( 'All', 'ultimate-product-catalogue' ) . '</option>';
		}

		foreach ( $this->sidebar_subcategories as $subcategory ) {

			if ( ! empty( $parent_id ) and $subcategory->parent != $parent_id ) { continue; }

			$this->taxonomy_term = $subcategory;

			if ( $control_type == 'radio' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-radio' ); }
			elseif ( $control_type == 'checkbox' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-checkbox' ); }
			elseif ( $control_type == 'dropdown' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-dropdown' ); }

			if ( $template ) {
				include( $template );
			}
		}

		if ( $control_type == 'dropdown' ) {

			echo '</select>';
		}
	}

	/**
	 * Loop through the sidebar tags and print them
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_tags() {
		global $ewd_upcp_controller;

		$this->taxonomy_type = 'tag';

		$control_type = $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-tags-control-type' );

		if ( $control_type == 'dropdown' ) {

			echo '<select name="ewd-upcp-catalog-sidebar-tags-dropdown">';
			echo '<option value="all">' . __( 'All', 'ultimate-product-catalogue' ) . '</option>';
		}

		foreach ( $this->sidebar_tags as $tag ) {

			$this->taxonomy_term = $tag;

			if ( $control_type == 'radio' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-radio' ); }
			elseif ( $control_type == 'checkbox' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-checkbox' ); }
			elseif ( $control_type == 'dropdown' ) { $template = $this->find_template( 'catalog-sidebar-taxonomy-dropdown' ); }

			if ( $template ) {
				include( $template );
			}
		}

		if ( $control_type == 'dropdown' ) {

			echo '</select>';
		}
	}

	/**
	 * Loop through the sidebar custom fields and print them
	 *
	 * @since 5.0.0
	 */
	public function print_sidebar_custom_fields() {
		global $ewd_upcp_controller;

		$this->taxonomy_type = 'custom_field';

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( empty( $custom_field->searchable ) ) { continue; }

			if ( empty( $this->sidebar_custom_fields[ $custom_field->id ] ) ) { continue; }

			$this->custom_field = $custom_field;

			ksort( $this->sidebar_custom_fields[ $this->custom_field->id ] );

			if ( $custom_field->filter_control_type == 'radio' ) { $template = $this->find_template( 'catalog-sidebar-custom-field-radio' ); }
			elseif ( $custom_field->filter_control_type == 'slider' ) { $template = $this->find_template( 'catalog-sidebar-custom-field-slider' ); }
			elseif ( $custom_field->filter_control_type == 'dropdown' ) { $template = $this->find_template( 'catalog-sidebar-custom-field-dropdown' ); }
			else { $template = $this->find_template( 'catalog-sidebar-custom-field-checkbox' ); }

			if ( $template ) {
				include( $template );
			}
		}
	}

	/**
	 * Returns the image associated with the current category being displayed
	 *
	 * @since 5.0.0
	 */
	public function get_current_category_image_src() {
		
		return get_term_meta( $this->category_id, 'image', true );
	}
	
	/**
	 * Returns true if the current taxonomy (from sidebar functions) is selected, false otherwise
	 *
	 * @since 5.0.0
	 */
	public function is_taxonomy_selected() {
		
		if ( $this->taxonomy_type == 'category' ) { return in_array( $this->taxonomy_term->term_id, $this->filtering_categories ); }
		if ( $this->taxonomy_type == 'subcategory' ) { return in_array( $this->taxonomy_term->term_id, $this->filtering_subcategories ); }
		if ( $this->taxonomy_type == 'tag' ) { return in_array( $this->taxonomy_term->term_id, $this->filtering_tags ); }

		return false;
	}

	/**
	 * Returns true if the current taxonomy (from sidebar functions) is collapsible and has children
	 *
	 * @since 5.0.0
	 */
	public function taxonomy_has_collapsible_children() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'styling-sidebar-title-collapse' ) ) ) { return; }

		if ( $this->taxonomy_type != 'category' ) { return; }

		return count( get_term_children( $this->taxonomy_term->term_id, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY ) );
	}
	
	/**
	 * Returns true if the specified field value (from sidebar functions) is selected, false otherwise
	 *
	 * @since 5.0.0
	 */
	public function is_custom_field_value_selected( $field_value ) {

		if ( empty( $this->filtering_custom_fields[ $this->custom_field->id ] ) ) { return false; }

		return in_array( $field_value, $this->filtering_custom_fields[ $this->custom_field->id ] );
	}

	/**
	 * Prints the current taxonomy image, if one exists and if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_taxonomy_image() {
		global $ewd_upcp_controller;
		
		if ( $this->taxonomy_type == 'category' and ! in_array( 'sidebar', $ewd_upcp_controller->settings->get_setting( 'display-category-image' ) ) ) { return; } 

		if ( $this->taxonomy_type == 'subcategory' and empty( $ewd_upcp_controller->settings->get_setting( 'display-subcategory-image' ) ) ) { return; } 

		if ( empty( get_term_meta( $this->taxonomy_term->term_id, 'image', true ) ) ) { return; } 

		$template = $this->find_template( 'catalog-sidebar-taxonomy-image' );

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Prints the current taxonomy description, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_taxonomy_description() {
		global $ewd_upcp_controller;
		
		if ( $this->taxonomy_type != 'category' ) { return; } 

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'show-category-descriptions' ) ) ) { return; } 
		
		$template = $this->find_template( 'catalog-sidebar-taxonomy-description' );

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns the image associated with the current taxonomy term
	 *
	 * @since 5.0.0
	 */
	public function get_taxonomy_image() {
		
		return get_term_meta( $this->taxonomy_term->term_id, 'image', true );
	}

	/**
	 * Prints out the currency symbol, if location matches the selected location
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_currency_symbol( $location ) {
		global $ewd_upcp_controller;

		if ( $location != $ewd_upcp_controller->settings->get_setting( 'currency-symbol-location' ) ) { return; }

		echo esc_html( $ewd_upcp_controller->settings->get_setting( 'currency-symbol' ) );
	}

	/**
	 * Returns the different views available
	 *
	 * @since 5.0.0
	 */
	public function get_catalog_views() {

		$views = array( 'thumbnail', 'list', 'detail' );

		return array_diff( $views, $this->excluded_views );
	}

	/**
	 * Returns the items to be displayed in the current overview mode
	 *
	 * @since 5.0.0
	 */
	public function get_overview_items() {

		$overview_items = array();

		$property_name = 'sidebar_' . $this->overview_mode;

		foreach ( $this->$property_name as $item ) {

			$overview_items[] = (object) array(
				'permalink'	=> add_query_arg( $this->overview_mode, $item->term_id, $this->ajax_url ),
				'image'		=> get_term_meta( $item->term_id, 'image', true ),
				'title'		=> $item->name
			);
		}

		return $overview_items;
	}

	/**
	 * Returns the fields that should be displayed when comparing products
	 *
	 * @since 5.0.0
	 */
	public function get_product_comparison_custom_fields() {
		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		foreach ( $custom_fields as $key => $custom_field ) {

			if ( empty( $custom_field->comparison_display ) ) { unset( $custom_fields[ $key ] ); }
		}

		return $custom_fields;
	}

	/**
	 * Returns a product inquiry form for the catalog inquiry cart
	 *
	 * @since 5.0.0
	 */
	public function get_inquiry_form() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-plugin' ) == 'cf7' ) { return $this->get_contact_form_7_inquiry_form(); }
		else { return $this->get_wp_forms_inquiry_form(); }
	}

	/**
	 * Returns the Contact Form 7 form to be used for the catalog inquiry cart 
	 *
	 * @since 5.0.0
	 */
	public function get_contact_form_7_inquiry_form() {
		global $ewd_upcp_controller;

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) { return; }

		$contact_form = get_page_by_path( 'upcp-product-inquiry-form', OBJECT, 'wpcf7_contact_form' );

		if ( ! $contact_form ) { return; }

		return $this->replace_inquiry_form_fields( do_shortcode( '[contact-form-7 id="' . $contact_form->ID . '" title="' . $contact_form->post_title . '"]' ) );
	}

	/**
	 * Returns the WP Forms form to be used for the catalog inquiry cart 
	 *
	 * @since 5.0.0
	 */
	public function get_wp_forms_inquiry_form() {
		global $ewd_upcp_controller;

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'wpforms/wpforms.php' ) and ! is_plugin_active( 'wpforms-lite/wpforms.php' ) ) { return ''; }

		$contact_form = get_page_by_path( 'upcp-wp-forms-product-inquiry-form', OBJECT, 'wpforms' );

		if ( ! $contact_form ) { return; }

		return $this->replace_inquiry_form_fields( do_shortcode( '[wpforms id="' . $contact_form->ID . '"]' ) );
	}

	/**
	 * Converts product inquiry form tags into their corresponding values
	 *
	 * @since 5.0.0
	 */
	public function replace_inquiry_form_fields( $text ) {

		$search = array( 
			'%PRODUCT_NAME%', 
			'%PRODUCT_ID%'
		);
		
		$replace = array( 
			! empty( $this->cart_products ) ? esc_attr( implode( ',', array_map( 'get_the_title', $this->cart_products ) ) ) : __( 'No products selected', 'ultimate-product-catalogue' ),
			! empty( $this->cart_products ) ? esc_attr( implode( ',', $this->cart_products ) ) : '',
		);

		return str_replace( $search, $replace, $text );
	}

	/**
	 * Returns the correct action for the product cart form
	 *
	 * @since 5.0.0
	 */
	public function get_cart_action_url() {
		global $ewd_upcp_controller;

		$woocommerce_page_id = $ewd_upcp_controller->settings->get_setting( 'woocommerce-cart-page' ) == 'cart' ? get_option( 'woocommerce_cart_page_id' ) : get_option('woocommerce_checkout_page_id' );

		return ( ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-checkout' ) ) and ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-sync' ) ) ) ? get_permalink( $woocommerce_page_id ) : get_permalink();
	}

	/**
	 * Returns the correct label for the product cart
	 *
	 * @since 5.0.0
	 */
	public function get_cart_submit_label() {
		global $ewd_upcp_controller;

		return ( empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-checkout' ) ) or empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-sync' ) ) ) ? $this->get_label( 'label-send-inquiry' ) : $this->get_label( 'label-checkout' );
	}

	/**
	 * Determine which items to load into the catalog
	 *
	 * @since 5.0.0
	 */
	public function set_items() {

		if ( ! empty( $this->selected_product_id ) ) { $this->set_single_product(); }
		elseif ( ! empty( $this->comparison_products ) ) { $this->set_comparison_products(); }
		else { $this->set_standard_catalog_items(); }
	}

	/**
	 * Load the view for a single selected product
	 *
	 * @since 5.0.0
	 */
	public function set_single_product() {

		$product = new ewdupcpProduct();

		$product->load_post( $this->selected_product_id );

		$args = array(
			'product'	=> $product
		);

		$this->items = new ewdupcpViewSingleProduct( $args );
	}

	/**
	 *  Sets the items in the catalog based on which products are being compared
	 *
	 * @since 5.0.0
	 */
	public function set_comparison_products() {

		$args = array(
			'post_type'			=> EWD_UPCP_PRODUCT_POST_TYPE,
			'posts_per_page'	=> -1,
			'post__in' 			=> $this->comparison_products,
		);

		$product_posts = get_posts( $args );

		foreach ( $product_posts as $product_post ) {

			$product = new ewdupcpProduct();

			$product->load_post( $product_post );

			$args = array(
				'product'	=> $product
			);

			$this->items[] = new ewdupcpViewCatalogProduct( $args );
		}
	}

	/**
	 * Sets the items in the catalog based on the admin selections(products, categories, etc.)
	 *
	 * @since 5.0.0
	 */
	public function set_standard_catalog_items() {
		
		$catalog_items = is_array( get_post_meta( $this->catalog->ID, 'items', true ) ) ? get_post_meta( $this->catalog->ID, 'items', true ) : array();

		$product_ids = array();
		$category_ids = array();

		foreach ( $catalog_items as $catalog_item ) {

			if ( $catalog_item->type == 'product' ) { $product_ids[] = $catalog_item->id; }
			if ( $catalog_item->type == 'category' ) { $category_ids[] = $catalog_item->id; }
		}

		$args = array(
			'post_type'			=> EWD_UPCP_PRODUCT_POST_TYPE,
			'posts_per_page'	=> -1,
		);

		if ( ! empty( $product_ids ) ) { 

			$post_args = array_merge( $args, array( 'post__in' => $product_ids ) ); 

			$products = get_posts( $post_args );
		}
		else {

			$products = array();
		}

		if ( ! empty( $category_ids ) ) { 

			$tax_query = array(
				array(
					'taxonomy'	=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
					'field'		=> 'term_id',
					'terms'		=> $category_ids
				)
			);

			$post_args = array_merge( $args, array( 'tax_query' => $tax_query ) ); 

			$category_products = get_posts( $post_args );
		}
		else {

			$category_products = array();
		}

		$products = array_merge( $products, $category_products );

		$products = is_array( $products ) ? $products : array();

		$products = $this->filter_products( $products );

		$this->set_product_data( $products );

		// verify there are items for overview mode, turn off otherwise
		if ( ! empty( $this->overview_mode ) ) {

			$property_name = 'sidebar_' . $this->overview_mode;

			if ( empty( $this->$property_name ) ) { $this->overview_mode = false; }
		}

		if ( ! empty( $this->orderby ) ) {

			$this->set_sorted_items( $products );

			return;
		}

		foreach ( $catalog_items as $key => $catalog_item ) {

			if ( $catalog_item->type == 'product' ) { 

				 if ( ! empty( $products[ $catalog_item->id ] ) ) { 

				 	$catalog_item->product = $products[ $catalog_item->id ];

				 	$this->product_count++;
				 }
				 else { 

				 	unset( $catalog_items[ $key ] );
				 } 
			}
			
			if ( $catalog_item->type == 'category' ) { 

				$catalog_item->term = get_term( $catalog_item->id, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );

				$catalog_item->products = array();

				foreach ( $products as $product ) {
					
					if ( ! in_array( $catalog_item->term->term_id, $product->product->category_ids ) ) { continue; }

					$catalog_item->products[] = $product;  

					$this->product_count++;
				}

				if ( empty( $catalog_item->products ) ) {

					unset( $catalog_items[ $key ] );

					continue;
				}

				usort( $catalog_item->products, array( $this, 'sort_products_by_order' ) );
			}
		}
		
		$this->items = $catalog_items;
	}

	/**
	 * Filters a set of product posts, returning product objects that pass
	 * filtering based on shortcode args and $_REQUEST args
	 * @since 5.0.0
	 */
	public function filter_products( $product_posts ) {
		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		$control_types = array();

		foreach ( $custom_fields as $custom_field ) {

			$control_types[ $custom_field->id ] = $custom_field->filter_control_type;
		}

		$product_objects = array();

		foreach ( $product_posts as $product_post ) {

			$product = new ewdupcpProduct();

			$product->load_post( $product_post );

			if ( empty( $product->display ) ) { continue; }

			if ( ! empty( $this->filtering_min_price ) and $product->filtering_price < $this->filtering_min_price ) { continue; }

			if ( ! empty( $this->filtering_max_price ) and $product->filtering_price > $this->filtering_max_price ) { continue; }

			if ( ! empty( $this->filtering_categories ) and empty( array_intersect( $product->category_ids, $this->filtering_categories ) ) ) { continue; }

			if ( ! empty( $this->filtering_subcategories ) and empty( array_intersect( $product->subcategory_ids, $this->filtering_subcategories ) ) ) { continue; }

			if ( ! empty( $this->filtering_tags ) ) {

				if ( $ewd_upcp_controller->settings->get_setting( 'tag-logic' ) == 'and' ) {

					if ( sizeof( array_intersect( $product->tag_ids, $this->filtering_tags ) ) != sizeof( $this->filtering_tags ) ) { continue; }
				}
				else {

					if ( empty( array_intersect( $product->tag_ids, $this->filtering_tags ) ) ) { continue; }
				}
			}

			if ( ! empty( $this->filtering_custom_fields ) ) { 
				
				foreach ( $this->filtering_custom_fields as $field_id => $field_values ) {
					
					if (
						$control_types[ $field_id ] == 'slider'
						and
						(
							$product->custom_fields[ $field_id ] < min( $field_values )
							or
							max( $field_values ) < $product->custom_fields[ $field_id ]
						)
					) {
						// Skip this product
						continue 2;
					}
					elseif (
						$control_types[ $field_id ] != 'slider'
						and
						empty(
							array_intersect(
								(array) $product->custom_fields[ $field_id ],
								$field_values
							)
						)
					) {
						// Skip this product
						continue 2;
					}
				}
			}

			if ( ! empty( $this->filtering_text ) ) {

				$match = false;

				$match_function = extension_loaded( 'mbstring' ) ? 'mb_stripos' : 'stripos';

				if ( $this->text_match( $product->name ) ) { 

					$match = true; 
				}
				elseif ( in_array( 'description', $ewd_upcp_controller->settings->get_setting( 'product-search' ) ) and $this->text_match( $product->description ) ) { 

					$match = true; 
				}
				elseif ( in_array( 'custom_fields', $ewd_upcp_controller->settings->get_setting( 'product-search' ) ) ) {
					
					foreach ( $product->custom_fields as $field_id => $field_values ) {
						
						if ( $match ) { break; }

						foreach ( (array) $field_values as $field_value ) {
							
							if ( $this->text_match( $field_value ) ) { $match = true; }
						}
					}
				}

				if ( ! $match ) { continue; }
			}

			$args = array(
				'product'		=> $product,
				'catalog_url'	=> $this->ajax_url,
			);

			$product_objects[ $product->id ] = new ewdupcpViewCatalogProduct( $args );
		}

		return $product_objects;
	}

	/**
	 * Sets the min/max price of products in the catalogue, counts the number
	 * of products in each taxonomy that match the current filtering
	 * @since 5.0.0
	 */
	public function set_product_data( $catalog_items ) {

		foreach ( $catalog_items as $catalog_item ) {

			$this->sidebar_min_price = min( $this->sidebar_min_price, $catalog_item->product->filtering_price );

			$this->sidebar_max_price = max( $this->sidebar_max_price, $catalog_item->product->filtering_price );

			foreach ( $catalog_item->product->categories as $category ) { 

				if ( empty( $this->sidebar_categories[ $category->term_id ] ) ) { $this->sidebar_categories[ $category->term_id ] = $category; }

				$this->sidebar_categories[ $category->term_id ]->catalog_count = empty( $this->sidebar_categories[ $category->term_id ]->catalog_count ) ? 1 : $this->sidebar_categories[ $category->term_id ]->catalog_count + 1;
			}

			foreach ( $catalog_item->product->subcategories as $subcategory ) { 

				if ( empty( $this->sidebar_subcategories[ $subcategory->term_id ] ) ) { $this->sidebar_subcategories[ $subcategory->term_id ] = $subcategory; }

				$this->sidebar_subcategories[ $subcategory->term_id ]->catalog_count = empty( $this->sidebar_subcategories[ $subcategory->term_id ]->catalog_count ) ? 1 : $this->sidebar_subcategories[ $subcategory->term_id ]->catalog_count + 1;
			}

			foreach ( $catalog_item->product->tags as $tag ) { 

				if ( empty( $this->sidebar_tags[ $tag->term_id ] ) ) { $this->sidebar_tags[ $tag->term_id ] = $tag; }

				$this->sidebar_tags[ $tag->term_id ]->catalog_count = empty( $this->sidebar_tags[ $tag->term_id ]->catalog_count ) ? 1 : $this->sidebar_tags[ $tag->term_id ]->catalog_count + 1;
			}

			foreach ( $catalog_item->product->custom_fields as $field_id => $field_values ) {

				if ( empty( $this->sidebar_custom_fields[ $field_id ] ) ) { $this->sidebar_custom_fields[ $field_id ] = array(); }

				$field_values = ! empty( $field_values ) ? ( is_array( $field_values ) ? $field_values : explode( ',', $field_values ) ) : array();

				foreach ( $field_values as $field_value ) {

					empty( $this->sidebar_custom_fields[ $field_id ][ $field_value ] ) ? $this->sidebar_custom_fields[ $field_id ][ $field_value ] = 1 : $this->sidebar_custom_fields[ $field_id ][ $field_value ]++;
				}
			}
		}

		$this->sidebar_min_price = $this->sidebar_min_price == 1000000 ? 0 : $this->sidebar_min_price;
		$this->sidebar_max_price = $this->sidebar_max_price == 0 ? 1000000 : $this->sidebar_max_price;
	}

	/**
	 * Sorts the categories, sub-categories and tags into their user-specified order
	 * @since 5.0.0
	 */
	public function set_sidebar_ordering() {
		
		usort( $this->sidebar_categories, array( $this, 'sort_taxonomy_items' ) );
		usort( $this->sidebar_subcategories, array( $this, 'sort_taxonomy_items' ) );
		usort( $this->sidebar_tags, array( $this, 'sort_taxonomy_items' ) );
	}

	/**
	 * Compares two sidebar items based on their user specified order
	 * @since 5.0.0
	 */
	public function sort_taxonomy_items( $a, $b ) {

		if ( get_term_meta( $a->term_id, 'order', true ) == get_term_meta( $b->term_id, 'order', true ) ) { return 0; }

		return get_term_meta( $a->term_id, 'order', true ) > get_term_meta( $b->term_id, 'order', true ) ? 1 : -1;
	}

	/**
	 * Sets the catalog items to the sorted items, based on orderby and order properties
	 * @since 5.0.0
	 */
	public function set_sorted_items( $products ) {

		if ( $this->orderby == 'name' and $this->order == 'ASC' ) { $function_name = 'sort_items_name_asc'; }
		elseif ( $this->orderby == 'name' and $this->order == 'DESC' ) { $function_name = 'sort_items_name_desc'; }
		elseif ( $this->orderby == 'price' and $this->order == 'ASC' ) { $function_name = 'sort_items_price_asc'; }
		elseif ( $this->orderby == 'price' and $this->order == 'DESC' ) { $function_name = 'sort_items_price_desc'; }
		elseif ( $this->orderby == 'rating' and $this->order == 'ASC' ) { $function_name = 'sort_items_rating_asc'; }
		elseif ( $this->orderby == 'rating' and $this->order == 'DESC' ) { $function_name = 'sort_items_rating_desc'; }
		elseif ( $this->orderby == 'date' and $this->order == 'ASC' ) { $function_name = 'sort_items_date_asc'; }
		elseif ( $this->orderby == 'date' and $this->order == 'DESC' ) { $function_name = 'sort_items_date_desc'; }

		usort( $products, array( $this, $function_name ) );

		$items = array();

		foreach ( $products as $product ) {

			$items[] = (object) array(
				'type'		=> 'product',
				'id'		=> $product->id,
				'product'	=> $product
			);

			$this->product_count++;
		}

		$this->items = $items;
	}

	/**
	 * Match text based on settings and server extensions
	 * @since 5.2.4
	 */
	public function text_match( $text ) {
		global $ewd_upcp_controller;

		$match_function = extension_loaded( 'mbstring' ) ? 'mb_stripos' : 'stripos';

		$match = $match_function( ( $ewd_upcp_controller->settings->get_setting( 'product-search-without-accents') ? remove_accents( $text ) : $text ), $this->filtering_text ) !== false;

		return $match;
	}

	/**
	 * Default product ordering, when orderby isn't set
	 * @since 5.0.0
	 */
	public function sort_products_by_order( $a, $b ) {

		if ( $a->product->category_order == $b->product->category_order ) { return 0; }

		return $a->product->category_order > $b->product->category_order ? 1 : -1;
	}

	/**
	 * Functions below used for sorting the products when orderby is set
	 * @since 5.0.0
	 */
	public function sort_items_name_asc( $a, $b ) {

		return strcasecmp( $a->product->name, $b->product->name );
	}

	public function sort_items_name_desc( $a, $b ) {

		return -1 * strcasecmp( $a->product->name, $b->product->name );
	}

	public function sort_items_price_asc( $a, $b ) {

		if ( $a->product->filtering_price == $b->product->filtering_price ) { return 0; }

		return $a->product->filtering_price > $b->product->filtering_price ? 1 : -1;
	}

	public function sort_items_price_desc( $a, $b ) {

		if ( $a->product->filtering_price == $b->product->filtering_price ) { return 0; }

		return $a->product->filtering_price < $b->product->filtering_price ? 1 : -1;
	}

	public function sort_items_rating_asc( $a, $b ) {

		if ( $a->product->get_average_product_rating() == $b->product->get_average_product_rating() ) { return 0; }

		return $a->product->get_average_product_rating() > $b->product->get_average_product_rating() ? 1 : -1;
	}

	public function sort_items_rating_desc( $a, $b ) {

		if ( $a->product->get_average_product_rating() == $b->product->get_average_product_rating() ) { return 0; }

		return $a->product->get_average_product_rating() < $b->product->get_average_product_rating() ? 1 : -1;
	}

	public function sort_items_date_asc( $a, $b ) {

		$ad = new DateTime( $a->product->date );
  		$bd = new DateTime( $b->product->date );

  		if ( $ad == $bd ) { return 0; }

		return $ad < $bd ? 1 : -1;
	}

	public function sort_items_date_desc( $a, $b ) {

		$ad = new DateTime( $a->product->date );
  		$bd = new DateTime( $b->product->date );

  		if ( $ad == $bd ) { return 0; }

		return $ad > $bd ? 1 : -1;
	}

	/**
	 * Return the data on product counts, used to update the sidebar filtering counts
	 * @since 5.0.0
	 */
	public function get_filtering_data() {

		return array(
			'categories'	=> $this->sidebar_categories,
			'subcategories'	=> $this->sidebar_subcategories,
			'tags'			=> $this->sidebar_tags,
			'custom_fields'	=> $this->sidebar_custom_fields,
			'products'		=> $this->product_count,
			'max_pages'		=> $this->max_pages,
		);
	}

	/**
	 * Get the initial catalog css classes
	 * @since 5.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_upcp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-upcp-catalog-div',
				'ewd-upcp-catalog-' . $ewd_upcp_controller->settings->get_setting( 'styling-catalog-skin' )
			)
		);

		if ( $ewd_upcp_controller->settings->get_setting( 'lightbox-mode' ) ) {

			$classes[] = 'ewd-upcp-lightbox-mode';
		}

		return apply_filters( 'ewd_upcp_catalog_classes', $classes, $this );
	}

	/**
	 * Get the initial css classes for a specific view
	 * @since 5.0.0
	 */
	public function get_view_classes( $view, $classes = array() ) {
		global $ewd_upcp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-upcp-catalog-view',
				'ewd-upcp-' . $view . '-view',
			)
		);

		if ( $this->starting_layout != $view ) {

			$classes[] = 'ewd-upcp-hidden';
		}

		if ( $view == 'thumbnail' and $ewd_upcp_controller->settings->get_setting( 'styling-fixed-thumbnail-size' ) ) {

			$classes[] = 'ewd-upcp-catalog-fixed-thumbnail';
		}

		$classes[] = 'ewd-upcp-thumbnail-' . $ewd_upcp_controller->settings->get_setting( 'styling-number-of-columns' ) . '-columns';

		return apply_filters( 'ewd_upcp_classes_' . $view, $classes, $this );
	}

	/**
	 * Allows certain properties (categories displayed, etc. ) to be overwritten in the URL
	 * @since 5.0.0
	 */
	public function set_request_parameters() {

		$_GET_lower = array_change_key_case( $_GET, CASE_LOWER );

		if ( ! empty( $_GET_lower['singleproduct'] ) ) { $this->selected_product_id = intval( $_GET_lower['singleproduct'] ); }
		if ( ! empty( get_query_var( 'single_product' ) ) ) { 

			$post = get_page_by_path( sanitize_text_field( trim( get_query_var( 'single_product' ), '/? ' ) ), OBJECT, EWD_UPCP_PRODUCT_POST_TYPE );

			$this->selected_product_id = ! empty( $post ) ? $post->ID : false; 
		}

		if ( ! empty( $_POST['comparison_products'] ) ) {

			$this->comparison_products = is_array( $_POST['comparison_products'] ) ? array_map( 'intval', $_POST['comparison_products'] ) : array();
		}

		if ( ! empty( $_REQUEST['prod_name'] ) ) { $this->filtering_text = sanitize_text_field( $_REQUEST['prod_name'] ); }
		if ( ! empty( $_GET['max_price'] ) ) { $this->filtering_max_price = sanitize_text_field( $_GET['max_price'] ); }
		if ( ! empty( $_GET['min_price'] ) ) { $this->filtering_min_price = sanitize_text_field( $_GET['min_price'] ); }
		if ( ! empty( $_GET['overview_mode'] ) ) { $this->overview_mode = sanitize_text_field( $_GET['overview_mode'] ); }
		if ( ! empty( $_GET['categories'] ) ) { $this->filtering_categories = array_map( 'intval', explode( ',', $_GET['categories'] ) ); }
		if ( ! empty( $_GET['sub-categories'] ) ) { $this->filtering_subcategories = array_map( 'intval', explode( ',', $_GET['sub-categories'] ) ); }
		if ( ! empty( $_GET['subcategories'] ) ) { $this->filtering_subcategories = array_merge( $this->filtering_subcategories, array_map( 'intval', explode( ',', $_GET['subcategories'] ) ) ); }
		if ( ! empty( $_GET['tags'] ) ) { $this->filtering_tags = array_map( 'intval', explode( ',', $_GET['tags'] ) ); }
	}

	/**
	 * Set any neccessary variables for displaying the catalog
	 * @since 5.0.0
	 */
	public function set_variables() {
		global $ewd_upcp_controller;

		$catalog_post = get_post( $this->id );

		if ( empty( $catalog_post ) or $catalog_post->post_type != EWD_UPCP_CATALOG_POST_TYPE ) {
			
			$args = array(
				'post_type'		=> EWD_UPCP_CATALOG_POST_TYPE,
				'meta_query' 	=> array(
					array(
						'key'		=> 'old_catalog_id',
						'value'		=> $this->id
					)
				)
			);

			$posts = get_posts( $args );

			$catalog_post = ! empty( $posts ) ? reset( $posts ) : null;
		}

		$this->catalog 				= $catalog_post;
		$this->sidebar 				= strtolower( $this->sidebar ) == 'no' ? false : true;
		$this->excluded_views 		= strtolower( $this->excluded_layouts ) == 'none' ? array() : array_map( 'strtolower', explode( ',', $this->excluded_layouts ) );
		$this->starting_layout 		= strtolower( $this->starting_layout );
		$this->ajax_url 			= ! empty( $this->ajax_url ) ? $this->ajax_url : get_permalink();

		$this->filtering_text 			= ! empty( $this->prod_name ) ? sanitize_text_field( $this->prod_name ) : $this->filtering_text;
		$this->filtering_max_price 		= ! empty( $this->max_price ) ? sanitize_text_field( $this->max_price ) : ( ! empty( $this->filtering_max_price ) ? $this->filtering_max_price : '' );
		$this->filtering_min_price 		= ! empty( $this->min_price ) ? sanitize_text_field( $this->min_price ) : ( ! empty( $this->filtering_min_price ) ? $this->filtering_min_price : '' );
		$this->filtering_categories 	= ! empty( $this->category ) ? ( ! empty( $this->filtering_categories ) ? array_intersect( $this->filtering_categories, explode( ',', $this->category ) ) : explode( ',', $this->category ) ) : $this->filtering_categories;
		$this->filtering_subcategories 	= ! empty( $this->subcategory ) ? ( ! empty( $this->filtering_subcategories ) ? array_intersect( $this->filtering_subcategories, explode( ',', $this->subcategory ) ) : explode( ',', $this->subcategory ) ) : $this->filtering_subcategories;
		$this->filtering_tags 			= ! empty( $this->tags ) ? ( ! empty( $this->filtering_tags ) ? array_intersect( $this->filtering_tags, explode( ',', $this->tags ) ) : explode( ',', $this->tags ) ) : $this->filtering_tags;

		$this->filtering_text = $ewd_upcp_controller->settings->get_setting( 'product-search-without-accents') ? remove_accents( $this->filtering_text ) : $this->filtering_text;

		$this->filtering_custom_fields = array();

		$custom_fields =  ! empty( $this->custom_fields ) ? explode( ',', $this->custom_fields ) : array();

		foreach ( $custom_fields as $custom_field ) {

			$field_id = substr( $custom_field, 0, strpos( $custom_field, '=' ) );
			$field_value = substr( $custom_field, strpos( $custom_field, '=' ) + 1 );

			$this->filtering_custom_fields[ $field_id ] = empty( $this->filtering_custom_fields[ $field_id ] ) ? array( $field_value ) : array_merge( $this->filtering_custom_fields[ $field_id ], array( $field_value ) );
		}

		$this->orderby 				= ! empty( $this->orderby ) ? $this->orderby : '';
		$this->order 	 			= ! empty( $this->order ) ? $this->order : 'ASC';

		$this->overview_mode = empty( $this->overview_mode ) ? $ewd_upcp_controller->settings->get_setting( 'overview-mode' ) : strtolower( $this->overview_mode );
		$this->overview_mode = ( $this->overview_mode == 'full' and ! empty( $this->filtering_categories ) and empty( $this->filtering_subcategories ) ) ? 'subcategories' : ( ( $this->overview_mode != 'none' and empty( $this->filtering_categories ) and empty( $this->filtering_subcategories ) ) ? 'categories' : false ); 
		$this->omit_fields = empty( $this->omit_fields ) ? array() : array_map( 'strtolower', explode( ',', $this->omit_fields ) );

		$comparison_fields = array(
			'image',
			'price',
			'categories',
			'subcategories',
			'tags',
		);

		$this->product_comparison_fields = array_diff( $comparison_fields, $this->omit_fields );

		$this->cart_products = ! empty( $_COOKIE['upcp_cart_products'] ) ? explode( ',', sanitize_text_field( $_COOKIE['upcp_cart_products'] ) ) : array();
	}

	/**
	 * Set data about pagination for this catalog
	 * @since 5.0.0
	 */
	public function set_pagination_data() {
		global $ewd_upcp_controller;

		$this->current_page 		= intval( $this->current_page );
		$this->products_per_page 	= ! empty( $this->products_per_page ) ? intval( $this->products_per_page ) : $ewd_upcp_controller->settings->get_setting( 'products-per-page' );
		$this->max_pages			= ! empty( $this->product_count ) ? ceil( $this->product_count / $this->products_per_page ) : 1;
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 5.0.0
	 */
	public function enqueue_assets() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) != 'custom' or $ewd_upcp_controller->settings->get_setting( 'product-page' ) != 'large' ) {

			wp_enqueue_style( 'ewd-upcp-gridster' );
		}

		wp_enqueue_style( 'ewd-upcp-css' );
		wp_enqueue_style( 'ewd-upcp-jquery-ui' );

		if ( $ewd_upcp_controller->settings->get_setting( 'no' ) != 'no' ) {

			wp_enqueue_style( 'ewd-ulb-main-css' );
			wp_enqueue_script( 'ultimate-lightbox' );
		}

		$data = array(
			'infinite_scroll'						=> $ewd_upcp_controller->settings->get_setting( 'infinite-scroll' ),
			'hide_empty_filtering_options'			=> $ewd_upcp_controller->settings->get_setting( 'hide-empty-options-filtering' ),
			'price_filtering_disabled'				=> $ewd_upcp_controller->settings->get_setting( 'disable-price-filter' ),
			'disable_auto_adjust_thumbnail_heights'	=> $ewd_upcp_controller->settings->get_setting( 'disable-thumbnail-auto-adjust' ),
			'updating_results_label'				=> $ewd_upcp_controller->settings->get_setting( 'label-updating-results' ),
			'no_results_found_label'				=> $ewd_upcp_controller->settings->get_setting( 'label-no-results-found' ),
			'compare_label'							=> $ewd_upcp_controller->settings->get_setting( 'label-compare' ),
			'side_by_side_label'					=> $ewd_upcp_controller->settings->get_setting( 'label-side-by-side' ),
			'list_click_action'						=> $ewd_upcp_controller->settings->get_setting( 'styling-list-view-click-action' ),
		);

		$ewd_upcp_controller->add_front_end_php_data( 'ewd-upcp-js', 'ewd_upcp_php_data', $data );

		wp_enqueue_script( 'ewd-upcp-js' );

	}
}