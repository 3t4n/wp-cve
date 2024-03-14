<?php

namespace PargoWp\Includes;
use Exception;
use JsonException;
use WC_Order;

use PargoWp\PargoAdmin\Pargo_Admin_API;

// Extending Admin API instead of having to init the API class on each method
class Pargo_Orders extends Pargo_Admin_API
{
    private const SOURCE_WOOCOMMERCE = "woocommerce";
    public const PROCESS_TYPE_W2D = "W2D";
    public const PROCESS_TYPE_W2P = "W2P";

    /**
     * @param          $order WC_Order
     * @param  string  $processType
     * @return false|string|void
     */
    final public function placeOrder(WC_Order $order, string $processType)
    {
        if (!in_array($processType, [self::PROCESS_TYPE_W2D, self::PROCESS_TYPE_W2P])) {
            error_log('Only process types of W2D and W2P are supported');
            return '';
        }
        $chosen_shipping = '';
        // check if pargo is the chosen shipping method
        if ($order->has_shipping_method('wp_pargo')) {
            $chosen_shipping = 'wp_pargo';
        }
        if ($order->has_shipping_method('wp_pargo_home')) {
            $chosen_shipping = 'wp_pargo_home';
        }
        if ( !in_array($chosen_shipping, ['wp_pargo', 'wp_pargo_home'])) {
            return false;
        }

        if ($chosen_shipping === 'wp_pargo') {
            // if no pickup point is set then do not post order
            $pup_code = $order->get_meta('pargo_pc');
            if (empty($pup_code)) {
                $note = __("Pargo pickup point is invalid value, is not set or null.");
                $order->add_order_note($note);
                $order->save();
                return;
            }
        }

        return $this->doOrderRequest($order, $processType);
    }

    /**
     * @param  string  $responseData
     * @param          $order
     * @return string
     */
    private function handlePargoOrderApiError(string $responseData, $order): string
    {
        $error_message = $responseData->get_error_message();
        $note = __("Pargo Order failed to submit: check config ".$error_message);
        $order->add_order_note($note);
        $order->save();

        return $note;
    }

    /**
     * @param  array   $responseData
     * @param  string  $url
     * @param          $order
     * @return string
     */
    private function handlePargoOrderApiResponse(array $responseData, string $url, $order): string
    {
        $response = wp_remote_retrieve_body($responseData);

		$body = json_decode($response);

        if (isset($body->success) && !$body->success) {
            $note = __("Pargo Order failed to submit to url: " . $url);
            $order->add_order_note($response);
            $order->add_order_note($note);
            $order->update_status('on-hold');
            $order->save();

            if (is_array($body->errors) && isset($body->errors[0])){
				if ($body->errors[0]->detail && !empty($body->errors[0]->detail)) {
					$note = __( "API error response " . json_decode( $response )->errors[0]->detail );
					$order->add_order_note( $note );
					$order->update_status( 'on-hold' );
					$order->save();
				}
            }
        } else {
            $waybill = $body->data->attributes->orderData->trackingCode;
            $labelUrl = $body->data->attributes->orderData->orderLabel;

            update_post_meta($order->get_id(), 'pargo_waybill', ['waybill' => $waybill, 'label' => $labelUrl]);
            $order->update_meta_data('pargo_waybill', $waybill);

            update_post_meta($order->get_id(), 'pargo_order_sent', 'yes');
            $order->update_meta_data('pargo_label', $labelUrl);

            // set session to check for duplicate order creates
            if (isset(WC()->session)) {
                WC()->session->set("pargo_" . $order->get_id(), 'sent');
            }

            $note = __("Pargo Order created - waybill: ".$waybill);
            $order->add_order_note($note);
            $order->save();
        }

        unset($_GET['ship-now']);

        return $response;
    }

	private function get_phone_numbers(WC_Order $order) {
		$phone_numbers = [];
		$shipping_phone = $order->get_data()['shipping']['phone'];
		$billing_phone = $order->get_data()['billing']['phone'];
		if (!empty($billing_phone)) {
			$phone_numbers[] = $billing_phone;
		}
		if ($shipping_phone !== $billing_phone && !empty($shipping_phone)) {
			$phone_numbers[] = $shipping_phone;
		}
		return $phone_numbers;
	}

	private function get_supplier_id() {
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		return $pargo_shipping_method->get_option('pargo_supplier_id');
	}

    /**
     * @param $order WC_Order
     * @return false|string
     * @throws JsonException
     */
    private function getW2pData(WC_Order $order): string
    {
        $pup_code = $order->get_meta('pargo_pc');
	    $phone_numbers = $this->get_phone_numbers($order);
	    $supplier_id = $this->get_supplier_id();
        $data = [
            "source" => self::SOURCE_WOOCOMMERCE,
            "source_channel" => self::SOURCE_WOOCOMMERCE,
            "data" => [
                "type" => self::PROCESS_TYPE_W2P,
                "version" => 1,
                "attributes" => [
                    "warehouseAddressCode" => null,
                    "returnAddressCode" => null,
                    "trackingCode" => null,
                    "externalReference" => (string) $order->get_id(),
                    "pickupPointCode" => $pup_code,
                    "consignee" => [
                        "firstName" => $order->get_data()['billing']['first_name'],
                        "lastName" => $order->get_data()['billing']['last_name'],
                        "email" => $order->get_data()['billing']['email'],
                        "phoneNumbers" => $phone_numbers,
                        "address1" => $order->get_data()['billing']['address_1'],
                        "address2" => $order->get_data()['billing']['address_2'],
                        "suburb" => $order->get_meta('_billing_suburb'),
                        "province" => $order->get_data()['shipping']['state'],
                        "postalCode" => $order->get_data()['billing']['postcode'],
                        "city" => $order->get_data()['billing']['city'],
                        'country' => $order->get_data()['billing']['country'],
                    ],
                ],
            ],
        ];

		if (!empty($supplier_id)) {
			$data['data']['attributes']['owner'] = $supplier_id;
		}
		// unset the consignee phone number if it is empty
		if (empty($data['data']['attributes']['consignee']['phoneNumbers'])) {
			unset($data['data']['attributes']['consignee']['phoneNumbers']);
		}
        if ( $order->get_payment_method() === 'cod' ) {
            $data['data']['attributes']['paymentOnCollectionAmount'] = $order->get_total();
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $order WC_Order
     * @return false|string
     * @throws JsonException
     */
    private function getW2dData(WC_Order $order): string
    {
        $phone_numbers = $this->get_phone_numbers($order);
	    $supplier_id = $this->get_supplier_id();
        $data = [
            "source" => self::SOURCE_WOOCOMMERCE,
            "source_channel" => self::SOURCE_WOOCOMMERCE,
            "data" => [
                "type" => self::PROCESS_TYPE_W2D,
                "version" => 1,
                "attributes" => [
                    "warehouseAddressCode" => null,
                    "returnAddressCode" => null,
                    "trackingCode" => null,
                    "externalReference" => (string) $order->get_id(),
                    "consignee" => [
                        "firstName" => $order->get_data()['shipping']['first_name'],
                        "lastName" => $order->get_data()['shipping']['last_name'],
                        "email" => $order->get_data()['billing']['email'],
                        "phoneNumbers" => $phone_numbers,
                        "address1" => $order->get_data()['shipping']['address_1'],
                        "address2" => $order->get_data()['shipping']['address_2'],
                        "suburb" => $order->get_meta('_shipping_suburb'),
                        "province" => $order->get_data()['shipping']['state'],
                        "postalCode" => $order->get_data()['shipping']['postcode'],
                        "city" => $order->get_data()['shipping']['city'],
                        "country" => $order->get_data()['shipping']['country'],
                    ],
                ],
            ],
        ];

	    if (!empty($supplier_id)) {
		    $data['data']['attributes']['owner'] = $supplier_id;
	    }
		// unset the consignee phone numbers if it is empty
	    if (empty($data['data']['attributes']['consignee']['phoneNumbers'])) {
		    unset($data['data']['attributes']['consignee']['phoneNumbers']);
	    }
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param          $order WC_Order
     * @param string $processType
     * @return string
     * @throws \JsonException
     */
    private function doOrderRequest(WC_Order $order, string $processType): string
    {
		// If the user has disabled backend shipping return an empty string
	    if ($this->backend_shipping_disabled($order)) {
		    return '';
	    }
        try {
            if ($processType == self::PROCESS_TYPE_W2P) {
                $order_data = $this->getW2pData($order);
            }
            if ($processType == self::PROCESS_TYPE_W2D) {
                $order_data = $this->getW2dData($order);
            }
        } catch (JsonException $e) {
            error_log($e);
            return '';
        }

        $apiUrl = $this->get_api_url();
        $url = $apiUrl . 'orders';

        // Get an Authentication token
        $accessToken = $this->get_auth_token();

        $headers = array(
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
            'cache-control' => 'no-cache'
        );

        $pargo_order_sent = get_post_meta($order->get_id(), 'pargo_order_sent', true);

		if ($processType === self::PROCESS_TYPE_W2P) {
			Analytics::submit( 'customer', 'click', 'create_order_w2p' );
		}
	    if ($processType === self::PROCESS_TYPE_W2D) {
		    Analytics::submit( 'customer', 'click', 'create_order_w2d' );
	    }

        if (!empty($pargo_order_sent)) {
            return '';
        }

        $responseData = $this->post_api($url, $order_data, $headers);

        if (is_wp_error($responseData)) {
            return $this->handlePargoOrderApiError($responseData, $order);
        } else {
            return $this->handlePargoOrderApiResponse($responseData, $url, $order);
        }
    }

	private function backend_shipping_disabled(WC_Order $order) {
		if ($order->has_shipping_method('wp_pargo')) {
			$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
			$backend_shipping = $pargo_shipping_method->get_option('use_backend_shipping');
		}
		if ($order->has_shipping_method('wp_pargo_home')) {
			$pargo_shipping_method = new Pargo_Wp_Shipping_Method_Home_Delivery();
			$backend_shipping = $pargo_shipping_method->get_option('home_use_backend_shipping');
		}

		if (isset($backend_shipping) && $backend_shipping === 'no') {
			return true;
		}
		return false;
	}

	/**
	 * Check to see if the user has disabled backend shipping in the shipping zone
	 * @param WC_Order $order
	 *
	 * @return bool
	 */
	private function backend_shipping_zone_disabled(WC_Order $order) {
		$instance_id = null;
		$order_shipping = $order->get_items('shipping');
		$shipping = reset($order_shipping);

		if (isset($shipping)) {
			$instance_id = $shipping->get_instance_id();
		}

		if (is_null($instance_id)) {
			return false;
		}

		$shipping_class_names = WC()->shipping->get_shipping_method_class_names();
		$method_instance = new $shipping_class_names[$shipping->get_method_id()]( $instance_id );
		$backend_shipping = $method_instance->get_option( 'use_backend_shipping' ); // w2p shipping option
		$home_backend_shipping = $method_instance->get_option ('home_use_backend_shipping'); // w2d shipping option
		if (isset($backend_shipping) && $backend_shipping === 'no' || isset($home_backend_shipping) && $home_backend_shipping === 'no') {
			return true;
		}
		return false;
	}
}
