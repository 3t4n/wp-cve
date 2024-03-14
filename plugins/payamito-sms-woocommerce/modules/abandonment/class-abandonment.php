<?php

namespace Payamito\Woocommerce\Modules\Abandoned;

use Payamito_Woocommerce;

/**
 * Cart Abandonment
 *
 * @package Payamito
 */

/**
 * Cart abandonment tracking class.
 */
class Abandonment
{
	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance()
	{
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *  Constructor function that initializes required actions and hooks.
	 */
	public function __construct()
	{
		DB::create();

		$option = get_option( "payamito_wc_abandonment" );
		if ( ! $option ) {
			return;
		}
		$this->define_cart_abandonment_constants();

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		if ( $option['active'] == true && ! isset( $_COOKIE['pwc_abandonment_ca_skip_track_data'] ) ) {
			// Add script to track the cart abandonment.
			add_action( 'woocommerce_after_checkout_form', [ $this, 'cart_abandonment_tracking_script' ] );

			// Store user details from the current checkout page.
			add_action( 'wp_ajax_abandonment_data', [ $this, 'save_cart_abandonment_data' ] );
			add_action( 'wp_ajax_nopriv_abandonment_data', [ $this, 'save_cart_abandonment_data' ] );

			// Delete the stored cart abandonment data once order gets created.
			add_action( 'woocommerce_new_order', [ $this, 'delete_cart_abandonment_data' ] );
			add_action( 'woocommerce_thankyou', [ $this, 'delete_cart_abandonment_data' ] );

			add_action( "payamito_wc_abandonment_send", [ $this, "send_callback" ], 10, 4 );

			add_action( 'woocommerce_order_status_changed', [
				$this,
				'pwc_abandonment_ca_update_order_status',
			], 999, 3 );

			// Adding filter to restore the data if recreating abandonment order.
			add_filter( 'wp', [ $this, 'restore_cart_abandonment_data' ], 10 );
			add_filter( 'wp', [ $this, 'unsubscribe_cart_abandonment_emails' ], 10 );
			add_filter( 'cron_schedules', [ $this, 'add_cron' ] );

			if ( time() > wp_next_scheduled( 'payamito_wc_abandoned' ) ) {
				wp_schedule_event( time(), 'every_one_minutes', 'payamito_wc_abandoned' );
			}
			add_action( 'payamito_wc_abandoned', [ $this, 'abandonment' ] );
		}
	}

	public function enqueue_scripts()
	{
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'payamito' ) {
			wp_enqueue_script( 'cart-abandonment', PAYAMITO_WC_URL . '/modules/abandonment/admin/assets/js/abandonment-admin.js', [ 'jquery' ] );
		}
	}

	/**
	 * Get cart abandonment tracking cut off time.
	 *
	 * @param boolean $in_seconds get cutoff time in seconds if true.
	 *
	 * @return bool
	 */
	public function get_cart_abandonment_tracking_cut_off_time( $in_seconds = false )
	{
		$cart_abandoned_time = apply_filters( 'payamito_wc_abandonment_cut_off_time', WCF_DEFAULT_CUT_OFF_TIME );

		return $in_seconds ? $cart_abandoned_time * MINUTE_IN_SECONDS : $cart_abandoned_time;
	}

	/**
	 * Update the Order status.
	 *
	 * @param integer $order_id         order id.
	 * @param string  $old_order_status old order status.
	 * @param string  $new_order_status new order status.
	 */
	public function pwc_abandonment_ca_update_order_status( $order_id, $old_order_status, $new_order_status )
	{
		$acceptable_order_statuses = [ 'completed', 'processing', 'failed' ];

		$exclude_on_hold_order = apply_filters( 'woo_ca_exclude_on_hold_order_from_tracking', false );

		if ( $exclude_on_hold_order ) {
			array_push( $acceptable_order_statuses, 'on-hold' );
		}

		if ( ( PWCAB_CART_FAILED_ORDER === $new_order_status ) ) {
			return;
		}

		if ( $order_id && in_array( $new_order_status, $acceptable_order_statuses, true ) ) {
			$order = wc_get_order( $order_id );

			$order_phone   = $order->billing_phone;
			$captured_data = ( PWCAB_CART_FAILED_ORDER === $new_order_status ) ? $this->get_tracked_data_without_status( $order_phone ) : $this->get_captured_data_by_phone( $order_phone );

			if ( $captured_data && is_object( $captured_data ) ) {
				$capture_status = $captured_data->order_status;
				global $wpdb;
				$cart_abandonment_table = DB::table_name( "cart_abandonment" );

				if ( ( PWCAB_CART_NORMAL_ORDER === $capture_status ) ) {
					$wpdb->delete( $cart_abandonment_table, [ 'session_id' => sanitize_key( $captured_data->session_id ) ] );
				}

				if ( ( PWCAB_CART_ABANDONED_ORDER === $capture_status || PWCAB_CART_LOST_ORDER === $capture_status ) ) {
					$this->skip_future_sms_when_order_is_completed( sanitize_key( $captured_data->session_id ) );
					$note = __( 'This order was abandoned & subsequently recovered.', 'payamito-woocommerce ' );
					$order->add_order_note( $note );
					$order->save();
					if ( WC()->session ) {
						WC()->session->__unset( 'pwc_abandonment_session_id' );
					}
				}
			}
		}
	}

	/**
	 * Create custom schedule.
	 *
	 * @param array $schedules schedules.
	 *
	 * @return mixed
	 */
	public function add_cron( $schedules )
	{
		/**
		 * Add filter to change the cron interval time to uodate order status.
		 */
		$cron_time = apply_filters( 'woo_ca_update_order_cron_interval', 1 );

		$schedules['every_one_minutes'] = [
			'interval' => $cron_time * MINUTE_IN_SECONDS,
			'display'  => __( 'Every One Minutes', 'payamito-woocommerce ' ),
		];

		return $schedules;
	}

	/**
	 *  Unsubscribe the user from the mailing list.
	 */
	public function unsubscribe_cart_abandonment_emails()
	{
		$unsubscribe              = filter_input( INPUT_GET, 'unsubscribe', FILTER_VALIDATE_BOOLEAN );
		$pwc_abandonment_ac_token = filter_input( INPUT_GET, 'pwc_abandonment_ac_token', FILTER_SANITIZE_STRING );
		if ( $unsubscribe && $this->is_valid_token( $pwc_abandonment_ac_token ) ) {
			$token_data = $this->pwc_abandonment_decode_token( $pwc_abandonment_ac_token );
			if ( isset( $token_data['pwc_abandonment_session_id'] ) ) {
				$session_id = $token_data['pwc_abandonment_session_id'];
				global $wpdb;
				$cart_abandonment_table = DB::table_name( "cart_abandonment" );;
				$wpdb->update( $cart_abandonment_table, [ 'unsubscribed' => true ], [ 'session_id' => $session_id ] );
				wp_die( esc_html__( 'You have successfully unsubscribed from our phone list.', 'payamito-woocommerce ' ), esc_html__( 'Unsubscribed', 'payamito-woocommerce ' ) );
			}
		}
	}

	/**
	 *  Initialise all the constants
	 */
	public function define_cart_abandonment_constants()
	{
		if ( ! defined( 'PWCAB_CART_ABANDONED_ORDER' ) ) {
			define( 'PWCAB_CART_ABANDONED_ORDER', 'abandoned' );
		}
		if ( ! defined( 'PWCAB_CART_COMPLETED_ORDER' ) ) {
			define( 'PWCAB_CART_COMPLETED_ORDER', 'completed' );
		}
		if ( ! defined( 'PWCAB_CART_LOST_ORDER' ) ) {
			define( 'PWCAB_CART_LOST_ORDER', 'lost' );
		}
		if ( ! defined( 'PWCAB_CART_NORMAL_ORDER' ) ) {
			define( 'PWCAB_CART_NORMAL_ORDER', 'normal' );
		}
		if ( ! defined( 'PWCAB_CART_FAILED_ORDER' ) ) {
			define( 'PWCAB_CART_FAILED_ORDER', 'failed' );
		}
		if ( ! defined( 'PWCAB_CA_DATETIME_FORMAT' ) ) {
			define( 'PWCAB_CA_DATETIME_FORMAT', 'Y-m-d H:i:s' );
		}
		if ( ! defined( 'PWCAB_CA_DATETIME_FORMAT' ) ) {
			define( 'PWCAB_CA_CA_COUPON_DESCRIPTION', 'This coupon is for abandoned cart templates.' );
		}
	}

	/**
	 * Restore cart abandonemnt data on checkout page.
	 *
	 * @param array $fields checkout fields values.
	 *
	 * @return array field values
	 */
	public function restore_cart_abandonment_data( $fields = [] )
	{
		global $woocommerce;
		$result = [];
		// Restore only of user is not logged in.
		$pwc_abandonment_ac_token = filter_input( INPUT_GET, 'pwc_abandonment_ac_token', FILTER_SANITIZE_STRING );
		if ( $this->is_valid_token( $pwc_abandonment_ac_token ) ) {
			// Check if `pwc_abandonment_restore_token` exists to restore cart data.
			$token_data = $this->pwc_abandonment_decode_token( $pwc_abandonment_ac_token );
			if ( is_array( $token_data ) && isset( $token_data['pwc_abandonment_session_id'] ) ) {
				$result = $this->get_checkout_details( $token_data['pwc_abandonment_session_id'] );
				if ( isset( $result ) && PWCAB_CART_ABANDONED_ORDER === $result->order_status || PWCAB_CART_LOST_ORDER === $result->order_status ) {
					WC()->session->set( 'pwc_abandonment_session_id', $token_data['pwc_abandonment_session_id'] );
				}
			}

			if ( $result ) {
				$cart_content = unserialize( $result->cart_contents );

				if ( $cart_content ) {
					$woocommerce->cart->empty_cart();
					wc_clear_notices();
					foreach ( $cart_content as $cart_item ) {
						$cart_item_data = [];
						$id             = $cart_item['product_id'];
						$qty            = $cart_item['quantity'];

						// Skip bundled products when added main product.
						if ( isset( $cart_item['bundled_by'] ) ) {
							continue;
						}

						if ( isset( $cart_item['ppom'] ) ) {
							$cart_item_data['ppom'] = $cart_item['ppom'];
						}

						if ( isset( $cart_item['bump'] ) ) {
							$cart_item_data['bump'] = $cart_item['bump'];
						}

						if ( isset( $cart_item['custom_price'] ) ) {
							$cart_item_data['custom_price'] = $cart_item['custom_price'];
						}

						$woocommerce->cart->add_to_cart( $id, $qty, $cart_item['variation_id'], [], $cart_item_data );
					}

					if ( isset( $token_data['pwc_abandonment_coupon_code'] ) && ! $woocommerce->cart->applied_coupons ) {
						$woocommerce->cart->add_discount( $token_data['pwc_abandonment_coupon_code'] );
					}
				}
				$other_fields = unserialize( $result->other_fields );

				$parts = explode( ',', $other_fields['pwc_abandonment_location'] );
				if ( count( $parts ) > 1 ) {
					$country = $parts[0];
					$city    = trim( $parts[1] );
				} else {
					$country = $parts[0];
					$city    = '';
				}

				foreach ( $other_fields as $key => $value ) {
					$key           = str_replace( 'pwc_abandonment_', '', $key );
					$_POST[ $key ] = sanitize_text_field( $value );
				}
				$_POST['billing_first_name'] = sanitize_text_field( $other_fields['pwc_abandonment_first_name'] );
				$_POST['billing_last_name']  = sanitize_text_field( $other_fields['pwc_abandonment_last_name'] );
				$_POST['billing_phone']      = sanitize_text_field( $other_fields['pwc_abandonment_phone_number'] );
				$_POST['billing_email']      = sanitize_email( $result->email );
				$_POST['billing_city']       = sanitize_text_field( $city );
				$_POST['billing_country']    = sanitize_text_field( $country );
			}
		}

		return $fields;
	}

	/**
	 * Load cart abandonemnt tracking script.
	 *
	 * @return void
	 */
	public function cart_abandonment_tracking_script()
	{
		$pwc_abandonment_ca_ignore_users = get_option( 'pwc_abandonment_ca_ignore_users' );
		$current_user                    = wp_get_current_user();
		$roles                           = $current_user->roles;
		$role                            = array_shift( $roles );

		if ( ! empty( $pwc_abandonment_ca_ignore_users ) ) {
			foreach ( $pwc_abandonment_ca_ignore_users as $user ) {
				$user = strtolower( $user );
				$role = preg_replace( '/_/', ' ', $role );
				if ( $role === $user ) {
					return;
				}
			}
		}

		global $post;
		wp_enqueue_script( 'payamito-wc-cart-abandonment', PAYAMITO_WC_Module_URL . '/abandonment/assets/js/cart-abandonment.js', [ 'jquery' ], PAYAMITO_WC_ABANDONED, true );

		$vars = [
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'_nonce'             => wp_create_nonce( 'abandonment_data' ),
			'_gdpr_nonce'        => wp_create_nonce( 'cartflows_skip_cart_tracking_gdpr' ),
			'_post_id'           => get_the_ID(),
			'_show_gdpr_message' => "",
			'enable_ca_tracking' => true,
		];

		wp_localize_script( 'payamito-wc-cart-abandonment', 'PayamitoWcVars', $vars );
	}

	/**
	 * Validate the token before use.
	 *
	 * @param string $token token form the url.
	 *
	 * @return bool
	 */
	public function is_valid_token( $token )
	{
		$is_valid   = false;
		$token_data = $this->pwc_abandonment_decode_token( $token );
		if ( is_array( $token_data ) && array_key_exists( 'pwc_abandonment_session_id', $token_data ) ) {
			$result = $this->get_checkout_details( $token_data['pwc_abandonment_session_id'] );
			if ( isset( $result ) ) {
				$is_valid = true;
			}
		}

		return $is_valid;
	}

	public function abandonment_sended( $session_id, $template_id, $queue_id )
	{
		global $wpdb;
		$table_name = DB::table_name( "history" );
		$sql        = $wpdb->prepare( "SELECT `id`,`queue` FROM {$table_name} WHERE `session_id`=%s AND `template_id`=%s ", $session_id, $template_id );
		$results    = $wpdb->get_row( $sql, 'ARRAY_A' );
		if ( is_array( $results ) && count( $results ) != 0 ) {
			foreach ( $results as $key => $result ) {
				if ( $key != 'id' ) {
					$queue = unserialize( $result );
					if ( is_array( $queue ) ) {
						foreach ( $queue as $q ) {
							if ( $q['id'] == $queue_id ) {
								return 1;
							}
						}
					}
				}
			}

			return $results;
		}

		return - 1;
	}

	public function abandonment_completed( $session_id, $template_id, $arg )
	{
		global $wpdb;
		$table_name = DB::table_name( "history" );
		if ( empty( $session_id ) || empty( $template_id ) ) {
			return true;
		}

		if ( ! is_array( $arg ) ) {
			return true;
		}
		$ids = [];
		foreach ( $arg as $item ) {
			if ( isset( $item['id'] ) ) {
				array_push( $ids, $item['id'] );
			}
		}
		$sql     = $wpdb->prepare( "SELECT `queue` FROM `{$table_name}` WHERE `session_id`=%s AND `template_id`=%s ", $session_id, $template_id );
		$results = $wpdb->get_row( $sql, 'ARRAY_A' );
		if ( is_null( $results ) || count( $results ) == 0 ) {
			return false;
		}
		$queue = array_column( maybe_unserialize( $results['queue'] ), 'id' );
		$def   = array_diff( $ids, $queue );
		if ( count( $def ) == 0 ) {
			return true;
		}

		return false;
	}

	public function abandonment_history_update( $sended, $arg, $queue )
	{
		$sended_queue = unserialize( $sended['queue'] );
		$queue        = unserialize( $queue );
		$ids          = [];
		foreach ( $sended_queue as $key => $send ) {
			array_push( $ids, $send['id'] );
		}

		if ( ! in_array( $arg['pattern_id'], $ids ) ) {
			global $wpdb;
			array_push( $sended_queue, $queue[0] );
			$wpdb->update( DB::table_name( "history" ), [ 'queue' => serialize( $sended_queue ) ], [ 'id' => $sended['id'] ] );
		}
	}

	public function abandonment_history_insert( $template_id, $session_id, $queue )
	{
		global $wpdb;
		$wpdb->insert( DB::table_name( "history" ),

			[
				'template_id'    => $template_id,
				'session_id'     => $session_id,
				'scheduled_time' => date_i18n( "Y-m-d H:i:s" ),
				'queue'          => $queue,
			] );
	}

	public function abandonment()
	{
		$options = get_option( "payamito_wc_abandonment" );
		if ( $options['active'] != true ) {
			return;
		}
		$Template = Template::get_instance();

		$templates = $Template->prepare( $Template->get_templates() );

		if ( count( $templates ) == 0 ) {
			return;
		}

		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );

		/**
		 * Delete abandoned cart orders if empty.
		 */
		$this->delete_empty_abandoned_order();

		$abandonments = Helper::prepare_abandonments( DB::select( $cart_abandonment_table ) );

		if ( count( $abandonments ) == 0 ) {
			return;
		}

		foreach ( $abandonments as $abandonment ) {
			if ( isset( $abandonment['session_id'] ) ) {
				$current_session_id = $abandonment['session_id'];

				foreach ( $templates as $template ) {
					if ( $template['method'] === 'pattern' ) {
						foreach ( $template['patterns'] as $pattern ) {
							if ( $this->abandonment_completed( $current_session_id, $template['id'], $template['patterns'] ) ) {
								continue;
							}
							$guest_user = $template['guest_user'] == '1' ? true : false;
							if ( $guest_user ) {
								$phone = $abandonment['other_fields'][ trim( $template['field_id'] ) ];

								if ( is_null( $phone ) || empty( trim( $phone ) ) ) {
									continue;
								}
							} else {
								$phone = get_user_meta( $abandonment['user_id'], $template['meta_key'], true );
								if ( empty( $phone ) || $phone == false ) {
									continue;
								}
							}
							if ( $this->is_time_send( $current_session_id, $template['id'], $abandonment['time'], $pattern['frequency'] ) ) {
								global $wpdb;
								$queue = [];
								array_push( $queue, [
									'id'        => $pattern['id'],
									'send_time' => date_i18n( "Y-m-d H:i:s", current_time( 'timestamp' ), true ),
								] );
								$queue = serialize( $queue );

								$sended      = $this->abandonment_sended( $current_session_id, $template['id'], $pattern['id'] );
								$coupon_data = [];

								if ( isset( $template['coupon_data'] ) ) {
									$coupon_data = $template['coupon_data'];
								}
								if ( $sended === - 1 ) {
									$send = $this->prepare_pattern_send( $phone, $abandonment, $pattern, $coupon_data );
									if ( $send === true ) {
										$this->abandonment_history_insert( $template['id'], $current_session_id, $queue );
									}
								}
								if ( is_array( $sended ) ) {
									$send = $this->prepare_pattern_send( $phone, $abandonment, $pattern, $coupon_data );
									if ( $send === true ) {
										$this->abandonment_history_update( $sended, [
											'pattern_id' => $pattern['id'],
											'phone'      => $phone,
										], $queue );
									}
								}
							}
						}
					}
					if ( $template['method'] === 'messege' ) {
						foreach ( $template['messeges'] as $messege ) {
							if ( $this->abandonment_completed( $current_session_id, $messege['id'], $messege['messege'] ) ) {
								continue;
							}
							if ( $this->is_time_send( $current_session_id, $template['id'], $abandonment['time'], $messege['frequency'] ) ) {
								$this->prepare_text_send( $phone, $abandonment, $messege );
							}
						}
					}
				}
			}
		}
	}

	public function is_time_send( $session_id, $template_id, $abandonment_time, $frequency )
	{
		$min = $this->is_first_send_template( $session_id, $template_id );
		if ( $min !== false ) {
			$abandonment_time = $min;
		} else {
			$abandonment_time = $abandonment_time;
		}
		$current_time = date_i18n( "Y-m-d H:i:s" );
		$send_time    = Helper::prepare_send_time( $abandonment_time, $frequency );

		if ( $current_time > $send_time ) {
			return true;
		}

		return false;
	}

	public function is_first_send_template( $session_id, $template_id )
	{
		global $wpdb;
		$history_table = DB::table_name( "history" );

		$query  = $wpdb->prepare( "SELECT `queue` FROM  `{$history_table}` WHERE  `session_id` = %s AND `template_id`=%s ", $session_id, $template_id );
		$result = $wpdb->get_row( $query, 'ARRAY_A' );
		if ( is_null( $result ) || count( $result ) == 0 || is_null( $result ) ) {
			return false;
		}
		$time_send = array_column( unserialize( $result['queue'] ), 'send_time' );
		$min_time  = min( $time_send );

		return $min_time;
	}

	public function prepare_pattern_send( $phone, $abandonment, $pattern, $options )
	{
		$body_sms = Helper::prepare_body_sms( $pattern, $abandonment, $options );
		$send     = $this->pattern_send( $phone, $body_sms, $pattern['pattern_id'] );

		if ( $send['result'] === true ) {
			return true;
		}
		$coupen_id = Helper::$coupen_id;

		if ( ! is_null( $coupen_id ) ) {
			$this->delete_coupen( $coupen_id );
		}

		return false;
	}

	public function delete_coupen( $id )
	{
		wp_delete_post( $id, true );
	}

	public function prepare_text_send( $phone, $abandonment, $messege )
	{
		#coming soon
	}

	/**
	 * Save cart abandonment tracking and schedule new event.
	 *
	 * @since 1.0.0
	 */
	public function save_cart_abandonment_data()
	{
		check_ajax_referer( 'abandonment_data', 'security' );

		$post_data = Helper::sanitize_post_data();

		$user_phone = Helper::sanitize_phone_number( $post_data['billing_phone'] );

		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );

		// Verify if email is already exists.
		$session_id               = WC()->session->get( 'pwc_abandonment_session_id' );
		$session_checkout_details = null;

		if ( isset( $session_id ) ) {
			$session_checkout_details = $this->get_checkout_details( $session_id );
		} else {
			$session_checkout_details = $this->get_checkout_details_by_phone( $user_phone );
			if ( $session_checkout_details ) {
				$session_id = $session_checkout_details->session_id;
				WC()->session->set( 'pwc_abandonment_session_id', $session_id );
			} else {
				$session_id = md5( uniqid( wp_rand(), true ) );
			}
		}

		$checkout_details = $this->prepare_abandonment_data( $post_data );

		if ( isset( $session_checkout_details ) && PWCAB_CART_COMPLETED_ORDER === $session_checkout_details->order_status ) {
			WC()->session->__unset( 'pwc_abandonment_session_id' );
			$session_id = md5( uniqid( wp_rand(), true ) );
		}

		if ( isset( $checkout_details['cart_total'] ) && $checkout_details['cart_total'] > 0 ) {
			if ( ( ! is_null( $session_id ) ) && ! is_null( $session_checkout_details ) ) {
				// Updating row in the Database where users Session id = same as prevously saved in Session.
				$wpdb->update( $cart_abandonment_table, $checkout_details, [ 'session_id' => $session_id ] );
			} else {
				$checkout_details['session_id'] = sanitize_text_field( $session_id );
				// Inserting row into Database.
				$wpdb->insert( $cart_abandonment_table, $checkout_details );

				// Storing session_id in WooCommerce session.
				WC()->session->set( 'pwc_abandonment_session_id', $session_id );
			}
		} else {
			$wpdb->delete( $cart_abandonment_table, [ 'session_id' => sanitize_key( $session_id ) ] );
		}

		wp_send_json_success();
	}

	/**
	 * Prepare cart data to save for abandonment.
	 *
	 * @param array $post_data post data.
	 *
	 * @return array
	 */
	public function prepare_abandonment_data( $post_data = [] )
	{
		if ( function_exists( 'WC' ) ) {
			// Retrieving cart total value and currency.
			$cart_total = WC()->cart->total;

			// Retrieving cart products and their quantities.
			$products     = WC()->cart->get_cart();
			$current_time = date_i18n( "Y-m-d H:i:s" );

			$other_fields = [
				'billing_email'       => $post_data['billing_email'],
				'billing_first_name'  => $post_data['billing_first_name'],
				'billing_last_name'   => $post_data['billing_last_name'],
				'billing_phone'       => $post_data['billing_phone'],
				'billing_company'     => $post_data['billing_company'],
				'billing_address_1'   => $post_data['billing_address_1'],
				'billing_address_2'   => $post_data['billing_address_2'],
				'billing_state'       => $post_data['billing_state'],
				'billing_postcode'    => $post_data['billing_postcode'],
				'shipping_first_name' => $post_data['shipping_first_name'],
				'shipping_last_name'  => $post_data['shipping_last_name'],
				'shipping_company'    => $post_data['shipping_company'],
				'shipping_country'    => $post_data['shipping_country'],
				'shipping_address_1'  => $post_data['shipping_address_1'],
				'shipping_address_2'  => $post_data['shipping_address_2'],
				'shipping_city'       => $post_data['shipping_city'],
				'shipping_state'      => $post_data['shipping_state'],
				'shipping_postcode'   => $post_data['shipping_postcode'],
				'order_comments'      => $post_data['order_comments'],

			];
			is_user_logged_in() ? $user_id = get_current_user_id() : $user_id = null;
			$checkout_details = [
				'phone'         => $post_data['billing_phone'],
				'cart_contents' => json_encode( $products ),
				'cart_total'    => sanitize_text_field( $cart_total ),
				'time'          => sanitize_text_field( $current_time ),
				'other_fields'  => json_encode( $other_fields ),
				'checkout_id'   => $post_data['post_id'],
				'user_id'       => $user_id,
			];
		}

		return $checkout_details;
	}

	/**
	 * Deletes cart abandonment tracking and scheduled event.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @since 1.0.0
	 */
	public function delete_cart_abandonment_data( $order_id )
	{
		$acceptable_order_statuses = [ 'completed', 'processing' ];
		$order                     = wc_get_order( $order_id );
		$order_status              = $order->get_status();
		if ( ! in_array( $order_status, $acceptable_order_statuses, true ) ) {
			// Proceed if order status in completed or processing.
			return;
		}

		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );

		if ( isset( WC()->session ) ) {
			$session_id = WC()->session->get( 'pwc_abandonment_session_id' );

			if ( isset( $session_id ) ) {
				$checkout_details = $this->get_checkout_details( $session_id );

				$has_mail_sent = count( $this->fetch_scheduled_checkout( $session_id, true ) );

				if ( ! $has_mail_sent ) {
					$wpdb->delete( $cart_abandonment_table, [ 'session_id' => sanitize_key( $session_id ) ] );
				} else {
					if ( $checkout_details && ( PWCAB_CART_ABANDONED_ORDER === $checkout_details->order_status || PWCAB_CART_LOST_ORDER === $checkout_details->order_status ) ) {
						$this->skip_future_sms_when_order_is_completed( $session_id );

						$order = wc_get_order( $order_id );
						$note  = __( 'This order was abandoned & subsequently recovered.', 'payamito-woocommerce ' );
						$order->add_order_note( $note );
						$order->save();
					} elseif ( PWCAB_CART_COMPLETED_ORDER !== $checkout_details->order_status ) {
						// Normal checkout.

						$billing_phone = filter_input( INPUT_POST, 'billing_phone', FILTER_SANITIZE_EMAIL );

						if ( $billing_phone ) {
							$order_data = $this->get_captured_data_by_phone( $billing_phone );

							if ( ! is_null( $order_data ) ) {
								$existing_cart_contents = unserialize( $order_data->cart_contents );
								$order_cart_contents    = unserialize( $checkout_details->cart_contents );
								$existing_cart_products = array_keys( (array) $existing_cart_contents );
								$order_cart_products    = array_keys( (array) $order_cart_contents );
								if ( $this->check_if_similar_cart( $existing_cart_products, $order_cart_products ) ) {
									$this->skip_future_sms_when_order_is_completed( $order_data->session_id );
								}
							}
						}
						$wpdb->delete( $cart_abandonment_table, [ 'session_id' => sanitize_key( $session_id ) ] );
					}
				}
			}
			if ( WC()->session ) {
				WC()->session->__unset( 'pwc_abandonment_session_id' );
			}
		}
	}

	/**
	 * Unschedule future emails for completed orders.
	 *
	 * @param string $session_id    session id.
	 * @param bool   $skip_complete skip update query.
	 */
	public function skip_future_sms_when_order_is_completed( $session_id, $skip_complete = false )
	{
		global $wpdb;
		$email_history_table    = DB::table_name( "history" );
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );

		if ( ! $skip_complete ) {
			$wpdb->update( $cart_abandonment_table, [
				'order_status' => PWCAB_CART_COMPLETED_ORDER,
			], [
				'session_id' => sanitize_key( $session_id ),
			] );
		}

		$wpdb->update( $email_history_table, [ 'email_sent' => - 1 ], [
			'session_id' => $session_id,
			'email_sent' => 0,
		] );
	}

	/**
	 * Compare cart if similar products.
	 *
	 * @param array $cart_a cart_a.
	 * @param array $cart_b cart_b.
	 *
	 * @return bool
	 */
	public function check_if_similar_cart( $cart_a, $cart_b )
	{
		return ( is_array( $cart_a ) && is_array( $cart_b ) && count( $cart_a ) === count( $cart_b ) && array_diff( $cart_a, $cart_b ) === array_diff( $cart_b, $cart_a ) );
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $pwc_abandonment_session_id checkout page session id.
	 *
	 * @since 1.0.0
	 */
	public function get_checkout_details( $pwc_abandonment_session_id )
	{
		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );
		$result                 = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $cart_abandonment_table . '` WHERE session_id = %s', $pwc_abandonment_session_id ) // phpcs:ignore
		);

		return $result;
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $phone user phone.
	 *
	 * @since 1.0.0
	 */
	public function get_checkout_details_by_phone( $phone )
	{
		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );
		$result                 = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $cart_abandonment_table . '` WHERE phone = %s AND `order_status` IN ( %s, %s )', $phone, PWCAB_CART_ABANDONED_ORDER, PWCAB_CART_NORMAL_ORDER ) // phpcs:ignore
		);

		return $result;
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $value value.
	 *
	 * @since 1.0.0
	 */
	public function get_captured_data_by_phone( $value )
	{
		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );
		$result                 = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $cart_abandonment_table . '` WHERE phone = %s AND `order_status` IN (%s, %s) ORDER BY `time` DESC LIMIT 1', $value, PWCAB_CART_ABANDONED_ORDER, PWCAB_CART_LOST_ORDER ) );

		return $result;
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $value value.
	 *
	 * @since 1.0.0
	 */
	public function get_tracked_data_without_status( $value )
	{
		global $wpdb;
		$cart_abandonment_table = DB::table_name( "cart_abandonment" );
		$result                 = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $cart_abandonment_table . '` WHERE phone = %s LIMIT 1', $value ) // phpcs:ignore
		);

		return $result;
	}

	/**
	 * Count abandoned carts
	 *
	 * @since 1.1.5
	 */
	public function abandoned_cart_count()
	{
		global $wpdb;
		$cart_abandonment_table_name = DB::table_name( "cart_abandonment" );

		$query       = $wpdb->prepare( "SELECT   COUNT(`id`) FROM `{$cart_abandonment_table_name}`  WHERE `order_status` = %s", PWCAB_CART_ABANDONED_ORDER ); // phpcs:ignore
		$total_items = $wpdb->get_var( $query ); // phpcs:ignore

		return $total_items;
	}

	/**
	 *  Decode and get the original contents.
	 *
	 * @param string $token token.
	 */
	public function pwc_abandonment_decode_token( $token )
	{
		$token = sanitize_text_field( $token );
		parse_str( base64_decode( urldecode( $token ) ), $token );

		return $token;
	}

	/**
	 *  Callback trigger event to send the sms.
	 */
	public function pattern_send( $phone, $body_sms, $puttern_id )
	{
		$send   = Payamito_Woocommerce::get_object_send();
		$result = $send->Send_pattern( $phone, $body_sms, $puttern_id );

		return $result;
	}

	/**
	 * Fetch all the scheduled emails with templates for the specific session.
	 *
	 * @param string  $session_id session id.
	 * @param boolean $fetch_sent sfetch sent emails.
	 *
	 * @return array|object|null
	 */
	public function fetch_scheduled_checkout( $session_id, $fetch_sent = false )
	{
		global $wpdb;
		$history_table = DB::table_name( "history" );

		$query = $wpdb->prepare( "SELECT * FROM  `{$history_table}`  `session_id` = %s", sanitize_text_field( $session_id ) );

		if ( $fetch_sent ) {
			$query .= ' AND _sent = 1';
		}

		$result = $wpdb->get_results( $query ); // phpcs:ignore

		return $result;
	}

	/**
	 * Delete orders from cart abandonment table whose cart total is zero and order status is abandoned.
	 */
	public function delete_empty_abandoned_order()
	{
		global $wpdb;

		$cart_abandonment_table = DB::table_name( "cart_abandonment" );

		$where = [
			'cart_total' => 0,
		];

		$wpdb->delete( $cart_abandonment_table, $where );
	}
}