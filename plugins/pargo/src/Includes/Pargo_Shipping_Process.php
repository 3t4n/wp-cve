<?php

namespace PargoWp\Includes;

use PargoWp\PargoAdmin\Pargo_Admin_API;

class Pargo_Shipping_Process
{
	protected $plugin_version;
	private $plugin_name;

	public function __construct($plugin_name, $plugin_version)
	{
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
	}

    public function setShippingZip()
    {
        global $woocommerce;
        $state = null;
        if (isset(WC()->session->get('pargo_shipping_address')['province'])) {
            switch (WC()->session->get('pargo_shipping_address')['province']) {
                case 'Western Cape':
                    $state = 'WC';
                    break;
                case 'Northern Cape':
                    $state = 'NC';
                    break;
                case 'Eastern Cape':
                    $state = 'EC';
                    break;
                case 'Gauteng':
                    $state = 'GP';
                    break;
                case 'North West':
                    $state = 'NW';
                    break;
                case 'Mpumalanga':
                    $state = 'MP';
                    break;
                case 'Free State':
                    $state = 'FS';
                    break;
                case 'Limpopo':
                    $state = 'LP';
                    break;
                case 'KwaZulu-Natal':
                    $state = 'KZN';
                    break;

                default:
                    $state = null;
                    break;
            }
        }

        //set it
        if (isset(WC()->session->get('pargo_shipping_address')['address1'])) {
            $woocommerce->customer->set_shipping_address(WC()->session->get('pargo_shipping_address')['address1']);
        }
        if (isset(WC()->session->get('pargo_shipping_address')['address2'])) {
            $woocommerce->customer->set_shipping_address_2(WC()->session->get('pargo_shipping_address')['address2']);
        }
        if (isset(WC()->session->get('pargo_shipping_address')['city'])) {
            $woocommerce->customer->set_shipping_city(WC()->session->get('pargo_shipping_address')['city']);
        }
        if (isset(WC()->session->get('pargo_shipping_address')['province'])) {
            $woocommerce->customer->set_shipping_state(WC()->session->get('pargo_shipping_address')['province']);
        }
        if (!is_null($state)) {
            $woocommerce->customer->set_shipping_state($state);
        }

        if (isset(WC()->session->get('pargo_shipping_address')['storeName'])) {
            $woocommerce->customer->set_shipping_company(WC()->session->get('pargo_shipping_address')['storeName'] . ' (' . WC()->session->get('pargo_shipping_address')['pargoPointCode'] . ')');
        }
        if (isset(WC()->session->get('pargo_shipping_address')['postalcode'])) {
            $woocommerce->customer->set_shipping_postcode(WC()->session->get('pargo_shipping_address')['postalcode']);
        }
    }

    /**
     * @return array|string
     */
    public function getRenderPickupPoints()
    {
        if ($this->customerAddressAvailable()) {
            $custAddr = $this->getCustomerFullAddress();
            $pargoMaps = new Wp_Pargo_Map();

            $response = $pargoMaps->getClosestPups(3, $pargoMaps);

            return $response;
        } else {
            return "Customer address not found";
        }
    }

    /**
     * @return bool
     */
    public function customerAddressAvailable()
    {
        if (WC()->customer->get_billing_address()) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getCustomerFullAddress()
    {
        $customerAddress = WC()->customer->get_billing_address();
        $customerAddress1 = WC()->customer->get_billing_address_1();
        $customerAddress2 = WC()->customer->get_billing_address_2();
        $customerCity = WC()->customer->get_billing_city();
        $customerCountry = WC()->customer->get_billing_country();

        $custFullAddress = $customerAddress;
        $custFullAddress .= " " . $customerAddress1;
        $custFullAddress .= " " . $customerAddress2;
        $custFullAddress .= ", " . $customerCity;
        $custFullAddress .= ", " . $customerCountry;

        return $custFullAddress;
    }

	public function check_pup_code($code)
	{
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		$pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
		$pargo_admin_api = new Pargo_Admin_API($this->plugin_name, $this->plugin_version);
		$pargo_url = $pargo_admin_api::PARGO_API_ENDPOINTS[$pargo_url_endpoint];
		$url = trailingslashit($pargo_url) . "pickup_points?pickupPointCode=" . $code;
		$accessToken = $pargo_admin_api->get_auth_token();
		$headers = array(
			'Authorization' => "Bearer $accessToken",
			'Content-Type' => 'application/json',
			'cache-control' => 'no-cache'
		);
		$response = $pargo_admin_api->post_api($url, "", $headers, "GET");

		if (!is_wp_error($response)) {
			$response = wp_remote_retrieve_body($response);
			try {
				$response = json_decode($response, true);
				if (!empty($response["data"])) {
					return true;
				}
			} catch (\Exception $e) {
				error_log("Could not decode response from Pargo API:" . $e);
			}
		}
		return false;
	}

    /**
     * @param $label
     * @param $method
     * @return mixed|string
     */
    public function wcPargoLabelChange($label, $method)
    {
        $chosen_methods = WC()->session->get('chosen_shipping_methods');

        if ($method->method_id != 'wp_pargo') {
            return $label;
        }

        if ($chosen_methods[0] != 'wp_pargo') {
            $label = $label;
        } else {
            if ($method->method_id == 'wp_pargo') {
                //get the backend settings
                $readyPargoSettings = new Pargo_Wp_Shipping_Method();

                $pargoSettings = $readyPargoSettings->getPargoSettings();
                $pargoMerchantUserToken = $pargoSettings['pargo_map_token'];
                $pargo_button_caption = $pargoSettings['pargo_buttoncaption'];
                $pargoButtonCaptionAfter = $pargoSettings['pargo_buttoncaption_after'];
                $pargo_map_is_production = $pargoSettings['pargo_url_endpoint'] === 'production';
                $pargo_map_display = $pargoSettings['pargo_map_display'];

                WC()->shipping->calculate_shipping(WC()->shipping->packages);
                $image = null;
                $storeName = null;
                $storeAddress = null;
                $businessHours = null;

	            $pargo_shipping_address = null;
				if ( WC()->session->get( 'pargo_shipping_address' ) !== null ) {
					 $pargo_shipping_address = json_decode(stripslashes(WC()->session->get('pargo_shipping_address')), true);
				}

                $action = "mountPargoApp('')";
                if ($pargo_map_display == 'no') {
                    $action = "mountPargoApp('modal')";
                }
                //button
                return $label . "<div>
                        <div id=\"pargo-modal\"></div>
                        <button type=\"button\" class=\"pargo_style_button\" onclick=\"" . $action . "\">Select a Pickup Point</button>
                    </div>";
            }
        }
        return $label;
    }

    /**
     * @param $posted
     */
    public function pargoValidateOrders($posted)
    {
        $packages = WC()->shipping->get_packages();
        $chosen_methods = WC()->session->get('chosen_shipping_methods');
        if (is_array($chosen_methods) && in_array('wp_pargo', $chosen_methods)) {
            foreach ($packages as $i => $package) {
                if ($chosen_methods[$i] != "wp_pargo") {
                    continue;
                }

                $Pargo_Shipping_Method = new Pargo_Wp_Shipping_Method();
                $weightLimit = (int)$Pargo_Shipping_Method->settings['weight'];
                $weight = 0;

                foreach ($package['contents'] as $item_id => $values) {
                    $_product = $values['data'];
                    $weight = $_product->get_weight();
                }

                $weight = wc_get_weight($weight, 'kg');

                if ($weight > $weightLimit) {
                    $message = sprintf(__(
                        'Sorry, something in your cart of %d kg exceeds the maximum weight of %d kg allowed for %s',
                        'woocommerce'
                    ), $weight, $weightLimit, $Pargo_Shipping_Method->title);
                    $messageType = "error";

                    if (!wc_has_notice($message, $messageType)) {
                        wc_add_notice($message, $messageType);
                    }
                }
            }
        }
    }
}
