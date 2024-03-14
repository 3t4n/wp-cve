<?php
require_once TBLIGHT_PLUGIN_PATH . 'classes/company.helper.php';

class BookingHelper {

		/**
		 * Pulls settings from database and stores in an static object
		 *
		 * @return object
		 * @since 0.9
		 */
	public static function config() {
		global $wpdb;

		$rows = $wpdb->get_results( 'SELECT text FROM ' . $wpdb->prefix . 'tblight_configs' );

		$config = new stdClass();
		if ( count( $rows ) > 0 ) {
			foreach ( $rows as $row ) {
				$params = json_decode( $row->text );
				foreach ( $params as $key => $value ) {
					$config->$key = $value;
				}
			}
		}

		$tbConfig = $config;

		return $tbConfig;
	}

	public static function getMaxSeatsData() {
		global $wpdb;

		$row = $wpdb->get_row(
			'SELECT MAX(passenger_no) AS max_passenger, MAX(suitcase_no) AS max_suitcase,
					MAX(child_seat_no) AS max_child
					FROM ' . $wpdb->prefix . 'tblight_cars
					WHERE state = 1'
		);

		return $row;
	}

	/**
	 * Convert number of seconds into hours, minutes and seconds
	 * and return an array containing those values
	 *
	 * @param integer $seconds Number of seconds to parse
	 * @return array
	 */
	public static function secondsToTime( $seconds ) {
		$duration_str = '';
		// extract hours
		$hours = floor( $seconds / ( 60 * 60 ) );

		// extract minutes
		$divisor_for_minutes = $seconds % ( 60 * 60 );
		$minutes             = floor( $divisor_for_minutes / 60 );

		// extract the remaining seconds
		$divisor_for_seconds = $divisor_for_minutes % 60;
		$seconds             = ceil( $divisor_for_seconds );

		// return the final array
		$obj = array(
			'h' => (int) $hours,
			'm' => (int) $minutes,
			's' => (int) $seconds,
		);

		$duration_str .= ( (int) $hours > 0 ) ? sprintf( '%s hrs ', $hours ) : '';
		$duration_str .= ( (int) $minutes > 0 ) ? sprintf( '%s mins', $minutes ) : '';
		// $duration_str .= ((int) $seconds > 0) ? ' '.$seconds.' sec' : '';
		// $duration_str .= ((int) $seconds > 1) ? 's' : '';

		return $duration_str;
	}

	public static function isJSON( $string ) {
		return is_string( $string ) && is_object( json_decode( $string ) ) ? true : false;
	}

		/**
		 * Logic to Calculate distance between two address
		 *
		 * @access   public
		 * @since    1.5
		 */
	public static function calculateDistance( $lat1, $lon1, $lat2, $lon2, $elsettings = array(), $waypoint_coords = array() ) {
		if ( empty( $elsettings ) ) {
			$elsettings = self::config();
		}

		$unit        = $elsettings->distance_unit;
		$unit_system = ( $unit == 'mile' ) ? 'imperial' : 'metric';

		$distance_meters  = 0;  // distance in meter
		$duration_seconds = 0;  // duration in second

		$q  = 'https://maps.googleapis.com/maps/api/directions/json?';
		$q .= "origin={$lat1},{$lon1}&destination={$lat2},{$lon2}&mode=driving&units=$unit_system";

		if ( ! empty( $waypoint_coords ) ) {
			$q .= ( (int) $elsettings->optimize_stops == 1 ) ? '&waypoints=optimize:true|' : '&waypoints=optimize:false|';

			$temp_waypts = array();
			for ( $i = 0;$i < count( $waypoint_coords );$i++ ) {
				$temp_waypts[] = implode( ',', $waypoint_coords[ $i ] );
			}
			$q .= implode( '|', $temp_waypts );
		}

		if ( $elsettings->api_server_key != '' ) {
			$q .= '&key=' . urlencode( $elsettings->api_server_key );
		}
		if ( ! empty( $elsettings->gmap_api_avoids ) ) {
			$q .= '&avoid=' . implode( '|', $elsettings->gmap_api_avoids );
		}

		$remote = wp_remote_get(
			$q,
			array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json',
				),
			)
		);

		if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
			$json = $remote['body'];
		} else {
			return array( 0, 0, 'HTTP API Error', 'HTTP API Error Message' );
		}

		if ( ! self::isJSON( $json ) ) {
			return array( 0, 0, 'Google Maps API Error', $json );
		} else {
			$response = json_decode( $json, true );

			$gapi_msg = '';
			if ( $response['status'] == 'OK' ) {
				foreach ( $response['routes'][0]['legs'] as $leg ) {
					$distance_meters  += $leg['distance']['value'];
					$duration_seconds += $leg['duration']['value'];
				}
			}
			if ( ! empty( $response['error_message'] ) ) {
				$gapi_msg = $response['error_message'];
			}

			$distance_km = $distance_meters / 1000;

			if ( $unit == 'km' ) {
				return array( $distance_km, $duration_seconds, $response['status'], $gapi_msg );
			} else {
				return array( ( $distance_km / 1.609344 ), $duration_seconds, $response['status'], $gapi_msg );
			}
		}
	}

	public static function calculate_time_difference( $date1, $date2, $output = 'hr' ) {
		$date_obj1 = new DateTime( $date1 ); // '2000-01-20'
		$date_obj2 = new DateTime( $date2 );
		// $hrs = round(($date_obj2->format('U') - $date_obj1->format('U')) / (60*60));

		if ( $output == 'day' ) {
			$difference = ( $date_obj2->format( 'U' ) - $date_obj1->format( 'U' ) ) / ( 24 * 60 * 60 );
		} else {
			$difference = ( $date_obj2->format( 'U' ) - $date_obj1->format( 'U' ) ) / ( 60 * 60 );
		}

		return $difference;
	}

	public static function price_display( $price, $elsettings = false, $price_debug = false ) {
		 $formatted_price = '';
		if ( $elsettings === false ) {
			$elsettings = self::config();
		}

		if ( $elsettings->currency_sign_position == 'before' ) {
			if ( substr( $price, 0, 1 ) == '+' || substr( $price, 0, 1 ) == '-' ) {
				$sign             = substr( $price, 0, 1 );
				$price            = (float) substr( $price, 1 );
				$formatted_price .= $sign . $elsettings->currency_symbol;
			} else {
				$formatted_price .= $elsettings->currency_symbol;
			}
		} else {
			if ( substr( $price, 0, 1 ) == '+' || substr( $price, 0, 1 ) == '-' ) {
				$sign             = substr( $price, 0, 1 );
				$price            = (float) substr( $price, 1 );
				$formatted_price .= $sign;
			}
		}

		// UPDATE July 13.2016 = if Round up = Whole number is selected we should not show decimals anyway
		// same for Round up = Nearst 5, just strip decimals

		// UPDATE Sep29.2016 - in price debug, prices will always be with 2 decimals
		if ( $price_debug ) {
			$formatted_price .= number_format( (float) $price, 2, '.', ',' );
		} else {
			if ( $elsettings->roundup_price != 'no' ) {
				$formatted_price .= number_format( (float) $price, 0, '.', ',' );
			} else {
				$formatted_price .= number_format( (float) $price, 2, '.', ',' );
			}
		}

		if ( $elsettings->currency_sign_position == 'after' ) {
			$formatted_price .= $elsettings->currency_symbol;
		}

		return $formatted_price;
	}
	public static function distance_display( $distance, $elsettings = false ) {
		if ( $elsettings === false ) {
			$elsettings = self::config();
		}

		if ( $distance > 1 ) {
			return number_format( $distance, 2 ) . ' ' . $elsettings->distance_unit . 's';
		} else {
			return number_format( $distance, 2 ) . ' ' . $elsettings->distance_unit;
		}
	}

	/**
	 * Logic to Calculate distance Base and Pickup
	 *
	 * @access   public
	 * @since    1.5
	 */
	public static function considerBase( $elsettings, $booking_data ) {
		 $base_pickup_distance  = $base_pickup_duration = $base_pickup_price = 0;
		$dropoff_base_distance  = $dropoff_base_duration = $dropoff_base_price = 0;
		$base_pickup_price_calc = $dropoff_base_price_calc = self::price_display( 0, $elsettings );
		$booking_type           = $booking_data['booking_type'];

		if ( $elsettings->base_lat != '' && $elsettings->base_long != '' ) {
			if ( $elsettings->calculate_base_pickup == 1 ) {
				$pickup_coords = $booking_data['lat_long_from'];

				if ( ! empty( $pickup_coords ) ) {
					list($distance,$duration_seconds,$gapi_status,$gapi_msg) = self::calculateDistance( $elsettings->base_lat, $elsettings->base_long, $pickup_coords[0], $pickup_coords[1], $elsettings );

					if ( $gapi_status == 'OK' ) {
						$base_pickup_distance = $distance;
						$base_pickup_duration = $duration_seconds;

						if ( $elsettings->base_pickup_price_type == 'flat' ) {
							$base_pickup_price     += (float) $elsettings->base_pickup_price;
							$base_pickup_price_calc = self::price_display( $elsettings->base_pickup_price, $elsettings, true );
						} else {
							$base_pickup_price     += ( $base_pickup_distance * (float) $elsettings->base_pickup_price );
							$base_pickup_price_calc = self::distance_display( $base_pickup_distance, $elsettings ) . ' X ' . self::price_display( $elsettings->base_pickup_price, $elsettings, true ) . ' = ' . self::price_display( $base_pickup_price, $elsettings, true );
						}

						// Charge if Base to Pick up over: XX miles
						if ( (float) $elsettings->milage_charging_base_pickup > 0
						   && $base_pickup_distance <= (float) $elsettings->milage_charging_base_pickup
						   ) {
							$base_pickup_price      = 0;
							$base_pickup_price_calc = self::price_display( 0, $elsettings, true ) . ' (Base to Pickup ' . self::distance_display( $base_pickup_distance, $elsettings ) . ' is less than limit ' . self::distance_display( $elsettings->milage_charging_base_pickup, $elsettings ) . ')';
						}
					}
				}
			}

			if ( $elsettings->calculate_dropoff_base == 1 ) {
				$dropoff_coords = $booking_data['lat_long_to'];

				if ( ! empty( $dropoff_coords ) ) {
					list($distance,$duration_seconds,$gapi_status,$gapi_msg) = self::calculateDistance( $dropoff_coords[0], $dropoff_coords[1], $elsettings->base_lat, $elsettings->base_long, $elsettings );

					if ( $gapi_status == 'OK' ) {
						$dropoff_base_distance = $distance;
						$dropoff_base_duration = $duration_seconds;

						if ( $elsettings->dropoff_base_price_type == 'flat' ) {
							$dropoff_base_price     += (float) $elsettings->dropoff_base_price;
							$dropoff_base_price_calc = self::price_display( $elsettings->dropoff_base_price, $elsettings, true );
						} else {
							$dropoff_base_price     += ( $dropoff_base_distance * (float) $elsettings->dropoff_base_price );
							$dropoff_base_price_calc = self::distance_display( $dropoff_base_distance, $elsettings ) . ' X ' . self::price_display( $elsettings->dropoff_base_price, $elsettings, true ) . ' = ' . self::price_display( $dropoff_base_price, $elsettings, true );
						}

						// Charge if Drop off to Base over: XX miles
						if ( (float) $elsettings->milage_charging_dropoff_base > 0
						   && $dropoff_base_distance <= (float) $elsettings->milage_charging_dropoff_base
						   ) {
							$dropoff_base_price      = 0;
							$dropoff_base_price_calc = self::price_display( 0, $elsettings, true ) . ' (Drop off to Base ' . self::distance_display( $dropoff_base_distance, $elsettings ) . ' is less than limit ' . self::distance_display( $elsettings->milage_charging_dropoff_base, $elsettings ) . ')';
						}
					}
				}
			}
		}

		return array(
			$base_pickup_distance,
			$base_pickup_duration,
			$base_pickup_price,
			$base_pickup_price_calc,
			$dropoff_base_distance,
			$dropoff_base_duration,
			$dropoff_base_price,
			$dropoff_base_price_calc,
		);
	}
	public static function date_format( $timestamp = '', $format = 'Y-m-d H:i:s', $elsettings = false, $set_timezone = false ) {
		if ( $timestamp == '' ) {
			return '';
		}

		if ( $elsettings === false ) {
			$elsettings = self::config();
		}

		if ( $set_timezone ) {
			$offset = get_option( 'gmt_offset' );
			date_default_timezone_set( $offset );
		}

		if ( $format == 'Y-m-d' ) {
			if ( $elsettings->date_format == 'mm-dd-yy' ) {
				$format = 'm-d-Y';
			} else {
				$format = 'd-m-Y';
			}
		}

		if ( $format == 'Y-m-d H:i:s' ) {
			if ( $elsettings->date_format == 'mm-dd-yy' ) {
				$format = 'm-d-Y';
			} else {
				$format = 'd-m-Y';
			}
			if ( $elsettings->time_format == '12hr' ) {
				$format .= ' h:i A';
			} else {
				$format .= ' H:i';
			}
		}

		return date( $format, (int) $timestamp );
	}
	public static function clear_booking_data() {
		// Starting session
		session_start();

		// Destroying session
		session_destroy();

		return;
	}
	public static function getInitialDebugArray( $company_configs, $elsettings ) {
		$debug_array = array(
			'base_pickup'  => array(
				'title'         => CompanyHelper::getConfigURL( $company_configs['base-settings'], 'Base to Pickup Charge' ),
				'total_unit'    => 'string',
				'charge_string' => 0,
			),
			'dropoff_base' => array(
				'title'         => CompanyHelper::getConfigURL( $company_configs['base-settings'], 'Dropoff to Base Charge' ),
				'total_unit'    => 'string',
				'charge_string' => 0,
			),
		);

		return $debug_array;
	}
	/**
	 * Method to check whether this car is same as the selected car type
	 */
	public static function check_car_type( $car, $selected_car_type ) {
		 return true;
	}
	/**
	 * Method to check pickup date is a blocked date or not for this car.
	 *
	 * @param   object
	 * @param   pickup date set from mobile app
	 */
	public static function check_car_block_dates( $car, $pickup_time_str = '' ) {
		if ( ! is_object( $car ) ) {
			$car = self::get_car_details( $car );
		}

		$order_date_time_str = $pickup_time_str;

		if ( $order_date_time_str != '' ) {
			$order_date        = date( 'Y-m-d', $order_date_time_str );
			$blocked_dates_arr = json_decode( $car->blocked_dates );

			if ( ! empty( $blocked_dates_arr ) && in_array( $order_date, $blocked_dates_arr ) ) {
				return false;
			}
		}

		return true;
	}
	/**
	 * Method to check if car is available on pickup date
	 *
	 * @param   object
	 * @param   pickup date set from mobile app
	 */
	public static function check_todays_availability( $car, $pickup_time_str = '', $dropoff_time_str = '', $return_time_str = '' ) {
		if ( ! is_object( $car ) ) {
			$car = self::get_car_details( $car );
		}

		$order_date_time_str = $pickup_time_str;

		if ( $pickup_time_str != '' ) {
			$order_date_time_str = $pickup_time_str;
		} else {
			$order_date_time_str = (int) $_SESSION['timestr1'];
		}

		if ( $order_date_time_str == '' ) {
			return false;
		}

		$order_date          = gmdate( 'Y-m-d', $order_date_time_str );
		$order_date_time_hr  = gmdate( 'H', $order_date_time_str );
		$order_date_time_min = gmdate( 'i', $order_date_time_str );
		$order_date_week_day = gmdate( 'N', $order_date_time_str );  // 1 (for Monday) through 7 (for Sunday)
		// echo 'current - '.$order_date_time_hr.' - '.$order_date_time_min;

		if ( $dropoff_time_str != '' ) {
			$dropoff_date          = gmdate( 'Y-m-d', $dropoff_time_str );
			$dropoff_date_time_hr  = gmdate( 'H', $dropoff_time_str );
			$dropoff_date_time_min = gmdate( 'i', $dropoff_time_str );
			$dropoff_date_week_day = gmdate( 'N', $dropoff_time_str );  // 1 (for Monday) through 7 (for Sunday)
			// echo 'current - '.$dropoff_date_time_hr.' - '.$dropoff_date_time_min;
		}

		$weekdays = array(
			0 => 'MON',
			1 => 'TUE',
			2 => 'WED',
			3 => 'THU',
			4 => 'FRI',
			5 => 'SAT',
			6 => 'SUN',
		);

		$car_availability_arr = json_decode( $car->days_availability );

		$car_data = array(
			'opening_hr'   => 0,
			'opening_min'  => 0,
			'closing_hr'   => 0,
			'closing_min'  => 0,
			'is_available' => 0,
		);

		$todays_availabilty = ! empty( $car_availability_arr[ $order_date_week_day - 1 ] ) ? (array) $car_availability_arr[ $order_date_week_day - 1 ] : array();

		if ( empty( $car_availability_arr ) ) {
			$car_data['is_available'] = 0;
		} elseif ( empty( $todays_availabilty ) || empty( $todays_availabilty['is_available'] ) || $todays_availabilty['is_available'] == 0 ) {
			$car_data['is_available'] = 0;
		} elseif ( $todays_availabilty['is_available'] == 1 && $todays_availabilty['opening_hrs'] == -1 && $todays_availabilty['opening_mins'] == -1 && $todays_availabilty['closing_hrs'] == -1 && $todays_availabilty['closing_mins'] == -1 ) {
			$car_data['is_available'] = 1;
		} else {
			$car_data['opening_hr']  = (int) $todays_availabilty['opening_hrs'];
			$car_data['opening_min'] = (int) $todays_availabilty['opening_mins'];
			$car_data['closing_hr']  = (int) $todays_availabilty['closing_hrs'];
			$car_data['closing_min'] = (int) $todays_availabilty['closing_mins'];

			// now check the availability based on opening/closing time
			if ( $car_data['opening_hr'] > -1 && $car_data['closing_hr'] > -1 ) {
				if ( $car_data['opening_hr'] > $order_date_time_hr && $car_data['closing_hr'] > $order_date_time_hr ) { // out of hour range
					$car_data['is_available'] = 0;
				} elseif ( $car_data['opening_hr'] < $order_date_time_hr && $car_data['closing_hr'] < $order_date_time_hr ) { // out of hour range
					$car_data['is_available'] = 0;
				} else {

					if ( $car_data['opening_hr'] < $order_date_time_hr && $order_date_time_hr < $car_data['closing_hr'] ) { // inside of hour range
						$car_data['is_available'] = 1;
					} else { // opening OR closing hour == current hour, so minutes will be considered now

						if ( $car_data['opening_hr'] == $order_date_time_hr ) {
							if ( $car_data['opening_min'] <= $order_date_time_min ) {
								$car_data['is_available'] = 1;
							} else {
								$car_data['is_available'] = 0;
							}
						} elseif ( $car_data['closing_hr'] == $order_date_time_hr ) {
							if ( $order_date_time_min <= $car_data['closing_min'] ) {
								$car_data['is_available'] = 1;
							} else {
								$car_data['is_available'] = 0;
							}
						}
					}
				}
			}
		}

		if ( $car_data['is_available'] == 1 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Method to check previous booking of this car
	 *
	 * @param   object
	 * @param   pickup date set from mobile app
	 */
	public static function check_car_previous_bookings( $car, $pickup_time_str = '', $editing_order_id = 0, $duration_seconds = 0, $base_pickup_duration = 0, $dropoff_base_duration = 0, $return_time_str = '', $elsettings = false ) {
		global $wpdb;

		if ( ! is_object( $car ) ) {
			$car = self::get_car_details( $car );
		}

		$order_date_time_str = $pickup_time_str;

		if ( $order_date_time_str != '' ) {
			// if car tracking is set NO, no need to check previous booking
			if ( (int) $car->track_availability == 0 ) {
				return true;
			}

			if ( $base_pickup_duration == 0 ) {
				// $base_pickup_duration = $session->get('base_pickup_duration', 0);
			}
			if ( $dropoff_base_duration == 0 ) {
				// $dropoff_base_duration = $session->get('dropoff_base_duration', 0);
			}

			// here we have 2 time ranges
			// one is pickup to pickup_plus_duration
			// another is database booking_start to booking_end
			// availability check -- 4 OVERLAPPED cases

			$pickup               = $order_date_time_str;
			$pickup_plus_duration = $order_date_time_str + (float) $duration_seconds;

			$pickup               -= (float) $base_pickup_duration;
			$pickup_plus_duration += (float) $dropoff_base_duration;

			if ( $elsettings ) {
				$pickup_plus_duration += 60 * (float) $elsettings->time_after_each_booking;
			}

			// 1) (pickup in between booking_start and booking_end) AND (pickup_plus_duration in between booking_start and booking_end) -- OVERLAPPED
			// 2) (pickup less than booking_start)  AND (pickup_plus_duration greater than booking_end) -- OVERLAPPED
			// 3) (pickup in between booking_start and booking_end) AND (pickup_plus_duration not in between booking_start and booking_end) -- OVERLAPPED
			// 4) (pickup not in between booking_start and booking_end) AND (pickup_plus_duration in between booking_start and booking_end) -- OVERLAPPED
			$sql  = 'SELECT COUNT(id) FROM ' . $wpdb->prefix . 'tblight_order_car_rel';
			$sql .= ' WHERE vehicle_id = ' . (int) $car->id;
			$sql .= ' AND ( ("' . $pickup . '" BETWEEN `booking_time_start` AND `booking_time_end` ) AND ("' . $pickup_plus_duration . '" BETWEEN `booking_time_start` AND `booking_time_end` )'
				. '  	OR ("' . $pickup . '" <= `booking_time_start` AND `booking_time_end` <= "' . $pickup_plus_duration . '" )  '
				. '  	OR ("' . $pickup . '" BETWEEN `booking_time_start` AND `booking_time_end` ) AND ("' . $pickup_plus_duration . '" NOT BETWEEN `booking_time_start` AND `booking_time_end` )'
				. '  	OR ("' . $pickup . '" NOT BETWEEN `booking_time_start` AND `booking_time_end` ) AND ("' . $pickup_plus_duration . '" BETWEEN `booking_time_start` AND `booking_time_end` )'
				. ' )';

			if ( $editing_order_id > 0 ) {
				$sql .= ' AND order_id <> ' . (int) $editing_order_id;
			}

			$previous_booking = $wpdb->get_var( $sql );

			if ( $previous_booking > 0 ) {
				return false;
			}
		}

		return true;
	}
	public static function get_car_details( $id = 0 ) {
		 global $wpdb;

		$sql  = 'SELECT * FROM ' . $wpdb->prefix . 'tblight_cars';
		$sql .= ' WHERE id = ' . (int) $id;
		$row  = $wpdb->get_row( $sql );

		if ( $row ) {
			return $row;
		}

		return false;
	}
	public static function calculate_charge_per_min( $duration = 0, $car, $elsettings ) {
		$charge = $unit_charge = 0;
		if ( $duration > 0 ) {
			$unit_charge = (float) $car->charge_per_min;
			$charge      = ceil( $duration / 60 ) * $unit_charge; // duration will be in seconds
		}
		return array( $charge, $unit_charge );
	}
	public static function round_price( $price = 0, $elsettings ) {

		if ( $elsettings->roundup_price == 'nearest5' ) {
			/*
			1. Round to the next multiple of 5, exclude the current number

			Behaviour: 50 outputs 55, 52 outputs 55

			function roundUpToAny($n,$x=5) {
			return round(($n+$x/2)/$x)*$x;
			}

			2. Round to the nearest multiple of 5, include the current number

			Behaviour: 50 outputs 50, 52 outputs 55, 50.25 outputs 50

			function roundUpToAny($n,$x=5) {
			return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
			}

			3. Round up to an integer, then to the nearest multiple of 5

			Behaviour: 50 outputs 50, 52 outputs 55, 50.25 outputs 55

			function roundUpToAny($n,$x=5) {
			return (ceil($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
			}
			*/
			$rounded_price = self::roundUpToAny( $price );
		} elseif ( $elsettings->roundup_price == 'whole' ) {
			$rounded_price = round( $price );
		} else {
			$rounded_price = (float) number_format( $price, 2, '.', '' );
		}

		return $rounded_price;
	}
	public static function roundUpToAny( $n, $x = 5 ) {
		return ( ceil( $n ) % $x === 0 ) ? ceil( $n ) : round( ( $n + $x / 2 ) / $x ) * $x;
	}
	public static function get_payment_details( $id = 0 ) {
		 global $wpdb;

		$sql  = 'SELECT id, title, text, payment_element, payment_params FROM ' . $wpdb->prefix . 'tblight_paymentmethods';
		$sql .= ' WHERE id = ' . (int) $id;
		$row  = $wpdb->get_row( $sql );

		// process params
		if ( ! empty( $row ) ) {
			$params = json_decode( $row->payment_params );
			foreach ( $params as $k => $v ) {
				if ( ! empty( $v ) ) {
					$row->$k = $v;
				}
			}
		}

		return $row;
	}
	public static function get_order_by_id( $id = 0 ) {
		 global $wpdb;

		$sql = 'SELECT a.*, pm.title AS payment_method
				FROM ' . $wpdb->prefix . 'tblight_orders AS a 
				LEFT JOIN ' . $wpdb->prefix . 'tblight_paymentmethods AS pm ON pm.id = a.payment 
				WHERE a.id = ' . (int) $id;
		$row = $wpdb->get_row( $sql );

		return $row;
	}

	public static function get_order_by_order_number( $order_number = '' ) {
		global $wpdb;

		if ( $order_number != '' ) {
			$sql = 'SELECT a.*, pm.title AS payment_method 
					FROM ' . $wpdb->prefix . 'tblight_orders AS a 
					LEFT JOIN ' . $wpdb->prefix . "tblight_paymentmethods AS pm ON pm.id = a.payment 
					WHERE a.order_number = '" . $order_number . "'";
			$row = $wpdb->get_row( $sql );

			return $row;
		}

		return array();
	}

	public static function get_order_status_text( $order, $colored = false ) {
		if ( ! is_object( $order ) ) {
			$order = self::get_order_by_id( $order );
		}
		if ( $order->state == 1 ) {
			$text  = 'Accepted';
			$color = 'green';
		} elseif ( $order->state == 0 ) {
			$text  = 'Rejected';
			$color = 'red';
		} elseif ( $order->state == -1 ) {
			$text  = 'Archived';
			$color = 'yellow';
		} elseif ( $order->state == -2 ) {
			$text  = 'Waiting';
			$color = 'green';
		} else {
			$text  = 'Waiting';
			$color = 'green';
		}

		if ( $colored ) {
			$text = '<span style="color:' . $color . ';">' . $text . '</span>';
		}

		return $text;
	}

	public static function get_order_car( $order ) {
		if ( ! empty( $order ) ) {
			if ( $order->custom_car != '' ) {
				return $order->custom_car;
			} else {
				global $wpdb;

				$sql  = 'SELECT id, title FROM ' . $wpdb->prefix . 'tblight_cars';
				$sql .= ' WHERE id = ' . (int) $order->vehicletype;
				$row  = $wpdb->get_row( $sql );

				if ( $row ) {
					return sprintf( '<a href="?page=%s&action=%s&id=%s">' . $row->title . '</a>', esc_attr( 'cars' ), 'show', absint( $row->id ) );
				} else {
					return 'NOT FOUND';
				}
			}
		} else {
			return 'NOT FOUND';
		}
	}

	public static function get_order_pickup_date( $order, $elsettings = false ) {
		if ( ! empty( $order ) ) {

			if ( $order->source == 'backend' ) {
				return self::date_format( $order->datetime1, 'Y-m-d H:i:s', $elsettings );
			} else {
				if ( is_numeric( $order->payment ) ) {
					return self::date_format( $order->datetime1, 'Y-m-d H:i:s', $elsettings );
				} else {
					return self::DateTimeHuman( $order->datetime1 );
				}
			}
		} else {
			return 'NOT FOUND';
		}
	}

	public static function DateTimeHuman( $datetime ) {
		 // "200809230845"
		if ( ( strlen( $datetime ) == 12 ) && is_numeric( $datetime ) ) {
				$temp  = substr( $datetime, 6, 2 );   // day date
				$temp .= '-';
				$temp .= substr( $datetime, 4, 2 );   // month
				$temp .= '-';
				$temp .= substr( $datetime, 0, 4 );   // year
				$temp .= ' ';

				$temp .= substr( $datetime, 8, 2 );   // hours time
				$temp .= ':';
				$temp .= substr( $datetime, 10, 2 );  // minutes
				return $temp;
		} else {
			return false;
		}
	}

	public static function DateHuman( $datetime ) {
		 // "200809230845"
		if ( ( strlen( $datetime ) == 12 ) && is_numeric( $datetime ) ) {
			$temp  = substr( $datetime, 6, 2 );   // day  date
			$temp .= '/';

			$temp .= substr( $datetime, 4, 2 );   // month
			$temp .= '/';

			$temp .= substr( $datetime, 0, 4 );   // year
			return $temp;
		} else {
			return false;
		}
	}

	public static function TimeHuman( $datetime ) {
		 // "200809230845"
		if ( ( strlen( $datetime ) == 12 ) && is_numeric( $datetime ) ) {
			$temp  = substr( $datetime, 8, 2 );   // time
			$temp .= ':';
			$temp .= substr( $datetime, 10, 2 );
			return $temp;
		} else {
			return false;
		}
	}

	public static function get_order_payment( $order ) {
		if ( ! empty( $order ) ) {

			if ( $order->custom_payment != '' ) {
				return $order->custom_payment;
			} else {
				if ( is_numeric( $order->payment ) ) {
					return $order->payment_method;
				} else {
					return $order->payment;
				}
			}
		} else {
			return 'NOT FOUND';
		}
	}

	public static function update_order_status( $order_id = 0, $data = array(), $notify_customer = true ) {
		 global $wpdb;

		if ( $order_id > 0 && ! empty( $data ) ) {
			$old_row_queue = self::get_order_by_id( $order_id );

			$columnArray          = array();
			$columnArray['state'] = $data['order_status'];

			if ( isset( $data['driver_id'] ) ) {
				$columnArray['driver_id'] = $data['driver_id'];
			}
			if ( isset( $data['vehicletype'] ) ) {
				$columnArray['vehicletype'] = $data['vehicletype'];
			}
			if ( isset( $data['modified'] ) ) {
				$columnArray['modified'] = $data['modified'];
			}
			if ( isset( $data['modified_by'] ) ) {
				$columnArray['modified_by'] = $data['modified_by'];
			}

			$row = $wpdb->update(
				$wpdb->prefix . 'tblight_orders',
				$columnArray,
				array(
					'id' => $order_id,
				)
			);

			$elsettings = self::config();
			$row_queue  = self::get_order_by_id( $order_id );

			if ( $row_queue ) {
				// Rejected orders will be archived only, but review email will not be sent
				if ( (int) $row_queue->state == -1 && $old_row_queue->state != 0 ) {
					// self::sendOrderArchiveEmail($row_queue, $elsettings);
				} else {

					$carObj                  = self::get_car_details( $row_queue->vehicletype );
					$row_queue->order_status = self::get_order_status_text( $row_queue, true ); // colored presentation = true

					$payment_data = '';
					if ( ! empty( $paymentObj ) ) {
						if ( ! empty( $paymentObj->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php' ) ) {
							require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
							require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php';

							$pluginTitle     = 'plgTblightPayment' . ucfirst( $paymentObj->payment_element );
							$tbPaymentPlugin = new $pluginTitle();
							$payment_data    = $tbPaymentPlugin->plgTbOnShowOrderEmailsInvoice( $row_queue->id, $row_queue->payment );
						}
					}

					$converted_currency_label = '';
					$header_info              = $elsettings->header_info;
					$contact_info             = $elsettings->contact_info;

					// now get the email template
					ob_start();
					include TBLIGHT_PLUGIN_PATH . 'templates/order_emails/confirmation_email.tpl.php';
					$mailbody = ob_get_contents();
					ob_end_clean();

					// Remove car booking data if order status is changed to rejected
					if ( $row_queue->state == 0 ) {
						self::cancel_car_bookings( $order_id );
					}

					if ( $notify_customer ) {
						$emailSubject = sprintf( 'BOOKING_STATUS_CHANGED_ORDER', $row_queue->order_number );

						// Always set content-type when sending HTML email
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

						// More headers
						$headers .= 'From: <' . get_option( 'admin_email' ) . '>' . "\r\n";

						try {
							mail( $row_queue->email, $emailSubject, $mailbody, $headers );
						} catch ( Exception $e ) {
							// echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
					}
				}
			}
		}

		return true;
	}

	public static function cancel_car_bookings( $order_id = 0 ) {
		if ( $order_id > 0 ) {
			global $wpdb;

			$wpdb->delete(
				"{$wpdb->prefix}tblight_order_car_rel",
				array( 'order_id' => $order_id ),
				array( '%d' )
			);
		}
		return true;
	}
}
