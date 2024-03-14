<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 6:57 PM
 */
namespace Hfd\Woocommerce\Helper\Hfd;

use Hfd\Woocommerce\Container;

class Api
{
    /**
     * @var \Hfd\Woocommerce\Setting
     */
    protected $setting;

    public function __construct()
    {
        $this->setting = Container::get('Hfd\Woocommerce\Setting');
    }

    /**
     * @param \WC_Order $order
     * @return bool
     */
    public function syncOrder($order)
    {
        $orderNumber = $order->get_id();

        list($param3, $param7) = $this->_paramBaseOnShippingMethod($order);
        $userName = __('Guest', 'hfd-integration');

        $street = $aparment = $floor = $entrance = $houseNumber = '';

        if ($order->get_shipping_address_1()) {
            $street .= $order->get_shipping_address_1();
        }

        if ($order->get_shipping_address_2()) {
            $street .= ' ' . $order->get_shipping_address_2();
        }

        if ($order->get_shipping_first_name() || $order->get_shipping_last_name()) {
            $userName = $order->get_shipping_first_name() .' '. $order->get_shipping_last_name();
        }
		
		$user_phone = $order->get_billing_phone() ? $order->get_billing_phone() : '';
		/* //take shipping phone if exist
		if( $order->get_shipping_phone() != "" ){
			$user_phone = $order->get_shipping_phone();
		} */
        $pParam = array(
            $this->getCustomerNumber(),     // <param1> = customer number
            $this->_getParam2($order),                                    // <param2>= מסירה
            $param3,        // <param3>= value depends on the shipping method user selected. If the user selected shipping method  = Epost the value need to send is 50 If the user selected shipping method = “Baldar”(its not our module) or “free shipping” value need to send is 35 We will not send to the API orders with shipping method table rates (pickup). Need to keep the original module configurations fields in the backend
            '',             // <param4>= leave empty
            $this->getSenderName(),         // <param5>= send alsways the value = betanet
            '',             // <param6>=  leave empty
            $param7,        // <param7>= If the user selected shipping method  = Epost the value need to send is 11. If the user selected shipping method = “Baldar”(its not our module) or “free shipping” value need to send is 10
            $this->_getParam8($order),             // <param8>=  leave empty
            '',             // <param9>=  leave empty
            '',             // <param10>=  leave empty
            $userName,      // <param11>= this is the user name -taken from the shipping address
            '',             // <param12>= leave empty
            $order->get_shipping_city() ? $order->get_shipping_city() : '',     // <param13>= shipping city
            '',             // <param14>=  leave empty
            $street,    // <param15>= shipping street
            $houseNumber,    // <param16>= shipping  house number
            $entrance,    // <param17>= shipping  entrence number
            $floor,    // <param18>= shipping  floor number
            $aparment,    // <param19>= shipping apartment number
            $user_phone,   // <param20>= shipping telephone number
            '',             // <param21>=  leave empty
            $orderNumber,   // <param22>=  order number
            1,              // <param23>=  send always the value 1
            '',             // <param24>=  address comment
            $order->get_customer_note(),        // <param25>= order comment
            $orderNumber,   // <param26>=  leave empty
            '',             // <param27>=  leave empty
            '',             // <param28>=  leave empty
            '',             // <param29>=  leave empty
            $this->_getParam30($order),             // <param30>=  leave empty
            $this->_getParam31($order),             // <param31>=  leave empty
            $this->_getParam32($order),             // <param32>=  leave empty
            $this->_getParam33($order),             // <param33>=  leave empty
            '',             // <param34>=  leave empty
            $this->_getParam35($order),         // <param35>= only if shipping method is Epost, need to send here value of Epost branch code, otherwise leave empty
            'XML',          // <param36>=  send value = XML
            '',             // <param37>=  leave empty
            '',             // <param38>=  leave empty
            '',             // <param39>=  leave empty
            $order->get_billing_email(),        // <param40>=  customer email
            '',             // <param41>=  leave empty
            '',             // <param42>=  leave empty
        );
		$pParam = apply_filters( 'hfd_before_sync', $pParam );
        $url = $this->getServiceUrl();
//        echo '<pre>';
//        print_r($pParam);
//        exit;
        foreach ($pParam as $key => $value) {
            $index = $key + 1;
            $value = str_replace(',', '-', $value);
            $url = str_replace("<param{$index}>", urlencode($value), $url);
        }
		
        $result = array(
            'error'     => false,
            'message'   => ''
        );
		
        $setting = Container::get('Hfd\Woocommerce\Setting');
        $authToken = $setting->get('betanet_epost_hfd_auth_token');
        try {
			$args = array(
				'timeout' => 15,
				'user-agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
			);
			// add bearer token into request
            if( $authToken ){
				$args['headers'] = array( 'Authorization' => 'Bearer '.$authToken );
			}
			
			$response = wp_remote_get( $url, $args );

            if( is_wp_error( $response ) ){
                throw new \Exception('Fail to connect API');
            }
			
			$response = wp_remote_retrieve_body( $response );
            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            $arrResponse = json_decode(json_encode($xml), true);

            if ($this->isApiDebug()) {
                $_response = $arrResponse;
                if (is_array($_response)) {
                    $_response = json_encode($_response);
                }
                $_logInfo = PHP_EOL .'===== Begin =====';
                $_logInfo .= PHP_EOL . '> Request parameters: '. json_encode($pParam);
                $_logInfo .= PHP_EOL .'> Call API: '. $url;
                $_logInfo .= PHP_EOL .'> Response: '. $_response;
                $_logInfo .= PHP_EOL .'====== End ======';
                $filesystem = Container::get('Hfd\Woocommerce\Filesystem');
                $filesystem->writeLog($_logInfo, 'HFD');
            }

            $isSuccess = boolval($arrResponse['mydata']['answer']['ship_create_num']);
            if (!$isSuccess) {
                $result['error'] = true;
                $result['message'] = $arrResponse['mydata']['answer']['ship_create_error'];
            } else {
                $result['number'] = $arrResponse['mydata']['answer']['ship_create_num'];
                $result['rand_number'] = $arrResponse['mydata']['answer']['ship_num_rand'];
            }
            return $result;
        } catch (\Exception $e) {
            $result['error'] = true;
            $result['message'] = $e->getMessage();

            return $result;
        }
    }

    protected function _orderAddressParser($address)
    {
        if (is_array($address)) {
            $address = $address[0];
        }
        $apartment = 'דירה';
        $floor = 'קומה';
        $entrance = 'כניסה';
        $parser = preg_split('/(?<=\D)(?=\d)|\d+\K/', $address);
        if (!$parser) {
            $res['street'] = $address;
        }
        $parser = array_map('trim', $parser);
        $res['street'] = $parser[0];
        $res['number'] = isset($parser[1]) ? $parser[1] : '';
        if (in_array($apartment, $parser)) {
            $key = array_search($apartment, $parser);
            $res['apartment'] = isset($parser[$key+1]) ? $parser[$key+1] : '';
        }
        if (in_array($floor, $parser)) {
            $key = array_search($floor, $parser);
            $res['floor'] = isset($parser[$key+1]) ? $parser[$key+1] : '';
        }
        if (in_array($entrance, $parser)) {
            $key = array_search($entrance, $parser);
            $res['entrance'] = isset($parser[$key+1]) ? $parser[$key+1] : '';
        }
        return $res;
    }

    /**
     * @param \WC_Order $order
     * @return string
     */
    protected function _getShippingMethod($order)
    {
        $shippingMethod = '';
        /* @var \WC_Order_Item_Shipping $method */
        foreach ($order->get_shipping_methods() as $method) {
            $shippingMethod = $method->get_method_id();
            if (substr($shippingMethod, 0, strlen(\Hfd\Woocommerce\Shipping\Epost::METHOD_ID)) == \Hfd\Woocommerce\Shipping\Epost::METHOD_ID) {
                $shippingMethod = \Hfd\Woocommerce\Shipping\Epost::METHOD_ID;
                break;
            }
			
			if (substr($shippingMethod, 0, strlen(\Hfd\Woocommerce\Shipping\Govina::METHOD_ID)) == \Hfd\Woocommerce\Shipping\Govina::METHOD_ID) {
                $shippingMethod = \Hfd\Woocommerce\Shipping\Govina::METHOD_ID;
                break;
            }
			
			if (substr($shippingMethod, 0, strlen(\Hfd\Woocommerce\Shipping\Home_Delivery::METHOD_ID)) == \Hfd\Woocommerce\Shipping\Home_Delivery::METHOD_ID) {
                $shippingMethod = \Hfd\Woocommerce\Shipping\Home_Delivery::METHOD_ID;
                break;
            }
			
            if (substr($shippingMethod, 0, strlen('free_shipping')) == 'free_shipping') {
                $shippingMethod = 'free_shipping';
                break;
            }
        }
        return $shippingMethod;
    }

    /**
     * @param \WC_Order $order
     * @return array
     */
    protected function _paramBaseOnShippingMethod($order)
    {
        $shippingMethod = $this->_getShippingMethod($order);
        switch ($shippingMethod) {
            case \Hfd\Woocommerce\Shipping\Epost::METHOD_ID:
                $result = ['50', '11'];
                break;
			case \Hfd\Woocommerce\Shipping\Govina::METHOD_ID:
                $result = ['37', '10'];
                break;
            case 'free_shipping':
            default:
                $result = ['35', '10'];
                break;
        }

        return $result;
    }

    /**
     * @param \WC_Order $order
     * @return string
     */
    protected function _getParam35($order)
    {
        $shippingMethod = $this->_getShippingMethod($order);
        if ($shippingMethod !== \Hfd\Woocommerce\Shipping\Epost::METHOD_ID) {
            return '';
        }

        try {
            /* @var \WC_Order_Item_Shipping $method */
            foreach ($order->get_shipping_methods() as $method) {
                if (substr($shippingMethod, 0, strlen(\Hfd\Woocommerce\Shipping\Epost::METHOD_ID)) == \Hfd\Woocommerce\Shipping\Epost::METHOD_ID) {
                    $spotInfo = $method->get_meta('epost_pickup_info');
                    $spotInfo = unserialize($spotInfo);

                    if ($spotInfo['n_code']) {
                        return $spotInfo['n_code'];
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return '';
    }
	
	public function _getParam8($order){
		$shippingMethod = $this->_getShippingMethod($order);
		if( $shippingMethod == \Hfd\Woocommerce\Shipping\Home_Delivery::METHOD_ID ){
            return 10;
        }
		return '';
	}
	
	public function _getParam31($order){
		$shippingMethod = $this->_getShippingMethod($order);
        if( $shippingMethod == \Hfd\Woocommerce\Shipping\Govina::METHOD_ID ){
            return $order->get_total();
        }
		return '';
	}
	
	public function _getParam2($order){
		$shippingMethod = $this->_getShippingMethod($order);
        if( $shippingMethod == \Hfd\Woocommerce\Shipping\Home_Delivery::METHOD_ID ){
            return 'איסוף';
        }
		return 'מסירה';
	}
	
	public function _getParam30($order){
		/* $shippingMethod = $this->_getShippingMethod($order);
        if( $shippingMethod == \Hfd\Woocommerce\Shipping\Govina::METHOD_ID ){
            $betanet_pmethod = get_post_meta( $order->get_id(), 'betanet_pmethod', true );
			if( $betanet_pmethod == "govina_cash" ){
				return 1;
			}else if( $betanet_pmethod == "govina_cheque" ){
				return 11;
			}
        } */
		return '';
	}
	
	public function _getParam32($order){
		$shippingMethod = $this->_getShippingMethod($order);
        if( $shippingMethod == \Hfd\Woocommerce\Shipping\Govina::METHOD_ID ){
			$order_date = $order->get_date_paid();
			if( empty( $order_date ) ){
				$order_date = $order->get_date_created();
			}
            return date( 'd/m/Y', strtotime( $order_date ) );
        }
		return '';
	}
	
	public function _getParam33($order){
		$shippingMethod = $this->_getShippingMethod($order);
        if( $shippingMethod == \Hfd\Woocommerce\Shipping\Govina::METHOD_ID ){
            return $order->get_customer_note();
        }
		return '';
	}
    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->setting->get('betanet_epost_hfd_active');
    }

    /**
     * @return string
     */
    public function getServiceUrl()
    {
        $serviceUrl = $this->setting->get('betanet_epost_hfd_service_url');
        $serviceUrl .= "?APPNAME=run&PRGNAME=ship_create_anonymous&ARGUMENTS=-N<param1>,-A<param2>,-N<param3>,-N<param4>,-A<param5>,-A<param6>,-N<param7>,-N<param8>,-N<param9>,-N<param10>,-A<param11>,-A<param12>,-A<param13>,-A<param14>,-A<param15>,-A<param16>,-A<param17>,-A<param18>,-A<param19>,-A<param20>,-A<param21>,-A<param22>,-A<param23>,-A<param24>,-A<param25>,-A<param26>,-A<param27>,-A<param28>,-N<param29>,-N<param30>,-N<param31>,-A<param32>,-A<param33>,-A<param34>,-N<param35>,-A<param36>,-A<param37>,-A<param38>,-N<param39>,-A<param40>,-A<param41>,-A<param42>";

        return $serviceUrl;
    }

    /**
     * @return string
     */
    public function getCustomerNumber()
    {
        return $this->setting->get('betanet_epost_hfd_customer_number');
    }

    /**
     * @return int
     */
    public function isApiDebug()
    {
        return $this->setting->get('betanet_epost_hfd_debug');
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->setting->get('betanet_epost_hfd_sender_name') ?: '';
    }
}