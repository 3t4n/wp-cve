<?php

class PMSC_Server_Yahoo extends PMCS_Exchange_Server_Abstract {
	public $api_url = 'https://query1.finance.yahoo.com/v8/finance/chart/';
	public $base = 'USD';
	public $label = '';
	public $website = 'https://yahoo.com';

	public function __construct() {
		$this->label = __( 'Yahoo Finance', 'pmcs' );
		$this->base = $this->get_shop_base();
	}

	public function settings() {
		$fields = array();
		return $fields;
	}

	public function get_rate( $from_code, $to_code ) {
		// Convert yo itselft.
		if ( strtoupper( $from_code ) == strtoupper( $to_code ) ) {
			return 1;
		}

		$url = $this->api_url . $from_code . $to_code . '=X?interval=1d&includePrePost=false&events=div%7Csplit%7Cearn&lang=en-US&region=US&corsDomain=finance.yahoo.com';
		$res = wp_remote_get( $url );

		if ( 200 != wp_remote_retrieve_response_code( $res ) ) {
			return null;
		}

		$data = json_decode( wp_remote_retrieve_body( $res ), true );
		
		if ( ! is_array( $data ) ) {
			return null;
		}

		$volumnes = $data['chart']['result'][0]['indicators'];

		$request = null;
		$result = isset( $volumnes['quote'][0]['open'] ) ? $volumnes['quote'][0]['open'] : ( isset( $data['chart']['result'][0]['meta']['previousClose'] ) ? array( $data['chart']['result'][0]['meta']['previousClose'] ) : array() );
		if ( count( $result ) && is_array( $result ) ) {
			$request = end( $result );
		}

		if ( ! $request ) {
			$request = isset( $volumnes['adjclose'][0]['adjclose'] ) ? $volumnes['adjclose'][0]['adjclose'] : 0;
		}

		if ( is_array( $request ) ) {
			$request = end( $request );
		}

		if ( ! is_numeric( $request ) ) {
			$request = 0;
		}

		return $request;
	}

	/**
	 * Update exchange rates
	 *
	 * @param string|bool $from
	 * @param string|bool $to
	 * @return array
	 */
	public function update( $from = false, $to = false ) {

		$using_currencies = $this->get_using_currencies();
		$this->base = $from ? $from : $this->base;
		$rest = array(
			'base'  => $this->base,
			'rates' => array(),
		);

		$codes = wp_list_pluck( $using_currencies, 'currency_code' );
		if ( $to ) {
			$to_array = explode( ',', $to );
			$codes = array_map( 'trim', $to_array );
		}

		foreach ( $codes as $code ) {
			if ( $code ) {
				$code = strtoupper( $code );
				$rest['rates'][ $code ] = $this->get_rate( $this->base, $code );
			}
		}

		$this->save( $rest );
		return $rest;
	}


}
