<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API shipment tracking controller.
 *
 * Handles requests to /tracking-webhook endpoint.
 *
 * @since 1.0.0
 */

class TrackShip_REST_API_Controller extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v1';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'shop_order';
	
	/**
	 * Name Space function
	 *
	 * @param $namespace
	 *
	 * @return TrackShip_REST_API_Controller
	 */
	public function set_namespace( $namespace ) {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * Register the routes for trackings.
	 */
	public function register_routes() {
		
		//disconnect_from_trackship
		register_rest_route( $this->namespace, '/disconnect_from_trackship', array(
			array(
				'methods'				=> WP_REST_Server::CREATABLE,
				'callback'				=> array( $this, 'disconnect_from_trackship_fun' ),
				'permission_callback'	=> array( $this, 'get_item_permissions_check' ),
				'args'					=> array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), array(
					'user_key' => array(
						'required' => true,
					),
				) ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
		
		//tracking webhook
		register_rest_route( $this->namespace, '/tracking-webhook', array(
			array(
				'methods'				=> 'POST',
				'callback'				=> array( $this, 'tracking_webhook' ),
				'permission_callback'	=> array( $this, 'create_item_permissions_check' ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
		
		//check_ts4wc_installed
		register_rest_route( $this->namespace, '/check_ts4wc_installed', array(
			array(
				'methods'				=> 'POST',
				'callback'				=> array( $this, 'check_ts4wc_installed' ),
				'permission_callback'	=> array( $this, 'get_item_permissions_check' ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	public function check_ts4wc_installed( $request ) {

		// check TS4WC installed 
		$trackship_apikey = get_trackship_key();
		if ( empty( $trackship_apikey ) ) {
			update_option('wc_ast_api_key', $request['user_key']); // Will be deprecated, added new key in version 1.5.0
			update_option('trackship_apikey', $request['user_key']);
		}
		
		if ( $request['trackers_balance'] ) {
			update_option( 'trackers_balance', $request['trackers_balance'] );
		}			
		
		$trackship = new WC_Trackship_Actions();
		$trackship->create_tracking_page();
		
		//check which shipment tracking plugin active
		$plugin = 'tswc';
		$version_info = [];
		$version_info['ts4wc'] = trackship_for_woocommerce()->version;
		$version_info['site_url'] = get_site_url();
		$version_info['home_url'] = get_home_url();
		$version_info['trackship_db'] = get_option( 'trackship_db' );
		
		if ( is_plugin_active( 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php' ) ) {
			$plugin.= '-ast-free';
			$version_info['ast-free'] = wc_advanced_shipment_tracking()->version;
		}

		if ( is_plugin_active( 'ast-pro/ast-pro.php' ) ) {
			$plugin.= '-ast-pro';
			$version_info['ast-pro'] = ast_pro()->version;
		}
		
		if ( trackship_for_woocommerce()->is_st_active() ) {
			$plugin.= '-st';
			$version_info['st'] = WC_SHIPMENT_TRACKING_VERSION;
		}

		if ( is_plugin_active( 'yith-woocommerce-order-tracking/init.php' ) ) {
			$plugin.= '-yith-free';
			$version_info['yith-free'] = YITH_YWOT_VERSION;
		}

		if ( is_plugin_active( 'yith-woocommerce-order-tracking-premium/init.php' ) ) {
			$plugin.= '-yith-pro';
			$version_info['yith-pro'] = YITH_YWOT_VERSION;
		}

		if ( is_plugin_active( 'woo-orders-tracking/woo-orders-tracking.php' ) ) {
			$plugin.= '-wot-free';
			$version_info['wot-free'] = VI_WOO_ORDERS_TRACKING_VERSION;
		}

		if ( is_plugin_active( 'woocommerce-orders-tracking/woocommerce-orders-tracking.php' ) ) {
			$plugin.= '-wot-pro';
			$version_info['wot-pro'] = VI_WOOCOMMERCE_ORDERS_TRACKING_VERSION;
		}
		$version_info['trackship_settings'] = get_option( 'trackship_settings' );
		
		$database_version	= wc_get_server_database_version();

		global $wpdb;
		$shipment_structure = $wpdb->get_results("DESCRIBE {$wpdb->prefix}trackship_shipment");
		$shipment_meta_structure = $wpdb->get_results("DESCRIBE {$wpdb->prefix}trackship_shipment_meta");
		$shipping_provider_structure = $wpdb->get_results("DESCRIBE {$wpdb->prefix}trackship_shipping_provider");

		$server_info = array(
			'phpversion' => PHP_VERSION,
			'SERVER_SOFTWARE' => isset($_SERVER['SERVER_SOFTWARE']) ? sanitize_text_field($_SERVER['SERVER_SOFTWARE']) : '',
			'mysql_version' => $database_version['number'],
			'trackship_shipment' => $shipment_structure ? $shipment_structure : 'Table does not exist',
			'trackship_shipment_meta' => $shipment_meta_structure ? $shipment_meta_structure : 'Table does not exist',
			'trackship_shipping_provider' => $shipping_provider_structure ? $shipping_provider_structure : 'Table does not exist',
		);

		$REQUEST_TIME_FLOAT = isset($_SERVER['REQUEST_TIME_FLOAT']) ? sanitize_text_field($_SERVER['REQUEST_TIME_FLOAT']) : '';
		$data = array(
			'status'		=> 'installed',
			'plugin'		=> $plugin,
			'execution_time'=> microtime(true) - $REQUEST_TIME_FLOAT,
			'version_info'	=> $version_info,
			'server_info'	=> $server_info,
		);
		return rest_ensure_response( $data );
	}
	
	public function tracking_webhook( $request ) {
		$content = print_r($request, true);
		$logger = wc_get_logger();
		$context = array( 'source' => 'trackship_tracking_update' );
		$logger->info( "trackship_tracking_update \n" . $content . "\n", $context );
		
		//validation
		$user_key = $request['user_key'];
		$order_id = $request['order_id'];
		$tracking_number = $request['tracking_number'];
		$tracking_provider = $request['tracking_provider'];
		$tracking_event_status = $request['tracking_event_status'];
		$last_event_time = $request['last_event_time'];
		$tracking_est_delivery_date = $request['tracking_est_delivery_date'];
		$tracking_events = $request['events'];
		$tracking_destination_events = $request['destination_events'];
		$updated_at = $request['updated_at'];
		
		$trackship = WC_Trackship_Actions::get_instance();
		
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		$order = wc_get_order( $order_id );
		if ( !$order ) {
			$data = array(
				'status' => 'success'
			);
			return rest_ensure_response( $data );
		}
		$query = [];
		
		foreach ( ( array ) $tracking_items as $key => $tracking_item ) {
			if ( trim( $tracking_item['tracking_number'] ) != trim($tracking_number) ) {
				continue;
			}
			$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id , $tracking_item['tracking_number'] );
			$previous_status = isset( $row->shipment_status ) ? $row->shipment_status : '';	

			$order = wc_get_order( $order_id );
			
			$ts_shipment_status[$key]['status'] = $tracking_event_status;
			
			$last_event = '';
			$last_event = $this->get_last_event( $tracking_events, $tracking_destination_events );

			$args = array(
				'pending_status'		=> null,
				'shipment_status'		=> $tracking_event_status,
				'last_event'			=> $last_event,
				'updated_at'			=> $updated_at,
				'last_event_time'		=> $last_event_time ? $last_event_time : gmdate( 'Y-m-d H:i:s' ),
			);
			$args2 = array(
				'origin_country'		=> $request['origin_country'],
				'destination_country'	=> $request['destination_country'],
				'destination_state'		=> $request['destination_state'],
				'destination_city'		=> $request['destination_city'],
				'delivery_number'		=> $request['delivery_number'],
				'delivery_provider'		=> $request['delivery_provider'],
				'shipping_service'		=> $request['shipping_service'],
				'tracking_events'		=> json_encode($tracking_events),
				'destination_events'	=> json_encode($tracking_destination_events),
			);
			$args['est_delivery_date'] = $tracking_est_delivery_date ? gmdate('Y-m-d', strtotime($tracking_est_delivery_date)) : null;
			$query = trackship_for_woocommerce()->actions->update_shipment_data( $order_id, $tracking_item['tracking_number'], $args, $args2 );
			
			$order->update_meta_data( 'ts_shipment_status', $ts_shipment_status );
			$order->save();

			if ( $previous_status != $tracking_event_status && 'delivered' != $order->get_status() ) {
				// Schedule action for send Shipment status notifiations
				if ( in_array( $tracking_event_status, array( 'in_transit', 'available_for_pickup', 'out_for_delivery', 'failure', 'on_hold', 'exception', 'return_to_sender', 'delivered' ) ) ) {
					as_schedule_single_action( time(), 'ts_status_change_trigger', array( $order_id, $previous_status, $tracking_event_status, $tracking_number ), 'TrackShip' );
				}

				// Schedule action for send Pickup reminder notifiations
				$enable = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_enable_pickupreminder_email', '');
				if ( 'available_for_pickup' == $tracking_event_status && $enable ) {
					$time = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_pickupreminder_email_settings', 'pickupreminder_days', 2);
					$time = 24*60*60*intval($time);
					as_schedule_single_action( time() + $time, 'trigger_pickup_reminder_email', array( $order_id, $previous_status, $tracking_event_status, $tracking_number ), 'TrackShip' );
				}

				// hook for send data to paypal in AST PRO
				do_action( 'ast_trigger_ts_status_change', $order_id, $previous_status, $tracking_event_status, $tracking_item, [] );

				do_action( 'trackship_shipment_status_trigger', $order_id, $previous_status, $tracking_event_status, $tracking_number );
			}

		}
		
		$trackship->check_tracking_delivered( $order_id );
		
		$REQUEST_TIME_FLOAT = isset($_SERVER['REQUEST_TIME_FLOAT']) ? sanitize_text_field($_SERVER['REQUEST_TIME_FLOAT']) : '';
		$data = array(
			'status' => 'success',
			'execution_time' => microtime(true) - $REQUEST_TIME_FLOAT,
			'data' => $query,
		);
		
		return rest_ensure_response( $data );
	}

	public function get_last_event( $events, $destination_events ) {
		$last_event = '';
		$tracking_events = $destination_events ? $destination_events : $events;

		if ( $tracking_events ) {
			$tracking_events = array_reverse($tracking_events);
			$tracking_event = $tracking_events[0];
			// print_r($tracking_event);
			$last_event = $tracking_event['message'];
		}
		return $last_event;
	}

	/*
	* disconnect store from TS
	*/
	public function disconnect_from_trackship_fun( $request ) {
		update_option( 'wc_ast_api_key', '' ); // Will be deprecated, added new key in version 1.5.0
		update_option( 'trackship_apikey', '' );
		delete_option( 'trackers_balance' );
	}
	
	/**
	 * Check if a given request has access create order shipment-tracking.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean
	 */
	public function create_item_permissions_check( $request ) {
		
		if ( ! wc_rest_check_post_permissions( $this->post_type, 'create' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_create', __( 'Sorry, you are not allowed to create resources.', 'woocommerce-shipment-tracking' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}

	/**
	 * Check if a given request has access to read a order shipment-tracking.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! wc_rest_check_post_permissions( $this->post_type, 'read', (int) $request['order_id'] ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'woocommerce-shipment-tracking' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}

	public function get_permissions_check( $request ) {
		if ( ! wc_rest_check_manager_permissions( 'settings', 'read' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}
}
