<?php
require_once dirname( __DIR__ ) . '/functions/common.php';

class Woocommerce_Core_Shipday {
	protected $order;
	public static function to_state_name( $state_code, $country_code ) {
		return ! empty( $state_code ) ? WC()->countries->get_states( $country_code )[ $state_code ] : $state_code;
	}

	public static function to_country_name( $country_code ) {
		return ! empty( $country_code ) ? ( new WC_Countries() )->get_countries()[ $country_code ] : '';
	}

	public static function add_calling_country_code($phone_number, $country_code) {
		return $phone_number;
	}

	function get_customer_info(): array {
		$name = sanitize_user( shipday_handle_null( $this->order->get_billing_first_name() ) ) . ' ' . sanitize_user( shipday_handle_null( $this->order->get_billing_last_name() ) );

		$address1     = shipday_handle_null( $this->order->get_billing_address_1() );
//		$address2     = handle_null( $this->order->get_billing_address_2() );
		$city         = shipday_handle_null( $this->order->get_billing_city() );
		$state_code   = shipday_handle_null( $this->order->get_billing_state() );
		$post_code    = shipday_handle_null( $this->order->get_billing_postcode() );
		$country_code = shipday_handle_null( $this->order->get_billing_country() );

		$state        = $this->to_state_name( $state_code, $country_code );
		$country      = $this->to_country_name( $country_code );
		$full_address = $address1 . ', ' . $city . ', ' . $state . ', ' . $post_code . ', ' . $country;

		$phoneNumber  = $this->add_calling_country_code(shipday_handle_null( $this->order->get_billing_phone() ), $country_code);

		$emailAddress = shipday_handle_null( $this->order->get_billing_email() );

		$customer_info = array(
			"customerName"        => $name,
			"customerAddress"     => $full_address,
			"customerPhoneNumber" => $phoneNumber,
			"customerEmail"       => $emailAddress
		);

		return $customer_info;
	}

	function get_shipping_address() : array {
		if ( ! $this->order->has_shipping_address() ) {
			return $this->get_customer_info();
		}
		$shipping_info = array(
			"customerName"        => sanitize_user( shipday_handle_null( $this->order->get_shipping_first_name() ) ) . ' ' . sanitize_user( shipday_handle_null( $this->order->get_shipping_last_name() ) ),
			"customerAddress"     => shipday_handle_null( $this->order->get_shipping_address_1() ) . ', ' .
			                         shipday_handle_null( $this->order->get_shipping_city() ) . ', ' .
			                         $this->to_state_name( shipday_handle_null( $this->order->get_shipping_state() ), shipday_handle_null( $this->order->get_shipping_country() ) ) . ', ' .
			                         shipday_handle_null( $this->order->get_shipping_postcode() ) . ', ' .
			                         $this->to_country_name( shipday_handle_null( $this->order->get_shipping_country() ) ),
			"customerPhoneNumber" => ! empty( $this->order->shipping_phone ) ?
				self::add_calling_country_code($this->order->shipping_phone, $this->order->get_shipping_country()) :
				$this->add_calling_country_code(shipday_handle_null( $this->order->get_billing_phone() ), $this->order->get_billing_country()),
			"customerEmail"       => ! empty( $this->order->shipping_email ) ? $this->order->shipping_email : shipday_handle_null( $this->order->get_billing_email() )
		);

		return $shipping_info;
	}

	function get_dropoff_object(): array {
		$address = $this->order->has_shipping_address() ? $this->order->get_address( 'shipping' ) : $this->order->get_address('billing');

		$address1     = shipday_handle_null( $address['address_1'] );
		$address2     = shipday_handle_null( $address['address_2'] );
		$city         = shipday_handle_null( $address['city'] );
		$state_code   = shipday_handle_null( $address['state'] );
        try {
            $post_code    = shipday_handle_null( $address['postcode'] );
        } catch (Exception $exception) {
            ;
        }
		$country_code = shipday_handle_null( $address['country'] );

		$state   = ! empty( $state_code ) ? WC()->countries->get_states( $country_code )[ $state_code ] : '';
		$country = ! empty( $country_code ) ? ( new WC_Countries() )->get_countries()[ $country_code ] : '';

		return array(
			'dropoff' => array(
				'address' => array(
					'unit'    => $address2,
					'street'  => $address1,
					'city'    => $city,
					'state'   => $state,
					'zip'     => $post_code,
					'country' => $country
				)
			)
		);
	}

	function get_order_items($items = null) : array {
		if ($items === null) $items = $this->order->get_items();
		foreach ( $items as $item_id => $item ) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id); // can return boolean
            $price = $product ? $product->get_price() : 0.0;
            $orderItem[] = array(
				'name'      => $item->get_name(),
				'quantity'  => $item->get_quantity(),
				'unitPrice' => $price,
			);
		}
		return array(
			'orderItem' => $orderItem
		);
	}

	function get_payment_info() : array {
		$paymentMethod = $this->order->get_payment_method() == 'cod' ? 'CASH' : '';
		return array(
			'paymentMethod' => $paymentMethod
		);
	}


	function get_message() : array {
        $address2 = shipday_handle_null($this->order->get_shipping_address_2());
        $notes = shipday_handle_null($this->order->get_customer_note());
		return array(
			'deliveryInstruction' => implode('. ', array(
                $address2,
                $notes
            ))
		);
	}

    function get_signature(): array {
        global $shipday_plugin_version;
        return array(
            'orderSource' => 'WooCommerce',
            'signature' => array(
                'version' => $shipday_plugin_version,
                'wooVersion' => WC()->version,
                'url' => get_site_url(),
                'datetime modifiers' => get_shipday_datetime_plugins(),
                'timezone' => get_shipday_datetime_timezone()
            )
        );
    }

    function is_pickup_order() {
        $shipping_methods = $this->order->get_shipping_methods();
        $is_pickup = false;
        foreach ($shipping_methods as $shipping_method) {
            $is_pickup = $is_pickup || $shipping_method->get_method_id() == 'local_pickup';
        }
        return $is_pickup;
    }

    function prevent_order_sync() {
        if ($this->order == null) return true;
        $flag = get_post_meta($this->order->get_id(), '_shipday_order_sync_prevent', true);
        return $flag == "yes";
    }

    public function get_user_filtered_payload($payload) {
        return apply_filters('shipday_order_data_filter', $payload, $this->order->get_id());
    }

}