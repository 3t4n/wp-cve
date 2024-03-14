<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_Klaviyo_TS4WC {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();	
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WOO_Klaviyo_TS4WC
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/*
	* init from parent mail class
	*/
	public function init() {
		add_action( 'trackship_shipment_status_trigger', array( $this, 'ts_status_change_callback'), 10, 4 );
		add_action( 'klaviyo_hoook', array( $this, 'klaviyo_callback'), 10, 4 );
	}

	public function ts_status_change_callback ( $order_id, $old_status, $new_status, $tracking_number ) {
		$timestamp = time() + 3;
		$hook = 'klaviyo_hoook';
		$args = array(
			'order_id'			=> $order_id,
			'old_status'		=> $old_status,
			'new_status'		=> $new_status,
			'tracking_number'	=> $tracking_number
		);
		as_schedule_single_action( $timestamp, $hook, $args, 'TrackShip klaviyo' );
	}
	
	/**
	 * Schedule action callback for integrately_callback
	 */
	public function klaviyo_callback( $order_id, $old_status, $new_status, $tracking_number ) {

		if ( !get_trackship_settings( 'klaviyo', '') ) {
			return;
		}

		$klaviyo_settings = get_option('klaviyo_settings');
		$api_key = isset($klaviyo_settings['klaviyo_public_api_key']) ? $klaviyo_settings['klaviyo_public_api_key'] : '';

		if ( !$api_key ) {
			return;
		}

		// API execution url
		$url = 'https://a.klaviyo.com/api/track';

		$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id , $tracking_number );

		$order = wc_get_order( $order_id );
		// Add requirements body parameters in below array
		$body = array(
			// Klaviyo Token
			'token' => $api_key,
			'event' => 'TrackShip Shipments events',
			// customer properties are requierd
			// must required $email or $phone_number
			'customer_properties' => [
				'$email'		=> $order ? $order->get_billing_email() : '',
				'$first_name'	=> $order ? $order->get_billing_first_name() : '',
				'$last_name'	=> $order ? $order->get_billing_last_name() : '',
				'$phone_number'	=> $order ? $order->get_billing_phone() : '',
				'$city'			=> $order ? $order->get_billing_city() : '',
				'$region'		=> $order ? $order->get_billing_state() : '',
				'$country'		=> $order ? $order->get_billing_country() : '',
			],
			// In the properties what you want to send in the Klavio
			'properties'	=> [
				'order_id'						=> $order_id,
				'order_number'					=> $order ? $order->get_order_number() : $order_id,
				'tracking_number'				=> $tracking_number,
				'tracking_provider'				=> $row->shipping_provider,
				'tracking_event_status'			=> $row->shipment_status,
				'tracking_est_delivery_date'	=> $row->est_delivery_date,
				'tracking_link'					=> trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $tracking_number ),
				'latest_event' 					=> $row->last_event,
				'origin_country'				=> $row->origin_country,
				'destination_country'			=> $row->destination_country,
				'delivery_number'				=> $row->delivery_number,
				'delivery_provider'				=> $row->delivery_provider,
				'shipping_service'				=> $row->shipping_service,
				'last_event_time'				=> $row->last_event_time,
			]
		);

		// Add requirements header parameters in below array
		$args = array(
			'body'		=> wp_json_encode($body),
			'headers'	=> array(
				'Content-Type'	=> 'application/json'
			),
		);

		// Example API call on integrately
		$response = wp_remote_post( $url, $args );

		$content = print_r($response, true);
		$logger = wc_get_logger();
		$context = array( 'source' => 'trackship-klaviyo-response' );
		$logger->info( "Response \n" . $content . "\n", $context );

	}
}
