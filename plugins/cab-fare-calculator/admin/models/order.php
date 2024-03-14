<?php

class OrderModel {

	public $dbtable;

	public function __construct() {
		 global $wpdb;

		$this->dbtable = $wpdb->prefix . 'tblight_orders';
	}

	public function getItems() {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable} ORDER BY datetime1 DESC"
			)
		);

		return $rows;
	}

	public function getItemById( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		return $row;
	}

	public function getDefaultData() {
		require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';
		$elsettings = BookingHelper::config();
		$row        = new stdClass();

		$now          = current_time( 'timestamp' );
		$default_time = $now + 10 * 60;

		$row->id                   = 0;
		$row->names                = '';
		$row->email                = '';
		$row->country_id           = $elsettings->default_country;
		$row->country_calling_code = '';
		$row->phone                = '';
		$row->booking_type         = 'address';
		$row->pickup_date          = gmdate( 'Y-m-d', $default_time );
		$row->pickup_hr            = gmdate( 'H', $default_time );
		$row->pickup_min           = gmdate( 'i', $default_time );
		$row->begin                = '';
		$row->end                  = '';
		$row->pickup_lat           = '';
		$row->pickup_lng           = '';
		$row->dropoff_lat          = '';
		$row->dropoff_lng          = '';
		$row->selpassengers        = 0;
		$row->selluggage           = 0;
		$row->selchildseats        = 0;
		$row->custom_car           = '';
		$row->vehicletype          = 0;
		$row->cprice               = '';
		$row->price_override       = '';
		$row->custom_payment       = '';
		$row->payment_notes        = '';
		$row->state                = -2;

		return $row;
	}

	public function store( $post_data ) {
		require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';

		global $wpdb;

		$elsettings = BookingHelper::config();

		$id                   = (int) $post_data['id'];
		$title                = $post_data['names'];
		$email                = $post_data['email'];
		$country_id           = $post_data['country_id'];
		$country_calling_code = $post_data['country_calling_code'];
		$phone                = $post_data['phone'];
		$booking_type         = $post_data['booking_type'];
		$pickup_date          = $post_data['pickup_date'];
		$pickup_hr            = $post_data['pickup_hr'];
		$pickup_min           = $post_data['pickup_min'];
		if ( $pickup_date != '' ) {
			$order_date_time_str = strtotime( $pickup_date . ' ' . $pickup_hr . ':' . $pickup_min );
			$order_date_time     = gmdate( 'Y-m-d H:i:s', $order_date_time_str );
		}
		$pickup_address   = $post_data['pickup_address'];
		$pickup_lat       = $post_data['pickup_lat'];
		$pickup_lng       = $post_data['pickup_lng'];
		$dropoff_address  = $post_data['dropoff_address'];
		$dropoff_lat      = $post_data['dropoff_lat'];
		$dropoff_lng      = $post_data['dropoff_lng'];
		$selpassengers    = $post_data['selpassengers'];
		$selluggage       = $post_data['selluggage'];
		$selchildseats    = $post_data['selchildseats'];
		$custom_car       = $post_data['custom_car'];
		$car_id           = $post_data['car_id'];
		$distance         = $post_data['distance'];
		$duration_seconds = $post_data['duration_seconds'];
		$duration_text    = BookingHelper::secondsToTime( $duration_seconds );
		$price            = $post_data['price'];
		$price_override   = $post_data['price_override'];
		$custom_payment   = $post_data['custom_payment'];
		$payment_notes    = $post_data['payment_notes'];
		$source           = $post_data['source'];
		$state            = $post_data['state'];

		$car           = BookingHelper::get_car_details( $car_id );
		$vehicle_title = '';
		if ( ! empty( $car ) ) {
			$vehicle_title = $car->title;
		}

		if ( $id == 0 ) { // New Item
			$row = $wpdb->insert(
				$this->dbtable,
				array(
					'order_number'         => uniqid(),
					'names'                => $title,
					'email'                => $email,
					'country_id'           => $country_id,
					'country_calling_code' => $country_calling_code,
					'phone'                => $phone,
					'booking_type'         => $booking_type,
					'datetime1'            => $order_date_time_str,
					'begin'                => $pickup_address,
					'pickup_lat'           => $pickup_lat,
					'pickup_lng'           => $pickup_lng,
					'end'                  => $dropoff_address,
					'dropoff_lat'          => $dropoff_lat,
					'dropoff_lng'          => $dropoff_lng,
					'selpassengers'        => $selpassengers,
					'selluggage'           => $selluggage,
					'selchildseats'        => $selchildseats,
					'custom_car'           => $custom_car,
					'vehicletype'          => $car_id,
					'vehicle_title'        => $vehicle_title,
					'distance'             => $distance,
					'duration'             => $duration_seconds,
					'duration_text'        => $duration_text,
					'cprice'               => $price,
					'price_override'       => $price_override,
					'custom_payment'       => $custom_payment,
					'payment_notes'        => $payment_notes,
					'source'               => $source,
					'state'                => $state,
					'created_by'           => get_current_user_id(),
					'created'              => current_time( 'Y-m-d H:i:s' ),
					'ip_address'           => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
					'user_agent'           => sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ),
				),
				array(
					'%s', // order_number
					'%s', // names
					'%s', // email
					'%d', // country_id
					'%d', // calling_code
					'%s', // phone
					'%s', // booking_type
					'%s', // datetime1
					'%s', // begin
					'%f', // pickup_lat
					'%f', // pickup_lng
					'%s', // end
					'%f', // dropoff_lat
					'%f', // dropoff_lng
					'%d', // selpassengers
					'%d', // selluggage
					'%d', // selchildseats
					'%s', // custom_car
					'%d', // vehicletype
					'%s', // vehicle_title
					'%s', // distance
					'%s', // duration
					'%s', // duration_text
					'%f', // cprice
					'%f', // price_override
					'%s', // custom_payment
					'%s', // payment_notes
					'%s', // source
					'%d', // state
					'%d', // created_by
					'%s', // created
					'%s', // ip_address
					'%s',  // user_agent
				)
			);

			$id = (int) $wpdb->insert_id;
		} elseif ( $id > 0 ) {
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'names'                => $title,
					'email'                => $email,
					'country_id'           => $country_id,
					'country_calling_code' => $country_calling_code,
					'phone'                => $phone,
					'datetime1'            => $order_date_time_str,
					'begin'                => $pickup_address,
					'pickup_lat'           => $pickup_lat,
					'pickup_lng'           => $pickup_lng,
					'end'                  => $dropoff_address,
					'dropoff_lat'          => $dropoff_lat,
					'dropoff_lng'          => $dropoff_lng,
					'selpassengers'        => $selpassengers,
					'selluggage'           => $selluggage,
					'selchildseats'        => $selchildseats,
					'custom_car'           => $custom_car,
					'vehicletype'          => $car_id,
					'vehicle_title'        => $vehicle_title,
					'distance'             => $distance,
					'duration'             => $duration_seconds,
					'duration_text'        => $duration_text,
					'cprice'               => $price,
					'price_override'       => $price_override,
					'custom_payment'       => $custom_payment,
					'payment_notes'        => $payment_notes,
					'state'                => $state,
					'modified_by'          => get_current_user_id(),
					'modified'             => current_time( 'Y-m-d H:i:s' ),
					'ip_address'           => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
					'user_agent'           => sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ),
				),
				array(
					'id' => $id,
				)
			);
		}

		$order = BookingHelper::get_order_by_id( $id );
		if ( ! empty( $order ) ) {
			// store order vehicle booking time data if track_availability is set YES for the booked car
			$track_availability = $car->track_availability;

			if ( (int) $track_availability == 1 ) {
				$booking_time_start = $order_date_time_str;
				$booking_time_end   = $order_date_time_str + $duration_seconds;

				// Car is set to use own base, so time_after_each_booking will come from car Object
				if ( $elsettings->calculate_base_pickup == 1 ) {
					$base_pickup_distance         = (float) $_SESSION['base_pickup_distance'];
					$base_pickup_duration_seconds = (int) $_SESSION['base_pickup_duration'];
					$booking_time_start          -= $base_pickup_duration_seconds;
				}
				if ( $elsettings->calculate_dropoff_base == 1 ) {
					$dropoff_base_distance         = (float) $_SESSION['dropoff_base_distance'];
					$dropoff_base_duration_seconds = (int) $_SESSION['dropoff_base_duration'];
					$booking_time_end             += $dropoff_base_duration_seconds;
				}
				$booking_time_end += 60 * (float) $elsettings->time_after_each_booking;

				$row = $wpdb->insert(
					$wpdb->prefix . 'tblight_order_car_rel',
					array(
						'order_id'           => $id,
						'vehicle_id'         => $order->vehicletype,
						'journey_type'       => 'outbound',
						'booking_time_start' => $booking_time_start,
						'booking_time_end'   => $booking_time_end,
						'created_date'       => current_time( 'Y-m-d H:i:s' ),
					),
					array(
						'%d', // order_id
						'%d', // vehicle_id
						'%s', // journey_type
						'%s', // booking_time_start
						'%s', // booking_time_end
						'%s',  // created_date
					)
				);

				if ( $wpdb->last_error ) {
					echo json_encode(
						array(
							'error' => 1,
							'msg'   => $wpdb->last_error,
						)
					);
					exit();
				}

				$order_car_rel_id = (int) $wpdb->insert_id;
			}

			// $this->sendMailDetailsToOwner($order,$elsettings,$paymentObj,$car);
		}

		return $id;
	}

	public function delete( $id = 0 ) {
		global $wpdb;

		return $wpdb->delete(
			$this->dbtable,
			array( 'id' => $id ),
			array( '%d' )
		);
	}

	public function status( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT state FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		if ( $row->state == 0 ) {
			$wpdb->update( $this->dbtable, array( 'state' => 1 ), array( 'id' => $id ) );
		} else {
			$wpdb->update( $this->dbtable, array( 'state' => 0 ), array( 'id' => $id ) );
		}

		return true;
	}
}
