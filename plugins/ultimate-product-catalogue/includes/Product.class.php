<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdupcpProduct' ) ) {
/**
 * Class to handle a Product for Ultimate Product Catalog
 *
 * @since 5.0.0
 */
class ewdupcpProduct {

	public $category_ids = array();

	public $subcategory_ids = array();

	public $tag_ids = array();

	public $custom_fields = array();

	public function __construct() {}

	/**
	 * Load the Product information from a WP_Post object or an ID
	 *
	 * @uses load_wp_post()
	 * @since 5.0.0
	 */
	public function load_post( $post ) {

		if ( is_int( $post ) || is_string( $post ) ) {
			$post = get_post( $post );
		}

		if ( get_class( $post ) == 'WP_Post' && $post->post_type == EWD_UPCP_PRODUCT_POST_TYPE ) {
			$this->load_wp_post( $post );
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Load data from WP post object and retrieve metadata
	 *
	 * @uses load_post_metadata()
	 * @since 5.0.0
	 */
	public function load_wp_post( $post ) {

		// Store post for access to other data if needed by extensions
		$this->post = $post;

		$this->ID = $this->id = $post->ID;
		$this->name = $post->post_title;
		$this->slug = $post->post_name;
		$this->date = $post->post_date;
		$this->description = $post->post_content;

		$this->load_post_categories();
		$this->load_post_subcategories();
		$this->load_post_tags();

		$this->load_custom_fields();
		$this->load_post_metadata();

		do_action( 'ewd_upcp_product_load_post_data', $this, $post );
	}

	/**
	 * Load all top-level categories for this product as an array
	 * @since 5.0.0
	 */
	public function load_post_categories() {

		$taxonomy_categories = get_the_terms( $this->post, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );
		
		$categories = array();

		if ( ! is_array( $taxonomy_categories ) ) { $taxonomy_categories = array(); }

		foreach ( $taxonomy_categories as $taxonomy_category ) {

			if ( $taxonomy_category->parent != 0 ) { continue; }

			$categories[] = $taxonomy_category;
		}

		$this->categories = $categories;

		foreach ( $this->categories as $category ) {

			$this->category_ids[] = $category->term_id;
		}
	}

	/**
	 * Load all lower-level categories for this product as an array
	 * @since 5.0.0
	 */
	public function load_post_subcategories() {

		$taxonomy_categories = get_the_terms( $this->post, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );
		
		$subcategories = array();

		if ( ! is_array( $taxonomy_categories ) ) { $taxonomy_categories = array(); }

		foreach ( $taxonomy_categories as $taxonomy_category ) {

			if ( $taxonomy_category->parent == 0 ) { continue; }

			$subcategories[] = $taxonomy_category;
		}

		$this->subcategories = $subcategories;

		foreach ( $this->subcategories as $subcategory ) {

			$this->subcategory_ids[] = $subcategory->term_id;
		}
	}

	/**
	 * Load all tags for this product as an array
	 * @since 5.0.0
	 */
	public function load_post_tags() {

		$tags = get_the_terms( $this->post, EWD_UPCP_PRODUCT_TAG_TAXONOMY );
		
		$this->tags = is_array( $tags ) ? $tags : array(); 

		foreach ( $this->tags as $tag ) {

			$this->tag_ids[] = $tag->term_id;
		}
	}

	/**
	 * Store custom field information for post
	 * @since 5.0.0
	 */
	public function load_custom_fields() {
		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! $custom_field->id ) { continue; }

			$this->custom_fields[ $custom_field->id ] = get_post_meta( $this->ID, 'custom_field_' . $custom_field->id, true );
		}
	}

	/**
	 * Store metadata for post
	 * @since 5.0.0
	 */
	public function load_post_metadata() {
		global $ewd_upcp_controller;

		$this->regular_price 	= get_post_meta( $this->ID, 'price', true );
		$this->sale_price 		= get_post_meta( $this->ID, 'sale_price', true );
		$this->sale_mode 		= get_post_meta( $this->ID, 'sale_mode', true );
		$this->link 			= get_post_meta( $this->ID, 'link', true );
		$this->display 			= get_post_meta( $this->ID, 'display', true );
		$this->category_order 	= get_post_meta( $this->ID, 'order', true );
		$this->woocommerce_id 	= get_post_meta( $this->ID, 'woocommerce_id', true );

		$this->related_products = 	$ewd_upcp_controller->settings->get_setting( 'related-products' ) == 'none' ? array() :
									( $ewd_upcp_controller->settings->get_setting( 'related-products' ) == 'automatic' ? $this->get_automatic_related_products() : 
									( is_array( get_post_meta( $this->ID, 'related_products', true ) ) ? array_filter( get_post_meta( $this->ID, 'related_products', true ) ) : array() ) );
		
		$this->next_product 	= 	$ewd_upcp_controller->settings->get_setting( 'next-previous-products' ) == 'none' ? false : 
									( $ewd_upcp_controller->settings->get_setting( 'next-previous-products' ) == 'automatic' ? $this->get_automatic_next_product() :
									( get_post_meta( $this->ID, 'next_product', true ) ) );

		$this->previous_product =	$ewd_upcp_controller->settings->get_setting( 'next-previous-products' ) == 'none' ? false : 
									( $ewd_upcp_controller->settings->get_setting( 'next-previous-products' ) == 'automatic' ? $this->get_automatic_previous_product() :
									( get_post_meta( $this->ID, 'previous_product', true ) ) );

		$this->external_image 		= get_post_meta( $this->ID, 'external_image', true );
		$this->external_image_url 	= get_post_meta( $this->ID, 'external_image_url', true );

		$this->images 			= is_array( get_post_meta( $this->ID, 'product_images', true ) ) ? get_post_meta( $this->ID, 'product_images', true ) : array();
		$this->videos 			= is_array( get_post_meta( $this->ID, 'product_videos', true ) ) ? get_post_meta( $this->ID, 'product_videos', true ) : array();

		$this->set_current_price();

		$this->set_filtering_price(); 
	}

	/**
	 * Gets the product's current price, based on sales settings
	 * @since 5.0.0
	 */
	public function set_current_price() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'sale-mode' ) == 'none' ) { 

			$this->current_price = empty( $this->regular_price ) ? $this->sale_price : $this->regular_price; 
		}
		elseif ( $ewd_upcp_controller->settings->get_setting( 'sale-mode' ) == 'all' ) { 

			$this->current_price = ! empty( $this->sale_price ) ? $this->sale_price : $this->regular_price; 
		}
		else {
			
			$this->current_price = ! empty( $this->sale_mode ) ? ( ! empty( $this->sale_price ) ? $this->sale_price : $this->regular_price ) : $this->regular_price;
		}
	}

	public function set_filtering_price() {

		$this->filtering_price = $this->current_price;

		$divisor = ( substr( $this->current_price, -3, 1 ) == ',' or substr( $this->current_price, -3, 1 ) == '.' ) ? 100 : 1;

		$this->filtering_price = empty( $this->current_price ) ? 0 : intval( preg_replace( '~\D~', '', $this->current_price ) ) / $divisor;
	}

	/**
	 * Gets the product's current price, based on sales settings
	 * @since 5.0.0
	 */
	public function get_display_price( $price_type = 'current' ) {
		global $ewd_upcp_controller;

		$price = $price_type == 'current' ? $this->current_price : ( $price_type == 'regular' ? $this->regular_price : $this->sale_price );

		if ( empty( $price ) ) { return $price; }

		return $ewd_upcp_controller->settings->get_setting( 'currency-symbol-location' ) == 'before' ? $ewd_upcp_controller->settings->get_setting( 'currency-symbol' ) . $price : $price . $ewd_upcp_controller->settings->get_setting( 'currency-symbol' );
	}

	/**
	 * Returns the filtered product description
	 * @since 5.0.0
	 */
	public function get_product_description() {
		global $ewd_upcp_controller;

		$product_description = wpautop( $this->description );

		if ( empty( $ewd_upcp_controller->settings->get_setting( 'disable-custom-field-conversion' ) ) ) { 

			$product_description = $this->convert_custom_fields( $product_description ); 
		}

		$args = array(
			'product_id' => $this->ID
		);

		return apply_filters( 'ewd_upcp_description_filter', $product_description, $args );
	}

	/**
	 * Returns video objects ready to be posted
	 * @since 5.0.0
	 */
	public function get_videos() {

		$protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";

		foreach ( $this->videos as $key => $video ) {

			$this->videos[ $key ]->embed_url = $protocol . 'www.youtube.com/embed/' . $video->url . '?rel=0&fs=1';

			$this->videos[ $key ]->description = "No title available for this video";
		}

		return $this->videos;
	}

	/**
	 * Returns a product inquiry form specific to this product
	 * @since 5.0.0
	 */
	public function get_inquiry_form() {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-plugin' ) == 'cf7' ) { return $this->get_contact_form_7_inquiry_form(); }
		else { return $this->get_wp_forms_inquiry_form(); }
	}

	/**
	 * Returns a product inquiry form specific to this product
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
	 * Returns a product inquiry form specific to this product
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
	 * Converts any custom field tags in the supplied text
	 * @since 5.0.0
	 */
	public function convert_custom_fields( $text ) {
		global $ewd_upcp_controller;

		$search = array( 
			'[product-name]', 
			'[upcp-price]'
		);

		$replace = array( 
			$this->name, 
			$this->current_price 
		);

		foreach ( $ewd_upcp_controller->settings->get_custom_fields() as $custom_field ) {

			$search[] = '[' . $custom_field->slug . ']';
			
			if ( empty( $this->custom_fields[ $custom_field->id ] ) ) {

				$replace[] = '';
			}
			elseif ( $custom_field->type == 'file' ) {

				$replace[] = '<a href="' . $this->custom_fields[ $custom_field->id ] . '" download>' . basename( $this->custom_fields[ $custom_field->id ] ) . '</a>';
			}
			elseif ( $custom_field->type == 'link' ) {

				$replace[] = '<a href="' . $this->custom_fields[ $custom_field->id ] . '">' . __( 'Link', 'ultimate-product-catalogue' ) . '</a>';
			}
			else { 

				$replace[] = is_array( $this->custom_fields[ $custom_field->id ] ) ? implode( ',', $this->custom_fields[ $custom_field->id ] ) : $this->custom_fields[ $custom_field->id ]; 
			}
		}

		return str_replace( $search, $replace, $text );
	}

	/**
	 * Converts product inquiry form tags into their corresponding values
	 * @since 5.0.0
	 */
	public function replace_inquiry_form_fields( $text ) {

		$text = $this->convert_custom_fields( $text );

		$search = array( 
			'%PRODUCT_NAME%', 
			'%PRODUCT_ID%',
			'%PRODUCT_PRICE%',
			'%PRODUCT_CATEGORY%',
			'%PRODUCT_SUBCATEGORY%',
			'%PRODUCT_TAGS%',
			'%PRODUCT_DESCRIPTION%'
		);
		
		$replace = array( 
			esc_attr( $this->name ), 
			esc_attr( $this->ID ),
			esc_attr( $this->current_price ),
			esc_attr( $this->get_category_names() ),
			esc_attr( $this->get_subcategory_names() ),
			esc_attr( $this->get_tag_names() ),
			esc_attr( $this->get_product_description() )
		);

		return str_replace( $search, $replace, $text );
	}

	/**
	 * Returns a comma-separated string of this product's category names
	 * @since 5.0.0
	 */
	public function get_category_names() {

		$categories = '';

		foreach ( $this->categories as $category ) {

			$categories .= $category->name . ',';
		}

		return trim( $categories, ',' );
	}

	/**
	 * Returns a comma-separated string of this product's sub-category names
	 * @since 5.0.0
	 */
	public function get_subcategory_names() {

		$subcategories = '';

		foreach ( $this->subcategories as $subcategory ) {

			$subcategories .= $subcategory->name . ',';
		}

		return trim( $subcategories, ',' );
	}

	/**
	 * Returns a comma-separated string of this product's tag names
	 * @since 5.0.0
	 */
	public function get_tag_names() {

		$tags = '';

		foreach ( $this->tags as $tag ) {

			$tags .= $tag->name . ',';
		}

		return trim( $tags, ',' );
	}

	/**
	 * Returns the URL of the main image for this product
	 * @since 5.0.0
	 */
	public function get_main_image_url() {
		global $ewd_upcp_controller;

		if ( $this->external_image ) {

			$image_url = $this->external_image_url;
		}
		elseif ( $attachment_id = get_post_thumbnail_id( $this->post ) ) {

			$image_url = ( $ewd_upcp_controller->settings->get_setting( 'thumbnail-support' ) and ! $ewd_upcp_controller->is_single_product ) ? wp_get_attachment_image_src( $attachment_id, 'medium' )[0] : wp_get_attachment_image_src( $attachment_id, 'full' )[0];
		}
		else {

			$image_url = EWD_UPCP_PLUGIN_URL . '/assets/img/No-Photo-Available.png';
		}

		return $image_url;
	}

	/**
	 * Returns an array containing all of the images for this product
	 * @since 5.0.0
	 */
	public function get_all_images() {
		global $ewd_upcp_controller;

		$main_image = array(
			(object) array(
				'url' 			=> $this->get_main_image_url(),
				'description'	=> $this->name . ' main image'
			)
		);

		if ( ! in_array( 'videos', $ewd_upcp_controller->settings->get_setting( 'extra-elements' ) ) ) { return array_merge( $main_image, $this->images ); }

		$video_images = array();

		foreach ( $this->get_videos() as $key => $video ) {

			$video_images[] = (object) array(
				'url' 			=> 'https://img.youtube.com/vi/' . $video->url . '/default.jpg',
				'embed_url'		=> $video->embed_url,
				'description'	=> 'YouTube product video',
				'video_key'		=> $key + 1
			);
		}

		return array_merge( $main_image, $this->images, $video_images );
	}

	/**
	 * Returns an img tag for this product
	 * @since 5.0.0
	 */
	public function get_image() {
		global $ewd_upcp_controller;

		$image_url = $this->get_main_image_url();

		return '<img class="ewd-upcp-product-image" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $this->name ) . '-image" />';
	}

	/**
	 * Returns a number of related product IDs based on the current product's categories
	 * @since 5.0.0
	 */
	public function get_automatic_related_products() {

		$current_category = wp_get_object_terms( $this->ID, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, array( 'fields' => 'names' ) );

		$args = array( 
			'numberposts' 	=> 5, 
			'post__not_in' 	=> array( $this->ID ),
			'post_type'		=> EWD_UPCP_PRODUCT_POST_TYPE,
			'tax_query' => array(
				array(
					'taxonomy' => EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
					'field' => 'name', 
					'terms' => $current_category,
					'include_children' => false
				)
			)
		);

		$related_posts = get_posts( $args );

		$related_IDs = array();

		foreach ( $related_posts as $related_post ) {

			$related_IDs[] = $related_post->ID;
		}

		return $related_IDs;
	}

	/**
	 * Returns the next product based on the current product's ID
	 * @since 5.0.0
	 */
	public function get_automatic_next_product() {
		global $post; 

		$post = $this->post;

		setup_postdata( $post );

		$next_post = get_next_post();

		wp_reset_postdata();

		return is_object( $next_post ) ? $next_post->ID : 0;
	}

	/**
	 * Returns the previous product based on the current product's ID
	 * @since 5.0.0
	 */
	public function get_automatic_previous_product() {
		global $post; 

		$post = $this->post;

		setup_postdata( $post );

		$previous_post = get_previous_post();

		wp_reset_postdata();
		
		return is_object( $previous_post ) ? $previous_post->ID : 0;
	}

	/**
	 * Gets the average rating for this product from 'Ultimate Reviews' reviews
	 * @since 5.0.0
	 */
	public function get_average_product_rating() {

		if ( ! isset( $this->rating ) ) { $this->set_average_product_rating(); }

    	return $this->rating;
	}

	/**
	 * Sets the average rating for this product from 'Ultimate Reviews' reviews
	 * @since 5.0.0
	 */
	public function set_average_product_rating() {
		global $wpdb;

		$post_id_results = $wpdb->get_results( $wpdb->prepare(
    		"SELECT $wpdb->posts.ID
    	    FROM $wpdb->posts
    	    INNER JOIN $wpdb->postmeta on $wpdb->posts.ID=$wpdb->postmeta.post_id
    	    WHERE $wpdb->postmeta.meta_key='EWD_URP_Product_Name'
    	    AND $wpdb->postmeta.meta_value='%s'
    	    AND $wpdb->posts.post_type = 'urp_review'", 
    	    $this->name
    	) );

    	if ( empty( $post_id_results ) ) { 

    		$this->rating = 0;

    		return false; 
    	}

    	$post_ids = array();

    	foreach ( $post_id_results as $post_id_result ) { 

    		$post_ids[] = $post_id_result->ID;
    	}

		$this->rating = $wpdb->get_var( $wpdb->prepare(
    		"SELECT AVG( meta_value )
    		FROM $wpdb->postmeta
    		WHERE meta_key = 'EWD_URP_Overall_Score'
    		AND post_id IN (%s)",
    		implode( ',', $post_ids )
    	) );
	}
}
} // endif;
