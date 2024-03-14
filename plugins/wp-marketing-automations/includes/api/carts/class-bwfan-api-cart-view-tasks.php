<?php

class BWFAN_API_Carts_View_Tasks extends BWFAN_API_Base {
	public static $ins;
	public $task_localized = [];
	public $total_count = 0;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/carts/(?P<abandoned_id>[\\d]+)/tasks/';
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function default_args_values() {
		return [
			'abandoned_id' => '',
		];
	}

	public function process_api_call() {
		$abandoned_id = $this->args['abandoned_id'];
		if ( empty( $abandoned_id ) ) {
			return $this->error_response( __( 'Abandoned cart is missing', 'wp-marketing-automations' ) );
		}
		$type = $this->args['type'];

		global $wpdb;
		$cart_details  = [];
		$basic_details = [];
		if ( 'recovered' === $type ) {
			if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
				$query    = $wpdb->prepare( "SELECT `order_id` FROM {$wpdb->prefix}wc_orders_meta WHERE `meta_key` = %s AND `meta_value`= %d", '_bwfan_recovered_ab_id', $abandoned_id );
				$order_id = $wpdb->get_var( $query );
			} else {
				$query    = $wpdb->prepare( "SELECT `post_id` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = %s AND `meta_value`= %d", '_bwfan_recovered_ab_id', $abandoned_id );
				$order_id = $wpdb->get_var( $query );
			}
			if ( empty( $order_id ) ) {
				return $this->error_response( __( 'Abandoned cart data is missing', 'wp-marketing-automations' ) );
			}

			$order = wc_get_order( $order_id );
			if ( ! $order instanceof WC_Order ) {
				return $this->error_response( __( 'Order is missing', 'wp-marketing-automations' ) );
			}

			$cart_details['email'] = $order->get_meta( '_billing_email' );

			$basic_details['email']  = $cart_details['email'];
			$basic_details['f_name'] = $order->get_meta( '_billing_first_name' );
			$basic_details['l_name'] = $order->get_meta( '_billing_last_name' );
		} else {
			$cart_details = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
			if ( empty( $cart_details ) ) {
				return $this->error_response( __( 'Abandoned cart data is missing', 'wp-marketing-automations' ) );
			}
			$cart_data = json_decode( $cart_details['checkout_data'], true );

			$basic_details['email']  = $cart_details['email'];
			$basic_details['f_name'] = isset( $cart_data['fields'] ) && isset( $cart_data['fields']['billing_first_name'] ) ? $cart_data['fields']['billing_first_name'] : '';
			$basic_details['l_name'] = isset( $cart_data['fields'] ) && isset( $cart_data['fields']['billing_last_name'] ) ? $cart_data['fields']['billing_last_name'] : '';
		}

		$arr = [ 'v1' => [], 'v2' => [] ];

		/** Automation v1 */
		$cart_tasks = [];

		/** Automation v2 */
		$cart_automations = [];

		if ( BWFAN_Common::is_automation_v1_active() ) {
			$cart_tasks = BWFAN_Recoverable_Carts::get_cart_tasks( $abandoned_id );
			$arr['v1']  = $cart_tasks;
		}

		$cart_email = $cart_details['email'];

		$table1 = $wpdb->prefix . 'bwfan_automation_contact';
		$table2 = $wpdb->prefix . 'bwfan_automation_complete_contact';

		$query1  = "SELECT * FROM $table1 WHERE `event` LIKE 'ab_cart_abandoned' AND `data` LIKE '%cart_abandoned_id\":\"{$abandoned_id}\"%' AND data LIKE '%email\":\"{$cart_email}\"%'";
		$result1 = $wpdb->get_results( $query1, ARRAY_A );

		$query2  = "SELECT * FROM $table2 WHERE `event` LIKE 'ab_cart_abandoned' AND `data` LIKE '%cart_abandoned_id\":\"{$abandoned_id}\"%' AND data LIKE '%email\":\"{$cart_email}\"%'";
		$result2 = $wpdb->get_results( $query2, ARRAY_A );

		if ( ! empty( $result1 ) ) {
			foreach ( $result1 as $a_contact ) {
				$event_slug = $a_contact['event'];
				$event_obj  = BWFAN_Core()->sources->get_event( $event_slug );
				$event_name = ! empty( $event_obj ) ? $event_obj->get_name() : '';

				$automation_data = BWFAN_Model_Automations_V2::get_automation( $a_contact['aid'] );
				unset( $a_contact['data'] );
				$a_contact['event']            = $event_name;
				$a_contact['automation_title'] = $automation_data['title'];
				$a_contact['e_time']           = isset( $a_contact['e_time'] ) ? date( 'Y-m-d H:i:s', $a_contact['e_time'] ) : '';
				$a_contact['last_time']        = isset( $a_contact['last_time'] ) ? date( 'Y-m-d H:i:s', $a_contact['last_time'] ) : '';
				$cart_automations[]            = array_merge( $a_contact, $basic_details );
			}
		}

		if ( ! empty( $result2 ) ) {
			foreach ( $result2 as $a_contact ) {
				$event_slug = $a_contact['event'];
				$event_obj  = BWFAN_Core()->sources->get_event( $event_slug );
				$event_name = ! empty( $event_obj ) ? $event_obj->get_name() : '';

				$automation_data = BWFAN_Model_Automations_V2::get_automation( $a_contact['aid'] );
				unset( $a_contact['data'] );
				$a_contact['last_time']        = isset( $a_contact['c_date'] ) ? $a_contact['c_date'] : '';
				$a_contact['event']            = $event_name;
				$a_contact['automation_title'] = $automation_data['title'];

				$cart_automations[] = array_merge( $a_contact, $basic_details );
			}
		}
		$msg = __( 'Automation tasks found', 'wp-marketing-automations' );
		if ( empty( $cart_tasks ) && empty( $cart_automations ) ) {
			$msg = __( 'No automation found for cart id: ', 'wp-marketing-automations' ) . $abandoned_id;
		}

		if ( ! empty( $cart_automations ) ) {
			$arr['v2'] = $cart_automations;
		}

		return $this->success_response( $arr, $msg );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Carts_View_Tasks' );
