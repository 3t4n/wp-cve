<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WC_TrackShip_Api_Call {
	
	public function __construct() {
		
	}
	
	/*
	* check if string is json or not
	*/
	public function isJson( $string ) {
		json_decode( $string );
		return ( json_last_error() == JSON_ERROR_NONE );
	}
	
	/*
	* get trackship shipment status and update in order meta
	*/
	public function get_trackship_apicall( $order_id ) {
		
		$logger = wc_get_logger();
		$context = array( 'source' => 'wc_ast_trackship' );
		$array = array();
		$order = wc_get_order( $order_id );
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		
		if ( $tracking_items ) {
			
			foreach ( ( array ) $tracking_items as $key => $val ) {
				
				$tracking_number = trim( $val['tracking_number'] );
				if ( ! isset( $tracking_number ) ) {
					continue;
				}
				$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id, $tracking_number );

				if ( isset($row->shipment_status) && 'delivered' == $row->shipment_status && !get_trackship_settings( 'ts_migration' ) ) {
					continue;
				}
				
				if ( isset( $val['tracking_provider'] ) && '' != $val['tracking_provider'] ) {
					$tracking_provider = $val['tracking_provider'];
				} else {
					$tracking_provider = $val['custom_tracking_provider'];
				}
				$tracking_provider = apply_filters( 'convert_provider_name_to_slug', $tracking_provider );
				
				$bool = apply_filters( 'exclude_to_send_data_for_provider', true, $tracking_provider );
				if ( !$bool ) {
					continue;
				}

				//do api call to TrackShip
				$response = $this->get_trackship_data( $order, $tracking_number, $tracking_provider );
				
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					
					$logger = wc_get_logger();
					$context = array( 'source' => 'TrackShip_apicall_error' );
					$logger->error( "Something went wrong: {$error_message} For Order id :" . $order->get_id(), $context );
					
					//error like 403 500 502 
					$timestamp = time() + 5*60;
					$args = array( $order->get_id() );
					$hook = 'trackship_tracking_apicall';
					as_schedule_single_action( $timestamp, $hook, $args );
					
					$args = array(
						'pending_status'	=> 'Something went wrong',
						'shipping_provider'	=> $tracking_provider,
						'shipping_date'		=> date_i18n('Y-m-d', $val['date_shipped'] ),
						'est_delivery_date' => null,
					);
					trackship_for_woocommerce()->actions->update_shipment_data( $order_id, $tracking_number, $args );

				} else {
					
					$code = $response['response']['code'];

					if ( 200 == $code ) {
						//update trackers_balance, status_msg
						if ( !$this->isJson($response['body']) ) {
							return;
						}
						$body = json_decode($response['body'], true);
						
						if ( isset( $body['trackers_balance'] ) ) {
							update_option( 'trackers_balance', $body['trackers_balance'] );
						}
						if ( isset( $body['user_plan'] ) ) {
							update_option( 'user_plan', $body['user_plan'] );
						}
						
						$ts_shipment_status = $order->get_meta( 'ts_shipment_status', true );
						if ( is_string( $ts_shipment_status ) ) {
							$ts_shipment_status = array();
						}
						$ts_shipment_status[$key]['status'] = $body['status_msg'];
						$order->update_meta_data( 'ts_shipment_status', $ts_shipment_status );
						$args = array(
							'pending_status'	=> $body['status_msg'],
							'shipping_provider'	=> $tracking_provider,
							'shipping_date'		=> date_i18n('Y-m-d', $val['date_shipped'] ),
							'shipping_country'	=> $order->get_shipping_country() ? WC()->countries->countries[ $order->get_shipping_country() ] : '',
							'est_delivery_date' => null,
							'last_event_time'	=> gmdate( 'Y-m-d H:i:s' ),
						);
						$order->save();
						trackship_for_woocommerce()->actions->update_shipment_data( $order_id, $val['tracking_number'], $args );
						
					} else {
						//error like 400
						$body = json_decode($response['body'], true);
						$args = array(
							'pending_status'	=> $body['status_msg'],
							'shipping_provider'	=> $tracking_provider,
							'shipping_date'		=> date_i18n('Y-m-d', $val['date_shipped'] ),
							'est_delivery_date' => null,
						);
						trackship_for_woocommerce()->actions->update_shipment_data( $order_id, $val['tracking_number'], $args );
						
						$logger = wc_get_logger();
						$context = array( 'source' => 'Trackship_apicall_error' );
						$logger->error( 'Error code : ' . $code . ' For Order id :' . $order->get_id(), $context );
						$logger->error( 'Body : ' . $response['body'], $context );
					}
				}
			}
		}
		return $array;
	}
	
	/*
	* Get trackship shipment data
	*/
	public function get_trackship_data( $order, $tracking_number, $tracking_provider ) {
		$user_key = get_trackship_key();
		$domain = get_site_url();
		$domain = apply_filters( 'trackship_for_site_url', $domain );
		$domain = str_replace( 'http://', 'https://', $domain );
		$order_id = $order->get_id();
		$custom_order_number = $order->get_order_number();
		
		if ( $order->get_shipping_country() != null ) {
			$shipping_country = $order->get_shipping_country();
		} else {
			$shipping_country = $order->get_billing_country();
		}
		
		if ( $order->get_shipping_postcode() != null ) {
			$shipping_postal_code = $order->get_shipping_postcode();
		} else {
			$shipping_postal_code = $order->get_billing_postcode();
		}
		
		$url = 'https://my.trackship.com/api/create-tracker/ts4wc';
		
		$args['body'] = array(
			'user_key'				=> $user_key, // Deprecated since 19-Aug-2022
			'domain'				=> $domain, // Deprecated since 19-Aug-2022
			'order_id'				=> $order_id,
			'custom_order_id'		=> $custom_order_number,
			'tracking_number'		=> $tracking_number,
			'tracking_provider'		=> $tracking_provider,
			'postal_code'			=> $shipping_postal_code,
			'destination_country'	=> $shipping_country,
		);

		$args['headers'] = array(
			'trackship-api-key'	=> $user_key,
			'store'	=> $domain,
		);	
		$args['timeout'] = 10;
		$response = wp_remote_post( $url, $args );
		return $response;
	}
	
	/*
	* delete tracking number from trackship
	*/
	public function delete_tracking_number_from_trackship( $order_id, $tracking_number, $tracking_provider ) {
		$user_key = get_trackship_key();
		$domain = get_site_url();
		$domain = apply_filters( 'trackship_for_site_url', $domain );
		$domain = str_replace( 'http://', 'https://', $domain );
		$url = 'https://my.trackship.com/api/shipment/delete';
		
		$args['body'] = array(
			'order_id'			=> $order_id,
			'tracking_number'	=> $tracking_number,
			'tracking_provider'	=> $tracking_provider,
		);

		$args['headers'] = array(
			'trackship-api-key'	=> $user_key,
			'app-name'	=> $domain,
		);	
		$args['timeout'] = 10;
		$response = wp_remote_post( $url, $args );
		return $response;
	}
}
