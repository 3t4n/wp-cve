<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Autoupdate;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

abstract class Base extends LegacyApi\Internal\Base {

	public function process() :ApiResponse {
		try {
			$sys = \ICWP_Plugin::GetAutoUpdatesSystem();

			$sys->setAutoUpdate(
				$this->getItem(),
				$this->getActionParam( 'set' ),
				$this->getContext()
			);

			$sys->setIsMainFeatureEnabled(
				!empty( $sys->getAutoUpdates( 'plugins' ) ) || !empty( $sys->getAutoUpdates( 'themes' ) )
			);
		}
		catch ( \Exception $e ) {
			return $this->fail( $e->getMessage() );
		}

		return $this->success( [
			'options' => $sys->getOptionsVo()->getStoredOptions()
		] );
	}

	abstract protected function getItem() :string;

	abstract protected function getContext() :string;
}