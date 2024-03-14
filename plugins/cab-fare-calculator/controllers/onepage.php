<?php
require_once TBLIGHT_PLUGIN_PATH . 'classes/company.helper.php';
require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';

class TblightControllerOnepage {

	public function getPrice() {
		global $wpdb;
		session_start();

		$post_data = sanitize_post( $_POST );

		$booking_type        = $post_data['booking_type'];
		$orderdate           = $post_data['orderdate'];
		$selPtHr1            = $post_data['selPtHr1'];
		$selPtMn1            = $post_data['selPtMn1'];
		$seltimeformat1      = $post_data['seltimeformat1'];
		$address_from        = $post_data['address_from'];
		$address_from_lat    = $post_data['address_from_lat'];
		$address_from_lng    = $post_data['address_from_lng'];
		$address_to          = $post_data['address_to'];
		$address_to_lat      = $post_data['address_to_lat'];
		$address_to_lng      = $post_data['address_to_lng'];
		$adultseats          = $post_data['passengers'];
		$page                = $post_data['page'];
		$chseats             = $post_data['chseats'];
		$selected_car        = $post_data['selected_car'];
		$name                = $post_data['name'];
		$email               = $post_data['email'];
		$phone               = $post_data['phone'];
		$country_id          = $post_data['country_id'];
		$tb_paymentmethod_id = $post_data['tb_paymentmethod_id'];

		$_SESSION['booking_type'] = $booking_type;

		$result = array(
			'error' => 0,
			'msg'   => '',
		);

		$begin         = $end = '';
		$lat_long_from = $lat_long_to = $waypoint_coords = array();

		$elsettings = BookingHelper::config();

		if ( ! empty( $post_data ) && $post_data['booking_type'] == 'address' ) {
			$begin            = $post_data['address_from'];
			$address_from_lat = $post_data['address_from_lat'];
			$address_from_lng = $post_data['address_from_lng'];

			$end            = $post_data['address_to'];
			$address_to_lat = $post_data['address_to_lat'];
			$address_to_lng = $post_data['address_to_lng'];

			if ( $address_from_lat == '' || $address_from_lng == '' ) {
				$result['error'] = 1;
				$result['msg']   = esc_attr_e( 'SELECT_COLLECTION_PLACE' );
				echo json_encode( $result );
				exit();
			} elseif ( $address_to_lat == '' || $address_to_lng == '' ) {
				$result['error'] = 1;
				$result['msg']   = esc_attr_e( 'SELECT_DESTINATION_PLACE' );
				echo json_encode( $result );
				exit();
			}

			$_SESSION['address_from']     = $begin;
			$_SESSION['address_from_lat'] = $address_from_lat;
			$_SESSION['address_from_lng'] = $address_from_lng;
			$lat_long_from                = array( $address_from_lat, $address_from_lng );

			$_SESSION['address_to']     = $end;
			$_SESSION['address_to_lat'] = $address_to_lat;
			$_SESSION['address_to_lng'] = $address_to_lng;
			$lat_long_to                = array( $address_to_lat, $address_to_lng );

			$_SESSION['begin']         = $begin;
			$_SESSION['end']           = $end;
			$_SESSION['lat_long_from'] = $lat_long_from;
			$_SESSION['lat_long_to']   = $lat_long_to;

			$distance = 0;
			$duration = '';
			if ( ! empty( $lat_long_from ) && ! empty( $lat_long_to ) ) {
				$call_gapi = true;

				if ( $call_gapi ) {
					list($distance,$duration_seconds,$gapi_status,$gapi_msg) = BookingHelper::calculateDistance( $lat_long_from[0], $lat_long_from[1], $lat_long_to[0], $lat_long_to[1], $elsettings );
				}

				if ( $gapi_status == 'OK' ) {
					if ( $call_gapi && $distance == 0 ) {
						$result['error'] = 1;
						$result['msg']   = 'No route could be calculated between desired destinations. Please amend them and try again.';
					} else {
						$distance_text = number_format( $distance, 2 );

						if ( $elsettings->distance_unit == 'mile' ) {
							$distance_text .= ' miles';
						} else {
							$distance_text .= ' kilometers';
						}

						$duration_text                = BookingHelper::secondsToTime( $duration_seconds );
						$_SESSION['distance']         = $distance; // distance ins KM or Mile
						$_SESSION['duration']         = $duration_text;
						$_SESSION['duration_seconds'] = $duration_seconds;

						$result['msg'] = array(
							'begin'          => $begin,
							'end'            => $end,
							'lat_long_from'  => $lat_long_from,
							'lat_long_to'    => $lat_long_to,
							'distance'       => $distance_text,
							'duration'       => $duration_text,
							'map_zoom'       => $elsettings->map_zoom,
							'show_direction' => 0,
						);
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
			} else {
				$result['error'] = 1;
				$result['msg']   = esc_attr_e( 'SESSION_EXPIRED' );
			}
		}

		return $result;
	}

	// get vehicle list according to the customer's choice
	public function getVehicles() {
		 $result = array(
			 'error'               => 0,
			 'call_next_available' => 0,
			 'msg'                 => '',
		 );

		 global $wpdb;
		 session_start();

		 $post_data = sanitize_post( $_POST );

		 $dtnow = current_time( 'mysql' );
		 $now   = current_time( 'timestamp' );

		 // Get the component configuration
		 $elsettings = BookingHelper::config();

		 $booking_type        = $post_data['booking_type'];
		 $orderdate           = $post_data['orderdate'];
		 $selPtHr1            = $post_data['selPtHr1'];
		 $selPtMn1            = $post_data['selPtMn1'];
		 $seltimeformat1      = $post_data['seltimeformat1'];
		 $address_from        = $post_data['address_from'];
		 $address_from_lat    = $post_data['address_from_lat'];
		 $address_from_lng    = $post_data['address_from_lng'];
		 $address_to          = $post_data['address_to'];
		 $address_to_lat      = $post_data['address_to_lat'];
		 $address_to_lng      = $post_data['address_to_lng'];
		 $adultseats          = $post_data['passengers'];
		 $page                = $post_data['page'];
		 $chseats             = $post_data['chseats'];
		 $selected_car        = $post_data['selected_car'];
		 $name                = $post_data['name'];
		 $email               = $post_data['email'];
		 $phone               = $post_data['phone'];
		 $country_id          = $post_data['country_id'];
		 $tb_paymentmethod_id = $post_data['tb_paymentmethod_id'];

		 $limit = 8;

		 $_SESSION['passengers'] = $adultseats;
		 $_SESSION['suitcases']  = $suitcases;
		 $_SESSION['chseats']    = $chseats;

		 $datepicker_type = 'jquery';
		 $date1           = $time1 = $date2 = $time2 = $dropoff_date = $dropoff_time = '';

		 if ( $datepicker_type == 'jquery' ) {
			 if ( ! empty( $post_data['orderdate'] ) ) {
				 if ( $post_data['orderdate'] != '' && $post_data['orderdate'] != 'Date' ) {
					 $date1 = $post_data['orderdate'];

					 if ( $elsettings->date_format == 'mm-dd-yy' ) {
						 $date1_arr = explode( '-', $date1 );
						 $date1     = $date1_arr[1] . '-' . $date1_arr[0] . '-' . $date1_arr[2];
					 }
				 }
			 }
		 } elseif ( $datepicker_type == 'inline' ) {
			 $date1 = $post_data['pickup_day'] . '-' . $post_data['pickup_month'] . '-' . $post_data['pickup_year'];
		 }

		 if ( isset( $post_data['selPtHr1'] ) && ( $post_data['selPtHr1'] != '' ) && isset( $post_data['selPtMn1'] ) && ( $post_data['selPtMn1'] != '' ) ) {
			 $time1 = $post_data['selPtHr1'] . ':' . $post_data['selPtMn1'];

			 if ( $elsettings->time_format == '12hr' ) {
				 $time1 .= $post_data['seltimeformat1'];
			 }
		 }

		 $_SESSION['date1'] = $date1;
		 $_SESSION['time1'] = $time1;

		 if ( $date1 != '' && $time1 != '' ) {
			 $order_date_time_str  = strtotime( $date1 . ' ' . $time1 );
			 $order_date_time      = gmdate( 'Y-m-d H:i:s', $order_date_time_str );
			 $_SESSION['timestr1'] = $order_date_time_str;
		 }

		 $returnjurney = 0;

		 $booking_data = array(
			 'booking_type'  => $booking_type,
			 'lat_long_from' => array( sanitize_text_field( $_SESSION['address_from_lat'] ), sanitize_text_field( $_SESSION['address_from_lng'] ) ),
			 'lat_long_to'   => array( sanitize_text_field( $_SESSION['address_to_lat'] ), sanitize_text_field( $_SESSION['address_to_lng'] ) ),
			 'adultseats'    => $adultseats,
			 'suitcases'     => $suitcases,
			 'chseats'       => $chseats,
		 );

		 list($base_pickup_distance,
			 $base_pickup_duration,
			 $base_pickup_price,
			 $base_pickup_price_calc,
			 $dropoff_base_distance,
			 $dropoff_base_duration,
			 $dropoff_base_price,
			 $dropoff_base_price_calc) = BookingHelper::considerBase( $elsettings, $booking_data );

		 $_SESSION['base_pickup_distance']    = $base_pickup_distance;
		 $_SESSION['base_pickup_duration']    = $base_pickup_duration;
		 $_SESSION['base_pickup_price']       = $base_pickup_price;
		 $_SESSION['base_pickup_price_calc']  = $base_pickup_price_calc;
		 $_SESSION['dropoff_base_distance']   = $dropoff_base_distance;
		 $_SESSION['dropoff_base_duration']   = $dropoff_base_duration;
		 $_SESSION['dropoff_base_price']      = $dropoff_base_price;
		 $_SESSION['dropoff_base_price_calc'] = $dropoff_base_price_calc;

		 // When Base to Pickup calculation is Enabled, we have to consider Leave Base time instead of Pickup Time
		 $order_date_time_leave_base_time = $order_date_time;
		 if ( (int) $elsettings->calculate_base_pickup == 1 ) {
			 $order_date_time_leave_base_time_str = $order_date_time_str - $base_pickup_duration;
			 $order_date_time_leave_base_time     = gmdate( 'Y-m-d H:i:s', $order_date_time_leave_base_time_str );
		 }
		 // print $dtnow;
		 // print $order_date_time_leave_base_time.'--';
		 // print BookingHelper::calculate_time_difference($dtnow, $order_date_time_leave_base_time, 'hr');
		 // die();
		 if ( $adultseats == 0 ) {
			 $result['error'] = 1;
			 $result['msg']   = 'No vehicle found! please check your passengers and suitcases.';
		 } elseif ( $_SESSION['date1'] == '' || $_SESSION['time1'] == '' ) {
			 $result['error'] = 1;
			 $result['msg']   = 'No vehicle found! Please enter departing date';
		 } elseif ( BookingHelper::calculate_time_difference( $dtnow, $order_date_time_leave_base_time, 'hr' ) < $elsettings->restrict_time ) {
			 $result['error'] = 1;
			 if ( (int) $elsettings->calculate_base_pickup == 1 ) {
				 $result['call_next_available'] = 0; // call another service to get next available car
			 }
			 $result['msg'] = BookingHelper::date_format( (int) $_SESSION['timestr1'], 'Y-m-d H:i:s', $elsettings ) . ' (' . sprintf( 'Booking is too soon, please allow %s hours before your time of Departure.', $elsettings->restrict_time ) . ')';
		 }

		 if ( $result['error'] == 0 ) {
			 $company_configs = CompanyHelper::getCompanyConfigs();
			 $debug_array     = BookingHelper::getInitialDebugArray( $company_configs, $elsettings );

			 $distance         = (float) $_SESSION['distance'];
			 $duration         = (float) $_SESSION['duration'];
			 $duration_seconds = (int) $_SESSION['duration_seconds'];

			 $price = 0;

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

			 if ( ! empty( $cars ) ) {
				 $debug_cars_array = array();
				 foreach ( $cars as $key => $car ) {
					 if ( BookingHelper::check_car_block_dates( $car ) === false ) {
						 unset( $cars[ $key ] );
					 } elseif ( BookingHelper::check_todays_availability( $car ) === false ) { // check todays opening/closing time and compare with current time
						 unset( $cars[ $key ] );
					 } elseif ( BookingHelper::check_car_previous_bookings( $car, $order_date_time_str, 0, $duration_seconds ) === false ) { // check this car previous booking journey
						 unset( $cars[ $key ] );
					 } else {
						 $car_price                    = 0;
						 $debug_cars_array[ $car->id ] = array(
							 'pickup_dropoff'        => array(
								 'title'         => '<a href="javascript:void(0);" target="_blank">Pickup to Dropoff Charge</a>',
								 'charge_string' => BookingHelper::price_display( 0, $elsettings ),
							 ),
							 'child_seats'           => array(
								 'title'         => '<a href="javascript:void(0);" target="_blank">Child Seats Charge</a>',
								 'total_unit'    => 'string',
								 'charge_string' => 0,
							 ),
							 'additional_car_charge' => array(
								 'title'         => '<a href="javascript:void(0);" target="_blank">Additional Car type Charge</a>',
								 'charge_string' => BookingHelper::price_display( 0, $elsettings ),
							 ),
							 'duration_charge'       => array(
								 'title'         => '<a href="javascript:void(0);" target="_blank">Duration Charge</a>',
								 'charge_string' => BookingHelper::price_display( 0, $elsettings ),
							 ),
							 'outbound_price'        => array(
								 'title'         => 'Outbound Price',
								 'charge_string' => BookingHelper::price_display( 0, $elsettings ),
							 ),
						 );

						 $unit_price    = (float) $car->unit_price;
						 $car_price     = $distance * $unit_price;
						 $charge_string = BookingHelper::distance_display( $distance, $elsettings ) . ' X ' . BookingHelper::price_display( $unit_price, $elsettings, true );
						 $debug_cars_array[ $car->id ]['pickup_dropoff']['charge_string'] = $charge_string . ' = ' . BookingHelper::price_display( $car_price, $elsettings, true );

						 // we have 2 conditions for min distance
						 // first if minimum distance > 0, price will be min.price if journey distance is less than min distance
						 // if Minimum distance 0 or empty, then Price will be Min price if calculated price is less than Min price
						 if ( (float) $car->minmil > 0 ) {
							 if ( $distance < (float) $car->minmil && $car_price < (float) $car->minprice ) {
								 $car_price = (float) $car->minprice;
								 $debug_cars_array[ $car->id ]['pickup_dropoff']['charge_string'] = BookingHelper::price_display( $car_price, $elsettings, true ) . ' (Minimum distance price)';
							 }
						 } else {
							 if ( (float) $car->minprice > 0 && $car_price < (float) $car->minprice ) {
								 $car_price = (float) $car->minprice;
								 $debug_cars_array[ $car->id ]['pickup_dropoff']['charge_string'] = BookingHelper::price_display( $car_price, $elsettings, true ) . ' (Minimum distance price)';
							 }
						 }

						 if ( $chseats > 0 ) {
							 $price_to_added = $chseats * (float) $car->child_seat_price;
							 $car_price     += $price_to_added;
							 $debug_cars_array[ $car->id ]['child_seats']['charge_string'] = $chseats . ' X ' . BookingHelper::price_display( (float) $car->child_seat_price, $elsettings, true ) . ' = ' . BookingHelper::price_display( $price_to_added, $elsettings, true );
						 }

						 // add car flat price
						 $debug_cars_array[ $car->id ]['additional_car_charge']['charge_string'] = BookingHelper::price_display( $car->price, $elsettings, true );
						 $car_price += $car->price;

						 // add seats + poi additional charge
						 $car_price += $price;

						 // add base price
						 $car_price                                   += ( $base_pickup_price + $dropoff_base_price );
						 $debug_array['base_pickup']['charge_string']  = $base_pickup_price_calc;
						 $debug_array['dropoff_base']['charge_string'] = $dropoff_base_price_calc;

						 // charge per min will be applied for Address booking only
						 if ( $booking_type == 'address' ) {
							 list($duration_charge,$unit_charge) = BookingHelper::calculate_charge_per_min( $duration_seconds, $car, $elsettings );
							 $car_price                         += $duration_charge;
							 $debug_cars_array[ $car->id ]['duration_charge']['charge_string'] = ceil( $duration_seconds / 60 ) . 'mins X ' . $unit_charge . ' = ' . BookingHelper::price_display( $duration_charge, $elsettings, true );
						 }

						 $outbound_price = $car_price;
						 $debug_cars_array[ $car->id ]['outbound_price']['charge_string'] = BookingHelper::price_display( ( $outbound_price ), $elsettings, true );

						 // round price based on configuration
						 $car_price      = BookingHelper::round_price( $car_price, $elsettings );
						 $car->car_price = $car_price;
					 }
				 }
			 }

			 require_once TBLIGHT_PLUGIN_PATH . 'helpers/sort_helper.php';
			 $cars       = sort_stack( $cars, 'car_price', 'ASC' );
			 $total_cars = count( $cars );
			 // $cars = array_slice($cars, 0, $page*$limit);
			 // $result['test'] = $total_cars;
			 // now generate car list html
			 $html       = '';
			 $car_prices = array();

			 require_once ABSPATH . 'wp-includes/pluggable.php';
			 $is_admin = false;
			 if ( current_user_can( 'administrator' ) ) {
				 $is_admin = true;
			 }

			 if ( ! empty( $cars ) ) {
				 $html .= '<div class="vehicles-body clearfix grid-view">
						<div class="vehicles-grid">';

				 $car_grid_html = $car_list_html = '';

				 $counter = 0;
				 foreach ( $cars as $car ) {
					 if ( $car->image != '' ) {
						 $img_src = $car->image;
					 } else {
						 $img_src = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/no-image.jpg';
					 }
					 $image = '<img src="' . $img_src . '" alt="' . $car->title . '" title="' . $car->title . '" />';

					 $car_price              = $car->car_price;
					 $car_prices[ $car->id ] = $car_price;

					 // display available car types
					 $available_car_type = '';

					 ob_start();
					 include TBLIGHT_PLUGIN_PATH . 'templates/car_three_grid.tpl.php';
					 $car_grid_html .= ob_get_contents();
					 ob_end_clean();

					 ob_start();
					 include TBLIGHT_PLUGIN_PATH . 'templates/car_three_list.tpl.php';
					 $car_list_html .= ob_get_contents();
					 ob_end_clean();
				 }

				 $_SESSION['car_prices'] = $car_prices;

				 $html .= $car_grid_html;
				 $html .= '</div></div><div class="vehicles-body clearfix list-view" style="display:none;">';
				 $html .= $car_list_html;
				 $html .= '</div>';

				 if ( $total_cars > ( $page * $limit ) ) {
					 $html .= '<div style="text-align:center;"><a href="javascript:void(0);" class="btn button-color" id="load_more_trigger">Load More</a></div>';
				 }

				 $result['msg']   = $html;
				 $result['total'] = $total_cars;
				 $result['limit'] = $limit;
				 $result['page']  = $page;
			 } else {
				 $result['error']               = 1;
				 $result['call_next_available'] = 0; // call another service to get next available car
				 $result['msg']                 = '<div class="cars">No vehicles available for this DateTime, please change DateTime and try again.</div>';
			 }
		 }

		 // additional information
		 $result['duration'] = (float) $_SESSION['duration'];
		 // $result['pickup_datetime'] = $date1.' '.$time1;
		 $pickup_datetime_str       = BookingHelper::date_format( (int) $_SESSION['timestr1'], 'Y-m-d H:i:s', $elsettings );
		 $result['pickup_datetime'] = $pickup_datetime_str;

		 $result['additional_seats_html'] = '';

		 $show_stops           = 0;
		 $stops_html           = '';
		 $result['show_stops'] = $show_stops;
		 $result['stops_html'] = $stops_html;

		 $result['message_for_customer'] = '';

		 echo json_encode( $result );
		 exit();
	}

	public function bookNow() {
		 $result = array(
			 'error' => 0,
			 'msg'   => '',
		 );

		 session_start();
		 $elsettings = BookingHelper::config();

		 $post_data = sanitize_post( $_POST );

		 $dtnow = current_time( 'mysql' );
		 $now   = current_time( 'timestamp' );

		 $vehicle_id = $post_data['vehicle_id'];
		 $car        = BookingHelper::get_car_details( $vehicle_id );

		 $car_prices   = (array) $_SESSION['car_prices'];
		 $booking_type = $_SESSION['booking_type'];
		 $returnjurney = 0;

		 $order_date_time_str = (int) $_SESSION['timestr1'];
		 $order_date_time     = gmdate( 'Y-m-d H:i:s', $order_date_time_str );

		 $next_datetime_str     = '';
		 $orderreturn_date_time = '';

		 $error = 0;
		 if ( empty( $car_prices ) ) {
			 $result['error'] = 1;
			 $result['msg']   = 'No route could be calculated between desired destinations. Please amend them and try again.';
		 } elseif ( $_SESSION['date1'] == '' || $_SESSION['time1'] == '' ) {
			 $result['error'] = 1;
			 $result['msg']   = 'No vehicle found! Please enter departing date';
		 } elseif ( BookingHelper::calculate_time_difference( $dtnow, $order_date_time, 'hr' ) < $elsettings->restrict_time ) {
			 $result['error'] = 1;
			 $result['msg']   = BookingHelper::date_format( (int) $_SESSION['timestr1'], 'Y-m-d H:i:s', $elsettings ) . ' (' . sprintf( 'Booking is too soon, please allow %s hours before your time of Departure.', $elsettings->restrict_time ) . ')';
		 } elseif ( ! BookingHelper::check_car_block_dates( $car ) ) {
			 $result['error'] = 1;
			 $result['msg']   = 'Booking date is blocked for this vehicle. Please choose another vehicle.';
		 } elseif ( ! BookingHelper::check_todays_availability( $car ) ) { // check todays opening/closing time and compare with current time
			 $result['error'] = 1;
			 $result['msg']   = 'This vehicle is not available on this Booking datetime.';
		 } elseif ( $vehicle_id > 0 && ! empty( $car_prices ) && isset( $car_prices[ $vehicle_id ] ) ) {  // vehicle validation
			 $price                  = $car_prices[ $vehicle_id ];
			 $_SESSION['vehicle_id'] = $vehicle_id;
			 $_SESSION['price']      = $price;

			 // payment methods list
			 $payment_not_found_text = '';
			 $selectedPayment        = (int) $_SESSION['tb_paymentmethod_id'];

			 if ( file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/cash.php' ) ) {
				 require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
				 require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/cash.php';

				 $tbPaymentPlugin      = new plgTblightPaymentCash();
				 $payment_methods      = $tbPaymentPlugin->plgTbDisplayListFEPayment( $selectedPayment );
				 $found_payment_method = count( $payment_methods );
				 $payment_html         = implode( "\n", $payment_methods );
			 }

			 $additional_seats_html = '';
			 $adultseats            = (int) $_SESSION['passengers'];
			 $suitcases             = (int) $_SESSION['suitcases'];
			 $chseats               = (int) $_SESSION['chseats'];

			 if ( $adultseats > 1 ) {
				 $adultseats_html = $adultseats . ' Passengers';
			 } else {
				 $adultseats_html = $adultseats . ' Passenger';
			 }

			 $stop_text  = '';
			 $show_stops = 0;

			 $sub_total = $price;

			 if ( $suitcases > 0 ) {
				 $additional_seats_html .= '<div class="check_box_wrap clearfix">
				    <div class="check_name">Suitcases:</div>
				    <div class="check_desc">' . $suitcases . '</div>
				</div>';
			 }
			 if ( $chseats > 0 ) {
				 $price_to_added         = $chseats * (float) $car->child_seat_price;
				 $price_to_added         = ( $price_to_added > 0 ) ? ' (' . BookingHelper::price_display( $price_to_added, $elsettings ) . ')' : '';
				 $additional_seats_html .= '<div class="check_box_wrap clearfix">
				    <div class="check_name">Child seats:</div>
				    <div class="check_desc">' . $chseats . $price_to_added . '</div>
				</div>';
			 }

			 $flat_cost       = (float) $_SESSION['flat_cost'];
			 $percentage_cost = (float) $_SESSION['percentage_cost'];
			 $grand_total     = $price + $flat_cost + $percentage_cost;

			 $pickup_datetime = BookingHelper::date_format( (int) $_SESSION['timestr1'], 'Y-m-d H:i:s', $elsettings );

			 // UPDATE July14.2016 - hide Additional seats and Total seats in 3rd step if Child seats are all disabled at back end
			 $show_additional_seats = 1;
			 if ( $chseats == 0
				) {
				 $show_additional_seats = 0;
			 }

			 $booking_data = array(
				 'booking_type'          => 'address',
				 'returnjurney'          => 0,
				 'show_price'            => (int) $elsettings->show_price,
				 'begin'                 => $_SESSION['begin'],
				 'show_stops'            => $show_stops,
				 'stop_text'             => $stop_text,
				 'end'                   => $_SESSION['end'],
				 'price'                 => BookingHelper::price_display( (float) $_SESSION['price'], $elsettings ),
				 'payment_html'          => $payment_html,
				 'found_payment_method'  => $found_payment_method,
				 'car'                   => $car,
				 'show_price'            => (int) $elsettings->show_price,
				 'enable_captcha'        => (int) $elsettings->enable_captcha,
				 'additional_seats_html' => $additional_seats_html,
				 'sub_total'             => BookingHelper::price_display( $sub_total, $elsettings ),
				 'show_flat_cost'        => ( $flat_cost != 0 ) ? 1 : 0,
				 'flat_cost'             => ( $flat_cost != 0 ) ? BookingHelper::price_display( $flat_cost, $elsettings ) : 0,
				 'show_percentage_cost'  => ( $percentage_cost != 0 ) ? 1 : 0,
				 'percentage_cost'       => ( $percentage_cost != 0 ) ? BookingHelper::price_display( $percentage_cost, $elsettings ) : 0,
				 'grand_total'           => BookingHelper::price_display( $grand_total, $elsettings ),
				 'pickup_datetime'       => $pickup_datetime,
				 'adultseats'            => $adultseats,
				 'adultseats_html'       => $adultseats_html,
				 'childseats'            => $chseats,
				 'totalpassengers'       => $adultseats + $chseats,
				 'show_additional_seats' => $show_additional_seats,
				 'suitcases'             => $suitcases,
			 );

			 $result['msg'] = $booking_data;
		 }

		 echo json_encode( $result );
		 exit();
	}
	/**
	 * Logic to calculate grand total on 3rd step
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	public function calculateTotal() {
		$json_result = array(
			'error' => 0,
			'msg'   => '',
		);

		session_start();
		$elsettings = BookingHelper::config();

		$post_data = sanitize_post( $_POST );

		$tb_paymentmethod_id             = $post_data['tb_paymentmethod_id'];
		$_SESSION['tb_paymentmethod_id'] = $tb_paymentmethod_id;

		$grand_total  = $sub_total = 0;
		$price        = (float) $_SESSION['price'];
		$booking_type = 'address';
		$returnjurney = 0;

		$sub_total = $price;

		// calculate extras price
		$total_extra_price = $return_extra_price = $total_user_details_extra_price = 0;

		$grand_total += $price + $total_extra_price + $return_extra_price + $total_user_details_extra_price;

		$payment_labels = '';

		$paymentObj = BookingHelper::get_payment_details( $tb_paymentmethod_id );

		if ( ! empty( $paymentObj ) ) {
			if ( ! empty( $paymentObj->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php' ) ) {
				require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
				require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php';

				$pluginTitle     = 'plgTblightPayment' . ucfirst( $paymentObj->payment_element );
				$tbPaymentPlugin = new $pluginTitle();
				$tbPaymentPlugin->plgTbonSelectedCalculatePricePayment( $tb_paymentmethod_id, $grand_total );
			}
		}

		$flat_cost       = (float) $_SESSION['flat_cost'];
		$percentage_cost = (float) $_SESSION['percentage_cost'];

		$grand_total += $flat_cost + $percentage_cost;

		$booking_data = array(
			'booking_type'         => 'address',
			'show_price'           => (int) $elsettings->show_price,
			'sub_total'            => BookingHelper::price_display( $sub_total, $elsettings ),
			'show_flat_cost'       => ( $flat_cost != 0 ) ? 1 : 0,
			'flat_cost'            => ( $flat_cost != 0 ) ? BookingHelper::price_display( $flat_cost, $elsettings ) : 0,
			'show_percentage_cost' => ( $percentage_cost != 0 ) ? 1 : 0,
			'percentage_cost'      => ( $percentage_cost != 0 ) ? BookingHelper::price_display( $percentage_cost, $elsettings ) : 0,
			'grand_total'          => BookingHelper::price_display( $grand_total, $elsettings ),
			'payment_labels'       => $payment_labels,
		);

		$result['msg'] = $booking_data;

		echo json_encode( $result );
		exit();
	}
	public function submitOrder() {
		 global $wpdb;

		$json_result = array(
			'error'        => 0,
			'msg'          => '',
			'redirect_url' => '',
		);

		session_start();
		$elsettings = BookingHelper::config();

		$post_data = sanitize_post( $_POST );

		$gmtnow = current_time( 'mysql' );
		$dtnow  = current_time( 'mysql' );
		$now    = current_time( 'timestamp' );

		// get data from request
		$booking_type         = 'address';
		$name                 = $post_data['name'];
		$email                = $post_data['email'];
		$countryId            = $post_data['country_id'];
		$phone                = $post_data['phone'];
		$country_calling_code = $post_data['country_calling_code'];
		// tripping leading 0 and + from phone number
		$phone                = ltrim( $phone, '0+' );
		$g_recaptcha_response = $post_data['booking-g-recaptcha-response'];

		$tb_paymentmethod_id = $post_data['tb_paymentmethod_id'];
		$payment_post_data   = $post_data['payment_data'];

		$returnjurney = 0;

		$order_date_time_str = (int) $_SESSION['timestr1'];
		$order_date_time     = gmdate( 'Y-m-d H:i:s', $order_date_time_str );

		$orderreturn_date_time = '';

		if ( BookingHelper::calculate_time_difference( $dtnow, $order_date_time, 'hr' ) < $elsettings->restrict_time ) {
			$err_msg              = BookingHelper::date_format( (int) $_SESSION['timestr1'] ) . ' (' . sprintf( 'Booking is too soon, please allow %s hours before your time of Departure.', $elsettings->restrict_time ) . ')';
			$json_result['msg']   = $err_msg;
			$json_result['error'] = 1;
		} elseif ( ! is_email( $email ) ) {
			$err_msg              = 'Please add a valid email address';
			$json_result['msg']   = $err_msg;
			$json_result['error'] = 1;
		} elseif ( $tb_paymentmethod_id == 0 ) {
			$err_msg              = 'No payment method is set!';
			$json_result['msg']   = $err_msg;
			$json_result['error'] = 1;
		} elseif ( $elsettings->enable_captcha == 1 && $g_recaptcha_response == '' ) {
			$err_msg              = 'Please prove that you are not a robot!';
			$json_result['msg']   = $err_msg;
			$json_result['error'] = 1;
		} else {
			$price_debug = false; // this is for internal use

			$returntrip      = 0;
			$cprice          = (float) $_SESSION['price'];
			$flat_cost       = (float) $_SESSION['flat_cost'];
			$percentage_cost = (float) $_SESSION['percentage_cost'];
			$selpassengers   = (int) $post_data['passengers'];
			$selsuitcases    = (int) $post_data['suitcases'];
			$selchildseats   = (int) $post_data['chseats'];
			$vehicletype     = (int) $_SESSION['vehicle_id'];

			$order_date_time_str       = (int) $_SESSION['timestr1'];
			$orderreturn_date_time_str = '';

			$distance         = (float) $_SESSION['distance'];  // distance in KM or Mile
			$duration_seconds = (int) $_SESSION['duration_seconds'];
			$duration_text    = (float) $_SESSION['duration'];
			$begin            = $_SESSION['begin'];
			$end              = $_SESSION['end'];

			$pickup_lat  = sanitize_text_field( $_SESSION['address_from_lat'] );
			$pickup_lng  = sanitize_text_field( $_SESSION['address_from_lng'] );
			$dropoff_lat = sanitize_text_field( $_SESSION['address_to_lat'] );
			$dropoff_lng = sanitize_text_field( $_SESSION['address_to_lng'] );

			// calculate extras price
			$total_extra_price = $return_extra_price = $total_user_details_extra_price = 0;

			$cprice += $total_extra_price + $return_extra_price + $total_user_details_extra_price;

			if ( $price_debug ) {
				echo "\n sub_total: " . esc_attr( $cprice );
				echo "\n flat_cost: " . esc_attr( $flat_cost );
				echo "\n percentage_cost: " . esc_attr( $percentage_cost );
			}

			// total cost
			$grand_total = $cprice + $flat_cost + $percentage_cost;
			if ( $price_debug ) {
				echo "\n grand_total: " . esc_attr( $grand_total );
			}

			// auto approve free orders if backend setting is set to YES
			if ( ( (int) $elsettings->show_price == 0 ) || ( $grand_total <= 0 && (int) $elsettings->auto_approve_free_order == 1 ) ) {
				$new_order_status = 1;
			} else {
				$new_order_status = -2; // WAITING
			}

			if ( $price_debug ) {
				//echo '<pre>';
				//die( print_r( $order ) );
			}

			$car = BookingHelper::get_car_details( $vehicletype );

			$vehicle_title = '';
			if ( ! empty( $car ) ) {
				$vehicle_title = $car->title;
			}

			$paymentObj   = BookingHelper::get_payment_details( $tb_paymentmethod_id );
			$payment_name = '';
			if ( ! empty( $paymentObj ) ) {
				$payment_name = $paymentObj->title;
			}

			// now prepare order data
			$row = $wpdb->insert(
				$wpdb->prefix . 'tblight_orders',
				array(
					'order_number'         => uniqid(),
					'names'                => $name,
					'email'                => $email,
					'phone'                => $phone,
					'country_id'           => $countryId,
					'country_calling_code' => $country_calling_code,
					'booking_type'         => $booking_type,
					'sub_total'            => $cprice,
					'flat_cost'            => $flat_cost,
					'percentage_cost'      => $percentage_cost,
					'cprice'               => $grand_total,
					'selpassengers'        => $selpassengers,
					'selchildseats'        => $selchildseats,
					'selluggage'           => $selsuitcases,
					'datetime1'            => $order_date_time_str,
					'datetime2'            => $orderreturn_date_time_str,
					'distance'             => $distance,
					'duration'             => $duration_seconds,
					'duration_text'        => $duration_text,
					'begin'                => $begin,
					'end'                  => $end,
					'state'                => $new_order_status,
					'driver_id'            => 0,
					'payment'              => $tb_paymentmethod_id,
					'payment_name'         => $payment_name,
					'vehicletype'          => $vehicletype,
					'vehicle_title'        => $vehicle_title,
					'created'              => current_time( 'Y-m-d H:i:s' ),
					'source'               => 'frontend',
					'pickup_lat'           => $pickup_lat,
					'pickup_lng'           => $pickup_lng,
					'dropoff_lat'          => $dropoff_lat,
					'dropoff_lng'          => $dropoff_lng,
					'ip_address'           => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
					'user_agent'           => sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ),
				),
				array(
					'%s', // order_number
					'%s', // names
					'%s', // email
					'%s', // phone
					'%d', // country_id
					'%d', // country_calling_code
					'%s', // booking_type
					'%f', // sub_total
					'%f', // flat_cost
					'%f', // percentage_cost
					'%f', // cprice
					'%d', // selpassengers
					'%d', // selchildseats
					'%d', // selluggage
					'%s', // datetime1
					'%s', // datetime2
					'%f', // distance
					'%f', // duration
					'%s', // duration_text
					'%s', // begin
					'%s', // end
					'%d', // state
					'%d', // driver_id
					'%d', // payment
					'%s', // payment_name
					'%d', // vehicletype
					'%s', // vehicle_title
					'%s', // created
					'%s', // source
					'%f', // pickup_lat
					'%f', // pickup_lng
					'%f', // dropoff_lat
					'%f', // dropoff_lng
					'%s', // ip_address
					'%s',  // user_agent
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

			$order_id = (int) $wpdb->insert_id;
			$order    = BookingHelper::get_order_by_id( $order_id );

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
						'order_id'           => $order_id,
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

			if ( ! empty( $paymentObj ) ) {
				if ( ! empty( $paymentObj->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php' ) ) {
					require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
					require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php';

					$pluginTitle     = 'plgTblightPayment' . ucfirst( $paymentObj->payment_element );
					$tbPaymentPlugin = new $pluginTitle();
					$tbPaymentPlugin->plgTbOrderSubmit( $order );
				}
			}

			$this->sendMailDetailsToOwner( $order, $elsettings, $paymentObj, $car );

			// set last order Id to show in thanks page
			$_SESSION['tb_last_order_id'] = $order_id;

			$this->clearSession();

			if ( ! empty( $post_data['booking_form_url'] ) ) {
				$redirect_url = $post_data['booking_form_url'] . '?pg=thanks&oid=' . $order_id;
			} else {
				$redirect_url = site_url();
			}

			$json_result['redirect_url'] = $redirect_url;
		}

		echo json_encode( $json_result );
		exit();
	}

	/**
	 * Send Email to owner of the order
	 *
	 * @access public
	 * @since 1.0
	 */
	public function sendMailDetailsToOwner( $row_queue, $elsettings, $paymentObj, $carObj ) {
		$row_queue->order_status = BookingHelper::get_order_status_text( $row_queue, true ); // colored presentation = true

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

		// $fp = fopen(TBLIGHT_PLUGIN_PATH.'checkemail.txt', 'a');
		// fwrite($fp, $mailbody);
		// fclose($fp);

		// now send the Pending status email to customer if Send email for Pending status = YES
		if ( isset( $paymentObj->send_email_pending_status ) && isset( $paymentObj->send_email_approved_status ) ) {
			if ( ( $row_queue->state == -2 && $paymentObj->send_email_pending_status == 1 )
			  || ( $row_queue->state == 1 && $paymentObj->send_email_approved_status == 1 )
			) {
				$notify_customer = true;
			} else {
				$notify_customer = false;
			}
		} else {
			$notify_customer = true;
		}

		$order_invoice_number = $row_queue->order_number;

		if ( $notify_customer ) {
			$emailSubject = sprintf( 'Booking Notification : Reference no - %s', $order_invoice_number ) . ' ' . sprintf( 'STATUS: %s', BookingHelper::get_order_status_text( $row_queue, false ) );

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

		return;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function getAvailableCarsAjax() {
		require_once TBLIGHT_PLUGIN_PATH . 'classes/adminbooking.php';

		global $wpdb;

		session_start();

		$json_result = array(
			'error'              => 0,
			'msg'                => '',
			'selected_car'       => 0,
			'selected_car_price' => 0,
		);

		$elsettings = BookingHelper::config();

		$post_data = sanitize_post( $_POST );

		$editing_order_id = $post_data['id'];
		$booking_type     = $post_data['booking_type'];

		$order_data               = new stdClass();
		$order_data->booking_type = $booking_type;
		$order_data->pickup_date  = $post_data['pickup_date'] . ' ' . $post_data['pickup_hr'] . ':' . $post_data['pickup_min'];
		if ( $elsettings->date_format == 'mm-dd-yy' ) {
			$date1_temp              = explode( '-', $post_data['pickup_date'] );
			$order_data->pickup_date = $date1_temp[2] . '-' . $date1_temp[0] . '-' . $date1_temp[1];
		}

		$order_data->adultseats = $post_data['selpassengers'];
		$order_data->suitcases  = $post_data['selluggage'];
		$order_data->childseats = $post_data['selchildseats'];

		if ( $booking_type == 'address' ) {
			$order_data->pickup_address  = $post_data['pickup_address'];
			$order_data->pickup_coords   = array( $post_data['pickup_lat'], $post_data['pickup_lng'] );
			$order_data->dropoff_address = $post_data['dropoff_address'];
			$order_data->dropoff_coords  = array( $post_data['dropoff_lat'], $post_data['dropoff_lng'] );
		}

		$order_data->editing_order_id = $editing_order_id;
		$result                       = TBAdminBooking::getAvailableCars( $order_data );

		if ( $result['error'] == 1 ) {
			$json_result['error'] = 1;
			$json_result['msg']   = $result['msg'];
		} else {
			if ( ! empty( $result['available_cars'] ) ) {
				$available_cars = $result['available_cars'];

				if ( $editing_order_id > 0 ) {
					$editOrderObj = BookingHelper::get_order_by_id( $editing_order_id );
				}

				ob_start();
				?>
<table class="table table-striped no-more-tables" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th class="title" width="20%"><?php esc_attr_e( 'Title' ); ?></th>
			<th class="numeric" width="20%"><?php esc_attr_e( 'Image' ); ?></th>
			<th class="numeric" width="10%"><?php esc_attr_e( 'Passenger No' ); ?></th>
			<th class="numeric" width="10%"><?php esc_attr_e( 'Suitcase No' ); ?></th>
			<th class="numeric" width="20%"><?php esc_attr_e( 'Price' ); ?></th>
			<th class="numeric" width="20%"><?php esc_attr_e( 'Select' ); ?></th>
		</tr>
	</thead>
	<tbody>
				<?php
				$k = 0;
				for ( $i = 0, $n = count( $available_cars ); $i < $n; $i++ ) {
					$car = $available_cars[ $i ];
					?>
		<tr class="car_row <?php echo "row$k"; ?>" data-price="<?php echo $car->car_price; ?>">
			<td data-title="<?php esc_attr_e( 'Title' ); ?>" align="center">
				<span><?php echo htmlspecialchars( $car->title, ENT_QUOTES, 'UTF-8' ); ?></span>
			</td>
			<td data-title="<?php echo _( 'Image' ); ?>" class="numeric" align="center">
					<?php if ( $car->image != '' ) { ?>
				<img src="<?php echo $car->image; ?>" alt="<?php echo $car->title; ?>" width="50" />
						<?php
					} else {
						echo '&nbsp;'; }
					?>
			</td>
			<td data-title="<?php esc_attr_e( 'Passenger No' ); ?>" class="numeric" align="center"><?php echo $car->passenger_no; ?></td>
			<td data-title="<?php esc_attr_e( 'Suitcase No' ); ?>" class="numeric" align="center"><?php echo $car->suitcase_no; ?></td>
			<td data-title="<?php esc_attr_e( 'Price' ); ?>" class="numeric" align="center"><?php echo BookingHelper::price_display( (float) $car->car_price ); ?></td>
			<td data-title="Assign" class="numeric" align="center">
					<?php
					if ( $editing_order_id > 0 ) {
						if ( $editOrderObj->vehicletype == $car->id ) {
							$checked                           = ' checked="checked"';
							$json_result['selected_car']       = $car->id;
							$json_result['selected_car_price'] = $car->car_price;
						} else {
							$checked = '';
						}
					} else {
						$checked = '';
					}
					?>
				<input type="radio" class="assign_car" name="car_id" id="car_<?php echo $car->id; ?>" value="<?php echo $car->id; ?>"<?php echo $checked; ?> />
			</td>
		</tr>
				<?php $k = 1 - $k; } ?>
	</tbody>
</table>

<input type="hidden" name="distance" id="distance" value="<?php echo ! empty( $result['additional_params']['distance'] ) ? $result['additional_params']['distance'] : 0; ?>" />
<input type="hidden" name="duration_seconds" id="duration_seconds" value="<?php echo ! empty( $result['additional_params']['duration_seconds'] ) ? $result['additional_params']['duration_seconds'] : 0; ?>" />
<input type="hidden" name="base_pickup_duration" id="base_pickup_duration" value="<?php echo ! empty( $result['additional_params']['base_pickup_duration'] ) ? $result['additional_params']['base_pickup_duration'] : 0; ?>" />
<input type="hidden" name="dropoff_base_duration" id="dropoff_base_duration" value="<?php echo ! empty( $result['additional_params']['dropoff_base_duration'] ) ? $result['additional_params']['dropoff_base_duration'] : 0; ?>" />

				<?php
				$html = ob_get_contents();
				ob_end_clean();

				$json_result['msg'] = $html;
			} else {
				$json_result['error'] = 1;
				$json_result['msg']   = esc_attr_e( 'Car Not Found' );
			}
		}

		echo json_encode( $json_result );
		exit();
	}

	public function changeStatusAjax() {
		$json_result = array(
			'error' => 0,
			'msg'   => '',
		);

		global $wpdb;

		$post_data = sanitize_post( $_POST );

		$elsettings = BookingHelper::config();
		$order_id   = $post_data['id'];
		$new_status = $post_data['new_status'];

		if ( $order_id > 0 ) {
			$row = $wpdb->update(
				$wpdb->prefix . 'tblight_orders',
				array(
					'state'       => $new_status,
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $order_id,
				)
			);

			$row_queue = BookingHelper::get_order_by_id( $order_id );

			if ( $row_queue ) {
				if ( $new_status == -1 ) {
					// BookingHelper::sendOrderArchiveEmail($row_queue, $elsettings);
				} else {
					$carObj     = BookingHelper::get_car_details( $row_queue->vehicletype );
					$paymentObj = BookingHelper::get_payment_details( $row_queue->payment );

					$row_queue->order_status = BookingHelper::get_order_status_text( $row_queue, true ); // colored presentation = true

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

					$emailSubject = sprintf( 'Booking Notification : Reference no - %s', $order_invoice_number ) . ' ' . sprintf( 'STATUS: %s', BookingHelper::get_order_status_text( $row_queue, false ) );

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

					// Remove car booking data if order status is changed to rejected
					if ( $row_queue->state == 0 ) {
						BookingHelper::cancel_car_bookings( $order_id );
					}
				}

				$json_result['error'] = 0;
				$json_result['msg']   = 'Successful';
			} else {
				$json_result['error'] = 1;
				$json_result['msg']   = 'Order not found!';
			}
		} else {
			$json_result['error'] = 1;
			$json_result['msg']   = 'Order not found!';
		}

		echo json_encode( $json_result );
		exit();
	}

	public function resetBookingForm() {
		$json_result = array(
			'error' => 0,
			'msg'   => '',
		);

		$this->clearSession();

		$json_result['msg'] = 'Session deleted successfully!';

		echo json_encode( $json_result );
		exit();
	}

	public function clearSession() {
		session_start();

		$_SESSION['price']           = 0;
		$_SESSION['flat_cost']       = 0;
		$_SESSION['percentage_cost'] = 0;

		$_SESSION['distance']         = 0;
		$_SESSION['duration_seconds'] = 0;
		$_SESSION['duration']         = 0;
		$_SESSION['begin']            = '';
		$_SESSION['end']              = '';

		$_SESSION['address_from']     = '';
		$_SESSION['address_from_lat'] = '';
		$_SESSION['address_from_lng'] = '';
		$_SESSION['lat_long_from']    = array();

		$_SESSION['address_to']     = '';
		$_SESSION['address_to_lat'] = '';
		$_SESSION['address_to_lng'] = '';
		$_SESSION['lat_long_to']    = array();

		$_SESSION['vehicle_id'] = 0;

		$_SESSION['passengers'] = 0;
		$_SESSION['suitcases']  = 0;
		$_SESSION['chseats']    = 0;
		$_SESSION['car_prices'] = '';

		$_SESSION['date1']               = '';
		$_SESSION['time1']               = '';
		$_SESSION['timestr1']            = '';
		$_SESSION['tb_paymentmethod_id'] = 0;

		return;
	}
}
