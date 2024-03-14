<?php

abstract class ICWP_APP_Api_Internal_Collect_Base extends ICWP_APP_Api_Internal_Base {

	/**
	 * @var ICWP_APP_Api_Internal_Collect_Base[]
	 */
	private $aCollectors;

	/**
	 * @return ICWP_APP_Api_Internal_Collect_Base|ICWP_APP_Api_Internal_Collect_Capabilities
	 */
	protected function getCollector_Capabilities() {
		$key = 'capabilities';
		if ( !isset( $this->aCollectors[ $key ] ) ) {
			$oCollector = new ICWP_APP_Api_Internal_Collect_Capabilities();
			$this->aCollectors[ $key ] = $oCollector->setRequestParams( $this->getRequestParams() );
		}
		return $this->aCollectors[ $key ];
	}

	/**
	 * @return ICWP_APP_Api_Internal_Collect_Base|ICWP_APP_Api_Internal_Collect_Paths
	 */
	protected function getCollector_Paths() {
		$key = 'paths';
		if ( !isset( $this->aCollectors[ $key ] ) ) {
			$oCollector = new ICWP_APP_Api_Internal_Collect_Paths();
			$this->aCollectors[ $key ] = $oCollector->setRequestParams( $this->getRequestParams() );
		}
		return $this->aCollectors[ $key ];
	}

	/**
	 * @return ICWP_APP_Api_Internal_Collect_Base|ICWP_APP_Api_Internal_Collect_Wordpress
	 */
	protected function getCollector_WordPressInfo() {
		$key = 'wordpress-info';
		if ( !isset( $this->aCollectors[ $key ] ) ) {
			$oCollector = new ICWP_APP_Api_Internal_Collect_Wordpress();
			$this->aCollectors[ $key ] = $oCollector->setRequestParams( $this->getRequestParams() );
		}
		return $this->aCollectors[ $key ];
	}

	/**
	 * @param string $context
	 * @return mixed
	 */
	protected function getAutoUpdates( $context = 'plugins' ) {
		return ICWP_Plugin::GetAutoUpdatesSystem()->getAutoUpdates( $context );
	}
}