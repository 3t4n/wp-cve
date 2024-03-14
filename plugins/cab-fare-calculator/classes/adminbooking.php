<?php
require_once TBLIGHT_PLUGIN_PATH . 'helpers/sort_helper.php';

class TBAdminBooking {

	public static function getAvailableCars( $order_data ) {
		global $wpdb;

		// session_start();

		$json_data = array(
			'error'             => 0,
			'msg'               => '',
			'available_cars'    => array(),
			'additional_params' => array(),
		);

		$now        = current_time( 'timestamp' );
		$elsettings = BookingHelper::config();

		if ( empty( $order_data ) ) {
			$json_data['error'] = 1;
			$json_data['msg']   = 'Order data is empty!';
		} elseif ( empty( $order_data->pickup_date ) ) {
			$json_data['error'] = 1;
			$json_data['msg']   = 'Pickup Date is not defined!';
		} else {
			$booking_type        = ! empty( $order_data->booking_type ) ? $order_data->booking_type : 'address';
			$pickup_address      = ! empty( $order_data->pickup_address ) ? $order_data->pickup_address : '';
			$pickup_coords       = ! empty( $order_data->pickup_coords ) ? $order_data->pickup_coords : array();
			$dropoff_address     = ! empty( $order_data->dropoff_address ) ? $order_data->dropoff_address : '';
			$dropoff_coords      = ! empty( $order_data->dropoff_coords ) ? $order_data->dropoff_coords : array();
			$adultseats          = $order_data->adultseats;
			$order_date_time_str = strtotime( $order_data->pickup_date );

			$suitcases = ! empty( $order_data->suitcases ) ? $order_data->suitcases : 0;
			$chseats   = ! empty( $order_data->childseats ) ? $order_data->childseats : 0;

			// $waypoint_coords  = !empty($order_data->waypoint_coords) ? $order_data->waypoint_coords : array();
			// $total_waypoint_duration  = !empty($order_data->total_waypoint_duration) ? $order_data->total_waypoint_duration : 0;
			$editing_order_id = ! empty( $order_data->editing_order_id ) ? $order_data->editing_order_id : 0;

			$return_date_time_str = '';

			$distance = 0;
			$duration = '';
			if ( ! empty( $pickup_coords ) && ! empty( $dropoff_coords ) ) {
				$call_gapi = true;

				if ( $call_gapi ) {
					list($distance,$duration_seconds,$gapi_status,$gapi_msg) = BookingHelper::calculateDistance( $pickup_coords[0], $pickup_coords[1], $dropoff_coords[0], $dropoff_coords[1], $elsettings );
				}

				if ( $gapi_status == 'OK' ) {
					if ( $call_gapi && $distance == 0 ) {
						$result['error'] = 1;
						$result['msg']   = 'No route could be calculated between desired destinations. Please amend them and try again.';
					} else {
						// $distance = number_format($distance,2);
						$distance_text = number_format( $distance, 2 ) . ' ' . $elsettings->distance_unit . 's';

						$duration_text = BookingHelper::secondsToTime( $duration_seconds );
					}
				} elseif ( $gapi_status == 'ZERO_RESULTS' ) {
					// indicates that the geocode was successful but returned no results. This may occur if the geocode was passed a non-existent address or a latlng in a remote location.
					$result['error'] = 1;
					$result['msg']   = 'The geocode was successful but returned no results. This may occur if the geocoder was passed a non-existent address.';
				} elseif ( $gapi_status == 'OVER_QUERY_LIMIT' ) {
					// indicates that you are over your quota of geocode requests against the google api
					$result['error'] = 1;
					$result['msg']   = $gapi_msg;
				} elseif ( $gapi_status == 'REQUEST_DENIED' ) {
					// indicates that your request was denied, generally because of lack of a sensor parameter.
					$result['error'] = 1;
					$result['msg']   = $gapi_msg;
				} elseif ( $gapi_status == 'INVALID_REQUEST' || $gapi_status == 'NOT_FOUND' ) {
					// generally indicates that the query (address or latlng) is missing.
					$result['error'] = 1;
					$result['msg']   = 'The query (address or latlng) is missing.';
				} else {
					$result['error'] = 1;
					$result['msg']   = $gapi_msg;
				}
			}

			$price = 0;

			$booking_data = array(
				'booking_type'  => $booking_type,
				'lat_long_from' => $pickup_coords,
				'lat_long_to'   => $dropoff_coords,
				'adultseats'    => $adultseats,
				'suitcases'     => $suitcases,
				'chseats'       => $chseats,
			);

			// now list cars
			$sql  = 'SELECT * FROM ' . $wpdb->prefix . 'tblight_cars';
			$sql .= ' WHERE state = 1';

			if ( $adultseats > 0 ) {
				$sql .= ' AND passenger_no >= ' . (int) $adultseats;
			}
			if ( $suitcases > 0 ) {
				$sql .= ' AND suitcase_no >= ' . (int) $suitcases;
			}
			if ( $chseats > 0 ) {
				$sql .= ' AND child_seat_no >= ' . (int) $chseats;
			}

			if ( $booking_type == 'address' ) {
				$sql .= ' AND use_in_address = 1';
			}
			// adult + child must be less than the maximum passenger seats for a car
			$total_passengers = (int) $adultseats + (int) $chseats;

			$sql .= ' AND passenger_no >= ' . (int) $total_passengers;
			$sql .= ' ORDER BY price ASC';

			$cars = $wpdb->get_results( $sql );

			list($base_pickup_distance,
					 $base_pickup_duration,
					 $base_pickup_price,
					 $base_pickup_price_calc,
					 $dropoff_base_distance,
					 $dropoff_base_duration,
					 $dropoff_base_price,
					 $dropoff_base_price_calc) = BookingHelper::considerBase( $elsettings, $booking_data );

			if ( ! empty( $cars ) ) {
				foreach ( $cars as $key => $car ) {
					// first check today is a blocked date or not for this car
					if ( BookingHelper::check_car_block_dates( $car, $order_date_time_str ) === false ) {
						unset( $cars[ $key ] );
					} elseif ( BookingHelper::check_todays_availability( $car, $order_date_time_str, '', $return_date_time_str ) === false ) { // check todays opening/closing time and compare with current time
						unset( $cars[ $key ] );
					} elseif ( BookingHelper::check_car_previous_bookings( $car, $order_date_time_str, $editing_order_id, $duration_seconds, $base_pickup_duration, $dropoff_base_duration, $return_date_time_str ) === false ) { // check this car previous booking journey
						unset( $cars[ $key ] );
					} else {

						$car_price = 0;

						$unit_price = (float) $car->unit_price;
						$car_price  = $distance * $unit_price;

						// we have 2 conditions for min distance
						// first if minimum distance > 0, price will be min.price if journey distance is less than min distance
						// if Minimum distance 0 or empty, then Price will be Min price if calculated price is less than Min price
						if ( (float) $car->minmil > 0 ) {
							if ( $distance < (float) $car->minmil && $car_price < (float) $car->minprice ) {
								$car_price = (float) $car->minprice;
							}
						} else {
							if ( (float) $car->minprice > 0 && $car_price < (float) $car->minprice ) {
								$car_price = (float) $car->minprice;
							}
						}

						if ( $chseats > 0 ) {
							$price_to_added = $chseats * (float) $car->child_seat_price;
							$car_price     += $price_to_added;
						}

						// add car flat price
						$car_price += $car->price;

						// add seats + poi additional charge
						$car_price += $price;

						// add base price
						$car_price += ( $base_pickup_price + $dropoff_base_price );

						// charge per min will be applied for Address booking only
						if ( $booking_type == 'address' ) {
							list($duration_charge,$unit_charge) = BookingHelper::calculate_charge_per_min( $duration_seconds, $car, $elsettings );
							$car_price                         += $duration_charge;
						}

						$outbound_price = $car_price;

						// round price based on configuration
						$car_price      = BookingHelper::round_price( $car_price, $elsettings );
						$car->car_price = $car_price;
					}
				}
			}

			$cars = sort_stack( $cars, 'car_price', 'ASC' );

			// now generate car list html
			if ( ! empty( $cars ) ) {
				$json_data['available_cars']    = $cars;
				$json_data['additional_params'] = array(
					'distance'              => $distance,
					'duration_seconds'      => $duration_seconds,
					'base_pickup_distance'  => $base_pickup_distance,
					'base_pickup_duration'  => $base_pickup_duration,
					'dropoff_base_distance' => $dropoff_base_distance,
					'dropoff_base_duration' => $dropoff_base_duration,
				);
			} else {
				$json_data['error'] = 1;
				$json_data['msg']   = esc_attr_e( 'No vehicle found on this date and time' );
			}
		}

		return $json_data;
	}
}
