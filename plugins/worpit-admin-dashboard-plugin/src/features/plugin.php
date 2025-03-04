<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class ICWP_APP_FeatureHandler_Plugin extends ICWP_APP_FeatureHandler_Base {

	/**
	 * @var LegacyApi\RequestParameters
	 */
	protected $oRequestParams;

	protected function doPostConstruction() {
		if ( is_admin() ) {
			add_action( 'wp_loaded', [ $this, 'doAutoRemoteSiteAdd' ] );
		}
		add_filter( 'plugin_action_links_'.$this->getController()->getPluginBaseFile(), [
			$this,
			'onWpPluginActionLinks'
		], 100, 1 );
	}

	/**
	 */
	public function displayFeatureConfigPage() {
		$this->display(
			[
				'aPluginLabels' => $this->getController()->getPluginLabels(),
				'sAuthKey'      => $this->getPluginAuthKey(),
				'sAssignedTo'   => $this->getAssignedTo(),
				'bAssigned'     => $this->getAssigned(),
				'bIsLinked'     => $this->getIsSiteLinked(),
				'bCanHandshake' => $this->getCanHandshake(),
				'sExtraContent' => apply_filters( $this->getController()->doPluginPrefix( 'main_extracontent' ), '' ),
			],
			'feature-plugin'
		);
	}

	/**
	 * @param array $aActions
	 * @return array
	 */
	public function onWpPluginActionLinks( $aActions ) {
		if ( $this->getIsSiteLinked() && isset( $aActions[ 'deactivate' ] ) ) {
			$sJsConfirmCode = '" onClick="return confirm(\'WARNING: If you have WorpDrive automatic backups active on this site, backups will also stop running. Are you absolutely sure?\');" >';
			$aActions[ 'deactivate' ] = preg_replace( '#"\s*>#i', $sJsConfirmCode, $aActions[ 'deactivate' ], 1 );
		}
		return $aActions;
	}

	/**
	 */
	public function doClearAdminFeedback() {
		$this->setOpt( 'feedback_admin_notice', [] );
	}

	/**
	 * @param string $sMessage
	 */
	public function doAddAdminFeedback( $sMessage ) {
		$aFeedback = $this->getOpt( 'feedback_admin_notice', [] );
		$aFeedback[] = $sMessage;
		$this->setOpt( 'feedback_admin_notice', $aFeedback );
	}

	/**
	 * @param bool $bDoHidePlugin
	 * @return bool
	 */
	public function getIfHidePlugin( $bDoHidePlugin ) {
		return $this->getIsSiteLinked() && $this->getOptIs( 'enable_hide_plugin', 'Y' );
	}

	/**
	 * @param bool $doVerify
	 * @return bool
	 */
	public function getCanHandshake( $doVerify = false ) {

		if ( !$doVerify ) { // we always verify can handshake at least once every 24hrs
			$sinceLastHandshakeCheck = $this->loadDP()
											 ->time() - $this->getOpt( 'time_last_check_can_handshake', 0 );
			if ( $sinceLastHandshakeCheck > DAY_IN_SECONDS ) {
				$doVerify = true;
			}
		}

		if ( $doVerify ) {
			$canHandshake = apply_filters( $this->getController()
												 ->doPluginPrefix( 'verify_site_can_handshake' ), false );
			$this->setOpt( 'can_handshake', ( $canHandshake ? 'Y' : 'N' ) );
		}
		return $this->getOptIs( 'can_handshake', 'Y' );
	}

	/**
	 * @return bool
	 */
	public function setCanHandshake() {
		return $this->getCanHandshake( true );
	}

	/***
	 * @return bool
	 */
	public function getIsSiteLinked() {
		return $this->getAssigned() && is_email( $this->getAssignedTo() );
	}

	public function doExtraSubmitProcessing() {
		$oDp = $this->loadDP();

		if ( $oDp->FetchPost( $this->getController()->doPluginOptionPrefix( 'reset_plugin' ) ) ) {
			$sTo = $this->getAssignedTo();
			$sKey = $this->getPluginAuthKey();
			$sPin = $this->getPluginPin();

			if ( !empty( $sTo ) && !empty( $sKey ) && !empty( $sPin ) ) {
				$aParts = [ urlencode( $sTo ), $sKey, $sPin ];
				$this->loadFS()->getUrl( $this->getAppUrl( 'reset_site_url' ).implode( '/', $aParts ) );
			}
			$this->setOpt( 'key', '' );
			$this->setPluginPin( '' );
			$this->setAssignedAccount( '' );
			return;
		}

		//Clicked the button to remotely add site$this->getController()->doPluginOptionPrefix( 'reset_plugin' )
		if ( $oDp->FetchPost( $this->getController()->doPluginOptionPrefix( 'remotely_add_site_submit' ) ) ) {
			$sAuthKey = $oDp->FetchPost( 'account_auth_key' );
			$sEmailAddress = $oDp->FetchPost( 'account_email_address' );
			if ( $sAuthKey && $sEmailAddress ) {

				$sAuthKey = trim( $sAuthKey );
				$sEmailAddress = trim( $sEmailAddress );

				$oResponse = $this->doRemoteAddSiteLink( $sAuthKey, $sEmailAddress );
				if ( $oResponse ) {
					$this->doAddAdminFeedback( sprintf( ( '%s Plugin options updated successfully.' ), $this->getController()
																											->getHumanName() ) );
				}
			}
			$this->doAddAdminFeedback( sprintf( ( '%s Site NOT added.' ), $this->getController()->getHumanName() ) );
			return;
		}
		$this->doAddAdminFeedback( sprintf( ( '%s Plugin options updated successfully.' ), $this->getController()
																								->getHumanName() ) );
	}

	/**
	 * This function always returns false, however the return is never actually used just yet.
	 *
	 * @param string $sAuthKey
	 * @param string $sEmailAddress
	 * @return boolean
	 */
	protected function doRemoteAddSiteLink( $sAuthKey, $sEmailAddress ) {
		if ( $this->getIsSiteLinked() ) {
			return false;
		}

		if ( strlen( $sAuthKey ) == 32 && is_email( $sEmailAddress ) ) {

			//looks good. Now attempt remote link.
			$aPostVars = [
				'wordpress_url'         => home_url(),
				'plugin_url'            => $this->getController()->getPluginUrl(),
				'account_email_address' => $sEmailAddress,
				'account_auth_key'      => $sAuthKey,
				'plugin_key'            => $this->getPluginAuthKey()
			];
			$aArgs = [
				'body' => $aPostVars
			];
			return $this->loadFS()->postUrl( $this->getAppUrl( 'remote_add_site_url' ), $aArgs );
		}
		return false;
	}

	/**
	 * reads the auto_add.php file (yaml) to an api key and email and automatically adds the site to the account.
	 */
	public function doAutoRemoteSiteAdd() {
		$sAutoAddFilePath = $this->getController()->getRootDir().'auto_add.php';
		if ( $this->getIsSiteLinked() || !$this->loadFS()->isFile( $sAutoAddFilePath ) ) {
			return;
		}
		$sContent = $this->loadDP()
						 ->readFileContentsUsingInclude( $sAutoAddFilePath );
		$this->loadFS()->deleteFile( $sAutoAddFilePath );
		if ( !empty( $sContent ) ) {
			$aParsed = json_decode( $sContent, true );
			$sApiKey = isset( $aParsed[ 'api-key' ] ) ? $aParsed[ 'api-key' ] : '';
			$sEmail = isset( $aParsed[ 'email' ] ) ? $aParsed[ 'email' ] : '';
			$this->doRemoteAddSiteLink( $sApiKey, $sEmail );
		}
	}

	/**
	 * @return array
	 */
	public function getActivePluginFeatures() {
		$aActiveFeatures = $this->getOptionsVo()->getRawData_SingleOption( 'active_plugin_features' );
		$aPluginFeatures = [];
		if ( empty( $aActiveFeatures[ 'value' ] ) || !is_array( $aActiveFeatures[ 'value' ] ) ) {
			return $aPluginFeatures;
		}

		foreach ( $aActiveFeatures[ 'value' ] as $nPosition => $aFeature ) {
			if ( isset( $aFeature[ 'hidden' ] ) && $aFeature[ 'hidden' ] ) {
				continue;
			}
			$aPluginFeatures[ $aFeature[ 'slug' ] ] = $aFeature;
		}
		return $aPluginFeatures;
	}

	/**
	 * @return bool
	 */
	public function getAssigned() {
		return $this->getOptIs( 'assigned', 'Y' );
	}

	/**
	 * @return string (email)
	 */
	public function getAssignedTo() {
		return $this->getOpt( 'assigned_to', '' );
	}

	/**
	 * @return string (URL)
	 */
	public function getHelpdeskSsoUrl() {
		return $this->getOpt( 'helpdesk_sso_url', '' );
	}

	/**
	 * @return string
	 */
	public function getIcwpPublicKey() {
		$sKey = $this->getDefinition( 'icwp_public_key' );
		return empty( $sKey ) ? '' : base64_decode( $sKey );
	}

	/**
	 * @return string
	 */
	public function getPluginAuthKey() {
		$sOptionKey = 'key';
		$sAuthKey = $this->getOpt( $sOptionKey );
		if ( empty( $sAuthKey ) ) {
			$sAuthKey = $this->loadDP()->GenerateRandomString( 24, 7 );
			$this->setOpt( $sOptionKey, $sAuthKey );
		}
		return $sAuthKey;
	}

	/**
	 * @return string
	 */
	public function getPluginPin() {
		return $this->getOpt( 'pin' );
	}

	/**
	 * @return array
	 */
	public function getPermittedApiChannels() {
		return $this->getDefinition( 'permitted_api_channels' );
	}

	/**
	 * @return array
	 */
	public function getSupportedInternalApiAction() {
		return $this->getDefinition( 'internal_api_supported_actions' );
	}

	/**
	 * @return array
	 */
	public function getSupportedModules() {
		return $this->getDefinition( 'supported_modules' );
	}

	/**
	 * No checking or validation done for email.  If it's empty, the site is unassigned.
	 *
	 * @param $sAccountEmail
	 */
	public function setAssignedAccount( $sAccountEmail = null ) {
		if ( !empty( $sAccountEmail ) && is_email( $sAccountEmail ) ) {
			$this->setOpt( 'assigned', 'Y' );
			$this->setOpt( 'assigned_to', $sAccountEmail );
		}
		else {
			$this->setOpt( 'assigned', 'N' );
			$this->setOpt( 'assigned_to', '' );
		}
	}

	/**
	 * @param string $sEmail
	 * @return $this
	 */
	public function setAssignedTo( $sEmail ) {
		$this->setOpt( 'assigned_to', $sEmail );
		return $this;
	}

	/**
	 * @param string $sUrl
	 * @return $this
	 */
	public function setHelpdeskSsoUrl( $sUrl ) {
		$this->setOpt( 'helpdesk_sso_url', $sUrl );
		return $this;
	}

	/**
	 * The PIN should be passed here without any pre-processing (such as MD5)
	 *
	 * @param $rawPin
	 * @return $this
	 */
	public function setPluginPin( $rawPin ) {
		$trimmed = \trim( (string)$rawPin );
		$this->setOpt( 'pin', empty( $trimmed ) ? '' : \md5( $trimmed ) );
		return $this;
	}

	public function getRequestParams() :LegacyApi\RequestParameters {
		if ( !isset( $this->oRequestParams ) ) {
			$DP = $this->loadDP();
			$this->oRequestParams = new LegacyApi\RequestParameters(
				$DP->FetchGet( 'reqpars', [] ),
				$DP->FetchPost( 'reqpars', [] )
			);
		}
		return $this->oRequestParams;
	}

	/**
	 * @param array $aSummaryData
	 * @return array
	 */
	public function filter_getFeatureSummaryData( $aSummaryData ) {
		return $aSummaryData;
	}

	/**
	 * This is the point where you would want to do any options verification
	 */
	protected function doPrePluginOptionsSave() {
		$DP = $this->loadDP();

		if ( $this->getOpt( 'activated_at', 0 ) <= 0 ) {
			$this->setOpt( 'activated_at', $DP->time() );
		}
		if ( $this->getOpt( 'installation_time', 0 ) <= 0 ) {
			$this->setOpt( 'installation_time', $DP->time() );
		}

		$this->setOpt( 'installed_version', $this->getController()->getVersion() );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getAppUrl( $key ) {
		$urls = $this->getDefinition( 'urls' );
		return empty( $urls[ $key ] ) ? '' : $urls[ $key ];
	}
}