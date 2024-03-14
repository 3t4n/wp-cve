<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Channel;

use FernleafSystems\Wordpress\Plugin\iControlWP;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Download extends \ICWP_APP_Processor_Plugin_Api {

	/**
	 * Override so that we don't run the handshaking etc.
	 * @return LegacyApi\ApiResponse
	 */
	public function run() {
		$this->preActionEnvironmentSetup();
		try {
			$this->processAction();
		}
		catch ( \Exception $e ) {
			wp_die( $e->getMessage() );
		}
		return $this->setSuccessResponse();
	}

	/**
	 * @return LegacyApi\ApiResponse
	 * @throws \Exception
	 */
	protected function processAction() {
		$this->getStandardResponse()->die = true;

		( new iControlWP\Ops\ZipDownload\Download() )
			->setCon( $this->getController() )
			->byID( $this->getRequestParams()->zip_id );
		die();
	}
}