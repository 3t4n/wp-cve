<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class SibsPaymentCore {

	protected static $checkout_url_live = 'https://oppwa.com/v1/checkouts';

	protected static $checkout_url_test = 'https://test.oppwa.com/v1/checkouts';


	protected static $back_office_url_live = 'https://oppwa.com/v1/payments/';

	protected static $back_office_url_test = 'https://test.oppwa.com/v1/payments/';


	protected static $server_to_server_url_live = 'https://oppwa.com/v1/payments';

	protected static $server_to_server_url_test = 'https://test.oppwa.com/v1/payments';


	protected static $payment_widget_url_live = 'https://oppwa.com/v1/paymentWidgets.js?checkoutId=';

	protected static $payment_widget_url_test = 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=';


	protected static $register_url_live = 'https://oppwa.com/v1/registrations/';

	protected static $register_url_test = 'https://test.oppwa.com/v1/registrations/';


	protected static $query_url_live = 'https://oppwa.com/v1/query';

	protected static $query_url_test = 'https://test.oppwa.com/v1/query';


	protected static $ack_patterns = array(
		'/^(000\.000\.|000\.100\.1|000\.[36])/',
	);

	protected static $in_review_patterns = array(
		'/^(800\.400\.5|100\.400\.500)/',
	);


	protected static $pending_patterns = array(
		'/^(000\.200)/',
		'/^(800\.400\.5|100\.400\.500)/',
	);


	protected static $nok_patterns = array(
		'/^(000\.400\.[1][0-9][1-9]|000\.400\.2)/',
		'/^(800\.[17]00|800\.800\.[123])/',
		'/^(900\.[1234]00)/',
		'/^(800\.5|999\.|600\.1|800\.800\.8)/',
		'/^(100\.39[765])/',
		'/^(100\.400|100\.38|100\.370\.100|100\.370\.11])/',
		'/^(800\.400\.1)/',
		'/^(800\.400\.2|100\.380\.4|100\.390)/',
		'/^(100\.100\.701|800\.[32])/',
		'/^(800\.1[123456]0)/',
		'/^(600\.2|500\.[12]|800\.121)/',
		'/^(100\.[13]50)/',
		'/^(100\.250|100\.360)/',
		'/^(700\.[1345][05]0)/',
		'/^(200\.[123]|100\.[53][07]|800\.900|100\.[69]00\.500)/',
		'/^(100\.800)/',
		'/^(100\.[97]00)/',
		'/^(100\.100|100.2[01])/',
		'/^(100\.55)/',
		'/^(100\.380\.[23]|100\.380\.101)/',
	);


	private static function sibs_get_checkout_url( $server_mode ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$checkout_url_live;
		} else {
			return self::$checkout_url_test;
		}
	}


	private static function sibs_get_payment_status_url( $server_mode, $checkout_id ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$checkout_url_live . '/' . $checkout_id . '/payment';
		} else {
			return self::$checkout_url_test . '/' . $checkout_id . '/payment';
		}
	}


	private static function sibs_get_back_office_url( $server_mode, $reference_id ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$back_office_url_live . $reference_id;
		} else {
			return self::$back_office_url_test . $reference_id;
		}
	}


	private static function sibs_get_server_to_server_url( $server_mode ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$server_to_server_url_live;
		} else {
			return self::$server_to_server_url_test;
		}
	}


	private static function sibs_get_payment_server_to_server_status_url( $server_mode, $checkout_id ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$server_to_server_url_live . '/' . $checkout_id;
		} else {
			return self::$server_to_server_url_test . '/' . $checkout_id;
		}
	}


	private static function sibs_get_url_to_use_registered_account( $server_mode, $reference_id ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$register_url_live . $reference_id . '/payments';
		} else {
			return self::$register_url_test . $reference_id . '/payments';
		}
	}


	private static function sibs_get_deregister_url( $server_mode, $reference_id ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$register_url_live . $reference_id;
		} else {
			return self::$register_url_test . $reference_id;
		}
	}

	private static function sibs_get_query_url( $server_mode ) {
		if ( 'LIVE' === $server_mode ) {
			return self::$query_url_live;
		} else {
			return self::$query_url_test;
		}
	}


	private static function sibs_get_ssl_verify( $server_mode ) {
		if ( 'TEST' === $server_mode ) {
			return false;
		}
		return true;
	}


	private static function sibs_get_wp_response( $response ) {
		if ( is_wp_error( $response ) ) {
			$result['response'] = 'ERROR_UNKNOWN';
			$result['is_valid'] = false;
			$error_code = $response->get_error_code();
			$error_message = $response->get_error_message();
			if ( false !== strpos( $error_message, 'cURL error 60' ) ) {
				$result['response'] = 'ERROR_MERCHANT_SSL_CERTIFICATE';
			}
			return $result;
		}

		$response = wp_remote_retrieve_body( $response );
		$json_response = json_decode( $response, true );
		if ( isset( $json_response ) ) {
			$result['response'] = $json_response;
		} else {
			$result['response'] = $response;
		}
		$result['is_valid'] = true;

		return $result;
	}


	private static function sibs_get_response_data( $data, $url, $server_mode, $token = null ) {
        $headers = array(
            'content-type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
        );

        if (!empty($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        $response = wp_remote_post(
			$url, array(
				'headers'   => $headers,
				'body'      => $data,
				'sslverify' => self::sibs_get_ssl_verify( $server_mode ),
				'timeout'   => 100,
			)
		);

        return self::sibs_get_wp_response($response);
	}


	public static function sibs_get_payment_widget_content( $url, $server_mode ) {
		$response = wp_remote_get( $url,
			array(
				'sslverify' => self::sibs_get_ssl_verify( $server_mode ),
			)
		);
		return self::sibs_get_wp_response( $response );
	}


	private static function sibs_get_payment_response( $url, $token=null ) {
	    $headers = [];
        if (!empty($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
		$response = wp_remote_get(
			$url, array(
			    'headers' => $headers,
				'sslverify' => false,
			)
		); // Ok.
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $response );
		return json_decode( $response, true );
	}


	private static function sibs_send_deregistration( $url, $token=null ) {
	    $headers = [];
	    if(!empty($token)) {
	        $headers['Authorization'] = 'Bearer ' . $token;
        }
		$response = wp_remote_get(
			$url, array(
			    'headers' => $headers,
				'sslverify' => false,
				'method'    => 'DELETE',
			)
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $response );
		return json_decode( $response, true );
	}

    /**
     * @param $order
     *
     * @return mixed
     */
    public static function getToken($order)
    {
        $log = new WC_Logger();
        $log_entry = print_r('woocommerce_' . $order['payment_brand'] . '_settings', true);
        $log->add('woocommerce-sibs-log', 'OPTION NAME: ' . $log_entry);

        $payment_brand = $order['payment_brand'];
        if ('MBWAY' === $payment_brand) {
            $payment_brand = 'sibs_mbway';
        }
        elseif ('SIBS_MULTIBANCO' == $payment_brand)
        {
            $payment_brand = 'sibs_multibanco';
        }
        else
        {
            $payment_brand = 'sibs_cc';
        }
        
        $payment_setting = get_option('woocommerce_' . $payment_brand . '_settings');
        $token = $payment_setting['token'];

        $log_entry = print_r($token, true);
        $log->add('woocommerce-sibs-log', 'TOKEN: ' . $log_entry);
        return $token;
    }

    private static function sibs_get_current_payment_status( $url, $xml_request ) {
		$response = wp_remote_post(
			$url, array(
				'headers'   => array(
					'content-type' => 'application/x-www-form-urlencoded',
					'charset'      => 'UTF-8',
				),
				'body'      => $xml_request,
				'sslverify' => false,
				'timeout'   => 100,
			)
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $response );
		return $response;
	}


	private static function sibs_get_payment_credential( $order ) {
	    // TODO: Remove credentials from here
		$payment_credential                            = array();
		// $payment_credential['authentication.userId']   = $order['login'];
		// $payment_credential['authentication.password'] = $order['password'];
		$payment_credential['entityId'] = $order['channel_id'];

		// test mode payment_credential ( true ).
		if ( ! empty( $order['test_mode'] ) ) {
			$payment_credential['testMode'] = $order['test_mode'];
		}

		return $payment_credential;
	}


	public static function sibs_set_number_format( $number ) {
		return number_format( str_replace( ', ', '.', $number ), 2, '.', '' );
	}

	private static function sibs_set_cart_items_parameters( $cart_items ) {
		$parameters       = array();
		$count_cart_items = count( $cart_items );
		for ( $i = 0; $i < $count_cart_items; $i++ ) {
			$parameters[ 'cart.items[' . $i . '].merchantItemId' ] = $cart_items[ $i ]['merchant_item_id'];
			if ( ! empty( $cart_items[ $i ]['discount'] ) ) {
				$parameters[ 'cart.items[' . $i . '].discount' ] = self::sibs_set_number_format( $cart_items[ $i ]['discount'] );
			}
			$parameters[ 'cart.items[' . $i . '].quantity' ] = $cart_items[ $i ]['quantity'];
			$parameters[ 'cart.items[' . $i . '].name' ]     = $cart_items[ $i ]['name'];
			$parameters[ 'cart.items[' . $i . '].price' ]    = self::sibs_set_number_format( $cart_items[ $i ]['price'] );
			if ( ! empty( $cart_items[ $i ]['tax'] ) ) {
				$parameters[ 'cart.items[' . $i . '].tax' ] = self::sibs_set_number_format( $cart_items[ $i ]['tax'] );
			}
		}
		return $parameters;
	}

	private static function sibs_set_checkout_parameters( $order ) {
		$parameters                          = array();
		$parameters                          = self::sibs_get_payment_credential( $order );
		$parameters['merchantTransactionId'] = $order['transaction_id'];
		$parameters['customer.email']        = $order['customer']['email'];
		$parameters['customer.givenName']    = $order['customer']['first_name'];
		$parameters['customer.surname']      = $order['customer']['last_name'];
		$parameters['billing.street1']       = $order['billing']['street'];
		$parameters['billing.city']          = $order['billing']['city'];
		$parameters['billing.postcode']      = $order['billing']['zip'];
		$parameters['billing.country']       = $order['billing']['country_code'];

		/* o sibs_mnultibanco apenas devolve referencia se o pais for PT */ 
		$parameters['billing.country']       = 'PT';


		//CHANGE: Multibanco to Server to Server payment
		// Sibs server to server têm de indicar a paymentBrand
		if($order['payment_brand'] == 'SIBS_MULTIBANCO'){
		 	$parameters['paymentBrand'] = $order['payment_brand'];
		}
		//

		if ( isset( $order['shipping']['street'] ) ) {
			$parameters['shipping.street1'] = $order['shipping']['street'];
		}
		if ( isset( $order['shipping']['city'] ) ) {
			$parameters['shipping.city'] = $order['shipping']['city'];
		}
		if ( isset( $order['shipping']['zip'] ) ) {
			$parameters['shipping.postcode'] = $order['shipping']['zip'];
		}
		if ( isset( $order['shipping']['country_code'] ) ) {
			$parameters['shipping.country'] = $order['shipping']['country_code'];
		}
		$parameters['amount']   = self::sibs_set_number_format( $order['amount'] );
		$parameters['currency'] = $order['currency'];

		if ( isset( $order['customer']['sex'] ) ) {
			$parameters['customer.sex'] = $order['customer']['sex'];
		}
		if ( isset( $order['customer']['birthdate'] ) && '0000-00-00' !== $order['customer']['birthdate'] ) {
			$parameters['customer.birthDate'] = $order['customer']['birthdate'];
		}
		if ( isset( $order['customer']['phone'] ) ) {
			$parameters['customer.phone'] = $order['customer']['phone'];
		}
		if ( isset( $order['customer']['mobile'] ) ) {
			$parameters['customer.mobile'] = $order['customer']['mobile'];
		}

		// klarna parameters.
		if ( ! empty( $order['cartItems'] ) ) {
			$parameters = array_merge( $parameters, self::sibs_set_cart_items_parameters( $order['cartItems'] ) );
		}
		if ( ! empty( $order['customParameters']['KLARNA_CART_ITEM1_FLAGS'] ) ) {
			$parameters['customParameters[KLARNA_CART_ITEM1_FLAGS]'] = $order['customParameters']['KLARNA_CART_ITEM1_FLAGS'];
		}
		if ( ! empty( $order['customParameters']['KLARNA_PCLASS_FLAG'] ) && trim( $order['customParameters']['KLARNA_PCLASS_FLAG'] ) !== '' ) {
			$parameters['customParameters[KLARNA_PCLASS_FLAG]'] = $order['customParameters']['KLARNA_PCLASS_FLAG'];
		}
		// paydirekt parameters.
		if ( ! empty( $order['customParameters']['PAYDIREKT_minimumAge'] ) ) {
			$parameters['customParameters[PAYDIREKT_minimumAge]'] = $order['customParameters']['PAYDIREKT_minimumAge'];
		}
		if ( ! empty( $order['customParameters']['PAYDIREKT_payment.isPartial'] ) ) {
			$parameters['customParameters[PAYDIREKT_payment.isPartial]'] = $order['customParameters']['PAYDIREKT_payment.isPartial'];
		}
		if ( ! empty( $order['customParameters']['PAYDIREKT_payment.shippingAmount'] ) ) {
			$parameters['customParameters[PAYDIREKT_payment.shippingAmount]'] = self::sibs_set_number_format( $order['customParameters']['PAYDIREKT_payment.shippingAmount'] );
		}

		// sibs parameter.
		if ( ! empty( $order['customParameters']['SIBS_ENV'] ) ) {
			$parameters['customParameters[SIBS_ENV]'] = $order['customParameters']['SIBS_ENV'];
		}
		if ( ! empty( $order['customParameters']['authorization_type'] ) ) {
			$parameters['customParameters[authorization_type]'] = $order['customParameters']['authorization_type'];
		}

		$parameters['customParameters[woocommerce]'] = constant( 'SIBS_VERSION' );
		$parameters['customParameters[sibsplugin]'] = 'woocommerce';


		// multibanco parameter.
		if ( ! empty( $order['customParameters']['SIBSMULTIBANCO_PtmntEntty'] ) ) {
			$parameters['customParameters[SIBSMULTIBANCO_PtmntEntty]'] = $order['customParameters']['SIBSMULTIBANCO_PtmntEntty'];
		}
		if ( ! empty( $order['customParameters']['SIBSMULTIBANCO_RefIntlDtTm'] ) ) {
			$parameters['customParameters[SIBSMULTIBANCO_RefIntlDtTm]'] = $order['customParameters']['SIBSMULTIBANCO_RefIntlDtTm'];
		}
		if ( ! empty( $order['customParameters']['SIBSMULTIBANCO_RefLmtDtTm'] ) ) {
			$parameters['customParameters[SIBSMULTIBANCO_RefLmtDtTm]'] = $order['customParameters']['SIBSMULTIBANCO_RefLmtDtTm'];
		}

		// easycredit parameters.
		if ( isset( $order['customParameters']['RISK_ANZAHLBESTELLUNGEN'] ) ) {
			$parameters['customParameters[RISK_ANZAHLBESTELLUNGEN]'] = $order['customParameters']['RISK_ANZAHLBESTELLUNGEN'];
		}
		if ( isset( $order['customParameters']['RISK_BESTELLUNGERFOLGTUEBERLOGIN'] ) ) {
			$parameters['customParameters[RISK_BESTELLUNGERFOLGTUEBERLOGIN]'] = $order['customParameters']['RISK_BESTELLUNGERFOLGTUEBERLOGIN'];
		}
		if ( isset( $order['customParameters']['RISK_KUNDENSTATUS'] ) ) {
			$parameters['customParameters[RISK_KUNDENSTATUS]'] = $order['customParameters']['RISK_KUNDENSTATUS'];
		}
		if ( isset( $order['customParameters']['RISK_KUNDESEIT'] ) ) {
			$parameters['customParameters[RISK_KUNDESEIT]'] = $order['customParameters']['RISK_KUNDESEIT'];
		}
		if ( isset( $order['shopperResultUrl'] ) ) {
			$parameters['shopperResultUrl'] = $order['shopperResultUrl'];
		}

		// payment type for RG.DB or only RG.
		if ( ! empty( $order['payment_type'] ) ) {
			$parameters['paymentType'] = $order['payment_type'];
		}

		if ( isset( $order['payment_brand'] ) && 'RATENKAUF' === $order['payment_brand'] ) {
			$parameters['paymentBrand'] = $order['payment_brand'];
		}

		// registration parameter ( true ).
		if ( ! empty( $order['payment_registration'] ) ) {
			$parameters['createRegistration'] = $order['payment_registration'];
			if ( ! empty( $order['3D'] ) ) {
				$parameters['customParameters[presentation.amount3D]']   = self::sibs_set_number_format( $order['3D']['amount'] );
				$parameters['customParameters[presentation.currency3D]'] = $order['3D']['currency'];
			}
		}

		// recurring payment parameters : initial/repeated.
		if ( ! empty( $order['payment_recurring'] ) ) {
			$parameters['recurringType'] = $order['payment_recurring'];
		}

		if ( ! empty( $order['customer_ip'] ) ) {
			$parameters['customer.ip'] = $order['customer_ip'];
		}

		if ( ! empty( $order['registrations'] ) ) {
			foreach ( $order['registrations'] as $key => $value ) {
				$parameters[ 'registrations[' . $key . '].id' ] = $value;
			}
		}

		return $parameters;
	}

	
	private static function sibs_set_registered_account_parameters( $order ) {
		$parameters                          = self::sibs_get_payment_credential( $order );
		$parameters['amount']                = self::sibs_set_number_format( $order['amount'] );
		$parameters['currency']              = $order['currency'];
		$parameters['paymentType']           = $order['payment_type'];
		$parameters['merchantTransactionId'] = $order['transaction_id'];
		$parameters['recurringType']         = $order['payment_recurring'];

		return $parameters;
	}

	
	private static function sibs_set_back_office_parameters( $order ) {
		$parameters                = self::sibs_get_payment_credential( $order );
		$parameters['paymentType'] = $order['payment_type'];

		// Reversal ( RV ) didn't send amount & currency parameter.
		if ( 'RV' !== $order['payment_type'] ) {
			$parameters['amount']   = self::sibs_set_number_format( $order['amount'] );
			$parameters['currency'] = $order['currency'];
		}

		return $parameters;
	}

	
	private static function sibs_get_xml_current_payment_status( $reference_id, $order ) {
		switch ( $order['test_mode'] ) {
			case 'INTERNAL':
				$mode = 'INTEGRATOR_TEST';
				break;
			case 'EXTERNAL':
				$mode = 'CONNECTOR_TEST';
				break;
			case 'LIVE':
				$mode = 'LIVE';
				break;
		}

		$xml = 'load=<?xml version="1.0" encoding="UTF-8"?><Request version="1.0">
			<Header><Security sender="' . $order['channel_id'] . '"/></Header>
			<Query mode="' . $mode . '" level="CHANNEL" entity="' . $order['channel_id'] . '" type="STANDARD">
				<User login="' . $order['login'] . '" pwd="' . $order['password'] . '"/>
				<Identification>
					<UniqueID>' . $reference_id . '</UniqueID>
				</Identification>
				</Query>
			</Request>';

		return $xml;
	}

	
	private static function sibs_is_payment_get_response( $payment_status_url, &$payment_response, $token=null ) {
		for ( $i = 0; $i < 3; $i++ ) {
			$response = true;
			try {
				$payment_response = self::sibs_get_payment_response( $payment_status_url, $token );
			} catch ( Exception $e ) {
				$response = false;
			}
			if ( $response && $payment_response ) {
				return true;
			}
		}
		return false;
	}

	
	public static function sibs_get_checkout_result( $order ) {
		// prepareCheckout.
		$checkout_url = self::sibs_get_checkout_url( $order['server_mode'] );
		$post_data = self::sibs_set_checkout_parameters( $order );

		// added payment registrations
		if ( array_key_exists ( 'registrations' , $order ) ){
			if ( isset($order['registrations']) === true && empty( ($order['registrations'] ) === false ) ) {
				$var['registrations'] = $order['registrations'];
				$order['registrations'] = null;
			} else {
				$var['registrations'] = null;
			}

			$post_data = self::sibs_set_checkout_parameters( $order );
			$count = 0;
	
			if ($var['registrations'] !== null){
				foreach ($var['registrations'] as $key => $value) {
				
					//if ($count == 0){
					//	$post_data['createRegistration'] = true;
					//}
		
					$string1 = 'registrations' . '[' . $count . '].id';
					$post_data[$string1] = $var['registrations'][$count]['reg_id'];
					$count = $count + 1;
				}
			}
		}

        $token = self::getToken($order);
		
		return self::sibs_get_response_data( $post_data, $checkout_url, $order['server_mode'], $token );
	}


	public static function sibs_get_payment_widget_url( $order, $checkout_id ) {
		if ( 'LIVE' === $order['server_mode'] ) {
			return self::$payment_widget_url_live . $checkout_id;
		} else {
			return self::$payment_widget_url_test . $checkout_id;
		}
	}

	
	public static function sibs_get_payment_status( $checkout_id, $order ) {
		$payment_status_url  = self::sibs_get_payment_status_url( $order['server_mode'], $checkout_id );
        $payment_status_url .= '?' . http_build_query( self::sibs_get_payment_credential( $order ), '', '&' );

        $token = self::getToken($order);

        $response            = self::sibs_is_payment_get_response( $payment_status_url, $payment_response, $token );
		if ( $response ) {
			return $payment_response;
		}

		return false;
	}

	
	public static function sibs_back_office_operation( $reference_id, $order ) {
		$back_office_url = self::sibs_get_back_office_url( $order['server_mode'], $reference_id );
		$post_data       = self::sibs_set_back_office_parameters( $order );


        $token = self::getToken($order);

		$response_data   = self::sibs_get_response_data( $post_data, $back_office_url, $order['server_mode'], $token );

		return $response_data;
	}

	
	public static function sibs_get_server_to_server_response( $order ) {
		$server_to_server_url = self::sibs_get_server_to_server_url( $order['server_mode'] ) ;
		$post_data            = self::sibs_set_checkout_parameters( $order );

        $token = self::getToken($order);

		$payment_response     = self::sibs_get_response_data( $post_data, $server_to_server_url, $order['server_mode'], $token );
		if ( self::sibs_get_transaction_result( $payment_response['response']['result']['code'] ) === 'NOK' ) {
			return false;
		}

		return $payment_response;
	}

	
	public static function sibs_get_payment_server_to_server_status( $checkout_id, $order ) {
		$payment_status_url  = self::sibs_get_payment_server_to_server_status_url( $order['server_mode'], $checkout_id );
		$payment_status_url .= '?' . http_build_query( self::sibs_get_payment_credential( $order ) );

        $token = self::getToken($order);

		$response            = self::sibs_is_payment_get_response( $payment_status_url, $payment_response, $token );

		if ( $response ) {
			return $payment_response;
		}

		return false;
	}
    
	public static function sibs_get_query_merchantTransactionId( $merchantTransactionId, $order ) {
		$payments_url  = self::sibs_get_query_url( $order['server_mode'] );
        $params = self::sibs_get_payment_credential( $order );
        $params['merchantTransactionId'] = $merchantTransactionId;
		$payments_url .= '?' . http_build_query( $params );

        $token = self::getToken($order);

		$response            = self::sibs_is_payment_get_response( $payments_url, $payment_response, $token );

		if ( $response ) {
			return $payment_response;
		}

		return false;
	}

	
	public static function sibs_use_registered_account( $reference_id, $order ) {
		$registered_account_url = self::sibs_get_url_to_use_registered_account( $order['server_mode'], $reference_id );
		$post_data              = self::sibs_set_registered_account_parameters( $order );

        $token = self::getToken($order);

		$response_data          = self::sibs_get_response_data( $post_data, $registered_account_url, $order['server_mode']. $token );

		return $response_data;
	}

	
	public static function sibs_delete_registered_account( $reference_id, $order ) {
		$deregister_url          = self::sibs_get_deregister_url( $order['server_mode'], $reference_id );
		$deregister_url         .= '?' . http_build_query( self::sibs_get_payment_credential( $order ), '', '&' );

        $token = self::getToken($order);

		$deregistration_response = self::sibs_send_deregistration( $deregister_url, $token );

		return $deregistration_response;
	}


	public static function sibs_update_status( $reference_id, $order ) {
		unset( $order['test_mode'] );
		$status_url  = self::sibs_get_back_office_url( $order['server_mode'], $reference_id );
		$status_url .= '?' . http_build_query( self::sibs_get_payment_credential( $order ), '', '&');

        $token = self::getToken($order);

        return self::sibs_get_payment_response( $status_url,$token );
	}


	public static function sibs_get_transaction_result( $return_code = false ) {
		if ( $return_code ) {
			$ack_patterns = array_merge(
				self::$ack_patterns,
				self::$in_review_patterns,
				self::$pending_patterns
			);
			foreach ( $ack_patterns as $pattern ) {
				if ( preg_match( $pattern, $return_code ) ) {
					return 'ACK';
				}
			}
			foreach ( self::$nok_patterns as $pattern ) {
				if ( preg_match( $pattern, $return_code ) ) {
					return 'NOK';
				}
			}
		}
		return false;
	}

	public static function sibs_get_transaction_result_ack_only( $return_code = false ) {
		if ( $return_code ) {

			foreach ( self::$ack_patterns as $pattern ) {
				if ( preg_match( $pattern, $return_code ) ) {
					return 'ACK';
				}
			}

			return 'NOK';
		}
		return false;
	}


	public static function sibs_is_success_review( $code ) {
		if ( $code ) {
			foreach ( self::$in_review_patterns as $pattern ) {
				if ( preg_match( $pattern, $code ) ) {
					return true;
				}
			}
		}
		return false;
	}

}
