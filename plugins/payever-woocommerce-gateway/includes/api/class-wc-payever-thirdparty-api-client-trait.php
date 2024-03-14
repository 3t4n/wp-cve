<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_ThirdParty_Api_Client_Trait' ) ) {
	return;
}

use Payever\Sdk\ThirdParty\ThirdPartyApiClient;

trait WC_Payever_ThirdParty_Api_Client_Trait {

	/** @var ThirdPartyApiClient */
	private $third_party_api_client;

	/**
	 * @param ThirdPartyApiClient $third_party_api_client
	 * @return $this
	 * @internal
	 */
	public function set_third_party_api_client( ThirdPartyApiClient $third_party_api_client ) {
		$this->third_party_api_client = $third_party_api_client;

		return $this;
	}

	/**
	 * @return ThirdPartyApiClient
	 * @throws \Exception
	 * @codeCoverageIgnore
	 */
	private function get_third_party_api_client() {
		return null === $this->third_party_api_client
			? $this->third_party_api_client = WC_Payever_Api::get_instance()->get_third_party_api_client()
			: $this->third_party_api_client;
	}
}
