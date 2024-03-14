<?php
defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Gateways {

	private static $_instance;
	public $mobile =[];
	public $message = '';
	public $country = '';
	public $senderNumber = '';
	private $apiKey = '';
	private $country_code = '';
	private $password = '';
	
	public function __construct() {
		$this->apiKey     = WooNotify()->Options( '360Messenger_gateway_apiKey' );
		$country_list = WooNotify()->Options( '360Messenger_country_list' );
		$WC_Countries = new WC_Countries();
		$bcountry_code = $WC_Countries->get_country_calling_code( $country_list );
		$this->country_code = preg_replace( '/\D/is', '', $bcountry_code );
	}

	public static function init() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
			
		}

		return self::$_instance;
	}

	public static function get_360Messenger_gateway() {

		$gateway = [
			'Europe'          => 'GlobalV1',
			'Asia'          => 'GlobalV2',
			
		];

		return apply_filters( 'WooNotify_360Messenger_gateways', $gateway );
	}

	


	public function Europe() {
		$apiKey = $this->apiKey;
		$to       = $this->mobile;
		$massage  = $this->message;
		$publiccountrycode = $this->country_code;
		$usercountrycode = $this->country;

	    for($i=0;$i<count($to);$i++){
		    $zero= substr($to[$i], 0, 1);
			if($usercountrycode)
				$countrycode = $usercountrycode;
			else
				$countrycode = $publiccountrycode;

            if ($zero=='0')
                $to[$i]= $countrycode . ltrim($to[$i], '0');
		}

		if ( empty( $apiKey ) || empty( $massage ) ) {
			return false;
		}
			$client =  "https://api.360messenger.net/sendMessage/" . $apiKey;	
			$args = [
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'body'        => array(
						'phonenumber' => sanitize_html_class($to),
						'text' => esc_attr(($massage)),
				),
			];
			$response = wp_remote_post($client, $args);
			if ( is_wp_error( $response ) ) {
				return $response;
			}
			$body= wp_remote_retrieve_body( $response );
			$response_code = wp_remote_retrieve_response_code( $response );
				switch ( $response_code ) {
					case 201:
						return true;
						
					case 200:
						$body = $body;
						return new WP_Error(
							$body->errorCode,
							$body->errorDescription
						);
					case 400:
						return new WP_Error(
							400,
							esc_html('Bad Request')
						);
	
					default:
						return new WP_Error(
							$response_code,
							esc_html('Bad Request')
						);
				}
			

	}

	public function Asia() {
		
		$apiKey = $this->apiKey;
		$to       = $this->mobile;
		$massage  = $this->message;
		$publiccountrycode = $this->country_code;
		$usercountrycode = $this->country;

	    for($i=0;$i<count($to);$i++){
		    $zero= substr($to[$i], 0, 1);
			if($usercountrycode)
				$countrycode = $usercountrycode;
			else
				$countrycode = $publiccountrycode;

            if ($zero=='0')
                $to[$i]= $countrycode . ltrim($to[$i], '0');
		}
		
		if ( empty( $apiKey ) || empty( $massage ) ) {
			return false;
		}
			$client =  "https://api.wamessenger.net/sendMessage/" . $apiKey;	
			$args = [
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'body'        => array(
				'phonenumber' => sanitize_html_class($to),
				'text' => esc_attr(($massage)),
				),
			];
			
			$response = wp_remote_post($client, $args);
			if ( is_wp_error( $response ) ) {
				return $response;
			}
			
				
			$body= wp_remote_retrieve_body( $response );
			$response_code = wp_remote_retrieve_response_code( $response );
				switch ( $response_code ) {
					case 201:
						return true;
						
					case 200:
						$body = $body;
						return new WP_Error(
							$body->errorCode,
							$body->errorDescription
						);
					case 400:
						return new WP_Error(
							400,
							esc_html('Bad Request')
						);
	
					default:
						return new WP_Error(
							$response_code,
							esc_html('Bad Request')
						);
				}
			

	}


}
	

