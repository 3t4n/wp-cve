<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Shield;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;
use FernleafSystems\Wordpress\Plugin\Shield\Controller\Controller;

abstract class Base extends LegacyApi\Internal\Base {

	/**
	 * @return Controller|null
	 */
	protected function getShieldController() {
		$con = null;
		try {
			if ( \class_exists( 'ICWP_WPSF_Shield_Security' ) ) {
				$con = \ICWP_WPSF_Shield_Security::GetInstance()->getController();
			}
			else {
				global $oICWP_Wpsf;
				if ( isset( $oICWP_Wpsf ) ) {
					$con = $oICWP_Wpsf->getController();
				}
			}
		}
		catch ( \Exception $e ) {
		}
		return $con;
	}

	/**
	 * @return bool
	 */
	protected function isInstalled() {
		return !is_null( $this->getShieldController() );
	}
}