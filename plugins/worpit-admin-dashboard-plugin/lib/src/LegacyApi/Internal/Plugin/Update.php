<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Update extends Base {

	use LegacyApi\Internal\Common\AutoOrLegacyUpdater;
	use LegacyApi\Internal\Common\Rollback;

	public function process() :LegacyApi\ApiResponse {
		$success = false;

		$file = $this->getFile();
		$data = [
			'rollback' => false,
		];

		$WPP = $this->loadWpPlugins();
		$plugin = $WPP->getPlugin( $file );
		if ( !empty( $plugin ) ) {
			$data[ 'rollback' ] = $this->getActionParam( 'do_rollback_prep' )
								  && $this->prepRollbackData( $file, 'plugins' );

			$wasActive = $WPP->getIsActive( $file );
			$wasNetworkActive = $this->loadWP()->isMultisite() && is_plugin_active_for_network( $file );
			$preVersion = $plugin[ 'Version' ];

			$this->isMethodAuto() ? $this->processAuto( $file ) : $this->processLegacy( $file );

			$plugin = $WPP->getPlugin( $file );
			$success = !empty( $plugin ) && $preVersion !== $plugin[ 'Version' ];

			if ( $success && $wasActive && !$WPP->getIsActive( $file ) ) {
				activate_plugin( $file, '', $wasNetworkActive );
			}
		}

		return $success ? $this->success( $data ) : $this->fail( 'Update failed', -1, $data );
	}

	/**
	 * @param string $mAsset
	 */
	protected function processAuto( $mAsset ) {
		( new LegacyApi\Internal\Common\RunAutoupdates() )->plugin( $mAsset );
	}

	/**
	 * @param string $mAsset
	 * @return mixed[]
	 */
	protected function processLegacy( $mAsset ) {

		// handles manual Third Party Update Checking.
//			$oWpUpdatesHandler->prepThirdPartyPlugins();

		// For some reason, certain updates don't appear and we may have to force an update check to ensure WordPress
		// knows about the update.
		$oAvailableUpdates = $this->loadWP()->updatesGather( 'plugins' );
		if ( empty( $oAvailableUpdates ) || empty( $oAvailableUpdates->response[ $mAsset ] ) ) {
			$this->loadWP()->updatesCheck( 'plugins', true );
		}

		return $this->loadWpPlugins()->update( $mAsset );
	}
}