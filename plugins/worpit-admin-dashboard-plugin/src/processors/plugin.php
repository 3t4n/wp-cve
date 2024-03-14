<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class ICWP_APP_Processor_Plugin extends ICWP_APP_Processor_BaseApp {

	public function run() {
		/** @var \ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();
		$params = $this->getRequestParams();

		add_filter( $mod->doPluginPrefix( 'get_service_ips_v4' ), [ $this, 'getServiceIpAddressesV4' ] );
		add_filter( $mod->doPluginPrefix( 'get_service_ips_v6' ), [ $this, 'getServiceIpAddressesV6' ] );

		add_filter( $mod->doPluginPrefix( 'verify_site_can_handshake' ), [ $this, 'doVerifyCanHandshake' ] );
		add_filter( $mod->doPluginPrefix( 'hide_plugin' ), [ $mod, 'getIfHidePlugin' ] );
		add_filter( $mod->doPluginPrefix( 'filter_hidePluginMenu' ), [ $mod, 'getIfHidePlugin' ] );

		if ( $this->loadDP()->FetchRequest( 'geticwppluginurl', false ) == 1 ) {
			add_action( 'init', [ $this, 'getPluginUrl' ], -1000 );
		}

		$apiHook = $params->getApiHook();
		if ( $params->worpit_link ) {
			if ( $apiHook == 'immediate' ) {
				$this->doApiLinkSite();
			}
			else {
				add_action( $apiHook, [ $this, 'doApiLinkSite' ], $params->getApiHookPriority() );
			}
		}
		elseif ( $params->worpit_api || $params->icwpapi ) {
			if ( $apiHook == 'immediate' ) {
				$this->doApiAction();
			}
			else {
				add_action( $apiHook, [ $this, 'doApiAction' ], $params->getApiHookPriority() );
			}
		}
	}

	public function getPluginUrl() {
		if ( $this->loadDP()->FetchRequest( 'geticwppluginurl', false ) == 1 ) {
			$this->returnIcwpPluginUrl();
		}
	}

	/**
	 * @return array
	 */
	public function getServiceIpAddressesV4() {
		return $this->getValidServiceIps( 'ipv4' );
	}

	/**
	 * @return array
	 */
	public function getServiceIpAddressesV6() {
		return $this->getValidServiceIps( 'ipv6' );
	}

	/**
	 * @param string $sIps
	 * @return array
	 */
	protected function getValidServiceIps( $sIps = 'ipv4' ) {
		$aLists = $this->getFeatureOptions()->getDefinition( 'service_ip_addresses' );
		if ( isset( $aLists[ $sIps ] ) && is_array( $aLists[ $sIps ] ) && isset( $aLists[ $sIps ][ 'valid' ] ) && is_array( $aLists[ $sIps ][ 'valid' ] ) ) {
			return $aLists[ $sIps ][ 'valid' ];
		}
		return [];
	}

	/**
	 * @param boolean $bCanHandshake
	 * @return boolean
	 */
	public function doVerifyCanHandshake( $bCanHandshake ) {
		/** @var \ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();

		$mod->setOpt( 'time_last_check_can_handshake', $this->loadDP()->time() );

		// First simply check SSL support
		if ( $this->loadEncryptProcessor()->getSupportsOpenSslSign() ) {
			return true;
		}

		$nTimeout = 20;
		$response = $this->loadFS()->getUrlContent( $mod->getAppUrl( 'handshake_verify_test_url' ), [
			'timeout'     => $nTimeout,
			'redirection' => $nTimeout,
			'sslverify'   => true //this is default, but just to make sure.
		] );

		if ( !$response ) {
			return false;
		}

		$jsonResponse = \json_decode( \trim( $response ) );
		return \is_object( $jsonResponse )
			   && isset( $jsonResponse->success )
			   && $jsonResponse->success === true;
	}

	/**
	 * @uses die()
	 */
	public function doApiLinkSite() {
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		$this->sendApiResponse( ( new \ICWP_APP_Processor_Plugin_SiteLink( $this->getFeatureOptions() ) )->run() );
		die();
	}

	/**
	 * If any of the conditions are met and our plugin executes either the transport or link
	 * handlers, then all execution will end
	 * @return void
	 * @uses die
	 */
	public function doApiAction() {
		/** @var \ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();
		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );

		$apiChannel = $this->getApiChannel(); // also verifies it's a valid channel

		switch ( $apiChannel ) {
			case 'auth':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Auth( $mod );
				break;
			case 'retrieve':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Retrieve( $mod );
				break;
			case 'execute':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Execute( $mod );
				break;
			case 'internal':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Internal( $mod );
				break;
			case 'status':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Status( $mod );
				break;
			case 'login':
				$processor = new \ICWP_APP_Processor_Plugin_Api_Login( $mod );
				break;
			case 'download':
				$processor = new LegacyApi\Channel\Download( $mod );
				break;
			default: // case 'index':
				echo $apiChannel;
				$processor = new ICWP_APP_Processor_Plugin_Api_Index( $mod );
				break;
		}

		$this->sendApiResponse(
			$processor->run(),
			true,
			$this->getRequestParams()->icwpenc
		);
		die();
	}

	/**
	 * @return string
	 */
	protected function getApiChannel() {
		/** @var \ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();
		$params = $this->getRequestParams();
		return in_array( $params->m, $mod->getPermittedApiChannels() ) ? $params->m : 'index';
	}

	/**
	 * @return void
	 */
	protected function returnIcwpPluginUrl() {
		$this->flushResponse( $this->getController()->getPluginUrl(), false, false );
	}

	/**
	 * @param LegacyApi\ApiResponse $oResponse
	 * @param bool                  $bDoBinaryEncode
	 * @param bool                  $bEncrypt
	 * @uses die() / wp_die()
	 */
	protected function sendApiResponse( $oResponse, $bDoBinaryEncode = true, $bEncrypt = false ) {

		if ( $oResponse->die ) {
			wp_die( $oResponse->error_message );
		}

		/** @var \ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();

		$oResponse->authenticated = $this->loadWpUsers()->isUserLoggedIn();

		$oToSend = clone $oResponse;

		if ( $bEncrypt && !empty( $oToSend->data ) ) {
			$oEncryptedResult = $this->loadEncryptProcessor()->sealData(
				$oToSend->data,
				$oMod->getIcwpPublicKey()
			);

			if ( $oEncryptedResult->success ) {
				$oToSend->data = [
					'is_encrypted' => 1,
					'password'     => $oEncryptedResult->encrypted_password,
					'sealed_data'  => $oEncryptedResult->encrypted_data
				];
			}
		}

		$oBody = $oToSend->getResponsePackage();
		if ( $bDoBinaryEncode ) {
			$oBody = base64_encode( $this->loadDP()->encodeJson( $oBody ) );
		}
		$this->flushResponse( $oBody, $bDoBinaryEncode ? 'json' : 'none', $bDoBinaryEncode );
	}

	/**
	 * @param string $sContent
	 * @param string $sEncoding
	 * @param bool   $bBinary
	 */
	private function flushResponse( $sContent, $sEncoding = 'json', $bBinary = true ) {
		/** @var ICWP_APP_FeatureHandler_Plugin $oMod */
		$oMod = $this->getFeatureOptions();

		$this->sendHeaders( $bBinary );
		echo sprintf( "<icwp>%s</icwp>", $sContent );
		echo sprintf( "<icwpencoding>%s</icwpencoding>", $sEncoding );
		echo sprintf( "<icwpversion>%s</icwpversion>", $oMod->getVersion() );
		if ( !$oMod->getIsSiteLinked() && $this->loadEncryptProcessor()->getSupportsOpenSslSign() ) {
			/**
			 * displaying the key here is irrelevant because its use is essentially completely
			 * redundant for sites that support OpenSSL signatures.
			 */
			echo sprintf( "<icwpauth>%s</icwpauth>", $oMod->getPluginAuthKey() );
		}
		die();
	}

	/**
	 * @param bool $bAsBinary
	 */
	private function sendHeaders( $bAsBinary = true ) {
		if ( $bAsBinary ) {
			header( "Content-type: application/octet-stream" );
			header( "Content-Transfer-Encoding: binary" );
		}
		else {
			header( "Content-type: text/html" );
			header( "Content-Transfer-Encoding: quoted-printable" );
		}
	}
}