<?php

require_once dirname( __DIR__ ) . '/functions/common.php';
require_once dirname( __FILE__ ) . '/Woocommerce_Core_Shipday.php';
require_once dirname(__FILE__). '/Woo_Order_Shipday.php';
require_once dirname(__DIR__). '/date-modifiers/order_delivery_date.php';

class WCFM_Order_Shipday extends Woocommerce_Core_Shipday {
	protected $order;
    protected $store_shipping;
	private $items_by_vendors;
	private $order_payloads;
	private $api_keys;


	function __construct($order_id) {
        shipday_logger('info', 'Constructing WCFM order from order id '.$order_id);
        try {
            $this->order            = wc_get_order($order_id);
        } catch (Exception $e) {
            shipday_logger('error', $order_id.': WCFM construct wc_get_order failed');
        }
        try {
            $this->store_shipping = (new WCFMmp_Shipping())->get_order_vendor_shipping($this->order);
        } catch (Exception $e) {
            shipday_logger('error', $order_id.': WCFM construct get_order_vendor_shipping failed');
        }
        try {
            $this->items_by_vendors = $this->split_items_by_vendors();
        } catch (Exception $e) {
            shipday_logger('error', $order_id.': WCFM construct split_items_by_vendors failed');
        }
        try {
            $this->generate_payloads_api_keys();
        } catch (Exception $e) {
            shipday_logger('error', $order_id.': WCFM construct generate_payloads_api_keys failed');
        }
	}

	public function get_payloads() {
		for ($i = 0; $i < count($this->api_keys); $i++) {
			$api_key = $this->api_keys[$i];
			$payload = $this->order_payloads[$i];
			$payloads[$api_key][] = $this->get_user_filtered_payload($payload);
		}

		return $payloads;
	}

	function split_items_by_vendors() {
		$items_by_vendors = array();
		foreach ($this->order->get_items() as $item) {
			$product_id = $item->get_product_id();
			$store_id = wcfm_get_vendor_id_by_post($product_id);
			if (!array_key_exists($store_id, $items_by_vendors))
				$items_by_vendors[$store_id] = array();
			$items_by_vendors[$store_id][] = $item;
		}
		return $items_by_vendors;
	}

	function generate_payloads_api_keys() {
		$this->order_payloads = array();
		$this->api_keys       = array();
		foreach ($this->items_by_vendors as $store_id => $items){
			$payload                = array_merge(
				$this->get_ids(),
				$this->get_shipping_address(),
				$this->get_vendor_info($store_id),
				$this->get_order_items($items),
				$this->get_costing($store_id, $items),
				$this->get_payment_info(),
				$this->get_dropoff_object(),
				$this->get_message(),
				$this->get_signature_for_store($store_id),
				get_shipday_pickup_delivery_times($this->order)
			);
			$this->order_payloads[] = $payload;
			$api_key = $this->get_wcfm_api_key($store_id);
			$this->api_keys[] = $api_key;
		}
	}
	function get_ids() : array {
		return array(
			'orderNumber' => $this->order->get_id(),
			'additionalId' => $this->order->get_id()
		);
	}

	function is_admin_store($store_id) {
		$store_user    = wcfmmp_get_store( $store_id );
		$store_name = $store_user->get_shop_name();
		if ($store_id == 0 && empty($store_name) ) {
			return true;
		}
		return false;
	}

	function get_wcfm_api_key($store_id) {
		if (get_shipday_order_manager() == 'admin_manage' || $this->is_admin_store($store_id)) return get_shipday_api_key();
		$vendor_data            = get_user_meta( $store_id, 'wcfmmp_profile_settings', true );
		return shipday_handle_null($vendor_data['shipday']['api_key']);
	}

	function get_vendor_info($store_id) : array {
		$store_user    = wcfmmp_get_store( $store_id );
		$store_name = $store_user->get_shop_name();

		if ($this->is_admin_store($store_id)) return Woo_Order_Shipday::get_restaurant_info();

		$address = $store_user->get_address();
		$address1 = $address['street_1'];
		$address2 = $address['street_2'];
		$city     = $address['city'];
		$post_code     = $address['zip'];
		$state_code = $address['state'];
		$country_code = $address['country'];

		$state = $this->to_state_name($state_code, $country_code);
		$country       = $this->to_country_name( $country_code );

		$full_address = $address1 . ', ' . $address2 . ', ' . $city . ', ' . $state . ', ' . $post_code . ', ' . $country;

		$phone = self::add_calling_country_code($store_user->get_phone(), $country_code);

		return array(
			"restaurantName"    => $store_name,
			"restaurantAddress" => $full_address,
			"restaurantPhoneNumber" => $phone
		);
	}


	function get_costing($store_id, $items) : array {
        $shipping_info = $this->store_shipping[$store_id];
		$tips = 0.0;
		$tax = 0;
		$discount = 0.0;
		$delivery_fee = floatval($shipping_info['shipping']);
		$total = 0;
		foreach ($items as $item) {
			$tax          += floatval( $item->get_total_tax() );
			$total        += floatval( $item->get_total() );
            $product       = wc_get_product($item->get_product_id());
            $discount     += floatval($item->get_total()) - floatval($product->get_price()) * intval($item->get_quantity());
		}

		$costing = array(
			'tips'           => $tips,
			'tax'            => $tax,
			'discountAmount' => $discount,
			'deliveryFee'    => $delivery_fee,
			'totalOrderCost' => strval($total + $delivery_fee + $tax + $tips)
		);

		return $costing;
	}
    function get_signature_for_store($store_id): array {
        $data = $this->get_signature();
        $data['signature']['vendor id'] = $store_id;
        return $data;
    }
	function get_signature(): array {
        $data = parent::get_signature();
        $data['signature']['type'] = 'multi-vendor';
        $data['signature']['Order Managed By'] = get_shipday_order_manager();
        $data['signature']['plugin'] = 'WCFM';
        $data['signature']['WCFM version'] = WCFM_VERSION;
        $data['signature']['WCFMmp version'] = WCFMmp_VERSION;
        return $data;
	}

}