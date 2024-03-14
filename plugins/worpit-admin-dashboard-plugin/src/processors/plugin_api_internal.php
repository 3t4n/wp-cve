<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class ICWP_APP_Processor_Plugin_Api_Internal extends \ICWP_APP_Processor_Plugin_Api {

	/**
	 * @return LegacyApi\ApiResponse|mixed
	 */
	protected function processAction() {
		$action = $this->getRequestParams()->action;
		if ( empty( $action ) || !$this->isActionSupported( $action ) ) {
			return $this->setErrorResponse( sprintf( 'Action "%s" is not currently supported.', $action ) );
		}
		return $this->processActionHandler();
	}

	/**
	 * @throws \Exception
	 */
	protected function findLegacyApiClass() :string {
		$action = $this->getRequestParams()->action;
		$parts = \array_map( '\ucfirst', \explode( '_', $action ) );
		if ( \count( $parts ) < 2 ) {
			throw new Exception( sprintf( 'Unsupported Action Name: %s', $action ) );
		}

		$class = '\\FernleafSystems\\Wordpress\\Plugin\\iControlWP\\LegacyApi\\Internal\\'.implode( '\\', $parts );

		if ( !@\class_exists( $class ) ) {
			throw new Exception( sprintf( 'Class Not Found: %s', $class ) );
		}
		return $class;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	protected function findOldApi() :string {
		$action = $this->getRequestParams()->action;
		$parts = \explode( '_', $action );

		$sBase = dirname( dirname( __FILE__ ) ).DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'internal'.DIRECTORY_SEPARATOR;
		$sFullPath = $sBase.$parts[ 0 ].DIRECTORY_SEPARATOR.$parts[ 1 ].'.php';
		@require_once( $sFullPath );

		/** @var \ICWP_APP_Api_Internal_Base $oApi */
		$sClassName = 'ICWP_APP_Api_Internal_'.ucfirst( $parts[ 0 ] ).'_'.ucfirst( $parts[ 1 ] );
		if ( !@class_exists( $sClassName, false ) ) {
			throw new Exception( sprintf( 'Class %s does not exist.', $sClassName ) );
		}
		return $sClassName;
	}

	/**
	 * @return LegacyApi\ApiResponse
	 */
	protected function processActionHandler() {
		try {
			$className = $this->findLegacyApiClass();
		}
		catch ( \Exception $e ) {
			try {
				$className = $this->findOldApi();
			}
			catch ( Exception $e ) {
				return $this->setErrorResponse( $e->getMessage() );
			}
		}

		/** @var \ICWP_APP_Api_Internal_Base|\FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Base $API */
		$API = new $className();
		$API->setCon( $this->getController() )
			->setRequestParams( $this->getRequestParams() )
			->setStandardResponse( $this->getStandardResponse() )
			->preProcess();

		return \call_user_func( [ $API, 'process' ] );
	}

	protected function isActionSupported( string $action ) :bool {
		/** @var \ICWP_APP_FeatureHandler_Plugin $mod */
		$mod = $this->getFeatureOptions();
		return \in_array( $action, $mod->getSupportedInternalApiAction() );
	}
}