<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
	*   PayPal helper class for REST API requests.
	*
*/
#[\AllowDynamicProperties]
class Eh_Rest_Request_Built {

	private $token                       = null;
	private $client_id                   = null;
	private $client_secret               = null;
	protected $params                    = array();
	public $supported_decimal_currencies = array( 'HUF', 'JPY', 'TWD' );
	public $store_currency;
	public $http_version;
	/**
		*   Class constructor.
		*
	*/
	public function __construct( $client_id, $client_secret ) {
		$this->make_params(
			array(
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
			)
		);
		$this->http_version = '1.1';
		$this->currency     = get_woocommerce_currency();
	}

	public function make_params( $args ) {
		if ( is_array( $args ) && ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				 $this->params[ $key ] = $value;
			}
		}

	}

	public function make_request_params( array $args ) {
		$this->currency_code = get_woocommerce_currency();
		$eh_paypal_express_options = get_option('woocommerce_eh_paypal_express_settings');
		$this->make_params(
			array(
				'intent'              => $args['intent'],
				'application_context' => array(
					'brand_name'          => $args['brand_name'],
					'locale'              => ( ( strpos( $args['locale'], '_' ) !== false ) ? str_replace( '_', '-', $args['locale'] ) : $args['locale'] ),
					'landing_page'        => $args['landing_page'],
					'shipping_preference' => $args['shipping_preference'],
					'user_action'         => $args['user_action'],
					'return_url'          => $args['return_url'],
					'cancel_url'          => $args['cancel_url'],
				),

			)
		);

		//order variable exist only if it is order pay page or save abandoned order is enabled
		if ( $args['save_abandoned_order'] || $args['pay_for_order'] ) {
			$order    = wc_get_order( $args['order_id'] );
			$order_no = $order->get_order_number();
			$this->make_params(
				array(
					'purchase_units' => array( 0 => array( 'invoice_id' => ( ! empty( $args['invoice_prefix'] ) ? $args['invoice_prefix'] . $order_no : $order_no ) ) ),
				)
			);

		}

		$i = 0;
		if ( ! defined( 'WOOCOMMERCE_CART' ) ) {
			define( 'WOOCOMMERCE_CART', true );
		}

		//if order processed from pay for order page set line items from order details as cart doesn't contains product details
		if ( $args['pay_for_order'] ) {

			$order_id = $args['order_id'];
			$order    = wc_get_order( $order_id );
			$this->order_item_params( $order );

			$this->add_shipping_details(
				array(
					'name'    => array( 'full_name' => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() ),
					'address' => array(
						'address_line_1' => $order->get_shipping_address_1(),
						'address_line_2' => $order->get_shipping_address_2(),
						'admin_area_2'   => $order->get_shipping_city(),
						'admin_area_1'   => $order->get_shipping_state(),
						'postal_code'    => $order->get_shipping_postcode(),
						'country_code'   => $order->get_shipping_country(),
					),
				)
			);

		} else {
			WC()->cart->calculate_totals();

			//sets recurring cart items to session
			if ( isset( WC()->cart->recurring_carts ) ) {
				if ( count( WC()->cart->recurring_carts ) > 0 ) {

					WC()->session->eh_recurring_carts = WC()->cart->recurring_carts;
				}
			}
			$discount_amount = 0;

			//fix for compatibility issue with store credit coupon created with  WC Smart Coupon plugin
			//$order = wc_get_order($args['order_id']);
			if ( isset( WC()->cart->smart_coupon_credit_used ) ) {

				/*foreach( $order->get_items( 'coupon' ) as $item_id => $coupon_item_obj ){

					$coupon_item_data = $coupon_item_obj->get_data();

					$coupon_data_id = $coupon_item_data['id'];

					$order->remove_item($coupon_data_id);

				}
				foreach ( WC()->cart->get_coupons() as $code => $coupon ) {

					$item = new WC_Order_Item_Coupon();
					$item->set_props(
						array(
							'code'         => $code,
							'discount'     => WC()->cart->get_coupon_discount_amount( $code ),
							'discount_tax' => WC()->cart->get_coupon_discount_tax_amount( $code ),
						)
					);
					// Avoid storing used_by - it's not needed and can get large.
					$coupon_data = $coupon->get_data();
					unset( $coupon_data['used_by'] );
					$item->add_meta_data( 'coupon_data', $coupon_data );

					// // Add item to order and save.
					$order->add_item( $item );

					$order->set_total( WC()->cart->get_total( 'edit' ) );
					$order->save();

				}*/

				$total         = $this->make_paypal_amount( WC()->cart->total );
				$rounded_total = ( $this->make_paypal_amount( WC()->cart->cart_contents_total + WC()->cart->fee_total ) ) + ( $this->make_paypal_amount( WC()->cart->shipping_total ) ) + ( wc_round_tax_total( WC()->cart->tax_total + WC()->cart->shipping_tax_total ) );

				if ( $total != $rounded_total ) {

					$discount_amount += $total - $rounded_total;
				}
			}

			//when checkout using express button some fee details are not saved in order
			/* if(!empty(WC()->cart->get_fees()) && (count($order->get_fees()) != count(WC()->cart->get_fees()))){

				foreach( $order->get_items( 'fee' ) as $item_id => $fee_obj ){

					$fee_item_data = $fee_obj->get_data();
					$fee_data_id = $fee_item_data['id'];

					$order->remove_item($fee_data_id);
				}

				//adding fee line item to order
				foreach ( WC()->cart->get_fees() as $fee_key => $fee ) {
					$item                 = new WC_Order_Item_Fee();
					$item->legacy_fee     = $fee;
					$item->legacy_fee_key = $fee_key;
					$item->set_props(
						array(
							'name'      => $fee->name,
							'tax_class' => $fee->taxable ? $fee->tax_class : 0,
							'amount'    => $fee->amount,
							'total'     => $fee->total,
							'total_tax' => $fee->tax,
							'taxes'     => array(
								'total' => $fee->tax_data,
							),
						)
					);

					// Add item to order and save.
					$order->add_item( $item );
					$order->save();
					$order->calculate_totals();
				}
			}*/


			//sum of pric of all line item excluding tax
			$cart_subtotal_ex_tax = 0;
			$cart_subtotal_ex_tax = $this->make_paypal_amount( WC()->cart->subtotal_ex_tax, $this->currency_code );

			/*Only send line items to paypal based on the settings option*/
			$smart_button_hide_line_item = isset($eh_paypal_express_options['smart_button_hide_line_item']) ? $eh_paypal_express_options['smart_button_hide_line_item'] : 'no';
			if('yes' != $smart_button_hide_line_item){

				$cart_item = wc()->cart->get_cart();

				$line_item_total_amount = 0;

				$wt_skip_line_items = $this->wt_skip_line_items(); // if tax enabled and when product has inclusive tax
				foreach ( $cart_item as $item ) {
					$cart_product    = $item['data'];
					$line_item_title = $cart_product->get_title();
					$desc_temp       = array();
					$line_item_desc  = '';
					if ( isset( $item['variation'] ) && ! empty( $item['variation'] ) ) {
						foreach ( $item['variation'] as $key => $value ) {
							$desc_temp[] = wc_attribute_label( str_replace( 'attribute_', '', $key ) ) . ' : ' . $value;
						}
						$line_item_desc = implode( ', ', $desc_temp );

						//check whether description length exceed the limit
						if ( ! empty( $line_item_desc ) && strlen( $line_item_desc ) > 127 ) {
							$line_item_desc = substr( $line_item_desc, 0, 127 );
						}
					}

					$line_item_url   = $cart_product->get_permalink();
					$line_item_quan  = $item['quantity'];
					$line_item_total = $item['line_subtotal'] / $line_item_quan;

					if ( $wt_skip_line_items ) {   // if tax enabled and when product has inclusive tax

						$this->add_line_items(
							array(

								'name'        => substr($line_item_title . ' x ' . $line_item_quan, 0, 126),
								'description' => $line_item_desc,
								'quantity'    => $line_item_quan,
								'unit_amount' => array(
									'currency_code' => $this->currency_code,
									'value'         => $this->make_paypal_amount( $line_item_total, $this->currency_code ),
								),
									//'ITEMURL'   => $line_item_url
							),
							$i++
						);

					} else {

						$this->add_line_items(
							array(

								'name'        => substr($line_item_title, 0, 126),
								'description' => $line_item_desc,
								'unit_amount' => array(
									'currency_code' => $this->currency_code,
									'value'         => $this->make_paypal_amount( $line_item_total, $this->currency_code ),
								),
								'quantity'    => $line_item_quan,

							),
							$i++
						);

					}

					$total_amount           = ( $line_item_quan * $this->make_paypal_amount( $line_item_total, $this->currency_code ) );
					$line_item_total_amount = $line_item_total_amount + $total_amount;
				}

				/*if (WC()->cart->get_cart_discount_total() > 0)
				{
					$cart_discount_amount = $this->make_paypal_amount(WC()->cart->get_cart_discount_total(), $this->currency_code);

					$line_item_total_amount  = $line_item_total_amount - $cart_discount_amount;
				}

				//add fee to cart line items
				if(!empty(WC()->cart->get_fees())){
				   foreach ( WC()->cart->get_fees() as $fee_key => $fee_values ) {
						$this->add_line_items( array(

							'name' => $fee_values->name,
							'description' => '',
							'unit_amount' => array(
								'currency_code' => $this->currency_code,
								'value' => $this->make_paypal_amount($fee_values->total, $this->currency_code)
							),
							'quantity' => 1,

						), $i++);
						$line_item_total_amount  = $line_item_total_amount + $this->make_paypal_amount( $fee_values->total, $this->currency_code);

					}
				}*/

				//add line items amount and compares it with cart total amount to check for any total mismatch.cart_contents_total is line item total - doscount
				$item_amount = $this->make_paypal_amount( WC()->cart->cart_contents_total + WC()->cart->fee_total, $this->currency_code );

	            
	            $smart_button_add_extra_line_item = (isset($eh_paypal_express_options['smart_button_add_extra_line_item']) ? $eh_paypal_express_options['smart_button_add_extra_line_item'] : 'yes');
	            if ('yes' == $smart_button_add_extra_line_item && $line_item_total_amount != $cart_subtotal_ex_tax) {
				
					$diff = $this->make_paypal_amount( $cart_subtotal_ex_tax - $line_item_total_amount, $this->currency_code );
					if ( abs( $diff ) > 0.000001 && 0.0 !== (float) $diff ) {
						//add extra line item if there is a total mismatch
							$this->add_line_items(
								array(
									'name'        => __('Extra line item', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
									'description' => '',
									'quantity'    => 1,
									'unit_amount' => array(
										'currency_code' => $this->currency_code,
										'value'         => abs( $diff ),
									),

								),
								$i++
							);

					}
					
				}
			}

			//handle mismatch due to rounded tax calculation
			$ship_discount_amount = 0;
			$cart_total           = $this->make_paypal_amount( WC()->cart->total, $this->currency_code );

			$cart_tax = $this->make_paypal_amount( WC()->cart->tax_total + WC()->cart->shipping_tax_total, $this->currency_code );

			$cart_fee_total = $this->make_paypal_amount( WC()->cart->fee_total, $this->currency_code );

			$cart_shipping_total = $this->make_paypal_amount( WC()->cart->shipping_total, $this->currency_code );

			$cart_discount_total = $this->make_paypal_amount( abs( WC()->cart->get_cart_discount_total() ), $this->currency_code );

			$cart_items_total = ( $cart_subtotal_ex_tax + $cart_shipping_total + $cart_tax + $cart_fee_total ) - $cart_discount_total;

			if ('yes' == $smart_button_add_extra_line_item && $cart_total != $cart_items_total) {
				
				if ( $cart_items_total < $cart_total ) {
					$cart_tax += $cart_total - $cart_items_total;
				} else {
					$ship_discount_amount += $this->make_paypal_amount( $cart_items_total - $cart_total, $this->currency_code );
				}
				
			}

			$this->add_amount_breakdown(
				array(
					'currency_code' => $this->currency_code,
					'value'         => $cart_total,
					'breakdown'     => array(
						'item_total'        => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( $cart_subtotal_ex_tax, $this->currency_code ),
						),
						'shipping'          => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( $cart_shipping_total, $this->currency_code ),
						),
						'tax_total'         => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( abs( $cart_tax ), $this->currency_code ),
						),
						'shipping_discount' => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( abs( $discount_amount + $ship_discount_amount ), $this->currency_code ),
						),
						'discount'          => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( $cart_discount_total, $this->currency_code ),
						),
						'handling'          => array(
							'currency_code' => $this->currency_code,
							'value'         => $this->make_paypal_amount( $cart_fee_total, $this->currency_code ),
						),

					),
				)
			);

			$shipping_address = apply_filters("wt_shipping_address_create_order", array(
						'name'    => array( 'full_name' => ( empty( WC()->session->post_data['shipping_first_name'] ) ? '' : WC()->session->post_data['shipping_first_name'] ) . ' ' . ( empty( WC()->session->post_data['shipping_last_name'] ) ? '' : WC()->session->post_data['shipping_last_name'] ) ),
						'address' => array(
							'address_line_1' => empty( WC()->session->post_data['shipping_address_1'] ) ? '' : wc_clean( WC()->session->post_data['shipping_address_1'] ),
							'address_line_2' => empty( WC()->session->post_data['shipping_address_2'] ) ? '' : wc_clean( WC()->session->post_data['shipping_address_2'] ),
							'admin_area_2'   => empty( WC()->session->post_data['shipping_city'] ) ? '' : wc_clean( WC()->session->post_data['shipping_city'] ),
							'admin_area_1'   => empty( WC()->session->post_data['shipping_state'] ) ? '' : wc_clean( WC()->session->post_data['shipping_state'] ),
							'postal_code'    => empty( WC()->session->post_data['shipping_postcode'] ) ? '' : wc_clean( WC()->session->post_data['shipping_postcode'] ),
							'country_code'   => empty( WC()->session->post_data['shipping_country'] ) ? '' : wc_clean( WC()->session->post_data['shipping_country'] ),
							//'SHIPTOPHONENUM'    =>  empty(WC()->session->post_data['billing_phone'])        ? '' : wc_clean(WC()->session->post_data['billing_phone']),
							//'NOTETEXT'          =>  empty(WC()->session->post_data['order_comments'])       ? '' : wc_clean(WC()->session->post_data['order_comments']),
							//'PAYMENTREQUESTID'  =>  $args['order_id'],
						),

					), $args['order_id']);

			$eh_paypal_express_options = get_option( 'woocommerce_eh_paypal_express_settings' );
			$need_shipping             = $eh_paypal_express_options['smart_button_send_shipping'];
			if ( (( 'yes' ===  $need_shipping) && ( isset( WC()->session->post_data['ship_to_different_address'] ) ) && ( 1 == WC()->session->post_data['ship_to_different_address'] )) ||  true === apply_filters("wt_force_send_shipping_address", false)) {

				$this->add_shipping_details($shipping_address );
			} else {

				$this->add_shipping_details(
					array(

						'name'    => array( 'full_name' => ( empty( WC()->session->post_data['billing_first_name'] ) ? ( ( ( WC()->version < '2.7.0' ) ? WC()->session->post_data['billing_first_name'] : WC()->customer->get_billing_first_name() ) . ' ' . ( ( WC()->version < '2.7.0' ) ? WC()->session->post_data['billing_last_name'] : WC()->customer->get_billing_last_name() ) ) : WC()->session->post_data['billing_first_name'] ) . ' ' . ( empty( WC()->session->post_data['billing_last_name'] ) ? '' : WC()->session->post_data['billing_last_name'] ) ),
						'address' => array(
							'address_line_1' => empty( WC()->session->post_data['billing_address_1'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_address() : WC()->customer->get_billing_address() ) : wc_clean( WC()->session->post_data['billing_address_1'] ),
							'address_line_2' => empty( WC()->session->post_data['billing_address_2'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_address_2() : WC()->customer->get_billing_address_2() ) : wc_clean( WC()->session->post_data['billing_address_2'] ),
							'admin_area_2'   => empty( WC()->session->post_data['billing_city'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_city() : WC()->customer->get_billing_city() ) : wc_clean( WC()->session->post_data['billing_city'] ),
							'admin_area_1'   => empty( WC()->session->post_data['billing_state'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_state() : WC()->customer->get_billing_state() ) : wc_clean( WC()->session->post_data['billing_state'] ),
							'postal_code'    => empty( WC()->session->post_data['billing_postcode'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_postcode() : WC()->customer->get_billing_postcode() ) : wc_clean( WC()->session->post_data['billing_postcode'] ),
							'country_code'   => empty( WC()->session->post_data['billing_country'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_country() : WC()->customer->get_billing_country() ) : wc_clean( WC()->session->post_data['billing_country'] ),
							//'SHIPTOPHONENUM'    =>  empty(WC()->session->post_data['billing_phone'])       ? (( WC()->version < '2.7.0' ) ? WC()->session->post_data['billing_phone'] : WC()->customer->get_billing_phone()) : wc_clean(WC()->session->post_data['billing_phone']),
							//'NOTETEXT'          =>  empty(WC()->session->post_data['order_comments'])      ? '' : wc_clean(WC()->session->post_data['order_comments']),
							//'PAYMENTREQUESTID'  => $args['order_id']
						),

					)
				);
			}
		}

		//to avoid adding 14 digit precision while json encoding
		ini_set( 'precision', 14 );
		ini_set( 'serialize_precision', -1 );

		$headers      = array(
			'Authorization' => 'Bearer ' . $args['access_token'],
			'Content-Type'  => 'application/json',
		);
		$this->params = apply_filters( 'wt_rest_request_params', $this->params );

		if ( apply_filters( 'wt_paypal_show_sensitive_data', false ) === true ) {
			$this->params['access_token'] = $args['access_token'];
		}

		$api_name = 'Create Order API';

		Eh_PayPal_Log::log_update( wp_json_encode( $this->params, JSON_PRETTY_PRINT ), $api_name, 'json' );
		$body = wp_json_encode( $this->query_params() );
		return $this->get_params( 'POST', $headers, $body );
	}

	public function get_order_details( $reqst ) {
		Eh_PayPal_Log::log_update( wp_json_encode( $reqst, JSON_PRETTY_PRINT ), 'Get Order Details API', 'json' );
		 $headers = array(
			 'Authorization' => 'Bearer ' . $reqst['access_token'],
			 'Content-Type'  => 'application/json',
		 );
		 return $this->get_params( 'GET', $headers );
	}

	public function update_order( $reqst, $order ) {
		$order_no = $order->get_order_number();
		$this->make_params( array( 'invoice_id' => ( ! empty( $reqst['invoice_prefix'] ) ? $reqst['invoice_prefix'] . $order_no : $order_no ) ) );
		$this->order_item_params( $order );
		 $headers = array(
			 'Authorization' => 'Bearer ' . $reqst['access_token'],
			 'Content-Type'  => 'application/json',
		 );
		 $this->get_address_details();
		  $body      = $this->query_params();
		 $req_params = $this->alter_query_params( $body );

		 //to avoid adding 14 digit precision while json encoding
		 ini_set( 'precision', 14 );
		 ini_set( 'serialize_precision', -1 );
		  Eh_PayPal_Log::log_update( wp_json_encode( $req_params, JSON_PRETTY_PRINT ), 'Update Order API', 'json' );
		 return $this->get_params( 'PATCH', $headers, wp_json_encode( $req_params ) );
	}

	public function capture_order( $reqst ) {
		Eh_PayPal_Log::log_update( wp_json_encode( $reqst, JSON_PRETTY_PRINT ), 'Captue Order API', 'json' );
		 $headers = array(
			 'Authorization' => 'Bearer ' . $reqst['access_token'],
			 'Content-Type'  => 'application/json',
		 );
		 return $this->get_params( 'POST', $headers );
	}

	public function authorize_order( $reqst ) {
		Eh_PayPal_Log::log_update( wp_json_encode( $reqst, JSON_PRETTY_PRINT ), 'Authorize Order API', 'json' );
		 $headers = array(
			 'Authorization' => 'Bearer ' . $reqst['access_token'],
			 'Content-Type'  => 'application/json',
		 );
		 return $this->get_params( 'POST', $headers );
	}

	public function make_refund_params( $args ) {
		 $headers = array(
			 'Authorization' => 'Bearer ' . $args['access_token'],
			 'Content-Type'  => 'application/json',
		 );
		 if ( isset( $args['amount'] ) ) {
			 $this->make_params(
				array(
					'invoice_number' => $args['invoice_number'],
					'note_to_payer'  => $args['note_to_payer'],
					'amount'         => array(
						'value'         => $this->make_paypal_amount( $args['amount'], $args['currency'] ),
						'currency_code' => $args['currency'],
					),
				)
			 );
		 } else {
			 $this->make_params(
				array(
					'invoice_number' => $args['invoice_number'],
					'note_to_payer'  => $args['note_to_payer'],

				)
			 );
		 }

		 if ( isset( $this->params['note_to_payer'] ) && empty( $this->params['note_to_payer'] ) ) {
			 unset( $this->params['note_to_payer'] );
		 }
		 Eh_PayPal_Log::log_update( wp_json_encode( $this->params, JSON_PRETTY_PRINT ), 'Refund Order API', 'json' );

		 //to avoid adding 14 digit precision while json encoding
		 ini_set( 'precision', 14 );
		 ini_set( 'serialize_precision', -1 );

		 return $this->get_params( 'POST', $headers, wp_json_encode( $this->params ) );
	}

	public function order_item_params( $order ) {

		$order_item = $order->get_items( array( 'line_item', 'fee' ) );
		$i          = 0;

		//gets fee total amount
		$total_fee = 0;
		$fees      = $order->get_fees();
		foreach ( $fees as $fee ) {
			$total_fee = $total_fee + $fee->get_amount();
		}

		$line_item_total_amount = 0;

		$currency = ( WC()->version < '2.7.0' ) ? $order->get_order_currency() : $order->get_currency();

		$order_id = ( WC()->version < '2.7.0' ) ? $order->id : $order->get_id();

		$wt_skip_line_items = $this->wt_skip_line_items(); // if tax enabled and when product has inclusive tax

		foreach ( $order_item as $item ) {

			$line_item_title = $item['name'];
			$desc_temp       = array();
			foreach ( $item as $key => $value ) {
				if ( strstr( $key, 'pa_' ) ) {
					$desc_temp[] = wc_attribute_label( $key ) . ' : ' . $value;
				}
			}
			$line_item_desc  = substr(implode( ', ', $desc_temp ), 0, 126);
			$line_item_quan  = $item['quantity'];
			if(0 !== $line_item_quan){
			$line_item_total = $item['line_subtotal'] / $line_item_quan;
			}
			else{
				$line_item_total = $item['line_subtotal'];
			}
			
			if ( $wt_skip_line_items ) {

				$this->add_line_items(
					array(
						'name'        => substr($line_item_title . ' x ' . $line_item_quan, 0, 126),
						'description' => $line_item_desc,
						'unit_amount' => array(
							'currency_code' => $currency,
							'value'         => $this->make_paypal_amount( $line_item_total, $currency ),
						),
						'quantity'    => $line_item_quan,
					),
					$i++
				);

			} else {

				$this->add_line_items(
					array(
						'name'        => substr($line_item_title, 0, 126),
						'description' => $line_item_desc,
						'unit_amount' => array(
							'currency_code' => $currency,
							'value'         => $this->make_paypal_amount( $line_item_total, $currency ),
						),
						'quantity'    => $line_item_quan,
					),
					$i++
				);
			}
			$total_amount           = ( $line_item_quan * $this->make_paypal_amount( $line_item_total, $currency ) );
			$line_item_total_amount = $line_item_total_amount + $total_amount;

		}

		//add line items amount and compares it with order total amount to check for any total mismatch
		$order_get_subtotal = $this->make_paypal_amount( $order->get_subtotal(), $currency );
		if ( $line_item_total_amount != $order_get_subtotal ) {
			$diff = $this->make_paypal_amount( $order_get_subtotal - $line_item_total_amount, $currency );
			if ( abs( $diff ) > 0.000001 && 0.0 !== (float) $diff ) {
				//add extra line item if there is a total mismatch
				$this->add_line_items(
					array(
						'name'        => __('Extra line item', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
						'description' => '',
						'quantity'    => 1,
						'unit_amount' => array(
							'currency_code' => $currency,
							'value'         => abs($diff),
						),
					),
					$i++
				);
			}
		}

		//handle mismatch due to rounded tax calculation
		$ship_discount_amount = 0;
		$order_tax            = 0;

		//comment this tax because it add additional tax when review page is shown
		$order_tax             = $this->make_paypal_amount( $order->get_total_tax(), $currency );
		 $order_total          = $this->make_paypal_amount( $order->get_total(), $currency );
		 $order_shipping_total = $this->make_paypal_amount( $order->get_total_shipping(), $currency );
		 $order_discount_total = $this->make_paypal_amount( $order->get_total_discount(), $currency );

		$order_items_total = $this->make_paypal_amount( ( $order_get_subtotal + $order_shipping_total + $order_tax + $total_fee ) - $order_discount_total, $currency );

		if ( $order_total != $order_items_total ) {
			if ( $order_items_total < $order_total ) {
				$order_tax += $order_total - $order_items_total;
			} else {
				$ship_discount_amount += $this->make_paypal_amount( $order_items_total - $order_total );
			}
		}

		$this->add_amount_breakdown(
			array(
				'currency_code' => $currency,
				'value'         => $this->make_paypal_amount( $order_total, $currency ),
				'breakdown'     => array(
					'item_total'        => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $order_get_subtotal, $currency ),
					),
					'shipping'          => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $order_shipping_total, $currency ),
					),
					'tax_total'         => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $order_tax, $currency ),
					),
					'shipping_discount' => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $ship_discount_amount, $currency ),
					),
					'discount'          => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $order_discount_total, $currency ),
					),
					'handling'          => array(
						'currency_code' => $currency,
						'value'         => $this->make_paypal_amount( $total_fee, $currency ),
					),
				),
			)
		);

	}

	public function get_address_details() {
		$eh_paypal_express_options = get_option( 'woocommerce_eh_paypal_express_settings' );
		$need_shipping             = $eh_paypal_express_options['smart_button_send_shipping'];
		if ( ( 'yes' === $need_shipping ) && ( isset( WC()->session->post_data['ship_to_different_address'] ) ) && ( 1 == WC()->session->post_data['ship_to_different_address'] ) ) {

			$this->add_shipping_details(
				array(
					'name'    => array( 'full_name' => ( empty( WC()->session->post_data['shipping_first_name'] ) ? '' : WC()->session->post_data['shipping_first_name'] ) . ' ' . ( empty( WC()->session->post_data['shipping_last_name'] ) ? '' : WC()->session->post_data['shipping_last_name'] ) ),
					'address' => array(
						'address_line_1' => empty( WC()->session->post_data['shipping_address_1'] ) ? '' : wc_clean( WC()->session->post_data['shipping_address_1'] ),
						'address_line_2' => empty( WC()->session->post_data['shipping_address_2'] ) ? '' : wc_clean( WC()->session->post_data['shipping_address_2'] ),
						'admin_area_2'   => empty( WC()->session->post_data['shipping_city'] ) ? '' : wc_clean( WC()->session->post_data['shipping_city'] ),
						'admin_area_1'   => empty( WC()->session->post_data['shipping_state'] ) ? '' : wc_clean( WC()->session->post_data['shipping_state'] ),
						'postal_code'    => empty( WC()->session->post_data['shipping_postcode'] ) ? '' : wc_clean( WC()->session->post_data['shipping_postcode'] ),
						'country_code'   => empty( WC()->session->post_data['shipping_country'] ) ? '' : wc_clean( WC()->session->post_data['shipping_country'] ),

					),

				)
			);

		} else {

			$this->add_shipping_details(
				array(

					'name'    => array( 'full_name' => ( empty( WC()->session->post_data['billing_first_name'] ) ? ( ( ( WC()->version < '2.7.0' ) ? WC()->session->post_data['billing_first_name'] : WC()->customer->get_billing_first_name() ) . ' ' . ( ( WC()->version < '2.7.0' ) ? WC()->session->post_data['billing_last_name'] : WC()->customer->get_billing_last_name() ) ) : WC()->session->post_data['billing_first_name'] ) . ' ' . ( empty( WC()->session->post_data['billing_last_name'] ) ? '' : WC()->session->post_data['billing_last_name'] ) ),
					'address' => array(
						'address_line_1' => empty( WC()->session->post_data['billing_address_1'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_address() : WC()->customer->get_billing_address() ) : wc_clean( WC()->session->post_data['billing_address_1'] ),
						'address_line_2' => empty( WC()->session->post_data['billing_address_2'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_address_2() : WC()->customer->get_billing_address_2() ) : wc_clean( WC()->session->post_data['billing_address_2'] ),
						'admin_area_2'   => empty( WC()->session->post_data['billing_city'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_city() : WC()->customer->get_billing_city() ) : wc_clean( WC()->session->post_data['billing_city'] ),
						'admin_area_1'   => empty( WC()->session->post_data['billing_state'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_state() : WC()->customer->get_billing_state() ) : wc_clean( WC()->session->post_data['billing_state'] ),
						'postal_code'    => empty( WC()->session->post_data['billing_postcode'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_postcode() : WC()->customer->get_billing_postcode() ) : wc_clean( WC()->session->post_data['billing_postcode'] ),
						'country_code'   => empty( WC()->session->post_data['billing_country'] ) ? ( ( WC()->version < '2.7.0' ) ? WC()->customer->get_country() : WC()->customer->get_billing_country() ) : wc_clean( WC()->session->post_data['billing_country'] ),

					),

				)
			);

		}
	}

	public function alter_query_params( $rqst ) {
		$req_params = array();
		$index      = 0;

		if ( isset( $rqst['invoice_id'] ) ) {
			if ( isset( WC()->session->invoice_id_exist ) && WC()->session->invoice_id_exist == 'no' ) {
				$req_params[ $index ]['op'] = 'add';
			} else {
				$req_params[ $index ]['op'] = 'replace';
			}
			$req_params[ $index ]['path']  = "/purchase_units/@reference_id=='default'/invoice_id";
			$req_params[ $index ]['value'] = $rqst['invoice_id'];
			$index++;
		}
		if ( isset( $rqst['purchase_units'][0]['amount'] ) ) {
			$req_params[ $index ]['op']    = 'replace';
			$req_params[ $index ]['path']  = "/purchase_units/@reference_id=='default'/amount";
			$req_params[ $index ]['value'] = $rqst['purchase_units'][0]['amount'];
			$index++;
		}
		if ( isset( $rqst['purchase_units'][0]['shipping']['name'] ) ) {
			$req_params[ $index ]['op']    = 'replace';
			$req_params[ $index ]['path']  = "/purchase_units/@reference_id=='default'/shipping/name";
			$req_params[ $index ]['value'] = $rqst['purchase_units'][0]['shipping']['name'];
			$index++;
		}
		if ( isset( $rqst['purchase_units'][0]['shipping']['address'] ) ) {
			$req_params[ $index ]['op']    = 'replace';
			$req_params[ $index ]['path']  = "/purchase_units/@reference_id=='default'/shipping/address";
			$req_params[ $index ]['value'] = $rqst['purchase_units'][0]['shipping']['address'];
			$index++;
		}

		return $req_params;
	}

	public function make_paypal_amount( $amount, $currency = '' ) {
		$currency = empty( $currency ) ? $this->store_currency : $currency;
		if ( in_array( $currency, $this->supported_decimal_currencies ) ) {
			return abs(round( (float) $amount, 0 ));
		} else {
			return abs(round( (float) $amount, 2 ));
		}
	}

	public function wt_skip_line_items() {
		return ( 'yes' === get_option( 'woocommerce_calc_taxes' ) && 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
	}

	public function add_line_items( $items, $count ) {

		$this->params['purchase_units'][0]['items'][ $count ] = $items;

	}

	public function add_amount_breakdown( $items ) {

		$this->params['purchase_units'][0]['amount'] = $items;

	}

	public function add_shipping_details( $items ) {
		//remove address offset if any of the addres fields are empty
		$flag_unset_address = false;
		if ( isset( $items['address'] ) ) {
			foreach ( $items['address'] as $key => $val ) {
				if ( empty( $items['address']['country_code'] ) || empty( $items['address']['postal_code'] ) ) {
					$flag_unset_address = true;
					break;
				}
			}

			if ( $flag_unset_address ) {
				 unset( $items['address'] );
			}
		}

		$this->params['purchase_units'][0]['shipping'] = $items;
	}

	public function query_params() {
		foreach ( $this->params as $key => $value ) {
			if ( '' === $value || is_null( $value ) ) {
				unset( $this->params[ $key ] );
			}
		}

		if ( isset( $this->params['client_id'] ) ) {
			 unset( $this->params['client_id'] );
		}
		if ( isset( $this->params['client_secret'] ) ) {
			unset( $this->params['client_secret'] );
		}
		return $this->params;
	}

	public function get_params( $method, $header, $body = null ) {

		$args = array(
			'method'      => $method,
			'timeout'     => 120,
			'redirection' => 0,
			'httpversion' => $this->http_version,
			'sslverify'   => false,
			'blocking'    => true,
			'user-agent'  => 'EH_PAYPAL_EXPRESS_CHECKOUT',
			'headers'     => $header,
			'body'        => $body,
			'cookies'     => array(),
		);
        $args = apply_filters("wt_paypal_http_request", $args);        				
		return $args;
	}

	public function get_token($obj) {

        $environment = (isset($_REQUEST['woocommerce_eh_paypal_express_smart_button_environment']) && !empty($_REQUEST['woocommerce_eh_paypal_express_smart_button_environment'])) ? $_REQUEST['woocommerce_eh_paypal_express_smart_button_environment'] : $obj->smart_button_environment; 

        if("live" === $environment) {
	        $client_id = ( isset($_REQUEST['woocommerce_eh_paypal_express_live_client_id'])) ? $_REQUEST['woocommerce_eh_paypal_express_live_client_id'] : $this->params['client_id'];
	        
	        $client_secret = ( isset($_REQUEST['woocommerce_eh_paypal_express_live_client_secret'])) ? $_REQUEST['woocommerce_eh_paypal_express_live_client_secret'] : $this->params['client_secret'] ;	

        } 
        else{
	        $client_id = ( isset($_REQUEST['woocommerce_eh_paypal_express_sandbox_client_id'])) ? $_REQUEST['woocommerce_eh_paypal_express_sandbox_client_id'] : $this->params['client_id'];
	        
	        $client_secret = ( isset($_REQUEST['woocommerce_eh_paypal_express_sandbox_client_secret'])) ? $_REQUEST['woocommerce_eh_paypal_express_sandbox_client_secret'] : $this->params['client_secret'] ;	
        }						
       $arr_credentials = array($client_id, $client_secret);
       Eh_PayPal_Log::log_update(json_encode($arr_credentials, JSON_PRETTY_PRINT),'Credentials', 'json');

		$encoded_value = base64_encode( $client_id . ':' . $client_secret );
		$header         = array( 'Authorization' => 'Basic ' . $encoded_value );
		$body           = http_build_query( array( 'grant_type' => 'client_credentials' ) );
		return $this->get_params( 'POST', $header, $body );
	}

}
