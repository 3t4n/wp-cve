<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Controller
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Controller' ) ) {
	class WFFN_REST_Controller extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * @var string
		 */
		public static $sql_datetime_format = 'Y-m-d H:i:s';

		/**
		 * Endpoint namespace.
		 *
		 * @var string
		 */
		protected $namespace = 'woofunnels-analytics';

		/**
		 * Route base.
		 *
		 * @var string
		 */
		protected $rest_base = '';


		public function date_format( $interval ) {
			switch ( $interval ) {
				case 'hour':
					$format = '%Y-%m-%d %H';
					break;
				case 'day':
					$format = '%Y-%m-%d';
					break;
				case 'month':
					$format = '%Y-%m';
					break;
				case 'quarter':
					$format = 'QUARTER';
					break;
				case 'year':
					$format = 'YEAR';
					break;
				default:
					$format = '%x-%v';
					break;
			}

			return apply_filters( 'WFFN_api_date_format_' . $interval, $format, $interval );
		}

		public function get_interval_format_query( $interval, $table_col ) {

			$interval_type = $this->date_format( $interval );
			$avg           = ( $interval === 'day' ) ? 1 : 0;
			if ( 'YEAR' === $interval_type ) {
				$interval = ", YEAR(" . $table_col . ") ";
				$avg      = 365;
			} elseif ( 'QUARTER' === $interval_type ) {
				$interval = ", CONCAT(YEAR(" . $table_col . "), '-0', QUARTER(" . $table_col . ")) ";
				$avg      = 90;
			} elseif ( '%x-%v' === $interval_type ) {
				$first_day_of_week = absint( get_option( 'start_of_week' ) );

				if ( 1 === $first_day_of_week ) {
					$interval = ", DATE_FORMAT(" . $table_col . ", '" . $interval_type . "')";
				} else {
					$interval = ", CONCAT(YEAR(" . $table_col . "), '-', LPAD( FLOOR( ( DAYOFYEAR(" . $table_col . ") + ( ( DATE_FORMAT(MAKEDATE(YEAR(" . $table_col . "),1), '%w') - $first_day_of_week + 7 ) % 7 ) - 1 ) / 7  ) + 1 , 2, '0'))";
				}
				$avg = 7;
			} else {
				$interval = ", DATE_FORMAT( " . $table_col . ", '" . $interval_type . "')";
			}

			$interval       .= " as time_interval ";
			$interval_group = " `time_interval` ";

			return array(
				'interval_query' => $interval,
				'interval_group' => $interval_group,
				'interval_avg'   => $avg,

			);

		}


		public function get_total_intervals( $start_date, $end_date, $interval, $table, $table_col ) {
			global $wpdb;

			$get_interval   = $this->get_interval_format_query( $interval, $table_col );
			$interval_query = $get_interval['interval_query'];
			$interval_group = $get_interval['interval_group'];

			$query = "SELECT MIN(" . $table_col . ") AS start_date, MAX(" . $table_col . ") as end_date, " . ltrim( $interval_query, ',' ) . "  FROM `" . $table . "` WHERE 1=1 AND $table_col >= '" . $start_date . "' AND `" . $table_col . "` < '" . $end_date . "' GROUP BY " . $interval_group . " ASC";

			$intervals = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			return ( is_array( $intervals ) && count( $intervals ) > 0 ) ? $intervals : array();
		}

		public function maybe_interval_exists( $all_data, $interval_key, $current_interval ) {

			if ( is_array( $all_data ) && count( $all_data ) > 0 ) {
				foreach ( $all_data as $data ) {
					if ( isset( $data[ $interval_key ] ) && $current_interval === $data[ $interval_key ] ) {
						return array( $data );
					}

				}
			}

			return false;
		}

		public static function string_to_date( $datetime_string ) {
			$datetime = new DateTime( $datetime_string, new \DateTimeZone( wp_timezone_string() ) );

			return $datetime;
		}

		public static function convert_local_datetime_to_gmt( $datetime_string ) {
			$datetime = new DateTime( $datetime_string, new \DateTimeZone( wp_timezone_string() ) );
			$datetime->setTimezone( new DateTimeZone( 'GMT' ) );

			return $datetime;
		}

		public static function default_date( $diff_time = 0 ) {
			$now      = time();
			$datetime = new DateTime();
			if ( $diff_time > 0 ) {
				$week_back = $now - $diff_time;
				$datetime->setTimestamp( $week_back );
			}
			$datetime->setTimezone( new DateTimeZone( wp_timezone_string() ) );

			return $datetime;
		}

		public function missing_intervals( $sql_intervals, $all_intervals ) {

			if ( count( $sql_intervals ) === count( $all_intervals ) ) {
				return $sql_intervals;
			}

			if ( count( $sql_intervals ) === 0 ) {
				return $all_intervals;
			}

			$array = array_merge( $sql_intervals, $all_intervals );

			$temp_array = [];
			$key        = 'time_interval';

			foreach ( $array as &$v ) {
				if ( ! isset( $temp_array[ $v[ $key ] ] ) ) {
					$temp_array[ $v[ $key ] ] =& $v;
				}
			}

			usort( $temp_array, function ( $a, $b ) {
				$datetime1 = strtotime( $a['time_interval'] );
				$datetime2 = strtotime( $b['time_interval'] );

				return $datetime1 - $datetime2;
			} );


			$array = array_values( $temp_array );

			return $array;


		}

		public function intervals_between( $start, $end, $interval, $overall = false ) {

			switch ( $interval ) {
				case 'hour':
					$interval_type = 'PT60M';
					$format        = 'Y-m-d H';
					break;
				case 'day':
					$interval_type = "P1D";
					$format        = 'Y-m-d';
					break;
				case 'month':
					$interval_type = "P1M";
					$format        = 'Y-m';
					break;
				case 'quarter':
					$interval_type = "P3M";
					$format        = 'Y-m';
					break;
				case 'year':
					$interval_type = "P1Y";
					$format        = 'Y';
					break;
				default:
					$interval_type = "P1W";
					$format        = 'W';
					break;
			}


			$result = array();

			// Variable that store the date interval
			// of period 1 day
			$period = new DateInterval( $interval_type );

			$realEnd = new DateTime( $end );

			$realEnd->add( $period );

			$period   = new DatePeriod( new DateTime( $start ), $period, $realEnd );
			$date_end = date_create( $end );
			$count    = iterator_count( $period );

			if ( 'week' !== $interval ) {
				$count = $count - 1;
			}

			if ( true === $overall ) {
				$count = $count + 1;
			}

			foreach ( $period as $date ) {
				if ( $count >= 1 ) {
					$new_interval = array();

					if ( 'day' === $interval || 'hour' === $interval ) {
						$new_interval['start_date'] = $date->format( self::$sql_datetime_format );
						$new_interval['end_date']   = $date->format( 'Y-m-d 23:59:59' );
					} else {
						$new_interval['start_date'] = self::maybe_first_date( $date, $format );
						$new_interval['end_date']   = ( $count > 1 ) ? self::maybe_last_date( $date, $format ) : $date_end->format( self::$sql_datetime_format );
					}
					if ( 'week' === $interval ) {
						$year                          = $date->format( 'Y' );
						$new_interval['time_interval'] = $year . '-' . $date->format( $format );
					} else if ( 'quarter' === $interval ) {
						$quarter = $this->get_quarter_by_month( $date->format( 'm' ) );
						$year    = $date->format( 'Y' );

						$new_interval['time_interval'] = $year . '-' . $quarter;
					} else {

						$new_interval['time_interval'] = $date->format( $format );
					}

					$result[] = $new_interval;
				}
				$count --;

			}

			return $result;
		}

		public static function maybe_first_date( $newDate, $period ) {
			switch ( $period ) {
				case 'Y':
					$newDate->modify( 'first day of january ' . $newDate->format( 'Y' ) );
					break;
				case 'quarter':
					$month = $newDate->format( 'n' );
					if ( $month < 4 ) {
						$newDate->modify( 'first day of january ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 3 && $month < 7 ) {
						$newDate->modify( 'first day of april ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 6 && $month < 10 ) {
						$newDate->modify( 'first day of july ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 9 ) {
						$newDate->modify( 'first day of october ' . $newDate->format( 'Y' ) );
					}
					break;
				case 'Y-m':
					$newDate->modify( 'first day of this month' );
					break;
				case 'W':
					$newDate->modify( ( $newDate->format( 'w' ) === '0' ) ? self::first_day_of_week() . ' last week' : self::first_day_of_week() . ' this week' );
					break;
			}

			return $newDate->format( self::$sql_datetime_format );

		}

		public static function maybe_last_date( $newDate, $period ) {
			switch ( $period ) {
				case 'Y':
					$newDate->modify( 'last day of december ' . $newDate->format( 'Y' ) );
					break;
				case 'quarter':
					$month = $newDate->format( 'n' );

					if ( $month < 4 ) {
						$newDate->modify( 'last day of march ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 3 && $month < 7 ) {
						$newDate->modify( 'last day of june ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 6 && $month < 10 ) {
						$newDate->modify( 'last day of september ' . $newDate->format( 'Y' ) );
					} elseif ( $month > 9 ) {
						$newDate->modify( 'last day of december ' . $newDate->format( 'Y' ) );
					}
					break;
				case 'Y-m':
					$newDate->modify( 'last day of this month' );
					break;
				case 'W':
					$newDate->modify( ( $newDate->format( 'w' ) === '0' ) ? 'now' : self::last_day_of_week() . ' this week' );
					break;
			}

			return $newDate->format( 'Y-m-d 23:59:59 ' );

		}

		public static function first_day_of_week() {
			$days_of_week = array(
				1 => 'monday',
				2 => 'tuesday',
				3 => 'wednesday',
				4 => 'thursday',
				5 => 'friday',
				6 => 'saturday',
				7 => 'sunday',
			);

			$day_number = absint( get_option( 'start_of_week' ) );

			return $days_of_week[ $day_number ];
		}

		public static function last_day_of_week() {
			$days_of_week = array(
				1 => 'sunday',
				2 => 'saturday',
				3 => 'friday',
				4 => 'thursday',
				5 => 'wednesday',
				6 => 'tuesday',
				7 => 'monday',
			);

			$day_number = absint( get_option( 'start_of_week' ) );

			return $days_of_week[ $day_number ];
		}

		/**
		 * Get percentage of a given number against a total
		 *
		 * @param float|int $total total number of occurrences
		 * @param float|int $number the number to get percentage against
		 *
		 * @return float|int
		 */
		public function get_percentage( $total, $number ) {
			if ( $total > 0 ) {
				return round( $number / ( $total / 100 ), 2 );
			} else {
				return 0;
			}
		}

		public static function get_quarter_by_month( $month ) {
			$month = absint( $month );
			switch ( $month ) {
				case $month <= 3:
					$quarter = '01';
					break;
				case $month <= 6:
					$quarter = '02';
					break;
				case $month <= 9:
					$quarter = '03';
					break;
				default:
					$quarter = '04';
					break;
			}

			return $quarter;
		}

		/**
		 * get base url of post
		 *
		 * @param $post_data
		 *
		 * @return string
		 */
		public function get_base_url( $post_data ) {

			$data = [
				'wffn_landing'   => [ "landing_page_base", 'sp' ],
				'wfacp_checkout' => [ "checkout_page_base", 'checkouts' ],
				'wffn_optin'     => [ "optin_page_base", 'op' ],
				"wffn_oty"       => [ "optin_ty_page_base", 'op-confirmed' ],
				'wfocu_offer'    => [ "wfocu_page_base", 'offer' ],
				'wffn_ty'        => [ "ty_page_base", 'order-confirmed' ]
			];

			$base_url = '';
			if ( isset( $data[ $post_data->post_type ] ) ) {
				$base_data = $data[ $post_data->post_type ];
				$base_url  = $this->base_url( $base_data[0], $base_data[1] );
			}

			return $base_url . $post_data->post_name;
		}

		/**
		 * @param $base_name
		 * @param $default
		 *
		 * @return string
		 */
		public static function base_url( $base_name, $default ) {
			$slug        = self::get_url_rewrite_slug( $base_name, $default );
			$permalink   = get_option( 'permalink_structure' );
			$rewritecode = array(
				'%year%',
				'%monthnum%',
				'%day%',
				'%hour%',
				'%minute%',
				'%second%',
				'%postname%',
				'%post_id%',
				'%category%',
				'%author%',
				'%pagename%',
				'/'
			);
			$permalink   = str_replace( $rewritecode, '', $permalink );

			return home_url( "/{$permalink}/{$slug}/" );
		}


		public static function get_url_rewrite_slug( $base_name, $default ) {
			$rewrite_slug = BWF_Admin_General_Settings::get_instance()->get_option( $base_name );

			return false === $rewrite_slug ? $default : $rewrite_slug;
		}

		// Format Ruleset Select.
		public function format_rules_select( $array, $switch_nvp = 1 ) {
			$nvp = array();
			if ( ! empty( $array ) ) {
				foreach ( $array as $key => $val ) {
					$field               = ( 1 === $switch_nvp ) ? wffn_rest_api_helpers()->array_to_nvp( array_flip( $val ), 'label', 'value', 'value', 'key' ) : $val;
					$field[0]['nameKey'] = $key;
					$nvp                 = array_merge( $nvp, $field );
				}

				foreach ( $nvp as $key => $pair ) {
					if ( ! isset( $pair['nameKey'] ) ) {
						$nvp[ $key ]['nameKey'] = null;
					}
				}
			}

			return array_values( $nvp );
		}

		public function get_category_based_rules() {
			return [
				'order_term',
				'order_payment_gateway',
				'order_category',
				'cart_item_type',
				'cart_coupons',
				'cart_shipping_method',
				'customer_user',
				'customer_role',
				'customer_purchased_cat',
				'order_shipping_method',
				'order_shipping_country',
				'order_billing_country',
				'customer_purchased_products',
				'cart_shipping_country',
				'cart_billing_country',
				'wfacp_page',
				'day',
				'order_term',
				'order_item_type',
				'order_coupons',
				'order_subs',
			];
		}

		public function strip_group_rule_keys( $array ) {

			$rules  = array();
			$groups = array();

			if ( ! empty( $array ) && is_array( $array ) ) {

				foreach ( $array as $gkey => $rule ) {

					if ( ! strpos( $gkey, 'groupId' ) ) {
						foreach ( $rule as $rkey => $_rule ) {

							$_rule['gkey'] = $gkey;
							$_rule['rkey'] = $rkey;

							if ( ! empty( $_rule['operator'] ) ) {
								$_rule['operator'] = wp_specialchars_decode( $_rule['operator'] );
							}
							if ( ! empty( $_rule['rule_type'] ) && in_array( $_rule['rule_type'], [ 'cart_coupons', 'order_coupons' ], true ) ) {
								if ( ! empty( $_rule['condition'] ) ) {
									$_rule['condition'] = array_map( function ( $k ) {
										return sanitize_title( $k );
									}, $_rule['condition'] );
								}
							}

							if ( ! empty( $_rule['rule_type'] ) && in_array( $_rule['rule_type'], [ 'order_coupon_exist' ], true ) ) {
								$_rule['condition'] = 'parent_order';
							}


							if ( ! empty( $_rule['rule_type'] ) && in_array( $_rule['rule_type'], $this->get_category_based_rules(), true ) ) {
								if ( ! empty( $_rule['condition'] ) ) {
									$condition                        = $_rule['condition'];
									$_rule['condition']               = [];
									$_rule['condition']['categories'] = $condition;
								} else {
									$_rule['condition']['categories'] = [];
								}
							}


							if ( ! empty( $_rule['rule_type'] ) && 'time' === $_rule['rule_type'] ) {
								$_rule['condition'] = ! empty( $_rule['condition'] ) ? str_replace( " ", "", $_rule['condition'] ) : '';
							}

							$rules[] = $_rule;

						}
						$groups[] = $rules;
					}
					$rules = array();
				}

			}

			return $groups;

		}

		public function rectify_posted_rules( $posted_data ) {

			foreach ( $posted_data['basic'] as $rkey => $rules ) {
				foreach ( $rules as $key => $_rule ) {
					if ( ! empty( $_rule['rule_type'] ) && 'order_item' === $_rule['rule_type'] ) {
						$condition = $_rule['condition'];
						$rule      = $_rule;

						if ( is_array( $_rule['condition']['products'] ) ) {
							$condition['products'] = ! empty( $_rule['condition']['products']['ID'] ) ? $_rule['condition']['products']['ID'] : 0;
							$rule['condition']     = $condition;
						}

						$posted_data['basic'][ $rkey ][ $key ] = $rule;
					} elseif ( ! empty( $_rule['rule_type'] ) && in_array( $_rule['rule_type'], $this->get_category_based_rules(), true ) ) {
						$condition = $_rule['condition'];
						$rule      = $_rule;
						unset( $rule['condition']['categories'] );
						$rule['condition']                     = $condition['categories'];
						$posted_data['basic'][ $rkey ][ $key ] = $rule;
					}
				}
			}

			return $posted_data;
		}

		// Function to render input field based on rule_tpye.
		public function render_input_fields( $data ) {

			$fields = array();

			if ( ! empty( $data ) && is_array( $data ) ) {

				if ( isset( $data['condition_input_type'] ) ) {

					switch ( $data['condition_input_type'] ) {
						case 'Html_Always' :
							break;
						case 'Cart_Category_Select' :
						case 'Cart_Tag_Select' :
							$fields = $this->input_cart_category_select( $data['value_args'] );
							break;
						case 'Cart_Product_Select' :
							$fields = $this->input_cart_product_select( $data['value_args'] );
							break;
						case 'Chosen_Select' :
							$fields = $this->input_chosen_select( $data['value_args'] );
							break;
						case 'User_Select' :
							$fields = $this->input_user_select( $data['value_args'] );
							break;
						case 'Text' :
							$fields = $this->input_text( $data['value_args'] );
							break;
						case 'Coupon_Select' :
							$fields = $this->input_coupon_select( $data['value_args'] );
							break;
						case 'Coupon_Exist' :
							$fields = $this->input_coupon_exist( $data['value_args'] );
							break;
						case 'Coupon_Text_Match' :
							$fields = $this->input_coupon_text_match( $data['value_args'] );
							break;
						case 'Item_Text_Match' :
							$fields = $this->input_item_text_match( $data['value_args'] );
							break;
						case 'Select' :
							$fields = $this->input_select( $data['value_args'] );
							break;
						case 'Product_Select' :
							$fields = $this->input_product_select( $data['value_args'] );
							break;
						case 'Date' :
							$fields = $this->input_date( $data['value_args'] );
							break;
						case 'Time' :
							$fields = $this->input_time( $data['value_args'] );
							break;
						case 'Customer_Rule_Unavailable' :
							$fields = [
								[
									'type'  => 'custom-html',
									'key'   => 'custom_html',
									'label' => $this->rule_unavailable(),
								],
							];
							break;

					}

				}

			}

			return $fields;

		}

		// Fields for cart category select option.
		public function input_cart_category_select( $field = array() ) {

			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'class'         => 'ajax_chosen_select_products'
			);

			$field   = array_merge( $defaults, $field );
			$choices = $field['choices'];

			$fields = [
				[
					'type'        => 'chosen-select',
					'key'         => $field['name'] . '[category_select]',
					'placeholder' => __( 'Search ...', 'funnel-builder-powerpack' ),
					'label'       => __( 'Categories', 'funnel-builder-powerpack' ),
					'options'     => ! empty( $choices ) ? wffn_rest_api_helpers()->array_to_nvp( array_flip( $choices ), 'name', 'id' ) : [],
					'optionValue' => ! empty( $choices ) ? array_keys( $choices ) : [],
				],
			];

			return $fields;

		}

		// Fields for cart product select option.
		public function input_cart_product_select( $field = array() ) {

			$products = array();
			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'class'         => 'ajax_chosen_select_products'
			);

			$field = array_merge( $defaults, $field );

			$product_defaults = array(
				'category'         => 0,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => array(),
				'exclude'          => array(),
				'post_type'        => 'product',
				'suppress_filters' => true,
				'fields'           => 'ids',
			);
			$current          = isset( $field['choices'] ) ? array( $field['choices'] ) : array();
			$product_ids      = ! empty( $current ) ? array_map( 'absint', array_unique( array_merge( get_posts( $product_defaults ), $current[0] ) ) ) : get_posts( $product_defaults );

			if ( empty( $current ) ) {
				$current = $product_ids;
			}
			if ( $product_ids ) {
				foreach ( $product_ids as $product_id ) {
					$product                 = wc_get_product( $product_id );
					$product_name            = strip_tags( BWF_WC_Compatibility::woocommerce_get_formatted_product_name( $product ) );
					$products[ $product_id ] = $product_name;
				}
			}

			$fields = [
				[
					'type'        => 'text',
					'key'         => $field['name'] . '[qty]',
					'label'       => __( 'Quantity', 'funnel-builder-powerpack' ),
					'placeholder' => '',
					'values'      => 1,
				],
				[
					'type'        => 'multiSelect',
					'key'         => $field['name'] . '[products]',
					'apiEndPoint' => '/funnels/products/search',
					'label'       => __( 'Products', 'funnel-builder-powerpack' ),
					'placeholder' => __( 'Select Product', 'funnel-builder-powerpack' ),
					'options'     => wffn_rest_api_helpers()->array_to_nvp( array_flip( $products ), 'name', 'id' ),
				],
			];

			return $fields;

		}

		// Fields for cart chosen select option.
		public function input_chosen_select( $field = array() ) {

			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => array(),
				'class'         => '',
				'name'          => 'chosen_select'
			);

			$field   = array_merge( $defaults, $field );
			$choices = $field['choices'];
			$choices = array_map( 'ucwords', $choices );
			$fields  = [
				[
					'type'        => 'chosen-select',
					'key'         => $field['name'] . '[chosen_select]',
					'placeholder' => __( 'Select Option', 'funnel-builder-powerpack' ),
					'label'       => '',
					'options'     => ! empty( $choices ) ? wffn_rest_api_helpers()->array_to_nvp( ( $choices ), 'id', 'name' ) : [],
					'optionValue' => ! empty( $choices ) ? array_values( ( $choices ) ) : [],
				],
			];

			return $fields;

		}

		// Fields for cart text option.
		public function input_text( $field = array() ) {

			$defaults = array(
				'default_value' => '',
				'class'         => '',
				'placeholder'   => ''
			);

			$field = array_merge( $defaults, $field );

			$fields = [
				[
					'type'   => 'text',
					'key'    => $field['name'] . '[text]',
					'label'  => '',
					'values' => [],
				],
			];

			return $fields;

		}

		// Fields for coupon select option.
		public function input_coupon_select( $field = array() ) {

			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => array(),
				'class'         => '',
				'name'          => 'chosen_select'
			);

			$field   = array_merge( $defaults, $field );
			$choices = $field['choices'];

			$fields = [
				[
					'type'        => 'chosen-select',
					'key'         => $field['name'] . '[coupon_select]',
					'label'       => __( 'Coupons', 'funnel-builder-powerpack' ),
					'placeholder' => __( 'Select Coupons..', 'funnel-builder-powerpack' ),
					'options'     => is_array( $choices ) ? wffn_rest_api_helpers()->array_to_nvp( ( $choices ), "id", "name" ) : $choices,
					'optionValue' => ! empty( $choices ) ? array_keys( ( $choices ) ) : [],
				],
			];

			return $fields;

		}

		// Fields for coupon select option.
		public function input_coupon_exist( $field = array() ) {

			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array( 'parent_order' => __( 'In parent order', 'funnel-builder-powerpack' ) ),
				'default_value' => 'no',
				'class'         => 'chosen_coupon_exist'
			);

			$field   = array_merge( $defaults, $field );
			$choices = $field['choices'];

			$fields = [
				[
					'type'        => 'select',
					'key'         => $field['name'] . '[coupon_exist]',
					'label'       => __( 'Coupon Exist', 'funnel-builder-powerpack' ),
					'placeholder' => __( 'Select Option', 'funnel-builder-powerpack' ),
					'options'     => ! empty( $choices ) && is_array( $choices ) ? wffn_rest_api_helpers()->array_to_nvp( array_flip( $choices ), 'label', 'value', 'value', 'key' ) : []
				],
			];

			return $fields;

		}

		// Fields for Coupon Text match text option.
		public function input_coupon_text_match( $field = array() ) {

			$defaults = array(
				'id'            => 'coupon_text_match',
				'multiple'      => 0,
				'allow_null'    => 0,
				'default_value' => '',
				'class'         => 'coupon_text_match',
				'placeholder'   => __( 'Enter the search key...', 'funnel-builder-powerpack' )
			);

			$field = array_merge( $defaults, $field );

			$fields = [
				[
					'type'        => 'text',
					'key'         => $field['name'] . '[coupon_text_match]',
					'apiEndPoint' => '/funnels/products/search',
					'placeholder' => __( 'Enter the search key..', 'funnel-builder-powerpack' ),
					'label'       => __( 'Select Coupon', 'funnel-builder-powerpack' ),
				],
			];

			return $fields;

		}

		// Fields for Item Text match text option.
		public function input_item_text_match( $field = array() ) {

			$defaults = array(
				'id'            => 'item_text_match',
				'multiple'      => 0,
				'allow_null'    => 0,
				'default_value' => '',
				'class'         => 'item_text_match',
				'placeholder'   => __( 'Enter the search key...', 'funnel-builder-powerpack' )
			);

			$field = array_merge( $defaults, $field );

			$fields = [
				[
					'type'        => 'text',
					'key'         => $field['name'] . '[item_text_match]',
					'placeholder' => __( 'Enter the search key..', 'funnel-builder-powerpack' ),
					'label'       => __( 'Select Coupon', 'funnel-builder-powerpack' ),
				],
			];

			return $fields;

		}

		// Fields for coupon select option.
		public function input_select( $field = array() ) {

			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array( 'parent_order' => __( 'In parent order', 'funnel-builder-powerpack' ) ),
				'default_value' => 'no',
				'class'         => 'chosen_coupon_exist'
			);

			$field   = array_merge( $defaults, $field );
			$choices = $field['choices'];

			$fields = [
				[
					'type'        => 'select',
					'key'         => $field['name'] . '[coupon_exist]',
					'placeholder' => __( 'Select Option', 'funnel-builder-powerpack' ),
					'label'       => '',
					'options'     => ! empty( $choices ) && is_array( $choices ) ? wffn_rest_api_helpers()->array_to_nvp( array_flip( $choices ), 'label', 'value', 'value', 'key' ) : [],
				],
			];

			return $fields;

		}

		// Fields for User select option.
		public function input_user_select( $field = array() ) {

			$this->defaults = array(
				'multiple'      => 1,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'class'         => 'ajax_chosen_select_users'
			);

			$users = get_users( array( 'number' => 5, 'fields' => array( 'ID' ) ) );

			$user_ids = array();
			$_user    = array();

			foreach ( $users as $user ) {
				$_user['name'] = get_user_by( 'id', $user->ID )->display_name;
				$_user['id']   = ( string ) $user->ID;
				$user_ids[]    = $_user;
			}

			if ( isset( $field['choices'] ) && ! empty( $field['choices'] ) ) {
				foreach ( $field['choices'] as $user ) {
					$_user['name'] = get_user_by( 'id', (int) $user )->display_name;
					$_user['id']   = ( string ) $user;
					$user_ids[]    = $_user;
				}
			}
			$choices = wffn_rest_api_helpers()->array_change_key( $user_ids, 'label', 'name' );


			$fields = [
				[
					'type'        => 'chosen-select',
					'key'         => $field['name'] . '[user_select]',
					'placeholder' => __( 'Select Option', 'funnel-builder-powerpack' ),
					'label'       => '',
					'options'     => ! empty( $choices ) ? $choices : [],
					'optionValue' => ! empty( $choices ) ? array_values( $choices ) : [],
				],
			];

			return $fields;

		}

		// Fields for product select option.
		public function input_product_select( $field = array() ) {
			$products = array();
			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'class'         => 'ajax_chosen_select_products'
			);

			$field = array_merge( $defaults, $field );

			$product_defaults = array(
				'category'         => 0,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => array(),
				'exclude'          => array(),
				'post_type'        => 'product',
				'suppress_filters' => true,
				'fields'           => 'ids',
			);
			$current          = isset( $field['choices'] ) ? array( $field['choices'] ) : array();
			$product_ids      = ! empty( $current ) ? array_map( 'absint', array_unique( array_merge( get_posts( $product_defaults ), $current[0] ) ) ) : get_posts( $product_defaults );

			if ( empty( $current ) ) {
				$current = $product_ids;
			}
			if ( $product_ids ) {
				foreach ( $product_ids as $product_id ) {
					$product                 = wc_get_product( $product_id );
					$product_name            = strip_tags( BWF_WC_Compatibility::woocommerce_get_formatted_product_name( $product ) );
					$products[ $product_id ] = $product_name;
				}
			}

			$fields = [
				[
					'type'        => 'multiSelect',
					'key'         => $field['name'] . '[products]',
					'apiEndPoint' => '/funnels/products/search',
					'placeholder' => __( 'Search for a product..', 'funnel-builder-powerpack' ),
					'label'       => __( 'Select Product', 'funnel-builder-powerpack' ),
					'options'     => wffn_rest_api_helpers()->array_to_nvp( $products, 'id', 'name' ),
				],
			];

			return $fields;

		}

		// Fields for date option.
		public function input_date( $field = array() ) {

			$defaults = array(
				'default_value' => '',
				'class'         => '',
				'placeholder'   => ''
			);

			$field  = array_merge( $defaults, $field );
			$fields = [
				[
					'type'        => 'text',
					'key'         => $field['name'] . '[date]',
					'label'       => __( 'Date', 'funnel-builder-powerpack' ),
					'placeholder' => '',
					'class'       => $field['class'],
					'values'      => [],
				],
			];

			return $fields;

		}

		// Fields for time option.
		public function input_time( $field = array() ) {


			$defaults = array(
				'default_value' => '',
				'class'         => '',
				'placeholder'   => ''
			);

			$field = array_merge( $defaults, $field );

			$fields = [
				[
					'type'        => 'text',
					'key'         => $field['name'] . '[time]',
					'label'       => __( 'Time', 'funnel-builder-powerpack' ),
					'placeholder' => __( 'For eg: 23:59', 'funnel-builder-powerpack' ),
					'values'      => [],
				],
			];

			return $fields;

		}

		public function get_formatted_variations( $variations, $offer_variations ) {
			$formatted_variations = [];

			if ( ! empty( $variations ) ) {

				$variation = array();

				foreach ( $variations as $id ) {
					$attribute        = array();
					$variable_product = wc_get_product( $id );
					$attributes       = $variable_product->get_attributes();

					if ( ! empty( $attributes ) ) {
						foreach ( $attributes as $key => $val ) {
							$attribute[] = $key . " : " . $val;
						}
					}


					$regular_price = ! empty( $variable_product->get_regular_price() ) ? $variable_product->get_regular_price() : 0;
					$sale_price    = ! empty( $variable_product->get_sale_price() ) ? $variable_product->get_sale_price() : 0;

					$product_availibility = wffn_rest_api_helpers()->get_availability_price_text( $variable_product->get_id() );
					$product_stock        = $product_availibility['text'];
					$stock_status         = ( $variable_product->is_in_stock() ) ? true : false;

					$variation['id']                   = $variable_product->get_id();
					$variation['regular_price']        = $regular_price;
					$variation['sale_price']           = $sale_price;
					$variation['is_on_sale']           = $variable_product->is_on_sale();
					$variation['product_stock']        = $product_stock;
					$variation['product_stock_status'] = $stock_status;
					$variation['discount_amount']      = ! empty( $offer_variations[ $variation['id'] ]['discount_amount'] ) ? $offer_variations[ $variation['id'] ]['discount_amount'] : 0;
					$variation['attributes']           = ! empty( $attribute ) ? $attribute : '';
					$formatted_variations[]            = $variation;
				}
			}

			return $formatted_variations;
		}

		public function strip_product_data( $product ) {
			if ( ! empty( $product['key'] ) ) {
				$product['id'] = $product['key'];
				unset( $product['key'] );
				unset( $product['currency_symbol'] );
				unset( $product['product_image'] );
				unset( $product['product_stock_status'] );
				unset( $product['product_stock'] );

			}

			return $product;
		}

		/** iterate over all the saved rules and club all the product ids
		 * the result of this method will be used to populate nice names of already saved values
		 *
		 * @param array $groups
		 *
		 * @return array
		 */
		public function get_product_from_conditions( $groups ) {
			$products = [];
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group ) {
					foreach ( $group as $rule ) {

						if ( ! empty( $rule['condition'] ) && isset( $rule['condition']['products'] ) && ! empty( $rule['condition']['products'] ) ) {
							if ( is_array( $rule['condition']['products'] ) ) {
								$products = wp_parse_args( $products, $rule['condition']['products'] );
							} else {
								$products[] = $rule['condition']['products'];
							}
						}
						if ( 'customer_purchased_products' === $rule['rule_type'] ) {
							$products = wp_parse_args( $products, $rule['condition'] );

						}

					}
				}
			}

			return $products;
		}

		public function get_coupons_from_conditions( $groups ) {
			$users = [];
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group ) {
					foreach ( $group as $rule ) {
						if ( 'cart_coupons' === $rule['rule_type'] || 'order_coupons' === $rule['rule_type'] ) {
							$users = wp_parse_args( $users, $rule['condition'] );

						}
					}
				}
			}

			return $users;
		}

		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

		public function rule_unavailable() {
			$state = absint( WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->get_upgrade_state() );
			if ( 3 === $state ) {
				$text = __( 'Indexing of orders is underway. This setting will work once the process completes.', 'funnel-builder-powerpack' );
			} else {
				$text = __( 'This rule needs indexing of past orders. Go to <a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=woofunnels&tab=tools' ) ) . '">Tools > Index Orders</a> and click \'Start\' to index orders', 'funnel-builder-powerpack' );
			}

			return $text;
		}

		/**
		 * @param $startDate
		 * @param $endDate
		 *
		 * @return string
		 * @throws Exception
		 */
		function get_two_date_interval( $startDate, $endDate ) {
			// Convert the date strings to DateTime objects
			$startDateTime = new DateTime( $startDate );
			$endDateTime   = new DateTime( $endDate );

			// Calculate the difference in seconds between the two dates
			$intervalInSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();

			// Determine an appropriate interval based on the time difference
			if ( $intervalInSeconds <= 3888000 ) { // Less than or equal to 45 days
				return 'day'; // Show data on a daily basis
			} elseif ( $intervalInSeconds <= 31622400 ) { // Less than or equal to 365 days
				return 'month'; // Show data on a monthly basis
			} else {
				return 'year'; // Show data on a yearly basis for longer date ranges
			}
		}


	}
}