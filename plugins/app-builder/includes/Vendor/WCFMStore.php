<?php

/**
 * class WCFMStoreStore
 *
 * @link       https://appcheap.io
 * @since      1.0.13
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Vendor;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

class WCFMStore extends BaseStore {

	public $cache_group = '';

	private array $wcfm_profile_personal_fields = array(
		'fd_user_avatar' => 'avatar',
		'email'          => 'email',
		'first_name'     => 'first_name',
		'last_name'      => 'last_name',
		'billing_phone'  => 'phone',
		'description'    => 'description',
	);

	public function register_routes() {
		add_filter( 'woocommerce_rest_prepare_product_object', array(
			$this,
			'woocommerce_rest_prepare_product_object'
		), 100, 3 );
		add_filter( 'woocommerce_rest_product_object_query', array(
			$this,
			'enable_vendor_on_list_product_query'
		), 10, 2 );
		parent::register_routes();
	}

	/**
	 * Get stores
	 *
	 * @param $request
	 *
	 * @return object|\WP_Error|\WP_REST_Response
	 * @since 1.0.12
	 *
	 */
	public function get_stores( $request ) {
		global $WCFMmp;

		$search      = $request->get_param( 'search' ) ? sanitize_text_field( $request->get_param( 'search' ) ) : '';
		$category    = $request->get_param( 'category' ) ? sanitize_text_field( $request->get_param( 'category' ) ) : '';
		$page        = $request->get_param( 'page' ) ? absint( $request->get_param( 'page' ) ) : 1;
		$per_page    = $request->get_param( 'per_page' ) ? absint( $request->get_param( 'per_page' ) ) : 10;
		$includes    = $request->get_param( 'includes' ) ? sanitize_text_field( $request->get_param( 'includes' ) ) : '';
		$excludes    = $request->get_param( 'excludes' ) ? sanitize_text_field( $request->get_param( 'excludes' ) ) : '';
		$has_product = $request->get_param( 'has_product' ) ? sanitize_text_field( $request->get_param( 'has_product' ) ) : '';
		$order       = $request->get_param( 'order' ) ? sanitize_text_field( $request->get_param( 'order' ) ) : 'ASC';
		$orderby     = $request->get_param( 'orderby' ) ? sanitize_text_field( $request->get_param( 'orderby' ) ) : 'ID';

		$search_data = array();

		$length = absint( $per_page );
		$offset = ( $page - 1 ) * $length;

		$search_data['excludes'] = $excludes;

		if ( $includes ) {
			$includes = explode( '', '', $includes );
		} else {
			$includes = array();
		}

		$search_data = apply_filters('app_builder_wcfm_search_data', $search_data, $request);

		$stores = $WCFMmp->wcfmmp_vendor->wcfmmp_get_vendor_list( true, $offset, $length, $search, $includes, $order, $orderby, $search_data, $category, $has_product );

		$data_objects = [];

		foreach ( $stores as $id => $store ) {
			$stores_data    = $this->prepare_item_for_response( $id, $request );
			$data_objects[] = $this->prepare_response_for_collection( $stores_data );
		}

		/**
		 * Filter store list data before response to client
		 */
		$results = apply_filters( 'app_builder_get_stores', $data_objects, $request );

		return rest_ensure_response( $results );
	}

	/**
	 * Prepare a single user output for response
	 *
	 * @param $id int
	 * @param \WP_REST_Request $request Request object.
	 * @param array $additional_fields (optional)
	 *
	 * @return \WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $id, $request, $additional_fields = [] ) {
		$store = get_user_meta( $id, 'wcfmmp_profile_settings', true );

		// Gravatar image
		$gravatar_url = isset( $store['gravatar'] ) ? wp_get_attachment_url( $store['gravatar'] ) : '';

		// List Banner URL
		$list_banner_url = isset( $store['list_banner'] ) ? wp_get_attachment_url( $store['list_banner'] ) : '';

		// Banner URL
		$banner_url = isset( $store['banner'] ) ? wp_get_attachment_url( $store['banner'] ) : '';

		// Mobile Banner URL
		$mobile_banner_url = isset( $store['mobile_banner'] ) ? wp_get_attachment_url( $store['mobile_banner'] ) : '';

		$store_user = wcfmmp_get_store( $id );

		$data = array(
			'id'               => intval( $id ),
			'store_name'       => $store['store_name'] ?? '',
			'first_name'       => $store['first_name'] ?? '',
			'last_name'        => $store['last_name'] ?? '',
			'phone'            => $store['phone'] ?? '',
			'show_email'       => true,
			'email'            => $store['store_email'] ?? '',
			'vendor_address'   => $store_user->get_address_string(),
			'banner'           => $banner_url,
			'mobile_banner'    => $mobile_banner_url,
			'list_banner'      => $list_banner_url,
			'gravatar'         => $gravatar_url,
			'shop_description' => $store['shop_description'] ?? '',
			'social'           => $store['social'] ?? '',
			'address'          => $store['address'] ?? '',
			'customer_support' => $store['customer_support'] ?? '',
			'featured'         => false,
			'rating'           => array(
				'rating' => intval( $store_user->get_total_review_rating() ),
				'count'  => intval( $store_user->get_total_review_count() ),
				'avg'    => intval( $store_user->get_avg_review_rating() ),
			),
			'geolocation'      => $store['geolocation'] ?? '',
		);

		$response = rest_ensure_response( $data );

		return apply_filters( 'wcfm_rest_prepare_store_item_for_response', $response );

	}

	/**
	 * Prepare object for product response
	 *
	 * @return void
	 *
	 */
	public function woocommerce_rest_prepare_product_object( $response, $object, $request ) {
		$data = $response->get_data();

		if ( isset( $data['store'] ) && is_array( $data['store'] ) && $data['store']['vendor_id'] ) {
			$store_data    = $this->prepare_item_for_response( $data['store']['vendor_id'], $request );
			$data['store'] = $this->prepare_response_for_collection( $store_data );
			$response->set_data( $data );
		}

		return $response;
	}

	/**
	 *
	 * Enable filter product by vendor
	 *
	 * @param $args
	 * @param $request
	 *
	 * @return mixed
	 */
	function enable_vendor_on_list_product_query( $args, $request ) {
		$args['author']         = isset( $request['vendor'] ) ? $request['vendor'] : '';
		$args['author__in']     = isset( $request['include_vendor'] ) ? $request['include_vendor'] : '';
		$args['author__not_in'] = isset( $request['exclude_vendor'] ) ? $request['exclude_vendor'] : '';

		return $args;
	}

	/**
	 *
	 * Get store categories
	 *
	 * @param $request
	 *
	 * @return object|WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_categories( $request ) {

		// Store id
		$store_id = (int) $request->get_param( 'id' );

		// Get store detail
		$store_user = wcfmmp_get_store( $store_id );

		// Get store categories
		$vendor_categories = $store_user->get_store_taxonomies();

		$ids = $this->flatten_categories( $vendor_categories );

		return rest_ensure_response( $ids );
	}

	/**
	 *
	 * Flatten categories
	 *
	 * @param $categories
	 *
	 * @return array
	 */
	public function flatten_categories( $categories ): array {
		$result = array();
		foreach ( $categories as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, [ (int) $key ], $this->flatten_categories( $value ) );
			} else {
				$result[] = (int) $value;
			}
		}

		return $result;
	}

	/**
	 *
	 * Get sales by product
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_sales_by_product( $request ) {
		global $WCFM, $wpdb;

		$wcfm_is_allow_reports = apply_filters( 'wcfm_is_allow_reports', true );
		if ( ! $wcfm_is_allow_reports ) {
			return rest_ensure_response( array(
				"labels" => [],
				"datas"  => []
			) );
		}

		$query = array();

		$query['fields']  = "SELECT SUM( order_item_meta.meta_value ) as qty, order_item_meta_2.meta_value as product_id
			FROM {$wpdb->posts} as posts";
		$query['join']    = "INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_id ";
		$query['join']    .= "INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id ";
		$query['join']    .= "INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id ";
		$query['where']   = "WHERE posts.post_type IN ( 'shop_order','shop_order_refund' ) ";
		$query['where']   .= "AND posts.post_status IN ( 'wc-" . implode( "','wc-", apply_filters( 'woocommerce_reports_order_statuses', array(
				'completed',
				'processing',
				'on-hold'
			) ) ) . "' ) ";
		$query['where']   .= "AND order_item_meta.meta_key = '_qty' ";
		$query['where']   .= "AND order_item_meta_2.meta_key = '_product_id' ";
		$query['where']   .= "AND posts.post_date >= '" . date( 'Y-m-d', strtotime( '-7 DAY', current_time( 'timestamp' ) ) ) . "' ";
		$query['where']   .= "AND posts.post_date <= '" . date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) . "' ";
		$query['groupby'] = "GROUP BY product_id";
		$query['orderby'] = "ORDER BY qty DESC";
		$query['limits']  = "LIMIT 5";

		$top_sellers = $wpdb->get_results( implode( ' ', apply_filters( 'woocommerce_dashboard_status_widget_top_seller_query', $query, 5 ) ) );

		$top_seller_labels = [];
		$top_seller_datas  = [];
		if ( ! empty( $top_sellers ) ) {
			foreach ( $top_sellers as $index => $top_seller ) {
				if ( $top_seller && $top_seller->product_id ) {
					$product = wc_get_product( $top_seller->product_id );
					if ( $product && is_object( $product ) ) {
						$top_seller_labels[] = addslashes( $product->get_title() );
						$top_seller_datas[]  = $top_seller->qty;
					}
				}
			}
		}

		return rest_ensure_response( array(
			'labels' => $top_seller_labels,
			'datas'  => $top_seller_datas,
		) );
	}

	/**
	 *
	 * Main store analytics
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_analytics( $request ) {

		include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );

		global $wp_locale, $wpdb, $WCFM;

		$start_date = strtotime( '-6 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
		$end_date   = strtotime( 'midnight', current_time( 'timestamp' ) );

		$current_range  = '7day';
		$chart_interval = absint( ceil( max( 0, ( $end_date - $start_date ) / ( 60 * 60 * 24 ) ) ) );
		$start_date     = strtotime( '-6 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
		$chart_groupby  = 'day';

		// Generate Data for total earned commision
		$select = "SELECT commission.count AS count, commission.visited";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_daily_analysis AS commission";
		$sql .= " WHERE 1=1";

		if ( wcfm_is_vendor() ) {
			$sql .= " AND commission.author_id = %d";
			$sql .= " AND commission.is_store = 1";
		} else {
			$sql .= " AND commission.is_shop = 1";
		}
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );

		$sql .= " GROUP BY DATE( commission.visited )";

		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );

		if ( wcfm_is_vendor() ) {
			$is_marketplace = wcfm_is_marketplace();
			if ( $is_marketplace == 'wcpvendors' ) {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
			}
		} else {
			$results = $wpdb->get_results( $sql );
		}

		// Prepare data for report
		$report     = new \WC_Admin_Report();
		$view_count = $report->prepare_chart_data( $results, 'visited', 'count', $chart_interval, $start_date, $chart_groupby );
		$chart_data = $WCFM->wcfm_prepare_chart_data( $view_count );

		return json_decode( $chart_data );
	}

	/**
	 *
	 * Get reports sales by date
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_reports_sales_by_date( $request ) {
		global $WCMp, $WCFM, $wpdb;

		$range = $request->get_param( 'range' ) ? $request->get_param( 'range' ) : 'month';

		include_once( $WCFM->plugin_path . 'includes/reports/class-wcfmmarketplace-report-sales-by-date.php' );
		$wcfm_report_sales_by_date = new \WCFM_Marketplace_Report_Sales_By_Date( $range );
		$wcfm_report_sales_by_date->calculate_current_range( $range );

		ob_start();
		$wcfm_report_sales_by_date->get_main_chart();
		$content = ob_get_clean();

		preg_match( '/var sales_data = .+};/', $content, $m );

		if ( count( $m ) == 0 ) {
			return parent::get_reports_sales_by_date( $request );
		}

		$data = array(
			'legend_data'   => $wcfm_report_sales_by_date->get_report_data(),
			'legend'        => $wcfm_report_sales_by_date->get_chart_legend(),
			'data'          => json_decode( substr_replace( str_replace( 'var sales_data = ', '', $m[0] ), '', - 1 ) ),
			'price_decimal' => get_option( 'woocommerce_price_num_decimals', 2 ),
			'currency'      => get_woocommerce_currency(),
		);

		return rest_ensure_response( $data );
	}

	/**
	 *
	 * Get store settings
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_settings( $request ) {
		$vendor_id = get_current_user_id();

		$_vendor_settings_data       = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
		$_wcfm_policy_vendor_options = get_user_meta( $vendor_id, 'wcfm_policy_vendor_options', true );

		$vendor_settings_data       = ! ! $_vendor_settings_data ? $_vendor_settings_data : array();
		$wcfm_policy_vendor_options = ! ! $_wcfm_policy_vendor_options ? $_wcfm_policy_vendor_options : array();

		if ( isset( $vendor_settings_data['store_name'] ) ) {
			$vendor_settings_data['store_name'] = get_user_meta( $vendor_id, 'store_name', true );
		}

		if ( isset( $vendor_settings_data['gravatar'] ) ) {
			$vendor_settings_data['gravatar_src'] = $this->get_attachment( $vendor_settings_data['gravatar'] );
		}

		if ( isset( $vendor_settings_data['banner'] ) ) {
			$vendor_settings_data['banner_src'] = $this->get_attachment( $vendor_settings_data['banner'] );
		}

		if ( isset( $vendor_settings_data['mobile_banner'] ) ) {
			$vendor_settings_data['mobile_banner_src'] = $this->get_attachment( $vendor_settings_data['mobile_banner'] );
		}


		if ( isset( $vendor_settings_data['store_seo'] ) ) {
			$store_seo = $vendor_settings_data['store_seo'];

			if ( isset( $store_seo['wcfmmp-seo-twitter-image'] ) && $store_seo['wcfmmp-seo-twitter-image'] ) {
				$store_seo['wcfmmp-seo-twitter-image-src'] = $this->get_attachment( $store_seo['wcfmmp-seo-twitter-image']);
			}

			if ( isset( $store_seo['wcfmmp-seo-og-image'] ) && $store_seo['wcfmmp-seo-og-image'] ) {
				$store_seo['wcfmmp-seo-og-image-src'] = $this->get_attachment( $store_seo['wcfmmp-seo-og-image'] );
			}

			$vendor_settings_data['store_seo'] = $store_seo;
		}

		$data = array_merge( $wcfm_policy_vendor_options, $vendor_settings_data );

		// Check $data['payment'] is empty then unset it
		if( isset( $data['payment'] ) && empty( $data['payment'] ) ) {
			unset( $data['payment'] );
		}

		return rest_ensure_response( $data );
	}

	/**
	 *
	 * Set store settings
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_settings( $request ) {
		$vendor_id = get_current_user_id();

		$settings             = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
		$vendor_settings_data = is_array( $settings ) ? $settings : array();

		/**
		 * General
		 */
		$store_name       = $request->get_param( 'store_name' );
		$phone            = $request->get_param( 'phone' );
		$store_email      = $request->get_param( 'store_email' );
		$shop_description = $request->get_param( 'shop_description' );
		$mobile_banner    = $request->get_param( 'mobile_banner' );
		$banner           = $request->get_param( 'banner' );
		$gravatar         = $request->get_param( 'gravatar' );

		if ( $store_name ) {
			update_user_meta( $vendor_id, 'store_name', strip_tags( $store_name ) );
			update_user_meta( $vendor_id, 'wcfmmp_store_name', strip_tags( $store_name ) );
			$vendor_settings_data['store_name'] = $store_name;
		}

		if ( $phone ) {
			$vendor_settings_data['phone'] = $phone;
		}

		if ( $store_email ) {
			$vendor_settings_data['store_email'] = $store_email;
		}

		if ( $shop_description ) {
			$vendor_settings_data['shop_description'] = $shop_description;
			update_user_meta( $vendor_id, '_store_description', $shop_description );
		}

		if ( $mobile_banner ) {
			$vendor_settings_data['mobile_banner'] = $mobile_banner;
		}

		if ( $banner ) {
			$vendor_settings_data['banner'] = $banner;
		}

		if ( $gravatar ) {
			$vendor_settings_data['gravatar'] = $gravatar;
		}

		/**
		 * Payment
		 */
		$payment = $request->get_param( 'payment' );
		if ( $payment ) {
			$vendor_settings_data['payment'] = $payment;
		}

		/**
		 * Customer support
		 */
		$customer_support = $request->get_param( 'customer_support' );
		if ( $customer_support ) {
			$vendor_settings_data['customer_support'] = $customer_support;
		}

		/**
		 *  Store Policies
		 */

		$wcfm_policy_vendor_options = array();
		$wcfm_policy_tab_title      = $request->get_param( 'policy_tab_title' );
		if ( $wcfm_policy_tab_title ) {
			$wcfm_policy_vendor_options['policy_tab_title'] = $wcfm_policy_tab_title;
		}

		$wcfm_shipping_policy = $request->get_param( 'shipping_policy' );
		if ( $wcfm_shipping_policy ) {
			$wcfm_policy_vendor_options['shipping_policy'] = $wcfm_shipping_policy;
		}

		$wcfm_refund_policy = $request->get_param( 'refund_policy' );
		if ( $wcfm_refund_policy ) {
			$wcfm_policy_vendor_options['refund_policy'] = $wcfm_refund_policy;
		}

		$wcfm_cancellation_policy = $request->get_param( 'cancellation_policy' );
		if ( $wcfm_cancellation_policy ) {
			$wcfm_policy_vendor_options['cancellation_policy'] = $wcfm_cancellation_policy;
		}

		if ( ! empty( $wcfm_policy_vendor_options ) ) {
			update_user_meta( $vendor_id, 'wcfm_policy_vendor_options', $wcfm_policy_vendor_options );
		}

		/**
		 * Address
		 */
		$address = $request->get_param( 'address' );
		if ( $address ) {
			$vendor_settings_data['address'] = $address;
		}

		/**
		 * Geolocation
		 */
		$geolocation = $request->get_param( 'geolocation' );
		if ( $geolocation ) {
			$vendor_settings_data['geolocation'] = $geolocation;

			$find_address   = $geolocation['find_address'];
			$store_location = $geolocation['store_location'];
			$store_lat      = $geolocation['store_lat'];
			$store_lng      = $geolocation['store_lng'];

			update_user_meta( $vendor_id, '_wcfm_find_address', $find_address );
			update_user_meta( $vendor_id, '_wcfm_store_location', $store_location );
			update_user_meta( $vendor_id, '_wcfm_store_lat', $store_lat );
			update_user_meta( $vendor_id, '_wcfm_store_lng', $store_lng );

			$vendor_settings_data['find_address']   = $find_address;
			$vendor_settings_data['store_location'] = $store_location;
			$vendor_settings_data['store_lat']      = $store_lat;
			$vendor_settings_data['store_lng ']     = $store_lng;
		}

		/**
		 * Socials
		 */
		$social = $request->get_param( 'social' );
		if ( $social ) {
			$vendor_settings_data['social'] = $social;
		}

		/**
		 * store_seo
		 */
		$store_seo = $request->get_param( 'store_seo' );
		if ( $store_seo ) {
			$vendor_settings_data['store_seo'] = $store_seo;
		}

		return rest_ensure_response(
			array(
				'success' => update_user_meta( $vendor_id, 'wcfmmp_profile_settings', $vendor_settings_data )
			),
		);
	}

	/**
	 * Get profile info
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_profile( $request ) {
		$user_id  = get_current_user_id();
		$personal = array();

		foreach ( $this->wcfm_profile_personal_fields as $key => $field ) {

			if ( $key == "email" ) {
				$user               = wp_get_current_user();
				$personal[ $field ] = $user->user_email;
			} else {
				$personal[ $field ] = get_user_meta( $user_id, $key, true );
			}

			if ( $key == "fd_user_avatar" ) {
				$personal['avatar_src'] = $this->get_attachment( $personal[ $field ] );
			}

		}

		return rest_ensure_response( array( 'personal' => $personal ) );
	}

	/**
	 * Set profile info
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_profile( $request ) {
		$user     = get_currentuserinfo();
		$user_id  = get_current_user_id();
		$personal = $request->get_param( 'personal' );

		$old_email = $user->user_email;
		$new_email = $personal['email'];

		// Validate email
		if ( ! is_email( $new_email ) ) {
			return new WP_Error(
				"app_builder_set_profile_error",
				__( "The email address isn’t correct.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		// Email exist
		if ( email_exists( $new_email ) && $old_email != $new_email ) {
			return new WP_Error(
				"app_builder_set_profile_error",
				__( "The email address is already used", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		foreach ( $this->wcfm_profile_personal_fields as $key => $field ) {
			if ( $key == "email" && $personal[ $field ] ) {
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $personal[ $field ] ) );
			} else {
				update_user_meta( $user_id, $key, $personal[ $field ] );
			}
		}

		return $this->get_profile( $request );
	}

	/**
	 *
	 * Get sales stats
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_sales_stats( $request ) {
		global $WCMp, $WCFM, $wpdb;

		include_once( $WCFM->plugin_path . 'includes/reports/class-wcfmmarketplace-report-sales-by-date.php' );

		$wcfm_report_sales_by_date = new \WCFM_Marketplace_Report_Sales_By_Date( 'month' );
		$wcfm_report_sales_by_date->calculate_current_range( 'month' );
		$month = $wcfm_report_sales_by_date->get_report_data();

		$wcfm_report_sales_by_date = new \WCFM_Marketplace_Report_Sales_By_Date( '7day' );
		$wcfm_report_sales_by_date->calculate_current_range( '7day' );
		$last_7day = $wcfm_report_sales_by_date->get_report_data();

		$wcfm_report_sales_by_date = new \WCFM_Marketplace_Report_Sales_By_Date( 'last_month' );
		$wcfm_report_sales_by_date->calculate_current_range( 'last_month' );
		$last_month = $wcfm_report_sales_by_date->get_report_data();

		$wcfm_report_sales_by_date = new \WCFM_Marketplace_Report_Sales_By_Date( 'year' );
		$wcfm_report_sales_by_date->calculate_current_range( 'year' );
		$year = $wcfm_report_sales_by_date->get_report_data();

		return rest_ensure_response(
			array(
				'data'          => array(
					'month'      => $month,
					'7day'       => $last_7day,
					'last_month' => $last_month,
					'year'       => $year,
				),
				'price_decimal' => get_option( 'woocommerce_price_num_decimals', 2 ),
				'currency'      => get_woocommerce_currency(),
			)
		);
	}

	/**
	 *
	 * Get reviews
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_reviews( $request ) {
		global $WCFM, $WCFMmp;
		$_POST["controller"]         = 'wcfm-reviews';
		$_POST['length']             = ! empty( $request['per_page'] ) ? intval( $request['per_page'] ) : 10;
		$_POST['start']              = ! empty( $request['page'] ) ? ( intval( $request['page'] ) - 1 ) * $_POST['length'] : 0;
		$_POST['orderby']            = ! empty( $request['orderby'] ) ? $request['orderby'] : '';
		$_POST['order']              = ! empty( $request['order'] ) ? $request['order'] : '';
		$_POST['status_type']        = ! empty( $request['status_type'] ) ? $request['status_type'] : '';
		$_REQUEST['wcfm_ajax_nonce'] = wp_create_nonce( 'wcfm_ajax_nonce' );
		define( 'WCFM_REST_API_CALL', true );
		$WCFM->init();
		$reviews = $WCFMmp->wcfmmp_reviews->wcfm_reviews_ajax_controller();
		foreach ( $reviews as $each_review ) {
			$wp_user_avatar_id = get_user_meta( $each_review->author_id, 'wp_user_avatar', true );
			$wp_user_avatar    = wp_get_attachment_url( $wp_user_avatar_id );
			if ( ! $wp_user_avatar ) {
				$wp_user_avatar = $WCFM->plugin_url . 'assets/images/avatar.png';
			}
			$each_review->author_image = $wp_user_avatar;
		}

		return $reviews;
	}

	/**
	 *
	 * Update review status
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_store_review_status( $request ) {
		global $WCFM, $WCFMmp, $wpdb;

		$review_data = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wcfm_marketplace_reviews WHERE `ID`= " . absint( $request['id'] ) );
		if ( ! $review_data || empty( $review_data ) || ! is_object( $review_data ) ) {
			return new WP_Error( "wcfmapi_rest_invalid_review_id", sprintf( __( "Invalid ID", 'wcfm-marketplace-rest-api' ), __METHOD__ ), array( 'status' => 404 ) );
		} else {
			$_POST['reviewid'] = absint( $request['id'] );
		}
		if ( isset( $request['review_status'] ) && $request['review_status'] == 'approve' ) {
			$_POST['status'] = 1;
		} elseif ( isset( $request['review_status'] ) && $request['review_status'] == 'unapprove' ) {
			$_POST['status'] = 0;
		} else {
			return new WP_Error( "wcfmapi_rest_invalid_review_status", sprintf( __( "Status Invalid", 'wcfm-marketplace-rest-api' ), __METHOD__ ), array( 'status' => 404 ) );
		}
		$_REQUEST['wcfm_ajax_nonce'] = wp_create_nonce( 'wcfm_ajax_nonce' );
		define( 'WCFM_REST_API_CALL', true );
		$WCFM->init();
		$reply_id = $WCFMmp->wcfmmp_reviews->wcfmmp_reviews_status_update();
		if ( $reply_id ) {
			$response = $review_data;
		}

		return rest_ensure_response( array( "success" => true ) );
	}

	/**
	 *
	 * Get notification
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_notification( $request ) {
		global $WCFM;

		$unread_notice  = $WCFM->wcfm_notification->wcfm_direct_message_count( 'notice' );
		$unread_message = $WCFM->wcfm_notification->wcfm_direct_message_count( 'message' );
		$unread_enquiry = $WCFM->wcfm_notification->wcfm_direct_message_count( 'enquiry' );

		return rest_ensure_response( array(
			"notice"  => $unread_notice,
			"message" => $unread_message,
			"enquiry" => $unread_enquiry,
		) );
	}

	/**
	 * Handle Message mark as Read
	 *
	 * @since 1.0.0
	 */
	function messages_mark_read( $request ) {
		global $WCFM, $wpdb;


		$messageid  = absint( $request->get_param( 'message_id' ) );
		$message_to = apply_filters( 'wcfm_message_author', get_current_user_id() );
		$todate     = date( 'Y-m-d H:i:s' );

		$wcfm_read_message = $wpdb->prepare( "INSERT into {$wpdb->prefix}wcfm_messages_modifier 
																(`message`, `is_read`, `read_by`, `read_on`)
																VALUES
																(%d, %d, %d, %s)",
			$messageid, 1, $message_to, $todate );

		if ( wcfm_is_vendor() || ( function_exists( 'wcfm_is_delivery_boy' ) && wcfm_is_delivery_boy() ) || ( function_exists( 'wcfm_is_affiliate' ) && wcfm_is_affiliate() ) ) {
			$cache_key = $this->cache_group . '-message-' . $message_to;
		} else {
			$cache_key = $this->cache_group . '-message-0';
		}

		delete_transient( $cache_key );

		$wpdb->query( $wcfm_read_message );

		return rest_ensure_response( array( 'status' => true ) );
	}

	/**
	 * Handle Message Delete
	 *
	 * @since 1.0.0
	 */
	function messages_delete( $request ) {
		global $WCFM, $wpdb;

		$messageid = absint( $request->get_param( 'message_id' ) );

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_messages WHERE `ID` = %d", $messageid ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_messages_modifier WHERE `message` = %d", $messageid ) );

		if ( wcfm_is_vendor() || ( function_exists( 'wcfm_is_delivery_boy' ) && wcfm_is_delivery_boy() ) || ( function_exists( 'wcfm_is_affiliate' ) && wcfm_is_affiliate() ) ) {
			$message_to = apply_filters( 'wcfm_message_author', get_current_user_id() );
			$cache_key  = $this->cache_group . '-message-' . $message_to;
		} else {
			$cache_key = $this->cache_group . '-message-0';
		}
		delete_transient( $cache_key );

		return rest_ensure_response( array( 'status' => true ) );
	}
}
