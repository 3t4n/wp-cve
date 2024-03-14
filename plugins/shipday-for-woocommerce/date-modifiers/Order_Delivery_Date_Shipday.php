<?php
require_once dirname( __FILE__ ) . '/Date_Picker_Object.php';

class Order_Delivery_Date_Shipday extends  Date_Picker_Object {

	public function __construct($order_id) {
		$this->utc = new DateTimeZone('Utc');
		if(is_plugin_active('order-delivery-date-for-woocommerce/order_delivery_date.php')) {
			$this->initialize_ordd_lite($order_id);
		} else if (is_plugin_active('order-delivery-date/order_delivery_date.php')) {
			$this->initialize_ordd_pro($order_id);
		}
	}

	private function initialize_ordd_lite($order_id) {
		//See Orddd_Lite_Common::orddd_lite_get_order_delivery_date($order_id) in includes/class-orddd-lite-common.php

		$data = get_post_meta( $order_id );
        if (isset($data['_orddd_lite_timeslot_timestamp'])) {
            $delivery_timestamp = $data['_orddd_lite_timeslot_timestamp'][0];
        } elseif ( isset( $data['_orddd_lite_timestamp'] ) ) {
			$delivery_timestamp = $data['_orddd_lite_timestamp'][0];
		} elseif ( array_key_exists( get_option( 'orddd_lite_delivery_date_field_label' ), $data ) ) {
			$delivery_timestamp = strtotime( $data[ get_option( 'orddd_lite_delivery_date_field_label' ) ][0] );
		} elseif ( array_key_exists( get_option( 'orddd_delivery_date_field_label' ), $data ) ) {
			$delivery_timestamp = strtotime( $data[ get_option( 'orddd_delivery_date_field_label' ) ][0] );
		}

		$this->delivery_date_time = (new DateTime())->setTimestamp($delivery_timestamp);
        $this->delivery_date_time->setTimezone($this->utc);
        $this->delivery_time_flag = true;
	}

	private function initialize_ordd_pro($order_id) {
		// See orddd_common.php orddd_common::get_order_delivery_date()

		$delivery_timestamp = get_post_meta( $order_id, '_orddd_timestamp', true );
		$this->delivery_date_time = (new DateTime())->setTimestamp($delivery_timestamp);
        $time_slot= (new orddd_common())->orddd_get_order_timeslot($order_id);
        if (isset($time_slot) and !is_null($time_slot)) {
            $this->delivery_date_time->setTimezone(wp_timezone());
            $times = explode('-', $time_slot);
            $hr_min = explode(':', $times[0]);
            $this->delivery_date_time->setTime(intval($hr_min[0]), intval($hr_min[1]));
        }
        $this->delivery_date_time->setTimezone($this->utc);
		$this->delivery_time_flag = true;

	}
}