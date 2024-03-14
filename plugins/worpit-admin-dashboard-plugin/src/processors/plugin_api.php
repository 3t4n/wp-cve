<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

abstract class ICWP_APP_Processor_Plugin_Api extends ICWP_APP_Processor_BaseApp {

	/**
	 * @var LegacyApi\ApiResponse
	 */
	protected static $oActionResponse;

	/**
	 * @var string
	 */
	protected $sLoggedInUser;

	/**
	 * @return LegacyApi\ApiResponse
	 */
	public function run() {
		$actionResponse = self::getStandardResponse();
		$this->preActionVerify();
		if ( $actionResponse->success ) {
			$this->preActionEnvironmentSetup();
			$this->processAction();
		}
		$this->postProcessAction();
		return $actionResponse;
	}

	protected function preActionVerify() {
		/** @var \ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();

		$oResponse = $this->getStandardResponse();
		$oResponse->channel = $this->getApiChannel();

		$this->preApiCheck();

		if ( !$oResponse->success ) {
			if ( !$this->attemptSiteReassign()->success ) {
				return;
			}
		}

		$this->handshake();

		if ( !$oResponse->success ) {
			if ( $oResponse->code == 9991 ) {
				$oMod->setCanHandshake(); //recheck ability to handshake
			}
		}
	}

	protected function postProcessAction() {
		$oR = $this->getStandardResponse();
		$aData = $oR->data;
		$aData[ 'verification_code' ] = $this->getRequestParams()->verification_code;
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	abstract protected function processAction();

	/**
	 * @return string
	 */
	protected function getApiChannel() {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();
		$oParams = $this->getRequestParams();
		return in_array( $oParams->m, $oMod->getPermittedApiChannels() ) ? $oParams->m : 'index';
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	protected function preApiCheck() {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();
		$oReqParams = $this->getRequestParams();
		$oResponse = $this->getStandardResponse();

		if ( !$oMod->getIsSiteLinked() ) {
			$sErrorMessage = 'NotAssigned';
			return $this->setErrorResponse(
				$sErrorMessage,
				9999
			);
		}

		if ( empty( $oReqParams->key ) ) {
			$sErrorMessage = 'EmptyRequestKey';
			return $this->setErrorResponse(
				$sErrorMessage,
				9995
			);
		}

		if ( $oReqParams->key != $oMod->getPluginAuthKey() ) {
			$sErrorMessage = 'InvalidKey';
			return $this->setErrorResponse(
				$sErrorMessage,
				9998
			);
		}

		if ( empty( $oReqParams->pin ) ) {
			$sErrorMessage = 'EmptyRequestPin';
			return $this->setErrorResponse(
				$sErrorMessage,
				9994
			);
		}
		$sPin = $oMod->getPluginPin();
		if ( md5( $oReqParams->pin ) != $sPin ) {
			$sErrorMessage = 'InvalidPin';
			return $this->setErrorResponse(
				$sErrorMessage,
				9997
			);
		}

		return $oResponse;
	}

	/**
	 * Attempts to relink/reassign a site upon API failure, with certain pre-conditions
	 *
	 * 1) The channel is "retrieve"
	 * 2) The site CAN Handshake (it will check this)
	 * 3) The handshake is verified for this package
	 *
	 * @return LegacyApi\ApiResponse
	 */
	protected function attemptSiteReassign() {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();
		$oReqParams = $this->getRequestParams();

		if ( empty( $oReqParams->m ) || !in_array( $oReqParams->m, [ 'auth', 'internal', 'retrieve' ] ) ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'Site action method is neither "retrieve" nor "internal".' ),
				9806
			);
		}

		// We first verify fully if we CAN handshake
		if ( !$oMod->getCanHandshake( true ) ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'Site cannot handshake' ),
				9801
			);
		}

		$this->handshake();
		$oResponse = $this->getStandardResponse();

		if ( !$oResponse->success ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'Handshake verify failed' ),
				9802
			);
		}

		if ( empty( $oReqParams->accname ) || !is_email( $oReqParams->accname ) ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'Request account empty or invalid' ),
				9803
			);
		}

		if ( empty( $oReqParams->key ) || strlen( $oReqParams->key ) != 24 ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'Auth Key not of the correct format' ),
				9804
			);
		}

		if ( empty( $oReqParams->pin ) ) {
			return $this->setErrorResponse(
				sprintf( 'Attempting Site Reassign Failed: %s.', 'PIN empty' ),
				9805
			);
		}

		$oMod->setOpt( 'key', $oReqParams->key );
		$oMod->setAssignedAccount( $oReqParams->accname );
		$oMod->setPluginPin( $oReqParams->pin );
		$oMod->savePluginOptions();

		return $this->setSuccessResponse(
			'Attempting Site Reassign Succeeded.',
			9800
		);
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	protected function handshake() {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();
		$oParams = $this->getRequestParams();
		$oResponse = $this->getStandardResponse();

		if ( !$oMod->getCanHandshake() ) {
			$oResponse->handshake = 'unsupported';
			return $oResponse;
		}
		$oResponse->handshake = 'failed';

		if ( empty( $oParams->verification_code ) || empty( $oParams->package_name ) || empty( $oParams->pin ) ) {
			return $this->setErrorResponse(
				'Either the Verification Code, Package Name, or PIN were empty. Could not Handshake.',
				9990
			);
		}

		$oEncryptProcessor = $this->loadEncryptProcessor();
		if ( $oEncryptProcessor->getSupportsOpenSslSign() ) {
			$sPublicKey = $oMod->getIcwpPublicKey();
			if ( !empty( $oParams->opensig ) && !empty( $sPublicKey ) ) {
				$nSslSuccess = $oEncryptProcessor->verifySslSignature(
					$oParams->verification_code, $oParams->opensig, $sPublicKey
				);
				$oResponse->openssl_verify = $nSslSuccess;
				if ( $nSslSuccess === 1 ) {
					$oResponse->handshake = 'openssl';
					return $this->setSuccessResponse(); // just to be sure we proceed thereafter
				}
			}
		}

		$sHandshakeVerifyBaseUrl = $oMod->getAppUrl( 'handshake_verify_url' );
		// We can do this because we've assumed at this point we've validated the communication with iControlWP
		$sHandshakeVerifyUrl = sprintf(
			'%s/%s/%s/%s',
			rtrim( $sHandshakeVerifyBaseUrl, '/' ),
			$oParams->verification_code,
			$oParams->package_name,
			$oParams->pin
		);

		$sResponse = $this->loadFS()->getUrlContent( $sHandshakeVerifyUrl );
		if ( empty( $sResponse ) ) {
			return $this->setErrorResponse(
				sprintf( 'Package Handshaking Failed against URL "%s" with an empty response.', $sHandshakeVerifyUrl ),
				9991
			);
		}

		$oJsonResponse = json_decode( trim( $sResponse ) );
		if ( !is_object( $oJsonResponse ) || !isset( $oJsonResponse->success ) || $oJsonResponse->success !== true ) {
			return $this->setErrorResponse(
				sprintf( 'Package Handshaking Failed against URL "%s" with response: "%s".', $sHandshakeVerifyUrl, print_r( $oJsonResponse, true ) ),
				9992
			);
		}

		$oResponse->handshake = 'url';
		return $this->setSuccessResponse(); //just to be sure we proceed thereafter
	}

	protected function preActionEnvironmentSetup() {
		$this->loadWP()->doBustCache();
		@set_time_limit( $this->getRequestParams()->timeout );
	}

	/**
	 * @return bool
	 */
	protected function setWpEngineAuth() {
		if ( @getenv( 'IS_WPE' ) == '1' && class_exists( 'WpeCommon', false ) && $this->isLoggedInUser() ) {
			$oWpEngineCommon = WpeCommon::instance();
			$oWpEngineCommon->set_wpe_auth_cookie();
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	protected function setAuthorizedUser() {

		if ( !$this->isLoggedInUser() ) {
			$oWpUser = $this->loadWpUsers();
			$oReqParams = $this->getRequestParams();
			$sWpUser = $oReqParams->wpadmin_user;
			if ( empty( $sWpUser ) ) {

				if ( version_compare( $this->loadWP()->getWordpressVersion(), '3.1', '>=' ) ) {
					$aUserRecords = get_users( [
						'role'    => 'administrator',
						'number'  => 1,
						'orderby' => 'ID'
					] );
					if ( is_array( $aUserRecords ) && count( $aUserRecords ) ) {
						$oUser = $aUserRecords[ 0 ];
					}
				}
				else {
					$oUser = $oWpUser->getUserById( 1 );
				}
				$sWpUser = ( !empty( $oUser ) && is_a( $oUser, 'WP_User' ) ) ? $oUser->get( 'user_login' ) : 'admin';
			}

			if ( $oWpUser->setUserLoggedIn( $sWpUser, $oReqParams->isSilentLogin() ) ) {
				$this->setLoggedInUser( $sWpUser );
			}
		}
		return $this->isLoggedInUser();
	}

	/**
	 * Used by Execute and Retrieve
	 * @param string $installerFileToInclude
	 * @return LegacyApi\ApiResponse
	 */
	protected function runInstaller( $installerFileToInclude ) {
		$FS = $this->loadFS();

		$bIncludeSuccess = include_once( $installerFileToInclude );
		$FS->deleteFile( $installerFileToInclude );

		if ( !$bIncludeSuccess ) {
			return $this->setErrorResponse(
				'PHP failed to include the Installer file for execution.'
			);
		}

		if ( !class_exists( 'Worpit_Package_Installer', false ) ) {
			$sErrorMessage = sprintf( 'Worpit_Package_Installer class does not exist after including file: "%s".', $installerFileToInclude );
			return $this->setErrorResponse(
				$sErrorMessage,
				-1 //TODO: Set a code
			);
		}

		$installer = new Worpit_Package_Installer();
		$installerResponse = $installer->run();

		$msg = !empty( $installerResponse[ 'message' ] ) ? $installerResponse[ 'message' ] : 'No message';
		$response = $installerResponse[ 'data' ] ?? [];
		if ( isset( $installerResponse[ 'success' ] ) && $installerResponse[ 'success' ] ) {
			return $this->setSuccessResponse(
				sprintf( 'Package Execution SUCCEEDED with message: "%s".', $msg ),
				0,
				$response
			);
		}
		else {
			return $this->setErrorResponse(
				sprintf( 'Package Execution FAILED with error message: "%s"', $msg ),
				-1, //TODO: Set a code
				$response
			);
		}
	}

	/**
	 * @param string $sErrorMessage
	 * @param int    $nErrorCode
	 * @param mixed  $mErrorData
	 * @return LegacyApi\ApiResponse
	 */
	protected function setErrorResponse( $sErrorMessage = '', $nErrorCode = -1, $mErrorData = [] ) {
		return $this->getStandardResponse()
					->setFailed()
					->setErrorMessage( $sErrorMessage )
					->setCode( $nErrorCode )
					->setData( $mErrorData );
	}

	/**
	 * @param string $msg
	 * @param int    $successCode
	 * @param mixed  $data
	 * @return LegacyApi\ApiResponse
	 */
	protected function setSuccessResponse( $msg = '', $successCode = 0, $data = [] ) {
		return $this->getStandardResponse()
					->setSuccess( true )
					->setMessage( $msg )
					->setCode( $successCode )
					->setData( empty( $data ) ? [ 'success' => 1 ] : $data );
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	static public function getStandardResponse() {
		if ( is_null( self::$oActionResponse ) ) {
			self::$oActionResponse = new LegacyApi\ApiResponse();
		}
		return self::$oActionResponse;
	}

	/**
	 * @param string $sUser
	 * @return $this
	 */
	protected function setLoggedInUser( $sUser ) {
		$this->sLoggedInUser = $sUser;
		return $this;
	}

	/**
	 * @return string
	 */
	protected function getLoggedInUser() {
		$sLoggedInUser = $this->sLoggedInUser;
		if ( empty( $sLoggedInUser ) ) {
			$oWpUser = $this->loadWpUsers();
			if ( $oWpUser->isUserLoggedIn() && $oWpUser->isUserAdmin() ) {
				$sLoggedInUser = $oWpUser->getCurrentWpUser()->get( 'user_login' );
				$this->setLoggedInUser( $sLoggedInUser );
			}
		}
		return $this->sLoggedInUser;
	}

	/**
	 * @return bool
	 */
	protected function isLoggedInUser() {
		$sLoggedInUser = $this->getLoggedInUser();
		return !empty( $sLoggedInUser );
	}
}