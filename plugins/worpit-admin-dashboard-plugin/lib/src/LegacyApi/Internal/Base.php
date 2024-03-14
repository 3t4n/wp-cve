<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;
use FernleafSystems\Wordpress\Plugin\iControlWP\Traits\PluginControllerConsumer;

abstract class Base extends \ICWP_APP_Foundation {

	use PluginControllerConsumer;

	/**
	 * @var ApiResponse
	 */
	protected $actionResponse;

	/**
	 * @var LegacyApi\RequestParameters
	 */
	protected $oRequestParams;

	public function preProcess() {
		if ( $this->isIgnoreUserAbort() ) {
			ignore_user_abort( true );
		}
		$this->initFtp();
	}

	protected function initFtp() {
		$ftpCred = $this->getRequestParams()->ftpcred;
		if ( !empty( $ftpCred ) && is_array( $ftpCred ) ) {
			$mapRequestToWpFtp = [
				'hostname'        => 'ftp_host',
				'username'        => 'ftp_user',
				'password'        => 'ftp_pass',
				'public_key'      => 'ftp_public_key',
				'private_key'     => 'ftp_private_key',
				'connection_type' => 'ftp_protocol',
			];
			foreach ( $mapRequestToWpFtp as $sWpKey => $sRequestKey ) {
				$_POST[ $sWpKey ] = $ftpCred[ $sRequestKey ] ?? '';
			}

			$useFtp = false;
			if ( !empty( $ftpCred[ 'ftp_user' ] ) ) {
				if ( !defined( 'FTP_USER' ) ) {
					$useFtp = true;
					\define( 'FTP_USER', $ftpCred[ 'ftp_user' ] );
				}
			}
			if ( !empty( $ftpCred[ 'ftp_pass' ] ) ) {
				if ( !defined( 'FTP_PASS' ) ) {
					$useFtp = true;
					\define( 'FTP_PASS', $ftpCred[ 'ftp_pass' ] );
				}
			}

			if ( !empty( $_POST[ 'public_key' ] ) && !empty( $_POST[ 'private_key' ] ) && !defined( 'FS_METHOD' ) ) {
				\define( 'FS_METHOD', 'ssh' );
			}
			elseif ( $useFtp ) {
				\define( 'FS_METHOD', 'ftpext' );
			}
		}
	}

	abstract public function process() :ApiResponse;

	/**
	 * @return ApiResponse
	 */
	public function getStandardResponse() :ApiResponse {
		if ( \is_null( $this->actionResponse ) ) {
			$this->actionResponse = new ApiResponse();
		}

		return $this->actionResponse;
	}

	/**
	 * @param ApiResponse $response
	 * @return $this
	 */
	public function setStandardResponse( $response ) {
		$this->actionResponse = $response;
		return $this;
	}

	/**
	 * @param array  $executionData
	 * @param string $msg
	 */
	protected function success( $executionData = [], $msg = '' ) :ApiResponse {
		return $this->getStandardResponse()
					->setSuccess( true )
					->setData( empty( $executionData ) ? [ 'success' => 1 ] : $executionData )
					->setMessage( sprintf( 'INTERNAL Package Execution SUCCEEDED with message: "%s".', $msg ) )
					->setCode( 0 );
	}

	/**
	 * @param string $sErrorMessage
	 * @param int    $nErrorCode
	 * @param mixed  $mErrorData
	 */
	protected function fail( $sErrorMessage = '', $nErrorCode = -1, $mErrorData = [] ) :ApiResponse {
		return $this->getStandardResponse()
					->setFailed()
					->setErrorMessage( $sErrorMessage )
					->setCode( $nErrorCode )
					->setData( $mErrorData );
	}

	/**
	 * @return array
	 */
	protected function getActionParams() :array {
		return $this->getRequestParams()->action_params;
	}

	/**
	 * @param string     $sKey
	 * @param mixed|null $mDefault
	 * @return mixed|null
	 */
	protected function getActionParam( string $sKey, $mDefault = null ) {
		$aP = $this->getActionParams();
		return $aP[ $sKey ] ?? $mDefault;
	}

	/**
	 * @return LegacyApi\RequestParameters
	 */
	public function getRequestParams() {
		return $this->oRequestParams;
	}

	/**
	 * @param LegacyApi\RequestParameters $oRequestParams
	 * @return $this
	 */
	public function setRequestParams( $oRequestParams ) {
		$this->oRequestParams = $oRequestParams;
		return $this;
	}

	/**
	 * @return \ICWP_APP_WpCollectInfo
	 */
	protected function getWpCollector() {
		return \ICWP_APP_WpCollectInfo::GetInstance();
	}

	/**
	 * @return array
	 */
	protected function collectPlugins() {
		$oCollector = new \ICWP_APP_Api_Internal_Collect_Plugins();
		return $oCollector->setRequestParams( $this->getRequestParams() )
						  ->collect();
	}

	/**
	 * @return array
	 */
	protected function collectThemes() {
		$oCollector = new \ICWP_APP_Api_Internal_Collect_Themes();
		return $oCollector->setRequestParams( $this->getRequestParams() )
						  ->collect();
	}

	/**
	 * @return bool
	 */
	protected function isForceUpdateCheck() {
		$aActionParams = $this->getActionParams();
		return isset( $aActionParams[ 'force_update_check' ] ) ? (bool)$aActionParams[ 'force_update_check' ] : true;
	}

	/**
	 * @return bool
	 */
	protected function isIgnoreUserAbort() {
		$aActionParams = $this->getActionParams();
		return isset( $aActionParams[ 'ignore_user_abort' ] ) ? (bool)$aActionParams[ 'ignore_user_abort' ] : false;
	}
}