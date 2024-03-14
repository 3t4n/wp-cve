<?php
require_once dirname( __DIR__ ) . '/functions/common.php';
require_once dirname(__DIR__). '/date-modifiers/order_delivery_date.php';
require_once dirname( __FILE__ ) . '/Woocommerce_Core_Shipday.php';


class Woo_Order_Shipday extends Woocommerce_Core_Shipday {
	protected $order;

	function __construct($order_id) {
		$this->order = wc_get_order($order_id);
	}
	public function get_payloads() {
		return array(
			get_shipday_api_key() => [$this->get_user_filtered_payload($this->get_payload())]
		);
	}

	public function get_payload() {
		return array_merge(
			$this->get_payload_without_dependant_info(),
			$this->get_restaurant_info(),
			get_shipday_pickup_delivery_times($this->order),
			$this->get_signature(),
			$this->get_uuid()
		);
	}

	public function get_payload_without_dependant_info() {
		return array_merge(
			$this->get_ids(),
			$this->get_shipping_address(),
			$this->get_costing(),
			$this->get_dropoff_object(),
			$this->get_order_items(),
			$this->get_payment_info(),
			$this->get_message()
		);
	}

	function get_ids() : array {
		return array(
			'orderNumber' => $this->order->get_id(),
			'additionalId' => $this->order->get_id()
		);
	}

	/** Needs more info */
	public static function get_restaurant_info( ): array {
		$store_name = shipday_handle_null( get_bloginfo( 'name' ) );

		$address1      = shipday_handle_null( get_option( 'woocommerce_store_address' ) );
		$city          = shipday_handle_null( get_option( 'woocommerce_store_city' ) );
		$post_code     = shipday_handle_null( get_option( 'woocommerce_store_postcode' ) );
		$country_state = shipday_handle_null( get_option( 'woocommerce_default_country' ) );

		$split_country = explode( ":", $country_state );
		$country_code  = $split_country[0];
		$state_code    = $split_country[1];
		$state         = self::to_state_name( $state_code, $country_code );
		$country       = self::to_country_name( $country_code );

		$full_address = $address1 . ', ' . $city . ', ' . $state . ', ' . $post_code . ', ' . $country;

		return array(
			"restaurantName"    => $store_name,
			"restaurantAddress" => $full_address
		);
	}

	function get_costing(): array {
		$tax          = $this->order->get_total_tax();
		$discount     = $this->order->get_total_discount();
		$delivery_fee = $this->order->get_shipping_total();
		$total        = $this->order->get_total();
        $subtotal     = $this->order->get_subtotal();

        $tips = $total - $subtotal - $tax + $discount - $delivery_fee;

		return array(
			'tips'           => $tips,
			'tax'            => $tax,
			'discountAmount' => $discount,
			'deliveryFee'    => $delivery_fee,
			'totalOrderCost' => strval($total)
		);
	}

	public function get_uuid(): array {
		return array(
			'uuid' => get_option('wc_settings_tab_shipday_registered_uuid')
		);
	}

	function get_signature(): array {
        $data = parent::get_signature();
        $data['signature']['type'] = 'single-vendor';
        $data['signature']['plugin'] = 'vanilla';

        return $data;
	}

}