<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

/**
 * Class ICWP_APP_Processor_Plugin_Api_Login
 */
class ICWP_APP_Processor_Plugin_Api_Login extends ICWP_APP_Processor_Plugin_Api {

	/**
	 * Override so that we don't run the handshaking etc.
	 * @return LegacyApi\ApiResponse
	 */
	public function run() {
		$this->preActionEnvironmentSetup();
		try {
			$this->processAction();
		}
		catch ( \Exception $oE ) {
			wp_die( $oE->getMessage() );
		}
		return $this->setSuccessResponse();
	}

	/**
	 * @return LegacyApi\ApiResponse
	 * @throws \Exception
	 */
	protected function processAction() {
		$oReqParams = $this->getRequestParams();
		$WP = $this->loadWP();

		$this->getStandardResponse()->die = true;

		if ( empty( $oReqParams->token ) ) {
			throw new \Exception( 'No valid Login Token was sent.' );
		}

		$token = $WP->getTransient( LegacyApi\Internal\User\Login::LoginTokenKey );
		$WP->deleteTransient( LegacyApi\Internal\User\Login::LoginTokenKey ); // One chance per token

		if ( empty( $token ) || !\is_array( $token ) ) {
			throw new \Exception( 'Login Token is not present or is not of the correct format.' );
		}
		if ( empty( $token[ 'token' ] ) || \strlen( $token[ 'token' ] ) !== 64 ) {
			throw new \Exception( 'Login Token is not correct.' );
		}
		if ( !\hash_equals( $token[ 'token' ], $oReqParams->token ) ) {
			throw new \Exception( 'Login Token does not match.' );
		}

		$WPU = $this->loadWpUsers();

		$username = $oReqParams->getStringParam( 'username' );
		$user = $WPU->getUserByUsername( $username );
		if ( empty( $username ) || empty( $user ) ) {
			$aUserRecords = \version_compare( $WP->getWordpressVersion(), '3.1', '>=' ) ? get_users( 'role=administrator' ) : [];
			if ( empty( $aUserRecords[ 0 ] ) ) {
				throw new \Exception( 'Failed to find a valid user.' );
			}
			$user = $aUserRecords[ 0 ];
		}

		// By-passes the 2FA process on Shield
		add_filter( 'odp-shield-2fa_skip', '__return_true' );

		if ( !$WPU->setUserLoggedIn( $user->get( 'user_login' ) ) ) {
			throw new \Exception( sprintf( 'There was a problem logging you in as "%s".', $user->get( 'user_login' ) ) );
		}

		$redirectTo = $oReqParams->getStringParam( 'redirect' );
		if ( empty( $redirectTo ) ) {
			$redirectTo = $token[ 'redirect' ];
		}

		empty( $redirectTo ) ? $WP->redirectToAdmin() : $WP->doRedirect( $redirectTo );

		die();
	}
}