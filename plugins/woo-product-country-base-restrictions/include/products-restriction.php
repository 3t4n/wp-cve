<?php
/**
 * CBR Setting 
 *
 * @class   CBR_Product_Restriction
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_Product_Restriction class
 *
 * @since 1.0.0
 */
class CBR_Product_Restriction {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return CBR_Product_Restriction
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	* Construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->init();
	}

	/*
	* Init function
	*
	* @since 1.0.0
	*/
	public function init() {

		//callback on activate plugin
		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		
		//hook for geolocation_update_database	
		add_filter( 'woocommerce_maxmind_geolocation_update_database_periodically', array($this, 'update_geo_database'), 10, 1 );
		
		if ( 'hide_completely' == get_option('product_visibility') || ( '1' == get_option('wpcbr_make_non_purchasable') && 'hide_catalog_visibility' == get_option('product_visibility') ) || 'show_catalog_visibility' == get_option('product_visibility') ) {				
			add_filter( 'woocommerce_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
			add_filter( 'woocommerce_variation_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
			add_filter( 'woocommerce_available_variation', array( $this, 'variation_filter' ), 10, 3 );
			
			//subscription variation item if that is restricted
			add_filter('woocommerce_subscription_variation_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
		}
		
		$position = get_option('wpcbr_message_position', 33 );
		if ('custom_shortcode' == $position) {
			//message position shortcode function for Elementor product
			add_shortcode('cbr_message_position', array( $this, 'cbr_message_position_func') );
		} else {
			add_action( 'woocommerce_single_product_summary', array( $this, 'meta_area_message' ), $position );
		} 
		
		//hook for pre_get_posts	
		add_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
		
		//hooks for cart item message
		add_filter('woocommerce_cart_item_removed_message', array( $this, 'cart_item_removed_massage'), 10 , 2);
		
		//callback for redirect 404 error page
		add_action( 'template_redirect', array( $this, 'redirect_404_to_homepage' ));
		
		//callback for cart page
		add_action( 'woocommerce_calculated_shipping', array( $this, 'update_cart_and_checkout_items'), 10 );
		
		//callback for checkout page
		add_action( 'woocommerce_review_order_after_shipping', array( $this, 'update_cart_and_checkout_items' ), 10 );
		
		//Query args modify for Visual Composser products of restricted
		add_filter( 'woocommerce_shortcode_products_query', array( $this,  'vc_product_restricted_query'), 10, 1 );			
		
		add_filter( 'woocommerce_related_products',	array( $this,  'restricted_related_products'), 10, 3 );
		
		add_filter( 'woocommerce_product_get_upsell_ids', array( $this, 'restricted_upsell_products'), 10, 2 ); 

	}
	
	/**
	 * WC_Geolocation database update hooks
	 *
	 * @since 1.0.0
	 */
	public function on_activation() {
		WC_Geolocation::update_database();                     
	}
	
	/**
	 * Update geo database
	 *
	 * @since 1.0.0
	 */
	public function update_geo_database() {
		return true;
	}
	
	/*
	* Check restricted by the product id for simple product
	*
	* @since 1.0.0
	*/
	public function is_restricted_by_id( $id ) {
		$restriction = get_post_meta( $id, '_fz_country_restriction_type', true );

		if ( 'specific' == $restriction || 'excluded' == $restriction ) {
			$countries = get_post_meta( $id, '_restricted_countries', true );
			if ( empty( $countries ) || ! is_array( $countries ) ) {
				$countries = array();
			}

			$customercountry = $this->get_country();

			if ( 'specific' == $restriction && !in_array( $customercountry, $countries ) ) {
				return true;
			}

			if ( 'excluded' == $restriction && in_array( $customercountry, $countries ) ) {
				return true;
			}
		}

		return false;
	}
	
	/*
	* Check restricted by the product id for variation
	*
	* @since 1.0.0
	*/
	public function is_restricted( $product ) {
		
		if ( $product ) { 
			$id = $product->get_id();
		}
		
		if ( ( $product ) && ( 'variation' == $product->get_type()  || 'variable-subscription' == $product->get_type() || 'subscription_variation' == $product->get_type() ) ) {
			$parentid = $product->get_parent_id();
			$parentRestricted = $this->is_restricted_by_id( $parentid );
			if ( $parentRestricted ) {
				return true;
			}
		}
		return $this->is_restricted_by_id($id);
	}
	
	/*
	* Check product is_purchasable or not
	*
	* @since 1.0.0
	*/
	public function is_purchasable( $purchasable, $product ) {		
		
		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			$purchasable = false;
		}	
			
		return $purchasable;
	}
	
	/*
	* Check variation product filter for restriction
	*
	* @since 1.0.0
	*/
	public function variation_filter( $data, $product, $variation ) {
		if ( ! $data['is_purchasable'] ) {
			$data['variation_description'] = $this->no_soup_for_you() . $data['variation_description'];
			if ( '1' == get_option('wpcbr_hide_restricted_product_variation') ) {
				$data['variation_is_active'] = '';
			}
		}
		return $data;
	}
	
	/*
	* Message position shortcode support for Elementor product
	*
	* @since 1.0.0
	*/
	public function cbr_message_position_func() {
		ob_start();
		$this->meta_area_message();
		return ob_get_clean();
	}
	
	/*
	* Cbr add default_message for restricted product
	*
	* @since 1.0.0
	*/
	public function meta_area_message() {
		global $product;

		if ( $this->is_restricted($product) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			if ( !$product->is_purchasable() ) {
				echo $this->no_soup_for_you();
			}
		}
	}

	/*
	* Get default_message for restricted product
	*
	* @since 1.0.0
	*/
	public function default_message() {
		
		return	apply_filters( 'cbr_restricted_product_message', __( 'Sorry, this product is not available to purchase in your country.', 'woo-product-country-base-restrictions' ) );
	}        
	
	/*
	* get custom message for restricted product
	*
	* @since 1.0.0
	*/
	public function no_soup_for_you() {
		$msg = get_option('wpcbr_default_message', $this->default_message());
		if (empty($msg)) { 
			$msg = $this->default_message();
		}
		return "<p class='restricted_country'>" . stripslashes($msg) . '</p>';
	}
	
	/*
	* Get country
	*
	* @since 1.0.0
	*/
	public function get_country() {
		
		if ( get_option('wpcbr_debug_mode') && is_admin() ) {
			$cookie_country = isset($_COOKIE['country']) ? sanitize_text_field($_COOKIE['country']) : '';
			if ( !empty( $cookie_country ) ) {
				$user_country = $cookie_country;
				return $user_country;
			}
		}
		
		$force_geoloaction = get_option('wpcbr_force_geo_location');
		if ( !$force_geoloaction ) {			
			global $woocommerce;
			if ( isset($woocommerce->customer) ) {
				$shipping_country = $woocommerce->customer->get_shipping_country();
				$cookie_country = !empty($_COOKIE['country']) ? sanitize_text_field($_COOKIE['country']) : $shipping_country;
				if ( isset($cookie_country) ) {
					$user_country = $cookie_country;
					return $user_country;
				}
			}
		}				
		
		if ( empty( $user_country ) ) {
			$geoloc = WC_Geolocation::geolocate_ip();
			$cookie_country = !empty($_COOKIE['country']) ? sanitize_text_field($_COOKIE['country']) : $geoloc['country'];
			$user_country = $cookie_country;
			return $user_country;
		}
		
		return $user_country;
	}
	
	/*
	* Posts & category set NOT_IN and IN by query modified
	*
	* @since 1.0.0
	*/
	public function product_by_country_pre_get_posts( $query ) {
		
		if ( is_admin() ) {
			return;
		}
		
		// when post_type is not product or not a category/shop page return 
		if ( isset($query->query_vars['post_type']) && 'product' != $query->query_vars['post_type'] && !isset( $query->query_vars['product_cat'] ) ) {
			return;
		}
		
		// shop, category, search = visible, single page visible
		if ( 'show_catalog_visibility' == get_option('product_visibility') ) {
			return;
		}
		
		// shop, category, search = hidden, single page visible
		if ( 'hide_catalog_visibility' == get_option('product_visibility') && 1 == $query->is_single ) {
			return;
		}
		
		//for hide completely continue
		remove_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
		
		$post__not_in = $query->get( 'post__not_in' );

		$args = $query->query_vars;
		$args['fields'] = 'ids';
		$args['posts_per_page'] = '-1';
		$loop = new WP_Query( $args );

		foreach ( $loop->posts as $product_id ) { 
			if ( $this->is_restricted_by_id( $product_id ) ) {
				$post__not_in[] = $product_id;
			}
		}
		$query->set( 'post__not_in', $post__not_in );
		if ( class_exists('SitePress') && isset($query->query_vars['p']) && in_array( $query->query_vars['p'], $post__not_in ) ) {
			$query->set( 'p', '0' );
		}

		do_action( 'cbr_pre_query', $query, $loop );
		
		add_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
	}
	
	
	/*
	* Query args modify for Visual Composser products of restricted
	*
	* @since 1.0.0
	* @Competibility with Visual Composser plugin
	*/
	public function vc_product_restricted_query( $query_args ) {

		if ( is_admin() ) {
			return $query_args;
		}
		
		// when post_type is not product or not a category/shop page return 
		if ( isset($query_args['post_type']) && 'product' != $query_args['post_type'] ) {
			return $query_args;
		}
		
		// shop, category, search = visible, single page visible
		if ( 'show_catalog_visibility' == get_option('product_visibility') ) {
			return $query_args;
		}
		
		// shop, category, search = hidden, single page visible
		if ( 'hide_catalog_visibility' == get_option('product_visibility') && is_product() ) {
			return $query_args;
		}
		
		$post__in = array();
		if ( isset($query_args['post__in']) ) {
			foreach ( $query_args['post__in'] as $product_id ) {
				if ( '1' != $this->is_restricted_by_id( $product_id )) {
					$post__in[] = $product_id;
				}
			}
		}
		if (!empty($post__in)) {
			$query_args['post__in'] = $post__in;
		}
		
		
		return $query_args;
	}
	
	/*
	* Removed restricted related products by the product id for single product page
	*
	* @since 1.0.0
	*/
	public function restricted_related_products( $related_posts, $product_id, $arg ) {

		if ( 'hide_completely' != get_option('product_visibility') ) {
			return $related_posts;
		}

		foreach ($related_posts as $key => $id) {
			if ($this->is_restricted_by_id($id)) {
				unset($related_posts[$key]);
			}
		}

		return $related_posts;
	}
	
	/*
	* Removed restricted upsell products for single product page
	*
	* @since 1.0.0
	*/
	public function restricted_upsell_products( $upsell_ids, $instance ) {
		
		if ('hide_completely' != get_option('product_visibility')) {
			return $upsell_ids;
		}
		
		foreach ( $upsell_ids as $key => $id ) {
			if ( $this->is_restricted_by_id($id) ) {
				unset($upsell_ids[$key]);
			}
		}
		
		return $upsell_ids;
	}
	
	
	/**
	 * Redirect 404 error page.
	 *
	 * @since 1.0.0
	 */
	public function redirect_404_to_homepage( $page_dir ) {
		if (is_404() && '1' == get_option('wpcbr_redirect_404_page')) {
			$redirect_page = get_option('wpcbr_choose_the_page_to_redirect', wc_get_page_id( 'shop' ));
			$page_dir = esc_url(get_permalink($redirect_page));
			wp_safe_redirect( $page_dir );
			exit;
		}
	}
	
	/**
	 * Cart item message update by filter
	 *
	 * @since 1.0.0
	 */
	public function cart_item_removed_massage( $message, $product ) {
		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			/* translators: %s: Error Message */
			$message = sprintf( __( '%s has been removed from your cart because it can no longer be purchased. Please contact us if you need assistance.', 'woocommerce' ), $product->get_name() );     
			$message = apply_filters( 'cbr_cart_message', $message, $product );
			$message = apply_filters( 'cbr_cart_item_removed_message', $message, $product );
		}
		return $message;
	}
	
	/*
	* update cart and checkout items list
	*
	* @since 1.0.0
	*/
	public function update_cart_and_checkout_items() {	
		global $woocommerce;
		$woocommerce->cart->get_cart_from_session();
	}
	
}

