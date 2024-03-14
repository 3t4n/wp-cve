<?php
/**
 * Class for calling ANAF server for VAT info
 *
 * @copyright  Intelligent IT SRL 2018-2022
 * @package smartbill-facturare-si-gestiune
 */

/**
 * Class for calling ANAF server for VAT info
 *
 * @copyright  Intelligent IT SRL 2018-2022
 */
class ANAFAPIClient {
	const ANAF_API_URL = 'https://webservicesp.anaf.ro/PlatitorTvaRest/api/v8/ws/tva';

	/**
	 * Function that calls an endpoint using 
	 *
	 * @param string $url endpoint.
	 * @param Array  $data endpoint parameters.
	 *
	 * @return Response $ch
	 */
	private function curl( $url, $data ) {
		$args=[];
		$args['timeout'] = 10;
		$args['headers'] = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
		$args['method'] = 'POST';
		$args['body'] = json_encode( $data );

		$ch = wp_remote_post($url,$args);


		return json_decode(wp_remote_retrieve_body( $ch ));
	}

	/**
	 * Calls and returns data from ANAF server for a vat id
	 *
	 * @param string|null $vat vat id/cif.
	 *
	 * @return Array|false
	 */
	public function get_vat_info( $vat = null ) {
		if ( ! $vat ) {
			return false;
		}

		// doar CUI-uri numerice.
		$vat  = preg_replace( '/[^0-9]/', '', $vat );
		$data = array(
			array(
				'cui'  => $vat,
				'data' => gmdate( 'Y-m-d' ),
			),

		);

		try {
			return $this->curl( self::ANAF_API_URL, $data );
		} catch ( \Exception $e ) {
			return "error";
		}
	}

	/**
	 *  Returns true if the entity pays taxes and vat id is valid.
	 *
	 *  @param Array|null $vat_info VAT info array returned by get_vat_info.
	 *
	 * @return boolean
	 */
	public function is_tax_payer( $vat_info = null ) {
		if ( is_null($vat_info) || !isset($vat_info->found) || empty($vat_info->found)) {
			return false;
		}else{
			$vat_info=$vat_info->found[0];

			if(isset($vat_info->inregistrare_scop_Tva) && isset($vat_info->inregistrare_scop_Tva->scpTVA) ){
				$vat_info=$vat_info->inregistrare_scop_Tva;
				return true == $vat_info->scpTVA ? true : false;
			}else{
				return false;
			}
		}
		
		return false;
	}

}
