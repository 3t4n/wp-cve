<?php

if ( class_exists( 'ICWP_APP_Processor_Plugin_Api_Status', false ) ) {
	return;
}

require_once( dirname( __FILE__ ).'/plugin_api.php' );

/**
 * Class ICWP_APP_Processor_Plugin_Api_Index
 */
class ICWP_APP_Processor_Plugin_Api_Status extends ICWP_APP_Processor_Plugin_Api {

	/**
	 * @return ApiResponse
	 */
	protected function processAction() {
		return $this->setSuccessResponse( 'Status', 0, $this->getStatusData() );
	}

	/**
	 * @return array
	 */
	protected function getStatusData() {
		/** @var ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();
		$con = $this->getController();
		return array(
			'plugin_status'      => 1,
			'plugin_version'     => $con->getVersion(),
			'plugin_url'         => $con->getPluginUrl(),
			'supported_internal' => $mod->getSupportedInternalApiAction(),
			'supported_modules'  => $mod->getSupportedModules(),
			'supported_channels' => $mod->getPermittedApiChannels(),
			'supported_openssl'  => $this->loadEncryptProcessor()->getSupportsOpenSslSign() ? 1 : 0,
		);
	}
}