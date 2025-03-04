<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

/**
 * Class ICWP_APP_Processor_Plugin_Api_Index
 */
class ICWP_APP_Processor_Plugin_Api_Auth extends ICWP_APP_Processor_Plugin_Api {

	/**
	 * @return LegacyApi\ApiResponse
	 */
	protected function processAction() {
		return $this->doAuth();
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	protected function doAuth() {
		return $this->setSuccessResponse(
			'Auth',
			0,
			[
				'is_logged_in'     => $this->setAuthorizedUser(),
				'is_wpe'           => @getenv( 'IS_WPE' ),
				'is_wpe_logged_in' => $this->setWpEngineAuth(),
			]
		);
	}
}