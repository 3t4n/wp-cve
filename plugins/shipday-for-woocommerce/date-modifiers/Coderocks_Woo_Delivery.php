<?php
require_once dirname( __FILE__ ) . '/Date_Picker_Object.php';

class Coderocks_Woo_Delivery extends Date_Picker_Object {

	public function __construct($order_id) {
		$this->utc = new DateTimeZone('Utc');

		if(is_plugin_active('woo-delivery/coderockz-woo-delivery.php') || is_plugin_active('coderockz-woocommerce-delivery-date-time-pro/coderockz-woo-delivery.php')) {
			require_once CODEROCKZ_WOO_DELIVERY_DIR . '/includes/class-coderockz-woo-delivery-helper.php';
			require_once CODEROCKZ_WOO_DELIVERY_DIR . '/includes/class-coderockz-woo-delivery-delivery-option.php';
			$this->time_zone = (new Coderockz_Woo_Delivery_Helper())->get_the_timezone();

			$this->set_pickup_datetime($order_id);
			$this->set_delivery_datetime($order_id);
		}
	}


	private function set_pickup_datetime($order_id) {
		if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") {
			$formatted_pickup_date = get_post_meta( $order_id, 'pickup_date', true );

			if (metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) != '') {
				$this->pickup_time_flag = true;
				$timeslot                 = get_post_meta($order_id, 'pickup_time', true);
				$times = explode('-', $timeslot);
				$formatted_pickup_date = $formatted_pickup_date.', ' . $times[0];
			}

			$this->pickup_date_time = new DateTime($formatted_pickup_date, new DateTimeZone($this->time_zone));
			$this->pickup_date_time->setTimezone($this->utc);
		}
	}

	private function set_delivery_datetime($order_id) {
		if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id,"delivery_date",true) != "") {
			$formatted_delivery_date = get_post_meta( $order_id, 'delivery_date', true );

			if (metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) != '') {
				$this->delivery_time_flag = true;
				$timeslot                 = get_post_meta($order_id, 'delivery_time', true);
				$times = explode('-', $timeslot);
				$formatted_delivery_date = $formatted_delivery_date.', ' . $times[0];
			}

			$this->delivery_date_time = new DateTime($formatted_delivery_date, new DateTimeZone($this->time_zone));
			$this->delivery_date_time->setTimezone($this->utc);
		}
	}
}