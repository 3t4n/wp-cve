<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Common;

class ICWP_APP_Api_Internal_Plugin_Update extends ICWP_APP_Api_Internal_Base {

	use Common\AutoOrLegacyUpdater;

	/**
	 * @inheritDoc
	 */
	public function process() {
		$success = false;

		$file = $this->getActionParam( 'plugin_file' );
		$data = [
			'rollback' => false,
		];

		$WPP = $this->loadWpPlugins();
		$thePlugin = $WPP->getPlugin( $file );
		if ( !empty( $thePlugin ) ) {
			$data[ 'rollback' ] = $this->getActionParam( 'do_rollback_prep' )
								   && ( new ICWP_APP_Api_Internal_Common_Plugins() )->prepRollbackData( $file, 'plugins' );

			$wasActive = $WPP->getIsActive( $file );
			$preVersion = $thePlugin[ 'Version' ];

			$this->isMethodAuto() ? $this->processAuto( $file ) : $this->processLegacy( $file );

			$thePlugin = $WPP->getPlugin( $file );
			$success = !empty( $thePlugin ) && $preVersion !== $thePlugin[ 'Version' ];

			if ( $success && $wasActive && !$WPP->getIsActive( $file ) ) {
				activate_plugin( $file );
			}
		}

		return $success ? $this->success( $data ) : $this->fail( 'Update failed', -1, $data );
	}

	/**
	 * @param string $mAsset
	 */
	protected function processAuto( $mAsset ) {
		( new Common\RunAutoupdates() )->plugin( $mAsset );
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