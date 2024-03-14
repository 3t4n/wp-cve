<?php
/**
 * This file contains a class that is used to setup the LearnDash endpoints.
 *
 * @package learndash-reports-by-wisdmlabs
 */

require_once 'class-wrld-common-functions.php';
/**
 * Class that sets up all the LearnDash endpoints
 *
 * @author     WisdmLabs
 * @since      1.0.0
 * @subpackage LearnDash API
 */
class WRLD_Revenue_API extends WRLD_Common_Functions {

	/**
	 * This static contains the number of points being assigned on course completion
	 *
	 * @var    Instance of WRLD_Revenue_API class
	 * @since  1.0.0
	 * @access private
	 */
	private static $instance = null;

	/**
	 * This static method is used to return a single instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Object
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * This is a constructor which will be used to initialize required hooks
	 *
	 * @since  1.0.0
	 * @access private
	 * @see    initHook static method
	 */
	private function __construct() {
	}

	/**
	 * This method is used to get total learners registered in the date range.
	 *
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_total_learners() {
		// Get inputs and validate.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$duration     = self::get_duration_data( $request_data['start_date'], $request_data['end_date'] );
		$user_stats   = count_users();
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			 $response = apply_filters('wrld_api_response_from_json_daily_enrollment_total',$duration);

			 set_transient( "wrld_daily_enrollment_data_data", $response, 3000 );
			
			$totalELearner = $response['totalEnrollments'];


			return new WP_REST_Response(
					array(
						'requestData'       => self::get_values_for_request_params( $request_data ),
						'userStats'         => 'active',
						'totalLearners'     => $totalELearner, // backword compatibility
						'newRegistrations'  => 10,
						'prevRegistrations' => 20,
						'percentChange'     => rand(-10,90),
					),
					200
				);
		}

		$duration_user_count      = self::get_users_registered_between( $duration['start_date'], $duration['end_date'] );
		$prev_duration_user_count = self::get_users_registered_between( $duration['prev_start_date'], $duration['prev_end_date'] );

		$percentage_change = 0;
		if ( 0 != $prev_duration_user_count ) {
			$percentage_change = floatval( number_format( ( $duration_user_count - $prev_duration_user_count ) * 100 / $prev_duration_user_count, 2, '.', '' ) );// Cast to integer if no decimals.
		}

		return new WP_REST_Response(
			array(
				'requestData'       => self::get_values_for_request_params( $request_data ),
				'userStats'         => $user_stats,
				'totalLearners'     => $duration_user_count, // backword compatibility
				'newRegistrations'  => $duration_user_count,
				'prevRegistrations' => $prev_duration_user_count,
				'percentChange'     => $percentage_change,
			),
			200
		);
	}

	/**
	 * This method is used to get total courses added in the date range.
	 *
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_total_courses() {
		// Get inputs and validate.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$duration     = self::get_duration_data( $request_data['start_date'], $request_data['end_date'] );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language

		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_total_course',$request_data);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}

		$courses_added_in_duration       = self::get_courses_created_between( $duration['start_date'], $duration['end_date'] );
		$courses_added_in_prev_duration  = self::get_courses_created_between( $duration['prev_start_date'], $duration['prev_end_date'] );
		$courses_count_for_duration      = $courses_added_in_duration->found_posts;
		$courses_count_for_prev_duration = $courses_added_in_prev_duration->found_posts;

		$percentage_change = 0;
		if ( 0 != $courses_count_for_prev_duration ) {
			// Calculate % change.
			$percentage_change = floatval( number_format( ( $courses_count_for_duration - $courses_count_for_prev_duration ) * 100 / $courses_count_for_prev_duration, 2, '.', '' ) );// Cast to integer if no decimals.
		}
		return new WP_REST_Response(
			array(
				'requestData'   => self::get_values_for_request_params( $request_data ),
				'totalCourses'  => $courses_count_for_duration,
				'percentChange' => $percentage_change,
			),
			200
		);
	}

	/**
	 * This method is an API callback that return the total revenue earned on the LMS.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_total_revenue() {
		// Get inputs and validate.
		$currency     = '';
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$duration     = self::get_duration_data( $request_data['start_date'], $request_data['end_date'] );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		// Get revenue from LearnDash transactions.
		$revenue = $this->get_learndash_transactions_total( $duration['start_date'], $duration['end_date'] );
		// Check if WooCommerce active.
		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			// Add the revenue from WooCommerce orders(LD-Woo integration product type.).
			$revenue  = $revenue + $this->get_woocommerce_transactions_total( $duration['start_date'], $duration['end_date'] );
			$currency = get_woocommerce_currency();
		}

		$currency = function_exists( 'learndash_get_currency_symbol' ) ? learndash_get_currency_symbol() : '';
		$currency = empty( $currency ) && function_exists( 'learndash_30_get_currency_symbol' ) ? @learndash_30_get_currency_symbol() : $currency;
		$currency = empty( $currency ) ? $currency : '$';


		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_revenue_from_course_earned',$request_data);

			set_transient( "wrld_revene_reaponse_data", $response, 3000 );
			
			$totalRevenueEarned = $response['totalRevenue'];

			return new WP_REST_Response(
				array(
					'requestData'        => self::get_values_for_request_params( $request_data ),
					'totalRevenueEarned' => $totalRevenueEarned,
					'percentChange'      => rand(-10,90),
					'currency'           => $currency,
				),
				200
			);
		}

		// Calculate the previous Revenue.
		$previous_revenue = $this->get_learndash_transactions_total( $duration['prev_start_date'], $duration['prev_end_date'] );
		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			$previous_revenue = $previous_revenue + $this->get_woocommerce_transactions_total( $duration['prev_start_date'], $duration['prev_end_date'] );
		}
		$percentage_change = 0;
		if ( 0 != $previous_revenue ) {
			// Calculate % change.
			$percentage_change = floatval( number_format( ( $revenue - $previous_revenue ) * 100 / $previous_revenue, 2, '.', '' ) );// Cast to integer if no decimals.
		}

		return new WP_REST_Response(
			array(
				'requestData'        => self::get_values_for_request_params( $request_data ),
				'totalRevenueEarned' => floatval( number_format( $revenue, 2, '.', '' ) ),
				'percentChange'      => $percentage_change,
				'currency'           => $currency,
			),
			200
		);
	}


	/**
	 * This function when called , calculates coursewise revenue from the LD Transactions between the specified dates
	 * and returns an array of course ids with respect to the total revenue earned from those courses.
	 *
	 * @param string $from_date
	 * @param string $to_date
	 * @return array $revenue_by_course
	 */
	public static function get_ld_coursewise_revenue( $from_date, $to_date ) {
		$revenue_by_course = array();
		$ld_transactions   = self::get_ld_transactions( $from_date, $to_date );
		$excluded_courses  = get_option( 'exclude_courses', false );

		foreach ( $ld_transactions as $transaction ) {
			if ( ! empty( $excluded_courses ) && in_array( $transaction->course_id, $excluded_courses ) ) {
				continue;
			}
			if ( isset( $_GET[ 'wpml_lang' ] ) ) {
				$args             = array(
					'post_type'      => 'sfwd-courses',
					'posts_per_page' => -1,
					'suppress_filters' => 0,
					'fields'           => 'ids',
				);
				$language_courses = get_posts( $args );
				if ( ! in_array( $transaction->course_id, $language_courses ) ) {
					continue;
				}
			}
			if ( ! isset( $revenue_by_course[ $transaction->course_id ] ) ) {
				$course_title = get_the_title( $transaction->course_id );
				if ( 'sfwd-courses' != get_post_type( $transaction->course_id ) ) {
					$course_title = sprintf( '%s: %s', \LearnDash_Custom_Label::get_label( 'group' ), get_the_title( $transaction->course_id ) );
				}
				$revenue_by_course[ $transaction->course_id ] = array(
					'title' => $course_title,
					'total' => number_format( (float) $transaction->amount, 2, '.', '' ),
				);
			} else {
				$course_revenue_total                                  = $revenue_by_course[ $transaction->course_id ]['total'] + $transaction->amount;
				$revenue_by_course[ $transaction->course_id ]['total'] = number_format( (float) $course_revenue_total, 2, '.', '' );
			}
		}

		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			$wc_course_revenue = self::get_course_revenues_from_woo( $from_date, $to_date );
			foreach ( $wc_course_revenue as $course_id => $amount ) {
				if ( ! empty( $excluded_courses ) && in_array( $course_id, $excluded_courses ) ) {
					continue;
				}
				if ( ! isset( $revenue_by_course[ $course_id ] ) ) {
					if ( isset( $_GET[ 'wpml_lang' ] ) ) {
						$args             = array(
							'post_type'      => 'sfwd-courses',
							'posts_per_page' => -1,
							'suppress_filters' => 0,
							'fields'           => 'ids',
						);
						$language_courses = get_posts( $args );
						if ( ! in_array( $course_id, $language_courses ) ) {
							continue;
						}
					}
					$course_title = get_the_title( $course_id );
					if ( 'sfwd-courses' != get_post_type( $course_id ) ) {
						$course_title = sprintf( '%s: %s', \LearnDash_Custom_Label::get_label( 'group' ), get_the_title( $course_id ) );
					}
					$revenue_by_course[ $course_id ] = array(
						'title' => $course_title,
						'total' => number_format( (float) $amount, 2, '.', '' ),
					);
				} else {
					$course_revenue_total                     = $revenue_by_course[ $course_id ]['total'] + $amount;
					$revenue_by_course[ $course_id ]['total'] = number_format( (float) $course_revenue_total, 2, '.', '' );
				}
			}
		}

		return $revenue_by_course;
	}


	/**
	 * This method when called fetches the woocommerce orders & calculates the revenue generated per course & group
	 * during the specified duration & returns an array [course/group id]=>total revenue from woo orders
	 *
	 * @param string $from_date
	 * @param string $to_date
	 * @return array $revenue_data
	 */
	public static function get_course_revenues_from_woo( $from_date, $to_date ) {
		$revenue_data     = array();
		$orders           = self::get_woocommerce_orders( $from_date, $to_date );
		$excluded_courses = get_option( 'exclude_courses', false );
		$excluded_users   = get_option( 'exclude_users', false );
		$excluded_ur      = get_option( 'exclude_ur', false );
		foreach ( $orders as $order ) {
			if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				if ( in_array( $order->get_user_id(), $excluded_users ) ) {
					continue;
				}
			}
			if ( ! empty( $excluded_ur ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args = array(
					'number'   => -1,
					'fields'   => array(
						'ID',
					),
					'role__in' => $excluded_ur,
				);

				$users    = get_users( $args );
				$user_ids = wp_list_pluck( $users, 'ID' );
				if ( in_array( $order->get_user_id(), $user_ids ) ) {
					continue;
				}
			}
			// Loop through order items.
			foreach ( $order->get_items() as $item ) {
				// Get an instance of the WC_Product Object from the WC_Order_Item_Product.
				$product = $item->get_product();
				if ( ! empty( $product ) ) {
					// Check if product type is course.
					if ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) || $product->is_type( 'course' ) ) {
						$linked_courses   = $product->get_meta( '_related_course' );
						$linked_ld_groups = $product->get_meta( '_related_group' );
						$revenue_earned   = $item->get_subtotal();
						if ( ! empty( $linked_courses ) ) {
							$revenue_per_course = $revenue_earned / count( $linked_courses );
							foreach ( $linked_courses as $course_id ) {
								if ( ! empty( $excluded_courses ) && in_array( $course_id, $excluded_courses ) ) {
									continue;
								}
								if ( ! isset( $revenue_data[ $course_id ] ) ) {
									$revenue_data[ $course_id ] = $revenue_per_course;
								} else {
									$revenue_data[ $course_id ] = $revenue_data[ $course_id ] + $revenue_per_course;
								}
							}
						}

						if ( ! empty( $linked_ld_groups ) ) {
							$revenue_per_group = $revenue_earned / count( $linked_ld_groups );
							foreach ( $linked_ld_groups as $group_id ) {
								// $group_courses = learndash_group_enrolled_courses( $group_id, true );
								if ( ! isset( $revenue_data[ $group_id ] ) ) {
									$revenue_data[ $group_id ] = $revenue_per_group;
								} else {
									$revenue_data[ $group_id ] = $revenue_data[ $group_id ] + $revenue_per_group;
								}
							}
						}
					}
				}
			}
		}
		return $revenue_data;
	}

	/**
	 * This method when called with the arguements 'from date' & 'to date' returns the woocommerce orders
	 * placed between those dates & has the status 'wc-completed', you can get the orders with different statuses
	 * by passing the third optional parameter to the method.
	 *
	 * @param string $from_date
	 * @param string $to_date
	 * @param string $status default: wc-completed
	 * @return array array of WC-Order objects
	 */
	public static function get_woocommerce_orders( $from_date, $to_date, $status = 'wc-completed' ) {
		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'status'       => array( $status ),
				'date_created' => $from_date . '...' . $to_date,
			)
		);

		if ( empty( $orders ) ) {
			return array();
		}

		return $orders;
	}

	/**
	 * This method is used to fetch all LearnDash transactions amount in a course-wise bases or a total based on the type passed.
	 *
	 * @param  string $start_date Start Date.
	 * @param  string $end_date   End Date.
	 * @return integer/array      Array of objects of transaction data.
	 */
	public function get_learndash_transactions_total( $start_date, $end_date ) {
		$total_revenue_earned = 0;
		$ld_transactions      = self::get_ld_transactions( $start_date, $end_date );
		$excluded_courses     = get_option( 'exclude_courses', false );
		foreach ( $ld_transactions as $transaction ) {
			if ( ! empty( $excluded_courses ) && in_array( $transaction->course_id, $excluded_courses ) ) {
				continue;
			}
			if ( isset( $_GET[ 'wpml_lang' ] ) ) {
				$args             = array(
					'post_type'      => 'sfwd-courses',
					'posts_per_page' => -1,
					'suppress_filters' => 0,
					'fields'           => 'ids',
				);
				$language_courses = get_posts( $args );
				if ( ! in_array( $transaction->course_id, $language_courses ) ) {
					continue;
				}
			}
			$total_revenue_earned = $total_revenue_earned + $transaction->amount;
		}

		return $total_revenue_earned;
	}


	/**
	 * This function queries the database for learndash course purchase transactions
	 * from the queried start date to the end date. returns empty array when the start date is undefined.
	 * when end date is undefined/empty, sets it to the current date.
	 *
	 * @param string $start_date date timestamp from when the transaction data is required.
	 * @param string $end_date date timestamp to which the transaction data is required.
	 * @return array empty array when no transactions are found, array of object containing transaction details otherwise.
	 */
	public static function get_ld_transactions( $start_date, $end_date ) {
		// Fetch transactions.
		$transaction_details = array();
		if ( empty( $start_date ) ) {
			return array();
		}

		if ( empty( $end_date ) ) {
			$end_date = strtotime( 'now' );
		}

		$transactions_query_args = array(
			'post_type'      => 'sfwd-transactions',
			'posts_per_page' => '-1',
			'date_query'     => array(
				array(
					'after'     => $start_date,
					'before'    => $end_date,
					'inclusive' => true,
				),
			),
		);

		$excluded_users = get_option( 'exclude_users', false );
		if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$transactions_query_args['author__not_in'] = $excluded_users;
		}

		$excluded_ur = get_option( 'exclude_ur', false );
		if ( ! empty( $excluded_ur ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$args = array(
				'number'   => -1,
				'fields'   => array(
					'ID',
				),
				'role__in' => $excluded_ur,
			);

			$users    = get_users( $args );
			$user_ids = wp_list_pluck( $users, 'ID' );
			if ( isset( $transactions_query_args['author__not_in'] ) ) {
				$transactions_query_args['author__not_in'] = array_merge( $transactions_query_args['author__not_in'], $user_ids );
			} else {
				$transactions_query_args['author__not_in'] = $user_ids;
			}
		}

		$transactions = new WP_Query( $transactions_query_args );

		if ( ( isset( $transactions->posts ) ) && ( ! empty( $transactions->posts ) ) ) {
			$all_transactions = $transactions->posts;
			foreach ( $all_transactions as $transaction ) {
				$transaction_details[] = self::get_ld_transaction_details( $transaction );
			}
		}
		return $transaction_details;
	}


	/**
	 * This function when called & passed with the LD Transaction object
	 * goes through the transaction details and prepare a standard object with the following transaction details.
	 * last_updated, amount, payment_processor, course_id, group_id
	 *
	 * @since v1.0.7
	 * @param object $transaction LD transaction object
	 * @return object $transaction_data object with the following details [last_updated, amount, payment_processor, course_id, group_id]
	 */
	public static function get_ld_transaction_details( $transaction ) {
		$transaction_data = array();
		$amount           = null;
		if ( empty( $transaction ) ) {
			return $amount;
		}
		$transaction_id = $transaction->ID;

		if ( class_exists( 'Learndash_Transaction_Model' ) ) {
			$transaction_model = \Learndash_Transaction_Model::find( $transaction->ID );
			$payment_processor = $transaction_model->get_gateway_name();
		} else {
			$payment_processor = get_post_meta( $transaction_id, 'ipn_track_id', true );
			if ( ! empty( $payment_processor ) ) {
				$payment_processor = 'paypal_ipn';
			}
			$payment_processor = empty( $payment_processor ) ? get_post_meta( $transaction_id, 'ld_payment_processor', true ) : $payment_processor;
			$payment_processor = empty( $payment_processor ) ? get_post_meta( $transaction_id, 'action', true ) : $payment_processor;
			$payment_processor = empty( $payment_processor ) ? get_post_meta( $payment_processor, 'learndash-checkout', true ) : $payment_processor;
		}
		

		$meta_key = null;
		$format   = null;

		switch ( $payment_processor ) {
			case 'stripe':
				$stripe_price_type = 'stripe_' . get_post_meta( $transaction_id, 'stripe_price_type', true );
				$meta_key          = 'stripe_price';
				$format            = 'money_stripe';
				break;
			case 'paypal':
			case 'paypal_ipn':
				$meta_key = 'mc_gross';
				$format   = 'money';
				break;
			case '2co':
				$meta_key = 'total';
				$format   = 'money';
				break;
			default:
				// code...
				break;
		}
		if ( ! isset( $meta_key ) || empty( $meta_key ) ) {
			return null;
		}

		$amount = get_post_meta( $transaction_id, $meta_key, true );
		if ( ! empty( $amount ) ) {
			switch ( $format ) {
				case 'money_stripe':
					if ( ! in_array( $stripe_price_type, array( 'stripe_paynow', 'stripe_subscribe' ), true ) ) {
						$amount = $amount / 100;
					}
					// no break.
				case 'money':
					$amount = floatval( number_format( $amount, 2, '.', '' ) );
					break;
				default:
					break;
			}
		} else {
			$amount = 0;
		}

		$group_id  = null;
		$course_id = get_post_meta( $transaction->ID, 'post_id', true );
		$course_id = empty( $course_id ) ? get_post_meta( $transaction->ID, 'course_id', true ) : $course_id;
		if ( empty( $course_id ) ) {
			$course_id = get_post_meta( $transaction->ID, 'group_id', true );
			$group_id  = $course_id;
		}

		$transaction_data                    = new stdClass();
		$transaction_data->last_updated      = $transaction->post_modified_gmt;
		$transaction_data->amount            = $amount;
		$transaction_data->payment_processor = $payment_processor;
		$transaction_data->course_id         = $course_id;
		$transaction_data->group_id          = $group_id;
		return $transaction_data;
	}

	/**
	 * This method is used to fetch all WooCommerce orders amount for completed orders.
	 *
	 * @param  string $start_date Start Date.
	 * @param  string $end_date   End Date.
	 * @return integer/array      Total amount.
	 */
	public function get_woocommerce_transactions_total( $start_date, $end_date ) {
		$total_revenue_earned = 0;
		// Get completed orders in the date range.
		$orders = self::get_woocommerce_orders( $start_date, $end_date );
		if ( empty( $orders ) ) {
			return $total_revenue_earned;
		}
		$excluded_courses = get_option( 'exclude_courses', false );
		$excluded_users   = get_option( 'exclude_users', false );
		$excluded_ur      = get_option( 'exclude_ur', false );
		foreach ( $orders as $order ) {
			if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				if ( in_array( $order->get_user_id(), $excluded_users ) ) {
					continue;
				}
			}
			if ( ! empty( $excluded_ur ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args = array(
					'number'   => -1,
					'fields'   => array(
						'ID',
					),
					'role__in' => $excluded_ur,
				);

				$users    = get_users( $args );
				$user_ids = wp_list_pluck( $users, 'ID' );
				if ( in_array( $order->get_user_id(), $user_ids ) ) {
					continue;
				}
			}
			// Loop through order items.
			foreach ( $order->get_items() as $item ) {
				// Get an instance of the WC_Product Object from the WC_Order_Item_Product.
				$product = $item->get_product();
				if ( ! empty( $product ) ) {
					// Check if product type is course.
					$linked_courses = $product->get_meta( '_related_course' );
					$order_amount   = $item->get_subtotal();
					if ( $product->is_type( 'course' ) ) {
						if ( ! empty( $excluded_courses ) && ! empty( $linked_courses ) ) {
							$excluded_courses_for_transaction = array_intersect( $excluded_courses, $linked_courses );
							if ( isset( $_GET[ 'wpml_lang' ] ) ) {
								$args             = array(
									'post_type'      => 'sfwd-courses',
									'posts_per_page' => -1,
									'suppress_filters' => 0,
									'fields'           => 'ids',
								);
								$language_courses = get_posts( $args );
								$excluded_courses_for_transaction = array_intersect( $excluded_courses_for_transaction, $language_courses );
							}
							$product_course_count             = count( $linked_courses );
							$excluded_course_count            = count( $excluded_courses_for_transaction );
							$total_revenue_earned             = $total_revenue_earned + ( ( $product_course_count - $excluded_course_count ) / $product_course_count ) * $order_amount;
						} else {
							if ( isset( $_GET[ 'wpml_lang' ] ) ) {
								$args             = array(
									'post_type'      => 'sfwd-courses',
									'posts_per_page' => -1,
									'suppress_filters' => 0,
									'fields'           => 'ids',
								);
								$language_courses = get_posts( $args );
								if ( empty( array_intersect( $linked_courses, $language_courses ) ) ) {
									continue;
								}
							}
							$total_revenue_earned = $total_revenue_earned + $order_amount;
						}
					} elseif ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) {
						$linked_ld_groups = $product->get_meta( '_related_group' );
						if ( ! empty( $linked_ld_groups ) ) {
							if ( isset( $_GET[ 'wpml_lang' ] ) ) {
								$args             = array(
									'post_type'      => 'groups',
									'posts_per_page' => -1,
									'suppress_filters' => 0,
									'fields'           => 'ids',
								);
								$language_courses = get_posts( $args );
								if ( empty( array_intersect( $linked_ld_groups, $language_courses ) ) ) {
									continue;
								}
							}
							$total_revenue_earned = $total_revenue_earned + $item->get_subtotal();
							continue;
						}
						if ( ! empty( $excluded_courses ) && ! empty( $linked_courses ) ) {
							$excluded_courses_for_transaction = array_intersect( $excluded_courses, $linked_courses );
							if ( isset( $_GET[ 'wpml_lang' ] ) ) {
								$args             = array(
									'post_type'      => 'sfwd-courses',
									'posts_per_page' => -1,
									'suppress_filters' => 0,
									'fields'           => 'ids',
								);
								$language_courses = get_posts( $args );
								$excluded_courses_for_transaction = array_intersect( $excluded_courses_for_transaction, $language_courses );
							}
							$product_course_count             = count( $linked_courses );
							$excluded_course_count            = count( $excluded_courses_for_transaction );
							$total_revenue_earned             = $total_revenue_earned + ( ( $product_course_count - $excluded_course_count ) / $product_course_count ) * $order_amount;
						} elseif ( ! empty( $linked_courses ) ) {
							if ( isset( $_GET[ 'wpml_lang' ] ) ) {
								$args             = array(
									'post_type'      => 'sfwd-courses',
									'posts_per_page' => -1,
									'suppress_filters' => 0,
									'fields'           => 'ids',
								);
								$language_courses = get_posts( $args );
								if ( empty( array_intersect( $linked_courses, $language_courses ) ) ) {
									continue;
								}
							}
							$total_revenue_earned = $total_revenue_earned + $order_amount;
						}
					}
				}
			}
		}
		return $total_revenue_earned;
	}

	/**
	 * This method is an API callback that return the total revenue earned on a course-to-course bases.
	 * This will not fetch data from WooCommerce because in WooCommerce 1 product can be linked to multiple courses.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_coursewise_revenue() {
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$request_data     = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data     = self::get_request_params( $request_data );
		$duration         = self::get_duration_data( $request_data['start_date'], $request_data['end_date'], 'Y-m-d' );
		$revenue          = self::get_ld_coursewise_revenue( $duration['start_date'], $duration['end_date'] );
		$previous_revenue = self::get_ld_coursewise_revenue( $duration['prev_start_date'], $duration['prev_end_date'] );

		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_revenue_from_course',$request_data);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}


		$total_revenue    = array_sum( array_column( $revenue, 'total' ) );
		$total_courses    = count( $revenue );
		$average_revenue  = 0;

		if ( ! empty( $total_courses ) ) {
			$average_revenue = $total_revenue / $total_courses;
		}

		$currency = function_exists( 'learndash_get_currency_symbol' ) ? learndash_get_currency_symbol() : '';
		$currency = empty( $currency ) && function_exists( 'learndash_30_get_currency_symbol' ) ? @learndash_30_get_currency_symbol() : $currency;
		$currency = empty( $currency ) ? '$' : $currency;
		// Logic for missing courses in the current and previous durations.
		foreach ( $previous_revenue as $course_id => $course_data ) {
			if ( isset( $revenue[ $course_id ] ) ) {
				$revenue[ $course_id ] = $revenue[ $course_id ];
			}
		}
		foreach ( $revenue as $course_id => $v ) {
			if ( isset( $previous_revenue[ $course_id ] ) ) {
				$previous_revenue[ $course_id ] = $previous_revenue[ $course_id ];
			}
		}

		ksort( $revenue );
		ksort( $previous_revenue );

		$response = array(
			'requestData'           => self::get_values_for_request_params( $request_data ),
			'currentRevenueEarned'  => $revenue,
			'previousRevenueEarned' => $previous_revenue,
			'averageRevenue'        => number_format_i18n( $average_revenue, 2 ),
			'totalRevenue'          => number_format_i18n( $total_revenue, 2 ),
			'totalCourses'          => $total_courses,
			'currency'              => $currency,
		);
		$response = apply_filters('wrld_api_response_to_excel',$response);
		return new WP_REST_Response(
			$response,
			200
		);
	}


	/**
	 * Returns the users registered between the specified date range.
	 *
	 * @return array array of objects returned by WP_User_Query
	 */
	public static function get_users_registered_between( $start_date, $end_date ) {
		global $wpdb;
		$query          = $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . $wpdb->users . ' as users JOIN ' . $wpdb->usermeta . " as usermeta ON users.ID=usermeta.user_id WHERE users.user_registered>=%s AND users.user_registered<=%s AND usermeta.meta_key LIKE '%_capabilities' AND usermeta.meta_value LIKE '%s'", $start_date, $end_date, '%subscriber%' );// phpcs:ignore
		// AND usermeta.meta_key='wp_capabilities' AND usermeta.meta_value NOT LIKE %s '%administrator%'
		// $start_date = strtotime( $start_date );
		// $end_date = strtotime( $end_date );
		// $query = $wpdb->prepare( 'SELECT COUNT( DISTINCT activity.user_id ) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'user_activity' ) ) . ' as activity WHERE activity.activity_type=%s AND activity.activity_started BETWEEN %s AND %s', 'access', $start_date, $end_date );
		$user_role_access   = WRLD_Course_Progress_Info::get_current_user_role_access();
		$accessible_courses = WRLD_Course_Progress_Info::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'learners_registered_between' );
		$accessible_users   = WRLD_Course_Progress_Info::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'learners_registered_between' );
		$excluded_users     = get_option( 'exclude_users', false );
		if ( ! empty( $accessible_users ) && -1 != $accessible_users ) {
			$accessible_users = implode( ',', $accessible_users );
			$query           .= ' AND users.ID IN (' . $accessible_users . ')';
		}
		if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users   = implode( ',', $excluded_users );
			$query           .= ' AND users.ID NOT IN (' . $excluded_users . ')';
		}
		// if ( ! empty( $accessible_courses ) ) {
		// $accessible_courses = implode( ',', $accessible_courses );
		// $query         .= ' AND activity.course_id IN (' . $accessible_courses . ')';
		// }
		$users = $wpdb->get_var( $query );// phpcs:ignore
		return $users;
	}

	/**
	 * Returns the courses created between the specified date range.
	 *
	 * @return array array of objects returned by WP_Query
	 */
	public static function get_courses_created_between( $start_date, $end_date ) {
		$query = array(
			'post_type'   => 'sfwd-courses',
			'date_query'  => array(
				array(
					'column'    => 'post_date',
					'after'     => $start_date,
					'before'    => $end_date,
					'inclusive' => true,
				),
			),
			'post_status' => 'publish',
		);

		$user_role_access   = WRLD_Course_Progress_Info::get_current_user_role_access();
		$accessible_courses = WRLD_Course_Progress_Info::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'courses_registered_between' );
		if ( ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) && empty( $accessible_courses ) ) ) {
			$query['post__in'] = array( -1 );
		} else {
			if ( -1 != $accessible_courses ) {
				$query['post__in'] = $accessible_courses;
			}
		}
		return new WP_Query( $query );
	}
}
