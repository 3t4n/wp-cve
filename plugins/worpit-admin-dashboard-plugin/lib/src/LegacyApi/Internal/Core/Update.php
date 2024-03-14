<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Core;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Update extends LegacyApi\Internal\Base {

	use LegacyApi\Internal\Common\AutoOrLegacyUpdater;

	/**
	 * @inheritDoc
	 */
	public function process() :LegacyApi\ApiResponse {
		$this->loadWpUpgrades();
		$WP = $this->loadWP();
		$version = $this->getActionParam( 'version' );

		if ( !$WP->getIfCoreUpdateExists( $version ) ) {
			return $this->success( [], 'The requested version is not currently available to install.' );
		}

		$coreUpgrade = $WP->getCoreUpdateByVersion( $version );
		if ( is_wp_error( $coreUpgrade ) ) {
			return $this->fail( 'Upgrade failed with error: '.$coreUpgrade->get_error_message() );
		}

		$result = $this->isMethodAuto() ? $this->processAuto( $coreUpgrade ) : $this->processLegacy( $coreUpgrade );

		if ( $version !== $WP->getWordpressVersion( true ) ) {
			return $this->fail( 'Upgrade Failed', -1, [
				'result' => $result,
			] );
		}

		// This was added because some sites didn't upgrade the database
		$this->loadWP()->doWpUpgrade();

		return $this->success( [
			'success' => 1,
			'result'  => $result,
		] );
	}

	/**
	 * @param string|object $oCoreUpdate
	 */
	protected function processAuto( $oCoreUpdate ) {
		( new LegacyApi\Internal\Common\RunAutoupdates() )->core( $oCoreUpdate );
	}

	/**
	 * @param $oCoreUpdate
	 * @return false|string|\WP_Error
	 */
	protected function processLegacy( $oCoreUpdate ) {
		$oSkin = $this->loadWP()->getWordpressIsAtLeastVersion( '3.7' ) ?
			new \Automatic_Upgrader_Skin()
			: new \ICWP_Upgrader_Skin();
		return ( new \Core_Upgrader( $oSkin ) )->upgrade( $oCoreUpdate );
	}
}