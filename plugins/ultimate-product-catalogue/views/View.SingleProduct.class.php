<?php

/**
 * Class to display a single product on the front end.
 *
 * @since 5.0.0
 */
class ewdupcpViewSingleProduct extends ewdupcpViewProduct {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 5.0.0
	 */
	public function render() {
		global $ewd_upcp_controller;

		$ewd_upcp_controller->is_single_product = true;

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		$this->set_catalog_links();

		$this->set_variables();

		$this->add_schema_data();

		$this->update_product_view_count();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();
		
		$this->add_custom_styling();

		if ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'tabbed' ) { $template = $this->find_template( 'tabbed-single-product' ); }
		elseif ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'shop_style' ) { $template = $this->find_template( 'shop-style-single-product' ); }
		elseif ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'custom' ) { $template = $this->find_template( 'custom-single-product' ); }
		elseif ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'large' ) { $template = $this->find_template( 'custom-large-single-product' ); }
		else { $template = $this->find_template( 'default-single-product' ); }
		
		if ( $template ) {
			include( $template );
		}
		$output = ob_get_clean();

		return apply_filters( 'ewd_upcp_single_product_output', $output, $this );
	}

	/**
	 * Print the breadcrumbs (catalog, category, etc.)
	 *
	 * @since 5.0.0
	 */
	public function print_product_breadcrumbs() {

		$template = $this->find_template( 'single-product-breadcrumbs' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product title
	 *
	 * @since 5.0.0
	 */
	public function print_title() {
		
		$template = $this->find_template( 'single-product-title' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product price, if not disabled
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_price() {
		global $ewd_upcp_controller;

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'disable-product-page-price' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-price' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a product's sale price, if different from its regular price
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_sale_price() {
		global $ewd_upcp_controller;

		if ( $this->product->current_price == $this->product->regular_price ) { return; }

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'disable-product-page-price' ) ) ) { return; }
		
		$template = $this->find_template( 'catalog-product-sale-price' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's additional images
	 *
	 * @since 5.0.0
	 */
	public function print_additional_images() {
		
		$template = $this->find_template( 'single-product-additional-images' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's main image
	 *
	 * @since 5.0.0
	 */
	public function print_main_image() {
		
		$template = $this->find_template( 'single-product-main-image' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the previous thumbnail's button, if there are multiple images/videos
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_previous_thumbnails_button() {
		global $ewd_upcp_controller;

		if ( ( sizeof( $this->product->images ) + sizeof( $this->product->videos ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-thumbnails-previous-button' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the next thumbnail's button, if there are multiple images/videos
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_next_thumbnails_button() {
		global $ewd_upcp_controller;

		if ( ( sizeof( $this->product->images ) + sizeof( $this->product->videos ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-thumbnails-next-button' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's description
	 *
	 * @since 5.0.0
	 */
	public function print_product_description() {
		
		$template = $this->find_template( 'single-product-description' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's extra description elements (categories, tags, etc.)
	 *
	 * @since 5.0.0
	 */
	public function print_extra_description_elements() {
		
		$template = $this->find_template( 'single-product-extra-elements' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's related products, if any exist
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_related_products() {
		
		if ( empty( $this->product->related_products ) ) { return; }
			
		$template = $this->find_template( 'single-product-related-products' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's next/previous products, if any exist
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_next_previous_products() {

		if ( empty( $this->product->next_product ) and empty( $this->product->previous_product ) ) { return; }
		
		$template = $this->find_template( 'single-product-next-previous-products' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the product's videos, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_videos() {
		global $ewd_upcp_controller;

		if ( ! in_array( 'videos', $ewd_upcp_controller->settings->get_setting( 'extra-elements' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-videos' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print an inquiry form for the product, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_inquiry_form() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-form' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-inquiry-form' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the tab selectors for the tabbed product page
	 *
	 * @since 5.0.0
	 */
	public function print_tabs_menu() {
		
		$template = $this->find_template( 'single-product-tabs-menu' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print an inquiry form tab for the product, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_inquiry_form_tab() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-form' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-tab-inquiry-form' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a reviews tab for the product, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_reviews_tab() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-reviews' ) ) ) { return; }

		$template = $this->find_template( 'single-product-tab-reviews' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print an FAQ tab for the product, if selected
	 *
	 * @since 5.0.0
	 */
	public function maybe_print_faqs_tab() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'product-faqs' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-tab-faqs' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print any user created tabs that exist
	 *
	 * @since 5.0.0
	 */
	public function print_custom_tabs() {
		global $ewd_upcp_controller;

		if ( empty( get_option( 'ewd-upcp-product-page-tabs' ) ) ) { return; }
		
		$template = $this->find_template( 'single-product-custom-tabs' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the default single product page template, for use in the custom-large-single-product page template
	 *
	 * @since 5.0.0
	 */
	public function print_mobile_default_product_page() {

		$template = $this->find_template( 'default-single-product' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Return the large screen product page elements 
	 *
	 * @since 5.0.0
	 */
	public function get_custom_product_page_elements() {
		global $ewd_upcp_controller;

		$product_page_serialized = get_option( 'UPCP_Product_Page_Serialized' );

		$gridster = strpos( $product_page_serialized, 'class=\\\\') !== false ? json_decode( stripslashes( $product_page_serialized ) ) : json_decode( $product_page_serialized );

		$gridster = is_array( $gridster ) ? $gridster : array();

		usort( $gridster, array( $ewd_upcp_controller->admin_product_page, 'sort_gridster' ) );

		return $gridster;
	}

	/**
	 * Return the mobile screen product page elements 
	 *
	 * @since 5.0.0
	 */
	public function get_mobile_custom_product_page_elements() {
		global $ewd_upcp_controller;

		$product_page_serialized = get_option( 'UPCP_Product_Page_Serialized_Mobile' );

		$gridster = strpos( $product_page_serialized, 'class=\\\\') !== false ? json_decode( stripslashes( $product_page_serialized ) ) : json_decode( $product_page_serialized );

		$gridster = is_array( $gridster ) ? $gridster : array();

		usort( $gridster, array( $ewd_upcp_controller->admin_product_page, 'sort_gridster' ) );

		return $gridster;
	}

	/**
	 * Prints a single element for the custom product page layout
	 *
	 * @since 5.0.0
	 */
	public function print_custom_product_page_element( $element ) {

		$this->element = $element;
		
		if ( $element->element_class == 'additional_images' ) { $template = $this->find_template( 'single-product-custom-element-additional-images' ); }
		elseif ( $element->element_class == 'back' ) { $template = $this->find_template( 'single-product-custom-element-back-to-catalog' ); }
		elseif ( $element->element_class == 'blank' ) { $template = $this->find_template( 'single-product-custom-element-blank' ); }
		elseif ( $element->element_class == 'category' ) { $template = $this->find_template( 'single-product-custom-element-categories' ); }
		elseif ( $element->element_class == 'category_label' ) { $template = $this->find_template( 'single-product-custom-element-categories-label' ); }
		elseif ( $element->element_class == 'description' ) { $template = $this->find_template( 'single-product-custom-element-description' ); }
		elseif ( $element->element_class == 'main_image' ) { $template = $this->find_template( 'single-product-custom-element-main-image' ); }
		elseif ( $element->element_class == 'next_previous' ) { $template = $this->find_template( 'single-product-custom-element-next-previous' ); }
		elseif ( $element->element_class == 'price' ) { $template = $this->find_template( 'single-product-custom-element-price' ); }
		elseif ( $element->element_class == 'price_label' ) { $template = $this->find_template( 'single-product-custom-element-price-label' ); }
		elseif ( $element->element_class == 'product_link' ) { $template = $this->find_template( 'single-product-custom-element-link' ); }
		elseif ( $element->element_class == 'product_name' ) { $template = $this->find_template( 'single-product-custom-element-title' ); }
		elseif ( $element->element_class == 'related_products' ) { $template = $this->find_template( 'single-product-custom-element-related-products' ); }
		elseif ( $element->element_class == 'subcategory' ) { $template = $this->find_template( 'single-product-custom-element-subcategories' ); }
		elseif ( $element->element_class == 'subcategory_label' ) { $template = $this->find_template( 'single-product-custom-element-subcategories-label' ); }
		elseif ( $element->element_class == 'tags' ) { $template = $this->find_template( 'single-product-custom-element-tags' ); }
		elseif ( $element->element_class == 'tags_label' ) { $template = $this->find_template( 'single-product-custom-element-tags-label' ); }
		elseif ( $element->element_class == 'custom_field' ) { $template = $this->find_template( 'single-product-custom-element-custom-field' ); }
		elseif ( $element->element_class == 'custom_label' ) { $template = $this->find_template( 'single-product-custom-element-custom-field-label' ); }
		else { $template = $this->find_template( 'single-product-custom-element-text' ); }
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Prints field_name => field_value pairs for this product
	 *
	 * @since 5.0.0
	 */
	public function print_custom_fields() {
		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields(); 

		foreach ( $custom_fields as $custom_field ) {
	
			if ( ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'tabbed' or $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'shop_style' ) and empty( $custom_field->tabbed_display ) ) { continue; }

			if ( $ewd_upcp_controller->settings->get_setting( 'hide-blank-custom-fields' ) and empty( $this->product->custom_fields[ $custom_field->id ] ) ) { continue; }

			if ( $custom_field->type == 'file' ) { $template = $this->find_template( 'single-product-custom-element-file-field' ); }
			else { $template = $this->find_template( 'single-product-custom-element-field' ); }

			$this->custom_field = $custom_field;

			if ( $template ) {
				include( $template );
			}
		}
	}

	/**
	 * Print the selected social media buttons
	 *
	 * @since 5.0.0
	 */
	public function print_social_media_buttons() {
		global $ewd_upcp_controller;
		
		if ( in_array( 'facebook', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'twitter', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'linkedin', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'pinterest', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'email', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) {
			echo '<ul class="rrssb-buttons">';
		}
		
		if ( in_array( 'facebook', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) { $this->print_facebook_button(); }
		if ( in_array( 'twitter', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) { $this->print_twitter_button(); }
		if ( in_array( 'linkedin', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) { $this->print_linkedin_button(); }
		if ( in_array( 'pinterest', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) { $this->print_pinterest_button(); }
		if ( in_array( 'email', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) { $this->print_email_button(); }

		if ( in_array( 'facebook', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'twitter', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'linkedin', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'pinterest', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) or in_array( 'email', $ewd_upcp_controller->settings->get_setting( 'social-media-links' ) ) ) {
			echo '</ul>';
		}
	}

	/**
	 * Print a link to share this Product on Facebook
	 *
	 * @since 5.0.0
	 */
	public function print_facebook_button() {
		
		$template = $this->find_template( 'single-product-social-media-facebook' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a link to share this Product on Twitter
	 *
	 * @since 5.0.0
	 */
	public function print_twitter_button() {
		
		$template = $this->find_template( 'single-product-social-media-twitter' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a link to share this Product on LinkedIn
	 *
	 * @since 5.0.0
	 */
	public function print_linkedin_button() {
		
		$template = $this->find_template( 'single-product-social-media-linkedin' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a link to share this Product on Pinterest
	 *
	 * @since 5.0.0
	 */
	public function print_pinterest_button() {
		
		$template = $this->find_template( 'single-product-social-media-pinterest' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print a link to share this Product via email
	 *
	 * @since 5.0.0
	 */
	public function print_email_button() {
		
		$template = $this->find_template( 'single-product-social-media-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns a link to this Product for Twitter
	 *
	 * @since 5.0.0
	 */
  	public function get_social_twitter_link() {

  		$text = __( 'Check out this product', 'ultimate-products' ) . ': ';

  		return 'https://twitter.com/intent/tweet?text=' . urlencode( $text ) . urlencode( $this->product->name ) . urlencode( ' | ' ) . urlencode( $this->details_link );
  	}

	/**
	 * Returns a link to this Product for LinkedIn
	 *
	 * @since 5.0.0
	 */
  	public function get_social_linkedin_link() {

  		$text = __( 'Check out this product', 'ultimate-products' );

  		return 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $this->details_link . "&amp;title=" . urlencode( $text ) . "&amp;summary=" . urlencode( $this->product->name );
  	}

	/**
	 * Returns a link to this Product for Pinterest
	 *
	 * @since 5.0.0
	 */
  	public function get_social_pinterest_link() {

  		$text = __( 'Check out this product', 'ultimate-products' ) . ': ';

  		return 'http://pinterest.com/pin/create/button/?url=' . $this->details_link . "&amp;description=" . urlencode( $text ) . urlencode( $this->product->name );
  	}

	/**
	 * Returns a link to this Product that can be emailed
	 *
	 * @since 5.0.0
	 */
  	public function get_social_email_mailto_link() {

  		$subject = __( 'Check out this product', 'ultimate-products' );

  		return 'mailto:?subject=' . urlencode( $subject ) . "&amp;body=" . urlencode( $this->product->name ) . urlencode( ' | ' ) . urlencode( $this->details_link );
  	}

  	/**
	 * Render the minimal product view for a specified product
	 *
	 * @since 5.0.0
	 */
  	public function render_minimal_product( $product_id ) {

  		$product = new ewdupcpProduct();

  		$product->load_post( $product_id );

  		$args = array(
  			'product'	=> $product
  		);

  		$minimal_view = new ewdupcpViewCatalogProduct( $args );

  		return $minimal_view->render_view( 'minimal' );
  	}

  	/**
	 * Returns the lightbox class that should be applied to the main product image, if any
	 *
	 * @since 5.0.0
	 */
  	public function get_main_image_lightbox_class() {
		global $ewd_upcp_controller;

  		return $ewd_upcp_controller->settings->get_setting( 'product-image-lightbox' ) == 'no' ? '' :
  			( ( $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'custom' or $ewd_upcp_controller->settings->get_setting( 'product-page' ) == 'large' ) ? 'ewd-ulb-lightbox' : 'ewd-ulb-open-lightbox' );
  	}

  	/**
	 * Returns the lightbox class that should be applied to the additional product images, if any
	 *
	 * @since 5.0.0
	 */
  	public function get_additional_images_lightbox_class() {
		global $ewd_upcp_controller;

  		return $ewd_upcp_controller->settings->get_setting( 'product-image-lightbox' ) == 'yes' ? 'ewd-ulb-lightbox' : ( $ewd_upcp_controller->settings->get_setting( 'product-image-lightbox' ) == 'main' ? 'ewd-ulb-lightbox-noclick-image' : '' );
  	}

  	/**
	 * Returns the category label, based on the label or number of category terms
	 *
	 * @since 5.0.0
	 */
  	public function get_categories_label() {
  		global $ewd_upcp_controller;

  		return $ewd_upcp_controller->settings->get_setting( 'label-product-page-category' ) ? $ewd_upcp_controller->settings->get_setting( 'label-product-page-category' ) : ( sizeOf( $this->product->categories ) == 1 ? __( 'Category:', 'ultimate-products' ) : __( 'Categories:', 'ultimate-products' ) );
  	}

  	/**
	 * Returns the sub-category label, based on the label or number of category terms
	 *
	 * @since 5.0.0
	 */
  	public function get_subcategories_label() {
  		global $ewd_upcp_controller;

  		return $ewd_upcp_controller->settings->get_setting( 'label-product-page-subcategory' ) ? $ewd_upcp_controller->settings->get_setting( 'label-product-page-subcategory' ) : ( sizeOf( $this->product->subcategories ) == 1 ? __( 'Sub-Category:', 'ultimate-products' ) : __( 'Sub-Categories:', 'ultimate-products' ) );
  	}

  	/**
	 * Returns the tag label, based on the label or number of tag terms
	 *
	 * @since 5.0.0
	 */
  	public function get_tags_label() {
  		global $ewd_upcp_controller;

  		return $ewd_upcp_controller->settings->get_setting( 'label-product-page-tags' ) ? $ewd_upcp_controller->settings->get_setting( 'label-product-page-tags' ) : ( sizeOf( $this->product->tags ) == 1 ? __( 'Tag:', 'ultimate-products' ) : __( 'Tags:', 'ultimate-products' ) );
  	}

	/**
	 * Returns all of the custom product page tabs that have been created
	 *
	 * @since 5.0.0
	 */
  	public function get_product_page_tabs() {

  		return is_array( get_option( 'ewd-upcp-product-page-tabs' ) ) ? get_option( 'ewd-upcp-product-page-tabs' ) : array();
  	}

	/**
	 * Returns whether a tab is the tab that starts open
	 *
	 * @since 5.0.0
	 */
  	public function is_starting_tab( $tab ) {
  		
  		return empty( get_option( 'ewd-upcp-product-page-starting-tab' ) ) ? ( $tab == 'details' ) : ( get_option( 'ewd-upcp-product-page-starting-tab' ) == $tab );
  	}

  	/**
	 * Returns the name of a custom field, given its ID
	 *
	 * @since 5.0.0
	 */
  	public function get_custom_field_name( $field_id ) {
  		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( $custom_field->id == $field_id ) { return $custom_field->name; }
		}

		return '';
  	}

  	/**
	 * Returns a comma-separated string of this product's category slugs, if any
	 * @since 5.0.0
	 */
	public function get_product_category_slugs() {

		$category_slugs = '';

		foreach ( $this->product->categories as $category ) {

			$category_slugs .= $category->slug . ',';
		}

		return trim( $category_slugs, ',' );
	}

	/**
	 * Adds schema data about the Product being displayed
	 *
	 * @since 5.0.0
	 */
	public function add_schema_data() {
		global $ewd_upcp_controller;

		$schema_object = array(
			'@context'			=> 'https://schema.org',
	    	'@type' 			=> 'Product',
	    	'name' 				=> $this->product->name,
	    	'image' 			=> $this->product->get_main_image_url(),
	    	'url'				=> $this->details_link,
	    	'offers'			=> array(
	    		'type'				=> 'Offer',
	    		'price'				=> $this->product->current_price,
	    		'url'				=> $this->details_link,
	    	)
	    );

	    $ewd_upcp_controller->schema_product_data = $schema_object; 
	}

	/**
	 * Updates the number of times that a particular product has been viewed
	 * @since 5.0.0
	 */
	public function update_product_view_count() {
		
		update_post_meta( $this->product->id, 'views', intval( get_post_meta( $this->product->id, 'views', true ) ) + 1 );
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
				'ewd-upcp-single-product-div',
				
			)
		);

		return apply_filters( 'ewd_upcp_single_product_classes', $classes, $this );
	}


	/**
	 * Set any neccessary variables when the Product is created
	 * @since 5.0.0
	 */
	public function set_variables() {
		global $ewd_upcp_controller;
	}

	/**
	 * Builds the display permalink for the current Product, based on selected settings
	 * @since 5.0.0
	 */
	public function add_product_permalink() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'permalink-type' ) == 'individual_page' ) {

			$this->permalink =  get_permalink( $this->post->ID );

			return;
		}

		if ( $ewd_upcp_controller->settings->get_setting( 'pretty-permalinks' ) ) {

			$this->permalink =  get_permalink() . 'single-product/' . $this->post->post_name . '/';

			return;
		} 

		$this->permalink = add_query_arg( 'Display_Product', $this->post->ID, ( ! empty( $this->current_url ) ? $this->current_url : get_permalink() ) );
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 5.0.0
	 */
	public function enqueue_assets() {
		
		wp_enqueue_style( 'ewd-upcp-css' );
		wp_enqueue_style( 'rrssb' );

		wp_enqueue_script( 'ewd-upcp-gridster' );
		wp_enqueue_script( 'ewd-upcp-js' );

	}
}
