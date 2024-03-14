<?php

use function EDD\Blocks\Downloads\image;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Getway" ) ) {
	class Payamito_Getway
	{

		private static $instance;
		private        $username;
		private        $password;
		private        $from;

		private $send_endpoint = 'http://api.payamak-panel.com/post/Send.asmx?wsdl';

		public function __construct()
		{
			$connection = Payamito_Connection::instance();

			$this->username = apply_filters( 'payamito_username', $connection->username );
			$this->password = apply_filters( 'payamito_password', $connection->password );
			$this->from     = apply_filters( 'payamito_from', $connection->from );
		}

		public function send_pattern( $to, $text, $bodyid )
		{
			do_action( 'payamito_before_send_pattern', $to, $text, $bodyid );

			$endpoint = 'https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber';
			$args     = [
'timeout' => 15,
				'body'    => [
					"username" => $this->username,
					"password" => $this->password,
					"to"       => $to,
					"text"     => implode( ';', $text ),
					'bodyId'   => $bodyid,
				],
			];
			$args     = apply_filters( 'payamito_send_pattern_args', $args );
			try {
				$result = wp_remote_post( $endpoint, $args );
				if ( is_wp_error( $result ) ) {
					return - 1001;
				}
				$result = json_decode( wp_remote_retrieve_body( $result ), true );
				$result = $result['Value'] ?? '-1001';
			} catch ( exception $e ) {
				$result = - 1001;
			}

			do_action( 'payamito_after_send_pattern', $result, $args );

			return $result;
		}

		public function send( $to, $text )
		{
			do_action( 'payamito_before_send', $to, $text );

			//ini_set("soap.wsdl_cache_enabled", 0);
			$client                   = new \nusoap_client( $this->send_endpoint, true );
			$client->soap_defencoding = 'UTF-8';
			$client->decode_utf8      = true;
			foreach ( $to as $mobile ) {
				$args = [
					"username" => $this->username,
					"password" => $this->password,
					"from"     => $this->from,
					"to"       => $mobile,
					"text"     => $text,
					"isflash"  => false,
				];

				$args = apply_filters( 'payamito_send_args', $args );

				try {
					//$result = $client->SendSimpleSMS($args)->SendSimpleSMSResult;
					$result = $client->call( 'SendSimpleSMS2', $args );
					$result = $result['SendSimpleSMS2Result'];
				} catch ( exception $e ) {
					$result = - 1001;
				}

				if ( is_null( $result ) ) {
					$result = - 100;
				}
				do_action( 'payamito_after_send', $result, $args );
			}

			return $result;
		}

		public function payamito_group_send( $to, $text, $sendernumber = null )
		{
			do_action( 'payamito_before_group_send', $to, $text );

			try {
				$client                   = new \nusoap_client( $this->send_endpoint, true );
				$client->soap_defencoding = 'UTF-8';
				$client->decode_utf8      = true;

				$parameters['username'] = $this->username;
				$parameters['password'] = $this->password;
				$sendernumber === null ? $parameters['from'] = $this->from : $parameters['from'] = $sendernumber;
				$parameters['to']      = $to;
				$parameters['text']    = $text;
				$parameters['isflash'] = false;
				$parameters['udh']     = "";
				$parameters['recId']   = [ 0 ];
				$parameters['status']  = 0x0;
				$result                = $client->call( 'SendSms', $parameters );
				$result                = $result['SendSmsResult'];
			} catch ( SoapFault $ex ) {
				return - 1001;
			}
			do_action( 'payamito_after_group_send', $to, $text );

			return $result;
		}

		public function crediet()
		{
			$client                   = new \nusoap_client( 'http://api.payamak-panel.com/post/Send.asmx?wsdl', true );
			$client->soap_defencoding = 'UTF-8';
			$client->decode_utf8      = true;

			$parameters['username'] = $this->username;
			$parameters['password'] = $this->password;

			try {
				$client = $client->call( 'GetCredit', $parameters );
				$client = $client['GetCreditResult'];
			} catch ( exception $e ) {
				return - 1001;
			}

			return $client;
		}

		public static function instance()
		{
			$class = static::class;

			if ( ! isset( self::$instance[ $class ] ) ) {
				self::$instance[ $class ] = new $class();
			}

			return self::$instance[ $class ];
		}
	}
}
