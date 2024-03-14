<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class wfacp_AJAX_Controller
 * Handles All the request came from front end or the backend
 */
#[AllowDynamicProperties]
abstract class WFACP_AJAX_Controller {
	private static $bump_action_data = '';
	private static $output_resp = [];


	public static function init() {
		/**
		 * Backend AJAX actions
		 */
		if ( is_admin() ) {
			self::handle_admin_ajax();
		}
		self::handle_public_ajax();

	}


	public static function handle_admin_ajax() {
		add_action( 'wp_ajax_wfacp_save_global_settings', [ __CLASS__, 'save_global_settings' ] );
		add_action( 'wp_ajax_wfacp_preview_details', [ __CLASS__, 'preview_details' ] );
		add_action( 'wp_ajax_wfacp_add_checkout_page', [ __CLASS__, 'add_checkout_page' ] );
		add_action( 'wp_ajax_wfacp_update_page_status', [ __CLASS__, 'update_page_status' ] );
		add_action( 'wp_ajax_wfacp_add_product', [ __CLASS__, 'add_product' ] );
		add_action( 'wp_ajax_wfacp_remove_product', [ __CLASS__, 'remove_product' ] );
		add_action( 'wp_ajax_wfacp_product_search', [ __CLASS__, 'product_search' ] );
		add_action( 'wp_ajax_wfacp_save_products', [ __CLASS__, 'save_products' ] );
		add_action( 'wp_ajax_wfacp_save_layout', [ __CLASS__, 'save_layout' ] );
		add_action( 'wp_ajax_wfacp_save_design', [ __CLASS__, 'save_design' ] );
		add_action( 'wp_ajax_wfacp_save_design_settings', [ __CLASS__, 'save_design_settings' ] );
		add_action( 'wp_ajax_wfacp_remove_design', [ __CLASS__, 'remove_design' ] );
		add_action( 'wp_ajax_wfacp_save_settings', [ __CLASS__, 'save_settings' ] );
		add_action( 'wp_ajax_wfacp_make_wpml_duplicate', [ __CLASS__, 'make_wpml_duplicate' ] );
		add_action( 'wp_ajax_wfacp_hide_notification', [ __CLASS__, 'hide_notification' ] );
		add_action( 'wp_ajax_wfacp_import_template', [ __CLASS__, 'import_template' ] );
		add_action( 'wp_ajax_wfacp_activate_plugin', [ __CLASS__, 'activate_plugin' ] );
		add_action( 'wp_ajax_notice_dismise_link', array( __CLASS__, 'notice_dismise_link' ) );
		add_action( 'wp_ajax_wfacp_update_edit_url', [ __CLASS__, 'update_edit_url' ] );
	}


	public static function handle_public_ajax() {

		add_action( 'woocommerce_checkout_update_order_review', [ __CLASS__, 'check_actions' ], - 10 );
		add_action( 'bwf_global_save_settings_wfacp', [ __CLASS__, 'update_global_settings_fields' ] );

		$endpoints = self::get_available_public_endpoints();
		foreach ( $endpoints as $action => $function ) {
			if ( method_exists( __CLASS__, $function ) ) {
				add_action( 'wc_ajax_' . $action, [ __CLASS__, $function ] );
			} else {
				do_action( 'wfacp_wc_ajax_' . $action, $function );
			}
		}
	}

	public static function check_actions( $data ) {
		if ( empty( $data ) ) {
			return;
		}
		parse_str( $data, $post_data );
		if ( empty( $post_data ) || ! isset( $post_data['wfacp_input_hidden_data'] ) || empty( $post_data['wfacp_input_hidden_data'] ) ) {
			return;
		}

		$bump_action_data = json_decode( $post_data['wfacp_input_hidden_data'], true );

		if ( empty( $bump_action_data ) ) {
			return;
		}
		$action = $bump_action_data['action'];

		/* fetching available payment method before modifying bump */
		$input_data = [];
		if ( isset( $bump_action_data['data'] ) ) {
			$input_data = $bump_action_data['data'];
		}
		if ( 'apply_coupon_field' == $action || 'apply_coupon_main' == $action ) {
			self::$output_resp = self::apply_coupon( $bump_action_data );
		} else if ( 'remove_coupon_field' == $action || 'remove_coupon_main' == $action ) {
			self::$output_resp = self::remove_coupon( $bump_action_data );
		} elseif ( method_exists( __CLASS__, $action ) ) {
			self::$output_resp = self::$action( $input_data );
		}
		$bump_action_data['wfacp_id'] = $_REQUEST['wfacp_id'];

		self::$bump_action_data        = $action;
		self::$output_resp['wfacp_id'] = $bump_action_data['wfacp_id'];
		//JS callback ID
		self::$output_resp['callback_id'] = isset( $bump_action_data['callback_id'] ) ? $bump_action_data['callback_id'] : '';

		add_filter( 'woocommerce_update_order_review_fragments', [ __CLASS__, 'merge_fragments' ], 999 );

	}


	public static function merge_fragments( $fragments ) {

		$data                         = [];
		$data['action']               = self::$bump_action_data;
		$data['analytics_data']       = WFACP_Common::analytics_checkout_data();
		$extra_data                   = WFACP_Common::ajax_extra_frontend_data();
		$data                         = array_merge( $extra_data, $data );
		$fragments['wfacp_ajax_data'] = array_merge( $data, self::$output_resp );

		return $fragments;
	}

	public static function get_available_public_endpoints() {
		$endpoints = [
			'wfacp_get_divi_form_data' => 'get_divi_form_data',
			'wfacp_analytics'          => 'analytics',
			'wfacp_check_email'        => 'check_email',
			'wfacp_refresh'            => 'refresh',
		];

		return apply_filters( 'wfacp_public_endpoints', $endpoints );
	}

	public static function get_public_endpoints() {
		$endpoints        = [];
		$public_endpoints = self::get_available_public_endpoints();
		if ( count( $public_endpoints ) > 0 ) {
			foreach ( $public_endpoints as $key => $function ) {
				$endpoints[ $key ] = WC_AJAX::get_endpoint( $key );
			}
		}

		return $endpoints;
	}

	/**
	 * Create new checkout page OR Update checkout page
	 */
	public static function add_checkout_page() {
		self::check_nonce( true );
		$resp = array(
			'msg'    => __( 'Checkout Page not found', 'funnel-builder' ),
			'status' => false,
		);
		if ( isset( $_POST['wfacp_name'] ) && $_POST['wfacp_name'] != '' ) {

			$post_title          = filter_input( INPUT_POST, 'wfacp_name', FILTER_UNSAFE_RAW );
			$post_name           = filter_input( INPUT_POST, 'post_name', FILTER_UNSAFE_RAW );
			$post_content        = filter_input( INPUT_POST, 'post_content', FILTER_UNSAFE_RAW );
			$post                = array();
			$post['post_title']  = ! is_null( $post_title ) ? $post_title : '';
			$post['post_type']   = WFACP_Common::get_post_type_slug();
			$post['post_status'] = 'publish';
			$post['post_name']   = ! is_null( $post_name ) ? $post_name : $post['post_title'];
			$post_description    = ! is_null( $post_content ) ? $post_content : '';
			if ( ! empty( $post ) ) {

				if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 ) {
					$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
					$status   = wp_update_post( [
						'ID'         => $wfacp_id,
						'post_title' => $post['post_title'],
						'post_name'  => $post['post_name'],
					] );
					if ( ! is_wp_error( $status ) ) {

						update_post_meta( $wfacp_id, '_post_description', $post_description );
						$resp['status']       = true;
						$resp['new_url']      = get_the_permalink( $wfacp_id );
						$resp['redirect_url'] = '#';
						$resp['msg']          = __( 'Checkout Page Successfully Update', 'funnel-builder' );
					}
					WFACP_Common::save_publish_checkout_pages_in_transient();
					self::send_resp( $resp );
				}
				$wfacp_id = wp_insert_post( $post );
				if ( $wfacp_id !== 0 && ! is_wp_error( $wfacp_id ) ) {

					$resp['status']       = true;
					$resp['redirect_url'] = add_query_arg( array(
						'page'     => 'wfacp',
						'section'  => 'design',
						'wfacp_id' => $wfacp_id,
					), admin_url( 'admin.php' ) );
					$resp['msg']          = __( 'Checkout Page Successfully Created', 'funnel-builder' );
					update_post_meta( $wfacp_id, '_wfacp_version', WFACP_VERSION );
					update_post_meta( $wfacp_id, '_post_description', $post_description );
					update_post_meta( $wfacp_id, '_wp_page_template', 'default' );

					WFACP_Common::save_publish_checkout_pages_in_transient();
				}
			}
		}
		self::send_resp( $resp );
	}

	/*
	 * Send Response back to checkout page builder
	 * With nonce security keys
	 * also delete transient of particular checkout page it page is found in request
	 */

	public static function check_nonce( $admin = false ) {
		$rsp = [
			'status' => 'false',
			'msg'    => 'Invalid Call',
		];
		if ( isset( $_POST['post_data'] ) ) {
			$post_data = [];
			parse_str( $_POST['post_data'], $post_data );
			if ( ! empty( $post_data ) ) {
				WFACP_Common::$post_data = $post_data;
			}
		}

		if ( true === $admin ) {
			if ( ! isset( $_REQUEST['wfacp_nonce'] ) || ! wp_verify_nonce( $_REQUEST['wfacp_nonce'], 'wfacp_admin_secure_key' ) ) {
				wp_send_json( $rsp );
			}

			return;
		}

		if ( ! isset( $_REQUEST['wfacp_nonce'] ) || ! wp_verify_nonce( $_REQUEST['wfacp_nonce'], 'wfacp_secure_key' ) ) {
			wp_send_json( $rsp );
		}
	}

	public static function send_resp( $data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = [];
		}

		if ( function_exists( 'WC' ) && WC()->cart instanceof WC_Cart && ! is_null( WC()->cart ) ) {
			$data['cart_total'] = WC()->cart->get_total( 'edit' );

			if ( class_exists( 'WC_Subscriptions_Cart' ) ) {
				$data['cart_contains_subscription'] = WC_Subscriptions_Cart::cart_contains_subscription();
			}
			$data['cart_is_virtual'] = WFACP_Common::is_cart_is_virtual();
		}
		wp_send_json( $data );
	}

	public static function product_search( $term = false, $return = false ) {
		self::check_nonce( true );
		$post_terms = filter_input( INPUT_POST, 'term', FILTER_UNSAFE_RAW );
		$term       = wc_clean( empty( $term ) ? stripslashes( $post_terms ) : $term );
		if ( empty( $term ) ) {
			wp_die();
		}
		$variations = true;
		$ids        = WFACP_Common::search_products( $term, $variations );

		/**
		 * Products types that are allowed in the offers
		 */
		$allowed_types   = apply_filters( 'wfacp_offer_product_types', array(
			'simple',
			'variable',
			'course',
			'variation',
			'subscription',
			'variable-subscription',
			'subscription_variation',
			'virtual_subscription',
			'bundle',
			'yith_bundle',
			'woosb',
			'braintree-subscription',
			'braintree-variable-subscription',
		) );
		$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_editable' );

		$product_objects = array_filter( $product_objects, function ( $arr ) use ( $allowed_types ) {
			return $arr && is_a( $arr, 'WC_Product' ) && in_array( $arr->get_type(), $allowed_types );
		} );

		$products = array();
		/**
		 * @var $product_object WC_Product;
		 */
		foreach ( $product_objects as $product_object ) {
			$products[] = array(
				'id'      => $product_object->get_id(),
				'product' => rawurldecode( WFACP_Common::get_formatted_product_name( $product_object ) ),
			);
		}
		wp_send_json( apply_filters( 'wfacp_woocommerce_json_search_found_products', $products ) );
	}

	/**
	 * Add product to product list of Checkout page
	 */
	public static function add_product() {
		self::check_nonce( true );

		$resp = array(
			'msg'      => '',
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['wfacp_id'] ) && isset( $_POST['products'] ) && count( $_POST['products'] ) > 0 ) {
			$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$wfacp_id = absint( $wfacp_id );
			$products = wc_clean( $_POST['products'] );

			$existing_product = WFACP_Common::get_page_product( $wfacp_id );

			foreach ( $products as $pid ) {
				$unique_id = uniqid( 'wfacp_' );
				$product   = wc_get_product( $pid );
				if ( $product instanceof WC_Product ) {
					$product_type                    = $product->get_type();
					$image_id                        = $product->get_image_id();
					$default                         = WFACP_Common::get_default_product_config();
					$default['type']                 = $product_type;
					$default['id']                   = $product->get_id();
					$default['parent_product_id']    = $product->get_parent_id();
					$default['title']                = $product->get_title();
					$default['stock']                = $product->is_in_stock();
					$default['is_sold_individually'] = $product->is_sold_individually();

					$product_image_url = '';
					$images            = wp_get_attachment_image_src( $image_id );
					if ( is_array( $images ) && count( $images ) > 0 ) {
						$product_image_url = wp_get_attachment_image_src( $image_id )[0];
					}
					$default['image'] = apply_filters( 'wfacp_product_image', $product_image_url, $product );

					if ( $default['image'] == '' ) {
						$default['image'] = wc_placeholder_img_src();
					}

					if ( in_array( $product_type, WFACP_Common::get_variable_product_type() ) ) {
						$default['variable'] = 'yes';
						$default['price']    = $product->get_price_html();
					} else {
						if ( in_array( $product_type, WFACP_Common::get_variation_product_type() ) ) {
							$default['title'] = $product->get_name();
						}
						$row_data                 = $product->get_data();
						$sale_price               = $row_data['sale_price'];
						$default['price']         = wc_price( $row_data['price'] );
						$default['regular_price'] = wc_price( $row_data['regular_price'] );
						if ( '' != $sale_price ) {
							$default['sale_price'] = wc_price( $sale_price );
						}
					}

					$resp['products'][ $unique_id ] = $default;
					$default                        = WFACP_Common::remove_product_keys( $default );
					$existing_product[ $unique_id ] = $default;
				}
			}
			WFACP_Common::update_page_product( $wfacp_id, $existing_product );
			if ( count( $resp['products'] ) > 0 ) {
				$resp['status'] = true;
			}
		}
		self::send_resp( $resp );
	}

	/**
	 * Remove a product from product list of checkout page
	 */
	public static function remove_product() {
		self::check_nonce( true );
		$resp = array(
			'status' => false,
			'msg'    => '',
		);
		if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 && isset( $_POST['product_key'] ) && $_POST['product_key'] != '' ) {
			$wfacp_id         = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$product_key      = filter_input( INPUT_POST, 'product_key', FILTER_UNSAFE_RAW );
			$wfacp_id         = absint( $wfacp_id );
			$product_key      = trim( $product_key );
			$existing_product = WFACP_Common::get_page_product( $wfacp_id );
			if ( isset( $existing_product[ $product_key ] ) ) {
				unset( $existing_product[ $product_key ] );
				WFACP_Common::update_page_product( $wfacp_id, $existing_product );
				$resp['status'] = true;
				$resp['msg']    = __( 'Product removed from checkout page' );
			}
		}
		self::send_resp( $resp );
	}

	/**
	 * Save product with product settings to checkout page
	 * Save checkout page
	 */
	public static function save_products() {
		self::check_nonce( true );
		$resp = array(
			'msg'      => __( 'Changes saved', 'funnel-builder' ),
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['products'] ) && count( $_POST['products'] ) > 0 ) {
			$products = wc_clean( $_POST['products'] );
			$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$settings = isset( $_POST['settings'] ) ? wc_clean( $_POST['settings'] ) : [];
			foreach ( $products as $key => $val ) {
				if ( isset( $products[ $key ]['variable'] ) ) {

					$pro                = WFACP_Common::wc_get_product( $products[ $key ]['id'], $key );
					$is_found_variation = WFACP_Common::get_default_variation( $pro );

					if ( count( $is_found_variation ) > 0 ) {
						$products[ $key ]['default_variation']      = $is_found_variation['variation_id'];
						$products[ $key ]['default_variation_attr'] = $is_found_variation['attributes'];
					}
				}
				$products[ $key ] = WFACP_Common::remove_product_keys( $products[ $key ] );
			}

			$old_settings = WFACP_Common::get_page_product_settings( $wfacp_id );
			if ( isset( $_POST['settings'] ) && isset( $_POST['settings']['add_to_cart_setting'] ) && $old_settings['add_to_cart_setting'] !== $_POST['settings']['add_to_cart_setting'] ) {
				//unset default products
				$s = get_post_meta( $wfacp_id, '_wfacp_product_switcher_setting', true );
				if ( ! empty( $s ) ) {
					$s['default_products'] = [];
					update_post_meta( $wfacp_id, '_wfacp_product_switcher_setting', $s );
				}
			}
			WFACP_Common::update_page_product( $wfacp_id, $products );
			WFACP_Common::update_page_product_setting( $wfacp_id, $settings );
			$resp['status'] = true;
		}
		self::send_resp( $resp );
	}

	public static function is_wfacp_front_ajax() {
		if ( defined( 'DOING_AJAX' ) && true === DOING_AJAX && null !== filter_input( INPUT_POST, 'action' ) && false !== strpos( filter_input( INPUT_POST, 'action' ), 'wfacp_front' ) ) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * add ct_inner in oxygen url
	 */
	public function update_edit_url() {
		self::check_nonce( true );

		$id  = isset( $_POST['id'] ) ? wc_clean( $_POST['id'] ) : 0;//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$url = isset( $_POST['url'] ) ? wc_clean( $_POST['url'] ) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( absint( $id ) > 0 && ( $url !== '' ) ) {


			// Get post template
			$post_template = intval( get_post_meta( $id, 'ct_other_template', true ) );
			// Check if we should edit the post or it's template
			$post_editable  = false;
			$template_inner = false;
			if ( $post_template == 0 ) { // default template
				// Get default template
				$default_template = null;
				if ( get_option( 'page_for_posts' ) == $id || get_option( 'page_on_front' ) == $id ) {
					$default_template = ct_get_archives_template( $id );
				}
				if ( empty( $default_template ) ) {
					$default_template = ct_get_posts_template( $id );
				}
				if ( $default_template ) {
					$shortcodes = get_post_meta( $default_template->ID, 'ct_builder_shortcodes', true );
					if ( $shortcodes && strpos( $shortcodes, '[ct_inner_content' ) !== false ) {
						$post_editable  = true;
						$template_inner = true;
					}
				} else {
					$post_editable = true;
				}
			} else if ( $post_template == - 1 ) { // None
				$post_editable = true;
			} else { // Custom template
				$shortcodes = get_post_meta( $post_template, 'ct_builder_shortcodes', true );
				if ( $shortcodes && strpos( $shortcodes, '[ct_inner_content' ) !== false ) {
					$post_editable  = true;
					$template_inner = true;
				}
			}
			// Generate edit link
			if ( $post_editable ) {
				if ( $template_inner ) {
					$url = add_query_arg( [ 'ct_inner' => 'true' ], $url );
				}
			}
		}

		$resp = [
			'status' => true,
			'url'    => $url,
		];
		self::send_resp( $resp );
	}

	/**
	 * Toggle status of checkout page from Admin page and Builder page
	 */
	public static function update_page_status() {
		self::check_nonce( true );
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['id'] ) && $_POST['id'] > 0 && isset( $_POST['post_status'] ) ) {
			$id          = filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW );
			$post_status = filter_input( INPUT_POST, 'post_status', FILTER_UNSAFE_RAW );
			$args        = [
				'ID'          => $id,
				'post_status' => 'true' == $post_status ? 'publish' : 'draft',
			];

			$meta = get_post_meta( $id, '_wp_page_template', true );
			wp_update_post( $args );

			update_post_meta( $id, '_wp_page_template', $meta );
			WFACP_Common::save_publish_checkout_pages_in_transient();
			$resp = array(
				'msg'    => __( 'Checkout Page status updated', 'funnel-builder' ),
				'status' => true,
			);
		}
		self::send_resp( $resp );
	}

	/**
	 * Save selected design template against checkout page
	 */

	public static function save_design() {
		self::check_nonce( true );
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 ) {
			$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$wfacp_id = absint( $wfacp_id );
			WFACP_Common::update_page_design( $wfacp_id, [
				'selected'        => filter_input( INPUT_POST, 'selected', FILTER_UNSAFE_RAW ),
				'selected_type'   => filter_input( INPUT_POST, 'selected_type', FILTER_UNSAFE_RAW ),
				'template_active' => filter_input( INPUT_POST, 'template_active', FILTER_UNSAFE_RAW ),
			] );

			$resp = array(
				'msg'    => __( 'Template imported', 'funnel-builder' ),
				'status' => true,
			);

		}
		self::send_resp( $resp );
	}

	public static function save_design_settings() {
		self::check_nonce( true );
		if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 ) {
			$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$settings = isset( $_POST['settings'] ) ? wc_clean( $_POST['settings'] ) : [];
			WFACP_Common::set_id( $wfacp_id );
			update_option( WFACP_SLUG . '_c_' . $wfacp_id, $settings, 'no' );

		}
		$resp = array(
			'msg'    => __( 'Form Setting Saved', 'funnel-builder' ),
			'status' => true,
		);
		self::send_resp( $resp );
	}

	public static function remove_design() {
		self::check_nonce( true );
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 ) {
			$wfacp_id = absint( $_POST['wfacp_id'] );
			delete_post_meta( $wfacp_id, '_wfacp_selected_design' );
			$option = WFACP_SLUG . '_c_' . $wfacp_id;
			delete_option( $option );

			do_action( 'wfacp_template_removed', $wfacp_id );

			//Remove Template Meta key
			global $wpdb;
			$wpdb->delete( $wpdb->postmeta, [ 'meta_key' => '_et_pb_use_builder', 'post_id' => $wfacp_id ] );
			$wpdb->delete( $wpdb->postmeta, [ 'meta_key' => 'tcb_editor_enabled', 'post_id' => $wfacp_id ] );

			$resp = array(
				'msg'    => __( 'Design Saved Successfully', 'funnel-builder' ),
				'status' => true,
			);
		}
		self::send_resp( $resp );
	}


	/**
	 * Save checkout page setting to checkout page
	 */
	public static function save_settings() {
		self::check_nonce( true );
		$resp = [
			'msg'    => '',
			'status' => false,
		];
		if ( isset( $_POST['wfacp_id'] ) && $_POST['wfacp_id'] > 0 && isset( $_POST['settings'] ) ) {
			$wfacp_id = absint( $_POST['wfacp_id'] );
			$settings = $_POST['settings'];
			WFACP_Common::update_page_settings( $wfacp_id, $settings );
			$resp = [
				'msg'    => __( 'Changes saved', 'funnel-builder' ),
				'status' => true,
			];
		}
		self::send_resp( $resp );
	}

	/**
	 * Save aero checkout global settings
	 */
	public static function save_global_settings() {
		self::check_nonce( true );
		$options = isset( $_POST['settings'] ) ? $_POST['settings'] : 0; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$resp    = self::update_global_settings_fields( $options );
		self::send_resp( $resp );
	}

	public static function update_global_settings_fields( $options ) {

		$options = ( is_array( $options ) && count( $options ) > 0 ) ? wp_unslash( $options ) : 0;
		$resp    = [
			'status' => false,
			'msg'    => __( 'Changes saved', 'funnel-builder' ),
		];

		if ( ! is_array( $options ) || count( $options ) === 0 ) {
			return $resp;
		}

		$options['wfacp_checkout_global_css']    = isset( $options['wfacp_checkout_global_css'] ) ? stripslashes_deep( $options['wfacp_checkout_global_css'] ) : '';
		$options['wfacp_global_external_script'] = isset( $options['wfacp_global_external_script'] ) ? stripslashes_deep( $options['wfacp_global_external_script'] ) : '';

		update_option( '_wfacp_global_settings', $options, true );
		do_action( 'wfacp_global_settings_updated', $options );
		$resp['status'] = true;

		return $resp;
	}

	public static function preview_details() {
		self::check_nonce();
		$resp     = [
			'msg'    => '',
			'status' => false,
		];
		$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
		if ( ! is_null( $wfacp_id ) && $wfacp_id > 0 ) {
			$wfacp_id           = absint( $wfacp_id );
			$post_data          = get_post( $wfacp_id );
			$title              = $post_data->post_title;
			$post_status        = $post_data->post_status;
			$guid               = get_the_permalink( $wfacp_id );
			$productData        = WFACP_Common::get_page_product( $wfacp_id );
			$discount_type_keys = [
				'fixed_discount_reg'    => __( 'on Regular Price', 'funnel-builder' ),
				'fixed_discount_sale'   => __( 'on Sale Price', 'funnel-builder' ),
				'percent_discount_reg'  => '% on Regular Price',
				'percent_discount_sale' => '% on Sale Price',
			];

			ob_start();
			?>
            <div class="wfacp-fp-wrap preview_sec product_preview">

				<?php
				if ( is_array( $productData ) && count( $productData ) > 0 ) {
					?>
                    <h3><?php _e( 'Products', 'woocommerce' ); ?></h3>
                    <div class="wfacp-sec">
                        <div class="wfacp-fp-offer-products">
                            <div class="wfacp_pop_table wfacp_popup_table_wrap">

								<?php
								foreach ( $productData as $product_key => $product_val ) {
									$discount_type   = $product_val['discount_type'];
									$discount_amount = $product_val['discount_amount'];
									$discounted_val  = __( '--', 'funnel-builder' );
									if ( $discount_amount > 0 ) {
										if ( false !== strpos( $discount_type, 'percent_discount_' ) ) {
											$discounted_val = $discount_amount . '' . $discount_type_keys[ $discount_type ];
										} else {
											$discounted_val = wc_price( $discount_amount ) . ' ' . $discount_type_keys[ $discount_type ];
										}
									}
									$strinCon = [];
									if ( isset( $product_val['title'] ) && $product_val['title'] != '' ) {
										$strinCon[] = $product_val['title'];
									}
									if ( isset( $product_val['quantity'] ) && $product_val['title'] != '' ) {
										$strinCon[] = 'x ' . $product_val['quantity'];
									}

									if ( isset( $discounted_val ) && $discounted_val > 0 ) {
										$strinCon[] = '@ ' . $discounted_val;
									}
									$popUptext = '';

									if ( is_array( $strinCon ) ) {
										$popUptext = implode( ' ', $strinCon );
									}

									if ( $popUptext != '' ) {
										echo "<p>$popUptext</p>";
									}
								}
								?>

                            </div>
                        </div>
                    </div>
					<?php
				} else {

					?>
                    <div class="wfacp-sec">
                        <div class="wfacp-fp-offer-products">
                            <div class="wfacp_pop_table wfacp_popup_table_wrap"><?php _e( 'No product is associated in the checkout form', 'funnel-builder' ) ?></div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
			$html                = ob_get_clean();
			$resp                = [
				'msg'    => 'Settings Data saved',
				'status' => true,
			];
			$resp['wfacp_id']    = $post_data;
			$resp['post_name']   = $title;
			$resp['launch_url']  = $guid;
			$resp['post_status'] = $post_status;
			$resp['html']        = $html;
		}
		self::send_resp( $resp );
	}

	public static function update_cart_item_quantity( $post ) {

		$resp     = [
			'msg'    => '',
			'status' => false,
		];
		$quantity = floatval( $post['quantity'] );

		if ( $quantity <= 0 ) {
			$quantity = 0;
		}

		$cart_key = $post['cart_key'];

		if ( $quantity == 0 ) {

			$items       = WC()->cart->get_cart();
			$deletion_by = $post['by'];
			if ( 'mini_cart' !== $deletion_by && 1 == WFACP_Common::get_cart_count( $items ) && false == WFACP_Common::enable_cart_deletion() ) {
				$resp             = WFACP_Common::last_item_delete_message( $resp, $cart_key );
				$resp['qty']      = isset( $post['old_qty'] ) ? $post['old_qty'] : 1;
				$resp['cart_key'] = $cart_key;
				$resp['status']   = false;

				return $resp;
			}
			WC()->cart->remove_cart_item( $cart_key );
			$resp['status'] = true;

			return ( $resp );

		}
		WFACP_Common::disable_wcct_pricing();
		$cart_item = WC()->cart->get_cart_item( $cart_key );
		/**
		 * @var $product_obj WC_Product;
		 */
		$save_product_list = WC()->session->get( 'wfacp_product_data_' . WFACP_Common::get_id(), [] );

		$new_qty  = $quantity;
		$aero_key = '';
		if ( isset( $cart_item['_wfacp_product_key'] ) ) {
			$aero_key     = $cart_item['_wfacp_product_key'];
			$product_data = $save_product_list[ $aero_key ];
			if ( isset( $save_product_list[ $aero_key ] ) ) {
				$org_qty = ( $product_data['org_quantity'] );
				if ( $org_qty > 0 ) {
					$new_qty = $org_qty * $quantity;
				}
			}
		}
		$product_obj  = $cart_item['data'];
		$stock_status = WFACP_Common::check_manage_stock( $product_obj, $new_qty );
		if ( false == $stock_status ) {
			/* Add the wc Notice */
			$current_session_order_id = isset( WC()->session->order_awaiting_payment ) ? absint( WC()->session->order_awaiting_payment ) : 0;
			$held_stock               = wc_get_held_stock_quantity( $product_obj, $current_session_order_id );
			$resp['error']            = sprintf( __( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'woocommerce' ), $product_obj->get_name(), wc_format_stock_quantity_for_display( $product_obj->get_stock_quantity() - $held_stock, $product_obj ) );


			$resp['qty']      = $cart_item['quantity'];
			$resp['status']   = false;
			$resp['cart_key'] = $cart_key;

			return ( $resp );
		}

		$set = WC()->cart->set_quantity( $cart_key, $new_qty );

		if ( $set ) {
			if ( '' !== $aero_key ) {
				$save_product_list[ $aero_key ]['quantity'] = $quantity;
				WC()->session->set( 'wfacp_product_data_' . WFACP_Common::get_id(), $save_product_list );
			}
			$resp['qty']    = $quantity;
			$resp['status'] = true;
		}

		return ( $resp );
	}

	public static function make_wpml_duplicate() {
		self::check_nonce( true );
		$resp = [
			'msg'    => __( 'Something went wrong', 'funnel-builder' ),
			'status' => false,
		];
		if ( isset( $_POST['trid'] ) && $_POST['trid'] > 0 && class_exists( 'SitePress' ) && method_exists( 'SitePress', 'get_original_element_id_by_trid' ) ) {
			$trid           = filter_input( INPUT_POST, 'trid', FILTER_UNSAFE_RAW );
			$lang           = filter_input( INPUT_POST, 'lang', FILTER_UNSAFE_RAW );
			$language_code  = filter_input( INPUT_POST, 'language_code', FILTER_UNSAFE_RAW );
			$trid           = absint( $trid );
			$lang           = ! is_null( $lang ) ? trim( $lang ) : '';
			$language_code  = ! is_null( $language_code ) ? trim( $language_code ) : '';
			$lang           = empty( $lang ) ? $language_code : $lang;
			$master_post_id = SitePress::get_original_element_id_by_trid( $trid );
			if ( false !== $master_post_id ) {
				global $sitepress;
				$duplicate_id = $sitepress->make_duplicate( $master_post_id, $lang );
				if ( is_int( $duplicate_id ) && $duplicate_id > 0 ) {
					WFACP_Common::get_duplicate_data( $duplicate_id, $master_post_id );
					$new_post = get_post( $duplicate_id );
					if ( ! is_null( $new_post ) ) {
						$args['post_title'] = $new_post->post_title . ' - ' . __( 'Copy - ' . $lang, 'funnel-builder' );

						$args['ID'] = $duplicate_id;
						wp_update_post( $args );
						delete_post_meta( $duplicate_id, '_icl_lang_duplicate_of' );
					}
					$resp['redirect_url'] = add_query_arg( [
						'section'  => 'products',
						'wfacp_id' => $duplicate_id,
					], admin_url( 'admin.php?page=wfacp' ) );
					$resp['duplicate_id'] = $duplicate_id;
					$resp['status']       = true;
				}
			}
		}
		self::send_resp( $resp );
	}

	public static function update_cart_multiple_page( $post ) {
		$resp              = [
			'msg'    => '',
			'status' => false,
		];
		$success           = [];
		$switcher_products = $post['products'];
		$coupons           = $post['coupons'];
		$wfacp_id          = absint( $post['wfacp_id'] );
		if ( ! is_array( $switcher_products ) || empty( $switcher_products ) ) {
			return ( $resp );
		}

		WC()->cart->empty_cart();
		WFACP_Common::set_id( $wfacp_id );
		WFACP_Core()->public->get_page_data( $wfacp_id );


		$products = WFACP_Common::get_page_product( $wfacp_id );
		do_action( 'wfacp_before_add_to_cart', $products );
		$added_products = [];

		foreach ( $products as $index => $data ) {

			$product_id   = absint( $data['id'] );
			$quantity     = absint( $data['quantity'] );
			$variation_id = 0;
			if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
				$product_id   = absint( $data['parent_product_id'] );
				$variation_id = absint( $data['id'] );
			}
			$product_obj = WFACP_Common::wc_get_product( ( $variation_id > 0 ? $variation_id : $product_id ), $index );
			if ( ! $product_obj instanceof WC_Product ) {
				continue;
			}

			if ( ! $product_obj->is_purchasable() ) {
				unset( $products[ $index ] );
				continue;
			}
			$stock_status = WFACP_Common::check_manage_stock( $product_obj, $quantity );

			if ( false == $stock_status ) {
				unset( $products[ $index ] );
				continue;
			}
			$products[ $index ] = $data;
			$product_obj->add_meta_data( 'wfacp_data', $data );
			$added_products[ $index ] = $product_obj;
		}

		if ( count( $added_products ) > 0 ) {
			add_filter( 'wp_redirect', '__return_false', 100 );
			foreach ( $added_products as $index => $product_obj ) {
				if ( ! isset( $switcher_products[ $index ] ) ) {
					continue;
				}
				$data         = $product_obj->get_meta( 'wfacp_data' );
				$product_id   = absint( $data['id'] );
				$quantity     = isset( $data['org_quantity'] ) ? absint( $data['org_quantity'] ) : absint( $data['quantity'] );
				$variation_id = 0;
				if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
					$product_id   = absint( $data['parent_product_id'] );
					$variation_id = absint( $data['id'] );
				}
				try {
					$attributes  = [];
					$custom_data = [];
					if ( isset( $data['variable'] ) ) {
						$variation_id                             = $data['default_variation'];
						$attributes                               = $data['default_variation_attr'];
						$custom_data['wfacp_variable_attributes'] = $attributes;
					}
					$custom_data['_wfacp_product']     = true;
					$custom_data['_wfacp_product_key'] = $index;
					$current_quantity                  = 1;
					if ( $switcher_products[ $index ] ) {
						$current_quantity = absint( $switcher_products[ $index ] );
						if ( $current_quantity > 0 ) {
							$quantity = $current_quantity * $quantity;
						}
					}
					$products[ $index ]['quantity'] = $current_quantity;
					$custom_data['_wfacp_options']  = $data;
					$success[]                      = $cart_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes, $custom_data );
					if ( is_string( $cart_key ) ) {

						$data['is_added_cart'] = $cart_key;
						$added_products[ $index ]->update_meta_data( 'wfacp_data', $data );

						$products[ $index ]['is_added_cart'] = $cart_key;

					} else {
						unset( $added_products[ $index ], $products[ $index ] );
					}
				} catch ( Exception $e ) {

				}
			}
		}
		do_action( 'wfacp_after_add_to_cart' );

		if ( count( $success ) > 0 ) {

			WC()->session->set( 'wfacp_id', WFACP_Common::get_id() );
			WC()->session->set( 'wfacp_cart_hash', md5( maybe_serialize( WC()->cart->get_cart_contents() ) ) );
			WC()->session->set( 'wfacp_product_objects_' . WFACP_Common::get_id(), $added_products );
			WC()->session->set( 'wfacp_product_data_' . WFACP_Common::get_id(), $products );
			if ( is_array( $coupons ) && ! empty( $coupons ) ) {
				remove_action( 'woocommerce_applied_coupon', [ WFACP_Core()->public, 'set_session_when_coupon_applied' ] );
				//	WC()->cart->add_discount( $coupon );
				foreach ( $coupons as $coupon_id ) {
					$coupon_id = trim( $coupon_id );
					WC()->cart->add_discount( $coupon_id );
				}

			}
			WC()->session->__unset( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_id() );
			wc_clear_notices();
			$resp = array(
				'success' => $success,
				'status'  => true
			);
		}
		do_action( 'wfacp_after_add_to_cart' );

		return ( $resp );
	}

	public static function apply_coupon( $bump_action_data ) {

		if ( isset( $bump_action_data['coupon_code'] ) ) {
			remove_all_filters( 'woocommerce_coupons_enabled' );
			do_action( 'wfacp_before_coupon_apply', $bump_action_data );
			$status = true;
			if ( apply_filters( 'wfacp_apply_coupon_via_ajax', true, $bump_action_data ) ) {
				$status = WC()->cart->add_discount( sanitize_text_field( $bump_action_data['coupon_code'] ) );
			} else {
				do_action( 'wfacp_apply_coupon_via_ajax_placeholder', $bump_action_data );
			}

			WC()->cart->calculate_totals();


			$all_notices  = WC()->session->get( 'wc_notices', array() );
			$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
			$message      = [];

			foreach ( $notice_types as $notice_type ) {
				if ( wc_notice_count( $notice_type ) > 0 ) {
					$message = array(
						$notice_type => $all_notices[ $notice_type ],
					);
				}
			}


			wc_clear_notices();

			$resp = array(
				'status'  => $status,
				'message' => $message,
			);
		} else {
			$resp = array(
				'status'  => false,
				'message' => array(
					'error' => [ 'Please provide a coupon code' ],
				),
			);
		}

		return $resp;
	}

	public static function remove_coupon( $bump_action_data ) {
		$coupon = isset( $bump_action_data['coupon_code'] ) ? wc_format_coupon_code( wp_unslash( $bump_action_data['coupon_code'] ) ) : false;
		wc_clear_notices();
		do_action( 'wfacp_before_coupon_removed', $bump_action_data );
		$status = true;
		if ( empty( $coupon ) ) {
			$message = __( 'Sorry there was a problem removing this coupon.', 'woocommerce' );
			$status  = false;
		} else {
			WC()->cart->remove_coupon( $coupon );
			$message = __( 'Coupon has been removed.', 'woocommerce' );
			do_action( 'wfacp_after_coupon_removed', $bump_action_data );

		}

		$resp = array(
			'status'  => $status,
			'message' => $message,
		);

		wc_clear_notices();

		return $resp;
	}

	public static function prep_fees() {
		$fees = [];

		foreach ( WC()->cart->get_fees() as $fee ) {
			$out         = (object) [];
			$out->name   = $fee->name;
			$out->amount = ( 'excl' == WFACP_Common::get_tax_display_mode() ) ? wc_price( $fee->total ) : wc_price( $fee->total + $fee->tax );
			$fees[]      = $out;
		}

		return $fees;
	}

	public static function remove_cart_item( $post ) {

		$resp = [
			'msg'    => '',
			'status' => false,
		];

		$wfacp_id = absint( $post['wfacp_id'] );
		if ( $wfacp_id == 0 ) {
			return ( $resp );
		}
		if ( isset( $post['cart_key'] ) && '' !== $post['cart_key'] ) {
			$cart_item_key = sanitize_text_field( wp_unslash( $post['cart_key'] ) );
			$cart_item     = WC()->cart->get_cart_item( $cart_item_key );
			if ( $cart_item ) {
				WFACP_Common::set_id( $wfacp_id );
				$status = WC()->cart->remove_cart_item( $cart_item_key );
				if ( $status ) {
					$product           = wc_get_product( $cart_item['product_id'] );
					$item_is_available = false;
					// Don't show undo link if removed item is out of stock.
					if ( $product && $product->is_in_stock() && $product->has_enough_stock( $cart_item['quantity'] ) ) {
						$item_is_available = true;
						$removed_notice    = "&nbsp;" . ' <a href="javascript:void(0)" class="wfacp_restore_cart_item" data-cart_key="' . $cart_item_key . '">' . __( 'Undo?', 'woocommerce' ) . '</a>';
					} else {
						$item_is_available = false;
						/* Translators: %s Product title. */
						$removed_notice = sprintf( __( '%s removed.', 'woocommerce' ), '' );
					}
					$resp['item_is_available'] = $item_is_available;
					$resp['status']            = true;
					$resp['remove_notice']     = $removed_notice;

					return ( $resp );
				}
			}
		}

		return ( $resp );
	}

	public static function undo_cart_item( $post ) {
		$resp = [
			'msg'    => '',
			'status' => false,
		];

		if ( isset( $post['cart_key'] ) && '' !== $post['cart_key'] ) {
			// Undo Cart Item.
			$cart_item_key = sanitize_text_field( wp_unslash( $post['cart_key'] ) );
			WC()->cart->restore_cart_item( $cart_item_key );
			do_action( 'wfacp_restore_cart_item', $cart_item_key, $post );
			$item                 = WC()->cart->get_cart_item( $cart_item_key );
			$resp['restore_item'] = [];
			if ( is_array( $item ) && $item['data'] instanceof WC_Product ) {
				$data                 = [ 'type' => $item['data']->get_type() ];
				$resp['restore_item'] = apply_filters( 'wfacp_restore_cart_item_data', $data, $item );
			}
			if ( isset( $item['_wfacp_product_key'] ) ) {
				$product_key       = $item['_wfacp_product_key'];
				$save_product_list = WC()->session->get( 'wfacp_product_data_' . WFACP_Common::get_id() );
				if ( isset( $save_product_list[ $product_key ] ) ) {
					$save_product_list[ $product_key ]['is_added_cart'] = $cart_item_key;
					WC()->session->set( 'wfacp_product_data_' . WFACP_Common::get_id(), $save_product_list );
				}
			}
			$resp['status'] = true;
		}

		return ( $resp );
	}

	public static function hide_notification() {
		$rsp = [
			'status' => false,

		];

		if ( isset( $_POST['wfacp_id'] ) && isset( $_POST['index'] ) && isset( $_POST['message_type'] ) ) {
			$index        = filter_input( INPUT_POST, 'index', FILTER_UNSAFE_RAW );
			$wfacp_id     = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			$message_type = filter_input( INPUT_POST, 'message_type', FILTER_UNSAFE_RAW );
			if ( isset( $message_type ) && 'global' == $message_type ) {
				$notification = get_option( 'wfacp_global_notifications', [] );
			} else {
				$notification = get_post_meta( $wfacp_id, 'notifications', true );
			}
			if ( ! is_array( $notification ) ) {
				$notification = [];
			}

			$notification[ $index ] = true;
			if ( isset( $message_type ) && 'global' == $message_type ) {
				update_option( 'wfacp_global_notifications', $notification, 'no' );
			} else {
				update_post_meta( $wfacp_id, 'notifications', $notification );
			}
			$rsp['status'] = true;
		}
		wp_send_json( $rsp );
	}


	public static function import_template() {
		self::check_nonce( true );
		$resp     = [
			'status' => false,
			'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
		];
		$builder  = filter_input( INPUT_POST, 'builder', FILTER_UNSAFE_RAW );
		$template = filter_input( INPUT_POST, 'template', FILTER_UNSAFE_RAW );
		$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
		$is_multi = filter_input( INPUT_POST, 'is_multi', FILTER_UNSAFE_RAW );

		$response = WFACP_Core()->importer->import( $wfacp_id, $builder, $template, $is_multi );
		if ( isset( $response['error'] ) ) {
			$resp['status'] = false;
			$resp['msg']    = $response['error'];
			self::send_resp( $resp );
		}
		if ( isset( $response['status'] ) && true == $response['status'] ) {
			$resp['status'] = true;
			$resp['msg']    = __( 'Importing of template finished', 'funnel-builder' );
			self::send_resp( $resp );
		}

		self::send_resp( $resp );
	}

	/**
	 * Ajax action to activate plugin
	 */
	public static function activate_plugin() {
		self::check_nonce( true );

		$resp        = array( 'success' => false );
		$plugin_init = isset( $_POST['plugin_init'] ) ? sanitize_text_field( $_POST['plugin_init'] ) : '';
		$activate    = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			$resp['message'] = $activate->get_error_message();
			$resp['init']    = $plugin_init;
			self::send_resp( $resp );
		}

		$resp['success'] = true;
		$resp['message'] = __( 'Plugin Successfully Activated', 'funnel-builder' );
		$resp['init']    = $plugin_init;

		self::send_resp( $resp );
	}

	public static function notice_dismise_link() {
		$results     = array(
			'status' => 'failed',
			'msg'    => 'Problem With dismiss',
		);
		$wfacp_nonce = filter_input( INPUT_POST, 'wfacp_nonce', FILTER_UNSAFE_RAW );
		if ( is_null( $wfacp_nonce ) || ! wp_verify_nonce( $wfacp_nonce, 'wfacp_admin_secure_key' ) ) {
			wp_send_json( $results );
		}

		if ( ( isset( $_POST['notice_dismise_link'] ) && $_POST['notice_dismise_link'] !== '' ) ) {
			$noticekey = filter_input( INPUT_POST, 'notice_dismise_link', FILTER_UNSAFE_RAW );
			$pageID    = filter_input( INPUT_POST, 'page_id', FILTER_UNSAFE_RAW );

			if ( $pageID != '' ) {

				update_post_meta( $pageID, $noticekey, $noticekey );

			}

			$results = array(
				'status' => 'success',
				'msg'    => $noticekey . ' Notification Deleted',
				'key'    => $noticekey,
			);
		}

		wp_send_json( $results );
	}

	public static function get_divi_form_data() {


		if ( isset( $_REQUEST['wfacp_id'] ) ) {
			$post_id = $_REQUEST['wfacp_id'];
			$post    = get_post( $post_id );
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {

				WFACP_Common::wc_ajax_get_refreshed_fragments();

			} else {
				return;
			}
		}

		$json = file_get_contents( 'php://input' );
		if ( '' !== $json ) {
			$json = json_decode( $json, true );
		} else {
			$json = [];
		}

		$template = wfacp_template();
		$id       = 'wfacp_divi_checkout_form';
		WFACP_Common::set_session( $id, $json );
		$template->set_form_data( $json );

		if ( isset( $_COOKIE['wfacp_divi_open_page'] ) && wp_doing_ajax() ) {
			$cookie = $_COOKIE['wfacp_divi_open_page'];
			$parts  = explode( '@', $cookie );
			$template->set_current_open_step( $parts[1] );
		}
		include $template->wfacp_get_form();

		exit( 0 );
	}

	public static function analytics() {
		self::check_nonce();
		$resp       = array( 'status' => false );
		$data       = $_POST['data'];
		$event_data = $data['event_data'];
		$source     = isset( $data['source'] ) ? $data['source'] : '';
		$pixel      = WFACP_Analytics_Pixel::get_instance();

		$get_all_fb_pixel  = $pixel->get_key();
		$get_each_pixel_id = explode( ',', $get_all_fb_pixel );

		if ( ! is_array( $get_each_pixel_id ) || empty( $get_each_pixel_id ) ) {
			self::send_resp( $resp );
		}


		$user_data                      = WFACP_Common::pixel_advanced_matching_data();
		$user_data['client_ip_address'] = ! empty( WC_Geolocation::get_ip_address() ) ? WC_Geolocation::get_ip_address() : '127.0.0.1';
		$user_data['client_user_agent'] = wc_get_user_agent();
		if ( isset( $_COOKIE['_fbp'] ) && ! empty( $_COOKIE['_fbp'] ) ) {
			$user_data['_fbp'] = wc_clean( $_COOKIE['_fbp'] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
		}
		if ( isset( $_COOKIE['_fbc'] ) && ! empty( $_COOKIE['_fbc'] ) ) {
			$user_data['_fbc'] = wc_clean( $_COOKIE['_fbc'] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
		} elseif ( isset( $_COOKIE['wffn_fbclid'] ) && isset( $_COOKIE['wffn_flt'] ) && ! empty( $_COOKIE['wffn_fbclid'] ) ) {
			$user_data['_fbc'] = 'fb.1.' . strtotime( $_COOKIE['wffn_flt'] ) . '.' . $_COOKIE['wffn_fbclid'];
		}

		foreach ( $get_each_pixel_id as $key => $pixel_id ) {
			$access_token = $pixel->get_conversion_api_access_token();
			$access_token = explode( ',', $access_token );
			if ( is_array( $access_token ) && count( $access_token ) > 0 ) {
				if ( isset( $access_token[ $key ] ) ) {
					BWF_Facebook_Sdk_Factory::setup( trim( $pixel_id ), trim( $access_token[ $key ] ) );
				}
			}

			$get_test      = $pixel->get_conversion_api_test_event_code();
			$get_test      = explode( ',', $get_test );
			$is_test_event = $pixel->admin_general_settings->get_option( 'is_fb_conv_enable_test' );
			if ( is_array( $is_test_event ) && count( $is_test_event ) > 0 && $is_test_event[0] === 'yes' && is_array( $get_test ) && count( $get_test ) > 0 ) {
				if ( isset( $get_test[ $key ] ) && ! empty( $get_test[ $key ] ) ) {
					BWF_Facebook_Sdk_Factory::set_test( trim( $get_test[ $key ] ) );
				}
			}

			BWF_Facebook_Sdk_Factory::set_partner( 'woofunnels' );
			$instance = BWF_Facebook_Sdk_Factory::create();
			if ( is_null( $instance ) ) {
				self::send_resp( $resp );
			}

			if ( is_array( $event_data ) && count( $event_data ) > 0 ) {
				foreach ( $event_data as $single_item ) {
					$instance->set_event_id( $single_item['event_id'] );
					$instance->set_user_data( $user_data );
					$instance->set_event_source_url( $source );

					$fb_single_data = isset( $single_item['data'] ) ? $single_item['data'] : [];
					if ( isset( $fb_single_data['contents'] ) ) {
						foreach ( $fb_single_data['contents'] as $ckey => $a ) {
							unset( $fb_single_data['contents'][ $ckey ]['value'] );
						}
					}
					$instance->set_event_data( $single_item['event'], $fb_single_data );
					$response = $instance->execute();
					WFACP_Common::maybe_insert_log( '----Facebook conversion API-----------' . print_r( $response, true ) ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				}

				$resp['status'] = true;
			}

		}


		self::send_resp( $resp );
	}

	public static function save_layout() {
		self::check_nonce( true );
		$resp = array(
			'msg'      => '',
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['wfacp_id'] ) ) {
			$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
			WFACP_Common::set_id( $wfacp_id );
			WFACP_Common::update_page_layout( $wfacp_id, $_POST );
			$resp['status'] = true;
			$resp['msg']    = __( 'Changes saved', 'funnel-builder' );
		}
		self::send_resp( $resp );
	}
}

WFACP_AJAX_Controller::init();
