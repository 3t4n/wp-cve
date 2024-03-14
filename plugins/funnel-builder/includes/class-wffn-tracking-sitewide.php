<?php

/**
 * Class WFFN_Ecomm_Tracking_SiteWide
 */

if ( ! class_exists( 'WFFN_Tracking_SiteWide' ) ) {
	class WFFN_Tracking_SiteWide extends WFFN_Ecomm_Tracking_Common {
		public $api_events = [];
		public $gtag_rendered = false;
		private static $ins = null;
		private $pending_events = [];

		public function __construct() {
			$this->admin_general_settings = BWF_Admin_General_Settings::get_instance();
		}

		public function get_pending_events() {
			return $this->pending_events;
		}

		public function add_to_cart_process( $cart_item_key, $product_id, $quantity, $variation_id ) {

			if ( 0 < did_action( 'wfacp_after_checkout_page_found' ) ) {
				return;
			}

			if ( $this->is_fb_pixel() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_add_to_cart_global' ) ) ) {

				$this->pending_events['pixel'][0] = array(
					'event'    => 'AddToCart',
					'data'     => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'pixel' ),
					'event_id' => $this->get_event_id( 'AddToCart' )
				);


			}

			if ( $this->ga_code() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_add_to_cart_global' ) ) ) {
				$this->pending_events['ga'][0] = array(
					'event' => 'add_to_cart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'google_ua' ),

				);

			}

			if ( $this->gad_code() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_add_to_cart_global' ) ) ) {


				$this->pending_events['gad'][0] = array(
					'event' => 'add_to_cart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'google_ads' ),

				);


			}


			if ( $this->is_pint_pixel() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_add_to_cart_global' ) ) ) {


				$this->pending_events['pint'][0] = array(
					'event' => 'AddToCart',
					'data'  => $this->get_pint_add_to_cart_prams( $product_id, $variation_id, $quantity ),
				);


			}

			if ( '' !== $this->admin_general_settings->get_option( 'tiktok_pixel' ) && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_add_to_cart_global' ) ) ) {


				$this->pending_events['tiktok'][0] = array(
					'event' => 'AddToCart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'tiktok' ),
				);


			}

			if ( '' !== $this->admin_general_settings->get_option( 'snapchat_pixel' ) && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_add_to_cart_global' ) ) ) {


				$this->pending_events['snapchat'][0] = array(
					'event' => 'ADD_CART',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'snapchat' ),
				);


			}


		}

		public function get_add_to_cart_prams( $product_id, $variation_id, $quantity, $mode ) {
			if ( ! empty( $variation_id ) && $variation_id > 0 && ( $this->do_treat_variable_as_simple( $mode ) ) ) {
				$_product_id = $variation_id;
			} else {
				$_product_id = $product_id;
			}

			$categories = '';

			$product_obj = wc_get_product( $_product_id );
			$price       = apply_filters( 'wffn_add_to_cart_tracking_price', $product_obj->get_price(), $product_obj, $variation_id, $quantity, $mode, $this->admin_general_settings );

			if ( $product_obj->get_type() === 'product_variation' ) {
				$cat_post_id = $product_obj->get_parent_id(); // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product_obj->get_id() );
			}

			if ( count( $cat_list ) ) {
				$categories = implode( ', ', $cat_list );
			}

			$event_data = [
				'value'        => $price,
				'content_name' => $product_obj->get_name(),
				'content_type' => 'product',
				'currency'     => get_woocommerce_currency(),
				'content_ids'  => [ $this->get_woo_product_content_id( $product_id, $mode ) ],
				'contents'     => [
					[
						'id'         => $this->get_woo_product_content_id( $product_id, $mode ),
						'item_price' => $price,
						'quantity'   => $quantity,
						'value'      => $price,
					],
				],
				'user_roles'   => $this->get_current_user_role(),
			];

			if ( ( 'pixel' === $mode ) ) {
				unset( $event_data['contents'][0]['value'] );

				if ( true !== $this->is_fb_enable_content_on() ) {
					unset( $event_data['content_ids'] );
					unset( $event_data['contents'] );
				}

			}


			if ( 'tiktok' === $mode ) {
				$event_data['content_id'] = $this->get_woo_product_content_id( $product_id, $mode );
				$event_data['price']      = $price;
				$event_data['quantity']   = $quantity;
				unset( $event_data['content_ids'] );
				unset( $event_data['user_roles'] );
				unset( $event_data['contents'] );
			}

			if ( 'snapchat' === $mode ) {
				$event_data['number_items']  = count( $event_data['content_ids'] );
				$event_data['item_ids']      = $event_data['content_ids'];
				$event_data['price']         = $price;
				$event_data['item_category'] = $categories;
				unset( $event_data['content_ids'] );
				unset( $event_data['user_roles'] );
				unset( $event_data['contents'] );
			}

			if ( 'pint' === $mode ) {
				$event_data['product_id']       = $this->get_woo_product_content_id( $product_id, $mode );
				$event_data['product_name']     = $product_obj->get_name();
				$event_data['product_price']    = $price;
				$event_data['product_quantity'] = $quantity;
			}

			if ( 'google_ua' === $mode ) {
				if ( function_exists( 'wc_get_price_to_display' ) && absint( $quantity ) > 1 ) {
					$price = (float) wc_get_price_to_display( $product_obj, array( 'qty' => $quantity, 'price' => $price ) );
				}
				$event_data['items'][0]['id']       = $product_id;
				$event_data['items'][0]['name']     = $product_obj->get_name();
				$event_data['items'][0]['category'] = $categories;
				$event_data['items'][0]['quantity'] = $quantity;
				$event_data['items'][0]['price']    = $price;
				if ( $this->is_ga4_tracking() ) {
					$event_data['items'][0]['item_id']   = $event_data['items'][0]['id'];
					$event_data['items'][0]['item_name'] = $event_data['items'][0]['name'];
					$event_data['items'][0]['currency']  = get_woocommerce_currency();
					unset( $event_data['items'][0]['id'] );
					unset( $event_data['items'][0]['name'] );
					unset( $event_data['event_category'] );
					unset( $event_data['ecomm_pagetype'] );
					unset( $event_data['ecomm_prodid'] );
					unset( $event_data['ecomm_totalvalue'] );

				}
			}

			return $event_data;
		}

		/**
		 * @return WFFN_Tracking_SiteWide|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function should_render() {
			return false;
		}

		public function get_user_email() {
			$current_user = wp_get_current_user();

			// not logged in
			if ( empty( $current_user ) || $current_user->ID === 0 ) {
				return '';
			}

			return $current_user->user_email;
		}


		public function track_event_data() {

			$pixel          = $this->admin_general_settings->get_option( 'fb_pixel_key' );
			$ga             = $this->admin_general_settings->get_option( 'ga_key' );
			$gad            = $this->admin_general_settings->get_option( 'gad_key' );
			$tiktok         = $this->admin_general_settings->get_option( 'tiktok_pixel' );
			$pinterest      = $this->admin_general_settings->get_option( 'pint_key' );
			$pint_event     = $this->admin_general_settings->get_option( 'is_pint_page_view_global' );
			$pint_visit     = $this->admin_general_settings->get_option( 'is_pint_page_visit_global' );
			$snapchat       = $this->admin_general_settings->get_option( 'snapchat_pixel' );
			$pixel_event    = $this->admin_general_settings->get_option( 'is_fb_page_view_global' );
			$pixel_view     = $this->admin_general_settings->get_option( 'is_fb_page_product_content_global' );
			$tiktok_view    = $this->admin_general_settings->get_option( 'is_tiktok_page_product_content_global' );
			$ga_event       = $this->admin_general_settings->get_option( 'is_ga_page_view_global' );
			$ga_view_item   = $this->admin_general_settings->get_option( 'is_ga_view_item_global' );
			$gad_event      = $this->admin_general_settings->get_option( 'is_gad_page_view_global' );
			$gad_view_item  = $this->admin_general_settings->get_option( 'is_gad_view_item_global' );
			$tiktok_event   = $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' );
			$snapchat_event = $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' );


			$data = [
				'pixel'          => [
					'id'             => $pixel,
					'settings'       => [
						'page_view' => $pixel_event,
					],
					'data'           => [],
					'conversion_api' => $this->is_conversion_api(),
					'fb_advanced'    => WFFN_Common::pixel_advanced_matching_data(),
				],
				'ga'             => [
					'id'       => $ga,
					'settings' => [
						'page_view' => $ga_event,
					],
					'data'     => []
				],
				'gad'            => [
					'id'       => $gad,
					'settings' => [
						'page_view' => $gad_event,
					],
					'data'     => []
				],
				'tiktok'         => [
					'id'       => $tiktok,
					'settings' => [
						'page_view' => $tiktok_event,

					],
					'data'     => [],
					'advanced' => WFFN_Common::tiktok_advanced_matching_data(),
				],
				'pint'           => [
					'id'       => $pinterest,
					'settings' => [
						'page_view' => $pint_event,

					],
					'data'     => []
				],
				'snapchat'       => [
					'id'       => $snapchat,
					'settings' => [
						'page_view'  => $snapchat_event,
						'user_email' => $this->get_user_email(),
					],
					'data'     => []
				],
				'ajax_endpoint'  => admin_url( 'admin-ajax.php' ),
				'pending_events' => $this->pending_events,
				'should_render'  => apply_filters( 'wffn_allow_site_wide_tracking_js', true ),

			];

			if ( true === wffn_string_to_bool( $pixel_view ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['pixel']['settings']['view_content'] = $pixel_view;
				$data['pixel']['content_data']             = $this->get_add_to_product_data();
			}

			if ( true === wffn_string_to_bool( $tiktok_view ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['tiktok']['settings']['view_content'] = $tiktok_view;
				$data['tiktok']['content_data']             = $this->get_add_to_product_data();
			}

			if ( true === wffn_string_to_bool( $pint_visit ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['pint']['settings']['view_content'] = $pint_visit;
				$data['pint']['content_data']             = $this->pint_content_data();
			}

			if ( true === wffn_string_to_bool( $gad_view_item ) && is_array( $this->get_view_items_data( 'gad' ) ) && count( $this->get_view_items_data( 'gad' ) ) > 0 ) {
				$data['gad']['settings']['view_content']  = $gad_view_item;
				$data['gad']['content_data']['view_item'] = $this->get_view_items_data( 'gad' );
			}

			if ( true === wffn_string_to_bool( $ga_view_item ) && is_array( $this->get_view_items_data( 'ga' ) ) && count( $this->get_view_items_data( 'ga' ) ) > 0 ) {
				$data['ga']['settings']['view_content']  = $ga_view_item;
				$data['ga']['content_data']['view_item'] = $this->get_view_items_data( 'ga' );
			}

			return $data;
		}

		public function tracking_script() {
			$live_or_dev = 'live';
			$suffix      = '.min';

			if ( defined( 'WFFN_IS_DEV' ) && true === WFFN_IS_DEV ) {
				$live_or_dev = 'dev';
				$suffix      = '';
			}
			$instance = BWF_Admin_General_Settings::get_instance();

			if ( false === $this->should_render_global() ) {
				return false;
			}

			if ( false === apply_filters( 'wffn_allow_site_wide_tracking', true, $this->should_render_global(), $instance ) ) {
				return false;
			}

			if ( class_exists( 'WFACP_Core' ) && wffn_is_wc_active() && ! empty( WFACP_Core()->public ) && WFACP_Core()->public->is_native_checkout() ) {
				return false;
			}

			if ( wffn_is_wc_active() && is_order_received_page() ) {
				return false;
			}

			wp_enqueue_script( 'wffn-tracking', plugin_dir_url( WFFN_PLUGIN_FILE ) . 'assets/' . $live_or_dev . '/js/tracks' . $suffix . '.js', [ 'jquery' ], WFFN_VERSION_DEV, false );
			wp_localize_script( 'wffn-tracking', 'wffnTracking', $this->track_event_data() );

		}

		public function should_render_global() {

			/** Landing page check */
			$landing_ins = WFFN_Core()->landing_pages;
			if ( $landing_ins instanceof WFFN_Landing_Pages && $landing_ins->is_wflp_page() ) {
				return false;
			}

			if ( function_exists( 'WFOPP_Core' ) ) {
				/** Optin page check */
				$optin_ins = WFOPP_Core()->optin_pages;
				if ( $optin_ins instanceof WFFN_Optin_Pages && $optin_ins->is_wfop_page() ) {
					return false;
				}

				/** Optin thank you page check */
				$optin_ty_ins = WFOPP_Core()->optin_ty_pages;
				if ( $optin_ty_ins instanceof WFFN_Optin_TY_Pages && $optin_ty_ins->is_wfoty_page() ) {
					return false;
				}
			}

			if ( function_exists( 'WFOCU_Core' ) ) {
				/** Upsell page check */
				$upsell_ins = WFOCU_Core()->public;
				if ( $upsell_ins instanceof WFOCU_Public && $upsell_ins->if_is_offer() ) {
					return false;
				}
			}

			/** WC thankyou page check */
			$thank_you_ins = WFFN_Core()->thank_you_pages;
			if ( $thank_you_ins instanceof WFFN_Thank_You_WC_Pages && $thank_you_ins->is_wfty_page() ) {
				return false;
			}

			if ( did_action( 'wfacp_after_template_found' ) ) {
				return false;
			}

			/**
			 * IF Fb not set to fire global AND
			 * IF GA not set to fire global AND
			 * IF GAD not set to fire global AND
			 * IF SNAPCHAT not set to fire global
			 */
			$fb       = ( false === $this->is_fb_pixel() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_add_to_cart_global' ) ) ) );
			$ga       = ( false === $this->ga_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_add_to_cart_global' ) ) ) );
			$gad      = ( false === $this->gad_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_add_to_cart_global' ) ) ) );
			$snapchat = ( false === $this->snapchat_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_add_to_cart_global' ) ) ) );
			$pint     = ( false === $this->is_pint_pixel() || false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_add_to_cart_global' ) ) );
			$tiktok   = ( false === $this->tiktok_code() || false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_add_to_cart_global' ) ) );
			if ( $fb && $ga && $gad && $snapchat && $pint && $tiktok ) {
				return false;
			}

			return true;
		}

		public function get_add_to_product_data() {
			global $post;
			$event_data = array();

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			$event_data = [
				'content_type'   => $product->get_type(),
				'user_role'      => $this->get_current_user_role(),
				'event_url'      => $this->getRequestUri(),
				'category_name'  => '',
				'currency'       => get_woocommerce_currency(),
				'value'          => $product->get_price(),
				'content_name'   => $product->get_title(),
				'content_ids'    => [ $this->get_woo_product_content_id( $product->get_id(), 'pixel' ) ],
				'product_price'  => $product->get_price(),
				'post_id'        => $post->ID,
				'landing_page'   => $this->get_traffic_source( 'referrer' ),
				'contents'       => [
					[
						'id'       => $this->get_woo_product_content_id( $product->get_id(), 'pixel' ),
						'quantity' => ( null !== $product->get_stock_quantity() ) ? $product->get_stock_quantity() : 1,
					],
				],
				'traffic_source' => $this->get_traffic_source( 'source' ),
			];

			if ( isset( $_COOKIE['wffn_referrer'] ) ) {
				$event_data['landing_page'] = wffn_clean( $_COOKIE['wffn_referrer'] );
			}

			$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
			if ( count( $tag_list ) ) {
				$event_data['tags'] = implode( ', ', $tag_list );
			}

			if ( $post->post_type === 'product_variation' ) {
				$cat_post_id = $post->post_parent; // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( count( $cat_list ) ) {
				$event_data['category_name'] = implode( ', ', $cat_list );
			}

			return $event_data;
		}

		/**
		 * @return array
		 */
		public function pint_content_data() {
			global $post;
			$event_data = array();

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			$event_data = [
				'post_type'      => 'product',
				'product_id'     => $this->get_woo_product_content_id( $product->get_id(), 'pint' ),
				'product_price'  => $product->get_price(),
				'content_name'   => $product->get_name(),
				'value'          => $product->get_price(),
				'currency'       => get_woocommerce_currency(),
				'page_title'     => $post->post_title,
				'post_id'        => $post->ID,
				'event_url'      => $this->getEventRequestUri(),
				'user_role'      => $this->get_current_user_role(),
				'traffic_source' => $this->get_traffic_source( 'source' ),
			];

			if ( isset( $_COOKIE['wffn_referrer'] ) ) {
				$event_data['landing_page'] = wffn_clean( $_COOKIE['wffn_referrer'] );
			}

			$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
			if ( count( $tag_list ) > 0 ) {
				$event_data['tags'] = implode( ', ', $tag_list );
			}

			if ( $post->post_type === 'product_variation' ) {
				$cat_post_id = $post->post_parent; // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( count( $cat_list ) > 0 ) {
				$event_data['category_name'] = implode( ', ', $cat_list );
			}

			return $event_data;
		}

		public function get_pint_add_to_cart_prams( $product_id, $variation_id, $quantity ) {
			$mode = 'pint';
			if ( ! empty( $variation_id ) && $variation_id > 0 && ( $this->do_treat_variable_as_simple( $mode ) ) ) {
				$_product_id = $variation_id;
			} else {
				$_product_id = $product_id;
			}

			$product = wc_get_product( $_product_id );
			$price   = apply_filters( 'wffn_add_to_cart_tracking_price', $product->get_price(), $product, $variation_id, $quantity, $mode, $this->admin_general_settings );

			$event_data = [
				'post_type'        => 'product',
				'product_id'       => $this->get_woo_product_content_id( $product_id, $mode ),
				'product_quantity' => $quantity,
				'content_name'     => $product->get_name(),
				'value'            => $price,
				'currency'         => get_woocommerce_currency(),
				'product_price'    => $price,
				'page_title'       => $product->get_name(),
				'post_id'          => $product_id,
				'event_url'        => $this->getEventRequestUri(),
				'user_role'        => $this->get_current_user_role(),
				'traffic_source'   => $this->get_traffic_source( 'source' ),
				'eventID'          => $this->get_event_id( 'AddToCart' )
			];

			$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
			if ( count( $tag_list ) > 0 ) {
				$event_data['tags'] = implode( ', ', $tag_list );
			}

			if ( $product->get_type() === 'product_variation' ) {
				$cat_post_id = $product->get_parent_id(); // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( count( $cat_list ) > 0 ) {
				$event_data['category_name'] = implode( ', ', $cat_list );
			}

			return $event_data;
		}

		public function get_product_tags( $taxonomy, $post_id ) {

			$terms   = get_the_terms( $post_id, $taxonomy );
			$results = array();

			if ( is_wp_error( $terms ) || empty ( $terms ) ) {
				return array();
			}

			// decode special chars
			foreach ( $terms as $term ) {
				$results[] = html_entity_decode( $term->name );
			}

			return $results;

		}

		public function get_view_items_data( $mode ) {
			global $post;
			$event_data = array();
			$categories = '';

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			if ( $product->get_type() === 'product_variation' ) {
				$cat_post_id = $product->get_parent_id(); // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( is_array( $cat_list ) && count( $cat_list ) > 0 ) {
				$categories = implode( '/', $cat_list );
			}

			$event_data = array(
				'event_category'   => 'ecommerce',
				'ecomm_prodid'     => $this->get_woo_product_content_id( $product->get_id(), $mode ),
				'ecomm_pagetype'   => 'product',
				'ecomm_totalvalue' => $product->get_price(),
				'items'            => array(
					array(
						'id' => $this->get_woo_product_content_id( $product->get_id(), $mode ),
					)
				),
			);

			if ( 'gad' === $mode ) {
				$event_data['page_title'] = $post->post_title;
				$event_data['post_id']    = $post->ID;
				$event_data['post_type']  = 'product';
				$event_data['value']      = $product->get_price();
			}

			if ( 'ga' === $mode ) {
				$event_data['items'][0]['name']          = $product->get_name();
				$event_data['items'][0]['category']      = $categories;
				$event_data['items'][0]['quantity']      = 1;
				$event_data['items'][0]['list_position'] = 1;
				$event_data['items'][0]['price']         = $product->get_price();
				if ( $this->is_ga4_tracking() ) {
					$event_data['items'][0]['item_id']   = $event_data['items'][0]['id'];
					$event_data['items'][0]['item_name'] = $event_data['items'][0]['name'];
					$event_data['items'][0]['currency']  = get_woocommerce_currency();
					$event_data['post_id']               = $post->ID;
					$event_data['post_type']             = "product";
					$event_data['items'][0]['index']     = 0;
					$count                               = 1;
					if ( is_array( $cat_list ) && count( $cat_list ) > 0 ) {
						foreach ( $cat_list as $cat_name ) {
							if ( 1 === $count ) {
								$event_data['items'][0]['item_category'] = $cat_name;

							} else {
								$event_data['items'][0][ 'item_category' . $count ] = $cat_name;
							}
							$count ++;
						}
					}
					unset( $event_data['items'][0]['id'] );
					unset( $event_data['items'][0]['name'] );
					unset( $event_data['event_category'] );
					unset( $event_data['ecomm_pagetype'] );
					unset( $event_data['ecomm_prodid'] );
					unset( $event_data['ecomm_totalvalue'] );

				}
			}

			return $event_data;

		}

		public function is_ga4_tracking() {
			$is_ga4_tracking = $this->admin_general_settings->get_option( 'is_ga4_tracking' );
			if ( is_array( $is_ga4_tracking ) && count( $is_ga4_tracking ) > 0 && 'yes' === $is_ga4_tracking[0] ) {
				return true;
			}

			return false;
		}

		public function get_traffic_source( $type = 'source' ) {
			$referrer = "";
			$source   = "";

			$referrer = wp_get_referer();

			if ( 'referrer' === $type ) {
				return $referrer;
			}

			if ( empty( $referrer ) ) {
				$external = false;
			} else {
				$external = strpos( site_url(), $referrer ) == 0; //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			}

			if ( ! $external ) {
				$source = 'direct';
			} else {
				$source = $referrer;
			}

			if ( $source !== 'direct' ) {
				$parse = wp_parse_url( $source );
				if ( isset( $parse['host'] ) ) {
					return $parse['host'];// leave only domain (Issue #70)
				} else {
					return "direct";
				}
			}

			return $source;
		}

		public function get_current_user_role() {
			if ( is_user_logged_in() ) {
				if ( is_super_admin() ) {
					return 'administrator';
				} else {
					return 'customer';
				}
			}

			return 'guest';
		}

		final public function number_format( $value, $format_count = 2 ) {

			$output = number_format( floatval( $value ), wc_get_price_decimals(), '.', '' );

			return apply_filters( 'bwf_analytics_number_format', $output, $value, $format_count, $this );
		}


	}


}

