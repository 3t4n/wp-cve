<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WFFN_REST_Funnel_Modules
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnel_Modules' ) ) {
	class WFFN_REST_Funnel_Modules extends WFFN_REST_Controller {

		public static $_instance = null;
		/**
		 * Route base.
		 *
		 * @var string
		 */
		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnel-(?P<type>[a-z-_]+)';


		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function pr( $array, $exit = 0 ) {
			echo '<pre>';
			print_r( $array );  // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			echo '</pre>';
			if ( $exit ) {
				exit;
			}
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register API Routes for WooFunnels UI.
		 */
		public function register_routes() {

			// Route for Search and retrieve template list.
			register_rest_route( $this->namespace, '/funnels/templates/search', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_templates' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'builder' => array(
							'description'       => __( 'Page Builder', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'type'    => array(
							'description'       => __( 'Funnel type', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			// Route to Search Pages.
			register_rest_route( $this->namespace, '/funnels/pages/search', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_pages' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'term'  => array(
							'description'       => __( 'search term', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'pages' => array(
							'description'       => __( 'Post type', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			// Search for WooCommerce Products.
			register_rest_route( $this->namespace, '/' . '/funnels/products/search', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'product_list' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'term' => array(
							'description'       => __( 'Product name', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'required'          => true,
						),
					),
				),
			) );

			// Search Product for steps
			register_rest_route( $this->namespace, '/' . 'funnels' . '/products/search_variant', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'product_search_variant' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'term'       => array(
							'description'       => __( 'Product name', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'required'          => true,
						),
						'variations' => array(
							'description'       => __( 'Search for Variation name', 'funnel-builder' ),
							'type'              => 'boolean',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			// Search Coupons
			register_rest_route( $this->namespace, '/' . 'funnels' . '/coupons/search', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_coupons' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'term' => array(
							'description'       => __( 'Coupon name', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'required'          => true,
						),
					),
				),
			) );

			// Search Coupons
			register_rest_route( $this->namespace, '/' . 'funnels' . '/customers/search', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_customers' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'term' => array(
							'description'       => __( 'Customer User Name', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'required'          => true,
						),
					),
				),
			) );

			// Routes for Step Design.
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<step_id>[\d]+)' . '/design', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_design' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_step' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_design' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => $this->get_delete_steps_collection(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Routes for Customize Tab.
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<step_id>[\d]+)' . '/customize', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_customization_options' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_customization_options' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Route for Customize Tab get configurations.
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<step_id>[\d]+)/customize/configurations', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_customization_config' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register routes to save Step State.
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/save-state' . '/(?P<step_id>[\d]+)', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),

				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_state' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register routes for Step Settings.
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<step_id>[\d]+)' . '/settings', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_customsettings' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_customsettings' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );


			// Save Order API
			// Register routes for Order Bumps Layout.
			register_rest_route( $this->namespace, '/' . 'funnels' . '/steps/order/save' . '/(?P<step_id>[\d]+)', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'type'    => array(
						'description' => __( 'Substep Type', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'substep_save_order' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Routes for Checkout Save Design Settings.
			register_rest_route( $this->namespace, '/' . 'funnel-thankyou' . '/(?P<step_id>[\d]+)' . '/design/save-settings', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'wfty_save_design_config' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function search_templates( WP_REST_Request $request ) {

			$resp                      = array();
			$resp['success']           = false;
			$resp['data']['templates'] = array();

			do_action( 'wffn_rest_before_get_templates' );

			$templates = WooFunnels_Dashboard::get_all_templates();

			$builder   = ! empty( $request->get_param( 'builder' ) ) ? wffn_clean( $request->get_param( 'builder' ) ) : '';
			$page_type = ! empty( $request->get_param( 'type' ) ) ? wffn_clean( $request->get_param( 'type' ) ) : '';

			$template_data = array();

			if ( ! empty( $page_type ) ) {
				$templates = $templates[ $page_type ];
				if ( ! empty( $builder ) ) {
					$templates = $templates[ $builder ];
				}

				$template_data['funnel'] = $templates;
				$templates               = $template_data;
			}

			if ( is_array( $templates ) && count( $templates ) > 0 ) {
				$resp['success']           = true;
				$resp['data']['templates'] = $templates;
			}

			return rest_ensure_response( $resp );
		}

		public function product_list( WP_REST_Request $request ) {
			$term = $request->get_param( 'term' );

			$resp                     = array();
			$resp['success']          = false;
			$resp['data']['products'] = array();

			if ( ! empty( $term ) ) {

				$term = empty( $term ) ? wc_clean( stripslashes( filter_input( INPUT_POST, 'term', FILTER_UNSAFE_RAW ) ) ) : $term; //phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter
				if ( empty( $term ) ) {
					wp_die();
				}

				$data_store = WC_Data_Store::load( 'product' );
				$ids        = $data_store->search_products( $term, '', true, false, 30, [], [] );

				$products = array();
				if ( ! empty( $ids ) ) {
					foreach ( $ids as $id ) {
						$product_object = wc_get_product( $id );

						if ( ! wc_products_array_filter_readable( $product_object ) ) {
							continue;
						}
						$formatted_name = $product_object->get_formatted_name();
						$products[]     = [ 'id' => $product_object->get_id(), 'name' => rawurldecode( wp_strip_all_tags( $formatted_name ) ) ];
					}
				}
				if ( ! empty( $products ) ) {
					$resp['success']          = true;
					$resp['data']['products'] = $products;
				}
			}

			return rest_ensure_response( $resp );
		}

		public function product_search_variant( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'No Product Found', 'funnel-builder' );

			$term       = $request->get_param( 'term' );
			$variations = $request->get_param( 'variations' );
			$type       = $request->get_param( 'type' );

			$type = ! empty( $type ) ? $type : 'checkout';

			if ( isset( $variations ) && true !== $variations ) {
				$variations = false;
			}

			$ids = $this->search_products( $term, $variations );

			$allowed_types = [];
			/**
			 * Products types that are allowed in Product search
			 */

			if ( 'checkout' === $type ) {
				$allowed_types = apply_filters( 'wfacp_offer_product_types', array(
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
			}

			if ( 'bump' === $type ) {
				$allowed_types = apply_filters( 'wfob_offer_product_types', array(
					'simple',
					'variable',
					'course',
					'variation',
					'subscription',
					'variable-subscription',
					'subscription_variation',
					'bundle',
					'yith_bundle',
					'woosb',
				) );
			}

			if ( 'offer' === $type || 'upsell' === $type ) {
				$allowed_types = apply_filters( 'wfocu_offer_product_types', array(
					'simple',
					'variable',
					'variation',
				) );
			}

			$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_editable' );
			$product_objects = array_filter( $product_objects, function ( $arr ) use ( $allowed_types ) {
				return $arr && is_a( $arr, 'WC_Product' ) && in_array( $arr->get_type(), $allowed_types, true );
			} );
			$products        = array();
			foreach ( $product_objects as $product_object ) {

				if ( 'publish' === $product_object->get_status() ) {

					$product_image        = ! empty( wp_get_attachment_thumb_url( $product_object->get_image_id() ) ) ? wp_get_attachment_thumb_url( $product_object->get_image_id() ) : WFFN_PLUGIN_URL . '/admin/assets/img/product_default_icon.jpg';
					$product_availability = wffn_rest_api_helpers()->get_availability_price_text( $product_object->get_id() );
					$product_stock        = $product_availability['text'];
					$stock_status         = ( $product_object->is_in_stock() ) ? true : false;

					if ( is_a( $product_object, 'WC_Product_Variation' ) ) {

						$variation_name = wffn_rest_api_helpers()->get_name_part( $product_object->get_name(), 1 );

						$products[] = array(
							'id'                   => $product_object->get_id(),
							'product'              => rawurldecode( $product_object->get_title() ),
							'product_attribute'    => $variation_name,
							'product_price'        => $product_availability['price'],
							'product_image'        => $product_image,
							'product_stock'        => $product_stock,
							'product_stock_status' => $stock_status,
							'currency_symbol'      => get_woocommerce_currency_symbol(),
							'product_type'         => $product_object->get_type()
						);

					} else {

						$products[] = array(
							'id'                   => $product_object->get_id(),
							'product'              => rawurldecode( $product_object->get_title() ),
							'product_attribute'    => '',
							'product_price'        => $product_availability['price'],
							'product_image'        => $product_image,
							'product_stock'        => $product_stock,
							'product_stock_status' => $stock_status,
							'product_type'         => $product_object->get_type(),
							'currency_symbol'      => get_woocommerce_currency_symbol(),
						);

					}
				}
			}
			$products = apply_filters( 'wffn_woocommerce_json_search_found_products', $products );

			if ( count( $products ) ) {
				$resp['success']          = true;
				$resp['data']['products'] = $products;
				$resp['msg']              = __( 'Products Loaded', 'funnel-builder' );
			}

			return rest_ensure_response( $resp );
		}

		public function search_coupons( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'No Coupon Found', 'funnel-builder' );

			$term = $request->get_param( 'term' );

			if ( empty( $term ) ) {
				rest_ensure_response( $resp );
			}

			$ids = array();
			// Search by ID.
			if ( is_numeric( $term ) ) {
				$coupon = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'post__in'         => array( intval( $term ) ),
					'post_type'        => 'shop_coupon',
					'fields'           => 'ids',
					'numberposts'      => 100,
					'paged'            => 1,
					'suppress_filters' => false,
				) );
				if ( count( $coupon ) > 0 ) {
					$ids = array( current( $coupon ) );
				}
			}

			$args = array(
				'post_type'        => 'shop_coupon',
				'numberposts'      => 100,
				'paged'            => 1,
				's'                => $term,
				'post_status'      => 'publish',
				'suppress_filters' => false,
			);

			$posts = get_posts( $args ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
			if ( $posts && is_array( $posts ) && count( $posts ) > 0 ) {
				foreach ( $posts as $post ) {
					array_push( $ids, $post->ID );
					$ids = array_unique( $ids );
				}
			}

			$found_coupons = array();

			foreach ( $ids as $id ) {
				$coupon_title    = sprintf( /* translators: $1: coupon title */ esc_html__( '%1$s', 'woocommerce' ), get_the_title( $id ) );
				$coupon['id']    = sanitize_title( $coupon_title );
				$coupon['name']  = $coupon_title;
				$found_coupons[] = $coupon;
			}

			if ( count( $found_coupons ) ) {
				$resp['success']         = true;
				$resp['data']['coupons'] = $found_coupons;
				$resp['msg']             = __( 'Coupons Loaded', 'funnel-builder' );
			}

			return rest_ensure_response( $resp );
		}

		public function search_customers( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'No Customer Found', 'funnel-builder' );

			$term = $request->get_param( 'term' );

			if ( empty( $term ) ) {
				rest_ensure_response( $resp );
			}

			$ids = array();

			$limit = 0;

			if ( empty( $term ) ) {
				wp_die();
			}

			// Search by ID.
			if ( is_numeric( $term ) ) {
				$customer = new WC_Customer( intval( $term ) );

				// Customer does not exists.
				if ( 0 !== $customer->get_id() ) {
					$ids = array( $customer->get_id() );
				}
			}

			// Usernames can be numeric so we first check that no users was found by ID before searching for numeric username, this prevents performance issues with ID lookups.
			if ( empty( $ids ) ) {
				$data_store = WC_Data_Store::load( 'customer' );

				// If search is smaller than 3 characters, limit result set to avoid
				// too many rows being returned.
				if ( 3 > strlen( $term ) ) {
					$limit = 20;
				}
				$ids = $data_store->search_customers( $term, $limit );
			}

			$found_customers = array();

			if ( ! empty( $_GET['exclude'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$ids = array_diff( $ids, array_map( 'absint', (array) wp_unslash( $_GET['exclude'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
			}
			$customers = [];
			foreach ( $ids as $id ) {
				$customer = new WC_Customer( $id );
				/* translators: 1: user display name 2: user ID 3: user email */
				$customers['id']   = (string) $id;
				$customers['name'] = sprintf( /* translators: $1: customer name, $2 customer id, $3: customer email */ esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'woocommerce' ), $customer->get_display_name(), $customer->get_id(), $customer->get_email() );;
				$found_customers[] = $customers;
			}

			if ( count( $found_customers ) ) {
				$resp['success']           = true;
				$resp['data']['customers'] = $found_customers;
				$resp['msg']               = __( 'Customers Loaded', 'funnel-builder' );
			}

			return rest_ensure_response( $resp );
		}

		public function sanitize_custom( $data, $skip_clean = 0 ) {
			$data = json_decode( $data, true );

			if ( 0 === $skip_clean ) {
				return wffn_clean( $data );
			}

			return $data;
		}

		public function import_upsell_template( $args ) {
			$resp     = array(
				'success' => false,
				'msg'     => __( 'Importing of template failed', 'funnel-builder' ),
			);
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$offer_id = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';
			$id       = get_post_meta( $offer_id, '_funnel_id', true );

			$meta = get_post_meta( $offer_id, '_wfocu_setting', true );
			$meta = ! empty( $meta ) ? $meta : new stdClass();

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return $resp;
			}
			if ( is_object( $meta ) || empty( $meta ) ) {
				$meta->template       = $template;
				$meta->template_group = $builder;

				$result = WFOCU_Core()->importer->maybe_import_data( $builder, $template, $offer_id, $meta );
				if ( is_string( $result ) ) {
					$resp['success'] = false;
					$resp['msg']     = $result;
				} else {
					WFOCU_Common::update_offer( $offer_id, $meta );

					if ( '' !== $id ) {
						WFOCU_Common::update_funnel_time( $id );
					}
					$resp['success'] = true;
					$resp['msg']     = __( 'Importing of template finished', 'funnel-builder' );
				}
			}

			return rest_ensure_response( $resp );
		}

		public static function remove_wc_template( $step_id ) {

			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			if ( ! empty( $step_id ) && absint( $step_id ) > 0 ) {
				$step_id = absint( $step_id );

				$option = WFACP_SLUG . '_c_' . $step_id;
				delete_option( $option );
				delete_post_meta( $step_id, '_wfacp_selected_design' );
				do_action( 'wfacp_template_removed', $step_id );

				// Remove Template Meta key.
				global $wpdb;
				$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_et_pb_use_builder', 'post_id' => $step_id ) );
				$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'tcb_editor_enabled', 'post_id' => $step_id ) );

				$resp = array(
					'msg'     => __( 'Design Saved Successfully', 'funnel-builder' ),
					'success' => true,
				);
			}

			return rest_ensure_response( $resp );
		}

		public static function remove_upsell_template( $step_id, $offer_id ) {

			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			if ( ! empty( $step_id ) && absint( $step_id ) > 0 && absint( $offer_id ) > 0 ) {
				$step_id = absint( $step_id );

				$offer     = $offer_id;
				$funnel_id = $step_id;
				$meta      = get_post_meta( $offer, '_wfocu_setting', true );

				if ( is_object( $meta ) ) {
					$meta->template       = '';
					$meta->template_group = '';
					WFOCU_Common::update_offer( $offer, $meta );

					do_action( 'wfocu_template_removed', $offer );
				}
				if ( '' !== $funnel_id ) {
					WFOCU_Common::update_funnel_time( $funnel_id );
				}

				$resp = array(
					'msg'     => __( 'Design Saved Successfully', 'funnel-builder' ),
					'success' => true,
				);
			}

			return rest_ensure_response( $resp );
		}

		public function wc_get_customsettings( $values ) {
			$tracking_analysis = [];

			$track_event_options = WFACP_Common_Helper::track_events_options();
			$track_event_options = wffn_rest_api_helpers()->array_change_key( $track_event_options, 'id', 'value' );

			$tracking_analysis['override_global_track_event'] = ! empty( $values['override_global_track_event'] ) ? wc_clean( $values['override_global_track_event'] ) : 'false';

			$tracking_analysis['pixel_is_page_view']                     = ! empty( $values['pixel_is_page_view'] ) ? wc_clean( $values['pixel_is_page_view'] ) : 'false';
			$tracking_analysis['pixel_add_to_cart_event']                = ! empty( $values['pixel_add_to_cart_event'] ) ? wc_clean( $values['pixel_add_to_cart_event'] ) : '';
			$tracking_analysis['pixel_add_to_cart_event_position']       = ! empty( $values['pixel_add_to_cart_event_position'] ) ? wc_clean( $values['pixel_add_to_cart_event_position'] ) : '';
			$tracking_analysis['pixel_initiate_checkout_event']          = ! empty( $values['pixel_initiate_checkout_event'] ) ? wc_clean( $values['pixel_initiate_checkout_event'] ) : '';
			$tracking_analysis['pixel_initiate_checkout_event_position'] = ! empty( $values['pixel_initiate_checkout_event_position'] ) ? wc_clean( $values['pixel_initiate_checkout_event_position'] ) : '';
			$tracking_analysis['pixel_add_payment_info_event']           = ! empty( $values['pixel_add_payment_info_event'] ) ? wc_clean( $values['pixel_add_payment_info_event'] ) : '';

			$tracking_analysis['google_ua_is_page_view']                     = ! empty( $values['google_ua_is_page_view'] ) ? wc_clean( $values['google_ua_is_page_view'] ) : 'false';
			$tracking_analysis['google_ua_add_to_cart_event']                = ! empty( $values['google_ua_add_to_cart_event'] ) ? wc_clean( $values['google_ua_add_to_cart_event'] ) : '';
			$tracking_analysis['google_ua_add_to_cart_event_position']       = ! empty( $values['google_ua_add_to_cart_event_position'] ) ? wc_clean( $values['google_ua_add_to_cart_event_position'] ) : '';
			$tracking_analysis['google_ua_initiate_checkout_event']          = ! empty( $values['google_ua_initiate_checkout_event'] ) ? wc_clean( $values['google_ua_initiate_checkout_event'] ) : '';
			$tracking_analysis['google_ua_initiate_checkout_event_position'] = ! empty( $values['google_ua_initiate_checkout_event_position'] ) ? wc_clean( $values['google_ua_initiate_checkout_event_position'] ) : '';
			$tracking_analysis['google_ua_add_payment_info_event']           = ! empty( $values['google_ua_add_payment_info_event'] ) ? wc_clean( $values['google_ua_add_payment_info_event'] ) : '';

			$tracking_analysis['google_ads_is_page_view']               = ! empty( $values['google_ads_is_page_view'] ) ? wc_clean( $values['google_ads_is_page_view'] ) : 'false';
			$tracking_analysis['google_ads_add_to_cart_event']          = ! empty( $values['google_ads_add_to_cart_event'] ) ? wc_clean( $values['google_ads_add_to_cart_event'] ) : '';
			$tracking_analysis['google_ads_add_to_cart_event_position'] = ! empty( $values['google_ads_add_to_cart_event_position'] ) ? wc_clean( $values['google_ads_add_to_cart_event_position'] ) : '';

			$tracking_analysis['pint_is_page_view']               = ! empty( $values['pint_is_page_view'] ) ? wc_clean( $values['pint_is_page_view'] ) : 'false';
			$tracking_analysis['pint_initiate_checkout_event']    = ! empty( $values['pint_initiate_checkout_event'] ) ? wc_clean( $values['pint_initiate_checkout_event'] ) : 'false';
			$tracking_analysis['pint_add_to_cart_event']          = ! empty( $values['pint_add_to_cart_event'] ) ? wc_clean( $values['pint_add_to_cart_event'] ) : 'false';
			$tracking_analysis['pint_add_to_cart_event_position'] = ! empty( $values['pint_add_to_cart_event_position'] ) ? wc_clean( $values['pint_add_to_cart_event_position'] ) : '';

			$tracking_analysis['tiktok_is_page_view']                     = ! empty( $values['tiktok_is_page_view'] ) ? wc_clean( $values['tiktok_is_page_view'] ) : 'false';
			$tracking_analysis['tiktok_add_to_cart_event']                = ! empty( $values['google_ads_add_to_cart_event'] ) ? wc_clean( $values['google_ads_add_to_cart_event'] ) : '';
			$tracking_analysis['tiktok_add_to_cart_event_position']       = ! empty( $values['tiktok_add_to_cart_event_position'] ) ? wc_clean( $values['tiktok_add_to_cart_event_position'] ) : '';
			$tracking_analysis['tiktok_initiate_checkout_event']          = ! empty( $values['tiktok_initiate_checkout_event'] ) ? wc_clean( $values['tiktok_initiate_checkout_event'] ) : '';
			$tracking_analysis['tiktok_initiate_checkout_event_position'] = ! empty( $values['tiktok_initiate_checkout_event_position'] ) ? wc_clean( $values['tiktok_initiate_checkout_event_position'] ) : '';

			$tracking_analysis['snapchat_is_page_view']                     = ! empty( $values['snapchat_is_page_view'] ) ? wc_clean( $values['snapchat_is_page_view'] ) : 'false';
			$tracking_analysis['snapchat_add_to_cart_event']                = ! empty( $values['snapchat_add_to_cart_event'] ) ? wc_clean( $values['snapchat_add_to_cart_event'] ) : 'false';
			$tracking_analysis['snapchat_add_to_cart_event_position']       = ! empty( $values['snapchat_add_to_cart_event_position'] ) ? wc_clean( $values['snapchat_add_to_cart_event_position'] ) : '';
			$tracking_analysis['snapchat_initiate_checkout_event']          = ! empty( $values['snapchat_initiate_checkout_event'] ) ? wc_clean( $values['snapchat_initiate_checkout_event'] ) : '';
			$tracking_analysis['snapchat_initiate_checkout_event_position'] = ! empty( $values['snapchat_initiate_checkout_event_position'] ) ? wc_clean( $values['snapchat_initiate_checkout_event_position'] ) : '';

			$tabs = array();
			if ( ! empty( $values ) && is_array( $values ) ) {
				$tabs = include_once WFFN_PLUGIN_DIR . '/admin/rest-api-helpers/checkout-settings.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

			}

			return $tabs;
		}

		public function upsell_get_customsettings( $values ) {

			$prop_image = WFOCU_PLUGIN_URL . '/assets/img/funnel-settings-prop.jpg';

			$tabs = [
				'upsell_orders' => [
					'title'    => __( 'Order', 'funnel-builder' ),
					'heading'  => __( 'Order Settings', 'funnel-builder' ),
					'slug'     => 'upsell_ordes',
					'hint'     => '',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'order_behavior',
							'label'  => __( 'Each accepted upsell will be', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'batching',
									'name'  => __( 'Merged with the main order', 'funnel-builder' ),
								],
								1 => [
									'value' => 'create_order',
									'name'  => __( 'Create a new order', 'funnel-builder' ),
								],
							],
						],
						1 => [
							'type'    => 'radios',
							'key'     => 'is_cancel_order',
							'label'   => __( 'Cancel Main Order', 'funnel-builder' ),
							'toggler' => [
								'key'   => 'order_behavior',
								'value' => 'create_order',
							],
							'hint'    => __( 'Enable this setting to cancel the main order when first offer is accepted.', 'funnel-builder' ),
							'values'  => [
								0 => [
									'value' => 'yes',
									'name'  => __( 'Yes', 'funnel-builder' ),
								],
								1 => [
									'value' => 'no',
									'name'  => __( 'No', 'funnel-builder' ),
								],
							],
						],
					],
					'priority' => 10,
					'values'   => [
						'order_behavior'  => ! empty( $values['order_behavior'] ) ? wffn_clean( $values['order_behavior'] ) : 'batching',
						'is_cancel_order' => ! empty( $values['is_cancel_order'] ) ? wffn_clean( $values['is_cancel_order'] ) : 'no',
					],
				],
				'upsell_prices' => [
					'title'    => __( 'Prices', 'funnel-builder' ),
					'heading'  => __( 'Price Settings', 'funnel-builder' ),
					'hint'     => '',
					'slug'     => 'upsell_prices',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'is_tax_included',
							'label'  => __( 'Show Prices with Taxes', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'yes',
									'name'  => __( 'Yes (Recommended)', 'funnel-builder' ),
								],
								1 => [
									'value' => 'no',
									'name'  => __( 'No', 'funnel-builder' ),
								],
							],
						],
					],
					'priority' => 15,
					'values'   => [
						'is_tax_included' => ! empty( $values['is_tax_included'] ) ? wffn_clean( $values['is_tax_included'] ) : 'yes',
					],
				],

				'confirmation_messages' => [
					'title'          => __( 'Confirmation Messages', 'funnel-builder' ),
					'heading'        => __( 'Upsell Confirmation Messages', 'funnel-builder' ),
					'hint'           => __( 'These messages show when buyer\'s upsell order is charged &amp; confirmed. If unable to charge user, a failure message will show.', 'funnel-builder' ),
					'hint_link'      => $prop_image,
					'hint_link_text' => __( 'Click here to learn about these settings.', 'funnel-builder' ),
					'slug'           => 'confirmation_messages',
					'fields'         => [
						0 => [
							'key'         => 'offer_success_message_pop',
							'type'        => 'textArea',
							'label'       => __( 'Upsell Success Message', 'funnel-builder' ),
							'placeholder' => '',
						],
						1 => [
							'key'         => 'offer_failure_message_pop',
							'type'        => 'textArea',
							'label'       => __( 'Upsell Failure Message', 'funnel-builder' ),
							'placeholder' => '',
						],
						2 => [
							'key'         => 'offer_wait_message_pop',
							'type'        => 'textArea',
							'label'       => __( 'Upsell Processing Message', 'funnel-builder' ),
							'placeholder' => '',
						],
					],
					'priority'       => 15,
					'values'         => [
						'offer_success_message_pop' => ! empty( $values['offer_success_message_pop'] ) ? wffn_clean( $values['offer_success_message_pop'] ) : __( 'Congratulations! Your item has been successfully added to the order.', 'funnel-builder' ),
						'offer_failure_message_pop' => ! empty( $values['offer_failure_message_pop'] ) ? wffn_clean( $values['offer_failure_message_pop'] ) : __( 'Sorry! We are unable to add this item to your order.', 'funnel-builder' ),
						'offer_wait_message_pop'    => ! empty( $values['offer_wait_message_pop'] ) ? wffn_clean( $values['offer_wait_message_pop'] ) : __( 'Updating your order...', 'funnel-builder' ),
					],
				],
				'tracking_code'         => [
					'title'    => __( 'External Tracking Code', 'funnel-builder' ),
					'heading'  => __( 'External Tracking Code', 'funnel-builder' ),
					'hint'     => '',
					'slug'     => 'tracking_code',
					'fields'   => [
						0 => [
							'key'         => 'funnel_success_script',
							'type'        => 'textArea',
							'label'       => __( 'Add tracking code to run, this upsells', 'funnel-builder' ),
							'placeholder' => __( 'Place your code here', 'funnel-builder' ),
						],
					],
					'priority' => 15,
					'values'   => [
						'funnel_success_script' => ! empty( $values['funnel_success_script'] ) ? ( $values['funnel_success_script'] ) : '',
					]
				],
			];

			return $tabs;
		}

		public function offer_get_customsettings( $values = array() ) {

			if ( is_object( $values ) && isset( $values->settings ) ) {
				$values = (array) $values->settings;
			} else {
				$values = [];
			}

			$tabs = [
				'dynamic_shipping' => [
					'title'    => __( 'Dynamic Shipping', 'funnel-builder' ),
					'heading'  => __( 'Dynamic Shipping', 'funnel-builder' ),
					'slug'     => 'ship_dynamic',
					'hint'     => '',
					'fields'   => [
						0 => [
							'type'   => 'checklist',
							'key'    => 'ship_dynamic',
							'label'  => __( 'Dynamic Shipping', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => "true",
									'name'  => __( 'Check this box to charge the user separately for the shipping of this item. The cost will be calculated on the fly based on your store\'s configuration and shown to the user upon clicking ‘accept’. Any Flat Shipping charges set above will be overridden by dynamic shipping.', 'funnel-builder' ),
								],
							],
						]
					],
					'priority' => 0,
					'values'   => [
						'ship_dynamic' => ( ! empty( $values['ship_dynamic'] ) && true === wffn_string_to_bool( $values['ship_dynamic'] ) ) ? wffn_clean( (array) 'true' ) : [],
					],
				],
				'ask_confirm'      => [
					'title'    => __( 'Ask Confirmation', 'funnel-builder' ),
					'heading'  => __( 'Ask Confirmation', 'funnel-builder' ),
					'slug'     => 'ask_confirm',
					'hint'     => '',
					'fields'   => [
						0 => [
							'type'   => 'checklist',
							'key'    => 'ask_confirmation',
							'label'  => __( 'Ask Confirmation', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => "true",
									'name'  => __( 'Ask for confirmation every time user accepts this offer. A new side cart will trigger and ask for confirmation if this option is enabled.', 'funnel-builder' ),
								],
							],
						],
					],
					'priority' => 0,
					'values'   => [
						'ask_confirmation' => ( ! empty( $values['ask_confirmation'] ) && true === wffn_string_to_bool( $values['ask_confirmation'] ) ) ? wffn_clean( (array) 'true' ) : [],
					],
				],
				'trackingcode'     => [
					'title'    => __( 'Tracking Code', 'funnel-builder' ),
					'heading'  => __( 'Tracking Code', 'funnel-builder' ),
					'slug'     => 'trackingcode',
					'hint'     => '',
					'fields'   => [
						0 => [
							'type'   => 'checklist',
							'key'    => 'check_add_offer_script',
							'label'  => __( 'Tracking Code', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => "true",
									'name'  => __( 'Add tracking code if the buyer views this offer', 'funnel-builder' ),
								],
							],
						],
						1 => [
							'type'        => 'textarea',
							'key'         => 'upsell_page_track_code',
							'label'       => '',
							'hint'        => '',
							'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'check_add_offer_script',
								'value' => "true",
							]
						],
						2 => [
							'type'   => 'checklist',
							'key'    => 'check_add_offer_purchase',
							'label'  => '',
							'hint'   => '',
							'values' => [
								0 => [
									'value' => "true",
									'name'  => __( 'Add tracking code if the buyer accepts this offer', 'funnel-builder' ),
								],
							],
						],
						3 => [
							'type'        => 'textarea',
							'key'         => 'upsell_page_purchase_code',
							'label'       => '',
							'hint'        => '',
							'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'check_add_offer_purchase',
								'value' => "true",
							]
						],
					],
					'priority' => 10,
					'values'   => [
						'check_add_offer_script'    => ( ! empty( $values['check_add_offer_script'] ) && true === wffn_string_to_bool( $values['check_add_offer_script'] ) ) ? wffn_clean( ( array ) 'true' ) : [],
						'check_add_offer_purchase'  => ( ! empty( $values['check_add_offer_purchase'] ) && true === wffn_string_to_bool( $values['check_add_offer_purchase'] ) ) ? wffn_clean( ( array ) 'true' ) : [],
						'upsell_page_track_code'    => ! empty( $values['upsell_page_track_code'] ) ? ( $values['upsell_page_track_code'] ) : '',
						'upsell_page_purchase_code' => ! empty( $values['upsell_page_purchase_code'] ) ? ( $values['upsell_page_purchase_code'] ) : '',
					],
				],
			];

			$tabs = apply_filters( 'wffn_offer_admin_settings_fields', $tabs, $values );

			return $tabs;
		}


		public function save_pg_settings( $step_id, $post_data, $type ) {
			$resp = array();
			if ( ! empty( $post_data['settings'] ) ) {
				$options = $this->sanitize_custom( $post_data['settings'], true );
			}


			$options['custom_css'] = isset( $options['custom_css'] ) ? htmlentities( $options['custom_css'] ) : '';
			$options['custom_js']  = isset( $options['custom_js'] ) ? htmlentities( $options['custom_js'] ) : '';
			if ( ! empty( $options['select_redirect_page'] ) ) {

				if ( 'optin' === $type || 'optin_ty' === $type ) {
					$options['custom_redirect_page'] = $options['select_redirect_page'];
				}
				if ( 'wc_thankyou' === $type ) {
					$options['custom_redirect_page'] = wffn_clean( wffn_rest_api_helpers()->array_change_key( $options['select_redirect_page'], 'name', 'product' ) );
				}

				unset( $options['select_redirect_page'] );
			}

			update_post_meta( $step_id, 'wffn_step_custom_settings', $options );
			wp_update_post( get_post( $step_id ) );

			$resp['success'] = true;
			$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
			$resp['data']    = array();

			return rest_ensure_response( $resp );
		}

		public function save_checkout_settings( $step_id, $post_data ) {
			$resp    = array();
			$options = [];
			if ( ! empty( $post_data['settings'] ) ) {
				$options                  = $this->sanitize_custom( $post_data['settings'], true );
				$options['header_script'] = isset( $options['header_script'] ) ? $options['header_script'] : '';
				$options['footer_script'] = isset( $options['footer_script'] ) ? $options['footer_script'] : '';
			}

			$saved_settings = WFACP_Common::get_page_settings( $step_id );
			$db_settings    = wp_parse_args( $options, $saved_settings );

			WFACP_Common::update_page_settings( $step_id, $db_settings );

			$resp['success'] = true;
			$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
			$resp['data']    = array();

			return rest_ensure_response( $resp );
		}

		public function save_upsell_settings( $step_id, $post_data ) {
			$resp    = array();
			$options = [];
			if ( ! empty( $post_data['settings'] ) ) {
				$options = $this->sanitize_custom( $post_data['settings'], true );
			}

			$options = WFOCU_Common::maybe_filter_boolean_strings( $options );

			WFOCU_Core()->funnels->save_funnel_options( $step_id, $options );
			WFOCU_Common::update_funnel_time( $step_id );

			$resp['success'] = true;
			$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
			$resp['data']    = array();

			return rest_ensure_response( $resp );
		}

		public function save_offer_settings( $step_id, $options ) {

			$resp = array();

			$offer_meta   = WFOCU_Common::get_offer( $step_id );
			$ship_dynamic = false;


			if ( absint( $step_id ) && ! empty( $options['settings'] ) ) {

				$offer_meta     = ! empty( $offer_meta ) ? $offer_meta : new stdClass();
				$offer_settings = ! empty( $offer_meta->settings ) ? $offer_meta->settings : new stdClass();
				$options        = $this->sanitize_custom( $options['settings'], true );
				$option_values  = new stdClass();

				foreach ( $options as $key => $val ) {
					$option_values->$key = $val;
					/**
					 * handle checklist array data and save in bool
					 */
					if ( is_array( $val ) ) {
						$get_fields = wp_list_pluck( $this->offer_get_customsettings(), 'fields' );
						foreach ( $get_fields as $fields ) {
							foreach ( $fields as $field ) {
								if ( isset( $field['type'] ) && isset( $field['key'] ) && $field['key'] === $key && $field['type'] === 'checklist' ) {
									$option_values->$key = ! empty( $val ) ? true : false;
								}
							}
						}
					}

				}
				$offer_meta->settings = (object) array_merge( (array) $offer_settings, (array) $option_values );
				$ship_dynamic         = ( ! empty( $offer_meta->settings ) && ! empty( $offer_meta->settings->ship_dynamic ) ) ? $offer_meta->settings->ship_dynamic : false;

				WFOCU_Common::update_offer( $step_id, $offer_meta );

				/**
				 * this step needs upsell downsell meta to be checked, as settings have value for terminate
				 */
				$funnel_id       = WFOCU_Core()->offers->get_parent_funnel( $step_id );
				$steps           = WFOCU_Core()->funnels->get_funnel_steps( $funnel_id );
				$upsell_downsell = WFOCU_Core()->funnels->prepare_upsell_downsells( $steps );
				WFOCU_Common::update_funnel_upsell_downsell( $funnel_id, $upsell_downsell );

			}

			$resp['success'] = true;
			$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
			$resp['data']    = array(
				'ship_dynamic' => $ship_dynamic
			);

			return rest_ensure_response( $resp );
		}

		public function save_wfob_settings( $step_id, $post_data ) {
			$resp = array();

			if ( ! empty( $post_data['settings'] ) ) {
				$options       = $this->sanitize_custom( $post_data['settings'] );
				$wfob_settings = WFOB_Common::get_setting_data( $step_id );

				$options = wp_parse_args( $options, $wfob_settings );
				WFOB_Common::update_setting_data( $step_id, $options );
			}

			$resp['success'] = true;
			$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
			$resp['data']    = array();

			return rest_ensure_response( $resp );
		}

		public function get_delete_steps_collection() {
			$params         = array();
			$params['type'] = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,
			);

			return apply_filters( 'wffn_rest_delete_steps_collection', $params );
		}

		public function search_pages( WP_REST_Request $request ) {

			$term = $request->get_param( 'term' );

			$resp                  = array();
			$resp['success']       = false;
			$resp['msg']           = __( 'Failed', 'funnel-builder' );
			$resp['data']['pages'] = array();

			$term  = ( isset( $term ) && wffn_clean( $term ) ) ? stripslashes( wffn_clean( $term ) ) : '';
			$pages = ! empty( $request->get_param( 'pages' ) ) ? stripslashes( wffn_clean( $request->get_param( 'pages' ) ) ) : '';

			if ( empty( $term ) ) {
				return rest_ensure_response( $resp );
			}

			$post_types = [];
			switch ( $pages ) {
				case "wc_thankyou":
					add_filter( 'wffn_exclude_post_types_from_search', function ( $excludes ) {
						array_push( $excludes, 'wfacp_checkout' );

						return $excludes;
					} );
					break;
				case "lifter_lms":
					$post_types = [ 'course' ];
					break;
				case "learndash":
					$post_types = [ 'sfwd-courses' ];
					break;
				default:
					$post_types = [];
			}

			$ids = WFFN_Common::search_page( $term, $post_types );

			$pages = array();

			foreach ( $ids as $id ) {
				$page_data = get_post( $id );
				if ( $page_data instanceof WP_Post ) {
					$pages[] = array(
						'id'   => $id,
						'name' => html_entity_decode( get_the_title( $id ) ),
					);
				}
			}

			if ( count( $pages ) ) {
				$resp['data']['pages'] = $pages;
				$resp['success']       = true;
				$resp['msg']           = __( 'Pages Loaded', 'funnel-builder' );
			}

			return rest_ensure_response( $resp );
		}


		public function get_step( WP_REST_Request $request ) {

			$resp                        = $step = $design = array();
			$additional_tabs             = null;
			$resp['success']             = false;
			$resp['msg']                 = __( 'Failed', 'funnel-builder' );
			$resp['data']['step']        = array();
			$resp['data']['funnel_data'] = array();

			$step_id   = $request->get_param( 'step_id' );
			$type      = $request->get_param( 'type' );
			$funnel_id = $request->get_param( 'funnel_id' );


			if ( ! empty( $type ) ) {
				$type = $this->step_swap_slug( $type );
			}

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( ! empty( $type ) && $this->is_valid_page_type( $type ) ) {
				// Get instance of respective WFFN Page Class.
				$page_class_instance = $this->get_page_class_instance( $type );

				if ( ! empty( $page_class_instance ) ) {
					$resp['success'] = true;
					$resp['msg']     = __( 'Details loaded', 'funnel-builder' );

					if ( is_object( $page_class_instance ) ) {
						$design = $page_class_instance->get_page_design( $step_id );
					}

					if ( 'WFACP_Common' === $page_class_instance ) {
						$design = WFACP_Common::get_page_design( $step_id );
					}

					if ( 'WFOCU_Common' === $page_class_instance ) {

						$resp['success'] = true;

						$resp['data']['is_product'] = false;

						$offers = WFOCU_Core()->offers->get_offer( $step_id );
						$type   = 'upsell';

						$design['template']        = 'build_from_scratch';
						$design['template_active'] = 'no';

						$offer_data = $this->sanitize_custom( wp_json_encode( $offers ), 1 );

						if ( ! empty( $offer_data['products'] ) && count( $offer_data['products'] ) > 0 ) {
							$resp['data']['is_product'] = true;
						}

						if ( ! empty( $offer_data['template'] ) ) {
							unset( $design );
							$resp['success']         = true;
							$design['selected']      = $offer_data['template'];
							$design['selected_type'] = empty( $offer_data['template_group'] ) ? 'customizer' : $offer_data['template_group'];
						}

					}

					if ( empty( $design ) ) {
						return rest_ensure_response( $resp );
					}

					if ( empty( $design['template_active'] ) || ( ! empty( $design['template_active'] ) && 'no' !== $design['template_active'] ) ) {
						$additional_tabs = $this->get_additional_tabs_info( $type, $design, $step_id );
					}

					if ( 'wc_checkout' === $type && 'embed_forms' !== $design['selected_type'] && 'no' !== $design['template_active'] ) {
						$additional_tabs = [];
					}


					$post_data        = get_post( $step_id );
					$post_description = get_post_meta( $step_id, '_post_description', true );

					$step_post = wffn_rest_api_helpers()->get_step_post( $step_id );
					if ( 0 === absint( $funnel_id ) ) {

						if ( isset( $step_post['upsell_id'] ) ) {
							$funnel_id = get_post_meta( $step_post['upsell_id'], '_bwf_in_funnel', true );

						} else {
							$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

						}

					}
					$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
					$resp['data']['step_data']   = $step_post;

					$step['status']             = $step_post['status'];
					$step['post_edit_link']     = ( 'wc_upsells' !== $type ) ? htmlspecialchars_decode( get_edit_post_link( $step_id ) ) : htmlspecialchars_decode( get_edit_post_link( $step_id ) );
					$step['edit_link']          = ! empty( $design['selected_type'] ) ? $this->get_edit_url( $design['selected_type'], $step_id, $type, $funnel_id ) : '';
					$step['view_link']          = $this->get_base_url( $post_data );
					$step['post_title']         = WFFN_Core()->admin->maybe_empty_title( $post_data->post_title );
					$step['post_modified_date'] = ! empty( $post_data->post_modified ) ? date_i18n( get_option( 'date_format' ), strtotime( $post_data->post_modified ) ) : '';
					$step['post_name']          = $post_data->post_name;
					$step['post_description']   = ! empty( $post_description ) ? $post_description : '';

					$step['design'] = $design;

					if ( ! empty( $design['selected_type'] ) ) {

						if ( ! in_array( $design['selected_type'], [ 'elementor', 'divi', 'oxy', 'gutenberg', 'pre_built', 'embed_forms', 'customizer' ], true ) ) {
							$step['other_builder'] = $this->detectActivePageBuilder( $post_data );
						}

						$all_builder = array(
							'oxy'        => 'Oxygen',
							'wp_editor'  => 'Other',
							'customizer' => 'Customizer',
							'pre_built'  => 'Customizer',
						);

						$step['template']               = ! empty( wffn_rest_api_helpers()->get_template_design( $design['selected_type'], $design['selected'], $type ) ) ? wffn_rest_api_helpers()->get_template_design( $design['selected_type'], $design['selected'], $type ) : null;
						$step['design']['builder_name'] = isset( $all_builder[ $design['selected_type'] ] ) ? ucfirst( $all_builder[ $design['selected_type'] ] ) : ucfirst( $design['selected_type'] );


						$all_page_builder = wffn_rest_funnels()->get_all_builders();
						if ( isset( $all_page_builder[ $type ][ $design['selected_type'] ] ) ) {
							$step['design']['builder_name'] = $all_page_builder[ $type ][ $design['selected_type'] ];
						}
						if ( 'embed_forms' === $design['selected_type'] ) {
							$step['design']['builder_name'] = ! empty( $step['template']['name'] ) ? $step['template']['name'] : 'Embed Form';
						}
						if ( $step['design']['selected_type'] === 'custom_page' ) {
							$step['design']['builder_name'] = __( 'Custom Page', 'funnel-builder' );
						}

					}

					$step['additional_tabs'] = $additional_tabs;
					$resp['data']['step']    = $step;

				}

			}

			return rest_ensure_response( $resp );
		}

		public function get_customsettings( WP_REST_Request $request ) {
			$resp            = $tabs = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );
			$resp['data']    = array();

			$step_id   = $request->get_param( 'step_id' );
			$type      = $request->get_param( 'type' );
			$funnel_id = $request->get_param( 'funnel_id' );

			if ( ! empty( $type ) ) {
				$type = $this->step_swap_slug( $type );
			}

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 && ! empty( $type ) && $this->is_valid_page_type( $type ) ) {

				if ( 'optin' === $type ) {
					$resp['data']['step'] = wffn_rest_funnel_modules()->get_step_design( $step_id, $type );
				}

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				if ( 0 === absint( $funnel_id ) ) {
					$upsell_id = get_post_meta( $step_id, '_funnel_id', true );
					$funnel_id = get_post_meta( $upsell_id, '_bwf_in_funnel', true );
				}

				$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data']   = wffn_rest_api_helpers()->get_step_post( $step_id );

				// Get instance of respective WFFN Page Class.
				$page_class_instance = $this->get_page_class_instance( $type );
				if ( ! empty( $page_class_instance ) ) {
					$resp['success'] = true;
					$resp['msg']     = __( 'Details loaded', 'funnel-builder' );

					if ( is_object( $page_class_instance ) ) {
						// Get Custom settings from Core Optin Page Class.
						$custom_settings = WFOPP_Core()->optin_pages->setup_custom_options( $step_id );
						$tabs            = $page_class_instance->get_settings_tab_data( $custom_settings );
					}

					if ( 'WFACP_Common' === $page_class_instance && 'wc_checkout' === $type ) {
						$custom_settings = WFACP_Common::get_page_settings( $step_id );
						$tabs            = $this->wc_get_customsettings( $custom_settings );
					}

					if ( 'WFOCU_Common' === $page_class_instance && 'wc_upsells' === $type ) {
						$custom_settings = get_post_meta( $step_id, '_wfocu_settings', true );
						$tabs            = $this->upsell_get_customsettings( $custom_settings );
					}

					if ( 'WFOCU_Common' === $page_class_instance && 'offer' === $type ) {
						$custom_settings = WFOCU_Common::get_offer( $step_id );
						$tabs            = $this->offer_get_customsettings( $custom_settings );
					}

					$resp['data']['settings'] = $tabs;
				}
			}

			return rest_ensure_response( $resp );
		}

		public function update_customsettings( WP_REST_Request $request ) {
			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );
			$resp['data']    = array();

			$step_id  = $request->get_param( 'step_id' );
			$settings = ! empty( $request->get_body() ) ? $request->get_body() : $request->get_param( 'settings' );
			$type     = $request->get_param( 'type' );

			if ( ! empty( $type ) ) {
				$type = $this->step_swap_slug( $type );
			}


			if ( absint( $step_id ) > 0 && ! empty( $settings ) && 0 !== $settings && $this->is_valid_page_type( $type ) ) {

				$options = $this->sanitize_custom( $settings, true );

				if ( is_array( $options ) ) {

					if ( 'landing' === $type || 'optin' === $type || 'optin_ty' === $type || 'wc_thankyou' === $type || 'optin-confirmation' === $type || 'thankyou' === $type ) {
						return $this->save_pg_settings( $step_id, $options, $type );
					}

					if ( 'wc_checkout' === $type || 'checkout' === $type ) {
						return $this->save_checkout_settings( $step_id, $options );
					}

					if ( 'wc_upsells' === $type || 'upsell' === $type ) {
						return $this->save_upsell_settings( $step_id, $options );
					}

					if ( 'wfob' === $type || 'bump' === $type ) {
						return $this->save_wfob_settings( $step_id, $options );
					}

					if ( 'offer' === $type ) {
						return $this->save_offer_settings( $step_id, $options );
					}

				}
			}

			return rest_ensure_response( $resp );
		}

		public function get_page_class_instance( $type ) {
			$instance = '';

			if ( $this->is_valid_page_type( $type ) ) {

				switch ( $type ) {
					case 'optin':
						return WFFN_Optin_Pages::get_instance();
					case 'thankyou':
					case 'wc_thankyou':
						return WFFN_Thank_You_WC_Pages::get_instance();
					case 'optin_ty':
					case 'optin-confirmation':
						return WFFN_Optin_TY_Pages::get_instance();
					case 'landing':
						return WFFN_Landing_Pages::get_instance();

					case 'wc_checkout':
					case 'checkout':
						return 'WFACP_Common';
					case 'upsell':
					case 'wc_upsells':
					case 'offer':
						return 'WFOCU_Common';
					default:
						return '';
				}
			}

			return $instance;
		}

		public function remove_design( WP_REST_Request $request ) {
			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			$step_id = $request->get_param( 'step_id' );
			$type    = $request->get_param( 'type' );

			if ( ! empty( $type ) ) {
				$type = $this->step_swap_slug( $type );
			}

			if ( isset( $step_id ) && absint( $step_id ) > 0 && $this->is_valid_page_type( $type ) ) {
				$step_id = absint( $step_id );

				if ( 'wc_checkout' === $type ) {
					return $this->remove_wc_template( $step_id );
				}

				if ( 'offer' === $type ) {
					$upsell_id = get_post_meta( $step_id, '_funnel_id', true );

					return $this->remove_upsell_template( $upsell_id, $step_id );
				}

				$template                    = $this->default_design_data();
				$template['template_active'] = 'no';

				$this->update_page_design( $step_id, $template, $type );

				do_action( 'wfop_template_removed', $step_id );
				do_action( 'woofunnels_module_template_removed', $step_id );

				$args = array(
					'ID'           => $step_id,
					'post_content' => '',
				);
				wp_update_post( $args );

				$resp = array(
					'msg'     => __( 'Design Saved Successfully', 'funnel-builder' ),
					'success' => true,
				);
			}

			return rest_ensure_response( $resp );
		}

		public function save_design( WP_REST_Request $request ) {
			$resp = array(
				'msg'            => __( 'Failed', 'funnel-builder' ),
				'success'        => false,
				'builder_status' => null,
			);

			$step_id = ! empty( $request->get_param( 'step_id' ) ) ? absint( $request->get_param( 'step_id' ) ) : 0;
			$type    = ! empty( $request->get_param( 'type' ) ) ? wffn_clean( $request->get_param( 'type' ) ) : '';
			$design  = ! empty( $request->get_param( 'design' ) ) ? wffn_clean( $request->get_param( 'design' ) ) : '';

			if ( ! empty( $type ) ) {
				$type = $this->step_swap_slug( $type );
			}

			if ( empty( $design ) ) {
				return rest_ensure_response( $resp );
			}

			if ( absint( $step_id ) > 0 && ! empty( $type ) ) {

				$template = $this->sanitize_custom( $design );
				if ( ! is_array( $template ) || count( $template ) === 0 ) {
					return rest_ensure_response( $resp );
				}

				$step_args = array(
					'id'       => $step_id,
					'builder'  => $template['builder'],
					'template' => $template['template'],
				);

				if ( ! empty( $template['builder'] ) ) {
					$builder_status = WFFN_Core()->page_builders->builder_status( $template['builder'] );

					if ( isset( $builder_status['builders_options']['status'] ) && ! empty( $builder_status['builders_options']['status'] ) && 'activated' !== $builder_status['builders_options']['status'] ) {
						return rest_ensure_response( $builder_status );
					}
				}

				$response = [];

				switch ( $type ) {
					case 'landing':
						$response = WFFN_REST_Steps::get_instance()->import_lp_template( $step_args );
						break;
					case 'optin':
						$response = WFFN_REST_Steps::get_instance()->import_op_template( $step_args );
						break;
					case 'optin_ty':
						$response = WFFN_REST_Steps::get_instance()->import_oty_template( $step_args );
						break;
					case 'wc_thankyou':
						$response = WFFN_REST_Steps::get_instance()->import_ty_template( $step_args );
						break;
					case 'wc_checkout':
						$response = WFFN_REST_Steps::get_instance()->import_wc_template( $step_args );
						break;
					case 'offer':
						// Core Upsell Import function imports to first offer only, use this one
						return $this->import_upsell_template( $step_args );
					default:
						break;
				}

				if ( true === $response['status'] ) {
					$resp = array(
						'msg'     => __( 'Design Saved Successfully', 'funnel-builder' ),
						'success' => true,
					);
				} else {
					$resp = $response;
				}
			}

			return rest_ensure_response( $resp );
		}

		// Save form state.
		public function save_state( WP_REST_Request $request ) {
			$resp = array(
				'success' => false,
				'msg'     => __( 'Unable to change state', 'funnel-builder' ),
			);

			$step_id = $request->get_param( 'step_id' );
			$options = $request->get_body();

			if ( absint( $step_id ) && ! empty( $options ) ) {

				$posted_data = $this->sanitize_custom( $options );
				$step_id     = isset( $step_id ) ? absint( $step_id ) : '';

				$route = $request->get_route();

				$is_offer = strpos( $route, 'funnel-offer' );


				if ( isset( $posted_data['status'] ) ) {
					$posted_data['status'] = wffn_string_to_bool( $posted_data['status'] );
					$status                = ( true === $posted_data['status'] ) ? 'publish' : 'draft';
					wp_update_post( array( 'ID' => $step_id, 'post_status' => $status ) );
					do_action( 'wffn_state_toggle_step', $status, $request );


				}

				if ( isset( $posted_data['slug'] ) || isset( $posted_data['title'] ) ) {
					$post_name = ( isset( $posted_data['slug'] ) && ! empty( $posted_data['slug'] ) ) ? $posted_data['slug'] : $posted_data['title'];
					wp_update_post( array( 'ID' => $step_id, 'post_title' => $posted_data['title'], 'post_name' => sanitize_title( $post_name ) ) );
				}

				if ( absint( $is_offer ) > 0 ) {
					$this->update_offer_meta_in_upsell( $step_id, $posted_data );
				}

				$all_data = wffn_rest_api_helpers()->get_step_post( $step_id, true );


				$resp['step_data'] = is_array( $all_data ) && isset( $all_data['step_data'] ) ? $all_data['step_data'] : false;


				$resp['success']   = true;
				$resp['step_list'] = is_array( $all_data ) && isset( $all_data['step_list'] ) ? $all_data['step_list'] : false;
				$resp['msg']       = __( 'Status changed successfully', 'funnel-builder' );

			}

			return rest_ensure_response( $resp );
		}


		// Get edit url for Page id with respect to builder.
		public function get_edit_url( $builder, $page_id, $type, $funnel_id = 0 ) {

			$builder = ( 'embed_forms' === $builder ) ? 'customizer' : $builder;
			$builder = ( 'pre_built' === $builder ) ? 'customizer' : $builder;
			switch ( $builder ) {
				case 'divi':
					$edit_url = add_query_arg( array( 'p' => $page_id, 'et_fb' => true, 'PageSpeed' => 'off' ), site_url() );
					break;

				case 'elementor':
					$edit_url = add_query_arg( array( 'post' => $page_id, 'action' => 'elementor' ), admin_url( 'post.php' ) );
					break;

				case 'oxy':
					$edit_url = add_query_arg( array( 'ct_builder' => true ), get_the_permalink( $page_id ) );
					break;
				case 'customizer':
					$edit_url = add_query_arg( array( 'ct_builder' => true ), get_the_permalink( $page_id ) );
					break;

				default:
					$edit_url = htmlspecialchars_decode( get_edit_post_link( $page_id ) );
					break;
			}

			if ( 'wc_checkout' === $type && 'customizer' === $builder ) {
				$url = add_query_arg( [
					'wfacp_customize' => 'loaded',
					'wfacp_id'        => $page_id,
				], get_the_permalink( $page_id ) );

				$return_url = add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-checkout/" . $page_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) );

				$edit_url = add_query_arg( [
					'url'             => apply_filters( 'wfacp_customize_url', urlencode_deep( $url ), $this ),
					'wfacp_customize' => 'loaded',
					'wfacp_id'        => $page_id,
					'return'          => rawurlencode( $return_url ),
				], admin_url( 'customize.php' ) );
			}
			if ( 'wc_checkout' === $type && 'divi' === $builder ) {
				$edit_url = add_query_arg( [ 'et_wfacp_id' => $page_id ], $edit_url );
			}
			if ( 'upsell' === $type && 'customizer' === $builder ) {
				$url = add_query_arg( [
					'wfocu_customize' => 'loaded',
					'offer_id'        => $page_id,
				], get_the_permalink( $page_id ) );

				$return_url = add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-offer/" . $page_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) );

				$edit_url = add_query_arg( [
					'url'             => urlencode_deep( $url ),
					'wfocu_customize' => 'loaded',
					'offer_id'        => $page_id,
					'return'          => rawurlencode( $return_url ),
				], admin_url( 'customize.php' ) );

			}

			if ( 'upsell' === $type && 'custom_page' === $builder ) {
				$custom_page = get_post_meta( $page_id, '_wfocu_custom_page', true );
				if ( ! empty( $custom_page ) ) {
					$edit_url = add_query_arg( array( 'post' => $custom_page, 'action' => 'edit' ), admin_url( 'post.php' ) );
				}

			}

			return $edit_url;
		}


		// Get details for Customization configuration.
		public function get_customization_config( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );
			$resp['data']    = array();

			$step_id = $request->get_param( 'step_id' );

			if ( absint( $step_id ) > 0 ) {
				$fetch_fonts_list = bwf_get_fonts_list();

				$fonts_json = str_replace( array( 'id', 'name' ), array( 'label', 'value' ), wp_json_encode( $fetch_fonts_list, 1 ) );

				$resp['data']['fonts_list'] = json_decode( $fonts_json, 1 );
				$resp['success']            = true;
				$resp['msg']                = __( 'Configuration Loaded', 'funnel-builder' );
			}

			return rest_ensure_response( $resp );
		}

		// Get details for Customize design.
		public function get_customization_options( WP_REST_Request $request ) {
			$resp                 = $step = $design = array();
			$customization_tab    = null;
			$resp['success']      = false;
			$resp['msg']          = __( 'Failed', 'funnel-builder' );
			$resp['data']['step'] = array();

			$step_id   = $request->get_param( 'step_id' );
			$funnel_id = $request->get_param( 'funnel_id' );
			$type      = $request->get_param( 'type' );

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 && ! empty( $type ) && $this->is_valid_page_type( $type ) ) {

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data']   = wffn_rest_api_helpers()->get_step_post( $step_id );
				// Get instance of respective WFFN Page Class.
				$page_class_instance = $this->get_page_class_instance( $type );

				if ( ! empty( $page_class_instance ) ) {
					$resp['success']       = true;
					$resp['msg']           = __( 'Details loaded', 'funnel-builder' );
					$step['is_pro_active'] = WFFN_Common::wffn_is_funnel_pro_active();

					if ( is_object( $page_class_instance ) ) {
						$design         = $page_class_instance->get_page_design( $step_id );
						$step['design'] = $design;
					}

					if ( empty( $design ) || ! isset( $design['selected_type'] ) ) {
						return rest_ensure_response( $resp );
					}

					$customization_tab['template_active'] = ! empty( $design['template_active'] ) ? $design['template_active'] : 'yes';
					$form_fields                          = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $step_id );

					$fields           = WFFN_Optin_Pages::get_instance()->get_page_layout( $step_id );
					$formatted_fields = WFFN_REST_OPTIN_API_EndPoint::get_instance()->format_op_form_fields( $fields, true, true );

					$customization_tab['form_fields'] = $formatted_fields['single_step'][0]['fields'];

					$customization_form                = WFOPP_Core()->optin_pages->form_builder->get_form_customization_option( 'all', $step_id );
					$customization_tab['initialValue'] = array();

					if ( ! empty( $customization_tab['form']['initial_value'] ) ) {
						$customization_tab['initialValue'] = ! empty( $customization_tab['form']['initial_value'] ) ? $customization_tab['form']['initial_value'] : array();
						unset( $customization_tab['form']['initial_value'] );
					}


					if ( defined( 'WFOPP_PRO_PLUGIN_FILE' ) ) {
						$customization_tab['scripts'] = plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/js/intltelinput.min.js';
						$customization_tab['styles']  = plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/css/phone-flag.css';
					}
					$form_controller = WFOPP_Core()->form_controllers->get_integration_object( 'form' );
					ob_start();
					$form_controller->frontend_render_form( $step_id, 'inline' );
					$inline_form = ob_get_contents();
					ob_clean();

					$form_controller->frontend_render_form( $step_id, 'popover' );
					$pop_form = ob_get_contents();

					ob_end_clean();

					$customization_tab['preview'] = [
						'inline' => $inline_form,
						'popup'  => $pop_form,
					];
					if ( defined( 'WFFN_PRO_VERSION' ) ) {
						$customization_tab['field_values'] = $this->get_form_customization_options( $customization_form, $form_fields, 'popup' );
					} else {
						$customization_tab['field_values'] = $this->get_form_customization_options( $customization_form, $form_fields, 'inline' );
					}

					$step['customization_tab'] = $customization_tab;
					$resp['data']['step']      = $step;

				}
			}


			return rest_ensure_response( $resp );
		}

		// Save customization details.
		public function save_customization_options( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$step_id     = $request->get_param( 'step_id' );
			$data        = $request->get_body();
			$posted_data = $this->sanitize_custom( $data );

			if ( absint( $step_id ) > 0 && ! empty( $data ) && $posted_data['fields'] ) {

				$form_fields    = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $step_id );
				$customizations = $this->sanitize_custom( $posted_data['fields'] );

				$optin_id = absint( $step_id );

				$customization_data = $this->pasre_customize_form_fields( $customizations, $form_fields );

				if ( ! empty( $customization_data['customization_fields'] ) ) {
					WFFN_Optin_Pages::get_instance()->form_builder->save_form_customizations( $optin_id, $customization_data['customization_fields'] );
				}

				if ( ! empty( $customization_data['form_field_width'] ) ) {
					WFFN_Optin_Pages::get_instance()->form_builder->save_form_field_width( $optin_id, wp_json_encode( $customization_data['form_field_width'] ) );
				}

				$resp['success'] = true;
				$resp['msg']     = __( 'Customizations saved', 'funnel-builder' );

			}

			return rest_ensure_response( $resp );
		}

		// Get form customization Options.
		public function get_form_customization_options( $form_options, $form_fields, $form_type = 'inline' ) {
			$customization_options = array();

			if ( is_array( $form_options ) ) {
				if ( 'inline' === $form_type ) {
					$default_form_customizations = WFOPP_Core()->optin_pages->form_builder->form_customization_settings_default( 0 );
					$customization_options       = array_intersect_key( $form_options, $default_form_customizations );
				} elseif ( 'popup' === $form_type && class_exists( 'WFFN_Pro_Optin_Pages' ) ) {
					$customization_options = $form_options;
				}

				if ( count( $form_fields ) ) {
					foreach ( $form_fields as $fields ) {
						$customization_options['initial_value'][ $fields['InputName'] ] = wc_clean( trim( $fields['default'] ) );
						$customization_options[ $fields['InputName'] ]                  = $fields['width'];
					}
				}
			}

			return $customization_options;
		}

		public function pasre_customize_form_fields( $customization_data, $form_fields ) {
			$customization_fields = $form_field_width = array();
			if ( is_array( $customization_data ) && is_array( $form_fields ) ) {
				$field_columns = array_values( array_column( $form_fields, 'InputName' ) );
				foreach ( $customization_data as $key => $c_data ) {
					if ( ! in_array( $key, $field_columns, true ) ) {
						if ( isset( $customization_data[ $key ] ) ) {
							$customization_fields[ $key ] = $c_data;
						}
					} elseif ( in_array( $key, $field_columns, true ) ) {
						if ( isset( $customization_data[ $key ] ) ) {
							$form_field_width[ $key ] = $c_data;
						}
					}
				}
			}

			return array(
				'customization_fields' => $customization_fields,
				'form_field_width'     => $form_field_width,
			);
		}

		public function update_offer_meta_in_upsell( $offer_id, $data ) {
			if ( class_exists( 'WFOCU_Core' ) && absint( $offer_id ) > 0 && is_array( $data ) && count( $data ) > 0 ) {
				$step_id = get_post_meta( $offer_id, '_funnel_id', true );
				$steps   = WFOCU_Core()->funnels->get_funnel_steps( $step_id );

				$update_steps = [];
				if ( $steps && is_array( $steps ) && count( $steps ) > 0 ) {
					foreach ( $steps as $key => $step ) {
						if ( ! empty( $step ) ) {
							if ( intval( $step['id'] ) === absint( $offer_id ) ) {

								if ( isset( $data['status'] ) ) {
									$step['state'] = ( true === $data['status'] ) ? '1' : '0';
								}
								$step['name'] = isset( $data['title'] ) ? $data['title'] : $step['name'];
								$step['slug'] = isset( $data['slug'] ) ? $data['slug'] : $step['slug'];
								$step         = WFOCU_Core()->offers->filter_step_object_for_db( $step );

							}
							$update_steps[ $key ] = $step;
						}
					}
					$upsell_downsell = WFOCU_Core()->funnels->prepare_upsell_downsells( $update_steps );

					WFOCU_Common::update_funnel_steps( $step_id, $update_steps );
					WFOCU_Common::update_funnel_upsell_downsell( $step_id, $upsell_downsell );

				}

			}
		}


		public function default_design_data() {
			return array(
				'selected'        => 'wp_editor_1',
				'selected_type'   => 'wp_editor',
				'template_active' => 'no',
			);
		}

		public function update_page_design( $page_id, $data, $type ) {
			if ( $page_id < 1 ) {
				return $data;
			}

			if ( ! is_array( $data ) ) {
				$data = $this->default_design_data();
			}

			switch ( $type ) {
				case 'optin':
					update_post_meta( $page_id, '_wfop_selected_design', $data );
					break;
				case 'wc_thankyou':
					update_post_meta( $page_id, '_wftp_selected_design', $data );
					break;
				case 'landing':
					update_post_meta( $page_id, '_wflp_selected_design', $data );
					break;
				case 'optin_ty':
					update_post_meta( $page_id, '_wfoty_selected_design', $data );
					break;
				case 'upsell':
					$meta = get_post_meta( $page_id, '_wfocu_setting', true );
					if ( is_object( $meta ) ) {
						$meta->template       = '';
						$meta->template_group = '';
						WFOCU_Common::update_offer( $page_id, $meta );

					}
					break;
				default:
					break;
			}

			if ( isset( $data['selected_type'] ) && 'wp_editor' === $data['selected_type'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'wfop - boxed . php' );
			} else {
				update_post_meta( $page_id, '_wp_page_template', 'wfop - canvas . php' );
			}

			// Perform do action template removed after Design Update post meta.
			switch ( $type ) {
				case 'optin':
					do_action( 'wfop_template_removed', $page_id );
					break;
				case 'wc_thankyou':
					do_action( 'wftp_template_removed', $page_id );
					break;
				case 'landing':
					do_action( 'wflp_template_removed', $page_id );
					break;
				case 'optin_ty':
					do_action( 'wfoty_template_removed', $page_id );
					break;
				case 'upsell':
					do_action( 'wfocu_template_removed', $page_id );
					break;
				default:
					break;
			}

			return $data;
		}

		public function wcty_design_settings( $step_id = null ) {

			$typography_fonts = [];
			$fonts_list       = bwf_get_fonts_list();

			if ( ! empty( $fonts_list ) ) {
				$fonts_list = wffn_rest_api_helpers()->array_change_key( $fonts_list, 'id', 'key' );
				$fonts_list = wffn_rest_api_helpers()->array_change_key( $fonts_list, 'name', 'label' );

				foreach ( $fonts_list as $font ) {
					$font['value']      = $font['key'];
					$typography_fonts[] = $font;
				}

			}

			$default_models = [
				'txt_color'                           => '#444444',
				'txt_fontfamily'                      => 'default',
				'txt_font_size'                       => 15,
				'head_color'                          => '#444444',
				'head_font_size'                      => 20,
				'head_font_weight'                    => 'default',
				'layout_settings'                     => '2c',
				'order_details_heading'               => __( 'Order Details', 'funnel-builder' ),
				'customer_details_heading'            => __( 'Customer Details', 'funnel-builder' ),
				'order_details_img'                   => true,
				'order_downloads_btn_text'            => __( 'Download', 'funnel-builder' ),
				'order_download_heading'              => __( 'Downloads', 'funnel-builder' ),
				'order_downloads_show_file_downloads' => true,
				'order_downloads_show_file_expiry'    => true,
				'order_subscription_heading'          => __( 'Subscription', 'funnel-builder' ),
			];

			$schema = [
				[
					'label'  => __( 'General Typography', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "select",
							'label'   => __( 'Font Family', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'options' => $typography_fonts,
							'key'     => 'txt_fontfamily',
						],
						[
							'type'  => "color",
							'label' => __( 'Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'txt_color',
						],
						[
							'type'  => "number",
							'label' => __( 'Font Size (in px)', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'txt_font_size',
						],
						[
							'type'  => "color",
							'label' => __( 'Heading Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'head_color',
						],
						[
							'type'  => "number",
							'label' => __( 'Heading Font Size (in px)', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'head_font_size',
						],
						[
							'type'    => "select",
							'label'   => __( 'Font Weight', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'options' => [
								[ 'key' => 'default', 'value' => 'default', 'label' => 'Default' ],
								[ 'key' => 'bold', 'value' => 'normal', 'label' => 'Normal' ],
								[ 'key' => 'bold', 'value' => 'bold', 'label' => 'Bold' ],
								[ 'key' => '300', 'value' => '300', 'label' => '300' ],
								[ 'key' => '400', 'value' => '400', 'label' => '400' ],
								[ 'key' => '500', 'value' => '500', 'label' => '500' ],
								[ 'key' => '600', 'value' => '600', 'label' => '600' ],
								[ 'key' => '700', 'value' => '700', 'label' => '700' ],
							],
							'key'     => 'head_font_weight',
						],
					],
				],
				/* Order details */
				[
					'label'  => __( 'Order Details', 'funnel-builder' ),
					"fields" => [
						[
							'type'  => "toggle",
							'label' => __( 'Show Images', 'funnel-builder' ),
							'class' => '',
							'key'   => 'order_details_img',
						],
						[
							'type'  => "text",
							'label' => __( 'Download Button Text', 'funnel-builder' ),
							'class' => '',
							'key'   => 'order_downloads_btn_text',
						],
						[
							'type'  => "toggle",
							'label' => __( 'Show File Downloads Column', 'funnel-builder' ),
							'class' => '',
							'key'   => 'order_downloads_show_file_downloads',
						],
						[
							'type'         => "toggle",
							'label'        => __( 'Show File Expiry Column', 'funnel-builder' ),
							'styleClasses' => 'wfty_design_setting_full',
							'key'          => 'order_downloads_show_file_expiry',
						],
					],
				],
				/* Customer details */
				[
					'label'  => __( 'Customer Details', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "select",
							'label'   => __( 'Layout', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'options' => [
								[ 'key' => '2c', 'value' => '2c', 'label' => 'Two Column' ],
								[ 'key' => 'full_width', 'value' => 'full_width', 'label' => 'Full width' ],
							],
							'key'     => 'layout_settings',
						],
					],
				],
			];

			$models = [];

			if ( absint( $step_id ) > 0 ) {
				$models = get_post_meta( $step_id, '_shortcode_settings', true );
			}

			$models = wp_parse_args( $models, $default_models );

			$models['order_details_img']                   = ! empty( $models['order_details_img'] ) ? bwf_string_to_bool( $models['order_details_img'] ) : false;
			$models['order_downloads_show_file_expiry']    = ! empty( $models['order_downloads_show_file_expiry'] ) ? bwf_string_to_bool( $models['order_downloads_show_file_expiry'] ) : false;
			$models['order_downloads_show_file_downloads'] = ! empty( $models['order_downloads_show_file_downloads'] ) ? bwf_string_to_bool( $models['order_downloads_show_file_downloads'] ) : false;

			return [ 'schema' => $schema, 'values' => $models ];
		}

		public function wfty_save_design_config( WP_REST_Request $request ) {
			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );
			$resp['data']    = array();

			$step_id  = $request->get_param( 'step_id' );
			$settings = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $settings ) && 0 !== $settings ) {

				$options = $this->sanitize_custom( $settings, true );

				$options['order_details_img']                   = ! empty( $options['order_details_img'] ) ? 'true' : 'false';
				$options['order_downloads_show_file_expiry']    = ! empty( $options['order_downloads_show_file_expiry'] ) ? 'true' : 'false';
				$options['order_downloads_show_file_downloads'] = ! empty( $options['order_downloads_show_file_downloads'] ) ? 'true' : 'false';

				update_post_meta( $step_id, '_shortcode_settings', $options );

				if ( is_array( $options ) ) {
					$resp = array(
						'msg'     => __( 'Form Setting Saved', 'funnel-builder' ),
						'success' => true,
					);
				}
			}

			return rest_ensure_response( $resp );
		}

		public function design_settings( $wfacp_id = null ) {

			if ( absint( $wfacp_id ) > 0 ) {
				$models = get_option( WFACP_SLUG . '_c_' . $wfacp_id, true );
			} else {
				$models = WFACP_Common::get_option( '', true );
			}

			$typography_fonts = [];
			$fonts_list       = bwf_get_fonts_list();

			if ( ! empty( $fonts_list ) ) {
				$fonts_list = wffn_rest_api_helpers()->array_change_key( $fonts_list, 'id', 'key' );
				$fonts_list = wffn_rest_api_helpers()->array_change_key( $fonts_list, 'name', 'label' );

				foreach ( $fonts_list as $font ) {
					$font['value']      = $font['key'];
					$typography_fonts[] = $font;
				}

			}

			$default_models       = array(
				'wfacp_form_section_embed_forms_2_step_form_max_width'                          => '420',
				'wfacp_form_section_embed_forms_2_form_border_width'                            => '2',
				'wfacp_form_section_embed_forms_2_disable_steps_bar'                            => true,
				'wfacp_form_section_embed_forms_2_form_border_type'                             => 'double',
				'wfacp_form_section_embed_forms_2_form_border_color'                            => '#e61e1e',
				'wfacp_form_section_embed_forms_2_form_inner_padding'                           => '16',
				'wfacp_form_section_embed_forms_2_name_0'                                       => 'GET YOUR FREE COPY OF AMAZING BOOK1',
				'wfacp_form_section_embed_forms_2_headline_0'                                   => 'Shipped in less than 3 days!3',
				'wfacp_form_section_embed_forms_2_step_heading_font_size'                       => 20,
				'wfacp_form_section_embed_forms_2_heading_fs'                                   => 21,
				'wfacp_form_section_embed_forms_2_heading_font_weight'                          => 'wfacp-normal',
				'wfacp_form_section_embed_forms_2_heading_talign'                               => 'wfacp-text-right',
				'wfacp_form_section_embed_forms_2_sec_heading_color'                            => '#ee2020',
				'wfacp_form_section_embed_forms_2_sec_bg_color'                                 => '#f8f6f6',
				'wfacp_form_section_embed_forms_2_rbox_border_type'                             => 'solid',
				'wfacp_form_section_embed_forms_2_rbox_border_width'                            => '1',
				'wfacp_form_section_embed_forms_2_rbox_padding'                                 => '8',
				'wfacp_form_section_embed_forms_2_rbox_margin'                                  => '10',
				'wfacp_form_section_embed_forms_2_sub_heading_fs'                               => 17,
				'wfacp_form_section_embed_forms_2_sub_heading_font_weight'                      => 'wfacp-bold',
				'wfacp_form_section_embed_forms_2_sub_heading_talign'                           => 'wfacp-text-right',
				'wfacp_form_section_embed_forms_2_sec_sub_heading_color'                        => '#d43e3e',
				'wfacp_form_section_embed_forms_2_field_style_fs'                               => 19,
				'wfacp_form_section_embed_forms_2_step_sub_heading_font_size'                   => 16,
				'wfacp_form_section_embed_forms_2_step_alignment'                               => 'right',
				'wfacp_form_section_ct_active_inactive_tab'                                     => 'active',
				'wfacp_form_section_embed_forms_2_active_step_bg_color'                         => '#d84f4f',
				'wfacp_form_section_embed_forms_2_active_step_text_color'                       => '#ffffff',
				'wfacp_form_section_embed_forms_2_active_step_tab_border_color'                 => '#f6bd88',
				'wfacp_form_section_embed_forms_2_field_border_layout'                          => 'dotted',
				'wfacp_form_section_embed_forms_2_field_border_width'                           => '3',
				'wfacp_form_section_embed_forms_2_field_style_color'                            => '#e62222',
				'wfacp_form_section_embed_forms_2_field_border_color'                           => '#3d2828',
				'wfacp_form_section_embed_forms_2_field_focus_color'                            => '#235c7f',
				'wfacp_form_section_embed_forms_2_field_input_color'                            => '#4e0d0d',
				'wfacp_form_section_payment_methods_heading'                                    => 'Payment method666',
				'wfacp_form_section_payment_methods_sub_heading'                                => '',
				'wfacp_form_section_embed_forms_2_btn_order-place_btn_text'                     => 'PLACE ORDER NOW',
				'wfacp_form_section_embed_forms_2_btn_order-place_fs'                           => 26,
				'wfacp_form_section_embed_forms_2_btn_order-place_top_bottom_padding'           => '17',
				'wfacp_form_section_embed_forms_2_btn_order-place_left_right_padding'           => '25',
				'wfacp_form_section_embed_forms_2_btn_order-place_border_radius'                => '14',
				'wfacp_form_section_embed_forms_2_btn_order-place_btn_font_weight'              => 'normal',
				'wfacp_form_section_embed_forms_2_btn_order-place_width'                        => 'initial',
				'wfacp_form_section_embed_forms_2_btn_order-place_make_button_sticky_on_mobile' => 'no_sticky',
				'wfacp_form_section_embed_forms_2_color_type'                                   => 'hover',
				'wfacp_form_section_embed_forms_2_btn_order-place_bg_color'                     => '#c36a18',
				'wfacp_form_section_embed_forms_2_btn_order-place_text_color'                   => '#e12727',
				'wfacp_form_section_embed_forms_2_additional_text_color'                        => '#c62424',
				'wfacp_form_section_embed_forms_2_additional_bg_color'                          => '#fad5d5',
				'wfacp_form_section_embed_forms_2_validation_color'                             => '#511e1e',
				'wfacp_form_section_embed_forms_2_btn_order-place_bg_hover_color'               => '#111111',
				'wfacp_form_section_embed_forms_2_btn_order-place_text_hover_color'             => '#ffffff',
				'wfacp_form_section_text_below_placeorder_btn'                                  => '* 100% Secure &amp; Safe Payments *555',
				'wfacp_form_section_embed_forms_2_form_content_color'                           => '#46960d',
				'wfacp_form_section_embed_forms_2_form_content_link_color'                      => '#000000',
				'wfacp_form_section_embed_forms_2_section_bg_color'                             => '#fdfbfb',
				'wfacp_form_section_embed_forms_2_form_content_link_color_type'                 => 'hover',
				'wfacp_form_section_embed_forms_2_form_content_link_hover_color'                => '#2e2222',
				'wfacp_form_form_fields_1_embed_forms_2_billing_email'                          => 'wfacp-col-left-half',
				'wfacp_form_form_fields_1_embed_forms_2_billing_email_other_classes'            => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_first_name'                     => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_first_name_other_classes'       => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_last_name'                      => 'wfacp-col-full',
				'wfacp_form_form_fields_1_embed_forms_2_billing_last_name_other_classes'        => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_phone'                          => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_phone_other_classes'            => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_same_as_billing'               => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_same_as_billing_other_classes' => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_address_1'                     => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_address_1_other_classes'       => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_city'                          => 'wfacp-col-full',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_city_other_classes'            => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_postcode'                      => 'wfacp-col-two-third',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_postcode_other_classes'        => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_country'                       => 'wfacp-col-two-third',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_country_other_classes'         => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_state'                         => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_shipping_state_other_classes'           => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_address_1'                      => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_address_1_other_classes'        => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_city'                           => 'wfacp-col-two-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_city_other_classes'             => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_postcode'                       => 'wfacp-col-two-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_country'                        => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_state'                          => 'wfacp-col-left-third',
				'wfacp_form_form_fields_1_embed_forms_2_billing_postcode_other_classes'         => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_country_other_classes'          => 'gg',
				'wfacp_form_form_fields_1_embed_forms_2_billing_state_other_classes'            => 'gg',
				'wfacp_style_typography_embed_forms_2_content_ff'                               => 'Abhaya Libre',
			);
			$models               = wp_parse_args( $models, $default_models );
			$checkout_form_fields = [
				/* Form Style  */
				[
					'label'  => __( 'Form Style', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "number",
							'label'   => __( 'Width', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '640',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'step_form_max_width'
						],
						[
							'type'    => "number",
							'label'   => __( 'Form Padding', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => '16',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'form_inner_padding'
						],
						[
							'type'    => "select",
							'label'   => __( 'Border Type', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third',
							'default' => 'solid',
							'options' => [
								[ 'key' => 'none', 'value' => 'none', 'label' => 'None' ],
								[ 'key' => 'solid', 'value' => 'solid', 'label' => 'Solid' ],
								[ 'key' => 'double', 'value' => 'double', 'label' => 'Double' ],
								[ 'key' => 'dotted', 'value' => 'dotted', 'label' => 'Dotted' ],
								[ 'key' => 'dashed', 'value' => 'dashed', 'label' => 'Dashed' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'form_border_type'
						],
						[
							'type'    => "number",
							'label'   => __( 'Width', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third bwf-field-one-third-last ',
							'default' => '640',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'form_border_width'
						],
						[
							'type'  => "color",
							'label' => __( 'Border Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-third bwf-field-one-third-last ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'form_border_color',
						],
						[
							'type'    => "select",
							'label'   => __( 'Typography', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'options' => $typography_fonts,
							'key'     => 'wfacp_style_typography_embed_forms_2_' . 'content_ff'
						],
					]
				],
				/* Top Bar */
				[
					'label'  => __( 'Top Bar', 'funnel-builder' ),
					"fields" => [
						[
							'type'  => "toggle",
							'label' => __( 'Disable Top Bar', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'disable_steps_bar'
						],
						[
							'type'    => "text",
							'label'   => __( 'Heading', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'name_0',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							]
						],
						[
							'type'    => "text",
							'label'   => __( 'Sub Heading', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last ',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'headline_0',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							]
						],
						[
							'type'    => "number",
							'label'   => __( 'Step Heading (in px)', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'step_heading_font_size',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							]
						],
						[
							'type'    => "number",
							'label'   => __( 'Step Sub Heading (in px)', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'step_sub_heading_font_size',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							],
						],
						[
							'type'    => "select",
							'label'   => __( 'Text Alignment', 'funnel-builder' ),
							'class'   => '',
							'options' => [
								[ 'key' => 'left', 'value' => 'left', 'label' => 'left' ],
								[ 'key' => 'center', 'value' => 'center', 'label' => 'center' ],
								[ 'key' => 'right', 'value' => 'right', 'label' => 'Right' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'step_alignment',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							],
						],
						[
							'type'    => "color",
							'label'   => __( 'Background', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third ',
							'default' => '#e61e1e',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'active_step_bg_color',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							],
						],
						[
							'type'    => "color",
							'label'   => __( 'Text', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third bwf-field-one-third-last',
							'default' => '#e61e1e',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'active_step_text_color',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							]
						],
						[
							'type'    => "color",
							'label'   => __( 'Tab Color', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third  bwf-field-one-third-last',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'active_step_tab_border_color',
							"toggler" => [
								"key"   => 'wfacp_form_section_embed_forms_2_' . "disable_steps_bar",
								"value" => true
							]
						],
					]
				],
				/* Section  */
				[
					'label'  => __( 'Section', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "number",
							'label'   => __( 'Font Size (in px)', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'heading_fs'
						],
						[
							'type'    => "select",
							'label'   => __( 'Font Weight', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => 'wfacp-normal',
							'options' => [
								[ 'key' => 'wfacp-normal', 'value' => 'wfacp-normal', 'label' => 'Normal' ],
								[ 'key' => 'wfacp-bold', 'value' => 'wfacp-bold', 'label' => 'Bold' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'heading_font_weight'
						],
						[
							'type'  => "number",
							'label' => __( 'Margin Bottom', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'rbox_margin'
						],
						[
							'type'  => "number",
							'label' => __( 'Padding (Left and Right)', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'rbox_padding'
						],
						[
							'type'    => "select",
							'label'   => __( 'Text Alignment', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => 'wfacp-text-left',
							'options' => [
								[ 'key' => 'wfacp-text-left', 'value' => 'wfacp-text-left', 'label' => 'Left' ],
								[ 'key' => 'wfacp-text-center', 'value' => 'wfacp-text-center', 'label' => 'Center' ],
								[ 'key' => 'wfacp-text-right', 'value' => 'wfacp-text-right', 'label' => 'Right' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'heading_talign'
						],
						[
							'type'    => "color",
							'label'   => __( 'Section Heading', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => '#e61e1e',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'sec_heading_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Section Background', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'sec_bg_color',
						],
						[
							'type'    => "select",
							'label'   => __( 'Border Type', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third',
							'default' => 'solid',
							'options' => [
								[ 'key' => 'none', 'value' => 'none', 'label' => 'None' ],
								[ 'key' => 'solid', 'value' => 'solid', 'label' => 'Solid' ],
								[ 'key' => 'double', 'value' => 'double', 'label' => 'Double' ],
								[ 'key' => 'dotted', 'value' => 'dotted', 'label' => 'Dotted' ],
								[ 'key' => 'dashed', 'value' => 'dashed', 'label' => 'Dashed' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'rbox_border_type'
						],
						[
							'type'    => "number",
							'label'   => __( 'Width', 'funnel-builder' ),
							'class'   => 'bwf-field-one-third bwf-field-one-third-last',
							'default' => '1',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'rbox_border_width'
						],
						[
							'type'    => "color",
							'label'   => __( 'Color', 'woofunnels-aero-checkout ' ),
							'class'   => 'bwf-field-one-third  bwf-field-one-third-last',
							'default' => '#e61e1e',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'rbox_border_color',
						],
					]
				],
				/* Sub Section  */
				[
					'label'  => __( 'Section Sub heading', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "number",
							'label'   => __( 'Font Size (in px)', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'sub_heading_fs'
						],
						[
							'type'    => "select",
							'label'   => __( 'Font Weight', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => 'normal',
							'options' => [
								[ 'key' => 'wfacp-normal', 'value' => 'wfacp-normal', 'label' => 'Normal' ],
								[ 'key' => 'wfacp-bold', 'value' => 'wfacp-bold', 'label' => 'Bold' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'sub_heading_font_weight'

						],
						[
							'type'    => "select",
							'label'   => __( 'Text Alignment', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half ',
							'default' => 'wfacp-text-left',
							'options' => [
								[ 'key' => 'wfacp-text-left', 'value' => 'wfacp-text-left', 'label' => 'Left' ],
								[ 'key' => 'wfacp-text-center', 'value' => 'wfacp-text-center', 'label' => 'Center' ],
								[ 'key' => 'wfacp-text-right', 'value' => 'wfacp-text-right', 'label' => 'Right' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'sub_heading_talign'
						],
						[
							'type'  => "color",
							'label' => __( 'Section Subheading', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'sec_sub_heading_color',
						],
					]
				],
				/* Field Style */
				[
					'label'  => __( 'Field Style', 'funnel-builder' ),
					"fields" => [
						[
							'type'    => "number",
							'label'   => __( 'Font Size (in px)', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => '',
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'field_style_fs'
						],
						[
							'type'    => "select",
							'label'   => __( 'Field Border Layout', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => 'solid',
							'options' => [
								[ 'key' => 'none', 'value' => 'none', 'label' => 'None' ],
								[ 'key' => 'solid', 'value' => 'solid', 'label' => 'Solid' ],
								[ 'key' => 'double', 'value' => 'double', 'label' => 'Double' ],
								[ 'key' => 'dotted', 'value' => 'dotted', 'label' => 'Dotted' ],
								[ 'key' => 'dashed', 'value' => 'dashed', 'label' => 'Dashed' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'field_border_layout'
						],
						[
							'type'  => "number",
							'label' => __( 'Field Border Width', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'field_border_width'
						],
						[
							'type'  => "color",
							'label' => __( 'Field Label', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'field_style_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Field Border', 'funnel-builder' ),
							'class' => 'bwf-field-one-third ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'field_border_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Field Focus', 'funnel-builder' ),
							'class' => 'bwf-field-one-third bwf-field-one-third-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'field_focus_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Field Value', 'funnel-builder' ),
							'class' => 'bwf-field-one-third bwf-field-one-third-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'field_input_color',
						],
					]
				],
				/* Buttons */
				[
					'label'  => __( 'Buttons', 'funnel-builder' ),
					"fields" => [
						[
							'type'  => "text",
							'label' => __( 'Button Label', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_btn_text'
						],
						[
							'type'  => "number",
							'label' => __( 'Font Size', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_fs'
						],
						[
							'type'  => "number",
							'label' => __( 'Padding Top Bottom', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_top_bottom_padding'
						],
						[
							'type'  => "number",
							'label' => __( 'Padding Left Right', 'funnel-builder' ),
							'class' => 'bwf-field-one-half',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_left_right_padding'
						],
						[
							'type'  => "number",
							'label' => __( 'Border Radius', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_border_radius'
						],
						[
							'type'    => "select",
							'label'   => __( 'Font Weight', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => 'wfacp-normal',
							'options' => [
								[ 'key' => 'normal', 'value' => 'normal', 'label' => 'Normal' ],
								[ 'key' => 'bold', 'value' => 'bold', 'label' => 'Bold' ],
							],

							'key' => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_btn_font_weight'
						],
						[
							'type'    => "select",
							'label'   => __( 'Width', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => 'normal',
							'options' => [
								[ 'key' => '100', 'value' => '100', 'label' => 'Full Width' ],
								[ 'key' => 'initial', 'value' => 'initial', 'label' => 'Normal' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_width'
						],
						[
							'type'    => "select",
							'label'   => __( 'Alignment', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half',
							'default' => 'left',
							'options' => [
								[ 'key' => 'left', 'value' => 'left', 'label' => 'Left' ],
								[ 'key' => 'center', 'value' => 'center', 'label' => 'Center' ],
								[ 'key' => 'right', 'value' => 'right', 'label' => 'Right' ],
							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_talign'
						],
						[
							'type'    => "select",
							'label'   => __( 'Sticky on Mobile', 'funnel-builder' ),
							'class'   => 'bwf-field-one-half bwf-field-one-half-last',
							'default' => 'no_sticky',
							'options' => [
								[ 'key' => 'yes_sticky', 'value' => 'yes_sticky', 'label' => 'Yes' ],
								[ 'key' => 'no_sticky', 'value' => 'no_sticky', 'label' => 'No' ],

							],
							'key'     => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_make_button_sticky_on_mobile'
						],
						[
							'type'  => "color",
							'label' => __( 'Background', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_bg_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Label', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_text_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Background Hover', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_bg_hover_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Label Hover', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'btn_order-place_text_hover_color',
						],
						[
							'type'  => "textarea",
							'label' => __( 'Text Below Place Order Buttons', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'wfacp_form_section_text_below_placeorder_btn'
						],
						[
							'type'  => "color",
							'label' => __( 'Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'additional_text_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Background', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'additional_bg_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Validation Text', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'validation_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Form Content', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'form_content_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Background', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'section_bg_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Form Links Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-half bwf-field-one-half-last',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'form_content_link_color',
						],
						[
							'type'  => "color",
							'label' => __( 'Form Links Hover Color', 'funnel-builder' ),
							'class' => 'bwf-field-one-half ',
							'key'   => 'wfacp_form_section_embed_forms_2_' . 'form_content_link_hover_color',
						],

					]
				],
				//Payment Gateways
				[
					'label'  => __( 'Payment Methods', 'funnel-builder' ),
					"fields" => [
						[
							'type'  => "input",
							'label' => __( 'Heading', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_payment_methods_heading'
						],
						[
							'type'  => "textarea",
							'label' => __( 'Sub heading', 'funnel-builder' ),
							'class' => '',
							'key'   => 'wfacp_form_section_payment_methods_sub_heading'
						]
					]
				],
			];

			$models['wfacp_form_section_embed_forms_2_disable_steps_bar'] = ! empty( $models['wfacp_form_section_embed_forms_2_disable_steps_bar'] ) ? wffn_string_to_bool( $models['wfacp_form_section_embed_forms_2_disable_steps_bar'] ) : false;

			return [ 'schema' => $checkout_form_fields, 'values' => $models ];
		}

		public function get_additional_tabs_info( $page_type, $design, $step_id ) {

			$additional_tabs = array();
			if ( $this->is_valid_page_type( $page_type ) ) {
				switch ( $page_type ) {
					case 'optin':
						if ( isset( $design['selected_type'] ) && 'wp_editor_1' === $design['selected'] ) {
							$additional_tabs[] = [
								'mainHeading' => __( 'Optin Page Settings', 'funnel-builder' ),
								'heading'     => __( 'Form Shortcodes', 'funnel-builder' ),
								'title'       => __( '', 'funnel-builder' ),
								'fields'      => [
									[
										'label' => __( 'Optin Form Shortcode', 'funnel-builder' ),
										'value' => '[wfop_form]',
										'id'    => 'wfop_form'
									],
									[
										'label' => __( 'Optin Popup Link', 'funnel-builder' ),
										'value' => esc_url( site_url() . '/?wfop-popup=yes' ),
										'id'    => 'wfop_popup',
									]
								],
							];
						}
						$additional_tabs[] = [
							'mainHeading' => __( 'Optin Page Settings', 'funnel-builder' ),
							'heading'     => __( 'Personalization Shortcodes', 'funnel-builder' ),
							'title'       => __( '', 'funnel-builder' ),
							'fields'      => [
								[
									'label' => __( 'Optin First Name', 'funnel-builder' ),
									'value' => '[wfop_first_name]',
									'id'    => 'wfop_first_name'
								],
								[
									'label' => __( 'Optin Last Name', 'funnel-builder' ),
									'value' => '[wfop_last_name]',
									'id'    => 'wfop_last_name',
								],
								[
									'label' => __( 'Optin Email', 'funnel-builder' ),
									'value' => '[wfop_email]',
									'id'    => 'wfop_email'
								],
								[
									'label' => __( 'Optin Phone', 'funnel-builder' ),
									'value' => '[wfop_phone]',
									'id'    => 'wfop_phone'
								],
								[
									'label' => __( 'Optin Custom Fields', 'funnel-builder' ),
									'value' => '[wfop_custom key = \'Label\']',
									'id'    => 'wfop_custom'
								]
							],
						];
						break;
					case 'wc_thankyou':
						if ( isset( $design['selected_type'] ) && 'wp_editor_1' === $design['selected'] ) {
							$additional_tabs[] = [
								'mainHeading' => __( 'Thank You Page Settings', 'funnel-builder' ),
								'heading'     => __( 'Order Shortcodes', 'funnel-builder' ),
								'title'       => __( '', 'funnel-builder' ),
								'fields'      => [
									[
										'label' => __( 'Order Details', 'funnel-builder' ),
										'value' => '[wfty_order_details]',
										'id'    => 'wfty_order_details'
									],
									[
										'label' => __( 'Customer Details', 'funnel-builder' ),
										'value' => '[wfty_customer_details]',
										'id'    => 'wfty_customer_details',
									]
								],
							];
						}
						if ( isset( $design['selected_type'] ) && 'oxy' === $design['selected_type'] ) {
							$fields = array(
								[
									'label' => __( 'Customer Email', 'funnel-builder' ),
									'value' => "[oxygen data='phpfunction' function='wfty_customer_email']",
									'id'    => 'wfty_customer_email',
								],
								[
									'label' => __( 'Customer First Name', 'funnel-builder' ),
									'value' => "[oxygen data='phpfunction' function='wfty_customer_first_name']",
									'id'    => 'wfty_first_name'
								],
								[
									'label' => __( 'Customer last Name', 'funnel-builder' ),
									'value' => "[oxygen data='phpfunction' function='wfty_customer_last_name']",
									'id'    => 'wfty_last_name'
								],
								[
									'label' => __( 'Customer Phone Number', 'funnel-builder' ),
									'value' => "[oxygen data='phpfunction' function='wfty_customer_phone_number']",
									'id'    => 'wfty_customer_phone_number'
								],
								[
									'label' => __( 'Order Number', 'funnel-builder' ),
									'value' => "[oxygen data='phpfunction' function='wfty_order_number']",
									'id'    => 'wfty_order_number'
								],
							);
						} else {
							$fields = array(
								[
									'label' => __( 'Customer Email', 'funnel-builder' ),
									'value' => '[wfty_customer_email]',
									'id'    => 'wfty_customer_email',
								],
								[
									'label' => __( 'Customer First Name', 'funnel-builder' ),
									'value' => '[wfty_customer_first_name]',
									'id'    => 'wfty_customer_first_name'
								],
								[
									'label' => __( 'Customer last Name', 'funnel-builder' ),
									'value' => '[wfty_customer_last_name]',
									'id'    => 'wfty_customer_last_name'
								],
								[
									'label' => __( 'Customer Phone Number', 'funnel-builder' ),
									'value' => '[wfty_customer_phone_number]',
									'id'    => 'wfty_customer_phone_number'
								],
								[
									'label' => __( 'Order Number', 'funnel-builder' ),
									'value' => '[wfty_order_number]',
									'id'    => 'wfty_order_number'
								],
								[
									'label' => __( 'Order Total', 'funnel-builder' ),
									'value' => '[wfty_order_total]',
									'id'    => 'wfty_order_total'
								],
							);
						}

						$short_code_fields = [];
						$additional_tabs[] = [
							'mainHeading' => __( 'Thank You Page Settings', 'funnel-builder' ),
							'heading'     => __( 'Personalization Shortcodes', 'funnel-builder' ),
							'title'       => __( '', 'funnel-builder' ),
							'fields'      => $fields
						];

						if ( isset( $design['selected_type'] ) && 'wp_editor_1' === $design['selected'] ) {

							$short_code_fields[0]['heading'] = $additional_tabs['0']['heading'];
							foreach ( $additional_tabs[0]['fields'] as $field ) {
								$short_code_fields[] = $field;
							}

							$short_code_fields[ count( $short_code_fields ) ]['heading'] = $additional_tabs['1']['heading'];
							foreach ( $additional_tabs[1]['fields'] as $field ) {
								$short_code_fields[] = $field;
							}

							$custom_shortcode = [
								'type'   => 'shortcode',
								'fields' => $short_code_fields,
							];
							$form_design      = $this->wcty_design_settings( $step_id );;

							$tabs = [
								[ 'id' => 'tab_custom_shortcode', 'name' => __( 'ShortCode', 'funnel-builder' ) ],
								[ 'id' => 'tab_design', 'name' => __( 'Design', 'funnel-builder' ) ],
							];

							$content = [ 'tab_custom_shortcode' => $custom_shortcode, 'tab_design' => $form_design['schema'] ];

							unset( $additional_tabs );

							$additional_tabs[] = [
								'tabs'    => $tabs,
								'content' => $content,
								'heading' => __( 'Thank You Page Settings', 'funnel-builder' ),
								"values"  => $form_design['values']
							];


						}


						break;
					case 'landing':
						$additional_tabs[] = [
							'mainHeading' => __( 'Sale Page Settings', 'funnel-builder' ),
							'heading'     => __( '', 'funnel-builder' ),
							'title'       => __( '', 'funnel-builder' ),
							'fields'      => [
								[
									'label' => __( 'Next Step Button Link', 'funnel-builder' ),
									'value' => esc_url( site_url() . '/?wffn-next-link=yes' ),
									'id'    => 'next_step_link'
								],
							],
						];
						break;
					case 'optin_ty':
						break;
					case 'upsell':
						if ( isset( $design['selected_type'] ) && 'oxy' === $design['selected_type'] ) {
							$shortcode = WFOCU_Common::get_oxy_builder_shortcode( true );
							if ( is_array( $shortcode ) && count( $shortcode ) > 0 ) {
								foreach ( $shortcode as &$item ) {
									$item['label'] = $item['name'];
									$item['value'] = $item['tag'];
									$item['hint']  = '';
									unset( $item['name'] );
									unset( $item['tag'] );
								}
							}

							$additional_tabs[] = [
								'mainHeading' => __( 'Upsell Page Settings', 'funnel-builder' ),
								'heading'     => __( 'Personalization Shortcodes', 'funnel-builder' ),
								'title'       => sprintf( __( 'Using page builders to build custom upsell pages? <a href=%s target="_blank">Read this guide to learn more</a> about using Button widgets of your page builder <a href=%s target="_blank">Personalization shortcodes</a>', 'woofunnels-upstroke-one-click-upsell' ), esc_url( 'https://funnelkit.com/docs/one-click-upsell/design/custom-designed-one-click-upsell-pages/' ), esc_url( 'https://funnelkit.com/docs/one-click-upsell/design/custom-designs/#order-personalization-shortcodes' ) ),
								'fields'      => $shortcode
							];
						} else if ( isset( $design['selected_type'] ) && in_array( $design['selected_type'], [ 'beaver', 'custom', 'custom_page' ], true ) ) {
							if ( class_exists( 'WFOCU_Core' ) ) {
								if ( $design ) {
									$offer_data = WFOCU_Core()->offers->get_offer_meta( $step_id );
								}
								$offer_shortcode = WFOCU_Core()->admin->get_shortcodes_list();
								$product_count   = 1;
								if ( is_array( $offer_shortcode ) && count( $offer_shortcode ) > 0 ) {

									if ( ! empty( $offer_data ) && isset( $offer_data->products ) && count( (array) $offer_data->products ) > 0 ) {

										foreach ( $offer_data->products as $product_id ) {
											$shortcode = array();
											if ( ! wc_get_product( $product_id ) instanceof WC_Product ) {
												continue;
											}

											$shortcode[] = [ 'heading' => wc_get_product( $product_id )->get_title() ];

											foreach ( $offer_shortcode as $item ) {
												if ( isset( $item['code'] ) && isset( $item['code']['multi'] ) ) {
													$s_id = trim( str_replace( array( '[', ']' ), '', $item['code']['single'] ) );
													if ( false !== strpos( $s_id, '?' ) ) {
														$s_id = explode( '?', $s_id );
														$s_id = ( is_array( $s_id ) && isset( $s_id[1] ) ) ? str_replace( [ ' ', '-', '=' ], '_', $s_id[1] ) : '';
													}
													$shortcode[] = [
														'label' => $item['label'],
														'value' => sprintf( $item['code']['multi'], $product_count ),
														'id'    => $s_id . '_' . $product_count,
													];

												}
											}
											if ( $product_count === 1 ) {
												$additional_tabs[] = [
													'mainHeading' => __( 'Upsell Page Settings', 'funnel-builder' ),
													'heading'     => sprintf( __( 'Offer Settings', 'funnel-builder' ), wc_get_product( $product_id )->get_title() ),
													'title'       => sprintf( __( 'Using page builders to build custom upsell pages? <a href=%s target="_blank">Read this guide to learn more</a> about using Button widgets of your page builder <a href=%s target="_blank">Personalization shortcodes</a>', 'woofunnels-upstroke-one-click-upsell' ), esc_url( 'https://funnelkit.com/docs/one-click-upsell/design/custom-designed-one-click-upsell-pages/' ), esc_url( 'https://funnelkit.com/docs/one-click-upsell/design/custom-designs/#order-personalization-shortcodes' ) ),
													'fields'      => $shortcode
												];
											} else {
												$additional_tabs[] = [
													'mainHeading' => __( 'Upsell Page Settings', 'funnel-builder' ),
													'heading'     => '',
													'title'       => '',
													'fields'      => $shortcode
												];
											}
											$product_count ++;
										}

									} else {
										$additional_tabs[] = [];
									}
								}
							}
						}
						break;
					case 'wc_checkout':

						$is_pro_active = WFFN_Common::wffn_is_funnel_pro_active();
						$url           = admin_url( 'post.php?post=' . $step_id . '&action=edit' );
						$link          = "<a href='$url'>WordPress Editor</a>";

						$custom_shortcode = [
							'type'   => 'shortcode',
							'fields' => [
								[
									'label' => __( 'Form Shortcode', 'funnel-builder' ),
									'value' => '[wfacp_forms]',
									'id'    => 'wfacp_forms',
									'hint'  => __( 'Use this shortcode to embed the checkout form on this page. Switch to ' . $link . '.', 'woofunnels-aero-checkout' )
								],

								[
									'label' => __( 'Mini Cart Shortcode', 'funnel-builder' ),
									'value' => '[wfacp_mini_cart]',
									'id'    => 'wfacp_mini_cart',
									'hint'  => __( 'Use this shortcode to display mini cart your pages.', 'woofunnels-aero-checkout' )
								],
							],
						];

						if ( $is_pro_active ) {
							$additional_tabs[] = $custom_shortcode;
						} else {

							$fieldsets_data   = WFACP_Common::get_fieldset_data( $step_id );
							$field_width_data = [];

							$width_options = [
								[ 'key' => 'wfacp-col-full', 'value' => 'wfacp-col-full', 'label' => 'Full' ],
								[ 'key' => 'wfacp-col-left-half', 'value' => 'wfacp-col-left-half', 'label' => 'One Half' ],
								[ 'key' => 'wfacp-col-left-third', 'value' => 'wfacp-col-left-third', 'label' => 'One Third' ],
								[ 'key' => 'wfacp-col-two-third', 'value' => 'wfacp-col-two-third', 'label' => 'Two Third' ],
							];

							$tabs = [
								[ 'id' => 'tab_custom_shortcode', 'name' => __( 'ShortCode', 'funnel-builder' ) ],
								[ 'id' => 'tab_form_style', 'name' => __( 'Form Style', 'funnel-builder' ) ],
								[ 'id' => 'tab_field_width', 'name' => __( 'Field Width', 'funnel-builder' ) ]
							];

							$design_settings = $this->design_settings( $step_id );
							$form_style      = $design_settings['schema'];

							if ( ! empty( $fieldsets_data ) ) {
								$fieldsets = $fieldsets_data['fieldsets'];
								foreach ( $fieldsets as $fieldset ) {
									foreach ( $fieldset as $field_set ) {
										$field_data = [];
										if ( ! empty( $field_set['fields'] ) ) {

											$field_data['label'] = ! empty( $field_set['name'] ) ? $field_set['name'] : __( 'No section Heading', 'funnel-builder' );
											foreach ( $field_set['fields'] as $_field ) {
												if ( ! empty( $_field['label'] ) ) {
													$field['label']   = $_field['label'];
													$field['key']     = "wfacp_form_form_fields_1_embed_forms_2_" . $_field['id'];
													$field['class']   = '';
													$field['type']    = 'select';
													$field['options'] = $width_options;

													$field_data['fields'][] = $field;
												}
											}
											$field_width_data[] = $field_data;
										}
									}
								}
							}

							$content = [ 'tab_custom_shortcode' => $custom_shortcode, 'tab_form_style' => $form_style, 'tab_field_width' => $field_width_data ];

							$additional_tabs[] = [
								'tabs'    => $tabs,
								'content' => $content,
								'heading' => __( 'Checkout Form', 'funnel-builder' ),
								"values"  => $design_settings['values']
							];

						}

						break;
					default:
						break;
				}
			}

			return $additional_tabs;
		}

		public function is_valid_page_type( $type ) {
			$template_types = array( 'landing', 'optin', 'optin_ty', 'wc_checkout', 'wfob', 'wc_thankyou', 'wc_upsells', 'offer', 'upsell', 'optin-confirmation', 'thankyou', 'checkout', 'bump' );

			if ( in_array( $type, $template_types, true ) ) {
				return true;
			}

			return false;
		}

		public static function search_products( $term, $include_variations = false ) {
			global $wpdb;
			$like_term     = '%' . $wpdb->esc_like( $term ) . '%';
			$post_types    = $include_variations ? array(
				'product',
				'product_variation',
			) : array( 'product' );
			$post_statuses = current_user_can( 'edit_private_products' ) ? array(
				'private',
				'publish',
			) : array( 'publish' );

			$product_ids = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT posts.ID FROM {$wpdb->posts} posts
				LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE (
					posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
				AND posts.post_type IN ('" . implode( "','", $post_types ) . "') AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "') ORDER BY posts.post_parent ASC, posts.post_title ASC", $like_term, $like_term ) );  //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.PreparedSQLPlaceholders.QuotedDynamicPlaceholderGeneration

			if ( is_numeric( $term ) ) {
				$post_id   = absint( $term );
				$post_type = get_post_type( $post_id );

				if ( 'product_variation' === $post_type && $include_variations ) {
					$product_ids[] = $post_id;
				} elseif ( 'product' === $post_type ) {
					$product_ids[] = $post_id;
				}

				$product_ids[] = wp_get_post_parent_id( $post_id );
			}

			return wp_parse_id_list( $product_ids );
		}

		public function substep_save_order( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Error', 'funnel-builder' );

			$step_id = ! empty( $request->get_param( 'step_id' ) ) ? absint( $request->get_param( 'step_id' ) ) : '';
			$type    = ! empty( $request->get_param( 'type' ) ) ? wffn_clean( $request->get_param( 'type' ) ) : '';
			$option  = $request->get_body();

			if ( ! empty( $step_id ) && ! empty( $type ) && ! empty( $option ) ) {
				$option = $this->sanitize_custom( $option );
				$order  = ! empty( $option['order'] ) ? $option['order'] : [];

				switch ( $type ) {
					case 'offer' :
						$response = WFFN_REST_UPSELL_API_EndPoint::get_instance()->save_upsell_order( $step_id, $order );
						break;
					case 'wc_order_bump' :
						$response = $this->save_bump_order( $step_id, $order );
						break;
					default:
						break;
				}

				if ( $response ) {
					$resp['success'] = true;
					$resp['msg']     = __( 'Order saved', 'funnel-builder' );
				}
			}

			return rest_ensure_response( $resp );
		}


		public function save_bump_order( $step_id, $order ) {
			$return    = false;
			$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );
			$funnel    = WFFN_Core()->admin->get_funnel( $funnel_id );
			$steps     = $funnel->get_steps();
			$search    = array_search( absint( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			$step      = $steps[ $search ];

			if ( ! empty( $step ) && ! empty( $steps[ $search ] ) && ! empty( $step['substeps']['wc_order_bump'] ) ) {
				$steps[ $search ]['substeps']['wc_order_bump'] = $order;
				$funnel->set_steps( $steps );
				$return = $funnel->save( [] );
			}

			return $return;
		}

		public function get_step_design( $step_id, $type = 'optin' ) {
			$resp                = [];
			$page_class_instance = wffn_rest_funnel_modules()->get_page_class_instance( $type );
			if ( is_object( $page_class_instance ) ) {
				$design         = $page_class_instance->get_page_design( $step_id );
				$resp['design'] = $design;
			}

			return $resp;
		}

		public static function get_first_item( $order_id ) {
			$return = array(
				'titles' => '',
				'more'   => ''
			);
			if ( ! empty( $order_id ) ) {
				$wc_order = wc_get_order( $order_id );

				if ( is_a( $wc_order, 'WC_Order' ) ) {
					$order_items = $wc_order->get_items();
					$items_count = count( $order_items );

					if ( $items_count > 0 ) {
						$titles = [];
						foreach ( $order_items as $item ) {
							$titles[] = $item->get_name();
						}
						$return['titles'] = implode( ', ', $titles );
						$return['more']   = ( 1 === $items_count ) ? '' : ( $items_count - 1 ) . __( ' More', 'funnel-builder' );
					}
				}
			}

			return $return;
		}

		public function step_swap_slug( $type ) {

			switch ( $type ) {
				case 'optin':
					return $type;
				case 'thankyou':
				case 'wc_thankyou':
					return 'wc_thankyou';
				case 'optin_ty':
				case 'optin-confirmation':
					return 'optin_ty';
				case 'landing':
					return $type;
				case 'wfob':
				case 'bump':
					return 'wfob';
				case 'wc_checkout':
				case 'checkout':
					return 'wc_checkout';
				case 'upsell':
				case 'wc_upsells':
					return 'wc_upsells';
				case 'offer':
					return 'offer';
				default:
					return '';
			}
		}

		/**
		 * Detect Which Page builder used in POST or Page
		 *
		 * @param $post_data
		 *
		 * @return array
		 */
		public function detectActivePageBuilder( $post_data ) {

			$postID = $post_data->ID;

			$native_edit_link        = add_query_arg( [ 'action' => 'edit', 'post' => $postID ], admin_url( 'post.php' ) );
			$permalink               = get_the_permalink( $postID );
			$pageBuilderPostMetaKeys = array(
				array( '_fl_builder_enabled', 'bb-plugin/fl-builder.php', 'Beaver Builder', 'meta', add_query_arg( [ 'fl_builder' => 'yes' ], $permalink ) ),
				array( '_fl_builder_enabled', 'beaver-builder-lite-version/fl-builder.php', 'Beaver builder', 'meta', add_query_arg( [ 'fl_builder' => 'yes' ], $permalink ) ),
				array(
					'wp:siteorigin-panels',
					'siteorigin-panels/siteorigin-panels.php',
					'SiteOrigin',
					'content',
					$native_edit_link
				),
				array(
					'tcb_editor_enabled',
					'thrive-visual-editor/thrive-visual-editor.php',
					'Thrive Builder',
					'meta',
					add_query_arg( [ 'action' => 'architect', 'tve' => 'true', 'post' => $postID ], admin_url( 'post.php' ) )
				),
				array( '_wpb_vc_js_status', 'js_composer/js_composer.php', 'WPBakery Page Builder', 'meta', $native_edit_link ),
				array( 'brizy_enabled', 'brizy/brizy.php', 'Brizy', 'meta', add_query_arg( [ 'action' => 'in-front-editor', 'post' => $postID ], admin_url( 'post.php' ) ) ),
				array( '_bricks_editor_mode', defined( 'BRICKS_VERSION' ), 'Bricks', 'meta', add_query_arg( [ 'bricks' => 'run' ], $permalink ) ),
				array( 'breakdance_data', 'breakdance/plugin.php', 'Break Dance', 'meta', add_query_arg( [ "breakdance" => "builder", "id" => $postID ], home_url( '/' ) ) ),
				array( 'wp:flatsome', defined( 'UX_BUILDER_PATH' ), 'UX Builder', 'content', add_query_arg( [ 'app' => 'uxbuilder', 'type' => 'editor', 'post' => $postID ], admin_url( 'post.php' ) ) )
			);
			$metaValues              = get_post_meta( $postID );
			foreach ( $pageBuilderPostMetaKeys as $metaData ) {
				$metaKey    = $metaData[0];
				$pluginSlug = $metaData[1];
				$builder    = $metaData[2];
				$key_type   = $metaData[3];
				$url        = $metaData[4];
				if ( true !== $pluginSlug && 'activated' !== WFFN_Common::get_plugin_status( $pluginSlug ) ) {
					continue;
				}
				if ( $key_type === 'content' ) {
					if ( false !== strpos( $post_data->post_content, $metaKey ) ) {
						return [ 'page_builder' => $builder, 'url' => $url ];
					}
				}
				if ( isset( $metaValues[ $metaKey ] ) && ! empty( $metaValues[ $metaKey ][0] ) ) {
					return [ 'page_builder' => $builder, 'url' => $url ];
				}
			}

			return [ 'page_builder' => __( 'Other Page Builder', 'funnel-builder' ), 'url' => $native_edit_link ]; // If no active builder is detected or the required plugin is not activated
		}

	}

	if ( ! function_exists( 'wffn_rest_funnel_modules' ) ) {
		function wffn_rest_funnel_modules() {
			return WFFN_REST_Funnel_Modules::get_instance();
		}
	}

	if ( ! function_exists( 'wffn_pre' ) ) {
		function wffn_pre( $array, $exit = 0 ) {
			if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
				wffn_rest_funnel_modules()->pr( $array, $exit );
			}

			return null;
		}
	}

	wffn_rest_funnel_modules();
}
