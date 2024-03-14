<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Theme;

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

		$WPT = $this->loadWpFunctionsThemes();
		$theme = $WPT->getTheme( $file );
		if ( !empty( $theme ) ) {
			$data[ 'rollback' ] = $this->getActionParam( 'do_rollback_prep' )
								  && $this->prepRollbackData( $file, 'themes' );

			$preVersion = $theme->get( 'Version' );

			$this->isMethodAuto() ? $this->processAuto( $file ) : $this->processLegacy( $file );

			$theme = $WPT->getTheme( $file );
			$success = !empty( $theme ) && $preVersion !== $theme->get( 'Version' );
		}

		return $success ? $this->success( $data ) : $this->fail( 'Update failed', -1, $data );
	}

	/**
	 * @param string $mAsset
	 */
	protected function processAuto( $mAsset ) {
		( new LegacyApi\Internal\Common\RunAutoupdates() )->theme( $mAsset );
	}

	/**
	 * @param string $mAsset
	 * @return mixed[]
	 */
	protected function processLegacy( $mAsset ) {
		return $this->loadWpFunctionsThemes()->update( $mAsset );
	}
}