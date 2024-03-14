<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class ICWP_APP_Processor_Plugin_SiteLink extends ICWP_APP_Processor_Plugin_Api {

	/**
	 * @return LegacyApi\ApiResponse
	 */
	public function run() {
		$this->preActionEnvironmentSetup();
		if ( $this->getRequestParams()->a == 'check' ) {
			return $this->getStandardResponse()->setSuccess( true );
		}
		return $this->processAction();
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	public function processAction() {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();
		$oParams = $this->getRequestParams();
		$oResponse = $this->getStandardResponse();

		if ( $oMod->getIsSiteLinked() ) {
			return $oResponse->setMessage( 'Assigned To:'.$this->getOption( 'assigned_to' ) )
							 ->setStatus( 'AlreadyAssigned' )
							 ->setCode( 1 )
							 ->setSuccess( false );
		}

		if ( empty( $oParams->key ) ) {
			return $oResponse->setMessage( 'KeyEmpty:'.'.' )
							 ->setCode( 2 );
		}
		if ( $oParams->key != $oMod->getPluginAuthKey() ) {
			return $oResponse->setMessage( 'KeyMismatch:'.$oParams->key.'.' )
							 ->setCode( 3 );
		}

		if ( empty( $oParams->pin ) ) {
			return $oResponse->setMessage( 'PinEmpty:.' )
							 ->setCode( 4 );
		}

		if ( empty( $oParams->accname ) ) {
			return $oResponse->setMessage( 'AccountEmpty:.' )
							 ->setCode( 5 );
		}
		if ( !is_email( $oParams->accname ) ) {
			return $oResponse->setMessage( 'AccountNotValid:'.$oParams->accname )
							 ->setCode( 6 );
		}

		$oEncryptProcessor = $this->loadEncryptProcessor();
		if ( $oEncryptProcessor->getSupportsOpenSslSign() ) {

			$sPublicKey = $oMod->getIcwpPublicKey();
			if ( !empty( $oParams->opensig ) && !empty( $sPublicKey ) ) {
				$nSslSuccess = $oEncryptProcessor->verifySslSignature(
					$oParams->verification_code, $oParams->opensig, $sPublicKey
				);
				$oResponse->openssl_verify = $nSslSuccess;
				if ( $nSslSuccess !== 1 ) {
					$oResponse->message = 'Failed to Verify SSL Signature.';
					$oResponse->code = 7;
					return $oResponse;
				}
			}
		}

		$oMod->setPluginPin( $oParams->pin );
		$oMod->setAssignedAccount( $oParams->accname );
		return $oResponse->setSuccess( true );
	}
}